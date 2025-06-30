<?php
class OrdenPagoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Insertar un nuevo pago para una orden
    public function insertarPago($data) {
        $stmt = $this->db->prepare("INSERT INTO orden_pagos (orden_id, usuario_id, fecha_pago, costo_total, valor_repuestos, descripcion_repuestos, metodo_pago, saldo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iissdssd",
            $data['orden_id'],
            $data['usuario_id'],
            $data['fecha_pago'],
            $data['costo_total'],
            $data['valor_repuestos'],
            $data['descripcion_repuestos'],
            $data['metodo_pago'],
            $data['saldo']
        );
        return $stmt->execute();
    }

    // Obtener pagos de una orden específica
    public function obtenerPagosPorOrden($orden_id) {
        $stmt = $this->db->prepare("SELECT * FROM orden_pagos WHERE orden_id = ? ORDER BY fecha_pago DESC");
        $stmt->bind_param("i", $orden_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
