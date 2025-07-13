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
// --- NUEVA VENTA DE PRODUCTOS ---
let clienteSeleccionado = null;
let productosVenta = [];

function renderSugerenciasClientes(sugerencias) {
    const cont = document.getElementById('sugerenciasClienteVenta');
    cont.innerHTML = '';
    sugerencias.forEach(cliente => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action';
        item.textContent = `${cliente.nombre} (${cliente.identificacion || ''})`;
        item.onclick = function() {
            clienteSeleccionado = cliente;
            document.getElementById('inputClienteVenta').value = cliente.nombre;
            cont.innerHTML = '';
        };
        cont.appendChild(item);
    });
    cont.style.display = sugerencias.length ? 'block' : 'none';
}

let todosLosClientes = [];
function cargarClientesParaAutocomplete() {
    fetch('index.php?action=obtenerClientes')
        .then(r => r.json())
        .then(clientes => {
            todosLosClientes = clientes;
        });
}
cargarClientesParaAutocomplete();

// --- Autocompletado de productos ---
function renderSugerenciasProductos(sugerencias) {
    const cont = document.getElementById('sugerenciasProductoVenta');
    cont.innerHTML = '';
    sugerencias.forEach(producto => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action';
        item.textContent = `${producto.nombre} ($${producto.precio_venta}) - Stock: ${producto.stock}`;
        item.onclick = function() {
            // Al hacer clic, agregar automáticamente a la tabla de detalles
            agregarProductoADetalle(producto);
            document.getElementById('inputProductoVenta').value = '';
            document.getElementById('inputProductoVenta').dataset.productoId = '';
            cont.innerHTML = '';
        };
        cont.appendChild(item);
    });
    cont.style.display = sugerencias.length ? 'block' : 'none';
}

function agregarProductoADetalle(producto) {
    const existente = productosVenta.find(p => p.id == producto.id);
    if (existente) {
        existente.cantidad += 1;
    } else {
        productosVenta.push({
            id: producto.id,
            nombre: producto.nombre,
            precio_venta: Number(producto.precio_venta),
            cantidad: 1
        });
    }
    renderDetalleVenta();
}

