<?php

namespace Clases;

Class Andreani
{
    private $usuario;
    private $password;
    private $cod;
    private $envioSucursal;
    private $envioDomicilio;
    private $envioUrgente;

    private $config;
    private $f;

    public function __construct()
    {
        $this->funciones = new PublicFunction();
        $this->config = new Config();
    }

    public function getSucursales()
    {
        $url = 'https://api.andreani.com/v1/sucursales';
        $response = $this->funciones->curl("", $url, '');
        $data = json_decode($response, true);
        var_dump($data);
    }

    public function getPronvincias()
    {

    }


    /*
    private $usuario;
    private $password;
    private $cod;
    private $envioSucursal;
    private $envioDomicilio;
    private $envioUrgente;

    private $header;
    private $funcion;
    private $config;

    public function __construct()
    {
        $this->funcion = new PublicFunction();
        $this->config = new Config();
        $andreaniData = $this->config->viewAndreani();
        $this->usuario = $andreaniData['data']['usuario'];
        $this->password = $andreaniData['data']['contraseña'];
        $this->cod = $andreaniData['data']['cod'];
        $this->envioSucursal = $andreaniData['data']['envio_sucursal'];
        $this->envioDomicilio = $andreaniData['data']['envio_domicilio'];
        $this->envioUrgente = $andreaniData['data']['envio_urgente'];

        $this->header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                   <env:Envelope
                       xmlns:env=\"http://www.w3.org/2003/05/soap-envelope\"
                       xmlns:ns1=\"urn:ConsultarSucursales\"
                       xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
                       xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
                       xmlns:ns2=\"http://xml.apache.org/xml-soap\"
                       xmlns:ns3=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\"
                       xmlns:enc=\"http://www.w3.org/2003/05/soap-encoding\">
                       <env:Header>
                           <ns3:Security env:mustUnderstand=\"true\">
                               <ns3:UsernameToken>
                                    <ns3:Username >" . $this->usuario . "</ns3:Username >
                                    <ns3:Password >" . $this->password . "</ns3:Password >
                                </ns3:UsernameToken>
                           </ns3:Security>
                       </env:Header>";
    }

    public function getSucursalByCod($codigo)
    {
        $url = "https://sucursalespreprod.andreani.com/ws?wsdl";

        $xml = "       <env:Body>
                          <ns1:ConsultarSucursales env:encodingStyle=\"http://www.w3.org/2003/05/soap-encoding\">
                              <Consulta xsi:type=\"ns2:Map\">
                                  <item>
                                      <key xsi:type=\"xsd:string\">consulta</key>
                                      <value xsi:type=\"ns2:Map\">
                                          <item>
                                              <key xsi:type=\"xsd:string\">Localidad</key>
                                              <value xsi:type=\"xsd:string\"></value>
                                          </item>
                                          <item>
                                               <key xsi:type=\"xsd:string\">CodigoPostal</key>
                                               <value xsi:type=\"xsd:string\">" . $codigo . "</value>
                                          </item>
                                          <item>
                                              <key xsi:type=\"xsd:string\">Provincia</key>
                                              <value xsi:type=\"xsd:string\"></value>
                                          </item>
                                      </value>
                                  </item>
                              </Consulta>
                          </ns1:ConsultarSucursales>
                       </env:Body>
                   </env:Envelope>";

        $final = $this->header . $xml;
        $da_ = $this->funcion->curlXML("POST", $url, $final);
        $response = simplexml_load_string($da_, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
        $response->registerXPathNamespace('soap-env', 'http://schemas.xmlsoap.org/soap/envelope/');
        $response->registerXPathNamespace('services', 'http://www.sample.com/services');
        $nodes = $response->xpath('/env:Envelope/env:Body/ns1:ConsultarSucursalesResponse/cantidad/ConsultarSucursalesResult/ResultadoConsultarSucursales');

        $sucursal = '';
        foreach ($nodes as $item) {
            foreach ($item as $item_) {
                $sucursal = array(
                    "Descripcion" => $item_->Descripcion->__toString(),
                    "Direccion" => $item_->Direccion->__toString(),
                    "HoradeTrabajo" => $item_->HoradeTrabajo->__toString(),
                    "Latitud" => $item_->Latitud->__toString(),
                    "Longitud" => $item_->Longitud->__toString(),
                    "Mail" => $item_->Mail->__toString(),
                    "Numero" => $item_->Numero->__toString(),
                    "Responsable" => $item_->Responsable->__toString(),
                    "Resumen" => $item_->Resumen->__toString(),
                    "Sucursal" => $item_->Sucursal->__toString(),
                    "Telefono1" => $item_->Telefono1->__toString(),
                    "Telefono2" => $item_->Telefono2->__toString(),
                    "Telefono3" => $item_->Telefono3->__toString(),
                    "TipoSucursal" => $item_->TipoSucursal->__toString(),
                    "TipoTelefono1" => $item_->TipoTelefono1->__toString(),
                    "TipoTelefono2" => $item_->TipoTelefono2->__toString(),
                    "TipoTelefono3" => $item_->TipoTelefono3->__toString(),
                );
            }
        }
        if (!empty($sucursal)) {
            return $result = array("status" => true, "data" => $sucursal);
        } else {
            return $result = array("status" => false);
        }
    }

    public function cotizarEnvio($codigo, $peso, $tipo, $sucursal)
    {
        $url = "https://cotizadorpreprod.andreani.com/ws?wsdl";

        $xml = '    <env:Body>
                        <ns1:CotizarEnvio env:encodingStyle="http://www.w3.org/2003/05/soap-encoding">
                            <cotizacionEnvio xsi:type="ns2:Map">
                                <item>
                                    <key xsi:type="xsd:string">cotizacionEnvio</key>
                                    <value xsi:type="ns2:Map">
                                        <item>
                                            <key xsi:type="xsd:string">CPDestino</key>
                                            <value xsi:type="xsd:string">' . $codigo . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">Cliente</key>
                                            <value xsi:type="xsd:string">' . $this->cod . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">Contrato</key>
                                            <value xsi:type="xsd:string">' . $tipo . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">Peso</key>
                                            <value xsi:type="xsd:int">' . $peso . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">SucursalRetiro</key>
                                            <value xsi:type="xsd:string">' . $sucursal . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">Volumen</key>
                                            <value xsi:type="xsd:int">0</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">ValorDeclarado</key>
                                            <value xsi:type="xsd:int">0</value>
                                        </item>
                                    </value>
                                </item>
                            </cotizacionEnvio>
                        </ns1:CotizarEnvio>
                    </env:Body>
                </env:Envelope>';

        $final = $this->header . $xml;
        $da_ = $this->funcion->curlXML("POST", $url, $final);
        $response = simplexml_load_string($da_, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
        $response->registerXPathNamespace('soap-env', 'http://schemas.xmlsoap.org/soap/envelope/');
        $response->registerXPathNamespace('services', 'http://www.sample.com/services');
        $nodes = $response->xpath('/env:Envelope/env:Body/ns1:CotizarEnvioResponse/cantidad/CotizarEnvioResult');

        $tarifa = '';
        foreach ($nodes as $item) {
            $tarifa = array(
                "CategoriaDistancia" => $item->CategoriaDistancia->__toString(),
                "CategoriaDistanciaId" => $item->CategoriaDistanciaId->__toString(),
                "CategoriaPeso" => $item->CategoriaPeso->__toString(),
                "CategoriaPesoId" => $item->CategoriaPesoId->__toString(),
                "PesoAforado" => $item->PesoAforado->__toString(),
                "Tarifa" => $item->Tarifa->__toString()
            );
        }

        if (!empty($tarifa)) {
            return $result = array("status" => true, "data" => $tarifa);
        } else {
            return $result = array("status" => false);
        }
    }

    public function altaEnvio()
    {
        $url = "https://cotizadorpreprod.andreani.com/ws?wsdl";

        $xml = '    <env:Body>
                        <ns1:CotizarEnvio env:encodingStyle="http://www.w3.org/2003/05/soap-encoding">
                            <cotizacionEnvio xsi:type="ns2:Map">
                                <item>
                                    <key xsi:type="xsd:string">cotizacionEnvio</key>
                                    <value xsi:type="ns2:Map">
                                        <item>
                                            <key xsi:type="xsd:string">CPDestino</key>
                                            <value xsi:type="xsd:string">' . $codigo . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">Cliente</key>
                                            <value xsi:type="xsd:string">' . $this->cod . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">Contrato</key>
                                            <value xsi:type="xsd:string">' . $tipo . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">Peso</key>
                                            <value xsi:type="xsd:int">' . $peso . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">SucursalRetiro</key>
                                            <value xsi:type="xsd:string">' . $sucursal . '</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">Volumen</key>
                                            <value xsi:type="xsd:int">0</value>
                                        </item>
                                        <item>
                                            <key xsi:type="xsd:string">ValorDeclarado</key>
                                            <value xsi:type="xsd:int">0</value>
                                        </item>
                                    </value>
                                </item>
                            </cotizacionEnvio>
                        </ns1:CotizarEnvio>
                    </env:Body>
                </env:Envelope>';

        $final = $this->header . $xml;
        $da_ = $this->funcion->curlXML("POST", $url, $final);
        $response = simplexml_load_string($da_, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
        $response->registerXPathNamespace('soap-env', 'http://schemas.xmlsoap.org/soap/envelope/');
        $response->registerXPathNamespace('services', 'http://www.sample.com/services');
        $nodes = $response->xpath('/env:Envelope/env:Body/ns1:CotizarEnvioResponse/cantidad/CotizarEnvioResult');

        $tarifa = '';
        foreach ($nodes as $item) {
            $tarifa = array(
                "CategoriaDistancia" => $item->CategoriaDistancia->__toString(),
                "CategoriaDistanciaId" => $item->CategoriaDistanciaId->__toString(),
                "CategoriaPeso" => $item->CategoriaPeso->__toString(),
                "CategoriaPesoId" => $item->CategoriaPesoId->__toString(),
                "PesoAforado" => $item->PesoAforado->__toString(),
                "Tarifa" => $item->Tarifa->__toString()
            );
        }

        if (!empty($tarifa)) {
            return $result = array("status" => true, "data" => $tarifa);
        } else {
            return $result = array("status" => false);
        }
    }*/
}