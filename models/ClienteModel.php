<?php

class ClienteModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener todos los clientes
    public function obtenerClientes() {
        $query = "SELECT * FROM clientes";
        $result = $this->db->query($query);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    // Crear un nuevo cliente
    public function crearCliente($identificacion, $nombre, $telefono, $email, $direccion) {
        $query = "INSERT INTO clientes (identificacion, nombre, telefono, email, direccion) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("sssss", $identificacion, $nombre, $telefono, $email, $direccion);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}
?>