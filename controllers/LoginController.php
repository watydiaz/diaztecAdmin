<?php

require_once 'models/UsuarioModel.php';

class LoginController {
    private $usuarioModel;

    public function __construct($db) {
        $this->usuarioModel = new UsuarioModel($db);
    }

    // Método para manejar el login
    public function login($email, $password) {
        $usuario = $this->usuarioModel->obtenerUsuarioPorEmail($email);

        if ($usuario && password_verify($password, $usuario['contraseña'])) {
            // Iniciar sesión
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Redirigir al dashboard
            header("Location: index.php?controller=dashboard&action=home");
            exit();
        }

        // Si las credenciales son incorrectas, redirigir al login con un mensaje de error
        header("Location: index.php?controller=login&action=showLogin&error=1");
        exit();
    }

    // Método para manejar el logout
    public function logout() {
        session_start();
        session_unset();
        session_destroy();

        // Redirigir al login
        header("Location: index.php?controller=login&action=showLogin");
        exit();
    }
}
?>