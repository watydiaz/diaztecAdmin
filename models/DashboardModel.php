<?php
class DashboardModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtiene el total de pagos realizados hoy
    public function getPagosDiarios() {
        $sql = "SELECT IFNULL(SUM(costo_total - saldo),0) as total_diario FROM orden_pagos WHERE DATE(fecha_pago) = CURDATE()";
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }

    // Obtiene el total de pagos realizados en el mes actual
    public function getPagosMensuales() {
        $sql = "SELECT IFNULL(SUM(costo_total - saldo),0) as total_mensual FROM orden_pagos WHERE YEAR(fecha_pago) = YEAR(CURDATE()) AND MONTH(fecha_pago) = MONTH(CURDATE())";
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }
}
