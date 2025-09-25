<?php
require_once("../config/conexion.php");

class RegistrarModel {
    private $dbh;

    public function __construct() {
        $this->dbh = (new Conectar())->Conexion();
    }

    // Método para registrar un nuevo usuario
    public function registrarUsuario($usuario, $nombre, $contraseñaHash, $rol) {
        
        // El controlador ya encriptó la contraseña, no es necesario hacerlo de nuevo.
        // La variable $contraseñaHash ya es el hash listo para guardar.

        // Validar que el rol es válido
        if ($rol != 1 && $rol != 2) {
            return "Rol inválido.";
        }

        // SQL para insertar el usuario en la tabla `rol`
        $sql = "INSERT INTO rol (nombre, contrasenia, usuario, id_rol, ultima_actualizacion_password) 
                VALUES (:nombre, :contrasenia, :usuario, :id_rol, :fecha)";

        try {
            // Preparar la consulta SQL
            $stmt = $this->dbh->prepare($sql);

            // Obtener la fecha actual para el registro
            $fecha = date('Y-m-d H:i:s');

            // Enlazar los parámetros
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':contrasenia', $contraseñaHash, PDO::PARAM_STR);
            $stmt->bindParam(':id_rol', $rol, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>