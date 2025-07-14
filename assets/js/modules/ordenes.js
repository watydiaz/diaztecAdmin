/**
 * Módulo de Gestión de Órdenes de Trabajo
 * Funcionalidades JavaScript para el módulo de órdenes
 */

// Variables globales
let ordenes = [];

// Inicialización del módulo
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners para formularios
    document.getElementById('formAgregarOrden')?.addEventListener('submit', guardarOrden);
    document.getElementById('formAgregarClienteRapido')?.addEventListener('submit', guardarClienteRapido);
    document.getElementById('formEditarOrden')?.addEventListener('submit', actualizarOrden);
    document.getElementById('formAgregarPago')?.addEventListener('submit', guardarPago);
    
    // Event listeners para cálculos automáticos de pagos
    if (document.getElementById('costo_total_inicial')) {
        document.getElementById('costo_total_inicial').addEventListener('input', calcularSaldoInicial);
        document.getElementById('dinero_recibido_inicial').addEventListener('input', calcularSaldoInicial);
    }
    
    // Event listeners para el modal de gestión de pagos (diferente al de creación)
    if (document.getElementById('costo_total')) {
        document.getElementById('costo_total').addEventListener('input', calcularSaldoGestionPagos);
    }
    if (document.getElementById('dinero_recibido')) {
        document.getElementById('dinero_recibido').addEventListener('input', calcularSaldoGestionPagos);
    }
    
    // Cargar técnicos al inicializar
    cargarTecnicos();
    
    // Cargar saldos de todas las órdenes en la tabla
    cargarSaldosTabla();
    
    // Establecer fecha actual por defecto
    const fechaActual = new Date();
    fechaActual.setMinutes(fechaActual.getMinutes() - fechaActual.getTimezoneOffset());
    if (document.getElementById('fecha_ingreso')) {
        document.getElementById('fecha_ingreso').value = fechaActual.toISOString().slice(0, 16);
    }
    
    // Actualizar fecha en header
    if (document.getElementById('fechaActual')) {
        document.getElementById('fechaActual').textContent = new Date().toLocaleDateString('es-CO', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
});

/**
 * Funciones para el modal de agregar orden
 */

// Función para mostrar modal agregar orden
function mostrarModalAgregarOrden() {
    document.getElementById('formAgregarOrden').reset();
    
    // Restablecer fecha actual
    const fechaActual = new Date();
    fechaActual.setMinutes(fechaActual.getMinutes() - fechaActual.getTimezoneOffset());
    document.getElementById('fecha_ingreso').value = fechaActual.toISOString().slice(0, 16);
    
    // Limpiar resultados de búsqueda
    document.getElementById('resultadosClientes').innerHTML = '';
    document.getElementById('cliente_id').value = '';
    
    cargarTecnicos();
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarOrden'));
    modal.show();
}

// Función para cargar técnicos
function cargarTecnicos() {
    fetch('index.php?action=obtenerTecnicos')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                const select = document.getElementById('usuario_tecnico_id');
                if (!select) return;
                
                select.innerHTML = '<option value="">Seleccione un técnico</option>';
                
                if (data.success && data.tecnicos) {
                    data.tecnicos.forEach(tecnico => {
                        select.innerHTML += `<option value="${tecnico.id}">${tecnico.nombre}</option>`;
                    });
                } else {
                    console.warn('No se pudieron cargar los técnicos:', data);
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
            }
        })
        .catch(error => {
            console.error('Error al cargar técnicos:', error);
        });
}

// Función para cargar técnicos en el modal de edición
function cargarTecnicosEditar(selectedId) {
    fetch('index.php?action=obtenerTecnicos')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                const select = document.getElementById('editarUsuarioTecnicoId');
                if (!select) return;
                
                select.innerHTML = '<option value="">Seleccione un técnico</option>';
                
                if (data.success && data.tecnicos) {
                    data.tecnicos.forEach(tecnico => {
                        const selected = tecnico.id == selectedId ? 'selected' : '';
                        select.innerHTML += `<option value="${tecnico.id}" ${selected}>${tecnico.nombre}</option>`;
                    });
                } else {
                    console.warn('No se pudieron cargar los técnicos:', data);
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
            }
        })
        .catch(error => {
            console.error('Error al cargar técnicos:', error);
        });
}

/**
 * Funciones para búsqueda y gestión de clientes
 */

