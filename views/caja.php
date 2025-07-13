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
<link rel="stylesheet" href="assets/css/modules/caja.css">
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
                            <th>Orden</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Acciones</th>
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
                            <th>Factura</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Método</th>
                            <th>Acciones</th>
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
<script src="assets/js/modules/caja.js"></script>
<?php
require_once 'footer.php';
?>
