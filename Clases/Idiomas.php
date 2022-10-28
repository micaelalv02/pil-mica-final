<?php

namespace Clases;


class Idiomas
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
    public function add()
    {
        $sql = "INSERT INTO `idiomas`(`cod`, `titulo`,`default`) 
                  VALUES ({$this->cod},{$this->titulo},{$this->default})";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function edit()
    {
        $sql = "UPDATE `idiomas` 
                  SET `cod` = {$this->cod} ,
                      `titulo` = {$this->titulo},
                      `default` = {$this->default}
                  WHERE `id`= {$this->id} ";
        $query = $this->con->sqlReturn($sql);
        return true;
    }
    public function delete()
    {
        $sql = "DELETE FROM `idiomas` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }


    public function editSingle($atributo, $valor)
    {
        $sql = "UPDATE `idiomas` SET `$atributo` = {$valor} WHERE `cod`={$this->cod}";
        if ($this->con->sqlReturn($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        $sql = "SELECT * FROM `idiomas` WHERE `cod` = {$this->cod} LIMIT 1";
        $area = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($area);
        $row_ = array("data" => $row);
        if (!empty($row_)) {
            return $row_;
        }
    }
    public function list($filter = '', $order = '', $limit = '')
    {
        $array = array();
        $filterSql = is_array($filter) ? "WHERE " . implode(" AND ", $filter) : '';
        $orderSql = ($order != '') ? $order : "id DESC";
        $limitSql = ($limit != '') ? "LIMIT " . $limit : '';
        $sql = "SELECT * FROM `idiomas` $filterSql  ORDER BY $orderSql $limitSql";
        $area = $this->con->sqlReturn($sql);
        if ($area) {
            while ($row = mysqli_fetch_assoc($area)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }
    public function changeDefault($cod)
    {
        $query = array();
        $sqlSet1 = "UPDATE `idiomas` SET `default`= '1' WHERE `cod` = '$cod'";
        $query = $this->con->sqlReturn($sqlSet1);
        $sqlSet0 = "UPDATE `idiomas` SET `default`= '0' WHERE `cod` != '$cod'";
        $query = $this->con->sqlReturn($sqlSet0);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function viewDefault()
    {
        $sql = "SELECT * FROM `idiomas` WHERE `default` = '1' LIMIT 1";
        $area = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($area);
        $row_ = array("data" => $row);
        if (!empty($row_)) {
            return $row_;
        }
    }
    public function recursive($array, $key = [], $escalon_1 = '', $escalon_0 = '')
    {
        if (is_array($array)) {
            foreach ($array as $escalon_2 => $item) {
                $key = !empty($escalon_0) ? [$escalon_0 => [$escalon_1 => [$escalon_2 => $item]]] : [$escalon_1 => [$escalon_2 => $item]];
                if ($escalon_0 != '') {
                    $name = ($escalon_0 . "[" . $escalon_1  . "]" . "[" . $escalon_2 . "]");
                } elseif ($escalon_1 != '') {
                    $name = ($escalon_1  . "[" . $escalon_2 . "]");
                } else {
                    $name = ($escalon_2);
                }
?>
                <div class="ml-20">
                    <?php if (!is_string($item)) { ?>
                        <h3 class="fs-18 text-uppercase"><?= str_replace("_", " ", $escalon_2) ?></h3>
                    <?php } else { ?>
                        <div class="input-group mb-1 fs-14">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-uppercase" id="basic-addon1"><?= str_replace("_", " ", $escalon_2) ?></span>
                            </div>
                            <input type="text" class="form-control" value="<?= $item ?>" name="<?= $name ?>" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    <?php }   ?>
                    <?php $this->recursive($item, @$key[$escalon_2], $escalon_2, $escalon_1); ?>
                </div>
<?php
            }
        }
    }
}
