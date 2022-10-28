<?php

namespace Clases;

use Exception;

class Categorias
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $area;
    public $descripcion;
    public $idioma;
    public $orden;

    private $con;
    private $subcategoria;
    private $imagenes;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->subcategoria = new Subcategorias();
        $this->imagenes = new Imagenes();
    }

    public function set($atributo, $valor)
    {
        if (strlen($valor)) {
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

    public function add($array)
    {
        $attr = implode(",", array_keys($array));
        $values = ":" . str_replace(",", ",:", $attr);
        $sql = "INSERT INTO categorias ($attr) VALUES ($values)";
        try {
            $stmt = $this->con->conPDO()->prepare($sql);
            $stmt->execute($array);
            $response = true;
        } catch (Exception $e) {
            $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
        }
        return $response;
    }
    public function edit($array, $cod)
    {
        $query = implode(", ", array_map(function ($v) {
            return "$v=:$v";
        }, array_keys($array)));
        $condition = implode(' AND ', $cod);
        $sql = "UPDATE categorias SET $query WHERE $condition";
        try {
            $stmt = $this->con->conPDO()->prepare($sql);
            $stmt->execute($array);
            $response = true;
        } catch (Exception $e) {
            $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
        }
        return $response;
    }

    public function delete($array)
    {
        $sql = "DELETE FROM `categorias` WHERE cod=:cod AND idioma=:idioma";
        try {
            $stmt = $this->con->conPDO()->prepare($sql);
            $stmt->execute($array);
            if (!empty($this->imagenes->list($array, "", "", true))) {
                $this->imagenes->deleteAll($array);
            }
            $response = true;
        } catch (Exception $e) {
            $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
        }
        return $response;
    }
    public function searchCategoryForArea($area)
    {
        $array = [];
        $sql = "SELECT * FROM `categorias` WHERE `area`  = '$area'";
        $query = $this->con->sqlReturn($sql);
        if ($query->num_rows) {
            while ($row = mysqli_fetch_assoc($query)) {
                $array[] = array("data" => $row);
            }
        }
        return $array;
    }
    public function deleteForArea($area, $idioma)
    {
        $categoriasArea  = $this->searchCategoryForArea($area);
        foreach ($categoriasArea as $catItem) {
            $cod = $catItem['data']['cod'];
            if (!empty($this->imagenes->list(["cod" => $cod, "idioma" => $idioma], "", "", true))) {
                $this->imagenes->deleteAll(["cod" => $cod, "idioma" => $idioma]);
            }
        }
        $sql = "DELETE FROM `categorias` WHERE `area`  =  '$area' AND `idioma` = '$idioma'";
        $query = $this->con->sqlReturn($sql);
    }



    public function list($filter = [], $order = '', $limit = '', $idioma, $single = false,  $images = true)
    {
        $array = array();
        $filter[] = "idioma = '" . $idioma . "'";
        $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' AND ', $filter) : '';

        $orderSql = ($order != '') ?  $order  : "`id` DESC";
        $limitSql = ($limit != '') ? "LIMIT " . $limit : '';

        $sql = "SELECT * FROM `categorias` $filterSql ORDER BY $orderSql $limitSql";
        $categorias = $this->con->sqlReturn($sql);
        if (!empty($categorias) && $categorias->num_rows) {
            while ($row = mysqli_fetch_assoc($categorias)) {
                $img = $this->imagenes->list(["cod" => $row['cod'], "idioma" => $idioma], 'imagenes.orden ASC', '');
                $sub = $this->subcategoria->list(["categoria='" . $row['cod'] . "'"], '', '', $idioma);
                $array_ = array("data" => $row, "subcategories" => $sub, "images" => $img);
                $array[] = $array_;
            }
            return ($single) ? $array_ : $array;
        } else {
            return false;
        }
    }

    /**
     *
     * Traer un array con todas las categorias que esten en uso en la base de datos buscada.
     *
     * @param    string $db nombre de la base de datos de la cual se desea traer las categorias.
     * @param    string  $area en caso de que la base de datos sea Contenido podes identificar un area en especifico.
     * @return   array retorna un array con toda la informacion de las categorias y la cantidad de veces que se usa.
     *
     */
    public function listIfHave($db, $area = '', $idioma = '')
    {
        $idioma = ($idioma) ? $idioma : $_SESSION['lang'];
        if (!empty($area)) {
            $area = ($area != 'productos') ? " AND `categorias`.`area` = '$area' " :  " AND `categorias`.`area` = '$area' AND productos.mostrar_web = 1  AND productos.idioma = '$idioma'";
        }
        $productos = ($db == 'productos') ? " AND  productos.mostrar_web = 1 " : "";
        $array = array();
        $sql = " SELECT `categorias`.`titulo`,`categorias`.`cod`,`categorias`.`id`, count(`" . $db . "`.`categoria`) as cantidad FROM `" . $db . "`,`categorias` WHERE categorias.idioma = '$idioma'  AND  `categoria` = `categorias`.`cod` $area  $productos GROUP BY categoria ORDER BY `categorias`.`orden` ASC , `categorias`.`titulo` ASC ";

        $listIfHave = $this->con->sqlReturn($sql);
        if ($listIfHave) {
            while ($row = mysqli_fetch_assoc($listIfHave)) {
                $img = $this->imagenes->list(["cod" => $row['cod'], "idioma" => $idioma], 'imagenes.orden ASC', '');
                $sub = $this->subcategoria->listIfHave($db, $row["cod"], $idioma);
                $array[] = array("data" => $row, "subcategories" => $sub, "images" => $img);
            }
            return $array;
        }
    }
    public function listExcel($filter = [], $order = '', $limit = '', $idioma)
    {
        $array = array();
        $filter[] = "idioma = '" . $idioma . "'";
        $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' AND ', $filter) : '';
        $orderSql = ($order != '') ?  $order  : "`id` DESC";
        $limitSql = ($limit != '') ? "LIMIT " . $limit : '';

        $sql = "SELECT `categorias`.`cod`, `categorias`.`titulo` FROM `categorias` $filterSql ORDER BY $orderSql $limitSql";
        $categorias = $this->con->sqlReturn($sql);
        if ($categorias->num_rows) {
            while ($row = mysqli_fetch_assoc($categorias)) {
                $sub = $this->subcategoria->listExcel(["categoria='" . $row['cod'] . "'"], '', '', $idioma);
                $array_ = array("titulo" => $row['titulo'], "subcategorias" => $sub);
                $array[$row['cod']] = $array_;
            }
            return $array;
        } else {
            return false;
        }
    }


    public function listForManyCods($cods, $idioma)
    {
        $array = [];
        $categoriasFilterStr = '';
        foreach ($cods as $cod) {
            $categoriasFilterStr .= "cod = '" . $cod . "' OR ";
        }
        $categoriasFilterStr = substr($categoriasFilterStr, 0, -4);

        $sql = "SELECT * FROM `categorias` WHERE $categoriasFilterStr AND idioma = '$idioma'";
        $categorias = $this->con->sqlReturn($sql);
        if ($categorias->num_rows) {
            while ($row = mysqli_fetch_assoc($categorias)) {
                $sub = $this->subcategoria->list(["categoria='" . $row['cod'] . "'"], '', '', $row["idioma"]);
                $array[] = array("data" => $row, "subcategories" => $sub);
            }
            return $array;
        }
    }

    public function listSubcategoriesForManyCods($cods, $idioma)
    {

        $categoriasArray = $this->list(["area = 'productos'"], "", "", $idioma);
        $subcategoriasSavedArray = [];
        if ($categoriasArray) {
            foreach ($categoriasArray as $categoria) {
                foreach ($categoria["subcategories"] as $subcategoria) {
                    $subcategoria["categoriaTitulo"] = $categoria['data']['titulo'];
                    if (in_array($subcategoria['data']['cod'], $cods)) {
                        $subcategoriasSavedArray[] = $subcategoria;
                    }
                }
            }
        }
        return $subcategoriasSavedArray;
    }

    public function listForDiscount()
    {

        $array = array();
        $sql = " SELECT `categorias`.`titulo`,`categorias`.`cod` FROM `categorias` WHERE `area` = 'productos' AND `idioma` = '$this->idioma' ";
        $listDiscount = $this->con->sqlReturn($sql);
        if ($listDiscount) {
            while ($row = mysqli_fetch_assoc($listDiscount)) {
                $sub = $this->subcategoria->listForDiscount($row['cod']);
                $array[] = array("data" => $row, "subcategories" => $sub);
            }
            return $array;
        }
    }
    public function listAreas()
    {
        $array = array();
        $sql = "SELECT * FROM `categorias` GROUP BY area";
        $areas = $this->con->sqlReturn($sql);
        if ($areas->num_rows) {
            while ($row = mysqli_fetch_assoc($areas)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }
    public function editSingle($atributo, $valor)
    {
        $sql = "UPDATE `categorias` SET `$atributo` = {$valor} WHERE `cod`={$this->cod} AND `idioma` = {$this->idioma}";
        if ($this->con->sqlReturn($sql)) {
            return true;
        } else {
            return false;
        }
    }


    public function countContents($filter = [], $idioma)
    {
        $filter[] = "categorias.idioma = '" . $idioma . "'";
        $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' AND ', $filter) : '';
        $sql = "SELECT COUNT(*) as cantidad FROM `categorias` $filterSql ";
        $query = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($query);
        return $row["cantidad"];
    }
    public function paginador($url, $filter, $limit, $page = 1, $range = 1, $friendly = true)
    {
        $separator = ($friendly) ? '/p/' : '&pagina=';
        $count = $this->countContents([$filter], $_SESSION["lang"]);
        $total = ceil($count / $limit);
        $pre = $page - 1;
        $next = $page + 1;
        $html = "<nav class='pagination-section mt-30'>";
        $html .=  "<ul class='pagination justify-content-center'>";
        if ($pre > 0) {
            $html .= "<li class='page-item' style='height:5px'>";
            $html .= "<a class='page-link' href='" . $url . $separator . "1'><i class='fa fa-angle-double-left'></i></a>";
            $html .=  "</li>";
            $html .=  "<li class='page-item'>";
            $html .=  "<a class='page-link' href='" . $url . $separator . $pre . "'><i class='fa fa-angle-left'></i></a>";
            $html .=  "</li>";
        }
        foreach (range($page - $range, $page + $range) as $i) {
            if ($i > 0 && $i <= $total) {
                $active = ($i == $page) ? 'active' : '';
                $html .=  "<li class='page-item $active'>";
                $html .=  "<a class='page-link' href='" . $url . $separator . $i . "'>$i</a>";
                $html .=  "</li>";
            }
        }
        if ($next <= $total) {
            $html .= "<li class='page-item'>";
            $html .= "<a class='page-link' href='" . $url . $separator . $next . "'><i class='fa fa-angle-right'></i></a>";
            $html .=  "</li>";
            $html .= "<li class='page-item' style='height:5px'>";
            $html .= "<a class='page-link' href='" . $url . $separator . $total . "'><i class='fa fa-angle-double-right'></i></a>";
            $html .=  "</li>";
        }
        $html .=  "</ul>";
        $html .=  "</nav>";
        if ($total <= 1) $html = '';
        return $html;
    }
}
