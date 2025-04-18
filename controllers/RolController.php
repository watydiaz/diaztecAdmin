<?php
// Controlador para gestionar roles y permisos
class RolController {
    private $rolModel;

    public function __construct($rolModel) {
        $this->rolModel = $rolModel;
    }

    // Método para mostrar la vista de roles
    public function mostrarRoles() {
        $roles = $this->rolModel->obtenerRoles();
        include 'views/roles.php';
    }

    // Método para asignar un permiso a un rol
    public function asignarPermiso($rolId, $permisoId) {
        if ($this->rolModel->asignarPermisoARol($rolId, $permisoId)) {
            echo "<p style='color: green;'>Permiso asignado correctamente.</p>";
        } else {
            echo "<p style='color: red;'>Error al asignar el permiso.</p>";
        }
    }

    // Método para eliminar un permiso de un rol
    public function eliminarPermiso($rolId, $permisoId) {
        if ($this->rolModel->eliminarPermisoDeRol($rolId, $permisoId)) {
            echo "<p style='color: green;'>Permiso eliminado correctamente.</p>";
        } else {
            echo "<p style='color: red;'>Error al eliminar el permiso.</p>";
        }
    }
}
