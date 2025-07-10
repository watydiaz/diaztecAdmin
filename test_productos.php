<?php
try {
    $mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
    if ($mysqli->connect_error) {
        echo 'Error de conexión: ' . $mysqli->connect_error . PHP_EOL;
        exit;
    }
    
    // Verificar si la tabla productos existe
    $result = $mysqli->query('SHOW TABLES LIKE "productos"');
    if ($result->num_rows == 0) {
        echo 'La tabla productos NO existe' . PHP_EOL;
        
        // Mostrar todas las tablas disponibles
        $result = $mysqli->query('SHOW TABLES');
        echo 'Tablas disponibles:' . PHP_EOL;
        while ($row = $result->fetch_array()) {
            echo '- ' . $row[0] . PHP_EOL;
        }
    } else {
        echo 'La tabla productos SÍ existe' . PHP_EOL;
        
        // Mostrar estructura de la tabla
        $result = $mysqli->query('DESCRIBE productos');
        echo 'Estructura de la tabla productos:' . PHP_EOL;
        while ($row = $result->fetch_assoc()) {
            echo '- ' . $row['Field'] . ' (' . $row['Type'] . ')' . PHP_EOL;
        }
    }
    
    $mysqli->close();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>
