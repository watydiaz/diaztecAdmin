<?php
// Archivo temporal para generar un hash de contraseña
// Accede a este archivo desde el navegador para obtener el hash

// Contraseña a hashear
$password = 'admin2025';

// Generar el hash
$hash = password_hash($password, PASSWORD_DEFAULT);

// Mostrar el hash
echo "Hash generado para la contraseña '$password':<br>";
echo $hash;
