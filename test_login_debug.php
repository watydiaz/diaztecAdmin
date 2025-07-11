<?php
// Test específico para el problema de login
session_start();

echo "=== DIAGNÓSTICO DE PROBLEMA DE LOGIN ===\n";

// 1. Estado inicial
echo "1. Estado inicial de sesión:\n";
print_r($_SESSION);

// 2. Simular login
echo "\n2. Simulando login exitoso...\n";
$_SESSION['usuario_id'] = 1;
$_SESSION['usuario_nombre'] = 'Admin Test';
$_SESSION['usuario_email'] = 'admin@test.com';
$_SESSION['usuario_rol'] = 'admin';

echo "Sesión después del login:\n";
print_r($_SESSION);

// 3. Verificar que getUserId funciona
echo "\n3. Verificando getUserId...\n";
if (isset($_SESSION['usuario_id'])) {
    $response = [
        'success' => true, 
        'usuario_id' => $_SESSION['usuario_id'],
        'usuario_nombre' => $_SESSION['usuario_nombre'] ?? 'Usuario'
    ];
} else {
    $response = [
        'success' => false, 
        'message' => 'No hay sesión activa',
        'usuario_id' => null
    ];
}
echo "Respuesta getUserId: " . json_encode($response) . "\n";

// 4. Verificar que el registro de pago funcionaría
echo "\n4. Probando inserción directa en BD...\n";
try {
    $mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
    
    if ($mysqli->connect_error) {
        throw new Exception('Error de conexión: ' . $mysqli->connect_error);
    }
    
    $stmt = $mysqli->prepare("INSERT INTO orden_pagos (orden_id, usuario_id, fecha_pago, costo_total, dinero_recibido, valor_repuestos, descripcion_repuestos, metodo_pago, saldo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $orden_id = 1;
    $usuario_id = 1;
    $fecha_pago = '2025-07-11 10:00:00';
    $costo_total = 100000.00;
    $dinero_recibido = 50000.00;
    $valor_repuestos = 0.00;
    $descripcion_repuestos = 'Test directo desde script';
    $metodo_pago = 'efectivo';
    $saldo = 50000.00;
    
    $stmt->bind_param(
        "iisddsssd",
        $orden_id,
        $usuario_id,
        $fecha_pago,
        $costo_total,
        $dinero_recibido,
        $valor_repuestos,
        $descripcion_repuestos,
        $metodo_pago,
        $saldo
    );
    
    if ($stmt->execute()) {
        echo "✓ Inserción exitosa en BD. ID: " . $mysqli->insert_id . "\n";
    } else {
        echo "✗ Error en inserción: " . $stmt->error . "\n";
    }
    
    $stmt->close();
    $mysqli->close();
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n5. URL de prueba para navegador:\n";
echo "http://localhost/diaztecAdmin/index.php?action=getUserId\n";
echo "http://localhost/diaztecAdmin/index.php?action=dashboard\n";

?>
