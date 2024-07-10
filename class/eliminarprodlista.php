<?php

include_once "../Class/connection.php";
$con = new connection;
$conn = $con->conectar();
$idP = mysqli_real_escape_string($conn, $_GET['idP']); //id Producto
$idL = mysqli_real_escape_string($conn, $_GET['idL']); //id Lista

if ($conn != true) {
    die("Error de conexión " . mysqli_connect_error());
} else {
    $sql_eliminar = "DELETE FROM listaproductos WHERE id_producto = ? AND id_listacompra = ?";
    $query_eliminar = $conn->prepare($sql_eliminar);
    $query_eliminar->bind_param('ii', $idP, $idL);
    $query_eliminar->execute();
    
    session_start();
    $_SESSION['alert_message'] = "¡Los datos se eliminaron correctamente!";

    // Redirigir a la vista view-verlista.php
    header("Location: ../Views/view-verlista.php?idL=" . urlencode($idL));
    exit();
}
?>

