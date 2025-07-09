<?php
// Verificar que el endpoint de obtenerPagosCaja esté funcionando
$url = "http://localhost/diaztecAdmin/index.php?action=obtenerPagosCaja";

echo "=== VERIFICACIÓN FINAL DEL ENDPOINT ===\n";
echo "URL: $url\n\n";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json',
        'timeout' => 30
    ]
]);

$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ Error: No se pudo conectar al endpoint\n";
    echo "Posibles causas:\n";
    echo "- Apache no está corriendo\n";
    echo "- El archivo index.php no existe o tiene errores de sintaxis\n";
    echo "- Problema de permisos\n";
} else {
    echo "✅ Conexión exitosa\n";
    echo "Longitud de respuesta: " . strlen($response) . " caracteres\n\n";
    
    // Verificar si es JSON válido
    $data = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✅ JSON válido\n";
        if (isset($data['success']) && $data['success']) {
            echo "✅ Endpoint funcionando correctamente\n";
            echo "Pagos de órdenes: " . count($data['pagos_ordenes']) . "\n";
            echo "Pagos de productos: " . count($data['pagos_productos']) . "\n";
        } else {
            echo "❌ Error en endpoint: " . ($data['error'] ?? 'Sin mensaje específico') . "\n";
        }
    } else {
        echo "❌ Respuesta no es JSON válido\n";
        echo "Contenido:\n" . substr($response, 0, 500) . "...\n";
    }
}

echo "\n=== VERIFICACIÓN DE ARCHIVOS ===\n";
$files_to_check = [
    'c:/xampp/htdocs/diaztecAdmin/index.php',
    'c:/xampp/htdocs/diaztecAdmin/models/Conexion.php',
    'c:/xampp/htdocs/diaztecAdmin/views/caja.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ $file existe\n";
    } else {
        echo "❌ $file NO EXISTE\n";
    }
}
?>
