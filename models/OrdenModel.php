<?php
require_once 'Conexion.php';

class OrdenModel {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function obtenerOrdenes() {
        $query = "SELECT * FROM ordenes_reparacion";
        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarOrden($data) {
        $query = "INSERT INTO ordenes_reparacion (cliente_id, usuario_tecnico_id, marca, modelo, imei_serial, falla_reportada, diagnostico, estado, prioridad, contraseña_equipo, imagen_url, fecha_ingreso, fecha_entrega_estimada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('iisssssssssss', $data['cliente_id'], $data['usuario_tecnico_id'], $data['marca'], $data['modelo'], $data['imei_serial'], $data['falla_reportada'], $data['diagnostico'], $data['estado'], $data['prioridad'], $data['contraseña_equipo'], $data['imagen_url'], $data['fecha_ingreso'], $data['fecha_entrega_estimada']);
        return $stmt->execute();
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
}