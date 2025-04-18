<?php

// Activo la visualización de errores para depurar problemas
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Archivo principal para manejar el enrutamiento

// Incluir los controladores y modelos necesarios
require_once 'models/UsuarioModel.php';
require_once 'controllers/LoginController.php';
require_once 'controllers/RolController.php';
require_once 'models/RolModel.php';
require_once 'controllers/ClienteController.php';
require_once 'models/ClienteModel.php';

// Conexión a la base de datos
$mysqli = new mysqli('localhost', 'root', '', 'reparaciones_taller');
if ($mysqli->connect_error) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}

// Instanciar el modelo y controlador
$usuarioModel = new UsuarioModel($mysqli);
$loginController = new LoginController($usuarioModel);

// Instanciar el modelo y controlador de roles
$rolModel = new RolModel($mysqli);
$rolController = new RolController($rolModel);

// Instanciar el modelo y controlador de clientes
$clienteModel = new ClienteModel($mysqli);
$clienteController = new ClienteController($clienteModel);

// Agregar enlace a la vista de órdenes
if (isset($_GET['view']) && $_GET['view'] === 'ordenes') {
    include 'views/ordenes.php';
    exit;
}

// Manejar las acciones según el parámetro "action"
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $loginController->iniciarSesion($email, $password);
        } else {
            $loginController->mostrarLogin();
        }
        break;

    case 'logout':
        $loginController->cerrarSesion();
        break;

    case 'dashboard':
        // Aquí se incluirá la lógica para mostrar el dashboard
        include 'views/dashboard.php';
        break;

    case 'roles':
        $rolController->mostrarRoles();
        break;

    case 'asignarPermiso':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rolId = $_POST['rol_id'];
            $permisoId = $_POST['permiso_id'];
            $rolController->asignarPermiso($rolId, $permisoId);
        }
        break;

    case 'eliminarPermiso':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rolId = $_POST['rol_id'];
            $permisoId = $_POST['permiso_id'];
            $rolController->eliminarPermiso($rolId, $permisoId);
        }
        break;

    case 'clientes':
        $clienteController->mostrarClientes();
        break;

    case 'crearCliente':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $identificacion = $_POST['identificacion'];
            $telefono = $_POST['telefono'];
            $email = $_POST['email'];
            $direccion = $_POST['direccion'];

            $resultado = $clienteController->agregarCliente($nombre, $identificacion, $telefono, $email, $direccion);

            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
            exit;
        }
        break;

    case 'agregarCliente':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $identificacion = $_POST['identificacion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';
            $direccion = $_POST['direccion'] ?? '';

            $resultado = $clienteController->agregarClienteDesdeModal();

            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
            exit();
        }
        break;

    case 'eliminarCliente':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id'] ?? null;

            if ($id) {
                $resultado = $clienteController->eliminarCliente($id);

                header('Content-Type: application/json');
                echo json_encode(['success' => $resultado]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID de cliente no proporcionado']);
            }
            exit();
        }
        break;

    case 'obtenerCliente':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id'] ?? null;

            if ($id) {
                $clienteController->obtenerCliente($id);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID de cliente no proporcionado']);
                exit();
            }
        }
        break;

    case 'editarCliente':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clienteController->actualizarClienteDesdeModal();
        }
        break;

    default:
        // Redirigir al login si la acción no es válida
        header('Location: index.php?action=login');
        exit();
}
?>
