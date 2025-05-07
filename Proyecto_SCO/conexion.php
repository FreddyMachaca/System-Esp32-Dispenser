<?php
date_default_timezone_set('America/La_Paz');

class Conexion extends PDO {
    public function __construct() {
        $driver   = 'mysql';
        $host     = 'localhost';
        $dbname   = 'proyecto_sca';
        $port     = '3306';
        $usuario  = 'root';
        $clave    = '';

        $dsn = "$driver:host=$host;dbname=$dbname;port=$port;charset=utf8mb4";

        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT         => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        try {
            parent::__construct($dsn, $usuario, $clave, $opciones);
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            exit('No se pudo conectar a la base de datos.');
        }
    }
}
?>
