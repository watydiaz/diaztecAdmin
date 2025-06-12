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
        padding: 10px;
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
</style>

<div class="container-fluid">
    <center><br>
    <h3>Gestión de Clientes</h3>
    <p>Desde aquí puedes agregar, editar y eliminar clientes.</p>
    <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">
    Agregar Cliente
    </button>
    </center>
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
            <input type="text" id="buscadorClientes" class="form-control" placeholder="Buscar cliente por nombre, identificación, teléfono, email, dirección, etc...">
        </div>
    </div>

    <!-- Tabla para listar clientes -->
    <div class="responsive-table">
        <table class="table table-striped w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Identificación</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['id']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $cliente['identificacion']; ?></td>
                        <td><?php echo $cliente['telefono']; ?></td>
                        <td><?php echo $cliente['email']; ?></td>
                        <td><?php echo $cliente['direccion']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" onclick="abrirModalEditar(<?php echo $cliente['id']; ?>)" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="tel:<?php echo $cliente['telefono']; ?>" class="btn btn-success btn-sm" title="Llamar">
                                <i class="bi bi-telephone-fill"></i>
                            </a>
                            <a href="mailto:<?php echo $cliente['email']; ?>" class="btn btn-primary btn-sm" title="Correo">
                                <i class="bi bi-envelope-fill"></i>
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