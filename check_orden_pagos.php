<?php
$mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

echo "=== ESTRUCTURA DE TABLA orden_pagos ===\n";
$result = $mysqli->query("DESCRIBE orden_pagos");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']} ({$row['Type']}) - {$row['Null']} - {$row['Key']}\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}

echo "\n=== DATOS DE EJEMPLO ===\n";
$result = $mysqli->query("SELECT * FROM orden_pagos LIMIT 3");
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
