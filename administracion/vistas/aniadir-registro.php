<?php

/**
 * A través de este fichero mostramos un formulario para crear un nuevo registro
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos el fichero con las consultas y creamos un objeto consultas
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

// Si no hay sesión de usuario o se accede desde la URL nos devuelve al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/aniadir-registro.php") {
    header("Location: /");
}

// Si se han mandado todos los datos del formulario...
if (isset($_POST['centroElegido'])) {
    // Importamos el código de creación de registro
    require $_SERVER['DOCUMENT_ROOT'] . '/includes/aniadir-registro.inc.php';
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
        <h1>Añadir registro</h1>

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

        <form action="#" method="POST">

            <div class="bloqueSelector">
                <p>Centro</p>
                <select id="centroElegido" name="centroElegido">
                    <option value="">Selecciona un centro</option>

                    <?php
                    $centros = $consulta->getCentrosActivos();

                    foreach ($centros as $centro) {
                        $id = $centro['id'];
                        $nombre = $centro['nombre'];
                        echo "<option value=\"$id\">$nombre</option>";
                    }
                    ?>

                </select>
            </div>

            <div class="bloqueSelector">
                <p>Usuario</p>

                <select id="usuarioElegido" name="usuarioElegido">
                    <option value="">Selecciona un usuario</option>

                    <?php
                    $usuarios = $consulta->getUsuariosActivos();

                    foreach ($usuarios as $usuario) {
                        $id = $usuario['id'];
                        $nombreApellidos = $usuario['nombre'] . " " . $usuario['apellidos'];
                        $email = $usuario['email'];
                        $admin = $usuario['admin'];
                        $nombreEmail = $nombreApellidos . " (" . $email . ")";
                        if (!$admin) {
                            echo "<option value=\"$id\">$nombreEmail</option>";
                        }
                    }
                    ?>

                </select>
            </div>

            <div class="bloqueSelector">
                <p>Rol</p>
                <select id="rolElegido" name="rolElegido" onchange="mostrarClase(this);">
                    <option value="">Selecciona un rol</option>
                    <option value="direccion">Direccion</option>
                    <option value="tutor">Tutor</option>
                    <option value="alumno">Alumno</option>
                </select>
            </div>

            <div class="group" id="clase" style="display: none;">
                <input type="text" id="claseInput" name="clase">
                <label>Clase</label>
            </div>

            <br>

            <button type="submit">Crear registro</button>
        </form>
    </div>
    <?php
    include_once "footer.php";
    ?>
    <script src="/js/funciones.js"></script>
    <script>
        function mostrarClase(that) {
            if (that.value == "tutor" || that.value == "alumno") {
                document.getElementById("claseInput").required = true;    
                document.getElementById("clase").style.display = "block";
            } else {
                document.getElementById("claseInput").required = false;
                document.getElementById("clase").style.display = "none";
            }
        }

        $("#centroElegido").select2({
            placeholder: "Selecciona un centro",
            allowClear: true
        });

        $("#usuarioElegido").select2({
            placeholder: "Selecciona un usuario",
            allowClear: true
        });

        $("#rolElegido").select2({
            placeholder: "Selecciona un rol",
            allowClear: true
        });
    </script>
</body>

</html>