<?php

/**
 * Clase Db para gestionar la base de datos
 */
class Db {
    private $servidor;
    private $db;
    private $usuario;
    private $password;

    /**
     * Función que devuelve un objeto mysqli
     */
    protected function conectar() {
        $this->servidor = "localhost";
        $this->db = "dbs229806";
        $this->usuario = "root";
        $this->password = "toor";

        $conexion = new mysqli(
            $this->servidor,
            $this->usuario,
            $this->password,
            $this->db
        );

        return $conexion;
    }
}
