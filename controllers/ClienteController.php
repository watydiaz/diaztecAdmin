<?php

require_once 'models/ClienteModel.php';

class ClienteController {
    private $clienteModel;

    public function __construct($db) {
        $this->clienteModel = new ClienteModel($db);
    }

    // Listar clientes
    public function listarClientes() {
        return $this->clienteModel->obtenerClientes();
    }

    // Crear un cliente
    public function crearCliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identificacion = $_POST['identificacion'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $email = $_POST['email'];
            $direccion = $_POST['direccion'];

            // Validar campos obligatorios
            if (!empty($identificacion) && !empty($nombre)) {
                $resultado = $this->clienteModel->crearCliente($identificacion, $nombre, $telefono, $email, $direccion);

                if ($resultado) {
                    // Redirigir con éxito
                    header("Location: index.php?controller=cliente&action=listar&success=1");
                    exit();
                } else {
                    // Error al crear cliente
                    header("Location: index.php?controller=cliente&action=crear&error=1");
                    exit();
                }
            } else {
                // Campos obligatorios faltantes
                header("Location: index.php?controller=cliente&action=crear&error=2");
                exit();
            }
        }
    }
}
?>