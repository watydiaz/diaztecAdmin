<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
</head>
<body>
    <h1>Crear Cliente</h1>
    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] == 1): ?>
            <p style="color: red;">Error al crear el cliente. Inténtalo de nuevo.</p>
        <?php elseif ($_GET['error'] == 2): ?>
            <p style="color: red;">Por favor, completa los campos obligatorios.</p>
        <?php endif; ?>
    <?php endif; ?>
    <form method="POST" action="index.php?controller=cliente&action=crear">
        <label for="identificacion">Identificación:</label>
        <input type="text" name="identificacion" id="identificacion" required>
        <br>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono">
        <br>
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email">
        <br>
        <label for="direccion">Dirección:</label>
        <textarea name="direccion" id="direccion"></textarea>
        <br>
        <button type="submit">Crear Cliente</button>
    </form>
</body>
</html>