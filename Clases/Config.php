<?php

namespace Clases;

class Config
{
    ///Atributos
    public $id;
    ///Contacto
    public $whatsapp;
    public $messenger;
    public $telefono;
    public $email;
    public $domicilio;
    public $localidad;
    public $provincia;
    public $pais;
    ///Email
    public $remitente;
    public $smtp;
    public $smtp_secure;
    public $puerto;
    public $email_;
    public $password;
    ///Marketing
    public $googleDataStudioId;
    public $googleAnalytics;
    public $hubspot;
    public $mailrelay;
    public $onesignal;
    public $facebookPixel;
    public $facebookAccessToken;
    ///MercadoLibre
    public $app_id;
    public $app_secret;
    //Andreani
    public $usuario;
    public $contrasenia;
    public $codCliente;
    public $envioSucursal;
    public $envioDomicilio;
    public $envioUrgente;
    ///Pago
    public $variable1;
    public $variable2;
    public $variable3;
    public $cod;
    ///Redes
    public $facebook;
    public $twitter;
    public $instagram;
    public $linkedin;
    public $youtube;
    public $googleplus;
    ///Captcha
    public $captcha_key;
    public $captcha_secret;
    ///Header
    public $content_header;

    ///Exportador Mercadolibre
    public $clasica;
    public $premium;
    public $calcular_envio;
    //Hubspot
    public $api_key;

    //Impuestos
    public $codImpuesto;
    public $valor;
    public $tipoImpuesto;


    ///Checkout
    public $tipo;
    public $estado;
    public $envio;
    public $pago;
    public $idioma;
    public $mostrar_precio;


    private $con;
    private $pagos;


    //Arrays
    public $meli;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->meli = $this->viewMercadoLibre();
        $this->pagos = new Pagos();
        $this->envios = new Envios();
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

