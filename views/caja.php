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
<!-- Bootstrap Select (si lo usas en otra parte) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<div class="container-fluid">
    <h3 class="mt-4 mb-3">Caja - Pagos Registrados</h3>
    <!-- Filtro de fechas -->
    <div class="row mb-3">
        <div class="col-md-12 d-flex flex-wrap align-items-end gap-2">
            <label class="form-label mb-0 me-2">Filtrar por fecha:</label>
            <input type="date" id="fechaInicio" class="form-control" style="max-width:180px;">
            <span class="mx-1">a</span>
            <input type="date" id="fechaFin" class="form-control" style="max-width:180px;">
            <button class="btn btn-secondary ms-2" id="btnHoy">Hoy</button>
            <button class="btn btn-secondary ms-1" id="btnAyer">Ayer</button>
            <button class="btn btn-primary ms-2" id="btnFiltrarFechas">Filtrar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h5>Pagos de Órdenes de Servicio</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaCajaOrdenes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha de Pago</th>
                            <th>ID Orden</th>
                            <th>Dinero Recibido</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyCajaPagosOrdenes">
                        <!-- Pagos de órdenes -->
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                <span id="totalCajaOrdenes" class="fw-bold"></span>
            </div>
        </div>
        <div class="col-md-6 position-relative">
            <h5>Pagos por Venta de Productos</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaCajaProductos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha de Pago</th>
                            <th>ID Venta</th>
                            <th>Dinero Recibido</th>
                            <th>Método de Pago</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyCajaPagosProductos">
                        <!-- Pagos de productos -->
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                <span id="totalCajaProductos" class="fw-bold"></span>
            </div>
        </div>
    </div>
    <!-- Barra inferior total ventas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="text-center p-3 bg-success text-white rounded shadow-lg" style="font-size:2rem; font-weight:bold;">
                Venta Total: <span id="ventaTotal">$0</span>
            </div>
        </div>
    </div>
</div>

<!-- jQuery y Bootstrap JS -->
<!-- jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery UI después de jQuery -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<!-- Bootstrap JS después de jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Select (si lo usas en otra parte) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
// --- Lógica de filtro de fechas y totales ---
function getFechaHoy() {
    const hoy = new Date();
    return hoy.toISOString().slice(0,10);
}
function getFechaAyer() {
    const ayer = new Date();
    ayer.setDate(ayer.getDate() - 1);
    return ayer.toISOString().slice(0,10);
}
function filtrarPorFechas() {
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;
    // Filtrar tabla de órdenes
    document.querySelectorAll('#tablaCajaOrdenes tbody tr').forEach(tr => {
        const fecha = tr.children[1]?.textContent.trim();
        if (!fechaInicio || !fechaFin) {
            tr.style.display = '';
            return;
        }
        if (fecha >= fechaInicio && fecha <= fechaFin) {
            tr.style.display = '';
        } else {
            tr.style.display = 'none';
        }
    });
    // Filtrar tabla de productos
    document.querySelectorAll('#tablaCajaProductos tbody tr').forEach(tr => {
        const fecha = tr.children[1]?.textContent.trim();
        if (!fechaInicio || !fechaFin) {
            tr.style.display = '';
            return;
        }
        if (fecha >= fechaInicio && fecha <= fechaFin) {
            tr.style.display = '';
        } else {
            tr.style.display = 'none';
        }
    });
    actualizarVentaTotal();
}
function actualizarVentaTotal() {
    let total = 0;
    // Sumar visibles de órdenes
    document.querySelectorAll('#tablaCajaOrdenes tbody tr').forEach(tr => {
        if (tr.style.display !== 'none') {
            const val = tr.children[3]?.textContent.replace(/[^\d]/g, '') || '0';
            total += parseInt(val, 10) || 0;
        }
    });
    // Sumar visibles de productos
    document.querySelectorAll('#tablaCajaProductos tbody tr').forEach(tr => {
        if (tr.style.display !== 'none') {
            const val = tr.children[3]?.textContent.replace(/[^\d]/g, '') || '0';
            total += parseInt(val, 10) || 0;
        }
    });
    document.getElementById('ventaTotal').textContent = '$' + total.toLocaleString('es-CO');
}
document.addEventListener('DOMContentLoaded', function() {
    cargarPagosCaja();
    // Inicializar fechas por defecto a hoy
    const hoy = getFechaHoy();
    document.getElementById('fechaInicio').value = hoy;
    document.getElementById('fechaFin').value = hoy;
    filtrarPorFechas();
    document.getElementById('btnHoy').onclick = function() {
        const hoy = getFechaHoy();
        document.getElementById('fechaInicio').value = hoy;
        document.getElementById('fechaFin').value = hoy;
        filtrarPorFechas();
    };
    document.getElementById('btnAyer').onclick = function() {
        const ayer = getFechaAyer();
        document.getElementById('fechaInicio').value = ayer;
        document.getElementById('fechaFin').value = ayer;
        filtrarPorFechas();
    };
    document.getElementById('btnFiltrarFechas').onclick = filtrarPorFechas;
    document.getElementById('fechaInicio').onchange = filtrarPorFechas;
    document.getElementById('fechaFin').onchange = filtrarPorFechas;
    // Inicializar Bootstrap Select
    $('.selectpicker').selectpicker();
});

