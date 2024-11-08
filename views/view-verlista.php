<?php
session_start();
include_once "../Class/connection.php";
$con = new connection;
$conn = $con->conectar();

if (isset($_SESSION['user'])) {
  $inicio = $_SESSION['user']; // Obtener el nombre de usuario de la sesión
  $idL = mysqli_real_escape_string($conn, $_GET['idL']);
  $cambio = "<a class='dropdown-item' href='../views/view-listacompra.php'>Lista de deseos</a>";
  $reg = "<a class='dropdown-item' href='../class/Cerrarsesionlistas.php'>Cerrar Sesión</a>";

  $query = "SELECT COUNT(*) AS count FROM listaproductos WHERE id_listacompra = '$idL'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $count = $row['count'];
} else {
  $inicio = "Iniciar sesión";
  $cambio = "<a class='dropdown-item' href='../Views/view-register.php'>Iniciar Sesión</a>";
  $reg = "<a class='dropdown-item' href='../Views/view-register.php'>Registrarse</a>";
}

if (isset($_SESSION['alert_message'])) {
  $alert_message = $_SESSION['alert_message'];

  // Eliminar el mensaje de la sesión
  unset($_SESSION['alert_message']);
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


<head>
  <link href="..//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="icon" href="../img/lecut.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ahorrando</title>
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
    var regex = /^[a-zA-Z0-9ñÑ\s]+$/;
    
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
  <div class="container" style="background-color: rgb(255, 255, 255); margin-top: 25px;">
  <div class="d-flex justify-content-lg-between" style="margin-inline-start: 15%; margin-inline-end: 16%">
  <a href="../views/view-listacompra.php" class="btn btn-link text-decoration-none" style="color: black">⬅️ Volver a Las Listas</a>
  
  <a href="../index.php" class="btn btn-outline-success">Añadir productos</a>
</div>

      
  <div class="row justify-content-center">      
      <?php
      // Verificar si el usuario tiene listas de deseos
      $sql = "SELECT c.id, c.producto_name AS producto_name, c.producto_url, c.producto_image AS producto_image, c.producto_categoria AS producto_categoria, c.producto_price AS producto_price, c.producto_url AS producto_url, c.producto_logo AS producto_logo
                FROM producto c
                INNER JOIN listaproductos cw ON c.id = cw.id_producto
                INNER JOIN listacompra w ON cw.id_listacompra = w.id
                WHERE w.id = $idL";
      $result = $conn->query($sql);

      $productos = []; 
      $total = 0;
      if ($result) {
        if ($result->num_rows > 0) {
          // Iterar sobre los resultados y mostrar la información
          while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $name = $row['producto_name'];
            $logo = $row['producto_logo'];
            $urlImagen = $row['producto_image'];
            $price = number_format($row['producto_price'], 0, '', '.'); // Formato sin decimales y separador de miles
            $brand = $row['producto_categoria'];
            $url = $row['producto_url'];
            $prices = intval($row['producto_price']); // Convertir a entero si es necesario
            $total += $prices;
           // Inicializas el array de productos vacío o con los productos que ya tienes
            $productos[] = [
              'name' => $name,
              'price' => $price,
              'url' => $url
          ];
            // Formatear el precio con símbolo de dólar y separadores de miles
            $formatted_Price = "$" . number_format($price, 0, '', '.'); // Formato sin decimales y separador de miles

            ?>
<div class="card mb-4 col-12 col-md-8" style="background-color: rgb(255, 255, 255); margin-top: 1px;">
  <div class="row g-0 align-items-start">
    <div class="col-md-4 col-12 d-flex justify-content-center align-items-center"> <!-- Centrado con Flexbox -->
      <img src="<?php echo $urlImagen ?>" class="img-fluid rounded-start card-image" alt="..." style="max-width: 100%; height: auto;">
    </div>
    <div class="col-md-8 d-flex flex-column">
      <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title" style="color: black; font-size: 1.3rem;"><?php echo $name; ?></h5>
          <img src="<?php echo $logo; ?>" alt="Imagen" style="width: 7%;">
        </div>
        <p class="card-text" style="color: black; font-size: 0.8rem;"><?php echo $brand; ?></p>

        <div class="d-flex justify-content-between align-items-center">
          <p class="card-title mb-0" style="color: black; font-size: 1.5rem;">$<?php echo $price; ?></p>

          <!-- Botones alineados a la derecha -->
          <div class="d-flex flex-column align-items-end">
            <a href="../class/eliminarprodlista.php?idP=<?php echo $id ?>&idL=<?php echo $idL ?>"
               class="btn btn-danger mb-2" style="width: 160px;">Eliminar</a>
            <a href="<?php echo $url ?>" target="_blank" class="btn btn-primary" style="width: 160px;">Ir a la tienda</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

            <?php
          }
        } else {
          echo "¡Haz Click en Añadir Productos!";
        }
      } else {
        echo "Error en la consulta: " . mysqli_error($conn);
      }
      ?>
    </div>
    
<?php
    $formatted_total = '$' . number_format($total, 0, ',', '.'); // Formato sin decimales y separador de miles
?>
<!-- mostrar el total -->
<div class="row justify-content-center mt-3">
    <div class="col-auto">
        <h2>Total: <?php echo $formatted_total; ?></h2>
    </div>
</div>
    
</div>
  <?php if (isset($alert_message)): ?>
    <script>
      alert("<?php echo $alert_message; ?>");
    </script>
  <?php endif; 
if (count($productos) == 0) {
  $mensaje = "No existen productos en la lista de compras.";
} else {
  $mensaje = "Estos son los productos en mi lista de compras:\n\n";
  foreach ($productos as $producto) {
      $mensaje .= "• " . $producto['name'] . " - $" . $producto['price'] . "\n  Link: " . $producto['url'] . "\n";
      $total += $producto['price']; // Calculamos el total
  }
  $mensaje .= "\nTotal: $" . number_format($total, 0, ',', '.');
}
 ?>
 <div class="col-12 text-center">
    
     <!-- Botón para compartir por WhatsApp -->
     <button class="btn btn-success" style="margin-top: 10px;" onclick="compartirPorWhatsApp('<?php echo urlencode($mensaje); ?>')">Compartir
         por WhatsApp <i class="fa-brands fa-whatsapp"></i></button>
 </div>
 </div>
 </div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-ENjdO4Dr2bkBIFxQpeoQW1gK8e3bCWvbPR4zOFO8hA7MaJx8oEmNmeFdnelbGI6z" crossorigin="anonymous">
 </script>
 <script>
 function compartirPorWhatsApp(mensaje) {
     var url = "https://wa.me/?text=" + mensaje;
     window.open(url, '_blank');
 }
 </script>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/e801c5457b.js" crossorigin="anonymous"></script>
  <script src="../js/js.js"></script>
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