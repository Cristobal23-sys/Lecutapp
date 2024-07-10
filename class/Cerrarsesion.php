<?php
session_start();

// Cerrar la sesión
session_destroy();

// Obtener la URL de la página anterior
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../views/index.php';

// Redirigir al usuario a la página anterior
header("Location: " . $referrer);
exit();