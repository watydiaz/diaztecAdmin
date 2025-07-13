// JS del módulo Caja

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
    if (!fechaInicio || !fechaFin) {
        alert('Por favor selecciona tanto la fecha de inicio como la fecha fin');
        return;
    }
    if (fechaInicio > fechaFin) {
        alert('La fecha de inicio no puede ser mayor que la fecha fin');
        return;
    }
    cargarPagosCaja(fechaInicio, fechaFin);
}
function actualizarVentaTotal() {
    console.log('actualizarVentaTotal() - Función obsoleta, totales calculados en servidor');
}
document.addEventListener('DOMContentLoaded', function() {
    cargarPagosCaja();
    const hoy = getFechaHoy();
    document.getElementById('fechaInicio').value = hoy;
    document.getElementById('fechaFin').value = hoy;
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
    $('.selectpicker').selectpicker();
});

function cargarPagosCaja(fechaInicio = null, fechaFin = null) {
    if (!fechaInicio || !fechaFin) {
        const hoy = getFechaHoy();
        fechaInicio = hoy;
        fechaFin = hoy;
    }
    const url = `/diaztecAdmin/index.php?action=obtenerPagosCaja&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    fetch(url)
        .then(r => r.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success === false) {
                    throw new Error(data.error || 'Error desconocido del servidor');
                }
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
                const totalGeneral = totalOrdenes + totalProductos;
                document.getElementById('ventaTotal').textContent = '$' + totalGeneral.toLocaleString('es-CO');
                const infoPeriodo = fechaInicio === fechaFin ? 
                    `Mostrando ventas del ${formatearFechaSinHora(fechaInicio)}` : 
                    `Mostrando ventas desde ${formatearFechaSinHora(fechaInicio)} hasta ${formatearFechaSinHora(fechaFin)}`;
                let infoElement = document.getElementById('infoPeriodo');
                if (!infoElement) {
                    infoElement = document.createElement('div');
                    infoElement.id = 'infoPeriodo';
                    infoElement.className = 'alert alert-info text-center mb-3';
                    document.querySelector('.container-fluid h3').insertAdjacentElement('afterend', infoElement);
                }
                infoElement.innerHTML = `<i class="bi bi-calendar3"></i> ${infoPeriodo}`;
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
            const tbodyOrdenes = document.getElementById('tbodyCajaPagosOrdenes');
            const tbodyProductos = document.getElementById('tbodyCajaPagosProductos');
            const mensajeError = `<tr><td colspan="5" class="text-center text-danger">
                <i class="bi bi-exclamation-triangle"></i> Error al cargar datos: ${error.message}
                <br><small>Revisa la consola para más detalles</small>
            </td></tr>`;
            tbodyOrdenes.innerHTML = mensajeError;
            tbodyProductos.innerHTML = mensajeError.replace('colspan="5"', 'colspan="6"');
            alert('Error al cargar los datos de caja. Revisa la consola del navegador para más información.');
        });
}
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
function formatearFechaSinHora(fechaStr) {
    if (!fechaStr) return '';
    const fecha = new Date(fechaStr + 'T00:00:00');
    return fecha.toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}
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
// ... (continúa el resto del JS de caja.php) 