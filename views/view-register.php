<?php
include "../class/connection.php";
$conn = new connection();
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <title>LogIn</title>
  <link rel="stylesheet" type="text/css" href="../Css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>

  <nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
      <div class="navbar-left">
        <a class="navbar-brand" style="display: flex; justify-content: center;" href="index.php">Ahorrando®</a>
      </div>

    </div>
  </nav>
  <h2 style="display: flex; justify-content: center;">Registro</h2>
  <!-- Logo Ahorrando -->
  <div class="fadeIn first" style="display: flex; justify-content: center;">

  </div>


  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><img src="../img/ahor.png" id="icon" alt="User Icon" style="width: 30% ;" /></h4>
          </div>
          <div class="card-body">
            <div class="card">
              <br>
              <form name="f1" style=" justify-content: center; margin-left: 33%; " action="../class/newUsr.php"
                method="POST" onsubmit="return validateForm()">🧔🏽‍♂️
                <input type="text" style="margin-bottom:5px ;" id="user" class="fadeIn second" name="user"
                  placeholder="Usuario" required>
                <br> 📨 <input type="email" id="email" style="margin-bottom:5px ;" class="fadeIn third" name="email"
                  placeholder="email" required onblur="validateEmail(this.value)">
                <span id="emailError" style="color: red; display: none;">Ingresa un correo electrónico válido</span>
                <br> 🔒 <input type="password" id="password" class="fadeIn third" name="pass" placeholder="Contraseña"
                  required onkeyup="maskPassword(this)">
                <br>
                <br> <input type="submit" class="fadeIn fourth" value="Registrarse" style="margin-left: 14%">
              </form>
              <p style="display: flex; justify-content: center;">Ya tienes cuentas?</p><a href="index.php"
                style="display: flex; justify-content: center;">Iniciar sesion</a>
              <!-- Remind Passowrd -->
             <p><?php
    // Verificar si hay un mensaje de error almacenado en la sesión
    
    $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
    unset($_SESSION['error_message']); // Limpiar el mensaje de error de la sesión
    
    // Mostrar el mensaje de error si existe
    if (!empty($errorMessage)) {
      echo '<p style="color: red;">' . $errorMessage . '</p>';
    }
    ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <div id="formFooter" style="display: flex; justify-content: center; ">
      Ahorrando® 2024
    </div>


</body>

</html>