<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$pdo = require __DIR__ . '/../config/database.php';

$path = trim($_GET['path'] ?? '', '/');
$path = strpos($path, '?') !== false ? strstr($path, '?', true) : $path;
$segments = $path ? explode('/', $path) : [];
$resource = $segments[0] ?? '';
$id = isset($segments[1]) && ctype_digit($segments[1]) ? (int) $segments[1] : null;

$method = $_SERVER['REQUEST_METHOD'];
$input = [];
if (in_array($method, ['POST', 'PUT']) && str_starts_with($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
} elseif (in_array($method, ['POST', 'PUT'])) {
    $input = $_POST;
}

try {
    switch ($resource) {
        case 'opportunities':
            echo json_encode(handleOpportunities($pdo, $method, $id, $input));
            break;
        case 'companies':
            echo json_encode(handleCompanies($pdo, $method, $id, $input));
            break;
        case 'contacts':
            echo json_encode(handleContacts($pdo, $method, $id, $input));
            break;
        case 'stages':
            echo json_encode(handleStages($pdo));
            break;
        case 'dashboard':
            echo json_encode(handleDashboard($pdo));
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Recurso no encontrado']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function handleOpportunities(PDO $pdo, string $method, ?int $id, array $input): array
{
    $stageFilter = $_GET['stage_id'] ?? null;
    $companyFilter = $_GET['company_id'] ?? null;
    $statusFilter = $_GET['status'] ?? null;

    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $stmt = $pdo->prepare("
                    SELECT o.*, c.name as company_name, c.industry as company_industry,
                           ct.name as contact_name, ct.email as contact_email,
                           s.name as stage_name, s.slug as stage_slug, s.color as stage_color
                    FROM opportunities o
                    LEFT JOIN companies c ON o.company_id = c.id
                    LEFT JOIN contacts ct ON o.contact_id = ct.id
                    LEFT JOIN stages s ON o.stage_id = s.id
                    WHERE o.id = ?
                ");
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if (!$row) {
                    http_response_code(404);
                    return ['error' => 'Oportunidad no encontrada'];
                }
                return $row;
            }
            $sql = "
                SELECT o.*, c.name as company_name, s.name as stage_name, s.slug as stage_slug, s.color as stage_color,
                       ct.name as contact_name
                FROM opportunities o
                LEFT JOIN companies c ON o.company_id = c.id
                LEFT JOIN contacts ct ON o.contact_id = ct.id
                LEFT JOIN stages s ON o.stage_id = s.id
                WHERE 1=1
            ";
            $params = [];
            if ($stageFilter !== null && $stageFilter !== '') {
                $sql .= " AND o.stage_id = ?";
                $params[] = $stageFilter;
            }
            if ($companyFilter !== null && $companyFilter !== '') {
                $sql .= " AND o.company_id = ?";
                $params[] = $companyFilter;
            }
            if ($statusFilter !== null && $statusFilter !== '') {
                $sql .= " AND o.status = ?";
                $params[] = $statusFilter;
            }
            $sql .= " ORDER BY o.updated_at DESC, o.created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();

        case 'POST':
            $required = ['company_id', 'title'];
            foreach ($required as $key) {
                if (empty($input[$key])) {
                    http_response_code(400);
                    return ['error' => "Campo requerido: $key"];
                }
            }
            $stmt = $pdo->prepare("
                INSERT INTO opportunities (company_id, contact_id, title, description, estimated_value, currency, stage_id, probability, expected_close_date, assigned_to, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                (int) $input['company_id'],
                !empty($input['contact_id']) ? (int) $input['contact_id'] : null,
                $input['title'],
                $input['description'] ?? null,
                isset($input['estimated_value']) ? (float) $input['estimated_value'] : null,
                $input['currency'] ?? 'EUR',
                (int) ($input['stage_id'] ?? 1),
                (int) ($input['probability'] ?? 10),
                !empty($input['expected_close_date']) ? $input['expected_close_date'] : null,
                $input['assigned_to'] ?? null,
                $input['status'] ?? 'open',
            ]);
            $newId = (int) $pdo->lastInsertId();
            return ['id' => $newId, 'message' => 'Oportunidad creada'];
        case 'PUT':
            if ($id === null) {
                http_response_code(400);
                return ['error' => 'ID requerido'];
            }
            $fields = [];
            $params = [];
            $allowed = ['company_id', 'contact_id', 'title', 'description', 'estimated_value', 'currency', 'stage_id', 'probability', 'expected_close_date', 'assigned_to', 'status'];
            foreach ($allowed as $key) {
                if (array_key_exists($key, $input)) {
                    $fields[] = "$key = ?";
                    $params[] = $key === 'company_id' || $key === 'contact_id' || $key === 'stage_id' ? (int) $input[$key] : $input[$key];
                }
            }
            if (empty($fields)) {
                http_response_code(400);
                return ['error' => 'Ningún campo para actualizar'];
            }
            $fields[] = "updated_at = CURRENT_TIMESTAMP";
            $params[] = $id;
            $stmt = $pdo->prepare("UPDATE opportunities SET " . implode(', ', $fields) . " WHERE id = ?");
            $stmt->execute($params);
            return ['message' => 'Oportunidad actualizada'];
        case 'DELETE':
            if ($id === null) {
                http_response_code(400);
                return ['error' => 'ID requerido'];
            }
            $pdo->prepare("DELETE FROM opportunities WHERE id = ?")->execute([$id]);
            return ['message' => 'Oportunidad eliminada'];
        default:
            http_response_code(405);
            return ['error' => 'Método no permitido'];
    }
}

function handleCompanies(PDO $pdo, string $method, ?int $id, array $input): array
{
    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if (!$row) {
                    http_response_code(404);
                    return ['error' => 'Empresa no encontrada'];
                }
                $stmt2 = $pdo->prepare("SELECT * FROM contacts WHERE company_id = ? ORDER BY is_primary DESC, name");
                $stmt2->execute([$id]);
                $row['contacts'] = $stmt2->fetchAll();
                return $row;
            }
            $stmt = $pdo->query("SELECT * FROM companies ORDER BY name");
            return $stmt->fetchAll();
        case 'POST':
            if (empty($input['name'])) {
                http_response_code(400);
                return ['error' => 'El nombre de la empresa es obligatorio'];
            }
            $stmt = $pdo->prepare("INSERT INTO companies (name, industry, size, website, notes) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $input['name'],
                $input['industry'] ?? null,
                $input['size'] ?? null,
                $input['website'] ?? null,
                $input['notes'] ?? null,
            ]);
            return ['id' => (int) $pdo->lastInsertId(), 'message' => 'Empresa creada'];
        case 'PUT':
            if ($id === null) {
                http_response_code(400);
                return ['error' => 'ID requerido'];
            }
            $stmt = $pdo->prepare("UPDATE companies SET name=?, industry=?, size=?, website=?, notes=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
            $stmt->execute([
                $input['name'] ?? '',
                $input['industry'] ?? null,
                $input['size'] ?? null,
                $input['website'] ?? null,
                $input['notes'] ?? null,
                $id,
            ]);
            return ['message' => 'Empresa actualizada'];
        case 'DELETE':
            if ($id === null) {
                http_response_code(400);
                return ['error' => 'ID requerido'];
            }
            $pdo->prepare("DELETE FROM companies WHERE id = ?")->execute([$id]);
            return ['message' => 'Empresa eliminada'];
        default:
            http_response_code(405);
            return ['error' => 'Método no permitido'];
    }
}

