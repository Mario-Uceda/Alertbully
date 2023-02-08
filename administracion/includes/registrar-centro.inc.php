<?php

/**
 * Fichero para realizar el registro de centros en la base de datos.
 * Este fichero será llamado desde el fichero registrar-centro.php de la carpeta vistas. 
 */

// Cogemos los datos introducidos por el usuario
$idCentro = htmlspecialchars($_POST['idCentro']);
$nombreCentro = htmlspecialchars($_POST['nombreCentro']);
$direccionCentro = htmlspecialchars($_POST['direccionCentro']);
$poblacionCentro = htmlspecialchars($_POST['poblacionCentro']);
$tlfnCentro = htmlspecialchars($_POST['tlfnCentro']);

// Comprobamos si alguno de los campos está vacio, en caso de que no haya ninguno vacio...
if (!empty($idCentro) && !empty($nombreCentro) && !empty($direccionCentro) && !empty($poblacionCentro) && !empty($tlfnCentro)) {
    // Comprobamos que el id del centro sea un número
    if (!is_numeric($idCentro)) {
        $error = $CODIGO_DEBER_SER_NUMERO;
        // Comprobamos que el teléfono sea un número
    } else if (!is_numeric($tlfnCentro)) {
        $error = $TLFN_DEBER_SER_NUMERO;
        // Comprobamos que el número de teléfono no tenga menos de 9 números
    } else if ($tlfnCentro < 9){
        $error = $TLFN_NO_ADMITIDO;
        // Si no hay problemas...
    } else {
        // Comprobamos si existe un centro a partir de $idCentro con la función getDatosCentro()
        // y lo guardamos en $centro
        $centro = $consulta->getDatosCentro($idCentro);

        // Si $centro no es null es porque existe un centro
        if ($centro != null) {
            $error = $CENTRO_EXISTE;
            // Si no...
        } else {
            // Guardamos el centro en la base de datos a través de la función guardarCentro()
            // Guardamos el estado de la ejecución de la consulta en $ejecucion
            $ejecucion = $consulta->guardarCentro($idCentro, $nombreCentro, $direccionCentro, $poblacionCentro, $tlfnCentro);

            // Si la consulta no falla, avisamos de que se ha guardado el centro en la BBDD
            if ($ejecucion) {
                header('Location: /centros?centro=registrado');
                // Si la consulta da error, le notificamos al usuario de que ha ocurrido un error
            } else {
                $error = $ERROR_GENERAL;
            }
        }
    }
} else {
    $error = $CAMPOS_VACIOS;
}
