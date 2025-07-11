<?php
require_once 'header.php';
?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS después de jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="assets/css/modules/inventario.css">

<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Header de bienvenida -->
        <div class="welcome-header">
            <h1><i class="fas fa-boxes me-3"></i>Gestión de Inventario</h1>
            <p class="mb-0">Centro de Control de Productos y Stock</p>
            <small id="fechaActual"></small>
        </div>

        <!-- Estadísticas del inventario -->
        <div class="stats-grid">
            <div class="card dashboard-card text-white gradient-primary">
                <div class="card-body">
                    <i class="fas fa-cubes card-icon"></i>
                    <div class="metric-label">Total Productos</div>
                    <div class="metric-value" id="totalProductos">0</div>
                </div>
            </div>

            <div class="card dashboard-card text-white gradient-success">
                <div class="card-body">
                    <i class="fas fa-shopping-cart card-icon"></i>
                    <div class="metric-label">Valor Costo</div>
                    <div class="metric-value" id="valorInventarioCosto">$0</div>
                    <small class="opacity-75">Precio de compra total</small>
                </div>
            </div>

            <div class="card dashboard-card text-white gradient-info">
                <div class="card-body">
                    <i class="fas fa-dollar-sign card-icon"></i>
                    <div class="metric-label">Valor Venta</div>
                    <div class="metric-value" id="valorInventarioVenta">$0</div>
                    <small class="opacity-75">Precio de venta total</small>
                </div>
            </div>

            <div class="card dashboard-card text-white gradient-warning">
                <div class="card-body">
                    <i class="fas fa-chart-line card-icon"></i>
                    <div class="metric-label">Margen Potencial</div>
                    <div class="metric-value" id="margenGanancia">$0</div>
                    <small class="opacity-75">Diferencia venta - costo</small>
                </div>
            </div>

            <div class="card dashboard-card text-white gradient-danger">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle card-icon"></i>
                    <div class="metric-label">Stock Bajo</div>
                    <div class="metric-value" id="stockBajo">0</div>
                </div>
            </div>

            <div class="card dashboard-card text-white gradient-danger">
                <div class="card-body">
                    <i class="fas fa-times-circle card-icon"></i>
                    <div class="metric-label">Agotados</div>
                    <div class="metric-value" id="stockAgotado">0</div>
                </div>
            </div>
        </div>

        <!-- Controles superiores -->
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title mb-0">
                    <i class="fas fa-list me-2"></i>Productos
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-info" onclick="mostrarResumenFinanciero()" title="Ver resumen financiero">
                        <i class="fas fa-chart-pie me-2"></i>Resumen
                    </button>
                    <button class="btn btn-outline-primary" onclick="exportarInventario()">
                        <i class="fas fa-download me-2"></i>Exportar
                    </button>
                    <button class="btn btn-outline-warning" onclick="mostrarProductosBajoStock()">
                        <i class="fas fa-exclamation-triangle me-2"></i>Stock Bajo
                    </button>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="search-box">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-search me-2"></i>Buscar producto
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="buscarProducto" 
                                   placeholder="Nombre o descripción..." 
                                   onkeyup="filtrarProductos()">
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-filter me-2"></i>Estado Stock
                        </label>
                        <select class="form-select" id="filtroStock" onchange="filtrarProductos()">
                            <option value="">Todos</option>
                            <option value="alto">Stock Alto</option>
                            <option value="medio">Stock Medio</option>
                            <option value="bajo">Stock Bajo</option>
                            <option value="agotado">Agotado</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-sort me-2"></i>Ordenar por
                        </label>
                        <select class="form-select" id="ordenarPor" onchange="ordenarProductos()">
                            <option value="nombre">Nombre</option>
                            <option value="stock">Stock</option>
                            <option value="precio">Precio</option>
                            <option value="fecha">Fecha Creación</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                            <i class="fas fa-redo me-2"></i>Limpiar
                        </button>
                    </div>
                </div>

                <!-- Filtros activos -->
                <div id="filtrosActivos" class="mt-2"></div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaInventario">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>ID</th>
                            <th><i class="fas fa-image me-2"></i>Imagen</th>
                            <th><i class="fas fa-tag me-2"></i>Producto</th>
                            <th><i class="fas fa-list me-2"></i>Categoría</th>
                            <th><i class="fas fa-barcode me-2"></i>Código Barras</th>
                            <th><i class="fas fa-boxes me-2"></i>Stock Actual</th>
                            <th><i class="fas fa-shopping-cart me-2"></i>Precio Compra</th>
                            <th><i class="fas fa-dollar-sign me-2"></i>Precio Venta</th>
                            <th><i class="fas fa-percentage me-2"></i>Margen %</th>
                            <th><i class="fas fa-info-circle me-2"></i>Estado</th>
                            <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaInventario">
                        <!-- Se carga dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    Mostrando <span id="mostrandoDesde">1</span> a <span id="mostrandoHasta">10</span> 
                    de <span id="totalRegistros">0</span> productos
                </div>
                <nav aria-label="Paginación de inventario">
                    <ul class="pagination mb-0" id="paginacion">
                        <!-- Se genera dinámicamente -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Botón flotante para agregar producto -->
