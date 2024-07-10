<?php
include "connection.php";

class Auth extends connection
{
    public function registrar($email, $pass, $username)
    {
        $conexion = parent::conectar();

        // Verificar si el correo electrónico ya existe en la base de datos
        $sql_verificar = "SELECT COUNT(*) as count FROM usuario WHERE email = ?";
        $query_verificar = $conexion->prepare($sql_verificar);
        $query_verificar->bind_param('s', $email);
        $query_verificar->execute();
        $resultado_verificar = $query_verificar->get_result();
        $fila_verificar = $resultado_verificar->fetch_assoc();

        if ($fila_verificar['count'] > 0) {
            // El correo electrónico ya existe, no se puede crear un nuevo usuario

            return false;
        } else {
            // El correo electrónico no existe, se puede crear un nuevo usuario
            $sql_insertar = "INSERT INTO usuario (email, pass, username) VALUES (?, ?, ?)";
            $query_insertar = $conexion->prepare($sql_insertar);
            $query_insertar->bind_param('sss', $email, $pass, $username);
            return $query_insertar->execute();
        }
    }

    public function logear($username, $pass)
    {
        $conexion = parent::conectar();
        $passwordExistente = "";
        $sql = "SELECT * FROM usuario 
                    WHERE username = '$username'";
        $respuesta = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($respuesta) > 0) {
            $passwordExistente = mysqli_fetch_array($respuesta);
            $passwordExistente = $passwordExistente['pass'];

            if (password_verify($pass, $passwordExistente)) {
                $_SESSION['username'] = $username;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
