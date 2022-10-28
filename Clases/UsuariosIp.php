<?php

namespace Clases;

class UsuariosIp
{
    //Atributos
    public $id;
    public $usuario;
    public $ip;
    public $dispositivo;
    public $fecha_creacion;
    public $ultima_actualizacion;
    public $fecha;
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
        $sql = "INSERT INTO `usuarios_ip`(`usuario`,`ip`,`dispositivo`,`frecha_creacion`) 
                  VALUES ({$this->usuario}, {$this->ip}, {$this->dispositivo},NOW())";
        $query = $this->con->sqlReturn($sql);
        (!empty($query)) ? true : false;
    }

    public function checkIfExists()
    {
        $sql = "SELECT * FROM `usuarios_ip` WHERE `ip` = {$this->ip}";
        $query = $this->con->sqlReturn($sql);
        if ($query->num_rows) {
            if ($this->usuario != "NULL") $this->editSingle("usuario", $this->usuario);
        } else {
            $this->add();
        }
    }
    public function editSingle($atributo, $valor)
    {

        $sql = "UPDATE `usuarios_ip` SET `$atributo` = {$valor} WHERE `ip`={$this->ip}";
        $this->con->sqlReturn($sql);
        return (!empty($query)) ? true : false;
    }

    public function delete()
    {
        $sql = "DELETE FROM `usuarios_ip` WHERE `ip`  = {$this->ip}";
        $query = $this->con->sql($sql);

        return (!empty($query)) ? true : false;
    }

    public function view($attr, $value)
    {
        $sql = "SELECT * FROM usuarios_ip WHERE   $attr = '$value'  ";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function list($filter, $order, $limit)
    {
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $data = [];
        $sql = "SELECT * FROM `usuarios_ip` $filterSql";
        $products = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($products)) {
            $data[] = $row;
        }
        return $data;
    }
}
