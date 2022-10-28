<?php

namespace Clases;


class Area
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;

    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        if (strlen($valor)) {
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


    /**
     *
     * Realizamos un list de varios ....
     *
     * @param    array  $array  todos los campos a actualizar con la key = campo sql
     *
     */

    public function add($array)
    {
        $attr = implode(",", array_keys($array));
        $values = ":" . str_replace(",", ",:", $attr);
        $sql = "INSERT INTO area ($attr) VALUES ($values)";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
    }


    /**
     *
     * Realizamos un list de varios ....
     *
     * @param    array  $array  todos los campos a actualizar con la key = campo sql
     * @param    array  $params filtros para el where del update ["id = 1"]     
     *
     */

    public function edit($array, $params)
    {
        $query = implode(", ", array_map(function ($v) {
            return "$v=:$v";
        }, array_keys($array)));
        $condition = implode(' AND ', $params);
        $sql = "UPDATE area SET $query WHERE $condition";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
    }

    public function delete()
    {
        $sql = "DELETE FROM `area` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function list($filter = [], $order = '', $limit  = '', $idioma, $single = false)
    {
        $array = [];
        ($filter != [""]) ? $filter[] = "idioma = '$idioma'" : $filter[0] = "idioma = '$idioma'";
        $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' AND ', $filter) : '';
        $orderSql = ($order != '') ?  $order : "`id` DESC";
        $limitSql = ($limit != '') ? "LIMIT " . $limit : '';

        $sql = "SELECT * FROM `area` $filterSql  ORDER BY $orderSql $limitSql";
        $area = $this->con->sqlReturn($sql);
        if ($area->num_rows) {
            while ($row = mysqli_fetch_assoc($area)) {
                $array_ = ["data" => $row];
                $array[] = $array_;
            }
            return ($single) ? $array_ : $array;
        }
    }

    public function listIfHave($db)
    {
        $array = array();
        $sql = " SELECT `area`.`titulo`,`area`.`cod`,`area`.`id`, count(`" . $db . "`.`area`) as cantidad FROM `" . $db . "`,`contenidos` WHERE `area` = `contenidos`.`cod` GROUP BY area ORDER BY cantidad DESC ";
        $listIfHave = $this->con->sqlReturn($sql);
        if ($listIfHave) {
            while ($row = mysqli_fetch_assoc($listIfHave)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }
}
