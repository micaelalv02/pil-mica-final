<?php

namespace Clases;

class Usuarios
{

    //Atributos
    public $id;
    public $cod;
    public $nombre;
    public $apellido;
    public $doc;
    public $email;
    public $password;
    public $direccion;
    public $postal;
    public $localidad;
    public $provincia;
    public $pais;
    public $telefono;
    public $celular;
    public $minorista;
    public $invitado;
    public $descuento;
    public $fecha;
    public $estado;
    public $idioma;

    private $con;
    private $usuariosIp;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->usuariosIp = new UsuariosIp();
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function getAttrWithTitle()
    {
        $data = [];
        $data["cod"] = "Codigo de Usuario";
        $data["doc"] = "DNI";
        $data["nombre"] = "Nombre";
        $data["apellido"] = "Apellido";
        $data["email"] = "Correo Electrónico";
        $data["password"] = "Contraseña";
        $data["telefono"] = "Telefono";
        $data["celular"] = "Celular";
        $data["direccion"] = "Direccion";
        $data["postal"] = "Código postal";
        $data["localidad"] = "Localidad";
        $data["provincia"] = "Provincia";
        $data["pais"] = "Pais";
        $data["minorista"] = "Tipo de Usuario";
        $data["estado"] = "Estado";
        $data["idioma"] = "Idioma";

        return $data;
    }

    public function transformQuery()
    {
        $atributes = array("cod" => $this->cod, "nombre" => $this->nombre, "apellido" => $this->apellido, "doc" => $this->doc, "email" => $this->email, "password" => $this->password, "direccion" => $this->direccion, "postal" => $this->postal, "localidad" => $this->localidad, "provincia" => $this->provincia, "pais" => $this->pais, "telefono" => $this->telefono, "celular" => $this->celular, "minorista" => $this->minorista, "invitado" => $this->invitado, "descuento" => $this->descuento, "fecha" => $this->fecha, "estado" => $this->estado, "idioma" => $this->idioma);

        foreach ($atributes as $name => $value) {
            if (strlen($value)) {
                $valor = "'" . $value . "'";
            } else {
                $valor = "NULL";
            }
            $this->$name = $valor;
        }
    }

