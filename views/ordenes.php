<?php
require_once 'header.php';
?>

<div class="container">
    <h3>Gestión de Órdenes de Reparación</h3>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarOrden">
        Agregar Orden
    </button>

    <!-- Modal para agregar orden con cliente y técnico dinámicos -->
    <div class="modal fade" id="modalAgregarOrden" tabindex="-1" aria-labelledby="modalAgregarOrdenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarOrdenLabel">Agregar Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarOrden">
                        <!-- Campo para buscar y seleccionar cliente -->
                        <div class="mb-3">
                            <label for="buscarCliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="buscarCliente" placeholder="Buscar cliente por nombre o identificación">
                            <ul class="list-group mt-2" id="listaClientes" style="display: none;"></ul>
                            <input type="hidden" id="cliente_id" name="cliente_id">
                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#submodalAgregarCliente">Crear Cliente</button>
                        </div>

                        <!-- Campo para seleccionar técnico -->
                        <div class="mb-3">
                            <label for="usuario_tecnico_id" class="form-label">Técnico</label>
                            <select class="form-control" id="usuario_tecnico_id" name="usuario_tecnico_id">
                                <option value="">Seleccione un técnico</option>
                            </select>
                        </div>

                        <!-- Otros campos del formulario -->
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

    <!-- Submodal para agregar cliente -->
    <div class="modal fade" id="submodalAgregarCliente" tabindex="-1" aria-labelledby="submodalAgregarClienteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submodalAgregarClienteLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formSubmodalAgregarCliente">
                        <div class="mb-3">
                            <label for="submodalNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="submodalNombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="submodalIdentificacion" class="form-label">Identificación</label>
                            <input type="text" class="form-control" id="submodalIdentificacion" name="identificacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="submodalTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="submodalTelefono" name="telefono">
                        </div>
                        <div class="mb-3">
                            <label for="submodalEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="submodalEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="submodalDireccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="submodalDireccion" name="direccion"></textarea>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buscarClienteInput = document.getElementById('buscarCliente');
        const listaClientes = document.getElementById('listaClientes');
        const clienteIdInput = document.getElementById('cliente_id');

        buscarClienteInput.addEventListener('input', function() {
            const query = this.value;

            if (query.length > 2) {
                fetch(`index.php?action=buscarCliente&query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        listaClientes.innerHTML = '';
                        if (data.clientes.length > 0) {
                            listaClientes.style.display = 'block';
                            data.clientes.forEach(cliente => {
                                const li = document.createElement('li');
                                li.className = 'list-group-item list-group-item-action';
                                li.textContent = `${cliente.nombre} (${cliente.identificacion})`;
                                li.dataset.id = cliente.id;
                                listaClientes.appendChild(li);
                            });
                        } else {
                            listaClientes.style.display = 'none';
                        }
                    });
            } else {
                listaClientes.style.display = 'none';
            }
        });

        listaClientes.addEventListener('click', function(event) {
            if (event.target.tagName === 'LI') {
                const selectedCliente = event.target;
                buscarClienteInput.value = selectedCliente.textContent;
                clienteIdInput.value = selectedCliente.dataset.id;
                listaClientes.style.display = 'none';
            }
        });

        // Cargar técnicos dinámicamente
        fetch('index.php?action=obtenerTecnicos')
            .then(response => response.json())
            .then(data => {
                const tecnicoSelect = document.getElementById('usuario_tecnico_id');
                data.tecnicos.forEach(tecnico => {
                    const option = document.createElement('option');
                    option.value = tecnico.id;
                    option.textContent = tecnico.nombre;
                    tecnicoSelect.appendChild(option);
                });
            });

        // Crear nuevo cliente desde el submodal
        const formSubmodalAgregarCliente = document.getElementById('formSubmodalAgregarCliente');

        formSubmodalAgregarCliente.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('index.php?action=agregarCliente', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Asociar el cliente recién creado
                    buscarClienteInput.value = `${formData.get('nombre')} (${formData.get('identificacion')})`;
                    clienteIdInput.value = data.cliente_id; // Suponiendo que el backend devuelve el ID del cliente creado

                    // Cerrar solo el submodal
                    const submodal = bootstrap.Modal.getInstance(document.getElementById('submodalAgregarCliente'));
                    submodal.hide();

                    // Simular clic en el botón de agregar orden
                    const botonAgregarOrden = document.querySelector('[data-bs-target="#modalAgregarOrden"]');
                    botonAgregarOrden.click();
                } else {
                    alert('Error al agregar cliente: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>

<?php
require_once 'footer.php';
?>