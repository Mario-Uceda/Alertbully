<?php

/**
 * Con este fichero mostraremos los datos de un usuario a partir de su ID pasado por URL.
 * Además desde aquí podremos:
 * 1.- Eliminar el usuario (/includes/borrar-usuario.inc.php)
 * 2.- Modificaar y guardar los datos del usuario en la BBDD
 * Mediante un script de JS comprobamos si se modifican los datos del centro para poder desbloquear el botón de guardar
 * Mediante otro script de JS deshabilitamos los campos que no correspondan al tipo de usuario seleccionado
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con los correos electrónicos
require $_SERVER['DOCUMENT_ROOT'] . '/includes/correos.inc.php';

// Importamos conexion y creamos un objeto Consulta
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

// Si no hay sesión de usuario o se accede desde la URL "real"
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/usuario.php") {
    header("Location: /");
}

// Si hay un id en la url...
if (isset($_GET['id'])) {
    // Guardamos el id en $id
    $id = $_GET['id'];

    // Mediante el método getDatosUsuarioConID() conseguiremos, pasándole el id del usuario, los datos
    // del usuario
    $usuario = $consulta->getDatosUsuarioConID($id);

    //Si la variable $usuario está vacío volvemos a usuarios
    if ($usuario == null) {
        header("Location: /usuarios");
    } else {
        $idUsuario = $usuario['id'];
        $nombreUsuario = $usuario['nombre'];
        $apellidosUsuario = $usuario['apellidos'];
        $telefonoUsuario = $usuario['telefono'];
        $emailUsuario = $usuario['email'];
        $admin = $usuario['admin'];
        $activo = $usuario['activo'];
    }
}

// Si se ha pulsado el botón de borrar...
if (isset($_POST['botonBorrar'])) {
    // Guardamos el id en $id
    $id = $_GET['id'];

    // Ejecutamos una consulta para desactivar el usuario en la base de datos
    $ejecucion = $consulta->desactivarUsuario($id);

    // Si la consulta no falla mostramos un mensaje de confirmación
    if ($ejecucion) {
        header('Location: /usuarios?usuario=eliminado');
    } else {
        $error = $ERROR_GENERAL;
    }
}

// Si se ha pulsado el botón de guardar
if (isset($_POST['botonGuardar'])) {
    $nombreUsuario = htmlspecialchars($_POST['nombreUsuario']);
    $apellidosUsuario = htmlspecialchars($_POST['apellidosUsuario']);
    $telefonoUsuario = htmlspecialchars($_POST['telefonoUsuario']);
    $emailUsuario = htmlspecialchars($_POST['emailUsuario']);
    $rolUsuario = isset($_POST['rolUsuario']);

    // Comprobamos que el número de telefono solo tiene números
    if (!is_numeric($telefonoUsuario)) {
        $error = $TLFN_DEBER_SER_NUMERO;
        // Comprobamos que el teéfono tiene, como mínimo 9 dígitos
    } else if ($telefonoUsuario < 9) {
        $error = $TLFN_NO_ADMITIDO;
    } else {
        $id = $_GET['id'];
        $usuario = $consulta->getDatosUsuarioConEmail($emailUsuario);
        if ($usuario['id'] == $id || $usuario == null) {
            $ejecucion = $consulta->actualizarUsuario($nombreUsuario, $apellidosUsuario, $telefonoUsuario, $emailUsuario, $rolUsuario, $id);
            // Si la consulta ha salido bien...
            if ($ejecucion) {
                header('Location: /usuarios?usuario=actualizado');
            } else {
                $error = $ERROR_GENERAL;
            }
        } else {
            $error = $USUARIO_EXISTE;
        }
    }
}

// Si se ha pulsado el botón de reactivar cuenta
if (isset($_POST['botonReactivar'])) {
    $id = $_GET['id'];

    $ejecucion = $consulta->reactivarUsuario($id);

    // Si la consulta ha salido bien...
    if ($ejecucion) {
        header('Location: /usuarios?usuario=reactivado');
    } else {
        $error = $ERROR_GENERAL;
    }
}

// Si se ha pulsado el botón de restablecimiento de contraseña...

if (isset($_POST['botonRestablecerPass'])) {
    $id = $_GET['id'];

    $nuevaPassword = generarPassword();
    $passwordHash = hash('sha512', $nuevaPassword);

    $ejecucion = $consulta->actualizarPasswordUsuario($id,  $passwordHash);

    // Si la consulta ha salido bien...
    if ($ejecucion) {
        correoNuevaPass($emailUsuario, $nuevaPassword);
        header('Location: /usuarios?usuario=passwdreset');
    } else {
        $error = $ERROR_RESTABLECER_PASS;
    }
}

/**
 * Función para generar una contraseña aleatoria
 */
