<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Sistema de Tickets'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="https://diaztecnologia.com/img/logo.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="https://diaztecnologia.com/img/logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        nav {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: black; /* Fondo negro para que sea visible */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra para destacar */
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .nav-logo {
            display: flex;
            align-items: center;
        }

        .nav-logo img {
            height: 80px;
            margin-right: 10px;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .menu-toggle div {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 3px 0;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                align-items: center;
                width: 100%;
                margin-top: 10px;
                background-color: black;
            }

            .nav-links.active {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-logo">
            <img src="https://diaztecnologia.com/img/logo.png" alt="Logo">
            <span style="color: white; font-size: 18px;">Sistema de Tickets</span>
        </div>
        <div class="menu-toggle" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="nav-links">
            <a href="index.php?action=dashboard">Inicio</a>
            <a href="index.php?action=clientes">Clientes</a>
            <a href="index.php?action=ordenes">Órdenes de Trabajo</a>
            <a href="index.php?action=inventario">Inventario</a>
            <a href="index.php?action=caja">Caja</a>
            <a href="index.php?action=usuarios">Usuarios</a>
            <a href="index.php?action=rolesGestion">Roles</a>
        </div>
    </nav>
    <script>
        function toggleMenu() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }
    </script>
    <main>
