<?php

namespace Clases;



use Exception;

class Productos
{
    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $precio;
    public $precio_descuento;
    public $precio_mayorista;
    public $peso;
    public $stock;
    public $desarrollo;
    public $categoria;
    public $subcategoria;
    public $keywords;
    public $description;
    public $destacado;
    public $envio_gratis;
    public $mostrar_web;
    public $fecha;
    public $meli;
    public $tercercategoria;
    public $cod_producto;
    public $img;
    public $url;

    //Clases
    private $con;
    public $f;
    private $categoriasClass;
    private $subcategoriasClass;
    private $imagenesClass;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->f = new PublicFunction();
        $this->categoriasClass = new Categorias();
        $this->subcategoriasClass = new Subcategorias();
        $this->imagenesClass = new Imagenes();
        $this->atributosClass = new Atributos();
        $this->combinacionClass = new Combinaciones();
        $this->favorite = new Favoritos();
    }

    public function set($atributo, $valor)
    {
        if (!empty($valor)) {
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

    public function getAttrWithTitle()
    {
        $data = [];
        $data["cod_producto"] = "Codigo Producto";
        $data["titulo"] = "Titulo";
        $data["desarrollo"] = "Desarrollo";
        $data["stock"] = "Stock";
        $data["peso"] = "Peso";
        $data["precio"] = "Precio";
        $data["precio_descuento"] = "Precio con Descuento";
        $data["precio_mayorista"] = "Precio Mayorista";
        $data["categoria"] = "Categoria";
        $data["subcategoria"] = "Subcategoria";
        $data["tercercategoria"] = "Tercer Categoria";
        $data["keywords"] = "Palabras Claves";
        $data["description"] = "Descripcion Breve";
        $data["mostrar_web"] = "Mostrar en web";
        $data["idioma"] = "Idioma";
        $data["fecha"] = "Fecha";
        return $data;
    }



    public function add($array)
    {
        $attr = implode(",", array_keys($array));
        $values = ":" . str_replace(",", ",:", $attr);
        $sql = "INSERT INTO productos ($attr) VALUES ($values)";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
    }

    public function edit($array, $cod)
    {
        $query = implode(", ", array_map(function ($v) {
            return "$v=:$v";
        }, array_keys($array)));
        $condition = implode(' AND ', $cod);
        $sql = "UPDATE productos SET $query WHERE $condition";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
    }

    public function editSingle($atributo, $valor, $idioma)
    {
        $sql = "UPDATE `productos` SET `$atributo` = {$valor} WHERE `cod`={$this->cod} AND `idioma`= '{$idioma}'";
        if ($this->con->sqlReturn($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($array)
    {
        $sql = "DELETE FROM `productos` WHERE cod=:cod AND idioma=:idioma";
        try {
            $stmt = $this->con->conPDO()->prepare($sql);
            $stmt->execute($array);
            if (!empty($this->imagenesClass->list($array, "", "", true))) {
                $this->imagenesClass->deleteAll($array);
            }
            $response = true;
        } catch (Exception $e) {
            $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
        }
        return $response;
    }


    public function truncate()
    {
        $sql = "TRUNCATE `productos`";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function updateStockAvilableCero()
    {
        $sql = "UPDATE `productos` SET `mostrar_web` = 0 AND `stock` = 0 ";
        return ($this->con->sqlReturn($sql)) ? true : false;
    }

    public function viewSimple($cod, $idioma, $attr)
    {
        if (is_array($cod)) {
            foreach ($cod as $codigo) {
                $sql_filter[] = "`productos`.`$attr` = '" . $codigo . "' ";
            }
            $filter = implode(" OR ", $sql_filter);
        } else {
            $filter = "`productos`.`$attr` = '" . $cod . "' ";
        }
        $array = [];
        $sql = "SELECT cod, cod_proucto , titulo FROM `productos` WHERE  ($filter) AND idioma = '$idioma'";
        $productos = $this->con->sqlReturn($sql);
        if ($productos) {
            while ($row = mysqli_fetch_assoc($productos)) {
                $array[] = ["data" => $row];
            }
        }
        return $array;
    }

    function listSearch($search, $limit, $idioma)
    {
        $search = trim($search);
        $search_array = explode(' ', $search);
        $searchSql = '';
        foreach ($search_array as $key => $searchData) {
            if ($key == 0) {
                $searchSql .= "UPPER(`productos`.`cod_producto`) LIKE UPPER('%$searchData%') OR UPPER(`productos`.`titulo`) LIKE UPPER('%$searchData%')";
            } else {
                $searchSql .= " AND `productos`.`titulo` LIKE '%$searchData%'";
            }
        }
        $sql = "SELECT `productos`.`titulo`, `productos`.`cod`  FROM `productos` WHERE mostrar_web = 1 AND idioma = '$idioma' AND ($searchSql) AND cod_producto NOT LIKE '%|%' LIMIT $limit";
        $contenido = $this->con->sqlReturn($sql);

        if ($contenido) {
            while ($row = mysqli_fetch_assoc($contenido)) {
                $link = URL . '/producto/' . $this->f->normalizar_link($row['titulo']) . '/' . $row['cod'];
                $array[] = ["value" => $row['titulo'], "label" => $row['titulo'], "link" => $link];
            }
            $array[] = ["value" => 'VER RESULTADOS DE ' . mb_strtoupper($search), "label" => 'VER RESULTADOS DE ' . mb_strtoupper($search), "link" => URL . "/productos/b/titulo/" . $this->f->normalizar_link($search)];
            return $array;
        }
    }


    /**
     *
     * Traer más de un array 
     *
     * @param    array  $data array con todos los filtros de lo que se desea listar
     * @param    string  $idioma si se entra desde el admin
     * @param    bool  $single si solo se desea traer 1 producto (view)
     * @return   array retorna un array con todos los array internos de productos
     *
     */


    function list($data, $idioma, $single = false)
    {
        $filter = !empty($data['filter']) ? $data['filter'] :  [];
        $category = !empty($data['category']) ? $data['category'] :  false;
        $subcategory = !empty($data['subcategory']) ? $data['subcategory'] :  false;
        $tercercategory = !empty($data['tercercategory']) ? $data['tercercategory'] :  false;
        $images = !empty($data['images']) ? $data['images'] :  false;
        $admin = isset($data['admin']) ? $data['admin'] :  false;
        $promos = isset($data['promos']) ? $data['promos'] :  false;
        $order = !empty($data['order']) ? $data['order'] :  'productos.id DESC';
        $attribute = !empty($data['attribute']) ? $data['attribute'] :  '';
        $combination = !empty($data['combination']) ? $data['combination'] :  '';
        $limit = !empty($data['limit']) ? $data['limit'] :  '';
        $idioma = !empty($idioma) ? $idioma :  $_SESSION['lang'];

        $combinaciones = '';
        $atributos = '';
        $array_ = '';
        $array = array();
        is_array($filter) ? $filter[] = "`productos`.`idioma` = '" . $idioma . "' " : $filter = "`productos`.`idioma` = '" . $idioma . "'";
        $filterSql = "";
        $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' AND ', $filter) : '';
        if ($category) {
            $arrayAttr[] = "`categorias`.`titulo` as 'categoria_titulo',`categorias`.`area` as 'categoria_area', `categorias`.`descripcion` as 'categoria_descripcion'";
            $arrayLeft[] = "LEFT JOIN `categorias` ON `categorias`.`cod` = `productos`.`categoria` AND `categorias`.`idioma` = '$idioma'";
        }
        if ($subcategory) {
            $arrayAttr[] = "`subcategorias`.`titulo`as 'subcategoria_titulo'";
            $arrayLeft[] = "LEFT JOIN `subcategorias` ON `subcategorias`.`cod` = `productos`.`subcategoria` AND `subcategorias`.`idioma` = '$idioma'";
        }
        if ($tercercategory) {
            $arrayAttr[] = "`tercercategorias`.`titulo`as 'tercercategoria_titulo'";
            $arrayLeft[] = "LEFT JOIN `tercercategorias` ON `tercercategorias`.`cod` = `productos`.`tercercategoria` AND `tercercategorias`.`idioma` = '$idioma'";
        }
        if ($promos) {
            $arrayAttr[] = "`promos`.`lleva` as 'promoLleva', `promos`.`paga` as 'promoPaga'";
            $arrayLeft[] = "LEFT JOIN `promos` ON `promos`.`producto` = `productos`.`cod` AND `promos`.`idioma` = '$idioma'";
        }
        if ($images) {
            $arrayAttr[] = "GROUP_CONCAT(`imagenes`.`id` ORDER BY `imagenes`.`orden` SEPARATOR ',') AS 'imagenes_id',
            GROUP_CONCAT(`imagenes`.`orden` ORDER BY `imagenes`.`orden` SEPARATOR ',') AS 'imagenes_orden',
            GROUP_CONCAT(`imagenes`.`ruta` ORDER BY `imagenes`.`orden` SEPARATOR ',') AS 'imagenes_rutas'";
            $arrayLeft[] = "LEFT JOIN `imagenes` ON `imagenes`.`cod` = `productos`.`cod` AND `imagenes`.`idioma` = '$idioma'";
        }

        $attr = isset($arrayAttr) ? " , " . implode(" , ", $arrayAttr) . " " : '';
        $left = isset($arrayLeft) ? implode(" ", $arrayLeft) : '';
        $orderSql = ($order != '') ? $order : " ";
        $limitSql = ($limit != '') ? "LIMIT " . $limit : '';
        $sql = "SELECT `productos`.* $attr FROM `productos` $left $filterSql 
        GROUP BY `productos`.`id` ORDER BY $orderSql $limitSql";
        $producto = $this->con->sqlReturn($sql);
        if ($producto) {
            while ($row = mysqli_fetch_assoc($producto)) {
                $productFecha = $row["fecha"];
                $productFecha = date("Y-m-d", strtotime($productFecha . "+ 7 days"));
                $fecha = ($productFecha >= date("Y-m-d")) ? $_SESSION['lang-txt']['productos']['nuevo'] : '';
                if ($attribute) {
                    $this->atributosClass->set("productoCod", $row['cod']);
                    $this->atributosClass->set("idioma", $idioma);
                    $atributos = $this->atributosClass->list();
                }
                if ($combination) {
                    $this->combinacionClass->set("codProducto", $row['cod']);
                    $this->combinacionClass->idioma = $idioma;
                    $combinaciones = $this->combinacionClass->listByProductCod();
                }
                if (!$admin) {
                    $row =   $this->checkPriceByUser($row, $combinaciones);
                }
                $fav =  (isset($_SESSION['usuarios']['cod'])) ? $this->favorite->view($_SESSION['usuarios']['cod'], $row['cod'], $row['idioma']) : '';
                $link = URL . '/producto/' .  $this->f->normalizar_link(($row['titulo'])) . '/' . $row['cod'];
                $imagesArray = ($images) ? $this->createArrayImages($row) : '';
                if ($images) unset($row["imagenes_id"], $row["imagenes_orden"], $row["imagenes_rutas"]);
                $array_ = ["data" => $row, "nuevo" => $fecha, "images" => $imagesArray, "atributo" => $atributos, "combination" => $combinaciones, "link" => $link, "favorite" => $fav];
                $array[] = $array_;
            }
            return ($single) ? $array_ : $array;
        } else {
            return false;
        }
    }
    public function getAllCods($idioma)
    {
        $array = [];
        $sql = "SELECT `productos`.`cod_producto` FROM `productos` WHERE `cod_producto` != '' AND idioma = '$idioma'";
        $producto = $this->con->sqlReturn($sql);
        if ($producto) {
            while ($row = mysqli_fetch_assoc($producto)) {
                $array[] = $row["cod_producto"];
            }
        }
        return $array;
    }
    public function createArrayImages($row)
    {
        $row['imagenes_id'] = explode(",", $row['imagenes_id']);
        $row['imagenes_orden'] = explode(",", $row['imagenes_orden']);
        $row['imagenes_rutas'] = explode(",", $row['imagenes_rutas']);
        $imagesLength = count($row['imagenes_id']) - 1;
        $images = $this->imagenesClass->checkForProduct($row['cod_producto'], "_");
        for ($i = 0; $i <= $imagesLength; $i++) {
            if ($row["imagenes_id"][$i]) {
                $img = URL . "/" . $row["imagenes_rutas"][$i];
                $imgNombre = str_replace(".webp", '_thumb.webp', $row["imagenes_rutas"][$i]);
                $thumb = $img;
                if (file_exists($imgNombre)) {
                    $thumb = $imgNombre;
                }
                $images[] = ["id" => $row["imagenes_id"][$i], "orden" => $row["imagenes_orden"][$i], "url" => $img, "thumb" => $thumb];
            }
        }
        $images = (count($images)) ? $images : [["url" => URL . "/assets/archivos/sin_imagen.jpg", "thumb" => URL . "/assets/archivos/sin_imagen.jpg"]];
        return $images;
    }

    public function viewByCod($cod_producto)
    {
        $array = [];
        $sql = "SELECT * FROM `productos` WHERE  cod_producto = '$cod_producto' LIMIT 1";
        $productos = $this->con->sqlReturn($sql);
        if ($productos) {
            $row = mysqli_fetch_assoc($productos);
            $row = !empty($row) ? $this->checkPriceByUser($row) : '';
            $array = ["data" => $row];
        }
        return $array;
    }

    function listVariable($variable)
    {
        $array = [];
        $sql = "SELECT DISTINCT $variable FROM `productos` ORDER BY $variable";
        $var = $this->con->sqlReturn($sql);
        if ($var) {
            while ($row = mysqli_fetch_assoc($var)) {
                $array[] = array("data" => $row);
            }
        }
        return $array;
    }


    function listMeli($filter, $order, $limit)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
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

        $sql = "SELECT cod FROM `productos` $filterSql ORDER BY $orderSql $limitSql";
        $producto = $this->con->sqlReturn($sql);
        if ($producto) {
            while ($row = mysqli_fetch_assoc($producto)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }

    //Especiales

    public function reduceStock($cod, $stock, $tipo, $combinacion = '')
    {
        $idioma = $_SESSION['lang'];
        $query = '';
        if (!empty($combinacion["id"]) && $tipo == "pr") {
            $id = $combinacion["id"];
            $sql = "UPDATE `detalle_combinaciones` SET `stock`= `stock` - $stock WHERE `id` = '$id'";
            $query = $this->con->sqlReturn($sql);
        } elseif ($tipo == "pr") {
            $sql = "UPDATE `productos` SET `stock`= `stock` - $stock WHERE `cod` = '$cod' AND `idioma` = '$idioma'";
            $query = $this->con->sqlReturn($sql);
        }
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkPriceByUser($product, $combinacion = [])
    {
        $user = new Usuarios();
        $userSession = (isset($_SESSION["usuarios"]["cod"])) ? $user->refreshSession($_SESSION["usuarios"]["cod"]) : '';
        //validar combinacion
        if (!$combinacion) {
            if ((isset($userSession["minorista"]) && $userSession["minorista"] == 0 && $product['precio_mayorista'] > 0)) {
                $product["precio"]  = $product['precio_mayorista'];
            }
        } else {
            //revisar la combinacion y reaplicar los cambios
            $product["precio"] = (!isset($userSession["minorista"]) || $userSession["minorista"] == 1) ? $combinacion[0]["detail"]["precio"] : $combinacion[0]["detail"]["mayorista"];
        }

        if (!empty($userSession)) {
            $product["precio"] = is_null($userSession["descuento"]) ? $product['precio'] : $product['precio'] - (($product['precio'] * $userSession['descuento']) / 100);
            $product["precio_final"] = !empty($product['precio_descuento']) && $product['precio_descuento'] > 0 ? (!is_null($userSession["descuento"]) ?  $product['precio_descuento'] - (($product['precio_descuento'] * $userSession['descuento']) / 100)  :  $product["precio_descuento"]) : $product["precio"];
            if ($userSession["minorista"] == 0) {
                if (isset($combinacion[0]["detail"]["mayorista"]) && !empty($combinacion[0]["detail"]["mayorista"])) {
                    $product["precio_final"] = intval($combinacion[0]["detail"]["mayorista"]);
                } elseif (!empty($product["precio_mayorista"])) {
                    $product["precio_final"] = $product['precio_mayorista'] != '' ? $product['precio_mayorista'] - (($product['precio_mayorista'] * $userSession['descuento']) / 100) : '';
                } else {
                    $product["precio_final"] = empty($userSession["descuento"]) ? $product['precio'] : $product['precio'] - (($product['precio'] * $userSession['descuento']) / 100);
                }
            }
        } else {
            $product["precio_final"] = !empty($product["precio_descuento"]) ? $product["precio_descuento"] : $product["precio"];
        }
        return $product;
    }


    public function listCodProduct()
    {
        $sql = "SELECT cod_producto FROM `productos`";
        $producto = $this->con->sqlReturn($sql);
        if ($producto) {
            while ($row = mysqli_fetch_assoc($producto)) {
                $array[] = $row['cod_producto'];
            }
            return $array;
        }
    }

    function paginador($filter, $cantidad)
    {
        $filterSql = $this->doAFilter($filter);
        $sql = "SELECT * FROM `productos` $filterSql";
        $contar = $this->con->sqlReturn($sql);
        $total = mysqli_num_rows($contar);
        $totalPaginas = $total / $cantidad;
        return ceil($totalPaginas);
    }

    function doAFilter($filters)
    {
        $filter = [];
        if (!empty($filters)) {
            $filterSql = "WHERE ";
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'categoria':
                        $categoria = $this->categoriasClass->list(["filter" => ["cod = $value"]], "", "", $_SESSION['lang'], true);
                        (!empty($categoria)) ? $filter[] = " (categoria='" . $categoria['data']['cod'] . "') " : false;
                        break;
                    case 'subcategoria':
                        $subcategoria = $this->subcategoriasClass->list(["filter" => ["cod = $value"]], "", "", $_SESSION['lang'], true);
                        (!empty($subcategoria)) ? $filter[] = " (subcategoria='" . $subcategoria['data']['cod'] . "') " : false;
                        break;
                    case 'titulo':
                        $filter[] = " (titulo LIKE '%" . $value . "%')";
                        break;
                }
            }
            $filterSql .= implode(" AND ", $filter);
            return $filterSql;
        } else {
            return '';
        }
    }
    public function viewProductMeliImport($cod)
    {
        $sql = "SELECT * FROM `mercadolibre` WHERE  product = '$cod' ";
        $productos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($productos);
        $array = array("data" => $row);
        return $array;
    }
}
