<?php
require_once 'models/Conexion.php';

$conexion = new Conexion();
$conn = $conexion->getConexion();

$query = "SELECT id, imagen_url FROM ordenes_reparacion WHERE imagen_url IS NOT NULL AND imagen_url != ''";
$result = $conn->query($query);

echo "<h2>Órdenes con imágenes:</h2>";
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " - imagen_url: " . $row['imagen_url'] . "<br>";
}

$conn->close();
?>
