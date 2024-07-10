<?php
include_once "../Class/connection.php";
$con = new connection;
$conn = $con->conectar();
$id = mysqli_real_escape_string($conn, $_GET['id']);

if ($conn != true) {
    die("Error de conexión " . mysqli_connect_error());
} else {
    // Verificar si existe una relación en tbl_calwish con la id_wishlist igual a $id
    $sql_verificar = "SELECT id FROM listaproductos WHERE id_listacompra = ?";
    $query_verificar = $conn->prepare($sql_verificar);
    $query_verificar->bind_param('i', $id);
    $query_verificar->execute();
    $resultado_verificar = $query_verificar->get_result();

    if ($resultado_verificar->num_rows > 0) {
        // Si existe una relación en tbl_calwish, eliminarla
        $sql_eliminar_relacion = "DELETE FROM listaproductos WHERE id_listacompra = ?";
        $query_eliminar_relacion = $conn->prepare($sql_eliminar_relacion);
        $query_eliminar_relacion->bind_param('i', $id);
        $query_eliminar_relacion->execute();
    }

    // Eliminar el dato de tbl_wishlist donde id sea igual a $id
    $sql_eliminar = "DELETE FROM listacompra WHERE id = ?";
    $query_eliminar = $conn->prepare($sql_eliminar);
    $query_eliminar->bind_param('i', $id);
    $query_eliminar->execute();
    session_start();
    $_SESSION['alert_message'] = "Tu lista ha sido eliminada";
    // Redirigir a la vista listacompra.php
    header("Location: ../Views/view-listacompra.php");
    exit();
}
?>