function handleContacts(PDO $pdo, string $method, ?int $id, array $input): array
{
    $companyId = $_GET['company_id'] ?? null;

    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if (!$row) {
                    http_response_code(404);
                    return ['error' => 'Contacto no encontrado'];
                }
                return $row;
            }
            if ($companyId !== null) {
                $stmt = $pdo->prepare("SELECT * FROM contacts WHERE company_id = ? ORDER BY is_primary DESC, name");
                $stmt->execute([$companyId]);
                return $stmt->fetchAll();
            }
            $stmt = $pdo->query("SELECT * FROM contacts ORDER BY name");
            return $stmt->fetchAll();
        case 'POST':
            if (empty($input['company_id']) || empty($input['name'])) {
                http_response_code(400);
                return ['error' => 'company_id y name son obligatorios'];
            }
            $stmt = $pdo->prepare("INSERT INTO contacts (company_id, name, role, email, phone, is_primary) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                (int) $input['company_id'],
                $input['name'],
                $input['role'] ?? null,
                $input['email'] ?? null,
                $input['phone'] ?? null,
                !empty($input['is_primary']) ? 1 : 0,
            ]);
            return ['id' => (int) $pdo->lastInsertId(), 'message' => 'Contacto creado'];
        case 'PUT':
            if ($id === null) {
                http_response_code(400);
                return ['error' => 'ID requerido'];
            }
            $stmt = $pdo->prepare("UPDATE contacts SET company_id=?, name=?, role=?, email=?, phone=?, is_primary=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
            $stmt->execute([
                (int) ($input['company_id'] ?? 0),
                $input['name'] ?? '',
                $input['role'] ?? null,
                $input['email'] ?? null,
                $input['phone'] ?? null,
                !empty($input['is_primary']) ? 1 : 0,
                $id,
            ]);
            return ['message' => 'Contacto actualizado'];
        case 'DELETE':
            if ($id === null) {
                http_response_code(400);
                return ['error' => 'ID requerido'];
            }
            $pdo->prepare("DELETE FROM contacts WHERE id = ?")->execute([$id]);
            return ['message' => 'Contacto eliminado'];
        default:
            http_response_code(405);
            return ['error' => 'Método no permitido'];
    }
}

function handleStages(PDO $pdo): array
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        return ['error' => 'Método no permitido'];
    }
    $stmt = $pdo->query("SELECT * FROM stages ORDER BY sort_order");
    return $stmt->fetchAll();
}

function handleDashboard(PDO $pdo): array
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        return ['error' => 'Método no permitido'];
    }
    $stmt = $pdo->query("
        SELECT s.id, s.name, s.slug, s.color, COUNT(o.id) as count, COALESCE(SUM(o.estimated_value), 0) as total_value
        FROM stages s
        LEFT JOIN opportunities o ON o.stage_id = s.id
        GROUP BY s.id ORDER BY s.sort_order
    ");
    $byStage = $stmt->fetchAll();
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM opportunities WHERE status = 'open'");
    $openCount = (int) $stmt->fetch()['total'];
    $stmt = $pdo->query("SELECT COALESCE(SUM(estimated_value), 0) as total FROM opportunities WHERE status = 'open'");
    $pipelineValue = (float) $stmt->fetch()['total'];
    return [
        'by_stage' => $byStage,
        'open_opportunities' => $openCount,
        'pipeline_value' => $pipelineValue,
    ];
}
