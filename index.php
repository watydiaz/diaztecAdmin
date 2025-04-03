<?php

require_once 'assets/bd/bd.php';
require_once 'controllers/LoginController.php';

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
}
?>