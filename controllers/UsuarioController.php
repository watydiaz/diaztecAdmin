<?php
require_once 'models/UsuarioModel.php';
require_once 'models/RolModel.php';

class UsuarioController {
    private $usuarioModel;
    private $rolModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        $this->rolModel = new RolModel();
    }

    public function index() {
        $usuarios = $this->usuarioModel->obtenerTodos();
        $roles = $this->rolModel->obtenerTodos();
        include 'views/usuarios.php';
    }

    public function listar() {
        try {
            $usuarios = $this->usuarioModel->obtenerTodos();
            $roles = $this->rolModel->obtenerTodos();
            
            echo json_encode([
                'success' => true,
                'usuarios' => $usuarios,
                'roles' => $roles
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function crear() {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol_id = $_POST['rol_id'] ?? '';
            $activo = isset($_POST['activo']) ? 1 : 0;

            // Validaciones
            if (empty($nombre) || empty($email) || empty($password) || empty($rol_id)) {
                throw new Exception('Todos los campos son obligatorios');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El email no es válido');
            }

            if (strlen($password) < 6) {
                throw new Exception('La contraseña debe tener al menos 6 caracteres');
            }

            // Verificar si el email ya existe
            if ($this->usuarioModel->emailExiste($email)) {
                throw new Exception('El email ya está registrado');
            }

            $resultado = $this->usuarioModel->crear($nombre, $email, $password, $rol_id, $activo);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario creado exitosamente'
                ]);
            } else {
                throw new Exception('Error al crear el usuario');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function actualizar() {
        try {
            $id = $_POST['id'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $rol_id = $_POST['rol_id'] ?? '';
            $activo = isset($_POST['activo']) ? 1 : 0;
            $password = $_POST['password'] ?? null;

            // Validaciones
            if (empty($id) || empty($nombre) || empty($email) || empty($rol_id)) {
                throw new Exception('Todos los campos son obligatorios');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El email no es válido');
            }

            // Verificar si el email ya existe para otro usuario
            if ($this->usuarioModel->emailExisteParaOtroUsuario($email, $id)) {
                throw new Exception('El email ya está registrado para otro usuario');
            }

            // Si se proporciona nueva contraseña, validarla
            if (!empty($password) && strlen($password) < 6) {
                throw new Exception('La contraseña debe tener al menos 6 caracteres');
            }

            $resultado = $this->usuarioModel->actualizar($id, $nombre, $email, $rol_id, $activo, $password);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario actualizado exitosamente'
                ]);
            } else {
                throw new Exception('Error al actualizar el usuario');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function eliminar() {
        try {
            $id = $_POST['id'] ?? '';

            if (empty($id)) {
                throw new Exception('ID de usuario requerido');
            }

            // No permitir eliminar al usuario actual
            session_start();
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                throw new Exception('No puedes eliminar tu propio usuario');
            }

            $resultado = $this->usuarioModel->eliminar($id);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar el usuario');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function obtenerPorId() {
        try {
            $id = $_GET['id'] ?? '';

            if (empty($id)) {
                throw new Exception('ID de usuario requerido');
            }

            $usuario = $this->usuarioModel->obtenerPorId($id);
            
            if ($usuario) {
                echo json_encode([
                    'success' => true,
                    'usuario' => $usuario
                ]);
            } else {
                throw new Exception('Usuario no encontrado');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function cambiarEstado() {
        try {
            $id = $_POST['id'] ?? '';
            $activo = isset($_POST['activo']) ? 1 : 0;

            if (empty($id)) {
                throw new Exception('ID de usuario requerido');
            }

            // No permitir desactivar al usuario actual
            session_start();
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id && $activo == 0) {
                throw new Exception('No puedes desactivar tu propio usuario');
            }

            $resultado = $this->usuarioModel->cambiarEstado($id, $activo);
            
            if ($resultado) {
                $estado_texto = $activo ? 'activado' : 'desactivado';
                echo json_encode([
                    'success' => true,
                    'message' => "Usuario {$estado_texto} exitosamente"
                ]);
            } else {
                throw new Exception('Error al cambiar el estado del usuario');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function resetearPassword() {
        try {
            $id = $_POST['id'] ?? '';
            $nueva_password = $_POST['nueva_password'] ?? '';

            if (empty($id) || empty($nueva_password)) {
                throw new Exception('ID de usuario y nueva contraseña requeridos');
            }

            if (strlen($nueva_password) < 6) {
                throw new Exception('La contraseña debe tener al menos 6 caracteres');
            }

            $resultado = $this->usuarioModel->resetearPassword($id, $nueva_password);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Contraseña restablecida exitosamente'
                ]);
            } else {
                throw new Exception('Error al restablecer la contraseña');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function obtenerEstadisticas() {
        try {
            $estadisticas = $this->usuarioModel->obtenerEstadisticas();
            
            echo json_encode([
                'success' => true,
                'estadisticas' => $estadisticas
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $datos = [
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'rol_id' => $_POST['rol_id'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            // Solo actualizar password si se proporciona uno nuevo
            if (!empty($_POST['password'])) {
                $datos['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($this->usuarioModel->actualizar($id, $datos)) {
                echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario']);
            }
        }
    }

    public function obtener() {
        $id = $_GET['id'];
        $usuario = $this->usuarioModel->obtenerPorId($id);
        
        if ($usuario) {
            echo json_encode(['success' => true, 'usuario' => $usuario]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    }

    // Obtener ID del usuario logueado (para uso desde AJAX)
    public function getUserId() {
        header('Content-Type: application/json');
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['usuario_id'])) {
            echo json_encode([
                'success' => true,
                'usuario_id' => $_SESSION['usuario_id']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No hay usuario en sesión'
            ]);
        }
    }
}
?>
