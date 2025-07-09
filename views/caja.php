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
    
    // Validar que ambas fechas estén presentes
    if (!fechaInicio || !fechaFin) {
        alert('Por favor selecciona tanto la fecha de inicio como la fecha fin');
        return;
    }
    
    // Validar que la fecha inicio no sea mayor que la fecha fin
    if (fechaInicio > fechaFin) {
        alert('La fecha de inicio no puede ser mayor que la fecha fin');
        return;
    }
    
    // Recargar datos con el nuevo rango de fechas
    cargarPagosCaja(fechaInicio, fechaFin);
}
// Función simplificada - ya no es necesaria porque los totales se calculan en cargarPagosCaja()
function actualizarVentaTotal() {
    // Esta función ya no es necesaria porque el filtro se hace en el servidor
    // Los totales se calculan automáticamente en cargarPagosCaja()
    console.log('actualizarVentaTotal() - Función obsoleta, totales calculados en servidor');
}
document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos del día actual por defecto
    cargarPagosCaja();
    
    // Inicializar fechas por defecto a hoy
    const hoy = getFechaHoy();
    document.getElementById('fechaInicio').value = hoy;
    document.getElementById('fechaFin').value = hoy;
    
    // Event listeners para los botones de fecha
    document.getElementById('btnHoy').onclick = function() {
        const hoy = getFechaHoy();
        document.getElementById('fechaInicio').value = hoy;
        document.getElementById('fechaFin').value = hoy;
        cargarPagosCaja(hoy, hoy);
    };
    
    document.getElementById('btnAyer').onclick = function() {
        const ayer = getFechaAyer();
        document.getElementById('fechaInicio').value = ayer;
        document.getElementById('fechaFin').value = ayer;
        cargarPagosCaja(ayer, ayer);
    };
    
    document.getElementById('btnFiltrarFechas').onclick = filtrarPorFechas;
    document.getElementById('fechaInicio').onchange = filtrarPorFechas;
    document.getElementById('fechaFin').onchange = filtrarPorFechas;
    
    // Inicializar Bootstrap Select
    $('.selectpicker').selectpicker();
});

function cargarPagosCaja(fechaInicio = null, fechaFin = null) {
    // Si no se proporcionan fechas, usar el día actual
    if (!fechaInicio || !fechaFin) {
        const hoy = getFechaHoy();
        fechaInicio = hoy;
        fechaFin = hoy;
    }
    
    // Construir URL con parámetros de fecha
    const url = `/diaztecAdmin/index.php?action=obtenerPagosCaja&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    
    fetch(url)
        .then(r => {
            console.log('Respuesta del servidor:', r);
            return r.text(); // Obtener como texto primero para ver qué está devolviendo
        })
        .then(text => {
            console.log('Respuesta raw del servidor:', text);
            try {
                const data = JSON.parse(text);
                console.log('Datos parseados correctamente:', data);
                
                // Verificar si hay error en la respuesta
                if (data.success === false) {
                    console.error('Error del servidor:', data.error);
                    throw new Error(data.error || 'Error desconocido del servidor');
                }
                
                // Pagos de órdenes
                const tbodyOrdenes = document.getElementById('tbodyCajaPagosOrdenes');
                tbodyOrdenes.innerHTML = '';
                let totalOrdenes = 0;
                
                if (data.pagos_ordenes && Array.isArray(data.pagos_ordenes) && data.pagos_ordenes.length > 0) {
                    data.pagos_ordenes.forEach((pago) => {
                        totalOrdenes += Number(pago.dinero_recibido || 0);
                        tbodyOrdenes.innerHTML += `
                            <tr>
                                <td><strong>${pago.numero_orden || `Orden #${pago.orden_id}`}</strong></td>
                                <td>${pago.cliente_nombre || ''}<br><small class="text-muted">${pago.cliente_identificacion || ''}</small></td>
                                <td>${formatearFecha(pago.fecha_pago || '')}</td>
                                <td><strong>$${Number(pago.dinero_recibido || 0).toLocaleString('es-CO')}</strong></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" onclick="verDetalleOrden(${pago.orden_id})" title="Ver detalle de orden">
                                        <i class="bi bi-eye"></i> Ver
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    tbodyOrdenes.innerHTML = '<tr><td colspan="5" class="text-center">No hay pagos de órdenes registrados en el período seleccionado.</td></tr>';
                }
                document.getElementById('totalCajaOrdenes').textContent = 'Total en caja (órdenes): $' + totalOrdenes.toLocaleString('es-CO');

                // Pagos de productos (ventas) - con información completa
                const tbodyProductos = document.getElementById('tbodyCajaPagosProductos');
                tbodyProductos.innerHTML = '';
                let totalProductos = 0;
                
                if (data.pagos_productos && Array.isArray(data.pagos_productos) && data.pagos_productos.length > 0) {
                    data.pagos_productos.forEach((pago) => {
                        totalProductos += Number(pago.total || 0);
                        tbodyProductos.innerHTML += `
                            <tr>
                                <td><strong>${pago.numero_factura || ''}</strong></td>
                                <td>${pago.cliente_nombre || ''}<br><small class="text-muted">${pago.cliente_identificacion || ''}</small></td>
                                <td>${formatearFecha(pago.fecha_pago || '')}</td>
                                <td><strong>$${Number(pago.total || 0).toLocaleString('es-CO')}</strong></td>
                                <td><span class="badge bg-primary">${(pago.metodo_pago || '').charAt(0).toUpperCase() + (pago.metodo_pago || '').slice(1)}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" onclick="verDetalleFactura(${pago.venta_id})" title="Ver detalle de factura">
                                        <i class="bi bi-eye"></i> Ver
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    tbodyProductos.innerHTML = '<tr><td colspan="6" class="text-center">No hay pagos de productos registrados en el período seleccionado.</td></tr>';
                }
                document.getElementById('totalCajaProductos').textContent = 'Total en caja (productos): $' + totalProductos.toLocaleString('es-CO');
                
                // Actualizar total general
                const totalGeneral = totalOrdenes + totalProductos;
                document.getElementById('ventaTotal').textContent = '$' + totalGeneral.toLocaleString('es-CO');
                
                // Mostrar información del período
                const infoPeriodo = fechaInicio === fechaFin ? 
                    `Mostrando ventas del ${formatearFechaSinHora(fechaInicio)}` : 
                    `Mostrando ventas desde ${formatearFechaSinHora(fechaInicio)} hasta ${formatearFechaSinHora(fechaFin)}`;
                
                // Agregar información del período si no existe
                let infoElement = document.getElementById('infoPeriodo');
                if (!infoElement) {
                    infoElement = document.createElement('div');
                    infoElement.id = 'infoPeriodo';
                    infoElement.className = 'alert alert-info text-center mb-3';
                    document.querySelector('.container-fluid h3').insertAdjacentElement('afterend', infoElement);
                }
                infoElement.innerHTML = `<i class="bi bi-calendar3"></i> ${infoPeriodo}`;
                
                // Mostrar información de debug si está disponible
                if (data.debug) {
                    console.log('Debug info:', data.debug);
                }
                
            } catch (parseError) {
                console.error('Error al parsear JSON:', parseError);
                console.error('Respuesta del servidor que no se pudo parsear:', text);
                throw new Error('El servidor devolvió una respuesta inválida. Respuesta: ' + text.substring(0, 200) + '...');
            }
        })
        .catch(error => {
            console.error('Error al cargar pagos:', error);
            
            // Mostrar mensaje de error más informativo
            const tbodyOrdenes = document.getElementById('tbodyCajaPagosOrdenes');
            const tbodyProductos = document.getElementById('tbodyCajaPagosProductos');
            
            const mensajeError = `<tr><td colspan="5" class="text-center text-danger">
                <i class="bi bi-exclamation-triangle"></i> Error al cargar datos: ${error.message}
                <br><small>Revisa la consola para más detalles</small>
            </td></tr>`;
            
            tbodyOrdenes.innerHTML = mensajeError;
            tbodyProductos.innerHTML = mensajeError.replace('colspan="5"', 'colspan="6"');
            
            // Mostrar alerta
            alert('Error al cargar los datos de caja. Revisa la consola del navegador para más información.');
        });
}

// Función auxiliar para formatear fecha
function formatearFecha(fechaStr) {
    if (!fechaStr) return '';
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-CO', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Función auxiliar para formatear fecha sin hora
function formatearFechaSinHora(fechaStr) {
    if (!fechaStr) return '';
    const fecha = new Date(fechaStr + 'T00:00:00'); // Agregar hora para evitar problemas de zona horaria
    return fecha.toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Función para ver el detalle de una factura
function verDetalleFactura(ventaId) {
    fetch(`/diaztecAdmin/index.php?action=obtenerDetalleFactura&venta_id=${ventaId}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                mostrarModalDetalleFactura(data.venta, data.detalles);
            } else {
                alert('Error al cargar el detalle de la factura: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al cargar detalle de factura:', error);
            alert('Error al cargar el detalle de la factura');
        });
}

