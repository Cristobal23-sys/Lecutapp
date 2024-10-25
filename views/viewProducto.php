<?php
include_once "../class/connection.php";
$con = new connection;
$conn = $con->conectar();
$prod = mysqli_real_escape_string($conn, $_GET['id']);
session_start();
if (isset($_SESSION['user'])) {
  $inicio = $_SESSION['user']; // Obtener el nombre de usuario de la sesión
  $sql = "SELECT `id` FROM usuario WHERE username = '$inicio'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $idUser = $row['id'];
    $cambio = "<a class='dropdown-item' href='../views/view-listacompra.php'>Lista de deseos</a>";
    $reg = "<a class='dropdown-item' href='../class/Cerrarsesion.php'>Cerrar Sesión</a>";

    $listaA = "<a 10px>   </a> <a  class='btn btn-success ' style=''target='_blank' href='#' data-bs-toggle='modal' data-bs-target='#wishlistModal'>Añadir a lista de compras</a>";

    // Obtener las listas de deseos del usuario actual
    $query = "SELECT id, nombre FROM listacompra WHERE id_usuario = '$idUser'"; // Cambiado 'name' a 'nombre' si ese es el nombre correcto de la columna
    $wishlistResult = mysqli_query($conn, $query);

    if ($wishlistResult) {
      $wishlistOptions = "";
      while ($wishlist = mysqli_fetch_assoc($wishlistResult)) {
        $wishlistId = $wishlist['id'];
        $wishlistName = $wishlist['nombre']; // Cambiado 'name' a 'nombre' para coincidir con la consulta SQL
        $wishlistOptions .= "<option value='$wishlistId'>$wishlistName</option>";
      }
    } else {
      // Manejar error en la consulta de listas de deseos
      echo "Error en la consulta de listas de deseos: " . mysqli_error($conn);
    }
  } else {
    // Manejar error en la consulta de usuario
    echo "Error en la consulta de usuario: " . mysqli_error($conn);
  }
} else {
  $inicio = "Iniciar sesión";
  $cambio = "<a class='dropdown-item' href='../Views/view-register.php'>Iniciar Sesión</a>";
  $reg = "<a class='dropdown-item' href='../Views/view-register.php'>Registrarse</a>";
  $listaA = "<p class='card-text' style='color: Black; text-decoration: none; text-align: left;'>Para agregar un producto debes Iniciar Sesión</p>";
}

$sqlCategorias = "SELECT DISTINCT producto_categoria FROM producto";
$resultCategorias = mysqli_query($conn, $sqlCategorias); // Cambiado $connection a $conn
$categorias = [];
if ($resultCategorias) {
  while ($row = mysqli_fetch_assoc($resultCategorias)) {
    $categorias[] = $row['producto_categoria'];
  }
} else {
  // Manejar error en la consulta de categorías
  echo "Error en la consulta de categorías: " . mysqli_error($conn);
}
$sqlReceta = "SELECT DISTINCT TipoReceta FROM receta";
$resultReceta = mysqli_query($conn, $sqlReceta);
$TipoReceta = [];
while ($row = mysqli_fetch_assoc($resultReceta)) {
  $TipoReceta[] = $row['TipoReceta'];
}

$sql = "SELECT `id`, `producto_name`, `producto_image`, `producto_price`, `producto_categoria`, `producto_url` ,`producto_logo` FROM `producto` WHERE id LIKE '$prod'";
$resultSet = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($resultSet);
$id = $product['id'];
$name = $product['producto_name'];
$urlImagen = $product['producto_image'];
$price = $product['producto_price'];
$brand = $product['producto_categoria'];
$url = $product['producto_url'];
$logo = $product['producto_logo'];
$formattedPrice = "$" . number_format($price, 0, '', '.');
$descripcion = "$name es un producto de la categoría $brand. Es muy cotizado por las familias chilenas.";
$sqlAleatorios = "SELECT `id`, `producto_name`, `producto_image`, `producto_price`, `producto_url`,`producto_logo`  FROM `producto` WHERE `producto_categoria` = '$brand' AND `id` != '$prod' ORDER BY RAND() LIMIT 15";
$resultAleatorios = mysqli_query($conn, $sqlAleatorios);
$productosAleatorios = [];
if ($resultAleatorios) {
  while ($row = mysqli_fetch_assoc($resultAleatorios)) {
    $productosAleatorios[] = $row;
  }
}

?>
<!DOCTYPE html>
<html>

<head>



  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="../img/lecut.ico">
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+ES+Deco:wght@100..400&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($name); ?> | Lecut</title>
</head>

<body style="background-color: rgb(255, 255, 255);">
  <!--Navbar-->
  <nav class="navbar navbar-expand-lg" style="background-color: rgb(71, 126, 213);">
    <div class="container">
    <style>

.button {
  margin: 0;
  height: auto;
  background: transparent;
  padding: 0;
  border: none;
  cursor: pointer;
}

