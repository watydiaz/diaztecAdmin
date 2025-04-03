<?php

require_once 'assets/bd/bd.php';
require_once 'controllers/LoginController.php';
require_once 'controllers/ClienteController.php';

$controller = $_GET['controller'] ?? 'login';
$action = $_GET['action'] ?? 'showLogin';

if ($controller === 'login') {
    $loginController = new LoginController($conn);

    if ($action === 'showLogin') {
        require_once 'views/login.php';
    } elseif ($action === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $loginController->login($email, $password);
    } elseif ($action === 'logout') {
        $loginController->logout();
    }
} elseif ($controller === 'dashboard') {
    if ($action === 'home') {
        require_once 'views/dashboard.php';
    }
} elseif ($controller === 'cliente') {
    $clienteController = new ClienteController($conn);

    if ($action === 'listar') {
        $clientes = $clienteController->listarClientes();
        require_once 'views/listar_clientes.php';
    } elseif ($action === 'crear') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clienteController->crearCliente();
        } else {
            require_once 'views/crear_cliente.php';
        }
    }
}

?>