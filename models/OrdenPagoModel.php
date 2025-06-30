<?php
class OrdenPagoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Insertar un nuevo pago para una orden
    public function insertarPago($data) {
        $stmt = $this->db->prepare("INSERT INTO orden_pagos (orden_id, usuario_id, fecha_pago, costo_total, valor_repuestos, descripcion_repuestos, metodo_pago, saldo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // Forzar tipos correctos
        $orden_id = (int)$data['orden_id'];
        $usuario_id = (int)$data['usuario_id'];
        $fecha_pago = $data['fecha_pago'];
        $costo_total = (float)$data['costo_total'];
        $valor_repuestos = (float)$data['valor_repuestos'];
        $descripcion_repuestos = $data['descripcion_repuestos'];
        $metodo_pago = $data['metodo_pago'];
        $saldo = (float)$data['saldo'];
        $stmt->bind_param(
            "iisdsssd",
            $orden_id,
            $usuario_id,
            $fecha_pago,
            $costo_total,
            $valor_repuestos,
            $descripcion_repuestos,
            $metodo_pago,
            $saldo
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
