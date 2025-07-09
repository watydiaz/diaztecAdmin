<?php
// Test para verificar el endpoint de obtener detalle de orden
$mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

echo "=== VERIFICACIÓN DE TABLAS ===\n";
$tables = ['ordenes', 'clientes', 'equipos', 'orden_pagos'];
foreach ($tables as $table) {
    $result = $mysqli->query("SHOW TABLES LIKE '$table'");
    echo "Tabla $table: " . ($result && $result->num_rows > 0 ? 'EXISTE' : 'NO EXISTE') . "\n";
}

echo "\n=== VERIFICACIÓN DE ÓRDENES ===\n";
$result = $mysqli->query('SELECT COUNT(*) as count FROM ordenes');
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total órdenes en BD: " . $row['count'] . "\n";
}

// Mostrar primeras 3 órdenes
$result = $mysqli->query('SELECT id, numero_orden, estado, cliente_id, equipo_id FROM ordenes LIMIT 3');
if ($result) {
    echo "\nPrimeras órdenes:\n";
    while ($orden = $result->fetch_assoc()) {
        echo "- ID: {$orden['id']}, Número: {$orden['numero_orden']}, Estado: {$orden['estado']}, Cliente ID: {$orden['cliente_id']}, Equipo ID: {$orden['equipo_id']}\n";
    }
}

echo "\n=== TEST ENDPOINT DETALLE ORDEN ===\n";
// Simular llamada al endpoint
$orden_id = 1; // Usar la primera orden
$url = "http://localhost/diaztecAdmin/index.php?action=obtenerDetalleOrden&orden_id=$orden_id";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json',
        'timeout' => 10
    ]
]);

$response = file_get_contents($url, false, $context);
echo "Response: $response\n";

// Verificar si es JSON válido
$data = json_decode($response, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "✓ JSON válido\n";
    if (isset($data['success'])) {
        echo "Success: " . ($data['success'] ? 'true' : 'false') . "\n";
        if (!$data['success'] && isset($data['message'])) {
            echo "Error: " . $data['message'] . "\n";
        }
    }
} else {
    echo "✗ JSON inválido: " . json_last_error_msg() . "\n";
}

$mysqli->close();
?>
