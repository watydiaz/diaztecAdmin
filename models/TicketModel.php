<?php

class TicketModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener todos los tickets
    public function obtenerTickets() {
        $query = "SELECT t.*, c.nombre AS cliente_nombre, u.nombre AS tecnico_nombre 
                  FROM tickets t
                  JOIN clientes c ON t.cliente_id = c.id
                  LEFT JOIN usuarios u ON t.tecnico_id = u.id";
        $result = $this->db->query($query);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    // Crear un nuevo ticket
    public function crearTicket($cliente_id, $tecnico_id, $tipo_dispositivo, $marca, $modelo, $imei_serial, $contraseña, $diagnostico, $prioridad, $proceso, $estado, $valor_reparacion, $costo_repuestos) {
        $query = "INSERT INTO tickets (cliente_id, tecnico_id, tipo_dispositivo, marca, modelo, imei_serial, contraseña, diagnostico, prioridad, proceso, estado, valor_reparacion, costo_repuestos) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("iissssssssdds", $cliente_id, $tecnico_id, $tipo_dispositivo, $marca, $modelo, $imei_serial, $contraseña, $diagnostico, $prioridad, $proceso, $estado, $valor_reparacion, $costo_repuestos);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}
?>