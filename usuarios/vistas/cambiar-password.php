<?php

/**
 * En este fichero se muestra el formulario para cambiar la contraseña del usuario
 * y se hace toda la lógica de cambio de contraseña
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con los correos electrónicos
require $_SERVER['DOCUMENT_ROOT'] . '/includes/correos.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

// Si no existe una sesión de usuario o se accede desde la url le devolvemos al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/cambiar-password.php") {
    header('Location: /');
}

// Si se ha mandado los campos de passActual, nuevaPass1 y nuevaPass2 desde el form...
if (isset($_POST['passActual']) && isset($_POST['nuevaPass1']) && isset($_POST['nuevaPass2'])) {
    // Importamos el código de cambio de contraseña
    require $_SERVER['DOCUMENT_ROOT'] . '/includes/cambiar-password.inc.php';
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
        <?php
        $usuario = $_SESSION['usuario'];
        echo "<a class=\"logo\" href=\"/\">Bienvenido $usuario</a>";
        ?>
        <div class="header-dere" id="topNav">
            <a class="atras" href="/"><i class="fa fa-chevron-left" style="position: relative; top: 1px;" aria-hidden="true"></i></a>
            <a href="/" class="primero">Inicio</a>
            <a class="active primero" href="cambiar-password">Cambiar contraseña</a>
            <a href="/cerrarSesion">Cerrar sesión</a>
            <a href="javascript:void(0);" class="icono" onclick="topNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>
    <div class="principal">
        <h1>Cambio de contraseña</h1>

        <?php

        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";
        // Si se ha completado el proceso con exito lo notificamos
        } else if (isset($confirmacion)) {
            echo "<p class=\"valido\">$confirmacion</p>";
        }

        ?>
        <p class="error" style="display:none"></p>

        <form action="#" class="formularioAcomprobar" method="POST">
            <p>
                <div class="group">
                    <input type="password" id="passActual" name="passActual" required>
                    <i class="fa fa-eye-slash" aria-hidden="true" id="ver1" onclick="verPassword('passActual', 'ver1')"></i>
                    <label>Contraseña actual</label>
                </div>

                <div class="group">
                    <input type="password" class="passwordComprobar" id="nuevaPass1" name="nuevaPass1" required>
                    <i class="fa fa-eye-slash" aria-hidden="true" id="ver2" onclick="verPassword('nuevaPass1', 'ver2')"></i>
                    <label>Nueva contraseña</label>
                </div>

                <div class="group">
                    <input type="password" class="rePasswordComprobar" id="nuevaPass2" name="nuevaPass2" required>
                    <i class="fa fa-eye-slash" aria-hidden="true" id="ver3" onclick="verPassword('nuevaPass2', 'ver3')"></i>
                    <label>Repite la nueva contraseña</label>
                </div>
            </p>
            <p>La nueva contraseña debe contener como mínimo:<br>8 caracteres, una mayúscula, una minúscula, un número y un símbolo.</p>
            <button type="submit">Cambiar contraseña</button>
        </form>
    </div>
    <?php
    include_once "footer.php";
    ?>
    <script src="/js/funciones.js"></script>
    <script src="/js/comprobaciones.js.php"></script>
</body>

</html>