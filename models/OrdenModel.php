<?php

class OrdenModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listarOrdenes() {
        $query = "SELECT o.*, c.nombre AS cliente_nombre FROM ordenes_reparacion o JOIN clientes c ON o.cliente_id = c.id";
        $result = $this->db->query($query);

        $ordenes = [];
        while ($row = $result->fetch_assoc()) {
            $ordenes[] = $row;
        }

        return $ordenes;
    }

    public function agregarOrden($cliente_id, $usuario_tecnico_id, $marca, $modelo, $imei_serial, $falla_reportada, $diagnostico, $estado, $prioridad, $contraseña_equipo, $imagen_url, $fecha_ingreso, $fecha_entrega_estimada) {
        $stmt = $this->db->prepare("INSERT INTO ordenes_reparacion (cliente_id, usuario_tecnico_id, marca, modelo, imei_serial, falla_reportada, diagnostico, estado, prioridad, contraseña_equipo, imagen_url, fecha_ingreso, fecha_entrega_estimada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssssssssss", $cliente_id, $usuario_tecnico_id, $marca, $modelo, $imei_serial, $falla_reportada, $diagnostico, $estado, $prioridad, $contraseña_equipo, $imagen_url, $fecha_ingreso, $fecha_entrega_estimada);

        return $stmt->execute();
    }

    public function obtenerTecnicos() {
        $query = "SELECT id, nombre FROM usuarios WHERE rol = 'tecnico'";
        $result = $this->db->query($query);

        $tecnicos = [];
        while ($row = $result->fetch_assoc()) {
            $tecnicos[] = $row;
        }

        return $tecnicos;
    }
}