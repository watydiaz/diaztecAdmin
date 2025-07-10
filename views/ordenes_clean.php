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

/* Avatar circular */
.avatar-circle {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
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
                                       placeholder="Buscar cliente por nombre o identificación..."
                                       onkeyup="buscarClientes(this.value)">
                                <button class="btn btn-outline-success" type="button" 
                                        onclick="mostrarModalAgregarCliente()" title="Agregar nuevo cliente">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <input type="hidden" id="cliente_id" name="cliente_id" required>
                            <div id="resultadosClientes" class="mt-2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="usuario_tecnico_id" class="form-label fw-bold">
                                <i class="fas fa-user-cog me-2"></i>Técnico Asignado
                            </label>
                            <select class="form-select" id="usuario_tecnico_id" name="usuario_tecnico_id">
                                <option value="">Seleccione un técnico</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="marca" class="form-label fw-bold">
                                <i class="fas fa-tag me-2"></i>Marca *
                            </label>
                            <input type="text" class="form-control" id="marca" name="marca" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modelo" class="form-label fw-bold">
                                <i class="fas fa-laptop me-2"></i>Modelo *
                            </label>
                            <input type="text" class="form-control" id="modelo" name="modelo" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="imei_serial" class="form-label fw-bold">
                                <i class="fas fa-barcode me-2"></i>IMEI/Serial
                            </label>
                            <input type="text" class="form-control" id="imei_serial" name="imei_serial">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label fw-bold">
                                <i class="fas fa-info-circle me-2"></i>Estado *
                            </label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="en-proceso">En Proceso</option>
                                <option value="terminado">Terminado</option>
                                <option value="entregado">Entregado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prioridad" class="form-label fw-bold">
                                <i class="fas fa-flag me-2"></i>Prioridad *
                            </label>
                            <select class="form-select" id="prioridad" name="prioridad" required>
                                <option value="baja">Baja</option>
                                <option value="media" selected>Media</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_ingreso" class="form-label fw-bold">
                                <i class="fas fa-calendar-plus me-2"></i>Fecha de Ingreso *
                            </label>
                            <input type="datetime-local" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
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
                            <i class="fas fa-exclamation-circle me-2"></i>Falla Reportada *
                        </label>
                        <textarea class="form-control" id="falla_reportada" name="falla_reportada" rows="3" 
                                  placeholder="Describa detalladamente la falla reportada por el cliente" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="diagnostico" class="form-label fw-bold">
                            <i class="fas fa-stethoscope me-2"></i>Diagnóstico Inicial
                        </label>
                        <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" 
                                  placeholder="Diagnóstico inicial del técnico (opcional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="contraseña_equipo" class="form-label fw-bold">
                            <i class="fas fa-lock me-2"></i>Contraseña del Equipo
                        </label>
                        <input type="text" class="form-control" id="contraseña_equipo" name="contraseña_equipo" 
                               placeholder="Contraseña, PIN o patrón de bloqueo">
                    </div>

                    <div class="mb-3">
                        <label for="imagenes" class="form-label fw-bold">
                            <i class="fas fa-camera me-2"></i>Subir Imágenes
                        </label>
                        <input type="file" class="form-control" id="imagenes" name="imagenes[]" 
                               accept="image/*" multiple capture="environment">
                        <div class="form-text">Puede subir múltiples imágenes del equipo y la falla</div>
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

<!-- Modal para agregar cliente rápido -->
<div class="modal fade" id="modalAgregarClienteRapido" tabindex="-1" aria-labelledby="modalAgregarClienteRapidoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarClienteRapidoLabel">
                    <i class="fas fa-user-plus me-2"></i>Agregar Cliente Rápido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAgregarClienteRapido">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreCliente" class="form-label fw-bold">
                            <i class="fas fa-user me-2"></i>Nombre Completo *
                        </label>
                        <input type="text" class="form-control" id="nombreCliente" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="identificacionCliente" class="form-label fw-bold">
                            <i class="fas fa-id-card me-2"></i>Identificación *
                        </label>
                        <input type="text" class="form-control" id="identificacionCliente" name="identificacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefonoCliente" class="form-label fw-bold">
                            <i class="fas fa-phone me-2"></i>Teléfono
                        </label>
                        <input type="text" class="form-control" id="telefonoCliente" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="emailCliente" class="form-label fw-bold">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control" id="emailCliente" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient" id="btnGuardarClienteRapido">
                        <i class="fas fa-save me-2"></i>Guardar y Seleccionar
                    </button>
                </div>
            </form>
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
    document.getElementById('formAgregarClienteRapido').addEventListener('submit', guardarClienteRapido);
    
    // Cargar técnicos al inicializar
    cargarTecnicos();
    
    // Establecer fecha actual por defecto
    const fechaActual = new Date();
    fechaActual.setMinutes(fechaActual.getMinutes() - fechaActual.getTimezoneOffset());
    document.getElementById('fecha_ingreso').value = fechaActual.toISOString().slice(0, 16);
    
    // Actualizar fecha en header
    document.getElementById('fechaActual').textContent = new Date().toLocaleDateString('es-CO', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

// Función para mostrar modal agregar orden
function mostrarModalAgregarOrden() {
    document.getElementById('formAgregarOrden').reset();
    
    // Restablecer fecha actual
    const fechaActual = new Date();
    fechaActual.setMinutes(fechaActual.getMinutes() - fechaActual.getTimezoneOffset());
    document.getElementById('fecha_ingreso').value = fechaActual.toISOString().slice(0, 16);
    
    // Limpiar resultados de búsqueda
    document.getElementById('resultadosClientes').innerHTML = '';
    document.getElementById('cliente_id').value = '';
    
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
                    select.innerHTML += `<option value="${tecnico.id}">${tecnico.nombre}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar técnicos:', error);
        });
}

// Función para buscar clientes
function buscarClientes(query) {
    if (query.length < 2) {
        document.getElementById('resultadosClientes').innerHTML = '';
        return;
    }
    
    fetch(`index.php?action=buscarClientes&q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            const resultados = document.getElementById('resultadosClientes');
            
            if (data.success && data.clientes.length > 0) {
                let html = '<div class="list-group">';
                data.clientes.forEach(cliente => {
                    html += `
                        <button type="button" class="list-group-item list-group-item-action" 
                                onclick="seleccionarCliente(${cliente.id}, '${cliente.nombre}')">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${cliente.nombre}</strong><br>
                                    <small class="text-muted">${cliente.identificacion} - ${cliente.telefono}</small>
                                </div>
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                        </button>
                    `;
                });
                html += '</div>';
                resultados.innerHTML = html;
            } else {
                resultados.innerHTML = '<div class="alert alert-info">No se encontraron clientes</div>';
            }
        })
        .catch(error => {
            console.error('Error al buscar clientes:', error);
        });
}

