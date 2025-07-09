<?php
$mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

echo "=== ESTRUCTURA DE TABLA ordenes_reparacion ===\n";
$result = $mysqli->query("DESCRIBE ordenes_reparacion");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']} ({$row['Type']}) - {$row['Null']} - {$row['Key']}\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}

echo "\n=== DATOS DE EJEMPLO ===\n";
$result = $mysqli->query("SELECT * FROM ordenes_reparacion LIMIT 2");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
        echo "---\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
