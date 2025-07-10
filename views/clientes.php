<?php
require_once 'header.php';
?>

<style>
    .responsive-table {
        width: 100%;
        overflow-x: auto;
        display: block;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <textarea class="form-control" id="direccion" name="direccion"></textarea>
                                </div>
                                <button type="submit" class="btn btn-dark">Guardar</button>    padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f4f4f4;
    }

    @media (max-width: 768px) {
        footer {
            text-align: center;
            font-size: 14px;
        }
    }

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
    
    /* Botón negro para acciones principales */
    .btn-dark {
        background: linear-gradient(45deg, #000000, #111111, #333333);
        border: 1px solid #444444;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    }
    
    .btn-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.4);
        background: linear-gradient(45deg, #111111, #333333, #555555);
    }
    
    .table th {
        background: linear-gradient(45deg, #000000, #111111, #222222);
        color: white;
        border: none;
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    }
    
    /* Limitar longitud de textos largos en la tabla */
    .direccion-truncada {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    
    .email-truncado {
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    
    .nombre-truncado {
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    
    /* Estilos para los botones con degradados */
    .btn-warning {
        background: linear-gradient(45deg, #ffc107, #e0a800, #d39e00);
        border: 1px solid #d39e00;
        color: #212529;
        text-shadow: 1px 1px 2px rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.3);
        background: linear-gradient(45deg, #e0a800, #d39e00, #ba8b00);
    }
    
    .btn-success {
        background: linear-gradient(45deg, #28a745, #218838, #1e7e34);
        border: 1px solid #1e7e34;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.3);
        background: linear-gradient(45deg, #218838, #1e7e34, #1c7430);
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0069d9, #0062cc);
        border: 1px solid #0062cc;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.4);
        background: linear-gradient(45deg, #0069d9, #0062cc, #005cbf);
    }
    
    .bg-primary {
        background: linear-gradient(45deg, #007bff, #0069d9, #0062cc) !important;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    }
    
    .btn-danger {
        background: linear-gradient(45deg, #dc3545, #c82333, #bd2130);
        border: 1px solid #bd2130;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0, 0.3);
        background: linear-gradient(45deg, #c82333, #bd2130, #a71d2a);
    }
    
    /* Asegurar que todos los iconos tengan el mismo tamaño */
    .btn-sm i {
        font-size: 12px;
        width: 12px;
        height: 12px;
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
    }
    
    /* Ajustar el espaciado de los botones para mejor alineación */
    .btn-sm {
        padding: 0.25rem 0.4rem;
        margin: 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        min-height: 28px;
    }
    
    /* Centrar los botones en la columna de acciones */
    .acciones-centradas {
        text-align: center;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 3px;
    }
</style>

<div class="page-container">
    <div class="container">
        <!-- Header de la página -->
        <div class="page-header">
            <h1><i class="bi bi-people-fill me-3"></i>Gestión de Clientes</h1>
            <p class="mb-0">Administra la información de clientes del sistema</p>
        </div>
        
        <!-- Botón para abrir el modal -->
        <div class="content-card">
            <div class="text-center mb-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">
                    <i class="bi bi-plus-circle me-2"></i>Agregar Cliente
                </button>
            </div>

            <!-- Modal para agregar cliente -->
            <div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formAgregarCliente">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="identificacion" class="form-label">Identificación</label>
                                        <input type="text" class="form-control" id="identificacion" name="identificacion" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <textarea class="form-control" id="direccion" name="direccion"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar cliente -->
            <div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarClienteLabel">Editar Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarCliente">
                                <input type="hidden" id="editarId" name="id">
                                <div class="mb-3">
                                    <label for="editarNombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="editarNombre" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editarIdentificacion" class="form-label">Identificación</label>
                                    <input type="text" class="form-control" id="editarIdentificacion" name="identificacion" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editarTelefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="editarTelefono" name="telefono">
                                </div>
                                <div class="mb-3">
                                    <label for="editarEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editarEmail" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="editarDireccion" class="form-label">Dirección</label>
                                    <textarea class="form-control" id="editarDireccion" name="direccion"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buscador de clientes -->
            <div class="row mb-3">
                <div class="col-md-6 offset-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="buscadorClientes" class="form-control" placeholder="Buscar cliente por nombre, identificación, teléfono, email, dirección, etc...">
                    </div>
                </div>
            </div>

            <!-- Tabla para listar clientes -->
            <div class="responsive-table">
                <table class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Nombre</th>
                            <th width="12%">Identificación</th>
                            <th width="10%">Teléfono</th>
                            <th width="15%">Email</th>
                            <th width="18%">Dirección</th>
                            <th width="20%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo $cliente['id']; ?></td>
                                <td><span class="nombre-truncado" title="<?php echo htmlspecialchars($cliente['nombre']); ?>"><?php echo $cliente['nombre']; ?></span></td>
                                <td><?php echo $cliente['identificacion']; ?></td>
                                <td><?php echo $cliente['telefono']; ?></td>
                                <td><span class="email-truncado" title="<?php echo htmlspecialchars($cliente['email']); ?>"><?php echo $cliente['email']; ?></span></td>
                                <td><span class="direccion-truncada" title="<?php echo htmlspecialchars($cliente['direccion']); ?>"><?php echo $cliente['direccion']; ?></span></td>
                                <td class="acciones-centradas">
                                    <a href="#" class="btn btn-warning btn-sm" onclick="abrirModalEditar(<?php echo $cliente['id']; ?>)" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="tel:<?php echo $cliente['telefono']; ?>" class="btn btn-success btn-sm" title="Llamar">
                                        <i class="bi bi-telephone-fill"></i>
                                    </a>
                                    <a href="mailto:<?php echo $cliente['email']; ?>" class="btn btn-primary btn-sm" title="Correo">
                                        <i class="bi bi-envelope"></i>
                                    </a>
                                    <a href="https://wa.me/<?php echo $cliente['telefono']; ?>" target="_blank" class="btn btn-success btn-sm" title="WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="confirmarEliminacion(event, <?php echo $cliente['id']; ?>)" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formAgregarCliente').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('index.php?action=agregarCliente', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cliente creado exitosamente');
                // Actualizar la tabla de clientes
                location.reload();
            } else {
                alert('Error al agregar cliente: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Abrir modal con datos del cliente
    function abrirModalEditar(id) {
        fetch(`index.php?action=obtenerCliente&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('editarId').value = data.cliente.id;
                    document.getElementById('editarNombre').value = data.cliente.nombre;
                    document.getElementById('editarIdentificacion').value = data.cliente.identificacion;
                    document.getElementById('editarTelefono').value = data.cliente.telefono;
                    document.getElementById('editarEmail').value = data.cliente.email;
                    document.getElementById('editarDireccion').value = data.cliente.direccion;

                    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarCliente'));
                    modalEditar.show();
                } else {
                    alert('Error al obtener los datos del cliente: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Enviar datos editados al servidor
    document.getElementById('formEditarCliente').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('index.php?action=editarCliente', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar la tabla de clientes
                location.reload();
            } else {
                alert('Error al actualizar cliente: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Confirmación para eliminar cliente
    function confirmarEliminacion(event, id) {
        event.preventDefault();
        if (confirm('¿Estás seguro de eliminar este cliente?')) {
            fetch(`index.php?action=eliminarCliente&id=${id}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar la tabla de clientes
                    location.reload();
                } else {
                    alert('Error al eliminar cliente: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    // --- FILTRO DE TABLA POR BUSCADOR DE CLIENTES ---
    document.getElementById('buscadorClientes').addEventListener('input', function() {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('.responsive-table tbody tr').forEach(function(fila) {
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
    });
</script>

<?php
require_once 'footer.php';
?>