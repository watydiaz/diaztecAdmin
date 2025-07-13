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
    <div class="row mb-3" id="rowCardsTotales">
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Efectivo</h5>
                <p class="card-text fs-4" id="totalEfectivo">$0</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Nequi</h5>
                <p class="card-text fs-4" id="totalNequi">$0</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Daviplata</h5>
                <p class="card-text fs-4" id="totalDaviplata">$0</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Tarjeta Crédito</h5>
                <p class="card-text fs-4" id="totalTarjeta">$0</p>
            </div>
        </div>
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
<!-- Botón flotante para nueva venta -->
<button id="btnNuevaVenta" class="btn btn-success rounded-circle shadow-lg" style="position: fixed; bottom: 32px; right: 32px; z-index: 1050; width: 64px; height: 64px; font-size: 2rem; display: flex; align-items: center; justify-content: center;">
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
