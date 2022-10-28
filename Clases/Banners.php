<?php

namespace Clases;

class Banners
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $tituloOn;
    public $subtitulo;
    public $subtituloOn;
    public $categoria;
    public $link;
    public $linkOn;
    public $idioma;
    public $fecha;
    public $orden;
    private $con;

    private $imagenes;
    private $categorias;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes = new Imagenes();
        $this->categorias = new Categorias();
    }


    public function add($array)
    {
        $attr = implode(",", array_keys($array));
        $values = ":" . str_replace(",", ",:", $attr);
        $sql = "INSERT INTO banners ($attr) VALUES ($values)";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
    }
    public function edit($array, $cod)
    {
        $query = implode(", ", array_map(function ($v) {
            return "$v=:$v";
        }, array_keys($array)));
        $condition = implode(' AND ', $cod);
        $sql = "UPDATE banners SET $query WHERE $condition";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
    }


    public function delete($array)
    {
        $sql   = "DELETE FROM `banners` WHERE cod=:cod AND idioma=:idioma";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
        if (!empty($this->imagenes->list($array, "", "", true))) {
            $this->imagenes->deleteAll($array);
        }
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    function list($filter, $order = '', $limit = '', $single = false)
    {

        $array = array();
        foreach ($filter as $key => $value) {
            $filters[] = $key . "=:" . $key;
        }
        $filterSql = implode(" AND ", $filters);
        $orderSql = ($order != '') ?  $order  : "`id` DESC";
        $limitSql = ($limit != '') ? "LIMIT " . $limit : '';
        $sql = "SELECT * FROM `banners` WHERE $filterSql ORDER BY $orderSql $limitSql";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($filter);
        while ($row = $stmt->fetch()) {
            $img = $this->imagenes->list(["cod" => $row['cod'], "idioma" => $row["idioma"]], "", "", true);
            $cat = $this->categorias->list(["cod = '" . $row['categoria'] . "'"], '', '', $row["idioma"], true);
            $cat_ = (isset($cat['data'])) ? $cat['data'] : '';
            $array_ = array("data" => $row, "category" => $cat_, "image" => $img);
            $array[] = $array_;
        }

        return ($single) ? $array_ : $array;
    }

    // function list($filter = [], $order = '', $limit = '', $idioma = '', $single = false)
    // {

    //     $array = array();
    //     $filter[] = "idioma = '" . $idioma . "'";
    //     $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' AND ', $filter) : '';
    //     $orderSql = ($order != '') ?  $order  : "`id` DESC";
    //     $limitSql = ($limit != '') ? "LIMIT " . $limit : '';

    //     $sql   = "SELECT * FROM `banners` $filterSql  ORDER BY $orderSql $limitSql";
    //     $data = $this->con->sqlReturn($sql);
    //     if ($data) {
    //         while ($row = mysqli_fetch_assoc($data)) {
    //             $cod = $row['cod'];
    //             $img = $this->imagenes->list(["cod = '$cod'"], "", "", "'".$row['idioma']."'", true);
    //             $cat = $this->categorias->list(["cod = '" . $row['categoria'] . "'"], '', '', $row["idioma"], true);
    //             $cat_ = (isset($cat['data'])) ? $cat['data'] : '';
    //             $array_ = array("data" => $row, "category" => $cat_, "image" => $img);
    //             $array[] = $array_;
    //         }

    //         return ($single) ? $array_ : $array;
    //     } else {
    //         return false;
    //     }
    // }
}
