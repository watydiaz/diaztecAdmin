<?php
require_once 'controllers/OrdenController.php';
$ordenController = new OrdenController();
$ordenes = $ordenController->listarOrdenes();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'cliente_id' => $_POST['cliente_id'],
        'usuario_tecnico_id' => $_POST['usuario_tecnico_id'],
        'marca' => $_POST['marca'],
        'modelo' => $_POST['modelo'],
        'imei_serial' => $_POST['imei_serial'],
        'falla_reportada' => $_POST['falla_reportada'],
        'diagnostico' => $_POST['diagnostico'],
        'estado' => $_POST['estado'],
        'prioridad' => $_POST['prioridad'],
        'contraseña_equipo' => $_POST['contraseña_equipo'],
        'imagen_url' => $_POST['imagen_url'],
        'fecha_ingreso' => $_POST['fecha_ingreso'],
        'fecha_entrega_estimada' => $_POST['fecha_entrega_estimada']
    ];
    $ordenController->agregarOrden($data);
    header('Location: ordenes.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes de Trabajo</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <h1 class="my-4">Órdenes de Trabajo</h1>

    <form method="POST" action="" class="p-4 border rounded">
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente ID:</label>
            <input type="number" name="cliente_id" id="cliente_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="usuario_tecnico_id" class="form-label">Técnico ID:</label>
            <input type="number" name="usuario_tecnico_id" id="usuario_tecnico_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="marca" class="form-label">Marca:</label>
            <input type="text" name="marca" id="marca" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="modelo" class="form-label">Modelo:</label>
            <input type="text" name="modelo" id="modelo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="imei_serial" class="form-label">IMEI/Serial:</label>
            <input type="text" name="imei_serial" id="imei_serial" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="falla_reportada" class="form-label">Falla Reportada:</label>
            <textarea name="falla_reportada" id="falla_reportada" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="diagnostico" class="form-label">Diagnóstico:</label>
            <textarea name="diagnostico" id="diagnostico" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado:</label>
            <select name="estado" id="estado" class="form-select">
                <option value="pendiente">Pendiente</option>
                <option value="en_proceso">En Proceso</option>
                <option value="terminado">Terminado</option>
                <option value="entregado">Entregado</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad:</label>
            <select name="prioridad" id="prioridad" class="form-select">
                <option value="baja">Baja</option>
                <option value="media">Media</option>
                <option value="alta">Alta</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="contraseña_equipo" class="form-label">Contraseña del Equipo:</label>
            <input type="text" name="contraseña_equipo" id="contraseña_equipo" class="form-control">
        </div>
        <div class="mb-3">
            <label for="imagen_url" class="form-label">URL de Imagen:</label>
            <input type="text" name="imagen_url" id="imagen_url" class="form-control">
        </div>
        <div class="mb-3">
            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso:</label>
            <input type="datetime-local" name="fecha_ingreso" id="fecha_ingreso" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="fecha_entrega_estimada" class="form-label">Fecha de Entrega Estimada:</label>
            <input type="datetime-local" name="fecha_entrega_estimada" id="fecha_entrega_estimada" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Agregar Orden</button>
    </form>

    <h2 class="mt-5">Listado de Órdenes</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente ID</th>
                <th>Técnico ID</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>IMEI/Serial</th>
                <th>Falla Reportada</th>
                <th>Diagnóstico</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Contraseña</th>
                <th>Imagen</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Entrega</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordenes as $orden): ?>
                <tr>
                    <td><?= $orden['id'] ?></td>
                    <td><?= $orden['cliente_id'] ?></td>
                    <td><?= $orden['usuario_tecnico_id'] ?></td>
                    <td><?= $orden['marca'] ?></td>
                    <td><?= $orden['modelo'] ?></td>
                    <td><?= $orden['imei_serial'] ?></td>
                    <td><?= $orden['falla_reportada'] ?></td>
                    <td><?= $orden['diagnostico'] ?></td>
                    <td><?= $orden['estado'] ?></td>
                    <td><?= $orden['prioridad'] ?></td>
                    <td><?= $orden['contraseña_equipo'] ?></td>
                    <td><img src="<?= $orden['imagen_url'] ?>" alt="Imagen" width="50"></td>
                    <td><?= $orden['fecha_ingreso'] ?></td>
                    <td><?= $orden['fecha_entrega_estimada'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php include 'footer.php'; ?>
</body>
</html>