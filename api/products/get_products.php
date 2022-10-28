<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
$combinacion = new Clases\Combinaciones();
$subcategoria = new Clases\Subcategorias();
$productosRelacionados = new Clases\ProductosRelacionados();

$search = isset($_POST['title']) ?  $f->antihack_mysqli($_POST['title']) : '';
$order = isset($_GET['order']) ?  $f->antihack_mysqli($_GET['order']) : '';
$start = isset($_GET['start']) ?  $f->antihack_mysqli($_GET['start']) : '0';
$limit = isset($_GET['limit']) ?  $f->antihack_mysqli($_GET['limit']) : '24';
if (isset($_POST['en_promocion'])) $en_promocion = $f->antihack_mysqli($_POST['en_promocion']);
if (isset($_POST['en_descuento'])) $en_descuento = $f->antihack_mysqli($_POST['en_descuento']);
if (isset($_POST['con_stock'])) $con_stock = $f->antihack_mysqli($_POST['con_stock']);
if (isset($_POST['destacado'])) $destacado = $f->antihack_mysqli($_POST['destacado']);


$catsFilter = [];
if (isset($destacado)) $filter[] = 'productos.destacado = 1';
if (isset($con_stock)) $filter[] = 'productos.stock > 0';
if (isset($en_descuento)) $filter[] = 'productos.precio_descuento > 0';
if (isset($en_promocion)) $filter[] = 'promos.lleva > 0 AND promos.paga > 0';

$filter[] = 'productos.mostrar_web = 1';

if (!empty($search)) {
    $search = trim($search);
    $search_array = explode(' ', $search);
    $searchSql = '(';
    foreach ($search_array as $key => $searchData) {
        if ($key == 0) {
            $searchSql .= "productos.cod_producto LIKE '%$searchData%' OR productos.titulo LIKE '%$searchData%'";
        } else {
            $searchSql .= " AND productos.titulo LIKE '%$searchData%'";
        }
    }

    $searchSql .= ')';

    $filter[] = $searchSql;
}

if (!empty($_POST['categories'])) {
    foreach ($_POST['categories'] as $key => $cat) {
        $cat_ = $f->antihack_mysqli($cat);
        if (!empty($cat_)) $cats[] = "'" . $cat_ . "'";
    }
    $catsImplode = implode(",", $cats);
    $catsFilter[] = "productos.categoria IN (" . $catsImplode . ")";
}

if (!empty($_POST['subcategories'])) {
    foreach ($_POST['subcategories'] as $key2 => $sub) {
        $subcat_ = $f->antihack_mysqli($sub);
        if (!empty($subcat_)) $subcats[] = "'" . $subcat_ . "'";
    }
    $subcatsImplode = implode(",", $subcats);
    $catsFilter[] = "productos.subcategoria IN (" . $subcatsImplode . ")";
}

if (!empty($_POST['tercercategories'])) {
    foreach ($_POST['tercercategories'] as $key3 => $ter) {
        $tercercat_ = $f->antihack_mysqli($ter);
        if (!empty($tercercat_)) $tercercats[] = "'" . $tercercat_ . "'";
    }
    $tercercatsImplode = implode(",", $tercercats);
    $catsFilter[] = "productos.tercercategoria IN (" . $tercercatsImplode . ")";
}

count($catsFilter) ? $filter[] = "(" . implode(" AND ", $catsFilter) . ")" : '';

switch ($order) {
    case "1":
        $order = "productos.id DESC";
        break;
    case "2":
        $order = "productos.precio ASC";
        break;
    case "3":
        $order = "productos.precio DESC";
        break;
    default:
        $order = "productos.id DESC";
        break;
}


if (empty($filter)) $filter = '';
$data = [
    "filter" => $filter,
    "admin" => false,
    "category" => true,
    "subcategory" => true,
    "tercercategory" => true,
    "images" => true,
    "promos" => true,
    "attribute" => true,
    "combination" => true,
    "limit" => $start . "," . $limit,
    "order" => $order,
];
$productosData = $producto->list($data, $_SESSION['lang']);

if (!empty($productosData)) {
    echo json_encode(["products" => $productosData, "user" => isset($_SESSION["usuarios"]) ? $_SESSION["usuarios"] : []]);
}
