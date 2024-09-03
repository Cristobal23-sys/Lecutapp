<?php
session_start();
include_once "../Class/connection.php";
$con = new connection;
$conn = $con->conectar();
if (isset($_SESSION['user'])) {
  $inicio = $_SESSION['user']; // Obtener el nombre de usuario de la sesión
  $sql = "SELECT `id` FROM usuario
            WHERE username = '$inicio'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $idUser = $row['id'];
  $cambio = "<a class='dropdown-item' href='../views/view-listacompra.php'>Lista de deseos</a>";
  $reg = "<a class='dropdown-item' href='../Class/logOut.php'>Cerrar Sesión</a>";

  $query = "SELECT COUNT(*) AS count FROM listacompra WHERE id_usuario = '$idUser'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $count = $row['count'];
} else {
  $inicio = "Iniciar sesión";
  $cambio = "<a class='dropdown-item' href='../Views/login.php'>Iniciar Sesión</a>";
  $reg = "<a class='dropdown-item' href='../Views/register.php'>Registrarse</a>";
}
if (isset($_SESSION['alert_message'])) {
  $alert_message = $_SESSION['alert_message'];
  unset($_SESSION['alert_message']);

  echo "<script>alert('$alert_message');</script>";
}

$sqlCategorias = "SELECT DISTINCT producto_categoria FROM producto";
$resultCategorias = mysqli_query($conn, $sqlCategorias);
$categorias = [];
while ($row = mysqli_fetch_assoc($resultCategorias)) {
  $categorias[] = $row['producto_categoria'];
}

$sqlReceta = "SELECT DISTINCT TipoReceta FROM receta";
$resultReceta = mysqli_query($conn, $sqlReceta); // Cambiado $connection a $conn
$TipoReceta = [];
while ($row = mysqli_fetch_assoc($resultReceta)) {
  $TipoReceta[] = $row['TipoReceta'];
}




?>
<!DOCTYPE html>
<html>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playwrite+ES+Deco:wght@100..400&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LeCut</title>
</head>

<body style="background-color: rgb(255, 255, 255);">
  <!--Navbar-->
  <nav class="navbar navbar-expand-lg" style="background-color: rgb(71, 126, 213);">
    <div class="container">
    <a class="navbar-brand" href="../views/index.php">
    <img src="https://i.postimg.cc/vBfDj9sv/icono-removebg-preview.png" alt="Logo" style="height: 50px; width: auto;">
</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <strong>Categorías</strong>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <?php foreach ($categorias as $categoria) { ?>
                <li><a class="dropdown-item"
                    href="../views/view-categorias.php?producto_categoria=<?php echo $categoria; ?>"><?php echo $categoria; ?></a>
                </li>
              <?php } ?>

            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <strong>Recetas</strong>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
              <?php foreach ($TipoReceta as $TipoRecetas) { ?>
                <li><a class="dropdown-item"
                    href="../views/view-cat-receta.php?TipoReceta=<?php echo $TipoRecetas; ?>"><?php echo $TipoRecetas; ?></a>
                </li>
              <?php } ?>
              <li><a class="dropdown-item" href="../views/view-cat-receta.php?"> Todas </a></li>
            </ul>
          </li>
        </ul>
        <form class="d-flex me-auto w-50" role="search" action="../class/search.php" method="GET" id="searchForm">
          <input class="form-control me-1 w-50" id="searchInput" type="search" name="buscar" placeholder="Buscar"
            aria-label="Search">
          <button class="btn btn-Light" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>        
        </form>      
<script>
  document.getElementById('searchForm').addEventListener('submit', function(event) {
    var input = document.getElementById('searchInput');
    var regex = /^[a-zA-Z0-9\s]+$/;
    
    if (!regex.test(input.value)) {
      input.classList.add('is-invalid'); // Agregar clase de Bootstrap para indicar error
      event.preventDefault(); // Prevenir el envío del formulario
    } else {
      input.classList.remove('is-invalid'); // Remover clase si la validación es correcta
    }
  });
