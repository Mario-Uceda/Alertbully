<?php

/**
 * Este fichero tiene la lógica del cambio de contraseña una vez logeado el usuario.
 * Este fichero será llamado desde el fichero cambiar-password.php de la carpeta vistas.
 */

// Cogemos los datos introducidos por el usuario
$passActualUsu = htmlspecialchars($_POST['passActual']);
$nuevaPass1 = htmlspecialchars($_POST['nuevaPass1']);
$nuevaPass2 = htmlspecialchars($_POST['nuevaPass2']);

// Cogemos el id del usuario almacenado en la sesión
$idUsuario = $_SESSION['id'];

// Encriptamos en un hash sha512 la contraseña actual introducida por el user
$passActualUsu = hash("sha512", $passActualUsu);

/* 
Comprobamos mediante el método getDatosUsuarioConID si hay usuarios con ese ID
Además devolvemos los datos del usuario para posteriormente trabajar con ello
*/
$usuario = $consulta->getDatosUsuarioConID($idUsuario);

if ($usuario != null) {

    // Guardamos el email en $email para posteriormente mandarle un correo al usuario
    // notificando de que se ha modificado su contraseña
    $email = $usuario['email'];

    // Guardamos en passActualBD la password almacenada en la base de datos
    $passActualBD = $usuario['password'];

    // Si la contraseña almacenada en la BD es la misma que la introducida por el usuario en el formulario...
    if ($passActualBD == $passActualUsu) {
        // Si las nuevas contraseñas son iguales...
        if ($nuevaPass1 == $nuevaPass2) {
            // Si la nueva contraseña cumple los requisitos...
            if ((strlen($nuevaPass1) >= 8) && (preg_match("#[0-9]+#", $nuevaPass1)) && (preg_match("#[A-Z]+#", $nuevaPass1)) && (preg_match("#[a-z]+#", $nuevaPass1)) && (preg_match("#\W+#", $nuevaPass1))
            ) {
                // Encriptamos la nueva contraseña en un hash sha512
                $nuevaPass1 = hash("sha512", $nuevaPass1);

                // Si la contraseña almacenada en la base de datos es diferente a la nueva pass...
                if ($passActualBD != $nuevaPass1) {
                    // Actualizamos la contraseña del usuario y guardamos en $ejecucion el estado de la consulta
                    $ejecucion = $consulta->actualizarPasswordUsuario($idUsuario, $nuevaPass1);

                    // Si la ejecucion es True...
                    if ($ejecucion) {
                        $confirmacion = $PASSWORD_CAMBIADA;

                        // Borramos las cookies para que tenga que relogear cuando salga de la web
                        if (isset($_COOKIE["IDUsuario"]) && isset($_COOKIE["Semilla"])) {
                            setcookie('IDUsuario', '', time() - 3600, '/');
                            setcookie('Semilla', '', time() - 3600, '/');
                        }

                        // Mandamos el correo al usuario notificando el cambio de contraseña
                        correoPasswordCambiada($email);
                    } else {
                        $error = $ERROR_GENERAL;
                    }
                } else {
                    $error = $PASSWORDS_IGUALES;
                }
            } else {
                $error = $PASSWORD_NO_CUMPLE_REQUISITOS;
            }
        } else {
            $error = $PASSWORDS_DIFERENTES;
        }
    } else {
        $error = $NO_PASSWORD_ACTUAL;
    }
} else {
    $error = $ERROR_VALIDAR_PETICION;
}
