<?php
$f = new Clases\PublicFunction();
$excel = new Clases\Excel();
$pedidos = new Clases\Pedidos();
$cod = isset($_GET["cod"]) ? $f->antihack_mysqli($_GET["cod"]) : '';
$pedidos->set("cod", $cod);
$array = $pedidos->view(); 
$ruta = $excel->exportPedido($array);
$f->headerMove(URL . '/export/' . $ruta);
