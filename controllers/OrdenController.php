<?php
require_once 'models/OrdenModel.php';

class OrdenController {
    private $model;

    public function __construct() {
        $this->model = new OrdenModel();
    }

    public function listarOrdenes() {
        return $this->model->listarOrdenes();
    }

    public function agregarOrden($data) {
        $cliente_id = $data['cliente_id'];
        $usuario_tecnico_id = $data['usuario_tecnico_id'];
        $marca = $data['marca'];
        $modelo = $data['modelo'];
        $imei_serial = $data['imei_serial'];
        $falla_reportada = $data['falla_reportada'];
        $diagnostico = $data['diagnostico'];
        $estado = $data['estado'];
        $prioridad = $data['prioridad'];
        $contraseña_equipo = $data['contraseña_equipo'];
        $imagen_url = $data['imagen_url'];
        $fecha_ingreso = $data['fecha_ingreso'];
        $fecha_entrega_estimada = $data['fecha_entrega_estimada'];

        return $this->model->agregarOrden($cliente_id, $usuario_tecnico_id, $marca, $modelo, $imei_serial, $falla_reportada, $diagnostico, $estado, $prioridad, $contraseña_equipo, $imagen_url, $fecha_ingreso, $fecha_entrega_estimada);
    }
}
