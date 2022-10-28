<?php

namespace Clases;

class Envios
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $peso;
    public $precio;
    public $estado;
    public $opciones;
    public $descripcion;
    public $limite;
    public $tipo_usuario;
    public $idioma;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }
    public function set($atributo, $valor)
    {
        if ($valor != '') {
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
        $sql = "INSERT INTO `envios`(`cod`, `titulo`,`descripcion`,`opciones`, `peso`, `precio`, `estado`,`limite`,`tipo_usuario`,`idioma`) 
                VALUES ({$this->cod},
                        {$this->titulo},
                        {$this->descripcion},
                        {$this->opciones},
                        {$this->peso},
                        {$this->precio},
                        {$this->estado},
                        {$this->limite},
                        {$this->tipo_usuario},
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
        $sql = "UPDATE `envios` 
                SET  `titulo`={$this->titulo},
                    `peso`={$this->peso},
                    `precio`={$this->precio},
                    `descripcion`={$this->descripcion},
                    `opciones`={$this->opciones},
                    `estado`={$this->estado},
                    `limite`={$this->limite},
                    `tipo_usuario`={$this->tipo_usuario},
                    `idioma`={$this->idioma}
                WHERE `cod`={$this->cod} AND `idioma`={$this->idioma}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function changeState()
    {
        $sql = "UPDATE `envios` SET `estado`={$this->estado} AND `idioma`={$this->idioma} WHERE `cod`={$this->cod} AND `idioma`={$this->idioma}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function delete()
    {
        $sql = "DELETE FROM `envios` WHERE `cod`  = {$this->cod} AND  `idioma`  = {$this->idioma}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function view()
    {
        $row_ = [];
        $sql = "SELECT * FROM `envios` WHERE cod = {$this->cod} AND idioma = {$this->idioma} ORDER BY id DESC";
        $envios = $this->con->sqlReturn($sql);
        if ($envios) {
            $row = mysqli_fetch_assoc($envios);
            $row_ = array("data" => $row);
        }
        return $row_;
    }
    function list($filter, $order, $limit, $idioma)
    {
        $array = array();
        $filterSql = "WHERE idioma = '$idioma' ";
        if (is_array($filter)) {
            $filterSql .= " AND ";
            $filterSql .= implode(" AND ", $filter);
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
        $sql = "SELECT * FROM `envios` $filterSql  ORDER BY $orderSql $limitSql";
        $envios = $this->con->sqlReturn($sql);
        if ($envios) {
            while ($row = mysqli_fetch_assoc($envios)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }
}
