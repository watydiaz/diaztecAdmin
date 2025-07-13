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
<style>
    html, body {
        background: #ffffff !important;
        min-height: 100vh;
    }
    .dashboard-container {
        min-height: 100vh;
        padding: 20px 0;
        overflow-x: hidden;
        width: 100%;
    }
    .welcome-header-caja {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: center;
        color: #212529;
        border: 2px solid #ced4da;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .caja-card {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
        min-height: 130px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .caja-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }
    /* Colores suaves y elegantes similares al dashboard */
    .gradient-success { background: linear-gradient(45deg, #56ab2f, #a8e063); }
    .gradient-info { background: linear-gradient(45deg, #4facfe, #00f2fe); }
    .gradient-primary { background: linear-gradient(45deg, #667eea, #764ba2); }
    .gradient-dark { background: linear-gradient(45deg, #232526, #414345); }
    .gradient-total { background: linear-gradient(90deg, #667eea, #764ba2); }
    .caja-card .card-title { font-size: 1.2rem; font-weight: 600; }
    .caja-card .card-text { font-size: 2rem; font-weight: bold; }
    .caja-section-title { color: #333; margin-bottom: 20px; font-weight: 600; border-bottom: 3px solid #4a6fa5; padding-bottom: 10px; }
    .table thead th { background: #000 !important; color: #fff !important; border: none; }
    .table-bordered td, .table-bordered th { border: 1.5px solid #6366f1; }
    .rounded { border-radius: 15px !important; }
    .shadow-lg { box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; }
    .btn-success, .bg-success { background: linear-gradient(45deg, #56ab2f, #a8e063) !important; border: none; }
    .btn-primary, .bg-primary { background: linear-gradient(45deg, #667eea, #764ba2) !important; border: none; }
    .btn-info, .bg-info { background: linear-gradient(45deg, #4facfe, #00f2fe) !important; border: none; }
    .btn-dark, .bg-dark { background: linear-gradient(45deg, #232526, #414345) !important; border: none; }
    .btn-agregar-venta {
        background: linear-gradient(45deg, #667eea, #764ba2) !important;
        color: #fff !important;
        border: none;
    }
    .modal-content { border-radius: 15px; }
    @media (max-width: 768px) {
        .welcome-header-caja { padding: 15px; margin-bottom: 15px; }
        .caja-section-title { font-size: 1.1rem; padding-bottom: 6px; }
    }
</style>
<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Card Header -->
        <div class="card shadow-lg mb-4" style="border-radius:18px;">
            <div class="welcome-header-caja card-body" style="background:linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);border:none;box-shadow:none;">
                <h1><i class="fas fa-cash-register me-3"></i>Caja - Pagos Registrados</h1>
                <p class="mb-0">Control y registro de pagos de productos y servicios</p>
            </div>
        </div>
        <!-- Cards de Métodos de Pago -->
        <div class="row mb-4" id="rowCardsTotales">
            <div class="col-md-3">
                <div class="card caja-card text-white gradient-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-money-bill-wave me-2"></i>Total Efectivo</h5>
                        <p class="card-text fs-4" id="totalEfectivo">$0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card caja-card text-white gradient-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fab fa-neos me-2"></i>Total Nequi</h5>
                        <p class="card-text fs-4" id="totalNequi">$0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card caja-card text-white gradient-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-university me-2"></i>Total Daviplata</h5>
                        <p class="card-text fs-4" id="totalDaviplata">$0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card caja-card text-white gradient-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-credit-card me-2"></i>Total Tarjeta Crédito</h5>
                        <p class="card-text fs-4" id="totalTarjeta">$0</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cards de Totales por Tipo -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-4">
                <div class="card caja-card text-white mb-3" style="background: linear-gradient(45deg, #ff6b6b, #ee5a24);">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-tools me-2"></i>Total en Caja (Órdenes)</h5>
                        <p class="card-text fs-4" id="totalCajaOrdenes">$0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card caja-card text-white mb-3" style="background: linear-gradient(45deg, #26de81, #20bf6b);">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-box-open me-2"></i>Total en Caja (Productos)</h5>
                        <p class="card-text fs-4" id="totalCajaProductos">$0</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Venta Total -->
        <div class="text-center p-3 text-white rounded shadow-lg mb-4 gradient-total" style="font-size:2rem; font-weight:bold;">
            Venta Total: <span id="ventaTotal">$0</span>
        </div>
        <!-- Card Filtro y Tablas -->
        <div class="card shadow-lg mb-4" style="border-radius:18px;">
            <div class="card-body">
                <div id="infoPeriodo" class="alert alert-info text-center mb-3" style="border-radius:12px;"></div>
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
                        <h5 class="caja-section-title"><i class="fas fa-tools me-2"></i>Pagos de Órdenes de Servicio</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaCajaOrdenes">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Método</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCajaPagosOrdenes">
                                    <!-- Pagos de órdenes -->
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            <span id="totalCajaOrdenesTabla" class="fw-bold"></span>
                        </div>
                    </div>
                    <div class="col-md-6 position-relative">
                        <h5 class="caja-section-title"><i class="fas fa-box-open me-2"></i>Pagos por Venta de Productos</h5>
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
                            <span id="totalCajaProductosTabla" class="fw-bold"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Botón flotante para nueva venta -->
<button id="btnNuevaVenta" class="btn btn-agregar-venta rounded-circle shadow-lg" style="position: fixed; bottom: 32px; right: 32px; z-index: 1050; width: 64px; height: 64px; font-size: 2rem; display: flex; align-items: center; justify-content: center;">
    <i class="bi bi-plus"></i>
</button>

<!-- Modal Nueva Venta -->
<div class="modal fade" id="modalNuevaVenta" tabindex="-1" aria-labelledby="modalNuevaVentaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevaVentaLabel">Registrar Nueva Venta de Productos</h5>
        <span id="numeroFacturaVenta" class="badge bg-primary ms-3" style="font-size:1.1rem;"></span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Cliente -->
        <div class="mb-3">
          <label for="inputClienteVenta" class="form-label">Cliente</label>
          <div class="input-group">
            <input type="text" class="form-control" id="inputClienteVenta" placeholder="Buscar o crear cliente...">
            <button class="btn btn-outline-primary" type="button" id="btnCrearClienteVenta"><i class="bi bi-person-plus"></i> Nuevo</button>
          </div>
          <div id="sugerenciasClienteVenta" class="list-group position-absolute w-100" style="z-index: 2000;"></div>
        </div>
        <!-- Producto -->
        <div class="mb-3">
          <label for="inputProductoVenta" class="form-label">Producto</label>
          <div class="input-group">
            <input type="text" class="form-control" id="inputProductoVenta" placeholder="Buscar producto...">
            <button class="btn btn-outline-primary" type="button" id="btnAgregarProductoVenta"><i class="bi bi-plus-square"></i> Agregar</button>
          </div>
          <div id="sugerenciasProductoVenta" class="list-group position-absolute w-100" style="z-index: 2000;"></div>
        </div>
        <!-- Detalle de productos seleccionados -->
        <div class="mb-3">
          <label class="form-label">Detalle de productos</label>
          <div class="table-responsive">
            <table class="table table-bordered align-middle" id="tablaDetalleVenta">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Precio</th>
                  <th>Cantidad</th>
                  <th>Subtotal</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Productos seleccionados -->
              </tbody>
            </table>
          </div>
        </div>
        <!-- Método de pago -->
        <div class="mb-3">
          <label class="form-label">Método de pago</label>
          <div id="grupoMetodoPago" class="d-flex flex-wrap gap-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="metodoPago" id="mpEfectivo" value="efectivo">
              <label class="form-check-label" for="mpEfectivo">Efectivo</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="metodoPago" id="mpNequi" value="nequi">
              <label class="form-check-label" for="mpNequi">Nequi</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="metodoPago" id="mpDaviplata" value="daviplata">
              <label class="form-check-label" for="mpDaviplata">Daviplata</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="metodoPago" id="mpDale" value="dale">
              <label class="form-check-label" for="mpDale">Dale</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="metodoPago" id="mpTarjeta" value="tarjeta_credito">
              <label class="form-check-label" for="mpTarjeta">Tarjeta Crédito</label>
            </div>
          </div>
        </div>
        <!-- Dinero recibido y cambio (solo efectivo) -->
        <div class="mb-3 d-none" id="grupoEfectivo">
          <div class="row g-2 align-items-end">
            <div class="col-md-6">
              <label for="inputDineroRecibido" class="form-label">Dinero recibido</label>
              <input type="number" min="0" step="100" class="form-control" id="inputDineroRecibido" placeholder="Monto entregado por el cliente">
            </div>
            <div class="col-md-6">
              <label class="form-label">Cambio</label>
              <input type="text" class="form-control" id="inputCambio" readonly>
            </div>
          </div>
        </div>
        <!-- Resumen -->
        <div class="mb-3 text-end">
          <span class="fw-bold">Total: </span>
          <span id="totalNuevaVenta" class="fw-bold fs-4 text-success">$0</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnRegistrarVenta">Registrar Venta</button>
      </div>
    </div>
  </div>
</div>
<!-- Submodal para crear cliente -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-labelledby="modalNuevoClienteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevoClienteLabel">Nuevo Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevoCliente">
          <div class="mb-2">
            <label for="nuevoClienteNombre" class="form-label">Nombre *</label>
            <input type="text" class="form-control" id="nuevoClienteNombre" required>
          </div>
          <div class="mb-2">
            <label for="nuevoClienteIdentificacion" class="form-label">Identificación *</label>
            <input type="text" class="form-control" id="nuevoClienteIdentificacion" required>
          </div>
          <div class="mb-2">
            <label for="nuevoClienteTelefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="nuevoClienteTelefono">
          </div>
          <div class="mb-2">
            <label for="nuevoClienteEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="nuevoClienteEmail">
          </div>
          <div class="mb-2">
            <label for="nuevoClienteDireccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="nuevoClienteDireccion">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarNuevoCliente">Guardar Cliente</button>
      </div>
    </div>
  </div>
</div>
<!-- Submodal para crear producto (dos columnas y subida de imagen) -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-labelledby="modalNuevoProductoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevoProductoLabel">Nuevo Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevoProducto" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-2">
                <label for="nuevoProductoNombre" class="form-label">Nombre *</label>
                <input type="text" class="form-control" id="nuevoProductoNombre" required>
              </div>
              <div class="mb-2">
                <label for="nuevoProductoDescripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="nuevoProductoDescripcion">
              </div>
              <div class="mb-2">
                <label for="nuevoProductoPrecioCompra" class="form-label">Precio de compra</label>
                <input type="number" min="0" step="0.01" class="form-control" id="nuevoProductoPrecioCompra">
              </div>
              <div class="mb-2">
                <label for="nuevoProductoPrecioVenta" class="form-label">Precio de venta</label>
                <input type="number" min="0" step="0.01" class="form-control" id="nuevoProductoPrecioVenta">
              </div>
              <div class="mb-2">
                <label for="nuevoProductoStock" class="form-label">Stock inicial</label>
                <input type="number" min="0" step="1" class="form-control" id="nuevoProductoStock">
              </div>
              <div class="mb-2">
                <label for="nuevoProductoStockMinimo" class="form-label">Stock mínimo</label>
                <input type="number" min="0" step="1" class="form-control" id="nuevoProductoStockMinimo">
              </div>
              <div class="mb-2 form-check">
                <input type="checkbox" class="form-check-input" id="nuevoProductoActivo" checked>
                <label class="form-check-label" for="nuevoProductoActivo">Activo</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-2">
                <label for="nuevoProductoImagen" class="form-label">Imagen</label>
                <input type="file" class="form-control" id="nuevoProductoImagen" accept="image/*">
                <img id="previewProductoImagen" src="#" alt="Previsualización" class="img-fluid mt-2 d-none" style="max-height:120px;" />
              </div>
              <div class="mb-2">
                <label for="nuevoProductoCategoria" class="form-label">Categoría</label>
                <select class="form-select" id="nuevoProductoCategoria">
                  <option value="">Selecciona una categoría</option>
                  <option value="Celulares">Celulares</option>
                  <option value="Accesorios">Accesorios</option>
                  <option value="Tablets">Tablets</option>
                  <option value="Computadores">Computadores</option>
                  <option value="Audio">Audio</option>
                  <option value="Otros">Otros</option>
                </select>
              </div>
              <div class="mb-2">
                <label for="nuevoProductoCodigoBarras" class="form-label">Código de barras</label>
                <input type="text" class="form-control" id="nuevoProductoCodigoBarras">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarNuevoProducto">Guardar Producto</button>
      </div>
    </div>
  </div>
</div>
<script src="assets/js/modules/caja.js"></script>
<?php
require_once 'footer.php';
?>
