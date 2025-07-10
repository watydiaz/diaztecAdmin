<?php
require_once 'header.php';
?>
<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<!-- jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery UI después de jQuery -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<!-- Bootstrap JS después de jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Select -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<style>
.inventory-stats {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    color: white;
    padding: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.search-box {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
}

.table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.table th {
    background-color: #495057;
    color: white;
    border: none;
    font-weight: 500;
    padding: 15px;
}

.table td {
    padding: 12px 15px;
    vertical-align: middle;
}

.btn-action {
    margin: 2px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.875rem;
}

.stock-badge {
    padding: 8px 12px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.875rem;
}

.stock-alto {
    background-color: #d4edda;
    color: #155724;
}

.stock-medio {
    background-color: #fff3cd;
    color: #856404;
}

.stock-bajo {
    background-color: #f8d7da;
    color: #721c24;
}

.stock-agotado {
    background-color: #e2e3e5;
    color: #383d41;
}

.floating-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    z-index: 1050;
}

.floating-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
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
}

.pagination .page-link:hover {
    color: #495057;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #667eea;
    border-color: #667eea;
}

.filter-chip {
    display: inline-block;
    background: #667eea;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
    margin: 2px;
}

.filter-chip .btn-close {
    filter: invert(1);
    font-size: 0.75rem;
    margin-left: 8px;
}
</style>

<div class="container-fluid">
    <!-- Header del módulo -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-boxes me-2"></i>Gestión de Inventario
                </h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="exportarInventario()">
                        <i class="bi bi-download me-2"></i>Exportar
                    </button>
                    <button class="btn btn-outline-warning" onclick="mostrarProductosBajoStock()">
                        <i class="bi bi-exclamation-triangle me-2"></i>Stock Bajo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas del inventario -->
    <div class="inventory-stats">
        <div class="row">
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number" id="totalProductos">0</div>
                    <div class="stat-label">Total Productos</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number" id="valorInventario">$0</div>
                    <div class="stat-label">Valor Total</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number text-warning" id="stockBajo">0</div>
                    <div class="stat-label">Stock Bajo</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number text-danger" id="stockAgotado">0</div>
                    <div class="stat-label">Agotados</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="search-box">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Buscar producto</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="buscarProducto" 
                           placeholder="Nombre, código o categoría..." 
                           onkeyup="filtrarProductos()">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" id="filtroCategoria" onchange="filtrarProductos()">
                    <option value="">Todas</option>
                    <option value="electronica">Electrónica</option>
                    <option value="repuestos">Repuestos</option>
                    <option value="accesorios">Accesorios</option>
                    <option value="herramientas">Herramientas</option>
                    <option value="otros">Otros</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Estado Stock</label>
                <select class="form-select" id="filtroStock" onchange="filtrarProductos()">
                    <option value="">Todos</option>
                    <option value="alto">Stock Alto</option>
                    <option value="medio">Stock Medio</option>
                    <option value="bajo">Stock Bajo</option>
                    <option value="agotado">Agotado</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Ordenar por</label>
                <select class="form-select" id="ordenarPor" onchange="ordenarProductos()">
                    <option value="nombre">Nombre</option>
                    <option value="stock">Stock</option>
                    <option value="precio">Precio</option>
                    <option value="categoria">Categoría</option>
                    <option value="fecha">Fecha Creación</option>
                </select>
            </div>
            <div class="col-md-2 mb-3 d-flex align-items-end">
                <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                </button>
            </div>
        </div>

        <!-- Filtros activos -->
        <div id="filtrosActivos" class="mt-2"></div>
    </div>

    <!-- Tabla de productos -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="tablaInventario">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Valor Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="bodyTablaInventario">
                    <!-- Se carga dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
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

<!-- Botón flotante para agregar producto -->
<button class="floating-btn" onclick="mostrarModalAgregarProducto()" title="Agregar nuevo producto">
    <i class="bi bi-plus"></i>
</button>

