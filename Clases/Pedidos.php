<?php

namespace Clases;

class Pedidos
{

    //Atributos
    public $id;
    public $cod;
    public $producto;
    public $cantidad;
    public $entrega;
    public $total;
    public $estado;
    public $usuario;
    public $detalle;
    public $observacion;
    public $fecha;
    public $visto;
    public $idioma;

    private $con;
    private $detallePedido;
    private $user;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->detallePedido = new DetallePedidos();
        $this->estado_pedido = new EstadosPedidos();
        $this->user = new Usuarios();
        $this->f = new PublicFunction();
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

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql = "INSERT INTO `pedidos`(`cod`,`entrega`,`total`, `estado`,`pago`, `usuario`, `detalle`, `observacion`, `fecha`, `visto`, `idioma`) 
                  VALUES ({$this->cod},
                          {$this->total},                          
                          {$this->entrega},
                          {$this->estado},
                          {$this->pago},
                          {$this->usuario},
                          {$this->detalle},
                          {$this->observacion},
                          {$this->fecha},
                          {$this->visto},
                          {$this->idioma})";
        $query = $this->con->sql($sql);
        return true;
    }

    public function edit()
    {
        $sql = "UPDATE `pedidos` 
                     SET  entrega =  {$this->entrega},
                     total =  {$this->total},
                     estado = {$this->estado},           
                     pago = {$this->pago},           
                     usuario = {$this->usuario},           
                     detalle = {$this->detalle},           
                     observacion = {$this->observacion},           
                     fecha = {$this->fecha},           
                     visto = {$this->visto},
                     idioma = {$this->idioma}         
                  WHERE `cod`= {$this->cod} AND `idioma` ={$this->idioma}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function editSingle($atributo, $valor)
    {
        $sql = "UPDATE `pedidos` SET `$atributo` = '{$valor}' WHERE `cod`={$this->cod}";
        $query = $this->con->sqlReturn($sql);
        return $query;
    }
    public function changeState()
    {
        $sql = "UPDATE `pedidos` SET `estado`={$this->estado} WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function changeValue($key)
    {
        $sql = "UPDATE `pedidos` SET `$key`={$this->$key} WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);
        return $query;
    }


    public function delete()
    {
        $sql = "DELETE FROM `pedidos` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sql($sql);
        $this->detallePedido->delete($this->cod);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `pedidos` WHERE cod = {$this->cod} ORDER BY id DESC";
        $pedidos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($pedidos);
        if ($row) {
            $details = $this->detallePedido->list($this->cod);
            $this->estado_pedido->set("id", $_SESSION['lang']);
            $estado = $this->estado_pedido->view($row["estado"]);
            $this->user->set("cod", $row['usuario']);
            $user = $this->user->view();
            $data = ["data" => $row, "user" => $user, "detail" => $details, "estados" => $estado];
        } else {
            $data = false;
        }
        return $data;
    }



    function list($filter, $order, $limit)
    {
        $array = [];
        $filterSql = '';
        if (is_array($filter) && !isset($filter['status']) && !isset($filter['date'])) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        }
        if (isset($filter['status'])) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" OR ", $filter['status']);
        }
        if (isset($filter['date'])) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter['date']);
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
        $sql = "SELECT * FROM `pedidos` $filterSql  ORDER BY $orderSql $limitSql";
        $result = $this->con->sqlReturn($sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $details = $this->detallePedido->list("'" . $row['cod'] . "'");
                $this->user->set("cod", $row['usuario']);
                $estado = $this->estado_pedido->view($row["estado"]);
                $user = $this->user->view();
                $array[] = ["data" => $row, "user" => $user, "detail" => $details, "estados" => $estado];
            }
        }
        return $array;
    }

    public function getTotalByStatus($filter = '')
    {
        // Genero todo el array en vacio
        $status = [];
        $statusTotal = [
            "0" => ["data" => ["cantidad" => 0, "total" => 0]],
            "1" => ["data" => ["cantidad" => 0, "total" => 0]],
            "2" => ["data" => ["cantidad" => 0, "total" => 0]],
            "3" => ["data" => ["cantidad" => 0, "total" => 0]]
        ];
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter['date']);
        } else {
            $filterSql = '';
        }

        $sql = "SELECT COUNT(*) as cantidad, SUM(pedidos.total) AS total , estados_pedidos.id as idEstado, estados_pedidos.titulo as titulo, pedidos.estado  
        FROM pedidos 
        LEFT JOIN estados_pedidos ON pedidos.estado = estados_pedidos.id $filterSql GROUP BY estados_pedidos.id ORDER BY estados_pedidos.estado ASC";
        $pedidos = $this->con->sqlReturn($sql);
        if ($pedidos) {
            while ($row = mysqli_fetch_assoc($pedidos)) {
                $status[] = ["data" => $row]; // Relleno el array vacio con los datos que traiga de la consulta y si no encuentra mantiene el vacio
                switch ($row['estado']) {
                    case 0:
                        $statusTotal[0] = ["data" => [
                            "cantidad" => ($statusTotal[0]['data']['cantidad'] + $row['cantidad']),
                            "total" => ($statusTotal[0]['data']['total'] + $row['total'])
                        ]];
                        break;
                    case 1:
                        $statusTotal[1] = ["data" => [
                            "cantidad" => ($statusTotal[1]['data']['cantidad'] + $row['cantidad']),
                            "total" => ($statusTotal[1]['data']['total'] + $row['total'])
                        ]];
                        break;
                    case 2:
                        $statusTotal[2] = ["data" => [
                            "cantidad" => ($statusTotal[2]['data']['cantidad'] + $row['cantidad']),
                            "total" => ($statusTotal[2]['data']['total'] + $row['total'])
                        ]];
                        break;
                    case 3:
                        $statusTotal[3] = ["data" => [
                            "cantidad" => ($statusTotal[3]['data']['cantidad'] + $row['cantidad']),
                            "total" => ($statusTotal[3]['data']['total'] + $row['total'])
                        ]];
                        break;
                }
            }
            $array = ["status" => $status, "statusTotal" => $statusTotal];
        }
        return $array;
    }



    public function tooltipData($totalByStatus)
    {
        $tooltipName[] = [];
        foreach ($totalByStatus as $tootltipStatus) {
            switch ($tootltipStatus['data']['estado']) {
                case 0:
                    $tooltipName[0][] = [
                        "cantidad" => $tootltipStatus['data']['cantidad'],
                        "total" => $tootltipStatus['data']['total'],
                        "titulo" => $tootltipStatus['data']['titulo']
                    ];
                    break;
                case 1:
                    $tooltipName[1][] = [
                        "cantidad" => $tootltipStatus['data']['cantidad'],
                        "total" => $tootltipStatus['data']['total'],
                        "titulo" => $tootltipStatus['data']['titulo']
                    ];
                    break;
                case 2:
                    $tooltipName[2][] = [
                        "cantidad" => $tootltipStatus['data']['cantidad'],
                        "total" => $tootltipStatus['data']['total'],
                        "titulo" => $tootltipStatus['data']['titulo']
                    ];
                    break;
                case 3:
                    $tooltipName[3][] = [
                        "cantidad" => $tootltipStatus['data']['cantidad'],
                        "total" => $tootltipStatus['data']['total'],
                        "titulo" => $tootltipStatus['data']['titulo']
                    ];
                    break;
            }
        }
        return $tooltipName;
    }
    public function getTooltip($tooltipData)
    {
        $total = count($tooltipData);
        $i = 0;
        foreach ($tooltipData as $tooltip) {
            $i++;
            echo "<div class='mt-20'><b>" . $tooltip["titulo"] . "</b> ( " . $tooltip["cantidad"] . " )<br/> <b>$" . number_format($tooltip["total"], "2", ",", ".") . "</div>";
            echo ($i != $total) ? "<hr/>" : "<br/>";
        }
    }



    /**
     *
     * Traer un array con el detalle del pedido y el  de informacion '' o 'pago'
     *
     * @param    array  $detalle array con la informacion del pedido
     * @param    string  $typeInfo  de informacion si es del apartado de pagos o del de s
     * @return   array retorna un array con cada dato ya incluido en una etiqueta <p></p>
     *
     */
    public function getInfoPedido($detalle, $typeInfo)
    {
        $textReturn = '';
        foreach ($detalle[$typeInfo] as $key => $value) {
            if ($key != 'similar' && $key != 'factura') {
                $textReturn .= !empty($value) ? "<p class='mb-0 fs-13'><b>" . $_SESSION["lang-txt"]["checkout"][$key] . ": </b>" . str_replace('/u([\da-fA-F]{4})/', '&#x\1;', $value) . "</p> " : "";
            }
        }
        return $textReturn;
    }
    public function countContents($filter = [])
    {
        $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' OR ', $filter) : '';
        $sql = "SELECT COUNT(*) as cantidad FROM `pedidos` $filterSql ";
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

    public function checkMercadoPago()
    {
        $urlCanonical = explode("&", CANONICAL);
        $collection_id = str_replace(URL . "/checkout/detail?collection_id=", "", $urlCanonical[0]);
        if ($collection_id != "null") {
            if (isset($collection_id) && !empty($collection_id)) {
                $this->f->curl("GET", URL . "/api/payments/ipn.php?id=" . $collection_id, '');
            }
        }

        ($collection_id == "null") ? $this->editSingle("estado", 1) : '';
    }
    public function gestionLTV($filter)
    {
        $array = [];
        $filterSql = '';
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        }
        $sql = "SELECT COUNT(`usuarios`.`cod`) AS cantidad_pedidos,`usuarios`.`nombre`, `usuarios`.`apellido`,`usuarios`.`email`,
        `usuarios`.`telefono`,`usuarios`.`localidad`,`usuarios`.`provincia`,
        SUBSTRING_INDEX( GROUP_CONCAT( `pedidos`.`fecha` ORDER BY `pedidos`.`id` DESC SEPARATOR '||' ), '||', 1 ) ultima_compra , 
        DATEDIFF(NOW(),SUBSTRING_INDEX( GROUP_CONCAT( `pedidos`.`fecha` ORDER BY `pedidos`.`id` DESC SEPARATOR '||' ), '||', 1 )) AS ultimo_dia 
        FROM pedidos 
        LEFT JOIN `usuarios` ON `usuarios`.`cod` = `pedidos`.`usuario`  
        LEFT JOIN `estados_pedidos` ON `estados_pedidos`.`id` = `pedidos`.`estado` 
        $filterSql
        GROUP BY `pedidos`.`usuario` ORDER BY ultima_compra DESC";
        $result = $this->con->sqlReturn($sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $array[] = ["data" => $row];
            }
        }
        return $array;
    }


    function listEstadisticas($filter, $order, $limit)
    {
        $array = [];
        $filterSql = '';
        if (is_array($filter) && !isset($filter['status']) && !isset($filter['date'])) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        }
        if (isset($filter['status'])) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" OR ", $filter['status']);
        }
        if (isset($filter['date'])) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter['date']);
        }

        if (
            $order != ''
        ) {
            $orderSql = $order;
        } else {
            $orderSql = "pedidos.id DESC";
        }

        if (
            $limit != ''
        ) {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }
        $sql = "SELECT pedidos.* FROM `pedidos` LEFT JOIN usuarios ON usuarios.cod = pedidos.usuario  $filterSql  ORDER BY $orderSql $limitSql";
        $result = $this->con->sqlReturn($sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $details = $this->detallePedido->list("'" . $row['cod'] . "'");
                $this->user->set("cod", $row['usuario']);
                $estado = $this->estado_pedido->view($row["estado"]);
                $user = $this->user->view();
                $array[] = ["data" => $row, "user" => $user, "detail" => $details, "estados" => $estado];
            }
        }
        return $array;
    }

    public function getProductsFromOrder($date = '')
    {
        $this->descuentos = new Descuentos();
        $array = [];
        $pedidos = $this->descuentos->getOrdersCod($date);
        if (!empty($pedidos)) {
            foreach ($pedidos as $key => $pedidoItem) {
                $array_ = [];
                foreach ($pedidoItem["pedidos_cod"] as $cod) {
                    foreach ($this->detallePedido->list("'" . $cod . "'") as $detail) {
                        if ($detail["tipo"] != "PR") continue;
                        $array[$key] = ["pedidos_cod" => $pedidoItem["pedidos_cod"], "cant_pedidos" => $pedidoItem["pedidos"]];
                        $array[$key]["productos"][$detail["cod_producto"]] = [];
                        if (isset($array_[$detail["cod_producto"]]["cantidad"])) {
                            $array_[$detail["cod_producto"]]["cantidad"] = $array_[$detail["cod_producto"]]["cantidad"]  + $detail["cantidad"];
                        } else {
                            $array_[$detail["cod_producto"]] = ["cantidad" => $detail["cantidad"], "titulo" => $detail["producto"]];
                        }
                    }
                    foreach ($this->list(["cod = '" . $cod . "'"], "", "") as $order) {
                        $arrayPedido["fecha"][] =  $order["data"]["fecha"];
                        $arrayPedido["precio"][] =  $order["data"]["total"];
                        $arrayUsuario[$order["user"]["data"]["cod"]] = ["email" => $order["user"]["data"]["email"]];
                    }
                }
                $array[$key]["pedido"] = $arrayPedido;
                $array[$key]["usuario"] = $arrayUsuario;
                $array[$key]["productos"] = $array_;
                unset($arrayPedido, $arrayUsuario, $array_);
                $array[$key]["cant_productos"] = count($array[$key]["productos"]);
            }
            return $array;
        }
    }
    public function getUsersCod($filter)
    {
        $array = [];
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        }
        $sql = "SELECT `pedidos`.`usuario` FROM `pedidos` 
        LEFT JOIN `usuarios` on `pedidos`.`usuario` = `usuarios`.`cod`
        LEFT JOIN `estados_pedidos` on `estados_pedidos`.`id` = `pedidos`.`estado` $filterSql";
        $result = $this->con->sqlReturn($sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $array[] =  $row["usuario"];
            }
        }
        return $array;
    }
    public function getOrderPerState($filter)
    {
        $filterSql = "";
        $array = [];
        if (!empty($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        }

        $sql = "SELECT `pedidos`.`cod`,`pedidos`.`fecha`,`pedidos`.`total`,`usuarios`.`nombre`,`usuarios`.`apellido`,`usuarios`.`provincia` 
                    FROM `pedidos` 
                LEFT JOIN `usuarios` ON `usuarios`.`cod` = `pedidos`.`usuario` 
                $filterSql
                ORDER BY `pedidos`.`fecha` DESC;";
        $result = $this->con->sqlReturn($sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $array[] = ["data" => $row];
            }
        }
        return $array;
    }
}
