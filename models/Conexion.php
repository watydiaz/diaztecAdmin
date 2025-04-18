<?php
// Archivo para gestionar la conexión a la base de datos
class Conexion {
    private static $host = 'localhost';
    private static $usuario = 'root';
    private static $password = '';
    private static $baseDatos = 'reparaciones_taller';
    private static $conexion;

    // Método para obtener la conexión
    public static function getConexion() {
        if (!self::$conexion) {
            self::$conexion = new mysqli(self::$host, self::$usuario, self::$password, self::$baseDatos);

            if (self::$conexion->connect_error) {
                die('Error de conexión a la base de datos: ' . self::$conexion->connect_error);
            } else {
                echo '<p style="color: green;">Conexión a la base de datos exitosa</p>';
            }
        }

        return self::$conexion;
    }

    // Método para cerrar la conexión
    public static function cerrarConexion() {
        if (self::$conexion) {
            self::$conexion->close();
            self::$conexion = null;
        }
    }
}
