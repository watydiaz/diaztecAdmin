<?php
require_once 'models/ProductoModel.php';

class ProductoController {
    private $productoModel;

    public function __construct() {
        $this->productoModel = new ProductoModel();
    }

    /**
     * Mostrar la vista principal del inventario
     */
    public function index() {
        include 'views/inventario.php';
    }

    /**
     * Obtener productos para la API (JSON)
     */
    public function listar() {
        try {
            // Obtener filtros de la petición
            $filtros = [];
            
            if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
                $filtros['buscar'] = $_GET['buscar'];
            }
            
            if (isset($_GET['stock_bajo'])) {
                $filtros['stock_bajo'] = intval($_GET['stock_bajo']);
            }
            
            if (isset($_GET['activo'])) {
                $filtros['activo'] = intval($_GET['activo']);
            } else {
                $filtros['activo'] = 1; // Solo productos activos por defecto
            }

            $productos = $this->productoModel->obtenerProductos($filtros);

            $this->enviarRespuestaJSON([
                'success' => true,
                'productos' => $productos
            ]);

        } catch (Exception $e) {
            $this->enviarRespuestaJSON([
                'success' => false,
                'message' => 'Error al obtener productos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Crear un nuevo producto
     */
    public function crear() {
        try {
            // Limpiar completamente cualquier salida previa
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Iniciar un nuevo buffer
            ob_start();
            
            // Desactivar cualquier salida de errores
            ini_set('display_errors', 0);
            
            // Log de datos recibidos para depuración (solo en archivo de log)
            error_log("ProductoController->crear() - Datos POST: " . print_r($_POST, true));
            
            // Validar datos requeridos
            $datos = $this->validarDatosProducto($_POST);
            
            if (!$datos) {
                $this->enviarRespuestaJSON([
                    'success' => false,
                    'message' => 'Datos de producto inválidos. Campos requeridos: nombre, precio_venta'
                ]);
                return;
            }

            // Verificar si el producto ya existe
            if ($this->productoModel->existeProducto($datos['nombre'])) {
                $this->enviarRespuestaJSON([
                    'success' => false,
                    'message' => 'Ya existe un producto con ese nombre'
                ]);
                return;
            }

            $id = $this->productoModel->crearProducto($datos);

            if ($id) {
                $producto = $this->productoModel->obtenerProductoPorId($id);
                
                $this->enviarRespuestaJSON([
                    'success' => true,
                    'message' => 'Producto creado exitosamente',
                    'producto' => $producto
                ]);
            } else {
                $this->enviarRespuestaJSON([
                    'success' => false,
                    'message' => 'Error al crear el producto en la base de datos'
                ]);
            }

        } catch (Exception $e) {
            error_log("Error en ProductoController->crear(): " . $e->getMessage());
            $this->enviarRespuestaJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Enviar respuesta JSON limpia
     */
    private function enviarRespuestaJSON($data) {
        // Limpiar cualquier salida anterior
        if (ob_get_length()) {
            ob_clean();
        }
        
        // Establecer headers
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');
        
        // Enviar JSON y terminar
        echo json_encode($data);
        
        // Terminar el script completamente
        exit;
    }

    /**
     * Obtener un producto por ID
     */
    public function obtenerPorId() {
        try {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            if (!$id) {
                throw new Exception('ID de producto no proporcionado');
            }

            $producto = $this->productoModel->obtenerProductoPorId($id);

            if (!$producto) {
                throw new Exception('Producto no encontrado');
            }

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'producto' => $producto
            ]);

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Actualizar un producto existente
     */
    public function actualizar() {
        try {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            
            if (!$id) {
                $this->enviarRespuestaJSON([
                    'success' => false,
                    'message' => 'ID de producto no proporcionado'
                ]);
                return;
            }

            // Validar datos
            $datos = $this->validarDatosProducto($_POST);
            
            if (!$datos) {
                $this->enviarRespuestaJSON([
                    'success' => false,
                    'message' => 'Datos de producto inválidos'
                ]);
                return;
            }

            // Verificar si existe otro producto con el mismo nombre
            if ($this->productoModel->existeProducto($datos['nombre'], $id)) {
                $this->enviarRespuestaJSON([
                    'success' => false,
                    'message' => 'Ya existe otro producto con ese nombre'
                ]);
                return;
            }

            $resultado = $this->productoModel->actualizarProducto($id, $datos);

            if ($resultado) {
                $producto = $this->productoModel->obtenerProductoPorId($id);
                
                $this->enviarRespuestaJSON([
                    'success' => true,
                    'message' => 'Producto actualizado exitosamente',
                    'producto' => $producto
                ]);
            } else {
                $this->enviarRespuestaJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el producto'
                ]);
            }

        } catch (Exception $e) {
            $this->enviarRespuestaJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar un producto (soft delete)
     */
    public function eliminar() {
        try {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            
            if (!$id) {
                throw new Exception('ID de producto no proporcionado');
            }

            $resultado = $this->productoModel->eliminarProducto($id);

            if ($resultado) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Producto eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar el producto');
            }

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ajustar stock de un producto
     */
    public function ajustarStock() {
        try {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
            $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
            $motivo = isset($_POST['motivo']) ? $_POST['motivo'] : '';

            if (!$id || !$cantidad || !in_array($tipo, ['incremento', 'decremento', 'establecer'])) {
                throw new Exception('Datos de ajuste de stock inválidos');
            }

            if ($tipo === 'establecer') {
                $resultado = $this->productoModel->establecerStock($id, $cantidad);
            } else {
                $resultado = $this->productoModel->ajustarStock($id, $cantidad, $tipo);
            }

            if ($resultado) {
                $producto = $this->productoModel->obtenerProductoPorId($id);
                
                // TODO: Aquí podrías registrar el movimiento en una tabla de historial
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Stock ajustado exitosamente',
                    'producto' => $producto
                ]);
            } else {
                throw new Exception('Error al ajustar el stock');
            }

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas del inventario
     */
    public function obtenerEstadisticas() {
        try {
            $estadisticas = $this->productoModel->obtenerEstadisticas();

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'estadisticas' => $estadisticas
            ]);

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Buscar productos (para autocomplete)
     */
    public function buscar() {
        try {
            $termino = isset($_GET['q']) ? trim($_GET['q']) : '';
            $limite = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

            if (empty($termino)) {
                throw new Exception('Término de búsqueda vacío');
            }

            $productos = $this->productoModel->buscarProductos($termino, $limite);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'productos' => $productos
            ]);

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener productos con stock crítico
     */
    public function obtenerStockCritico() {
        try {
            $productos = $this->productoModel->obtenerProductosStockCritico();

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'productos' => $productos
            ]);

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener productos con stock crítico: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Validar datos de producto
     */
    private function validarDatosProducto($datos) {
        $productoData = [];

        // Nombre (requerido)
        if (empty($datos['nombre'])) {
            return false;
        }
        $productoData['nombre'] = trim($datos['nombre']);

        // Descripción (opcional)
        $productoData['descripcion'] = isset($datos['descripcion']) ? trim($datos['descripcion']) : '';

        // Precio de compra (opcional, por defecto 0)
        $productoData['precio_compra'] = isset($datos['precio_compra']) ? 
            floatval($datos['precio_compra']) : 0.00;

        // Precio de venta (requerido)
        if (!isset($datos['precio_venta']) || floatval($datos['precio_venta']) <= 0) {
            return false;
        }
        $productoData['precio_venta'] = floatval($datos['precio_venta']);

        // Stock (opcional, por defecto 0)
        $productoData['stock'] = isset($datos['stock']) ? 
            intval($datos['stock']) : 0;

        // Stock mínimo (opcional, por defecto 0)
        $productoData['stock_minimo'] = isset($datos['stock_minimo']) ? 
            intval($datos['stock_minimo']) : 0;

        // Activo (opcional, por defecto 1)
        $productoData['activo'] = isset($datos['activo']) ? 
            intval($datos['activo']) : 1;

        // Imagen (opcional, procesar subida si existe)
        if (isset($_FILES['imagen']) && isset($_FILES['imagen']['tmp_name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
            $rutaDestino = 'assets/img/' . $nombreArchivo;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $productoData['imagen'] = $rutaDestino;
            } else {
                $productoData['imagen'] = '';
            }
        } else if (isset($datos['imagen'])) {
            $productoData['imagen'] = trim($datos['imagen']);
        } else {
            $productoData['imagen'] = '';
        }

        // Categoría (opcional)
        $productoData['categoria'] = isset($datos['categoria']) ? trim($datos['categoria']) : '';

        // Código de barras (opcional)
        $productoData['codigo_barras'] = isset($datos['codigo_barras']) ? trim($datos['codigo_barras']) : '';

        return $productoData;
    }
}
?>
