<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../models/OrdenPagoModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class OrdenPagoController {
    private $model;

    public function __construct() {
        $db = (new Conexion())->getConexion();
        $this->model = new OrdenPagoModel($db);
    }

    // Insertar un nuevo pago (desde POST)
    public function insertarPago() {
        $data = [
            'orden_id' => $_POST['orden_id'],
            'usuario_id' => $_POST['usuario_id'],
            'fecha_pago' => $_POST['fecha_pago'],
            'costo_total' => $_POST['costo_total'],
            'valor_repuestos' => $_POST['valor_repuestos'],
            'descripcion_repuestos' => $_POST['descripcion_repuestos'],
            'metodo_pago' => $_POST['metodo_pago'],
            'saldo' => $_POST['saldo']
        ];
        $resultado = $this->model->insertarPago($data);
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Pago registrado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el pago']);
        }
    }

    // Obtener pagos por orden (GET)
    public function obtenerPagosPorOrden($orden_id) {
        $pagos = $this->model->obtenerPagosPorOrden($orden_id);
        echo json_encode($pagos);
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
    exit;
}
