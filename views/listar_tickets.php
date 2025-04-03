<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tickets</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Lista de Tickets</h1>
    <a href="index.php?controller=ticket&action=crear">Crear Ticket</a>
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Ticket creado correctamente.</p>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Técnico</th>
                <th>Dispositivo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>IMEI/Serial</th>
                <th>Contraseña</th>
                <th>Diagnóstico</th>
                <th>Proceso</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Valor Reparación</th>
                <th>Costo Repuestos</th>
                <th>Creado En</th>
                <th>Actualizado En</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tickets)): ?>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo $ticket['id']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['cliente_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['tecnico_nombre'] ?? 'Sin técnico'); ?></td>
                        <td><?php echo htmlspecialchars($ticket['tipo_dispositivo']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['marca']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['modelo']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['imei_serial']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['contraseña']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['diagnostico']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['proceso']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['estado']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['prioridad']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['valor_reparacion']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['costo_repuestos']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['creado_en']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['actualizado_en']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="16" style="text-align: center;">No hay tickets registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>