<!-- Modal para agregar/editar producto -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductoLabel">
                    <i class="bi bi-box me-2"></i>Agregar Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formProducto">
                <div class="modal-body">
                    <input type="hidden" id="productoId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productoNombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control" id="productoNombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productoCodigo" class="form-label">Código/SKU</label>
                            <input type="text" class="form-control" id="productoCodigo" name="codigo">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productoCategoria" class="form-label">Categoría *</label>
                            <select class="form-select" id="productoCategoria" name="categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="electronica">Electrónica</option>
                                <option value="repuestos">Repuestos</option>
                                <option value="accesorios">Accesorios</option>
                                <option value="herramientas">Herramientas</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productoMarca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="productoMarca" name="marca">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="productoDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="productoDescripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="productoStockActual" class="form-label">Stock Actual *</label>
                            <input type="number" class="form-control" id="productoStockActual" name="stock_actual" min="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="productoStockMinimo" class="form-label">Stock Mínimo *</label>
                            <input type="number" class="form-control" id="productoStockMinimo" name="stock_minimo" min="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="productoPrecioCompra" class="form-label">Precio Compra</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="productoPrecioCompra" name="precio_compra" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="productoPrecioVenta" class="form-label">Precio Venta *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="productoPrecioVenta" name="precio_venta" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productoProveedor" class="form-label">Proveedor</label>
                            <input type="text" class="form-control" id="productoProveedor" name="proveedor">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productoUbicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="productoUbicacion" name="ubicacion" placeholder="Ej: Estante A-2">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarProducto">
                        <i class="bi bi-save me-2"></i>Guardar Producto
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
            <div class="modal-header">
                <h5 class="modal-title" id="modalAjustarStockLabel">
                    <i class="bi bi-arrow-up-down me-2"></i>Ajustar Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAjustarStock">
                <div class="modal-body">
                    <input type="hidden" id="ajusteProductoId" name="producto_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Producto</label>
                        <input type="text" class="form-control" id="ajusteProductoNombre" readonly>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Stock Actual</label>
                            <input type="number" class="form-control" id="ajusteStockActual" readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Nuevo Stock *</label>
                            <input type="number" class="form-control" id="ajusteNuevoStock" name="nuevo_stock" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ajusteTipo" class="form-label">Tipo de Ajuste *</label>
                        <select class="form-select" id="ajusteTipo" name="tipo_ajuste" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="entrada">Entrada (Compra/Devolución)</option>
                            <option value="salida">Salida (Venta/Pérdida)</option>
                            <option value="ajuste">Ajuste de Inventario</option>
                            <option value="correccion">Corrección</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ajusteMotivo" class="form-label">Motivo del Ajuste *</label>
                        <textarea class="form-control" id="ajusteMotivo" name="motivo" rows="3" required 
                                  placeholder="Describe el motivo del ajuste de stock..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning" id="btnGuardarAjuste">
                        <i class="bi bi-arrow-repeat me-2"></i>Ajustar Stock
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
                    <i class="bi bi-clock-history me-2"></i>Historial de Movimientos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6 id="historialProductoNombre" class="text-primary"></h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Stock Anterior</th>
                                <th>Cambio</th>
                                <th>Stock Nuevo</th>
                                <th>Motivo</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="historialMovimientos">
                            <!-- Se carga dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
    // Simulando llamada a la API - reemplaza con tu endpoint real
    fetch('/diaztecAdmin/index.php?action=obtenerInventario')
        .then(response => response.json())
        .then(data => {
            productos = data.productos || [];
            productosFiltrados = [...productos];
            actualizarEstadisticas();
            renderizarTabla();
            renderizarPaginacion();
        })
        .catch(error => {
            console.error('Error al cargar inventario:', error);
            // Datos de ejemplo para demostración
            productos = [
                {
                    id: 1,
                    codigo: 'TEL001',
                    nombre: 'iPhone 13 Pro',
                    categoria: 'electronica',
                    marca: 'Apple',
                    descripcion: 'Teléfono inteligente de alta gama',
                    stock_actual: 15,
                    stock_minimo: 5,
                    precio_compra: 800.00,
                    precio_venta: 1200.00,
                    proveedor: 'Tech Distributor',
                    ubicacion: 'A-1',
                    fecha_creacion: '2024-01-15'
                },
                {
                    id: 2,
                    codigo: 'REP001',
                    nombre: 'Pantalla Samsung A52',
                    categoria: 'repuestos',
                    marca: 'Samsung',
                    descripcion: 'Pantalla de repuesto original',
                    stock_actual: 3,
                    stock_minimo: 10,
                    precio_compra: 50.00,
                    precio_venta: 85.00,
                    proveedor: 'Parts Express',
                    ubicacion: 'B-3',
                    fecha_creacion: '2024-02-01'
                },
                {
                    id: 3,
                    codigo: 'ACC001',
                    nombre: 'Cargador Universal USB-C',
                    categoria: 'accesorios',
                    marca: 'Anker',
                    descripcion: 'Cargador rápido 25W',
                    stock_actual: 0,
                    stock_minimo: 8,
                    precio_compra: 15.00,
                    precio_venta: 25.00,
                    proveedor: 'Accessories Plus',
                    ubicacion: 'C-2',
                    fecha_creacion: '2024-01-20'
                }
            ];
            productosFiltrados = [...productos];
            actualizarEstadisticas();
            renderizarTabla();
            renderizarPaginacion();
        });
}

