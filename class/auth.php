<?php
include "connection.php";
session_start();
class Auth extends connection
{
    public function registrar($email, $pass, $username)
{
    $conexion = parent::conectar();

    // Verificar si el nombre de usuario tiene al menos 8 caracteres
    if (strlen($username) < 8) {
        $_SESSION['error_message'] = 'El nombre de usuario debe tener al menos 8 caracteres.';
        return false;
    }

    // Verificar si el correo electrónico ya existe en la base de datos
    $sql_verificar_email = "SELECT COUNT(*) as count FROM usuario WHERE email = ?";
    $query_verificar_email = $conexion->prepare($sql_verificar_email);
    $query_verificar_email->bind_param('s', $email);
    $query_verificar_email->execute();
    $resultado_verificar_email = $query_verificar_email->get_result();
    $fila_verificar_email = $resultado_verificar_email->fetch_assoc();

    // Verificar si el nombre de usuario ya existe en la base de datos
    $sql_verificar_username = "SELECT COUNT(*) as count FROM usuario WHERE username = ?";
    $query_verificar_username = $conexion->prepare($sql_verificar_username);
    $query_verificar_username->bind_param('s', $username);
    $query_verificar_username->execute();
    $resultado_verificar_username = $query_verificar_username->get_result();
    $fila_verificar_username = $resultado_verificar_username->fetch_assoc();

    if ($fila_verificar_email['count'] > 0) {
        // El correo electrónico ya existe, no se puede crear un nuevo usuario
        $_SESSION['error_message'] = 'El correo electrónico ya está registrado.';
        return false;
    } elseif ($fila_verificar_username['count'] > 0) {
        // El nombre de usuario ya existe, no se puede crear un nuevo usuario
        $_SESSION['error_message'] = 'El nombre de usuario ya está registrado.';
        return false;
    } else {
        // El correo electrónico y el nombre de usuario no existen, se puede crear un nuevo usuario
        $sql_insertar = "INSERT INTO usuario (email, pass, username) VALUES (?, ?, ?)";
        $query_insertar = $conexion->prepare($sql_insertar);
        $query_insertar->bind_param('sss', $email, $pass, $username);
        if ($query_insertar->execute()) {
            $_SESSION['success_message'] = 'Usuario registrado correctamente.';
            return true;
        } else {
            $_SESSION['error_message'] = 'Error al registrar el usuario.';
            return false;
        }
    }
}


public function logear($username, $pass)
{
    $conexion = parent::conectar();
    $sql = "SELECT * FROM usuario WHERE username = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    if (!$stmt) {
        error_log("Error en la preparación de la consulta: " . mysqli_error($conexion));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $respuesta = mysqli_stmt_get_result($stmt);

    if (!$respuesta) {
        error_log("Error en la ejecución de la consulta: " . mysqli_error($conexion));
        return false;
    }

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
