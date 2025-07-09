<?php
require_once 'Conexion.php';

// Modelo para gestionar los usuarios y la autenticación
class UsuarioModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConnection();
    }

    // Método para obtener todos los usuarios con información del rol
    public function obtenerTodos() {
        $query = "SELECT u.*, r.nombre as rol_nombre 
                  FROM usuarios u 
                  LEFT JOIN roles r ON u.rol_id = r.id 
                  ORDER BY u.id DESC";
        $resultado = $this->db->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Método para obtener un usuario por ID
    public function obtenerPorId($id) {
        $query = "SELECT u.*, r.nombre as rol_nombre 
                  FROM usuarios u 
                  LEFT JOIN roles r ON u.rol_id = r.id 
                  WHERE u.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Método para crear un nuevo usuario
    public function crear($datos) {
        $query = "INSERT INTO usuarios (nombre, email, password, rol_id, activo) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssii', 
            $datos['nombre'], 
            $datos['email'], 
            $datos['password'], 
            $datos['rol_id'], 
            $datos['activo']
        );
        return $stmt->execute();
    }

    // Método para actualizar un usuario
    public function actualizar($id, $datos) {
        if (isset($datos['password'])) {
            $query = "UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol_id = ?, activo = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sssiii', 
                $datos['nombre'], 
                $datos['email'], 
                $datos['password'], 
                $datos['rol_id'], 
                $datos['activo'], 
                $id
            );
        } else {
            $query = "UPDATE usuarios SET nombre = ?, email = ?, rol_id = ?, activo = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ssiii', 
                $datos['nombre'], 
                $datos['email'], 
                $datos['rol_id'], 
                $datos['activo'], 
                $id
            );
        }
        return $stmt->execute();
    }

    // Método para eliminar un usuario
    public function eliminar($id) {
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Método para cambiar estado activo/inactivo
    public function cambiarEstado($id, $estado) {
        $query = "UPDATE usuarios SET activo = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $estado, $id);
        return $stmt->execute();
    }

    // Método para obtener un usuario por su email (para login)
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

    // Método para obtener técnicos
    public function obtenerTecnicos() {
        $sql = "SELECT u.id, u.nombre, u.email 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.activo = 1 AND r.nombre LIKE '%tecnico%'
                GROUP BY u.id, u.nombre, u.email";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Método para verificar si un email ya existe
    public function emailExiste($email, $excluirId = null) {
        if ($excluirId) {
            $query = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('si', $email, $excluirId);
        } else {
            $query = "SELECT id FROM usuarios WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $email);
        }
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0;
    }
}
?>
