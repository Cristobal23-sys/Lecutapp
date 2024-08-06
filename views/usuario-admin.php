<?php
require_once '../class/connection.php';

$conn = new connection();
$conexion = $conn->conectar();
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Lógica para el CRUD de usuarios
if (isset($_POST['create'])) {
    $newUsername = $_POST['newUsername'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    $newEmail = $_POST['newEmail'];
    $newRol = $_POST['newRol'];

    $sql = "INSERT INTO usuario (username, pass, email, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }
    $stmt->bind_param("ssss", $newUsername, $newPassword, $newEmail, $newRol);
    $result = $stmt->execute();
    if ($result === false) {
        die("Error en la ejecución de la consulta: " . $stmt->error);
    }
    // No redirigir, mantener en la misma página
}

if (isset($_POST['update'])) {
    $userId = $_POST['userId'];
    $updateUsername = $_POST['updateUsername'];
    $updateEmail = $_POST['updateEmail'];
    $updateRol = $_POST['updateRol'];

    $sql = "UPDATE usuario SET username = ?, email = ?, rol = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }
    $stmt->bind_param("sssi", $updateUsername, $updateEmail, $updateRol, $userId);
    $result = $stmt->execute();
    if ($result === false) {
        die("Error en la ejecución de la consulta: " . $stmt->error);
    }

    // Notificación y redirección
    echo "<script>
            alert('Usuario editado correctamente.');
            window.location.href = '../views/usuario-admin.php';
          </script>";
}


if (isset($_POST['delete'])) {
    $userId = $_POST['userId'];

    // Confirmación antes de eliminar
    echo "<script>
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                // Primero actualizar las entradas en listaproductos para quitar la referencia
                var xhr = new XMLHttpRequest();
                xhr.open('POST', window.location.href, true); // Enviar a la misma página
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert('Usuario eliminado correctamente.');
                        window.location.href = '../views/usuario-admin.php';
                    } else {
                        alert('Error al eliminar el usuario.');
                    }
                };
                xhr.send('userId=" . $userId . "&delete=true'); // Enviar el ID del usuario y la acción de eliminar
            }
          </script>";

    // Primero actualizar las entradas en listaproductos para quitar la referencia
    $sql = "UPDATE listaproductos lp
            JOIN listacompra lc ON lp.id_listacompra = lc.id
            SET lp.id_listacompra = NULL
            WHERE lc.id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }
    $stmt->bind_param("i", $userId);
    $result = $stmt->execute();
    if ($result === false) {
        die("Error en la ejecución de la consulta: " . $stmt->error);
    }

    // Eliminar las entradas en listacompra
    $sql = "DELETE FROM listacompra WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }
    $stmt->bind_param("i", $userId);
    $result = $stmt->execute();
    if ($result === false) {
        die("Error en la ejecución de la consulta: " . $stmt->error);
    }

    // Finalmente eliminar el usuario
    $sql = "DELETE FROM usuario WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }
    $stmt->bind_param("i", $userId);
    $result = $stmt->execute();
    if ($result === false) {
        die("Error en la ejecución de la consulta: " . $stmt->error);
    }
}



// Manejo de búsqueda
$search = isset($_POST['search']) ? '%' . $_POST['search'] . '%' : '%';
$sql = "SELECT * FROM usuario WHERE username LIKE ? OR email LIKE ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

// Obtener usuarios para la edición
$editUserId = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$editUser = null;
if ($editUserId) {
    $sql = "SELECT * FROM usuario WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $editUserId);
    $stmt->execute();
    $editUser = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
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

</head>

<body>

    <div class="container mt-5">
        <a href="../views/admin.php" style="text-decoration: none;">⬅️<Strong>VOLVER</Strong></a>
        <h2>Administrar Usuarios</h2>



      <!-- Botón para mostrar el modal de creación -->
    <button id="showCreateForm" class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#createUserModal">Crear Nuevo Usuario</button>

<!-- Modal para crear usuario -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Crear Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="newUsername" class="form-label">Nombre de Usuario:</label>
                        <input type="text" id="newUsername" name="newUsername" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Contraseña:</label>
                        <input type="password" id="newPassword" name="newPassword" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="newEmail" class="form-label">Email:</label>
                        <input type="email" id="newEmail" name="newEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="newRol" class="form-label">Rol:</label>
                        <select id="newRol" name="newRol" class="form-select" required>
                            <option value="1">Usuario Común</option>
                            <option value="0">Administrador</option>
                        </select>
                    </div>
                    <button type="submit" name="create" class="btn btn-primary">Crear Usuario</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

      
        <!-- Modal para Editar Usuario -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <input type="hidden" name="userId" id="editUserId" value="">
                            <div class="mb-3">
                                <label for="updateUsername" class="form-label">Nombre de Usuario:</label>
                                <input type="text" id="updateUsername" name="updateUsername" class="form-control"
                                    required minlength="8">
                            </div>
                            <div class="mb-3">
                                <label for="updateEmail" class="form-label">Email:</label>
                                <input type="email" id="updateEmail" name="updateEmail" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateRol" class="form-label">Rol:</label>
                                <select id="updateRol" name="updateRol" class="form-select" required>
                                    <option value="1">Usuario Común</option>
                                    <option value="0">Administrador</option>
                                </select>
                            </div>
                            <button type="submit" name="update" class="btn btn-warning">Actualizar Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            // Función para establecer los datos del usuario a editar en el modal
            function setEditUser(userId) {
                // Realizar una llamada AJAX para obtener los datos del usuario desde el servidor
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '../class/obtener_usuario.php?id=' + userId, true); // Cambia 'obtener_usuario.php' a la ruta correcta
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var user = JSON.parse(xhr.responseText);
                        document.getElementById('editUserId').value = user.id;
                        document.getElementById('updateUsername').value = user.username;
                        document.getElementById('updateEmail').value = user.email;
                        document.getElementById('updateRol').value = user.rol;
                    } else {
                        console.error('Error al obtener los datos del usuario.');
                    }
                };
                xhr.send();
            }
        </script>

        <!-- Barra de búsqueda -->
        <form method="post" action="" class="mb-4">
            <div class="mb-3">
                <label for="search" class="form-label">Buscar Usuarios:</label>
                <input type="text" id="search" name="search" class="form-control"
                    value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>


        <!-- Tabla de usuarios -->
        <h2>Lista de Usuarios</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['rol'] == '0' ? 'Administrador' : 'Usuario Común'; ?></td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                onclick="setEditUser(<?php echo $row['id']; ?>)">Editar</a>
                            <form method="post" action="" style="display:inline-block;">
                                <input type="hidden" name="userId" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Borrar</button>
                            </form>
                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Agregar enlace a Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript para mostrar y ocultar formularios -->
    <script>
        document.getElementById('showCreateForm').addEventListener('click', function () {
            document.getElementById('createForm').style.display = 'block';
            this.style.display = 'none';
        });

        document.getElementById('hideCreateForm').addEventListener('click', function () {
            document.getElementById('createForm').style.display = 'none';
            document.getElementById('showCreateForm').style.display = 'block';
        });
    </script>
</body>

</html>