<?php

namespace Clases;

class ProductosVisitados
{
    //Atributos
    public $id;
    public $producto;
    public $usuario;
    public $fecha;
    public $idioma;

    private $con;
    private $usuariosIp;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->usuariosIp = new UsuariosIp();
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

    public function add($producto, $ip, $idioma)
    {
        $this->usuariosIp->set('usuario', isset($_SESSION["usuarios"]['cod']) ? $_SESSION["usuarios"]['cod'] : NULL);
        $this->usuariosIp->set('ip', $_SERVER['REMOTE_ADDR']);
        $this->usuariosIp->set('dispositivo', $_SERVER['HTTP_USER_AGENT']);
        $this->usuariosIp->checkIfExists();

        $sql = "INSERT INTO `productos_visitados`(`producto`,`usuario_ip`,`fecha`,`idioma`) 
                  VALUES ('$producto','$ip',NOW(),'$idioma')";
        $query = $this->con->sqlReturn($sql);
        return (!empty($query)) ? true : false;
    }
    public function list($filter, $order, $limit)
    {
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT * FROM `productos_visitados` $filterSql ";
        $products = $this->con->sqlReturn($sql);
        $data = [];
        if ($products) {
            while ($row = mysqli_fetch_assoc($products)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function listDistinct($filter)
    {

        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT DISTINCT`productos_visitados`.`producto` FROM `productos_visitados` $filterSql ";
        $products = $this->con->sqlReturn($sql);
        $data = [];
        if ($products) {
            while ($row = mysqli_fetch_assoc($products)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function getAllData($fecha = '', $idioma)
    {
        $array = [];
        $sql = " SELECT `productos_visitados`.* ,`usuarios`.`celular`,`usuarios`.`localidad`,`usuarios`.`telefono`,`usuarios`.`provincia`,`usuarios`.`cod`,`usuarios`.`email`,`usuarios`.`nombre`,`usuarios`.`apellido` FROM `productos_visitados`
       INNER JOIN `usuarios_ip` ON `productos_visitados`.`usuario_ip` = `usuarios_ip`.`ip` 
       INNER JOIN `usuarios` ON `usuarios_ip`.`usuario` = `usuarios`.`cod` WHERE `productos_visitados`.`idioma` = '$idioma' $fecha  ORDER BY `productos_visitados`.`id` DESC";
        $data = $this->con->sqlReturn($sql);
        $array = [];
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $array["data"][$row["producto"] . "|" . $row["idioma"]][]["usuario"] = ["cod" => $row["cod"], "ip" => $row["usuario_ip"], "visita_producto" => $row["fecha"], "producto_idioma" => $row["idioma"], "email" => $row["email"], "telefono" => $row["telefono"], "localidad" => $row["localidad"], "provincia" => $row["provincia"], "nombre" => $row["nombre"], "apellido" => $row["apellido"]];
            }
        }
        return $array;
    }

    public function countBy($attr, $filter, $groupBy)
    {
        if (!empty($groupBy)) $groupBy = "GROUP BY $groupBy";
        if (!empty($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT $attr FROM `productos_visitados` $filterSql $groupBy";
        $products = $this->con->sqlReturn($sql);
        $data = [];
        if ($products) {
            while ($row = mysqli_fetch_assoc($products)) {
                $data[] = $row;
            }
        }
        return count($data);
    }
}
