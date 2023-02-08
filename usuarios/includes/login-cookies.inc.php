<?php
/**
 * Este fichero tiene la lógica de incio de sesión a través de cookies.
 * Este fichero será llamado desde /index.php
 */

// Si las cookies no están vacias...
if (!empty($_COOKIE["IDUsuario"]) && !empty($_COOKIE["Semilla"])) {
    // Comprobar si existe un usario con esa ID y esa cookie en la BBDD
    $usuario = $consulta->comprobarCookieUsuario($_COOKIE['IDUsuario'], $_COOKIE['Semilla']);

    // Si registro no es null significa que hay un registro con esos datos...
    if ($usuario != null && $usuario['admin'] == true) {
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['usuario'] = $usuario['nombre'];
        include_once "vistas/panel.php";

    // Si los datos de las cookies no coinciden con los registros
    // borramos las cookies y mostramos de nuevo el formulario de login
    } else {
        setcookie('IDUsuario', '', time() - 3600, '/');
        setcookie('Semilla', '', time() - 3600, '/');
        include_once "vistas/login.php";
    }
}
