<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Conectar {
    private $dbh;

    public function Conexion() {
        try {
            $this->dbh = new PDO("mysql:locahost; port=3306;dbname=mike", "root", "");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->dbh;
        } catch (PDOException $e) {
            print "¡Error BD!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function set_names() {
        return $this->dbh->query("SET NAMES 'utf8mb4'");
    }
    
    // Nueva función para hashear códigos
    public static function hashCodigo($codigo) {
        return password_hash($codigo, PASSWORD_DEFAULT);
    }
    
    // Nueva función para verificar códigos
    public static function verificarCodigo($codigo, $hash) {
        return password_verify($codigo, $hash);
    }
}
?>