<?php

declare(strict_types=1);

$dataDir = dirname(__DIR__) . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$dbPath = $dataDir . '/opportunities.db';
$dsn = 'sqlite:' . $dbPath;

try {
    $pdo = new PDO($dsn, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Inicializar esquema si la tabla no existe
$check = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='opportunities'");
if ($check->fetch() === false) {
    $schema = file_get_contents(dirname(__DIR__) . '/sql/schema.sql');
    $pdo->exec($schema);
}

return $pdo;