// Función para buscar clientes
function buscarClientes(query) {
    if (query.length < 2) {
        document.getElementById('resultadosClientes').innerHTML = '';
        return;
    }
    
    fetch(`index.php?action=buscarCliente&query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                const resultados = document.getElementById('resultadosClientes');
                
                if (data.success && data.clientes && data.clientes.length > 0) {
                    let html = '<div class="list-group">';
                    data.clientes.forEach(cliente => {
                        html += `
                            <button type="button" class="list-group-item list-group-item-action" 
                                    onclick="seleccionarCliente(${cliente.id}, '${cliente.nombre.replace(/'/g, "\\'")}')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${cliente.nombre}</strong><br>
                                        <small class="text-muted">${cliente.identificacion || 'Sin identificación'} - ${cliente.telefono || 'Sin teléfono'}</small>
                                    </div>
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            </button>
                        `;
                    });
                    html += '</div>';
                    resultados.innerHTML = html;
                } else {
                    resultados.innerHTML = '<div class="alert alert-info">No se encontraron clientes que coincidan con la búsqueda</div>';
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
                document.getElementById('resultadosClientes').innerHTML = '<div class="alert alert-danger">Error al procesar la respuesta del servidor</div>';
            }
        })
        .catch(error => {
            console.error('Error al buscar clientes:', error);
            document.getElementById('resultadosClientes').innerHTML = '<div class="alert alert-danger">Error de conexión al buscar clientes</div>';
        });
}

// Función para buscar clientes en el modal de edición
function buscarClientesEditar(query) {
    if (query.length < 2) {
        document.getElementById('editarResultadosClientes').innerHTML = '';
        return;
    }
    
    fetch(`index.php?action=buscarCliente&query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                const resultados = document.getElementById('editarResultadosClientes');
                
                if (data.success && data.clientes && data.clientes.length > 0) {
                    let html = '<div class="list-group">';
                    data.clientes.forEach(cliente => {
                        html += `
                            <button type="button" class="list-group-item list-group-item-action" 
                                    onclick="seleccionarClienteEditar(${cliente.id}, '${cliente.nombre.replace(/'/g, "\\'")}')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${cliente.nombre}</strong><br>
                                        <small class="text-muted">${cliente.identificacion || 'Sin identificación'} - ${cliente.telefono || 'Sin teléfono'}</small>
                                    </div>
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            </button>
                        `;
                    });
                    html += '</div>';
                    resultados.innerHTML = html;
                } else {
                    resultados.innerHTML = '<div class="alert alert-info">No se encontraron clientes que coincidan con la búsqueda</div>';
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
                document.getElementById('editarResultadosClientes').innerHTML = '<div class="alert alert-danger">Error al procesar la respuesta del servidor</div>';
            }
        })
        .catch(error => {
            console.error('Error al buscar clientes:', error);
            document.getElementById('editarResultadosClientes').innerHTML = '<div class="alert alert-danger">Error de conexión al buscar clientes</div>';
        });
}

// Función para seleccionar cliente
function seleccionarCliente(id, nombre) {
    document.getElementById('cliente_id').value = id;
    document.getElementById('buscarCliente').value = nombre;
    document.getElementById('resultadosClientes').innerHTML = '';
}

// Función para seleccionar cliente en el modal de edición
function seleccionarClienteEditar(id, nombre) {
    document.getElementById('editarClienteId').value = id;
    document.getElementById('editarBuscarCliente').value = nombre;
    document.getElementById('editarResultadosClientes').innerHTML = '';
}

// Función para habilitar cambio de cliente en el modal de edición
function habilitarCambioCliente() {
    const input = document.getElementById('editarBuscarCliente');
    input.readOnly = false;
    input.focus();
    input.select();
}

// Función para mostrar modal agregar cliente
function mostrarModalAgregarCliente() {
    document.getElementById('formAgregarClienteRapido').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarClienteRapido'));
    modal.show();
}

// Función para guardar cliente rápido
function guardarClienteRapido(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarClienteRapido');
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    fetch('index.php?action=agregarCliente', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                // Seleccionar el cliente recién creado
                seleccionarCliente(data.cliente_id, formData.get('nombre'));
                
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarClienteRapido'));
                modal.hide();
                
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Cliente agregado y seleccionado',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', data.message || 'Error al guardar cliente', 'error');
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            console.error('Response text:', text);
            Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar y Seleccionar';
    });
}

/**
 * Funciones para gestión de órdenes
 */

// Función para guardar orden
function guardarOrden(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarOrden');
    
    // Validar cliente seleccionado
    if (!document.getElementById('cliente_id').value) {
        Swal.fire('Error', 'Debe seleccionar un cliente', 'error');
        return;
    }
    
    // Validar pago inicial si está activo
    if (document.getElementById('registrarPagoInicial').checked) {
        if (!document.getElementById('costo_total_inicial').value) {
            Swal.fire('Error', 'Debe ingresar el costo total', 'error');
            return;
        }
        if (!document.getElementById('dinero_recibido_inicial').value) {
            Swal.fire('Error', 'Debe ingresar el monto recibido', 'error');
            return;
        }
        if (!document.getElementById('metodo_pago_inicial').value) {
            Swal.fire('Error', 'Debe seleccionar un método de pago', 'error');
            return;
        }
        
        // Agregar indicador de pago inicial
        formData.append('registrar_pago_inicial', '1');
    }
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    fetch('index.php?action=agregarOrden', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Orden de trabajo creada exitosamente',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Cerrar modal y recargar
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarOrden'));
                modal.hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', data.message || 'Error al guardar la orden', 'error');
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            console.error('Response text:', text);
            Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al guardar orden', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Orden';
    });
}

// Función para actualizar orden
function actualizarOrden(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnActualizarOrden');
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
    
    fetch('index.php?action=actualizarOrden', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Orden actualizada correctamente',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Cerrar modal y recargar
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarOrden'));
                modal.hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', data.message || 'Error al actualizar la orden', 'error');
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            console.error('Response text:', text);
            Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al actualizar orden', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Actualizar Orden';
    });
}

