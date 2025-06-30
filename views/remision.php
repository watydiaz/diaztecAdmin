<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remisión</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
            .remision-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: #f9f9f9;
                font-family: Arial, sans-serif;
                overflow-x: auto; /* Agregado para evitar desbordamiento horizontal */
            }

            .remision-table {
                width: 100%;
                border-collapse: collapse;
            }

            .remision-table th, .remision-table td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }

            .remision-table th {
                background-color: #f4f4f4;
            }

            @media (max-width: 600px) {
                .remision-header h1 {
                    font-size: 20px;
                }

                .remision-header p {
                    font-size: 12px;
                }

                .remision-table th, .remision-table td {
                    font-size: 12px;
                }

                .imagenes img {
                    max-width: 80px;
                }
            }
        </style>
</head>
<body>
    <div class="remision-container">
        <div class="remision-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <img src="https://diaztecnologia.com/img/logo.png" alt="Logo de la Empresa" style="max-width: 100px;">
                <div style="text-align: right;">
                    <h1>Remisión de Servicio</h1>
                    <p><strong>Dirección:</strong> Transversal 12a 41b 31</p>
                    <p><strong>Teléfono:</strong> +57 3203200992</p>
                    <p><strong>Pagina Web:</strong> <?php echo ('<a href="www.diaztecnologia.com">www.diaztecnologia.com</a>'); ?></p>
                    <p><strong>Fecha:</strong> <?php echo date('d/m/Y'); ?></p>
                    
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <p style="font-size: 18px; font-weight: bold;">ID de la Orden: <?php echo $orden['id']; ?></p>
            </div>
        </div>

        <table class="remision-table">
            <tr>
                <th colspan="4">Datos del Cliente</th>
            </tr>
            <tr>
                <td><strong>Nombre:</strong></td>
                <td><?php echo $orden['cliente_nombre']; ?></td>
                <td><strong>Identificación:</strong></td>
                <td><?php echo $orden['cliente_identificacion']; ?></td>
            </tr>
            <tr>
                <td><strong>Teléfono:</strong></td>
                <td><?php echo $orden['cliente_telefono']; ?></td>
                <td><strong>Email:</strong></td>
                <td><?php echo $orden['cliente_email']; ?></td>
            </tr>
            <tr>
                <th colspan="4">Detalles del Equipo</th>
            </tr>
            <tr>
                <td><strong>Marca:</strong></td>
                <td><?php echo $orden['marca']; ?></td>
                <td><strong>Modelo:</strong></td>
                <td><?php echo $orden['modelo']; ?></td>
            </tr>
            <tr>
                <td><strong>Falla Reportada:</strong></td>
                <td colspan="3"><?php echo $orden['falla_reportada']; ?></td>
            </tr>
            <tr>
                <td><strong>Diagnóstico:</strong></td>
                <td colspan="3"><?php echo $orden['diagnostico']; ?></td>
            </tr>
            <tr>
                <td><strong>Estado:</strong></td>
                <td><?php echo $orden['estado']; ?></td>
                <td><strong>Fecha de Ingreso:</strong></td>
                <td><?php echo $orden['fecha_ingreso']; ?></td>
            </tr>
            <tr>
                <td><strong>Fecha Estimada de Entrega:</strong></td>
                <td colspan="3"><?php echo $orden['fecha_entrega_estimada']; ?></td>
            </tr>
        </table>

        <!-- DATOS DE PAGO -->
        <table class="remision-table" style="margin-top:20px;">
            <tr>
                <th colspan="4">Datos de Pago</th>
            </tr>
            <?php 
            // Obtener el último pago si existe
            if (function_exists('obtenerPagosPorOrden')) {
                $pagos = obtenerPagosPorOrden($orden['id']);
                $pago = !empty($pagos) ? $pagos[0] : null;
            } else if (isset($pago)) {
                // Si ya viene de un controlador
            } else {
                // --- Obtener el último pago desde el modelo si no viene del controlador ---
                if (!isset($pago)) {
                    require_once __DIR__ . '/../models/Conexion.php';
                    require_once __DIR__ . '/../models/OrdenPagoModel.php';
                    $db = Conexion::getConexion();
                    $pagoModel = new OrdenPagoModel($db);
                    $pagos = $pagoModel->obtenerPagosPorOrden($orden['id']);
                    $pago = !empty($pagos) ? $pagos[0] : null;
                }
            }
            ?>
            <?php if (!empty($pago)): ?>
            <tr>
                <td><strong>Método de Pago:</strong></td>
                <td><?php echo ucfirst($pago['metodo_pago']); ?></td>
                <td><strong>Fecha de Pago:</strong></td>
                <td><?php echo $pago['fecha_pago']; ?></td>
            </tr>
            <tr>
                <td><strong style="font-size:1.1em;color:#222;">Costo Total:</strong></td>
                <td colspan="3"><span style="font-weight:bold;font-size:1.3em;color:#007bff;">$<?php echo number_format($pago['costo_total'], 0, ',', '.'); ?></span></td>
            </tr>
            <tr>
                <td><strong>Descripción Repuestos:</strong></td>
                <td colspan="3"><?php echo $pago['descripcion_repuestos']; ?></td>
            </tr>
            <tr>
                <td><strong style="font-size:1.1em;color:#222;">Abono:</strong></td>
                <td><span style="font-weight:bold;font-size:1.2em;color:#28a745;">$<?php echo number_format($pago['costo_total'] - $pago['saldo'], 0, ',', '.'); ?></span></td>
                <td><strong style="font-size:1.1em;color:#222;">Saldo:</strong></td>
                <td><span style="font-weight:bold;font-size:1.2em;color:#dc3545;">$<?php echo number_format($pago['saldo'], 0, ',', '.'); ?></span></td>
            </tr>
            <?php else: ?>
            <tr><td colspan="4"><em>No hay datos de pago registrados para esta orden.</em></td></tr>
            <?php endif; ?>
        </table>

        <div class="imagenes">
            <p><strong>Imágenes:</strong></p>
            <?php 
            if (!empty($orden['imagen_url'])) {
                foreach (explode(',', $orden['imagen_url']) as $imagen): ?>
                    <img src="<?php echo $imagen; ?>" alt="Imagen de la orden">
                <?php endforeach; 
            } else {
                echo '<p>No hay imágenes disponibles.</p>';
            }
            ?>
        </div>

        <div class="acciones" style="margin-top: 20px; text-align: center; display: flex; flex-direction: column; gap: 10px; align-items: center;">
            <a href="https://wa.me/57<?php echo ltrim($orden['cliente_telefono'], '0'); ?>?text=Hola%20<?php echo urlencode($orden['cliente_nombre']); ?>,%20aquí%20está%20la%20remisión%20de%20su%20servicio:%20<?php echo urlencode('https://admin.diaztecnologia.com/index.php?action=generarRemision&id=' . $orden['id']); ?>" target="_blank" class="btn btn-success" style="padding: 10px 20px; background-color: #25D366; color: white; border: none; border-radius: 5px; text-decoration: none; font-size: 16px;">Enviar por WhatsApp</a>
            <button onclick="enviarPorEmail()" class="btn btn-primary" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px;">Enviar por Email</button>
        </div>

        <script>
            function enviarPorEmail() {
                const email = prompt('Ingrese el correo electrónico del destinatario:');
                if (email) {
                    const subject = 'Remisión de Servicio';
                    const body = `Hola ${encodeURIComponent('<?php echo $orden['cliente_nombre']; ?>')}, aquí está la remisión de su servicio: ${encodeURIComponent('http://example.com/index.php?action=generarRemision&id=' + <?php echo $orden['id']; ?>)}`;
                    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
                }
            }
        </script>
    </div>
</body>
</html>