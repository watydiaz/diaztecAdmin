<?php

// Solo mostrar errores si no es una petición AJAX
$is_ajax = isset($_GET['action']) && in_array($_GET['action'], [
    'obtenerPagosCaja', 'obtenerDetalleFactura', 'obtenerDetalleOrden', 'registrarVentaCompleta', 
    'obtenerInventario', 'listarProductos', 'crearProducto', 'obtenerProducto', 'actualizarProducto', 
    'eliminarProducto', 'ajustarStockProducto', 'obtenerEstadisticasInventario', 'buscarProductos', 
    'obtenerStockCritico', 'getUserId'
]);

if (!$is_ajax) {
    // Activo la visualización de errores para depurar problemas
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    // Para peticiones AJAX, desactivar errores en pantalla
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
}

// Iniciar buffer de salida
ob_start();

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

    // === MÓDULO DE USUARIOS ===
    case 'usuarios':
        require_once 'controllers/UsuarioController.php';
        $usuarioController = new UsuarioController();
        $usuarioController->index();
        break;

    case 'listarUsuarios':
        require_once 'controllers/UsuarioController.php';
        $usuarioController = new UsuarioController();
        $usuarioController->listar();
        break;

    case 'crearUsuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/UsuarioController.php';
            $usuarioController = new UsuarioController();
            $usuarioController->crear();
        }
        break;

    case 'actualizarUsuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/UsuarioController.php';
            $usuarioController = new UsuarioController();
            $usuarioController->actualizar();
        }
        break;

    case 'eliminarUsuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/UsuarioController.php';
            $usuarioController = new UsuarioController();
            $usuarioController->eliminar();
        }
        break;

    case 'obtenerUsuario':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once 'controllers/UsuarioController.php';
            $usuarioController = new UsuarioController();
            $usuarioController->obtenerPorId();
        }
        break;

    case 'cambiarEstadoUsuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/UsuarioController.php';
            $usuarioController = new UsuarioController();
            $usuarioController->cambiarEstado();
        }
        break;

    case 'resetearPasswordUsuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/UsuarioController.php';
            $usuarioController = new UsuarioController();
            $usuarioController->resetearPassword();
        }
        break;

    case 'obtenerEstadisticasUsuarios':
        require_once 'controllers/UsuarioController.php';
        $usuarioController = new UsuarioController();
        $usuarioController->obtenerEstadisticas();
        break;

    // === MÓDULO DE ROLES ===
    case 'rolesGestion':
        require_once 'controllers/RolController.php';
        $rolController = new RolController();
        $rolController->index();
        break;

    case 'listarRoles':
        require_once 'controllers/RolController.php';
        $rolController = new RolController();
        $rolController->listar();
        break;

    case 'listarPermisos':
        require_once 'controllers/RolController.php';
        $rolController = new RolController();
        $rolController->listarPermisos();
        break;

    case 'crearRol':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/RolController.php';
            $rolController = new RolController();
            $rolController->crear();
        }
        break;

    case 'actualizarRol':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/RolController.php';
            $rolController = new RolController();
            $rolController->actualizar();
        }
        break;

    case 'eliminarRol':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/RolController.php';
            $rolController = new RolController();
            $rolController->eliminar();
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ordenController->actualizarOrdenDesdeModal();
        }
        break;

    case 'buscarCliente':
        $clienteController->buscarClientes();
        break;

    case 'obtenerTecnicos':
        $loginController->obtenerTecnicos();
        break;

    case 'getUserId':
        header('Content-Type: application/json');
        // Por ahora devolver siempre usuario admin sin verificar sesión
        echo json_encode([
            'success' => true, 
            'usuario_id' => 1,
            'usuario_nombre' => 'Admin'
        ]);
        exit();
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

    case 'obtenerSaldosPendientes':
        require_once 'models/Conexion.php';
        
        try {
            $db = Conexion::getConexion();
            $saldosPendientes = 0;
            
            // Verificar si las tablas existen
            $check_orden_pagos = $db->query("SHOW TABLES LIKE 'orden_pagos'");
            
            if ($check_orden_pagos && $check_orden_pagos->num_rows > 0) {
                // Consulta para obtener órdenes con saldo pendiente
                $query = "SELECT 
                            orden_id,
                            MAX(costo_total) as costo_total,
                            SUM(dinero_recibido) as total_pagado
                          FROM orden_pagos 
                          GROUP BY orden_id
                          HAVING MAX(costo_total) > SUM(dinero_recibido)";
                
                $result = $db->query($query);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $costo_total = floatval($row['costo_total']);
                        $total_pagado = floatval($row['total_pagado']);
                        $saldo = $costo_total - $total_pagado;
                        if ($saldo > 0) {
                            $saldosPendientes += $saldo;
                        }
                    }
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'saldosPendientes' => $saldosPendientes
            ]);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener saldos pendientes: ' . $e->getMessage(),
                'saldosPendientes' => 0
            ]);
        }
        exit();
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

    // === MÓDULO DE INVENTARIO ===
    case 'inventario':
        require_once 'controllers/ProductoController.php';
        $productoController = new ProductoController();
        $productoController->index();
        break;

    case 'listarProductos':
        require_once 'controllers/ProductoController.php';
        $productoController = new ProductoController();
        $productoController->listar();
        break;

    case 'crearProducto':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                require_once 'controllers/ProductoController.php';
                $productoController = new ProductoController();
                $productoController->crear();
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Error en el sistema: ' . $e->getMessage()
                ]);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
        }
        break;

    case 'obtenerProducto':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once 'controllers/ProductoController.php';
            $productoController = new ProductoController();
            $productoController->obtenerPorId();
        }
        break;

    case 'actualizarProducto':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/ProductoController.php';
            $productoController = new ProductoController();
            $productoController->actualizar();
        }
        break;

    case 'eliminarProducto':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/ProductoController.php';
            $productoController = new ProductoController();
            $productoController->eliminar();
        }
        break;

    case 'ajustarStockProducto':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/ProductoController.php';
            $productoController = new ProductoController();
            $productoController->ajustarStock();
        }
        break;

    case 'obtenerEstadisticasInventario':
        require_once 'controllers/ProductoController.php';
        $productoController = new ProductoController();
        $productoController->obtenerEstadisticas();
        break;

    case 'buscarProductos':
        require_once 'controllers/ProductoController.php';
        $productoController = new ProductoController();
        $productoController->buscar();
        break;

    case 'obtenerStockCritico':
        require_once 'controllers/ProductoController.php';
        $productoController = new ProductoController();
        $productoController->obtenerStockCritico();
        break;

    // Mantener compatibilidad con el nombre anterior
    case 'obtenerInventario':
        require_once 'controllers/ProductoController.php';
        $productoController = new ProductoController();
        $productoController->listar();
        break;

    case 'obtenerPagosCaja':
        // Desactivar el buffer de salida y errores de display para evitar HTML extra
        ob_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        
        try {
            require_once 'models/Conexion.php';
            $db = Conexion::getConexion();
            
            // Obtener parámetros de fecha (por defecto día actual)
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d');
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');
            
            // Validar formato de fechas
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_inicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fin)) {
                $fecha_inicio = date('Y-m-d');
                $fecha_fin = date('Y-m-d');
            }
            
            // Inicializar arrays
            $pagos = [];
            $pagos_productos = [];
            
            // Pagos de productos (ventas) con filtro de fecha
            try {
                $query = "SELECT 
                            pp.id,
                            pp.venta_id,
                            pp.dinero_recibido as total,
                            COALESCE(pp.metodo_pago, 'efectivo') as metodo_pago,
                            pp.fecha_pago,
                            COALESCE(v.numero_factura, CONCAT('FAC-', pp.venta_id)) as numero_factura,
                            v.total as total_venta,
                            COALESCE(c.nombre, 'Cliente') as cliente_nombre,
                            COALESCE(c.identificacion, 'N/A') as cliente_identificacion,
                            COALESCE(u.nombre, 'Sistema') as usuario_nombre
                          FROM pagos_productos pp
                          INNER JOIN ventas v ON pp.venta_id = v.id
                          INNER JOIN clientes c ON v.cliente_id = c.id
                          LEFT JOIN usuarios u ON pp.usuario_id = u.id
                          WHERE DATE(pp.fecha_pago) BETWEEN ? AND ?
                          ORDER BY pp.fecha_pago DESC";
                
                $stmt_productos = $db->prepare($query);
                if ($stmt_productos) {
                    $stmt_productos->bind_param('ss', $fecha_inicio, $fecha_fin);
                    $stmt_productos->execute();
                    $result_productos = $stmt_productos->get_result();
                    
                    if ($result_productos) {
                        while ($row = $result_productos->fetch_assoc()) {
                            $pagos_productos[] = $row;
                        }
                    }
                    $stmt_productos->close();
                }
            } catch (Exception $e) {
                // Agregar error a los datos para debug
                $pagos_productos = [];
            }
            
            // Respuesta JSON limpia
            header('Content-Type: application/json; charset=utf-8');
            $response = [
                'success' => true,
                'pagos_ordenes' => $pagos,
                'pagos_productos' => $pagos_productos,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'debug' => [
                    'total_ordenes' => count($pagos),
                    'total_productos' => count($pagos_productos),
                    'fecha_servidor' => date('Y-m-d H:i:s')
                ]
            ];
            
            echo json_encode($response);
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'pagos_ordenes' => [],
                'pagos_productos' => [],
                'debug' => [
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine()
                ]
            ]);
        }
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

    case 'obtenerDetalleOrden':
        // Desactivar errores para esta petición AJAX
        ob_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        
        try {
            require_once 'models/Conexion.php';
            $db = Conexion::getConexion();
            $orden_id = isset($_GET['orden_id']) ? intval($_GET['orden_id']) : 0;
            
            if (!$orden_id) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'ID de orden no proporcionado']);
                exit();
            }
            
            // Verificar qué tablas existen
            $tables_exist = [];
            $check_tables = ['ordenes_reparacion', 'clientes', 'orden_pagos'];
            foreach ($check_tables as $table) {
                $result = $db->query("SHOW TABLES LIKE '$table'");
                $tables_exist[$table] = ($result && $result->num_rows > 0);
            }
            
            if (!$tables_exist['ordenes_reparacion']) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Tabla de órdenes no encontrada']);
                exit();
            }
            
            // Primero obtener la orden básica
            $query_orden = "SELECT 
                                o.id,
                                CONCAT('ORD-', o.id) as numero_orden,
                                COALESCE(o.falla_reportada, 'Sin descripción') as descripcion_problema,
                                COALESCE(o.diagnostico, 'Pendiente') as solucion,
                                o.fecha_ingreso,
                                o.fecha_entrega_estimada as fecha_entrega,
                                COALESCE(o.estado, 'pendiente') as estado,
                                COALESCE(c.nombre, 'Cliente no encontrado') as cliente_nombre,
                                COALESCE(c.identificacion, 'N/A') as cliente_identificacion,
                                COALESCE(c.telefono, '') as cliente_telefono,
                                COALESCE(c.email, '') as cliente_email,
                                COALESCE(c.direccion, '') as cliente_direccion,
                                CONCAT(COALESCE(o.marca, ''), ' ', COALESCE(o.modelo, '')) as equipo_nombre,
                                COALESCE(o.marca, '') as equipo_marca,
                                COALESCE(o.modelo, '') as equipo_modelo,
                                COALESCE(o.imei_serial, '') as equipo_serial
                            FROM ordenes_reparacion o" . 
                            ($tables_exist['clientes'] ? " LEFT JOIN clientes c ON o.cliente_id = c.id" : "") . 
                            " WHERE o.id = ?";
            
            if (!$tables_exist['clientes']) {
                // Si no hay tabla de clientes, usar valores por defecto
                $query_orden = "SELECT 
                                    o.id,
                                    CONCAT('ORD-', o.id) as numero_orden,
                                    COALESCE(o.falla_reportada, 'Sin descripción') as descripcion_problema,
                                    COALESCE(o.diagnostico, 'Pendiente') as solucion,
                                    o.fecha_ingreso,
                                    o.fecha_entrega_estimada as fecha_entrega,
                                    COALESCE(o.estado, 'pendiente') as estado,
                                    'Cliente no disponible' as cliente_nombre,
                                    'N/A' as cliente_identificacion,
                                    '' as cliente_telefono,
                                    '' as cliente_email,
                                    '' as cliente_direccion,
                                    CONCAT(COALESCE(o.marca, ''), ' ', COALESCE(o.modelo, '')) as equipo_nombre,
                                    COALESCE(o.marca, '') as equipo_marca,
                                    COALESCE(o.modelo, '') as equipo_modelo,
                                    COALESCE(o.imei_serial, '') as equipo_serial
                                FROM ordenes_reparacion o
                                WHERE o.id = ?";
            }
            
            $stmt = $db->prepare($query_orden);
            if ($stmt) {
                $stmt->bind_param('i', $orden_id);
                $stmt->execute();
                $orden = $stmt->get_result()->fetch_assoc();
                $stmt->close();
            }
            
            if (!$orden) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Orden no encontrada']);
                exit();
            }
            
            // Obtener el costo total correcto de la tabla orden_pagos
            $costo_total = 0;
            if ($tables_exist['orden_pagos']) {
                try {
                    $query_costo = "SELECT COALESCE(MAX(costo_total), 0) as costo_total 
                                   FROM orden_pagos 
                                   WHERE orden_id = ?";
                    $stmt_costo = $db->prepare($query_costo);
                    if ($stmt_costo) {
                        $stmt_costo->bind_param('i', $orden_id);
                        $stmt_costo->execute();
                        $result_costo = $stmt_costo->get_result();
                        if ($row_costo = $result_costo->fetch_assoc()) {
                            $costo_total = floatval($row_costo['costo_total']);
                        }
                        $stmt_costo->close();
                    }
                } catch (Exception $e) {
                    $costo_total = 0;
                }
            }
            
            // Agregar el costo total a la orden
            $orden['costo_total'] = $costo_total;
            
            // Obtener historial de pagos de la orden
            $pagos = [];
            if ($tables_exist['orden_pagos']) {
                try {
                    $query_pagos = "SELECT 
                                        id,
                                        COALESCE(dinero_recibido, 0) as dinero_recibido,
                                        COALESCE(costo_total, 0) as costo_total,
                                        fecha_pago,
                                        COALESCE(metodo_pago, 'efectivo') as metodo_pago
                                    FROM orden_pagos
                                    WHERE orden_id = ?
                                    ORDER BY fecha_pago DESC";
                    
                    $stmt_pagos = $db->prepare($query_pagos);
                    if ($stmt_pagos) {
                        $stmt_pagos->bind_param('i', $orden_id);
                        $stmt_pagos->execute();
                        $result_pagos = $stmt_pagos->get_result();
                        
                        while ($row = $result_pagos->fetch_assoc()) {
                            $pagos[] = $row;
                        }
                        $stmt_pagos->close();
                    }
                } catch (Exception $e) {
                    // Si hay error en pagos, continuar con array vacío
                    $pagos = [];
                }
            }
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'orden' => $orden,
                'pagos' => $pagos,
                'debug' => [
                    'orden_id' => $orden_id,
                    'tables_exist' => $tables_exist,
                    'total_pagos' => count($pagos)
                ]
            ]);
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener detalle de orden: ' . $e->getMessage(),
                'debug' => [
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'orden_id' => isset($orden_id) ? $orden_id : 'no definido'
                ]
            ]);
        }
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
            // 1. Generar número de factura único
            // Obtener el último número de factura y generar el siguiente
            $result = $db->query("SELECT numero_factura FROM ventas WHERE numero_factura REGEXP '^FAC-[0-9]+$' ORDER BY CAST(SUBSTRING(numero_factura, 5) AS UNSIGNED) DESC LIMIT 1");
            $ultimo_numero = 0;
            if ($result && $row = $result->fetch_assoc()) {
                $ultimo_numero = intval(substr($row['numero_factura'], 4));
            }
            $siguiente_numero = $ultimo_numero + 1;
            $numero_factura = 'FAC-' . str_pad($siguiente_numero, 3, '0', STR_PAD_LEFT);
            
            // Verificar que el número no exista (medida de seguridad adicional)
            $check_stmt = $db->prepare("SELECT COUNT(*) as count FROM ventas WHERE numero_factura = ?");
            $check_stmt->bind_param('s', $numero_factura);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result()->fetch_assoc();
            $check_stmt->close();
            
            // Si ya existe, generar uno nuevo incrementando hasta encontrar uno disponible
            while ($check_result['count'] > 0) {
                $siguiente_numero++;
                $numero_factura = 'FAC-' . str_pad($siguiente_numero, 3, '0', STR_PAD_LEFT);
                $check_stmt = $db->prepare("SELECT COUNT(*) as count FROM ventas WHERE numero_factura = ?");
                $check_stmt->bind_param('s', $numero_factura);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result()->fetch_assoc();
                $check_stmt->close();
            }
            
            // 2. Insertar la venta principal
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

    case 'obtenerVentasSemana':
        require_once 'models/Conexion.php';
        
        try {
            $db = Conexion::getConexion();
            $ventas = [];
            
            // Obtener ventas de los últimos 7 días
            $query = "SELECT 
                        DATE(fecha_pago) as fecha,
                        SUM(dinero_recibido) as total
                      FROM pagos_productos 
                      WHERE fecha_pago >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                      GROUP BY DATE(fecha_pago)
                      ORDER BY fecha";
            
            $result = $db->query($query);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $ventas[] = $row;
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'ventas' => $ventas
            ]);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener ventas: ' . $e->getMessage(),
                'ventas' => []
            ]);
        }
        exit();
        break;

    case 'obtenerProductividad':
        require_once 'models/Conexion.php';
        
        try {
            $db = Conexion::getConexion();
            $ordenesHoy = 0;
            $tiempoPromedio = '0d';
            
            // Órdenes creadas hoy
            $check_ordenes = $db->query("SHOW TABLES LIKE 'ordenes_reparacion'");
            if ($check_ordenes && $check_ordenes->num_rows > 0) {
                $result = $db->query("SELECT COUNT(*) as total FROM ordenes_reparacion WHERE DATE(fecha_ingreso) = CURDATE()");
                if ($result) {
                    $row = $result->fetch_assoc();
                    $ordenesHoy = $row['total'];
                }
                
                // Tiempo promedio de reparación (órdenes entregadas)
                $result = $db->query("SELECT AVG(DATEDIFF(fecha_entrega_estimada, fecha_ingreso)) as promedio 
                                     FROM ordenes_reparacion 
                                     WHERE estado = 'entregado' 
                                     AND fecha_entrega_estimada IS NOT NULL 
                                     AND fecha_ingreso IS NOT NULL");
                if ($result) {
                    $row = $result->fetch_assoc();
                    $dias = round($row['promedio'] ?? 0);
                    $tiempoPromedio = $dias . 'd';
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'ordenesHoy' => $ordenesHoy,
                'tiempoPromedio' => $tiempoPromedio
            ]);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener productividad: ' . $e->getMessage(),
                'ordenesHoy' => 0,
                'tiempoPromedio' => '0d'
            ]);
        }
        exit();
        break;

    case 'obtenerTopProductos':
        require_once 'models/Conexion.php';
        
        try {
            $db = Conexion::getConexion();
            $productos = [];
            
            // Top productos más vendidos
            $check_ventas = $db->query("SHOW TABLES LIKE 'detalle_ventas'");
            $check_productos = $db->query("SHOW TABLES LIKE 'productos'");
            
            if ($check_ventas && $check_ventas->num_rows > 0 && 
                $check_productos && $check_productos->num_rows > 0) {
                
                $query = "SELECT 
                            p.nombre,
                            SUM(dv.cantidad) as cantidad_vendida,
                            SUM(dv.subtotal) as total_ventas
                          FROM detalle_ventas dv
                          INNER JOIN productos p ON dv.producto_id = p.id
                          INNER JOIN ventas v ON dv.venta_id = v.id
                          WHERE v.fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                          GROUP BY p.id, p.nombre
                          ORDER BY cantidad_vendida DESC, total_ventas DESC
                          LIMIT 5";
                
                $result = $db->query($query);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $productos[] = $row;
                    }
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'productos' => $productos
            ]);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener productos: ' . $e->getMessage(),
                'productos' => []
            ]);
        }
        exit();
        break;

    case 'obtenerPagosCaja':
        // Desactivar el buffer de salida y errores de display para evitar HTML extra
        ob_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        
        try {
            require_once 'models/Conexion.php';
            $db = Conexion::getConexion();
            
            // Obtener parámetros de fecha (por defecto día actual)
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d');
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');
            
            // Validar formato de fechas
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_inicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fin)) {
                $fecha_inicio = date('Y-m-d');
                $fecha_fin = date('Y-m-d');
            }
            
            // Consultar pagos en el rango de fechas
            $query = "SELECT 
                            pp.id,
                            pp.orden_id,
                            COALESCE(pp.dinero_recibido, 0) as dinero_recibido,
                            COALESCE(pp.costo_total, 0) as costo_total,
                            pp.fecha_pago,
                            COALESCE(pp.metodo_pago, 'efectivo') as metodo_pago,
                            COALESCE(v.numero_factura, CONCAT('FAC-', pp.venta_id)) as numero_factura,
                            v.total as total_venta,
                            COALESCE(c.nombre, 'Cliente') as cliente_nombre,
                            COALESCE(c.identificacion, 'N/A') as cliente_identificacion,
                            COALESCE(u.nombre, 'Sistema') as usuario_nombre
                          FROM pagos_productos pp
                          INNER JOIN ventas v ON pp.venta_id = v.id
                          INNER JOIN clientes c ON v.cliente_id = c.id
                          LEFT JOIN usuarios u ON pp.usuario_id = u.id
                          WHERE DATE(pp.fecha_pago) BETWEEN ? AND ?
                          ORDER BY pp.fecha_pago DESC";
                
            $stmt = $db->prepare($query);
            if ($stmt) {
                $stmt->bind_param('ss', $fecha_inicio, $fecha_fin);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $pagos = [];
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $pagos[] = $row;
                    }
                }
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'pagos' => $pagos,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin
                ]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
        break;

    default:
        // REDIRECCIÓN AL LOGIN DESHABILITADA TEMPORALMENTE
        // header('Location: index.php?action=login');
        // exit();
        
        // Por ahora mostrar dashboard por defecto
        include 'views/dashboard.php';
        break;
}
?>
