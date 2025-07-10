<?php
require_once 'header.php';
?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.dashboard-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 25%, #4a4a4a 50%, #6a6a6a 75%, #8a8a8a 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.welcome-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    text-align: center;
    color: #212529;
    border: 2px solid #ced4da;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.content-section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin: 20px 0;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.section-title {
    color: #333;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 3px solid #4a6fa5;
    padding-bottom: 10px;
}

.search-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    border: 2px solid #dee2e6;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 20px;
}

.table th {
    background-color: #000000;
    color: white;
    border: none;
    font-weight: 500;
    padding: 15px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 12px 15px;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.btn-action {
    margin: 2px;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: none;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    color: white;
}

.floating-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.floating-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.35);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
    padding: 20px 25px;
}

.modal-header .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.modal-header .btn-close:hover {
    opacity: 1;
}

.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.2);
    overflow: hidden;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    background: #f8f9fa;
    border: none;
    padding: 20px 25px;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px 0 0 10px;
}

/* Estilos para truncar texto */
.email-truncado {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
}

.nombre-truncado {
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
}

/* Estilos adicionales para avatar y mejoras visuales */
.avatar-circle {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .content-section {
        padding: 20px;
        margin: 15px 0;
    }
    
    .search-box {
        padding: 15px;
    }
    
    .table th, .table td {
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .welcome-header {
        padding: 20px;
    }
    
    .content-section {
        padding: 15px;
    }
}
</style>

<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Header de bienvenida -->
        <div class="welcome-header">
            <h1><i class="fas fa-users me-3"></i>Gestión de Clientes</h1>
            <p class="mb-0">Centro de Control de Información de Clientes</p>
            <small id="fechaActual"></small>
        </div>
        
        <!-- Controles superiores -->
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Clientes
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-info" onclick="exportarClientes()" title="Exportar clientes">
                        <i class="fas fa-download me-2"></i>Exportar
                    </button>
                </div>
            </div>

            <!-- Buscador mejorado -->
            <div class="search-box">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-search me-2"></i>Buscar cliente
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="buscadorClientes" 
                                   placeholder="Nombre, identificación, teléfono, email o dirección..." 
                                   onkeyup="filtrarClientes()">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                            <i class="fas fa-redo me-2"></i>Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de clientes -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaClientes">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>ID</th>
                            <th><i class="fas fa-user me-2"></i>Nombre</th>
                            <th><i class="fas fa-id-card me-2"></i>Identificación</th>
                            <th><i class="fas fa-phone me-2"></i>Teléfono</th>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                            <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaClientes">
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td class="fw-bold text-primary"><?php echo $cliente['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="nombre-truncado fw-bold" title="<?php echo htmlspecialchars($cliente['nombre']); ?>">
                                            <?php echo $cliente['nombre']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td><?php echo $cliente['identificacion']; ?></td>
                                <td class="text-success fw-bold"><?php echo $cliente['telefono']; ?></td>
                                <td>
                                    <span class="email-truncado text-info" title="<?php echo htmlspecialchars($cliente['email']); ?>">
                                        <?php echo $cliente['email']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary btn-action" 
                                                onclick="abrirModalEditar(<?php echo $cliente['id']; ?>)" 
                                                title="Editar cliente">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="tel:<?php echo $cliente['telefono']; ?>" 
                                           class="btn btn-sm btn-outline-success btn-action" 
                                           title="Llamar">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                        <a href="mailto:<?php echo $cliente['email']; ?>" 
                                           class="btn btn-sm btn-outline-info btn-action" 
                                           title="Enviar email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        <a href="https://wa.me/<?php echo $cliente['telefono']; ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-success btn-action" 
                                           title="WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger btn-action" 
                                                onclick="confirmarEliminacion(event, <?php echo $cliente['id']; ?>)" 
                                                title="Eliminar cliente">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Botón flotante para agregar cliente -->
<button class="floating-btn" onclick="mostrarModalAgregarCliente()" title="Agregar nuevo cliente">
    <i class="fas fa-plus"></i>
</button>

<!-- Modal para agregar cliente -->
<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarClienteLabel">
                    <i class="fas fa-user-plus me-2"></i>Agregar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAgregarCliente">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Nombre Completo *
                            </label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="identificacion" class="form-label fw-bold">
                                <i class="fas fa-id-card me-2"></i>Identificación *
                            </label>
                            <input type="text" class="form-control" id="identificacion" name="identificacion" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label fw-bold">
                                <i class="fas fa-phone me-2"></i>Teléfono
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt me-2"></i>Dirección
                        </label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3" 
                                  placeholder="Dirección completa del cliente"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient" id="btnGuardarCliente">
                        <i class="fas fa-save me-2"></i>Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarClienteLabel">
                    <i class="fas fa-user-edit me-2"></i>Editar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarCliente">
                <div class="modal-body">
                    <input type="hidden" id="editarId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editarNombre" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Nombre Completo *
                            </label>
                            <input type="text" class="form-control" id="editarNombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editarIdentificacion" class="form-label fw-bold">
                                <i class="fas fa-id-card me-2"></i>Identificación *
                            </label>
                            <input type="text" class="form-control" id="editarIdentificacion" name="identificacion" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editarTelefono" class="form-label fw-bold">
                                <i class="fas fa-phone me-2"></i>Teléfono
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" class="form-control" id="editarTelefono" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editarEmail" class="form-label fw-bold">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="editarEmail" name="email">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editarDireccion" class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt me-2"></i>Dirección
                        </label>
                        <textarea class="form-control" id="editarDireccion" name="direccion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient" id="btnActualizarCliente">
                        <i class="fas fa-save me-2"></i>Actualizar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variables globales
let clientes = [];

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners para formularios
    document.getElementById('formAgregarCliente').addEventListener('submit', guardarCliente);
    document.getElementById('formEditarCliente').addEventListener('submit', actualizarCliente);
    
    // Actualizar fecha
    document.getElementById('fechaActual').textContent = new Date().toLocaleDateString('es-CO', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

// Función para mostrar modal agregar cliente
function mostrarModalAgregarCliente() {
    document.getElementById('formAgregarCliente').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarCliente'));
    modal.show();
}

// Función para guardar cliente
function guardarCliente(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnGuardarCliente');
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    
    fetch('index.php?action=agregarCliente', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Cliente agregado exitosamente',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Cerrar modal y recargar
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarCliente'));
            modal.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al guardar cliente', 'error');
    })
    .finally(() => {
        // Restaurar botón
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Cliente';
    });
}

// Función para abrir modal editar
function abrirModalEditar(id) {
    fetch(`index.php?action=obtenerCliente&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cliente = data.cliente;
                document.getElementById('editarId').value = cliente.id;
                document.getElementById('editarNombre').value = cliente.nombre;
                document.getElementById('editarIdentificacion').value = cliente.identificacion;
                document.getElementById('editarTelefono').value = cliente.telefono;
                document.getElementById('editarEmail').value = cliente.email;
                document.getElementById('editarDireccion').value = cliente.direccion;

                const modal = new bootstrap.Modal(document.getElementById('modalEditarCliente'));
                modal.show();
            } else {
                Swal.fire('Error', 'Error al cargar datos del cliente', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión', 'error');
        });
}

// Función para actualizar cliente
function actualizarCliente(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const btn = document.getElementById('btnActualizarCliente');
    
    // Deshabilitar botón
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
    
    fetch('index.php?action=editarCliente', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Cliente actualizado exitosamente',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Cerrar modal y recargar
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarCliente'));
            modal.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al actualizar cliente', 'error');
    })
    .finally(() => {
        // Restaurar botón
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Actualizar Cliente';
    });
}

// Función para confirmar eliminación
function confirmarEliminacion(event, id) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará el cliente permanentemente',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`index.php?action=eliminarCliente&id=${id}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: 'Cliente eliminado exitosamente',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error de conexión al eliminar cliente', 'error');
            });
        }
    });
}

// Función para filtrar clientes
function filtrarClientes() {
    const filtro = document.getElementById('buscadorClientes').value.toLowerCase();
    const filas = document.querySelectorAll('#bodyTablaClientes tr');
    
    filas.forEach(function(fila) {
        let textoFila = '';
        fila.querySelectorAll('td').forEach(function(td) {
            textoFila += (td.textContent || '').toLowerCase() + ' ';
        });
        
        if (textoFila.includes(filtro)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// Función para limpiar filtros
function limpiarFiltros() {
    document.getElementById('buscadorClientes').value = '';
    filtrarClientes();
}

// Función para exportar clientes
function exportarClientes() {
    Swal.fire({
        title: 'Exportar Clientes',
        text: 'Funcionalidad de exportación en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}
</script>

<?php
require_once 'footer.php';
?>