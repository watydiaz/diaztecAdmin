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
        $clienteController->agregarClienteDesdeModal();
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

    case 'caja':
        // Mostrar la vista de caja con los pagos
        require_once 'controllers/OrdenPagoController.php';
        $ordenPagoController = new OrdenPagoController();
        // La vista espera $pagos, así que lo obtenemos aquí
        $db = (new Conexion())->getConexion();
        $pagos = [];
        $result = $db->query("SELECT id, fecha_pago, orden_id, dinero_recibido FROM orden_pagos ORDER BY fecha_pago DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $pagos[] = $row;
            }
        }
        include 'views/caja.php';
        break;

    case 'obtenerPagosCaja':
        require_once 'models/Conexion.php';
        $db = (new Conexion())->getConexion();
        
        // Pagos de órdenes de servicio
        $pagos = [];
        $result = $db->query("SELECT id, fecha_pago, orden_id, dinero_recibido FROM orden_pagos ORDER BY fecha_pago DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $pagos[] = $row;
            }
        }
        
        // Pagos de productos (ventas)
        $pagos_productos = [];
        $query = "SELECT 
                    pp.id,
                    pp.venta_id,
                    pp.dinero_recibido as total,
                    pp.metodo_pago,
                    pp.fecha_pago,
                    v.numero_factura,
                    v.total as total_venta,
                    c.nombre as cliente_nombre,
                    c.identificacion as cliente_identificacion,
                    u.nombre as usuario_nombre
                  FROM pagos_productos pp
                  INNER JOIN ventas v ON pp.venta_id = v.id
                  INNER JOIN clientes c ON v.cliente_id = c.id
                  LEFT JOIN usuarios u ON pp.usuario_id = u.id
                  ORDER BY pp.fecha_pago DESC";
        
        $result_productos = $db->query($query);
        if ($result_productos) {
            while ($row = $result_productos->fetch_assoc()) {
                $pagos_productos[] = $row;
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'pagos_ordenes' => $pagos,
            'pagos_productos' => $pagos_productos
        ]);
        exit();
        break;

    case 'obtenerProductos':
        require_once 'models/Conexion.php';
        $db = Conexion::getConexion();
        $productos = [];
        $result = $db->query("SELECT id, nombre, precio_venta as precio FROM productos ORDER BY nombre ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($productos);
        exit();
        break;

    case 'obtenerClientes':
        require_once 'models/Conexion.php';
        $db = Conexion::getConexion();
        $clientes = [];
        $result = $db->query("SELECT id, nombre, telefono, email FROM clientes ORDER BY nombre ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $clientes[] = $row;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($clientes);
        exit();
        break;

    case 'buscarProducto':
        require_once 'models/Conexion.php';
        $db = Conexion::getConexion();
        $query = isset($_GET['query']) ? $_GET['query'] : '';
        $productos = [];
        
        if (!empty($query)) {
            $stmt = $db->prepare("SELECT id, nombre, precio_venta, stock FROM productos WHERE nombre LIKE ? ORDER BY nombre ASC LIMIT 10");
            $searchTerm = '%' . $query . '%';
            $stmt->bind_param('s', $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                // Asegurar que el precio_venta esté disponible
                $row['precio_venta'] = $row['precio_venta'] ?: 0;
                $productos[] = $row;
            }
            $stmt->close();
        }
        
        header('Content-Type: application/json');
        echo json_encode(['productos' => $productos]);
        exit();
        break;

    case 'agregarProducto':
        require_once 'models/Conexion.php';
        $db = Conexion::getConexion();
        
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $precio_compra = isset($_POST['precio_compra']) ? floatval($_POST['precio_compra']) : 0;
        $precio_venta = isset($_POST['precio_venta']) ? floatval($_POST['precio_venta']) : 0;
        $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
        
        if (empty($nombre)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'El nombre del producto es obligatorio']);
            exit();
        }
        
        // Verificar si el producto ya existe
        $stmt = $db->prepare("SELECT id FROM productos WHERE nombre = ?");
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt->close();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Ya existe un producto con ese nombre']);
            exit();
        }
        $stmt->close();
        
        // Insertar nuevo producto
        $stmt = $db->prepare("INSERT INTO productos (nombre, precio_compra, precio_venta, stock) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sddi', $nombre, $precio_compra, $precio_venta, $stock);
        
        if ($stmt->execute()) {
            $producto_id = $db->insert_id;
            $stmt->close();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Producto agregado exitosamente',
                'producto_id' => $producto_id,
                'producto' => [
                    'id' => $producto_id,
                    'nombre' => $nombre,
                    'precio_venta' => $precio_venta,
                    'stock' => $stock
                ]
            ]);
        } else {
            $stmt->close();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error al agregar el producto: ' . $db->error]);
        }
        exit();
        break;

    case 'registrarVentaProductos':
        require_once 'models/Conexion.php';
        $db = Conexion::getConexion();
        
        // Obtener datos JSON del request
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data || !isset($data['productos']) || !isset($data['total'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos de venta inválidos']);
            exit();
        }
        
        $productos = $data['productos'];
        $total = floatval($data['total']);
        
        if (empty($productos)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No se proporcionaron productos']);
            exit();
        }
        
        // Iniciar transacción
        $db->begin_transaction();
        
        try {
            $ventasRegistradas = [];
            
            // Insertar cada producto como una venta individual según la estructura existente
            $stmt = $db->prepare("INSERT INTO ventas_productos (producto_id, cantidad, precio_unitario, total) VALUES (?, ?, ?, ?)");
            
            foreach ($productos as $producto) {
                $producto_id = intval($producto['id']);
                $cantidad = intval($producto['cantidad']);
                $precio = floatval($producto['precio']);
                $subtotal = floatval($producto['subtotal']);
                
                $stmt->bind_param('iidd', $producto_id, $cantidad, $precio, $subtotal);
                
                if (!$stmt->execute()) {
                    throw new Exception('Error al registrar la venta del producto: ' . $stmt->error);
                }
                
                $ventasRegistradas[] = $db->insert_id;
                
                // Actualizar stock del producto
                $stmtStock = $db->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
                $stmtStock->bind_param('ii', $cantidad, $producto_id);
                
                if (!$stmtStock->execute()) {
                    throw new Exception('Error al actualizar stock del producto: ' . $stmtStock->error);
                }
                $stmtStock->close();
            }
            
            $stmt->close();
            
            // Commit de la transacción
            $db->commit();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Venta registrada exitosamente',
                'ventas_ids' => $ventasRegistradas
            ]);
            
        } catch (Exception $e) {
            // Rollback en caso de error
            $db->rollback();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        
        exit();
        break;

    case 'obtenerDetalleFactura':
        require_once 'models/Conexion.php';
        $db = Conexion::getConexion();
        $venta_id = isset($_GET['venta_id']) ? intval($_GET['venta_id']) : 0;
        
        if (!$venta_id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID de venta no proporcionado']);
            exit();
        }
        
        // Obtener información de la venta
        $query_venta = "SELECT 
                            v.id,
                            v.numero_factura,
                            v.total,
                            v.metodo_pago,
                            v.fecha_venta,
                            c.nombre as cliente_nombre,
                            c.identificacion as cliente_identificacion,
                            c.telefono as cliente_telefono,
                            c.email as cliente_email,
                            u.nombre as usuario_nombre
                        FROM ventas v
                        INNER JOIN clientes c ON v.cliente_id = c.id
                        LEFT JOIN usuarios u ON v.usuario_id = u.id
                        WHERE v.id = ?";
        
        $stmt = $db->prepare($query_venta);
        $stmt->bind_param('i', $venta_id);
        $stmt->execute();
        $venta = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (!$venta) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Venta no encontrada']);
            exit();
        }
        
        // Obtener detalles de productos
        $query_detalles = "SELECT 
                              dv.producto_id,
                              dv.cantidad,
                              dv.precio_unitario,
                              dv.subtotal,
                              p.nombre as producto_nombre
                           FROM detalle_ventas dv
                           INNER JOIN productos p ON dv.producto_id = p.id
                           WHERE dv.venta_id = ?
                           ORDER BY p.nombre ASC";
        
        $stmt_detalles = $db->prepare($query_detalles);
        $stmt_detalles->bind_param('i', $venta_id);
        $stmt_detalles->execute();
        $result_detalles = $stmt_detalles->get_result();
        
        $detalles = [];
        while ($row = $result_detalles->fetch_assoc()) {
            $detalles[] = $row;
        }
        $stmt_detalles->close();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'venta' => $venta,
            'detalles' => $detalles
        ]);
        exit();
        break;

    case 'registrarVentaCompleta':
        require_once 'models/Conexion.php';
        session_start();
        $db = Conexion::getConexion();
        
        // Obtener datos del POST
        $cliente_id = isset($_POST['cliente_id']) ? intval($_POST['cliente_id']) : null;
        $productos = isset($_POST['productos']) ? json_decode($_POST['productos'], true) : [];
        $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
        $metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : '';
        $dinero_recibido = isset($_POST['dinero_recibido']) ? floatval($_POST['dinero_recibido']) : 0;
        $cambio = isset($_POST['cambio']) ? floatval($_POST['cambio']) : 0;
        $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1; // Default admin
        
        // Validaciones
        if (!$cliente_id || empty($productos) || $total <= 0 || empty($metodo_pago) || $dinero_recibido <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos de venta incompletos']);
            exit();
        }
        
        // Iniciar transacción
        $db->begin_transaction();
        
        try {
            // 1. Insertar la venta principal
            $numero_factura = 'FAC-' . str_pad(($db->query("SELECT COUNT(*) as total FROM ventas")->fetch_assoc()['total'] + 1), 3, '0', STR_PAD_LEFT);
            $stmt = $db->prepare("INSERT INTO ventas (cliente_id, usuario_id, numero_factura, total, metodo_pago, fecha_venta) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param('iisds', $cliente_id, $usuario_id, $numero_factura, $total, $metodo_pago);
            
            if (!$stmt->execute()) {
                throw new Exception('Error al registrar la venta: ' . $stmt->error);
            }
            
            $venta_id = $db->insert_id;
            $stmt->close();
            
            // 2. Insertar los detalles de la venta
            $stmt_detalle = $db->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
            
            foreach ($productos as $producto) {
                $producto_id = intval($producto['id']);
                $cantidad = intval($producto['cantidad']);
                $precio_unitario = floatval($producto['precio']);
                $subtotal = $precio_unitario * $cantidad;
                
                $stmt_detalle->bind_param('iiidd', $venta_id, $producto_id, $cantidad, $precio_unitario, $subtotal);
                
                if (!$stmt_detalle->execute()) {
                    throw new Exception('Error al registrar detalle de venta: ' . $stmt_detalle->error);
                }
                
                // Verificar stock disponible
                $stmt_stock_check = $db->prepare("SELECT stock FROM productos WHERE id = ?");
                $stmt_stock_check->bind_param('i', $producto_id);
                $stmt_stock_check->execute();
                $stock_result = $stmt_stock_check->get_result()->fetch_assoc();
                $stock_actual = $stock_result ? $stock_result['stock'] : 0;
                $stmt_stock_check->close();
                
                if ($stock_actual < $cantidad) {
                    throw new Exception('Stock insuficiente para el producto ID: ' . $producto_id . '. Stock disponible: ' . $stock_actual);
                }
                
                // Actualizar stock (ya se hace automáticamente por el trigger, pero por seguridad)
                $stmt_stock = $db->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
                $stmt_stock->bind_param('ii', $cantidad, $producto_id);
                $stmt_stock->execute();
                $stmt_stock->close();
            }
            
            $stmt_detalle->close();
            
            // 3. Registrar el pago en pagos_productos para el módulo de caja
            // Nota: En pagos_productos guardamos el TOTAL de la venta, no el dinero recibido
            // El dinero_recibido se usa solo para calcular cambio en el frontend
            $stmt_pago = $db->prepare("INSERT INTO pagos_productos (venta_id, dinero_recibido, metodo_pago, fecha_pago, usuario_id) VALUES (?, ?, ?, NOW(), ?)");
            $stmt_pago->bind_param('idsi', $venta_id, $total, $metodo_pago, $usuario_id);
            
            if (!$stmt_pago->execute()) {
                throw new Exception('Error al registrar el pago: ' . $stmt_pago->error);
            }
            $stmt_pago->close();
            
            // Commit de la transacción
            $db->commit();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Venta registrada exitosamente',
                'venta_id' => $venta_id,
                'numero_factura' => $numero_factura,
                'total' => $total,
                'cambio' => $cambio
            ]);
            
        } catch (Exception $e) {
            // Rollback en caso de error
            $db->rollback();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        
        exit();
        break;

    default:
        // Redirigir al login si la acción no es válida
        header('Location: index.php?action=login');
        exit();
}
?>
