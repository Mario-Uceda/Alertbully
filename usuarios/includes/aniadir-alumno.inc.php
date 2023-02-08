<?php

/**
 * Este fichero es el encargado de guardar un nuevo alumno en la base de datos.
 * En caso de que exista se hará únicamente la asociación al curso y a la clase en la tabla registros.
 * Este fichero será llamado desde el fichero aniadir-alumno.php de la carpeta vistas.
 */

// Cogemos los datos introducidos
$nombreUsuario = htmlspecialchars($_POST['nombreUsuario']);
$apellidosUsuario = htmlspecialchars($_POST['apellidosUsuario']);
$telefonoUsuario = htmlspecialchars($_POST['telefonoUsuario']);
$emailUsuario = htmlspecialchars($_POST['emailUsuario']);
$idcentro = htmlspecialchars($_POST['centroElegido']);

// Comprobamos que los campos no están vacios
if (
    !empty($nombreUsuario) && !empty($apellidosUsuario) && !empty($telefonoUsuario) && !empty($emailUsuario) && !empty($idcentro)
) {
    
    // Comprobamos que el centro elegido existe
    if (($centro = $consulta->getDatosCentro($idcentro)) != null) {
        // Comprobamos que el usuario está en ese centro
        if(($centro = $consulta->usuarioEstaEnCentro($_SESSION['id'],$idcentro, true)) != null){
            // Guardamos los datos del registro del tutor en una variable
            $registroTutor = $consulta->getRegistro($_SESSION['id'], $idcentro, "tutor");

            // Comprobamos en la base de datos si existe ya una cuenta con ese correo.
            // En caso de que exista lo asociamos a la tabla de registros.
            $nuevoUsuario = $consulta->getDatosUsuarioConEmail($emailUsuario);
            if ($nuevoUsuario != null) {
                $ejecucion = $consulta->crearRegistro(
                    $nuevoUsuario['id'],
                    $idcentro,
                    "alumno",
                    $registroTutor['clase'],
                    $registroTutor['curso'],
                    1
                );

                // Si se ha guardado correctamente el registro...
                if ($ejecucion) {
                    header('Location: /aniadir-alumno?registro=creado');
                } else {
                    $error = $REGISTRO_EXISTE;
                }
                // Si el usuario no existe...
            } else {
                // Comprobamos el número de teléfono
                if (is_numeric($telefonoUsuario)) {
                    if ($telefonoUsuario >= 9) {
                        // Generamos la contraseña del usuario
                        $passwordUsuarioPlano = generarPassword();
                        $passwordUsuarioHash = hash('sha512', $passwordUsuarioPlano);

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
                            $nuevoUsuario = $consulta->getDatosUsuarioConEmail($emailUsuario);

                            $ejecucion = $consulta->crearRegistro(
                                $nuevoUsuario['id'],
                                $idcentro,
                                "alumno",
                                $registroTutor['clase'],
                                $registroTutor['curso'],
                                1
                            );
                            
                            // Si se ha guardado correctamente el registro...
                            if ($ejecucion) {
                                correoRegistro($emailUsuario, $passwordUsuarioPlano);
                                header('Location: /aniadir-alumno?registro=creado');
                            } else {
                                $error = $REGISTRO_EXISTE;
                            }
                        } else {
                            $error = $ERROR_GENERAL;
                        }
                    } else {
                        $error = $TLFN_NO_ADMITIDO;
                    }
                } else {
                    $error = $TLFN_DEBER_SER_NUMERO;
                }
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
