<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$con = new Clases\Conexion();
$f = new Clases\PublicFunction();
$productos = new Clases\Productos();
$excel = new Clases\Excel();
$categorias = new Clases\Categorias();

$_SESSION['categorias'] = $categorias->listExcel(["area = 'productos'"], "", "", "es");

foreach (array_keys($_POST, '1', true) as $key) {
    unset($_POST[$key]);
}

$idiomaSelected = '';
if (isset($_POST['modal-idioma-select'])) {
    $idiomaSelected = $_POST['modal-idioma-select'];
    unset($_POST['modal-idioma-select']);
}
$_SESSION['import'] = replaceKey($_SESSION['import'], $_POST);
$_POST['idioma'] = (isset($_POST['idioma'])) ? $_POST['idioma'] : 'idioma';

$countAdd = 0;
$countEdit = 0;
$pdo = $con->conPDO();
$pdo->beginTransaction();
foreach ($_SESSION['import'] as  $row) {
    if (!empty($row['cod_producto'])) {
        $insert = '';
        $row['idioma'] = (isset($row['idioma'])) ? $row['idioma'] : $idiomaSelected;

        foreach ($row as $attr => $value) {
            if (array_search($attr, $_POST, true) !== false) {
                $importArray[$attr] = trim($value, $character_mask = " \t\n\r\0\x0B$");
                if ($importArray[$attr] == '') {
                    unset($importArray[$attr]);
                }
            }
        }

        if (isset($importArray['precio'])) $importArray['precio'] =  str_replace(',', '', $importArray['precio']);
        if (isset($importArray["categoria"]) || isset($importArray["subcategoria"]) || isset($importArray["tercercategoria"])) {
            $categoriaFinalCheck = checkCategories(isset($importArray["categoria"]) ? trim($importArray["categoria"]) : '', isset($importArray["subcategoria"]) ?  trim($importArray["subcategoria"]) : '', isset($importArray["tercercategoria"]) ?  trim($importArray["tercercategoria"]) : '', $importArray['idioma']);
            unset($importArray["categoria"]);
            unset($importArray["subcategoria"]);
            unset($importArray["tercercategoria"]);
            if (isset($categoriaFinalCheck["categoria"]) && !empty($categoriaFinalCheck["categoria"])) $importArray['categoria'] = $categoriaFinalCheck["categoria"];
            if (isset($categoriaFinalCheck["subcategoria"]) && !empty($categoriaFinalCheck["subcategoria"]))  $importArray['subcategoria'] = $categoriaFinalCheck["subcategoria"];
            if (isset($categoriaFinalCheck["tercercategoria"]) && !empty($categoriaFinalCheck["tercercategoria"]))  $importArray['tercercategoria'] = $categoriaFinalCheck["tercercategoria"];
        }
        $importArray["stock"] = 9999;
        $sql = "SELECT `productos`.`cod` FROM `productos` WHERE `cod_producto` = '" . $row['cod_producto'] . "' AND `idioma` = '" . $row['idioma'] . "'";
        $query = $con->sqlReturn($sql);
        $productSearch = $query->fetch_row();
        if ($productSearch) {
            $query = implode(", ", array_map(function ($v) {
                return "$v=:$v";
            }, array_keys($importArray)));
            $condition = implode(' AND ', ["cod = '" . $productSearch[0] . "'", "idioma = '" . $importArray['idioma'] . "'"]);
            $pdo->prepare("UPDATE productos SET $query WHERE $condition")->execute($importArray);
            $countEdit++;
        } else {
            $importArray['cod'] = substr(md5(uniqid(rand())), 0, 10);
            $attr = implode(",", array_keys($importArray));
            $values = ":" . str_replace(",", ",:", $attr);
            $pdo->prepare("INSERT INTO productos ($attr) VALUES ($values)")->execute($importArray);
            $countAdd++;
        }
        unset($importArray);
    }
}

