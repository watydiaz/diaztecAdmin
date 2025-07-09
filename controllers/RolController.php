<?php
require_once 'models/RolModel.php';

class RolController {
    private $rolModel;

    public function __construct($rolModel = null) {
        if ($rolModel) {
            $this->rolModel = $rolModel;
        } else {
            $this->rolModel = new RolModel();
        }
    }

    // Método para mostrar la vista de roles
    public function mostrarRoles() {
        $roles = $this->rolModel->obtenerRoles();
        include 'views/roles.php';
    }

    // Mostrar la vista de gestión de roles
    public function index() {
        include 'views/roles_gestion.php';
    }

    // Listar roles con permisos (para AJAX)
    public function listar() {
        try {
            $roles = $this->rolModel->obtenerRolesConPermisos();
            
            echo json_encode([
                'success' => true,
                'roles' => $roles
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Listar permisos disponibles
    public function listarPermisos() {
        try {
            $permisos = $this->rolModel->obtenerPermisos();
            
            echo json_encode([
                'success' => true,
                'permisos' => $permisos
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Crear un nuevo rol
    public function crear() {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $permisos = $_POST['permisos'] ?? [];

            // Validaciones
            if (empty($nombre)) {
                throw new Exception('El nombre del rol es obligatorio');
            }

            if ($this->rolModel->rolExiste($nombre)) {
                throw new Exception('Ya existe un rol con ese nombre');
            }

            $resultado = $this->rolModel->crearRol($nombre, $permisos);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Rol creado exitosamente'
                ]);
            } else {
                throw new Exception('Error al crear el rol');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Actualizar un rol
    public function actualizar() {
        try {
            $id = $_POST['id'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $permisos = $_POST['permisos'] ?? [];

            // Validaciones
            if (empty($id) || empty($nombre)) {
                throw new Exception('ID y nombre del rol son obligatorios');
            }

            if ($this->rolModel->rolExiste($nombre, $id)) {
                throw new Exception('Ya existe otro rol con ese nombre');
            }

            $resultado = $this->rolModel->actualizarRol($id, $nombre, $permisos);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Rol actualizado exitosamente'
                ]);
            } else {
                throw new Exception('Error al actualizar el rol');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Eliminar un rol
    public function eliminar() {
        try {
            $id = $_POST['id'] ?? '';

            if (empty($id)) {
                throw new Exception('ID de rol requerido');
            }

            $resultado = $this->rolModel->eliminarRol($id);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Rol eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar el rol');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Método para asignar un permiso a un rol (compatibilidad)
    public function asignarPermiso($rolId, $permisoId) {
        if ($this->rolModel->asignarPermisoARol($rolId, $permisoId)) {
            echo "<p style='color: green;'>Permiso asignado correctamente.</p>";
        } else {
            echo "<p style='color: red;'>Error al asignar el permiso.</p>";
        }
    }

    // Método para eliminar un permiso de un rol (compatibilidad)
    public function eliminarPermiso($rolId, $permisoId) {
        if ($this->rolModel->eliminarPermisoDeRol($rolId, $permisoId)) {
            echo "<p style='color: green;'>Permiso eliminado correctamente.</p>";
        } else {
            echo "<p style='color: red;'>Error al eliminar el permiso.</p>";
        }
    }
}
