<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$comentarios = new Clases\Comentarios();


$id_comentario = isset($_POST["id"]) ?  $f->antihack_mysqli($_POST["id"]) : '';
if ($comentarios->delete($id_comentario)) {
    $result = array("status" => true, "message" => "¡Comentario eliminado!");
    echo json_encode($result);
} else {
    $result = array("status" => false, "message" => "¡Error al intentar eliminar el comentario!");
    echo json_encode($result);
}
