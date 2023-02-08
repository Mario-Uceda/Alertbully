<?php

/**
 * Este fichero tiene la lógica de cerrar un centro.
 * Este fichero será llamado desde el fichero panel.php de la carpeta vistas.
 */

// Cogemos los datos introducidos
$idcentro = htmlspecialchars($_POST['centroElegido']);

// Comprobamos que los campos no están vacios
if (
    !empty($idcentro)
) {
    // Comprobamos que el centro elegido existe
    if (($centro = $consulta->getDatosCentro($idcentro)) != null) {
        // Comprobamos que el usuario está en ese centro
        $registro = $consulta->usuarioEstaEnCentro($_SESSION['id'],$idcentro, true);
        if($registro != null){
            // Dependiendo del rol, se hará una query u otra
            if ($registro['rol'] == 'tutor') {
                $ejecucion = $consulta->cerrarCurso($idcentro, $registro['clase'], 'tutor');
            } else if($registro['rol'] == 'direccion') {
                $ejecucion = $consulta->cerrarCurso($idcentro, $registro['clase'], 'direccion');
            }

            if($ejecucion) {
                header("Location: /?curso=cerrado");
            } else {
                $error = $ERROR_GENERAL;
            }
        }else{
            $error = $NO_PERTENECE_CENTRO;
        }
    } else {
        $error = $CENTRO_NO_EXISTE;
    }
} else {
    $error = $CAMPOS_VACIOS;
}