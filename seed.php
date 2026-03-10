<?php

declare(strict_types=1);

/**
 * Datos base: empresas españolas y oportunidades con ámbito SAP.
 * Ejecutar una vez: php seed.php  o  abrir en navegador seed.php
 * Con datos existentes: seed.php?force=1  vacía e inserta de nuevo.
 */

$isCli = php_sapi_name() === 'cli';
$force = ($isCli && isset($argv) && (in_array('force', $argv, true) || in_array('--force', $argv, true)))
    || (isset($_GET['force']) && $_GET['force'] === '1');

$pdo = require __DIR__ . '/config/database.php';

// Comprobar si ya hay datos
$count = (int) $pdo->query("SELECT COUNT(*) FROM companies")->fetchColumn();
if ($count > 0 && !$force) {
    if ($isCli) {
        fwrite(STDERR, "Ya hay datos. Usa: php seed.php --force  (o seed.php?force=1 en navegador) para vaciar e insertar de nuevo.\n");
    } else {
        header('Content-Type: text/html; charset=utf-8');
        echo '<p>Ya hay datos en la base. <a href="seed.php?force=1">Ejecutar de nuevo (vaciar e insertar)</a></p>';
    }
    exit($isCli ? 1 : 0);
}

if ($force && $count > 0) {
    $pdo->exec('DELETE FROM opportunities');
    $pdo->exec('DELETE FROM contacts');
    $pdo->exec('DELETE FROM companies');
}

$responsibles = ['Alfredo Pérez', 'Rafa Calvo', 'Guillermo Truhan', 'Gerard Prats', 'Xavi Tor', 'Alvaro Arbaiza'];
$industries = ['Tecnología', 'Industria y manufactura', 'Banca y finanzas', 'Retail y distribución', 'Energía y utilities', 'Salud', 'Telecomunicaciones', 'Consultoría', 'Logística y transporte', 'Seguros'];
$sizes = ['pyme', 'mediana', 'gran_empresa', 'startup'];

$companies = [
    ['Aceros del Norte S.A.', 'Industria y manufactura', 'gran_empresa', 'https://www.acerosdelnorte.es', 'Cliente industrial, múltiples plantas.'],
    ['Banca Rural Mediterránea', 'Banca y finanzas', 'mediana', 'https://www.bancaruralmed.es', 'Entidad financiera regional.'],
    ['TecnoSoft España', 'Tecnología', 'mediana', 'https://www.tecnosoft.es', 'Software y servicios IT.'],
    ['Distribuciones Martorell S.L.', 'Retail y distribución', 'pyme', 'https://www.distrimartorell.com', 'Distribución alimentaria.'],
    ['Energías Renovables del Este', 'Energía y utilities', 'mediana', 'https://www.energiaseste.es', 'Parques eólicos y solar.'],
    ['Hospital Clínico del Mediterráneo', 'Salud', 'gran_empresa', 'https://www.hcm.es', 'Grupo hospitalario.'],
    ['Comunicaciones Peninsulares', 'Telecomunicaciones', 'mediana', null, 'Operador telecomunicaciones.'],
    ['Manufacturas Vega S.A.U.', 'Industria y manufactura', 'mediana', 'https://www.manufacturasvega.es', 'Automoción y componentes.'],
    ['Seguros Atlántico', 'Seguros', 'gran_empresa', 'https://www.segurosatlantico.es', 'Compañía de seguros.'],
    ['Logística Ibérica', 'Logística y transporte', 'mediana', 'https://www.logisticaiberica.com', 'Almacenes y transporte.'],
    ['Sistemas Integrados S.L.', 'Tecnología', 'pyme', 'https://www.sisintegrados.es', 'Integrador de sistemas.'],
    ['Caja del Sur', 'Banca y finanzas', 'mediana', 'https://www.cajadelsur.es', 'Caja rural.'],
    ['Retail Plus España', 'Retail y distribución', 'gran_empresa', 'https://www.retailplus.es', 'Cadena de retail.'],
    ['Gas y Electricidad Central', 'Energía y utilities', 'gran_empresa', 'https://www.gaselectricidadcentral.es', 'Utility.'],
    ['Clínica Norte', 'Salud', 'mediana', 'https://www.clinicanorte.es', 'Clínicas privadas.'],
];

$sapTitles = [
    'Implementación SAP S/4HANA',
    'Migración SAP ECC a S/4HANA',
    'Consultoría SAP Módulo FI/CO',
    'Proyecto SAP SD/MM',
    'SAP SuccessFactors - RRHH',
    'SAP Fiori - Experiencia de usuario',
    'Transformación digital con SAP',
    'SAP BW/4HANA - Business Intelligence',
    'SAP PP - Planificación de la producción',
    'SAP PM - Mantenimiento',
    'Diseño de procesos SAP',
    'Arquitectura SAP - Roadmap',
];

$sapDescriptions = [
    'Proyecto de consultoría e implementación SAP. Alcance: diseño de procesos, parametrización y acompañamiento en la puesta en marcha.',
    'Migración a SAP S/4HANA. Análisis de impacto, plan de migración y soporte técnico.',
    'Consultoría SAP en módulos financieros y controlling. Definición de estructura y reporting.',
    'Implementación de módulos de ventas y materiales. Integración con logística.',
];

$values = [45000, 78000, 120000, 185000, 250000, 320000, 95000, 140000, 210000, 65000, 155000, 42000, 175000, 98000, 265000, 88000, 195000, 52000];

