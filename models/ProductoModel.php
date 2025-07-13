<?php
require_once 'Conexion.php';

class ProductoModel {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Obtener todos los productos con filtros opcionales
     */
    public function obtenerProductos($filtros = []) {
        $where = [];
        $params = [];
        $types = '';

        // Construir condiciones WHERE dinámicamente
        if (!empty($filtros['buscar'])) {
            $where[] = "(nombre LIKE ? OR descripcion LIKE ?)";
            $params[] = '%' . $filtros['buscar'] . '%';
            $params[] = '%' . $filtros['buscar'] . '%';
            $types .= 'ss';
        }

        if (isset($filtros['activo'])) {
            $where[] = "activo = ?";
            $params[] = $filtros['activo'];
            $types .= 'i';
        }

        if (isset($filtros['stock_bajo']) && $filtros['stock_bajo'] == 1) {
            $where[] = "stock <= stock_minimo";
        }

        // Construir la consulta
        $sql = "SELECT 
                    id, 
                    nombre, 
                    descripcion, 
                    precio_compra, 
                    precio_venta, 
                    stock, 
                    stock_minimo, 
                    activo, 
                    fecha_creacion, 
                    fecha_actualizacion,
                    imagen,
                    categoria,
                    codigo_barras,
                    CASE WHEN stock <= stock_minimo THEN 1 ELSE 0 END as stock_bajo
                FROM productos";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY nombre ASC";

        // Aplicar límite si se especifica
        if (isset($filtros['limite'])) {
            $sql .= " LIMIT " . intval($filtros['limite']);
        }

        if (!empty($params)) {
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conexion->query($sql);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener un producto por ID
     */
    public function obtenerProductoPorId($id) {
        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Crear un nuevo producto
     */
    public function crearProducto($datos) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio_compra, precio_venta, stock, stock_minimo, activo, imagen, categoria, codigo_barras) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conexion->prepare($sql);
        
        // Asegurar que activo tenga un valor válido
        $activo = isset($datos['activo']) ? $datos['activo'] : 1;
        $imagen = isset($datos['imagen']) ? $datos['imagen'] : '';
        $categoria = isset($datos['categoria']) ? $datos['categoria'] : '';
        $codigo_barras = isset($datos['codigo_barras']) ? $datos['codigo_barras'] : '';
        
        $stmt->bind_param(
            'ssddiiisss',
            $datos['nombre'],
            $datos['descripcion'],
            $datos['precio_compra'],
            $datos['precio_venta'],
            $datos['stock'],
            $datos['stock_minimo'],
            $activo,
            $imagen,
            $categoria,
            $codigo_barras
        );

        if ($stmt->execute()) {
            return $this->conexion->insert_id;
        }
        return false;
    }

    /**
     * Actualizar un producto existente
     */
    public function actualizarProducto($id, $datos) {
        $sql = "UPDATE productos 
                SET nombre = ?, descripcion = ?, precio_compra = ?, precio_venta = ?, 
                    stock = ?, stock_minimo = ?, activo = ?, imagen = ?, categoria = ?, codigo_barras = ?, fecha_actualizacion = NOW()
                WHERE id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $imagen = isset($datos['imagen']) ? $datos['imagen'] : '';
        $categoria = isset($datos['categoria']) ? $datos['categoria'] : '';
        $codigo_barras = isset($datos['codigo_barras']) ? $datos['codigo_barras'] : '';
        $activo = isset($datos['activo']) ? $datos['activo'] : 1;
        
        $stmt->bind_param(
            'ssddiiisssi',
            $datos['nombre'],
            $datos['descripcion'],
            $datos['precio_compra'],
            $datos['precio_venta'],
            $datos['stock'],
            $datos['stock_minimo'],
            $activo,
            $imagen,
            $categoria,
            $codigo_barras,
            $id
        );

        return $stmt->execute();
    }

    /**
     * Eliminar un producto (soft delete - cambiar activo a 0)
     */
    public function eliminarProducto($id) {
        $sql = "UPDATE productos SET activo = 0, fecha_actualizacion = NOW() WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Eliminar producto permanentemente
     */
    public function eliminarProductoPermanente($id) {
        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Ajustar stock de un producto
     */
    public function ajustarStock($id, $cantidad, $tipo = 'incremento') {
        if ($tipo === 'incremento') {
            $sql = "UPDATE productos SET stock = stock + ?, fecha_actualizacion = NOW() WHERE id = ?";
        } else {
            $sql = "UPDATE productos SET stock = stock - ?, fecha_actualizacion = NOW() WHERE id = ?";
        }
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ii', $cantidad, $id);
        return $stmt->execute();
    }

    /**
     * Establecer stock específico
     */
    public function establecerStock($id, $stock) {
        $sql = "UPDATE productos SET stock = ?, fecha_actualizacion = NOW() WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ii', $stock, $id);
        return $stmt->execute();
    }

    /**
     * Obtener estadísticas del inventario
     */
    public function obtenerEstadisticas() {
        $estadisticas = [];

        // Total de productos
        $result = $this->conexion->query("SELECT COUNT(*) as total FROM productos WHERE activo = 1");
        $estadisticas['total_productos'] = $result->fetch_assoc()['total'];

        // Productos con stock bajo
        $result = $this->conexion->query("SELECT COUNT(*) as total FROM productos WHERE activo = 1 AND stock <= stock_minimo");
        $estadisticas['productos_stock_bajo'] = $result->fetch_assoc()['total'];

        // Valor total del inventario a precio de costo
        $result = $this->conexion->query("SELECT SUM(stock * COALESCE(precio_compra, 0)) as valor_total FROM productos WHERE activo = 1");
        $estadisticas['valor_total_costo'] = $result->fetch_assoc()['valor_total'] ?? 0;

        // Valor total del inventario a precio de venta
        $result = $this->conexion->query("SELECT SUM(stock * precio_venta) as valor_total FROM productos WHERE activo = 1");
        $estadisticas['valor_total_inventario'] = $result->fetch_assoc()['valor_total'] ?? 0;

        // Productos sin stock
        $result = $this->conexion->query("SELECT COUNT(*) as total FROM productos WHERE activo = 1 AND stock = 0");
        $estadisticas['productos_sin_stock'] = $result->fetch_assoc()['total'];

        return $estadisticas;
    }

    /**
     * Buscar productos por nombre (para autocomplete)
     */
    public function buscarProductos($termino, $limite = 10) {
        $sql = "SELECT id, nombre, precio_venta, stock 
                FROM productos 
                WHERE activo = 1 AND nombre LIKE ? 
                ORDER BY nombre ASC 
                LIMIT ?";
        
        $stmt = $this->conexion->prepare($sql);
        $termino = '%' . $termino . '%';
        $stmt->bind_param('si', $termino, $limite);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Verificar si un producto existe por nombre
     */
    public function existeProducto($nombre, $excluir_id = null) {
        $sql = "SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND activo = 1";
        $params = [$nombre];
        $types = 's';

        if ($excluir_id) {
            $sql .= " AND id != ?";
            $params[] = $excluir_id;
            $types .= 'i';
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] > 0;
    }

    /**
     * Obtener productos con stock crítico
     */
    public function obtenerProductosStockCritico() {
        $sql = "SELECT id, nombre, stock, stock_minimo 
                FROM productos 
                WHERE activo = 1 AND stock <= stock_minimo 
                ORDER BY (stock - stock_minimo) ASC";
        
        $result = $this->conexion->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
