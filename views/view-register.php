<?php
include "../class/connection.php";
$conn = new connection();
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>Registro | LeCut</title>
  <link rel="stylesheet" href="../Css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="../img/lecut.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<nav class="navbar bg-body-tertiary">
  <a class="navbar-brand" href="../index.php">
    <img src="https://i.postimg.cc/vBfDj9sv/icono-removebg-preview.png" alt="Logo" style="height: 50px; width: auto; margin-left: 10%;">
  </a>
</nav>



  <h2 class="text-center mt-4">Registro</h2>

  <style>
    .message {
      padding: 10px;
      margin: 10px 0;
      border: 1px solid transparent;
      border-radius: 5px;
    }
    .message.success {
      color: #155724;
      background-color: #d4edda;
      border-color: #c3e6cb;
    }
    .message.error {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
    }
    .Signup_paragraphText__MbGY9 {
      font-family: 'Barlow', sans-serif;
      font-size: 16px;
      text-align: center;
      color: #464646;
    }
  </style>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card">
          <div class="card-header text-center">
            <h4><img src="https://i.postimg.cc/C55HkmDK/iconosuperprime.png" alt="User Icon" style="width: 20%;" /></h4>
          </div>
          <div class="card-body">
            <p class="Signup_paragraphText__MbGY9">Regístrate y disfruta de nuestro sistema para organizar tus próximas compras.</p>
            <form name="f1" action="../class/newUsr.php" method="POST" onsubmit="return validateForm()">
              <div class="form-floating mb-3">
                <input type="text" name="user" class="form-control" id="floatingInput" placeholder="Usuario" required>
                <label for="floatingInput">Usuario</label>
              </div>
              <div class="form-floating mb-3">
                <input type="email" id="email" class="form-control" placeholder="name@example.com" name="email" required onblur="validateEmail(this.value)">
                <label for="email">Email address</label>
                <span id="emailError" class="text-danger d-none">Ingresa un correo electrónico válido</span>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="pass" placeholder="Contraseña" required onkeyup="maskPassword(this)">
                <label for="password">Contraseña</label>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
              </div>
            </form>
            <p class="text-center mt-3">¿Ya tienes cuenta? <a href="../index.php">Iniciar sesión</a></p>
            
            <?php
            $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
            unset($_SESSION['error_message']);
            if (!empty($errorMessage)) {
                echo '<p class="text-danger text-center">' . $errorMessage . '</p>';
            }

            $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
            unset($_SESSION['success_message']);
            if (!empty($successMessage)) {
                echo '<p class="text-success text-center">' . $successMessage . '</p>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center py-3">
    LeCut® 2024
  </footer>

</body>
<script>
    function validateForm() {
        var username = document.getElementById('user').value;
        if (username.length < 8) {
            alert('El nombre de usuario debe tener al menos 8 caracteres.');
            return false;
        }
        return true;
    }
</script>
</html>
