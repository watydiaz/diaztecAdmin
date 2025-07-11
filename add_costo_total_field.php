<?php
require_once 'models/Conexion.php';

try {
    $db = (new Conexion())->getConexion();
    
    echo "<h3>Agregando campo costo_total a la tabla ordenes_reparacion</h3>";
    
    // Verificar si el campo ya existe
    $check_column = $db->query("SHOW COLUMNS FROM ordenes_reparacion LIKE 'costo_total'");
    
    if ($check_column && $check_column->num_rows > 0) {
        echo "<p style='color: orange;'>⚠️ El campo 'costo_total' ya existe en la tabla ordenes_reparacion</p>";
    } else {
        // Agregar el campo costo_total
        $alter_query = "ALTER TABLE ordenes_reparacion ADD COLUMN costo_total DECIMAL(10,2) DEFAULT 0.00 AFTER prioridad";
        
        if ($db->query($alter_query)) {
            echo "<p style='color: green;'>✅ Campo 'costo_total' agregado exitosamente a la tabla ordenes_reparacion</p>";
            
            // Opcional: Inicializar algunos valores por defecto si es necesario
            echo "<p>📝 El campo se agregó con valor por defecto 0.00</p>";
            echo "<p>💡 Puedes actualizar manualmente los costos de las órdenes existentes desde el modal de edición</p>";
            
        } else {
            echo "<p style='color: red;'>❌ Error al agregar el campo: " . $db->error . "</p>";
        }
    }
    
    // Mostrar la estructura actualizada
    echo "<h4>Estructura actual de ordenes_reparacion:</h4>";
    $result = $db->query("DESCRIBE ordenes_reparacion");
    
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse; padding: 5px; margin-top: 10px;'>";
        echo "<tr style='background-color: #f0f0f0;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            $highlight = ($row['Field'] == 'costo_total') ? 'style="background-color: #d4edda;"' : '';
            echo "<tr $highlight>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<br><a href='index.php?action=ordenes' style='color: blue;'>← Volver a órdenes</a>";
?>
