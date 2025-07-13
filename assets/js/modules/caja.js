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
                let totalEfectivo = 0, totalNequi = 0, totalDaviplata = 0, totalTarjeta = 0;
                if (data.pagos_ordenes && Array.isArray(data.pagos_ordenes) && data.pagos_ordenes.length > 0) {
                    data.pagos_ordenes.forEach((pago) => {
                        totalOrdenes += Number(pago.dinero_recibido || 0);
                        // Sumar por método de pago
                        switch ((pago.metodo_pago || '').toLowerCase()) {
                            case 'efectivo': totalEfectivo += Number(pago.dinero_recibido || 0); break;
                            case 'nequi': totalNequi += Number(pago.dinero_recibido || 0); break;
                            case 'daviplata': totalDaviplata += Number(pago.dinero_recibido || 0); break;
                            case 'tarjeta_credito': totalTarjeta += Number(pago.dinero_recibido || 0); break;
                        }
                        tbodyOrdenes.innerHTML += `
                            <tr>
                                <td><strong>${pago.numero_orden || `Orden #${pago.orden_id}`}</strong></td>
                                <td>${pago.cliente_nombre || ''}<br><small class="text-muted">${pago.cliente_identificacion || ''}</small></td>
                                <td>${formatearFecha(pago.fecha_pago || '')}</td>
                                <td><strong>$${Number(pago.dinero_recibido || 0).toLocaleString('es-CO')}</strong></td>
                                <td><span class="badge bg-primary">${(pago.metodo_pago || '').charAt(0).toUpperCase() + (pago.metodo_pago || '').slice(1)}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" onclick="verDetalleOrden(${pago.orden_id})" title="Ver detalle de orden">
                                        <i class="bi bi-eye"></i> Ver
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    tbodyOrdenes.innerHTML = '<tr><td colspan="6" class="text-center">No hay pagos de órdenes registrados en el período seleccionado.</td></tr>';
                }
                // Pagos de productos
                const tbodyProductos = document.getElementById('tbodyCajaPagosProductos');
                tbodyProductos.innerHTML = '';
                let totalProductos = 0;
                if (data.pagos_productos && Array.isArray(data.pagos_productos) && data.pagos_productos.length > 0) {
                    data.pagos_productos.forEach((pago) => {
                        totalProductos += Number(pago.total || 0);
                        // Sumar por método de pago
                        switch ((pago.metodo_pago || '').toLowerCase()) {
                            case 'efectivo': totalEfectivo += Number(pago.total || 0); break;
                            case 'nequi': totalNequi += Number(pago.total || 0); break;
                            case 'daviplata': totalDaviplata += Number(pago.total || 0); break;
                            case 'tarjeta_credito': totalTarjeta += Number(pago.total || 0); break;
                        }
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
                // Actualizar los totales en las cards
                document.getElementById('totalCajaOrdenes').textContent = 'Total en caja (órdenes): $' + totalOrdenes.toLocaleString('es-CO');
                document.getElementById('totalCajaProductos').textContent = 'Total en caja (productos): $' + totalProductos.toLocaleString('es-CO');
                document.getElementById('totalEfectivo').textContent = `$${totalEfectivo.toLocaleString('es-CO')}`;
                document.getElementById('totalNequi').textContent = `$${totalNequi.toLocaleString('es-CO')}`;
                document.getElementById('totalDaviplata').textContent = `$${totalDaviplata.toLocaleString('es-CO')}`;
                document.getElementById('totalTarjeta').textContent = `$${totalTarjeta.toLocaleString('es-CO')}`;
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
// Función para mostrar el teléfono como enlace a WhatsApp
function formatearTelefonoWhatsapp(numero) {
    if (!numero) return '-';
    let tel = numero.replace(/[^\d]/g, '');
    if (tel.length === 10) tel = '57' + tel;
    return `<a href='https://wa.me/${tel}' target='_blank' style='text-decoration:none;color:#22c55e;font-weight:bold;'>${numero} <i class='bi bi-whatsapp'></i></a>`;
}
// --- MODAL DETALLE DE PAGO (reutilizable) ---
if (!document.getElementById('modalDetallePago')) {
    const modalHtml = `
    <div class="modal fade" id="modalDetallePago" tabindex="-1" aria-labelledby="modalDetallePagoLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDetallePagoLabel">Detalle del Pago</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body" id="modalDetallePagoBody">
            <!-- Aquí va el contenido dinámico -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btnImprimirDetallePago"><i class="bi bi-printer"></i> Imprimir</button>
          </div>
        </div>
      </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

// --- Función para mostrar el modal con datos de la empresa, cliente y detalle ---
function mostrarModalDetallePago(html) {
    document.getElementById('modalDetallePagoBody').innerHTML = html;
    const modal = new bootstrap.Modal(document.getElementById('modalDetallePago'));
    modal.show();
    document.getElementById('btnImprimirDetallePago').onclick = function() {
        const printContents = document.getElementById('modalDetallePagoBody').innerHTML;
        const win = window.open('', '', 'width=900,height=700');
        win.document.write('<html><head><title>Comprobante</title>');
        win.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
        win.document.write('</head><body>' + printContents + '</body></html>');
        win.document.close();
        win.print();
    };
}

// 1. Incluir html2canvas si no está cargado
if (typeof html2canvas === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js';
    document.head.appendChild(script);
}

// 2. Agregar botón WhatsApp al modal
if (!document.getElementById('btnWhatsappDetallePago')) {
    const btnWhatsapp = document.createElement('button');
    btnWhatsapp.className = 'btn btn-success';
    btnWhatsapp.id = 'btnWhatsappDetallePago';
    btnWhatsapp.innerHTML = '<i class="bi bi-whatsapp"></i> Enviar por WhatsApp';
    document.querySelector('#modalDetallePago .modal-footer').insertBefore(btnWhatsapp, document.getElementById('btnImprimirDetallePago'));
}

// Cambiar la función enviarDetallePorWhatsapp para solo enviar la URL del comprobante
async function enviarDetallePorWhatsapp(clienteTel, clienteNombre, comprobanteUrl) {
    if (clienteTel) {
        let tel = clienteTel.replace(/[^\d]/g, '');
        if (tel.length === 10) tel = '57' + tel; // Colombia
        // Mensaje mejorado: salto de línea antes de la URL, nota para el cliente, recordatorio de servicios y sugerencia de guardar contacto
        const mensaje = encodeURIComponent(`Hola ${clienteNombre || ''}, aquí está tu comprobante de pago:\n\n${comprobanteUrl}\n\nSi el enlace no se abre, por favor cópialo y pégalo en tu navegador.\n\nRecuerda que en DIAZTECNOLOGÍA te ofrecemos reparación de celulares, venta de accesorios, tecnología y servicio técnico especializado. ¡Guarda nuestro contacto para futuras necesidades!`);
        window.open(`https://wa.me/${tel}?text=${mensaje}`, '_blank');
    } else {
        alert('No hay número de teléfono registrado para el cliente.');
    }
}

// Modificar asignarEventoWhatsapp para pasar la URL del comprobante
function asignarEventoWhatsapp(clienteTel, clienteNombre, comprobanteUrl) {
    const btn = document.getElementById('btnWhatsappDetallePago');
    if (btn) {
        btn.onclick = function() {
            enviarDetallePorWhatsapp(clienteTel, clienteNombre, comprobanteUrl);
        };
    }
}

// Utilizar la URL base correcta para comprobante
const BASE_URL = 'http://localhost/diaztecAdmin';

// --- Mejorar verDetalleFactura para compartir comprobante por WhatsApp ---
function verDetalleFactura(ventaId) {
    fetch(`index.php?action=obtenerDetalleFactura&venta_id=${ventaId}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const v = data.venta;
                const detalles = data.detalles;
                let html = `
                <div class="row mb-2">
                  <div class="col-md-3 text-center">
                    <img src="https://diaztecnologia.com/img/logo.png" alt="Logo" style="max-width:100px;">
                  </div>
                  <div class="col-md-9">
                    <h4 class="mb-0">Diaztecnologia</h4>
                    <div>NIT: 1073679337-8</div>
                    <div>Transversal 12a 41b 31, Soacha - Ocales</div>
                    <div>Cel: ${formatearTelefonoWhatsapp(v.cliente_telefono)} - ${formatearTelefonoWhatsapp(v.cliente_telefono)}</div>
                    <div>Email: karol.jesusdiaz@gmail.com</div>
                    <div>Web: diaztecnologia.com</div>
                  </div>
                </div>
                <hr>
                <div class="row mb-2">
                  <div class="col-md-6">
                    <strong>Cliente:</strong><br>
                    ${v.cliente_nombre}<br>
                    <small>ID: ${v.cliente_identificacion}</small><br>
                    <small>Tel: ${formatearTelefonoWhatsapp(v.cliente_telefono)}</small><br>
                    <small>Email: ${v.cliente_email || '-'}</small>
                  </div>
                  <div class="col-md-6 text-end">
                    <strong>Factura:</strong> ${v.numero_factura}<br>
                    <strong>Fecha:</strong> ${formatearFecha(v.fecha_venta)}<br>
                    <strong>Método de pago:</strong> ${v.metodo_pago}<br>
                    <strong>Registrado por:</strong> ${v.usuario_nombre || '-'}
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                    <tbody>
                      ${detalles.map(d => `<tr><td>${d.producto_nombre}</td><td>${d.cantidad}</td><td>$${Number(d.precio_unitario).toLocaleString('es-CO')}</td><td>$${Number(d.subtotal).toLocaleString('es-CO')}</td></tr>`).join('')}
                    </tbody>
                  </table>
                </div>
                <div class="text-end fs-5"><strong>Total:</strong> $${Number(v.total).toLocaleString('es-CO')}</div>
                `;
                mostrarModalDetallePago(html);
                // Generar la URL del comprobante con la base correcta
                const comprobanteUrl = `${BASE_URL}/comprobante.php?venta_id=${ventaId}`;
                asignarEventoWhatsapp(v.cliente_telefono, v.cliente_nombre, comprobanteUrl);
            } else {
                alert('Error al cargar el detalle de la factura: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al cargar detalle de factura:', error);
            alert('Error al cargar el detalle de la factura');
        });
}

// --- Mejorar mostrarModalDetalleOrden para compartir comprobante por WhatsApp ---
function mostrarModalDetalleOrden(orden, pagos) {
    let html = `
    <div class="row mb-2">
      <div class="col-md-3 text-center">
        <img src="https://diaztecnologia.com/img/logo.png" alt="Logo" style="max-width:100px;">
      </div>
      <div class="col-md-9">
        <h4 class="mb-0">Diaztecnologia</h4>
        <div>NIT: 1073679337-8</div>
        <div>Transversal 12a 41b 31, Soacha - Ocales</div>
        <div>Cel: ${formatearTelefonoWhatsapp(orden.cliente_telefono)} - ${formatearTelefonoWhatsapp(orden.cliente_telefono)}</div>
        <div>Email: karol.jesusdiaz@gmail.com</div>
        <div>Web: diaztecnologia.com</div>
      </div>
    </div>
    <hr>
    <div class="row mb-2">
      <div class="col-md-6">
        <strong>Cliente:</strong><br>
        ${orden.cliente_nombre}<br>
        <small>ID: ${orden.cliente_identificacion}</small><br>
        <small>Tel: ${formatearTelefonoWhatsapp(orden.cliente_telefono)}</small><br>
        <small>Email: ${orden.cliente_email || '-'}</small><br>
        <small>Dirección: ${orden.cliente_direccion || '-'}</small>
      </div>
      <div class="col-md-6 text-end">
        <strong>Orden:</strong> ${orden.numero_orden}<br>
        <strong>Equipo:</strong> ${orden.equipo_nombre}<br>
        <strong>Estado:</strong> ${orden.estado}<br>
        <strong>Ingreso:</strong> ${formatearFecha(orden.fecha_ingreso)}<br>
        <strong>Entrega estimada:</strong> ${formatearFecha(orden.fecha_entrega)}
      </div>
    </div>
    <div class="mb-2"><strong>Problema reportado:</strong> ${orden.descripcion_problema}</div>
    <div class="mb-2"><strong>Diagnóstico / Solución:</strong> ${orden.solucion}</div>
    <div class="mb-2"><strong>Costo total:</strong> $${Number(orden.costo_total).toLocaleString('es-CO')}</div>
    <div class="mb-2"><strong>Pagos realizados:</strong></div>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead><tr><th>Fecha</th><th>Monto</th><th>Método</th></tr></thead>
        <tbody>
          ${pagos.map(p => `<tr><td>${formatearFecha(p.fecha_pago)}</td><td>$${Number(p.dinero_recibido).toLocaleString('es-CO')}</td><td>${p.metodo_pago}</td></tr>`).join('')}
        </tbody>
      </table>
    </div>
    <div class="text-end fs-5"><strong>Total pagado:</strong> $${pagos.reduce((acc, p) => acc + Number(p.dinero_recibido), 0).toLocaleString('es-CO')}</div>
    <div class="text-end fs-5"><strong>Saldo pendiente:</strong> $${(Number(orden.costo_total) - pagos.reduce((acc, p) => acc + Number(p.dinero_recibido), 0)).toLocaleString('es-CO')}</div>
    `;
    mostrarModalDetallePago(html);
    // Generar la URL del comprobante con la base correcta
    const comprobanteUrl = `${BASE_URL}/comprobante.php?orden_id=${orden.id}`;
    asignarEventoWhatsapp(orden.cliente_telefono, orden.cliente_nombre, comprobanteUrl);
}
window.verDetalleOrden = function(ordenId) {
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
};
// ... (continúa el resto del JS de caja.php) 