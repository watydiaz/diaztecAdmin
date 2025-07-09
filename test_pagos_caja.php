<?php
// Test para verificar el endpoint de pagos de caja
$url = "http://localhost/diaztecAdmin/index.php?action=obtenerPagosCaja";

echo "=== TEST ENDPOINT PAGOS CAJA ===\n";
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
    if (isset($data['success'])) {
        echo "Success: " . ($data['success'] ? 'true' : 'false') . "\n";
        if ($data['success']) {
            echo "Pagos de órdenes: " . count($data['pagos_ordenes']) . "\n";
            echo "Pagos de productos: " . count($data['pagos_productos']) . "\n";
            
            if (count($data['pagos_ordenes']) > 0) {
                $primera_orden = $data['pagos_ordenes'][0];
                echo "Primera orden - Cliente: " . ($primera_orden['cliente_nombre'] ?? 'N/A') . "\n";
            }
        } else {
            echo "Error: " . ($data['error'] ?? 'Sin mensaje') . "\n";
        }
    }
} else {
    echo "✗ JSON inválido: " . json_last_error_msg() . "\n";
}
?>
