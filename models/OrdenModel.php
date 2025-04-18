<?php

class OrdenModel {
    private $db;

    public function __construct() {
        require_once 'Conexion.php';
        $this->db = Conexion::getConexion();
    }

    public function listarOrdenes() {
        $query = $this->db->prepare("SELECT * FROM ordenes_reparacion");
        $query->execute();
        return $query->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarOrden($cliente_id, $usuario_tecnico_id, $marca, $modelo, $imei_serial, $falla_reportada, $diagnostico, $estado, $prioridad, $contraseña_equipo, $imagen_url, $fecha_ingreso, $fecha_entrega_estimada) {
        $query = $this->db->prepare(
            "INSERT INTO ordenes_reparacion (cliente_id, usuario_tecnico_id, marca, modelo, imei_serial, falla_reportada, diagnostico, estado, prioridad, contraseña_equipo, imagen_url, fecha_ingreso, fecha_entrega_estimada) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $query->execute([
            $cliente_id, $usuario_tecnico_id, $marca, $modelo, $imei_serial, $falla_reportada, $diagnostico, $estado, $prioridad, $contraseña_equipo, $imagen_url, $fecha_ingreso, $fecha_entrega_estimada
        ]);
    }
}
