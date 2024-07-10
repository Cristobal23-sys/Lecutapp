<?php 
    session_start();    
    include "../class/auth.php";
    $email = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $user = $_POST['user'];
    $Auth = new Auth();

    if ($Auth->registrar($email, $pass, $user)) {
        $_SESSION['error_message'] = "Usuario registrado correctamente.";
        header("location:../views/view-register.php");
    } else {
        session_start();
        $_SESSION['error_message'] = "El correo electrónico ya existe en la base de datos.";
        header("location:../views/view-register.php");
        exit();
    }

?>