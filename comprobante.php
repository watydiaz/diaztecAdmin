<?php
// comprobante.php
// Muestra comprobante de venta o de orden de servicio según el parámetro recibido

// Configuración de la empresa
$empresa = [
    'nombre' => 'Diaztecnologia',
    'nit' => '1073679337-8',
    'direccion' => 'Transversal 12a 41b 31, Soacha - Ocales',
    'celular' => '3202975605 - 3203200992',
    'email' => 'karol.jesusdiaz@gmail.com',
    'web' => 'diaztecnologia.com',
    'logo' => 'https://diaztecnologia.com/img/logo.png'
];

require_once 'models/Conexion.php';
$db = Conexion::getConexion();

$venta_id = isset($_GET['venta_id']) ? intval($_GET['venta_id']) : 0;
$orden_id = isset($_GET['orden_id']) ? intval($_GET['orden_id']) : 0;

$tipo = '';
$datos = [];
$detalles = [];
$error = '';

if ($venta_id) {
    $tipo = 'venta';
    $stmt = $db->prepare("SELECT v.id, v.numero_factura, v.total, v.metodo_pago, v.fecha_venta, c.nombre as cliente_nombre, c.identificacion as cliente_identificacion, c.telefono as cliente_telefono, c.email as cliente_email, u.nombre as usuario_nombre FROM ventas v INNER JOIN clientes c ON v.cliente_id = c.id LEFT JOIN usuarios u ON v.usuario_id = u.id WHERE v.id = ?");
    $stmt->bind_param('i', $venta_id);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($datos) {
        $stmt = $db->prepare("SELECT dv.producto_id, dv.cantidad, dv.precio_unitario, dv.subtotal, p.nombre as producto_nombre FROM detalle_ventas dv INNER JOIN productos p ON dv.producto_id = p.id WHERE dv.venta_id = ? ORDER BY p.nombre ASC");
        $stmt->bind_param('i', $venta_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $detalles[] = $row;
        }
        $stmt->close();
    } else {
        $error = 'Venta no encontrada.';
    }
} elseif ($orden_id) {
    $tipo = 'orden';
    $stmt = $db->prepare("SELECT o.id, CONCAT('ORD-', o.id) as numero_orden, COALESCE(o.falla_reportada, 'Sin descripción') as descripcion_problema, COALESCE(o.diagnostico, 'Pendiente') as solucion, o.fecha_ingreso, o.fecha_entrega_estimada as fecha_entrega, COALESCE(o.estado, 'pendiente') as estado, COALESCE(c.nombre, 'Cliente no encontrado') as cliente_nombre, COALESCE(c.identificacion, 'N/A') as cliente_identificacion, COALESCE(c.telefono, '') as cliente_telefono, COALESCE(c.email, '') as cliente_email, COALESCE(c.direccion, '') as cliente_direccion, CONCAT(COALESCE(o.marca, ''), ' ', COALESCE(o.modelo, '')) as equipo_nombre, COALESCE(o.marca, '') as equipo_marca, COALESCE(o.modelo, '') as equipo_modelo, COALESCE(o.imei_serial, '') as equipo_serial FROM ordenes_reparacion o LEFT JOIN clientes c ON o.cliente_id = c.id WHERE o.id = ?");
    $stmt->bind_param('i', $orden_id);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($datos) {
        $stmt = $db->prepare("SELECT id, COALESCE(dinero_recibido, 0) as dinero_recibido, COALESCE(costo_total, 0) as costo_total, fecha_pago, COALESCE(metodo_pago, 'efectivo') as metodo_pago FROM orden_pagos WHERE orden_id = ? ORDER BY fecha_pago DESC");
        $stmt->bind_param('i', $orden_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $detalles[] = $row;
        }
        $stmt->close();
    } else {
        $error = 'Orden de servicio no encontrada.';
    }
} else {
    $error = 'No se especificó comprobante.';
}

