<?php

namespace Clases;

class EstadosPedidos
{

    //Atributos
    public $id;
    public $estado;
    public $titulo;
    public $asunto;
    public $mensaje;
    public $enviar;
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
        $sql = "INSERT INTO `estados_pedidos`(`estado`, `titulo`, `asunto`, `mensaje`, `enviar`, `idioma`) 
                VALUES ({$this->estado},
                        {$this->titulo},
                        {$this->asunto},
                        {$this->mensaje},
                        {$this->enviar},
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
        $sql = "UPDATE `estados_pedidos` 
                SET  `estado`={$this->estado},
                    `titulo`={$this->titulo},
                    `asunto`={$this->asunto},
                    `mensaje`={$this->mensaje},
                    `enviar`={$this->enviar},
                    `idioma`={$this->idioma}
                WHERE `id`={$this->id} AND `idioma`= {$this->idioma}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function getStateBadge($id)
    {
        $state = $this->view($id);
        switch ($state['data']['estado']) {
            case 0:
                echo '<span class="badge badge-secondary fs-13 text-uppercase  pull-right" style="margin-right:40px"> Estado: ' . $state['data']['titulo'] . '</span>';
                break;
            case 1:
                echo '<span class="badge badge-warning fs-13 text-uppercase  pull-right" style="margin-right:40px">Estado: ' . $state['data']['titulo'] . '</span>';
                break;
            case 2:
                echo '<span class="badge badge-success fs-13 text-uppercase  pull-right" style="margin-right:40px">Estado: ' . $state['data']['titulo'] . '</span>';
                break;
            case 3:
                echo '<span class="badge badge-danger fs-13 text-uppercase  pull-right" style="margin-right:40px">Estado: ' . $state['data']['titulo'] . '</span>';
                break;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM `estados_pedidos` WHERE `id` = {$this->id} AND `idioma` = {$this->idioma}";

        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view($id)
    {
        $idioma = '';
        if ($this->idioma) {
            $idioma = "AND `idioma` = {$this->idioma}";
        }
        $sql = "SELECT * FROM `estados_pedidos` WHERE id = $id $idioma ORDER BY id DESC";
        $estados = $this->con->sqlReturn($sql);
        $row = !empty($estados) ? mysqli_fetch_assoc($estados) : '';
        $row_ = array("data" => $row);
        return $row_;
    }

    function list($filter, $order, $limit)
    {
        $array = array();
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

        $sql = "SELECT * FROM `estados_pedidos` $filterSql  ORDER BY $orderSql $limitSql";
        $estados = $this->con->sqlReturn($sql);
        if ($estados) {
            while ($row = mysqli_fetch_assoc($estados)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }
    function listByEstado()
    {
        $array = [];
        $sql = "SELECT `estado` , group_concat(`id` separator ',') as `id`  , group_concat(`titulo` separator ',') as `titulo`, group_concat(`enviar` separator ',') as `enviar` FROM `estados_pedidos` GROUP BY `estados_pedidos`.`estado`";
        $estados = $this->con->sqlReturn($sql);
        if ($estados) {
            while ($row = mysqli_fetch_assoc($estados)) {
                $idEstado = explode(",", $row['id']);
                $tituloEstado = explode(",", $row['titulo']);
                $enviarEstado = explode(",", $row['enviar']);
                foreach ($idEstado as $key => $value) {
                    $data[$key] = ['id' => $value, 'titulo' => $tituloEstado[$key], 'enviar' => $enviarEstado[$key]];
                }
                $array[$row['estado']] = array("data" => $data);
                unset($data);
            }
            return $array;
        }
    }
}
