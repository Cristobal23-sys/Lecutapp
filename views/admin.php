<?php
session_start();
require_once '../class/connection.php';

$conn = new connection();
// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$conexion = $conn->conectar();
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el rol del usuario actual
$username = $_SESSION['username'];
$sql = "SELECT rol FROM usuario WHERE username = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['rol'] != '0') { // Suponiendo que '0' es el rol de administrador
    header("Location: index.php");
    exit();
}

// Lógica para mostrar diferentes secciones de administración
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Panel de Administración</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <span class="navbar-text ms-auto">
                <h5 class="mb-0">Administrador: <?php echo htmlspecialchars($_SESSION['username']); ?></h5>
            </span>
            <form class="d-flex ms-3" method="post" action="../class/Cerrarsesionlistas.php">
                <button class="btn btn-outline-light" type="submit">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</nav>
<div class="container mt-5">
  <div class="row justify-content-center">

    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <a href="../views/usuario-admin.php" class="text-decoration-none">
        <div class="card h-100" style="width: 100%;">
          <div class="position-relative" style="width: 100%; height: 100%; padding-bottom: 75%; overflow: hidden;">
            <img src="https://e84bh8b3dtf.exactdn.com/wp-content/uploads/2020/08/User-Management-e1598275803473.png?strip=all&lossy=1&w=1920&ssl=1" 
                 alt="Administrar Usuarios" 
                 class="img-fluid position-absolute top-0 start-0 w-100 h-100 object-fit-cover" 
                 style="transition: transform 0.2s; cursor: pointer;"
                 onmouseover="this.style.transform='scale(1.15)';"
                 onmouseout="this.style.transform='scale(1)';">
          </div>
          <div class="card-footer text-center">
            Administración de Usuarios
          </div>
        </div>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <a href="../views/recetas-admin.php" class="text-decoration-none">
        <div class="card h-100" style="width: 100%;">
          <div class="position-relative" style="width: 100%; height: 100%; padding-bottom: 75%; overflow: hidden;">
            <img src="https://images.vexels.com/content/235848/preview/chefs-kitchen-hat-be81a4.png" 
                 alt="Administrar recetas" 
                 class="img-fluid position-absolute top-0 start-0 w-100 h-100 object-fit-cover" 
                 style="transition: transform 0.2s; cursor: pointer;"
                 onmouseover="this.style.transform='scale(1.15)';"
                 onmouseout="this.style.transform='scale(1)';">
          </div>
          <div class="card-footer text-center">
            Administración de Recetas
          </div>
        </div>
      </a>
    </div>
  </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>