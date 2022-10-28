<?php

namespace Clases;

class Descuentos
{
    //Atributos
    public $id;
    public $titulo;
    public $tipo;
    public $monto;
    public $categorias_cod;
    public $subcategorias_cod;
    public $productos_cod;
    public $sector;
    public $fecha_inicio;
    public $fecha_fin;
    public $todosProductos;
    public $todasCategorias;
    public $todasSubcategorias;
    public $cod;
    public $acumular;
    public $idioma;

    private $con;
    private $carrito;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->carrito = new Carrito();
    }

    public function set($atributo, $valor)
    {
        if (($atributo == "tipo" && empty($valor)) || ($atributo == "sector" && empty($valor))) {
            $valor = 0;
        } else {
            if (!empty($valor)) {
                $valor = "'" . $valor . "'";
            } else {
                $valor = "NULL";
            }
        }

        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql = "INSERT INTO `descuentos`(`cod`,`titulo`,`tipo`,`monto`,`categorias_cod`,`subcategorias_cod`, `productos_cod`, `sector`, `fecha_inicio`, `fecha_fin`, `todos_productos`, `todas_categorias`, `todas_subcategorias`,`acumular`, `idioma`) 
                  VALUES ({$this->cod},
                          {$this->titulo},
                          {$this->tipo},
                          {$this->monto},
                          {$this->categorias_cod},
                          {$this->subcategorias_cod},
                          {$this->productos_cod},
                          {$this->sector},
                          {$this->fecha_inicio},
                          {$this->fecha_fin},
                          {$this->todosProductos},
                          {$this->todasCategorias},
                          {$this->todasSubcategorias},
                          {$this->acumular},
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
        $sql = "UPDATE `descuentos` 
                  SET `titulo`={$this->titulo},
                      `cod`={$this->cod},
                      `tipo`={$this->tipo},
                      `monto`={$this->monto},
                      `categorias_cod`={$this->categorias_cod},
                      `subcategorias_cod`={$this->subcategorias_cod},
                      `productos_cod`={$this->productos_cod},
                      `sector`={$this->sector},
                      `fecha_inicio`={$this->fecha_inicio},
                      `fecha_fin`={$this->fecha_fin},
                      `todos_productos`={$this->todosProductos},
                      `todas_categorias`={$this->todasCategorias},
                      `todas_subcategorias`={$this->todasSubcategorias},
                      `acumular`={$this->acumular},
                      `idioma`={$this->idioma}
                  WHERE `id`={$this->id} AND `idioma`={$this->idioma} ";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function refreshCartDescuento($carro, $usuario = NULL)
    {

        $codDescuento = [];
        krsort($carro);
        foreach ($carro as $key => $item) {

            if (!empty($item['descuento']) && $item['descuento']['status'] == true) {
                if (isset($item['descuento']['cod'])) { // BANDERA DE DESCUENTO POR CUPON
                    $codDescuento[] = $item['id'];
                    $this->carrito->delete($key);
                    unset($carro[$key]);
                }
            }
        }

        krsort($codDescuento);
        foreach ($codDescuento as $cod) {
            $this->set("cod", $cod);
            $this->set("idioma", $_SESSION['lang']);
            $this->addCartDescuento($carro, $usuario);
        }
    }

    public function addCartDescuento($carro, $usuario = NULL)
    {
        $price = 0;
        $errorNum = 0;
        $errorMsg = '';
        $descuentoAplicado = false;
        $productosEnDescuento = [];

        $descuentoData = $this->view();
        if ($descuentoData != NULL) {
            $efectivo = (isset($descuentoData['data']['tipo']) && $descuentoData['data']['tipo'] == 0) ? true : false;
            $porcentaje = (isset($descuentoData['data']['tipo']) && $descuentoData['data']['tipo'] == 1) ? true : false;
            $sectorTodos = (isset($descuentoData['data']['sector']) && $descuentoData['data']['sector'] == 0) ? true : false;
            $sectorSinDescuento = (isset($descuentoData['data']['sector']) && $descuentoData['data']['sector'] == 1 && ($usuario == NULL || empty($usuario['data']['descuento']))) ? true : false;
            $sectorConDescuento = (isset($descuentoData['data']['sector']) && $descuentoData['data']['sector'] == 2 && !empty($usuario['data']['descuento'])) ? true : false;
            $fechaInicial = (isset($descuentoData['data']['fecha_inicio']) && strtotime($descuentoData['data']['fecha_inicio']) <= strtotime(strftime("%Y-%m-%d"))) ? true : false;
            $fechaFinal = (isset($descuentoData['data']['fecha_fin']) && strtotime($descuentoData['data']['fecha_fin']) >= strtotime(strftime("%Y-%m-%d"))) ? true : false;
            $todosProductos = (isset($descuentoData['data']['todos_productos']) && $descuentoData['data']['todos_productos'] == 1) ? true : false;
            $todasCategorias = (isset($descuentoData['data']['todas_categorias']) && $descuentoData['data']['todas_categorias'] == 1) ? true : false;
            $todasSubcategorias = (isset($descuentoData['data']['todas_subcategorias']) && $descuentoData['data']['todas_subcategorias'] == 1) ? true : false;

            $arrayProductosDescuento = (isset($descuentoData['data']['productos_cod'])) ? explode(",", $descuentoData['data']['productos_cod']) : '';
            $arrayCategoriasDescuento = (isset($descuentoData['data']['categorias_cod'])) ? explode(",", $descuentoData['data']['categorias_cod']) : '';
            $arraySubcategoriasDescuento = (isset($descuentoData['data']['subcategorias_cod'])) ? explode(",", $descuentoData['data']['subcategorias_cod']) : '';

            if ($sectorTodos || $sectorConDescuento || $sectorSinDescuento) {
                if ($fechaInicial) {
                    if ($fechaFinal) {
                        foreach ($carro as $key => $item) {
                            $descuentoUnidad = 0;
                            $descuentoTotal = 0;
                            //
                            if ($item["id"] == "Envio-Seleccion" || $item["id"] == "Metodo-Pago") continue;
                            if ($descuentoData['data']['acumular'] == "0" && $item['precio'] != $item['precio_inicial']) continue;
                            //ejemplo
                            if ($item["id"] != "Envio-Seleccion" && $item["id"] != "Metodo-Pago") {
                                if ($todosProductos || $todasCategorias || $todasSubcategorias) {
                                    if ($porcentaje) {
                                        $descuentoUnidad = ($item['precio'] * $descuentoData['data']['monto'] / 100);
                                        $descuentoTotal = $item['cantidad'] * $descuentoUnidad;
                                        $price += $descuentoTotal;
                                        $productosEnDescuento[] = ["titulo" => $item['titulo'], "monto" => "-%" . $descuentoData['data']['monto'], "descuentoUnidad" => $descuentoUnidad, "descuentoTotal" => $descuentoTotal];
                                        $detalle = "-%" . $descuentoData['data']['monto'];
                                        $descuentoAplicado = true;
                                    }
                                    if ($efectivo) {
                                        $price = $descuentoData['data']['monto'];
                                        $productosEnDescuento[] = ["titulo" => $item['titulo'], "monto" => "Efectivo"];
                                        $detalle = "efectivo";
                                        $descuentoAplicado = true;
                                    }
                                    if ($descuentoAplicado == true) {
                                        $_SESSION['carrito'][$key]['descuento'] = ["status" => true, "titulo" => $descuentoData['data']['titulo'], "detalle" => $detalle];
                                    }
                                } else {
                                    $existeEnProductos = @in_array($item['id'], $arrayProductosDescuento) ? true : false;
                                    $existeEnCategorias = @in_array($item['id'], $arrayCategoriasDescuento) ? true : false;
                                    $existeEnSubcategorias = @in_array($item['id'], $arraySubcategoriasDescuento) ? true : false;

                                    if ($existeEnProductos || $existeEnCategorias || $existeEnSubcategorias) {
                                        if ($porcentaje) {
                                            $descuentoUnidad = ($item['precio'] * $descuentoData['data']['monto'] / 100);
                                            $descuentoTotal = $item['cantidad'] * $descuentoUnidad;
                                            $price += $descuentoTotal;
                                            $productosEnDescuento[] = ["titulo" => $item['titulo'], "monto" => "-%" . $descuentoData['data']['monto'], "descuentoUnidad" => $descuentoUnidad, "descuentoTotal" => $descuentoTotal];
                                            $detalle = "-%" . $descuentoData['data']['monto'];
                                            $descuentoAplicado = true;
                                        }
                                        if ($efectivo) {
                                            $price = $descuentoData['data']['monto'];
                                            $productosEnDescuento[] = ["titulo" => $item['titulo'], "monto" => "Efectivo"];
                                            $detalle = "efectivo";
                                            $descuentoAplicado = true;
                                        }
                                        if ($descuentoAplicado == true) {
                                            $_SESSION['carrito'][$key]['descuento'] = ["status" => true, "titulo" => $descuentoData['data']['titulo'], "detalle" => $detalle];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $errorNum = 4;
                        $errorMsg = 'Este código ya venció.';
                    }
                } else {
                    $errorNum = 3;
                    $errorMsg = 'Este código aún no esta habilitado.';
                }
            } else {
                $errorNum = 2;
                $errorMsg = 'El código no es válido para este usuario.';
            }
        } else {
            $errorNum = 1;
            $errorMsg = 'El código no existe.';
        }

        if (!$descuentoAplicado) {
            if ($errorNum == 0) {
                $errorNum = 5;
                $errorMsg = 'Este código no aplica para ningún producto del carro.';
            }

            $status = ["applied" => false, "error" => ["errorNum" => $errorNum, "errorMsg" => $errorMsg]];
        } else {
            $this->carrito->set("id", $descuentoData['data']['cod']);
            $this->carrito->set("cantidad", 1);
            $this->carrito->set("titulo", $descuentoData['data']['titulo']);
            $this->carrito->set("precio", $price * (-1));
            $this->carrito->set("tipo", "cp"); //cupon
            $this->carrito->set("descuento", ['status' => true, 'cod' => str_replace("'", '', $this->cod), 'products' => $productosEnDescuento]);
            $this->carrito->add();

            $status = ["applied" => true, "error" => ""];
        }
        return ["status" => $status];
    }

    public function delete()
    {
        $sql = "DELETE FROM `descuentos` WHERE `cod`  = {$this->cod} AND idioma = {$this->idioma}";
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
        $sql = "SELECT * FROM descuentos WHERE cod = {$this->cod} AND idioma = {$this->idioma}";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            $row = mysqli_fetch_assoc($notas);
            $row_ = array("data" => $row);
        }
        return $row_;
    }

    public function list($filter, $order, $limit)
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
        $sql = "SELECT * FROM `descuentos` $filterSql  ORDER BY $orderSql $limitSql";
        $banners = $this->con->sqlReturn($sql);
        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {

                $array[] = array("data" => $row);
            }
        }
        return $array;
    }
    public function getOrdersCod($date)
    {
        $array = [];
        $sql = "SELECT `pedidos`.`cod` AS 'pedido',`detalle_pedidos`.`cod_producto` as 'descuento' FROM `pedidos` INNER JOIN `detalle_pedidos` ON `pedidos`.`cod` = `detalle_pedidos`.`cod` AND `detalle_pedidos`.`tipo` = 'CP' $date GROUP BY `pedidos`.`cod`";
        $orderCod = $this->con->sqlReturn($sql);
        if ($orderCod) {
            while ($row = mysqli_fetch_assoc($orderCod)) {
                $array[$row["descuento"]]["pedidos_cod"][] = $row["pedido"];
            }
        }
        if (!empty($array)) {
            foreach ($array as $key => $item) {
                $array[$key]["pedidos"] = count($array[$key]["pedidos_cod"]);
            }
        }
        return $array;
    }
}
