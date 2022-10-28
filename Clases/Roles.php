<?php

namespace Clases;

class Roles
{

    //Atributos
    public $id;
    public $nombre;
    public $permisos;
    public $cod;
    public $editar;
    public $crear;
    public $eliminar;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->f = new PublicFunction();
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql = "INSERT INTO `roles` ( `nombre`,  `cod`,`permisos`,`crear`,`eliminar`,`editar`) VALUES ('{$this->nombre}','{$this->cod}', '{$this->permisos}', '{$this->crear}', '{$this->eliminar}', '{$this->editar}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `roles` WHERE `cod`  = '$this->cod'";
        $query = $this->con->sql($sql);
        return $query;
    }
    public function edit()
    {
        $sql = "UPDATE `roles` 
                  SET nombre =  '{$this->nombre}' ,
                  cod =  '{$this->cod}' ,
                  permisos =  '{$this->permisos}'  ,
                  crear =  '{$this->crear}'  ,
                  editar =  '{$this->editar}'  ,
                  eliminar =  '{$this->eliminar}'  
                  WHERE `id`= {$this->id} ";
        $this->con->sql($sql);
        return true;
    }
    function list($filter, $order, $limit, $groupBy = '')
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
            $orderSql = "ORDER BY id ASC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }

        $sql = "SELECT *  FROM roles $filterSql $groupBy $orderSql $limitSql ";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[]["data"] = [
                    "id" => $row["id"],
                    "nombre" => $row["nombre"],
                    "cod" => $row["cod"],
                    "permisos" => [
                        "id" => $row["permisos"],
                        "crear" =>  $row["crear"],
                        "editar" =>  $row["editar"],
                        "eliminar" =>  $row["eliminar"]
                    ]
                ];
            }
            return $array;
        }
    }


    function listForMenu($filter, $order, $limit)
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
            $orderSql = "ORDER BY orden ASC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }

        $sql = "SELECT menu.* , `roles`.`crear`,`roles`.`editar`,`roles`.`eliminar`,`roles`.`permisos` FROM roles LEFT JOIN menu ON menu.id = roles.permisos $filterSql $orderSql $limitSql";
        $roles = $this->con->sqlReturn($sql);
        if ($roles) {
            while ($row = mysqli_fetch_assoc($roles)) {
                $array["data"][] = $row;
            }
            return $array;
        }
    }
    public function addDevPermissions($area, $titulo, $link, $idioma, $codInit = '')
    {
        $this->menu = new Menu();
        $menuAdd = $this->menu->list(["area = $area", "titulo = $titulo", "link = $link"], str_replace("'", "", $idioma), true);
        if (isset($menuAdd[0]["id"])) {
            $cod = empty($codInit) ? $this->list(["nombre= 'desarrollador'"], "", "1")[0]["data"]["cod"] : $codInit;
            $this->set("nombre", "desarrollador");
            $this->set("cod", "$cod");
            $this->set("permisos", $menuAdd[0]["id"]);
            $this->set("crear", "1");
            $this->set("eliminar", "1");
            $this->set("editar", "1");
            $this->add();
        }
    }
}
