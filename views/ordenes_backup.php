<?php
require_once 'header.php';
?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.dashboard-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 25%, #4a4a4a 50%, #6a6a6a 75%, #8a8a8a 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.welcome-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    text-align: center;
    color: #212529;
    border: 2px solid #ced4da;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.content-section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin: 20px 0;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.section-title {
    color: #333;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 3px solid #4a6fa5;
    padding-bottom: 10px;
}

.search-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    border: 2px solid #dee2e6;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 20px;
}

.table th {
    background-color: #000000;
    color: white;
    border: none;
    font-weight: 500;
    padding: 15px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 12px 15px;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.btn-action {
    margin: 2px;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: none;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    color: white;
}

.floating-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.floating-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.35);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
    padding: 20px 25px;
}

.modal-header .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.modal-header .btn-close:hover {
    opacity: 1;
}

.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.2);
    overflow: hidden;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    background: #f8f9fa;
    border: none;
    padding: 20px 25px;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px 0 0 10px;
}

/* Estados de órdenes */
.estado-pendiente { 
    background: linear-gradient(45deg, #ffc107, #e0a800);
    color: #212529; 
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
}

.estado-en-proceso { 
    background: linear-gradient(45deg, #17a2b8, #138496);
    color: white; 
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
}

.estado-terminado { 
    background: linear-gradient(45deg, #28a745, #218838);
    color: white; 
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
}

.estado-entregado { 
    background: linear-gradient(45deg, #6c757d, #5a6268);
    color: white; 
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
}

/* Prioridades */
.prioridad-baja { 
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white; 
    font-weight: bold;
    padding: 6px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
}

.prioridad-media { 
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    color: white; 
    font-weight: bold;
    padding: 6px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
}

.prioridad-alta { 
    background: linear-gradient(45deg, #dc3545, #e83e8c);
    color: white; 
    font-weight: bold;
    padding: 6px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
}

/* Imagen miniatura */
.miniatura-orden {
    width: 50px;
    height: 50px;
    object-fit: cover;
    cursor: pointer;
    border-radius: 8px;
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}

.miniatura-orden:hover {
    transform: scale(1.1);
    border-color: #667eea;
}

/* Avatar circular para órdenes */
.avatar-circle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

/* Carousel para imágenes */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0,0,0,0.7);
    border-radius: 50%;
    width: 2.5rem;
    height: 2.5rem;
    background-size: 60% 60%;
}

.carousel-control-prev,
.carousel-control-next {
    filter: drop-shadow(0 0 2px #fff);
}

/* Texto truncado */
.texto-truncado {
    max-width: 150px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .content-section {
        padding: 20px;
        margin: 15px 0;
    }
    
    .search-box {
        padding: 15px;
    }
    
    .table th, .table td {
        font-size: 0.875rem;
        padding: 10px 8px;
    }
    
    .btn-action {
        padding: 6px 8px;
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .welcome-header {
        padding: 20px;
    }
    
    .content-section {
        padding: 15px;
    }
    
    .miniatura-orden {
        width: 40px;
        height: 40px;
    }
}
</style>

<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Header de bienvenida -->
        <div class="welcome-header">
            <h1><i class="fas fa-tools me-3"></i>Gestión de Órdenes de Trabajo</h1>
            <p class="mb-0">Centro de Control de Órdenes de Reparación y Mantenimiento</p>
            <small id="fechaActual"></small>
        </div>
        
        <!-- Controles superiores -->
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Órdenes de Trabajo
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-info" onclick="exportarOrdenes()" title="Exportar órdenes">
                        <i class="fas fa-download me-2"></i>Exportar
                    </button>
                    <button class="btn btn-outline-success" onclick="filtrarPorEstado('pendiente')" title="Ver pendientes">
                        <i class="fas fa-clock me-2"></i>Pendientes
                    </button>
                    <button class="btn btn-outline-warning" onclick="filtrarPorPrioridad('alta')" title="Ver alta prioridad">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alta Prioridad
                    </button>
                </div>
            </div>

            <!-- Buscador mejorado -->
            <div class="search-box">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-search me-2"></i>Buscar orden
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="buscadorOrdenes" 
                                   placeholder="Cliente, marca, modelo, falla..." 
                                   onkeyup="filtrarOrdenes()">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-filter me-2"></i>Estado
                        </label>
                        <select class="form-select" id="filtroEstado" onchange="filtrarOrdenes()">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en-proceso">En Proceso</option>
                            <option value="terminado">Terminado</option>
                            <option value="entregado">Entregado</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-sort me-2"></i>Prioridad
                        </label>
                        <select class="form-select" id="filtroPrioridad" onchange="filtrarOrdenes()">
                            <option value="">Todas</option>
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                            <i class="fas fa-redo me-2"></i>Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de órdenes -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaOrdenes">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>ID</th>
                            <th><i class="fas fa-image me-2"></i>Imagen</th>
                            <th><i class="fas fa-user me-2"></i>Cliente</th>
                            <th><i class="fas fa-user-cog me-2"></i>Técnico</th>
                            <th><i class="fas fa-tag me-2"></i>Marca</th>
                            <th><i class="fas fa-laptop me-2"></i>Modelo</th>
                            <th><i class="fas fa-exclamation-circle me-2"></i>Falla</th>
                            <th><i class="fas fa-info-circle me-2"></i>Estado</th>
                            <th><i class="fas fa-flag me-2"></i>Prioridad</th>
                            <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                            <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaOrdenes">
                        <?php if (isset($ordenes) && !empty($ordenes)): ?>
                            <?php foreach ($ordenes as $orden): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?php echo $orden['id']; ?></td>
                                    <td>
                                        <?php if (!empty($orden['imagen'])): ?>
                                            <img src="assets/img/<?php echo $orden['imagen']; ?>" 
                                                 class="miniatura-orden" 
                                                 alt="Imagen orden" 
                                                 onclick="mostrarImagenCompleta('<?php echo $orden['imagen']; ?>')">
                                        <?php else: ?>
                                            <div class="avatar-circle">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="texto-truncado fw-bold" title="<?php echo htmlspecialchars($orden['cliente_nombre'] ?? 'Sin cliente'); ?>">
                                                <?php echo $orden['cliente_nombre'] ?? 'Sin cliente'; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <i class="fas fa-user-cog"></i>
                                            </div>
                                            <span class="texto-truncado" title="<?php echo htmlspecialchars($orden['tecnico_nombre'] ?? 'Sin asignar'); ?>">
                                                <?php echo $orden['tecnico_nombre'] ?? 'Sin asignar'; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-info"><?php echo $orden['marca'] ?? 'N/A'; ?></td>
                                    <td><?php echo $orden['modelo'] ?? 'N/A'; ?></td>
                                    <td class="texto-truncado" title="<?php echo htmlspecialchars($orden['falla_reportada'] ?? 'Sin descripción'); ?>">
                                        <?php echo $orden['falla_reportada'] ?? 'Sin descripción'; ?>
                                    </td>
                                    <td>
                                        <span class="estado-<?php echo str_replace(' ', '-', strtolower($orden['estado'] ?? 'pendiente')); ?>">
                                            <?php echo ucfirst($orden['estado'] ?? 'Pendiente'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="prioridad-<?php echo strtolower($orden['prioridad'] ?? 'media'); ?>">
                                            <?php echo ucfirst($orden['prioridad'] ?? 'Media'); ?>
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        <?php echo date('d/m/Y', strtotime($orden['fecha_ingreso'] ?? 'now')); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary btn-action" 
                                                    onclick="abrirModalEditarOrden(<?php echo $orden['id']; ?>)" 
                                                    title="Editar orden">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info btn-action" 
                                                    onclick="verDetallesOrden(<?php echo $orden['id']; ?>)" 
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success btn-action" 
                                                    onclick="imprimirOrden(<?php echo $orden['id']; ?>)" 
                                                    title="Imprimir">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-action" 
                                                    onclick="confirmarEliminacionOrden(event, <?php echo $orden['id']; ?>)" 
                                                    title="Eliminar orden">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay órdenes de trabajo registradas</h5>
                                    <p class="text-muted">Haz clic en el botón + para agregar la primera orden</p>
                                </td>
                            </tr>
                        <?php endif; ?>

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
                                <td class="fw-bold text-primary"><?php echo $orden['id']; ?></td>
                                <td class="text-center">
                                    <?php 
                                    $miniatura = '';
                                    if (!empty($orden['imagen_url'])) {
                                        $imagenes = explode(',', $orden['imagen_url']);
                                        $miniatura = $imagenes[0];
                                    }
                                    ?>
                                    <?php if ($miniatura): ?>
                                        <img src="<?php echo $miniatura; ?>" alt="Imagen principal" 
                                             class="miniatura-orden" 
                                             onclick="verImagenModal('<?php echo htmlspecialchars(json_encode($orden['imagen_url']), ENT_QUOTES, 'UTF-8'); ?>')">
                                    <?php else: ?>
                                        <div class="text-muted d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="texto-truncado fw-bold" title="<?php echo htmlspecialchars($orden['cliente_nombre']); ?>">
                                            <?php echo $orden['cliente_nombre']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="texto-truncado text-info" title="<?php echo htmlspecialchars($orden['tecnico_nombre']); ?>">
                                        <i class="fas fa-user-cog me-1"></i><?php echo $orden['tecnico_nombre']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="texto-truncado fw-bold" title="<?php echo htmlspecialchars($orden['marca']); ?>">
                                        <?php echo $orden['marca']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="texto-truncado" title="<?php echo htmlspecialchars($orden['modelo']); ?>">
                                        <?php echo $orden['modelo']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="texto-truncado text-muted" title="<?php echo htmlspecialchars($orden['falla_reportada']); ?>">
                                        <?php echo mb_strimwidth($orden['falla_reportada'], 0, 40, '...'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="estado-<?php echo str_replace('_', '-', $orden['estado']); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $orden['estado'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="prioridad-<?php echo $orden['prioridad']; ?>">
                                        <?php echo ucfirst($orden['prioridad']); ?>
                                    </span>
                                </td>
                                <td class="text-muted" title="<?php echo $orden['fecha_ingreso']; ?>">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <?php echo date('d/m/Y', strtotime($orden['fecha_ingreso'])); ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary btn-action" 
                                                onclick="abrirModalEditarOrden(<?php echo $orden['id']; ?>)" 
                                                title="Editar orden">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success btn-action" 
                                                onclick="cambiarEstadoTerminado(<?php echo $orden['id']; ?>)" 
                                                title="Marcar como terminado">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info btn-action" 
                                                onclick="cambiarEstadoEntregado(<?php echo $orden['id']; ?>)" 
                                                title="Marcar como entregado">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        <a href="tel:<?php echo $orden['telefono_cliente']; ?>" 
                                           class="btn btn-sm btn-outline-success btn-action" 
                                           title="Llamar cliente">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                        <a href="https://wa.me/57<?php echo ltrim($orden['telefono_cliente'], '0'); ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-success btn-action" 
                                           title="WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <a href="index.php?action=generarRemision&id=<?php echo $orden['id']; ?>" 
                                           class="btn btn-sm btn-outline-info btn-action" 
                                           title="Ver remisión">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm <?php echo ($orden['tiene_pago'] > 0 ? 'btn-outline-success' : 'btn-outline-warning'); ?> btn-action" 
                                                onclick="gestionarPagos(<?php echo $orden['id']; ?>)" 
                                                title="<?php echo ($orden['tiene_pago'] > 0 ? 'Ver pagos' : 'Registrar pago'); ?>">
                                            <i class="fas fa-dollar-sign"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-action" 
                                                onclick="eliminarOrden(<?php echo $orden['id']; ?>)" 
                                                title="Eliminar orden">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Botón flotante para agregar orden -->
<button class="floating-btn" onclick="mostrarModalAgregarOrden()" title="Agregar nueva orden">
    <i class="fas fa-plus"></i>
</button>

<!-- Modal para agregar orden -->
<div class="modal fade" id="modalAgregarOrden" tabindex="-1" aria-labelledby="modalAgregarOrdenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarOrdenLabel">
                    <i class="fas fa-plus-circle me-2"></i>Agregar Orden de Trabajo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAgregarOrden" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="buscarCliente" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Cliente *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="buscarCliente" 
                                       placeholder="Buscar cliente por nombre o identificación">
                            </div>
                            <ul class="list-group mt-2" id="listaClientes" style="display: none;"></ul>
                            <input type="hidden" id="cliente_id" name="cliente_id">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="usuario_tecnico_id" class="form-label fw-bold">
                                <i class="fas fa-user-cog me-2"></i>Técnico *
                            </label>
                            <select class="form-select" id="usuario_tecnico_id" name="usuario_tecnico_id" required>
                                <option value="">Seleccione un técnico</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="marca" class="form-label fw-bold">
                                <i class="fas fa-tag me-2"></i>Marca
                            </label>
                            <input type="text" class="form-control" id="marca" name="marca">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modelo" class="form-label fw-bold">
                                <i class="fas fa-laptop me-2"></i>Modelo
                            </label>
                            <input type="text" class="form-control" id="modelo" name="modelo">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="imei_serial" class="form-label fw-bold">
                                <i class="fas fa-hashtag me-2"></i>IMEI/Serial
                            </label>
                            <input type="text" class="form-control" id="imei_serial" name="imei_serial">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contraseña_equipo" class="form-label fw-bold">
                                <i class="fas fa-lock me-2"></i>Contraseña del Equipo
                            </label>
                            <input type="text" class="form-control" id="contraseña_equipo" name="contraseña_equipo">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label fw-bold">
                                <i class="fas fa-info-circle me-2"></i>Estado
                            </label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="terminado">Terminado</option>
                                <option value="entregado">Entregado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prioridad" class="form-label fw-bold">
                                <i class="fas fa-flag me-2"></i>Prioridad
                            </label>
                            <select class="form-select" id="prioridad" name="prioridad">
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_ingreso" class="form-label fw-bold">
                                <i class="fas fa-calendar me-2"></i>Fecha de Ingreso
                            </label>
                            <input type="datetime-local" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_entrega_estimada" class="form-label fw-bold">
                                <i class="fas fa-calendar-check me-2"></i>Fecha de Entrega Estimada
                            </label>
                            <input type="datetime-local" class="form-control" id="fecha_entrega_estimada" name="fecha_entrega_estimada">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="falla_reportada" class="form-label fw-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i>Falla Reportada
                        </label>
                        <textarea class="form-control" id="falla_reportada" name="falla_reportada" rows="3" 
                                  placeholder="Describe detalladamente la falla reportada..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="imagenes" class="form-label fw-bold">
                            <i class="fas fa-images me-2"></i>Subir Imágenes
                        </label>
                        <input type="file" class="form-control" id="imagenes" name="imagenes[]" 
                               accept="image/*" multiple capture="environment">
                        <small class="text-muted">Puedes subir múltiples imágenes del equipo</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient" id="btnGuardarOrden">
                        <i class="fas fa-save me-2"></i>Guardar Orden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver imágenes -->
<div class="modal fade" id="modalImagenes" tabindex="-1" aria-labelledby="modalImagenesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImagenesLabel">
                    <i class="fas fa-images me-2"></i>Imágenes de la Orden
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="carouselImagenes" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselImagenesInner">
                        <!-- Se cargan dinámicamente -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
let ordenes = [];

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners para formularios
    document.getElementById('formAgregarOrden').addEventListener('submit', guardarOrden);
    
    // Actualizar fecha
    document.getElementById('fechaActual').textContent = new Date().toLocaleDateString('es-CO', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Cargar técnicos al inicializar
    cargarTecnicos();
});

// Función para mostrar modal agregar orden
function mostrarModalAgregarOrden() {
    document.getElementById('formAgregarOrden').reset();
    cargarTecnicos();
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarOrden'));
    modal.show();
}

// Función para cargar técnicos
function cargarTecnicos() {
    fetch('index.php?action=obtenerTecnicos')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('usuario_tecnico_id');
            select.innerHTML = '<option value="">Seleccione un técnico</option>';
            
            if (data.success && data.tecnicos) {
                data.tecnicos.forEach(tecnico => {
                    const option = document.createElement('option');
                    option.value = tecnico.id;
                    option.textContent = tecnico.nombre;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar técnicos:', error);
        });
}

// Función para guardar orden
function guardarOrden(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarOrden');
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    fetch('index.php?action=agregarOrden', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Orden agregada exitosamente',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Cerrar modal y recargar
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarOrden'));
            modal.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al guardar orden', 'error');
    })
    .finally(() => {
        // Restaurar botón
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Orden';
    });
}

// Función para filtrar órdenes
function filtrarOrdenes() {
    const busqueda = document.getElementById('buscadorOrdenes').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value;
    const prioridad = document.getElementById('filtroPrioridad').value;
    
    const filas = document.querySelectorAll('#bodyTablaOrdenes tr');
    
    filas.forEach(function(fila) {
        let textoFila = '';
        fila.querySelectorAll('td').forEach(function(td) {
            textoFila += (td.textContent || '').toLowerCase() + ' ';
        });
        
        const cumpleBusqueda = !busqueda || textoFila.includes(busqueda);
        
        let cumpleEstado = true;
        if (estado) {
            const estadoTexto = fila.querySelector('td:nth-child(8)').textContent.toLowerCase();
            cumpleEstado = estadoTexto.includes(estado);
        }
        
        let cumplePrioridad = true;
        if (prioridad) {
            const prioridadTexto = fila.querySelector('td:nth-child(9)').textContent.toLowerCase();
            cumplePrioridad = prioridadTexto.includes(prioridad);
        }
        
        if (cumpleBusqueda && cumpleEstado && cumplePrioridad) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// Función para limpiar filtros
function limpiarFiltros() {
    document.getElementById('buscadorOrdenes').value = '';
    document.getElementById('filtroEstado').value = '';
    document.getElementById('filtroPrioridad').value = '';
    filtrarOrdenes();
}

// Función para filtrar por estado
function filtrarPorEstado(estado) {
    document.getElementById('filtroEstado').value = estado;
    filtrarOrdenes();
}

// Función para filtrar por prioridad
function filtrarPorPrioridad(prioridad) {
    document.getElementById('filtroPrioridad').value = prioridad;
    filtrarOrdenes();
}

// Función para ver imagen modal
function verImagenModal(imagenesJson) {
    try {
        const imagenes = JSON.parse(imagenesJson);
        const carouselInner = document.getElementById('carouselImagenesInner');
        carouselInner.innerHTML = '';
        
        if (typeof imagenes === 'string') {
            const imagenesArray = imagenes.split(',');
            imagenesArray.forEach((imagen, index) => {
                const div = document.createElement('div');
                div.className = `carousel-item ${index === 0 ? 'active' : ''}`;
                div.innerHTML = `<img src="${imagen.trim()}" class="d-block w-100" alt="Imagen ${index + 1}">`;
                carouselInner.appendChild(div);
            });
        }
        
        const modal = new bootstrap.Modal(document.getElementById('modalImagenes'));
        modal.show();
    } catch (error) {
        console.error('Error al procesar imágenes:', error);
        Swal.fire('Error', 'No se pudieron cargar las imágenes', 'error');
    }
}

// Función para cambiar estado a terminado
function cambiarEstadoTerminado(id) {
    Swal.fire({
        title: '¿Marcar como terminado?',
        text: 'Se cambiará el estado de la orden a terminado',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, marcar como terminado',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            cambiarEstadoOrden(id, 'terminado');
        }
    });
}

// Función para cambiar estado a entregado
function cambiarEstadoEntregado(id) {
    Swal.fire({
        title: '¿Marcar como entregado?',
        text: 'Se cambiará el estado de la orden a entregado',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, marcar como entregado',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            cambiarEstadoOrden(id, 'entregado');
        }
    });
}

// Función para cambiar estado de orden
function cambiarEstadoOrden(id, nuevoEstado) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('estado', nuevoEstado);
    
    fetch('index.php?action=cambiarEstadoOrden', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: `Orden marcada como ${nuevoEstado}`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión', 'error');
    });
}

// Función para eliminar orden
function eliminarOrden(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará la orden permanentemente',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`index.php?action=eliminarOrden&id=${id}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: 'Orden eliminada exitosamente',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error de conexión al eliminar orden', 'error');
            });
        }
    });
}

// Función para gestionar pagos
function gestionarPagos(id) {
    // Aquí puedes implementar la lógica para gestionar pagos
    Swal.fire({
        title: 'Gestión de Pagos',
        text: 'Funcionalidad de pagos en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

// Función para exportar órdenes
function exportarOrdenes() {
    Swal.fire({
        title: 'Exportar Órdenes',
        text: 'Funcionalidad de exportación en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}
</script>
</div>

<?php
require_once 'footer.php';
?>