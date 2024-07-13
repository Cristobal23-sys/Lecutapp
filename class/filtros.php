<?php
// Obtener el tipo de orden seleccionado
$orden = isset($_GET['orden']) ? $_GET['orden'] : '';

// Reiniciar la consulta SQL
$sql = "SELECT * FROM `producto` WHERE producto_name LIKE '%$buscar%'";

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
        // Por defecto, ordenar por algún criterio, por ejemplo, por nombre ascendente
        $sql .= " ORDER BY producto_name ASC";
        break;
}

// Modificar la consulta SQL para incluir la paginación
$sql .= " LIMIT $indiceInicio, $resultadosPorPagina";