// Función para abrir modal editar orden
function abrirModalEditarOrden(id) {
    fetch(`index.php?action=obtenerOrden&id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success && data.orden) {
                    const orden = data.orden;
                    
                    // Llenar formulario con datos existentes
                    document.getElementById('editarOrdenId').value = orden.id;
                    document.getElementById('editarBuscarCliente').value = orden.cliente_nombre;
                    document.getElementById('editarClienteId').value = orden.cliente_id;
                    document.getElementById('editarMarca').value = orden.marca || '';
                    document.getElementById('editarModelo').value = orden.modelo || '';
                    document.getElementById('editarImeiSerial').value = orden.imei_serial || '';
                    document.getElementById('editarEstado').value = orden.estado || 'pendiente';
                    document.getElementById('editarPrioridad').value = orden.prioridad || 'media';
                    document.getElementById('editarFallaReportada').value = orden.falla_reportada || '';
                    document.getElementById('editarDiagnostico').value = orden.diagnostico || '';
                    document.getElementById('editarContrasenaEquipo').value = orden.contraseña_equipo || '';
                    
                    // Convertir fechas para input datetime-local
                    if (orden.fecha_ingreso) {
                        const fechaIngreso = new Date(orden.fecha_ingreso);
                        fechaIngreso.setMinutes(fechaIngreso.getMinutes() - fechaIngreso.getTimezoneOffset());
                        document.getElementById('editarFechaIngreso').value = fechaIngreso.toISOString().slice(0, 16);
                    }
                    
                    if (orden.fecha_entrega_estimada && orden.fecha_entrega_estimada !== '0000-00-00 00:00:00') {
                        const fechaEntrega = new Date(orden.fecha_entrega_estimada);
                        fechaEntrega.setMinutes(fechaEntrega.getMinutes() - fechaEntrega.getTimezoneOffset());
                        document.getElementById('editarFechaEntregaEstimada').value = fechaEntrega.toISOString().slice(0, 16);
                    }
                    
                    // Cargar técnicos y seleccionar el actual
                    cargarTecnicosEditar(orden.usuario_tecnico_id);
                    
                    // Mostrar imágenes actuales
                    mostrarImagenesActuales(orden.imagen_url);
                    
                    // Limpiar resultados de búsqueda
                    document.getElementById('editarResultadosClientes').innerHTML = '';
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalEditarOrden'));
                    modal.show();
                } else {
                    Swal.fire('Error', 'No se pudo cargar la información de la orden', 'error');
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
                Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al cargar la orden', 'error');
        });
}

// Función para mostrar imágenes actuales en el modal de edición
function mostrarImagenesActuales(imagenesUrl) {
    const contenedor = document.getElementById('imagenesActuales');
    contenedor.innerHTML = '';
    
    if (!imagenesUrl) return;
    
    const imagenes = imagenesUrl.split(',');
    imagenes.forEach(img => {
        if (!img.trim()) return;
        
        // Limpiar ruta - obtener solo el nombre del archivo
        const nombreArchivo = img.includes('/') ? img.split('/').pop() : img;
        
        // Crear elemento de imagen
        const imgElement = document.createElement('div');
        imgElement.className = 'position-relative';
        imgElement.innerHTML = `
            <img src="${img}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;" 
                 onclick="mostrarImagenCompleta('${nombreArchivo}')">
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                    onclick="confirmarEliminarImagen('${img}', ${document.getElementById('editarOrdenId').value})">
                <i class="fas fa-times"></i>
            </button>
        `;
        contenedor.appendChild(imgElement);
    });
}

// Función para ver detalles de orden
function verDetallesOrden(id) {
    // Primero obtenemos la información de la orden
    fetch(`index.php?action=obtenerOrden&id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(ordenText => {
            try {
                const ordenData = JSON.parse(ordenText);
                
                if (ordenData.success && ordenData.orden) {
                    const orden = ordenData.orden;
                    
                    // Ahora obtenemos los pagos de manera separada
                    fetch(`controllers/OrdenPagoController.php?accion=obtener&orden_id=${id}`)
                        .then(pagosResponse => pagosResponse.text())
                        .then(pagosText => {
                            let pagos = [];
                            
                            // Intentar parsear los pagos, pero continuar aunque falle
                            try {
                                console.log('Respuesta de pagos:', pagosText);
                                if (pagosText.trim()) {
                                    const pagosData = JSON.parse(pagosText);
                                    console.log('Datos parseados:', pagosData);
                                    if (pagosData.success && pagosData.pagos) {
                                        pagos = pagosData.pagos;
                                        console.log('Pagos obtenidos:', pagos);
                                    }
                                }
                            } catch (pagosError) {
                                console.warn('Error parsing pagos JSON:', pagosError);
                                console.warn('Pagos response text:', pagosText);
                                // Continuar con array vacío de pagos
                            }
                            
                            // Calcular totales
                            const totalPagado = pagos.reduce((sum, pago) => sum + parseFloat(pago.dinero_recibido || 0), 0);
                            const costoTotal = parseFloat(orden.costo_total || 0);
                            const saldoPendiente = costoTotal - totalPagado;
                            
                            console.log('Cálculos financieros:');
                            console.log('- Costo total:', costoTotal);
                            console.log('- Total pagado:', totalPagado);
                            console.log('- Saldo pendiente:', saldoPendiente);
                            console.log('- Número de pagos:', pagos.length);
                            
                            // Construir el HTML con los detalles de la orden
                            let detallesHTML = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Orden #${orden.id}</h5>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold">Información del Cliente</h6>
                                                <p><strong>Cliente:</strong> ${orden.cliente_nombre || 'No especificado'}</p>
                                                <p><strong>Identificación:</strong> ${orden.cliente_identificacion || 'No especificado'}</p>
                                                <p><strong>Teléfono:</strong> ${orden.cliente_telefono || 'No especificado'}</p>
                                                <p><strong>Email:</strong> ${orden.cliente_email || 'No especificado'}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold">Información del Equipo</h6>
                                                <p><strong>Marca:</strong> ${orden.marca || 'No especificado'}</p>
                                                <p><strong>Modelo:</strong> ${orden.modelo || 'No especificado'}</p>
                                                <p><strong>IMEI/Serial:</strong> ${orden.imei_serial || 'No especificado'}</p>
                                                <p><strong>Contraseña:</strong> ${orden.contraseña_equipo || 'No especificado'}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold">Estado y Prioridad</h6>
                                                <p><strong>Estado:</strong> <span class="estado-${orden.estado || 'pendiente'}">${orden.estado ? orden.estado.charAt(0).toUpperCase() + orden.estado.slice(1) : 'Pendiente'}</span></p>
                                                <p><strong>Prioridad:</strong> <span class="prioridad-${orden.prioridad || 'media'}">${orden.prioridad ? orden.prioridad.charAt(0).toUpperCase() + orden.prioridad.slice(1) : 'Media'}</span></p>
                                                <p><strong>Técnico:</strong> ${orden.tecnico_nombre || 'No asignado'}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold">Fechas</h6>
                                                <p><strong>Fecha de Ingreso:</strong> ${new Date(orden.fecha_ingreso).toLocaleDateString('es-CO')}</p>
                                                <p><strong>Fecha Estimada de Entrega:</strong> ${orden.fecha_entrega_estimada && orden.fecha_entrega_estimada !== '0000-00-00 00:00:00' ? new Date(orden.fecha_entrega_estimada).toLocaleDateString('es-CO') : 'No especificado'}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Nueva sección de información financiera -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold">Información Financiera</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="card bg-primary text-white">
                                                            <div class="card-body text-center">
                                                                <h6 class="card-title">Costo Total</h6>
                                                                <h4 class="mb-0">$${costoTotal.toLocaleString('es-CO')}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card bg-success text-white">
                                                            <div class="card-body text-center">
                                                                <h6 class="card-title">Total Pagado</h6>
                                                                <h4 class="mb-0">$${totalPagado.toLocaleString('es-CO')}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card ${saldoPendiente > 0 ? 'bg-warning' : 'bg-info'} text-white">
                                                            <div class="card-body text-center">
                                                                <h6 class="card-title">Saldo Pendiente</h6>
                                                                <h4 class="mb-0">$${saldoPendiente.toLocaleString('es-CO')}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Historial de pagos -->
                                        ${pagos.length > 0 ? `
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold">Historial de Pagos</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>Fecha</th>
                                                                <th>Monto</th>
                                                                <th>Método</th>
                                                                <th>Observaciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            ${pagos.map(pago => `
                                                            <tr>
                                                                <td>${new Date(pago.fecha_pago).toLocaleDateString('es-CO')}</td>
                                                                <td class="text-end fw-bold text-success">$${parseFloat(pago.dinero_recibido).toLocaleString('es-CO')}</td>
                                                                <td><span class="badge bg-info">${pago.metodo_pago || 'No especificado'}</span></td>
                                                                <td>${pago.observaciones || 'Sin observaciones'}</td>
                                                            </tr>
                                                            `).join('')}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        ` : `
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold">Historial de Pagos</h6>
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>No hay pagos registrados para esta orden.
                                                </div>
                                            </div>
                                        </div>
                                        `}
                                        
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold">Falla Reportada</h6>
                                                <div class="p-3 bg-light rounded">${orden.falla_reportada || 'No especificado'}</div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold">Diagnóstico</h6>
                                                <div class="p-3 bg-light rounded">${orden.diagnostico || 'No especificado'}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            // Mostrar imágenes si hay
                            if (orden.imagen_url) {
                                const imagenes = orden.imagen_url.split(',');
                                if (imagenes.length > 0 && imagenes[0].trim()) {
                                    detallesHTML += '<div class="mt-4"><h6 class="fw-bold">Imágenes</h6><div class="d-flex flex-wrap gap-2">';
                                    imagenes.forEach(img => {
                                        if (img.trim()) {
                                            detallesHTML += `
                                                <img src="${img}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover; cursor: pointer" 
                                                     onclick="mostrarImagenCompleta('${img.includes('/') ? img.split('/').pop() : img}')">
                                            `;
                                        }
                                    });
                                    detallesHTML += '</div></div>';
                                }
                            }
                            
                            // Cargar los detalles en el modal
                            document.getElementById('contenidoDetallesOrden').innerHTML = detallesHTML;
                            
                            // Mostrar el modal
                            const modal = new bootstrap.Modal(document.getElementById('modalDetallesOrden'));
                            modal.show();
                        })
                        .catch(pagosError => {
                            console.warn('Error al cargar pagos:', pagosError);
                            // Continuar mostrando la orden sin información de pagos
                            mostrarOrdenSinPagos(orden);
                        });
                } else {
                    Swal.fire('Error', 'No se pudo cargar la información de la orden', 'error');
                }
            } catch (e) {
                console.error('Error parsing orden JSON:', e);
                console.error('Response text:', ordenText);
                Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al cargar la orden', 'error');
        });
}

// Función auxiliar para mostrar orden sin información de pagos
function mostrarOrdenSinPagos(orden) {
    const costoTotal = parseFloat(orden.costo_total || 0);
    
    let detallesHTML = `
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Orden #${orden.id}</h5>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Información del Cliente</h6>
                        <p><strong>Cliente:</strong> ${orden.cliente_nombre || 'No especificado'}</p>
                        <p><strong>Identificación:</strong> ${orden.cliente_identificacion || 'No especificado'}</p>
                        <p><strong>Teléfono:</strong> ${orden.cliente_telefono || 'No especificado'}</p>
                        <p><strong>Email:</strong> ${orden.cliente_email || 'No especificado'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Información del Equipo</h6>
                        <p><strong>Marca:</strong> ${orden.marca || 'No especificado'}</p>
                        <p><strong>Modelo:</strong> ${orden.modelo || 'No especificado'}</p>
                        <p><strong>IMEI/Serial:</strong> ${orden.imei_serial || 'No especificado'}</p>
                        <p><strong>Contraseña:</strong> ${orden.contraseña_equipo || 'No especificado'}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Estado y Prioridad</h6>
                        <p><strong>Estado:</strong> <span class="estado-${orden.estado || 'pendiente'}">${orden.estado ? orden.estado.charAt(0).toUpperCase() + orden.estado.slice(1) : 'Pendiente'}</span></p>
                        <p><strong>Prioridad:</strong> <span class="prioridad-${orden.prioridad || 'media'}">${orden.prioridad ? orden.prioridad.charAt(0).toUpperCase() + orden.prioridad.slice(1) : 'Media'}</span></p>
                        <p><strong>Técnico:</strong> ${orden.tecnico_nombre || 'No asignado'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Fechas</h6>
                        <p><strong>Fecha de Ingreso:</strong> ${new Date(orden.fecha_ingreso).toLocaleDateString('es-CO')}</p>
                        <p><strong>Fecha Estimada de Entrega:</strong> ${orden.fecha_entrega_estimada && orden.fecha_entrega_estimada !== '0000-00-00 00:00:00' ? new Date(orden.fecha_entrega_estimada).toLocaleDateString('es-CO') : 'No especificado'}</p>
                    </div>
                </div>
                
                <!-- Información financiera básica -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold">Información Financiera</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Costo Total</h6>
                                        <h4 class="mb-0">$${costoTotal.toLocaleString('es-CO')}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>No se pudo cargar la información detallada de pagos.
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold">Falla Reportada</h6>
                        <div class="p-3 bg-light rounded">${orden.falla_reportada || 'No especificado'}</div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold">Diagnóstico</h6>
                        <div class="p-3 bg-light rounded">${orden.diagnostico || 'No especificado'}</div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Mostrar imágenes si hay
    if (orden.imagen_url) {
        const imagenes = orden.imagen_url.split(',');
        if (imagenes.length > 0 && imagenes[0].trim()) {
            detallesHTML += '<div class="mt-4"><h6 class="fw-bold">Imágenes</h6><div class="d-flex flex-wrap gap-2">';
            imagenes.forEach(img => {
                if (img.trim()) {
                    detallesHTML += `
                        <img src="${img}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover; cursor: pointer" 
                             onclick="mostrarImagenCompleta('${img.includes('/') ? img.split('/').pop() : img}')">
                    `;
                }
            });
            detallesHTML += '</div></div>';
        }
    }
    
    // Cargar los detalles en el modal
    document.getElementById('contenidoDetallesOrden').innerHTML = detallesHTML;
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetallesOrden'));
    modal.show();
}

// Función para eliminar orden
function confirmarEliminacionOrden(event, id) {
    event.stopPropagation();
    
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción no se puede revertir. Si la orden tiene pagos asociados, debe eliminarlos primero.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarOrden(id);
        }
    });
}

function eliminarOrden(id) {
    fetch(`index.php?action=eliminarOrden&id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: 'La orden ha sido eliminada.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Error', data.message || 'No se pudo eliminar la orden.', 'error');
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
                Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al eliminar la orden', 'error');
        });
}

