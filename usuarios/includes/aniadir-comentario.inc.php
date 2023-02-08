<?php

/**
 * Este fichero es el encargado de guardar un nuevo comentario en la base de datos
 * Este fichero será llamado desde el fichero reporte.php de la carpeta vistas.
 */

// Cogemos los datos introducidos
$idreporte = $_GET['id'];
$comentario = $_POST['comentario'];

// Comprobamos que los campos no están vacios
if (!empty($comentario)) {
    // Guardamos el reporte en la base de datos
    $ejecucion = $consulta->guardarComentario(
        $_SESSION['id'],
        $idreporte,
        $comentario,
        date('d-m-Y H:i:s')
    );

    // Si se ha guardado correctamente el reporte...
    if ($ejecucion) {
        header('Location: /reporte/' . $idreporte);
    } else {
        $error = $ERROR_GENERAL;
    }
} else {
    $error = $CAMPOS_VACIOS;
}