try {
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollback();
}

$response = ['status' => true, "msg" => 'Se agregaron ' . $countAdd . ' y se editaron ' . $countEdit . ' productos.'];
unset($_SESSION['import']);
echo json_encode($response);

function replaceKey($original, $map = array())
{
    /** Create a temp var encoding object or array to json */
    $temp = json_encode($original);
    /** Loopint to replace keys on json */
    foreach ($map as $k => $v) {

        $temp = str_ireplace('"' . $k . '":', '"' . $v . '":', $temp);
    }
    /** Default return is array format but if the original is a object return it in object */
    $array = true;
    if (is_object($original)) {
        $array = false;
    }
    return json_decode($temp, $array);
}


function checkCategories($categoria, $subcategoria, $tercercategoria, $idioma)
{

    $array = [];
    $array["categoria"] = '';
    $array["subcategoria"] = '';
    $array["tercercategoria"] = '';
    $categorias = new Clases\Categorias();
    $subcategorias = new Clases\Subcategorias();
    $tercercategorias = new Clases\Tercercategorias();
    $categoriaExist = false;
    $subcategoriaExist = false;
    $tercercategoriaExist = false;
    $categoriaList = $_SESSION['categorias'];

    if (empty(trim($categoria))) return [];
    if (!empty($categoriaList)) {
        foreach ($categoriaList as $codCat => $categoria_) {
            if (!empty($categoria)) {
                if ($categoria_["titulo"] == $categoria) {
                    $categoriaExist = true;
                    $array["categoria"] = $codCat;
                    if (!empty($subcategoria)) {
                        if (!empty($categoria_["subcategorias"])) {
                            foreach ($categoria_["subcategorias"] as $codSub => $subcategoria_) {
                                if ($subcategoria_["titulo"] == $subcategoria) {
                                    $subcategoriaExist = true;
                                    $array["subcategoria"] = $codSub;
                                    if (!empty($tercercategoria)) {
                                        if (!empty($subcategoria_["tercercategorias"])) {
                                            foreach ($subcategoria_["tercercategorias"] as $codTer => $tercercategoria_) {
                                                if ($tercercategoria_["titulo"] == $tercercategoria) {
                                                    $tercercategoriaExist = true;
                                                    $array["tercercategoria"] = $codTer;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    break;
                }
            }
        }
    }
    if (!$categoriaExist && !empty(trim($categoria))) {
        $array["categoria"] = substr(md5(uniqid(rand())), 0, 10);
        $categorias->add(["cod" => $array["categoria"], "titulo" => $categoria, "area" => "productos", "idioma" => $idioma]);
        $_SESSION["categorias"][$array["categoria"]]["titulo"] = $categoria;
    }
    if (!$subcategoriaExist && !empty(trim($categoria)) && !empty(trim($subcategoria))) {
        $array["subcategoria"] = substr(md5(uniqid(rand())), 0, 10);
        $subcategorias->add(["cod" => $array["subcategoria"], "categoria" =>  $array["categoria"], "titulo" => $subcategoria, "idioma" => $idioma]);
        $_SESSION["categorias"][$array["categoria"]]["subcategorias"][$array["subcategoria"]]["titulo"] = $subcategoria;
    }
    if (!$tercercategoriaExist && !empty(trim($categoria)) && !empty(trim($subcategoria)) && !empty(trim($tercercategoria))) {
        $array["tercercategoria"] = substr(md5(uniqid(rand())), 0, 10);
        $tercercategorias->add(["cod" => $array["tercercategoria"], "subcategoria" =>  $array["subcategoria"], "titulo" => $tercercategoria, "orden" => 0, "idioma" => $idioma]);
        $_SESSION["categorias"][$array["categoria"]]["subcategorias"][$array["subcategoria"]]["tercercategorias"][$array["tercercategoria"]]["titulo"] = $tercercategoria;
    }
    return $array;
}
