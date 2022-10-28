<?php

namespace Clases;

use Exception;

class Seo
{

    //Atributos
    public $id;
    public $cod;
    public $url;
    public $title;
    public $description;
    public $keywords;
    public $idioma;

    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes = new Imagenes();
        $this->f = new PublicFunction();
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
        $sql = "INSERT INTO `seo`(`cod`, `url`, `title`,`description`,`keywords`,`idioma`) 
                VALUES ({$this->cod},
                        {$this->url},
                        {$this->title},
                        {$this->description},
                        {$this->keywords},
                        {$this->idioma})";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql = "UPDATE `seo` 
                SET cod = {$this->cod},
                    url = {$this->url},
                    title = {$this->title},
                    description = {$this->description},
                    keywords = {$this->keywords}
                WHERE `cod`={$this->cod} AND `idioma`={$this->idioma}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete($array)
    {
        $sql = "DELETE FROM `seo` WHERE cod=:cod AND idioma=:idioma";
        try {
            $stmt = $this->con->conPDO()->prepare($sql);
            $stmt->execute($array);
            if (!empty($this->imagenes->list($array, "", "", true))) {
                $this->imagenes->deleteAll($array);
            }
            $response = true;
        } catch (Exception $e) {
            $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
        }
        return $response;
    }

    public function view()
    {
        $sql = "SELECT * FROM `seo` WHERE cod = {$this->cod} ORDER BY id DESC LIMIT 1";
        $seo = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($seo);
        $img = $this->imagenes->list(["cod" => $row['cod'], "idioma" => $row["idioma"]], "", "", false);
        $array = array("data" => $row, "images" => $img);
        return $array;
    }

    public function viewURL($idioma)
    {
        $sql = "SELECT * FROM `seo` WHERE `url` = {$this->url} AND `idioma` = '$idioma' ORDER BY id DESC LIMIT 1";
        $seo = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($seo);
        if ($row) {
            $img = $this->imagenes->list(["cod" => $row['cod'], "idioma" => $row["idioma"]], "", "", false);
            $array = array("data" => $row, "images" => $img);
        } else {
            $array = false;
        }

        return $array;
    }

    function list($filter, $order, $limit, $idioma)
    {
        if (empty($idioma)) $idioma = $_SESSION["lang"];
        $array = array();
        is_array($filter) ? $filter[] = "`idioma` = '" . $idioma . "' " : $filter = "`idioma` = '" . $idioma . "'";
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = "WHERE " . $filter;
        }

        if ($order != '') {
            $orderSql = $order;
        } else {
            $orderSql = "id DESC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }

        $sql = "SELECT * FROM `seo` $filterSql ORDER BY $orderSql $limitSql";
        $seo = $this->con->sqlReturn($sql);
        if ($seo) {
            while ($row = mysqli_fetch_assoc($seo)) {
                $img = $this->imagenes->list(["cod" => $row['cod'], "idioma" => $row["idioma"]], "", "", false);
                $array[] = array("data" => $row, "images" => $img);
            }
            return $array;
        }
    }
}
