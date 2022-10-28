<?php


namespace Clases;


class Combinaciones
{
    //Atributos
    public $id;
    public $cod;
    public $codSubatributo;
    public $codProducto;
    private $detalleCombinacion;
    private $subAtributo;
    private $atributo;
    private $con;
    public $idioma;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->detalleCombinacion = new DetalleCombinaciones();
        $this->atributo = new Atributos();
        $this->subAtributo = new Subatributos();
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
        $sql = "INSERT INTO `combinaciones`(`cod`,`cod_subatributo`,`cod_producto`,`idioma`) 
                  VALUES ({$this->cod},{$this->codSubatributo},{$this->codProducto}, {$this->idioma})";
                  echo $sql;
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `combinaciones` 
                  SET  `cod_subatributo` =  {$this->codSubatributo}  
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
        $sql = "DELETE FROM `combinaciones` WHERE `cod`  = {$this->cod} AND `idioma`= {$this->idioma}";
        $query = $this->con->sqlReturn($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        $sql = "SELECT * FROM `combinaciones` WHERE `cod` = {$this->cod} AND `idioma`= '$this->idioma' ORDER BY id DESC LIMIT 1";
        $atributo = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($atributo);
        $this->detalleCombinacion->set("codCombinacion", $row['cod']);
        $this->detalleCombinacion->idioma = $this->idioma;
        $detailCombination = $this->detalleCombinacion->view();
        $array = array("combination" => $row, "detail" => $detailCombination);
        return $array;
    }


    // public function list($data = [], $group = false, $subatributo = false)
    // {
    //     $array = array();
    //     $sqlFilter = implode(" AND ", $data);
    //     $groupBy = ($group) ? 'GROUP BY cod' : '';
    //     $sql = "SELECT * FROM `combinaciones` WHERE $sqlFilter $groupBy";
    //     $data = $this->con->sqlReturn($sql);
    //     if ($data) {
    //         while ($row = mysqli_fetch_assoc($data)) {
    //             //DETALLES
    //             $this->detalleCombinacion->set("codCombinacion", $row['cod']);
    //             $this->detalleCombinacion->idioma = $row['idioma'];
    //             $detailCombination = $this->detalleCombinacion->view();

    //             //SUBATRIBUTO
    //             $this->subAtributo->set("cod", $row['cod_subatributo']);
    //             $this->subAtributo->idioma = $row['idioma'];

    //             $row["atribute"] = $this->subAtributo->view();

    //             $array[] = array("combination" => $row, "detail" => $detailCombination, "product" => $row["cod_producto"]);
    //         }
    //         return $array;
    //     } else {
    //         return false;
    //     }
    // }

    public function listByProductCod()
    {
        $array = array();
        $sql = "SELECT * FROM `combinaciones` WHERE `cod_producto` = {$this->codProducto} AND `idioma`= '$this->idioma' GROUP BY cod";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $this->set("cod", $row['cod']);
                $combinacionesData = $this->listByCod();
                $this->detalleCombinacion->set("codCombinacion", $row['cod']);
                $this->detalleCombinacion->idioma = $this->idioma;
                $detailCombination = $this->detalleCombinacion->view();
                $array[] = array("combination" => $combinacionesData, "detail" => $detailCombination, "product" => $row["cod_producto"]);
            }
            return $array;
        }
    }

    public function listByCod()
    {
        $array = array();
        $sql = "SELECT * FROM `combinaciones` WHERE `cod`={$this->cod} AND `idioma`= '$this->idioma'";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $this->subAtributo->set("cod", $row['cod_subatributo']);
                $this->subAtributo->idioma = $this->idioma;
                $subData = $this->subAtributo->view();
                $array[] = $subData['data'];
            }
            return $array;
        }
    }


    public function check($atributoPOST, $producto)
    {
        $atributo_post = implode(",", $atributoPOST);
        $sql = "SELECT cod, GROUP_CONCAT(cod_subatributo) as subatributo FROM combinaciones WHERE cod_producto = '$producto'  GROUP BY cod ORDER BY id ASC";
        
        $data = $this->con->sqlReturn($sql);
        $resultValidate = '';
        $combination = '';
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                if ($row["subatributo"] == $atributo_post) {
                    $resultValidate = 1;
                    $combination = $row["cod"];
                }
            }
        }
        return array("result" => $resultValidate, "combination" => $combination);
    }

    public function detail($combinacion)
    {
        $sql = "SELECT combinaciones.cod_producto , detalle_combinaciones.cod_combinacion , detalle_combinaciones.precio , detalle_combinaciones.stock ,
                subatributos.cod_atributo, atributos.value AS atributo , combinaciones.cod_subatributo, subatributos.value AS subatributo
                FROM `combinaciones`
                LEFT JOIN detalle_combinaciones ON detalle_combinaciones.cod_combinacion = combinaciones.cod
                LEFT JOIN subatributos ON subatributos.cod = combinaciones.cod_subatributo
                LEFT JOIN atributos ON atributos.cod = subatributos.cod_atributo
                WHERE combinaciones.cod = '$combinacion'";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }
}
