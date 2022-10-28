<?php

namespace Clases;


class tokenML
{
    private $accessToken;
    private $refreshToken;
    private $expireIn;
    private $secretRequestId;

    protected $con;

    public function __construct()
    {
        $this->con = new Conexion();

    }

    public function truncate(){
        $sql = "TRUNCATE token_ml";
        $this->con->sqlReturn($sql);
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

    public function view()
    {
        $sql = "SELECT * FROM token_ml";
        $token = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($token);
        $row_ = array("data" => $row);
        return $row_;
    }

    public function add()
    {
        $this->truncate();
        $sql = "INSERT INTO `token_ml`(`access_token`, `refresh_token`,`expire_in`,`secret_request_id`) 
                VALUES ({$this->accessToken},
                        {$this->refreshToken},
                        {$this->expireIn},
                        {$this->secretRequestId})";

        if (!empty($this->con->sqlReturn($sql))) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `token_ml` 
                SET `access_token` = {$this->accessToken},
                    `refresh_token` = {$this->refreshToken},
                    `expire_in` = {$this->expireIn}";

        if (!empty($this->con->sqlReturn($sql))) {
            return true;
        } else {
            return false;
        }
    }
}