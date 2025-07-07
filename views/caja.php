<?php
require_once 'header.php';
?>
<div class="container-fluid">
    <h3 class="mt-4 mb-3">Caja - Pagos Registrados</h3>
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
                        </tr>
                    </thead>
                    <tbody id="tbodyCajaPagosProductos">
                        <tr><td colspan="4" class="text-center">No hay pagos de productos registrados.</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                <span id="totalCajaProductos" class="fw-bold"></span>
            </div>
            <!-- Botón flotante para agregar pago de productos -->
            <button id="btnFlotanteAgregarPagoProducto" type="button" class="btn btn-primary rounded-circle shadow" 
                style="position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; z-index: 1050; display: flex; align-items: center; justify-content: center; font-size: 2rem;" 
                title="Agregar pago de producto"
                onclick="abrirModalPagoProducto()">
                <i class="bi bi-plus"></i>
            </button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarPagosCaja();
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

            // Pagos de productos (placeholder, puedes conectar a tu backend cuando esté listo)
            const tbodyProductos = document.getElementById('tbodyCajaPagosProductos');
            tbodyProductos.innerHTML = '<tr><td colspan="4" class="text-center">No hay pagos de productos registrados.</td></tr>';
            document.getElementById('totalCajaProductos').textContent = 'Total en caja (productos): $0';
        });
}

// Función placeholder para el modal de pago de productos
function abrirModalPagoProducto() {
    alert('Aquí se abrirá el formulario para registrar un pago de producto. (Próximamente)');
}
</script>
<?php
require_once 'footer.php';
?>
