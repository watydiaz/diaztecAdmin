<?php
// Test para verificar el endpoint de saldos pendientes
$url = "http://localhost/diaztecAdmin/index.php?action=obtenerSaldosPendientes";

echo "=== TEST ENDPOINT SALDOS PENDIENTES ===\n";
echo "URL: $url\n\n";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json',
        'timeout' => 10
    ]
]);

$response = file_get_contents($url, false, $context);
echo "Response:\n$response\n\n";

// Verificar si es JSON válido
$data = json_decode($response, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "✓ JSON válido\n";
    if (isset($data['success']) && $data['success']) {
        echo "✓ Endpoint funcionando correctamente\n";
        $saldos = floatval($data['saldosPendientes']);
        echo "Total saldos pendientes: $" . number_format($saldos, 0) . "\n";
    } else {
        echo "❌ Error: " . ($data['message'] ?? 'Sin mensaje específico') . "\n";
    }
} else {
    echo "❌ JSON inválido: " . json_last_error_msg() . "\n";
}

// También verificar directamente en la base de datos
echo "\n=== VERIFICACIÓN DIRECTA EN BD ===\n";
$mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

$query = "SELECT 
            orden_id,
            MAX(costo_total) as costo_total,
            SUM(dinero_recibido) as total_pagado,
            (MAX(costo_total) - SUM(dinero_recibido)) as saldo
          FROM orden_pagos 
          GROUP BY orden_id
          HAVING MAX(costo_total) > SUM(dinero_recibido)";

$result = $mysqli->query($query);
if ($result) {
    echo "Órdenes con saldo pendiente:\n";
    $total_saldos = 0;
    while ($row = $result->fetch_assoc()) {
        $saldo = floatval($row['saldo']);
        $total_saldos += $saldo;
        echo "- Orden {$row['orden_id']}: Costo {$row['costo_total']}, Pagado {$row['total_pagado']}, Saldo $" . number_format($saldo, 0) . "\n";
    }
    echo "TOTAL SALDOS: $" . number_format($total_saldos, 0) . "\n";
} else {
    echo "Error en consulta: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
