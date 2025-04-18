<?php
require_once 'header.php';
?>

<div class="container">
    <h1>Órdenes de Reparación</h1>

    <button id="btnAgregarOrden" class="btn btn-primary">Agregar Orden</button>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha Ingreso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaOrdenes">
            <!-- Las órdenes se cargarán dinámicamente aquí -->
        </tbody>
    </table>
</div>

<!-- Modal para agregar orden -->
<div class="modal" id="modalAgregarOrden" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Orden</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAgregarOrden">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <select id="cliente_id" name="cliente_id" class="form-control">
                            <!-- Opciones cargadas dinámicamente -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usuario_tecnico_id">Técnico</label>
                        <select id="usuario_tecnico_id" name="usuario_tecnico_id" class="form-control">
                            <!-- Opciones cargadas dinámicamente -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <input type="text" id="marca" name="marca" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo</label>
                        <input type="text" id="modelo" name="modelo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="imei_serial">IMEI/Serial</label>
                        <input type="text" id="imei_serial" name="imei_serial" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="falla_reportada">Falla Reportada</label>
                        <textarea id="falla_reportada" name="falla_reportada" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="prioridad">Prioridad</label>
                        <select id="prioridad" name="prioridad" class="form-control">
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contraseña_equipo">Contraseña del Equipo</label>
                        <input type="text" id="contraseña_equipo" name="contraseña_equipo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="imagen_url">Imagen</label>
                        <input type="text" id="imagen_url" name="imagen_url" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="fecha_entrega_estimada">Fecha de Entrega Estimada</label>
                        <input type="datetime-local" id="fecha_entrega_estimada" name="fecha_entrega_estimada" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnGuardarOrden">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
<?php
require_once 'footer.php';
?>