function cargarPagosCaja() {
    fetch('index.php?action=obtenerPagosCaja')
        .then(r => r.json())
        .then(data => {
            // Pagos de órdenes
            const tbodyOrdenes = document.getElementById('tbodyCajaPagosOrdenes');
            tbodyOrdenes.innerHTML = '';
            let totalOrdenes = 0;
            if (Array.isArray(data) && data.length > 0) {
                data.forEach((pago) => {
                    totalOrdenes += Number(pago.dinero_recibido || 0);
                    tbodyOrdenes.innerHTML += `
                        <tr>
                            <td>${pago.id}</td>
                            <td>${pago.fecha_pago || ''}</td>
                            <td>${pago.orden_id || ''}</td>
                            <td>$${Number(pago.dinero_recibido || 0).toLocaleString('es-CO')}</td>
                        </tr>`;
                });
            } else {
                tbodyOrdenes.innerHTML = '<tr><td colspan="4" class="text-center">No hay pagos registrados.</td></tr>';
            }
            document.getElementById('totalCajaOrdenes').textContent = 'Total en caja (órdenes): $' + totalOrdenes.toLocaleString('es-CO');

            // Pagos de productos (ahora con método de pago)
            const tbodyProductos = document.getElementById('tbodyCajaPagosProductos');
            tbodyProductos.innerHTML = '';
            let totalProductos = 0;
            if (Array.isArray(data.pagos_productos) && data.pagos_productos.length > 0) {
                data.pagos_productos.forEach((pago) => {
                    totalProductos += Number(pago.dinero_recibido || 0);
                    tbodyProductos.innerHTML += `
                        <tr>
                            <td>${pago.id}</td>
                            <td>${pago.fecha_pago || ''}</td>
                            <td>${pago.venta_id || ''}</td>
                            <td>$${Number(pago.dinero_recibido || 0).toLocaleString('es-CO')}</td>
                            <td>${(pago.metodo_pago || '').charAt(0).toUpperCase() + (pago.metodo_pago || '').slice(1)}</td>
                        </tr>`;
                });
            } else {
                tbodyProductos.innerHTML = '<tr><td colspan="5" class="text-center">No hay pagos de productos registrados.</td></tr>';
            }
            document.getElementById('totalCajaProductos').textContent = 'Total en caja (productos): $' + totalProductos.toLocaleString('es-CO');
        });
}
</script>
<!-- Botón flotante para agregar pago de productos -->
<button id="btnFlotanteAgregarPagoProducto" type="button" class="btn btn-primary rounded-circle shadow"
    style="position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; z-index: 1050; display: flex; align-items: center; justify-content: center; font-size: 2rem;"
    title="Agregar venta de producto"
    data-bs-toggle="modal" data-bs-target="#modalPagoProducto">
    <i class="bi bi-plus"></i>
</button>

