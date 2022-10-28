<?php

namespace Clases;

class Promos
{
    //Atributos
    public $id;
    public $producto;
    public $lleva;
    public $paga;
    public $idioma;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }


    public function insert($array)
    {
        $insert = '';
        foreach ($array as $key => $value) {
            $value2 = (!empty($value)) ? "'" . $value . "'" : 'null';
            $insert .= "`" . $key . "`= " . $value2 . " ,";
        }
        $insert = substr_replace($insert, "", -1);
        $sql = "INSERT INTO `promos` SET $insert ON DUPLICATE KEY UPDATE $insert";
        $query = $this->con->sqlReturn($sql);
        return $query;
    }

    public function delete($producto, $idioma)
    {
        $sql = "DELETE FROM `promos` WHERE `producto` = '$producto' AND `idioma`= '$idioma'";
        $query = $this->con->sqlReturn($sql);
        return $query;
    }
    public function exist()
    {
        $array = [];
        $lang = $_SESSION["lang"];
        $sql = "SELECT `promos`.`producto`,`promos`.`idioma`,`productos`.`cod` FROM `promos`,`productos`  WHERE `productos`.`cod` = `promos`.`producto` AND `promos`.`idioma`= '$lang'";
        $query = $this->con->sqlReturn($sql);
        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                $array["data"] = $row;
            }
        }
        return  $array;
    }
}