$stmtCompany = $pdo->prepare("INSERT INTO companies (name, industry, size, website, notes) VALUES (?, ?, ?, ?, ?)");
$stmtContact = $pdo->prepare("INSERT INTO contacts (company_id, name, role, email, phone, is_primary) VALUES (?, ?, ?, ?, ?, ?)");
$stmtOpp = $pdo->prepare("INSERT INTO opportunities (company_id, contact_id, title, description, estimated_value, currency, stage_id, probability, expected_close_date, assigned_to, status) VALUES (?, ?, ?, ?, ?, 'EUR', ?, ?, ?, ?, ?)");

$contactNames = [
    'Carlos Méndez', 'Laura Sánchez', 'Miguel Ángel Ruiz', 'Ana Belén Torres', 'Francisco Javier López',
    'Elena García', 'David Martínez', 'Isabel Fernández', 'José Antonio Díaz', 'María José Ramírez',
    'Pablo Serrano', 'Carmen Navarro', 'Antonio Jiménez', 'Rosa María Castro', 'Juan Carlos Mora',
];
$roles = ['Director de Operaciones', 'Director Financiero', 'Director de TI', 'Responsable de Procesos', 'CEO', 'CFO', 'CIO'];

$companyIds = [];
$contactIdsByCompany = [];

foreach ($companies as $c) {
    $stmtCompany->execute([$c[0], $c[1], $c[2], $c[3] ?? null, $c[4] ?? null]);
    $companyIds[] = (int) $pdo->lastInsertId();
}

$usedNames = [];
foreach ($companyIds as $i => $cid) {
    $n = $i % count($contactNames);
    $name = $contactNames[$n];
    $suffix = 0;
    while (isset($usedNames[$name])) { $name = $contactNames[($n + ++$suffix) % count($contactNames)] . ($suffix > 1 ? " $suffix" : ''); }
    $usedNames[$name] = true;
    $stmtContact->execute([$cid, $name, $roles[$i % count($roles)], strtolower(str_replace(' ', '.', $name)) . '@empresa' . $cid . '.es', '+34 9' . str_pad((string)(600000000 + $i * 11111), 8, '0'), 1]);
    $contactIdsByCompany[$cid] = [(int) $pdo->lastInsertId()];
    if ($i % 3 === 0) {
        $name2 = $contactNames[($i + 5) % count($contactNames)] . ' ' . $cid;
        $stmtContact->execute([$cid, $name2, $roles[($i + 2) % count($roles)], 'contacto2@empresa' . $cid . '.es', null, 0]);
        $contactIdsByCompany[$cid][] = (int) $pdo->lastInsertId();
    }
}

$today = new DateTimeImmutable();
$oppIndex = 0;
$stagePool = [];
foreach (range(1, 6) as $sid) {
    $n = $sid <= 4 ? (2 + $sid) : ($sid === 5 ? 4 : 3);
    $stagePool = array_merge($stagePool, array_fill(0, $n, $sid));
}
shuffle($stagePool);
$stagePoolIdx = 0;

foreach ($companyIds as $idx => $cid) {
    $contacts = $contactIdsByCompany[$cid];
    $numOpps = 2 + ($idx % 3);
    for ($o = 0; $o < $numOpps; $o++) {
        $titleIdx = $oppIndex % count($sapTitles);
        $descIdx = ($idx + $o) % count($sapDescriptions);
        $stageId = $stagePool[$stagePoolIdx % count($stagePool)];
        $stagePoolIdx++;
        $value = $values[($idx * 2 + $o) % count($values)];
        $probRanges = [1 => [10, 25], 2 => [20, 45], 3 => [40, 70], 4 => [60, 90], 5 => [100, 100], 6 => [0, 0]];
        [$lo, $hi] = $probRanges[$stageId] ?? [10, 50];
        $prob = $lo === $hi ? $lo : rand($lo, $hi);
        $daysOffset = ($stageId >= 5) ? -rand(30, 180) : (30 + ($idx + $o) * 14);
        $closeDate = $today->modify((string) $daysOffset . ' days')->format('Y-m-d');
        $contactId = $contacts[$o % count($contacts)];
        $assigned = $responsibles[$idx % count($responsibles)];
        $status = $stageId === 5 ? 'won' : ($stageId === 6 ? 'lost' : 'open');
        $stmtOpp->execute([
            $cid,
            $contactId,
            $sapTitles[$titleIdx],
            $sapDescriptions[$descIdx],
            $value,
            $stageId,
            $prob,
            $closeDate,
            $assigned,
            $status,
        ]);
        $oppIndex++;
    }
}

$totalCompanies = count($companyIds);
$totalOpps = (int) $pdo->query("SELECT COUNT(*) FROM opportunities")->fetchColumn();

if ($isCli) {
    echo "OK. Insertadas {$totalCompanies} empresas y {$totalOpps} oportunidades.\n";
} else {
    header('Content-Type: text/html; charset=utf-8');
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Seed</title></head><body>";
    echo "<p><strong>Datos base insertados.</strong></p>";
    echo "<p>{$totalCompanies} empresas y {$totalOpps} oportunidades (ámbito SAP, multiindustria, importes variados).</p>";
    echo "<p><a href='index.php'>Ir a la aplicación</a></p>";
    echo "</body></html>";
}