/* button styling */
.button {
  --border-right: 6px;
  --text-stroke-color: rgba(0,0,0);
  --animation-color: #ffffff;
  --fs-size: 1.2em;
  letter-spacing: 3px;
  text-decoration: none;
  font-size: var(--fs-size);
  font-family: "Lucida Handwriting";
  position: relative;
  text-transform: uppercase;
  color: transparent;
  -webkit-text-stroke: 1px var(--text-stroke-color);
}
/* this is the text, when you hover on button */
.hover-text {
  position: absolute;
  box-sizing: border-box;
  content: attr(data-text);
  color: var(--animation-color);
  width: 0%;
  inset: 0;
  border-right: var(--border-right) solid var(--animation-color);
  overflow: hidden;
  transition: 0.5s;
  -webkit-text-stroke: 1px var(--animation-color);
}
/* hover */
.button:hover .hover-text {
  width: 100%;
  filter: drop-shadow(0 0 23px var(--animation-color))
}
</style>

<button class="button" data-text="LeCut" onclick="location.href='../index.php'">
    <span class="actual-text">&nbsp;LeCut&nbsp;</span>
    <span aria-hidden="true" class="hover-text">&nbsp;LeCut&nbsp;</span>
</button>
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
        <style>
.container-input {
  position: relative;
}

.input {
  width: 350px;
  padding: 10px 0px 10px 40px;
  border-radius: 9999px;
  border: solid 1px #333;
  transition: all .2s ease-in-out;
  outline: none;
  opacity: 0.6;
}

.container-input svg {
  position: absolute;
  top: 50%;
  left: 10px;
  transform: translate(0, -50%);
}

.input:focus {
  opacity: 1;
  width: 350px;
}
</style>
        <form class="d-flex me-auto w-50 container-input" role="search" action="../class/search.php" method="GET" id="searchForm">
        <input  placeholder="Busca Tu Producto..." name="buscar" class="input" type="search" id="searchInput" aria-label="Search">
       
  <svg fill="#000000" width="20px" height="20px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
    <path d="M790.588 1468.235c-373.722 0-677.647-303.924-677.647-677.647 0-373.722 303.925-677.647 677.647-677.647 373.723 0 677.647 303.925 677.647 677.647 0 373.723-303.924 677.647-677.647 677.647Zm596.781-160.715c120.396-138.692 193.807-319.285 193.807-516.932C1581.176 354.748 1226.428 0 790.588 0S0 354.748 0 790.588s354.748 790.588 790.588 790.588c197.647 0 378.24-73.411 516.932-193.807l516.028 516.142 79.963-79.963-516.142-516.028Z" fill-rule="evenodd"></path>
