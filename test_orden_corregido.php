<?php
// Test para verificar el endpoint corregido
$orden_id = 8; // Usar una orden que sabemos que existe
$url = "http://localhost/diaztecAdmin/index.php?action=obtenerDetalleOrden&orden_id=$orden_id";

echo "=== TEST ENDPOINT DETALLE ORDEN CORREGIDO ===\n";
echo "Probando con orden ID: $orden_id\n";
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
            echo "Datos de la orden obtenidos correctamente\n";
            echo "Cliente: " . ($data['orden']['cliente_nombre'] ?? 'N/A') . "\n";
            echo "Equipo: " . ($data['orden']['equipo_marca'] ?? 'N/A') . " " . ($data['orden']['equipo_modelo'] ?? 'N/A') . "\n";
            echo "Falla: " . ($data['orden']['descripcion_problema'] ?? 'N/A') . "\n";
            echo "Estado: " . ($data['orden']['estado'] ?? 'N/A') . "\n";
        } else {
            echo "Error: " . ($data['message'] ?? 'Sin mensaje') . "\n";
        }
    }
} else {
    echo "✗ JSON inválido: " . json_last_error_msg() . "\n";
}
?>
