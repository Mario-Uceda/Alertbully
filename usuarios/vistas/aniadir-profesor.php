<?php

/**
 * A través de este fichero mostramos el formulario para crear un nuevo alumno en la base de datos.
 * En caso de que ya existe dicho alumno, se adjudicará automáticamente en la tabla de registros.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con los correos electrónicos
require $_SERVER['DOCUMENT_ROOT'] . '/includes/correos.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

$esDireccion = $consulta->comprobarRol($_SESSION['id'], "direccion", 1);

// Si no hay sesión de usuario o se accede desde la URL nos devuelve al index o no es un tutor
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/aniadir-profesor.php" || $esDireccion == null) {
    header("Location: /");
}

// Si se han mandado todos los datos del formulario...
if (
    isset($_POST['nombreTutor']) && isset($_POST['apellidosTutor']) && isset($_POST['telefonoTutor']) &&
    isset($_POST['emailTutor']) && isset($_POST['centroElegido'] ) && isset($_POST['claseTutor'])
) {
    // Importamos el código de registro de usuario
    require $_SERVER['DOCUMENT_ROOT'] . '/includes/aniadir-profesor.inc.php';
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
        <h1>Nuevo profesor</h1>

        <?php
        // Si hay un error de login lo mostramos
        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";
        }

        if (isset($_GET['registro'])) {
            if ($_GET['registro'] == 'creado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $REGISTRO_CREADO . '</p>';
            }
        }

        ?>

        <p class="error" style="display:none"></p>


        <form action="#" class="formularioAcomprobar" method="POST">
            <div class="group">
                <input type="text" name="nombreTutor" required>
                <label>Nombre</label>
            </div>

            <div class="group">
                <input type="text" name="apellidosTutor" required>
                <label>Apellido</label>
            </div>

            <div class="group">
                <input type="text" class="telefonoComprobar" name="telefonoTutor" required>
                <label>Telefono</label>
            </div>

            <div class="group">
                <input type="text" class="emailComprobar" name="emailTutor" required>
                <label>Email</label>
            </div>

            <div class="bloqueSelector">
                <p>Centro</p>
                <select id="centroElegido" name="centroElegido">
                    <option value="">Selecciona un centro</option>

                    <?php
                    $centros = $consulta->getCentrosDeUsuario($_SESSION['id'], "direccion", 1);

                    foreach ($centros as $centro) {
                        $id = $centro['id'];
                        $nombre = $centro['nombre'];
                        echo "<option value=\"$id\">$nombre</option>";
                    }
                    ?>

                </select>
                
            </div>
            
            <div class="group">
                <input type="text" name="claseTutor" required>
                <label>Clase del Profesor</label>
            </div>

            <button type="submit">Registrar profesor</button>
        </form>
    </div>

    <?php
    include_once "footer.php";
    ?>
    <script src="/js/funciones.js"></script>
    <script src="/js/comprobaciones.js.php"></script>
    <script>
        $("#centroElegido").select2({
            placeholder: "Selecciona un centro",
            allowClear: true
        });
    </script>
</body>

</html>