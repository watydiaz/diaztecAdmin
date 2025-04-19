<?php
require_once 'header.php';
?>

<div class="container">
    <h3>Gestión de Órdenes de Reparación</h3>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarOrden">
        Agregar Orden
    </button>

    <div class="modal fade" id="modalAgregarOrden" tabindex="-1" aria-labelledby="modalAgregarOrdenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarOrdenLabel">Agregar Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarOrden">
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente ID</label>
                            <input type="number" class="form-control" id="cliente_id" name="cliente_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="usuario_tecnico_id" class="form-label">Técnico ID</label>
                            <input type="number" class="form-control" id="usuario_tecnico_id" name="usuario_tecnico_id">
                        </div>
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" name="marca">
                        </div>
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo">
                        </div>
                        <div class="mb-3">
                            <label for="imei_serial" class="form-label">IMEI/Serial</label>
                            <input type="text" class="form-control" id="imei_serial" name="imei_serial">
                        </div>
                        <div class="mb-3">
                            <label for="falla_reportada" class="form-label">Falla Reportada</label>
                            <textarea class="form-control" id="falla_reportada" name="falla_reportada"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="diagnostico" class="form-label">Diagnóstico</label>
                            <textarea class="form-control" id="diagnostico" name="diagnostico"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="terminado">Terminado</option>
                                <option value="entregado">Entregado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prioridad" class="form-label">Prioridad</label>
                            <select class="form-control" id="prioridad" name="prioridad">
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="contraseña_equipo" class="form-label">Contraseña del Equipo</label>
                            <input type="text" class="form-control" id="contraseña_equipo" name="contraseña_equipo">
                        </div>
                        <div class="mb-3">
                            <label for="imagen_url" class="form-label">URL de Imagen</label>
                            <input type="text" class="form-control" id="imagen_url" name="imagen_url">
                        </div>
                        <div class="mb-3">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                            <input type="datetime-local" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
                        </div>
                        <div class="mb-3">
                            <label for="fecha_entrega_estimada" class="form-label">Fecha de Entrega Estimada</label>
                            <input type="datetime-local" class="form-control" id="fecha_entrega_estimada" name="fecha_entrega_estimada">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente ID</th>
                <th>Técnico ID</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordenes as $orden): ?>
                <tr>
                    <td><?php echo $orden['id']; ?></td>
                    <td><?php echo $orden['cliente_id']; ?></td>
                    <td><?php echo $orden['usuario_tecnico_id']; ?></td>
                    <td><?php echo $orden['marca']; ?></td>
                    <td><?php echo $orden['modelo']; ?></td>
                    <td><?php echo $orden['estado']; ?></td>
                    <td><?php echo $orden['prioridad']; ?></td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm" title="Editar">Editar</a>
                        <a href="#" class="btn btn-danger btn-sm" title="Eliminar">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'footer.php';
?>