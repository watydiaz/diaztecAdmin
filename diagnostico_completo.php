<?php
// Diagnóstico completo del flujo de redirección
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DIAGNÓSTICO COMPLETO DE REDIRECCIÓN ===\n";

// 1. Verificar qué está pasando con getUserId
echo "1. Test getUserId directo...\n";
ob_start();
$_GET = ['action' => 'getUserId'];

try {
    include 'index.php';
    $output = ob_get_contents();
    echo "getUserId output: " . $output . "\n";
} catch (Exception $e) {
    echo "Error en getUserId: " . $e->getMessage() . "\n";
} finally {
    ob_end_clean();
}

// 2. Verificar todas las posibles fuentes de redirección
echo "\n2. Buscando posibles redirecciones...\n";

// Verificar si hay algún header de redirección oculto
if (headers_sent()) {
    echo "⚠️ Headers ya fueron enviados!\n";
} else {
    echo "✓ Headers no enviados aún\n";
}

// 3. Test del controlador de pagos directo
echo "\n3. Test OrdenPagoController directo...\n";

$_POST = [
    'orden_id' => '12',
    'usuario_id' => '1',
    'fecha_pago' => '2025-07-11 16:00:00',
    'costo_total' => '50000',
    'dinero_recibido' => '25000',
    'valor_repuestos' => '0',
    'descripcion_repuestos' => 'Test diagnóstico',
    'metodo_pago' => 'efectivo',
    'saldo' => '25000'
];

$_GET = ['accion' => 'insertar'];

ob_start();
try {
    include 'controllers/OrdenPagoController.php';
    $pago_output = ob_get_contents();
    echo "Pago output: " . $pago_output . "\n";
    
    $json = json_decode($pago_output, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✓ JSON válido\n";
        if ($json['success']) {
            echo "✓ Pago registrado exitosamente\n";
        } else {
            echo "✗ Error: " . $json['message'] . "\n";
        }
    } else {
        echo "✗ JSON inválido: " . json_last_error_msg() . "\n";
    }
} catch (Exception $e) {
    echo "Error en pago: " . $e->getMessage() . "\n";
} finally {
    ob_end_clean();
}

// 4. Verificar si hay algún archivo que contenga redirecciones ocultas
echo "\n4. Verificando archivos por redirecciones...\n";

$archivos_sospechosos = [
    'views/header.php',
    'views/ordenes.php',
    'assets/js/modules/ordenes.js'
];

foreach ($archivos_sospechosos as $archivo) {
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        if (strpos($contenido, 'location.href') !== false || strpos($contenido, 'window.location') !== false) {
            echo "⚠️ Posible redirección en $archivo\n";
        }
    }
}

echo "\n5. URLs de prueba:\n";
echo "- http://localhost/diaztecAdmin/index.php?action=getUserId\n";
echo "- http://localhost/diaztecAdmin/index.php?action=ordenes\n";
echo "- http://localhost/diaztecAdmin/controllers/OrdenPagoController.php?accion=insertar\n";

?>
