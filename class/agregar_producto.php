<?php
include_once "../Class/connection.php";
$con = new connection;
$conn = $con->conectar();

if ($conn != true) {
    die("Error de conexión " . mysqli_connect_error());
}

if (isset($_POST['prod']) && isset($_POST['wishlist'])) {
    $prod = mysqli_real_escape_string($conn, $_POST['prod']);
    $wishlist = mysqli_real_escape_string($conn, $_POST['wishlist']);

    // Verificar si el producto y la lista de deseos existen
    $productQuery = "SELECT id FROM producto WHERE id = '$prod'";
    $wishlistQuery = "SELECT id FROM listacompra WHERE id = '$wishlist'";

    $productResult = mysqli_query($conn, $productQuery);
    $wishlistResult = mysqli_query($conn, $wishlistQuery);

    if ($productResult && $wishlistResult) {
        if (mysqli_num_rows($productResult) > 0 && mysqli_num_rows($wishlistResult) > 0) {
            // Tanto el producto como la lista de deseos existen, proceder a agregar el producto a la lista de deseos

            // Verificar si el producto ya está en la lista de deseos
            $checkQuery = "SELECT id FROM listaproductos WHERE id_producto = '$prod' AND id_listacompra = '$wishlist'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if ($checkResult) {
                if (mysqli_num_rows($checkResult) > 0) {
                    // El producto ya está en la lista de deseos
                    session_start();
                    $_SESSION['alert_message'] = "El producto ya está en la lista de deseos.";
                    // Redirigir a la vista wishLists.php
                    header("Location: ../views/view-verlista.php?idL=" . urlencode($wishlist));
                    exit();
                } else {
                    // Agregar el producto a la lista de deseos
                    $insertQuery = "INSERT INTO listaproductos (id_producto, id_listacompra) VALUES ('$prod', '$wishlist')";
                    if (mysqli_query($conn, $insertQuery)) {
                        session_start();
                        $_SESSION['alert_message'] = "¡Producto añadido!";
                        // Redirigir a la vista wishLists.php
                        header("Location: ../views/view-verlista.php?idL=" . urlencode($wishlist));
                        exit();
                    } else {
                        echo "Error al agregar el producto a la lista de deseos: " . mysqli_error($conn);
                    }
                }
            } else {
                echo "Error en la consulta de verificación: " . mysqli_error($conn);
            }
        } else {
            // El producto o la lista de deseos no existen
            echo "El producto o la lista de deseos no existen.";
        }
    } else {
        echo "Error en la consulta de producto o lista de deseos: " . mysqli_error($conn);
    }
} else {
    // Parámetros inválidos
    echo "Parámetros inválidos.";
}

mysqli_close($conn);
?>