/**
 * Script para el módulo de clientes
 * Este archivo contiene todas las funciones relacionadas con la gestión de clientes
 */

// Variables globales
let clientes = [];

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners para formularios
    const formAgregar = document.getElementById('formAgregarCliente');
    const formEditar = document.getElementById('formEditarCliente');
    
    if (formAgregar) {
        formAgregar.addEventListener('submit', agregarCliente);
    }
    
    if (formEditar) {
        formEditar.addEventListener('submit', actualizarCliente);
    }
    
    // Actualizar fecha
    const fechaActual = document.getElementById('fechaActual');
    if (fechaActual) {
        fechaActual.textContent = new Date().toLocaleDateString('es-CO', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
});

// Función para mostrar modal agregar cliente
function mostrarModalAgregarCliente() {
    document.getElementById('formAgregarCliente').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarCliente'));
    modal.show();
}

// Función para guardar cliente
function agregarCliente(e) {
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

// Función para exportar clientes
function exportarClientes() {
    Swal.fire({
        title: 'Exportar Clientes',
        text: 'Funcionalidad de exportación en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

// Inicialización al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Configurar evento de búsqueda en tiempo real
    document.getElementById('buscadorClientes').addEventListener('input', filtrarClientes);
    
    // Configurar eventos para los formularios
    document.getElementById('formAgregarCliente').addEventListener('submit', agregarCliente);
    document.getElementById('formEditarCliente').addEventListener('submit', actualizarCliente);
});
