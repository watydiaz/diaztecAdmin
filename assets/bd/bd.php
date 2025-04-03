<?php

$host = 'localhost'; // Cambia si usas otro host
$user = 'root'; // Usuario de MySQL
$password = ''; // Contraseña de MySQL
$database = 'sistema_tickets'; // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    echo "Conexión exitosa a la base de datos.";
}
?>