<?php

namespace Clases;



use Exception;

class OpcionesValor
{
    //Atributos
    public $id;
    public $cod;
    public $relacion_cod;
    public $opcion_cod;
    public $valor;
    public $idioma;

    //Clases
    private $con;
    public $f;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->f = new PublicFunction();
    }

    public function set($atributo, $value)
    {
        if (!empty($value)) {
            $value = "'" . $value . "'";
        } else {
            $value = "NULL";
        }
        $this->$atributo = $value;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $return  = false;
        $sql = "INSERT INTO `opciones_valor` (`cod`,`relacion_cod`,`opcion_cod`,`valor`,`idioma`) VALUES ({$this->cod},{$this->relacion_cod}, {$this->opcion_cod},  {$this->valor},{$this->idioma})";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) $return = true;
        return $return;
    }
    public function edit()
    {
        $return  = false;
        if ($this->valor != "NULL") {
            $sql = "UPDATE `opciones_valor` SET `valor` = {$this->valor} WHERE `cod` = {$this->cod} AND `idioma` = {$this->idioma} AND `opcion_cod` = {$this->opcion_cod};";
            $query = $this->con->sqlReturn($sql);
            if (!empty($query)) $return = true;
        } else {
            $this->delete();
            $return = true;
        }
        return $return;
    }
    public function checkIfExist()
    {
        $array = [];
        $sql = "SELECT `opciones_valor`.`cod` FROM `opciones_valor` WHERE `idioma` ={$this->idioma} AND `relacion_cod` = {$this->relacion_cod} AND `opcion_cod` = {$this->opcion_cod};";
        $query = $this->con->sqlReturn($sql);
        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                $array = array("data" => $row);
            }
            return $array;
        }
    }
    public function delete()
    {
        $sql = "DELETE FROM `opciones_valor` WHERE `cod` = {$this->cod} AND `idioma`= {$this->idioma} ";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) $return = true;
        return $return;
    }
    public function list($idioma, $filter = "", $labels = false)
    {
        $array = array();
        $filterSql = "WHERE `opciones_valor`.`idioma` = '$idioma'";
        if (is_array($filter)) {
            $filterSql .= " AND ";
            $filterSql .= implode(" AND ", $filter);
        }
        $selectAttr = "`opciones_valor`.*";
        $leftJoin = "";
        if ($labels) {
            $selectAttr = "`opciones_valor`.*,`opciones`.`titulo`";
            $leftJoin = "LEFT JOIN `opciones` ON `opciones`.`cod` = `opciones_valor`.`opcion_cod`";
        }
        $sql = "SELECT $selectAttr FROM `opciones_valor` $leftJoin $filterSql";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $array[$row['opcion_cod']] = array("data" => $row);
            }
            return $array;
        }
    }
}
