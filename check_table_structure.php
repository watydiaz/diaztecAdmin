<?php
require_once 'models/Conexion.php';

try {
    $db = (new Conexion())->getConexion();
    
    echo "<h3>Estructura de la tabla ordenes_reparacion:</h3>";
    $result = $db->query("DESCRIBE ordenes_reparacion");
    
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse; padding: 5px;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Verificar específicamente si existe costo_total
        $check_column = $db->query("SHOW COLUMNS FROM ordenes_reparacion LIKE 'costo_total'");
        if ($check_column && $check_column->num_rows > 0) {
            echo "<p style='color: green;'><strong>✓ El campo 'costo_total' SÍ existe en la tabla</strong></p>";
        } else {
            echo "<p style='color: red;'><strong>✗ El campo 'costo_total' NO existe en la tabla</strong></p>";
            echo "<p>Se necesita agregar este campo a la tabla.</p>";
        }
        
    } else {
        echo "<p style='color: red;'>Error al obtener estructura: " . $db->error . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error de conexión: " . $e->getMessage() . "</p>";
}
?>
