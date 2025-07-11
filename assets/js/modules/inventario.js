// inventario.js - Lógica independiente para el módulo de inventario

// Variables globales
let productos = [];
let productosFiltrados = [];
let paginaActual = 1;
let productosPorPagina = 10;
let ordenActual = 'nombre';
let direccionOrden = 'asc';

// Inicialización
window.addEventListener('DOMContentLoaded', function() {
    cargarInventario();
    document.getElementById('formProducto').addEventListener('submit', guardarProducto);
    document.getElementById('formAjustarStock').addEventListener('submit', ajustarStock);
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

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

function actualizarEstadisticas() {
    fetch('index.php?action=obtenerEstadisticasInventario')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.estadisticas;
                document.getElementById('totalProductos').textContent = stats.total_productos || 0;
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
            if (productos.length > 0) {
                const totalProductos = productos.length;
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

function obtenerEstadoStock(stockActual, stockMinimo) {
    if (stockActual === 0) return 'agotado';
    if (stockActual <= stockMinimo) return 'bajo';
    if (stockActual <= stockMinimo * 2) return 'medio';
    return 'alto';
}

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
    document.getElementById('mostrandoDesde').textContent = inicio + 1;
    document.getElementById('mostrandoHasta').textContent = Math.min(fin, productosFiltrados.length);
    document.getElementById('totalRegistros').textContent = productosFiltrados.length;
}

// Paginación
function renderizarPaginacion() {
    const paginacion = document.getElementById('paginacion');
    paginacion.innerHTML = '';
    const totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
    for (let i = 1; i <= totalPaginas; i++) {
        const li = document.createElement('li');
        li.className = 'page-item' + (i === paginaActual ? ' active' : '');
        li.innerHTML = `<button class="page-link" onclick="cambiarPagina(${i})">${i}</button>`;
        paginacion.appendChild(li);
    }
}

function cambiarPagina(pagina) {
    paginaActual = pagina;
    renderizarTabla();
    renderizarPaginacion();
}

// Filtros y búsqueda
function filtrarProductos() {
    const texto = document.getElementById('buscarProducto').value.toLowerCase();
    const estado = document.getElementById('filtroStock').value;
    productosFiltrados = productos.filter(p => {
        let coincide = p.nombre.toLowerCase().includes(texto) || (p.descripcion && p.descripcion.toLowerCase().includes(texto));
        if (estado) {
            coincide = coincide && obtenerEstadoStock(p.stock, p.stock_minimo) === estado;
        }
        return coincide;
    });
    paginaActual = 1;
    renderizarTabla();
    renderizarPaginacion();
}

function ordenarProductos() {
    const campo = document.getElementById('ordenarPor').value;
    if (ordenActual === campo) {
        direccionOrden = direccionOrden === 'asc' ? 'desc' : 'asc';
    } else {
        ordenActual = campo;
        direccionOrden = 'asc';
    }
    productosFiltrados.sort((a, b) => {
        if (a[campo] < b[campo]) return direccionOrden === 'asc' ? -1 : 1;
        if (a[campo] > b[campo]) return direccionOrden === 'asc' ? 1 : -1;
        return 0;
    });
    paginaActual = 1;
    renderizarTabla();
    renderizarPaginacion();
}

function limpiarFiltros() {
    document.getElementById('buscarProducto').value = '';
    document.getElementById('filtroStock').value = '';
    document.getElementById('ordenarPor').value = 'nombre';
    productosFiltrados = [...productos];
    paginaActual = 1;
    renderizarTabla();
    renderizarPaginacion();
}

// Modal agregar producto
function mostrarModalAgregarProducto() {
    document.getElementById('formProducto').reset();
    document.getElementById('productoId').value = '';
    document.getElementById('modalProductoLabel').textContent = 'Agregar Producto';
    const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
    modal.show();
}

// Guardar producto
function guardarProducto(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    let url = 'index.php?action=crearProducto';
    if (form.productoId.value) {
        url = 'index.php?action=actualizarProducto';
    }
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Éxito', data.message, 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalProducto'));
            modal.hide();
            cargarInventario();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'No se pudo guardar el producto', 'error');
    });
}

// Editar producto
function editarProducto(id) {
    const producto = productos.find(p => p.id === id);
    if (!producto) return;
    document.getElementById('productoId').value = producto.id;
    document.getElementById('productoNombre').value = producto.nombre;
    document.getElementById('productoCategoria').value = producto.categoria;
    document.getElementById('productoDescripcion').value = producto.descripcion;
    document.getElementById('productoCodigoBarras').value = producto.codigo_barras;
    document.getElementById('productoStockActual').value = producto.stock;
    document.getElementById('productoStockMinimo').value = producto.stock_minimo;
    document.getElementById('productoPrecioCompra').value = producto.precio_compra;
    document.getElementById('productoPrecioVenta').value = producto.precio_venta;
    document.getElementById('modalProductoLabel').textContent = 'Editar Producto';
    const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
    modal.show();
}

