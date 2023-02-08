<?php

/**
 * A través de este fichero mostramos los datos de un reporte y daremos la posibilidad de escribir
 * un comentario
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

// Si no hay sesión de usuario o se accede desde la URL nos devuelve al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/reporte.php") {
    header("Location: /");
}

// Guardamos los datos del reporte en $reporte
if (isset($_GET['id'])) {
    $reporte = $consulta->getDatosReporte($_GET['id']);
}

// Hacemos las comprobaciones necesarias para segurarnos de que el usuario puede acceder a dicho reporte

// Comprobamos que el usuario que está logeado es el propietario del ticket, en caso de que no sea...
if ($_SESSION['id'] != $reporte['idalumno']) {
    // Comprobamos que el usuario que está logeado es el tutor de la clase del reporte, en caso de que no lo sea...
    if (!$consulta->comprobarTutorClaseCentro($_SESSION['id'], $reporte['idcentro'], $reporte['clase'])) {
        // Comprobamos que el usuario que está logeado es del equipo directivo del centro del reporte
        if (!$consulta->comprobarDireccionCentro($_SESSION['id'], $reporte['idcentro'])) {
            // Si ninguna de las condiciones anteriores se cumplen, se devolverá al menú
            header("Location: /");
        }
    }
}

// Si se manda el formulario...
if (isset($_POST['comentario'])) {
    // Importamos el código de registro de usuario
    require $_SERVER['DOCUMENT_ROOT'] . '/includes/aniadir-comentario.inc.php';
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
        <h1>Reporte</h1>

        <?php

        if (isset($error)) {
            echo "<p class=\"error\">$error</p>";
        }

        if (isset($_GET['registro'])) {
            if ($_GET['registro'] == 'creado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $REGISTRO_CREADO . '</p>';
            }
        }

        ?>

        <div class="reporte">
            <p>
                <span style="color: #a73f2d; font-weight: bold;"><?php echo $reporte['titulo'] ?></span>
                <span style="color: grey; float: right"><?php echo $reporte['fechaHoraCreacion'] ?></span>
            </p>

            <p><span style="color: #a73f2d;">Creado por:</span> <?php echo $reporte['nombre'] . ' ' . $reporte['apellidos'] ?></p>

            <p><span style="color: #a73f2d;">Victima:</span> <?php echo $reporte['victima'] ?></p>

            <p><span style="color: #a73f2d;">Acosador:</span> <?php echo $reporte['acosador'] ?></p>

            <p><span style="color: #a73f2d;">Lugar:</span> <?php echo $reporte['lugar'] ?></p>

            <p><span style="color: #a73f2d;">Descripción:</span> <?php echo $reporte['descripcion'] ?></p>

            <p><span style="color: #a73f2d;">Fecha y hora del acoso:</span> <?php echo $reporte['fechaHora'] ?></p>
            <p><span style="color: #a73f2d;">Clase y curso: </span> <?php echo $reporte['clase'] . ' | ' .  $reporte['curso'] ?></p>
        </div>

        <div class="contenedorComentarios">

            <?php

            $comentarios = $consulta->getComentariosReporte($_GET['id']);

            foreach ($comentarios as $comentario) {
                $idComentario =  $comentario["id"];
                $usuario = $comentario["nombre"] . ' ' . $comentario["apellidos"];
                $fechaHora = $comentario["fechaHora"];
                $comentario = $comentario["comentario"];


                echo "<div class=\"comentario\">";
                echo "<p>
                <span style=\"color: #a73f2d; font-weight: bold;\"><span style=\"color: #373737; 
                background-color: #e8e9ea;
                border-radius: 10px;
                padding: 0 7px;
                font-weight: 100;
                margin-right: 4px;\">#$idComentario</span> $usuario</span><span style=\"color: grey; float: right\">$fechaHora</span>
                </p>";
                echo "<p style=\"text-align: justify;\">$comentario</p>";
                echo "</div>";
            }

            ?>

        </div>

        <form action="#" method="POST" style="width: auto;">
            <textarea style="width: 75%;" rows=4 placeholder="Escribe un comentario..." name="comentario" required></textarea>
            <br>
            <button type="submit">Enviar comentario</button>
        </form>
    </div>

    <?php
    include_once "footer.php";
    ?>

    <script src="/js/funciones.js"></script>
</body>

</html>