    public function addContact()
    {
        $contactoData = $this->viewContact();
        if (is_array($contactoData['data'])) {
            $sql = "UPDATE `_cfg_contacto` 
                SET whatsapp = {$this->whatsapp},
                    messenger = {$this->messenger},
                    telefono = {$this->telefono},
                    email = {$this->email},
                    domicilio = {$this->domicilio},
                    localidad = {$this->localidad},
                    provincia = {$this->provincia},
                    pais = {$this->pais}";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_contacto`(`whatsapp`,`messenger`, `telefono`, `email`, `domicilio`,`localidad`,`provincia`,`pais`) 
                VALUES ({$this->whatsapp},
                        {$this->messenger},
                        {$this->telefono},
                        {$this->email},
                        {$this->domicilio},
                        {$this->localidad},
                        {$this->provincia},
                        {$this->pais})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewContact()
    {
        $sql = "SELECT * FROM `_cfg_contacto`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function addEmail()
    {
        $emailData = $this->viewEmail();
        if (is_array($emailData['data'])) {
            $sql = "UPDATE `_cfg_email` 
                SET remitente = {$this->remitente},
                    smtp = {$this->smtp},
                    smtp_secure = {$this->smtp_secure},
                    puerto = {$this->puerto},
                    email = {$this->email_},
                    password = {$this->password}";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_email`(`remitente`, `smtp`,`smtp_secure`, `puerto`, `email`,`password`) 
                VALUES ({$this->remitente},
                        {$this->smtp},
                        {$this->smtp_secure},
                        {$this->puerto},
                        {$this->email_},
                        {$this->password})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewEmail()
    {
        $sql = "SELECT * FROM `_cfg_email`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function addMarketing()
    {
        $marketingData = $this->viewMarketing();
        if (is_array($marketingData['data'])) {
            $sql = "UPDATE `_cfg_marketing` 
                SET google_data_studio_id = {$this->googleDataStudioId},
                    google_analytics = {$this->googleAnalytics},
                    hubspot = {$this->hubspot},
                    mailrelay = {$this->mailrelay},
                    onesignal = {$this->onesignal},
                    facebook_pixel = {$this->facebookPixel},
                    facebook_access_token = {$this->facebookAccessToken}";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_marketing`(`google_data_studio_id`, `google_analytics`, `hubspot`, `mailrelay`,`onesignal`,`facebook_pixel`,`facebook_access_token`) 
                VALUES ({$this->googleDataStudioId},
                        {$this->googleAnalytics},
                        {$this->hubspot},
                        {$this->mailrelay},
                        {$this->facebookPixel},
                        {$this->facebookAccessToken},
                        {$this->onesignal})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewMarketing()
    {
        $sql = "SELECT * FROM `_cfg_marketing`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function addMercadoLibre()
    {
        $mercadoLibreData = $this->viewMercadoLibre();
        if (is_array($mercadoLibreData['data'])) {
            $sql = "UPDATE `_cfg_mercadolibre` 
                SET app_id = {$this->app_id},
                    app_secret = {$this->app_secret}";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_mercadolibre`(`app_id`, `app_secret`) 
                VALUES ({$this->app_id},
                        {$this->app_secret})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewMercadoLibre()
    {
        $sql = "SELECT * FROM `_cfg_mercadolibre`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }
    public function viewHubspot()
    {
        $sql = "SELECT * FROM `_cfg_hubspot`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }
    public function addHubspot()
    {
        $hubspotData = $this->viewHubspot();
        if (is_array($hubspotData['data'])) {
            $sql = "UPDATE `_cfg_hubspot` 
                SET api_key = {$this->api_key}";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_hubspot`(`api_key`) 
                VALUES ({$this->api_key})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }
    public function updatePayment()
    {
        $sql = "UPDATE `_cfg_pagos` 
                SET variable1 = {$this->variable1},
                    variable2 = {$this->variable2},
                    variable3 = {$this->variable3}
                WHERE id= {$this->id}";
        $query = $this->con->sql($sql);

        $payments = $this->pagos->list(array("tipo=" . $this->id), '', '', $_SESSION['lang']);
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $this->pagos->set("cod", $payment['data']['cod']);
                $this->pagos->set("estado", "0");
                $this->pagos->changeState();
            }
        }

        if (!empty($query)) {
            return true;
        } else {
            return "No se pudo modificar.";
        }
    }

    public function viewPayment()
    {
        $sql = "SELECT * FROM `_cfg_pagos`  WHERE id= {$this->id}";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function listPayment()
    {
        $array = array();
        $sql = "SELECT * FROM `_cfg_pagos`  WHERE variable1!= '' ";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }

    public function addSocial()
    {
        $socialData = $this->viewSocial();
        if (is_array($socialData['data'])) {
            $sql = "UPDATE `_cfg_redes` 
                SET facebook = {$this->facebook},
                    twitter = {$this->twitter},
                    instagram = {$this->instagram},
                    linkedin = {$this->linkedin},
                    youtube = {$this->youtube},
                    googleplus = {$this->googleplus}";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_redes`(`facebook`,`twitter`, `instagram`, `linkedin`, `youtube`,`googleplus`) 
                VALUES ({$this->facebook},
                        {$this->twitter},
                        {$this->instagram},
                        {$this->linkedin},
                        {$this->youtube},
                        {$this->googleplus})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewSocial()
    {
        $sql = "SELECT * FROM `_cfg_redes`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function addCaptcha()
    {
        $captchaData = $this->viewCaptcha();
        if (is_array($captchaData['data'])) {
            $sql = "UPDATE `_cfg_captcha` 
                SET captcha_key = {$this->captcha_key},
                    captcha_secret = {$this->captcha_secret}";
            $query = $this->con->sql($sql);
            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_captcha`(`captcha_key`, `captcha_secret`) 
                VALUES ({$this->captcha_key},
                        {$this->captcha_secret})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewCaptcha()
    {
        $sql = "SELECT * FROM `_cfg_captcha`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function addConfigHeader()
    {
        $configHeader = $this->viewConfigHeader();
        if (is_array($configHeader['data'])) {
            $sql = "UPDATE `_cfg_configheader` SET content_header = {$this->content_header}";
            $query = $this->con->sql($sql);
            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_configheader`(`content_header`) VALUES ({$this->content_header})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewConfigHeader()
    {
        $sql = "SELECT * FROM `_cfg_configheader`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function addAndreani()
    {
        $configAndreani = $this->viewAndreani();
        if (is_array($configAndreani['data'])) {
            $sql = "UPDATE `_cfg_andreani` 
                    SET usuario = {$this->usuario},
                        contraseña = {$this->contrasenia},
                        cod = {$this->codCliente},
                        envio_sucursal = {$this->envioSucursal},
                        envio_domicilio = {$this->envioDomicilio},
                        envio_urgente = {$this->envioUrgente}";
            $query = $this->con->sql($sql);
            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_andreani`(`usuario,contraseña,cod,envio_sucursal,envio_domicilio,envio_urgente`) 
                    VALUES ({$this->usuario},
                            {$this->contrasenia},
                            {$this->codCliente},
                            {$this->envioSucursal},
                            {$this->envioDomicilio},
                            {$this->envioUrgente})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewAndreani()
    {
        $sql = "SELECT * FROM `_cfg_andreani`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function addExportadorMeli()
    {
        $exportadorMeliData = $this->viewExportadorMeli();
        if (is_array($exportadorMeliData['data'])) {
            $sql = "UPDATE `_cfg_exportador_meli` 
                SET clasica = {$this->clasica},
                    premium = {$this->premium},
                    link_json = {$this->link_json},
                    carpeta_img = {$this->carpeta_img},
                    calcular_envio =  $this->calcular_envio ";
            $query = $this->con->sqlReturn($sql);
            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_exportador_meli`(`clasica`, `premium`, `link_json`, `carpeta_img`, `calcular_envio`) 
                VALUES ({$this->clasica},
                        {$this->premium},
                        {$this->link_json},
                        {$this->carpeta_img},
                        {$this->calcular_envio})";
            $query = $this->con->sqlReturn($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewExportadorMeli()
    {
        $sql = "SELECT * FROM `_cfg_exportador_meli`";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }


    public function addCheckout($type,$idioma)
    {
        $checkoutData = $this->viewCheckout($type,$idioma);
        if (is_array($checkoutData['data'])) {
            $sql = "UPDATE `_cfg_checkout` 
                SET tipo = {$this->tipo},
                    estado = {$this->estado},
                    mostrar_precio =  {$this->mostrar_precio},
                    envio = {$this->envio},
                    pago =  {$this->pago},
                    idioma =  {$this->idioma}
                    WHERE `id`={$this->id} AND `idioma`={$this->idioma}";
            $query = $this->con->sqlReturn($sql);
            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_checkout`(`tipo`, `estado`, `mostrar_precio`, `envio`, `pago`,`idioma`) 
                VALUES ({$this->tipo},
                        {$this->estado},
                        {$this->mostrar_precio},
                        {$this->envio},
                        {$this->pago},
                        {$this->idioma})";
            $query = $this->con->sqlReturn($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }

    public function viewCheckout($type,$idioma)
    {
        $this->set("tipo", $type);
        $this->set("idioma", $idioma);
        $sql = "SELECT * FROM `_cfg_checkout` WHERE `tipo` = $this->tipo AND `idioma` = $this->idioma";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        //seters envios
        $this->envios->set("cod", $row['envio']);
        $this->envios->set("idioma", $idioma);
        $envio = $this->envios->view();
        //seters pagos
        $this->pagos->set("cod", $row['pago']);
        $this->pagos->set("idioma", $idioma);
        $pago = $this->pagos->view();

        $row_ = array("data" => $row, "envio" => $envio, "pago" => $pago);
        return $row_;
    }

    function listCheckout($idioma)
    {
        $row_ = [];
        $sql = "SELECT * FROM `_cfg_checkout` WHERE `idioma` = '$idioma'";
        $checkout = $this->con->sqlReturn($sql);
        if ($checkout) {
            while ($row = mysqli_fetch_assoc($checkout)) {
                $this->envios->set("cod", $row['envio']);
                $this->envios->set("idioma", $idioma);
                $envio = $this->envios->view();
                $this->pagos->set("cod", $row['pago']);
                $this->pagos->set("idioma", $idioma);
                $pago = $this->pagos->view();
                $row_[] = array("data" => $row, "envio" => $envio, "pago" => $pago);
            }
        }
        return $row_;
    }
    function addTaxFactura()
    {
        $facturaData = $this->viewTaxFactura();
        if (is_array($facturaData['data'])) {
            $sql = "UPDATE `_cfg_impuestos` 
                SET cod = {$this->codImpuesto},
                    valor = {$this->valor},
                    tipo = {$this->tipoImpuesto}";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo modificar.";
            }
        } else {
            $sql = "INSERT INTO `_cfg_impuestos`(`cod`, `valor`,`tipo`) 
                VALUES ({$this->codImpuesto},
                        {$this->valor},
                        {$this->tipoImpuesto})";
            $query = $this->con->sql($sql);

            if (!empty($query)) {
                return true;
            } else {
                return "No se pudo agregar.";
            }
        }
    }
    function viewTaxFactura()
    {
        $row_ = [];
        $sql = "SELECT * FROM `_cfg_impuestos` WHERE `cod` = 'factura'";
        $data = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($data);
        $row_ = array("data" => $row);
        return $row_;
    }
}