// Función para imprimir orden
function imprimirOrden(id) {
    if (!id && document.getElementById('modalDetallesOrden').classList.contains('show')) {
        // Obtener ID de la orden del modal de detalles
        const url = document.getElementById('contenidoDetallesOrden').querySelector('.card-title').textContent;
        id = url.match(/\d+/)[0];
    }
    
    if (id) {
        window.open(`index.php?action=generarRemision&id=${id}`, '_blank');
    } else {
        Swal.fire('Error', 'No se pudo identificar la orden para imprimir', 'error');
    }
}

/**
 * Funciones para filtrar y exportar órdenes
 */

// Función para filtrar órdenes
function filtrarOrdenes() {
    const buscar = document.getElementById('buscadorOrdenes').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    const prioridad = document.getElementById('filtroPrioridad').value.toLowerCase();
    const filas = document.querySelectorAll('#bodyTablaOrdenes tr');
    
    filas.forEach(function(fila) {
        const textoFila = fila.textContent.toLowerCase();
        const estadoFila = fila.querySelector('.estado-pendiente, .estado-en-proceso, .estado-terminado, .estado-entregado');
        const prioridadFila = fila.querySelector('.prioridad-baja, .prioridad-media, .prioridad-alta');
        
        let mostrar = true;
        
        // Filtro de búsqueda
        if (buscar && !textoFila.includes(buscar)) {
            mostrar = false;
        }
        
        // Filtro de estado
        if (estado && estadoFila && !estadoFila.className.includes(`estado-${estado}`)) {
            mostrar = false;
        }
        
        // Filtro de prioridad
        if (prioridad && prioridadFila && !prioridadFila.className.includes(`prioridad-${prioridad}`)) {
            mostrar = false;
        }
        
        fila.style.display = mostrar ? '' : 'none';
    });
}

