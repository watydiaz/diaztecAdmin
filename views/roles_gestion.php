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

.stats-container {
    margin-bottom: 30px;
}

.stats-card {
    color: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s ease;
    border: none;
}

.stats-card:hover {
    transform: translateY(-5px);
}

/* Tarjeta Total Roles - Púrpura */
.stats-card.total-roles {
    background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%);
    box-shadow: 0 8px 25px rgba(156, 39, 176, 0.15);
}

.stats-card.total-roles:hover {
    box-shadow: 0 15px 35px rgba(156, 39, 176, 0.25);
}

/* Tarjeta Permisos - Índigo */
.stats-card.total-permisos {
    background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
    box-shadow: 0 8px 25px rgba(63, 81, 181, 0.15);
}

.stats-card.total-permisos:hover {
    box-shadow: 0 15px 35px rgba(63, 81, 181, 0.25);
}

/* Tarjeta Roles Activos - Teal */
.stats-card.roles-activos {
    background: linear-gradient(135deg, #009688 0%, #00695c 100%);
    box-shadow: 0 8px 25px rgba(0, 150, 136, 0.15);
}

.stats-card.roles-activos:hover {
    box-shadow: 0 15px 35px rgba(0, 150, 136, 0.25);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    display: block;
    margin-bottom: 5px;
}

.stats-label {
    font-size: 0.95rem;
    opacity: 0.9;
    font-weight: 500;
}

.rol-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.rol-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.rol-card h5 {
    color: #333;
    margin-bottom: 15px;
    font-weight: 700;
    font-size: 1.25rem;
}

.permiso-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    margin: 3px;
    display: inline-block;
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
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

.permiso-checkbox {
    margin: 10px 0;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    transition: all 0.3s ease;
}

.permiso-checkbox:hover {
    border-color: #667eea;
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.permiso-checkbox .form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

/* Sin datos */
.no-data {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.no-data i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .content-section {
        padding: 20px;
        margin: 15px 0;
    }
    
    .rol-card {
        padding: 20px;
    }
    
    .stats-card {
        margin-bottom: 15px;
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
            <h1><i class="fas fa-user-shield me-3"></i>Gestión de Roles y Permisos</h1>
            <p class="mb-0">Centro de Control de Roles, Permisos y Accesos del Sistema</p>
            <small id="fechaActual"></small>
        </div>
        
        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="row" id="statsContainer">
                <div class="col-md-4 mb-3">
                    <div class="stats-card total-roles">
                        <span class="stats-number" id="totalRoles">0</span>
                        <span class="stats-label">Total Roles</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stats-card total-permisos">
                        <span class="stats-number" id="totalPermisos">0</span>
                        <span class="stats-label">Total Permisos</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stats-card roles-activos">
                        <span class="stats-number" id="rolesActivos">0</span>
                        <span class="stats-label">Roles Activos</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controles superiores -->
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title mb-0">
                    <i class="fas fa-list me-2"></i>Roles del Sistema
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-info" onclick="exportarRoles()" title="Exportar roles">
                        <i class="fas fa-download me-2"></i>Exportar
                    </button>
                    <button class="btn btn-outline-success" onclick="mostrarPermisos()" title="Gestionar permisos">
                        <i class="fas fa-key me-2"></i>Permisos
                    </button>
                </div>
            </div>

            <!-- Lista de roles -->
            <div id="rolesContainer">
                <div class="no-data">
                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                    <h5>Cargando roles...</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botón flotante para agregar rol -->
<button class="floating-btn" onclick="abrirModalCrearRol()" title="Agregar nuevo rol">
    <i class="fas fa-plus"></i>
</button>

<!-- Modal Crear/Editar Rol -->
<div class="modal fade" id="modalRol" tabindex="-1" aria-labelledby="modalRolLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRolTitulo">
                    <i class="fas fa-user-shield me-2"></i>Nuevo Rol
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRol">
                <div class="modal-body">
                    <input type="hidden" id="rolId" name="id">
                    
                    <div class="mb-4">
                        <label for="nombreRol" class="form-label fw-bold">
                            <i class="fas fa-tag me-2"></i>Nombre del Rol *
                        </label>
                        <input type="text" class="form-control" id="nombreRol" name="nombre" required 
                               placeholder="Ej: Administrador, Técnico, Recepcionista...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-key me-2"></i>Permisos del Rol
                        </label>
                        <p class="text-muted small">Selecciona los permisos que tendrá este rol en el sistema</p>
                        <div id="permisosContainer">
                            <!-- Los permisos se cargarán aquí -->
                            <div class="no-data">
                                <i class="fas fa-key fa-2x"></i>
                                <p>Cargando permisos disponibles...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-gradient" onclick="guardarRol()">
                        <i class="fas fa-save me-2"></i>Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let roles = [];
let permisos = [];
let editandoRol = false;

document.addEventListener('DOMContentLoaded', function() {
    cargarRoles();
    cargarPermisos();
    cargarEstadisticas();
    
    // Actualizar fecha en header
    document.getElementById('fechaActual').textContent = new Date().toLocaleDateString('es-CO', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

function cargarRoles() {
    fetch('index.php?action=listarRoles')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                roles = data.roles;
                mostrarRoles();
                actualizarEstadisticas();
            } else {
                mostrarError('Error al cargar roles: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error de conexión al cargar roles');
        });
}

function cargarPermisos() {
    fetch('index.php?action=listarPermisos')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                permisos = data.permisos;
                actualizarEstadisticas();
            } else {
                console.error('Error al cargar permisos:', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function cargarEstadisticas() {
    // Esta función puede expandirse para obtener estadísticas del servidor
    actualizarEstadisticas();
}

function actualizarEstadisticas() {
    document.getElementById('totalRoles').textContent = roles.length || 0;
    document.getElementById('totalPermisos').textContent = permisos.length || 0;
    document.getElementById('rolesActivos').textContent = roles.filter(r => r.permisos && r.permisos.length > 0).length || 0;
}

function mostrarRoles() {
    const container = document.getElementById('rolesContainer');
    
    if (roles.length === 0) {
        container.innerHTML = `
            <div class="no-data">
                <i class="fas fa-user-shield fa-3x"></i>
                <h5>No hay roles registrados</h5>
                <p>Haz clic en el botón + para agregar el primer rol</p>
            </div>
        `;
        return;
    }

    let html = '';
    roles.forEach(rol => {
        const permisosHtml = rol.permisos && rol.permisos.length > 0 
            ? rol.permisos.map(permiso => `<span class="permiso-badge">${permiso}</span>`).join('')
            : '<span class="text-muted"><i class="fas fa-exclamation-triangle me-2"></i>Sin permisos asignados</span>';
            
        html += `
            <div class="rol-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5>
                            <i class="fas fa-user-tag me-2"></i>
                            ${rol.nombre}
                            ${rol.permisos && rol.permisos.length > 0 ? '' : '<small class="text-warning ms-2">(Sin permisos)</small>'}
                        </h5>
                        <div class="permisos-lista mb-3">
                            ${permisosHtml}
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-key me-1"></i>
                            ${rol.permisos ? rol.permisos.length : 0} permiso(s) asignado(s)
                        </small>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary btn-action me-2" 
                                onclick="editarRol(${rol.id})" 
                                title="Editar rol">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info btn-action me-2" 
                                onclick="verDetallesRol(${rol.id})" 
                                title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-action" 
                                onclick="eliminarRol(${rol.id})" 
                                title="Eliminar rol">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function abrirModalCrearRol() {
    editandoRol = false;
    document.getElementById('modalRolTitulo').innerHTML = '<i class="fas fa-user-shield me-2"></i>Nuevo Rol';
    document.getElementById('formRol').reset();
    document.getElementById('rolId').value = '';
    cargarPermisosEnModal();
    
    const modal = new bootstrap.Modal(document.getElementById('modalRol'));
    modal.show();
}

function editarRol(id) {
    const rol = roles.find(r => r.id == id);
    if (!rol) return;

    editandoRol = true;
    document.getElementById('modalRolTitulo').innerHTML = '<i class="fas fa-user-edit me-2"></i>Editar Rol';
    document.getElementById('rolId').value = rol.id;
    document.getElementById('nombreRol').value = rol.nombre;
    
    cargarPermisosEnModal(rol.permisos || []);

    const modal = new bootstrap.Modal(document.getElementById('modalRol'));
    modal.show();
}

function cargarPermisosEnModal(permisosAsignados = []) {
    const container = document.getElementById('permisosContainer');
    
    if (permisos.length === 0) {
        container.innerHTML = `
            <div class="no-data">
                <i class="fas fa-key fa-2x"></i>
                <p>No hay permisos disponibles</p>
            </div>
        `;
        return;
    }

    let html = '';
    permisos.forEach(permiso => {
        const checked = permisosAsignados.some(p => p === permiso.nombre) ? 'checked' : '';
        html += `
            <div class="permiso-checkbox">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                           id="permiso_${permiso.id}" name="permisos[]" 
                           value="${permiso.id}" ${checked}>
                    <label class="form-check-label fw-bold" for="permiso_${permiso.id}">
                        <i class="fas fa-shield-alt me-2"></i>
                        ${permiso.nombre}
                        ${permiso.descripcion ? `<br><small class="text-muted">${permiso.descripcion}</small>` : ''}
                    </label>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function guardarRol() {
    const formData = new FormData(document.getElementById('formRol'));
    const action = editandoRol ? 'actualizarRol' : 'crearRol';
    
    fetch(`index.php?action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: data.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            bootstrap.Modal.getInstance(document.getElementById('modalRol')).hide();
            cargarRoles();
        } else {
            Swal.fire('Error', data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al guardar rol', 'error');
    });
}

function eliminarRol(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará el rol permanentemente',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('index.php?action=eliminarRol', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    cargarRoles();
                } else {
                    Swal.fire('Error', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error de conexión al eliminar rol', 'error');
            });
        }
    });
}

function verDetallesRol(id) {
    const rol = roles.find(r => r.id == id);
    if (!rol) return;
    
    const permisosHtml = rol.permisos && rol.permisos.length > 0
        ? rol.permisos.map(p => `<li><i class="fas fa-check text-success me-2"></i>${p}</li>`).join('')
        : '<li class="text-muted"><i class="fas fa-times text-danger me-2"></i>Sin permisos asignados</li>';
    
    Swal.fire({
        title: `<i class="fas fa-user-tag me-2"></i>Detalles del Rol`,
        html: `
            <div class="text-start">
                <h5><strong>Nombre:</strong> ${rol.nombre}</h5>
                <h6 class="mt-3"><strong>Permisos asignados:</strong></h6>
                <ul class="list-unstyled mt-2">
                    ${permisosHtml}
                </ul>
            </div>
        `,
        width: '500px',
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#667eea'
    });
}

// Funciones adicionales
function exportarRoles() {
    Swal.fire({
        title: 'Exportar Roles',
        text: 'Funcionalidad de exportación en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

function mostrarPermisos() {
    Swal.fire({
        title: 'Gestión de Permisos',
        text: 'Funcionalidad de gestión de permisos en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}
</script>

<?php
require_once 'footer.php';
?>
