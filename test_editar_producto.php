<?php
require_once 'models/Conexion.php';
require_once 'models/ProductoModel.php';

try {
    $db = (new Conexion())->getConexion();
    $productoModel = new ProductoModel();
    
    echo "<h3>Diagnóstico de Edición de Productos</h3>";
    
    // 1. Verificar si hay productos en la base de datos
    $productos = $db->query("SELECT id, nombre, precio_venta FROM productos LIMIT 3");
    
    if ($productos && $productos->num_rows > 0) {
        echo "<h4>1. Productos disponibles para editar:</h4>";
        while ($producto = $productos->fetch_assoc()) {
            echo "<p>ID: {$producto['id']} - Nombre: {$producto['nombre']} - Precio: {$producto['precio_venta']}</p>";
        }
        
        // 2. Probar obtener un producto por ID
        $primerProducto = $db->query("SELECT * FROM productos LIMIT 1")->fetch_assoc();
        if ($primerProducto) {
            echo "<h4>2. Datos del primer producto:</h4>";
            echo "<pre>" . print_r($primerProducto, true) . "</pre>";
            
            // 3. Simular datos de actualización
            $datosActualizacion = [
                'id' => $primerProducto['id'],
                'nombre' => $primerProducto['nombre'] . ' (EDITADO)',
                'descripcion' => $primerProducto['descripcion'] ?? 'Descripción editada',
                'precio_compra' => $primerProducto['precio_compra'] ?? 0,
                'precio_venta' => $primerProducto['precio_venta'],
                'stock' => $primerProducto['stock'] ?? 0,
                'stock_minimo' => $primerProducto['stock_minimo'] ?? 0,
                'categoria' => $primerProducto['categoria'] ?? '',
                'codigo_barras' => $primerProducto['codigo_barras'] ?? ''
            ];
            
            echo "<h4>3. Datos de actualización simulados:</h4>";
            echo "<pre>" . print_r($datosActualizacion, true) . "</pre>";
            
            // 4. Probar la actualización directamente en el modelo
            echo "<h4>4. Probando actualización en el modelo:</h4>";
            $resultado = $productoModel->actualizarProducto($primerProducto['id'], $datosActualizacion);
            
            if ($resultado) {
                echo "<p style='color: green;'>✅ Actualización exitosa en el modelo</p>";
                
                // Verificar el producto actualizado
                $productoActualizado = $productoModel->obtenerProductoPorId($primerProducto['id']);
                echo "<h4>5. Producto después de la actualización:</h4>";
                echo "<pre>" . print_r($productoActualizado, true) . "</pre>";
                
                // Revertir el cambio para no afectar los datos
                $datosOriginales = [
                    'nombre' => str_replace(' (EDITADO)', '', $datosActualizacion['nombre']),
                    'descripcion' => $primerProducto['descripcion'] ?? '',
                    'precio_compra' => $primerProducto['precio_compra'] ?? 0,
                    'precio_venta' => $primerProducto['precio_venta'],
                    'stock' => $primerProducto['stock'] ?? 0,
                    'stock_minimo' => $primerProducto['stock_minimo'] ?? 0,
                    'categoria' => $primerProducto['categoria'] ?? '',
                    'codigo_barras' => $primerProducto['codigo_barras'] ?? ''
                ];
                $productoModel->actualizarProducto($primerProducto['id'], $datosOriginales);
                echo "<p style='color: blue;'>🔄 Cambio revertido</p>";
                
            } else {
                echo "<p style='color: red;'>❌ Error en la actualización del modelo</p>";
            }
            
        } else {
            echo "<p style='color: orange;'>No hay productos en la base de datos</p>";
        }
        
    } else {
        echo "<p style='color: orange;'>No hay productos en la base de datos</p>";
    }
    
    // 6. Verificar estructura de la tabla
    echo "<h4>6. Estructura de la tabla productos:</h4>";
    $estructura = $db->query("DESCRIBE productos");
    if ($estructura) {
        echo "<table border='1' style='border-collapse: collapse; padding: 5px;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($campo = $estructura->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$campo['Field']}</td>";
            echo "<td>{$campo['Type']}</td>";
            echo "<td>{$campo['Null']}</td>";
            echo "<td>{$campo['Key']}</td>";
            echo "<td>{$campo['Default']}</td>";
            echo "<td>{$campo['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 