<?php

namespace Clases;

use Exception;
use Verot\Upload\Upload;

class Imagenes
{

    //Atributos
    public $id;
    public $link;
    public $ruta;
    public $orden;
    public $cod;
    public $idioma;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->f = new PublicFunction();
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

    public function add()
    {
        $sql = "INSERT INTO `imagenes`(`ruta`, `cod`, `orden`, `idioma`) VALUES ({$this->ruta}, {$this->cod},0,{$this->idioma})";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `imagenes` SET ruta = {$this->ruta}, cod = {$this->cod} WHERE `id`={$this->id}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function editAllCod($cod)
    {
        $sql = "UPDATE `imagenes` SET cod = {$this->cod} WHERE `cod`='$cod'";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($array)
    {
        $sql = "SELECT * FROM `imagenes` WHERE id=:id AND idioma=:idioma";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
        while ($row = $stmt->fetch()) {
            try {
                $sql = "SELECT * FROM `imagenes` WHERE ruta =:ruta AND idioma !=:idioma ORDER BY cod DESC";
                $stmt2 = $this->con->conPDO()->prepare($sql);
                $stmt2->execute($row);
                $response = true;
            } catch (Exception $e) {
                $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
            }
            if (empty($stmt->fetchAll())) {
                $file = explode(".", $row["ruta"]);
                $files = $row["ruta"];
                $filesx1 = $file[0] . "_x1." . $file[1];
                $filesx2 = $file[0] . "_x2." . $file[1];
                @unlink("../" . $files);
                @unlink("../" . $filesx1);
                @unlink("../" . $filesx2);
            }
            try {
                $sqlDelete = "DELETE FROM `imagenes` WHERE id=:id AND idioma=:idioma";
                $stmt = $this->con->conPDO()->prepare($sqlDelete);
                $stmt->execute($array);
                $response = true;
            } catch (Exception $e) {
                $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
            }
            return $response;
        }
    }

    public function deleteAll($array)
    {
        $sql = "SELECT * FROM `imagenes` WHERE cod=:cod AND idioma=:idioma ORDER BY cod DESC";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
        while ($row = $stmt->fetch()) {
            unset($row["orden"], $row["id"], $row["cod"]);
            try {
                $sql = "SELECT * FROM `imagenes` WHERE ruta =:ruta AND idioma !=:idioma ORDER BY cod DESC";
                $stmt2 = $this->con->conPDO()->prepare($sql);
                $stmt2->execute($row);
                $response = true;
            } catch (Exception $e) {
                $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
            }
            if (empty($stmt2->fetchAll())) {
                $file = explode(".", $row["ruta"]);
                $files = $row["ruta"];
                $filesx1 = $file[0] . "_x1." . $file[1];
                $filesx2 = $file[0] . "_x2." . $file[1];
                unlink("../" . $files);
                @unlink("../" . $filesx1);
                @unlink("../" . $filesx2);
            }
        }

        try {
            $sqlDelete = "DELETE FROM `imagenes` WHERE cod=:cod AND idioma=:idioma";
            $stmt = $this->con->conPDO()->prepare($sqlDelete);
            $stmt->execute($array);
            $response = true;
        } catch (Exception $e) {
            $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->getMessage();
        }
        return $response;
    }

    public function view($cod)
    {
        $idioma = str_replace("''", "'", $this->idioma);
        $sql = ($this->idioma) != '' ?  "SELECT * FROM `imagenes` WHERE `cod` = '$cod' AND `idioma` = $idioma ORDER BY id ASC" :  "SELECT * FROM `imagenes` WHERE `cod` = '$cod' ORDER BY id ASC";
        $imagenes = $this->con->sqlReturn($sql);
        if (!empty($imagenes)) {
            $row = mysqli_fetch_assoc($imagenes);
        } else {
            $row = false;
        }
        return $row;
    }


    /**
     *
     * Mandamos la ruta de una imagen y nos de vuelve la misma pero con sus tamaños inferiores
     *
     * @param    string  $variable un string con la ruta de la imagen
     * @return    array retorna un array con 2 opciones $variable["x1"] y variable["x2"]
     *
     */


    function selectImageSize($variable)
    {
        $variable = str_replace(".jpg", "", $variable);
        $urlx1 = dirname(__DIR__) . "/" .  $variable . '_x1.jpg';
        $urlx2 = dirname(__DIR__) . "/" .  $variable . '_x2.jpg';
        $ruta["x1"] =  (@getimagesize($urlx1) ?  $variable . '_x1.jpg' :  'assets/archivos/sin_imagen.jpg');
        $ruta["x2"] =  (@getimagesize($urlx2) ?  $variable . '_x2.jpg' :  'assets/archivos/sin_imagen.jpg');
        return $ruta;
    }

    function list($filter, $order = '', $limit = '', $single = false)
    {
        $array = array();
        $array_ = array();
        foreach ($filter as $key => $value) {
            $filters[] = $key . "=:" . $key;
        }
        $filterSql = implode(" AND ", $filters);
        $orderSql = ($order != '') ?  $order  : "`id` DESC";
        $limitSql = ($limit != '') ? "LIMIT " . $limit : '';
        $sql = "SELECT * FROM `imagenes` WHERE $filterSql ORDER BY $orderSql $limitSql";
        try {
            $stmt = $this->con->conPDO()->prepare($sql);
            $stmt->execute($filter);
            while ($row = $stmt->fetch()) {
                $array_ = $row;
                $array[] = $array_;
            }
            $response = ($single) ? $array_ : $array;
        } catch (Exception $e) {
            $response['error'] = ($_ENV["DEBUG"]) ? (array)$e : $e->errorInfo;
        }
        return $response;
    }



    function checkForProduct($variable)
    {
        $images = [];
        if (strpos($variable, '|')) {
            $variable = explode('|', $variable)[0];
        }
        $matches = !empty($variable) ? glob(dirname(__DIR__) . '/assets/archivos/productos/' . $variable . '[!{_thumb}]*') : '';
        if (!empty($variable)) $matches = array_merge($matches, glob(dirname(__DIR__) . '/assets/archivos/productos/' . $variable . '_*[!{_thumb}].*'));
        if (is_array($matches)) $matches = (array_filter($matches));
        if (is_array($matches)) {
            foreach ($matches as $filename) {
                $img = URL . "/assets/" . explode("/assets/", $filename)[1];
                $thumb = $img;
                $thumbImg = str_replace('.webp', '_thumb.webp', $filename);
                if (file_exists($thumbImg)) {
                    $thumb = URL . "/assets/" . explode("/assets/", $thumbImg)[1];
                }
                $images[] = ["url" => $img, "thumb" => $thumb];
            }
        }
        return $images;
    }


    function listValidation($cod)
    {
        $array = array();
        $sql = "SELECT * FROM `imagenes` WHERE cod = '$cod' ORDER BY id ASC";
        $imagenes = $this->con->sqlReturn($sql);
        if ($imagenes->num_rows == 0) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($imagenes)) {
                $array[] = $row;
            }
            return $array;
        }
    }


