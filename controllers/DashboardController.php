<?php
require_once 'models/ClienteModel.php';
require_once 'models/OrdenModel.php';

class DashboardController {
    private $clienteModel;
    private $ordenModel;

    public function __construct() {
        $this->clienteModel = new ClienteModel();
        $this->ordenModel = new OrdenModel();
    }

    public function obtenerMetricas() {
        $clientesRegistrados = $this->clienteModel->contarClientes();
        $ordenesPendientes = $this->ordenModel->contarOrdenesPorEstado('pendiente');
        $ordenesEnProceso = $this->ordenModel->contarOrdenesPorEstado('en_proceso');
        $ordenesTerminadas = $this->ordenModel->contarOrdenesPorEstado('terminado');
        $ordenesEntregadas = $this->ordenModel->contarOrdenesPorEstado('entregado');

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'clientesRegistrados' => $clientesRegistrados,
            'ordenesPendientes' => $ordenesPendientes,
            'ordenesEnProceso' => $ordenesEnProceso,
            'ordenesTerminadas' => $ordenesTerminadas,
            'ordenesEntregadas' => $ordenesEntregadas
        ]);
        exit();
    }
}