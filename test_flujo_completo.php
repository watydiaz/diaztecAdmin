<?php
// Script para probar el flujo completo de registro de pago
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST FLUJO COMPLETO DE PAGO ===\n";

// 1. Probar si getUserId funciona
echo "1. Probando getUserId...\n";
$_GET = ['action' => 'getUserId'];
ob_start();
include 'index.php';
$getUserId_output = ob_get_contents();
ob_end_clean();

echo "Output getUserId: " . $getUserId_output . "\n";

// 2. Simular sesión activa
echo "\n2. Simulando sesión activa...\n";
session_start();
$_SESSION['usuario_id'] = 1;
$_SESSION['usuario_nombre'] = 'Admin Test';
echo "Sesión establecida: usuario_id = " . $_SESSION['usuario_id'] . "\n";

// 3. Probar getUserId con sesión
echo "\n3. Probando getUserId con sesión...\n";
$_GET = ['action' => 'getUserId'];
ob_start();
include 'index.php';
$getUserId_output2 = ob_get_contents();
ob_end_clean();

echo "Output getUserId con sesión: " . $getUserId_output2 . "\n";

// 4. Probar registro de pago directamente
echo "\n4. Probando registro de pago directo...\n";
$_POST = [
    'orden_id' => '1',
    'usuario_id' => '1', 
    'fecha_pago' => '2025-07-11 10:00:00',
    'costo_total' => '100000',
    'dinero_recibido' => '50000',
    'valor_repuestos' => '0',
    'descripcion_repuestos' => 'Test con sesión',
    'metodo_pago' => 'efectivo',
    'saldo' => '50000'
];
$_GET = ['accion' => 'insertar'];

ob_start();
include 'controllers/OrdenPagoController.php';
$pago_output = ob_get_contents();
ob_end_clean();

echo "Output registro pago: " . $pago_output . "\n";

// 5. Verificar si el registro se hizo en la BD
echo "\n5. Verificando base de datos...\n";
try {
    $mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
    $result = $mysqli->query("SELECT * FROM orden_pagos ORDER BY id DESC LIMIT 1");
    if ($result) {
        $ultimo_pago = $result->fetch_assoc();
        echo "Último pago en BD:\n";
        print_r($ultimo_pago);
    }
} catch (Exception $e) {
    echo "Error BD: " . $e->getMessage() . "\n";
}
?>