function generarPassword()
{
    $tamanioPass = 8;
    $diccionario = '|@#~$%()=^*+[]{}-abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $diccionarioLong = strlen($diccionario) - 1;
    for ($i = 0; $i < $tamanioPass; $i++) {
        $n = rand(0, $diccionarioLong);
        $pass[] = $diccionario[$n];
    }
    return implode($pass); // Convertimos el array a un string
}

?>

<!DOCTYPE html>
<html lang="es-ES">

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
            <a class="atras" href="/usuarios"><i class="fa fa-chevron-left" style="position: relative; top: 1px;" aria-hidden="true"></i></a>
            <a href="/" class="primero">Inicio</a>
            <a href="/cerrarSesion">Cerrar Sesión</a>
            <a href="javascript:void(0);" class="icono" onclick="topNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>
    <div class="principal">
        <?php
        echo "<h1>$nombreUsuario $apellidosUsuario</h1>";

        echo "<p class=\"error\" style=\"display:none\"></p>";

        //Si hay un error de login lo  mostramos
        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";
            // Si hay algún error con algún campo...
        } else if (isset($noNumero)) {
            echo "<p class=\"error\">$noNumero</p>";
        }

        if (isset($nombreCentro)) {
            echo "<p>$nombreCentro</p>";
        }

        echo "<form class=\"formularioAcomprobar\" method=\"POST\">";

        // Si tiene un nombre de usuario lo mostramos
        if (!empty($nombreUsuario)) {
            echo "
            <div class=\"group\">
                <input onkeyup=\"inputEscrito()\" id=\"nombreUsuario\" name=\"nombreUsuario\" value=\"$nombreUsuario\" required>
                <label>Nombre del usuario</label>
            </div>
            ";
        }

        echo "
        <div class=\"group\">
            <input onkeyup=\"inputEscrito()\" id=\"apellidosUsuario\" name=\"apellidosUsuario\" value=\"$apellidosUsuario\" required>
            <label>Apellidos del usuario</label>
        </div>
        ";

        // Si tiene un email de usuario lo mostramos
        if (!empty($emailUsuario)) {
            echo "
            <div class=\"group\">
                <input onkeyup=\"inputEscrito()\" id=\"emailUsuario\" name=\"emailUsuario\" value=\"$emailUsuario\" required>
                <label>Email del usuario</label>
            </div>
            ";
        }

        echo "
        <div class=\"group\">
            <input onkeyup=\"inputEscrito()\" class=\"telefonoComprobar\" id=\"telefonoUsuario\" name=\"telefonoUsuario\" value=\"$telefonoUsuario\" required>
            <label>Teléfono del usuario</label>
        </div>
        ";

        // Si es administrador mostraremos un checkbox marcado, sino, no
        if ($admin) {
            echo "
            <p>Administrador <input type=\"checkbox\" onclick=\"inputEscrito()\" id=\"rolUsuario\" name=\"rolUsuario\" checked></p>
            ";
        } else {
            echo "
            <p>Administrador <input type=\"checkbox\" onclick=\"inputEscrito()\" id=\"rolUsuario\" name=\"rolUsuario\"></p>
            ";
        }

        echo "<button name=\"botonGuardar\" type=\"submit\" id=\"botonGuardar\" class=\"botonRojo\" disabled><i class=\"fa fa-floppy-o\" aria-hidden=\"true\"></i></button>";

        if ($activo) {
            echo "<button name=\"botonBorrar\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></button>";
        } else {
            echo "<button name=\"botonReactivar\">Activar cuenta</button>";
        }

        echo "<br>";
        echo "<button name=\"botonRestablecerPass\" type=\"submit\" id=\"botonRestablecerPass\">Restablecer contraseña</button>";

        echo "</form>";
        ?>
    </div>

    <?php
    include_once "footer.php";
    ?>

    <script src="/js/funciones.js"></script>
    <script>
        var botonGuardar = document.getElementById("botonGuardar");

        var nombreUsuarioDef = document.getElementById("nombreUsuario").value;
        var apellidosUsuarioDef = document.getElementById("apellidosUsuario").value;
        var telefonoUsuarioDef = document.getElementById("telefonoUsuario").value;
        var emailUsuarioDef = document.getElementById("emailUsuario").value;
        var rolUsuarioDef = document.getElementById("rolUsuario").checked;

        function inputEscrito() {
            var nombreUsuario = document.getElementById("nombreUsuario").value;
            var apellidosUsuario = document.getElementById("apellidosUsuario").value;
            var telefonoUsuario = document.getElementById("telefonoUsuario").value;
            var emailUsuario = document.getElementById("emailUsuario").value;
            var rolUsuario = document.getElementById("rolUsuario").checked;

            if (nombreUsuarioDef != nombreUsuario || apellidosUsuarioDef != apellidosUsuario ||
                telefonoUsuarioDef != telefonoUsuario || emailUsuarioDef != emailUsuario || rolUsuarioDef != rolUsuario) {
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