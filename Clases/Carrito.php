<?php

namespace Clases;

class Carrito
{
    //Atributos
    public $id;
    public $titulo;
    public $cantidad;
    public $peso;
    public $precio;
    public $opciones;
    public $link;
    public $stock;
    public $descuento;
    public $producto_cod;
    public $tipo;
    public $precio_inicial;
    public $promo;
    private $con;


    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->productos = new Productos();
        $this->pagos = new Pagos();
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
        if (!isset($_SESSION["carrito"])) {
            $_SESSION["carrito"] = array();
        }


        $add = array('id' => $this->id, 'producto_cod' => $this->producto_cod, 'titulo' => $this->titulo, 'cantidad' => $this->cantidad, 'promo' => $this->promo, 'precio' => $this->precio, 'precio_inicial' => $this->precio_inicial, 'stock' => $this->stock, 'peso' => $this->peso, 'opciones' => $this->opciones, 'link' => $this->link, 'descuento' => $this->descuento, 'tipo' => $this->tipo);

        array_push($_SESSION["carrito"], $add);
        return true;
    }
    public function checkDescuento()
    {
        if ($_SESSION["carrito"]) {
            foreach ($_SESSION["carrito"] as $val) {
                if (isset($val['descuento']) && isset($val['descuento']['cod'])) {
                    return $val;
                }
            }
        }
        return null;
    }

    public function checkEnvio()
    {
        if (isset($_SESSION["carrito"])) {
            foreach ($_SESSION["carrito"] as $key => $val) {
                if ($val['id'] === "Envio-Seleccion") {
                    return $key;
                }
            }
        }
        return null;
    }

    public function checkPago()
    {
        if (isset($_SESSION["carrito"])) {
            foreach ($_SESSION["carrito"] as $key => $val) {
                if ($val['id'] === "Metodo-Pago") {
                    return $key;
                }
            }
        }
        return null;
    }

    public function return()
    {
        if (!isset($_SESSION["carrito"])) {
            $_SESSION["carrito"] = array();
            return $_SESSION["carrito"];
        } else {
            foreach ($_SESSION["carrito"] as $key => $item) {
                if ($item["tipo"] == 'pr') {
                    $producto = $this->productos->list(["filter" => ["cod = '" . $item["id"] . "'"]], $_SESSION['lang'], true);
                    if ($_SESSION["carrito"][$key]["precio"] != $producto["data"]["precio_final"]) {
                        $_SESSION["carrito"][$key]["precio"] = number_format($producto["data"]["precio_final"], 2, ".", "");
                    }
                }
            };
            return $_SESSION["carrito"];
        }
    }

    public function finalWeight()
    {
        $peso = 0;
        foreach ($_SESSION["carrito"] as $carrito) {
            $peso += ($carrito["peso"] * $carrito["cantidad"]);
        }
        return number_format($peso, "2", ".", "");
    }

    public function totalPrice()
    {
        $precio = 0;
        if (isset($_SESSION['carrito'])) {
            for ($i = 0; $i < count($_SESSION["carrito"]); $i++) {
                if ($_SESSION["carrito"][$i]["promo"] != '') {
                    $precio += $_SESSION['carrito'][$i]['precio'] * $_SESSION["carrito"][$i]["promo"];
                } else {
                    $precio += $_SESSION['carrito'][$i]['precio'] * $_SESSION["carrito"][$i]["cantidad"];
                }
            }
        }
        return max(number_format($precio, "2", ".", ""), 0);
    }

    public function precioSinMetodoDePago()
    {
        $precio = 0;
        foreach ($_SESSION["carrito"] as $key => $val) {
            if ($val['tipo'] != "mp") {
                if ($val['promo'] != '') {
                    $precio += ($val["precio"] * $val["promo"]);
                } else {
                    $precio += ($val["precio"] * $val["cantidad"]);
                }
            }
        }
        return number_format($precio, "2", ".", "");
    }
    public function precioSinMetodoDeEnvio()
    {
        $precio = 0;
        foreach ($_SESSION["carrito"] as $key => $val) {
            if ($val['tipo'] != "me") {
                if ($val['promo'] != '') {
                    $precio += ($val["precio"] * $val["promo"]);
                } else {
                    $precio += ($val["precio"] * $val["cantidad"]);
                }
            }
        }
        return number_format($precio, "2", ".", "");
    }
    public function delete($key)
    {
        unset($_SESSION["carrito"][$key]);
        $_SESSION["carrito"] = array_values($_SESSION["carrito"]);
    }
    public function deleteDiscount()
    {
        $lastArray = $_SESSION["carrito"][(count($_SESSION["carrito"]) - 1)];
        if ($lastArray['descuento'] != null) {
            unset($_SESSION["carrito"][(count($_SESSION["carrito"]) - 1)]);
            $_SESSION["carrito"] = array_values($_SESSION["carrito"]);
        }
    }

    public function deleteOnCheck($type)
    {
        $key = $this->checkKeyOnCart($type);
        if (is_numeric($key)) {
            unset($_SESSION["carrito"][$key]);
            $_SESSION["carrito"] = array_values($_SESSION["carrito"]);
        }
    }

    public function edit($key)
    {
        if (array_key_exists($key, $_SESSION["carrito"])) {
            $_SESSION["carrito"][$key]["cantidad"] = $this->cantidad;
            $_SESSION["carrito"][$key]["promo"] = $this->promo;
            $_SESSION["carrito"][$key]["titulo"] = $this->titulo;
        }
    }

    public function destroy()
    {
        unset($_SESSION["carrito"]);
        $_SESSION["carrito"] = array();
    }

    public function checkKeyOnCart($type)
    {
        if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION["carrito"] as $key => $val) {
                if ($val['id'] === $type) {
                    return $key;
                }
            }
        }
        return null;
    }

    public function changePriceByPayment(array $payment)
    {
        $precio = 0;
        if (!empty($payment['data']["monto"])) {
            foreach ($_SESSION["carrito"] as $cartItem) {

                $precio_item = $cartItem["precio"] * $cartItem["cantidad"];

                if ($cartItem["id"] != "Envio-Seleccion") {
                    $titulo = "CARGO +" . $payment['data']['monto'] . "% / " . mb_strtoupper($payment['data']["titulo"]);
                    if ($payment['data']["monto"] != 0 && $payment['data']["monto"] > 0) {
                        if ($cartItem["precio"] != $cartItem["precio_inicial"]) {
                            if ($payment["data"]["acumular"] == 1) $precio += (($precio_item * abs($payment['data']["monto"])) / 100);
                        } else {
                            $precio += (($precio_item * abs($payment['data']["monto"])) / 100);
                        }
                    } else {
                        $titulo = "DESCUENTO " . $payment['data']['monto'] . "% / " . mb_strtoupper($payment['data']["titulo"]);
                        if ($cartItem["precio"] != $cartItem["precio_inicial"]) {
                            if ($payment["data"]["acumular"] == 1) $precio -= (($precio_item * abs($payment['data']["monto"])) / 100);
                        } else {
                            $precio -= (($precio_item * abs($payment['data']["monto"])) / 100);
                        }
                    }
                }
            }
        } else {
            $titulo = mb_strtoupper($payment['data']["titulo"]);
            $totalCarrito = $this->precioSinMetodoDeEnvio();
            $precio = (($totalCarrito * $payment['data']["monto"]) / 100);
        }
        $opciones =  [
            "texto" => '',
            "subatributos" => '',
            "combinacion" => ['cod_combinacion' => '', 'id' => '', 'idioma' => '', 'mayorista' => '', 'precio' => '', 'stock' => '']
        ];
        $this->set("id", "Metodo-Pago");
        $this->set("titulo", $titulo);
        $this->set("cantidad", 1);
        $this->set("precio", $precio);
        $this->set("opciones", $opciones);
        $this->add();
    }


    public function checkPaymentsLimits()
    {
        $returnData = false;
        $final_price = $this->precioSinMetodoDePago();
        $data = $this->pagos->list(["minimo <=  $final_price"], "", "", $_SESSION["lang"]);
        if (!empty($data)) {
            foreach ($data as $item) {
                if (floatval($item["data"]["minimo"]) >= floatval($final_price)) {
                    $returnData = false;
                } else {
                    $returnData = true;
                }
            }
        }
        return $returnData;
    }

    public function checkMiniumLimits()
    {
        $data = $this->pagos->list(['estado = 1'], "minimo ASC", "1", $_SESSION["lang"]);
        return ($data) ? $data[0] : ["data" => ["minimo" => 0]];
    }


    public function checkPriceOnPayments($pago)
    {
        $precio = 0;
        if ($pago['data']["monto"] > 0) {
            $totalCarrito = $this->precioSinMetodoDeEnvio();
            $precio = (($totalCarrito * $pago['data']["monto"]) / 100) + $totalCarrito;
        } else {
            foreach ($_SESSION["carrito"] as $cartItem) {
                if ($cartItem["promo"] != '') {
                    $precio_item = $cartItem["precio"] * $cartItem["promo"];
                } else {
                    $precio_item = $cartItem["precio"] * $cartItem["cantidad"];
                }
                if ($cartItem["id"] != "Envio-Seleccion") {
                    if ($cartItem["precio"] != $cartItem["precio_inicial"]) {
                        if ($pago["data"]["acumular"] == 1) {
                            $precio += ($precio_item) - (($precio_item * abs($pago['data']["monto"])) / 100);
                        } else {
                            $precio += $precio_item;
                        }
                    } else {
                        $precio += ($precio_item) - (($precio_item * abs($pago['data']["monto"])) / 100);
                    }
                } else {
                    $precio += $precio_item;
                }
            }
        }
        if (!empty($pago['data']['entrega'])) {
            $precio = (($pago['data']['entrega'] * $precio) / 100);
        }
        return number_format($precio, "2", ".", "");
    }
}