<button class="floating-btn" onclick="mostrarModalAgregarProducto()" title="Agregar nuevo producto">
    <i class="fas fa-plus"></i>
</button>

<!-- Modal para agregar/editar producto -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductoLabel">
                    <i class="fas fa-box me-2"></i>Agregar Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formProducto">
                <div class="modal-body">
                    <input type="hidden" id="productoId" name="id">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="productoNombre" class="form-label fw-bold">
                                    <i class="fas fa-tag me-2"></i>Nombre del Producto *
                                </label>
                                <input type="text" class="form-control" id="productoNombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="productoCategoria" class="form-label fw-bold">
                                    <i class="fas fa-list me-2"></i>Categoría
                                </label>
                                <select class="form-select" id="productoCategoria" name="categoria" required>
                                    <option value="">Selecciona una categoría</option>
                                    <option value="Celulares">Celulares</option>
                                    <option value="Accesorios">Accesorios</option>
                                    <option value="Tablets">Tablets</option>
                                    <option value="Computadores">Computadores</option>
                                    <option value="Audio">Audio</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="productoDescripcion" class="form-label fw-bold">
                            <i class="fas fa-align-left me-2"></i>Descripción
                        </label>
                        <textarea class="form-control" id="productoDescripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productoCodigoBarras" class="form-label fw-bold">
                                <i class="fas fa-barcode me-2"></i>Código de Barras
                            </label>
                            <input type="text" class="form-control" id="productoCodigoBarras" name="codigo_barras" placeholder="Escanea o ingresa el código">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productoImagen" class="form-label fw-bold">
                                <i class="fas fa-image me-2"></i>Imagen
                            </label>
                            <input type="file" class="form-control" id="productoImagen" name="imagen" accept="image/*">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productoStockActual" class="form-label fw-bold">
                                <i class="fas fa-boxes me-2"></i>Stock Actual *
                            </label>
                            <input type="number" class="form-control" id="productoStockActual" name="stock" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productoStockMinimo" class="form-label fw-bold">
                                <i class="fas fa-chart-line me-2"></i>Stock Mínimo *
                            </label>
                            <input type="number" class="form-control" id="productoStockMinimo" name="stock_minimo" min="0" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productoPrecioCompra" class="form-label fw-bold">
                                <i class="fas fa-shopping-cart me-2"></i>Precio Compra
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="productoPrecioCompra" name="precio_compra" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productoPrecioVenta" class="form-label fw-bold">
                                <i class="fas fa-dollar-sign me-2"></i>Precio Venta *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="productoPrecioVenta" name="precio_venta" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarProducto">
                        <i class="fas fa-save me-2"></i>Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ajustar stock -->
<div class="modal fade" id="modalAjustarStock" tabindex="-1" aria-labelledby="modalAjustarStockLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAjustarStock">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjustarStockLabel">
                        <i class="fas fa-exchange-alt me-2"></i>Ajustar Stock
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-boxes me-2"></i>Stock Actual
                            </label>
                            <input type="number" class="form-control" id="ajusteStockActual" readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-edit me-2"></i>Nuevo Stock *
                            </label>
                            <input type="number" class="form-control" id="ajusteNuevoStock" name="nuevo_stock" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ajusteTipo" class="form-label fw-bold">
                            <i class="fas fa-list me-2"></i>Tipo de Ajuste *
                        </label>
                        <select class="form-select" id="ajusteTipo" name="tipo_ajuste" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="entrada">Entrada (Compra/Devolución)</option>
                            <option value="salida">Salida (Venta/Pérdida)</option>
                            <option value="ajuste">Ajuste de Inventario</option>
                            <option value="correccion">Corrección</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ajusteMotivo" class="form-label fw-bold">
                            <i class="fas fa-comment me-2"></i>Motivo del Ajuste *
                        </label>
                        <textarea class="form-control" id="ajusteMotivo" name="motivo" rows="3" required 
                                  placeholder="Describe el motivo del ajuste de stock..."></textarea>
                    </div>
                    <input type="hidden" id="ajusteProductoId" name="producto_id">
                    <input type="hidden" id="ajusteProductoNombre" name="producto_nombre">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning" id="btnGuardarAjuste">
                        <i class="fas fa-sync-alt me-2"></i>Ajustar Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para historial de movimientos -->
<div class="modal fade" id="modalHistorial" tabindex="-1" aria-labelledby="modalHistorialLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHistorialLabel">
                    <i class="fas fa-history me-2"></i>Historial de Movimientos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6 id="historialProductoNombre" class="text-primary">
                        <i class="fas fa-tag me-2"></i>
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                <th><i class="fas fa-exchange-alt me-2"></i>Tipo</th>
                                <th><i class="fas fa-history me-2"></i>Stock Anterior</th>
                                <th><i class="fas fa-arrows-alt-h me-2"></i>Cambio</th>
                                <th><i class="fas fa-boxes me-2"></i>Stock Nuevo</th>
                                <th><i class="fas fa-comment me-2"></i>Motivo</th>
                                <th><i class="fas fa-user me-2"></i>Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="historialMovimientos">
                            <!-- Se carga dinámicamente -->
                        </tbody>
                    </table>
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

<script src="assets/js/modules/inventario.js"></script>

<?php require_once 'footer.php'; ?>