function formatearFecha($fecha) {
    if (!$fecha) return '-';
    return date('d/m/Y H:i', strtotime($fecha));
}
function formatearMoneda($valor) {
    return '$' . number_format($valor, 0, ',', '.');
}
// Función para formatear uno o varios teléfonos como enlaces a WhatsApp
function formatearTelefonosWhatsapp($numeros) {
    if (!$numeros) return '-';
    // Extraer todos los grupos de 10 dígitos seguidos
    preg_match_all('/\d{10}/', $numeros, $matches);
    $enlaces = [];
    foreach ($matches[0] as $num) {
        $tel = '57' . $num;
        $enlaces[] = "<a href='https://wa.me/$tel' target='_blank' style='text-decoration:none;color:#22c55e;font-weight:bold;margin-right:6px;'>$num <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-whatsapp' viewBox='0 0 16 16'><path d='M13.601 2.326A7.956 7.956 0 0 0 8.004.001C3.584 0-.001 3.584-.001 8.004c0 1.41.368 2.789 1.065 4.004L.06 15.534a.5.5 0 0 0 .633.633l3.526-1.124A7.96 7.96 0 0 0 8.004 16c4.42 0 8.005-3.584 8.005-7.996a7.96 7.96 0 0 0-2.408-5.678zM8.004 15a6.96 6.96 0 0 1-3.563-.98.5.5 0 0 0-.377-.044l-3.01.96.96-3.01a.5.5 0 0 0-.044-.377A6.96 6.96 0 0 1 1.003 8.004c0-3.866 3.136-7.003 7.001-7.003 3.866 0 7.003 3.137 7.003 7.003 0 3.865-3.137 7.001-7.003 7.001z'/><path d='M11.603 9.233c-.2-.1-1.176-.58-1.36-.646-.183-.067-.317-.1-.45.1-.133.2-.516.646-.633.78-.117.133-.233.15-.433.05-.2-.1-.84-.31-1.6-.99-.592-.527-.992-1.177-1.11-1.377-.117-.2-.012-.308.088-.408.09-.09.2-.233.3-.35.1-.117.133-.2.2-.333.067-.133.033-.25-.017-.35-.05-.1-.45-1.083-.617-1.483-.163-.392-.33-.338-.45-.344l-.383-.007a.37.37 0 0 0-.267.125c-.092.1-.35.342-.35.833 0 .492.358.967.408 1.033.05.067.7 1.067 1.7 1.517 1 .45 1 .3 1.183.283.183-.017.583-.233.667-.458.083-.225.083-.417.058-.458z'/></svg></a>";
    }
    return $enlaces ? implode(' / ', $enlaces) : '-';
}
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante | <?php echo $empresa['nombre']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%); }
        .comprobante-box { background: #fff; border-radius: 18px; box-shadow: 0 4px 24px #0002; padding: 36px 28px; max-width: 750px; margin: 40px auto; border: 2px solid #6366f1; }
        .logo { max-width: 120px; border-radius: 12px; box-shadow: 0 2px 8px #6366f133; }
        .empresa-titulo { color: #4338ca; font-weight: bold; letter-spacing: 1px; }
        .empresa-info div { font-size: 15px; color: #555; }
        .table th { background: #6366f1; color: #fff; border: none; }
        .table-bordered td, .table-bordered th { border: 1.5px solid #6366f1; }
        .badge-total { background: #22c55e; font-size: 1.1rem; padding: 8px 18px; border-radius: 12px; }
        .badge-pendiente { background: #f59e42; font-size: 1.1rem; padding: 8px 18px; border-radius: 12px; }
        .badge-pagado { background: #2563eb; font-size: 1.1rem; padding: 8px 18px; border-radius: 12px; }
        .detalle-titulo { color: #6366f1; font-weight: 600; margin-top: 18px; }
        .alert-danger { border-radius: 10px; }
        @media (max-width: 600px) {
            .comprobante-box { padding: 12px 2vw; }
            .logo { max-width: 80px; }
        }
    </style>
</head>
<body>
<div class="comprobante-box">
    <div class="row mb-3 align-items-center">
        <div class="col-md-3 text-center">
            <img src="<?php echo $empresa['logo']; ?>" alt="Logo" class="logo mb-2">
        </div>
        <div class="col-md-9 empresa-info">
            <h3 class="mb-0 empresa-titulo"><?php echo $empresa['nombre']; ?></h3>
            <div>NIT: <?php echo $empresa['nit']; ?></div>
            <div><?php echo $empresa['direccion']; ?></div>
            <div>Cel: <?php echo formatearTelefonosWhatsapp($empresa['celular']); ?></div>
            <div>Email: <?php echo $empresa['email']; ?></div>
            <div>Web: <?php echo $empresa['web']; ?></div>
        </div>
    </div>
    <hr>
    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php elseif ($tipo === 'venta'): ?>
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="detalle-titulo">Datos del Cliente</span><br>
                <strong><?php echo $datos['cliente_nombre']; ?></strong><br>
                <small>ID: <?php echo $datos['cliente_identificacion']; ?></small><br>
                <small>Tel: <?php echo formatearTelefonosWhatsapp($datos['cliente_telefono']); ?></small><br>
                <small>Email: <?php echo $datos['cliente_email'] ?: '-'; ?></small>
            </div>
            <div class="col-md-6 text-end">
                <span class="detalle-titulo">Factura de Venta</span><br>
                <strong>Factura:</strong> <?php echo $datos['numero_factura']; ?><br>
                <strong>Fecha:</strong> <?php echo formatearFecha($datos['fecha_venta']); ?><br>
                <strong>Método de pago:</strong> <span class="badge bg-primary"><?php echo ucfirst($datos['metodo_pago']); ?></span><br>
                <strong>Registrado por:</strong> <?php echo $datos['usuario_nombre'] ?: '-'; ?>
            </div>
        </div>
        <div class="detalle-titulo">Detalle de Productos</div>
        <div class="table-responsive">
            <table class="table table-bordered mt-2">
                <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                <tbody>
                <?php foreach ($detalles as $d): ?>
                    <tr>
                        <td><?php echo $d['producto_nombre']; ?></td>
                        <td><?php echo $d['cantidad']; ?></td>
                        <td><?php echo formatearMoneda($d['precio_unitario']); ?></td>
                        <td><?php echo formatearMoneda($d['subtotal']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-end mt-3"><span class="badge badge-total">Total: <?php echo formatearMoneda($datos['total']); ?></span></div>
    <?php elseif ($tipo === 'orden'): ?>
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="detalle-titulo">Datos del Cliente</span><br>
                <strong><?php echo $datos['cliente_nombre']; ?></strong><br>
                <small>ID: <?php echo $datos['cliente_identificacion']; ?></small><br>
                <small>Tel: <?php echo $datos['cliente_telefono'] ?: '-'; ?></small><br>
                <small>Email: <?php echo $datos['cliente_email'] ?: '-'; ?></small><br>
                <small>Dirección: <?php echo $datos['cliente_direccion'] ?: '-'; ?></small>
            </div>
            <div class="col-md-6 text-end">
                <span class="detalle-titulo">Orden de Servicio</span><br>
                <strong>Orden:</strong> <?php echo $datos['numero_orden']; ?><br>
                <strong>Equipo:</strong> <?php echo $datos['equipo_nombre']; ?><br>
                <strong>Estado:</strong> <span class="badge bg-info text-dark"><?php echo ucfirst($datos['estado']); ?></span><br>
                <strong>Ingreso:</strong> <?php echo formatearFecha($datos['fecha_ingreso']); ?><br>
                <strong>Entrega estimada:</strong> <?php echo formatearFecha($datos['fecha_entrega']); ?>
            </div>
        </div>
        <div class="mb-2"><span class="detalle-titulo">Problema reportado:</span> <?php echo $datos['descripcion_problema']; ?></div>
        <div class="mb-2"><span class="detalle-titulo">Diagnóstico / Solución:</span> <?php echo $datos['solucion']; ?></div>
        <div class="mb-2"><span class="detalle-titulo">Pagos realizados:</span></div>
        <div class="table-responsive">
            <table class="table table-bordered mt-2">
                <thead><tr><th>Fecha</th><th>Monto</th><th>Método</th></tr></thead>
                <tbody>
                <?php foreach ($detalles as $p): ?>
                    <tr>
                        <td><?php echo formatearFecha($p['fecha_pago']); ?></td>
                        <td><?php echo formatearMoneda($p['dinero_recibido']); ?></td>
                        <td><span class="badge bg-primary"><?php echo ucfirst($p['metodo_pago']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-end mt-3"><span class="badge badge-pagado">Total pagado: <?php echo formatearMoneda(array_sum(array_column($detalles, 'dinero_recibido'))); ?></span></div>
        <div class="text-end mt-2"><span class="badge badge-pendiente">Saldo pendiente: <?php echo formatearMoneda($datos['costo_total'] - array_sum(array_column($detalles, 'dinero_recibido'))); ?></span></div>
    <?php endif; ?>
    <?php if ($tipo === 'venta'): ?>
        <div class="mt-4 p-3 bg-light border rounded" style="font-size: 0.97em; color: #444;">
            <strong>Términos y condiciones de venta:</strong><br>
            <ul style="margin-bottom:0; padding-left: 18px;">
                <li>La garantía de los productos aplica únicamente por defectos de fábrica y no cubre daños por mal uso, golpes, humedad o manipulación indebida.</li>
                <li>No se aceptan devoluciones después de 3 días de la compra.</li>
                <li>Conserve este comprobante para cualquier reclamo.</li>
                <li>La compra implica la aceptación de estos términos.</li>
            </ul>
        </div>
    <?php elseif ($tipo === 'orden'): ?>
        <div class="mt-4 p-3 bg-light border rounded" style="font-size: 0.97em; color: #444;">
            <strong>Condiciones de garantía y servicio:</strong><br>
            <ul style="margin-bottom:0; padding-left: 18px;">
                <li>La garantía del servicio cubre únicamente la reparación realizada y no aplica si el equipo presenta daños adicionales, manipulación por terceros, humedad o golpes posteriores a la entrega.</li>
                <li>El plazo de garantía es de 30 días a partir de la fecha de entrega.</li>
                <li>Conserve este comprobante para hacer válida la garantía.</li>
            </ul>
        </div>
    <?php endif; ?>
</div>
</body>
</html> 