// Función para limpiar filtros
function limpiarFiltros() {
    document.getElementById('buscadorOrdenes').value = '';
    document.getElementById('filtroEstado').value = '';
    document.getElementById('filtroPrioridad').value = '';
    filtrarOrdenes();
}

// Función para filtrar por estado específico
function filtrarPorEstado(estado) {
    document.getElementById('filtroEstado').value = estado;
    filtrarOrdenes();
}

// Función para filtrar por prioridad específica
function filtrarPorPrioridad(prioridad) {
    document.getElementById('filtroPrioridad').value = prioridad;
    filtrarOrdenes();
}

// Función para exportar órdenes
function exportarOrdenes() {
    Swal.fire({
        title: 'Exportar Órdenes',
        text: 'Funcionalidad de exportación en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

/**
 * Funciones para manejo de imágenes
 */

// Función para mostrar imagen completa
function mostrarImagenCompleta(imagen) {
    const imagenCompleta = document.getElementById('imagenCompleta');
    const descargarImagen = document.getElementById('descargarImagen');
    
    // Construir la ruta completa
    const rutaImagen = imagen.includes('assets/img/') ? imagen : `assets/img/${imagen}`;
    
    imagenCompleta.src = rutaImagen;
    descargarImagen.href = rutaImagen;
    descargarImagen.download = imagen.replace('assets/img/', '');
    
    const modal = new bootstrap.Modal(document.getElementById('modalImagenCompleta'));
    modal.show();
}

// Función para confirmar eliminación de imagen
function confirmarEliminarImagen(ruta, ordenId) {
    Swal.fire({
        title: '¿Eliminar imagen?',
        text: "Esta acción no se puede revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarImagen(ruta, ordenId);
        }
    });
}

// Función para eliminar imagen
function eliminarImagen(ruta, ordenId) {
    fetch('index.php?action=eliminarImagenOrden', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `ruta=${encodeURIComponent(ruta)}&orden_id=${ordenId}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                Swal.fire({
                    title: '¡Eliminada!',
                    text: 'La imagen ha sido eliminada.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Actualizar la vista de imágenes
                if (data.imagenes_restantes) {
                    mostrarImagenesActuales(data.imagenes_restantes);
                } else {
                    document.getElementById('imagenesActuales').innerHTML = '';
                }
            } else {
                Swal.fire('Error', data.message || 'No se pudo eliminar la imagen.', 'error');
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            console.error('Response text:', text);
            Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al eliminar la imagen', 'error');
    });
}

/**
 * Funciones para gestión de pagos
 */

// Función para mostrar/ocultar la sección de pago inicial
function togglePagoInicial() {
    const seccionPago = document.getElementById('seccionPagoInicial');
    const checkbox = document.getElementById('registrarPagoInicial');
    
    if (checkbox.checked) {
        seccionPago.style.display = 'block';
        // Establecer la fecha actual para el pago
        const fechaActual = new Date();
        fechaActual.setMinutes(fechaActual.getMinutes() - fechaActual.getTimezoneOffset());
        
        // Validar campos obligatorios para el pago
        document.getElementById('costo_total_inicial').setAttribute('required', 'required');
        document.getElementById('dinero_recibido_inicial').setAttribute('required', 'required');
        document.getElementById('metodo_pago_inicial').setAttribute('required', 'required');
    } else {
        seccionPago.style.display = 'none';
        // Quitar validación obligatoria cuando no se registra pago
        document.getElementById('costo_total_inicial').removeAttribute('required');
        document.getElementById('dinero_recibido_inicial').removeAttribute('required');
        document.getElementById('metodo_pago_inicial').removeAttribute('required');
    }
}

// Función para calcular el saldo pendiente en la creación inicial
function calcularSaldoInicial() {
    const costoTotal = parseFloat(document.getElementById('costo_total_inicial').value) || 0;
    const dineroRecibido = parseFloat(document.getElementById('dinero_recibido_inicial').value) || 0;
    const saldo = Math.max(0, costoTotal - dineroRecibido);
    
    document.getElementById('saldo_pendiente_inicial').textContent = saldo.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('saldo_inicial').value = saldo;
}

// Función para abrir modal de pagos
function abrirModalPagos() {
    const ordenId = document.getElementById('editarOrdenId').value;
    if (!ordenId) {
        Swal.fire('Error', 'No se pudo identificar la orden', 'error');
        return;
    }
    
    // Establecer la fecha actual en el formulario de pago
    const fechaActual = new Date();
    fechaActual.setMinutes(fechaActual.getMinutes() - fechaActual.getTimezoneOffset());
    document.getElementById('fecha_pago').value = fechaActual.toISOString().slice(0, 16);
    
    // Obtener datos de la orden
    fetch(`index.php?action=obtenerOrden&id=${ordenId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success && data.orden) {
                    const orden = data.orden;
                    
                    // Establecer los datos de la orden en el modal
                    document.getElementById('pagoOrdenId').value = orden.id;
                    document.getElementById('pago_orden_id').value = orden.id;
                    
                    // Intentar obtener el ID de usuario de la sesión actual o usar el técnico asignado
                    fetch('index.php?action=getUserId')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.usuario_id) {
                                document.getElementById('pago_usuario_id').value = data.usuario_id;
                            } else {
                                // Si no se puede obtener el ID de usuario, usar el técnico asignado o 1 por defecto
                                document.getElementById('pago_usuario_id').value = orden.usuario_tecnico_id || '1';
                            }
                        })
                        .catch(err => {
                            console.error("Error al obtener ID de usuario:", err);
                            document.getElementById('pago_usuario_id').value = orden.usuario_tecnico_id || '1';
                        });
                    
                    document.getElementById('pagosNumeroOrden').textContent = orden.id;
                    document.getElementById('pagosNombreCliente').textContent = orden.cliente_nombre || 'Sin nombre';
                    document.getElementById('pagosMarcaModelo').textContent = `${orden.marca || ''} ${orden.modelo || ''}`.trim() || 'No especificado';
                    
                    // Cargar historial de pagos
                    cargarHistorialPagos(orden.id);
                    
                    // Agregar event listeners para cálculo automático en el modal de gestión
                    const costoTotalInput = document.getElementById('costo_total');
                    const dineroRecibidoInput = document.getElementById('dinero_recibido');
                    
                    if (costoTotalInput) {
                        costoTotalInput.removeEventListener('input', calcularSaldoGestionPagos);
                        costoTotalInput.addEventListener('input', calcularSaldoGestionPagos);
                    }
                    if (dineroRecibidoInput) {
                        dineroRecibidoInput.removeEventListener('input', calcularSaldoGestionPagos);
                        dineroRecibidoInput.addEventListener('input', calcularSaldoGestionPagos);
                    }
                    
                    // Mostrar el modal
                    const modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditarOrden'));
                    if (modalEditar) {
                        modalEditar.hide();
                    }
                    
                    const modalPagos = new bootstrap.Modal(document.getElementById('modalGestionarPagos'));
                    modalPagos.show();
                } else {
                    Swal.fire('Error', 'No se pudo cargar la información de la orden', 'error');
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
                Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al cargar la orden', 'error');
        });
}

