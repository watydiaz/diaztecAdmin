<?php

require_once 'models/TicketModel.php';

class TicketController {
    private $ticketModel;

    public function __construct($db) {
        $this->ticketModel = new TicketModel($db);
    }

    // Listar tickets
    public function listarTickets() {
        return $this->ticketModel->obtenerTickets();
    }

    // Crear un ticket
    public function crearTicket() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente_id = $_POST['cliente_id'];
            $tecnico_id = $_POST['tecnico_id'];
            $tipo_dispositivo = $_POST['tipo_dispositivo'];
            $marca = $_POST['marca'];
            $modelo = $_POST['modelo'];
            $imei_serial = $_POST['imei_serial'];
            $contraseña = $_POST['contraseña'];
            $diagnostico = $_POST['diagnostico'];
            $prioridad = $_POST['prioridad'];
            $proceso = $_POST['proceso'];
            $estado = $_POST['estado'];
            $valor_reparacion = $_POST['valor_reparacion'];
            $costo_repuestos = $_POST['costo_repuestos'];

            if (!empty($cliente_id) && !empty($tipo_dispositivo) && !empty($marca) && !empty($modelo)) {
                $resultado = $this->ticketModel->crearTicket($cliente_id, $tecnico_id, $tipo_dispositivo, $marca, $modelo, $imei_serial, $contraseña, $diagnostico, $prioridad, $proceso, $estado, $valor_reparacion, $costo_repuestos);

                if ($resultado) {
                    header("Location: index.php?controller=ticket&action=listar&success=1");
                    exit();
                } else {
                    header("Location: index.php?controller=ticket&action=crear&error=1");
                    exit();
                }
            } else {
                header("Location: index.php?controller=ticket&action=crear&error=2");
                exit();
            }
        }
    }
}
?>