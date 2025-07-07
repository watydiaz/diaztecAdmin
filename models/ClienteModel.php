<?php
require_once 'Conexion.php';

class ClienteModel {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function obtenerClientes() {
        $query = "SELECT * FROM clientes";
        $resultado = $this->conexion->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarCliente($nombre, $identificacion, $telefono, $email, $direccion) {
        $query = "INSERT INTO clientes (nombre, identificacion, telefono, email, direccion) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('sssss', $nombre, $identificacion, $telefono, $email, $direccion);
        return $stmt->execute();
    }

    public function obtenerClientePorId($id) {
        $query = "SELECT * FROM clientes WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarCliente($id, $nombre, $identificacion, $telefono, $email, $direccion) {
        $query = "UPDATE clientes SET nombre = ?, identificacion = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('sssssi', $nombre, $identificacion, $telefono, $email, $direccion, $id);
        return $stmt->execute();
    }

    public function eliminarCliente($id) {
        $query = "DELETE FROM clientes WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function buscarClientesPorNombreOIdentificacion($query) {
        $query = "%" . $this->conexion->real_escape_string($query) . "%";
        $sql = "SELECT * FROM clientes WHERE UPPER(nombre) LIKE UPPER(?) OR UPPER(identificacion) LIKE UPPER(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ss', $query, $query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function contarClientes() {
        $query = "SELECT COUNT(*) as total FROM clientes";
        $resultado = $this->conexion->query($query);

        if (!$resultado) {
            error_log('Error en la consulta contarClientes: ' . $this->conexion->error);
            return 0;
        }

        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }
}