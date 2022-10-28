<?php


namespace Clases;


class DetalleCombinaciones
{
    //Atributos
    public $id;
    public $codCombinacion;
    public $precio;
    public $stock;
    public $mayorista;
    public $idioma;

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

    public function add()
    {
        $sql = "INSERT INTO `detalle_combinaciones`(`cod_combinacion`,`precio`,`stock`,`mayorista`,`idioma`) 
                  VALUES ({$this->codCombinacion},{$this->precio},{$this->stock},{$this->mayorista},{$this->idioma})";
        $query = $this->con->sqlReturn($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `detalle_combinaciones` 
                  SET  `precio` =  {$this->precio},
                       `stock` = {$this->stock},    
                       `mayorista` = {$this->mayorista},
                       `idioma` = {$this->idioma}
                  WHERE `cod_combinacion`= {$this->codCombinacion} AND `idioma`= {$this->idioma}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function editSingle($atributo, $valor)
    {
        $sql = "UPDATE `detalle_combinaciones` SET `$atributo` = {$valor} WHERE `cod_combinacion`={$this->codCombinacion} AND `idioma`= {$this->idioma}";
        $this->con->sql($sql);
    }

    public function delete()
    {
        $sql = "DELETE FROM `detalle_combinaciones` WHERE `cod_combinacion`  = {$this->codCombinacion} AND `idioma`= {$this->idioma}";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        $idioma = str_replace("'", '', $this->idioma);
        $sql = "SELECT * FROM `detalle_combinaciones` WHERE  `cod_combinacion` = {$this->codCombinacion} AND `idioma`= '$idioma' LIMIT 1";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        if ($row) {
            $array = $row;
            return $array;
        } else {
            return false;
        }
    }

    public function list()
    {
        $array = array();
        $sql = "SELECT * FROM `detalle_combinaciones` WHERE `cod_combinacion` = {$this->codCombinacion} AND `idioma`= {$this->idioma}";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $array[] = $row;
            }
            return $array;
        }
    }


    public function checkOnAddCart($cod, $atributeOptions)
    {
        $this->set("codCombinacion", $cod);
        $detailCombination = $this->view();
        if (!empty($detailCombination)) {
            $precio = $detailCombination['precio'];
            if (!empty($_SESSION['usuarios'])) {
                if ($_SESSION['usuarios']['invitado'] != 1 || $_SESSION["usuarios"]["minorista"] == 1) {
                    $precio = $detailCombination['precio'];
                } else {
                    if (!empty($detailCombination['mayorista'])) {
                        $precio = $detailCombination['mayorista'];
                    }
                }
            }
            $opciones = array("texto" => $atributeOptions, "combinacion" => $detailCombination);
            return array("status" => true, "precio" => $precio, "stock" => $detailCombination["stock"], "opciones" => $opciones);
        } else {
            return array("status" => false, "error" => "Ocurrió un error, intente nuevamente.");
        }
    }
}