    public function setOrder()
    {
        $sql = "UPDATE `imagenes` SET orden = {$this->orden} WHERE id = {$this->id}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function resizeImages($cod, $files, $path, $final_name = "", $idioma, $thumb = false)
    {
        foreach ($files['name'] as $f => $name) {
            $name = (!empty($final_name)) ? $final_name : $cod;
            $file = [
                "name" => $files["name"][$f],
                "type" => $files["type"][$f],
                "tmp_name" => $files["tmp_name"][$f],
                "error" => $files["error"][$f],
                "size" => $files["size"][$f]
            ];
            $final_name_image = $this->f->normalizar_titulo_imagenes($name) . "" . substr(md5(uniqid(rand())), 0, 10);
            $handle = new Upload($file);
            $handle->uploaded;
            foreach ($idioma as $idiomaItem) {
                $lang = isset($idiomaItem['data']['cod']) ? $idiomaItem['data']['cod'] : $idiomaItem;
                $newName = $final_name_image . "_" . $lang;
                $handle->file_new_name_body   = $newName;
                $handle->image_resize         = true;
                $handle->image_x              = 1920;
                $handle->image_ratio_y        = true;
                $handle->image_no_enlarging = true;
                $handle->webp_quality = 70;
                $handle->image_convert = 'webp';
                $handle->process(dirname(__DIR__, 1) . '/' . $path);
                // echo $handle->log;
                if ($handle->processed) {
                    $final_path = $path . '/' . $newName . '.webp';
                    $this->set("cod", $cod);
                    $this->set("ruta",  $final_path);
                    $this->set("idioma",  $lang);
                    $this->add();
                    if ($thumb) {
                        $handle->file_new_name_body   = $newName . '_thumb';
                        $handle->image_resize         = true;
                        $handle->image_x              = 400;
                        $handle->image_ratio_y        = true;
                        $handle->image_no_enlarging = true;
                        $handle->webp_quality = 85;
                        $handle->image_convert = 'webp';
                        $handle->process(dirname(__DIR__, 1) . '/' . $path);
                        $handle->processed;
                    }
                } else {
                    echo 'error : ' . $handle->error;
                }
            }
            $handle->clean();
        }
    }


    public function uploadFileInFolder($files, $path, $final_name)
    {
        $file = [
            "name" => $files["name"],
            "type" => $files["type"],
            "tmp_name" => $files["tmp_name"],
            "error" => $files["error"],
            "size" => $files["size"]
        ];
        $handle = new Upload($file);
        $handle->uploaded;
        $size = 1920;
        $name = $name = str_replace([".png", ".jpg", ".jpeg", ".gif"], "", $final_name);
        for ($i = 0; $i <= 1; $i++) {
            if ($i == 1) {
                $name = $name . "_thumb";
                $size = 400;
            }
            $handle->file_new_name_body   = $name;
            $handle->image_x              = $size;
            $handle->image_resize         = true;
            $handle->image_ratio_y        = true;
            $handle->image_no_enlarging   = true;
            $handle->webp_quality = 85;
            $handle->image_convert = 'webp';
            $handle->process(dirname(__DIR__, 1) . '/' . $path);
            // echo $handle->log;
            if ($handle->processed) {
                echo 'Cargado con exito';
            } else {
                echo 'error : ' . $handle->error;
            }
        }
        $handle->clean();
    }


    function selectSize($file_image, $x = false)
    {
        $file = explode(".", $file_image);
        $name_file = ($x) ? $file[0] . "_x$x." . $file[1] : $file_image;
        return $name_file;
    }
}
