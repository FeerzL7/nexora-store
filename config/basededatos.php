<?php
class Database
{
    private $hostname = "localhost";
    private $database = "tienda_ropa";
    private $username = "root";
    private $password = "root";
    private $charset  = "utf8mb4";

    function conectar()
    {
        try {
            $conexion = "mysql:host=" . $this->hostname .
                ";dbname=" . $this->database .
                ";charset=" . $this->charset;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            return new PDO($conexion, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            error_log('Error de conexion: ' . $e->getMessage());
            exit('No fue posible conectar con la base de datos.');
        }
    }
}
