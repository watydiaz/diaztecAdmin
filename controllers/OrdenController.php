<?php
require_once 'models/OrdenModel.php';

class OrdenController {
    private $ordenModel;

    public function __construct() {
        $this->ordenModel = new OrdenModel();
    }

    public function listarOrdenes() {
        $ordenes = $this->ordenModel->obtenerOrdenes();
        include 'views/ordenes.php';
    }

    public function agregarOrden($data) {
        // Validar que cliente_id y usuario_tecnico_id sean válidos
        if (empty($data['cliente_id']) || empty($data['usuario_tecnico_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Cliente o técnico no especificado.']);
            exit();
        }

        // Intentar agregar la orden
        $resultado = $this->ordenModel->agregarOrden($data);

        header('Content-Type: application/json');
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Orden registrada exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la orden.']);
        }
        exit();
    }

    public function obtenerOrden($id) {
        $orden = $this->ordenModel->obtenerOrdenPorId($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'orden' => $orden]);
        exit();
    }

    public function actualizarOrden($id, $data) {
        $this->ordenModel->actualizarOrden($id, $data);
        header('Location: index.php?action=ordenes');
        exit();
    }

    public function eliminarOrden($id) {
        $this->ordenModel->eliminarOrden($id);
        header('Location: index.php?action=ordenes');
        exit();
    }
}