// Función para seleccionar cliente
function seleccionarCliente(id, nombre) {
    document.getElementById('cliente_id').value = id;
    document.getElementById('buscarCliente').value = nombre;
    document.getElementById('resultadosClientes').innerHTML = '';
}

// Función para mostrar modal agregar cliente
function mostrarModalAgregarCliente() {
    document.getElementById('formAgregarClienteRapido').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarClienteRapido'));
    modal.show();
}

// Función para guardar cliente rápido
function guardarClienteRapido(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarClienteRapido');
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    fetch('index.php?action=agregarCliente', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Seleccionar el cliente recién creado
            seleccionarCliente(data.cliente_id, formData.get('nombre'));
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarClienteRapido'));
            modal.hide();
            
            Swal.fire({
                title: '¡Éxito!',
                text: 'Cliente agregado y seleccionado',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar y Seleccionar';
    });
}

// Función para guardar orden
function guardarOrden(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarOrden');
    
    // Validar cliente seleccionado
    if (!document.getElementById('cliente_id').value) {
        Swal.fire('Error', 'Debe seleccionar un cliente', 'error');
        return;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    fetch('index.php?action=agregarOrden', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Orden de trabajo creada exitosamente',
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
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Orden';
    });
}

// Función para filtrar órdenes
function filtrarOrdenes() {
    const buscar = document.getElementById('buscadorOrdenes').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    const prioridad = document.getElementById('filtroPrioridad').value.toLowerCase();
    const filas = document.querySelectorAll('#bodyTablaOrdenes tr');
    
    filas.forEach(function(fila) {
        const textoFila = fila.textContent.toLowerCase();
        const estadoFila = fila.querySelector('.estado-pendiente, .estado-en-proceso, .estado-terminado, .estado-entregado');
        const prioridadFila = fila.querySelector('.prioridad-baja, .prioridad-media, .prioridad-alta');
        
        let mostrar = true;
        
        // Filtro de búsqueda
        if (buscar && !textoFila.includes(buscar)) {
            mostrar = false;
        }
        
        // Filtro de estado
        if (estado && estadoFila && !estadoFila.className.includes(`estado-${estado}`)) {
            mostrar = false;
        }
        
        // Filtro de prioridad
        if (prioridad && prioridadFila && !prioridadFila.className.includes(`prioridad-${prioridad}`)) {
            mostrar = false;
        }
        
        fila.style.display = mostrar ? '' : 'none';
    });
}

// Función para limpiar filtros
function limpiarFiltros() {
    document.getElementById('buscadorOrdenes').value = '';
    document.getElementById('filtroEstado').value = '';
    document.getElementById('filtroPrioridad').value = '';
    filtrarOrdenes();
}

// Función para filtrar por estado específico
function filtrarPorEstado(estado) {
    document.getElementById('filtroEstado').value = estado;
    filtrarOrdenes();
}

// Función para filtrar por prioridad específica
function filtrarPorPrioridad(prioridad) {
    document.getElementById('filtroPrioridad').value = prioridad;
    filtrarOrdenes();
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

// Función para mostrar imagen completa
function mostrarImagenCompleta(imagen) {
    Swal.fire({
        title: 'Imagen de la Orden',
        imageUrl: `assets/img/${imagen}`,
        imageAlt: 'Imagen de la orden',
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto',
        customClass: {
            image: 'img-fluid'
        }
    });
}

// Función para abrir modal editar orden
function abrirModalEditarOrden(id) {
    Swal.fire({
        title: 'Editar Orden',
        text: 'Funcionalidad en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

// Función para ver detalles de orden
function verDetallesOrden(id) {
    Swal.fire({
        title: 'Detalles de Orden',
        text: 'Funcionalidad en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

// Función para imprimir orden
function imprimirOrden(id) {
    Swal.fire({
        title: 'Imprimir Orden',
        text: 'Funcionalidad en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

// Función para confirmar eliminación
function confirmarEliminacionOrden(event, id) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará la orden de trabajo permanentemente',
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
</script>

<?php
require_once 'footer.php';
?>
