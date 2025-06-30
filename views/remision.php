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
            <?php
            // --- Generar mensaje de remisión para WhatsApp con encabezado y presentación ---
            $mensajeRemision = "*DIAZTECNOLOGÍA*\n";
            $mensajeRemision .= "Transversal 12a #41b-31\nTel: +57 3203200992\nwww.diaztecnologia.com\n";
            $mensajeRemision .= "-----------------------------\n";
            $mensajeRemision .= "*Remisión de Servicio*\n";
            $mensajeRemision .= "N°: {$orden['id']}\n";
            $mensajeRemision .= "Fecha: " . date('d/m/Y') . "\n";
            $mensajeRemision .= "-----------------------------\n";
            $mensajeRemision .= "¡Hola {$orden['cliente_nombre']}!\n";
            $mensajeRemision .= "Hemos recibido su equipo para revisión y/o reparación. A continuación el detalle de su remisión:\n";
            $mensajeRemision .= "-----------------------------\n";
            $mensajeRemision .= "*Datos del Cliente*\n";
            $mensajeRemision .= "Nombre: {$orden['cliente_nombre']}\n";
            $mensajeRemision .= "Identificación: {$orden['cliente_identificacion']}\n";
            $mensajeRemision .= "Teléfono: {$orden['cliente_telefono']}\n";
            $mensajeRemision .= "Email: {$orden['cliente_email']}\n";
            $mensajeRemision .= "-----------------------------\n";
            $mensajeRemision .= "*Equipo*\n";
            $mensajeRemision .= "Marca: {$orden['marca']}\n";
            $mensajeRemision .= "Modelo: {$orden['modelo']}\n";
            $mensajeRemision .= "Falla Reportada: {$orden['falla_reportada']}\n";
            $mensajeRemision .= "Diagnóstico: {$orden['diagnostico']}\n";
            $mensajeRemision .= "Estado: {$orden['estado']}\n";
            $mensajeRemision .= "Fecha Ingreso: {$orden['fecha_ingreso']}\n";
            $mensajeRemision .= "Fecha Estimada Entrega: {$orden['fecha_entrega_estimada']}\n";
            if (!empty($pago)) {
                $mensajeRemision .= "-----------------------------\n";
                $mensajeRemision .= "*Datos de Pago*\n";
                $mensajeRemision .= "Método de Pago: " . ucfirst($pago['metodo_pago']) . "\n";
                $mensajeRemision .= "Fecha de Pago: {$pago['fecha_pago']}\n";
                $mensajeRemision .= "Costo Total: $" . number_format($pago['costo_total'], 0, ',', '.') . "\n";
                $mensajeRemision .= "Descripción Repuestos: {$pago['descripcion_repuestos']}\n";
                $mensajeRemision .= "Abono: $" . number_format($pago['costo_total'] - $pago['saldo'], 0, ',', '.') . "\n";
                $mensajeRemision .= "Saldo: $" . number_format($pago['saldo'], 0, ',', '.') . "\n";
            }
            $mensajeRemision .= "-----------------------------\n";
            $mensajeRemision .= "*Términos y Condiciones*\n";
            $mensajeRemision .= "No se otorga garantía por pantallas rotas, equipos mojados o golpeados. Para los cambios de pantalla, la entrega se realiza de dos maneras: si la pantalla se instala en el local y es probada por el cliente, no tiene garantía posterior; es responsabilidad del cliente dar un buen uso al equipo. Recuerde que una pantalla es un componente muy frágil: incluso una presión excesiva, aunque no la rompa, puede dañarla internamente. Ante cualquier inquietud, comuníquese a cualquiera de nuestros números de contacto. Gracias por confiar en DIAZTECNOLOGÍA.";
            $mensajeRemision .= "-----------------------------\n";
            $mensajeRemision .= "¡Gracias por confiar en nosotros!";
            $mensajeRemision = rawurlencode($mensajeRemision);
            $urlRemision = rawurlencode('https://admin.diaztecnologia.com/index.php?action=generarRemision&id=' . $orden['id']);
            $telefono = ltrim($orden['cliente_telefono'], '0');
            ?>
            <a href="https://wa.me/57<?php echo $telefono; ?>?text=Hola%20<?php echo urlencode($orden['cliente_nombre']); ?>,%20aquí%20está%20la%20remisión%20de%20su%20servicio:%20<?php echo $urlRemision; ?>" target="_blank" class="btn btn-success" style="padding: 10px 20px; background-color: #25D366; color: white; border: none; border-radius: 5px; text-decoration: none; font-size: 16px;">Enviar por WhatsApp (URL)</a>
            <a href="https://wa.me/57<?php echo $telefono; ?>?text=<?php echo $mensajeRemision; ?>" target="_blank" class="btn btn-dark" style="padding: 10px 20px; background-color: #222; color: white; border: none; border-radius: 5px; text-decoration: none; font-size: 16px;">Enviar por WhatsApp (Factura Detallada)</a>
            <button onclick="enviarPorEmail()" class="btn btn-primary" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px;">Enviar por Email</button>
        </div>

        <div class="terminos-condiciones" style="margin-top:30px; font-size:13px; color:#555; border-top:1px solid #ccc; padding-top:15px;">
            <strong>Términos y Condiciones:</strong><br>
            No se otorga garantía por pantallas rotas, equipos mojados o golpeados. Para los cambios de pantalla, la entrega se realiza de dos maneras: si la pantalla se instala en el local y es probada por el cliente, no tiene garantía posterior; es responsabilidad del cliente dar un buen uso al equipo. Recuerde que una pantalla es un componente muy frágil: incluso una presión excesiva, aunque no la rompa, puede dañarla internamente. Ante cualquier inquietud, comuníquese a cualquiera de nuestros números de contacto. Gracias por confiar en DIAZTECNOLOGÍA.
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