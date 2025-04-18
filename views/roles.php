<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="roles-container">
        <h1>Gestión de Roles</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Permisos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $rol): ?>
                    <tr>
                        <td><?php echo $rol['id']; ?></td>
                        <td><?php echo $rol['nombre']; ?></td>
                        <td>
                            <ul>
                                <?php 
                                $permisos = $this->rolModel->obtenerPermisosPorRol($rol['id']);
                                foreach ($permisos as $permiso): ?>
                                    <li><?php echo $permiso; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Asignar Permiso a Rol</h2>
        <form action="index.php?action=asignarPermiso" method="POST">
            <label for="rol_id">Rol:</label>
            <select name="rol_id" id="rol_id">
                <?php foreach ($roles as $rol): ?>
                    <option value="<?php echo $rol['id']; ?>"><?php echo $rol['nombre']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="permiso_id">Permiso:</label>
            <select name="permiso_id" id="permiso_id">
                <?php 
                $query = "SELECT * FROM permisos";
                $resultado = $this->rolModel->db->query($query);
                $permisos = $resultado->fetch_all(MYSQLI_ASSOC);
                foreach ($permisos as $permiso): ?>
                    <option value="<?php echo $permiso['id']; ?>"><?php echo $permiso['nombre']; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Asignar</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>