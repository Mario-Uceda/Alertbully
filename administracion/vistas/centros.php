<?php
/**
 * Con este fichero mostraremos un listado de todos los centros que hay en la base de datos en forma de botones.
 * También hacemos que cuando se pulse un botón de uno de los centros se muestre la información de ese centro
 * pasándole el ID del centro a /vistas/centro a través de la URL.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos conexion y creamos el objeto db
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';

session_start();

// Si no hay sesión de usuario o se accede desde la URL nos devuelve al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/centros.php") {
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
        <h1>Centros</h1>
        <?php

        if (isset($_GET['centro'])) {
            if ($_GET['centro'] == 'actualizado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $DATOS_ACTUALIZADOS . '</p>';
            } else if ($_GET['centro'] == 'eliminado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $CENTRO_DESACTIVADO . '</p>';
            } else if ($_GET['centro'] == 'registrado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $CENTRO_AÑADIDO . '</p>';
            } else if ($_GET['centro'] == 'reactivado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $CENTRO_REACTIVADO . '</p>';
            }
        }

        ?>

        <div style="margin-bottom: 0;" class="group">
            <input type="text" id="filtro" onkeyup="filtrar()" required>
            <label>Filtrar por ID o Nombre</label>
        </div>
        <div class="listaBotones">
            <?php

            $consulta = new Consultas();

            $centros = $consulta->getCentros();

            foreach ($centros as $centro) {
                $idCentro = $centro["id"];
                $nombreCentro = $centro["nombre"];
                $activo = $centro["activo"];

                if($activo) {
                    echo "<button class=\"botonAdaptable\" onclick=\"window.location.href = 'centro/$idCentro'\"> $idCentro | $nombreCentro </button>";
                    echo "<br>";
                } else {
                    echo "<button class=\"botonAdaptable\" style=\"background-color: #6b6b6b;\" onclick=\"window.location.href = 'centro/$idCentro'\"> $idCentro | $nombreCentro </button>";
                    echo "<br>";
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