<?php

namespace Clases;
use Exception;
class LandingSubs
{
    //Atributos
    public $id;
    public $landingCod;
    public $nombre;
    public $apellido;
    public $celular;
    public $email;
    public $dni;
    public $ganador;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add($array)
    {
        $attr = implode(",", array_keys($array));
        $values = ":" . str_replace(",", ",:", $attr);
        $sql = "INSERT INTO `landing_subs` ($attr) VALUES ($values)";
        try {
            $stmt = $this->con->conPDO()->prepare($sql);
            $stmt->execute($array);
            $response = true;
        } catch (Exception $e) {
            $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
        }
        return $response;
    }

    public function edit()
    {
        $sql = "UPDATE `landing_subs` 
                    SET landing_cod = '{$this->landingCod}',
                        nombre = '{$this->nombre}',
                        apellido = '{$this->apellido}',
                        celular = '{$this->celular}',
                        email = '{$this->email}',
                        dni = '{$this->dni}' 
                    WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function updateWinner()
    {
        $sql = "UPDATE `landing_subs` SET ganador='{$this->ganador}' WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function selectWinner($limit)
    {
        $sql = "SELECT * FROM `landing_subs` WHERE landing_cod = '{$this->landingCod}' ORDER BY RAND() LIMIT $limit";
        $landing = $this->con->sqlReturn($sql);
        if ($landing) {
            while ($row = mysqli_fetch_assoc($landing)) {
                $array[] = $row;
            }
            return $array;
        }
    }

    public function searchWinner()
    {
        $array='';
        $sql = "SELECT * FROM `landing_subs` WHERE landing_cod = '{$this->landingCod}' AND ganador>=1 ORDER BY ganador ASC";
        $landing = $this->con->sqlReturn($sql);
        if ($landing) {
            $array = [];
            while ($row = mysqli_fetch_assoc($landing)) {
                $array[] = $row;
            }
            return $array;
        }
    }

    public function resetWinner()
    {
        $sql = "UPDATE `landing_subs` SET ganador=0 WHERE landing_cod = '{$this->landingCod}'";
        $landing = $this->con->sql($sql);
        return $landing;
    }

    public function delete()
    {
        $sql = "DELETE FROM `landing_subs` WHERE `id`  = '{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `landing_subs` WHERE id = '{$this->id}' ORDER BY id DESC";
        $landing = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($landing);
        return $row;
    }

    function list($filter)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = "WHERE ";
            $filterSql .= $filter;
        }

        $sql = "SELECT * FROM `landing_subs` $filterSql  ORDER BY id DESC";
        $landing = $this->con->sqlReturn($sql);

        if ($landing) {
            while ($row = mysqli_fetch_assoc($landing)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}