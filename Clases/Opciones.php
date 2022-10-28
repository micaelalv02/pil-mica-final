<?php

namespace Clases;



use Exception;

class Opciones
{
    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $tipo;
    public $area;
    public $categoria;
    public $idioma;

    //Clases
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        if (!empty($valor)) {
            $valor = "'" . $valor . "'";
        } else {
            $valor = "NULL";
        }
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $return  = false;
        $sql = "INSERT INTO `opciones` (`cod`, `titulo`, `tipo`,`area`,`categoria`,`idioma`) VALUES ({$this->cod}, {$this->titulo}, {$this->tipo}, {$this->area},{$this->categoria},{$this->idioma})";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) $return = true;
        return $return;
    }
    public function edit()
    {
        $return  = false;
        $sql = "UPDATE `opciones` SET `titulo` = {$this->titulo}, `tipo` = {$this->tipo},`area` = {$this->area},`categoria` = {$this->categoria}  WHERE `cod` = {$this->cod} AND `idioma` = {$this->idioma}";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) $return = true;
        return $return;
    }
    public function delete()
    {
        $sql = "DELETE FROM `opciones` WHERE `cod` = {$this->cod} AND `idioma`= {$this->idioma} ";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) $return = true;
        return $return;
    }
    public function list($idioma, $filter = "", $values = false, $cod_validation = "", $single = false)
    {
        $array = array();
        $filterSql = "WHERE `opciones`.`idioma` = '$idioma'";
        if (is_array($filter)) {
            $filterSql .= " AND ";
            $filterSql .= implode(" AND ", $filter);
        }
        $leftJoin = "";
        $selectAttr = "`opciones`.`cod`,`opciones`.`titulo`,`opciones`.`tipo`,`opciones`.`area`,`opciones`.`categoria`,`opciones`.`idioma`";
        if ($values) {
            $leftJoin = "LEFT JOIN `opciones_valor` ON `opciones_valor`.`opcion_cod` = `opciones`.`cod` AND `opciones_valor`.`idioma` = `opciones`.`idioma` AND `opciones_valor`.`relacion_cod` = '$cod_validation'";
            $selectAttr = "`opciones`.`cod`,`opciones`.`titulo`,`opciones`.`tipo`,`opciones`.`area`,`opciones`.`idioma`, `opciones_valor`.`valor`";
        }
        $sql = "SELECT $selectAttr FROM `opciones` $leftJoin $filterSql";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                if ($row["tipo"] == "int") $row["tipo_mostrar"] = "Numérico";
                if ($row["tipo"] == "text") $row["tipo_mostrar"] = "Texto";
                if ($row["tipo"] == "boolean") $row["tipo_mostrar"] = "Si/No";
                ($single) ? $array = ["data" => $row] : $array[$row['cod']] = array("data" => $row);
            }
            return $array;
        }
    }
}
