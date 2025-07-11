<?php
// Test simple sin sesiones
echo "=== TEST SIN SESIONES ===\n";

// 1. Test getUserId
echo "1. Testing getUserId...\n";
$_GET = ['action' => 'getUserId'];
ob_start();
include 'index.php';
$output1 = ob_get_contents();
ob_end_clean();
echo "getUserId output: " . $output1 . "\n";

// 2. Test registro de pago con orden válida
echo "\n2. Testing registro de pago...\n";
$_POST = [
    'orden_id' => '12',  // Usar orden que existe
    'usuario_id' => '1', 
    'fecha_pago' => '2025-07-11 15:00:00',
    'costo_total' => '75000',
    'dinero_recibido' => '75000',
    'valor_repuestos' => '25000',
    'descripcion_repuestos' => 'Test sin sesiones',
    'metodo_pago' => 'efectivo',
    'saldo' => '0'
];
$_GET = ['accion' => 'insertar'];

ob_start();
include 'controllers/OrdenPagoController.php';
$output2 = ob_get_contents();
ob_end_clean();

echo "Registro pago output: " . $output2 . "\n";

$json = json_decode($output2, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "✓ JSON válido: " . ($json['success'] ? 'ÉXITO' : 'ERROR') . "\n";
    if (!$json['success']) {
        echo "Error: " . $json['message'] . "\n";
    }
} else {
    echo "✗ JSON inválido\n";
}
?>