// Función para actualizar estadísticas
function actualizarEstadisticas() {
    const totalProductos = productos.length;
    const valorTotal = productos.reduce((total, p) => total + (p.stock_actual * p.precio_venta), 0);
    const stockBajo = productos.filter(p => p.stock_actual <= p.stock_minimo && p.stock_actual > 0).length;
    const stockAgotado = productos.filter(p => p.stock_actual === 0).length;
    
    document.getElementById('totalProductos').textContent = totalProductos;
    document.getElementById('valorInventario').textContent = '$' + valorTotal.toLocaleString('es-CO');
    document.getElementById('stockBajo').textContent = stockBajo;
    document.getElementById('stockAgotado').textContent = stockAgotado;
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
        const estadoStock = obtenerEstadoStock(producto.stock_actual, producto.stock_minimo);
        const valorTotal = producto.stock_actual * producto.precio_venta;
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><code>${producto.codigo || 'N/A'}</code></td>
            <td>
                <div>
                    <strong>${producto.nombre}</strong>
                    <br>
                    <small class="text-muted">${producto.marca || ''}</small>
                </div>
            </td>
            <td>
                <span class="badge bg-secondary">${producto.categoria}</span>
            </td>
            <td class="text-center">
                <strong>${producto.stock_actual}</strong>
            </td>
            <td class="text-center">
                ${producto.stock_minimo}
            </td>
            <td>$${parseFloat(producto.precio_compra || 0).toLocaleString('es-CO')}</td>
            <td>$${parseFloat(producto.precio_venta).toLocaleString('es-CO')}</td>
            <td>$${valorTotal.toLocaleString('es-CO')}</td>
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
    const categoria = document.getElementById('filtroCategoria').value;
    const stock = document.getElementById('filtroStock').value;
    
    productosFiltrados = productos.filter(producto => {
        const cumpleBusqueda = !busqueda || 
            producto.nombre.toLowerCase().includes(busqueda) ||
            (producto.codigo && producto.codigo.toLowerCase().includes(busqueda)) ||
            producto.categoria.toLowerCase().includes(busqueda);
            
        const cumpleCategoria = !categoria || producto.categoria === categoria;
        
        const estadoStock = obtenerEstadoStock(producto.stock_actual, producto.stock_minimo);
        const cumpleStock = !stock || estadoStock === stock;
        
        return cumpleBusqueda && cumpleCategoria && cumpleStock;
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
    const categoria = document.getElementById('filtroCategoria').value;
    const stock = document.getElementById('filtroStock').value;
    
    if (busqueda) {
        contenedor.innerHTML += `
            <span class="filter-chip">
                Búsqueda: ${busqueda}
                <button type="button" class="btn-close" onclick="limpiarFiltro('buscarProducto')"></button>
            </span>
        `;
    }
    
    if (categoria) {
        contenedor.innerHTML += `
            <span class="filter-chip">
                Categoría: ${categoria}
                <button type="button" class="btn-close" onclick="limpiarFiltro('filtroCategoria')"></button>
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
    document.getElementById('filtroCategoria').value = '';
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
                valorA = a.stock_actual;
                valorB = b.stock_actual;
                break;
            case 'precio':
                valorA = a.precio_venta;
                valorB = b.precio_venta;
                break;
            case 'categoria':
                valorA = a.categoria;
                valorB = b.categoria;
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
    document.getElementById('productoCodigo').value = producto.codigo || '';
    document.getElementById('productoCategoria').value = producto.categoria;
    document.getElementById('productoMarca').value = producto.marca || '';
    document.getElementById('productoDescripcion').value = producto.descripcion || '';
    document.getElementById('productoStockActual').value = producto.stock_actual;
    document.getElementById('productoStockMinimo').value = producto.stock_minimo;
    document.getElementById('productoPrecioCompra').value = producto.precio_compra || '';
    document.getElementById('productoPrecioVenta').value = producto.precio_venta;
    document.getElementById('productoProveedor').value = producto.proveedor || '';
    document.getElementById('productoUbicacion').value = producto.ubicacion || '';
    
    const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
    modal.show();
}

// Función para guardar producto
function guardarProducto(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarProducto');
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    // Simular guardado - reemplaza con tu endpoint real
    setTimeout(() => {
        const esEdicion = formData.get('id');
        
        if (esEdicion) {
            alert('Producto actualizado exitosamente');
        } else {
            alert('Producto creado exitosamente');
        }
        
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalProducto'));
        modal.hide();
        
        // Recargar inventario
        cargarInventario();
        
        // Restaurar botón
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-2"></i>Guardar Producto';
    }, 1000);
}

// Función para mostrar modal de ajustar stock
function mostrarModalAjustarStock(id) {
    const producto = productos.find(p => p.id === id);
    if (!producto) return;
    
    document.getElementById('ajusteProductoId').value = producto.id;
    document.getElementById('ajusteProductoNombre').value = producto.nombre;
    document.getElementById('ajusteStockActual').value = producto.stock_actual;
    document.getElementById('ajusteNuevoStock').value = producto.stock_actual;
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
        alert('El stock no puede ser negativo');
        return;
    }
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Ajustando...';
    
    // Simular ajuste - reemplaza con tu endpoint real
    setTimeout(() => {
        alert(`Stock ajustado exitosamente. Cambio: ${stockActual} → ${nuevoStock}`);
        
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalAjustarStock'));
        modal.hide();
        
        // Recargar inventario
        cargarInventario();
        
        // Restaurar botón
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Ajustar Stock';
    }, 1000);
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
    
    if (confirm(`¿Estás seguro de que deseas eliminar el producto "${producto.nombre}"?`)) {
        // Simular eliminación - reemplaza con tu endpoint real
        alert('Producto eliminado exitosamente');
        cargarInventario();
    }
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
</script>

<?php require_once 'footer.php'; ?>
