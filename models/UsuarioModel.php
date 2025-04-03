<?php

class UsuarioModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerUsuarioPorEmail($email) {
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

?>
