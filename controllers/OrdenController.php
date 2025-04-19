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
        $this->ordenModel->agregarOrden($data);
        header('Location: index.php?action=ordenes');
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