<?php
require_once 'models/ClienteModel.php';
require_once 'models/OrdenModel.php';
require_once 'models/DashboardModel.php';

class DashboardController {
    private $clienteModel;
    private $ordenModel;
    private $dashboardModel;

    public function __construct() {
        $this->clienteModel = new ClienteModel();
        $this->ordenModel = new OrdenModel();
        $this->dashboardModel = new DashboardModel(Conexion::getConexion());
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

    public function obtenerPagosDashboard() {
        $pagosDiarios = $this->dashboardModel->getPagosDiarios();
        $pagosMensuales = $this->dashboardModel->getPagosMensuales();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'pagosDiarios' => $pagosDiarios['total_diario'],
            'pagosMensuales' => $pagosMensuales['total_mensual']
        ]);
        exit();
    }
}