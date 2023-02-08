<?php

require 'db.class.php';

/**
 * Clase que extiende de la clase Db y que contiene todas las consultas
 * a la base de datos
 * 
 * Grupos (usa Ctrl + F para encontrarlos más fácil):
 *  - Funciones de usuarios
 *  - Funciones de centros
 *  - Funciones de restablecimiento de contraseña
 *  - Funciones de la tabla registro
 *  - Funciones de la tabla reportes
 *  - Otras funciones
 */
class Consultas extends Db
{

    // FUNCIONES DE USUARIOS
    /**
     * Función para actualizar la contraseña del usuario
     */
    public function actualizarPasswordUsuario($idUsuario, $nuevoPassword)
    {
        $sql = "UPDATE usuarios SET password= ? WHERE id= ?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('si', $nuevoPassword, $idUsuario);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para obtener el ID y el Nombre de un usuario a partir de su email
     * y contraseña 
     */
    public function getDatosUsuarioConEmailPassword($email, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE email=? && password=? && activo=1 LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Función para obtener el email y la password de un usuario con un cierto id
     * y una contraseña
     */
    public function getDatosUsuarioConID($idUsuario)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ? LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Función para obtener los datos de un usuario a través de un email
     */
    public function getDatosUsuarioConEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Función para comprobar si existe un usuario con un id y una cookie
     */
    public function comprobarCookieUsuario($id, $cookie)
    {
        $sql = "SELECT * FROM usuarios where id=? && cookie=? LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $id, $cookie);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Función para registrar un nuevo usuario
     */
    public function guardarUsuario($nombre, $apellidos, $telefono, $email, $password, $activo, $admin)
    {
        $sql = "INSERT INTO usuarios(nombre, apellidos, telefono, email, password, activo, admin) VALUES(?,?,?,?,?,?,?)";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssissii', $nombre, $apellidos, $telefono, $email, $password, $activo, $admin);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    // FUNCIONES DE CENTROS
    /**
     * Función para obtener todos los centros
     */
    public function getCentros()
    {
        $sql = "SELECT * FROM centros;";
        $conexion = $this->conectar();

        $resultado = $conexion->query($sql);
        $centros = $resultado->fetch_all(MYSQLI_BOTH);

        $conexion->close();

        return $centros;
    }

    /**
     * Función para obtener todos los centros activos
     */
    public function getCentrosActivos()
    {
        $sql = "SELECT * FROM centros WHERE activo=1;";
        $conexion = $this->conectar();

        $resultado = $conexion->query($sql);
        $centros = $resultado->fetch_all(MYSQLI_BOTH);

        $conexion->close();

        return $centros;
    }

    /**
     * Función para obtener los datos de un centro
     */
    public function getDatosCentro($id)
    {
        $sql = "SELECT * FROM centros WHERE id=? LIMIT 1";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $centro = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $centro;
    }

    /**
     * Función para obtener los centros en los que está un usuario
     */
    public function getCentrosDeUsuario($idusuario, $rol, $activo)
    {
        if ($activo) {
            $sql = "SELECT centros.id as id, centros.nombre as nombre 
            FROM centros left outer join registros on centros.id = registros.idcentro
            WHERE registros.idusuario = ? && registros.rol = ? && registros.activo = 1;";
        } else {
            $sql = "SELECT centros.id as id, centros.nombre as nombre
            FROM centros left outer join registros on centros.id = registros.idcentro
            WHERE registros.idusuario = ? && registros.rol = ?;";
        }

        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('is', $idusuario, $rol);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $centro = $resultado->fetch_all(MYSQLI_BOTH);

        $stmt->close();
        $conexion->close();

        return $centro;
    }

    // FUNCIONES DE LA TABLA REGISTRO

    /**
     * Función para devolver todos los registros
     */
    public function getRegistros()
    {
        $sql = "SELECT * FROM registros;";
        $conexion = $this->conectar();

        $resultado = $conexion->query($sql);
        $registros = $resultado->fetch_all(MYSQLI_BOTH);

        $conexion->close();

        return $registros;
    }

    /**
     * Función para devolver un registro a partir de un id de usuario, de un id de centro y de un rol
     */
    public function getRegistro($idusuario, $idCentro, $rol)
    {
        $sql = "SELECT * FROM registros WHERE idusuario = ? && idcentro = ? && rol = ?;";

        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('iis', $idusuario, $idCentro, $rol);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $registros = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $registros;
    }

    /**
     * Función para comprobar que un usuario pertenece a un centro
     */
    public function usuarioEstaEnCentro($idusuario, $idcentro, $comprobarActivo)
    {
        $conexion = $this->conectar();

        if ($comprobarActivo) {
            $sql = "SELECT * FROM registros WHERE idusuario = ? && idcentro = ? && activo = 1 LIMIT 1;";
        } else {
            $sql = "SELECT * FROM registros WHERE idusuario = ? && idcentro = ? LIMIT 1;";
        }

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $idusuario, $idcentro);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $registros = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $registros;
    }

    /**
     * Función para realizar un registro
     */
    public function crearRegistro($idUsuario, $idCentro, $rol, $clase, $curso, $activo)
    {
        $sql = "INSERT INTO registros(idusuario, idcentro, rol, clase, curso, activo) VALUES(?,?,?,?,?,?);";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('iisssi', $idUsuario, $idCentro, $rol, $clase, $curso, $activo);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para obtener el tutor de una clase estando activo
     */
    public function comprobarTutorClaseCentro($idusuario, $idcentro, $clase)
    {
        $conexion = $this->conectar();

        $sql = "SELECT * FROM registros WHERE idusuario = ? && idcentro = ? && clase = ? && rol = 'tutor' && activo = 1;";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('iis', $idusuario, $idcentro, $clase);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $registros = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $registros;
    }

    /**
     * Función para comprobar si un usuario pertenece al equipo de dirección de un centro como activo
     */
    public function comprobarDireccionCentro($idusuario, $idcentro)
    {
        $conexion = $this->conectar();

        $sql = "SELECT * FROM registros WHERE idusuario = ? && idcentro = ? && rol = 'direccion' && activo = 1;";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $idusuario, $idcentro);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $registros = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $registros;
    }

    /**
     * Función para comprobar si un usuario tiene un rol en concreto dentro de la tabla registros dependiendo de si está
     * activo o no
     */
    public function comprobarRol($idusuario, $rol, $comprobarActivo)
    {
        $conexion = $this->conectar();

        if ($comprobarActivo) {
            $sql = "SELECT * FROM registros where idusuario=? && rol=? && activo=?;";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('isi', $idusuario, $rol, $comprobarActivo);
        } else {
            $sql = "SELECT * FROM registros where idusuario=? && rol=?;";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('is', $idusuario, $rol);
        }

        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Función para terminar un curso
     */
    public function cerrarCurso(
        $idCentro,
        $clase,
        $rol
    ) {
        if ($rol == 'tutor') {
            $sql = "UPDATE registros set activo=0 where idcentro = ? && clase = ? && activo=1;";
            $conexion = $this->conectar();

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('is',  $idCentro, $clase);
            $ejecucion = $stmt->execute();
        } else if ($rol == 'direccion') {
            $sql = "UPDATE registros set activo=0 where idcentro = ? && rol != 'direccion' && activo=1;";
            $conexion = $this->conectar();

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('i', $idCentro);
            $ejecucion = $stmt->execute();
        }

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    // FUNCIONES DE LA TABLA REPORTES
    /**
     * Función para crear un nuevo reporte en la tabla reportes
     */
    public function crearReporte(
        $idalumno,
        $idcentro,
        $clase,
        $curso,
        $titulo,
        $victima,
        $acosador,
        $lugar,
        $descripcion,
        $fechaHora,
        $fechaHoraCreacion
    ) {
        $sql = "INSERT INTO reportes(idalumno, idcentro, clase, curso, titulo, victima, acosador, lugar, descripcion, fechaHora, fechaHoraCreacion) VALUES(?,?,?,?,?,?,?,?,?,?,?);";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            'iisssssssss',
            $idalumno,
            $idcentro,
            $clase,
            $curso,
            $titulo,
            $victima,
            $acosador,
            $lugar,
            $descripcion,
            $fechaHora,
            $fechaHoraCreacion
        );

        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para obtener reportes de un usuario en un centro
     */
    public function getReportesUsuario($idcentro, $idusuario)
    {
        $sql = "SELECT * FROM reportes WHERE idalumno = ? && idcentro = ?";

        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $idusuario, $idcentro);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $reportes = $resultado->fetch_all(MYSQLI_BOTH);

        $stmt->close();
        $conexion->close();

        return $reportes;
    }

    /**
     * Función para obtener los reportes de un centro y de una clase en concreto
     */
    public function getReportesClase($idcentro, $clase)
    {
        $conexion = $this->conectar();

        $sql = "SELECT * FROM reportes WHERE idcentro = ? && clase = ?;";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('is', $idcentro, $clase);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $reportes = $resultado->fetch_all(MYSQLI_BOTH);

        $stmt->close();
        $conexion->close();

        return $reportes;
    }

    /**
     * Función para obtener los reportes de un centro
     */
    public function getReportesCentro($idcentro)
    {
        $conexion = $this->conectar();

        $sql = "SELECT * FROM reportes WHERE idcentro = ?;";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idcentro);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $reportes = $resultado->fetch_all(MYSQLI_BOTH);

        $stmt->close();
        $conexion->close();

        return $reportes;
    }

    /**
     * Función para obtener los datos de un reporte a partir de su id
     */
    public function getDatosReporte($id)
    {
        $conexion = $this->conectar();

        $sql = "SELECT * FROM reportes LEFT OUTER JOIN usuarios on reportes.idalumno = usuarios.id WHERE reportes.id = ?;";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $reporte = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $reporte;
    }


    /**
     * Función para obtener los comentarios de un reporte
     */
    public function getComentariosReporte($idreporte)
    {
        $conexion = $this->conectar();

        $sql = "SELECT comentarios.id as id, comentarios.comentario as comentario, usuarios.nombre as nombre, usuarios.apellidos as apellidos, comentarios.fechaHora as fechaHora FROM comentarios LEFT JOIN usuarios ON comentarios.idusuario = usuarios.id WHERE idreporte = ?;";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idreporte);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $comentarios = $resultado->fetch_all(MYSQLI_BOTH);

        $stmt->close();
        $conexion->close();

        return $comentarios;
    }

    /**
     * Función para guardar un nuevo comentario
     */
    public function guardarComentario($idusuario, $idreporte, $comentario, $fecha)
    {
        $sql = "INSERT INTO comentarios(id, idusuario, idreporte, comentario, fechaHora) VALUES(?,?,?,?,?);";
        $conexion = $this->conectar();

        $idcomentario = $this->ultimoIdComentarioReporte($idreporte);

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('iiiss', $idcomentario, $idusuario, $idreporte, $comentario, $fecha);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    public function ultimoIdComentarioReporte($idreporte)
    {
        $conexion = $this->conectar();

        $sql = "SELECT MAX(id) as id FROM comentarios WHERE idreporte = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idreporte);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $comentario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        if (!$comentario['id']) {
            return 1;
        } else {
            return $comentario['id'] + 1;
        }
    }

    // FUNCIONES DE RESTABLECIMIENTO DE CONTRASEÑA

    /**
     * Función para borrar el registro de la tabla pwdreset que contenga $email
     */
    public function borrarRegistroPwdresetPorEmail($email)
    {
        $sql = "DELETE FROM pwdreset WHERE email=?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

    /**
     * Función para insertar un nuevo registro en la tabla pwdreset
     */
    public function nuevoRegistroPwdreset($email, $selector, $tokenHash, $expira)
    {
        $sql = "INSERT INTO pwdreset(email, selector, token, expira) VALUES(?,?,?,?);";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssss', $email, $selector, $tokenHash, $expira);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para comprobar si existe un registro con un selector y una expiración
     * superior a la dada
     */
    public function comprobarRegistroPwdreset($selector, $momentoActual)
    {
        $sql = "SELECT * FROM pwdreset WHERE selector=? && expira >= ? LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ss', $selector, $momentoActual);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $registro = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $registro;
    }

    // OTRAS FUNCIONES

    /**
     * Función para establecer la cookie al usuario
     */
    public function crearCookie($numero_aleatorio, $idUsuario)
    {
        $sql = "UPDATE usuarios set cookie=? where id=?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $numero_aleatorio, $idUsuario);
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

    /**
     * Función para borrar una cookie en la base de datos
     */
    public function borrarCookie($idUsuario, $cookie)
    {
        $sql = "UPDATE usuarios SET cookie=null WHERE id=? && cookie=?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $idUsuario, $cookie);
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

    /**
     * Función para guardar la IP y la fecha de incio de sesión del usuario
     */
    public function guardarIpFecha($fecha, $ip, $idUsuario)
    {
        $sql = "UPDATE usuarios set ultimaCon=?, ipCon=? where id=?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssi', $fecha, $ip, $idUsuario);
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }
}
