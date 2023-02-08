<?php

/**
 * A través de este fichero mostramos el formulario para crear un nuevo centro en la base de datos y lo
 * guardamos.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

// Si no hay sesión de usuario o se accede desde la URL nos devuelve al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/registrar-centro.php") {
    header("Location: /");
}

// Si se han introducido todos los datos del formulario...
if (
    isset($_POST['idCentro']) && isset($_POST['nombreCentro']) && isset($_POST['direccionCentro'])
    && isset($_POST['poblacionCentro']) && isset($_POST['tlfnCentro'])
) {
    // Importamos el código de registro de centros
    require $_SERVER['DOCUMENT_ROOT'] . '/includes/registrar-centro.inc.php';
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
            <a href="/cerrarSesion">Cerrar sesión</a>
            <a href="javascript:void(0);" class="icono" onclick="topNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>
    <div class="principal">
        <h1>Nuevo centro</h1>
        <?php

        
        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";
        }

        ?>
        
        <p class="error" style="display:none"></p>
        <form action="#" class="formularioAcomprobar" method="POST">

            <div class="group">
                <input type="text" name="idCentro" required>
                <label>Código</label>
            </div>

            <div class="group">
                <input type="text" name="nombreCentro" required>
                <label>Nombre</label>
            </div>

            <div class="group">
                <input type="text" name="direccionCentro" required>
                <label>Dirección</label>
            </div>

            <div class="group">
                <input type="text" name="poblacionCentro" required>
                <label>Población</label>
            </div>

            <div class="group">
                <input type="text" class="telefonoComprobar" name="tlfnCentro" required>
                <label>Teléfono</label>
            </div>

            <button type="submit">Registrar Centro</button>
        </form>
    </div>
    <?php
    include_once "footer.php";
    ?>
    <script src="/js/funciones.js"></script>
    <script src="/js/comprobaciones.js.php"></script>
</body>

</html>