<?php


namespace Clases;


class Checkout
{
    public function __construct()
    {
        $this->con = new Conexion();
        $this->config = new Config();
    }

    public function initial(string $type, string $user)
    {
        //status EMPTY.OPEN,CLOSED
        //type USER,GUEST,NEWER
        //stage-X -> status OPEN,CLOSED
        //stage-1 -> subtype HOME,SPECIAL,API
        $cod = $_SESSION['cod_pedido'];
        $_SESSION['last_cod_pedido'] = $cod;
        $_SESSION['stages'] = array(
            "status" => 'OPEN',
            "type" => $type,
            "user_cod" => $user,
            "cod" => $cod,
            "stage-1" => '',
            "stage-2" => '',
            "stage-3" => ''
        );
    }

    public function user(string $user, string $type)
    {
        if (!empty($_SESSION['stages'])) {
            $_SESSION['stages']['user_cod'] = $user;
            $_SESSION['stages']['type'] = $type;
            return true;
        } else {
            return false;
        }
    }

    public function stage1(array $data)
    {
        //stage-X -> status OPEN,CLOSED
        //stage-1 -> subtype HOME,SPECIAL,API
        $_SESSION['cod_pedido'] = strtoupper(substr(md5(uniqid(rand())), 0, 7));
        if (is_array($data)) {
            $_SESSION['stages']['stage-1'] = array(
                "status" => "CLOSED",
                "type" => "SHIPPING",
                "subtype" => '',
                "cod" => $data['envio'],
                "data" => array(
                    "nombre" => $data['nombre'],
                    "apellido" => $data['apellido'],
                    "dni" => "",
                    "email" => $data['email'],
                    "calle" => $data['direccion'],
                    "numero" => "",
                    "piso" => "",
                    "otros" => "",
                    "pais" => "",
                    "provincia" => $data['provincia'],
                    "localidad" => $data['localidad'],
                    "postal" => "",
                    "telefono" => $data['telefono'],
                    "celular" => $data['celular'],
                    "fecha" => $data['fecha'],
                    "postal" => $data['postal'],
                    "similar" => $data['similar']
                ),
                "api_data" => array(
                    "tracking" => ''
                )
            );
            return true;
        } else {
            return false;
        }
    }

    public function stage2(array $data)
    {
        //stage-X -> status OPEN,CLOSED

        if (is_array($data)) {
            $_SESSION['stages']['stage-2'] = array(
                "status" => "CLOSED",
                "type" => "BILLING",
                "data" => array(
                    "nombre" => $data['nombre'],
                    "apellido" => $data['apellido'],
                    "dni" => $data['dni'],
                    "email" => $data['email'],
                    "calle" => $data['direccion'],
                    "numero" => "",
                    "piso" => "",
                    "otros" => "",
                    "pais" => "",
                    "provincia" => $data['provincia'],
                    "localidad" => $data['localidad'],
                    "postal" => "",
                    "telefono" => $data['telefono'],
                    "celular" => $data['celular'],
                    "factura" => $data['factura']
                )
            );



            return true;
        } else {
            return false;
        }
    }

    public function stage3(string $subtype, array $data)
    {
        //stage-X -> status OPEN,CLOSED
        //subtype NORMAL,API

        if (is_array($data)) {
            $_SESSION['stages']['stage-3'] = array(
                "status" => "CLOSED",
                "type" => "PAYMENT",
                "subtype" => '',
                "cod" => $data['cod'],
                "api_data" => array()
            );

            return true;
        } else {
            return false;
        }
    }

    public function progress()
    {
        if ($_SESSION['stages']['status'] == 'OPEN') {

            if (empty($_SESSION['stages']['stage-1'])) {
                $shipping = '';
            } else {
                $shipping = true;
            }

            if (empty($_SESSION['stages']['stage-2'])) {
                $billing = '';
            } else {
                $billing = true;
            }

            if (empty($_SESSION['stages']['stage-3'])) {
                $payment = '';
            } else {
                $payment = true;
            }

            $response = array(
                "stage-1" => $shipping,
                "stage-2" => $billing,
                "stage-3" => $payment,
                "finished" => false
            );

            return $response;
        } else {
            if ($_SESSION['stages']['status'] == 'CLOSED') {
                $response = array(
                    "stage-1" => true,
                    "stage-2" => true,
                    "stage-3" => true,
                    "finished" => true
                );
                return $response;
            } else {
                $response = array(
                    "stage-1" => '',
                    "stage-2" => '',
                    "stage-3" => '',
                    "finished" => false
                );
                return $response;
            }
        }
    }

    public function destroy()
    {
        if (isset($_SESSION['stages'])) {
            unset($_SESSION['stages']);
        }
    }

    public function close()
    {
        if (isset($_SESSION['stages'])) {
            $_SESSION['stages']['status'] = 'CLOSED';
        }
    }


    public function checkSkip($type)
    {
        if ($type == 1) {
            $skip = $this->config->viewCheckout("minorista", $_SESSION['lang']);
            $link = ($skip['data']['estado'] == 1) ? "checkout/skip-checkout" : "checkout/shipping";
        } else {
            $skip = $this->config->viewCheckout("mayorista", $_SESSION['lang']);
            $link = ($skip['data']['estado'] == 1) ? "checkout/skip-checkout" : "checkout/shipping";
        }

        return $link;
    }

    public function hidePrices()
    {
        $type = "mayorista";
        $ocultar = "";
        if (isset($_SESSION['usuarios']['minorista']) && $_SESSION['usuarios']['minorista'] == 1) {
            $type = "minorista";
        }
        $checkPrecio = $this->config->viewCheckout($type, $_SESSION['lang']);
        if ($checkPrecio['data']['mostrar_precio'] == 1) {
            $ocultar = "hidden";
        }
        return $ocultar;
    }
}
