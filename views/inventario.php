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

<style>
.dashboard-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 25%, #4a4a4a 50%, #6a6a6a 75%, #8a8a8a 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.dashboard-card {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    overflow: hidden;
    min-height: 140px;
    display: flex;
    flex-direction: column;
}

.dashboard-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 1.5rem;
    position: relative;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.card-icon {
    font-size: 3rem;
    opacity: 0.8;
    float: right;
    margin-top: -10px;
}

.metric-value {
    font-size: clamp(1.8rem, 3vw, 2.2rem);
    font-weight: bold;
    margin: 10px 0;
    line-height: 1.1;
    word-break: break-word;
    overflow-wrap: break-word;
}

.metric-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.gradient-primary { background: linear-gradient(45deg, #667eea, #764ba2); }
.gradient-success { background: linear-gradient(45deg, #56ab2f, #a8e6cf); }
.gradient-warning { background: linear-gradient(45deg, #f093fb, #f5576c); }
.gradient-info { background: linear-gradient(45deg, #4facfe, #00f2fe); }
.gradient-danger { background: linear-gradient(45deg, #fa709a, #fee140); }

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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
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

.stock-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stock-alto {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
}

.stock-medio {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    color: white;
}

.stock-bajo {
    background: linear-gradient(45deg, #dc3545, #e83e8c);
    color: white;
}

.stock-agotado {
    background: linear-gradient(45deg, #6c757d, #495057);
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

.floating-btn:active {
    transform: translateY(-1px) scale(1.02);
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

/* Estilos de paginación mejorados */
.pagination {
    justify-content: center;
    margin-top: 30px;
}

.page-link {
    border: none;
    border-radius: 10px;
    margin: 0 3px;
    padding: 10px 15px;
    color: #667eea;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Estilos para filtros mejorados */
.filter-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    border: 2px solid #dee2e6;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.filter-row {
    display: flex;
    gap: 15px;
    align-items: end;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 200px;
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
    color: white;
    border-bottom: none;
    border-radius: 15px 15px 0 0;
}

.modal-content {
    border-radius: 15px;
    border: none;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.modal-header .btn-close {
    filter: invert(1);
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.pagination .page-link {
    color: #667eea;
    border-color: #dee2e6;
    border-radius: 10px;
    margin: 0 2px;
}

.pagination .page-link:hover {
    color: #495057;
    background-color: #e9ecef;
    border-color: #dee2e6;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-color: #667eea;
}

.filter-chip {
    display: inline-block;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.875rem;
    margin: 3px;
    font-weight: 500;
}

.filter-chip .btn-close {
    filter: invert(1);
    font-size: 0.75rem;
    margin-left: 8px;
}

.btn-outline-primary:hover, .btn-outline-warning:hover, 
.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .metric-value {
        font-size: clamp(1.5rem, 4vw, 1.8rem);
    }
    
    .content-section {
        padding: 20px;
        margin: 15px 0;
    }
    
    .search-box {
        padding: 15px;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .metric-value {
        font-size: 1.6rem;
    }
    
    .dashboard-card .card-body {
        padding: 1.25rem;
    }
}
</style>

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

<script>
// Variables globales
let productos = [];
let productosFiltrados = [];
let paginaActual = 1;
let productosPorPagina = 10;
let ordenActual = 'nombre';
let direccionOrden = 'asc';

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    cargarInventario();
    
    // Event listeners para formularios
    document.getElementById('formProducto').addEventListener('submit', guardarProducto);
    document.getElementById('formAjustarStock').addEventListener('submit', ajustarStock);
    
    // Configurar tooltip para botones
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Función para cargar inventario
function cargarInventario() {
    fetch('index.php?action=listarProductos')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                productos = data.productos || [];
                productosFiltrados = [...productos];
                actualizarEstadisticas();
                renderizarTabla();
                renderizarPaginacion();
            } else {
                console.error('Error al cargar inventario:', data.message);
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error al cargar inventario:', error);
            Swal.fire('Error', 'Error de conexión al cargar inventario', 'error');
        });
}

// Función para actualizar estadísticas
function actualizarEstadisticas() {
    // Usar la API para obtener estadísticas reales
    fetch('index.php?action=obtenerEstadisticasInventario')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.estadisticas;
                document.getElementById('totalProductos').textContent = stats.total_productos || 0;
                
                // Valores del inventario
                const valorCosto = parseFloat(stats.valor_total_costo || 0);
                const valorVenta = parseFloat(stats.valor_total_inventario || 0);
                const margen = valorVenta - valorCosto;
                
                document.getElementById('valorInventarioCosto').textContent = '$' + valorCosto.toLocaleString('es-CO');
                document.getElementById('valorInventarioVenta').textContent = '$' + valorVenta.toLocaleString('es-CO');
                document.getElementById('margenGanancia').textContent = '$' + margen.toLocaleString('es-CO');
                
                document.getElementById('stockBajo').textContent = stats.productos_stock_bajo || 0;
                document.getElementById('stockAgotado').textContent = stats.productos_sin_stock || 0;
            } else {
                console.error('Error al obtener estadísticas:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas:', error);
            // Fallback: calcular estadísticas localmente si hay productos cargados
            if (productos.length > 0) {
                const totalProductos = productos.length;
                
                // Calcular valores de costo y venta
                const valorCosto = productos.reduce((total, p) => {
                    const precioCosto = parseFloat(p.precio_compra || 0);
                    return total + (p.stock * precioCosto);
                }, 0);
                
                const valorVenta = productos.reduce((total, p) => {
                    const precioVenta = parseFloat(p.precio_venta || 0);
                    return total + (p.stock * precioVenta);
                }, 0);
                
                const margen = valorVenta - valorCosto;
                
                const stockBajo = productos.filter(p => p.stock <= p.stock_minimo && p.stock > 0).length;
                const stockAgotado = productos.filter(p => p.stock === 0).length;
                
                document.getElementById('totalProductos').textContent = totalProductos;
                document.getElementById('valorInventarioCosto').textContent = '$' + valorCosto.toLocaleString('es-CO');
                document.getElementById('valorInventarioVenta').textContent = '$' + valorVenta.toLocaleString('es-CO');
                document.getElementById('margenGanancia').textContent = '$' + margen.toLocaleString('es-CO');
                document.getElementById('stockBajo').textContent = stockBajo;
                document.getElementById('stockAgotado').textContent = stockAgotado;
            }
        });
}

// Función para determinar el estado del stock
function obtenerEstadoStock(stockActual, stockMinimo) {
    if (stockActual === 0) return 'agotado';
    if (stockActual <= stockMinimo) return 'bajo';
    if (stockActual <= stockMinimo * 2) return 'medio';
    return 'alto';
}

// Función para renderizar la tabla
function renderizarTabla() {
    const tbody = document.getElementById('bodyTablaInventario');
    tbody.innerHTML = '';
    
    const inicio = (paginaActual - 1) * productosPorPagina;
    const fin = inicio + productosPorPagina;
    const productosParaMostrar = productosFiltrados.slice(inicio, fin);
    
    productosParaMostrar.forEach(producto => {
        const estadoStock = obtenerEstadoStock(producto.stock, producto.stock_minimo);
        const precioCosto = parseFloat(producto.precio_compra || 0);
        const precioVenta = parseFloat(producto.precio_venta || 0);
        let margenPorcentaje = 0;
        if (precioCosto > 0) {
            margenPorcentaje = ((precioVenta - precioCosto) / precioCosto) * 100;
        }
        let colorMargen = 'text-muted';
        if (margenPorcentaje > 50) colorMargen = 'text-success fw-bold';
        else if (margenPorcentaje > 20) colorMargen = 'text-info fw-bold';
        else if (margenPorcentaje > 0) colorMargen = 'text-warning';
        else if (margenPorcentaje < 0) colorMargen = 'text-danger fw-bold';
        // Imagen
        let imagenHtml = `<img src="assets/img/default-product.png" alt="Sin imagen" style="width:48px;height:48px;object-fit:cover;border-radius:8px;opacity:0.5;">`;
        if (producto.imagen) {
            imagenHtml = `<img src="${producto.imagen}" alt="Imagen" style="width:48px;height:48px;object-fit:cover;border-radius:8px;">`;
        }
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center">${producto.id}</td>
            <td class="text-center">${imagenHtml}</td>
            <td>
                <div class="producto-info">
                    <strong>${producto.nombre}</strong>
                    <small class="text-muted d-block">${producto.descripcion || 'Sin descripción'}</small>
                </div>
            </td>
            <td>${producto.categoria || '<span class="text-muted">Sin categoría</span>'}</td>
            <td>${producto.codigo_barras || '<span class="text-muted">-</span>'}</td>
            <td class="text-center"><strong>${producto.stock}</strong></td>
            <td>$${precioCosto.toLocaleString('es-CO')}</td>
            <td>$${precioVenta.toLocaleString('es-CO')}</td>
            <td class="${colorMargen}">
                ${margenPorcentaje.toFixed(1)}%
                ${margenPorcentaje > 0 ? '<i class="fas fa-arrow-up ms-1"></i>' : margenPorcentaje < 0 ? '<i class="fas fa-arrow-down ms-1"></i>' : ''}
            </td>
            <td>
                <span class="stock-badge stock-${estadoStock}">
                    ${estadoStock.charAt(0).toUpperCase() + estadoStock.slice(1)}
                </span>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-primary btn-action" onclick="editarProducto(${producto.id})" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-warning btn-action" onclick="mostrarModalAjustarStock(${producto.id})" title="Ajustar Stock">
                        <i class="bi bi-arrow-up-down"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info btn-action" onclick="verHistorial(${producto.id})" title="Historial">
                        <i class="bi bi-clock-history"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-action" onclick="eliminarProducto(${producto.id})" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    // Actualizar información de paginación
    document.getElementById('mostrandoDesde').textContent = inicio + 1;
    document.getElementById('mostrandoHasta').textContent = Math.min(fin, productosFiltrados.length);
    document.getElementById('totalRegistros').textContent = productosFiltrados.length;
}

// Función para renderizar paginación
function renderizarPaginacion() {
    const totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
    const paginacion = document.getElementById('paginacion');
    paginacion.innerHTML = '';
    
    if (totalPaginas <= 1) return;
    
    // Botón anterior
    const anteriorLi = document.createElement('li');
    anteriorLi.className = `page-item ${paginaActual === 1 ? 'disabled' : ''}`;
    anteriorLi.innerHTML = `<a class="page-link" href="#" onclick="cambiarPagina(${paginaActual - 1})">Anterior</a>`;
    paginacion.appendChild(anteriorLi);
    
    // Números de página
    for (let i = 1; i <= totalPaginas; i++) {
        if (i === 1 || i === totalPaginas || (i >= paginaActual - 2 && i <= paginaActual + 2)) {
            const li = document.createElement('li');
            li.className = `page-item ${i === paginaActual ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="cambiarPagina(${i})">${i}</a>`;
            paginacion.appendChild(li);
        } else if (i === paginaActual - 3 || i === paginaActual + 3) {
            const li = document.createElement('li');
            li.className = 'page-item disabled';
            li.innerHTML = `<span class="page-link">...</span>`;
            paginacion.appendChild(li);
        }
    }
    
    // Botón siguiente
    const siguienteLi = document.createElement('li');
    siguienteLi.className = `page-item ${paginaActual === totalPaginas ? 'disabled' : ''}`;
    siguienteLi.innerHTML = `<a class="page-link" href="#" onclick="cambiarPagina(${paginaActual + 1})">Siguiente</a>`;
    paginacion.appendChild(siguienteLi);
}

// Función para cambiar página
function cambiarPagina(nuevaPagina) {
    const totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
    if (nuevaPagina >= 1 && nuevaPagina <= totalPaginas) {
        paginaActual = nuevaPagina;
        renderizarTabla();
        renderizarPaginacion();
    }
}

// Función para filtrar productos
function filtrarProductos() {
    const busqueda = document.getElementById('buscarProducto').value.toLowerCase();
    const stock = document.getElementById('filtroStock').value;
    
    productosFiltrados = productos.filter(producto => {
        const cumpleBusqueda = !busqueda || 
            producto.nombre.toLowerCase().includes(busqueda) ||
            (producto.descripcion && producto.descripcion.toLowerCase().includes(busqueda));
            
        const estadoStock = obtenerEstadoStock(producto.stock, producto.stock_minimo);
        const cumpleStock = !stock || estadoStock === stock;
        
        return cumpleBusqueda && cumpleStock;
    });
    
    paginaActual = 1;
    renderizarTabla();
    renderizarPaginacion();
    mostrarFiltrosActivos();
}

// Función para mostrar filtros activos
function mostrarFiltrosActivos() {
    const contenedor = document.getElementById('filtrosActivos');
    contenedor.innerHTML = '';
    
    const busqueda = document.getElementById('buscarProducto').value;
    const stock = document.getElementById('filtroStock').value;
    
    if (busqueda) {
        contenedor.innerHTML += `
            <span class="filter-chip">
                Búsqueda: ${busqueda}
                <button type="button" class="btn-close" onclick="limpiarFiltro('buscarProducto')"></button>
            </span>
        `;
    }
    
    if (stock) {
        contenedor.innerHTML += `
            <span class="filter-chip">
                Stock: ${stock}
                <button type="button" class="btn-close" onclick="limpiarFiltro('filtroStock')"></button>
            </span>
        `;
    }
}

// Función para limpiar un filtro específico
function limpiarFiltro(filtroId) {
    document.getElementById(filtroId).value = '';
    filtrarProductos();
}

// Función para limpiar todos los filtros
function limpiarFiltros() {
    document.getElementById('buscarProducto').value = '';
    document.getElementById('filtroStock').value = '';
    filtrarProductos();
}

// Función para ordenar productos
function ordenarProductos() {
    const criterio = document.getElementById('ordenarPor').value;
    
    // Cambiar dirección si es el mismo criterio
    if (ordenActual === criterio) {
        direccionOrden = direccionOrden === 'asc' ? 'desc' : 'asc';
    } else {
        direccionOrden = 'asc';
        ordenActual = criterio;
    }
    
    productosFiltrados.sort((a, b) => {
        let valorA, valorB;
        
        switch (criterio) {
            case 'nombre':
                valorA = a.nombre.toLowerCase();
                valorB = b.nombre.toLowerCase();
                break;
            case 'stock':
                valorA = a.stock;
                valorB = b.stock;
                break;
            case 'precio':
                valorA = a.precio_venta;
                valorB = b.precio_venta;
                break;
            case 'fecha':
                valorA = new Date(a.fecha_creacion);
                valorB = new Date(b.fecha_creacion);
                break;
            default:
                return 0;
        }
        
        if (valorA < valorB) return direccionOrden === 'asc' ? -1 : 1;
        if (valorA > valorB) return direccionOrden === 'asc' ? 1 : -1;
        return 0;
    });
    
    paginaActual = 1;
    renderizarTabla();
    renderizarPaginacion();
}

// Función para mostrar modal de agregar producto
function mostrarModalAgregarProducto() {
    document.getElementById('modalProductoLabel').innerHTML = '<i class="bi bi-box me-2"></i>Agregar Producto';
    document.getElementById('formProducto').reset();
    document.getElementById('productoId').value = '';
    const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
    modal.show();
}

// Función para editar producto
function editarProducto(id) {
    const producto = productos.find(p => p.id === id);
    if (!producto) return;
    
    document.getElementById('modalProductoLabel').innerHTML = '<i class="bi bi-pencil me-2"></i>Editar Producto';
    document.getElementById('productoId').value = producto.id;
    document.getElementById('productoNombre').value = producto.nombre;
    document.getElementById('productoCategoria').value = producto.categoria || '';
    document.getElementById('productoDescripcion').value = producto.descripcion || '';
    document.getElementById('productoCodigoBarras').value = producto.codigo_barras || '';
    document.getElementById('productoStockActual').value = producto.stock;
    document.getElementById('productoStockMinimo').value = producto.stock_minimo;
    document.getElementById('productoPrecioCompra').value = producto.precio_compra || '';
    document.getElementById('productoPrecioVenta').value = producto.precio_venta;
    // No se carga imagen en el input file por seguridad, se muestra solo al renderizar tabla
    const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
    modal.show();
}

// Función para guardar producto
function guardarProducto(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarProducto');
    const esEdicion = formData.get('id');
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    const url = esEdicion ? 'index.php?action=actualizarProducto' : 'index.php?action=crearProducto';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));
        
        // Verificar si la respuesta es JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta del servidor no es JSON válido');
        }
        
        return response.text().then(text => {
            console.log('Response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Raw response:', text);
                throw new Error('Respuesta JSON inválida del servidor');
            }
        });
    })
    .then(data => {
        console.log('Parsed data:', data);
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: data.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalProducto'));
            modal.hide();
            
            // Recargar inventario
            cargarInventario();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        Swal.fire('Error', 'Error de conexión: ' + error.message, 'error');
    })
    .finally(() => {
        // Restaurar botón
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-2"></i>Guardar Producto';
    });
}

// Función para mostrar modal de ajustar stock
function mostrarModalAjustarStock(id) {
    const producto = productos.find(p => p.id === id);
    if (!producto) return;
    
    document.getElementById('ajusteProductoId').value = producto.id;
    document.getElementById('ajusteProductoNombre').value = producto.nombre;
    document.getElementById('ajusteStockActual').value = producto.stock;
    document.getElementById('ajusteNuevoStock').value = producto.stock;
    document.getElementById('ajusteTipo').value = '';
    document.getElementById('ajusteMotivo').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('modalAjustarStock'));
    modal.show();
}

// Función para ajustar stock
function ajustarStock(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarAjuste');
    
    // Validaciones
    const stockActual = parseInt(document.getElementById('ajusteStockActual').value);
    const nuevoStock = parseInt(formData.get('nuevo_stock'));
    
    if (nuevoStock < 0) {
        Swal.fire('Error', 'El stock no puede ser negativo', 'error');
        return;
    }
    
    // Determinar tipo de ajuste
    let tipo, cantidad;
    if (nuevoStock > stockActual) {
        tipo = 'incremento';
        cantidad = nuevoStock - stockActual;
    } else if (nuevoStock < stockActual) {
        tipo = 'decremento';
        cantidad = stockActual - nuevoStock;
    } else {
        Swal.fire('Info', 'No hay cambios en el stock', 'info');
        return;
    }
    
    // Preparar datos para envío
    const ajusteData = new FormData();
    ajusteData.append('id', formData.get('producto_id'));
    ajusteData.append('tipo', tipo);
    ajusteData.append('cantidad', cantidad);
    ajusteData.append('motivo', formData.get('motivo'));
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Ajustando...';
    
    fetch('index.php?action=ajustarStockProducto', {
        method: 'POST',
        body: ajusteData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: `Stock ajustado: ${stockActual} → ${nuevoStock}`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalAjustarStock'));
            modal.hide();
            
            // Recargar inventario
            cargarInventario();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al ajustar stock', 'error');
    })
    .finally(() => {
        // Restaurar botón
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Ajustar Stock';
    });
}

// Función para ver historial
function verHistorial(id) {
    const producto = productos.find(p => p.id === id);
    if (!producto) return;
    
    document.getElementById('historialProductoNombre').textContent = producto.nombre;
    
    // Simular historial - reemplaza con tu endpoint real
    const historialEjemplo = [
        {
            fecha: '2024-07-10 14:30',
            tipo: 'Entrada',
            stock_anterior: 10,
            cambio: '+5',
            stock_nuevo: 15,
            motivo: 'Compra a proveedor',
            usuario: 'Admin'
        },
        {
            fecha: '2024-07-09 10:15',
            tipo: 'Salida',
            stock_anterior: 12,
            cambio: '-2',
            stock_nuevo: 10,
            motivo: 'Venta a cliente',
            usuario: 'Vendedor'
        }
    ];
    
    const tbody = document.getElementById('historialMovimientos');
    tbody.innerHTML = '';
    
    historialEjemplo.forEach(mov => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${mov.fecha}</td>
            <td>
                <span class="badge ${mov.tipo === 'Entrada' ? 'bg-success' : 'bg-danger'}">
                    ${mov.tipo}
                </span>
            </td>
            <td>${mov.stock_anterior}</td>
            <td class="${mov.cambio.startsWith('+') ? 'text-success' : 'text-danger'}">
                <strong>${mov.cambio}</strong>
            </td>
            <td>${mov.stock_nuevo}</td>
            <td>${mov.motivo}</td>
            <td>${mov.usuario}</td>
        `;
        tbody.appendChild(tr);
    });
    
    const modal = new bootstrap.Modal(document.getElementById('modalHistorial'));
    modal.show();
}

// Función para eliminar producto
function eliminarProducto(id) {
    const producto = productos.find(p => p.id === id);
    if (!producto) return;
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminará el producto "${producto.nombre}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('index.php?action=eliminarProducto', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    cargarInventario();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error de conexión al eliminar producto', 'error');
            });
        }
    });
}

// Función para mostrar productos con stock bajo
function mostrarProductosBajoStock() {
    document.getElementById('filtroStock').value = 'bajo';
    filtrarProductos();
}

// Función para exportar inventario
function exportarInventario() {
    // Simular exportación - implementa según tus necesidades
    alert('Exportando inventario... (funcionalidad a implementar)');
}

// Función para mostrar resumen financiero
function mostrarResumenFinanciero() {
    if (productos.length === 0) {
        Swal.fire('Info', 'No hay productos para mostrar resumen', 'info');
        return;
    }
    
    // Calcular totales
    let totalCosto = 0;
    let totalVenta = 0;
    let productosConMargen = 0;
    let margenPromedio = 0;
    
    productos.forEach(producto => {
        const precioCosto = parseFloat(producto.precio_compra || 0);
        const precioVenta = parseFloat(producto.precio_venta || 0);
        const valorCosto = producto.stock * precioCosto;
        const valorVenta = producto.stock * precioVenta;
        
        totalCosto += valorCosto;
        totalVenta += valorVenta;
        
        if (precioCosto > 0) {
            const margen = ((precioVenta - precioCosto) / precioCosto) * 100;
            margenPromedio += margen;
            productosConMargen++;
        }
    });
    
    margenPromedio = productosConMargen > 0 ? margenPromedio / productosConMargen : 0;
    const gananciaPotencial = totalVenta - totalCosto;
    const margenTotal = totalCosto > 0 ? ((totalVenta - totalCosto) / totalCosto) * 100 : 0;
    
    Swal.fire({
        title: '<i class="fas fa-chart-pie text-primary"></i> Resumen Financiero',
        html: `
            <div class="text-start">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="bg-light p-3 rounded">
                            <h6 class="text-primary mb-2"><i class="fas fa-shopping-cart me-2"></i>Inversión Total</h6>
                            <h4 class="text-primary mb-0">$${totalCosto.toLocaleString('es-CO')}</h4>
                            <small class="text-muted">Precio de costo</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-3 rounded">
                            <h6 class="text-success mb-2"><i class="fas fa-dollar-sign me-2"></i>Valor de Venta</h6>
                            <h4 class="text-success mb-0">$${totalVenta.toLocaleString('es-CO')}</h4>
                            <small class="text-muted">Precio de venta</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <h6 class="text-warning mb-2"><i class="fas fa-chart-line me-2"></i>Ganancia Potencial</h6>
                            <h4 class="text-warning mb-0">$${gananciaPotencial.toLocaleString('es-CO')}</h4>
                            <small class="text-muted">Si se vende todo</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <h6 class="text-info mb-2"><i class="fas fa-percentage me-2"></i>Margen Total</h6>
                            <h4 class="text-info mb-0">${margenTotal.toFixed(1)}%</h4>
                            <small class="text-muted">Rentabilidad general</small>
                        </div>
                    </div>
                </div>
                
                <div class="bg-secondary bg-opacity-10 p-3 rounded">
                    <h6 class="mb-2"><i class="fas fa-calculator me-2"></i>Estadísticas Adicionales</h6>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Margen promedio por producto:</strong> ${margenPromedio.toFixed(1)}%</li>
                        <li><strong>Productos con precio de costo:</strong> ${productosConMargen} de ${productos.length}</li>
                        <li><strong>ROI potencial:</strong> ${margenTotal.toFixed(1)}% sobre inversión</li>
                    </ul>
                </div>
            </div>
        `,
        width: '600px',
        showConfirmButton: true,
        confirmButtonText: '<i class="fas fa-download me-2"></i>Exportar Reporte',
        showCancelButton: true,
        cancelButtonText: 'Cerrar',
        customClass: {
            popup: 'text-start'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            exportarInventario();
        }
    });
}
</script>

<?php require_once 'footer.php'; ?>
