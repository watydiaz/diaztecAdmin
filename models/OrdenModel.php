<?php
require_once 'Conexion.php';

class OrdenModel {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function obtenerOrdenes() {
        $query = "SELECT o.id, c.nombre AS cliente_nombre, c.telefono AS telefono_cliente, u.nombre AS tecnico_nombre, o.marca, o.modelo, o.falla_reportada, o.estado, o.prioridad, o.fecha_ingreso FROM ordenes_reparacion o INNER JOIN clientes c ON o.cliente_id = c.id INNER JOIN usuarios u ON o.usuario_tecnico_id = u.id";
        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarOrden($data) {
        $query = "INSERT INTO ordenes_reparacion (cliente_id, usuario_tecnico_id, marca, modelo, imei_serial, falla_reportada, diagnostico, estado, prioridad, contraseña_equipo, imagen_url, fecha_ingreso, fecha_entrega_estimada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            error_log('Error en la preparación de la consulta: ' . $this->conexion->error);
            return false;
        }

        $stmt->bind_param('iisssssssssss', $data['cliente_id'], $data['usuario_tecnico_id'], $data['marca'], $data['modelo'], $data['imei_serial'], $data['falla_reportada'], $data['diagnostico'], $data['estado'], $data['prioridad'], $data['contraseña_equipo'], $data['imagen_url'], $data['fecha_ingreso'], $data['fecha_entrega_estimada']);

        if (!$stmt->execute()) {
            error_log('Error al ejecutar la consulta: ' . $stmt->error);
            return false;
        }

        return true;
    }

    public function obtenerOrdenPorId($id) {
        $query = "SELECT * FROM ordenes_reparacion WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarOrden($id, $data) {
        $query = "UPDATE ordenes_reparacion SET cliente_id = ?, usuario_tecnico_id = ?, marca = ?, modelo = ?, imei_serial = ?, falla_reportada = ?, diagnostico = ?, estado = ?, prioridad = ?, contraseña_equipo = ?, imagen_url = ?, fecha_ingreso = ?, fecha_entrega_estimada = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('iisssssssssssi', $data['cliente_id'], $data['usuario_tecnico_id'], $data['marca'], $data['modelo'], $data['imei_serial'], $data['falla_reportada'], $data['diagnostico'], $data['estado'], $data['prioridad'], $data['contraseña_equipo'], $data['imagen_url'], $data['fecha_ingreso'], $data['fecha_entrega_estimada'], $id);
        return $stmt->execute();
    }

    public function eliminarOrden($id) {
        $query = "DELETE FROM ordenes_reparacion WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function contarOrdenesPorEstado($estado) {
        $query = "SELECT COUNT(*) as total FROM ordenes_reparacion WHERE estado = ?";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            error_log('Error en la preparación de la consulta contarOrdenesPorEstado: ' . $this->conexion->error);
            return 0;
        }

        $stmt->bind_param('s', $estado);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if (!$resultado) {
            error_log('Error al ejecutar la consulta contarOrdenesPorEstado: ' . $stmt->error);
            return 0;
        }

        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }
}