// Función para ver el detalle de una orden
function verDetalleOrden(ordenId) {
    fetch(`/diaztecAdmin/index.php?action=obtenerDetalleOrden&orden_id=${ordenId}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                mostrarModalDetalleOrden(data.orden, data.pagos);
            } else {
                alert('Error al cargar el detalle de la orden: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al cargar detalle de orden:', error);
            alert('Error al cargar el detalle de la orden');
        });
}
</script>
<!-- Botón flotante para agregar pago de productos -->
<button id="btnFlotanteAgregarPagoProducto" type="button" class="btn btn-primary rounded-circle shadow"
    style="position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; z-index: 1050; display: flex; align-items: center; justify-content: center; font-size: 2rem;"
    title="Agregar venta de producto">
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
                        <input type="text" class="form-control" id="clienteBusqueda" list="clientesList" placeholder="Buscar cliente por nombre... (si no existe, se creará automáticamente)">
                        <datalist id="clientesList"><!-- Opciones dinámicas JS --></datalist>
                        <input type="hidden" id="clienteSeleccionadoId" name="cliente_id">
                        <small class="form-text text-muted">Escribe el nombre del cliente. Si no existe, aparecerá la opción de crearlo automáticamente.</small>
                    </div>
                    <!-- Buscador de productos -->
                    <div class="mb-3">
                        <label for="productoBusqueda" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="productoBusqueda" list="productosList" placeholder="Buscar producto por nombre... (si no existe, se creará automáticamente)">
                        <datalist id="productosList"><!-- Opciones dinámicas JS --></datalist>
                        <small class="form-text text-muted">Escribe el nombre del producto. Si no existe, aparecerá la opción de crearlo automáticamente.</small>
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

<!-- Modal para crear nuevo cliente -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-labelledby="modalNuevoClienteLabel" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoClienteLabel">Crear Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info alert-sm mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>Después de crear el cliente, regresarás automáticamente al registro de venta con el cliente seleccionado.</small>
                </div>
                <form id="formNuevoCliente">
                    <div class="mb-3">
                        <label for="nuevoClienteNombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nuevoClienteNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoClienteIdentificacion" class="form-label">Identificación *</label>
                        <input type="text" class="form-control" id="nuevoClienteIdentificacion" name="identificacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoClienteTelefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="nuevoClienteTelefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="nuevoClienteEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="nuevoClienteEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="nuevoClienteDireccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="nuevoClienteDireccion" name="direccion" rows="2"></textarea>
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

<!-- Modal para crear nuevo producto -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-labelledby="modalNuevoProductoLabel" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoProductoLabel">Crear Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info alert-sm mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>Después de crear el producto, regresarás automáticamente al registro de venta con el producto listo para agregar.</small>
                </div>
                <form id="formNuevoProducto">
                    <div class="mb-3">
                        <label for="nuevoProductoNombre" class="form-label">Nombre del producto *</label>
                        <input type="text" class="form-control" id="nuevoProductoNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoProductoPrecioCompra" class="form-label">Precio de compra</label>
                        <input type="number" class="form-control" id="nuevoProductoPrecioCompra" name="precio_compra" min="0" step="0.01" value="0">
                    </div>
                    <div class="mb-3">
                        <label for="nuevoProductoPrecioVenta" class="form-label">Precio de venta *</label>
                        <input type="number" class="form-control" id="nuevoProductoPrecioVenta" name="precio_venta" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoProductoStock" class="form-label">Stock inicial</label>
                        <input type="number" class="form-control" id="nuevoProductoStock" name="stock" min="0" value="1">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que todos los elementos existen antes de continuar
    const modalVenta = document.getElementById('modalPagoProducto');
    const inputBusqueda = document.getElementById('clienteBusqueda');
    const datalist = document.getElementById('clientesList');
    const inputClienteId = document.getElementById('clienteSeleccionadoId');
    
    if (!modalVenta || !inputBusqueda || !datalist || !inputClienteId) {
        console.error('Error: No se encontraron todos los elementos necesarios:', {
            modalVenta: !!modalVenta,
            inputBusqueda: !!inputBusqueda,
            datalist: !!datalist,
            inputClienteId: !!inputClienteId
        });
        return;
    }
    
    console.log('Todos los elementos encontrados correctamente');
    
    // --- CLIENTES ---
    let clientes = [];

    // Función helper para obtener información del cliente seleccionado
    function obtenerClienteSeleccionado() {
        const clienteId = inputClienteId.value;
        if (!clienteId) return null;
        
        const cliente = clientes.find(c => c.id == clienteId);
        return cliente;
    }

    // Función helper para validar selección de cliente
    function validarClienteSeleccionado() {
        const clienteId = inputClienteId.value;
        const cliente = obtenerClienteSeleccionado();
        const isValid = !!cliente && !!clienteId;
        
        console.log('Validación de cliente detallada:', {
            clienteIdInput: clienteId,
            clienteBusquedaInput: inputBusqueda.value,
            clienteEncontrado: cliente,
            listaClientesCompleta: clientes,
            esValido: isValid,
            razonInvalida: !clienteId ? 'No hay ID de cliente' : !cliente ? 'Cliente no encontrado en lista' : 'Válido'
        });
        
        return isValid;
    }

    // Función para abrir el modal de venta de forma controlada
    function abrirModalVenta() {
        console.log('Abriendo modal de venta...');
        const modalInstance = new bootstrap.Modal(modalVenta, {
            focus: true,
            backdrop: true,
            keyboard: true
        });
        modalInstance.show();
    }

    // Función para abrir el modal de nuevo cliente con nombre prellenado
    function abrirModalNuevoClienteConNombre(nombreSugerido = '') {
        console.log('Abriendo modal de nuevo cliente con nombre sugerido:', nombreSugerido);
        
        // Cerrar el modal de venta temporalmente
        const modalVentaInstance = bootstrap.Modal.getInstance(document.getElementById('modalPagoProducto'));
        if (modalVentaInstance) {
            modalVentaInstance.hide();
        }
        
        // Esperar a que se cierre completamente antes de abrir el nuevo
        setTimeout(() => {
            const modalNuevoCliente = new bootstrap.Modal(document.getElementById('modalNuevoCliente'), {
                focus: false,
                backdrop: 'static'
            });
            modalNuevoCliente.show();
            
            // Prellenar el campo nombre si se proporciona
            if (nombreSugerido) {
                setTimeout(() => {
                    document.getElementById('nuevoClienteNombre').value = nombreSugerido;
                    // Poner el foco en el campo de identificación para continuar el flujo
                    document.getElementById('nuevoClienteIdentificacion').focus();
                }, 100);
            }
        }, 300);
    }

    // Event listener para el botón flotante
    document.getElementById('btnFlotanteAgregarPagoProducto').addEventListener('click', function() {
        abrirModalVenta();
    });

    // Manejar guardado de nuevo cliente
    document.getElementById('btnGuardarNuevoCliente').addEventListener('click', function() {
        const form = document.getElementById('formNuevoCliente');
        const formData = new FormData(form);
        const btn = this;
        
        // Validaciones básicas
        const nombre = formData.get('nombre').trim();
        const identificacion = formData.get('identificacion').trim();
        
        if (!nombre || !identificacion) {
            alert('Por favor completa los campos obligatorios (Nombre e Identificación)');
            return;
        }
        
        // Deshabilitar botón mientras se procesa
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Guardando...';
        
        // Enviar datos al servidor
        fetch('/diaztecAdmin/index.php?action=agregarCliente', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta completa del servidor:', data);
            console.log('cliente_id recibido:', data.cliente_id, 'tipo:', typeof data.cliente_id);
            
            if (data.success) {
                // Cliente creado exitosamente
                const nuevoCliente = {
                    id: data.cliente_id,
                    nombre: nombre,
                    identificacion: identificacion
                };
                
                console.log('Objeto nuevoCliente creado:', nuevoCliente);
                
                // Agregar a la lista de clientes
                clientes.push(nuevoCliente);
                
                // Actualizar el datalist inmediatamente con el nuevo cliente
                datalist.innerHTML = `<option value="${nombre} (${identificacion})" data-id="${data.cliente_id}">${nombre} (${identificacion})</option>`;
                
                // Seleccionar automáticamente el nuevo cliente
                inputBusqueda.value = `${nombre} (${identificacion})`;
                inputClienteId.value = data.cliente_id;
                
                console.log('Cliente creado y seleccionado:', {
                    nombre: nombre,
                    identificacion: identificacion,
                    id: data.cliente_id,
                    inputValue: inputBusqueda.value,
                    hiddenValue: inputClienteId.value
                });
                
                // Cerrar modal
                const modalNuevoCliente = bootstrap.Modal.getInstance(document.getElementById('modalNuevoCliente'));
                modalNuevoCliente.hide();
                
                // Limpiar formulario
                form.reset();
                
                // Guardar datos del cliente seleccionado para mantenerlos al reabrir el modal
                sessionStorage.setItem('clienteSeleccionado', JSON.stringify({
                    id: data.cliente_id,
                    nombre: nombre,
                    identificacion: identificacion,
                    displayText: `${nombre} (${identificacion})`
                }));
                
                // Reabrir el modal de venta después de crear el cliente
                setTimeout(() => {
                    abrirModalVenta();
                }, 300);
                
                alert('Cliente creado exitosamente');
            } else {
                alert('Error al crear cliente: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        })
        .finally(() => {
            // Restaurar botón
            btn.disabled = false;
            btn.innerHTML = 'Guardar Cliente';
        });
    });

    let modalVentaInicializado = false; // Flag para inicialización única
    let modalAbierto = false; // Flag para evitar múltiples aperturas simultáneas

    if (modalVenta && !modalVentaInicializado) {
        modalVentaInicializado = true;
        
        modalVenta.addEventListener('show.bs.modal', function(e) {
            if (modalAbierto) {
                console.log('Modal ya está abierto, evitando duplicación...');
                return;
            }
            modalAbierto = true;
            
            console.log('Modal de venta abriéndose...');
            
            // Verificar si hay un cliente seleccionado guardado
            const clienteGuardado = sessionStorage.getItem('clienteSeleccionado');
            if (clienteGuardado) {
                const cliente = JSON.parse(clienteGuardado);
                console.log('Cliente encontrado en sessionStorage:', cliente);
                
                // Asegurar que el cliente se establezca correctamente
                inputBusqueda.value = cliente.displayText;
                inputClienteId.value = cliente.id;
                
                // Agregar el cliente a la lista si no está
                if (!clientes.find(c => c.id === cliente.id)) {
                    clientes.push({
                        id: cliente.id,
                        nombre: cliente.nombre,
                        identificacion: cliente.identificacion
                    });
                }
                
                // Actualizar el datalist con el cliente seleccionado
                datalist.innerHTML = `<option value="${cliente.displayText}" data-id="${cliente.id}">${cliente.displayText}</option>`;
                
                console.log('Cliente restaurado desde sessionStorage:', {
                    displayText: cliente.displayText,
                    id: cliente.id,
                    inputValue: inputBusqueda.value,
                    hiddenValue: inputClienteId.value
                });
                
                // Limpiar sessionStorage
                sessionStorage.removeItem('clienteSeleccionado');
            } else {
                console.log('No hay cliente guardado, limpiando campos...');
                // Solo limpiar si no hay cliente guardado y no hay valores actuales
                if (!inputBusqueda.value && !inputClienteId.value) {
                    datalist.innerHTML = '';
                    inputBusqueda.value = '';
                    inputClienteId.value = '';
                }
            }
            
            // --- PRODUCTOS ---
            // Limpiar productos y tabla al abrir el modal
            productos = [];
            productosSeleccionados = [];
            datalistProductos.innerHTML = '';
            renderTablaProductos();
            inputProducto.value = '';
            
            // Verificar nuevamente el cliente después de limpiar productos
            if (inputClienteId.value) {
                console.log('Cliente mantenido después de limpiar productos:', {
                    inputValue: inputBusqueda.value,
                    hiddenValue: inputClienteId.value
                });
            }
        }, { once: false });
        
        modalVenta.addEventListener('shown.bs.modal', function(e) {
            // Solo ejecutar una vez por apertura del modal
            console.log('Modal mostrado completamente. Estado del cliente:', {
                inputValue: inputBusqueda.value,
                hiddenValue: inputClienteId.value,
                datalistOptions: datalist.innerHTML.length > 0 ? 'Tiene opciones' : 'Sin opciones'
            });
            
            // Si hay un valor en el input pero no en el campo oculto, intentar recuperarlo
            if (inputBusqueda.value && !inputClienteId.value) {
                const option = datalist.querySelector(`option[value="${inputBusqueda.value}"]`);
                if (option) {
                    const clienteId = option.getAttribute('data-id');
                    if (clienteId) {
                        inputClienteId.value = clienteId;
                        console.log('Cliente ID recuperado del datalist:', clienteId);
                    }
                }
            }
        }, { once: false });
        
        modalVenta.addEventListener('hidden.bs.modal', function(e) {
            console.log('Modal de venta cerrado');
            modalAbierto = false; // Reset del flag al cerrar
        }, { once: false });
    }

    // Limpiar modal de nuevo cliente al cerrarlo
    document.getElementById('modalNuevoCliente').addEventListener('hidden.bs.modal', function() {
        console.log('Modal de nuevo cliente cerrado');
        document.getElementById('formNuevoCliente').reset();
        
        // Verificar si se guardó un cliente en sessionStorage
        const clienteGuardado = sessionStorage.getItem('clienteSeleccionado');
        if (!clienteGuardado) {
            // Solo reabrir si se canceló la creación (no hay cliente guardado)
            console.log('Cliente no guardado, reabriendo modal de venta...');
            setTimeout(() => {
                abrirModalVenta();
            }, 100);
        } else {
            console.log('Cliente guardado, el modal se reabrirá automáticamente');
        }
    });

    let timeoutBusqueda; // Para el debounce de búsqueda

    inputBusqueda.addEventListener('input', function() {
        const query = this.value.trim();
        
        console.log('Input event disparado con query:', query);
        
        // Limpiar timeout anterior
        clearTimeout(timeoutBusqueda);
        
        // Verificar primero si se seleccionó una opción del datalist
        const opcionSeleccionada = datalist.querySelector(`option[value="${query}"]`);
        if (opcionSeleccionada) {
            const clienteId = opcionSeleccionada.getAttribute('data-id');
            if (clienteId) {
                console.log('Opción seleccionada del datalist detectada:', { query, clienteId });
                inputClienteId.value = clienteId;
                
                // Agregar el cliente a la lista local si no está
                if (!clientes.find(c => c.id == clienteId)) {
                    const match = query.match(/^(.+) \((.+)\)$/);
                    if (match) {
                        const nuevoCliente = {
                            id: clienteId,
                            nombre: match[1],
                            identificacion: match[2]
                        };
                        clientes.push(nuevoCliente);
                        console.log('Cliente agregado a lista local desde datalist:', nuevoCliente);
                    }
                }
                
                // No continuar con la búsqueda, ya se seleccionó un cliente
                return;
            }
        }
        
        // Si hay un cliente ya seleccionado, verificar si el query actual coincide
        if (inputClienteId.value) {
            const clienteActual = clientes.find(c => c.id == inputClienteId.value);
            if (clienteActual) {
                const formatoCompleto = `${clienteActual.nombre} (${clienteActual.identificacion})`;
                const nombreSolo = clienteActual.nombre;
                
                console.log('Verificando coincidencia:', {
                    query: query,
                    formatoCompleto: formatoCompleto,
                    nombreSolo: nombreSolo,
                    clienteActual: clienteActual
                });
                
                // Si el query actual es una subcadena del nombre o coincide exactamente, mantener la selección
                if (formatoCompleto === query || nombreSolo === query || 
                    (nombreSolo.toLowerCase().includes(query.toLowerCase()) && query.length > 2)) {
                    console.log('Manteniendo selección de cliente durante input:', clienteActual);
                    // No limpiar la selección, pero continuar con la búsqueda para mostrar opciones
                } else if (query.length > 1) {
                    // Solo limpiar si realmente está escribiendo algo diferente
                    console.log('Query no coincide con cliente actual, limpiando selección');
                    inputClienteId.value = '';
                }
            }
        }
        
        if (query.length > 1) {
            // Agregar un pequeño delay para evitar muchas peticiones
            timeoutBusqueda = setTimeout(() => {
                console.log('Buscando clientes para:', query);
                fetch(`/diaztecAdmin/index.php?action=buscarCliente&query=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(data => {
                        console.log('Respuesta de búsqueda de clientes:', data);
                        clientes = data.clientes || [];
                        datalist.innerHTML = '';
                        
                        if (clientes.length > 0) {
                            // Si hay resultados, mostrarlos
                            clientes.forEach(c => {
                                datalist.innerHTML += `<option value="${c.nombre} (${c.identificacion})" data-id="${c.id}">${c.nombre} (${c.identificacion})</option>`;
                            });
                            console.log(`Encontrados ${clientes.length} clientes para: "${query}"`);
                        } else {
                            // Si no hay resultados, sugerir crear cliente
                            console.log(`No se encontraron clientes para: "${query}". Mostrando opción de crear cliente.`);
                            datalist.innerHTML = `<option value="➕ Crear nuevo cliente: ${query}" data-action="create">➕ Crear nuevo cliente: "${query}"</option>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error buscando clientes:', error);
                        datalist.innerHTML = `<option value="➕ Crear nuevo cliente: ${query}" data-action="create">➕ Crear nuevo cliente: "${query}"</option>`;
                    });
            }, 300); // Delay de 300ms
        } else {
            datalist.innerHTML = '';
        }
        // No limpiar automáticamente el cliente seleccionado aquí
    });
    inputBusqueda.addEventListener('change', function() {
        const valor = this.value.trim();
        console.log('Cliente input changed:', valor);
        console.log('Lista de clientes disponible:', clientes);
        console.log('Cliente ID actual:', inputClienteId.value);
        
        // Si el campo está vacío, limpiar el ID del cliente
        if (!valor) {
            inputClienteId.value = '';
            console.log('Campo vacío, cliente ID limpiado');
            return;
        }
        
        // Verificar si es la opción de crear nuevo cliente
        if (valor.startsWith('➕ Crear nuevo cliente:')) {
            const nombreSugerido = valor.replace('➕ Crear nuevo cliente: ', '');
            console.log('Detectada opción de crear cliente para:', nombreSugerido);
            
            // Limpiar el input
            this.value = '';
            inputClienteId.value = '';
            
            // Abrir modal de nuevo cliente y prellenar el nombre
            abrirModalNuevoClienteConNombre(nombreSugerido);
            return;
        }
        
        // Si ya hay un cliente seleccionado, verificar si el valor actual coincide
        if (inputClienteId.value) {
            const clienteActual = clientes.find(c => c.id == inputClienteId.value);
            if (clienteActual) {
                const formatoCompleto = `${clienteActual.nombre} (${clienteActual.identificacion})`;
                const nombreSolo = clienteActual.nombre;
                
                console.log('Verificando cliente actual:', {
                    clienteActual: clienteActual,
                    formatoCompleto: formatoCompleto,
                    nombreSolo: nombreSolo,
                    valorInput: valor
                });
                
                // Si el valor actual coincide con el formato completo o el nombre solo del cliente seleccionado, mantener la selección
                if (valor === formatoCompleto || valor === nombreSolo) {
                    console.log('Manteniendo cliente seleccionado:', clienteActual);
                    return; // No hacer nada más, mantener la selección actual
                }
            }
        }
        
        // Buscar en la lista de clientes cargados (tanto por formato completo como por nombre solo)
        let cliente = clientes.find(c => `${c.nombre} (${c.identificacion})` === valor);
        if (!cliente) {
            cliente = clientes.find(c => c.nombre === valor);
        }
        
        console.log('Resultado de búsqueda en lista local:', cliente);
        
        if (cliente) {
            inputClienteId.value = cliente.id;
            console.log('Cliente encontrado en lista local:', cliente);
        } else {
            // Si no se encuentra en la lista local, buscar por ID en el datalist
            const option = datalist.querySelector(`option[value="${valor}"]`);
            console.log('Buscando en datalist para valor:', valor, 'Encontrado:', option);
            
            if (option) {
                const clienteId = option.getAttribute('data-id');
                if (clienteId) {
                    inputClienteId.value = clienteId;
                    console.log('Cliente encontrado en datalist:', { valor, clienteId });
                    
                    // Agregar el cliente a la lista local si no está
                    if (!clientes.find(c => c.id == clienteId)) {
                        // Extraer nombre e identificación del valor
                        const match = valor.match(/^(.+) \((.+)\)$/);
                        if (match) {
                            const nuevoCliente = {
                                id: clienteId,
                                nombre: match[1],
                                identificacion: match[2]
                            };
                            clientes.push(nuevoCliente);
                            console.log('Cliente agregado a lista local:', nuevoCliente);
                        }
                    }
                } else {
                    console.log('Opción encontrada pero sin data-id:', option);
                }
            } else {
                // No se encontró coincidencia, limpiar selección
                inputClienteId.value = '';
                console.log('Cliente no encontrado para valor:', valor);
            }
        }
        
        console.log('Estado final después del change:', {
            inputValue: inputBusqueda.value,
            hiddenValue: inputClienteId.value
        });
    });
    
    // Agregar listener adicional para sincronización en tiempo real
    inputBusqueda.addEventListener('blur', function() {
        // Trigger del evento change para asegurar sincronización
        this.dispatchEvent(new Event('change'));
    });

    // --- PRODUCTOS ---
    let productos = [];
    let productosSeleccionados = [];
    const inputProducto = document.getElementById('productoBusqueda');
    const datalistProductos = document.getElementById('productosList');
    const tablaProductos = document.getElementById('tablaProductosSeleccionados').querySelector('tbody');
    let timeoutBusquedaProducto;
    let ignoreInputEvent = false;

    // Función para abrir el modal de nuevo producto con nombre prellenado
    function abrirModalNuevoProductoConNombre(nombreSugerido = '') {
        console.log('Abriendo modal de nuevo producto con nombre sugerido:', nombreSugerido);
        
        // Cerrar el modal de venta temporalmente
        const modalVentaInstance = bootstrap.Modal.getInstance(document.getElementById('modalPagoProducto'));
        if (modalVentaInstance) {
            modalVentaInstance.hide();
        }
        
        // Esperar a que se cierre completamente antes de abrir el nuevo
        setTimeout(() => {
            const modalNuevoProducto = new bootstrap.Modal(document.getElementById('modalNuevoProducto'), {
                focus: false,
                backdrop: 'static'
            });
            modalNuevoProducto.show();
            
            // Prellenar el campo nombre si se proporciona
            if (nombreSugerido) {
                setTimeout(() => {
                    document.getElementById('nuevoProductoNombre').value = nombreSugerido;
                    // Poner el foco en el campo de precio de venta para continuar el flujo
                    document.getElementById('nuevoProductoPrecioVenta').focus();
                }, 100);
            }
        }, 300);
    }

    // Búsqueda dinámica de productos con debounce
    inputProducto.addEventListener('input', function(e) {
        if (ignoreInputEvent) return; // Evita doble ejecución
        const query = this.value.trim();
        
        // Limpiar timeout anterior
        clearTimeout(timeoutBusquedaProducto);
        
        if (query.length > 1) {
            // Agregar un pequeño delay para evitar muchas peticiones
            timeoutBusquedaProducto = setTimeout(() => {
                fetch(`/diaztecAdmin/index.php?action=buscarProducto&query=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(data => {
                        productos = data.productos || [];
                        datalistProductos.innerHTML = '';
                        
                        if (productos.length > 0) {
                            // Si hay resultados, mostrarlos
                            productos.forEach(p => {
                                datalistProductos.innerHTML += `<option value="${p.nombre}" data-id="${p.id}" data-precio="${p.precio_venta || p.precio}">${p.nombre} ($${parseFloat(p.precio_venta || p.precio).toLocaleString('es-CO')} - Stock: ${p.stock || 0})</option>`;
                            });
                            console.log(`Encontrados ${productos.length} productos para: "${query}"`);
                        } else {
                            // Si no hay resultados, sugerir crear producto
                            console.log(`No se encontraron productos para: "${query}". Mostrando opción de crear producto.`);
                            datalistProductos.innerHTML = `<option value="➕ Crear nuevo producto: ${query}" data-action="create">➕ Crear nuevo producto: "${query}"</option>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error buscando productos:', error);
                        datalistProductos.innerHTML = `<option value="➕ Crear nuevo producto: ${query}" data-action="create">➕ Crear nuevo producto: "${query}"</option>`;
                    });
            }, 300); // Delay de 300ms
        } else {
            datalistProductos.innerHTML = '';
        }
    });

    inputProducto.addEventListener('change', function() {
        const valor = this.value.trim();
        console.log('Producto input changed:', valor);
        
        // Si el input está vacío, no hacer nada
        if (!valor) {
            return;
        }
        
        // Verificar si es la opción de crear nuevo producto
        if (valor.startsWith('➕ Crear nuevo producto:')) {
            const nombreSugerido = valor.replace('➕ Crear nuevo producto: ', '');
            console.log('Detectada opción de crear producto para:', nombreSugerido);
            
            // Limpiar el input
            this.value = '';
            
            // Abrir modal de nuevo producto y prellenar el nombre
            abrirModalNuevoProductoConNombre(nombreSugerido);
            return;
        }
        
        // Buscar en la lista de productos cargados
        const prod = productos.find(p => p.nombre === valor);
        if (prod) {
            console.log('Producto encontrado en lista local:', prod);
            agregarProductoSeleccionado({
                id: prod.id,
                nombre: prod.nombre,
                precio_venta: prod.precio_venta || prod.precio,
                precio: prod.precio_venta || prod.precio
            });
            return;
        }
        
        // Buscar por ID en el datalist
        const option = datalistProductos.querySelector(`option[value="${valor}"]`);
        if (option) {
            const productoId = option.getAttribute('data-id');
            const precio = option.getAttribute('data-precio');
            console.log('Producto encontrado en datalist:', { id: productoId, nombre: valor, precio: precio });
            if (productoId && precio) {
                agregarProductoSeleccionado({
                    id: productoId,
                    nombre: valor,
                    precio_venta: precio,
                    precio: precio
                });
                return;
            }
        }
        
        console.log('Producto no encontrado:', valor);
        
        // Si no se encuentra el producto y no es una opción de crear, limpiar el input
        console.log('Producto no encontrado:', valor);
        this.value = '';
    });

    // Manejar guardado de nuevo producto
    document.getElementById('btnGuardarNuevoProducto').addEventListener('click', function() {
        const form = document.getElementById('formNuevoProducto');
        const formData = new FormData(form);
        const btn = this;
        
        // Validaciones básicas
        const nombre = formData.get('nombre').trim();
        const precio_venta = parseFloat(formData.get('precio_venta')) || 0;
        
        if (!nombre) {
            alert('Por favor completa el nombre del producto');
            return;
        }
        
        if (precio_venta <= 0) {
            alert('Por favor ingresa un precio de venta válido');
            return;
        }
        
        // Deshabilitar botón mientras se procesa
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Guardando...';
        
        // Enviar datos al servidor
        fetch('/diaztecAdmin/index.php?action=agregarProducto', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta completa del servidor:', data);
            
            if (data.success) {
                // Producto creado exitosamente
                const nuevoProducto = data.producto;
                
                console.log('Objeto nuevoProducto creado:', nuevoProducto);
                
                // Agregar a la lista de productos
                productos.push(nuevoProducto);
                
                // Cerrar modal primero
                const modalNuevoProducto = bootstrap.Modal.getInstance(document.getElementById('modalNuevoProducto'));
                modalNuevoProducto.hide();
                
                // Limpiar formulario
                form.reset();
                
                // Agregar automáticamente el producto a la venta después de cerrar el modal
                setTimeout(() => {
                    agregarProductoSeleccionado({
                        id: nuevoProducto.id,
                        nombre: nuevoProducto.nombre,
                        precio_venta: parseFloat(nuevoProducto.precio_venta),
                        precio: parseFloat(nuevoProducto.precio_venta)
                    });
                    
                    console.log('Producto creado y agregado a la venta:', {
                        nombre: nuevoProducto.nombre,
                        id: nuevoProducto.id,
                        precio_venta: nuevoProducto.precio_venta
                    });
                    
                    // Reabrir el modal de venta después de agregar el producto
                    abrirModalVenta();
                    
                    alert('Producto creado y agregado a la venta exitosamente');
                }, 200);
                
            } else {
                alert('Error al crear producto: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        })
        .finally(() => {
            // Restaurar botón
            btn.disabled = false;
            btn.innerHTML = 'Guardar Producto';
        });
    });

    // Limpiar modal de nuevo producto al cerrarlo
    document.getElementById('modalNuevoProducto').addEventListener('hidden.bs.modal', function() {
        console.log('Modal de nuevo producto cerrado');
        document.getElementById('formNuevoProducto').reset();
        
        // Solo reabrir el modal de venta si no se guardó un producto
        // Si se guardó un producto, ya se manejó en el success del guardado
        const modalVentaAbierto = document.getElementById('modalPagoProducto').classList.contains('show');
        if (!modalVentaAbierto) {
            setTimeout(() => {
                abrirModalVenta();
            }, 100);
        }
    });

    function agregarProductoSeleccionado(prod) {
        console.log('Agregando producto:', prod);
        
        // Obtener el precio correcto, manejando diferentes propiedades posibles
        let precio = 0;
        if (prod.precio_venta) {
            precio = parseFloat(prod.precio_venta);
        } else if (prod.precio) {
            precio = parseFloat(prod.precio);
        } else {
            console.warn('Producto sin precio válido:', prod);
            alert('Error: El producto no tiene un precio válido');
            return;
        }
        
        if (isNaN(precio) || precio <= 0) {
            console.warn('Precio inválido:', precio, 'para producto:', prod);
            alert('Error: El precio del producto no es válido');
            return;
        }
        
        const existente = productosSeleccionados.find(p => p.id == prod.id);
        if (existente) {
            existente.cantidad++;
            console.log('Cantidad incrementada para:', existente.nombre, 'Nueva cantidad:', existente.cantidad);
        } else {
            const nuevoProducto = {
                id: prod.id,
                nombre: prod.nombre,
                precio: precio,
                cantidad: 1
            };
            productosSeleccionados.push(nuevoProducto);
            console.log('Producto agregado a la lista:', nuevoProducto);
        }
        renderTablaProductos();
        // Evita que el input vuelva a disparar el evento input al limpiar
        ignoreInputEvent = true;
        inputProducto.value = '';
        setTimeout(() => { ignoreInputEvent = false; }, 100);
    }

    function renderTablaProductos() {
        const fragment = document.createDocumentFragment();
        let total = 0;
        
        console.log('Renderizando tabla de productos:', productosSeleccionados);
        
        productosSeleccionados.forEach((p, idx) => {
            // Validar que el precio sea un número válido
            const precio = parseFloat(p.precio);
            const cantidad = parseInt(p.cantidad);
            
            if (isNaN(precio) || isNaN(cantidad)) {
                console.error('Producto con datos inválidos:', p);
                return;
            }
            
            const subtotal = precio * cantidad;
            total += subtotal;
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${p.nombre}</td>
                <td>$${precio.toLocaleString('es-CO')}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger rounded-circle btn-restar d-inline-flex align-items-center justify-content-center" style="width:22px;height:22px;padding:0;font-size:0.88rem;" data-idx="${idx}" title="Restar">-</button>
                    <span class="mx-1" style="min-width:20px;display:inline-block;font-size:0.95rem;">${cantidad}</span>
                    <button type="button" class="btn btn-sm btn-success rounded-circle btn-sumar d-inline-flex align-items-center justify-content-center" style="width:22px;height:22px;padding:0;font-size:0.88rem;" data-idx="${idx}" title="Sumar">+</button>
                </td>
                <td>$${subtotal.toLocaleString('es-CO')}</td>
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
        
        console.log('Total calculado:', total);
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
    
    // Evento para guardar venta
    document.getElementById('btnGuardarVenta').addEventListener('click', function() {
        const btn = this;
        
        // Debug: mostrar estado actual
        console.log('Debug - Estado al intentar guardar venta:', {
            clienteInputValue: inputBusqueda.value,
            clienteIdValue: inputClienteId.value,
            productosSeleccionados: productosSeleccionados.length
        });
        
        // Validaciones usando función helper
        if (!validarClienteSeleccionado()) {
            const cliente = obtenerClienteSeleccionado();
            if (!cliente) {
                alert('Por favor selecciona un cliente válido. Cliente actual: ' + (inputBusqueda.value || 'ninguno'));
            }
            inputBusqueda.focus(); // Enfocar el campo de cliente para que el usuario pueda corregir
            return;
        }
        
        if (productosSeleccionados.length === 0) {
            alert('Por favor agrega al menos un producto');
            return;
        }
        
        const total = productosSeleccionados.reduce((s, p) => s + p.precio * p.cantidad, 0);
        const dineroRecibido = parseFloat(document.getElementById('inputConCuantoPagan').value) || 0;
        const tipoPago = document.getElementById('inputTipoPago').value;
        
        if (dineroRecibido <= 0) {
            alert('Por favor ingresa el dinero recibido');
            return;
        }
        
        if (!tipoPago) {
            alert('Por favor selecciona el tipo de pago');
            return;
        }
        
        if (dineroRecibido < total) {
            alert('El dinero recibido no puede ser menor al total');
            return;
        }
        
        const cambio = dineroRecibido - total;
        
        // Deshabilitar botón mientras se procesa
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Procesando...';
        
        // Preparar datos para enviar
        const clienteSeleccionado = obtenerClienteSeleccionado();
        const formData = new FormData();
        formData.append('cliente_id', inputClienteId.value);
        formData.append('productos', JSON.stringify(productosSeleccionados));
        formData.append('total', total);
        formData.append('metodo_pago', tipoPago.toLowerCase());
        formData.append('dinero_recibido', dineroRecibido);
        formData.append('cambio', cambio);
        
        console.log('Enviando datos al servidor:', {
            cliente_id: inputClienteId.value,
            cliente_info: clienteSeleccionado,
            productos_count: productosSeleccionados.length,
            total: total,
            metodo_pago: tipoPago.toLowerCase()
        });
        
        // Enviar al servidor
        fetch('/diaztecAdmin/index.php?action=registrarVentaCompleta', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data);
            
            if (data.success) {
                alert('¡Venta registrada exitosamente!\n\nFactura: ' + data.numero_factura + '\nTotal: $' + total.toLocaleString('es-CO') + '\nCambio: $' + cambio.toLocaleString('es-CO'));
                
                // Cerrar modal
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalPagoProducto'));
                if (modalInstance) {
                    modalInstance.hide();
                }
                
                // Limpiar formulario
                inputBusqueda.value = '';
                inputClienteId.value = '';
                productosSeleccionados = [];
                renderTablaProductos();
                document.getElementById('inputConCuantoPagan').value = '';
                document.getElementById('inputCambioVuelto').value = '';
                document.getElementById('inputTipoPago').value = '';
                
                // Recargar datos de caja manteniendo el filtro actual
                const fechaInicio = document.getElementById('fechaInicio').value;
                const fechaFin = document.getElementById('fechaFin').value;
                cargarPagosCaja(fechaInicio, fechaFin);
                
            } else {
                alert('Error al registrar la venta: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        })
        .finally(() => {
            // Restaurar botón
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-save me-2"></i>Guardar Venta';
        });
    });
});
</script>

<!-- Modal para mostrar detalle de factura -->
<div class="modal fade" id="modalDetalleFactura" tabindex="-1" aria-labelledby="modalDetalleFacturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleFacturaLabel">Detalle de Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="contenidoDetalleFactura">
                    <!-- Contenido dinámico -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="enviarFacturaWhatsApp()">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </button>
                <button type="button" class="btn btn-info" onclick="enviarFacturaEmail()">
                    <i class="bi bi-envelope"></i> Email
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirFactura()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar detalle de orden -->
<div class="modal fade" id="modalDetalleOrden" tabindex="-1" aria-labelledby="modalDetalleOrdenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleOrdenLabel">Detalle de Orden de Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="contenidoDetalleOrden">
                    <!-- Contenido dinámico -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="enviarOrdenWhatsApp()">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </button>
                <button type="button" class="btn btn-info" onclick="enviarOrdenEmail()">
                    <i class="bi bi-envelope"></i> Email
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirOrden()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para mostrar el modal de detalle de factura
function mostrarModalDetalleFactura(venta, detalles) {
    const contenido = document.getElementById('contenidoDetalleFactura');
    
    let detallesHtml = '';
    let totalFactura = 0;
    
    detalles.forEach(detalle => {
        totalFactura += parseFloat(detalle.subtotal);
        detallesHtml += `
            <tr>
                <td>${detalle.producto_nombre}</td>
                <td class="text-center">${detalle.cantidad}</td>
                <td class="text-end">$${Number(detalle.precio_unitario).toLocaleString('es-CO')}</td>
                <td class="text-end"><strong>$${Number(detalle.subtotal).toLocaleString('es-CO')}</strong></td>
            </tr>
        `;
    });
    
    contenido.innerHTML = `
        <!-- Encabezado con logo y datos de empresa -->
        <div class="row mb-4">
            <div class="col-md-3 text-center">
                <img src="https://diaztecnologia.com/img/logo.png" alt="Logo Empresa" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-md-9">
                <h4 class="fw-bold text-primary mb-1">DIAZTEC - Reparaciones y Tecnología</h4>
                <p class="mb-1"><strong>NIT:</strong> 1073679337</p>
                <p class="mb-1"><strong>Dirección:</strong> Transversal 12a 41b 31, Barrio Ocales Soacha</p>
                <p class="mb-1"><strong>Celular:</strong> 3202975605 - 3203200992</p>
                <p class="mb-0"><strong>Email:</strong> karol.jesusdiaz@gmail.com</p>
            </div>
        </div>
        
        <hr class="my-3">
        
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold text-primary">Información de la Venta</h6>
                <p><strong>Factura:</strong> ${venta.numero_factura}</p>
                <p><strong>Fecha:</strong> ${formatearFecha(venta.fecha_venta)}</p>
                <p><strong>Método de Pago:</strong> <span class="badge bg-primary">${venta.metodo_pago.charAt(0).toUpperCase() + venta.metodo_pago.slice(1)}</span></p>
                <p><strong>Vendedor:</strong> ${venta.usuario_nombre || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-success">Información del Cliente</h6>
                <p><strong>Nombre:</strong> ${venta.cliente_nombre}</p>
                <p><strong>Identificación:</strong> ${venta.cliente_identificacion}</p>
                <p><strong>Teléfono:</strong> ${venta.cliente_telefono || 'N/A'}</p>
                <p><strong>Email:</strong> ${venta.cliente_email || 'N/A'}</p>
            </div>
        </div>
        
        <hr class="my-3">
        
        <h6 class="fw-bold text-info">Productos Vendidos</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Precio Unit.</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${detallesHtml}
                </tbody>
                <tfoot>
                    <tr class="table-success">
                        <th colspan="3" class="text-end">TOTAL:</th>
                        <th class="text-end">$${Number(venta.total).toLocaleString('es-CO')}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="mt-3 text-center">
            <p class="text-muted small">¡Gracias por su compra! - DIAZTEC</p>
        </div>
    `;
    
    // Guardar datos para funciones de envío
    window.currentFactura = { venta, detalles };
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleFactura'));
    modal.show();
}

// Función para mostrar el modal de detalle de orden
function mostrarModalDetalleOrden(orden, pagos) {
    const contenido = document.getElementById('contenidoDetalleOrden');
    
    let pagosHtml = '';
    let totalPagado = 0;
    
    pagos.forEach(pago => {
        totalPagado += parseFloat(pago.dinero_recibido);
        pagosHtml += `
            <tr>
                <td>${formatearFecha(pago.fecha_pago)}</td>
                <td>$${Number(pago.dinero_recibido).toLocaleString('es-CO')}</td>
                <td><span class="badge bg-info">${pago.metodo_pago || 'N/A'}</span></td>
            </tr>
        `;
    });
    
    const saldoPendiente = parseFloat(orden.costo_total) - totalPagado;
    const estadoBadge = orden.estado === 'entregado' ? 'bg-success' : 
                       orden.estado === 'en_proceso' ? 'bg-warning' : 'bg-secondary';
    
    contenido.innerHTML = `
        <!-- Encabezado con logo y datos de empresa -->
        <div class="row mb-4">
            <div class="col-md-3 text-center">
                <img src="https://diaztecnologia.com/img/logo.png" alt="Logo Empresa" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-md-9">
                <h4 class="fw-bold text-primary mb-1">DIAZTEC - Reparaciones y Tecnología</h4>
                <p class="mb-1"><strong>NIT:</strong> 1073679337</p>
                <p class="mb-1"><strong>Dirección:</strong> Transversal 12a 41b 31, Barrio Ocales Soacha</p>
                <p class="mb-1"><strong>Celular:</strong> 3202975605 - 3203200992</p>
                <p class="mb-0"><strong>Email:</strong> karol.jesusdiaz@gmail.com</p>
            </div>
        </div>
        
        <hr class="my-3">
        
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold text-primary">Información de la Orden</h6>
                <p><strong>Orden:</strong> ${orden.numero_orden}</p>
                <p><strong>Fecha Ingreso:</strong> ${formatearFecha(orden.fecha_ingreso)}</p>
                <p><strong>Fecha Entrega:</strong> ${orden.fecha_entrega ? formatearFecha(orden.fecha_entrega) : 'Pendiente'}</p>
                <p><strong>Estado:</strong> <span class="badge ${estadoBadge}">${orden.estado.charAt(0).toUpperCase() + orden.estado.slice(1)}</span></p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-success">Información del Cliente</h6>
                <p><strong>Nombre:</strong> ${orden.cliente_nombre}</p>
                <p><strong>Identificación:</strong> ${orden.cliente_identificacion}</p>
                <p><strong>Teléfono:</strong> ${orden.cliente_telefono || 'N/A'}</p>
                <p><strong>Email:</strong> ${orden.cliente_email || 'N/A'}</p>
                <p><strong>Dirección:</strong> ${orden.cliente_direccion || 'N/A'}</p>
            </div>
        </div>
        
        <hr class="my-3">
        
        <div class="row">
            <div class="col-md-12">
                <h6 class="fw-bold text-info">Equipo a Reparar</h6>
                <p><strong>Equipo:</strong> ${orden.equipo_nombre || 'N/A'}</p>
                <p><strong>Marca:</strong> ${orden.equipo_marca || 'N/A'}</p>
                <p><strong>Modelo:</strong> ${orden.equipo_modelo || 'N/A'}</p>
                <p><strong>Serial:</strong> ${orden.equipo_serial || 'N/A'}</p>
            </div>
        </div>
        
        <hr class="my-3">
        
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold text-warning">Problema Reportado</h6>
                <p>${orden.descripcion_problema || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-success">Solución Aplicada</h6>
                <p>${orden.solucion || 'Pendiente'}</p>
            </div>
        </div>
        
        <hr class="my-3">
        
        <h6 class="fw-bold text-info">Historial de Pagos</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Método</th>
                    </tr>
                </thead>
                <tbody>
                    ${pagosHtml || '<tr><td colspan="3" class="text-center">No hay pagos registrados</td></tr>'}
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <th>Total Pagado:</th>
                        <th>$${totalPagado.toLocaleString('es-CO')}</th>
                        <th></th>
                    </tr>
                    <tr class="table-warning">
                        <th>Costo Total:</th>
                        <th>$${Number(orden.costo_total).toLocaleString('es-CO')}</th>
                        <th></th>
                    </tr>
                    <tr class="${saldoPendiente > 0 ? 'table-danger' : 'table-success'}">
                        <th>Saldo ${saldoPendiente > 0 ? 'Pendiente' : 'Completado'}:</th>
                        <th>$${Math.abs(saldoPendiente).toLocaleString('es-CO')}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="mt-3 text-center">
            <p class="text-muted small">¡Gracias por confiar en nosotros! - DIAZTEC</p>
        </div>
    `;
    
    // Guardar datos para funciones de envío
    window.currentOrden = { orden, pagos };
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleOrden'));
    modal.show();
}

// Funciones de envío para facturas
function enviarFacturaWhatsApp() {
    if (!window.currentFactura) {
        alert('No hay datos de factura disponibles');
        return;
    }
    
    const { venta, detalles } = window.currentFactura;
    let mensaje = `🧾 *FACTURA DIAZTEC* 🧾\n\n`;
    mensaje += `📋 *Factura:* ${venta.numero_factura}\n`;
    mensaje += `📅 *Fecha:* ${formatearFecha(venta.fecha_venta)}\n`;
    mensaje += `👤 *Cliente:* ${venta.cliente_nombre}\n`;
    mensaje += `🆔 *Identificación:* ${venta.cliente_identificacion}\n\n`;
    mensaje += `📦 *PRODUCTOS:*\n`;
    
    detalles.forEach(detalle => {
        mensaje += `• ${detalle.producto_nombre}\n`;
        mensaje += `  Cantidad: ${detalle.cantidad} - Precio: $${Number(detalle.precio_unitario).toLocaleString('es-CO')}\n`;
        mensaje += `  Subtotal: $${Number(detalle.subtotal).toLocaleString('es-CO')}\n\n`;
    });
    
    mensaje += `💰 *TOTAL: $${Number(venta.total).toLocaleString('es-CO')}*\n\n`;
    mensaje += `¡Gracias por su compra! 🙏\n`;
    mensaje += `📞 DIAZTEC - 3202975605 - 3203200992\n`;
    mensaje += `📧 karol.jesusdiaz@gmail.com\n`;
    mensaje += `📍 Transversal 12a 41b 31, Barrio Ocales Soacha`;
    
    const telefono = venta.cliente_telefono || '';
    if (telefono) {
        const telefonoLimpio = telefono.replace(/\D/g, '');
        const url = `https://wa.me/57${telefonoLimpio}?text=${encodeURIComponent(mensaje)}`;
        window.open(url, '_blank');
    } else {
        alert('El cliente no tiene teléfono registrado');
    }
}

function enviarFacturaEmail() {
    if (!window.currentFactura) {
        alert('No hay datos de factura disponibles');
        return;
    }
    
    const { venta, detalles } = window.currentFactura;
    let cuerpo = `Estimado(a) ${venta.cliente_nombre},\n\n`;
    cuerpo += `Adjunto encontrará el detalle de su compra:\n\n`;
    cuerpo += `FACTURA: ${venta.numero_factura}\n`;
    cuerpo += `FECHA: ${formatearFecha(venta.fecha_venta)}\n\n`;
    cuerpo += `PRODUCTOS ADQUIRIDOS:\n`;
    
    detalles.forEach(detalle => {
        cuerpo += `- ${detalle.producto_nombre} (${detalle.cantidad} x $${Number(detalle.precio_unitario).toLocaleString('es-CO')}) = $${Number(detalle.subtotal).toLocaleString('es-CO')}\n`;
    });
    
    cuerpo += `\nTOTAL: $${Number(venta.total).toLocaleString('es-CO')}\n\n`;
    cuerpo += `Gracias por su confianza.\n\n`;
    cuerpo += `Atentamente,\nDIAZTEC - Reparaciones y Tecnología\n`;
    cuerpo += `NIT: 1073679337\n`;
    cuerpo += `Dirección: Transversal 12a 41b 31, Barrio Ocales Soacha\n`;
    cuerpo += `Celular: 3202975605 - 3203200992\nEmail: karol.jesusdiaz@gmail.com`;
    
    const email = venta.cliente_email || '';
    if (email) {
        const asunto = `Factura ${venta.numero_factura} - DIAZTEC`;
        const url = `mailto:${email}?subject=${encodeURIComponent(asunto)}&body=${encodeURIComponent(cuerpo)}`;
        window.location.href = url;
    } else {
        alert('El cliente no tiene email registrado');
    }
}

// Funciones de envío para órdenes
function enviarOrdenWhatsApp() {
    if (!window.currentOrden) {
        alert('No hay datos de orden disponibles');
        return;
    }
    
    const { orden, pagos } = window.currentOrden;
    const totalPagado = pagos.reduce((sum, pago) => sum + parseFloat(pago.dinero_recibido), 0);
    const saldoPendiente = parseFloat(orden.costo_total) - totalPagado;
    
    let mensaje = `🔧 *ORDEN DE SERVICIO DIAZTEC* 🔧\n\n`;
    mensaje += `📋 *Orden:* ${orden.numero_orden}\n`;
    mensaje += `📅 *Fecha Ingreso:* ${formatearFecha(orden.fecha_ingreso)}\n`;
    mensaje += `👤 *Cliente:* ${orden.cliente_nombre}\n`;
    mensaje += `🆔 *Identificación:* ${orden.cliente_identificacion}\n\n`;
    mensaje += `💻 *EQUIPO:*\n`;
    mensaje += `• ${orden.equipo_nombre || 'N/A'} - ${orden.equipo_marca || ''} ${orden.equipo_modelo || ''}\n`;
    mensaje += `• Serial: ${orden.equipo_serial || 'N/A'}\n\n`;
    mensaje += `⚠️ *PROBLEMA:* ${orden.descripcion_problema || 'N/A'}\n\n`;
    if (orden.solucion && orden.solucion !== 'Pendiente') {
        mensaje += `✅ *SOLUCIÓN:* ${orden.solucion}\n\n`;
    }
    mensaje += `💰 *COSTO TOTAL:* $${Number(orden.costo_total).toLocaleString('es-CO')}\n`;
    mensaje += `💳 *PAGADO:* $${totalPagado.toLocaleString('es-CO')}\n`;
    mensaje += `💸 *SALDO:* $${Math.abs(saldoPendiente).toLocaleString('es-CO')}\n\n`;
    mensaje += `📞 DIAZTEC - 3202975605 - 3203200992\n`;
    mensaje += `📧 karol.jesusdiaz@gmail.com\n`;
    mensaje += `📍 Transversal 12a 41b 31, Barrio Ocales Soacha`;
    
    const telefono = orden.cliente_telefono || '';
    if (telefono) {
        const telefonoLimpio = telefono.replace(/\D/g, '');
        const url = `https://wa.me/57${telefonoLimpio}?text=${encodeURIComponent(mensaje)}`;
        window.open(url, '_blank');
    } else {
        alert('El cliente no tiene teléfono registrado');
    }
}

function enviarOrdenEmail() {
    if (!window.currentOrden) {
        alert('No hay datos de orden disponibles');
        return;
    }
    
    const { orden, pagos } = window.currentOrden;
    const totalPagado = pagos.reduce((sum, pago) => sum + parseFloat(pago.dinero_recibido), 0);
    const saldoPendiente = parseFloat(orden.costo_total) - totalPagado;
    
    let cuerpo = `Estimado(a) ${orden.cliente_nombre},\n\n`;
    cuerpo += `Le enviamos el detalle de su orden de servicio:\n\n`;
    cuerpo += `ORDEN: ${orden.numero_orden}\n`;
    cuerpo += `FECHA INGRESO: ${formatearFecha(orden.fecha_ingreso)}\n`;
    cuerpo += `ESTADO: ${orden.estado.charAt(0).toUpperCase() + orden.estado.slice(1)}\n\n`;
    cuerpo += `EQUIPO: ${orden.equipo_nombre || 'N/A'} - ${orden.equipo_marca || ''} ${orden.equipo_modelo || ''}\n`;
    cuerpo += `SERIAL: ${orden.equipo_serial || 'N/A'}\n\n`;
    cuerpo += `PROBLEMA REPORTADO: ${orden.descripcion_problema || 'N/A'}\n\n`;
    if (orden.solucion && orden.solucion !== 'Pendiente') {
        cuerpo += `SOLUCIÓN APLICADA: ${orden.solucion}\n\n`;
    }
    cuerpo += `COSTO TOTAL: $${Number(orden.costo_total).toLocaleString('es-CO')}\n`;
    cuerpo += `TOTAL PAGADO: $${totalPagado.toLocaleString('es-CO')}\n`;
    cuerpo += `SALDO PENDIENTE: $${Math.abs(saldoPendiente).toLocaleString('es-CO')}\n\n`;
    cuerpo += `Gracias por confiar en nosotros.\n\n`;
    cuerpo += `Atentamente,\nDIAZTEC - Reparaciones y Tecnología\n`;
    cuerpo += `NIT: 1073679337\n`;
    cuerpo += `Dirección: Transversal 12a 41b 31, Barrio Ocales Soacha\n`;
    cuerpo += `Celular: 3202975605 - 3203200992\nEmail: karol.jesusdiaz@gmail.com`;
    
    const email = orden.cliente_email || '';
    if (email) {
        const asunto = `Orden de Servicio ${orden.numero_orden} - DIAZTEC`;
        const url = `mailto:${email}?subject=${encodeURIComponent(asunto)}&body=${encodeURIComponent(cuerpo)}`;
        window.location.href = url;
    } else {
        alert('El cliente no tiene email registrado');
    }
}

// Funciones de impresión
function imprimirFactura() {
    window.print();
}

function imprimirOrden() {
    window.print();
}
</script>

<?php
require_once 'footer.php';
?>
