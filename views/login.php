<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="https://diaztecnologia.com/img/logo.png " type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 70vh;
        }

        header {
            margin-bottom: 20px;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #000000; /* Cambiado a negro */
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #333333; /* Negro más claro para hover */
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Logo -->
    <center>
        <div class="logo-container">
            <img src="https://diaztecnologia.com/img/logo.png" alt="Logo" class="logo" width="200">
        </div>
    </center>

    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <p class="error"> <?php echo $error; ?> </p>
        <?php endif; ?>
        <form action="index.php?action=login" method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-dark" >Ingresar</button>
        </form>
    </div>

</body>
</html>