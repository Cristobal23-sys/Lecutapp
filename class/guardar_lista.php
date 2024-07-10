<?php 
include_once "../Class/connection.php";
$con = new connection;
try {
    $conn = $con->conectar();
    $name = $_POST['name'];
    $desc = $_POST['desc']; 
    $idU = $_POST['idU'];
    $sql = "INSERT INTO listacompra (`nombre`, `descripcion`, `id_usuario`) VALUES ('$name', '$desc', '$idU')";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../Views/view-listacompra.php");
        exit();
    } else {
        echo "Error al insertar datos: " . $conn->error;
    }
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage();
}
