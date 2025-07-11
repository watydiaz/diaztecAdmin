<?php
// Test con orden que sí existe
session_start();
$_SESSION['usuario_id'] = 1;
$_SESSION['usuario_nombre'] = 'Admin';

echo "=== TEST CON ORDEN VÁLIDA ===\n";

// Simular datos POST con orden que SÍ existe (ID 12)
$_POST = [
    'orden_id' => '12',  // ← Cambié de 1 a 12
    'usuario_id' => '1', 
    'fecha_pago' => '2025-07-11 10:00:00',
    'costo_total' => '100000',
    'dinero_recibido' => '50000',
    'valor_repuestos' => '0',
    'descripcion_repuestos' => 'Test con orden válida ID 12',
    'metodo_pago' => 'efectivo',
    'saldo' => '50000'
];

$_GET = ['accion' => 'insertar'];

echo "Probando registro con orden ID 12...\n";

ob_start();
try {
    include 'controllers/OrdenPagoController.php';
    $output = ob_get_contents();
} catch (Exception $e) {
    $output = "ERROR: " . $e->getMessage();
} finally {
    ob_end_clean();
}

echo "Output del controlador: " . $output . "\n";

// Verificar si es JSON válido
$json_data = json_decode($output, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "✓ JSON válido: ";
    print_r($json_data);
} else {
    echo "✗ JSON inválido: " . json_last_error_msg() . "\n";
}

// Verificar en BD
echo "\nVerificando en BD...\n";
try {
    $mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
    $result = $mysqli->query("SELECT * FROM orden_pagos WHERE orden_id = 12 ORDER BY id DESC LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        echo "✓ Pago insertado correctamente:\n";
        print_r($row);
    } else {
        echo "✗ No se encontró el pago en la BD\n";
    }
} catch (Exception $e) {
    echo "Error BD: " . $e->getMessage() . "\n";
}
?>
