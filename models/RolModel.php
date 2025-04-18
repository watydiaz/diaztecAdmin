<?php
// Modelo para gestionar roles y permisos
class RolModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Método para obtener todos los roles
    public function obtenerRoles() {
        $query = "SELECT * FROM roles";
        $resultado = $this->db->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Método para obtener permisos de un rol
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

    // Método para asignar un permiso a un rol
    public function asignarPermisoARol($rolId, $permisoId) {
        $query = "INSERT INTO rol_permiso (rol_id, permiso_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $rolId, $permisoId);
        return $stmt->execute();
    }

    // Método para eliminar un permiso de un rol
    public function eliminarPermisoDeRol($rolId, $permisoId) {
        $query = "DELETE FROM rol_permiso WHERE rol_id = ? AND permiso_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $rolId, $permisoId);
        return $stmt->execute();
    }
}
