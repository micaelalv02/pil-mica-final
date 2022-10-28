<?php

namespace Clases;


class PublicFunction
{

    public function antihackMulti($array)
    {
        foreach ($array as $key => $value) {
            $data[$key] = $value;
            if (is_array($value)) {
                $this->antihackMulti($value);
            } else {
                if ($value != strip_tags($value)) continue;
                $data[$key] = ($value != '') ? $this->antihack_mysqli($value) : null;
            }
        }
        return $data;
    }


    public function antihack_mysqli($str)
    {
        $con = new Conexion();
        $conexion = $con->con();
        $str = mysqli_real_escape_string($conexion, $str);
        return $str;
    }

    public function antihack($str)
    {
        $str = stripslashes($str);
        $str = strip_tags($str);
        $str = htmlentities($str);
        return $str;
    }
    public function getPermissions($pageSite, $permissions)
    {
        $permisos = '';
        foreach ($permissions as $permission) {
            if ((strstr($permission["link"], "op=" . $pageSite["op"])) || (strstr($permission["link"], "area=" . $pageSite["area"]))) {
                if ($permission["opciones"] == 1) {
                    $permisos = ["editar" => ($permission["editar"] == "1") ? true : false, "crear" => ($permission["crear"] == "1") ? true : false, "eliminar" => ($permission["eliminar"] == "1") ? true : false];
                } else {
                    $permisos = ["editar" => true, "crear" => true, "eliminar" => true];
                }
            }
        }
        return $permisos;
    }
    public function headerMove($location)
    {
        echo "<script> document.location.href='" . $location . "';</script>";
    }


    public function changeRootContents($old, $new)
    {

        $con = new Conexion();
        $sql = "UPDATE contenidos SET contenido = REPLACE(contenido, '" . $old . "', '" . $new . "') WHERE contenido LIKE ('%" . $old . "%')";
        $sql2 = "UPDATE productos SET `desarrollo` = REPLACE(`desarrollo`, '" . $old . "', '" . $new . "') WHERE `desarrollo` LIKE ('%" . $old . "%')";
        $con->sql($sql);
        $con->sql($sql2);
    }