// Eliminar producto
function eliminarProducto(id) {
    Swal.fire({
        title: '¿Eliminar producto?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('index.php?action=eliminarProducto', {
                method: 'POST',
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminado', data.message, 'success');
                    cargarInventario();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudo eliminar el producto', 'error');
            });
        }
    });
}

// Modal ajustar stock
function mostrarModalAjustarStock(id) {
    const producto = productos.find(p => p.id === id);
    if (!producto) return;
    document.getElementById('ajusteProductoId').value = producto.id;
    document.getElementById('ajusteProductoNombre').value = producto.nombre;
    document.getElementById('ajusteStockActual').value = producto.stock;
    document.getElementById('ajusteNuevoStock').value = '';
    document.getElementById('ajusteTipo').value = '';
    document.getElementById('ajusteMotivo').value = '';
    const modal = new bootstrap.Modal(document.getElementById('modalAjustarStock'));
    modal.show();
}

// Ajustar stock
function ajustarStock(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    fetch('index.php?action=ajustarStockProducto', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Éxito', data.message, 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalAjustarStock'));
            modal.hide();
            cargarInventario();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'No se pudo ajustar el stock', 'error');
    });
}

// Ver historial de movimientos
function verHistorial(id) {
    const producto = productos.find(p => p.id === id);
    if (!producto) return;
    document.getElementById('historialProductoNombre').textContent = producto.nombre;
    fetch(`index.php?action=historialMovimientos&producto_id=${id}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('historialMovimientos');
            tbody.innerHTML = '';
            if (data.success && data.movimientos) {
                data.movimientos.forEach(mov => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${mov.fecha}</td>
                        <td>${mov.tipo}</td>
                        <td>${mov.stock_anterior}</td>
                        <td>${mov.cambio}</td>
                        <td>${mov.stock_nuevo}</td>
                        <td>${mov.motivo}</td>
                        <td>${mov.usuario}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        });
    const modal = new bootstrap.Modal(document.getElementById('modalHistorial'));
    modal.show();
}

// Resumen financiero
function mostrarResumenFinanciero() {
    Swal.fire({
        title: '<i class="fas fa-chart-pie me-2"></i>Resumen Financiero',
        html: `
            <div style="display:flex;flex-direction:column;align-items:center;gap:16px;">
                <div style="background:#e3f2fd;border-radius:12px;padding:16px;width:100%;max-width:340px;box-shadow:0 2px 8px #0001;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i class="fas fa-shopping-cart text-primary fa-2x"></i>
                        <div>
                            <div class="fw-bold text-primary">Valor Costo</div>
                            <div id="swalValorCosto" class="fs-5"></div>
                        </div>
                    </div>
                </div>
                <div style="background:#e8f5e9;border-radius:12px;padding:16px;width:100%;max-width:340px;box-shadow:0 2px 8px #0001;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i class="fas fa-dollar-sign text-success fa-2x"></i>
                        <div>
                            <div class="fw-bold text-success">Valor Venta</div>
                            <div id="swalValorVenta" class="fs-5"></div>
                        </div>
                    </div>
                </div>
                <div style="background:#fff3e0;border-radius:12px;padding:16px;width:100%;max-width:340px;box-shadow:0 2px 8px #0001;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i class="fas fa-chart-line text-warning fa-2x"></i>
                        <div>
                            <div class="fw-bold text-warning">Margen Potencial</div>
                            <div id="swalMargen" class="fs-5"></div>
                        </div>
                    </div>
                </div>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'Cerrar',
        width: 400,
        didOpen: () => {
            document.getElementById('swalValorCosto').textContent = document.getElementById('valorInventarioCosto').textContent;
            document.getElementById('swalValorVenta').textContent = document.getElementById('valorInventarioVenta').textContent;
            document.getElementById('swalMargen').textContent = document.getElementById('margenGanancia').textContent;
        }
    });
}

// Exportar inventario
function exportarInventario() {
    window.open('index.php?action=exportarInventario', '_blank');
}

// Mostrar productos bajo stock
function mostrarProductosBajoStock() {
    productosFiltrados = productos.filter(p => obtenerEstadoStock(p.stock, p.stock_minimo) === 'bajo');
    paginaActual = 1;
    renderizarTabla();
    renderizarPaginacion();
}

// Mostrar fecha actual
window.addEventListener('DOMContentLoaded', function() {
    const fecha = new Date();
    const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('fechaActual').textContent = fecha.toLocaleDateString('es-CO', opciones);
});
