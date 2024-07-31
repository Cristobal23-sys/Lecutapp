<?php
require_once '../class/connection.php';

$conn = new connection();

try {
  $connection = $conn->conectar();
  session_start();

  // Obtener el número total de registros
  $sqlTotal = "SELECT COUNT(*) AS total FROM producto";
  $resultTotal = mysqli_query($connection, $sqlTotal);
  $rowTotal = mysqli_fetch_assoc($resultTotal);
  $totalRegistros = $rowTotal['total'];

  $sqlCategorias = "SELECT DISTINCT producto_categoria FROM producto";
  $resultCategorias = mysqli_query($connection, $sqlCategorias);
  $categorias = [];
  while ($row = mysqli_fetch_assoc($resultCategorias)) {
    $categorias[] = $row['producto_categoria'];
  }

  $sqlReceta = "SELECT DISTINCT TipoReceta FROM receta";
  $resultReceta = mysqli_query($connection, $sqlReceta);
  $TipoReceta = [];
  while ($row = mysqli_fetch_assoc($resultReceta)) {
    $TipoReceta[] = $row['TipoReceta'];
  }

  // Definir la cantidad de resultados por página
  $resultadosPorPagina = 18;

  // Reiniciar la consulta SQL
  $sql = "SELECT * FROM `producto` ORDER BY RAND() LIMIT $resultadosPorPagina";

  $result = mysqli_query($connection, $sql);
  $count = mysqli_num_rows($result);
} catch (Exception $e) {
  echo "Error de conexión a la base de datos: " . $e->getMessage();
  exit;
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
  <link rel="stylesheet" href="../css/css.css">
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
  <nav class="navbar navbar-expand-lg" style="background-color: #f7d1c4;">
    <div class="container">
      <a class="navbar-brand" href="../views/index.php">
        <strong>L®</strong>
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
        <form class="d-flex me-auto w-50" role="search" action="../class/search.php" method="GET">
          <input class="form-control me-1 w-50" id="searchInput" type="search" name="buscar" placeholder="Buscar"
            aria-label="Search">
          <button class="btn btn-outline-success" type="submit">🔎</button>
        </form>
        <ul class="navbar-nav">
          <?php if (isset($_SESSION['username'])) { ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <strong>Bienvenido, <?php echo $_SESSION['username']; ?></strong>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                <li><a class="dropdown-item" href="../views/view-listacompra.php">Lista de compras</a></li>
                <li><a class="dropdown-item" href="../class/Cerrarsesion.php">Cerrar sesión</a></li>
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
                    <input type="text" id="user" class="fadeIn second" name="user" placeholder="Usuario" required>
                  </div>
                  <div class="mb-3">
                    <label for="exampleDropdownFormPassword2" class="form-label">🔏</label>
                    <input type="password" name="pass" class="fadeIn third" id="pass" placeholder="Contraseña" required
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
                  <p style="display: flex; justify-content: center;">¿Aún no tienes cuenta?</p><a
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
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../img/banner1.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../img/banner2.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../img/banner3.png" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<div class="container mt-5">
  <div class="row justify-content-center">
    <h2 class="text-center col-12"><strong>Categorías Destacadas</strong></h2>
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <a href="../views/view-categorias.php?producto_categoria=Lacteos">
        <div class="card h-100" style="width: 100%;">
          <div style="width: 100%; height: 100%; padding-bottom: 75%; position: relative; overflow: hidden;">
            <img src="https://i.postimg.cc/sgDSYzxy/1.png" alt="Imagen de Lacteos"
              style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s; cursor: pointer;"
              onmouseover="this.style.transform='scale(1.15)';"
              onmouseout="this.style.transform='scale(1)';">
          </div>
        </div>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <a href="../views/view-categorias.php?producto_categoria=Botilleria">
        <div class="card h-100" style="width: 100%;">
          <div style="width: 100%; height: 100%; padding-bottom: 75%; position: relative; overflow: hidden;">
            <img src="https://i.postimg.cc/MGnQLY1F/2.png" alt="Imagen de Botilleria"
              style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s; cursor: pointer;"
              onmouseover="this.style.transform='scale(1.15)';"
              onmouseout="this.style.transform='scale(1)';">
          </div>
        </div>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <a href="../views/view-categorias.php?producto_categoria=Mascotas">
        <div class="card h-100" style="width: 100%;">
          <div style="width: 100%; height: 100%; padding-bottom: 75%; position: relative; overflow: hidden;">
            <img src="https://i.postimg.cc/rwn4gHR4/3.png" alt="Imagen de Mascotas"
              style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s; cursor: pointer;"
              onmouseover="this.style.transform='scale(1.15)';"
              onmouseout="this.style.transform='scale(1)';">
          </div>
        </div>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <a href="../views/view-categorias.php?producto_categoria=Carniceria">
        <div class="card h-100" style="width: 100%;">
          <div style="width: 100%; height: 100%; padding-bottom: 75%; position: relative; overflow: hidden;">
            <img src="https://i.postimg.cc/SKffdRrT/4.png" alt="Imagen de Carniceria"
              style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s; cursor: pointer;"
              onmouseover="this.style.transform='scale(1.15)';"
              onmouseout="this.style.transform='scale(1)';">
          </div>
        </div>
      </a>
    </div>
  </div>
</div>


<style>
  .card {
    background-color: rgb(241, 192, 134);
    border-radius: 5px;
    width: 100%;
    /* Asegura que la tarjeta use todo el ancho disponible en su columna */
    height: 100%;
    /* Asegura que la tarjeta use todo el alto disponible en su columna */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .card-body {
    padding: 1rem;
  }

  .img-container {
    position: relative;
    overflow: hidden;
  }

  .img-container img:first-of-type {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .img-container img:last-of-type {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
  }

  .logo-img {
    width: 50px;
    /* Ajusta el tamaño según sea necesario */
    height: auto;
    position: absolute;
    bottom: 1%;
    right: 1%;
  }
</style>
<div class="container" style="background-color:rgb(255,255,255); margin-top: 25px;">
  <div class="d-flex justify-content-center">
    <div class="container mt-4">
      <h3 class="d-none d-md-block">Lo Mejor del día</h3>
      <div id="productCarousel" class="carousel slide" data-interval="false">
        <div class="carousel-inner">
          <?php
          // Aquí debería ir tu código PHP para obtener productos desde la base de datos
          if ($count > 0) {
            $first = true; // Variable para determinar el primer elemento
            $itemsPerSlide = 6; // Número de tarjetas por slide
            $currentItem = 0; // Contador de tarjetas
          
            // Iniciar el primer slide
            echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
            echo '<div class="row justify-content-center">';

            while ($row = mysqli_fetch_assoc($result)) {
              $id = $row['id'];
              $name = $row['producto_name'];
              $urlImagen = $row['producto_image'];
              $price = $row['producto_price'];
              $brand = $row['producto_categoria'];
              $logo = $row['producto_logo'];
              $shortName = substr($name, 0, 35);
              $formattedPrice = "$" . number_format($price, 0, '', '.');

              // Crear tarjeta
              echo '<div class="col-md-2 d-none d-md-block">'; // Ocultar en pantallas medianas y pequeñas
              echo '<a href="../views/viewProducto.php?id=' . $id . '" style="text-decoration: none;">';
              echo '<div class="card">';
              echo '<div class="img-container">';
              echo '<img src="../img/blanco.png" alt="Imagen Fondo">';
              echo '<img src="' . $urlImagen . '" alt="Imagen Superpuesta">';
              echo '</div>';
              echo '<div class="card-body">';
              echo '<h5 class="card-title">' . $shortName . '</h5>';
              echo '<p class="card-text">' . $brand . '</p>';
              echo '<p class="card-title"><strong>' . $formattedPrice . '</strong></p>';
              echo '<img src="' . $logo . '" alt="Imagen" class="logo-img">';
              echo '</div>';
              echo '</div>';
              echo '</a>';
              echo '</div>'; // Cierre de col-md-2
          
              // Incrementar el contador
              $currentItem++;

              // Si se han añadido 6 tarjetas, cerrar el slide y abrir uno nuevo
              if ($currentItem % $itemsPerSlide == 0) {
                echo '</div>'; // Cierre de la fila
                echo '</div>'; // Cierre del carousel-item
                if ($currentItem < $count) { // Solo abrir un nuevo slide si hay más productos
                  echo '<div class="carousel-item">';
                  echo '<div class="row justify-content-center">';
                }
              }
            }

            // Cerrar el último slide si hay tarjetas que no llenan el último slide
            if ($currentItem % $itemsPerSlide != 0) {
              echo '</div>'; // Cierre de la fila
              echo '</div>'; // Cierre del carousel-item
            }
          } else {
            echo "<p style='text-align: center;'>No se encontraron productos.</p>";
          }
          mysqli_close($connection);
          ?>
        </div>
        <div class="d-none d-md-flex"
          style="justify-content: center; align-items: center; gap: 20px; margin-top: 10px;">
          <!-- Botón de control anterior -->
          <button
            style="background: rgb(241, 192, 134); border: none; font-size: 30px; color: #333; cursor: pointer; transition: color 0.3s ease, transform 0.3s ease;"
            onmouseover="this.style.color='#6b8cff'; this.style.transform='scale(1.2)';"
            onmouseout="this.style.color='#333'; this.style.transform='scale(1)';" class="custom-carousel-control-prev"
            type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
          </button>
          <!-- Botón de control siguiente -->
          <button
            style="background: rgb(241, 192, 134); border: none; font-size: 30px; color: #333; cursor: pointer; transition: color 0.3s ease, transform 0.3s ease;"
            onmouseover="this.style.color='#6b8cff'; this.style.transform='scale(1.2)';"
            onmouseout="this.style.color='#333'; this.style.transform='scale(1)';" class="custom-carousel-control-next"
            type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .img-container {
    position: relative;
  }

  .img-container img:first-child {
    width: 100%;
  }

  .img-container img:last-child {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .card {
    margin-bottom: 15px;
  }

  .logo-img {
    width: 50px;
    height: auto;
  }
</style>


<br>
<div class="container">
    <div class="d-flex justify-content-center">
      <img src="../img/b-cupon.png" alt="Descripción de la imagen" class="img-fluid" style="max-width: 100%;">
    </div>
  </div>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card text-center" style="background-color: black;">
        <div class="card-body" style="background-color: white;">
          <h2 class="card-title">Suscríbete a nuestras novedades</h2>
          <p class="card-text">Recibe cupones y ofertas directamente en tu correo electrónico.</p>
          <form action="#" method="post">
            <div class="input-group mb-3">
              <input type="email" class="form-control" placeholder="Introduce tu email" required>
              <button class="btn btn-outline-success" type="submit">Suscribirse</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Asegúrate de que estos scripts se carguen en el orden correcto -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</div>


</body>
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
            <i class="fas fa-gem me-3"></i>Ahorrando
          </h6>
          <p>
            Los mejores precio en Ahorrando para los consumidores
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
            <a href="../views/view-categorias.php?producto_categoria=Frutas%20y%20verduras" class="text-reset">Frutas Y
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
            info@ahorrando.cl
          </p>
          <p><i class="fas fa-phone me-3"></i> +56 9 12345678</p>

        </div>
      </div>
    </div>
  </section>
  <div class="text-center p-4" style="background-color: rgba();">
    <span>© 2024</span>
    <a class="text-reset fw-bold" href="../Views/index.php">AHORRANDO<i class="fa-solid fa-cart-shopping"></i></a>
  </div>
</footer>

</html>