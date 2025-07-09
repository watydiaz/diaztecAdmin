<?php
// Test para los nuevos endpoints del dashboard
$endpoints = [
    'obtenerVentasSemana',
    'obtenerProductividad', 
    'obtenerTopProductos'
];

foreach ($endpoints as $endpoint) {
    echo "=== TEST $endpoint ===\n";
    $url = "http://localhost/diaztecAdmin/index.php?action=$endpoint";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json',
            'timeout' => 10
        ]
    ]);

    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "❌ Error: No se pudo conectar\n\n";
        continue;
    }
    
    $data = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✅ JSON válido\n";
        if (isset($data['success']) && $data['success']) {
            echo "✅ Endpoint funcionando\n";
            
            switch ($endpoint) {
                case 'obtenerVentasSemana':
                    echo "Días con ventas: " . count($data['ventas']) . "\n";
                    break;
                case 'obtenerProductividad':
                    echo "Órdenes hoy: " . ($data['ordenesHoy'] ?? 0) . "\n";
                    echo "Tiempo promedio: " . ($data['tiempoPromedio'] ?? '0d') . "\n";
                    break;
                case 'obtenerTopProductos':
                    echo "Productos encontrados: " . count($data['productos']) . "\n";
                    break;
            }
        } else {
            echo "❌ Error: " . ($data['message'] ?? 'Sin mensaje') . "\n";
        }
    } else {
        echo "❌ JSON inválido\n";
    }
    echo "\n";
}
?>
