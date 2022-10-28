<?php
require_once "../Config/Autoload.php";
Config\Autoload::run();
$productos = new Clases\Productos();
$excel = new Clases\Excel();

$productos->updateStockAvilableCero();

// A = id == cod_producto
// B = bard_code == variable1
// C = product == titulo
// D = price_w_tax == precio
// F = grupo == categoria
// H = linea == subcategoria
// I = habilitado == mostrar_web
// J = stock_web == stock
// k = tipo de producto == variable2
// L = cod_producto del relacionado == variable3
// M = cantidad del bulto == variable4
// N = promo cod_producto == variable5
// O = celiaco == variable6 

$myfile = fopen("lista_de_precios.txt", "r") or die("Unable to open file!");
$i = 0;
// Output one line until end-of-file
while (!feof($myfile)) {
    $sheet = (explode(",", fgets($myfile)));
    if ($sheet[9] != 1) continue;

    $titulo = normalizar_corto(utf8_encode($sheet[2]), true);
    $cod_cat = ($sheet[5] != '') ? $excel->checkCategory("categorias", normalizar_corto($sheet[5], false), 'productos') : '';
    $cod_subCat = ($sheet[7] != '') ? $excel->checkCategory("subcategorias", normalizar_corto($sheet[7], false), $cod_cat) : '';
    $stock = ($sheet[9] == 1) ? 9999 : 0;

    $array = [
        'cod_producto' =>  $sheet[0],
        'variable1' => $sheet[1],
        'titulo' => $titulo,
        'precio' => number_format((float)$sheet[3], 2, '.', ''),
        'categoria' => $cod_cat,
        'subcategoria' => $cod_subCat,
        'mostrar_web' => 1,
        'stock' => $stock,
        'variable2' => ($sheet[10] == 'null') ? null : $sheet[10],
        'variable3' => ($sheet[11] == 'null') ? null : $sheet[11],
        'variable4' => ($sheet[12] == 'null') ? null : $sheet[12],
        'variable5' => ($sheet[13] == 'null') ? null : $sheet[13],
        'variable6' => $sheet[14]
    ];

    if ($excel->view('productos', 'cod_producto', $sheet[0])) {
        $productos->edit($array, ["cod_producto = '" . $sheet[0] . "'"]);
        echo  $titulo . " - Editado";
    } else {
        $array['cod'] = substr(md5(uniqid(rand())), 0, 10);
        $productos->add($array);
        echo  $titulo . " - Agregado";
    }
    echo "<hr>";
}

function normalizar_corto($dato, $flag)
{
    $string = str_replace("'", "", $dato);
    $string = str_replace("***", "", $string);
    $string = str_replace("**", "", $string);
    $string = str_replace("---", "", $string);
    $string = str_replace("--", "", $string);
    if ($flag) {
        $string = str_replace("*", "x", $string);
        $string = str_replace("N�", "Nº", $string);
        $string = str_replace("1�", "1º", $string);
        $string = str_replace("�", "Ñ", $string);
    } else {
        $string = str_replace("*", " ", $string);
    }

    return $string;
}
