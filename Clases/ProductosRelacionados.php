<?php

namespace Clases;

class ProductosRelacionados
{
    //Atributos
    public $id;
    public $titulo;
    public $productos_cod;
    public $cod;

    private $con;
    private $productos;
    private $carrito;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->productos = new Productos();
        $this->carrito = new Carrito();
    }

    public function set($atributo, $valor)
    {
        if (($atributo == "tipo" && empty($valor)) || ($atributo == "sector" && empty($valor))) {
            $valor = 0;
        } else {
            if (!empty($valor)) {
                $valor = "'" . $valor . "'";
            } else {
                $valor = "NULL";
            }
        }

        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql = "INSERT INTO `productos_relacionados`(`cod`,`titulo`, `productos_cod`,`idioma`) 
                  VALUES ({$this->cod},
                          {$this->titulo},
                          {$this->productos_cod},
                          {$this->idioma})";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `productos_relacionados` 
                  SET `cod`={$this->cod},
                      `titulo`={$this->titulo},
                      `productos_cod`={$this->productos_cod},
                      `idioma`={$this->idioma}
                  WHERE `id`={$this->id} AND `idioma`={$this->idioma}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM `productos_relacionados` WHERE `cod`  = {$this->cod} AND `idioma`  = {$this->idioma}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        $sql = "SELECT * FROM productos_relacionados WHERE cod = {$this->cod} AND idioma = {$this->idioma}  ";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function list($filter, $order, $limit, $idioma = '')
    {
        if (empty($idioma)) $idioma = $_SESSION['lang'];
        is_array($filter) ? $filter[] = "productos_relacionados.idioma = '" . $idioma . "' " : $filter = "productos_relacionados.idioma = '" . $idioma . "'";

        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
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
        $sql = "SELECT * FROM `productos_relacionados` $filterSql  ORDER BY $orderSql $limitSql";
        $products = $this->con->sqlReturn($sql);
        $related = "";
        if ($products) {
            while ($row = mysqli_fetch_assoc($products)) {
                $related .= $row["productos_cod"] . ",";
            }
        }
        $explodeRow = explode(",", $related);
        $explodeRowFinal = array_unique($explodeRow);
        $explodeRowFinal = array_diff($explodeRowFinal, array("", 0, null));
        return $explodeRowFinal;
    }

    public function listAdmin($filter, $idioma = '')
    {
        $array = array();
        is_array($filter) ? $filter[] = "productos_relacionados.idioma = '" . $idioma . "' " : $filter = "productos_relacionados.idioma = '" . $idioma . "'";
        if (!empty($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT * FROM `productos_relacionados` $filterSql ORDER BY `productos_relacionados`.`id` DESC";
        $product = $this->con->sqlReturn($sql);
        if ($product) {
            while ($row = mysqli_fetch_assoc($product)) {
                $array[] = array("data" => $row);
            }
        }
        return $array;
    }

    public function CodRelatedProducts($cod_producto)
    {
        $sql = "SELECT `titulo` FROM `productos_relacionados` WHERE  productos_cod LIKE '%$cod_producto%'";
        $value = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($value);
        return $row;
    }
}
