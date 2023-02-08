<?php

/**
 * A través de este fichero se buscan reportes en la tabla de reportes y son devueltos para
 * mostrarlos en la lista de reportes.
 * 
 * A este fichero se accede por ajax.
 */

session_start();

if (isset($_POST['idcentro']) && isset($_SESSION['usuario']) && $_SERVER['REQUEST_URI'] == "/vistas/buscador-reportes.php") {
    // Importamos el diccionario de variables
    require $_SERVER['DOCUMENT_ROOT'] . '/includes/diccionario.inc.php';

    // Importamos el fichero con las consultas y creamos un objeto consultas
    require $_SERVER['DOCUMENT_ROOT'] . '/classes/consultas.class.php';
    $consulta = new Consultas();

    // Comprobamos que el centro elegido existe en la base de datos y que el usuario está en ese centro
    if (($centro = $consulta->getDatosCentro($_POST['idcentro'])) != null) {
        if (($registro = $consulta->usuarioEstaEnCentro($_SESSION['id'], $_POST['idcentro'], false)) != null) {

            $esAlumno = $consulta->comprobarRol($_SESSION['id'], "alumno", 1);
            $esTutor = $consulta->comprobarRol($_SESSION['id'], "tutor", 1);
            $esDireccion = $consulta->comprobarRol($_SESSION['id'], "direccion", 1);

            // Si es alumno, obtenemos los reportes que ha realizado
            if ($esAlumno != null) {
                $reportes = $consulta->getReportesUsuario($_POST['idcentro'], $_SESSION['id']);
            }

            // Si es tutor, cogemos los reportes que su clase ha realizado en ese centro
            if ($esTutor != null) {
                $clase = $consulta->getRegistro($_SESSION['id'], $_POST['idcentro'], 'tutor')['clase'];
                $reportes = $consulta->getReportesClase($_POST['idcentro'], $clase);
            }

            if ($esDireccion != null) {
                $reportes = $consulta->getReportesCentro($_POST['idcentro']);
            }

            foreach ($reportes as $reporte) {
                $idreporte = $reporte["id"];
                $titulo = $reporte["titulo"];
                $fechaCreacion = $reporte["fechaHoraCreacion"];

                echo "<button class=\"botonAdaptable\" onclick=\"window.location.href = 'reporte/$idreporte'\"> $titulo | $fechaCreacion </button>";
                echo "<br>";
            }
        } else {
            $error = $NO_PERTENECE_CENTRO;
        }
    } else {
        $error = $CENTRO_NO_EXISTE;
    }
} else {
    header('Location: 404');
}
