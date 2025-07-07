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
                    <!-- Aquí irán los campos de productos y pago -->
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('btnNuevoCliente').onclick = function() {
    const fields = document.getElementById('nuevoClienteFields');
    fields.style.display = fields.style.display === 'none' ? 'block' : 'none';
};

document.addEventListener('DOMContentLoaded', function() {
    const modalVenta = document.getElementById('modalPagoProducto');
    const inputBusqueda = document.getElementById('clienteBusqueda');
    const datalist = document.getElementById('clientesList');
    const inputClienteId = document.getElementById('clienteSeleccionadoId');
    let clientes = [];
    if (modalVenta) {
        modalVenta.addEventListener('show.bs.modal', function() {
            datalist.innerHTML = '';
            inputBusqueda.value = '';
            inputClienteId.value = '';
        });
    }
    // Búsqueda dinámica por nombre o identificación
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
    // Guardar el ID del cliente seleccionado
    inputBusqueda.addEventListener('change', function() {
        const valor = this.value;
        const cliente = clientes.find(c => `${c.nombre} (${c.identificacion})` === valor);
        if (cliente) {
            inputClienteId.value = cliente.id;
        } else {
            inputClienteId.value = '';
        }
    });
});
</script>
<?php
require_once 'footer.php';
?>
