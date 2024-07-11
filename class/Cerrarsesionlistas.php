<?php
// Iniciar la sesión
session_start();



// Destruir la sesión
session_destroy();

// Redirigir al usuario a index.php
header("Location: ../views/index.php");
exit();
