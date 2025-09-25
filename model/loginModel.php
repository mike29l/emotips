<?php
class Login extends Conectar
{
    public function GetLogin($usuario)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consultar al usuario por su nombre de usuario
        $sql = "SELECT * FROM rol WHERE usuario = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usuario);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

