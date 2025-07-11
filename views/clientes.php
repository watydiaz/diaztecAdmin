<?php
require_once 'header.php';
?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- CSS específico para el módulo de clientes -->
<link rel="stylesheet" href="assets/css/modules/clientes.css">
<!-- JavaScript específico para el módulo de clientes -->
<script src="assets/js/modules/clientes.js" defer></script>

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

<?php
require_once 'footer.php';
?>