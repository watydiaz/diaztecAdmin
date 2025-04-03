<?php require_once 'header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <p style="color: red;">Correo o contraseña incorrectos.</p>
    <?php endif; ?>
    <form action="index.php?controller=login&action=login" method="POST">
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
<?php require_once 'footer.php'; ?>