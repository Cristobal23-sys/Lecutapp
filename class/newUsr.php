<?php 
    session_start();    
    include "../class/auth.php";
    $email = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $user = $_POST['user'];
    $Auth = new Auth();

    if ($Auth->registrar($email, $pass, $user)) {
        $_SESSION['message'] = "Usuario registrado correctamente.";
        $_SESSION['message_type'] = "success";
        header("location:../views/view-register.php");
    } else {
        $_SESSION['message'] = "El correo electrónico ya existe en la base de datos.";
        $_SESSION['message_type'] = "error";
        header("location:../views/view-register.php");
        exit();
    }
?>
