<?php

/**
 * En este fichero tenemos toda la lógica para iniciar sesión a través del formulario.
 * Este fichero será llamado desde /index.php
 */

// Cogemos los datos del usuario y encriptamos la clave
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$password = htmlspecialchars(hash('sha512', $password));

// Llamamos a la función getDatosUsuarioConEmailPassword() pasándole el email y la password
// para obtener los datos del usuario con esos atributos
$usuario = $consulta->getDatosUsuarioConEmailPassword($email, $password);

// Si hay un usuario...
if ($usuario != null) {
    // Guardamos en la sesión el ID y el nombre de Usuario
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['usuario'] = $usuario['nombre'];

    // Si tenemos el checkbox de mantener la sesión abierta...
    if (isset($_POST['recordar'])) {
        // Generamos un númeror random a partir de una semilla
        mt_srand(time());
        $numero_aleatorio = mt_rand(1000000, 999999999);

        // Guardamos la cookie del usuario mediante la función crearCookie()
        $consulta->crearCookie($numero_aleatorio, $usuario['id']);

        // Creamos las cookies
        // Nombre Cookie / Dato / Tiempo de vida / Ruta / Dominio / Secure flag / HttpOnly flag
        setcookie("IDUsuario", $usuario['id'], time() + (60 * 60 * 24 * 365), "/", null, true, true);
        setcookie("Semilla", $numero_aleatorio, time() + (60 * 60 * 24 * 365), "/", null, true, true);
    }

    // Guardamos en $ip la dirección IP desde donde se ha hecho login
    // Intentamos obtener la IP real del visitante mediante esta serie de condiciones
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // La IP es de una conexión compartida
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // La IP pasa por un proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // Dirección IP desde la que se visita la web
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // Guardamos en $fecha la fecha actual con hora minuto y segundos
    $fecha = date("d") . "/" . date("m") . "/" . date("y") . " " . date("H") . ":" . date("i") . ":" . date("s");

    // Guardamos la fecha del incio de sesión y la IP desde donde se ha hecho en la base de datos
    $consulta->guardarIpFecha($fecha, $ip, $usuario['id']);

    // Reenviamos a la raíz
    header('Location: /');

    // Si no hay resultados, no hay un usuario registrado
} else {
    // Generamos un error y mostramos el formulario de login
    $error = $ERROR_LOGIN;
    include_once "vistas/login.php";
}
