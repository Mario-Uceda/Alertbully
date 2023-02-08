<?php
/**
 * A través de este fichero mostramos el formulario pidiendo la nueva contraseña del usuario a través del link
 * de restablecimiento.
 * Para hacer el código más entendible (o más organizado), en un momento de la ejecución importamos el fichero
 * "restablecer-password-olvidada.inc.php" que es el que tiene toda la lógica que trabajaba con la base de datos.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Si en la sesión hay usuario volvemos al index o se intenta acceder desde la url
if (isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/restablecer-password-olvidada.php") {
    header("Location: /");
}

// Si en la url están los parámetros "selector" y "token"...
if(isset($_GET['selector']) && isset($_GET['token'])) {

    // Los guardamos en variables
    $selector = $_GET['selector'];
    $token = $_GET['token'];

    // Si no están vacios...
    if (!empty($selector) && !empty($token)) {
        // Si los tokens son hexadecimales...
        if (ctype_xdigit($selector) && ctype_xdigit($token)) {
            // Usamos el código que se encuentra en restablecerPass.inc.php
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/restablecer-password-olvidada.inc.php';
        } else {
            $error = $ERROR_VALIDAR_PETICION;
        }
    } else {
        $error = $ERROR_VALIDAR_PETICION;
    }
} else {
    header("Location: /");
}

?>

<!DOCTYPE html>
<html lang="es-Es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/styles/style.css?<?php echo date('Y-m-d H:i:s'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>AlertBully</title>
</head>

<body>
    <header>
        <a class="logo" href="/">AlertBully</a>
    </header>
    <div class="principal">
        <h1>Restablecer contraseña</h1>
        <?php

        // Si ha ocurrido un error lo mostramos
        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";
            // Si todo ha ido bien lo avisamos
        } else if (isset($confirmacion)) {
            echo "<p class=\"valido\">$confirmacion</p>";
        }

        ?>
        <p class="error" style="display:none"></p>
        <form action="#" class="formularioAcomprobar" method="POST">
            <p>
                <div class="group">
                    <input type="password" class="passwordComprobar" name="nuevaPass1" id="nuevaPass1" required>
                    <i class="fa fa-eye-slash" aria-hidden="true" id="ver1" onclick="verPassword('nuevaPass1', 'ver1')"></i>
                    <label>Nueva contraseña</label>
                </div>
             
                <div class="group">
                    <input type="password" class="rePasswordComprobar" id="nuevaPass2" name="nuevaPass2" required>
                    <i class="fa fa-eye-slash" aria-hidden="true" id="ver2" onclick="verPassword('nuevaPass2', 'ver2')"></i>
                    <label>Repite la nueva contraseña</label>
                </div>
            </p>
            <button style="width: fit-content; margin-top: 3%;" type="submit">Restablecer contraseña</button>
        </form>
    </div>

    <?php
    include_once "footer.php";
    ?>

    <script src="/js/funciones.js"></script>
    <script src="/js/comprobaciones.js.php"></script>
</body>

</html>