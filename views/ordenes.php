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

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0,0,0,0.5);
        border-radius: 50%;
        width: 2.5rem;
        height: 2.5rem;
        background-size: 60% 60%;
    }
    .carousel-control-prev,
    .carousel-control-next {
        filter: drop-shadow(0 0 2px #fff);
    }

    /* Acciones en la tabla: mostrar en fila horizontal en móviles */
    @media (max-width: 768px) {
        .acciones-responsive {
            display: flex !important;
            flex-wrap: nowrap !important;
            gap: 4px;
            justify-content: flex-start;
            overflow-x: auto;
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }
        .acciones-responsive a {
            flex: 0 0 auto;
            min-width: 36px;
            margin: 0 2px;
        }
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
                    <form id="formAgregarOrden" enctype="multipart/form-data">
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
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="datetime-local" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
                            </div>
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

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="imagenes" class="form-label">Subir Imágenes</label>
                                <input type="file" class="form-control" id="imagenes" name="imagenes[]" accept="image/*" multiple>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="diagnostico" class="form-label">Diagnóstico</label>
                                <textarea class="form-control" id="diagnostico" name="diagnostico"></textarea>
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

    <!-- Modal para editar orden -->
    <div class="modal fade" id="modalEditarOrden" tabindex="-1" aria-labelledby="modalEditarOrdenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarOrdenLabel">Editar Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarOrden" enctype="multipart/form-data">
                        <input type="hidden" id="editarOrdenId" name="id">
                        <!-- Campos del formulario -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editarMarca" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="editarMarca" name="marca">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editarModelo" class="form-label">Modelo</label>
                                <input type="text" class="form-control" id="editarModelo" name="modelo">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editarImeiSerial" class="form-label">IMEI/Serial</label>
                                <input type="text" class="form-control" id="editarImeiSerial" name="imei_serial">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editarEstado" class="form-label">Estado</label>
                                <select class="form-control" id="editarEstado" name="estado">
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="terminado">Terminado</option>
                                    <option value="entregado">Entregado</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editarPrioridad" class="form-label">Prioridad</label>
                                <select class="form-control" id="editarPrioridad" name="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editarContraseñaEquipo" class="form-label">Contraseña del Equipo</label>
                                <input type="text" class="form-control" id="editarContraseñaEquipo" name="contraseña_equipo">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="editarFallaReportada" class="form-label">Falla Reportada</label>
                                <textarea class="form-control" id="editarFallaReportada" name="falla_reportada"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="editarDiagnostico" class="form-label">Diagnóstico</label>
                                <textarea class="form-control" id="editarDiagnostico" name="diagnostico"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Imágenes actuales</label>
                                <div id="imagenesActuales" class="d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="editarImagenes" class="form-label">Agregar otra imagen</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="editarImagenes" name="imagenes[]" accept="image/*">
                                    <button type="button" class="btn btn-secondary" id="btnAgregarInputImagen">Agregar otra imagen</button>
                                </div>
                                <div id="inputsExtraImagenes"></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles de la orden -->
    <div class="modal fade" id="modalVerOrden" tabindex="-1" aria-labelledby="modalVerOrdenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerOrdenLabel">Detalles de la Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="detalleId"></span></p>
                    <p><strong>Cliente:</strong> <span id="detalleCliente"></span></p>
                    <p><strong>Técnico:</strong> <span id="detalleTecnico"></span></p>
                    <p><strong>Marca:</strong> <span id="detalleMarca"></span></p>
                    <p><strong>Modelo:</strong> <span id="detalleModelo"></span></p>
                    <p><strong>Falla Reportada:</strong> <span id="detalleFalla"></span></p>
                    <p><strong>Estado:</strong> <span id="detalleEstado"></span></p>
                    <p><strong>Prioridad:</strong> <span id="detallePrioridad"></span></p>
                    <p><strong>Fecha de Ingreso:</strong> <span id="detalleFechaIngreso"></span></p>
                    <p><strong>Diagnóstico:</strong> <span id="detalleDiagnostico"></span></p>
                    <p><strong>Imagen:</strong></p>
                    <center><img id="detalleImagen" src="" alt="Imagen de la orden" style="width: 50%; height: auto;"></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver imagen en grande -->
    <div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="modalImagenLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImagenLabel">Imágenes de la Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="sliderImagenesOrden" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carouselImagenesInner"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#sliderImagenesOrden" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#sliderImagenesOrden" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="responsive-table">
        <table class="table table-striped w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Cliente</th>
                    <th>Técnico</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Falla Reportada</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Fecha de Ingreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenes as $orden): ?>
                    <tr class="fila-orden" style="<?php 
                        if ($orden['estado'] === 'terminado') {
                            echo 'background-color: rgba(0, 128, 0, 0.1);';
                        } elseif ($orden['estado'] !== 'entregado' && $orden['prioridad'] === 'alta') {
                            echo 'background-color: rgba(255, 0, 0, 0.1);';
                        }
                    ?>" data-id="<?php echo $orden['id']; ?>">
                        <td><?php echo $orden['id']; ?></td>
                        <td>
                            <?php 
                            $miniatura = '';
                            if (!empty($orden['imagen_url'])) {
                                $imagenes = explode(',', $orden['imagen_url']);
                                $miniatura = $imagenes[0];
                            }
                            ?>
                            <?php if ($miniatura): ?>
                                <img src="<?php echo $miniatura; ?>" alt="Imagen principal" style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;" onclick="verImagenModal('<?php echo htmlspecialchars(json_encode($orden['imagen_url']), ENT_QUOTES, 'UTF-8'); ?>')">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $orden['cliente_nombre']; ?></td>
                        <td><?php echo $orden['tecnico_nombre']; ?></td>
                        <td><?php echo $orden['marca']; ?></td>
                        <td><?php echo $orden['modelo']; ?></td>
                        <td title="<?php echo htmlspecialchars($orden['falla_reportada']); ?>">
                            <?php echo mb_strimwidth($orden['falla_reportada'], 0, 30, '...'); ?>
                        </td>
                        <td><?php echo $orden['estado']; ?></td>
                        <td><?php echo $orden['prioridad']; ?></td>
                        <td title="<?php echo $orden['fecha_ingreso']; ?>">
                            <?php echo date('Y-m-d', strtotime($orden['fecha_ingreso'])); ?>
                        </td>
                        <td class="acciones-responsive">
                            <a href="#" class="btn btn-warning btn-sm" title="Editar" onclick="abrirModalEditarOrden(<?php echo $orden['id']; ?>)">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="#" class="btn btn-success btn-sm" title="Marcar como Terminado" onclick="cambiarEstadoTerminado(<?php echo $orden['id']; ?>); return false;">
                                <i class="bi bi-check2-square"></i>
                            </a>
                            <a href="#" class="btn btn-dark btn-sm" title="Marcar como Entregado" onclick="cambiarEstadoEntregado(<?php echo $orden['id']; ?>)">
                                <i class="bi bi-check-circle"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm" title="Eliminar" data-id="<?php echo $orden['id']; ?>">
                                <i class="bi bi-trash"></i>
                            </a>
                            <a href="tel:<?php echo $orden['telefono_cliente']; ?>" class="btn btn-primary btn-sm" title="Llamar">
                                <i class="bi bi-telephone-fill"></i>
                            </a>
                            <a href="https://wa.me/57<?php echo ltrim($orden['telefono_cliente'], '0'); ?>" target="_blank" class="btn btn-success btn-sm" title="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="index.php?action=generarRemision&id=<?php echo $orden['id']; ?>" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-secondary btn-sm" title="Pagos" data-id="<?php echo $orden['id']; ?>">
                                <i class="bi bi-cash-coin"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para ver imagen en tamaño completo -->
    <div class="modal fade" id="modalVerImagen" tabindex="-1" aria-labelledby="modalVerImagenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerImagenLabel">Imagen de la Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="imagenOrdenCompleta" src="" alt="Imagen de la orden" style="width: 100%; height: auto;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
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
            document.getElementById('formAgregarOrden').addEventListener('submit', function(event) {
                event.preventDefault(); // Evitar el envío por defecto del formulario

                // Mostrar la imagen de cargando
                const loadingOverlay = document.createElement('div');
                loadingOverlay.style.position = 'fixed';
                loadingOverlay.style.top = '0';
                loadingOverlay.style.left = '0';
                loadingOverlay.style.width = '100%';
                loadingOverlay.style.height = '100%';
                loadingOverlay.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
                loadingOverlay.style.display = 'flex';
                loadingOverlay.style.justifyContent = 'center';
                loadingOverlay.style.alignItems = 'center';
                loadingOverlay.style.zIndex = '9999';

                const loadingImage = document.createElement('img');
                loadingImage.src = 'assets/img/loading-gear.gif'; // Asegúrate de tener una imagen de engranaje en esta ruta
                loadingImage.alt = 'Cargando...';
                loadingImage.style.width = '100px';

                loadingOverlay.appendChild(loadingImage);
                document.body.appendChild(loadingOverlay);

                // Deshabilitar el botón de envío
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;

                const formData = new FormData(this);

                fetch('index.php?action=agregarOrden', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Ocultar la imagen de cargando
                    document.body.removeChild(loadingOverlay);

                    // Habilitar el botón de envío
                    submitButton.disabled = false;

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

                    // Ocultar la imagen de cargando
                    document.body.removeChild(loadingOverlay);

                    // Habilitar el botón de envío
                    submitButton.disabled = false;
                });
            });

            // Manejar la acción de eliminar
            document.querySelectorAll('.btn-danger').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    if (confirm('¿Estás seguro de que deseas eliminar esta orden?')) {
                        const id = this.dataset.id; // Obtener el ID de la orden

                        fetch(`index.php?action=eliminarOrden&id=${id}`)
                            .then(response => {
                                if (response.ok) {
                                    alert('Orden eliminada exitosamente.');
                                    location.reload(); // Recargar la página
                            } else {
                                alert('Error al eliminar la orden.');
                            }
                        })
                        .catch(error => {
                            console.error('Error al eliminar la orden:', error);
                            alert('Ocurrió un error al intentar eliminar la orden.');
                        });
                    }
                });
            });

            // Lógica para agregar más inputs de imagen en la edición de orden
            let contadorInputsImagen = 1;
            const btnAgregarInputImagen = document.getElementById('btnAgregarInputImagen');
            const inputsExtraImagenesDiv = document.getElementById('inputsExtraImagenes');

            btnAgregarInputImagen.addEventListener('click', function() {
                contadorInputsImagen++;
                const nuevoInputImagen = document.createElement('div');
                nuevoInputImagen.className = 'mb-3';
                nuevoInputImagen.innerHTML = `
                    <input type="file" class="form-control" id="editarImagenes${contadorInputsImagen}" name="imagenes[]" accept="image/*">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarInputImagen(this)">Eliminar</button>
                `;
                inputsExtraImagenesDiv.appendChild(nuevoInputImagen);
            });

        });

    // Función para mostrar las imágenes en un slider modal
    function verImagenModal(imagenesStr) {
        let imagenes = [];
        if (Array.isArray(imagenesStr)) {
            imagenes = imagenesStr;
        } else if (typeof imagenesStr === 'string') {
            try {
                imagenes = JSON.parse(imagenesStr);
                if (!Array.isArray(imagenes)) {
                    imagenes = imagenesStr.split(',');
                }
            } catch (e) {
                imagenes = imagenesStr.split(',');
            }
        }
        // Limpiar comillas y espacios
        imagenes = imagenes.map(img => img.replace(/^"|"$/g, '').trim()).filter(img => img);
        const carouselInner = document.getElementById('carouselImagenesInner');
        carouselInner.innerHTML = '';
        let tieneVarias = imagenes.length > 1;
        imagenes.forEach((img, idx) => {
            const div = document.createElement('div');
            div.className = 'carousel-item' + (idx === 0 ? ' active' : '');
            const image = document.createElement('img');
            image.src = img;
            image.className = 'd-block w-100';
            image.style.maxHeight = '70vh';
            image.style.objectFit = 'contain';
            div.appendChild(image);
            carouselInner.appendChild(div);
        });
        // Mostrar/ocultar controles del slider
        document.querySelector('#sliderImagenesOrden .carousel-control-prev').style.display = tieneVarias ? '' : 'none';
        document.querySelector('#sliderImagenesOrden .carousel-control-next').style.display = tieneVarias ? '' : 'none';
        const modal = new bootstrap.Modal(document.getElementById('modalImagen'));
        modal.show();
    }

    function verOrden(id) {
        fetch(`index.php?action=obtenerOrden&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const orden = data.orden;
                    document.getElementById('detalleId').textContent = orden.id;
                    document.getElementById('detalleCliente').textContent = orden.cliente_nombre || 'No disponible';
                    document.getElementById('detalleTecnico').textContent = orden.tecnico_nombre || 'No disponible';
                    document.getElementById('detalleMarca').textContent = orden.marca;
                    document.getElementById('detalleModelo').textContent = orden.modelo;
                    document.getElementById('detalleFalla').textContent = orden.falla_reportada;
                    document.getElementById('detalleEstado').textContent = orden.estado;
                    document.getElementById('detallePrioridad').textContent = orden.prioridad;
                    document.getElementById('detalleFechaIngreso').textContent = orden.fecha_ingreso;
                    document.getElementById('detalleDiagnostico').textContent = orden.diagnostico;
                    document.getElementById('detalleImagen').src = orden.imagen_url || ''; // Asignar la URL de la imagen

                    if (orden.imagen_url) {
                        const imagenes = orden.imagen_url.split(',');
                        document.getElementById('detalleImagen').src = imagenes[0] || '';
                        document.getElementById('detalleImagen').onclick = function() {
                            verImagenModal(JSON.stringify(imagenes));
                        };
                    } else {
                        document.getElementById('detalleImagen').src = '';
                        document.getElementById('detalleImagen').onclick = null;
                    }

                    const modalVerOrden = new bootstrap.Modal(document.getElementById('modalVerOrden'));
                    modalVerOrden.show();
                } else {
                    alert('Error al obtener los datos de la orden.');
                }
            })
            .catch(error => {
                console.error('Error al obtener los datos de la orden:', error);
            });
    }

    function abrirModalEditarOrden(id) {
        fetch(`index.php?action=obtenerOrden&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const orden = data.orden;
                    document.getElementById('editarOrdenId').value = orden.id;
                    document.getElementById('editarMarca').value = orden.marca;
                    document.getElementById('editarModelo').value = orden.modelo;
                    document.getElementById('editarImeiSerial').value = orden.imei_serial;
                    document.getElementById('editarEstado').value = orden.estado;
                    document.getElementById('editarPrioridad').value = orden.prioridad;
                    document.getElementById('editarContraseñaEquipo').value = orden.contraseña_equipo;
                    document.getElementById('editarFallaReportada').value = orden.falla_reportada;
                    document.getElementById('editarDiagnostico').value = orden.diagnostico;

                    // Mostrar imágenes actuales with opción de eliminar
                    const imagenesActualesDiv = document.getElementById('imagenesActuales');
                    imagenesActualesDiv.innerHTML = '';
                    if (orden.imagen_url) {
                        const imagenes = orden.imagen_url.split(',');
                        imagenes.forEach(img => {
                            const wrapper = document.createElement('div');
                            wrapper.style.position = 'relative';
                            wrapper.style.display = 'inline-block';
                            wrapper.style.margin = '2px';
                            const image = document.createElement('img');
                            image.src = img;
                            image.style.width = '60px';
                            image.style.height = '60px';
                            image.style.objectFit = 'cover';
                            image.style.border = '1px solid #ccc';
                            image.style.borderRadius = '4px';
                            // Botón eliminar
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.innerHTML = '&times;';
                            btn.title = 'Eliminar imagen';
                            btn.style.position = 'absolute';
                            btn.style.top = '0';
                            btn.style.right = '0';
                            btn.style.background = 'rgba(255,0,0,0.7)';
                            btn.style.color = '#fff';
                            btn.style.border = 'none';
                            btn.style.borderRadius = '0 4px 0 4px';
                            btn.style.cursor = 'pointer';
                            btn.onclick = function() {
                                if (confirm('¿Eliminar esta imagen?')) {
                                    fetch('index.php?action=eliminarImagenOrden', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                        body: `id=${orden.id}&imagen=${encodeURIComponent(img)}`
                                    })
                                    .then(r => r.json())
                                    .then(resp => {
                                        if (resp.success) {
                                            wrapper.remove();
                                        } else {
                                            alert('Error: ' + resp.message);
                                        }
                                    });
                                }
                            };
                            wrapper.appendChild(image);
                            wrapper.appendChild(btn);
                            imagenesActualesDiv.appendChild(wrapper);
                        });
                    }

                    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarOrden'));
                    modalEditar.show();
                } else {
                    alert('Error al obtener los datos de la orden.');
                }
            })
            .catch(error => {
                console.error('Error al obtener los datos de la orden:', error);
            });
    }

    document.getElementById('formEditarOrden').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('index.php?action=actualizarOrden', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Orden actualizada exitosamente.');
                location.reload();
            } else {
                alert('Error al actualizar la orden: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al actualizar la orden:', error);
        });
    });

    function cambiarEstadoEntregado(id) {
        if (confirm('¿Estás seguro de que deseas marcar esta orden como entregada?')) {
            fetch(`index.php?action=cambiarEstadoEntregado&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('La orden ha sido marcada como entregada.');
                        location.reload();
                    } else {
                        alert('Error al cambiar el estado de la orden: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al intentar cambiar el estado de la orden.');
                });
        }
    }

    function eliminarImagen(imagenId, imgElement) {
        if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
            fetch(`index.php?action=eliminarImagen&id=${imagenId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Imagen eliminada exitosamente.');
                        // Eliminar el elemento de imagen del DOM
                        imgElement.parentElement.remove();
                    } else {
                        alert('Error al eliminar la imagen: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar la imagen:', error);
                    alert('Ocurrió un error al intentar eliminar la imagen.');
                });
        }
    }

    function eliminarInputImagen(button) {
        button.parentElement.remove();
    }

    // Agregar evento para mostrar detalles al seleccionar la fila
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.fila-orden').forEach(function(fila) {
            fila.addEventListener('click', function(e) {
                // Evitar conflicto con botones de acción
                if (e.target.tagName === 'A' || e.target.tagName === 'I' || e.target.tagName === 'IMG' || e.target.closest('a,button')) return;
                verOrden(this.dataset.id);
            });
        });
    });
    </script>
</div>

<?php
require_once 'footer.php';
?>