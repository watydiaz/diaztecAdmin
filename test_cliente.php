<?php
// Archivo de prueba para verificar la creación de clientes
require_once 'controllers/ClienteController.php';

// Simular datos POST
$_POST['nombre'] = 'Cliente Prueba';
$_POST['identificacion'] = '12345678';
$_POST['telefono'] = '3001234567';
$_POST['email'] = 'prueba@test.com';
$_POST['direccion'] = 'Dirección de prueba';
$_SERVER['REQUEST_METHOD'] = 'POST';

$clienteController = new ClienteController();

// Capturar la salida
ob_start();
$clienteController->agregarClienteDesdeModal();
$output = ob_get_clean();

echo "Respuesta del servidor:\n";
echo $output;
?>
