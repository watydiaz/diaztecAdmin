/**
 * main.js
 * Archivo principal que importa todos los módulos JavaScript
 */

// Importar módulos específicos - Estos se cargarán solo cuando se necesiten
// desde las vistas correspondientes usando la etiqueta script con atributo defer

// Funcionalidad global compartida
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap en toda la aplicación
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Configurar comportamiento para todos los modales
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function (event) {
            // Limpiar formularios cuando se cierra un modal
            const forms = this.querySelectorAll('form');
            forms.forEach(form => form.reset());
        });
    });

    // Configurar confirmación para eliminación de elementos
    const deleteButtons = document.querySelectorAll('[data-confirm="true"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
});

// Funciones de utilidad global
const Utils = {
    // Formatear fechas
    formatDate: function(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    },
    
    // Formatear números como moneda
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(amount);
    },
    
    // Mostrar notificaciones
    showNotification: function(message, type = 'success') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: type.charAt(0).toUpperCase() + type.slice(1),
                text: message,
                icon: type,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        } else {
            alert(`${type.toUpperCase()}: ${message}`);
        }
    },
    
    // Validar formulario
    validateForm: function(formId) {
        const form = document.getElementById(formId);
        if (!form) return false;
        
        // Verificar todos los campos requeridos
        const requiredFields = form.querySelectorAll('[required]');
        let valid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        return valid;
    }
};

// Exportar para uso global
window.Utils = Utils;
