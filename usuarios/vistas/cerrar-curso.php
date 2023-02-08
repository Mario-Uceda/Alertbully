<?php

/**
 * A través de este fichero mostramos el formulario para crear un nuevo alumno en la base de datos.
 * En caso de que ya existe dicho alumno, se adjudicará automáticamente en la tabla de registros.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

$esTutor = $consulta->comprobarRol($_SESSION['id'], "tutor", 1);
$esDireccion = $consulta->comprobarRol($_SESSION['id'], "direccion", 1);

// Si no hay sesión de usuario o se accede desde la URL nos devuelve al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/cerrar-curso.php" || (!$esDireccion && !$esTutor)) {
    header("Location: /");
}

// Si se manda el formulario...
if (isset($_POST['centroElegido'])) {
    // Importamos el código de registro de usuario
    require $_SERVER['DOCUMENT_ROOT'] . '/includes/cerrar-curso.inc.php';
}

?>

<!DOCTYPE html>
<html lang="es-Es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/styles/style.css?<?php echo date('Y-m-d H:i:s'); ?>">
    <link rel="stylesheet" type="text/css" href="/styles/select2.min.css?<?php echo date('Y-m-d H:i:s'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/select2.min.js"></script>
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
        <h1>Cerrar curso</h1>

        <?php

        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";
        }

        ?>

        <form class="formulario" method="POST">
            <div class="bloqueSelector">
                <p>Centro</p>

                <select id="centroElegido" name="centroElegido">
                    <option value="">Selecciona un centro</option>

                    <?php

                    $esTutor = $consulta->comprobarRol($_SESSION['id'], "tutor", 1);
                    $esDireccion = $consulta->comprobarRol($_SESSION['id'], "direccion", 1);

                    if ($esTutor) {
                        $centros = $consulta->getCentrosDeUsuario($_SESSION['id'], "tutor", 1);
                    }

                    if ($esDireccion) {
                        $centros = $consulta->getCentrosDeUsuario($_SESSION['id'], "direccion", 1);
                    }

                    foreach ($centros as $centro) {
                        $id = $centro['id'];
                        $nombre = $centro['nombre'];
                        echo "<option value=\"$id\">$nombre</option>";
                    }

                    ?>
                </select>
            </div>
            <button type="submit">Cerrar curso</button>
        </form>

        <div class="salida"></div>
    </div>

    <?php
    include_once "footer.php";
    ?>
    <script src="/js/funciones.js"></script>
    <script>
        $("#centroElegido").select2({
            placeholder: "Selecciona un centro",
            allowClear: true
        });
    </script>
</body>

</html>