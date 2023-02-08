<?php

/**
 * A través de este fichero mostraremos el formulario de logging
 * A diferencia de otros fichero, no hacemos el session start ya que la única forma de entrar aquí es desde index
 * y en index ya estamos iniciando la sesión.
 */

// Si en la sesión hay usuario volvemos al index o se intenta acceder desde la url
if (isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/login.php") {
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
        <h1>Inicio de sesión</h1>
        <?php

        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";

            // Si hay un parámetro llamado "sesion" con el contenido "logout"...
        } else if (isset($_GET['sesion']) && ($_GET['sesion'] == 'logout')) {
            echo "<p class=\"valido\">· Has cerrado sesión correctamente ·</p>";

            // Si hay un parámetro llamado "pass" con el contenido "cambiada"...
        } else if (isset($_GET['pass']) && ($_GET['pass'] == 'cambiada')) {
            echo "<p class=\"valido\">· Contraseña cambiada correctamente ·</p>";

            // Si se ha pedido un restablecimiento de contraseña lo mostramos aquí
        } else if (
            isset($_GET['email']) && ($_GET['email'] == 'enviado') &&
            isset($_GET['dir']) && !empty($_GET['dir'])
        ) {
            echo "<p class=\"valido\">· Se ha enviado un correo a " . $_GET['dir'] . " con las instrucciones para restablecer la contraseña ·</p>";
        }

        ?>
        <form action="" method="POST">
            <div class="group">
                <input type="text" id="email" name="email" required>
                <label for="email">Introduce el email</label>
            </div>
            <div class="group">
                <input type="password" name="password" id="password" required>
                <i class="fa fa-eye-slash" aria-hidden="true" id="ver1" onclick="verPassword('password', 'ver1')"></i>
                <label for="password">Introduce la contraseña</label>
            </div>
            <p><label style="position: relative; color: black; top: 0;" for="recordar">Mantener la sesión abierta </label><input id="recordar" type="checkbox" name="recordar"></p>
            <button type="submit">Iniciar sesión</button>
        </form>
        <p>¿Has olvidado la contraseña? Haz click <a href="recuperacion">aquí</a> para recuperarla.</p>
    </div>

    <?php
    include_once "footer.php";
    ?>

    <script src="/js/funciones.js"></script>
</body>

</html>