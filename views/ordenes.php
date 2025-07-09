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
        footer {
            text-align: center;
            font-size: 14px;
        }
    }
    
    .page-container {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 25%, #4a4a4a 50%, #6a6a6a 75%, #8a8a8a 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    
    .page-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: center;
        color: #212529;
        border: 2px solid #ced4da;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .content-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    /* Botón negro para acciones principales */
    .btn-dark {
        background: linear-gradient(45deg, #000000, #111111, #333333);
        border: 1px solid #444444;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    }
    
    .btn-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.4);
        background: linear-gradient(45deg, #111111, #333333, #555555);
    }
    
    .table th {
        background: linear-gradient(45deg, #000000, #111111, #222222);
        color: white;
        border: none;
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    }
    
    /* Limitar longitud de textos largos en la tabla */
    .texto-truncado {
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    
    /* Estilos para los botones con degradados */
    .btn-warning {
        background: linear-gradient(45deg, #ffc107, #e0a800, #d39e00);
        border: 1px solid #d39e00;
        color: #212529;
        text-shadow: 1px 1px 2px rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.3);
        background: linear-gradient(45deg, #e0a800, #d39e00, #ba8b00);
    }
    
    .btn-success {
        background: linear-gradient(45deg, #28a745, #218838, #1e7e34);
        border: 1px solid #1e7e34;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.3);
        background: linear-gradient(45deg, #218838, #1e7e34, #1c7430);
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0069d9, #0062cc);
        border: 1px solid #0062cc;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.4);
        background: linear-gradient(45deg, #0069d9, #0062cc, #005cbf);
    }
    
    .bg-primary {
        background: linear-gradient(45deg, #007bff, #0069d9, #0062cc) !important;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    }
    
    .btn-danger {
        background: linear-gradient(45deg, #dc3545, #c82333, #bd2130);
        border: 1px solid #bd2130;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.3);
        background: linear-gradient(45deg, #c82333, #bd2130, #a71d2a);
    }
    
    .btn-info {
        background: linear-gradient(45deg, #17a2b8, #138496, #117a8b);
        border: 1px solid #117a8b;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        transition: all 0.3s ease;
    }
    
    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.3);
        background: linear-gradient(45deg, #138496, #117a8b, #0e6674);
    }
    
    /* Asegurar que todos los iconos tengan el mismo tamaño */
    .btn-sm i {
        font-size: 12px;
        width: 12px;
        height: 12px;
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
    }
    
    /* Ajustar el espaciado de los botones para mejor alineación */
    .btn-sm {
        padding: 0.25rem 0.4rem;
        margin: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        min-height: 28px;
    }
    
    /* Centrar los botones en la columna de acciones */
    .acciones-centradas {
        text-align: center;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 3px;
    }
    
    /* Estilos para tabla fluida con muchas columnas */
    .table-fluid {
        width: 100%;
        min-width: 100%;
        table-layout: fixed;
    }
    
    .table-fluid th, .table-fluid td {
        padding: 8px 5px;
        font-size: 0.9rem;
    }
    
    /* Estilos específicos para cada columna en la tabla de órdenes */
    .col-id { width: 4%; }
    .col-imagen { width: 6%; }
    .col-cliente { width: 12%; }
    .col-tecnico { width: 12%; }
    .col-marca { width: 8%; }
    .col-modelo { width: 8%; }
    .col-falla { width: 15%; }
    .col-estado { width: 8%; }
    .col-prioridad { width: 8%; }
    .col-fecha { width: 7%; }
    .col-acciones { width: 12%; text-align: center; }
    
    /* Ajustes adicionales para diseño fluido */
    .container-fluid {
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }
    
    /* Mejorar el diseño responsive */
    @media (max-width: 768px) {
        .table-fluid th, .table-fluid td {
            padding: 6px 3px;
            font-size: 0.8rem;
        }
        
        .col-falla { width: 18%; }
        .col-cliente { width: 15%; }
        .col-tecnico { width: 15%; }
    }
    
    /* Estilos para mejorar la visualización de estados */
    .estado-terminado {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .estado-alta-prioridad {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    
    /* Ajustar imagen de miniatura */
    .miniatura-orden {
        width: 40px;
        height: 40px;
        object-fit: cover;
        cursor: pointer;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
</style>

<div class="page-container">
    <div class="container-fluid">
        <!-- Header de la página -->
        <div class="page-header">
            <h1><i class="bi bi-tools me-3"></i>Gestión de Órdenes de Reparación</h1>
            <p class="mb-0">Desde aquí puedes gestionar las órdenes de trabajo, asignar técnicos y registrar la información del cliente.</p>
        </div>
        
        <!-- Contenido principal -->
        <div class="content-card">
            <div class="text-center mb-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarOrden">
                    <i class="bi bi-plus-circle me-2"></i>Agregar Orden
                </button>
            </div>

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
                                <input type="file" class="form-control" id="imagenes" name="imagenes[]" accept="image/*" multiple capture="environment">
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
                            <input type="number" class="form-control" id="submodalIdentificacion" name="identificacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="submodalTelefono" class="form-label">Teléfono</label>
                            <input type="number" class="form-control" id="submodalTelefono" name="telefono">
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
                                    <input type="file" class="form-control" id="editarImagenes" name="imagenes[]" accept="image/*" capture="environment">
                                    <button type="button" class="btn btn-secondary" id="btnAgregarInputImagen">Agregar otra imagen</button>
                                </div>
                                <div id="inputsExtraImagenes"></div>
                            </div>
                        </div>
                        <!-- Select de técnico para editar orden -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editar_usuario_tecnico_id" class="form-label">Técnico</label>
                                <select class="form-control" id="editar_usuario_tecnico_id" name="usuario_tecnico_id">
                                    <option value="">Seleccione un técnico</option>
                                </select>
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

                    <!-- --- Sección de detalles de pago --- -->
                    <div id="detallePagoSection" style="margin-top:10px;display:none;"></div>

                    <!-- --- Sección de acciones --- -->
                    <div id="detalleAcciones" style="margin-top:10px;display:none;"></div>
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

    <!-- Modal para pagos de la orden -->
    <div class="modal fade" id="modalPagosOrden" tabindex="-1" aria-labelledby="modalPagosOrdenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPagosOrdenLabel">Gestión de Pagos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPagosOrden">
                        <div class="mb-3">
                            <label for="pagoOrdenId" class="form-label">ID Orden</label>
                            <input type="text" class="form-control" id="pagoOrdenId" name="pagoOrdenId" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="costoTotal" class="form-label">Costo total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="costoTotal" name="costoTotal" inputmode="numeric" pattern="[0-9.]*" autocomplete="off">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="abono" class="form-label">Dinero recibido</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="abono" name="abono" inputmode="numeric" pattern="[0-9.]*" autocomplete="off">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="saldo" class="form-label">Saldo</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="saldo" name="saldo" readonly value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="valorRepuestos" class="form-label">Valor repuestos</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="valorRepuestos" name="valorRepuestos" inputmode="numeric" pattern="[0-9.]*" autocomplete="off" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionRepuestos" class="form-label">Descripción repuestos</label>
                            <textarea class="form-control" id="descripcionRepuestos" name="descripcionRepuestos"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de pago</label>
                            <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                <option value="">Seleccione...</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="nequi">Nequi</option>
                                <option value="daviplata">Daviplata</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Registrar Pago</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Buscador de órdenes y clientes -->
    <div class="row mb-3">
        <div class="col-md-6 offset-md-3">
            <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="buscadorOrdenes" class="form-control" placeholder="Buscar por cliente, técnico, marca, modelo, estado, prioridad, ID, etc...">
            </div>
        </div>
    </div>

    <div class="responsive-table">
        <table class="table table-striped table-fluid w-100">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-imagen">Imagen</th>
                    <th class="col-cliente">Cliente</th>
                    <th class="col-tecnico">Técnico</th>
                    <th class="col-marca">Marca</th>
                    <th class="col-modelo">Modelo</th>
                    <th class="col-falla">Falla Reportada</th>
                    <th class="col-estado">Estado</th>
                    <th class="col-prioridad">Prioridad</th>
                    <th class="col-fecha">Fecha</th>
                    <th class="col-acciones text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenes as $orden): ?>
                    <tr class="fila-orden <?php 
                        if ($orden['estado'] === 'terminado') {
                            echo 'estado-terminado';
                        } elseif ($orden['estado'] !== 'entregado' && $orden['prioridad'] === 'alta') {
                            echo 'estado-alta-prioridad';
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
                                <img src="<?php echo $miniatura; ?>" alt="Imagen principal" class="miniatura-orden" onclick="verImagenModal('<?php echo htmlspecialchars(json_encode($orden['imagen_url']), ENT_QUOTES, 'UTF-8'); ?>')">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="texto-truncado" title="<?php echo htmlspecialchars($orden['cliente_nombre']); ?>"><?php echo $orden['cliente_nombre']; ?></span></td>
                        <td><span class="texto-truncado" title="<?php echo htmlspecialchars($orden['tecnico_nombre']); ?>"><?php echo $orden['tecnico_nombre']; ?></span></td>
                        <td><span class="texto-truncado" title="<?php echo htmlspecialchars($orden['marca']); ?>"><?php echo $orden['marca']; ?></span></td>
                        <td><span class="texto-truncado" title="<?php echo htmlspecialchars($orden['modelo']); ?>"><?php echo $orden['modelo']; ?></span></td>
                        <td><span class="texto-truncado" title="<?php echo htmlspecialchars($orden['falla_reportada']); ?>"><?php echo mb_strimwidth($orden['falla_reportada'], 0, 30, '...'); ?></span></td>
                        <td><?php echo $orden['estado']; ?></td>
                        <td><?php echo $orden['prioridad']; ?></td>
                        <td title="<?php echo $orden['fecha_ingreso']; ?>">
                            <?php echo date('Y-m-d', strtotime($orden['fecha_ingreso'])); ?>
                        </td>
                        <td class="acciones-centradas">
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
                            <a href="#" 
                               class="btn <?php echo ($orden['tiene_pago'] > 0 ? 'btn-success' : 'btn-secondary'); ?> btn-sm" 
                               title="Pagos" 
                               data-id="<?php echo $orden['id']; ?>" 
                               <?php echo ($orden['tiene_pago'] > 0 ? 'disabled' : ''); ?>>
                                <i class="bi bi-cash-coin"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        </div>
    </div>
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

    <!-- Preloader para carga de imágenes -->
    <div id="preloaderImagenes" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);z-index:99999;align-items:center;justify-content:center;">
      <img src="assets/img/loading-gear.gif" alt="Cargando..." style="width:80px;">
      <p style="text-align:center;">Subiendo imágenes...</p>
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

            // Cargar técnicos dinámicamente al abrir el modal de agregar orden
            const modalAgregarOrden = document.getElementById('modalAgregarOrden');
            modalAgregarOrden.addEventListener('show.bs.modal', function() {
                // Eliminar el select anterior y crear uno nuevo para evitar residuos y duplicados
                const colTecnico = document.querySelector('#modalAgregarOrden .col-md-6.mb-3 select#usuario_tecnico_id').parentElement;
                let oldSelect = document.getElementById('usuario_tecnico_id');
                if (oldSelect) oldSelect.remove();
                const newSelect = document.createElement('select');
                newSelect.className = 'form-control';
                newSelect.id = 'usuario_tecnico_id';
                newSelect.name = 'usuario_tecnico_id';
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Seleccione un técnico';
                newSelect.appendChild(defaultOption);
                colTecnico.appendChild(newSelect);
                fetch('index.php?action=obtenerTecnicos')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const idsAgregados = new Set();
                            data.tecnicos.forEach(tecnico => {
                                if (!idsAgregados.has(tecnico.id)) {
                                    const option = document.createElement('option');
                                    option.value = tecnico.id;
                                    option.textContent = tecnico.nombre;
                                    newSelect.appendChild(option);
                                    idsAgregados.add(tecnico.id);
                                }
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

            // Cargar técnicos dinámicamente al abrir el modal de editar orden
            const modalEditarOrden = document.getElementById('modalEditarOrden');
            modalEditarOrden.addEventListener('show.bs.modal', function() {
                const tecnicoSelect = document.getElementById('editar_usuario_tecnico_id');
                if (tecnicoSelect) {
                    tecnicoSelect.innerHTML = '';
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Seleccione un técnico';
                    tecnicoSelect.appendChild(defaultOption);
                    fetch('index.php?action=obtenerTecnicos')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const tecnicosMap = new Map();
                                data.tecnicos.forEach(tecnico => {
                                    if (!tecnicosMap.has(tecnico.id)) {
                                        tecnicosMap.set(tecnico.id, tecnico.nombre);
                                    }
                                });
                                tecnicosMap.forEach((nombre, id) => {
                                    const option = document.createElement('option');
                                    option.value = id;
                                    option.textContent = nombre;
                                    tecnicoSelect.appendChild(option);
                                });
                            }
                        });
                }
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
                .then(data => { // <-- CORREGIDO
                    // Ocultar la imagen de cargando
                    document.body.removeChild(loadingOverlay);

                    // Habilitar el botón de envío
                    submitButton.disabled = false;

                    if (data.success) {
                        alert('Orden de trabajo registrada exitosamente.');
                        // Guardar el ID de la orden creada en sessionStorage para abrir el modal de pagos tras recargar
                        if (data.orden_id) {
                            sessionStorage.setItem('abrirModalPagoOrdenId', data.orden_id);
                        }
                        guardarScrollAntesDeRecargar();
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
                            .then(response => response.json())
                            .then(res => {
                                if (res.success) {
                                    alert(res.message);
                                    location.reload();
                                } else {
                                    alert(res.message || 'No se pudo eliminar la orden.');
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
                    <input type="file" class="form-control" id="editarImagenes${contadorInputsImagen}" name="imagenes[]" accept="image/*" capture="environment">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarInputImagen(this)">Eliminar</button>
                `;
                inputsExtraImagenesDiv.appendChild(nuevoInputImagen);
            });

        });

    // --- FUNCIONES GLOBALES PARA BOTONES DE LA TABLA DE ÓRDENES ---
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

                    // --- Mostrar datos de pago en el modal de detalles ---
                    fetch('controllers/OrdenPagoController.php?accion=obtener&orden_id=' + orden.id)
                        .then(res => res.json())
                        .then(pagos => {
                            if (Array.isArray(pagos) && pagos.length > 0) {
                                const pago = pagos[0];
                                let htmlPago = `<div style='margin-top:15px; border-top:1px solid #eee; padding-top:10px;'>`;
                                htmlPago += `<h6 style='font-weight:bold;'>Datos de Pago</h6>`;
                                htmlPago += `<p><strong>Método de Pago:</strong> ${pago.metodo_pago ? pago.metodo_pago.charAt(0).toUpperCase() + pago.metodo_pago.slice(1) : ''}</p>`;
                                htmlPago += `<p><strong>Fecha de Pago:</strong> ${pago.fecha_pago || ''}</p>`;
                                htmlPago += `<p><strong style='color:#007bff;'>Costo Total:</strong> <span style='font-weight:bold;color:#007bff;'>$${Number(pago.costo_total).toLocaleString('es-CO')}</span></p>`;
                                const dineroRecibido = pago.dinero_recibido !== undefined ? pago.dinero_recibido : (pago.costo_total - pago.saldo);
                                htmlPago += `<p><strong style='color:#28a745;'>Dinero recibido:</strong> <span style='font-weight:bold;color:#28a745;'>$${Number(dineroRecibido).toLocaleString('es-CO')}</span></p>`;
                                htmlPago += `<p><strong style='color:#dc3545;'>Saldo:</strong> <span style='font-weight:bold;color:#dc3545;'>$${Number(pago.saldo).toLocaleString('es-CO')}</span></p>`;
                                htmlPago += `<p><strong>Descripción Repuestos:</strong> ${pago.descripcion_repuestos || ''}</p>`;
                                htmlPago += `</div>`;
                                document.getElementById('detallePagoSection').innerHTML = htmlPago;
                                document.getElementById('detallePagoSection').style.display = '';
                            } else {
                                document.getElementById('detallePagoSection').innerHTML = '<em>No hay datos de pago registrados para esta orden.</em>';
                                document.getElementById('detallePagoSection').style.display = '';
                            }
                        });
                    // --- Botones de acciones en el modal ---
                    let clienteHtml = `<div style='margin-bottom:10px; padding:10px; background:#f8f9fa; border-radius:6px;'>`;
                    clienteHtml += `<h6 style='font-weight:bold; color:#333;'><i class='bi bi-person'></i> Datos del Cliente</h6>`;
                    clienteHtml += `<p style='margin-bottom:2px;'><strong>Nombre:</strong> ${orden.cliente_nombre || 'No disponible'}</p>`;
                    clienteHtml += `<p style='margin-bottom:2px;'><strong>Teléfono:</strong> ${orden.telefono_cliente || 'No disponible'}</p>`;
                    clienteHtml += `<p style='margin-bottom:2px;'><strong>Identificación:</strong> ${orden.cliente_identificacion || 'No disponible'}</p>`;
                    clienteHtml += `<p style='margin-bottom:2px;'><strong>Correo:</strong> ${orden.cliente_correo || 'No disponible'}</p>`;
                    clienteHtml += `</div>`;

                    let accionesHtml = clienteHtml;
                    accionesHtml += `<div style='margin-top:10px; border-top:1px solid #eee; padding-top:10px;'>`;
                    accionesHtml += `<button class='btn btn-success btn-sm' onclick='cambiarEstadoTerminado(${orden.id})'><i class='bi bi-check2-square'></i> Terminar</button> `;
                    accionesHtml += `<button class='btn btn-dark btn-sm' onclick='cambiarEstadoEntregado(${orden.id})'><i class='bi bi-check-circle'></i> Entregar</button> `;
                    accionesHtml += `<a href='tel:${orden.telefono_cliente ? orden.telefono_cliente : ''}' class='btn btn-primary btn-sm'><i class='bi bi-telephone-fill'></i> Llamar</a> `;
                    accionesHtml += `<a href='https://wa.me/57${(orden.telefono_cliente || '').replace(/^0+/, "")}' target='_blank' class='btn btn-success btn-sm'><i class='bi bi-whatsapp'></i> WhatsApp</a> `;
                    accionesHtml += `<a href='index.php?action=generarRemision&id=${orden.id}' class='btn btn-info btn-sm' target='_blank'><i class='bi bi-eye'></i> Remisión</a> `;
                    accionesHtml += `<button class='btn btn-danger btn-sm' onclick='eliminarOrden(${orden.id})'><i class='bi bi-trash'></i> Eliminar</button> `;
                    accionesHtml += `</div>`;
                    document.getElementById('detalleAcciones').innerHTML = accionesHtml;
                    document.getElementById('detalleAcciones').style.display = '';

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

                    // Seleccionar el técnico correspondiente
                    const tecnicoSelect = document.getElementById('editar_usuario_tecnico_id');
                    if (tecnicoSelect && orden.usuario_tecnico_id) {
                        setTimeout(() => {
                            tecnicoSelect.value = orden.usuario_tecnico_id;
                        }, 300); // Espera a que se carguen las opciones
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
                guardarScrollAntesDeRecargar();
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
                        guardarScrollAntesDeRecargar();
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

    // --- MOVER cambiarEstadoTerminado AL ÁMBITO GLOBAL ---
    function cambiarEstadoTerminado(id) {
        if (confirm('¿Estás seguro de que deseas marcar esta orden como terminada?')) {
            fetch(`index.php?action=cambiarEstadoTerminado&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('La orden ha sido marcada como terminada.');
                        guardarScrollAntesDeRecargar();
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

    // Mostrar preloader al seleccionar imágenes
    ['imagenes', 'editarImagenes'].forEach(function(id) {
      const input = document.getElementById(id);
      if(input) {
        input.addEventListener('change', function() {
          if(this.files && this.files.length > 0) {
            document.getElementById('preloaderImagenes').style.display = 'flex';
          }
        });
      }
    });
    // Ocultar preloader al terminar la carga (en fetch de agregar/editar orden)
    document.getElementById('preloaderImagenes').style.display = 'none';

    // --- PERSISTENCIA DE SCROLL EN ACCIONES DE LA TABLA DE ÓRDENES ---
    function guardarScrollAntesDeRecargar() {
        sessionStorage.setItem('scrollY_ordenes', window.scrollY);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const scrollY = sessionStorage.getItem('scrollY_ordenes');
        if (scrollY !== null) {
            window.scrollTo(0, parseInt(scrollY, 10));
            sessionStorage.removeItem('scrollY_ordenes');
        }
    });
    // --- FIN PERSISTENCIA DE SCROLL ---

    // --- FILTRO DE TABLA POR BUSCADOR ---
    document.getElementById('buscadorOrdenes').addEventListener('input', function() {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('.responsive-table tbody tr').forEach(function(fila) {
            let textoFila = '';
            fila.querySelectorAll('td').forEach(function(td) {
                textoFila += (td.textContent || '').toLowerCase() + ' ';
            });
            if (textoFila.includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });
    // --- FIN FILTRO DE TABLA ---

    // --- MODAL PAGOS ---
    document.addEventListener('DOMContentLoaded', function() {
        function formatMiles(value) {
            value = value.replace(/\D/g, '');
            if (!value) return '';
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        function getIntValue(str) {
            return parseInt(str.replace(/\D/g, '')) || 0;
        }
        function updateSaldo() {
            const costo = getIntValue(document.getElementById('costoTotal').value);
            const abono = getIntValue(document.getElementById('abono').value);
            const saldo = Math.max(costo - abono, 0);
            document.getElementById('saldo').value = saldo ? formatMiles(saldo.toString()) : '';
        }
        ['costoTotal', 'abono'].forEach(function(id) {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function(e) {
                    let val = this.value.replace(/\D/g, '');
                    this.value = formatMiles(val);
                    if (id === 'costoTotal' || id === 'abono') updateSaldo();
                });
            }
        });
        document.querySelectorAll('.btn-secondary[title="Pagos"]').forEach(function(btnPagos) {
            btnPagos.addEventListener('click', function(e) {
                if (this.hasAttribute('disabled')) return; // No abrir modal si está deshabilitado
                e.preventDefault();
                const ordenId = this.dataset.id;
                document.getElementById('pagoOrdenId').value = ordenId;
                document.getElementById('costoTotal').value = '';
                document.getElementById('abono').value = '';
                document.getElementById('saldo').value = '';
                const modalPagos = new bootstrap.Modal(document.getElementById('modalPagosOrden'));
                modalPagos.show();
            });
        });

        // Función global para abrir el modal de pagos desde JS
        window.abrirModalPago = function(ordenId, auto = false) {
            // Busca el botón de pagos de la orden y simula el click si no está deshabilitado
            var btn = document.querySelector('.btn-secondary[title="Pagos"][data-id="' + ordenId + '"]');
            if (btn && !btn.hasAttribute('disabled')) {
                if (auto) sessionStorage.setItem('modalPagoAuto', '1');
                btn.click();
            }
        }

        // --- ENVÍO FORMULARIO PAGOS ---
        const formPagos = document.getElementById('formPagosOrden');
        if (formPagos) {
            formPagos.addEventListener('submit', function(e) {
                e.preventDefault();
                const datos = {
                    orden_id: document.getElementById('pagoOrdenId').value, // Asegura que el nombre coincida con el backend
                    usuario_id: 1, // TODO: Reemplazar por el usuario logueado
                    fecha_pago: new Date().toISOString().slice(0, 19).replace('T', ' '),
                    costo_total: document.getElementById('costoTotal').value.replace(/\./g, ''),
                    dinero_recibido: document.getElementById('abono').value.replace(/\./g, ''),
                    valor_repuestos: document.getElementById('valorRepuestos').value.replace(/\./g, ''),
                    descripcion_repuestos: document.getElementById('descripcionRepuestos').value,
                    metodo_pago: document.getElementById('metodo_pago').value,
                    saldo: document.getElementById('saldo').value.replace(/\./g, '')
                };
                fetch('controllers/OrdenPagoController.php?accion=insertar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(datos)
                })
                .then(res => res.json())
                .then(res => {
                    console.log(res); // Para depuración
                    alert(res.message);
                    if (res.success) {
                        formPagos.reset();
                        document.getElementById('saldo').value = '';
                        // Cerrar modal automáticamente SIEMPRE tras registrar pago
                        const modalPagos = bootstrap.Modal.getInstance(document.getElementById('modalPagosOrden'));
                        if (modalPagos) modalPagos.hide();
                        sessionStorage.removeItem('modalPagoAuto');
                        // --- ACTUALIZAR TABLA Y BOTÓN DE PAGO ---
                        const ordenId = datos.orden_id;
                        // Buscar la fila de la orden
                        const fila = document.querySelector('tr.fila-orden[data-id="' + ordenId + '"]');
                        if (fila) {
                            // Cambiar el botón de pago a verde, deshabilitado y texto "Pagado"
                            const btnPago = fila.querySelector('.btn-secondary[title="Pagos"], .btn-success[title="Pagos"]');
                            if (btnPago) {
                                btnPago.classList.remove('btn-secondary');
                                btnPago.classList.add('btn-success');
                                btnPago.setAttribute('disabled', 'disabled');
                                btnPago.innerHTML = '<i class="bi bi-cash-coin"></i>';
                            }
                        }
                    }
                })
                .catch((err) => { 
                    alert('Error al registrar el pago');
                    console.error(err);
                });
            });
        }
    });

    window.addEventListener('DOMContentLoaded', function() {
        // Revisar si hay una orden para abrir el modal de pagos automáticamente
        const ordenId = sessionStorage.getItem('abrirModalPagoOrdenId');
        if (ordenId) {
            sessionStorage.removeItem('abrirModalPagoOrdenId');
            abrirModalPago(ordenId, true); // true: abierto automáticamente
        }
    });

    // --- FUNCIÓN GLOBAL PARA ELIMINAR ORDEN ---
    function eliminarOrden(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta orden?')) {
            guardarScrollAntesDeRecargar();
            fetch(`index.php?action=eliminarOrden&id=${id}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        alert(res.message);
                        location.reload();
                    } else {
                        alert(res.message || 'No se pudo eliminar la orden.');
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar la orden:', error);
                    alert('Ocurrió un error al intentar eliminar la orden.');
                });
        }
    }

    // --- LÓGICA DE BÚSQUEDA Y CREACIÓN AUTOMÁTICA DE PRODUCTOS ---
    document.addEventListener('DOMContentLoaded', function() {
        // --- PRODUCTOS ---
        const inputBuscadorProducto = document.getElementById('buscadorProducto');
        const listaProductos = document.getElementById('listaProductos');
        const inputProductoId = document.getElementById('producto_id');
        let productos = [];
        let timeoutBusquedaProducto;

        // Función para abrir el modal de nuevo producto con nombre prellenado

        function abrirModalNuevoProductoConNombre(nombreSugerido = '') {
            console.log('Abriendo modal de nuevo producto con nombre sugerido:', nombreSugerido);
            
            const modalNuevoProducto = new bootstrap.Modal(document.getElementById('modalNuevoProducto'), {
                focus: false,
                backdrop: 'static'
            });
            modalNuevoProducto.show();
            
            // Prellenar el campo nombre si se proporciona
            if (nombreSugerido) {
                setTimeout(() => {
                    document.getElementById('nuevoProductoNombre').value = nombreSugerido;
                    // Poner el foco en el campo de precio de venta para continuar el flujo
                    document.getElementById('nuevoProductoPrecioVenta').focus();
                }, 100);
            }
        }

        // Búsqueda de productos con debounce
        if (inputBuscadorProducto) {
            inputBuscadorProducto.addEventListener('input', function() {
                const query = this.value.trim();
                
                // Limpiar timeout anterior
                clearTimeout(timeoutBusquedaProducto);
                
                if (query.length > 1) {
                    // Agregar un pequeño delay para evitar muchas peticiones
                    timeoutBusquedaProducto = setTimeout(() => {
                        fetch(`index.php?action=buscarProducto&query=${encodeURIComponent(query)}`)
                            .then(r => r.json())
                            .then(data => {
                                productos = data.productos || [];
                                const listaActiva = window.listaProductosActiva || listaProductos;
                                listaActiva.innerHTML = '';
                                listaActiva.style.display = 'none';
                                
                                if (productos.length > 0) {
                                    // Si hay resultados, mostrarlos
                                    listaActiva.style.display = 'block';
                                    productos.forEach(p => {
                                        const li = document.createElement('li');
                                        li.className = 'list-group-item list-group-item-action';
                                        li.textContent = `${p.nombre} - $${parseFloat(p.precio_venta || 0).toLocaleString('es-CO')} (Stock: ${p.stock || 0})`;
                                        li.dataset.id = p.id;
                                        li.dataset.nombre = p.nombre;
                                        li.dataset.precio = p.precio_venta || 0;
                                        listaActiva.appendChild(li);
                                    });
                                    console.log(`Encontrados ${productos.length} productos para: "${query}"`);
                                } else {
                                    // Si no hay resultados, sugerir crear producto
                                    console.log(`No se encontraron productos para: "${query}". Mostrando opción de crear producto.`);
                                    listaActiva.style.display = 'block';
                                    const li = document.createElement('li');
                                    li.className = 'list-group-item list-group-item-action text-primary';
                                    li.innerHTML = `<i class="bi bi-plus-circle me-2"></i>Crear nuevo producto: "${query}"`;
                                    li.dataset.action = 'create';
                                    li.dataset.nombre = query;
                                    listaActiva.appendChild(li);
                                }
                            })
                            .catch(error => {
                                console.error('Error buscando productos:', error);
                                const listaActiva = window.listaProductosActiva || listaProductos;
                                listaActiva.innerHTML = '';
                                listaActiva.style.display = 'block';
                                const li = document.createElement('li');
                                li.className = 'list-group-item list-group-item-action text-primary';
                                li.innerHTML = `<i class="bi bi-plus-circle me-2"></i>Crear nuevo producto: "${query}"`;
                                li.dataset.action = 'create';
                                li.dataset.nombre = query;
                                listaActiva.appendChild(li);
                            });
                    }, 300); // Delay de 300ms
                } else {
                    const listaActiva = window.listaProductosActiva || listaProductos;
                    listaActiva.innerHTML = '';
                    listaActiva.style.display = 'none';
                }
                inputProductoId.value = '';
            });

            // Manejo de selección de productos
            listaProductos.addEventListener('click', function(event) {
                if (event.target.tagName === 'LI') {
                    const selectedItem = event.target;
                    
                    // Verificar si es la opción de crear nuevo producto
                    if (selectedItem.dataset.action === 'create') {
                        const nombreSugerido = selectedItem.dataset.nombre;
                        console.log('Detectada opción de crear producto para:', nombreSugerido);
                        
                        // Limpiar lista y input
                        listaProductos.style.display = 'none';
                        inputBuscadorProducto.value = '';
                        inputProductoId.value = '';
                        
                        // Abrir modal de nuevo producto y prellenar el nombre
                        abrirModalNuevoProductoConNombre(nombreSugerido);
                        return;
                    }
                    
                    // Seleccionar producto existente
                    inputBuscadorProducto.value = selectedItem.dataset.nombre;
                    inputProductoId.value = selectedItem.dataset.id;
                    
                    listaProductos.style.display = 'none';
                    
                    console.log('Producto seleccionado:', {
                        id: selectedItem.dataset.id,
                        nombre: selectedItem.dataset.nombre,
                        precio: selectedItem.dataset.precio
                    });
                }
            });

            // Ocultar lista al hacer clic fuera
            document.addEventListener('click', function(event) {
                const listaActiva = window.listaProductosActiva || listaProductos;
                if (!inputBuscadorProducto.contains(event.target) && !listaActiva.contains(event.target)) {
                    listaActiva.style.display = 'none';
                }
            });
        }

        // Los event listeners para crear nuevo producto se manejan más abajo en el código
        // para evitar conflictos con el modal de venta

        // Alternativa para el botón si no existe con el ID específico
        const formNuevoProducto = document.getElementById('formNuevoProducto');
        if (formNuevoProducto) {
        }
    });

    </script>
</div>

<?php
require_once 'footer.php';
?>