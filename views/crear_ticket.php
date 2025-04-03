<?php require_once 'header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ticket</title>
</head>
<body>
    <h1>Crear Ticket</h1>
    <form method="POST" action="index.php?controller=ticket&action=crear">
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" id="cliente_id" required>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['nombre']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="tecnico_id">Técnico:</label>
        <select name="tecnico_id" id="tecnico_id">
            <option value="">Sin técnico asignado</option>
            <?php foreach ($tecnicos as $tecnico): ?>
                <option value="<?php echo $tecnico['id']; ?>"><?php echo htmlspecialchars($tecnico['nombre']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="tipo_dispositivo">Tipo de Dispositivo:</label>
        <input type="text" name="tipo_dispositivo" id="tipo_dispositivo" required>
        <br>
        <label for="marca">Marca:</label>
        <input type="text" name="marca" id="marca" required>
        <br>
        <label for="modelo">Modelo:</label>
        <input type="text" name="modelo" id="modelo" required>
        <br>
        <label for="imei_serial">IMEI/Serial:</label>
        <input type="text" name="imei_serial" id="imei_serial">
        <br>
        <label for="contraseña">Contraseña del dispositivo:</label>
        <input type="text" name="contraseña" id="contraseña">
        <br>
        <label for="diagnostico">Diagnóstico:</label>
        <textarea name="diagnostico" id="diagnostico"></textarea>
        <br>
        <label for="prioridad">Prioridad:</label>
        <select name="prioridad" id="prioridad">
            <option value="baja">Baja</option>
            <option value="media">Media</option>
            <option value="alta">Alta</option>
        </select>
        <br>
        <label for="proceso">Proceso:</label>
        <textarea name="proceso" id="proceso"></textarea>
        <br>
        <label for="estado">Estado:</label>
        <select name="estado" id="estado">
            <option value="pendiente">Pendiente</option>
            <option value="en_proceso">En Proceso</option>
            <option value="finalizado">Finalizado</option>
        </select>
        <br>
        <label for="valor_reparacion">Valor de Reparación:</label>
        <input type="number" step="0.01" name="valor_reparacion" id="valor_reparacion">
        <br>
        <label for="costo_repuestos">Costo de Repuestos:</label>
        <input type="number" step="0.01" name="costo_repuestos" id="costo_repuestos">
        <br>
        <button type="submit">Crear Ticket</button>
    </form>
</body>
</html>
<?php require_once 'footer.php'; ?>