<?php
require_once 'Conexion.php';

class RolModel {
    private $db;

    public function __construct($dbConnection = null) {
        if ($dbConnection) {
            $this->db = $dbConnection;
        } else {
            $conexion = new Conexion();
            $this->db = $conexion->getConexion();
        }
    }

    // Método para obtener todos los roles
    public function obtenerRoles() {
        $query = "SELECT * FROM roles ORDER BY nombre";
        $resultado = $this->db->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Alias para compatibilidad
    public function obtenerTodos() {
        return $this->obtenerRoles();
    }

    // Obtener todos los roles con sus permisos
    public function obtenerRolesConPermisos() {
        $roles = $this->obtenerRoles();
        
        foreach ($roles as &$rol) {
            $rol['permisos'] = $this->obtenerPermisosPorRol($rol['id']);
        }
        
        return $roles;
    }

    // Obtener todos los permisos disponibles
    public function obtenerPermisos() {
        $query = "SELECT * FROM permisos ORDER BY nombre";
        $resultado = $this->db->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Crear un nuevo rol
    public function crearRol($nombre, $permisos = []) {
        try {
            $this->db->begin_transaction();
            
            // Insertar rol
            $query = "INSERT INTO roles (nombre) VALUES (?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $nombre);
            $stmt->execute();
            
            $rolId = $this->db->insert_id;
            
            // Asignar permisos si se proporcionaron
            if (!empty($permisos)) {
                foreach ($permisos as $permisoId) {
                    $this->asignarPermisoARol($rolId, $permisoId);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    // Actualizar un rol
    public function actualizarRol($id, $nombre, $permisos = []) {
        try {
            $this->db->begin_transaction();
            
            // Actualizar nombre del rol
            $query = "UPDATE roles SET nombre = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('si', $nombre, $id);
            $stmt->execute();
            
            // Eliminar permisos existentes
            $query = "DELETE FROM rol_permiso WHERE rol_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            
            // Asignar nuevos permisos
            if (!empty($permisos)) {
                foreach ($permisos as $permisoId) {
                    $this->asignarPermisoARol($id, $permisoId);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    // Eliminar un rol
    public function eliminarRol($id) {
        try {
            $this->db->begin_transaction();
            
            // Verificar que el rol no esté en uso
            $query = "SELECT COUNT(*) as count FROM usuarios WHERE rol_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $fila = $resultado->fetch_assoc();
            
            if ($fila['count'] > 0) {
                throw new Exception('No se puede eliminar el rol porque está siendo usado por usuarios');
            }
            
            // Eliminar permisos del rol
            $query = "DELETE FROM rol_permiso WHERE rol_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            
            // Eliminar rol
            $query = "DELETE FROM roles WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    // Verificar si un rol existe
    public function rolExiste($nombre, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM roles WHERE nombre = ?";
        $params = [$nombre];
        $types = 's';
        
        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
            $types .= 'i';
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        
        return $fila['count'] > 0;
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
