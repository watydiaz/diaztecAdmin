<?php
// Verificar órdenes existentes en la BD
echo "=== VERIFICACIÓN DE ÓRDENES EXISTENTES ===\n";

try {
    $mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
    
    if ($mysqli->connect_error) {
        throw new Exception('Error de conexión: ' . $mysqli->connect_error);
    }
    
    // Verificar órdenes existentes
    echo "1. Órdenes existentes:\n";
    $result = $mysqli->query("SELECT id, cliente_id, marca, modelo, estado FROM ordenes_reparacion LIMIT 10");
    
    if ($result && $result->num_rows > 0) {
        while ($orden = $result->fetch_assoc()) {
            echo "   ID: {$orden['id']}, Cliente: {$orden['cliente_id']}, Equipo: {$orden['marca']} {$orden['modelo']}, Estado: {$orden['estado']}\n";
        }
    } else {
        echo "   ¡No hay órdenes en la tabla!\n";
    }
    
    // Verificar usuarios existentes
    echo "\n2. Usuarios existentes:\n";
    $result = $mysqli->query("SELECT id, nombre, email FROM usuarios LIMIT 5");
    
    if ($result && $result->num_rows > 0) {
        while ($usuario = $result->fetch_assoc()) {
            echo "   ID: {$usuario['id']}, Nombre: {$usuario['nombre']}, Email: {$usuario['email']}\n";
        }
    } else {
        echo "   ¡No hay usuarios en la tabla!\n";
    }
    
    // Verificar estructura de la tabla orden_pagos
    echo "\n3. Estructura de tabla orden_pagos:\n";
    $result = $mysqli->query("DESCRIBE orden_pagos");
    if ($result) {
        while ($field = $result->fetch_assoc()) {
            echo "   {$field['Field']}: {$field['Type']} {$field['Null']} {$field['Key']} {$field['Extra']}\n";
        }
    }
    
    // Verificar restricciones de clave foránea
    echo "\n4. Restricciones de clave foránea:\n";
    $result = $mysqli->query("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                              FROM information_schema.KEY_COLUMN_USAGE 
                              WHERE TABLE_NAME = 'orden_pagos' AND TABLE_SCHEMA = 'reparaciones_taller' 
                              AND REFERENCED_TABLE_NAME IS NOT NULL");
    
    if ($result && $result->num_rows > 0) {
        while ($constraint = $result->fetch_assoc()) {
            echo "   {$constraint['CONSTRAINT_NAME']}: {$constraint['COLUMN_NAME']} -> {$constraint['REFERENCED_TABLE_NAME']}.{$constraint['REFERENCED_COLUMN_NAME']}\n";
        }
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
