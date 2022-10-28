<?php

namespace Clases;

use DateTime;

class MercadoLibre
{
    public $id;
    public $code;
    public $type;
    public $price;
    public $stock;
    public $product;

    protected $token;
    protected $config;
    protected $con;
    protected $f;

    public function __construct()
    {
        $cfg = new Config();
        $tokenML = new TokenML();
        $this->con = new Conexion();
        $this->f = new PublicFunction();
        $this->config = $cfg->viewExportadorMeli();
        $this->token = $tokenML->view();
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


    /*
    TOKEN
    */
    public function checkExpiration()
    {
        if (!empty($_SESSION['access_token'])) {
            $interval = date_diff(
                new DateTime(date('Y-m-d H:i:s')),
                new DateTime(date("Y-m-d H:i:s", $_SESSION['expires_in']))
            );

            if (empty($interval->h)) {
                if ($interval->i <= 10) {
                    $this->refreshToken();
                }
            }
        }
    }

    private function refreshToken()
    {

        $data = json_encode([
            "grant_type" => "refresh_token",
            "client_id" => $this->config['data']['app_id'],
            "client_secret" => $this->config['data']['app_secret'],
            "refresh_token" => $_SESSION['refresh_token']
        ]);

        $result = json_decode(
            $this->f->curl(
                "POST",
                "https://api.mercadolibre.com/oauth/token",
                $data
            ),
            true
        );

        if (isset($result['access_token'])) {
            $expires = strtotime(date("Y-m-d H:i:s")) + $result['expires_in'];
            $_SESSION['access_token'] = $result['access_token'];
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['expires_in'] = $expires;
            $_SESSION['refresh_token'] = $result['refresh_token'];
        } else {
            $this->destroy();
        }
    }

    private function destroy()
    {
        unset($_SESSION['access_token']);
        unset($_SESSION['user_id']);
        unset($_SESSION['expires_in']);
        unset($_SESSION['refresh_token']);
    }

    /*
    CATEGORY
    */

    private function checkCategoryShipping($id)
    {
        $categoryData = json_decode(
            $this->f->curl(
                "GET",
                "https://api.mercadolibre.com/categories/" . $id . "/shipping_preferences",
                ""
            ),
            true
        );

        if (empty($categoryData['dimensions'])) return ["status" => false];

        $from = 2400;
        $to = 1675;
        $dimensions = $categoryData['dimensions']['height'] . "x" . $categoryData['dimensions']['width'] . "x" . $categoryData['dimensions']['length'] . "," . $categoryData['dimensions']['weight'];
        $shipCost = json_decode(
            $this->f->curl(
                "GET",
                "https://api.mercadolibre.com/sites/MLA/shipping_options?zip_code_from=" . $from . "&zip_code_to=" . $to . "&dimensions=" . $dimensions,
                ""
            ),
            true
        );

        if (isset($shipCost['error'])) return ["status" => false];

        $cost = 0;

        foreach ($shipCost['options'] as $shipOptions) {
            if ($shipOptions['cost'] > $cost) $cost = $shipOptions['cost'];
        }

        return ["status" => true, "cost" => $cost];
    }

    /*
    API MELI
    */


    public function create($title, $price, $description, $stock, $images, $type)
    {
        $result = json_decode($this->f->curl("GET", "https://api.mercadolibre.com/sites/MLA/category_predictor/predict?title=" . $this->normalize($title) . "", ""), true);
        $percent = ($type == 'gold_special') ? $this->config["data"]["clasica"] : $this->config["data"]["premium"];
        $price = number_format((($price * $percent) / 100) + $price, 2, ".", "");
        $mode = "me2";
        $cost = 0;
        $shipping = false;
        $shippingData = $this->checkCategoryShipping($result["id"]);

        if ($this->config["data"]["calcular_envio"]) {
            if ($shippingData['status']) {
                if ($price >= 2500) {
                    $shipping = true;
                    $price = $price + ceil($shippingData['cost']);
                    $cost = ceil($shippingData['cost']);
                }
            } else {
                $mode = "not_specified";
            }
        }

        $data = [
            "title" => $title,
            "category_id" => $result["id"],
            "price" => $price,
            "currency_id" => "ARS",
            "available_quantity" => $stock,
            "buying_mode" => "buy_it_now",
            "listing_type_id" => $type,
            "condition" => "new",
            "description" => [
                "plain_text" => $description
            ],
            "tags" => [
                "immediate_payment"
            ],
            "video_id" => '',
            "pictures" => $images,
            "shipping" => [
                "mode" => $mode,
                "local_pick_up" => true,
                "free_shipping" => $shipping,
                "free_methods" => []
            ]
        ];

        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $meli = json_decode($this->f->curl("POST", "https://api.mercadolibre.com/items?access_token=" . $_SESSION["access_token"], $data), true);

        $response = ["status" => false, "data" => "", "error" => "", "price" => $price, "shipment" => $cost, "title" => $title];

        if (isset($meli['error'])) {
            !empty($meli['cause']) ? $response['error'] = $meli['cause'] : $response['error'] = $meli['error'];
        } else {
            $response['status'] = true;
            $response['data'] = $meli;
        }

        return $response;
    }



    public function update($id, $title, $price, $description, $stock, $images, $type, $config)
    {
        $result = json_decode($this->f->curl("", 'https://api.mercadolibre.com/items/' . $id, ''), true);
        $percent = ($type == 'gold_special') ? $this->config["data"]["clasica"] : $this->config["data"]["premium"];
        $price = number_format((($price * $percent) / 100) + $price, 2, ".", "");
        $mode = "me2";
        $cost = 0;
        $shipping = false;
        $shippingData = $this->checkCategoryShipping($result["category_id"]);

        if ($this->config["data"]["calcular_envio"]) {
            if ($shippingData['status']) {
                if ($price >= 2500) {
                    $shipping = true;
                    $price = $price + ceil($shippingData['cost']);
                    $cost = ceil($shippingData['cost']);
                }
            } else {
                $mode = "not_specified";
            }
        }

        ($config['cfg-title']) ? $data["title"] = $title : '';
        ($config['cfg-price']) ? $data["price"] = $price : '';
        ($config['cfg-stock']) ? $data["available_quantity"] = $stock : '';
        ($config['cfg-images']) ? $data["images"] = $images : '';
        ($config['cfg-description']) ? $this->changeDescription($id, $description) : '';

        $data["shipping"] = [
            "mode" => $mode,
            "local_pick_up" => true,
            "free_shipping" => $shipping,
            "free_methods" => []
        ];

        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $meli = json_decode($this->f->curl("PUT", "https://api.mercadolibre.com/items/$id?access_token=" . $_SESSION["access_token"], $data), true);
        $response = ["status" => false, "data" => "", "error" => "", "price" => $price, "shipment" => $cost, "title" => $title];

        if (isset($meli['error'])) {
            !empty($meli['cause']) ? $response['error'] = $meli['cause'] : $response['error'] = $meli['error'];
        } else {
            $response['status'] = true;
            $response['data'] = $meli;
        }

        return $response;
    }

    public function getVariations($id)
    {
        return json_decode($this->f->curl(
            "GET",
            "https://api.mercadolibre.com/items/$id/variations?access_token=" . $_SESSION["access_token"],
            ""
        ));
    }

    public function deleteVariations($id, $variation)
    {
        $this->f->curl(
            "DELETE",
            "https://api.mercadolibre.com/items/$id/variations/$variation?access_token=" . $_SESSION["access_token"],
            ""
        );
    }

    public function changeStatus($id, $status)
    {
        return json_encode($this->f->curl("PUT", "https://api.mercadolibre.com/items/$id?access_token=" . $_SESSION["access_token"], '{ "status":"' . $status . '" }'));
    }

    public function changeDescription($id, $txt)
    {
        return json_encode($this->f->curl("PUT", "https://api.mercadolibre.com/items/$id/description?access_token=" . $_SESSION["access_token"], '{ "plain_text":"' . $txt . '" }'));
    }

    public function shipCost($zip)
    {
        $meli = json_decode(
            $this->f->curl(
                "GET",
                "https://api.mercadolibre.com/sites/MLA/shipping_options?zip_code_from=1059&zip_code_to=" . $zip . "&dimensions=10x10x10,1000",
                ""
            ),
            true
        );

        if (isset($meli['error'])) {
            return ['status' => false, "message" => "Código postal incorrecto"];
        } else {
            return ['status' => true, "data" => $meli];
        }
    }

    //DB


    public function add()
    {
        $sql = "INSERT INTO `mercadolibre`(`code`, `type`, `price`, `stock`, `product`) VALUES ({$this->code},{$this->type},{$this->price},{$this->stock},{$this->product})";
        if (!empty($this->con->sqlReturn($sql))) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `mercadolibre` SET `type`={$this->type},`price`={$this->price},`stock`={$this->stock},`product`={$this->product} WHERE  `code`={$this->code} ";

        if (!empty($this->con->sqlReturn($sql))) {
            return true;
        } else {
            return false;
        }
    }

    public function list($filter, $order, $limit)
    {
        $array = [];
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        !empty($order) ? $orderSql = $order : $orderSql = "id DESC";

        !empty($limit) ? $limitSql = "LIMIT " . $limit : $limitSql = '';

        $sql = "SELECT * FROM `mercadolibre` $filterSql ORDER BY $orderSql $limitSql";
        $producto = $this->con->sqlReturn($sql);
        if ($producto) {
            while ($row = mysqli_fetch_assoc($producto)) {
                $array[]["data"] = $row;
            }
        }
        return $array;
    }
       public function removeMeli()
   {
       $sql = "DELETE FROM `mercadolibre` WHERE  `code`={$this->code} ";
       if (!empty($this->con->sqlReturn($sql))) {
           return true;
       } else {
           return false;
       }
   }

    public function remove()
    {
        $sql = "DELETE FROM `mercadolibre` WHERE  `code`={$this->code} ";
        if (!empty($this->con->sqlReturn($sql))) {
            $this->changeStatus("code", "paused");
            return true;
        } else {
            return false;
        }
    }

    public function removeAll($product)
    {
        foreach ($this->list(["product = '$product'"], "", "") as $product_) {
            $this->changeStatus($product_["data"]["code"], "paused");
            $this->changeStatus($product_["data"]["code"], "closed");
        }
        $sql = "DELETE FROM `mercadolibre` WHERE  `product`='$product'";
        if (!empty($this->con->sqlReturn($sql))) {
            return true;
        } else {
            return false;
        }
    }

    private function normalize($string)
    {
        $utf8 = [
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            //'/ñ/' => 'n',
            //'/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        ];
        $string = preg_replace(array_keys($utf8), array_values($utf8), trim($string));

        $first = '/[^A-Za-z0-9\ ';
        $end = '-]/';

        $string = preg_replace($first . $end, ' ', $string);
        $string = str_replace(" ", "%20", $string);

        return strtolower($string);
    }
}
