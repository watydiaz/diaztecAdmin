<?php
// Iniciar buffer de salida y configurar headers para AJAX
ob_start();
header('Content-Type: application/json');

// Solo mostrar errores en desarrollo, no en producción
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/OrdenPagoModel.php';
require_once __DIR__ . '/../models/Conexion.php';

// --- Manejo global de errores para devolver siempre JSON ---
set_exception_handler(function($e) {
    ob_clean(); // Limpiar cualquier output previo
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error' => $e->getMessage()
    ]);
    exit;
});
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    ob_clean(); // Limpiar cualquier output previo
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error' => "$errstr en $errfile:$errline"
    ]);
    exit;
});

class OrdenPagoController {
    private $model;

    public function __construct() {
        $db = (new Conexion())->getConexion();
        $this->model = new OrdenPagoModel($db);
    }

    // Insertar un nuevo pago (desde POST)
    public function insertarPago() {
        try {
            $data = [
                'orden_id' => $_POST['orden_id'],
                'usuario_id' => $_POST['usuario_id'],
                'fecha_pago' => $_POST['fecha_pago'],
                'costo_total' => $_POST['costo_total'],
                'dinero_recibido' => $_POST['dinero_recibido'],
                'valor_repuestos' => $_POST['valor_repuestos'],
                'descripcion_repuestos' => $_POST['descripcion_repuestos'],
                'metodo_pago' => $_POST['metodo_pago'],
                'saldo' => $_POST['saldo']
            ];
            // Validación previa de campos numéricos
            if (!is_numeric($data['orden_id']) || !is_numeric($data['usuario_id']) || !is_numeric($data['costo_total']) || !is_numeric($data['valor_repuestos']) || !is_numeric($data['saldo'])) {
                echo json_encode(['success' => false, 'message' => 'Datos numéricos inválidos o vacíos']);
                return;
            }
            if (empty($data['metodo_pago'])) {
                echo json_encode(['success' => false, 'message' => 'Debe seleccionar un método de pago']);
                return;
            }
            
            /* VALIDACIONES DESHABILITADAS TEMPORALMENTE
            // Validación adicional: verificar que la orden existe
            $db = (new Conexion())->getConexion();
            $check_orden = $db->prepare("SELECT id FROM ordenes_reparacion WHERE id = ?");
            $check_orden->bind_param("i", $data['orden_id']);
            $check_orden->execute();
            $result = $check_orden->get_result();
            
            if ($result->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'La orden especificada no existe (ID: ' . $data['orden_id'] . ')']);
                return;
            }
            $check_orden->close();
            
            // Validación adicional: verificar que el usuario existe
            $check_usuario = $db->prepare("SELECT id FROM usuarios WHERE id = ?");
            $check_usuario->bind_param("i", $data['usuario_id']);
            $check_usuario->execute();
            $result_usuario = $check_usuario->get_result();
            
            if ($result_usuario->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'El usuario especificado no existe (ID: ' . $data['usuario_id'] . ')']);
                return;
            }
            $check_usuario->close();
            */
            $resultado = $this->model->insertarPago($data);
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Pago registrado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al registrar el pago']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
        }
    }

    // Obtener pagos por orden (GET)
    public function obtenerPagosPorOrden($orden_id) {
        try {
            $pagos = $this->model->obtenerPagosPorOrden($orden_id);
            echo json_encode(['success' => true, 'pagos' => $pagos]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
        }
    }
}

// --- Router para AJAX ---
if (isset($_GET['accion'])) {
    $controller = new OrdenPagoController();
    switch ($_GET['accion']) {
        case 'insertar':
            $controller->insertarPago();
            break;
        case 'obtener':
            if (isset($_GET['orden_id'])) {
                $controller->obtenerPagosPorOrden($_GET['orden_id']);
            }
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    
    // Limpiar y enviar el buffer de salida
    ob_end_flush();
    exit;
}