</script>
        <ul class="navbar-nav">
          <?php if (isset($_SESSION['username'])) { ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <strong>Bienvenido, <?php echo $_SESSION['username']; ?></strong>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                <li><a class="dropdown-item" href="../views/view-listacompra.php">Lista de compras</a></li>
                <li><a class="dropdown-item" href="../class/Cerrarsesionlistas.php">Cerrar sesión</a></li>
              </ul>
            </li>
          <?php } else { ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarLoginDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <strong> Iniciar sesión</strong>
              </a>
              <div class="dropdown-menu dropdown-menu-end p-4" aria-labelledby="navbarLoginDropdown">
                <form action="../class/pass.php" name="f1" onsubmit="return validation()" method="POST">
                  <div class="mb-3">
                    <label for="exampleDropdownFormEmail2" class="form-label">👨🏽‍💼</label>
                    <input type="text" id="user" class=" fadeIn second" name="user" placeholder="Usuario" required>
                  </div>
                  <div class="mb-3">
                    <label for="exampleDropdownFormPassword2" class="form-label">🔏</label>
                    <input type="password" name="pass" class=" fadeIn third" id="pass" placeholder="Contraseña" required
                      onkeyup="maskPassword(this)">
                  </div>
                  <?php
                  $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
                  unset($_SESSION['error_message']);
                  if (!empty($errorMessage)) {
                    echo '<p style="color: red;">' . $errorMessage . '</p>';
                  }
                  ?>
                  <button type="submit" class="btn btn-primary" style="margin-left: 35px;"> Iniciar sesión </button>
                  <p style="display: flex; justify-content: center;">¿Aun no tienes cuenta?</p><a
                    href="../views/view-register.php" style="display: flex; justify-content: center;">Regístrate</a>
                </form>
              </div>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
</body>

<!--carrusel-->
</div>



<?php
// Obtener los resultados de la consulta y asignarlos a $result
$result = mysqli_query($conn, "SELECT * FROM listacompra");


if ($result) {
  // La consulta se ejecutó correctamente
  while ($row = mysqli_fetch_assoc($result)) {
    // Resto del código para procesar cada fila de resultados
  }
} else {
  // La consulta falló, maneja el error aquí
  echo "Error en la consulta: " . mysqli_error($conn);
}

?>

<div class="container" style="background-color:rgb(255,255,255); margin-top: 25px;">
  <div class="row">
    <?php // Si el usuario no tiene listas de deseos, mostrar el mensaje y el botón de creación
    if ($count <= 0) {
      echo "<div class='container' style='background-color:rgb(255,255,255) margin-top: 25px;'>
                <div class='row'>
                    <div class='col'>
                        <p class='text-black' style='margin-left:30%'>No tienes listas de compras. ¿Deseas crear una?</p>
                        <button class='btn btn-success' style='margin-left:35%;' data-bs-toggle='modal' data-bs-target='#wishlistModal'>Crear nueva lista de compras</button>
                    </div>
                </div>
            </div>";
    } else {
      $query = "SELECT * FROM listacompra WHERE id_usuario = '$idUser'";
      $sql = mysqli_query($conn, $query);
      while ($row = mysqli_fetch_assoc($sql)) {
        $id = $row['id'];
        $name = $row['nombre'];
        $desc = $row['descripcion'];
        ?>

        <div class="card mb-3 col-12" style="background-color: rgb(255, 255, 255); margin-top: 10px;">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h5 class="card-title" style="color: black;"><?php echo $name ?></h5>
              <p class="card-text" style="color: black;"><?php echo $desc ?></p>
            </div>
            <div>
              <a href="../views/view-verlista.php?idL=<?php echo $id ?>" class="btn btn-primary btn-sm me-2">Ir a la
                lista</a>
              <a href="../class/eliminarlista.php?id=<?php echo $id ?>" class="btn btn-danger btn-sm">Eliminar</a>
            </div>
          </div>
        </div>


        <?php
      }
    }
    ?>


    <?php // Botón para crear nueva lista
    if ($count > 0) {
      ?>
      <div class="row justify-content-center mt-3">
        <div class="col-auto">
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#wishlistModal">Crear nueva
            lista</button>
        </div>
      </div>
      <?php
    }
    ?>
    <!-- Modal para crear una nueva lista -->
    <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="wishlistModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="wishlistModalLabel">Crear nueva lista</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="../Class/guardar_lista.php" method="post">
              <div class="mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" required>
              </div>
              <div class="mb-3">
                <textarea class="form-control" id="desc" name="desc" placeholder="Descripción" required></textarea>
              </div>
              <input type="hidden" class="form-control" id="idU" name="idU" value="<?php echo $idUser ?>">
              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Crear</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <body>
      <br>
  </div>

  <script>
    function toggleForm() {
      var form = document.getElementById("filter-form");

      // Verificar si el formulario está oculto
      if (form.style.display === "none") {
        // Mostrar el formulario con animación
        form.style.opacity = 0;
        form.style.display = "block";
        // Aplicar la animación de fundido
        fadeIn(form);
      } else {
        // Ocultar el formulario con animación
        fadeOut(form, function () {
          form.style.display = "none";
        });
      }
    }

    // Función para animar la aparición gradual del elemento
    function fadeIn(element) {
      var opacity = 0;
      var timer = setInterval(function () {
        if (opacity >= 1) {
          clearInterval(timer);
        }
        element.style.opacity = opacity;
        opacity += 0.1;
      }, 50);
    }

    // Función para animar la desaparición gradual del elemento
    function fadeOut(element, callback) {
      var opacity = 1;
      var timer = setInterval(function () {
        if (opacity <= 0) {
          clearInterval(timer);
          callback();
        }
        element.style.opacity = opacity;
        opacity -= 0.1;
      }, 50);
    }
  </script>

  </body>

  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <footer class="" style="margin-left:0px; color:black;">
    <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
      <div class="me-5 d-none d-lg-block">
        <span></span>
      </div>
      <div>
        <a href="https://www.facebook.com/" target="_blank" class="me-4 text-reset">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://twitter.com/home" target="_blank" class="me-4 text-reset">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://www.google.cl/?gws_rd=ssl" target="_blank" class="me-4 text-reset">
          <i class="fab fa-google"></i>
        </a>
        <a href="https://www.instagram.com/" target="_blank" class="me-4 text-reset">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://www.linkedin.com/feed/" target="_blank" class="me-4 text-reset">
          <i class="fab fa-linkedin"></i>
        </a>
        <a href="https://github.com/" target="_blank" class="me-4 text-reset">
          <i class="fab fa-github"></i>
        </a>
      </div>
    </section>
    <section class="" style="background-color: rgba(255, 255, 255)">
      <div class="container text-center text-md-start mt-5">
        <div class="row mt-3">
          <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
            <h6 class="text-uppercase fw-bold mb-4">
              <i class="fa-solid fa-cart-shopping"></i>
            </h6>
            <p>
              Los mejores precio en Lecut para los consumidores
            </p>
          </div>
          <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4" style="background-color: rgba(255, 255, 255)">
            <h6 class="text-uppercase fw-bold mb-4">
              Categorias
            </h6>
            <p>
              <a href="../views/view-categorias.php?producto_categoria=Lácteos" class="text-reset">Lácteos</a>
            </p>
            <p>
              <a href="../views/view-categorias.php?producto_categoria=Frutas%20y%20verduras" class="text-reset">Frutas
                Y
                Verduras</a>
            </p>
            <p>
              <a href="../views/view-categorias.php?producto_categoria=Carniceria" class="text-reset">Carnes</a>
            </p>
            <p>
              <a href="../views/view-categorias.php?producto_categoria=Botillería" class="text-reset">Botillería</a>
            </p>
          </div>
          <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4" style="background-color: rgba(255, 2555, 255)">
            <h6 class="text-uppercase fw-bold mb-4">
              Enlaces
            </h6>
            <p>
              <a href="../views/view-register.php" class="text-reset">Registrarse</a>
            </p>
            <p>
              <a href="../Views/ruleta.html" class="text-reset">Proximamente</a>
            </p>
            <p>
              <a href="../Views/P-frec.html" class="text-reset">Preguntas Frecuentes</a>
            </p>
            <p>
              <a href="../views/view-cat-receta.php?" class="text-reset">Recetas</a>
            </p>
          </div>
          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4" style="background-color: rgba(255, 255, 255)">
            <h6 class="text-uppercase fw-bold mb-4">Contacto</h6>
            <p><i class="fas fa-home me-3"></i> Juan Fernández 2010, Archipiélago, Curicó, Chile</p>
            <p>
              <i class="fas fa-envelope me-3"></i>
              info@lecut.cl
            </p>
            <p><i class="fas fa-phone me-3"></i> +56 9 12345678</p>

          </div>
        </div>
      </div>
    </section>
    <div class="text-center p-4" style="background-color: rgba();">
      <span>© 2024</span>
      <a class="text-reset fw-bold" href="../Views/index.php" style="text-decoration: none;">LeCut®</a>
    </div>
  </footer>

</html>