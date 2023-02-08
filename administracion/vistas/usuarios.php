<?php

/**
 * Con este fichero mostraremos un listado de todos los usuarios que hay en la base de datos en forma de botones.
 * También hacemos que cuando se pulse un botón de unos de los usuarios se muestre la información de ese ususario
 * pasándole el IDdel usuario a /vistas/usuario a través de la UREL.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos conexion y creamos el objeto db
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';

session_start();

//Si no hay sesión de usuario o se accede desde la URL "real", nos devuelve al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/usuarios.php") {
    header("Location: /");
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
            <a class="atras" href="/"><i class="fa fa-chevron-left" style="position: relative; top: 1px;" aria-hidden="true"></i></a>
            <a href="/" class="primero">Inicio</a>
            <a href="/cerrarSesion">Cerrrar sesión</a>
            <a href="javascript:void(0);" class="icono" onclick="topNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>
    <div class="principal">
        <h1>Usuarios</h1>
        <?php

        if (isset($_GET['usuario'])) {
            if ($_GET['usuario'] == 'actualizado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $DATOS_ACTUALIZADOS . '</p>';
            } else if ($_GET['usuario'] == 'eliminado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $USUARIO_DESACTIVADO . '</p>';
            } else if ($_GET['usuario'] == 'registrado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $USUARIO_AÑADIDO . '</p>';
            } else if ($_GET['usuario'] == 'reactivado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $USUARIO_REACTIVADO . '</p>';
            } else if ($_GET['usuario'] == 'passwdreset') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $MANDADA_NUEVA_PASS_USUARIO . '</p>';
            }
        }

        ?>
        <div style="margin-bottom: 0;" class="group">
            <input type="text" id="filtro" onkeyup="filtrar()" required>
            <label>Buscador...</label>
        </div>
        <div class="listaBotones">
            <?php

            $consulta = new Consultas();

            $usuarios = $consulta->getUsuarios();

            foreach ($usuarios as $usuario) {
                $id = $usuario['id'];
                $nombre = $usuario['nombre'];
                $apellidos = $usuario['apellidos'];
                $admin = $usuario['admin'];
                $activo = $usuario['activo'];
                
                if ($admin) {
                    if ($activo) {
                        echo "<button class=\"botonAdaptable\" onclick=\"window.location.href = 'usuario/$id'\"> Admin | $nombre $apellidos </button>";
                        echo "<br>";
                    } else {
                        echo "<button class=\"botonAdaptable\" style=\"background-color: #6b6b6b;\" onclick=\"window.location.href = 'usuario/$id'\"> Admin | $nombre $apellidos </button>";
                        echo "<br>";
                    }
                } else {
                    if ($activo) {
                        echo "<button class=\"botonAdaptable\" onclick=\"window.location.href = 'usuario/$id'\">$nombre $apellidos</button>";
                        echo "<br>";
                    } else {
                        echo "<button class=\"botonAdaptable\" style=\"background-color: #6b6b6b;\" onclick=\"window.location.href = 'usuario/$id'\">$nombre $apellidos</button>";
                        echo "<br>";
                    }
                }
            }

            ?>
        </div>
    </div>

    <?php
    include_once "footer.php";
    ?>

    <script src="/js/funciones.js"></script>
</body>

</html>