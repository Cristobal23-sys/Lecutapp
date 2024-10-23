<?php

require_once '../class/connection.php';

$conn = new connection();

try {
  $connection = $conn->conectar();
  session_start();

  // Obtener el término de búsqueda
  $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

  // Obtener el filtro de precio
  $precio = isset($_GET['precio']) ? $_GET['precio'] : '';

  // Procesar el filtro de precio
  $precioCondition = '';
  if ($precio) {
    $precioParts = explode('-', $precio);
    if (count($precioParts) == 2) {
      $minPrice = (int) $precioParts[0];
      $maxPrice = (int) $precioParts[1];
      if ($maxPrice > 0) {
        $precioCondition = " AND producto_price BETWEEN $minPrice AND $maxPrice";
      } else {
        $precioCondition = " AND producto_price >= $minPrice";
      }
    }
  }

  // Obtener el número total de registros que coinciden con el término de búsqueda y filtro de precio
  $sqlTotal = "SELECT COUNT(*) AS total FROM producto WHERE producto_name LIKE '%$buscar%' $precioCondition";
  $resultTotal = mysqli_query($connection, $sqlTotal);
  $rowTotal = mysqli_fetch_assoc($resultTotal);
  $totalRegistros = $rowTotal['total'];

  // Obtener categorías y tipos de receta
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

  // Obtener el tipo de orden seleccionado
  $orden = isset($_GET['orden']) ? $_GET['orden'] : '';

  // Definir la cantidad de resultados por página
  $resultadosPorPagina = 24;

  // Calcular el número total de páginas
  $totalPaginas = ceil($totalRegistros / $resultadosPorPagina);

  // Obtener el número de página actual
  $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

  // Calcular el índice de inicio y fin de los resultados
  $indiceInicio = ($paginaActual - 1) * $resultadosPorPagina;

  // Reiniciar la consulta SQL
  $sql = "SELECT * FROM producto WHERE producto_name LIKE '%$buscar%' $precioCondition";

  // Ordenar según la opción seleccionada
  switch ($orden) {
    case 'precio_asc':
      $sql .= " ORDER BY producto_price ASC";
      break;
    case 'precio_desc':
      $sql .= " ORDER BY producto_price DESC";
      break;
    case 'nombre_asc':
      $sql .= " ORDER BY producto_name ASC";
      break;
    case 'nombre_desc':
      $sql .= " ORDER BY producto_name DESC";
      break;
    default:
      // Por defecto, ordenar por nombre ascendente
      $sql .= " ORDER BY producto_name ASC";
      break;
  }

  // Modificar la consulta SQL para incluir la paginación
  $sql .= " LIMIT $indiceInicio, $resultadosPorPagina";

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
  <title>Busqueda | LeCut</title>
</head>

<body style="background-color: rgb(255, 255, 255);">
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

<button class="button" data-text="LeCut" onclick="location.href='../views/index.php'">
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
<?php echo "<h4 style='text-align: center; display: block;'>Tus Resultados Por La Busqueda De: <strong>" . htmlspecialchars($buscar) . "</strong>.</h4>"; ?>
 
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="dropdown">
          <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton"
            data-bs-toggle="dropdown" aria-expanded="false">
            🟰FILTROS
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <!-- Ordenar por nombre (A-Z) -->
            <li><a class="dropdown-item"
                href="?buscar=<?php echo htmlspecialchars($buscar); ?>&orden=nombre_asc&pagina=<?php echo $paginaActual; ?>&precio=<?php echo htmlspecialchars($precio); ?>"
                <?php if ($orden == 'nombre_asc')
                  echo 'class="active"'; ?>>Nombre (A-Z)</a></li>
            <!-- Ordenar por nombre (Z-A) -->
            <li><a class="dropdown-item"
                href="?buscar=<?php echo htmlspecialchars($buscar); ?>&orden=nombre_desc&pagina=<?php echo $paginaActual; ?>&precio=<?php echo htmlspecialchars($precio); ?>"
                <?php if ($orden == 'nombre_desc')
                  echo 'class="active"'; ?>>Nombre (Z-A)</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <!-- Ordenar por precio (Menor a Mayor) -->
            <li><a class="dropdown-item"
                href="?buscar=<?php echo htmlspecialchars($buscar); ?>&orden=precio_asc&pagina=<?php echo $paginaActual; ?>&precio=<?php echo htmlspecialchars($precio); ?>"
                <?php if ($orden == 'precio_asc')
                  echo 'class="active"'; ?>>Precio (Menor a Mayor)</a></li>

            <!-- Ordenar por precio (Mayor a Menor) -->
            <li><a class="dropdown-item"
                href="?buscar=<?php echo htmlspecialchars($buscar); ?>&orden=precio_desc&pagina=<?php echo $paginaActual; ?>&precio=<?php echo htmlspecialchars($precio); ?>"
                <?php if ($orden == 'precio_desc')
                  echo 'class="active"'; ?>>Precio (Mayor a Menor)</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <style>
    .card {
      background-color: rgb();
      width: 100%;
      /* Asegura que la tarjeta use todo el ancho disponible en su columna */
      height: 300px;
      /* Ajusta la altura automáticamente */
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      /* Centra el contenido horizontalmente */
      transition: transform 0.3s ease;
      /* Añade una transición suave */
    }

    .card:hover {
      transform: scale(1.05);
      /* Aumenta el tamaño de la tarjeta al pasar el mouse */
      z-index: 1;
      /* Asegura que la tarjeta esté por encima de otras */
    }

    .img-container {
      width: 80%;
      height: 90%;
      overflow: hidden;
      /* Asegura que la imagen no se desborde */
      display: flex;
      justify-content: center;
      /* Centra horizontalmente */
      align-items: center;
      /* Centra verticalmente */
    }

    .img-container img {
      width: 100%;
      /* La imagen ocupa todo el ancho del contenedor */
      height: auto;
      /* Mantiene la proporción de la imagen */
      object-fit: cover;
      /* Ajusta la imagen para cubrir el contenedor sin distorsionarse */
    }



    .card-body {
      padding: 0.5rem;
      /* Reduce el padding para acercar el texto a la imagen */
      text-align: center;
      /* Centra el texto */
    }

    .card-title {
      margin-bottom: 0.5rem;
      /* Reduce el margen inferior del título */
    }

    .logo-img {
      width: 40px;
      /* Ajusta el tamaño según sea necesario */

      position: absolute;
      bottom: 1%;
      right: 1%;
    }
  </style>
  <div class="container" style="background-color:rgb(); margin-top: 25px;">
    <div class="d-flex justify-content-center">
      <div class="container mt-4">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 row-cols-xl-8 g-4">
          <?php
          if ($count > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $id = $row['id'];
              $name = $row['producto_name'];
              $urlImagen = $row['producto_image'];
              $price = $row['producto_price'];
              $brand = $row['producto_categoria'];
              $logo = $row['producto_logo'];
              $shortName = substr($name, 0, 35);
              $formattedPrice = "$" . number_format($price, 0, '', '.');
              ?>
              <div class="col">
                <a href="../views/viewProducto.php?id=<?php echo $id; ?>" style="text-decoration: none;">
                  <div class="card">
                    <div class="img-container">
                      <img src="<?php echo $urlImagen; ?>" alt="Imagen Producto">
                    </div>
                    <div class="card-body">
                      <h6 class="card-title" style="color:#272727"><strong><?php echo $shortName; ?></strong></h6>
                      <p class="card-text"><?php echo $brand; ?></p>
                      <p class="card-title">
                      <h5><strong><?php echo $formattedPrice; ?></strong></h5>
                      </p>
                    </div>
                    <img src="<?php echo $logo; ?>" alt="Imagen" class="logo-img">
                  </div>
                </a>
              </div>
              <?php
            }
          } else {
            echo "<span style='text-align: center; display: block;'>No se encontraron productos de la búsqueda: <strong>" . htmlspecialchars($buscar) . "</strong>.</span>";
            }
            mysqli_close($connection);
            ?>
        </div>
    </div>
    </div>
  </div>
  <br>
  <!-- paginacion -->
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
      <?php if ($paginaActual > 1): ?>
        <li class="page-item">
          <a class="page-link"
            href="?pagina=<?php echo ($paginaActual - 1); ?>&buscar=<?php echo htmlspecialchars($buscar); ?>&orden=<?php echo htmlspecialchars($orden); ?>&precio=<?php echo htmlspecialchars($precio); ?>"
            aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
          </a>
        </li>
      <?php endif; ?>
      <?php
      // Calcular los límites inferior y superior para las páginas
      $limiteInferior = max(1, $paginaActual - 2);
      $limiteSuperior = min($totalPaginas, $paginaActual + 2);
      for ($i = $limiteInferior; $i <= $limiteSuperior; $i++):
        ?>
        <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
          <a class="page-link"
            href="?pagina=<?php echo $i; ?>&buscar=<?php echo htmlspecialchars($buscar); ?>&orden=<?php echo htmlspecialchars($orden); ?>&precio=<?php echo htmlspecialchars($precio); ?>"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>
      <?php if ($paginaActual < $totalPaginas): ?>
        <li class="page-item">
          <a class="page-link"
            href="?pagina=<?php echo ($paginaActual + 1); ?>&buscar=<?php echo htmlspecialchars($buscar); ?>&orden=<?php echo htmlspecialchars($orden); ?>&precio=<?php echo htmlspecialchars($precio); ?>"
            aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
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