    public function normalizar_link($string)
    {
        $string = str_replace("á", "a", $string);
        $string = str_replace("Á", "A", $string);
        $string = str_replace("ä", "a", $string);
        $string = str_replace("Ä", "A", $string);
        $string = str_replace("é", "e", $string);
        $string = str_replace("ë", "e", $string);
        $string = str_replace("Ë", "E", $string);
        $string = str_replace("É", "E", $string);
        $string = str_replace("í", "i", $string);
        $string = str_replace("ì", "i", $string);
        $string = str_replace("ï", "i", $string);
        $string = str_replace("Í", "I", $string);
        $string = str_replace("Ï", "I", $string);
        $string = str_replace("Ì", "I", $string);
        $string = str_replace("ó", "o", $string);
        $string = str_replace("Ó", "O", $string);
        $string = str_replace("ö", "o", $string);
        $string = str_replace("Ö", "O", $string);
        $string = str_replace("ú", "u", $string);
        $string = str_replace("Ú", "U", $string);
        $string = str_replace("Ü", "U", $string);
        $string = str_replace("ü", "u", $string);
        $string = str_replace(" ", "-", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("ñ", "n", $string);
        $string = str_replace("Ñ", "N", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("?", "", $string);
        $string = str_replace("¿", "", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace("*", "", $string);
        $string = str_replace("#", "", $string);
        $string = str_replace("~", "", $string);
        $string = str_replace("_", "", $string);
        $string = str_replace("'", "", $string);
        $string = str_replace("\"", "", $string);
        $string = str_replace("¡", "", $string);
        $string = str_replace("/", "", $string);
        $string = str_replace(",", "", $string);
        $string = str_replace(";", "", $string);
        $string = str_replace("(", "", $string);
        $string = str_replace(")", "", $string);
        $string = str_replace("+", "", $string);
        $string = str_replace(".", "", $string);
        $string = str_replace("°", "", $string);
        $string = str_replace("%", "", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace("º", "", $string);
        $string = str_replace("$", "", $string);
        $string = str_replace("´", "", $string);
        $string = str_replace("^", "", $string);
        $string = str_replace("}", "", $string);
        $string = str_replace("'", "", $string);
        $string = str_replace('"', "", $string);
        $string = str_replace('`', "", $string);
        $string = str_replace('´', "", $string);
        $string = str_replace("{", "", $string);
        $string = str_replace("_", "", $string);
        $string = str_replace(":", "", $string);
        $string = strtolower($string);
        //para ampliar los caracteres a reemplazar agregar lineas de este tipo:
        //$string = str_replace("caracter - que - queremos - cambiar","caracter - por - el - cual - lo - vamos - a - cambiar",$string);
        return $string;
    }
    public function normalizar_titulo_imagenes($string)
    {
        $string = str_replace("á", "a", $string);
        $string = str_replace("Á", "A", $string);
        $string = str_replace("ä", "a", $string);
        $string = str_replace("Ä", "A", $string);
        $string = str_replace("é", "e", $string);
        $string = str_replace("ë", "e", $string);
        $string = str_replace("Ë", "E", $string);
        $string = str_replace("É", "E", $string);
        $string = str_replace("í", "i", $string);
        $string = str_replace("ì", "i", $string);
        $string = str_replace("ï", "i", $string);
        $string = str_replace("Í", "I", $string);
        $string = str_replace("Ï", "I", $string);
        $string = str_replace("Ì", "I", $string);
        $string = str_replace("ó", "o", $string);
        $string = str_replace("Ó", "O", $string);
        $string = str_replace("ö", "o", $string);
        $string = str_replace("Ö", "O", $string);
        $string = str_replace("ú", "u", $string);
        $string = str_replace("Ú", "U", $string);
        $string = str_replace("Ü", "U", $string);
        $string = str_replace("ü", "u", $string);
        $string = str_replace(" ", "", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("ñ", "n", $string);
        $string = str_replace("Ñ", "N", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("?", "", $string);
        $string = str_replace("¿", "", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace("*", "", $string);
        $string = str_replace("#", "", $string);
        $string = str_replace("~", "", $string);
        $string = str_replace("_", "", $string);
        $string = str_replace("'", "", $string);
        $string = str_replace("\"", "", $string);
        $string = str_replace("¡", "", $string);
        $string = str_replace("/", "", $string);
        $string = str_replace(",", "", $string);
        $string = str_replace(";", "", $string);
        $string = str_replace("(", "", $string);
        $string = str_replace(")", "", $string);
        $string = str_replace("+", "", $string);
        $string = str_replace(".", "", $string);
        $string = str_replace("°", "", $string);
        $string = str_replace("%", "", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace("º", "", $string);
        $string = str_replace("$", "", $string);
        $string = str_replace("´", "", $string);
        $string = str_replace("^", "", $string);
        $string = str_replace("}", "", $string);
        $string = str_replace("'", "", $string);
        $string = str_replace('"', "", $string);
        $string = str_replace('`', "", $string);
        $string = str_replace('´', "", $string);
        $string = str_replace("{", "", $string);
        $string = str_replace("_", "", $string);
        $string = str_replace(":", "", $string);
        $string = str_replace("-", "", $string);
        $string = preg_replace(array('/ +/', '/-+/'), '-', $string);
        $string = preg_replace(array('/-*\.-*/', '/\.{2,}/'), '.', $string);
        $string = trim(strtolower($string));
        //para ampliar los caracteres a reemplazar agregar lineas de este tipo:
        //$string = str_replace("caracter - que - queremos - cambiar","caracter - por - el - cual - lo - vamos - a - cambiar",$string);
        return $string;
    }


    public function normalizar_meli($string)
    {
        $string = str_replace("á", "a", $string);
        $string = str_replace("Á", "A", $string);
        $string = str_replace("ä", "a", $string);
        $string = str_replace("Ä", "A", $string);
        $string = str_replace("é", "e", $string);
        $string = str_replace("ë", "e", $string);
        $string = str_replace("Ë", "E", $string);
        $string = str_replace("É", "E", $string);
        $string = str_replace("í", "i", $string);
        $string = str_replace("ï", "i", $string);
        $string = str_replace("Í", "I", $string);
        $string = str_replace("Ï", "I", $string);
        $string = str_replace("Ì", "I", $string);
        $string = str_replace("ó", "o", $string);
        $string = str_replace("Ó", "O", $string);
        $string = str_replace("ö", "o", $string);
        $string = str_replace("Ö", "O", $string);
        $string = str_replace("ú", "u", $string);
        $string = str_replace("Ú", "U", $string);
        $string = str_replace("Ü", "U", $string);
        $string = str_replace("ü", "u", $string);
        $string = str_replace(" ", "%20", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("ñ", "n", $string);
        $string = str_replace("Ñ", "N", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("?", "", $string);
        $string = str_replace("¿", "", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace("*", "", $string);
        $string = str_replace("#", "", $string);
        $string = str_replace("~", "", $string);
        $string = str_replace("_", "", $string);
        $string = str_replace("'", "", $string);
        $string = str_replace("\"", "", $string);
        $string = str_replace("¡", "", $string);
        $string = str_replace("/", "", $string);
        $string = str_replace(",", "", $string);
        $string = str_replace(";", "", $string);
        $string = str_replace("(", "", $string);
        $string = str_replace(")", "", $string);
        $string = str_replace("+", "", $string);
        $string = str_replace(".", "", $string);
        $string = str_replace("°", "", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace("º", "", $string);
        $string = str_replace("$", "", $string);
        $string = str_replace("´", "", $string);
        $string = str_replace("^", "", $string);
        $string = str_replace("}", "", $string);
        $string = str_replace("{", "", $string);
        $string = str_replace("_", "", $string);
        $string = str_replace(":", "", $string);
        $string = strtolower($string);
        //para ampliar los caracteres a reemplazar agregar lineas de este tipo:
        //$string = str_replace("caracter - que - queremos - cambiar","caracter - por - el - cual - lo - vamos - a - cambiar",$string);
        return $string;
    }


    public function leftJoin($table_main, $table_join, $attr_join, $attr_main, $attr_show)
    {
        if (is_array($attr_show)) {
            foreach ($attr_show as $attr_print) {
                $show[] = ["`$table_join`.`$attr_print` as `$table_join" . "_" . "$attr_print` "];
            }
        } else {
            $show = "`$table_join`.`$attr_show` as `$table_join" . "_" . "$attr_show` ";
        }
        $join = " LEFT JOIN `$table_join` ON `$table_join`.`$attr_join` = `$table_main`.`$attr_main` ";

        return array("show" => $show, "join" => $join);
    }



    function curl($method, $url, $data)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure" . curl_error($curl) . " " . curl_errno($curl));
        }
        curl_close($curl);
        return $result;
    }

    function curlML($method, $url, $data)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure" . curl_error($curl) . " " . curl_errno($curl));
        }
        curl_close($curl);
        return $result;
    }

    function curlXML($method, $url, $data)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/xml',
        ));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure" . curl_error($curl) . " " . curl_errno($curl));
        }
        curl_close($curl);
        return $result;
    }


    public function localidades()
    {
        $con = new Conexion();
        $palabra = ($_GET["elegido"]);
        $sql = "SELECT  distinct `_provincias`.`nombre`,`_localidades`.`nombre` FROM  `_localidades` , `_provincias` WHERE  `_localidades`.`provincia_id` =  `_provincias`.`id` AND `_provincias`.`nombre`  LIKE '%$palabra%' AND `_localidades`.`nombre` != '' ORDER BY `_localidades`.`nombre`";
        $notas = $con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($notas)) {
            echo strtoupper($row["nombre"]) . ";";
        }
    }

    public function provincias($default = '')
    {
        $con = new Conexion();
        $sql = "SELECT `nombre` FROM  `_provincias` ORDER BY nombre";
        $provincias = $con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($provincias)) {
            $selected = ($row['nombre'] == $default) ? 'selected' : '';
            echo '<option value="' . $row['nombre'] . '" ' . $selected . '>' . mb_strtoupper($row['nombre']) . '</option>';
        }
    }

    public function fileExists($url)
    {
        return (@fopen($url, "r") == true);
    }

    public function eliminar_get($url, $varname)
    {
        $parsedUrl = parse_url($url);
        $query = array();

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
            unset($query[$varname]);
        }

        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        $query = !empty($query) ? '?' . http_build_query($query) : '';

        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $path . $query;
    }

    public function variables_get_input($hidden)
    {
        foreach ($_GET as $key => $val) {
            if ($key == "pagina") {
            } else {
                if ($key != $hidden) {
                    echo "<input type='hidden' name='" . $key . "' value='" . $val . "' />";
                }
            }
        }
    }

    public function normalize($string)
    {
        $utf8 = [
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        ];
        $string = preg_replace(array_keys($utf8), array_values($utf8), $string);

        $first = '/[^A-Za-z0-9\ ';
        $end = '-]/';

        $string = preg_replace($first . $end, ' ', $string);

        return $string;
    }

    function parseInput()
    {
        $data = file_get_contents("php://input");
        if ($data == false)
            return array();

        parse_str($data, $result);
        return $result;
    }

    function roundUpToAny($n, $x = 5)
    {
        return (ceil($n) % $x === 0) ? ceil($n) : round(($n + $x / 2) / $x) * $x;
    }
    public function duplicate($table, $originalLang, $selectedLang, $where)
    {
        $con = new Conexion();
        foreach ($table as $tableItem) {
            foreach ($selectedLang as $idiomaItem) {
                $sqlCheck = "SELECT * FROM $tableItem WHERE `idioma` = '$originalLang' LIMIT 1";
                $row =  $con->sqlReturn($sqlCheck);
                if (!empty($row->num_rows)) {
                    #CREATE TEMPORAL TABLE
                    $sqlCreate = "CREATE TABLE `.$tableItem` LIKE `$tableItem`";
                    $con->sqlReturn($sqlCreate);
                    #END

                    #INSERT IN TEMPORAL TABLE
                    if ($tableItem == "categorias") {
                        $sqlInsert = "INSERT INTO `.$tableItem` SELECT * FROM $tableItem WHERE `idioma` = '$originalLang' $where";
                    } else {
                        $sqlInsert = "INSERT INTO `.$tableItem` SELECT * FROM $tableItem WHERE `idioma` = '$originalLang' ";
                    }
                    $con->sqlReturn($sqlInsert);
                    #END

                    #SELECT MAX ID AND EDIT AUTO_INCREMENT
                    $sqlMaxId = "SELECT MAX(id) as id FROM $tableItem";
                    $maxId = $con->sqlReturn($sqlMaxId);
                    $maxId = mysqli_fetch_assoc($maxId);
                    $id = intval($maxId['id']) + 1;
                    #END

                    #UPDATE IDIOMA IN TEMPORAL TABLE IDIOMA AND ID
                    $sqlUpdate = "UPDATE `.$tableItem` SET idioma = '$idiomaItem'";
                    $con->sqlReturn($sqlUpdate);
                    $sqlUpdateId = "ALTER TABLE `.$tableItem` DROP id";
                    $con->sqlReturn($sqlUpdateId);
                    $sqlCreateNewId = "ALTER TABLE `.$tableItem` ADD id INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (id), AUTO_INCREMENT=$id";
                    $con->sqlReturn($sqlCreateNewId);
                    #END

                    #INSERT TEMPORAL TABLE IN ORIGINAL TABLE

                    $sqlMerge = "INSERT IGNORE INTO `$tableItem` SELECT * FROM `.$tableItem`";
                    $con->sqlReturn($sqlMerge);
                    #END

                    #DROP TEMPORAL TABLE
                    $sqlDrop = "DROP TABLE IF EXISTS `.$tableItem`";
                    $con->sqlReturn($sqlDrop);
                    #END
                }
            }
        }
    }
    /**
     *
     * CREAR UNA NUEVA BASE DE DATOS 
     *
     * @param    string  $newDB Nombre de la nueva db
     * @param    string  $oldDB Nombre de la db de la cual queremos copiar la estructura
     *
     */

    public function duplicateAllDB($newDB, $oldDB)
    {
        $this->initCon = new Conexion();

        #CREO LA BASE DE DATOS
        $sqlCreateDB = "CREATE DATABASE `$newDB`";
        $this->initCon->sql($sqlCreateDB);
        #END


        #TRAIGO LOS NOMBRES DE LA BASE DE DATOS
        $sqlShow = "SHOW TABLES IN `$oldDB`";
        $tables = $this->initCon->sqlReturn($sqlShow);
        $arrayTable = [];
        $tableForInsert = [];
        #END

        #SELECCIONO UN ORDEN DE CARGA
        $orderPriority = ["idiomas", "area", "categorias", "subcategorias", "tercercategorias", "contenidos", "productos", "envios", "estados_pedidos", "pagos", "estado", "atributos", "subatributos", "combinaciones", "usuarios", "pedidos", "detalle_pedidos"];
        $count = count($orderPriority);
        #END


        #CREO LA ESTRUCTURA DE LAS TABLAS
        while ($table = mysqli_fetch_assoc($tables)) {
            $table = $table['Tables_in_' . $oldDB];
            $tables_ = $this->initCon->sqlReturn("SHOW CREATE TABLE `$table`");
            while ($table_ = mysqli_fetch_assoc($tables_)) {
                #GUARDO LAS SENTENCIAS SQL DE LAS TABLAS EN EL ORDEN QUE NECESITO
                $priority = array_keys($orderPriority, $table);
                $tableSql = str_replace("CREATE TABLE `" . $table . "`", "CREATE TABLE `$newDB`.`$table`", $table_["Create Table"]);
                $arrayTable[(isset($priority[0]) ?  $priority[0] : $count++)] = $tableSql  . ";";
                #END
            }
            #AGREGO LAS TABLAS A LAS CUALES QUIERO INSERTAR DATOS
            if ($table == "idiomas" || $table[0] == "_" || $table == "admin" || $table == "roles" || $table == "roles_admin" || $table == "usuarios" || $table == "menu") $tableForInsert = array_merge($tableForInsert, [$table]);
            #END
        }
        #END

        #EJECUTO LAS SENTENCIAS SQL DE LAS TABLAS
        ksort($arrayTable);
        foreach ($arrayTable as $tableFinal) {
            $this->initCon->sql($tableFinal);
        }
        #END
        #INSERTO LOS DATOS DE LAS TABLAS QUE QUIERO
        foreach ($tableForInsert as $key => $mainTable) {
            if ($mainTable == "usuarios" || $mainTable == "menu") {
                if ($mainTable == "menu") $where = " WHERE  `$oldDB`.`$mainTable`.`area` = 'admin'";
                if ($mainTable == "usuarios") $where = " WHERE  `$oldDB`.`$mainTable`.`admin` = '1'";
                $sqlInsert = "INSERT INTO `$newDB`.`$mainTable` SELECT * FROM `$oldDB`.`$mainTable` $where";
            } else {
                $sqlInsert = "INSERT INTO `$newDB`.`$mainTable` SELECT * FROM `$oldDB`.`$mainTable`";
            }
            $this->initCon->sql($sqlInsert);
        }
        #END
    }
    public function array_to_css($array, $indent = 0)
    {
        $css = '';
        $prefix = str_repeat('  ', $indent);

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $selector = $key;
                $properties = $value;

                $css .= $prefix . "$selector {\n";
                $css .= $prefix . $this->array_to_css($properties, $indent + 1);
                $css .= $prefix . "}\n";
            } else {
                $property = $key;
                $css .= $prefix . "$property: $value;\n";
            }
        }

        return $css;
    }
    public function youtubeIframe($url)
    {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "<iframe style='height: 580px;width: 100%;' class='hidden-md-down' src=\"//www.youtube.com/embed/$2?autoplay=1&mute=1&controls=0&disablekb=1&fs=1&modestbranding=1&rel=0&showinfo=1&loop=1&playlist=$2\" allow=\"autoplay;\" allowfullscreen=\"\" frameborder=\"0\" showinfo='0'  ></iframe>",
            $url
        );
    }
}
