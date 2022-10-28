<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$enviar = new Clases\Email();
$config = new Clases\Config();
$productosVisitados = new Clases\ProductosVisitados();
$productos = new Clases\Productos();
$usuariosIp = new Clases\UsuariosIp();
$users = new Clases\Usuarios();
$emailData = $config->viewEmail();

$fecha = date("Y-m-d H:i:s");
$fechaMenos = date("Y-m-d H:i:s", strtotime($fecha . "- 24 hours"));
$fechaMas = date("Y-m-d H:i:s", strtotime($fechaMenos . "+ 24 hours"));


$usuarios = $usuariosIp->list(["usuario != ''"], "", "");
#make a user foreach
if (!empty($usuarios)) {
    foreach ($usuarios as $user) {
        if ($user["usuario"] == '') continue;
        $users->set("cod", $user["usuario"]);
        $userData = $users->view();
        $ip = $user["ip"];
        $visitasData = $productosVisitados->listDistinct(["productos_visitados.fecha BETWEEN '$fechaMenos ' AND '$fechaMas'", "productos_visitados.usuario_ip = '$ip'"], "", "");
        $message = '<table style="display: grid; grid-gap: 10px; background-color: white;margin-top:10px;left-padding: 10px; text-align:center; grid-template-columns: 50% 50%;">';
        if (!empty($visitasData)) {
            foreach ($visitasData as $visit) {
                $cod = $visit["producto"];
                $idioma = $visit["idioma"];
                $productData = $productos->list(["productos.cod = '$cod'"], $idioma, true);
                $message .= '<tr style="width:50%;float: left;">';
                $message .= '<td>';
                $message .= '<img src="' . $productoData['images'][0]['url'] . '" style="object-fit:contain;width:100%;height:200px" alt="">';
                $message .= '<span>' . $productData["data"]["titulo"] . '</span>';
                $message .= '<div style="min-height:100px;min-width:200px">';
                $message .= '<a style="border-radius: 20px;color: #fff;font-size: 12px;padding: 7px 20px;text-transform: uppercase;position: relative;z-index: 1;background-color: #77d0e4;color: #fff;top: 20px;right: 9px;display: -webkit-inline-box;" href="' . $productoData["link"] . '">Comprar Producto</a>';
                $message .= '</div>';
                $message .= '</td>';
                $message .= '</tr>';
            }
            $message .= '</table>';
            $enviar->set("asunto", "Hay productos esperando por vos - Compra en " . TITULO);
            $enviar->set("receptor", $userData['data']["email"]);
            $enviar->set("emisor", $emailData['data']['remitente']);
            $enviar->set("mensaje", $message);
            if ($enviar->emailEnviar()) {
                echo "exitoso";
            } else {
                echo "error [1]";
            }
        } else {
            echo "error [2]";
        }
    }
} else {
    echo "error [3]";
}
