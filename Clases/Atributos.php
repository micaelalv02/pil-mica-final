<?php


namespace Clases;


class Atributos
{
    //Atributos
    public $id;
    public $productoCod;
    public $cod;
    public $value;
    public $idioma;

    private $subatributos;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->subatributos = new Subatributos();
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

    public function add()
    {
        $sql = "INSERT INTO `atributos`(`cod`,`cod_producto`, `value`, `idioma`) 
                  VALUES ({$this->cod},{$this->productoCod},{$this->value},{$this->idioma})";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `atributos` 
                  SET  `value` =  {$this->value}  
                  WHERE `cod`= {$this->cod} AND `idioma`= {$this->idioma}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }


    public function delete()
    {
        $sql = "DELETE FROM `atributos` WHERE `cod` = {$this->cod} AND `idioma`= {$this->idioma} ";
        $query = $this->con->sqlReturn($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        $sql = "SELECT * FROM `atributos` WHERE cod = {$this->cod} AND `idioma`= {$this->idioma}  ORDER BY id DESC LIMIT 1";
        $atributo = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($atributo);
        $this->subatributos->set("codAtributo", $row['cod']);
        $this->subatributos->idioma = $this->idioma;
        $subatributes = $this->subatributos->list();
        $row["subatributes"] = $subatributes;
        $array = array("atribute" => $row);
        return $array;
    }

    public function list()
    {
        $array = array();
        $sql = "SELECT * FROM `atributos` WHERE cod_producto={$this->productoCod} AND `idioma`= {$this->idioma} ";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $this->subatributos->set("codAtributo", $row['cod']);
                $this->subatributos->idioma = $this->idioma;
                $subatributes = $this->subatributos->list();
                $row["subatributes"] = $subatributes;
                $array[] = array("atribute" => $row);
            }
            return $array;
        }
    }


    public function checkAtributeOnAddCart($idioma, $atribute = null)
    {
        if ($atribute) {
            $opcion = '| ';
            $atri = array();
            foreach ($atribute as $key => $atrib) {
                $this->set("cod", $key);
                $this->set("idioma", $idioma);
                $titulo = $this->view()['atribute']['value'];
                $this->subatributos->set("cod", $atrib);
                $this->subatributos->set("idioma", $idioma);
                $sub = $this->subatributos->view()['data']['value'];
                $opcion .= "<strong>$titulo: </strong>$sub | ";
                $atri[] = array($titulo => $sub);
            }
            return array("texto" => $opcion, "subatributos" => $atri);
        } else {
            return false;
        }
    }

    public function deleteByProduct($cod_product, $idioma)
    {
        $sql = "DELETE FROM `atributos` WHERE `cod_producto` = '$cod_product' AND `idioma`= '$idioma' ";
        $query = $this->con->sqlReturn($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkAndDelete($cod_product, $idioma)
    {
        $this->set("productoCod", $cod_product);
        $this->set("idioma", $idioma);
        $attr = $this->list();
        if (!empty($attr[0]["atribute"])) {
            foreach ($attr as $attr_) {
                $this->set("cod", $attr_['atribute']['cod']);
                $this->set("idioma", $idioma);
                $this->delete();
            }
        }
    }
}
