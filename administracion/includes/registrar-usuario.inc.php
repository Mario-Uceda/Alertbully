<?php

/**
 * Fichero para realizar el registro de usuarios en la base de datos.
 * Este fichero será llamado desde el fichero registrar-usuario.php de la carpeta vistas.
 */

// Importamos el fichero con los correos electrónicos
require $_SERVER['DOCUMENT_ROOT'] . '/includes/correos.inc.php';

// Cogemos los datos introducidos
$nombreUsuario = htmlspecialchars($_POST['nombreUsuario']);
$apellidosUsuario = htmlspecialchars($_POST['apellidosUsuario']);
$telefonoUsuario = htmlspecialchars($_POST['telefonoUsuario']);
$emailUsuario = htmlspecialchars($_POST['emailUsuario']);
$admin = isset($_POST['casillaAdministrador']);

// Comprobamos que los campos no están vacios
if (
    !empty($nombreUsuario) && !empty($apellidosUsuario) && !empty($telefonoUsuario) &&  !empty($emailUsuario)
) {

    if (is_numeric($telefonoUsuario)) {
        if ($telefonoUsuario >= 9) {
            // Comprobamos que no hay una cuenta de usuario con ese email
            if (($usuario = $consulta->getDatosUsuarioConEmail($emailUsuario)) == null) {
                // Generamos la contraseña del usuario
                $passwordUsuarioPlano = generarPassword();
                $passwordUsuarioHash = hash('sha512', $passwordUsuarioPlano);

                // Si es administrador....
                if ($admin) {
                    $ejecucion = $consulta->guardarUsuario(
                        $nombreUsuario,
                        $apellidosUsuario,
                        $telefonoUsuario,
                        $emailUsuario,
                        $passwordUsuarioHash,
                        1,
                        1
                    );

                    // Si se ha guardado correctamente el usuario...
                    if ($ejecucion) {
                        correoRegistro($emailUsuario, $passwordUsuarioPlano);
                        header('Location: /usuarios?usuario=registrado');
                    } else {
                        $error = $ERROR_GENERAL;
                    }
                } else {
                    $ejecucion = $consulta->guardarUsuario(
                        $nombreUsuario,
                        $apellidosUsuario,
                        $telefonoUsuario,
                        $emailUsuario,
                        $passwordUsuarioHash,
                        1,
                        0
                    );

                    // Si se ha guardado correctamente el usuario...
                    if ($ejecucion) {
                        correoRegistro($emailUsuario, $passwordUsuarioPlano);
                        header('Location: /usuarios?usuario=registrado');
                    } else {
                        $error = $ERROR_GENERAL;
                    }
                }
            } else {
                $error = $USUARIO_EXISTE;
            }
        } else {
            $error = $TLFN_NO_ADMITIDO;
        }
    } else {
        $error = $TLFN_DEBER_SER_NUMERO;
    }
} else {
    $error = $CAMPOS_VACIOS;
}

/**
 * Función para generar una contraseña de forma aleatoria
 */
function generarPassword()
{
    $tamanioPass = 8;
    $diccionario = '|@#~$%()=^*+[]{}-abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $diccionarioLong = strlen($diccionario) - 1;
    for ($i = 0; $i < $tamanioPass; $i++) {
        $n = rand(0, $diccionarioLong);
        $pass[] = $diccionario[$n];
    }
    return implode($pass); // Convertimos el array a un string
}
