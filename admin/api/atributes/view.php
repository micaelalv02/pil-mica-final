<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$producto = new Clases\Productos();
$atributo = new Clases\Atributos();
$combinacion = new Clases\Combinaciones();

$product = isset($_POST['cod']) ? $f->antihack_mysqli($_POST['cod']) : '';
$idioma = isset($_POST['idioma']) ? $f->antihack_mysqli($_POST['idioma']) : '';
if (!empty($product)) {
    $productoData = $producto->list(["filter" => ["productos.cod= '" . $product . "'"]], $_SESSION['lang'], true);
    if (!empty($productoData)) {
        $atributo->set("productoCod", $productoData['data']['cod']);
        $atributo->set("idioma", $idioma);
        $atributosData = $atributo->list();
        $response = '';
        if (!empty($atributosData)) {
            $combinacion->set("codProducto", $productoData['data']['cod']);
            $combinacionData = $combinacion->listByProductCod();
            if (!empty($combinacionData)) {
                $response .= "<hr>Este producto tiene las siguientes combinaciones:<br>";
                foreach ($combinacionData as $comb) {
                    foreach ($comb['combination'] as $comb_) {
                        $response .= $comb_['value'] . " | ";
                    }
                    $response .= "<price class='hidden'><strong>Precio: </strong>$" . $comb['detail']['precio'] . " </price><strong>Stock: </strong>" . $comb['detail']['stock'];
                    if ($comb['detail']['mayorista'] > 0) {
                        $response .= "<price class='hidden'><strong> Precio Mayorista:</strong> $" . $comb['detail']['mayorista'] . "</price>";
                    } else {
                        $response .= "<price class='hidden'><strong> Precio Mayorista:</strong> No Posee</price>";
                    }
                }
                $response .= "<hr><br>";
            }
            $response .= "<form id='cartForm$product'>";
            $response .= "<input type='hidden' name='product' value='$product'>";
            if (!empty($combinacionData)) {
                $response .= "<input type='hidden' name='combination'>";
            }

            foreach ($atributosData as $atrib) {
                $response .= "<b>" . $atrib['atribute']['value'] . "</b>";
                $cod = $atrib['atribute']['cod'];
                $response .= "<select class='form-control' name='atribute[$cod]' required>";
                foreach ($atrib['atribute']['subatributes'] as $sub) {
                    $subCod = $sub['cod'];
                    $subVal = $sub['value'];
                    $response .= "<option value='$subCod'>$subVal</option>";
                }
                $response .= "</select>";
            }
            $response .= "<div class='mt-10'>";
            $response .= "<b>Cantidad: </b>";
            $response .= "<input type='number' id='amount' name='amount' min='1' class='form-control' value='1' required>";
            $response .= "</div>";
            $response .= "</form>";
            $result = array("status" => true, "response" => $response);
            echo json_encode($result);
        } else {
            $precio = $productoData['data']['precio'];
            $response .= "<form id='cartForm$product'>";
            $response .= "<input type='hidden' name='product' value='$product'>";
            $response .= "<div class='row'>";
            $response .= "<div class='col-md-6 mt-10'>";
            $response .= "<b>Precio: </b>";
            $response .= "<input type='text' readonly name='precio' class='form-control' value='$precio' required>";
            $response .= "</div>";
            $response .= "<div class='col-md-6 mt-10'>";
            $response .= "<b>Cantidad: </b>";
            $response .= "<input type='number' id='amount' name='amount' min='1' class='form-control' value='1' required>";
            $response .= "</div>";
            $response .= "</div>";
            $response .= "</form>";
            $result = array("status" => true, "response" => $response);
            echo json_encode($result);
        }
    } else {
        $result = array("status" => false, "message" => "Ocurrió un error, recarge la página.1");
        echo json_encode($result);
    }
} else {
    $result = array("status" => false, "message" => "Ocurrió un error, recarge la página.2");
    echo json_encode($result);
}
