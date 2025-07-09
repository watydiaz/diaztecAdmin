<?php
require_once 'Conexion.php';

class UsuarioModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    // Método para obtener un usuario por su email
    public function obtenerUsuarioPorEmail($email) {
        $query = "SELECT u.*, r.nombre as rol_nombre FROM usuarios u 
                  LEFT JOIN roles r ON u.rol_id = r.id 
                  WHERE u.email = ? AND u.activo = 1";
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

    // Obtener todos los usuarios con sus roles
    public function obtenerTodos() {
        $query = "SELECT u.id, u.nombre, u.email, u.activo, u.fecha_registro,
                         r.nombre as rol_nombre, r.id as rol_id
                  FROM usuarios u 
                  LEFT JOIN roles r ON u.rol_id = r.id 
                  ORDER BY u.fecha_registro DESC";
        
        $resultado = $this->db->query($query);
        $usuarios = [];
        
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        
        return $usuarios;
    }

    // Verificar si un email ya existe
    public function emailExiste($email) {
        $query = "SELECT COUNT(*) as count FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila['count'] > 0;
    }

    // Verificar si un email existe para otro usuario (para actualizaciones)
    public function emailExisteParaOtroUsuario($email, $userId) {
        $query = "SELECT COUNT(*) as count FROM usuarios WHERE email = ? AND id != ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $email, $userId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila['count'] > 0;
    }

    // Crear un nuevo usuario
    public function crear($nombre, $email, $password, $rol_id, $activo = 1) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO usuarios (nombre, email, password, rol_id, activo, fecha_registro) 
                  VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssii', $nombre, $email, $passwordHash, $rol_id, $activo);
        
        return $stmt->execute();
    }

    // Actualizar un usuario
    public function actualizar($id, $nombre, $email, $rol_id, $activo, $password = null) {
        if ($password) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol_id = ?, activo = ? 
                      WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sssiii', $nombre, $email, $passwordHash, $rol_id, $activo, $id);
        } else {
            $query = "UPDATE usuarios SET nombre = ?, email = ?, rol_id = ?, activo = ? 
                      WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ssiii', $nombre, $email, $rol_id, $activo, $id);
        }
        
        return $stmt->execute();
    }

    // Eliminar un usuario
    public function eliminar($id) {
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Obtener usuario por ID
    public function obtenerPorId($id) {
        $query = "SELECT u.id, u.nombre, u.email, u.rol_id, u.activo, u.fecha_registro,
                         r.nombre as rol_nombre
                  FROM usuarios u 
                  LEFT JOIN roles r ON u.rol_id = r.id 
                  WHERE u.id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Cambiar estado de un usuario
    public function cambiarEstado($id, $activo) {
        $query = "UPDATE usuarios SET activo = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $activo, $id);
        return $stmt->execute();
    }

    // Resetear contraseña
    public function resetearPassword($id, $nueva_password) {
        $passwordHash = password_hash($nueva_password, PASSWORD_DEFAULT);
        $query = "UPDATE usuarios SET password = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $passwordHash, $id);
        return $stmt->execute();
    }

    // Obtener estadísticas de usuarios
    public function obtenerEstadisticas() {
        $query = "SELECT 
                    COUNT(*) as total_usuarios,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as usuarios_activos,
                    SUM(CASE WHEN activo = 0 THEN 1 ELSE 0 END) as usuarios_inactivos
                  FROM usuarios";
        
        $resultado = $this->db->query($query);
        return $resultado->fetch_assoc();
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
}
?>
