<?php

namespace Clases;
class ImportarExcel
{

    //Atributos
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function add($tabla, $columnas, $datos)
    {
        $sql = "INSERT INTO " . $tabla;

        $string_columnas = implode(",", $columnas);
        $sql .= " (" . $string_columnas . ") ";

        $sql .= "VALUES";

        foreach ($datos as $col => $row) {
            $string_datos = implode("','", $row);
            $sql .= " ('" . $string_datos . "'),";
        }
        $sql = substr($sql,0,-1);
        $this->con->sql($sql);
    }

}
