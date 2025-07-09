<?php
// Test para verificar el endpoint corregido con costo total
$orden_id = 86; // El ID de la orden que muestra el problema
$url = "http://localhost/diaztecAdmin/index.php?action=obtenerDetalleOrden&orden_id=$orden_id";

echo "=== TEST ENDPOINT DETALLE ORDEN CON COSTO CORREGIDO ===\n";
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
    if (isset($data['success']) && $data['success']) {
        echo "✓ Endpoint funcionando correctamente\n";
        echo "Cliente: " . ($data['orden']['cliente_nombre'] ?? 'N/A') . "\n";
        echo "Equipo: " . ($data['orden']['equipo_marca'] ?? 'N/A') . " " . ($data['orden']['equipo_modelo'] ?? 'N/A') . "\n";
        echo "Costo Total: $" . number_format($data['orden']['costo_total'] ?? 0, 0) . "\n";
        echo "Falla: " . ($data['orden']['descripcion_problema'] ?? 'N/A') . "\n";
        echo "Estado: " . ($data['orden']['estado'] ?? 'N/A') . "\n";
        echo "Pagos registrados: " . count($data['pagos']) . "\n";
        
        if (count($data['pagos']) > 0) {
            echo "\nDetalle de pagos:\n";
            $total_pagado = 0;
            foreach ($data['pagos'] as $pago) {
                $monto = floatval($pago['dinero_recibido']);
                $total_pagado += $monto;
                echo "- Fecha: " . $pago['fecha_pago'] . ", Monto: $" . number_format($monto, 0) . ", Método: " . $pago['metodo_pago'] . "\n";
            }
            echo "Total pagado: $" . number_format($total_pagado, 0) . "\n";
            $saldo = floatval($data['orden']['costo_total']) - $total_pagado;
            echo "Saldo pendiente: $" . number_format($saldo, 0) . "\n";
        }
    } else {
        echo "❌ Error: " . ($data['message'] ?? 'Sin mensaje específico') . "\n";
    }
} else {
    echo "❌ JSON inválido: " . json_last_error_msg() . "\n";
}
?>
