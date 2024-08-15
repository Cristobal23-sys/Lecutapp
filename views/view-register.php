<?php
include "../class/connection.php";
$conn = new connection();
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Registro | LeCut</title>
  <link rel="stylesheet" type="text/css" href="../Css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="icon" href="../img/lecut.ico">
</head>

<body>

  <nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
      <div class="navbar-left">
        <a class="navbar-brand" style="display: flex; justify-content: center;" href="index.php">
        <img src="https://i.postimg.cc/vBfDj9sv/icono-removebg-preview.png" alt="Logo" style="height: 50px; width: auto;">
        </a>
      </div>

    </div>
  </nav>
  <h2 style="display: flex; justify-content: center;">Registro</h2>
  <!-- Logo -->
  <div class="fadeIn first" style="display: flex; justify-content: center;">

  </div>
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

    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }
    .Signup_paragraphText__MbGY9 {
  font-family: 'Barlow', sans-serif !important;
  font-style: normal !important;
  font-weight: 400 !important;
  font-size: 16px !important;
  line-height: 19.2px !important;
  text-align: center !important;
  color: #464646 !important;
}
    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h4 class="card-title"><img src="https://i.postimg.cc/C55HkmDK/iconosuperprime.png id="icon" alt="User Icon" style="width: 20% ;" /></h4>
          </div>
          <div class="card-body">
          <p class="Signup_paragraphText__MbGY9">Regístrate y disfruta de nuestros sistema para crear y organizar tus proximas compras en el supermercado</p>
            <br>
            <form name="f1" style=" justify-content: center; margin-left: 33%; " action="../class/newUsr.php"
              method="POST" onsubmit="return validateForm()">



              <div class="form-floating" style="width: 50% ;">
                <input type="text" name="user" style="margin-bottom:5px ;" class="form-control" id="floatingInput"
                  placeholder="Usuario" required>
                <label for="floatingInput">Usuario</label>
              </div>
              <br>
              <div class="form-floating" style="width: 50% ;">
                <input type="email" id="email" class="form-control" id="floatingInput" placeholder="name@example.com"
                  name="email" required onblur="validateEmail(this.value)">
                <label for="floatingInput">Email address</label>
                <span id="emailError" style="color: red; display: none;">Ingresa un correo electrónico válido</span>
              </div>
              <br>
              <div class="form-floating" style="width: 50% ;">
                <input type="password" class="form-control" id="password" name="pass"
                  placeholder="Contraseña" required onkeyup="maskPassword(this)">
                <label for="floatingPassword">Contraseña</label>
              </div>
              <br>
              <br> <input type="submit" class="w-30 btn btn-lg btn-primary" value="Registrarse" style="margin-left: 9%">
            </form>
            <p style="display: flex; justify-content: center;">Ya tienes cuentas?</p><a href="index.php"
              style="display: flex; justify-content: center;">Iniciar sesion</a>
            <!-- Remind Passowrd -->
            <p>   <?php
            $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
            unset($_SESSION['error_message']);
            if (!empty($errorMessage)) {
                echo '<p style="color: red; display: flex; justify-content: center;">' . $errorMessage . '</p>';
            }

            $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
            unset($_SESSION['success_message']);
            if (!empty($successMessage)) {
                echo '<p style="color: green; display: flex; justify-content: center;">' . $successMessage . '</p>';
            }
            ?></p>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="formFooter" style="display: flex; justify-content: center; ">
    LeCut® 2024
  </div>


</body>
<script>
    function validation() {
        var username = document.getElementById('user').value;

        if (username.length < 8) {
            alert('El nombre de usuario debe tener al menos 8 caracteres.');
            return false;
        }

        return true;
    }
</script>
</html>