// --- Lógica para detalle de productos seleccionados en la venta ---
function renderDetalleVenta() {
    const tbody = document.querySelector('#tablaDetalleVenta tbody');
    tbody.innerHTML = '';
    let total = 0;
    productosVenta.forEach((prod, idx) => {
        const subtotal = prod.cantidad * prod.precio_venta;
        total += subtotal;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${prod.nombre}</td>
            <td>$${Number(prod.precio_venta).toLocaleString('es-CO')}</td>
            <td>
                <button class="btn btn-sm btn-danger me-1" onclick="cambiarCantidadProducto(${idx}, -1)">-</button>
                <span class="mx-1">${prod.cantidad}</span>
                <button class="btn btn-sm btn-success ms-1" onclick="cambiarCantidadProducto(${idx}, 1)">+</button>
            </td>
            <td>$${Number(subtotal).toLocaleString('es-CO')}</td>
            <td><button class="btn btn-sm btn-danger" onclick="eliminarProductoDetalle(${idx})"><i class="bi bi-trash"></i></button></td>
        `;
        tbody.appendChild(tr);
    });
    document.getElementById('totalNuevaVenta').textContent = `$${total.toLocaleString('es-CO')}`;
}

window.cambiarCantidadProducto = function(idx, delta) {
    if (productosVenta[idx]) {
        productosVenta[idx].cantidad += delta;
        if (productosVenta[idx].cantidad < 1) productosVenta[idx].cantidad = 1;
        renderDetalleVenta();
    }
};

window.eliminarProductoDetalle = function(idx) {
    productosVenta.splice(idx, 1);
    renderDetalleVenta();
};

// Al seleccionar producto del autocompletado, agregar a la tabla
function agregarProductoSeleccionado() {
    const input = document.getElementById('inputProductoVenta');
    const id = parseInt(input.dataset.productoId);
    const nombre = input.value;
    if (!id || !nombre) return;
    // Buscar si ya está en la lista
    const existente = productosVenta.find(p => p.id === id);
    if (existente) {
        existente.cantidad += 1;
    } else {
        // Buscar precio del producto en sugerencias (o pedirlo al backend si es necesario)
        fetch(`index.php?action=buscarProducto&query=${encodeURIComponent(nombre)}`)
            .then(r => r.json())
            .then(data => {
                const prod = (data.productos || []).find(p => p.id == id);
                if (prod) {
                    productosVenta.push({
                        id: prod.id,
                        nombre: prod.nombre,
                        precio_venta: Number(prod.precio_venta),
                        cantidad: 1
                    });
                    renderDetalleVenta();
                }
            });
        return;
    }
    renderDetalleVenta();
}

// --- Lógica método de pago y efectivo ---
function actualizarMetodoPago() {
    const checkboxes = document.querySelectorAll('#grupoMetodoPago .form-check-input');
    checkboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            if (this.checked) {
                // Desmarcar los demás
                checkboxes.forEach(other => { if (other !== this) other.checked = false; });
                if (this.value === 'efectivo') {
                    document.getElementById('grupoEfectivo').classList.remove('d-none');
                } else {
                    document.getElementById('grupoEfectivo').classList.add('d-none');
                }
            } else {
                // Si se desmarca, ocultar efectivo
                document.getElementById('grupoEfectivo').classList.add('d-none');
            }
        });
    });
}
actualizarMetodoPago();

// Calcular cambio automáticamente
function calcularCambio() {
    const total = productosVenta.reduce((acc, p) => acc + p.cantidad * p.precio_venta, 0);
    const recibido = Number(document.getElementById('inputDineroRecibido').value) || 0;
    const cambio = recibido - total;
    document.getElementById('inputCambio').value = cambio >= 0 ? `$${cambio.toLocaleString('es-CO')}` : '';
}
document.getElementById('inputDineroRecibido').addEventListener('input', calcularCambio);
// Actualizar cambio si cambia el total
const observer = new MutationObserver(calcularCambio);
observer.observe(document.getElementById('totalNuevaVenta'), {childList: true});

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
    // Mostrar modal al hacer clic en el botón flotante
    document.getElementById('btnNuevaVenta').onclick = function() {
        const modal = new bootstrap.Modal(document.getElementById('modalNuevaVenta'));
        document.getElementById('numeroFacturaVenta').textContent = '';
        modal.show();
    };
    // Hook para buscar cliente (autocompletado)
    document.getElementById('inputClienteVenta').addEventListener('input', function(e) {
        const texto = e.target.value.trim();
        if (!texto) {
            renderSugerenciasClientes([]);
            return;
        }
        // Normalizar texto para buscar sin tildes
        const normalizar = s => (s ? s.normalize('NFD').replace(/[^\w\d]/g, '').replace(/\u0300-\u036f/g, '').toLowerCase() : '');
        const textoNorm = normalizar(texto);
        const sugerencias = todosLosClientes.filter(c =>
            normalizar(c.nombre).includes(textoNorm) ||
            normalizar(c.identificacion).includes(textoNorm)
        ).slice(0, 8);
        renderSugerenciasClientes(sugerencias);
    });
    document.getElementById('inputClienteVenta').addEventListener('blur', function() {
        setTimeout(() => {
            document.getElementById('sugerenciasClienteVenta').innerHTML = '';
        }, 200);
    });
    // Hook para crear cliente
    document.getElementById('btnCrearClienteVenta').onclick = function() {
        alert('Funcionalidad para crear cliente próximamente');
    };
    // --- Lógica para crear cliente desde submodal ---
    document.getElementById('btnCrearClienteVenta').onclick = function() {
        const modal = new bootstrap.Modal(document.getElementById('modalNuevoCliente'));
        document.getElementById('formNuevoCliente').reset();
        modal.show();
    };
    document.getElementById('btnGuardarNuevoCliente').onclick = function() {
        const nombre = document.getElementById('nuevoClienteNombre').value.trim();
        const identificacion = document.getElementById('nuevoClienteIdentificacion').value.trim();
        const telefono = document.getElementById('nuevoClienteTelefono').value.trim();
        const email = document.getElementById('nuevoClienteEmail').value.trim();
        const direccion = document.getElementById('nuevoClienteDireccion').value.trim();
        if (!nombre || !identificacion) {
            alert('Nombre e identificación son obligatorios.');
            return;
        }
        fetch('index.php?action=crearCliente', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                nombre,
                identificacion,
                telefono,
                email,
                direccion
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Agregar el nuevo cliente a la lista y seleccionarlo
                const nuevoCliente = {
                    id: data.cliente_id || data.id || data.insert_id || null,
                    nombre,
                    identificacion,
                    telefono,
                    email,
                    direccion
                };
                todosLosClientes.push(nuevoCliente);
                clienteSeleccionado = nuevoCliente;
                document.getElementById('inputClienteVenta').value = nombre;
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoCliente'));
                if (modal) modal.hide();
                alert('Cliente creado y seleccionado exitosamente.');
            } else {
                alert('Error al crear cliente: ' + (data.message || 'Error desconocido.'));
            }
        })
        .catch(err => {
            alert('Error de red o del servidor al crear cliente.');
            console.error(err);
        });
    };
    // Hook para buscar producto (autocompletado)
    document.getElementById('inputProductoVenta').addEventListener('input', function(e) {
        const texto = e.target.value.trim();
        if (!texto) {
            renderSugerenciasProductos([]);
            return;
        }
        fetch(`index.php?action=buscarProducto&query=${encodeURIComponent(texto)}`)
            .then(r => r.json())
            .then(data => {
                renderSugerenciasProductos(data.productos || []);
            });
    });
    document.getElementById('inputProductoVenta').addEventListener('blur', function() {
        setTimeout(() => {
            document.getElementById('sugerenciasProductoVenta').innerHTML = '';
        }, 200);
    });
    // Hook para agregar producto seleccionado
    document.getElementById('btnAgregarProductoVenta').textContent = 'Nuevo';
    document.getElementById('btnAgregarProductoVenta').onclick = function() {
        const modal = new bootstrap.Modal(document.getElementById('modalNuevoProducto'));
        document.getElementById('formNuevoProducto').reset();
        document.getElementById('nuevoProductoActivo').checked = true;
        modal.show();
    };
    // Previsualización de imagen en el submodal de producto
    const inputImg = document.getElementById('nuevoProductoImagen');
    const previewImg = document.getElementById('previewProductoImagen');
    inputImg.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            previewImg.src = '#';
            previewImg.classList.add('d-none');
        }
    });

    document.getElementById('btnGuardarNuevoProducto').onclick = function() {
        const nombre = document.getElementById('nuevoProductoNombre').value.trim();
        const descripcion = document.getElementById('nuevoProductoDescripcion').value.trim();
        const precio_compra = parseFloat(document.getElementById('nuevoProductoPrecioCompra').value) || 0;
        const precio_venta = parseFloat(document.getElementById('nuevoProductoPrecioVenta').value) || 0;
        const stock = parseInt(document.getElementById('nuevoProductoStock').value) || 0;
        const stock_minimo = parseInt(document.getElementById('nuevoProductoStockMinimo').value) || 0;
        const activo = document.getElementById('nuevoProductoActivo').checked ? 1 : 0;
        const categoria = document.getElementById('nuevoProductoCategoria').value.trim();
        const codigo_barras = document.getElementById('nuevoProductoCodigoBarras').value.trim();
        if (!nombre) {
            alert('El nombre del producto es obligatorio.');
            return;
        }
        // Subir imagen si hay
        const file = inputImg.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('imagen', file);
            fetch('assets/img/upload.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.nombre_archivo) {
                    crearProductoConImagen(data.nombre_archivo);
                } else {
                    alert('Error al subir la imagen: ' + (data.message || 'Error desconocido.'));
                }
            })
            .catch(err => {
                alert('Error de red o del servidor al subir imagen.');
                console.error(err);
            });
        } else {
            crearProductoConImagen('');
        }
        function crearProductoConImagen(nombreImagen) {
            fetch('index.php?action=agregarProducto', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    nombre,
                    descripcion,
                    precio_compra,
                    precio_venta,
                    stock,
                    stock_minimo,
                    activo,
                    imagen: nombreImagen,
                    categoria,
                    codigo_barras
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Agregar el nuevo producto a la lista y seleccionarlo
                    const nuevoProducto = data.producto;
                    agregarProductoADetalle(nuevoProducto);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoProducto'));
                    if (modal) modal.hide();
                    alert('Producto creado y agregado a la venta exitosamente.');
                } else {
                    alert('Error al crear producto: ' + (data.message || 'Error desconocido.'));
                }
            })
            .catch(err => {
                alert('Error de red o del servidor al crear producto.');
                console.error(err);
            });
        }
    };
    // Hook para registrar venta
    document.getElementById('btnRegistrarVenta').onclick = function() {
        // Validaciones básicas
        if (!clienteSeleccionado || !clienteSeleccionado.id) {
            alert('Selecciona un cliente válido.');
            return;
        }
        if (!productosVenta.length) {
            alert('Agrega al menos un producto a la venta.');
            return;
        }
        // Método de pago
        const metodoPago = Array.from(document.querySelectorAll('#grupoMetodoPago .form-check-input'))
            .find(chk => chk.checked)?.value;
        if (!metodoPago) {
            alert('Selecciona un método de pago.');
            return;
        }
        // Efectivo: dinero recibido y cambio
        let dineroRecibido = 0;
        let cambio = 0;
        if (metodoPago === 'efectivo') {
            dineroRecibido = Number(document.getElementById('inputDineroRecibido').value) || 0;
            const total = productosVenta.reduce((acc, p) => acc + p.cantidad * p.precio_venta, 0);
            cambio = dineroRecibido - total;
            if (dineroRecibido < total) {
                alert('El dinero recibido no puede ser menor al total de la venta.');
                return;
            }
        }
        const totalVenta = productosVenta.reduce((acc, p) => acc + p.cantidad * p.precio_venta, 0);
        // Preparar productos para backend
        const productosPayload = productosVenta.map(p => ({
            id: p.id,
            cantidad: p.cantidad,
            precio: p.precio_venta
        }));
        // Enviar al backend
        fetch('index.php?action=registrarVentaCompleta', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                cliente_id: clienteSeleccionado.id,
                productos: JSON.stringify(productosPayload),
                total: totalVenta,
                metodo_pago: metodoPago,
                dinero_recibido: dineroRecibido,
                cambio: cambio
            })
        })
        .then(r => r.json())
        .then(data => {
            console.log('Respuesta registrarVentaCompleta:', data);
            if (data.success) {
                document.getElementById('numeroFacturaVenta').textContent = data.numero_factura ? `Factura: ${data.numero_factura}` : '';
                alert('Venta registrada exitosamente.');
                // Limpiar modal y recargar tabla
                productosVenta = [];
                renderDetalleVenta();
                clienteSeleccionado = null;
                document.getElementById('inputClienteVenta').value = '';
                Array.from(document.querySelectorAll('#grupoMetodoPago .form-check-input')).forEach(chk => chk.checked = false);
                document.getElementById('grupoEfectivo').classList.add('d-none');
                document.getElementById('inputDineroRecibido').value = '';
                document.getElementById('inputCambio').value = '';
                // El modal se cierra después de un pequeño delay para que el usuario vea el número de factura
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaVenta'));
                    if (modal) modal.hide();
                    document.getElementById('numeroFacturaVenta').textContent = '';
                }, 1800);
                cargarPagosCaja();
            } else {
                alert('Error al registrar la venta: ' + (data.message || 'Error desconocido.'));
            }
        })
        .catch(err => {
            alert('Error de red o del servidor al registrar la venta.');
            console.error(err);
        });
    };
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
    fetch(`index.php?action=obtenerDetalleFactura&venta_id=${ventaId}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Aquí puedes mostrar el detalle en un modal o alert
                let detalle = `Factura: ${data.venta.numero_factura}\nCliente: ${data.venta.cliente_nombre}\nTotal: $${Number(data.venta.total).toLocaleString('es-CO')}\n\nProductos:\n`;
                data.detalles.forEach(d => {
                    detalle += `- ${d.producto_nombre} x${d.cantidad} ($${Number(d.precio_unitario).toLocaleString('es-CO')})\n`;
                });
                alert(detalle);
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