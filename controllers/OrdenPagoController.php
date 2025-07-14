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
require_once __DIR__ . '/../models/OrdenModel.php'; // Added for obtenerOrdenPorId

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
                // 'saldo' => $_POST['saldo'] // El saldo se calculará en el backend
            ];
            // Validación previa de campos numéricos
            if (!is_numeric($data['orden_id']) || !is_numeric($data['usuario_id']) || !is_numeric($data['costo_total']) || !is_numeric($data['valor_repuestos'])) {
                echo json_encode(['success' => false, 'message' => 'Datos numéricos inválidos o vacíos']);
                return;
            }
            if (empty($data['metodo_pago'])) {
                echo json_encode(['success' => false, 'message' => 'Debe seleccionar un método de pago']);
                return;
            }
            // Obtener el costo_total real de la orden desde la base de datos
            require_once __DIR__ . '/../models/OrdenModel.php';
            $ordenModel = new OrdenModel();
            $orden = $ordenModel->obtenerOrdenPorId($data['orden_id']);
            if (!$orden || !isset($orden['costo_total'])) {
                echo json_encode(['success' => false, 'message' => 'No se pudo obtener el costo total de la orden.']);
                return;
            }
            $costo_total_real = floatval($orden['costo_total']);
            // Calcular saldo real antes de guardar
            require_once __DIR__ . '/../models/OrdenPagoModel.php';
            $pagos_anteriores = $this->model->obtenerPagosPorOrden($data['orden_id']);
            $total_abonado = 0;
            foreach ($pagos_anteriores as $pago) {
                $total_abonado += floatval($pago['dinero_recibido']);
            }
            $saldo = $costo_total_real - $total_abonado - floatval($data['dinero_recibido']);
            if ($saldo < 0) $saldo = 0;
            $data['costo_total'] = $costo_total_real;
            $data['saldo'] = $saldo;
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
