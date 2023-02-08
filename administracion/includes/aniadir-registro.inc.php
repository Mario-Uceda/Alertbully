<?php

/**
 * Este fichero es el encargado de guardar un nuevo registro.
 * Este fichero será llamado desde el fichero aniadir-registro.php de la carpeta vistas
 */

// Cogemos los datos mandandos por el form y los guardamos en variables
$centroElegido = $_POST['centroElegido'];
$usuarioElegido = $_POST['usuarioElegido'];
$rolElegido = $_POST['rolElegido'];
$clase = strtoupper($_POST['clase']); // Guardamos la clase todo en mayúsculas

// Comprobamos que los datos del formulario no estén vacios
if (!empty($centroElegido) && !empty($usuarioElegido) && !empty($rolElegido)) {
    // Comprobamos que el centro elegido existe
    if (($centro = $consulta->getDatosCentro($centroElegido)) != null) {
        // Comprobamos que el usuario existe
        if (($usuario = $consulta->getDatosUsuarioConID($usuarioElegido)) != null) {
            // Comprobamos que el rol elegido es dirección, tutor o Alumno
            if ($rolElegido == "direccion" || $rolElegido == "tutor" || $rolElegido == "alumno") {
                // Obtenemos el curso actual
                $now = new DateTime();
                $year = $now->format('Y');
                $curso = ((($now->format('m') < 8) ? $year - 1 : $year) . "/" . (($now->format('m') < 8) ? $year : $year));

                // En caso de que el rol elegido sea tutor o alumno, comprobamos que la clase no está vacia
                if ($rolElegido == "tutor" || $rolElegido == "alumno") {
                    if(!empty($clase)){
                        // Guardamos el registro en la base de datos
                        $ejecucion = $consulta->crearRegistro($usuarioElegido, $centroElegido, $rolElegido, $clase, $curso, 1);
                    } else {
                        $error = $CLASE_VACIA;
                    }
                } else {
                    // Guardamos el registro en la base de datos
                    $ejecucion = $consulta->crearRegistro($usuarioElegido, $centroElegido, $rolElegido, null, $curso, 1);
                }

                // Si se ha guardado correctamente el usuario...
                if ($ejecucion) {
                    header('Location: /aniadir-registro?registro=creado');
                } else {
                    $error = $REGISTRO_EXISTE;
                }
            } else {
                $error = $ROL_ELEGIDO_INCORRECTO;
            }
        } else {
            $error = $USUARIO_EXISTE;
        }
    } else {
        $error = $CENTRO_NO_EXISTE;
    }
} else {
    $error = $CAMPOS_VACIOS;
}