// Función para abrir modal de pagos directamente desde la lista (sin modal de editar)
function abrirModalPagosDirecto(ordenId) {
    if (!ordenId) {
        Swal.fire('Error', 'No se pudo identificar la orden', 'error');
        return;
    }
    
    // Establecer la fecha actual en el formulario de pago
    const fechaActual = new Date();
    fechaActual.setMinutes(fechaActual.getMinutes() - fechaActual.getTimezoneOffset());
    document.getElementById('fecha_pago').value = fechaActual.toISOString().slice(0, 16);
    
    // Obtener datos de la orden
    fetch(`index.php?action=obtenerOrden&id=${ordenId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success && data.orden) {
                    const orden = data.orden;
                    
                    // Establecer los datos de la orden en el modal
                    document.getElementById('pagoOrdenId').value = orden.id;
                    document.getElementById('pago_orden_id').value = orden.id;
                    
                    // Intentar obtener el ID de usuario de la sesión actual o usar el técnico asignado
                    fetch('index.php?action=getUserId')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.usuario_id) {
                                document.getElementById('pago_usuario_id').value = data.usuario_id;
                            } else {
                                // Si no se puede obtener el ID de usuario, usar el técnico asignado o 1 por defecto
                                document.getElementById('pago_usuario_id').value = orden.usuario_tecnico_id || '1';
                            }
                        })
                        .catch(err => {
                            console.error("Error al obtener ID de usuario:", err);
                            document.getElementById('pago_usuario_id').value = orden.usuario_tecnico_id || '1';
                        });
                    
                    document.getElementById('pagosNumeroOrden').textContent = orden.id;
                    document.getElementById('pagosNombreCliente').textContent = orden.cliente_nombre || 'Sin nombre';
                    document.getElementById('pagosMarcaModelo').textContent = `${orden.marca || ''} ${orden.modelo || ''}`.trim() || 'No especificado';
                    
                    // Cargar historial de pagos
                    cargarHistorialPagos(orden.id);
                    
                    // Agregar event listeners para cálculo automático en el modal de gestión
                    const costoTotalInput = document.getElementById('costo_total');
                    const dineroRecibidoInput = document.getElementById('dinero_recibido');
                    
                    if (costoTotalInput) {
                        costoTotalInput.removeEventListener('input', calcularSaldoGestionPagos);
                        costoTotalInput.addEventListener('input', calcularSaldoGestionPagos);
                    }
                    if (dineroRecibidoInput) {
                        dineroRecibidoInput.removeEventListener('input', calcularSaldoGestionPagos);
                        dineroRecibidoInput.addEventListener('input', calcularSaldoGestionPagos);
                    }
                    
                    // Mostrar el modal de pagos directamente
                    const modalPagos = new bootstrap.Modal(document.getElementById('modalGestionarPagos'));
                    modalPagos.show();
                } else {
                    Swal.fire('Error', 'No se pudo cargar la información de la orden', 'error');
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
                Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al cargar la orden', 'error');
        });
}

// Función para cargar historial de pagos
function cargarHistorialPagos(ordenId) {
    fetch(`controllers/OrdenPagoController.php?accion=obtener&orden_id=${ordenId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            const pagos = JSON.parse(text);
            const historialElement = document.getElementById('historialPagos');
            
            // Calcular totales
            let totalPagado = 0;
            let ultimoSaldo = 0;
            
            if (pagos.length > 0) {
                let html = '';
                pagos.forEach(pago => {
                    // Formatear fecha
                    const fechaPago = new Date(pago.fecha_pago);
                    const fechaFormateada = fechaPago.toLocaleDateString('es-CO') + ' ' + 
                                          fechaPago.toLocaleTimeString('es-CO', {hour: '2-digit', minute:'2-digit'});
                    
                    // Acumular monto pagado
                    totalPagado += parseFloat(pago.dinero_recibido);
                    ultimoSaldo = parseFloat(pago.saldo);
                    
                    // Método de pago formateado
                    let metodoPago = pago.metodo_pago.charAt(0).toUpperCase() + pago.metodo_pago.slice(1);
                    
                    html += `
                        <tr>
                            <td>${fechaFormateada}</td>
                            <td>$${parseFloat(pago.dinero_recibido).toLocaleString('es-CO')}</td>
                            <td>${metodoPago}</td>
                            <td>$${parseFloat(pago.valor_repuestos).toLocaleString('es-CO')}</td>
                            <td>$${parseFloat(pago.saldo).toLocaleString('es-CO')}</td>
                        </tr>
                    `;
                });
                historialElement.innerHTML = html;
                
                // Actualizar resumen financiero
                document.getElementById('pagosCostoTotal').textContent = parseFloat(pagos[0].costo_total).toLocaleString('es-CO');
                document.getElementById('pagosMontoPagado').textContent = totalPagado.toLocaleString('es-CO');
                document.getElementById('pagosSaldoPendiente').textContent = ultimoSaldo.toLocaleString('es-CO');
                
                // Pre-llenar valores en el formulario de nuevo pago
                document.getElementById('costo_total').value = pagos[0].costo_total;
                // El saldo debe ser el saldo pendiente (último saldo registrado)
                document.getElementById('saldo').value = ultimoSaldo;
            } else {
                historialElement.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <i class="fas fa-info-circle text-muted me-2"></i>
                            No hay pagos registrados para esta orden
                        </td>
                    </tr>
                `;
                
                // Si no hay pagos, inicializar valores en cero
                document.getElementById('pagosCostoTotal').textContent = '0.00';
                document.getElementById('pagosMontoPagado').textContent = '0.00';
                document.getElementById('pagosSaldoPendiente').textContent = '0.00';
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            console.error('Response text:', text);
            document.getElementById('historialPagos').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-3 text-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error al cargar historial de pagos
                    </td>
                </tr>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('historialPagos').innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-3 text-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error de conexión al cargar pagos
                </td>
            </tr>
        `;
    });
}

// Función para calcular el saldo automáticamente (ORIGINAL - para creación de orden)
function calcularSaldo() {
    // Obtener el saldo actual del campo (que ya viene pre-cargado)
    const saldoActual = parseFloat(document.getElementById('saldo').value) || 0;
    const dineroRecibido = parseFloat(document.getElementById('dinero_recibido').value) || 0;
    
    // El nuevo saldo es el saldo actual menos lo que se está pagando ahora
    const nuevoSaldo = Math.max(0, saldoActual - dineroRecibido);
    
    document.getElementById('saldo').value = nuevoSaldo.toFixed(2);
}

// Función para calcular el saldo en el modal de gestión de pagos
function calcularSaldoGestionPagos() {
    const costoTotal = parseFloat(document.getElementById('costo_total').value) || 0;
    const dineroRecibido = parseFloat(document.getElementById('dinero_recibido').value) || 0;
    
    // Obtener el total ya pagado anteriormente
    const totalPagadoElement = document.getElementById('pagosTotalPagado');
    const totalPagadoAnterior = totalPagadoElement ? 
        parseFloat(totalPagadoElement.textContent.replace(/[$,]/g, '')) || 0 : 0;
    
    // Calcular el nuevo saldo: costo total - pagos anteriores - pago actual
    const nuevoSaldo = Math.max(0, costoTotal - totalPagadoAnterior - dineroRecibido);
    
    document.getElementById('saldo').value = nuevoSaldo.toFixed(2);
}

// Función para guardar un nuevo pago
function guardarPago(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarPago');
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    fetch('controllers/OrdenPagoController.php?accion=insertar', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Pago registrado correctamente',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Recargar historial de pagos
                cargarHistorialPagos(document.getElementById('pagoOrdenId').value);
                
                // Limpiar formulario pero mantener la orden y usuario
                const ordenId = document.getElementById('pago_orden_id').value;
                const usuarioId = document.getElementById('pago_usuario_id').value;
                document.getElementById('formAgregarPago').reset();
                document.getElementById('pago_orden_id').value = ordenId;
                document.getElementById('pago_usuario_id').value = usuarioId;
                
                // Actualizar fecha
                const fechaActual = new Date();
                fechaActual.setMinutes(fechaActual.getMinutes() - fechaActual.getTimezoneOffset());
                document.getElementById('fecha_pago').value = fechaActual.toISOString().slice(0, 16);
            } else {
                Swal.fire('Error', data.message || 'Error al registrar el pago', 'error');
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            console.error('Response text:', text);
            Swal.fire('Error', 'Error al procesar la respuesta del servidor', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al guardar pago', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Registrar Pago';
    });
}

// Función para cargar saldos de todas las órdenes en la tabla
function cargarSaldosTabla() {
    const filasOrden = document.querySelectorAll('[id^="saldo-orden-"]');
    
    filasOrden.forEach(elemento => {
        const ordenId = elemento.id.replace('saldo-orden-', '');
        
        // Obtener el último saldo de la orden
        fetch(`controllers/OrdenPagoController.php?accion=obtener&orden_id=${ordenId}`)
            .then(response => response.json())
            .then(respuesta => {
                let saldoTexto;
                let claseColor;
                // Adaptar a la estructura {success, pagos}
                if (respuesta && respuesta.success && Array.isArray(respuesta.pagos) && respuesta.pagos.length > 0) {
                    const ultimoSaldo = parseFloat(respuesta.pagos[0].saldo);
                    if (ultimoSaldo === 0) {
                        saldoTexto = '<i class="fas fa-check-circle me-1"></i>Pagado';
                        claseColor = 'text-success';
                    } else {
                        saldoTexto = '$' + ultimoSaldo.toLocaleString('es-CO');
                        claseColor = ultimoSaldo > 0 ? 'text-danger' : 'text-success';
                    }
                } else {
                    // Si no hay pagos, obtener el costo total de la orden
                    fetch(`index.php?action=obtenerOrden&id=${ordenId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.orden) {
                                const costoTotal = parseFloat(data.orden.costo_total) || 0;
                                if (costoTotal > 0) {
                                    elemento.innerHTML = `<span class="text-danger">$${costoTotal.toLocaleString('es-CO')}</span>`;
                                } else {
                                    elemento.innerHTML = '<span class="text-muted">Sin costo</span>';
                                }
                            } else {
                                elemento.innerHTML = '<span class="text-muted">N/A</span>';
                            }
                        })
                        .catch(() => {
                            elemento.innerHTML = '<span class="text-muted">Error</span>';
                        });
                    return;
                }
                elemento.innerHTML = `<span class="${claseColor}">${saldoTexto}</span>`;
            })
            .catch(error => {
                console.error('Error al cargar saldo para orden', ordenId, ':', error);
                elemento.innerHTML = '<span class="text-muted">Error</span>';
            });
    });
}

// Cargar saldos al iniciar
document.addEventListener('DOMContentLoaded', cargarSaldosTabla);