    public function hash()
    {
        return hash('sha256', $this->password . SALT);
    }
    public function countContents($filter = [])
    {
        $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' OR ', $filter) : '';
        $sql = "SELECT COUNT(*) as cantidad FROM `usuarios` $filterSql ";
        $query = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($query);
        return $row["cantidad"];
    }
    public function paginador($url, $filter, $limit, $page = 1, $range = 1, $friendly = true)
    {
        $filterCount = [];
        $filterCount = isset($filter['status']) ? $filter['status'] : $filter;
        $filterCount = isset($filter['date']) ? $filter['date'] : $filterCount;
        $separator = ($friendly) ? '/p/' : '&pagina=';

        $count = $this->countContents($filterCount);
        $total = ceil($count / $limit);
        $pre = $page - 1;
        $next = $page + 1;
        $html = "<nav class='pagination-section mt-30'>";
        $html .=  "<ul class='pagination justify-content-center'>";
        if ($pre > 0) {
            $html .= "<li class='page-item' style='height:5px'>";
            $html .= "<a class='page-link' href='" . $url . $separator . "1'><i class='fa fa-angle-double-left'></i></a>";
            $html .=  "</li>";
            $html .=  "<li class='page-item'>";
            $html .=  "<a class='page-link' href='" . $url . $separator . $pre . "'><i class='fa fa-angle-left'></i></a>";
            $html .=  "</li>";
        }
        foreach (range($page - $range, $page + $range) as $i) {
            if ($i > 0 && $i <= $total) {
                $active = ($i == $page) ? 'active' : '';
                $html .=  "<li class='page-item $active'>";
                $html .=  "<a class='page-link' href='" . $url . $separator . $i . "'>$i</a>";
                $html .=  "</li>";
            }
        }
        if ($next <= $total) {
            $html .= "<li class='page-item'>";
            $html .= "<a class='page-link' href='" . $url . $separator . $next . "'><i class='fa fa-angle-right'></i></a>";
            $html .=  "</li>";
            $html .= "<li class='page-item' style='height:5px'>";
            $html .= "<a class='page-link' href='" . $url . $separator . $total . "'><i class='fa fa-angle-double-right'></i></a>";
            $html .=  "</li>";
        }
        $html .=  "</ul>";
        $html .=  "</nav>";
        return $html;
    }
    public function add()
    {
        $validar = $this->validate();
        if (!$validar['status']) {
            if (!empty($this->password)) {
                $this->set("password", hash('sha256', $this->password . SALT));
            }
            $this->transformQuery();
            $sql = "INSERT INTO `usuarios` (`cod`, `nombre`, `apellido`, `doc`, `email`, `password`, `direccion`, `postal`, `localidad`, `provincia`, `pais`, `telefono`, `celular`, `minorista`, `invitado`, `descuento`, `fecha`, `estado`,`admin`, `idioma`) 
                    VALUES ({$this->cod},
                            {$this->nombre},
                            {$this->apellido},
                            {$this->doc},
                            {$this->email},
                            {$this->password},
                            {$this->direccion},
                            {$this->postal},
                            {$this->localidad},
                            {$this->provincia},
                            {$this->pais},
                            {$this->telefono},
                            {$this->celular},
                            {$this->minorista},
                            {$this->invitado},
                            {$this->descuento},
                            {$this->fecha},
                            {$this->estado},
                            {$this->admin},
                            '" . $_SESSION['lang'] . "')";
            $this->con->sql($sql);
            $this->usuariosIp->usuario = $this->cod;
            $this->usuariosIp->set('ip', $_SERVER['REMOTE_ADDR']);
            $this->usuariosIp->set('dispositivo', $_SERVER['HTTP_USER_AGENT']);
            $this->usuariosIp->checkIfExists();
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $usuario = $this->view();
        $validar = $this->validate();
        if (is_array($validar)) {
            if ($validar['data']["email"] == $usuario['data']["email"]) {
                if ($usuario['data']["password"] != $this->password) {
                    $this->set("password", hash('sha256', $this->password . SALT));
                }
                $this->transformQuery();
                $sql = "UPDATE `usuarios` 
                        SET `nombre` = {$this->nombre},
                            `apellido` = {$this->apellido},
                            `doc` = {$this->doc},
                            `email` = {$this->email},
                            `password` = {$this->password},
                            `direccion` = {$this->direccion},
                            `postal` = {$this->postal},
                            `localidad` = {$this->localidad},
                            `provincia` = {$this->provincia},
                            `pais` = {$this->pais},
                            `telefono` = {$this->telefono},
                            `celular` = {$this->celular},
                            `invitado` = {$this->invitado},
                            `minorista` = {$this->minorista},
                            `descuento` = {$this->descuento},
                            `estado` = {$this->estado},
                            `fecha` = {$this->fecha},
                            `idioma` = {$this->idioma}
                        WHERE `cod`={$this->cod}";
                $this->con->sql($sql);

                $this->usuariosIp->set('usuario',  $validar['data']["cod"]);
                $this->usuariosIp->set('ip', $_SERVER['REMOTE_ADDR']);
                $this->usuariosIp->set('dispositivo', $_SERVER['HTTP_USER_AGENT']);
                $this->usuariosIp->checkIfExists();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function editSingle($atributo, $valor)
    {
        $validar = $this->validate();
        $usuario = $this->view();
        if ($atributo == 'password') {
            $valor = hash('sha256', $valor . SALT);
        }
        $sql = "UPDATE `usuarios` SET `$atributo` = '{$valor}' WHERE `cod`='{$this->cod}'";
        if ($validar['status'] == true) {
            if ($validar['data']["email"] == $usuario['data']["email"]) {
                $this->con->sql($sql);
                return true;
            } else {
                return false;
            }
        } else {
            $this->con->sql($sql);
            return true;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM `usuarios`WHERE `cod`= {$this->cod}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function login()
    {
        $response = NULL;
        $this->set("password", hash('sha256', $this->password . SALT));
        $sql = "SELECT * FROM `usuarios` WHERE `email` = '{$this->email}' AND `password`= '{$this->password}' AND invitado = 0";
        $usuarios = $this->con->sqlReturn($sql);

        if (!empty($usuarios)) {
            $row = mysqli_fetch_assoc($usuarios);
            if (!empty($row)) {
                if ($row["estado"] == 1) {
                    $_SESSION["usuarios"] = array(
                        'cod' => $row['cod'],
                        'nombre' => $row['nombre'],
                        'apellido' => $row['apellido'],
                        'doc' => $row['doc'],
                        'email' => $row['email'],
                        'direccion' => $row['direccion'],
                        'localidad' => $row['localidad'],
                        'provincia' => $row['provincia'],
                        'pais' => $row['pais'],
                        'telefono' => $row['telefono'],
                        'minorista' => $row['minorista'],
                        'descuento' => $row['descuento'],
                        'estado' => $row['estado'],
                        'invitado' => $row['invitado'],
                        'fecha' => $row['fecha'],
                        'idioma' => $row['idioma']
                    );
                    $response = array("status" => true);
                    $this->usuariosIp->set('usuario',  $row['cod']);
                    $this->usuariosIp->set('ip', $_SERVER['REMOTE_ADDR']);
                    $this->usuariosIp->set('dispositivo', $_SERVER['HTTP_USER_AGENT']);
                    $this->usuariosIp->checkIfExists();
                } else {
                    $response = array("status" => false, "error" => 1);
                }
            } else {
                $response = array("status" => false, "error" => 2); //contraseña o email incorrecto
            }
        } else {
            $response = array("status" => false, "error" => 3); //error inesperado no debe existir en la base
        }
        return $response;
    }

    public function logout()
    {
        $f = new PublicFunction();
        unset($_SESSION["usuarios"]);
    }

    public function view()
    {
        $sql = "SELECT * FROM `usuarios` WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $usuario = $this->con->sqlReturn($sql);

        if (!empty($usuario)) {
            $row = mysqli_fetch_assoc($usuario);
            $row_ = array("data" => $row);
            return $row_;
        } else {
            return null;
        }
    }

    public function validate()
    {
        if (!empty($this->email)) {
            $sql = "SELECT * FROM `usuarios` WHERE email = '{$this->email}'";
            $usuario = $this->con->sqlReturn($sql);
            $row = mysqli_fetch_assoc($usuario);
            if (!empty($row)) {
                $response = array("status" => true, "data" => $row);
                return $response;
            } else {
                $response = array("status" => false);
                return $response;
            }
        } else {
            $response = array("status" => false);
            return $response;
        }
    }

    function list($filter, $order, $limit)
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

        $sql = "SELECT * FROM `usuarios` $filterSql  ORDER BY $orderSql $limitSql";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = array("data" => $row);
            }
            return $array;
        } else {
            return false;
        }
    }

    //Sessions
    public function viewSession()
    {
        if (!isset($_SESSION["usuarios"])) {
            $_SESSION["usuarios"] = array();
            return $_SESSION["usuarios"];
        } else {
            return $_SESSION["usuarios"];
        }
    }

    public function firstGuestSession()
    {
        $_SESSION["usuarios"] = array(
            'cod' => $this->cod,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'doc' => $this->doc,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'localidad' => $this->localidad,
            'idioma' => $this->idioma,
            'provincia' => $this->provincia,
            'telefono' => $this->telefono,
            'invitado' => $this->invitado,
            'minorista' => 1,
            'descuento' => 0,
            'fecha' => $this->fecha
        );

        $this->transformQuery();
        $sql = "INSERT INTO `usuarios` (`cod`, `nombre`, `apellido`, `doc`, `email`, `direccion`, `postal`, `localidad`, `provincia`, `pais`, `telefono`, `celular`, `minorista`,`invitado`,`descuento`, `fecha`,`estado`,`idioma`) 
                VALUES ({$this->cod},
                        {$this->nombre},
                        {$this->apellido},
                        {$this->doc},
                        {$this->email},
                        {$this->direccion},
                        {$this->postal},
                        {$this->localidad},
                        {$this->provincia},
                        {$this->pais},
                        {$this->telefono},
                        {$this->celular},
                        1,
                        1,
                        0,
                        {$this->fecha},
                        1,
                        {$this->idioma}
                        )";
        $this->con->sql($sql);
        $this->usuariosIp->usuario = $this->cod;
        $this->usuariosIp->set('ip', $_SERVER['REMOTE_ADDR']);
        $this->usuariosIp->set('dispositivo', $_SERVER['HTTP_USER_AGENT']);
        $this->usuariosIp->checkIfExists();
    }

    public function guestSession()
    {
        $_SESSION["usuarios"] = array(
            'cod' => $this->cod,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'doc' => $this->doc,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'localidad' => $this->localidad,
            'provincia' => $this->provincia,
            'telefono' => $this->telefono,
            'invitado' => $this->invitado,
            'idioma' => $this->idioma,
            'minorista' => 1,
            'descuento' => 0,
            'fecha' => $this->fecha
        );
    }

    //Metodos admin


    public function refreshSession($cod)
    {
        if (isset($_SESSION["usuarios"]["invitado"]) && $_SESSION["usuarios"]["invitado"] == 0) {
            $this->set("cod", $cod);
            $_SESSION["usuarios"] = $this->view()["data"];
            unset($_SESSION["usuarios"]["password"]);
            return $_SESSION["usuarios"];
        }
    }
    public function userSession($user)
    {
        $_SESSION["usuarios-ecommerce"] = array('cod' => $user["data"]["cod"], 'nombre' => $user["data"]["nombre"], 'apellido' => $user["data"]["apellido"], 'doc' => $user["data"]["doc"], 'email' => $user["data"]["email"], 'direccion' => $user["data"]["direccion"], 'localidad' => $user["data"]["localidad"], 'provincia' => $user["data"]["provincia"], 'telefono' => $user["data"]["telefono"], 'descuento' => $user["data"]["descuento"], 'idioma' => $user["data"]["idioma"]);
    }

    public function editEstado($atributo, $valor)
    {
        $validar = $this->validate();
        $usuario = $this->view();
        if ($atributo == 'password') {
            $valor = hash('sha256', $valor . SALT);
        }
        $sql = "UPDATE `usuarios` SET `$atributo` = '{$valor}' WHERE `cod`='{$this->cod}'";
        $this->con->sql($sql);
        return true;
    }
    public function userPurchases()
    {
        $array = [];
        $sql = "SELECT `usuarios`.`cod`, COUNT(*) as `cantidad_pedidos`, SUM(`pedidos`.`total`) AS `cantidad_gastada` FROM `pedidos` 
        LEFT JOIN `usuarios` ON `pedidos`.`usuario` = `usuarios`.`cod` 
        LEFT JOIN `estados_pedidos` ON `pedidos`.`estado` = `estados_pedidos`.`id` 
        WHERE `estados_pedidos`.`estado` != '3' AND `estados_pedidos`.`estado` != '0' GROUP BY `usuarios`.`cod` ORDER BY `cantidad_gastada` DESC LIMIT 100";
        $userPurchases = $this->con->sqlReturn($sql);
        if ($userPurchases) {
            while ($row = mysqli_fetch_assoc($userPurchases)) {
                $this->set("cod", $row["cod"]);
                $user = $this->view();
                $array[] = array("data" => $row, "user" => $user);
            }
            return $array;
        } else {
            return false;
        }
    }




    public function userNews()
    {
        $array = [];
        $array['minorista'] = [];
        $array['mayorista'] = [];
        $sql = "SELECT * FROM `usuarios` WHERE `fecha` BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE() ORDER BY `fecha` DESC";

        $userNews = $this->con->sqlReturn($sql);
        if ($userNews) {
            while ($row = mysqli_fetch_assoc($userNews)) {
                if ($row['minorista'] == 1) {
                    $array['minorista'][] = $row;
                } else {
                    $array['mayorista'][] = $row;
                }
            }
            return $array;
        } else {
            return false;
        }
    }
    public function allUsersPuchases($filter = "", $limit = "")
    {
        if (is_int($limit)) $limit = "LIMIT $limit";
        $filterSql = "WHERE `estados_pedidos`.`estado` != '3' AND `estados_pedidos`.`estado` != '0'";
        if (is_array($filter)) {
            $filterSql .=" AND ";
            $filterSql .= implode(" AND ", $filter);
        }

        $array = [];
        $sql = "SELECT `usuarios`.`cod`, COUNT(*) as `cantidad_pedidos`, SUM(`pedidos`.`total`) AS `cantidad_gastada` FROM `pedidos` 
        LEFT JOIN `usuarios` ON `pedidos`.`usuario` = `usuarios`.`cod` 
        LEFT JOIN `estados_pedidos` ON `pedidos`.`estado` = `estados_pedidos`.`id` 
        $filterSql
        GROUP BY `usuarios`.`cod` ORDER BY `cantidad_gastada` DESC $limit";
        $userPurchases = $this->con->sqlReturn($sql);
        if ($userPurchases) {
            while ($row = mysqli_fetch_assoc($userPurchases)) {
                $this->set("cod", $row["cod"]);
                $user = $this->view();
                $array[] = array("data" => $row, "user" => $user);
            }
            return $array;
        } else {
            return false;
        }
    }
}
