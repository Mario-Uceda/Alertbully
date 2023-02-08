<?php

/**
 * Con este fichero mostraremos un listado de todos los registros que hay en la base de datos.
 */

// Importamos el diccionario de variables
require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

// Importamos conexion y creamos el objeto db
require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
$consulta = new Consultas();

session_start();

// Si no hay sesión de usuario o se accede desde la URL nos devuelve al index
if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_URI'] == "/vistas/ver-registros.php") {
    header("Location: /");
}

// Si hay un id en la url...
if (isset($_POST['desactivar'])) {
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
        <h1>Registros</h1>

        <div class="group">
            <input type="text" id="buscadorTabla" onkeyup="buscarEnTabla('tablaRegistros')" required>
            <label>Buscador...</label>
        </div>

        <table class="tablaRegistros">
            <tr>
                <th>Usuario</th>
                <th>Centro</th>
                <th>Curso</th>
                <th>Rol</th>
                <th>Activo</th>
            </tr>

            <?php

            $todosRegistros = $consulta->getRegistros();

            foreach ($todosRegistros as $registro) {
                $idusuario = $registro["idusuario"];
                $idcentro = $registro["idcentro"];
                $clase = $registro['clase'];
                $curso = $registro["curso"];
                $rol = $registro["rol"]; 
                $activo = $registro["activo"]; 

                echo "<tr>";
                echo "<td>";
                $usuario = $consulta->getDatosUsuarioConID($idusuario);
                $nombreyapellidos = $usuario['nombre'] . " " . $usuario['apellidos'] . " (" . $usuario['email'] . ")";
                echo "<a href=\"/usuario/$idusuario\"> $nombreyapellidos </a>";
                echo "</td>";

                echo "<td>";
                $centro = $consulta->getDatosCentro($idcentro);
                $nombreCentro = $centro['nombre'];
                echo "<a href=\"/centro/$idcentro\"> $nombreCentro </a>";
                echo "</td>";

                echo "<td>";
                echo $curso;
                echo "</td>";

                echo "<td>";
                if(empty($clase)) {
                    echo ucfirst($rol);
                } else {
                    echo ucfirst($rol) . " (" . $clase . ")";
                }
                echo "</td>";

                echo "<td>";
                if($activo) {
                    echo "Si";
                } else {
                    echo "No";
                }
                
                echo "</td>";

                echo "</tr>";
            }

            ?>
        </table>

    </div>

    <?php
    include_once "footer.php";
    ?>

    <script src="/js/funciones.js"></script>
</body>

</html>