</svg>
             
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
  <br>
  <a href="javascript:history.back()" class="btn btn-link text-decoration-none" style="margin-left:30%; color: black">⬅️
    Volver</a>
  <div class="container" style="background-color:rgb(255,255,255); margin-top: 25px;">
    <div class="container" style="background-color:white; margin-top: 25px;">
      <div class="row justify-content-center">
        <?php
        if ($conn != true) {
          die("Error de conexión " . mysqli_connect_error());
        }
        $sql = "SELECT `id`, `producto_name`, `producto_image`, `producto_price`, `producto_categoria`,`producto_url` FROM `producto` WHERE id LIKE '$prod'";
        $resultSet = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($resultSet)) {
          $id = $row['id'];
          $name = $row['producto_name'];
          $urlImagen = $row['producto_image'];
          $price = $row['producto_price'];
          $brand = $row['producto_categoria'];
          $url = $row['producto_url'];
          $formattedPrice = "$" . number_format($price, 0, '', '.');
          ?>
          <?php $descripcion = "$name Es un producto de la categoria $brand. El cual es muy cotizado por las familias chilenas."; ?>
          <div class="col-md-5 col-lg-8">
            <div class="card" style="background-color: white; border-radius: 40px; box-shadow: 15px 15px 15px;">
              <div class="row g-4">
                <div class="col-md-6">
                  <img src="<?php echo $urlImagen; ?>" class="card-img-top" alt="Imagen"
                    style="border-radius: 30px; max-width: 85%; margin-left: 5%;">
                </div>
                <div class="col-md-6">
                  <div class="card-body">
                    <h5 class="card-title" style="color: Black;"><?php echo htmlspecialchars($name); ?></h5>
                    <p class="card-text" style="color: Black;"><?php echo htmlspecialchars($brand); ?></p>
                    <p class="card-text" style="color: Black;"><?php echo htmlspecialchars($descripcion); ?></p>
                    <img src="<?php echo htmlspecialchars($logo); ?>" alt="Imagen"
                      style="height: 20%; position: absolute; right: 15%;">
                    <h5 class="card-text" style="color: black;"> Precio: <?php echo htmlspecialchars($formattedPrice); ?>
                    </h5><br><br>
                    <div class="d-flex ">
                      <a href="<?php echo $url ?>" class="btn btn-primary " style=" margin-bottom: 8px ;"
                        target="_blank">Ir a la página</a><br>
                    </div>
                    <a href="" style=""><?php echo $listaA ?></a>
                  </div>
                </div>
              </div>
            </div>
            <?php
        }
        ?>
          <!-- Modal para mostrar las listas de deseos -->
          <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="wishlistModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="wishlistModalLabel">Seleccionar lista de deseos</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                  <form action="../class/agregar_producto.php" method="POST">
                    <input type="hidden" name="prod" value="<?php echo $id; ?>">
                    <div class="mb-3">
                      <label for="wishlistSelect" class="form-label">Lista de deseos:</label>
                      <select class="form-select" name="wishlist" id="wishlistSelect">
                        <?php echo $wishlistOptions; ?>
                      </select>
                    </div>
                    <div class="d-flex justify-content-center">
                      <button type="submit" class="btn btn-primary">Añadir</button>
                    </div>
                  </form>
                  <form action="../views/view-listacompra.php">
                    <div class="d-flex justify-content-center">
                      <button type="submit" class="btn btn-link" style="text-decoration:none;">Crear Lista</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <br>

    <br>
    <br>
    <div class="d-flex justify-content-center align-items-center" style="background-color: rgb();">
      <!-- height: 100vh es opcional -->
      <div class="col-md-10 text-center">
        <!-- Carrusel para pantallas medianas y grandes -->
        <div id="carouselExampleIndicators" class="carousel slide d-none d-lg-block" data-bs-ride="carousel">
          <div class="carousel-inner">
            <h5><strong>Te recomendamos también estos productos de la categoría <?php echo $brand ?>:</strong></h5><br>
            <?php
            $chunkedProducts = array_chunk($productosAleatorios, 5);
            foreach ($chunkedProducts as $index => $chunk) {
              $activeClass = $index === 0 ? 'active' : '';
              echo "<div class='carousel-item $activeClass'>";
              echo "<div class='row'>";
              foreach ($chunk as $producto) {
                $shortName = strlen($producto['producto_name']) > 20 ? substr($producto['producto_name'], 0, 20) . '...' : $producto['producto_name'];
                $formattedPrice = "$" . number_format($producto['producto_price'], 0, '', '.');
                echo "<div class='col'>";
                echo "<a href='../views/viewproducto.php?id={$producto['id']}' style='text-decoration: none;'>";
                echo "<div class='card' style='background-color: rgb(); width: 12rem; height: 18rem;'>";
                echo "<div class='img-container'>";
                
                echo "<img src='{$producto['producto_image']}' class='card-img-top' alt='Imagen' style='height: 12rem;'>";
                echo "</div>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title' style='color: black; font-size: 1.0rem;'>$shortName</h5>";
                echo "<p class='card-title' style='color: black; font-size: 1.1rem;'>$formattedPrice</p>";
                echo "<img src='$logo' alt='Imagen' style='height: 15%; position: absolute; bottom: 1%; right: 1%;'>";
                echo "</div>";
                echo "</div>";
                echo "</a>";
                echo "</div>";
              }
              echo "</div>";
              echo "</div> ";
              
            }
            ?>
          </div>
          <br>
          <div style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-top: 10px;">
            <!-- Botón de control anterior -->
            <button
    style="
        background: rgb(71, 126, 213); 
        border: none; 
        font-size: 30px; 
        color: #333; 
        cursor: pointer; 
        transition: color 0.3s ease, transform 0.3s ease; 
        border-radius: 50%; /* Hace el botón redondo */
        width: 60px; /* Ancho del botón */
        height: 60px; /* Alto del botón */
        display: flex; /* Para centrar el contenido */
        align-items: center; /* Centra verticalmente */
        justify-content: center; /* Centra horizontalmente */
    "
    onmouseover="this.style.color='#6b8cff'; this.style.transform='scale(1.2)';"
    onmouseout="this.style.color='#333'; this.style.transform='scale(1)';"
    class="custom-carousel-control-prev" 
    type="button" 
    data-bs-target="#carouselExampleIndicators"
    data-bs-slide="prev">
    <i class="fas fa-chevron-left"></i>
</button>
            <!-- Botón de control siguiente -->
            <button
    style="
        background: rgb(71, 126, 213); 
        border: none; 
        font-size: 30px; 
        color: #333; 
        cursor: pointer; 
        transition: color 0.3s ease, transform 0.3s ease; 
        border-radius: 50%; /* Hace el botón redondo */
        width: 60px; /* Ancho del botón */
        height: 60px; /* Alto del botón */
        display: flex; /* Para centrar el contenido */
        align-items: center; /* Centra verticalmente */
        justify-content: center; /* Centra horizontalmente */
    "
    onmouseover="this.style.color='#6b8cff'; this.style.transform='scale(1.2)';"
    onmouseout="this.style.color='#333'; this.style.transform='scale(1)';"
    class="custom-carousel-control-next" 
    type="button" 
    data-bs-target="#carouselExampleIndicators"
    data-bs-slide="next">
    <i class="fas fa-chevron-right"></i>
</button>
            <div class="d-block d-md-none">
              <p class="text-center">Visita nuestra tienda para ver los productos recomendados.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

</body>
<br>

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