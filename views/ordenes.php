<?php
require_once 'header.php';
?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Hoja de estilos específica para el módulo de órdenes -->
<link rel="stylesheet" href="assets/css/modules/ordenes.css">

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
                            <th><i class="fas fa-tag me-2"></i>Marca</th>
                            <th><i class="fas fa-laptop me-2"></i>Modelo</th>
                            <th><i class="fas fa-exclamation-circle me-2"></i>Falla</th>
                            <th><i class="fas fa-info-circle me-2"></i>Estado</th>
                            <th><i class="fas fa-flag me-2"></i>Prioridad</th>
                            <th><i class="fas fa-dollar-sign me-2"></i>Total Reparación</th>
                            <th><i class="fas fa-credit-card me-2"></i>Saldo</th>
                            <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaOrdenes">
                        <?php if (isset($ordenes) && !empty($ordenes)): ?>
                            <?php foreach ($ordenes as $orden): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?php echo $orden['id']; ?></td>
                                    <td>
                                        <?php if (!empty($orden['imagen_url'])): ?>
                                            <?php 
                                            // Separar múltiples imágenes (si hay varias separadas por comas)
                                            $imagenes = explode(',', $orden['imagen_url']);
                                            $primeraImagen = trim($imagenes[0]);
                                            
                                            if (!empty($primeraImagen)): 
                                                // Limpiar la ruta - si ya incluye assets/img/, usar tal como está
                                                // Si no, agregar el prefijo assets/img/
                                                if (strpos($primeraImagen, 'assets/img/') === 0) {
                                                    $rutaImagen = $primeraImagen; // Ya tiene la ruta completa
                                                    $nombreArchivo = str_replace('assets/img/', '', $primeraImagen);
                                                } else {
                                                    $rutaImagen = 'assets/img/' . $primeraImagen; // Solo nombre del archivo
                                                    $nombreArchivo = $primeraImagen;
                                                }
                                                
                                                if (file_exists($rutaImagen)): 
                                            ?>
                                                    <img src="<?php echo $rutaImagen; ?>" 
                                                         class="miniatura-orden" 
                                                         alt="Imagen orden" 
                                                         onclick="mostrarImagenCompleta('<?php echo $nombreArchivo; ?>')">
                                                    <?php if (count($imagenes) > 1): ?>
                                                        <small class="text-muted d-block">+<?php echo count($imagenes) - 1; ?> más</small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div class="avatar-circle" title="Imagen no encontrada: <?php echo $rutaImagen; ?>">
                                                        <i class="fas fa-image-slash text-warning"></i>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="avatar-circle" title="Imagen vacía">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="avatar-circle" title="Sin imagen">
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
                                    <td class="fw-bold text-info"><?php echo $orden['marca'] ?? 'N/A'; ?></td>
                                    <td><?php echo $orden['modelo'] ?? 'N/A'; ?></td>
                                    <td>
                                        <span class="falla-truncada" title="<?php echo htmlspecialchars($orden['falla_reportada'] ?? 'Sin descripción'); ?>">
                                            <?php echo $orden['falla_reportada'] ?? 'Sin descripción'; ?>
                                        </span>
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
                                    <td class="fw-bold text-success">
                                        <?php 
                                        $total = $orden['costo_total'] ?? 0;
                                        echo '$' . number_format($total, 0, ',', '.');
                                        ?>
                                    </td>
                                    <td class="fw-bold" id="saldo-orden-<?php echo $orden['id']; ?>">
                                        <span class="text-warning">
                                            <i class="fas fa-spinner fa-spin me-1"></i>Calculando...
                                        </span>
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
                                            <button class="btn btn-sm btn-outline-warning btn-action" 
                                                    onclick="abrirModalPagosDirecto(<?php echo $orden['id']; ?>)" 
                                                    title="Gestionar pagos">
                                                <i class="fas fa-dollar-sign"></i>
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
                    
                    <!-- Sección de pago inicial -->
                    <div class="card mt-4 border-success">
                        <div class="card-header bg-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Información de Pago Inicial</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="registrarPagoInicial" onchange="togglePagoInicial()">
                                    <label class="form-check-label text-white" for="registrarPagoInicial">Registrar pago ahora</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body seccion-oculta" id="seccionPagoInicial">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="costo_total_inicial" class="form-label fw-bold">
                                        <i class="fas fa-dollar-sign me-2"></i>Costo Total
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="costo_total_inicial" name="costo_total" 
                                               min="0" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dinero_recibido_inicial" class="form-label fw-bold">
                                        <i class="fas fa-hand-holding-usd me-2"></i>Abono Recibido
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="dinero_recibido_inicial" name="dinero_recibido" 
                                               min="0" step="0.01" placeholder="0.00" onchange="calcularSaldoInicial()">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="metodo_pago_inicial" class="form-label fw-bold">
                                        <i class="fas fa-credit-card me-2"></i>Método de Pago
                                    </label>
                                    <select class="form-select" id="metodo_pago_inicial" name="metodo_pago">
                                        <option value="">Seleccione método de pago</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                        <option value="transferencia">Transferencia Bancaria</option>
                                        <option value="nequi">Nequi</option>
                                        <option value="daviplata">Daviplata</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="valor_repuestos_inicial" class="form-label fw-bold">
                                        <i class="fas fa-tools me-2"></i>Valor de Repuestos
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="valor_repuestos_inicial" name="valor_repuestos" 
                                               min="0" step="0.01" value="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="descripcion_repuestos_inicial" class="form-label fw-bold">
                                        <i class="fas fa-clipboard-list me-2"></i>Descripción de Repuestos/Servicios
                                    </label>
                                    <textarea class="form-control" id="descripcion_repuestos_inicial" name="descripcion_repuestos" 
                                              rows="2" placeholder="Detalles de repuestos usados o servicios realizados"></textarea>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2 fs-4"></i>
                                    <div>
                                        <strong>Saldo pendiente:</strong> $<span id="saldo_pendiente_inicial">0.00</span>
                                        <input type="hidden" id="saldo_inicial" name="saldo" value="0">
                                        <div class="small">Este valor se calculará automáticamente al ingresar el costo total y el abono recibido.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<!-- Modal para editar orden -->
