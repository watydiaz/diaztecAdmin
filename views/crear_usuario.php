<?php
require_once '../assets/bd/bd.php';
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $rol = $_POST['rol'];

    if (!empty($nombre) && !empty($email) && !empty($contraseña) && !empty($rol)) {
        // Encriptar la contraseña
        $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

        // Insertar el usuario en la base de datos
        $query = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nombre, $email, $contraseñaHash, $rol);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Usuario creado correctamente.</p>";
        } else {
            echo "<p style='color: red;'>Error al crear el usuario: " . $conn->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color: red;'>Por favor, completa todos los campos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
</head>
<body>
    <h1>Crear Usuario</h1>
    <form method="POST" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" id="contraseña" required>
        <br>
        <label for="rol">Rol:</label>
        <select name="rol" id="rol" required>
            <option value="admin">Admin</option>
            <option value="tecnico">Técnico</option>
            <option value="cliente">Cliente</option>
        </select>
        <br>
        <button type="submit">Crear Usuario</button>
    </form>
</body>
</html>
<?php require_once 'footer.php'; ?>