<!-- Modal vacío para registrar venta de productos -->
<div class="modal fade" id="modalPagoProducto" tabindex="-1" aria-labelledby="modalPagoProductoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPagoProductoLabel">Registrar Venta de Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formVentaProducto">
                    <div class="mb-3">
                        <label for="clienteBusqueda" class="form-label">Cliente</label>
                        <input type="text" class="form-control" id="clienteBusqueda" list="clientesList" placeholder="Buscar cliente por nombre o identificación...">
                        <datalist id="clientesList"><!-- Opciones dinámicas JS --></datalist>
                        <input type="hidden" id="clienteSeleccionadoId" name="cliente_id">
                        <button type="button" class="btn btn-link p-0 mt-1" id="btnNuevoCliente">Agregar cliente manualmente</button>
                    </div>
                    <div id="nuevoClienteFields" style="display:none;">
                        <div class="mb-2">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nuevoClienteNombre" name="nuevo_cliente_nombre">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="nuevoClienteTelefono" name="nuevo_cliente_telefono">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="nuevoClienteEmail" name="nuevo_cliente_email">
                        </div>
                    </div>
                    <!-- Buscador de productos -->
                    <div class="mb-3">
                        <label for="productoBusqueda" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="productoBusqueda" list="productosList" placeholder="Buscar producto por nombre...">
                        <datalist id="productosList"><!-- Opciones dinámicas JS --></datalist>
                    </div>
                    <!-- Tabla de productos seleccionados -->
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm" id="tablaProductosSeleccionados" style="font-size:0.78rem;">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- Total, pago y cambio -->
                    <div class="mb-3 text-center">
                        <div style="font-size:1.3rem; font-weight:bold; color:#198754; background:#e9fbe7; border-radius:8px; padding:8px 0; margin-bottom:10px;">
                            Total a pagar: <span id="totalVentaProductos">$0</span>
                        </div>
                        <div class="row g-2 justify-content-center align-items-center">
                            <div class="col-12 col-md-4">
                                <label for="inputConCuantoPagan" class="form-label mb-1">¿Con cuánto pagan?</label>
                                <input type="number" min="0" step="any" class="form-control text-center" id="inputConCuantoPagan" placeholder="¿Con cuánto pagan?">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="inputCambioVuelto" class="form-label mb-1">Cambio / Vueltas</label>
                                <input type="text" class="form-control text-center bg-light fw-bold" id="inputCambioVuelto" placeholder="Cambio / Vueltas" readonly>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="inputTipoPago" class="form-label mb-1">Tipo de Pago</label>
                                <select class="form-select text-center" id="inputTipoPago">
                                    <option value="" selected disabled>Tipo Pago</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Nequi">Nequi</option>
                                    <option value="Daviplata">Daviplata</option>
                                    <option value="Transferencia">Transferencia</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-primary px-4" id="btnGuardarVenta"><i class="bi bi-save me-2"></i>Guardar Venta</button>
                        </div>
                    </div>
                    <!-- Aquí irán los campos de productos y pago -->
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- CLIENTES ---
    const modalVenta = document.getElementById('modalPagoProducto');
    const inputBusqueda = document.getElementById('clienteBusqueda');
    const datalist = document.getElementById('clientesList');
    const inputClienteId = document.getElementById('clienteSeleccionadoId');
    let clientes = [];
    document.getElementById('btnNuevoCliente').onclick = function() {
        const fields = document.getElementById('nuevoClienteFields');
        fields.style.display = fields.style.display === 'none' ? 'block' : 'none';
    };
    if (modalVenta) {
        modalVenta.addEventListener('show.bs.modal', function() {
            datalist.innerHTML = '';
            inputBusqueda.value = '';
            inputClienteId.value = '';
            // --- PRODUCTOS ---
            fetch('index.php?action=obtenerProductos')
                .then(r => r.json())
                .then(data => {
                    productos = data;
                    datalistProductos.innerHTML = '';
                    productos.forEach(p => {
                        datalistProductos.innerHTML += `<option value="${p.nombre}" data-id="${p.id}" data-precio="${p.precio}">${p.nombre} ($${parseFloat(p.precio).toLocaleString('es-CO')})</option>`;
                    });
                    productosSeleccionados = [];
                    renderTablaProductos();
                    inputProducto.value = '';
                });
        });
    }
    inputBusqueda.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length > 1) {
            fetch(`index.php?action=buscarCliente&query=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(data => {
                    clientes = data.clientes || [];
                    datalist.innerHTML = '';
                    clientes.forEach(c => {
                        datalist.innerHTML += `<option value="${c.nombre} (${c.identificacion})" data-id="${c.id}">${c.nombre} (${c.identificacion})</option>`;
                    });
                });
        } else {
            datalist.innerHTML = '';
        }
        inputClienteId.value = '';
    });
    inputBusqueda.addEventListener('change', function() {
        const valor = this.value;
        const cliente = clientes.find(c => `${c.nombre} (${c.identificacion})` === valor);
        if (cliente) {
            inputClienteId.value = cliente.id;
        } else {
            inputClienteId.value = '';
        }
    });
    // --- PRODUCTOS ---
    let productos = [];
    let productosSeleccionados = [];
    const inputProducto = document.getElementById('productoBusqueda');
    const datalistProductos = document.getElementById('productosList');
    const tablaProductos = document.getElementById('tablaProductosSeleccionados').querySelector('tbody');
    // Búsqueda dinámica de productos
    let ignoreInputEvent = false;
    inputProducto.addEventListener('input', function(e) {
        if (ignoreInputEvent) return; // Evita doble ejecución
        const query = this.value.trim().toLowerCase();
        datalistProductos.innerHTML = '';
        if (query.length > 1) {
            productos.filter(p => p.nombre.toLowerCase().includes(query)).forEach(p => {
                datalistProductos.innerHTML += `<option value="${p.nombre}" data-id="${p.id}" data-precio="${p.precio}">${p.nombre} ($${parseFloat(p.precio).toLocaleString('es-CO')})</option>`;
            });
        }
        // Si el valor coincide exactamente con un producto, lo agrega de inmediato
        const prod = productos.find(p => p.nombre.toLowerCase() === query);
        if (prod) {
            agregarProductoSeleccionado(prod);
        }
    });
    inputProducto.addEventListener('change', function() {
        const nombre = inputProducto.value.trim();
        const prod = productos.find(p => p.nombre === nombre);
        if (prod) {
            agregarProductoSeleccionado(prod);
        }
    });
    function agregarProductoSeleccionado(prod) {
        const existente = productosSeleccionados.find(p => p.id === prod.id);
        if (existente) {
            existente.cantidad++;
        } else {
            productosSeleccionados.push({
                id: prod.id,
                nombre: prod.nombre,
                precio: parseFloat(prod.precio),
                cantidad: 1
            });
        }
        renderTablaProductos();
        // Evita que el input vuelva a disparar el evento input al limpiar
        ignoreInputEvent = true;
        inputProducto.value = '';
        setTimeout(() => { ignoreInputEvent = false; }, 100);
    }
    // Eliminar el botón Agregar producto si existe
    const btnAgregarProducto = document.getElementById('btnAgregarProducto');
    if (btnAgregarProducto) btnAgregarProducto.style.display = 'none';
    function renderTablaProductos() {
        const fragment = document.createDocumentFragment();
        let total = 0;
        productosSeleccionados.forEach((p, idx) => {
            total += p.precio * p.cantidad;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${p.nombre}</td>
                <td>$${p.precio.toLocaleString('es-CO')}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger rounded-circle btn-restar d-inline-flex align-items-center justify-content-center" style="width:22px;height:22px;padding:0;font-size:0.88rem;" data-idx="${idx}" title="Restar">-</button>
                    <span class="mx-1" style="min-width:20px;display:inline-block;font-size:0.95rem;">${p.cantidad}</span>
                    <button type="button" class="btn btn-sm btn-success rounded-circle btn-sumar d-inline-flex align-items-center justify-content-center" style="width:22px;height:22px;padding:0;font-size:0.88rem;" data-idx="${idx}" title="Sumar">+</button>
                </td>
                <td>$${(p.precio * p.cantidad).toLocaleString('es-CO')}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle btn-eliminar d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;padding:0;" data-idx="${idx}" title="Eliminar">
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
                            <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5.5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6zm3 .5a.5.5 0 0 1 .5-.5.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6zm-7-1A1.5 1.5 0 0 1 5.5 4h5A1.5 1.5 0 0 1 12 5.5V6h1a.5.5 0 0 1 0 1h-1v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7H3a.5.5 0 0 1 0-1h1v-.5zM6 5a.5.5 0 0 0-.5.5V6h5v-.5A.5.5 0 0 0 10.5 5h-5z'/>
                        </svg>
                    </button>
                </td>
            `;
            fragment.appendChild(tr);
        });
        tablaProductos.innerHTML = '';
        tablaProductos.appendChild(fragment);
        document.getElementById('totalVentaProductos').textContent = '$' + total.toLocaleString('es-CO');
        calcularCambio();
        tablaProductos.querySelectorAll('.btn-restar').forEach(btn => {
            btn.onclick = function() {
                const idx = parseInt(this.getAttribute('data-idx'));
                if (productosSeleccionados[idx].cantidad > 1) {
                    productosSeleccionados[idx].cantidad--;
                    renderTablaProductos();
                }
            };
        });
        tablaProductos.querySelectorAll('.btn-sumar').forEach(btn => {
            btn.onclick = function() {
                const idx = parseInt(this.getAttribute('data-idx'));
                productosSeleccionados[idx].cantidad++;
                renderTablaProductos();
            };
        });
        tablaProductos.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.onclick = function() {
                const idx = parseInt(this.getAttribute('data-idx'));
                productosSeleccionados.splice(idx, 1);
                renderTablaProductos();
            };
        });
    }
    // Calcular cambio/vueltas
    function calcularCambio() {
        const total = productosSeleccionados.reduce((s, p) => s + p.precio * p.cantidad, 0);
        const pago = parseFloat(document.getElementById('inputConCuantoPagan').value) || 0;
        let cambio = '';
        if (pago > 0 && pago >= total) {
            cambio = '$' + (pago - total).toLocaleString('es-CO');
        } else if (pago > 0 && pago < total) {
            cambio = 'Falta $' + (total - pago).toLocaleString('es-CO');
        } else {
            cambio = '';
        }
        document.getElementById('inputCambioVuelto').value = cambio;
    }
    document.getElementById('inputConCuantoPagan').addEventListener('input', calcularCambio);
});
</script>
<?php
require_once 'footer.php';
?>
