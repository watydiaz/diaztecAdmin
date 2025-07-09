<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Diaztecnologia</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap para modales y componentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .page-container {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 25%, #4a4a4a 50%, #6a6a6a 75%, #8a8a8a 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .page-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            color: #212529;
            border: 2px solid #ced4da;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .btn-primary {
            background: linear-gradient(45deg, #000000, #333333, #666666);
            border: 1px solid #444444;
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0, 0.4);
            background: linear-gradient(45deg, #333333, #666666, #999999);
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        .table th {
            background: linear-gradient(45deg, #000000, #333333, #555555);
            color: white;
            border: none;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .action-buttons .btn {
            margin: 0 2px;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .modal-header {
            background: linear-gradient(45deg, #000000, #333333, #555555);
            color: white;
            border-bottom: none;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        }
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
            border: 2px solid #444444;
        }
        .form-control:focus {
            border-color: #666666;
            box-shadow: 0 0 0 0.2rem rgba(102, 102, 102, 0.25);
        }
        .stats-card {
            background: linear-gradient(45deg, #000000, #333333, #666666);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #444444;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        }
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }
        .loading {
            text-align: center;
            padding: 50px;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="page-container">
        <div class="container">
            <!-- Header de la página -->
            <div class="page-header">
                <h1><i class="fas fa-users me-3"></i>Gestión de Usuarios</h1>
                <p class="mb-0">Administra usuarios y roles del sistema</p>
            </div>

            <!-- Estadísticas -->
            <div class="row" id="statsContainer">
                <div class="col-md-4">
                    <div class="stats-card">
                        <span class="stats-number" id="totalUsuarios">0</span>
                        <span>Total Usuarios</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <span class="stats-number" id="usuariosActivos">0</span>
                        <span>Usuarios Activos</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <span class="stats-number" id="usuariosInactivos">0</span>
                        <span>Usuarios Inactivos</span>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4><i class="fas fa-list me-2"></i>Lista de Usuarios</h4>
                    <button class="btn btn-primary" onclick="abrirModalCrear()">
                        <i class="fas fa-plus me-2"></i>Nuevo Usuario
                    </button>
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaUsuarios">
                            <tr>
                                <td colspan="7" class="loading">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Cargando usuarios...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear/Editar Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioTitulo">Nuevo Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formUsuario">
                        <input type="hidden" id="usuarioId" name="id">
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña <span id="passwordRequired">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Mínimo 6 caracteres. <span id="passwordHelp">Requerida para nuevos usuarios.</span></div>
                        </div>

                        <div class="mb-3">
                            <label for="rol_id" class="form-label">Rol *</label>
                            <select class="form-control" id="rol_id" name="rol_id" required>
                                <option value="">Seleccionar rol...</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                                <label class="form-check-label" for="activo">
                                    Usuario activo
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarUsuario()">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Resetear Contraseña -->
    <div class="modal fade" id="modalResetPassword" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resetear Contraseña</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formResetPassword">
                        <input type="hidden" id="resetUserId">
                        <div class="mb-3">
                            <label for="nueva_password" class="form-label">Nueva Contraseña *</label>
                            <input type="password" class="form-control" id="nueva_password" name="nueva_password" required>
                            <div class="form-text">Mínimo 6 caracteres</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="resetearPassword()">
                        <i class="fas fa-key me-2"></i>Resetear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let usuarios = [];
        let roles = [];
        let editandoUsuario = false;

        document.addEventListener('DOMContentLoaded', function() {
            cargarUsuarios();
            cargarEstadisticas();
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
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-users me-2"></i>No hay usuarios registrados
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            usuarios.forEach(usuario => {
                const estadoBadge = usuario.activo == 1 ? 
                    '<span class="badge badge-success">Activo</span>' : 
                    '<span class="badge badge-danger">Inactivo</span>';
                
                const fechaRegistro = new Date(usuario.fecha_registro).toLocaleDateString('es-ES');

                html += `
                    <tr>
                        <td>${usuario.id}</td>
                        <td>${usuario.nombre}</td>
                        <td>${usuario.email}</td>
                        <td>${usuario.rol_nombre || 'Sin rol'}</td>
                        <td>${estadoBadge}</td>
                        <td>${fechaRegistro}</td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-outline-primary" onclick="editarUsuario(${usuario.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="abrirResetPassword(${usuario.id})" title="Resetear contraseña">
                                <i class="fas fa-key"></i>
                            </button>
                            <button class="btn btn-sm ${usuario.activo == 1 ? 'btn-outline-secondary' : 'btn-outline-success'}" 
                                    onclick="cambiarEstado(${usuario.id}, ${usuario.activo == 1 ? 0 : 1})" 
                                    title="${usuario.activo == 1 ? 'Desactivar' : 'Activar'}">
                                <i class="fas fa-${usuario.activo == 1 ? 'ban' : 'check'}"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarUsuario(${usuario.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
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
            document.getElementById('modalUsuarioTitulo').textContent = 'Nuevo Usuario';
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
            document.getElementById('modalUsuarioTitulo').textContent = 'Editar Usuario';
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
                    mostrarExito(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('modalUsuario')).hide();
                    cargarUsuarios();
                    cargarEstadisticas();
                } else {
                    mostrarError(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Error de conexión al guardar usuario');
            });
        }

        function eliminarUsuario(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar este usuario?')) return;

            const formData = new FormData();
            formData.append('id', id);

            fetch('index.php?action=eliminarUsuario', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarExito(data.message);
                    cargarUsuarios();
                    cargarEstadisticas();
                } else {
                    mostrarError(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Error de conexión al eliminar usuario');
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
                    mostrarExito(data.message);
                    cargarUsuarios();
                    cargarEstadisticas();
                } else {
                    mostrarError(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Error de conexión al cambiar estado');
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
                    mostrarExito(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('modalResetPassword')).hide();
                } else {
                    mostrarError(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Error de conexión al resetear contraseña');
            });
        }

        function mostrarExito(mensaje) {
            // Puedes implementar tu sistema de notificaciones aquí
            alert('✅ ' + mensaje);
        }

        function mostrarError(mensaje) {
            // Puedes implementar tu sistema de notificaciones aquí
            alert('❌ ' + mensaje);
        }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
