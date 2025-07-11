<?php
require_once 'models/Conexion.php';

$conexion = new Conexion();
$conn = $conexion->getConexion();

// Buscar órdenes que tengan rutas incorrectas (que empiecen con assets/img/)
$query = "SELECT id, imagen_url FROM ordenes_reparacion WHERE imagen_url LIKE 'assets/img/%'";
$result = $conn->query($query);

$correcciones = 0;

echo "<h2>Corrigiendo rutas de imágenes...</h2>";

while ($row = $result->fetch_assoc()) {
    $imagen_url_incorrecta = $row['imagen_url'];
    
    // Remover 'assets/img/' del inicio
    $imagen_url_correcta = str_replace('assets/img/', '', $imagen_url_incorrecta);
    
    // Actualizar en la base de datos
    $updateQuery = "UPDATE ordenes_reparacion SET imagen_url = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('si', $imagen_url_correcta, $row['id']);
    
    if ($stmt->execute()) {
        echo "✅ Orden ID " . $row['id'] . ": '" . $imagen_url_incorrecta . "' → '" . $imagen_url_correcta . "'<br>";
        $correcciones++;
    } else {
        echo "❌ Error al corregir orden ID " . $row['id'] . "<br>";
    }
}

echo "<br><strong>Total de correcciones realizadas: " . $correcciones . "</strong>";

$conn->close();
?>
