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

/* Tarjeta Total Usuarios - Azul */
.stats-card.total-usuarios {
    background: linear-gradient(135deg, #4285f4 0%, #1976d2 100%);
    box-shadow: 0 8px 25px rgba(66, 133, 244, 0.15);
}

.stats-card.total-usuarios:hover {
    box-shadow: 0 15px 35px rgba(66, 133, 244, 0.25);
}

/* Tarjeta Usuarios Activos - Verde */
.stats-card.usuarios-activos {
    background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.15);
}

.stats-card.usuarios-activos:hover {
    box-shadow: 0 15px 35px rgba(76, 175, 80, 0.25);
}

/* Tarjeta Usuarios Inactivos - Naranja/Rojo */
.stats-card.usuarios-inactivos {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    box-shadow: 0 8px 25px rgba(255, 152, 0, 0.15);
}

.stats-card.usuarios-inactivos:hover {
    box-shadow: 0 15px 35px rgba(255, 152, 0, 0.25);
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

/* Estados y badges */
.badge-activo {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
}

.badge-inactivo {
    background: linear-gradient(45deg, #dc3545, #e83e8c);
    color: white;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
}

/* Avatar circular */
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
    
    .table th, .table td {
        font-size: 0.875rem;
        padding: 10px 8px;
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
            <h1><i class="fas fa-users me-3"></i>Gestión de Usuarios</h1>
            <p class="mb-0">Centro de Control de Usuarios y Permisos del Sistema</p>
            <small id="fechaActual"></small>
        </div>
        
        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="row" id="statsContainer">
                <div class="col-md-4 mb-3">
                    <div class="stats-card total-usuarios">
                        <span class="stats-number" id="totalUsuarios">0</span>
                        <span class="stats-label">Total Usuarios</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stats-card usuarios-activos">
                        <span class="stats-number" id="usuariosActivos">0</span>
                        <span class="stats-label">Usuarios Activos</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stats-card usuarios-inactivos">
                        <span class="stats-number" id="usuariosInactivos">0</span>
                        <span class="stats-label">Usuarios Inactivos</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controles superiores -->
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Usuarios
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-info" onclick="exportarUsuarios()" title="Exportar usuarios">
                        <i class="fas fa-download me-2"></i>Exportar
                    </button>
                    <button class="btn btn-outline-success" onclick="filtrarPorEstado('activo')" title="Ver usuarios activos">
                        <i class="fas fa-user-check me-2"></i>Activos
                    </button>
                    <button class="btn btn-outline-warning" onclick="filtrarPorEstado('inactivo')" title="Ver usuarios inactivos">
                        <i class="fas fa-user-times me-2"></i>Inactivos
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaUsuariosMain">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>ID</th>
                            <th><i class="fas fa-user me-2"></i>Nombre</th>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                            <th><i class="fas fa-user-tag me-2"></i>Rol</th>
                            <th><i class="fas fa-toggle-on me-2"></i>Estado</th>
                            <th><i class="fas fa-calendar me-2"></i>Fecha Registro</th>
                            <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaUsuarios">
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                                <h5 class="text-muted">Cargando usuarios...</h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Botón flotante para agregar usuario -->
<button class="floating-btn" onclick="abrirModalCrear()" title="Agregar nuevo usuario">
    <i class="fas fa-plus"></i>
</button>

<!-- Modal Crear/Editar Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuarioTitulo">
                    <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUsuario">
                <div class="modal-body">
                    <input type="hidden" id="usuarioId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Nombre Completo *
                            </label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope me-2"></i>Email *
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-lock me-2"></i>Contraseña <span id="passwordRequired">*</span>
                            </label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Mínimo 6 caracteres. <span id="passwordHelp">Requerida para nuevos usuarios.</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rol_id" class="form-label fw-bold">
                                <i class="fas fa-user-tag me-2"></i>Rol *
                            </label>
                            <select class="form-select" id="rol_id" name="rol_id" required>
                                <option value="">Seleccionar rol...</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                            <label class="form-check-label fw-bold" for="activo">
                                <i class="fas fa-toggle-on me-2"></i>Usuario activo
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-gradient" onclick="guardarUsuario()">
                        <i class="fas fa-save me-2"></i>Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Resetear Contraseña -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalResetPasswordLabel">
                    <i class="fas fa-key me-2"></i>Resetear Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formResetPassword">
                <div class="modal-body">
                    <input type="hidden" id="resetUserId">
                    <div class="mb-3">
                        <label for="nueva_password" class="form-label fw-bold">
                            <i class="fas fa-lock me-2"></i>Nueva Contraseña *
                        </label>
                        <input type="password" class="form-control" id="nueva_password" name="nueva_password" required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Mínimo 6 caracteres
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-gradient" onclick="resetearPassword()">
                        <i class="fas fa-key me-2"></i>Resetear Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let usuarios = [];
let roles = [];
let editandoUsuario = false;

document.addEventListener('DOMContentLoaded', function() {
    cargarUsuarios();
    cargarEstadisticas();
    
    // Actualizar fecha en header
    document.getElementById('fechaActual').textContent = new Date().toLocaleDateString('es-CO', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

function cargarUsuarios() {
    fetch('index.php?action=listarUsuarios')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                usuarios = data.usuarios;
                roles = data.roles;
                mostrarUsuarios();
                llenarSelectRoles();
            } else {
                mostrarError('Error al cargar usuarios: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error de conexión al cargar usuarios');
        });
}

function cargarEstadisticas() {
    fetch('index.php?action=obtenerEstadisticasUsuarios')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalUsuarios').textContent = data.estadisticas.total_usuarios || 0;
                document.getElementById('usuariosActivos').textContent = data.estadisticas.usuarios_activos || 0;
                document.getElementById('usuariosInactivos').textContent = data.estadisticas.usuarios_inactivos || 0;
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas:', error);
        });
}

function mostrarUsuarios() {
    const tbody = document.getElementById('tablaUsuarios');
    
    if (usuarios.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay usuarios registrados</h5>
                    <p class="text-muted">Haz clic en el botón + para agregar el primer usuario</p>
                </td>
            </tr>
        `;
        return;
    }

    let html = '';
    usuarios.forEach(usuario => {
        const estadoBadge = usuario.activo == 1 ? 
            '<span class="badge-activo">Activo</span>' : 
            '<span class="badge-inactivo">Inactivo</span>';
        
        const fechaRegistro = new Date(usuario.fecha_registro).toLocaleDateString('es-ES');

        html += `
            <tr>
                <td class="fw-bold text-primary">${usuario.id}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-2">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="fw-bold">${usuario.nombre}</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope text-muted me-2"></i>
                        ${usuario.email}
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-tag text-muted me-2"></i>
                        <span class="fw-bold text-info">${usuario.rol_nombre || 'Sin rol'}</span>
                    </div>
                </td>
                <td>${estadoBadge}</td>
                <td class="text-muted">${fechaRegistro}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary btn-action" 
                                onclick="editarUsuario(${usuario.id})" 
                                title="Editar usuario">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning btn-action" 
                                onclick="abrirResetPassword(${usuario.id})" 
                                title="Resetear contraseña">
                            <i class="fas fa-key"></i>
                        </button>
                        <button class="btn btn-sm ${usuario.activo == 1 ? 'btn-outline-secondary' : 'btn-outline-success'} btn-action" 
                                onclick="cambiarEstado(${usuario.id}, ${usuario.activo == 1 ? 0 : 1})" 
                                title="${usuario.activo == 1 ? 'Desactivar' : 'Activar'}">
                            <i class="fas fa-${usuario.activo == 1 ? 'ban' : 'check'}"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-action" 
                                onclick="eliminarUsuario(${usuario.id})" 
                                title="Eliminar usuario">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

function llenarSelectRoles() {
    const select = document.getElementById('rol_id');
    select.innerHTML = '<option value="">Seleccionar rol...</option>';
    
    roles.forEach(rol => {
        select.innerHTML += `<option value="${rol.id}">${rol.nombre}</option>`;
    });
}

function abrirModalCrear() {
    editandoUsuario = false;
    document.getElementById('modalUsuarioTitulo').innerHTML = '<i class="fas fa-user-plus me-2"></i>Nuevo Usuario';
    document.getElementById('formUsuario').reset();
    document.getElementById('usuarioId').value = '';
    document.getElementById('passwordRequired').textContent = '*';
    document.getElementById('passwordHelp').textContent = 'Requerida para nuevos usuarios.';
    document.getElementById('password').required = true;
    
    const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    modal.show();
}

function editarUsuario(id) {
    const usuario = usuarios.find(u => u.id == id);
    if (!usuario) return;

    editandoUsuario = true;
    document.getElementById('modalUsuarioTitulo').innerHTML = '<i class="fas fa-user-edit me-2"></i>Editar Usuario';
    document.getElementById('usuarioId').value = usuario.id;
    document.getElementById('nombre').value = usuario.nombre;
    document.getElementById('email').value = usuario.email;
    document.getElementById('rol_id').value = usuario.rol_id;
    document.getElementById('activo').checked = usuario.activo == 1;
    document.getElementById('password').value = '';
    document.getElementById('passwordRequired').textContent = '';
    document.getElementById('passwordHelp').textContent = 'Dejar vacío para mantener la contraseña actual.';
    document.getElementById('password').required = false;

    const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    modal.show();
}

function guardarUsuario() {
    const formData = new FormData(document.getElementById('formUsuario'));
    const action = editandoUsuario ? 'actualizarUsuario' : 'crearUsuario';
    
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
            bootstrap.Modal.getInstance(document.getElementById('modalUsuario')).hide();
            cargarUsuarios();
            cargarEstadisticas();
        } else {
            Swal.fire('Error', data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al guardar usuario', 'error');
    });
}

function eliminarUsuario(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará el usuario permanentemente',
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

            fetch('index.php?action=eliminarUsuario', {
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
                    cargarUsuarios();
                    cargarEstadisticas();
                } else {
                    Swal.fire('Error', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error de conexión al eliminar usuario', 'error');
            });
        }
    });
}

function cambiarEstado(id, nuevoEstado) {
    const formData = new FormData();
    formData.append('id', id);
    if (nuevoEstado) formData.append('activo', '1');

    fetch('index.php?action=cambiarEstadoUsuario', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Actualizado!',
                text: data.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            cargarUsuarios();
            cargarEstadisticas();
        } else {
            Swal.fire('Error', data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al cambiar estado', 'error');
    });
}

function abrirResetPassword(id) {
    document.getElementById('resetUserId').value = id;
    document.getElementById('nueva_password').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('modalResetPassword'));
    modal.show();
}

function resetearPassword() {
    const formData = new FormData();
    formData.append('id', document.getElementById('resetUserId').value);
    formData.append('nueva_password', document.getElementById('nueva_password').value);

    fetch('index.php?action=resetearPasswordUsuario', {
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
            bootstrap.Modal.getInstance(document.getElementById('modalResetPassword')).hide();
        } else {
            Swal.fire('Error', data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al resetear contraseña', 'error');
    });
}

// Funciones de filtrado y exportación
function filtrarPorEstado(estado) {
    const filas = document.querySelectorAll('#tablaUsuarios tr');
    
    filas.forEach(function(fila) {
        const estadoFila = fila.querySelector('.badge-activo, .badge-inactivo');
        let mostrar = true;
        
        if (estado === 'activo' && estadoFila && !estadoFila.className.includes('badge-activo')) {
            mostrar = false;
        } else if (estado === 'inactivo' && estadoFila && !estadoFila.className.includes('badge-inactivo')) {
            mostrar = false;
        }
        
        fila.style.display = mostrar ? '' : 'none';
    });
}

function exportarUsuarios() {
    Swal.fire({
        title: 'Exportar Usuarios',
        text: 'Funcionalidad de exportación en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}
</script>

<?php
require_once 'footer.php';
?>
