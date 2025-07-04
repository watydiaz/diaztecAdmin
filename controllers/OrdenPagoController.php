<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../models/OrdenPagoModel.php';
require_once __DIR__ . '/../models/Conexion.php';

// --- Manejo global de errores para devolver siempre JSON ---
set_exception_handler(function($e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error' => $e->getMessage()
    ]);
    exit;
});
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    header('Content-Type: application/json');
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
        header('Content-Type: application/json');
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
        header('Content-Type: application/json');
        try {
            $pagos = $this->model->obtenerPagosPorOrden($orden_id);
            echo json_encode($pagos);
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
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    exit;
}
