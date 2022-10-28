<?php

namespace Clases;

use Clases\Config;

class Decidir
{
    private $publicKey;
    private $privateKey;

    public function __construct()
    {
        $this->config = new Config();
        $this->config->set("id", 5);
        $decidir = $this->config->viewPayment();
        if ($_ENV["DEVELOPMENT"] == 1) {
            $this->publicKey = "4ae76f00234843d1af5994ed4674fd76";
            $this->privateKey = "3891f691dc4f40b6941a25a68d17c7f4";
        } else {
            $this->publicKey = $decidir['data']['variable1'];
            $this->privateKey = $decidir['data']['variable2'];
        }
    }
    private function curl($key, $http, $url, $data = '')
    {
        if ($key == "public") $key = $this->publicKey;
        if ($key == "private") $key = $this->privateKey;
        if ($key == $this->publicKey || $key == $this->privateKey) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $http,
                CURLOPT_POSTFIELDS => (!empty($data)) ? json_encode($data) : '{}',
                CURLOPT_HTTPHEADER => array(
                    "apikey: $key",
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $err;
            } else {
                return $response;
            }
        }
    }
    public function getPaymentMethod($type = 'all')
    {
        if ($_ENV["DEVELOPMENT"] == 1) {
            return $this->curl("public", "GET", "https://developers.decidir.com/api/v2/payment-methods/1");
        } else {
            return $this->curl("public", "GET", "https://live.decidir.com/api/v2/payment-methods/1");
        }
    }

    public function getPaymentList()
    {
        if ($_ENV["DEVELOPMENT"] == 1) {
            return $this->curl("private", "GET", "https://developers.decidir.com/api/v2/payments");
        } else {
            return $this->curl("private", "GET", "https://live.decidir.com/api/v2/payments");
        }
    }
    public function getPaymentToken($card)
    {
        if ($_ENV["DEVELOPMENT"] == 1) {
            return $this->curl("public", "POST", "https://developers.decidir.com/api/v2/tokens", $card);
        } else {
            return $this->curl("public", "POST", "https://live.decidir.com/api/v2/tokens", $card);
        }
    }
    public function processPayment($codPedido, $getPaymentMethodId, $token, $card, $price, $installments, $mensaje)
    {

        if (strpos(",", $price) != false || strpos(".", $price) != false) {
            $price = number_format(intval($price), 2, '', '');
        } else {
            $price = $price . "00";
        }
        //"approved" ,"preapporved","review", "rejected"
        $data = [
            "site_transaction_id" => $codPedido,
            "token" => $token,
            "payment_method_id" => intval($getPaymentMethodId),
            "bin" => $card,
            "amount" => intval($price),
            "currency" => "ARS",
            "installments" => intval($installments),
            "description" => $mensaje,
            "payment_type" => "single",
            "sub_payments" => []
        ];
        if ($_ENV["DEVELOPMENT"] == 1) {
            return $this->curl("private", "POST", "https://developers.decidir.com/api/v2/payments", $data);
        } else {
            return $this->curl("private", "POST", "https://live.decidir.com/api/v2/payments", $data);
        }
    }

    public function getInstallmentsForPayment($orderDetail)
    {
        $cuotas = array_filter(
            $orderDetail,
            function ($element) {
                if ($element["tipo"] == "MP") {
                    return $element;
                }
            }
        );
        if (empty($cuotas[array_keys($cuotas)[0]]["cuotas"])) return false;
        switch ($cuotas[array_keys($cuotas)[0]]["cuotas"]) {
            case 3:
                $installments = 13;
                break;
            case 6:
                $installments = 16;
                break;
            case 12:
                $installments = 7;
                break;
            case 18:
                $installments = 8;
                break;
            case 24:
                $installments = 25;
                break;
            case 30:
                $installments = 31;
                break;
        }
        return $installments;
    }
}
