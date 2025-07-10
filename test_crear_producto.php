<?php
// Activar display de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "=== PRUEBA DE CREACIÓN DE PRODUCTO ===" . PHP_EOL;

// Simular datos POST
$_POST = [
    'nombre' => 'Producto de Prueba',
    'descripcion' => 'Descripción de prueba',
    'precio_compra' => '10.50',
    'precio_venta' => '15.00',
    'stock' => '5',
    'stock_minimo' => '2'
];

$_SERVER['REQUEST_METHOD'] = 'POST';

echo "Datos POST simulados:" . PHP_EOL;
print_r($_POST);

// Incluir archivos necesarios
try {
    echo "Incluyendo ProductoController..." . PHP_EOL;
    require_once 'controllers/ProductoController.php';
    
    echo "Creando instancia del controlador..." . PHP_EOL;
    $productoController = new ProductoController();
    
    echo "Ejecutando método crear()..." . PHP_EOL;
    
    // Capturar la salida
    ob_start();
    $productoController->crear();
    $output = ob_get_clean();
    
    echo "Salida capturada: " . PHP_EOL;
    echo $output . PHP_EOL;
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
}
?>
