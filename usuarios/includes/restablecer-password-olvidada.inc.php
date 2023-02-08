<?php
/**
 * En este fichero tenemos toda la lógica para establecer la contraseña del usuario.
 * Este fichero es importando desde /vistas/restablecer-password-olvidada.php, así que si no encuentras una variable
 * seguramente esté declarada en restablecer-password-olvidada.php y no aquí :)
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con los correos electrónicos
require $_SERVER['DOCUMENT_ROOT'] . '/includes/correos.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

// Si se ha introducido la nueva contraseña...
if (isset($_POST['nuevaPass1']) && isset($_POST['nuevaPass2'])) {
    // Almacenamos los datos en variables
    $pass1 = htmlspecialchars($_POST['nuevaPass1']);
    $pass2 = htmlspecialchars($_POST['nuevaPass2']);

    // Si las contraseñas no están vacias...
    if (!empty($pass1) && !empty($pass2)) {
        // Si las contraseñas son iguales...
        if ($pass1 == $pass2) {
            // Si la nueva contraseña cumple los requisitos...
            if ((strlen($pass1) >= 8) && (preg_match("#[0-9]+#", $pass1)) && (preg_match("#[A-Z]+#", $pass1)) && (preg_match("#[a-z]+#", $pass1)) && (preg_match("#\W+#", $pass1))
            ) {
                // Guardamos en $momentoActual el día de hoy en segundos desde 1970
                $momentoActual = date("U");

                // Comprobamos si existe un registro en pwdreset con ese selector y una fecha de expiración superior
                // a $momentoActual con la función comprobarRegistroPwdreset()
                $registro = $consulta->comprobarRegistroPwdreset($selector, $momentoActual);

                // Si registro no es null significa que el registro existe
                if ($registro != null) {
                    // Cogemos el token y lo pasamos de hexadecimal a binario ($token se encuentra en restablecer-password-olvidada.php)
                    $tokenBin = hex2bin($token);               
             
                    // Comprobamos que $tokenBin y el token almacenado en la BBDD sean iguales, si lo son...
                    if (hash("sha512", $tokenBin) == $registro["token"]) {

                        // Guardamos en $email el email del usuario vinculado a ese token
                        $email = $registro['email'];

                        // Nos conectamos a la base de datos y comprobamos que en usuarios exista ese email
                        $usuario = $consulta->getDatosUsuarioConEmail($email);

                        // Si $usuario no es null significa que hay un usuario con ese email
                        if ($usuario != null) {
                            // Encriptamos en un hash SHA512 la nueva pass del usuario
                            $pass1 = hash("sha512", $pass1);

                            // Actualizamos la contraseña del usuario
                            $consulta->actualizarPasswordUsuario($usuario['id'], $pass1);

                            // Borramos de la tabla pwdreset el registro que contenga el email del usuario
                            $consulta->borrarRegistroPwdresetPorEmail($email);

                            // Mandamos un correo notificando de que se ha cambiado la contraseña
                            correoPasswordCambiada($email);

                            // Volvemos al login con el parametro pass y el valor cambiada
                            header('Location: /?pass=cambiada');
                        } else {
                            $error = $ERROR_GENERAL_PASSWORD_OLVIDADA;
                        }
                    } else {
                        $error = $ERROR_GENERAL_PASSWORD_OLVIDADA;
                    }
                } else {
                    $error = $SOLICITUD_RESTABLECIMIENTO_CADUCADA;
                }
            } else {
                $error = $PASSWORD_NO_CUMPLE_REQUISITOS;
            }
        } else {
            $error = $PASSWORDS_DIFERENTES;
        }
    } else {
        $error = $CAMPOS_VACIOS;
    }
}
