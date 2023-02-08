<?php

/**
 * Con este fichero mostramos el menú principal del usuario
 */

// Si no existe una sesión de usuario o se intenta acceder desde la url le devolvemos al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/panel.php") {
    header('Location: /');
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
            <a class="active primero" href="/" class="primero">Inicio</a>
            <a href="cambiar-password">Cambiar contraseña</a>
            <a href="/cerrarSesion">Cerrar sesión</a>
            <a href="javascript:void(0);" class="icono" onclick="topNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>
    <div class="principal">
        <h1>Menú principal</h1>
        
        <?php

        if (isset($_GET['curso'])) {
            if ($_GET['curso'] == 'cerrado') {
                echo '<p style="margin: 4%; margin-bottom: 0;" class="valido">' . $CURSO_CERRADO . '</p>';
            }
        }

        ?>

        <div class="botonesPrincipal">
            <?php

            $esAlumno = $consulta->comprobarRol($_SESSION['id'], "alumno", 1);
            $esTutor = $consulta->comprobarRol($_SESSION['id'], "tutor", 1);
            $esDireccion = $consulta->comprobarRol($_SESSION['id'], "direccion", 1);

            echo "<button onclick=\"window.location.href = 'lista-reportes'\">Ver reportes</button>";

            if ($esAlumno) {
                echo "<button onclick=\"window.location.href = 'crear-reporte'\">Crear reporte</button>";
            }

            if ($esTutor) {
                echo "<button onclick=\"window.location.href = 'aniadir-alumno'\">Añadir alumno</button>";
                echo "<button onclick=\"window.location.href = 'cerrar-curso'\">Cerrar curso</button>";
            }

            if ($esDireccion) {
                echo "<button onclick=\"window.location.href = 'aniadir-profesor'\">Añadir profesor</button>";
                echo "<button onclick=\"window.location.href = 'cerrar-curso'\">Cerrar curso</button>";
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