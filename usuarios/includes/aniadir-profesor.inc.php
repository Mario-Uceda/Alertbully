<?php

/**
 * Este fichero es el encargado de guardar un nuevo alumno en la base de datos.
 * En caso de que exista se hará únicamente la asociación al curso y a la clase en la tabla registros.
 * Este fichero será llamado desde el fichero aniadir-profesor.php de la carpeta vistas.
 */

// Cogemos los datos introducidos
$nombreTutor = htmlspecialchars($_POST['nombreTutor']);
$apellidosTutor = htmlspecialchars($_POST['apellidosTutor']);
$telefonoTutor = htmlspecialchars($_POST['telefonoTutor']);
$emailTutor = htmlspecialchars($_POST['emailTutor']);
$idcentro = htmlspecialchars($_POST['centroElegido']);
$claseTutor = htmlspecialchars($_POST['claseTutor']);

// Comprobamos que los campos no están vacios
if (
    !empty($nombreTutor) && !empty($apellidosTutor) && !empty($telefonoTutor) && !empty($emailTutor) && !empty($idcentro) && !empty($claseTutor)
) {
    // Comprobamos que el centro elegido existe y que el usuario está en el centro
    if (($centro = $consulta->getDatosCentro($idcentro)) != null) {
        if (($registro = $consulta->usuarioEstaEnCentro($_SESSION['id'], $idcentro, true)) != null) {
            // Guardamos los datos del registro del tutor en una variable
            $registroDireccion = $consulta->getRegistro($_SESSION['id'], $idcentro, "direccion");

            // Comprobamos en la base de datos si existe ya una cuenta con ese correo.
            // En caso de que exista lo asociamos a la tabla de registros.
            $nuevoTutor = $consulta->getDatosUsuarioConEmail($emailTutor);
            if ($nuevoTutor != null) {
                $ejecucion = $consulta->crearRegistro(
                    $nuevoTutor['id'],
                    $idcentro,
                    "tutor",
                    $claseTutor,
                    cursoActual(),
                    1
                );

                // Si se ha guardado correctamente el registro...
                if ($ejecucion) {
                    header('Location: /aniadir-profesor?registro=creado');
                } else {
                    $error = $REGISTRO_EXISTE;
                }
                // Si el usuario no existe...
            } else {
                // Comprobamos el número de teléfono
                if (is_numeric($telefonoTutor)) {
                    if ($telefonoTutor >= 9) {
                        // Generamos la contraseña del usuario
                        $passwordTutorPlano = generarPassword();
                        $passwordTutorHash = hash('sha512', $passwordTutorPlano);

                        $ejecucion = $consulta->guardarUsuario(
                            $nombreTutor,
                            $apellidosTutor,
                            $telefonoTutor,
                            $emailTutor,
                            $passwordTutorHash,
                            1,
                            0
                        );

                        // Si se ha guardado correctamente el usuario...
                        if ($ejecucion) {
                            $nuevoTutor = $consulta->getDatosUsuarioConEmail($emailTutor);

                            $ejecucion = $consulta->crearRegistro(
                                $nuevoTutor['id'],
                                $idcentro,
                                "tutor",
                                $claseTutor,
                                cursoActual(),
                                1
                            );

                            // Si se ha guardado correctamente el registro...
                            if ($ejecucion) {
                                correoRegistro($emailTutor, $passwordTutorPlano);
                                header('Location: /aniadir-profesor?registro=creado');
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
        } else {
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

function cursoActual()
{
    $now = new DateTime();
    $year = $now->format('Y');
    $curso = ((($now->format('m') < 8) ? $year - 1 : $year) . "/" . (($now->format('m') < 8) ? $year : $year));
    return $curso;
}
