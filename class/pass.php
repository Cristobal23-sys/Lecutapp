<?php
include "../class/auth.php";

// Elimina la llamada a session_start() aquí
// session_start();

$user = $_POST['user'];
$pass = $_POST['pass'];

$Auth = new Auth($user, $pass);

$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php';

if ($Auth->logear($user, $pass)) {
    $_SESSION['user'] = $user;

    // Redirigir al usuario a la página anterior
    header("Location: " . $referrer);
    exit();
} else {
    $_SESSION['error_message'] = 'Usuario o contraseña incorrectos.';
    header("Location: " . $referrer);
    exit();
}


