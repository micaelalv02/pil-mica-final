<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$product = new Clases\Productos();
$user = new Clases\Usuarios();

$table = isset($_POST['table']) ? $f->antihack_mysqli($_POST['table']) : '';

switch ($table) {
    case 'productos':
        $return =  ["status" => true , "attr" =>  $product->getAttrWithTitle()];
        break;

    // case 'usuarios':
    //     $return = ["status" => true , "attr" =>  $user->getAttrWithTitle()];
    //     break;
        // futuras tablas

    default:
        $return = ["status" => false];
        break;
}

echo json_encode($return);
