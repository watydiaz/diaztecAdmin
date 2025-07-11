<?php
// Controlador para gestionar el inicio de sesión
class LoginController {
    private $usuarioModel;

    public function __construct($usuarioModel) {
        $this->usuarioModel = $usuarioModel;
    }

    // Método para mostrar la vista de login
    public function mostrarLogin() {
        include 'views/login.php';
    }

    // Método para manejar el inicio de sesión
    public function iniciarSesion($email, $password) {
        // Obtener el usuario por email
        $usuario = $this->usuarioModel->obtenerUsuarioPorEmail($email);

        if ($usuario && $this->usuarioModel->verificarPassword($password, $usuario['password'])) {
            // SESIONES DESHABILITADAS TEMPORALMENTE
            // session_start();
            // $_SESSION['usuario_id'] = $usuario['id'];
            // $_SESSION['usuario_nombre'] = $usuario['nombre'];
            // $_SESSION['usuario_email'] = $usuario['email'];
            // $_SESSION['usuario_rol'] = $usuario['rol'];
            
            // Redirigir al dashboard
            header('Location: index.php?action=dashboard');
            exit();
        } else {
            // Mostrar error en caso de credenciales incorrectas
            $error = 'Credenciales incorrectas';
            include 'views/login.php';
        }
    }

    // Método para cerrar sesión
    public function cerrarSesion() {
        // SESIONES DESHABILITADAS TEMPORALMENTE
        // session_start();
        // session_destroy();
        header('Location: index.php?action=login');
        exit();
    }

    // Método para obtener técnicos (llamando directamente a la base y retornando tal cual, sin lógica extra)
    public function obtenerTecnicos() {
        $tecnicos = $this->usuarioModel->obtenerTecnicos();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'tecnicos' => $tecnicos]);
        exit();
    }
}
