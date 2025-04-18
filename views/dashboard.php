<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="dashboard-container">
        <h1>Bienvenido al Sistema de Gestión</h1>
        <p>Seleccione una opción del menú para comenzar.</p>

        <div class="dashboard-links">
            <a href="index.php?action=clientes">Gestión de Clientes</a>
            <a href="index.php?action=ordenes">Gestión de Órdenes</a>
            <a href="index.php?action=inventario">Gestión de Inventario</a>
            <a href="index.php?action=usuarios">Gestión de Usuarios</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>