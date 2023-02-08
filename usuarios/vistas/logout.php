<?php

/**
 * Llamaremos a este fichero para cerrar la sesión del usario
 * Pese a que no muestra ninguna interfaz, lo tenemos en la carpeta vistas ya que si la tuvieramos en includes
 * tendríamos que hacer una excepción para el archivo
 */

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

if ($_SERVER['REQUEST_URI'] == "/vistas/logout.php") {
    header("Location: /");
} else {

    session_start();

    if ($_SESSION['usuario']) {

        // Eliminamos la sesión
        session_unset();
        session_destroy();

        // Borramos las cookies que mantenían la sesión abierta
        if (isset($_COOKIE["IDUsuario"]) && isset($_COOKIE["Semilla"])) {
            $consulta->borrarCookie($_COOKIE["IDUsuario"], $_COOKIE["Semilla"]);
            setcookie('IDUsuario', '', time() - 3600, '/');
            setcookie('Semilla', '', time() - 3600, '/');
        }

        header("Location: /?sesion=logout");
    } else {
        header("Location: /");
    }
}
