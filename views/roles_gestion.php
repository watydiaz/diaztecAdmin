<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles - Diaztecnologia</title>
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
        .rol-card {
            border: 2px solid #444444;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        .rol-card h5 {
            color: #000000;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
            font-weight: bold;
        }
        .permiso-badge {
            background: linear-gradient(45deg, #000000, #333333, #555555);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            margin: 3px;
            display: inline-block;
            font-size: 0.85rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
            border: 1px solid #666666;
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
        .loading {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        .permiso-checkbox {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="page-container">
        <div class="container">
            <!-- Header de la página -->
            <div class="page-header">
                <h1><i class="fas fa-user-shield me-3"></i>Gestión de Roles y Permisos</h1>
                <p class="mb-0">Administra roles y asigna permisos del sistema</p>
            </div>

            <!-- Contenido principal -->
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4><i class="fas fa-list me-2"></i>Roles del Sistema</h4>
                    <button class="btn btn-primary" onclick="abrirModalCrearRol()">
                        <i class="fas fa-plus me-2"></i>Nuevo Rol
                    </button>
                </div>

                <!-- Lista de roles -->
                <div id="rolesContainer">
                    <div class="loading">
                        <i class="fas fa-spinner fa-spin me-2"></i>Cargando roles...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear/Editar Rol -->
    <div class="modal fade" id="modalRol" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRolTitulo">Nuevo Rol</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formRol">
                        <input type="hidden" id="rolId" name="id">
                        
                        <div class="mb-3">
                            <label for="nombreRol" class="form-label">Nombre del Rol *</label>
                            <input type="text" class="form-control" id="nombreRol" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permisos</label>
                            <div id="permisosContainer">
                                <!-- Los permisos se cargarán aquí -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarRol()">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let roles = [];
        let permisos = [];
        let editandoRol = false;

        document.addEventListener('DOMContentLoaded', function() {
            cargarRoles();
            cargarPermisos();
        });

        function cargarRoles() {
            fetch('index.php?action=listarRoles')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        roles = data.roles;
                        mostrarRoles();
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
                    } else {
                        console.error('Error al cargar permisos:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function mostrarRoles() {
            const container = document.getElementById('rolesContainer');
            
            if (roles.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="fas fa-user-shield me-2"></i>No hay roles registrados
                    </div>
                `;
                return;
            }

            let html = '';
            roles.forEach(rol => {
                html += `
                    <div class="rol-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5><i class="fas fa-user-tag me-2"></i>${rol.nombre}</h5>
                                <div class="permisos-lista">
                                    ${rol.permisos ? rol.permisos.map(permiso => 
                                        `<span class="permiso-badge">${permiso}</span>`
                                    ).join('') : '<span class="text-muted">Sin permisos asignados</span>'}
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary me-2" onclick="editarRol(${rol.id})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="eliminarRol(${rol.id})" title="Eliminar">
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
            document.getElementById('modalRolTitulo').textContent = 'Nuevo Rol';
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
            document.getElementById('modalRolTitulo').textContent = 'Editar Rol';
            document.getElementById('rolId').value = rol.id;
            document.getElementById('nombreRol').value = rol.nombre;
            
            cargarPermisosEnModal(rol.permisos || []);

            const modal = new bootstrap.Modal(document.getElementById('modalRol'));
            modal.show();
        }

        function cargarPermisosEnModal(permisosAsignados = []) {
            const container = document.getElementById('permisosContainer');
            
            if (permisos.length === 0) {
                container.innerHTML = '<div class="text-muted">No hay permisos disponibles</div>';
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
                            <label class="form-check-label" for="permiso_${permiso.id}">
                                <strong>${permiso.nombre}</strong>
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
                    mostrarExito(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('modalRol')).hide();
                    cargarRoles();
                } else {
                    mostrarError(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Error de conexión al guardar rol');
            });
        }

        function eliminarRol(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar este rol?')) return;

            const formData = new FormData();
            formData.append('id', id);

            fetch('index.php?action=eliminarRol', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarExito(data.message);
                    cargarRoles();
                } else {
                    mostrarError(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Error de conexión al eliminar rol');
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
