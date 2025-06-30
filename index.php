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
require_once 'controllers/OrdenController.php';
require_once 'models/OrdenModel.php';
require_once 'controllers/RemisionController.php';
require_once 'models/RemisionModel.php';

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

// Instanciar el controlador de órdenes
$ordenController = new OrdenController();

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

    case 'ordenes':
        $ordenController->listarOrdenes();
        break;

    case 'agregarOrden':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ordenController->agregarOrden($_POST);
        }
        break;

    case 'obtenerOrden':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $ordenController->obtenerOrden($_GET['id']);
        }
        break;

    case 'editarOrden':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ordenController->actualizarOrden($_POST['id'], $_POST);
        }
        break;

    case 'eliminarOrden':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $ordenController->eliminarOrden($_GET['id']);
        }
        break;

    case 'actualizarOrden':
        $ordenController = new OrdenController();
        $ordenController->actualizarOrden($_POST['id'], $_POST);
        break;

    case 'buscarCliente':
        $clienteController->buscarClientes();
        break;

    case 'obtenerTecnicos':
        $loginController->obtenerTecnicos();
        break;

    case 'obtenerMetricas':
        require_once 'controllers/DashboardController.php';
        $dashboardController = new DashboardController();
        $dashboardController->obtenerMetricas();
        break;

    case 'remisiones':
        $remisionController = new RemisionController();
        $remisionController->mostrarRemisiones();
        break;

    case 'generarRemision':
        if (isset($_GET['id'])) {
            $ordenController->generarRemision($_GET['id']);
        } else {
            echo "<h1>ID de la orden no proporcionado</h1>";
        }
        break;

    case 'cambiarEstadoEntregado':
        if (isset($_GET['id'])) {
            $ordenController->cambiarEstadoEntregado($_GET['id']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de la orden no proporcionado.']);
        }
        break;

    case 'cambiarEstadoTerminado':
        if (isset($_GET['id'])) {
            $ordenController->cambiarEstadoTerminado($_GET['id']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de la orden no proporcionado.']);
        }
        break;

    case 'obtenerPagosDashboard':
        require_once 'controllers/DashboardController.php';
        $dashboardController = new DashboardController();
        $dashboardController->obtenerPagosDashboard();
        break;

    default:
        // Redirigir al login si la acción no es válida
        header('Location: index.php?action=login');
        exit();
}
?>
