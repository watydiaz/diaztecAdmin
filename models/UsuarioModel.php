<?php
// Modelo para gestionar los usuarios y la autenticación
class UsuarioModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Método para obtener un usuario por su email
    public function obtenerUsuarioPorEmail($email) {
        $query = "SELECT * FROM usuarios WHERE email = ? AND activo = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Método para verificar la contraseña
    public function verificarPassword($passwordIngresado, $passwordHash) {
        return password_verify($passwordIngresado, $passwordHash);
    }

    // Método para obtener los permisos de un usuario según su rol
    public function obtenerPermisosPorRol($rolId) {
        $query = "SELECT p.nombre FROM permisos p
                  INNER JOIN rol_permiso rp ON p.id = rp.permiso_id
                  WHERE rp.rol_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $rolId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $permisos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $permisos[] = $fila['nombre'];
        }
        return $permisos;
    }

    // Método para obtener técnicos (consulta más robusta, solo IDs únicos y sin confiar en rol_id)
    public function obtenerTecnicos() {
        $sql = "SELECT u.id, u.nombre, u.email FROM usuarios u WHERE u.activo = 1 AND EXISTS (SELECT 1 FROM roles r WHERE r.id = u.rol_id AND r.nombre LIKE '%tecnico%') GROUP BY u.id, u.nombre, u.email";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