<div class="modal fade" id="modalEditarOrden" tabindex="-1" aria-labelledby="modalEditarOrdenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarOrdenLabel">
                    <i class="fas fa-edit me-2"></i>Editar Orden de Trabajo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarOrden" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="editarOrdenId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editarBuscarCliente" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Cliente *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="editarBuscarCliente" 
                                       placeholder="Cliente actual..."
                                       onkeyup="buscarClientesEditar(this.value)" readonly>
                                <button class="btn btn-outline-warning" type="button" 
                                        onclick="habilitarCambioCliente()" title="Cambiar cliente">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </div>
                            <input type="hidden" id="editarClienteId" name="cliente_id" required>
                            <div id="editarResultadosClientes" class="mt-2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editarUsuarioTecnicoId" class="form-label fw-bold">
                                <i class="fas fa-user-cog me-2"></i>Técnico Asignado
                            </label>
                            <select class="form-select" id="editarUsuarioTecnicoId" name="usuario_tecnico_id">
                                <option value="">Seleccione un técnico</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="editarMarca" class="form-label fw-bold">
                                <i class="fas fa-tag me-2"></i>Marca *
                            </label>
                            <input type="text" class="form-control" id="editarMarca" name="marca" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editarModelo" class="form-label fw-bold">
                                <i class="fas fa-laptop me-2"></i>Modelo *
                            </label>
                            <input type="text" class="form-control" id="editarModelo" name="modelo" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editarImeiSerial" class="form-label fw-bold">
                                <i class="fas fa-barcode me-2"></i>IMEI/Serial
                            </label>
                            <input type="text" class="form-control" id="editarImeiSerial" name="imei_serial">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editarEstado" class="form-label fw-bold">
                                <i class="fas fa-info-circle me-2"></i>Estado *
                            </label>
                            <select class="form-select" id="editarEstado" name="estado" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="en-proceso">En Proceso</option>
                                <option value="terminado">Terminado</option>
                                <option value="entregado">Entregado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editarPrioridad" class="form-label fw-bold">
                                <i class="fas fa-flag me-2"></i>Prioridad *
                            </label>
                            <select class="form-select" id="editarPrioridad" name="prioridad" required>
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editarFechaIngreso" class="form-label fw-bold">
                                <i class="fas fa-calendar-plus me-2"></i>Fecha de Ingreso *
                            </label>
                            <input type="datetime-local" class="form-control" id="editarFechaIngreso" name="fecha_ingreso" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editarFechaEntregaEstimada" class="form-label fw-bold">
                                <i class="fas fa-calendar-check me-2"></i>Fecha de Entrega Estimada
                            </label>
                            <input type="datetime-local" class="form-control" id="editarFechaEntregaEstimada" name="fecha_entrega_estimada">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editarFallaReportada" class="form-label fw-bold">
                            <i class="fas fa-exclamation-circle me-2"></i>Falla Reportada *
                        </label>
                        <textarea class="form-control" id="editarFallaReportada" name="falla_reportada" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editarDiagnostico" class="form-label fw-bold">
                            <i class="fas fa-stethoscope me-2"></i>Diagnóstico
                        </label>
                        <textarea class="form-control" id="editarDiagnostico" name="diagnostico" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editarContrasenaEquipo" class="form-label fw-bold">
                            <i class="fas fa-lock me-2"></i>Contraseña del Equipo
                        </label>
                        <input type="text" class="form-control" id="editarContrasenaEquipo" name="contraseña_equipo">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-images me-2"></i>Imágenes Actuales
                        </label>
                        <div id="imagenesActuales" class="d-flex flex-wrap gap-2 mb-3"></div>
                        
                        <label for="editarImagenes" class="form-label fw-bold">
                            <i class="fas fa-camera me-2"></i>Agregar Nuevas Imágenes
                        </label>
                        <input type="file" class="form-control" id="editarImagenes" name="imagenes[]" 
                               accept="image/*" multiple capture="environment">
                        <div class="form-text">Las nuevas imágenes se agregarán a las existentes</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-success" id="btnGestionarPagos" onclick="abrirModalPagos()">
                        <i class="fas fa-money-bill-wave me-2"></i>Gestionar Pagos
                    </button>
                    <button type="submit" class="btn btn-gradient" id="btnActualizarOrden">
                        <i class="fas fa-save me-2"></i>Actualizar Orden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de orden -->
