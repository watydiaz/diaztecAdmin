<?php
$mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

echo "=== TABLAS EXISTENTES EN LA BASE DE DATOS ===\n";
$result = $mysqli->query("SHOW TABLES");
if ($result) {
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "\n";
    }
} else {
    echo "Error al obtener tablas: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
