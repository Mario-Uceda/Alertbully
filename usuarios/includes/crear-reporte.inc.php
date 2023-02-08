<?php

/**
 * Este fichero es el encargado de guardar un nuevo reporte en la base de datos.
 * Este fichero será llamado desde el fichero crear-reporte.php de la carpeta vistas.
 */

// Cogemos los datos introducidos
$titulo = htmlspecialchars($_POST['titulo']);
$victima = htmlspecialchars($_POST['victima']);
$acosador = htmlspecialchars($_POST['acosador']);
$lugar = htmlspecialchars($_POST['lugar']);
$fechaHora = htmlspecialchars($_POST['fechaHora']);
$descripcion = htmlspecialchars($_POST['descripcion']);
$idcentro = htmlspecialchars($_POST['centroElegido']);

// Comprobamos que los campos no están vacios
if ( !empty($titulo) && !empty($victima) && !empty($acosador) && !empty($lugar) && !empty($fechaHora) && !empty($descripcion) && !empty($idcentro)
) {
    // Comprobamos que el centro elegido existe
    if (($centro = $consulta->getDatosCentro($idcentro)) != null) {

        $fechaHoraActual = date('d-m-Y H:i:s');
        $registro = $consulta->getRegistro($_SESSION['id'], $idcentro, 'alumno');

        // Guardamos el reporte en la base de datos
        $ejecucion = $consulta->crearReporte(
            $_SESSION['id'],
            $idcentro,
            $registro['clase'],
            $registro['curso'],
            $titulo,
            $victima,
            $acosador,
            $lugar,
            $descripcion,
            $fechaHora,
            $fechaHoraActual
        );

        // Si se ha guardado correctamente el reporte...
        if ($ejecucion) {
            header('Location: /crear-reporte?reporte=creado');
        } else {
            $error = $ERROR_GENERAL;
        }
    } else {
        $error = $CENTRO_NO_EXISTE;
    }
} else {
    $error = $CAMPOS_VACIOS;
}
