<?php

/**
 * Con este fichero mostraremos los datos de un centro a partir de su ID pasado por URL.
 * Además desde aquí podremos:
 * 1.- Eliminar el centro (/includes/borrar-centro.inc.php)
 * 2.- Modificar y guardar los datos del centro en la BBDD ();
 * Mediante un script de JS comprobamos si se modifican los datos del centro para poder desbloquear el botón de guardar
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

// Si no hay sesión de usuario o se accede desde la URL real nos devuelve al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/centro.php") {
    header("Location: /");
}

// Si hay un id en la url...
if (isset($_GET['id'])) {
    // Guardamos el id en $id
    $id = $_GET['id'];

    // Mediante el método getDatosCentro() conseguiremos, pasándole el id del centro, los datos
    // del centro
    $centro = $consulta->getDatosCentro($id);

    // Si la variable $idCentro está vacio volvemos a centros
    if ($centro == null) {
        header("Location: /centros");
    } else {
        $idCentro = $centro['id'];
        $nombreCentro = $centro['nombre'];
        $direccionCentro = $centro['direccion'];
        $poblacionCentro = $centro['poblacion'];
        $tlfnCentro = $centro['telefono'];
        $activo = $centro['activo'];
    }
}

// Si se ha pulsado el botón de borrar, borraramos el centro de la base de datos
if (isset($_POST['botonBorrar'])) {
    // Guardamos el id en $id
    $id = $_GET['id'];

    // Mediante la función desactivarCentro() eliminamos el centro de la base de datos pasándole su id
    $ejecucion = $consulta->desactivarCentro($id);

    // Si la consulta no falla, avisamos de que se ha eliminado el centro de la BBDD
    if ($ejecucion) {
        header('Location: /centros?centro=eliminado');
        // Si la consulta da error, le notificamos al usuario de que ha ocurrido un error
    } else {
        $error = $ERROR_DESACTIVAR_CENTRO;
    }
}

// Si se ha pulsado el botón de reactivar centro...
if (isset($_POST['botonReactivar'])) {
    $id = $_GET['id'];

    $ejecucion = $consulta->reactivarCentro($id);

    // Si la consulta ha salido bien...
    if ($ejecucion) {
        header('Location: /centros?centro=reactivado');
    } else {
        $error = $ERROR_GENERAL;
    }
}

// Si se ha pulsado el botón de guardar actualizamos la información del centro
if (isset($_POST['botonGuardar'])) {

    // Guardamos los datos pasados por el formulario en variables
    $idCentro = htmlspecialchars($_POST['idCentro']);
    $nombreCentro = htmlspecialchars($_POST['nombreCentro']);
    $direccionCentro = htmlspecialchars($_POST['direccionCentro']);
    $poblacionCentro = htmlspecialchars($_POST['poblacionCentro']);
    $tlfnCentro = htmlspecialchars($_POST['tlfnCentro']);

    if (
        !empty($idCentro) && !empty($nombreCentro) && !empty($direccionCentro) &&
        !empty($poblacionCentro) && !empty($tlfnCentro)
    ) {
        // Si el $idCentro no es un número mostramos un error
        if (!is_numeric($idCentro)) {
            $error = $CODIGO_DEBER_SER_NUMERO;
            // Si el $tlfnCentro no es un número mostramos un error
        } else if (!is_numeric($tlfnCentro)) {
            $error = $TLFN_DEBER_SER_NUMERO;
            // Si el número tiene menos de 9 dígitos mostramos un error
        } else if ($tlfnCentro < 9) {
            $error = $TLFN_NO_ADMITIDO;
            // Si no hay problemas....
        } else {
            // Guardamos el id en $id
            $idActual = $_GET['id'];

            // Actualizamos los datos del centro mediante la consulta actualizarCentro() y guardamos el estado de ejecucion en $ejecucion
            $ejecucion = $consulta->actualizarCentro($idCentro, $nombreCentro, $direccionCentro, $poblacionCentro, $tlfnCentro, $idActual);

            // Si la consulta ha salido bien...
            if ($ejecucion) {
                header('Location: /centros?centro=actualizado');
            } else {
                $error = $ERROR_GENERAL;
            }
        }
    } else {
        $error = $CAMPOS_VACIOS;
    }
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
            <a class="atras" href="/centros"><i class="fa fa-chevron-left" style="position: relative; top: 1px;" aria-hidden="true"></i></a>
            <a href="/" class="primero">Inicio</a>
            <a href="/cerrarSesion">Cerrar sesión</a>
            <a href="javascript:void(0);" class="icono" onclick="topNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>
    <div class="principal">
        <h1><?php echo $nombreCentro ?></h1>

        <?php


        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";
            echo "<br>";
            // Si hay algún error con algún campo...
        } else if (isset($noNumero)) {
            echo "<p class=\"error\">$noNumero</p>";
            echo "<br>";
            // Si no, mostramos el título...
        }
        ?>
        <p class="error" style="display:none"></p>

        <form class="formularioAcomprobar" method="POST">
            <div class="group">
                <input onkeyup="inputEscrito()" id="idCentro" name="idCentro" value="<?php echo $idCentro ?>" required>
                <label>ID</label>
            </div>
            <div class="group">
                <input onkeyup="inputEscrito()" id="nombreCentro" name="nombreCentro" value="<?php echo $nombreCentro ?>" required>
                <label>Nombre</label>
            </div>
            <div class="group">
                <input onkeyup="inputEscrito()" id="direccionCentro" name="direccionCentro" value="<?php echo  $direccionCentro ?>" required>
                <label>Dirección</label>
            </div>
            <div class="group">
                <input onkeyup="inputEscrito()" id="poblacionCentro" name="poblacionCentro" value="<?php echo $poblacionCentro ?>" required>
                <label>Población</label>
            </div>
            <div class="group">
                <input onkeyup="inputEscrito()" class="telefonoComprobar" id="tlfnCentro" name="tlfnCentro" value="<?php echo $tlfnCentro ?>" required>
                <label>Teléfono</label>
            </div>
            <button name="botonGuardar" type="submit" id="botonGuardar" class="botonRojo" disabled><i class="fa fa-floppy-o" aria-hidden="true"></i></button>

            <?php

            if ($activo) {
                echo "<button name=\"botonBorrar\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></button>";
            } else {
                echo "<button name=\"botonReactivar\">Activar centro</button>";
            }

            ?>
        </form>
    </div>

    <?php
    include_once "footer.php";
    ?>

    <script src="/js/funciones.js"></script>
    <script>
        var botonGuardar = document.getElementById("botonGuardar");

        var idCentroDef = document.getElementById("idCentro").value;
        var nombreCentroDef = document.getElementById("nombreCentro").value;
        var direccionCentroDef = document.getElementById("direccionCentro").value;
        var poblacionCentroDef = document.getElementById("poblacionCentro").value;
        var telefonoComprobarDef = document.getElementById("tlfnCentro").value;

        function inputEscrito() {
            var idCentro = document.getElementById("idCentro").value;
            var nombreCentro = document.getElementById("nombreCentro").value;
            var direccionCentro = document.getElementById("direccionCentro").value;
            var poblacionCentro = document.getElementById("poblacionCentro").value;
            var telefonoComprobar = document.getElementById("tlfnCentro").value;

            if (idCentroDef != idCentro || nombreCentroDef != nombreCentro ||
                direccionCentroDef != direccionCentro || poblacionCentroDef != poblacionCentro || telefonoComprobarDef != telefonoComprobar) {
                botonGuardar.disabled = false;
                botonGuardar.className = "botonGuardar";
            } else {
                botonGuardar.disabled = true;
                botonGuardar.className = "botonRojo";
            }
        }
    </script>
    <script src="/js/comprobaciones.js.php"></script>
</body>

</html>