<div class="modal fade" id="modalDetallesOrden" tabindex="-1" aria-labelledby="modalDetallesOrdenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesOrdenLabel">
                    <i class="fas fa-eye me-2"></i>Detalles de la Orden
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="contenidoDetallesOrden">
                    <!-- El contenido se carga dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="imprimirOrden()">
                    <i class="fas fa-print me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para visualizar imagen completa -->
<div class="modal fade" id="modalImagenCompleta" tabindex="-1" aria-labelledby="modalImagenCompletaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImagenCompletaLabel">
                    <i class="fas fa-image me-2"></i>Imagen de la Orden
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imagenCompleta" src="" alt="Imagen de la orden" class="img-fluid rounded">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <a id="descargarImagen" href="" download class="btn btn-outline-primary">
                    <i class="fas fa-download me-2"></i>Descargar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para gestionar pagos de la orden -->
<div class="modal fade" id="modalGestionarPagos" tabindex="-1" aria-labelledby="modalGestionarPagosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGestionarPagosLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>Gestionar Pagos de la Orden
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pagoOrdenId" name="pagoOrdenId">
                
                <!-- Información resumen de la orden -->
                <div class="alert alert-info mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Información de la Orden</h6>
                            <p class="mb-1"><strong>Orden:</strong> #<span id="pagosNumeroOrden"></span></p>
                            <p class="mb-1"><strong>Cliente:</strong> <span id="pagosNombreCliente"></span></p>
                            <p class="mb-0"><strong>Equipo:</strong> <span id="pagosMarcaModelo"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2"><i class="fas fa-hand-holding-usd me-2"></i>Estado Financiero</h6>
                            <p class="mb-1"><strong>Costo Total:</strong> $<span id="pagosCostoTotal">0.00</span></p>
                            <p class="mb-1"><strong>Pagado:</strong> $<span id="pagosMontoPagado">0.00</span></p>
                            <p class="mb-0"><strong>Saldo Pendiente:</strong> $<span id="pagosSaldoPendiente">0.00</span></p>
                        </div>
                    </div>
                </div>

                <!-- Formulario para agregar nuevo pago -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Registrar Nuevo Pago o Abono</h6>
                    </div>
                    <div class="card-body">
                        <form id="formAgregarPago">
                            <input type="hidden" id="pago_orden_id" name="orden_id">
                            <input type="hidden" id="pago_usuario_id" name="usuario_id">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_pago" class="form-label fw-bold">
                                        <i class="fas fa-calendar me-2"></i>Fecha de Pago
                                    </label>
                                    <input type="datetime-local" class="form-control" id="fecha_pago" name="fecha_pago" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="metodo_pago" class="form-label fw-bold">
                                        <i class="fas fa-credit-card me-2"></i>Método de Pago
                                    </label>
                                    <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                        <option value="">Seleccione método de pago</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                        <option value="transferencia">Transferencia Bancaria</option>
                                        <option value="nequi">Nequi</option>
                                        <option value="daviplata">Daviplata</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="costo_total" class="form-label fw-bold">
                                        <i class="fas fa-dollar-sign me-2"></i>Costo Total
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="costo_total" name="costo_total" 
                                               min="0" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dinero_recibido" class="form-label fw-bold">
                                        <i class="fas fa-hand-holding-usd me-2"></i>Abono Recibido
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="dinero_recibido" name="dinero_recibido" 
                                               min="0" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="valor_repuestos" class="form-label fw-bold">
                                        <i class="fas fa-tools me-2"></i>Valor de Repuestos
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="valor_repuestos" name="valor_repuestos" 
                                               min="0" step="0.01" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="saldo" class="form-label fw-bold">
                                        <i class="fas fa-balance-scale me-2"></i>Saldo Pendiente
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="saldo" name="saldo" 
                                               min="0" step="0.01" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="descripcion_repuestos" class="form-label fw-bold">
                                        <i class="fas fa-clipboard-list me-2"></i>Descripción de Repuestos/Servicios
                                    </label>
                                    <textarea class="form-control" id="descripcion_repuestos" name="descripcion_repuestos" 
                                              rows="3" placeholder="Detalles de repuestos usados o servicios realizados"></textarea>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-gradient" id="btnGuardarPago">
                                    <i class="fas fa-save me-2"></i>Registrar Pago
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Historial de pagos de la orden -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Pagos</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                        <th><i class="fas fa-dollar-sign me-2"></i>Monto</th>
                                        <th><i class="fas fa-credit-card me-2"></i>Método</th>
                                        <th><i class="fas fa-tools me-2"></i>Repuestos</th>
                                        <th><i class="fas fa-file-invoice-dollar me-2"></i>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody id="historialPagos">
                                    <tr>
                                        <td colspan="5" class="text-center py-3">
                                            <i class="fas fa-info-circle text-muted me-2"></i>
                                            No hay pagos registrados para esta orden
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script específico para el módulo de órdenes -->
<script src="assets/js/modules/ordenes.js"></script>

<?php
require_once 'footer.php';
?>
