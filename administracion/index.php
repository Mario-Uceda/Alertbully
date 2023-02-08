<?php
/**
 * Este fichero contiene las distintas comprobaciones a la hora de acceder a la web.
 * Los pasos que sigue son los siguientes:
 * 1.- Si el usuario tiene las cookies necesarias para iniciar sesión intentaremos iniciar sesión con ellas.
 * 2.- En caso de que el usuario no tenga cookies o no sean correctas, le mostraremos el formulario de inicio de sesión.
 * 3.- Una vez recibido los credenciales del usuario se intentará inciar sesión, en caso de que sean válidos se crearán
 * las cookies correspondientes.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

// Creamos la cookie de sesión de PHP con los flags de HttpOnly y Secure para evitar
// posibles ataques y fugas de seguridad
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

session_start();

// Si el navegador del usuario tiene nuestras cookies...
if (isset($_COOKIE["IDUsuario"]) && isset($_COOKIE["Semilla"])) {
   // Importamos el código de inicio de sesión a través de cookies
   require $_SERVER['DOCUMENT_ROOT'] . '/includes/login-cookies.inc.php';

// Si el usuario ha iniciado sesión, mostramos el menú principal (panel.php)
} else if (isset($_SESSION['usuario'])) {
    // Importamos el código que muestra el menú principal
    require $_SERVER['DOCUMENT_ROOT'] . '/vistas/panel.php';

// Si recibimos un usuario y una contraseña desde login.php
} else if (isset($_POST['email']) && isset($_POST['password'])) {
    // Importamos el código de inicio de sesión a través del formulario
    require $_SERVER['DOCUMENT_ROOT'] . '/includes/login-formulario.inc.php';

// Si no es ninguna de las anteriores...
} else {
    // Importamos el código que muestra el formulario de inicio de sesión
    require "vistas/login.php";
    // Una vez mandado el formulario, se realizarán de nuevo las comprobaciones
}
