<?php
$cnx = new Clases\Conexion();
if (!$cnx->con()) $f->headerMove(URL . "/.install/index.php?step=1&error=1");

$f = new Clases\PublicFunction();
$menu = new Clases\Menu();
$roles = new Clases\Roles();

$path = 'json/menu.json';
$menuImport = json_decode(file_get_contents($path), true);
$menu->truncate('roles');
$menu->truncate('menu'); 

foreach ($menuImport as $value) {
    foreach ($value as $key => $value_) {
        $menu->set($key, $value_);
    }
    $roles->set("cod", 'admin1');
    $menu->add();
}

$f->headerMove(URL . "/.install/index.php?step=3");
