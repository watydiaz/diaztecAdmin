<?php
require_once 'header.php';
?>

<style>
    .responsive-table {
        width: 100%;
        overflow-x: auto;
        display: block;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f4f4f4;
    }
</style>

<div class="container-fluid">
    <center><br>
    <h3>Gestión de Órdenes de Reparación</h3>
    <p>Desde aquí puedes gestionar las órdenes de trabajo, asignar técnicos y registrar la información del cliente.</p>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarOrden">
        Agregar Orden
    </button>
    </center>

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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="buscarCliente" class="form-label">Cliente</label>
                                <input type="text" class="form-control" id="buscarCliente" placeholder="Buscar cliente por nombre o identificación">
                                <ul class="list-group mt-2" id="listaClientes" style="display: none;"></ul>
                                <input type="hidden" id="cliente_id" name="cliente_id">
                                <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#submodalAgregarCliente">Crear Cliente</button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_tecnico_id" class="form-label">Técnico</label>
                                <select class="form-control" id="usuario_tecnico_id" name="usuario_tecnico_id">
                                    <option value="">Seleccione un técnico</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marca" name="marca">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="imei_serial" class="form-label">IMEI/Serial</label>
                                <input type="text" class="form-control" id="imei_serial" name="imei_serial">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-control" id="estado" name="estado">
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="terminado">Terminado</option>
                                    <option value="entregado">Entregado</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-control" id="prioridad" name="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contraseña_equipo" class="form-label">Contraseña del Equipo</label>
                                <input type="text" class="form-control" id="contraseña_equipo" name="contraseña_equipo">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="imagen_url" class="form-label">URL de Imagen</label>
                                <input type="text" class="form-control" id="imagen_url" name="imagen_url">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="datetime-local" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_entrega_estimada" class="form-label">Fecha de Entrega Estimada</label>
                                <input type="datetime-local" class="form-control" id="fecha_entrega_estimada" name="fecha_entrega_estimada">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="falla_reportada" class="form-label">Falla Reportada</label>
                                <textarea class="form-control" id="falla_reportada" name="falla_reportada"></textarea>
                            </div>
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

    <div class="responsive-table">
        <table class="table table-striped w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Técnico</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Falla Reportada</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Fecha de Ingreso</th>
                    th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenes as $orden): ?>
                    <tr>
                        <td><?php echo $orden['id']; ?></td>
                        <td><?php echo $orden['cliente_nombre']; ?></td>
                        <td><?php echo $orden['tecnico_nombre']; ?></td>
                        <td><?php echo $orden['marca']; ?></td>
                        <td><?php echo $orden['modelo']; ?></td>
                        <td><?php echo $orden['falla_reportada']; ?></td>
                        <td><?php echo $orden['estado']; ?></td>
                        <td><?php echo $orden['prioridad']; ?></td>
                        <td><?php echo $orden['fecha_ingreso']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </a>
                            <a href="tel:<?php echo $orden['telefono_cliente']; ?>" class="btn btn-success btn-sm" title="Llamar">
                                <i class="bi bi-telephone-fill"></i>
                            </a>
                            <a href="https://wa.me/<?php echo $orden['telefono_cliente']; ?>" target="_blank" class="btn btn-success btn-sm" title="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="#" class="btn btn-info btn-sm" title="Ver" onclick="verOrden(<?php echo $orden['id']; ?>)">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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

        // Cargar técnicos dinámicamente al abrir el modal
        const modalAgregarOrden = document.getElementById('modalAgregarOrden');
        modalAgregarOrden.addEventListener('show.bs.modal', function() {
            const tecnicoSelect = document.getElementById('usuario_tecnico_id');
            tecnicoSelect.innerHTML = '<option value="">Seleccione un técnico</option>'; // Limpiar opciones previas

            fetch('index.php?action=obtenerTecnicos')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.tecnicos.forEach(tecnico => {
                            const option = document.createElement('option');
                            option.value = tecnico.id;
                            option.textContent = tecnico.nombre;
                            tecnicoSelect.appendChild(option);
                        });
                    } else {
                        alert('Error al cargar técnicos: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar técnicos:', error);
                });

            // Establecer la fecha y hora de ingreso automáticamente al abrir el modal
            const fechaIngresoInput = document.getElementById('fecha_ingreso');
            const now = new Date();
            const formattedDate = now.toISOString().slice(0, 16); // Formato compatible con datetime-local
            fechaIngresoInput.value = formattedDate;
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

        // Manejar el envío del formulario de agregar orden
        const formAgregarOrden = document.getElementById('formAgregarOrden');

        formAgregarOrden.addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar el envío por defecto del formulario

            const formData = new FormData(this);

            fetch('index.php?action=agregarOrden', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Orden de trabajo registrada exitosamente.');
                    location.reload(); // Recargar la página para reflejar los cambios
                } else {
                    alert('Error al registrar la orden: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al registrar la orden:', error);
                alert('Ocurrió un error al intentar registrar la orden.');
            });
        });
    });

    function verOrden(id) {
        fetch(`index.php?action=obtenerOrden&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const orden = data.orden;
                    alert(`Información de la Orden:\n\n` +
                          `ID: ${orden.id}\n` +
                          `Cliente: ${orden.cliente_nombre}\n` +
                          `Técnico: ${orden.tecnico_nombre}\n` +
                          `Marca: ${orden.marca}\n` +
                          `Modelo: ${orden.modelo}\n` +
                          `Falla Reportada: ${orden.falla_reportada}\n` +
                          `Fecha de Ingreso: ${orden.fecha_ingreso}`);
                } else {
                    alert('Error al obtener la información de la orden.');
                }
            })
            .catch(error => {
                console.error('Error al obtener la información de la orden:', error);
                alert('Ocurrió un error al intentar obtener la información de la orden.');
            });
    }
</script>

<?php
require_once 'footer.php';
?>