<?php
require_once 'header.php';
?>
<div class="container-fluid">
    <h3 class="mt-4 mb-3">Caja - Movimientos de Pagos</h3>
    <p>Consulta y gestiona todos los pagos y abonos realizados tanto a órdenes de reparación como a productos vendidos.</p>
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="buscadorPagos" class="form-control" placeholder="Buscar por cliente, orden, producto, método, etc...">
        </div>
        <div class="col-md-3">
            <select id="filtroTipo" class="form-select">
                <option value="">Todos los tipos</option>
                <option value="orden">Órdenes</option>
                <option value="producto">Productos</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filtroMetodo" class="form-select">
                <option value="">Todos los métodos</option>
                <option value="efectivo">Efectivo</option>
                <option value="nequi">Nequi</option>
                <option value="daviplata">Daviplata</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary w-100" onclick="limpiarFiltrosCaja()">Limpiar filtros</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablaCaja">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Referencia</th>
                    <th>Cliente</th>
                    <th>Método</th>
                    <th>Monto</th>
                    <th>Abono</th>
                    <th>Saldo</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody id="tbodyCajaPagos">
                <!-- Aquí se cargarán los pagos vía JS -->
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <span id="totalCaja" class="fw-bold"></span>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarPagosCaja();
    document.getElementById('buscadorPagos').addEventListener('input', cargarPagosCaja);
    document.getElementById('filtroTipo').addEventListener('change', cargarPagosCaja);
    document.getElementById('filtroMetodo').addEventListener('change', cargarPagosCaja);
});
function limpiarFiltrosCaja() {
    document.getElementById('buscadorPagos').value = '';
    document.getElementById('filtroTipo').value = '';
    document.getElementById('filtroMetodo').value = '';
    cargarPagosCaja();
}
function cargarPagosCaja() {
    const q = document.getElementById('buscadorPagos').value.trim();
    const tipo = document.getElementById('filtroTipo').value;
    const metodo = document.getElementById('filtroMetodo').value;
    fetch(`index.php?action=obtenerPagosCaja&q=${encodeURIComponent(q)}&tipo=${tipo}&metodo=${metodo}`)
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('tbodyCajaPagos');
            tbody.innerHTML = '';
            let total = 0;
            if (Array.isArray(data) && data.length > 0) {
                data.forEach((pago, idx) => {
                    total += Number(pago.abono || pago.monto || 0);
                    tbody.innerHTML += `
                        <tr>
                            <td>${idx+1}</td>
                            <td>${pago.fecha_pago || ''}</td>
                            <td>${pago.tipo == 'producto' ? 'Producto' : 'Orden'}</td>
                            <td>${pago.tipo == 'producto' ? (pago.producto_nombre || '-') : ('Orden #' + pago.orden_id)}</td>
                            <td>${pago.cliente_nombre || ''}</td>
                            <td>${pago.metodo_pago ? pago.metodo_pago.charAt(0).toUpperCase() + pago.metodo_pago.slice(1) : ''}</td>
                            <td>$${Number(pago.monto || pago.abono || 0).toLocaleString('es-CO')}</td>
                            <td>$${Number(pago.abono || 0).toLocaleString('es-CO')}</td>
                            <td>$${Number(pago.saldo || 0).toLocaleString('es-CO')}</td>
                            <td>${pago.descripcion_repuestos || pago.descripcion || ''}</td>
                        </tr>`;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="10" class="text-center">No hay pagos registrados.</td></tr>';
            }
            document.getElementById('totalCaja').textContent = 'Total en caja: $' + total.toLocaleString('es-CO');
        });
}
</script>
<?php
require_once 'footer.php';
