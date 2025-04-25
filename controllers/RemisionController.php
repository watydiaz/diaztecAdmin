<?php
require_once 'models/OrdenModel.php';

class RemisionController {
    private $ordenModel;

    public function __construct() {
        $this->ordenModel = new OrdenModel();
    }

    // Métodos relacionados con la remisión
}