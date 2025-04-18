<?php
require_once 'models/ClienteModel.php';

class ClienteController {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new ClienteModel();
    }

    public function listarClientes() {
        $clientes = $this->clienteModel->obtenerClientes();
        include 'views/clientes.php';
    }

    public function mostrarClientes() {
        $clientes = $this->clienteModel->obtenerClientes();
        include 'views/clientes.php';
    }

    public function agregarCliente($nombre, $identificacion, $telefono, $email, $direccion) {
        $this->clienteModel->agregarCliente($nombre, $identificacion, $telefono, $email, $direccion);
        header('Location: index.php?action=listarClientes');
        exit();
    }

    public function editarCliente($id, $nombre, $identificacion, $telefono, $email, $direccion) {
        $this->clienteModel->actualizarCliente($id, $nombre, $identificacion, $telefono, $email, $direccion);
        header('Location: index.php?action=listarClientes');
        exit();
    }

    public function eliminarCliente($id) {
        $resultado = $this->clienteModel->eliminarCliente($id);

        header('Content-Type: application/json');
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Cliente eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar cliente']);
        }
        exit();
    }

    public function agregarClienteDesdeModal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $identificacion = $_POST['identificacion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';
            $direccion = $_POST['direccion'] ?? '';

            $resultado = $this->clienteModel->agregarCliente($nombre, $identificacion, $telefono, $email, $direccion);

            header('Content-Type: application/json');
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Cliente agregado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar cliente']);
            }
            exit();
        }
    }

    public function obtenerCliente($id) {
        $cliente = $this->clienteModel->obtenerClientePorId($id);

        header('Content-Type: application/json');
        if ($cliente) {
            echo json_encode(['success' => true, 'cliente' => $cliente]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
        }
        exit();
    }

    public function actualizarClienteDesdeModal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $identificacion = $_POST['identificacion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';
            $direccion = $_POST['direccion'] ?? '';

            $resultado = $this->clienteModel->actualizarCliente($id, $nombre, $identificacion, $telefono, $email, $direccion);

            header('Content-Type: application/json');
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Cliente actualizado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar cliente']);
            }
            exit();
        }
    }
}