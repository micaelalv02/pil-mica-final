<?php

namespace Clases;

use PhpOffice\PhpSpreadsheet\Helper\Html as HtmlHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class Excel
{

    private $con;
    private $columns = [];

    public function __construct()
    {

        $this->con = new Conexion();
        $this->f = new PublicFunction();
        $this->usuarios = new Usuarios();
        $this->atributo = new Atributos();
        $this->subatributo = new Subatributos();
    }

    public function exportVisitedProducts($data)
    {
        $letterRow = "A";
        $numberRow = 1;
        $this->productos = new Productos();

        $spreadsheet = new Spreadsheet();


        $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, "CÓDIGO DE PRODUCTO");
        $letterRow++;
        $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, "TITULO");
        $letterRow++;
        $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, "EMAIL");
        $letterRow++;
        $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, "NOMBRE");
        $letterRow++;
        $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, "APELLIDO");
        $letterRow++;
        $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, "FECHA DE VISITA");
        $letterRow++;
        $numberRow++;
        foreach ($data["data"] as $key => $productosVisitados_) {
            $letterRow = "A";
            $keyExplode  = explode("|", $key);
            $cod = $keyExplode[0];
            $idioma = $keyExplode[1];
            $productoData = $this->productos->list(["filter" => ["productos.cod = '$cod'"]], $idioma, true);
            foreach ($productosVisitados_ as $key_ => $usuarios) {
                $letterRow = "A";
                $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $productoData["data"]["cod_producto"]);
                $letterRow++;
                $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $productoData["data"]["titulo"]);
                $letterRow++;
                $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $usuarios["usuario"]["email"]);
                $letterRow++;
                $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $usuarios["usuario"]["nombre"]);
                $letterRow++;
                $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $usuarios["usuario"]["apellido"]);
                $letterRow++;
                $spreadsheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $usuarios["usuario"]["visita_producto"]);
                $numberRow++;
            }
        }


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(dirname(__DIR__, 1) . "/admin/archivos/productos-visitados/visitados.xlsx");
        return URL . "/admin/archivos/productos-visitados/visitados.xlsx";
    }
    public function exportProduct($attr, $idioma)
    {
        if (!empty($attr)) {
            $sheet = new Spreadsheet();
            array_unshift($attr, "cod_producto"); // Forzamos el campo de COD_PRODUCTO para luego poder importar 

            // Genero las rutas y nombres de los excel a exportar 
            $folder = dirname(__DIR__, 1) . "/export/productos/$idioma/";
            $filename = "Lista de productos (" . strtoupper($idioma) . ") - " . date("d-m-Y") . '.xlsx';
            $finalPath = $folder . $filename;

            $letter = 'A';
            // Tomo los attr que pase desde la vista y los recorro generando la primer fila del excel.
            foreach ($attr as $attr_) {
                $sheet->setActiveSheetIndex(0)
                    ->setCellValue($letter . '1', $attr_);
                $letter++;
            }

            $letter = 'A';
            // Tomo los attr que pase desde la vista y los recorro generando la primer fila del excel.

            $sheet->getActiveSheet(0)->getProtection()->setSheet(true);
            $sheet->getDefaultStyle()->getProtection()->setLocked(false);
            foreach ($attr as $attr_) {
                $sheet->setActiveSheetIndex(0)
                    ->setCellValue($letter . '1', $attr_);
                $sheet->getActiveSheet(0)->getStyle($letter . '1')
                    ->getProtection()
                    ->setLocked(Protection::PROTECTION_PROTECTED);
                $letter++;
            }
            $sheet->getActiveSheet(0)->freezePane($letter . '2');

            // Si existe "categoria" o "Subcategoria" agrega el left join correspondiente para que me traiga solo el titulo y agilizar la consulta
            if (in_array('categoria', $attr)) {
                $join_data = $this->f->leftJoin("productos", "categorias", "cod", "categoria", "titulo");
                $replace = [array_search('categoria', $attr) => "%" . $join_data['show']];
                $attr = array_replace($attr, $replace);
                // guardo en un array el LEFT JOIN
                $join[] = $join_data['join'] . "AND `categorias`.`idioma` = `productos`.`idioma`";
            }
            if (in_array('subcategoria', $attr)) {
                $join_data = $this->f->leftJoin("productos", "subcategorias", "cod", "subcategoria", "titulo");
                $replace = [array_search('subcategoria', $attr) => "%" . $join_data['show']];
                $attr = array_replace($attr, $replace);
                // guardo en un array el LEFT JOIN
                $join[] = $join_data['join'] . "AND `subcategorias`.`idioma` = `productos`.`idioma`";
            }
            if (in_array('tercercategoria', $attr)) {
                $join_data = $this->f->leftJoin("productos", "tercercategorias", "cod", "tercercategoria", "titulo");
                $replace = [array_search('tercercategoria', $attr) => "%" . $join_data['show']];
                $attr = array_replace($attr, $replace);
                // guardo en un array el LEFT JOIN
                $join[] = $join_data['join'] . "AND `tercercategorias`.`idioma` = `productos`.`idioma`";
            }
            $attr = implode(" , productos.", $attr);
            if (!empty($join)) {
                $join =  implode(" ", $join);
                // Elimino la tabla principal con la bandera que puse antes de categoria y subcategoria, sino quedaria ej: "productos.categorias.titulo".
                $attr = str_replace("productos.%", "", $attr);
            } else {
                $join = '';
            }
            $sql = "SELECT productos.$attr FROM `productos` $join WHERE `productos`.`idioma` = '$idioma' ORDER BY `productos`.`idioma`,`productos`.`cod_producto` ASC";
            $pdo = $this->con->conPDO()->query($sql);
            $products = $pdo->fetchAll();

            $count =  2;
            foreach ($products as $product) {
                $letter = 'A';
                foreach ($product as $key => $value) {
                    $sheet->setActiveSheetIndex(0)
                        ->setCellValue($letter . $count, $value);
                    $letter++;
                }
                $count++;
            }
            $sheet->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
            $writer = IOFactory::createWriter($sheet, 'Xlsx');
            $writer->save($finalPath);

            return $filename;
        }
    }


    public function exportDiscount($data)
    {

        if (!empty($data)) {
            $sheet = new Spreadsheet();


            $finalPath = "/export/descuentos/LISTA DE DESCUENTOS.xlsx";

            $totalProduct = 0;
            $numberRow = 1;
            $letterRow = "A";
            foreach ($data as $key => $value) {
                $totalProduct = 0;
                if (!empty($key)) {
                    $sheet->getActiveSheet()->SetCellValue("A" . $numberRow, "CÓDIGO DE DESCUENTO:" . $key);
                    $numberRow++;
                    $numberRow++;
                    $sheet->getActiveSheet()->SetCellValue("A" . $numberRow, "PRODUCTOS");
                    $sheet->getActiveSheet()->SetCellValue("B" . $numberRow, "TOTAL");
                    $numberRow++;
                    foreach ($value["productos"] as $product) {
                        $letterRow = "A";
                        $sheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $product["titulo"]);
                        $letterRow++;
                        $sheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $product["cantidad"]);
                        $numberRow++;
                        $totalProduct += (int)$product["cantidad"];
                    }
                    $sheet->getActiveSheet()->SetCellValue("A" . $numberRow, "TOTAL");
                    $sheet->getActiveSheet()->SetCellValue("B" . $numberRow, $totalProduct);
                    $numberRow++;
                    $numberRow++;
                    $sheet->getActiveSheet()->SetCellValue("A" . $numberRow, "USUARIOS");
                    $numberRow++;
                    foreach ($value["usuario"] as $user) {
                        $sheet->getActiveSheet()->SetCellValue("A" . $numberRow, $user["email"]);
                        $numberRow++;
                    }
                    $numberRow++;
                    $sheet->getActiveSheet()->SetCellValue("A" . $numberRow, "PEDIDOS");
                    $sheet->getActiveSheet()->SetCellValue("B" . $numberRow, "PRECIO");
                    $sheet->getActiveSheet()->SetCellValue("C" . $numberRow, "FECHA");
                    $numberRow++;
                    foreach ($value["pedido"]["fecha"] as $key_ =>  $pedido) {
                        $letterRow = "A";

                        $sheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $value["pedidos_cod"][$key_]);
                        $letterRow++;
                        $sheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $value["pedido"]["precio"][$key_]);
                        $letterRow++;
                        $sheet->getActiveSheet()->SetCellValue($letterRow . $numberRow, $pedido);
                        $numberRow++;
                    }
                    $numberRow++;
                }
            }
            $sheet->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
            $writer = IOFactory::createWriter($sheet, 'Xlsx');
            $writer->save(dirname(__DIR__, 1) . $finalPath);
            return URL . "/" . $finalPath;
        }
    }
    public function saveFile()
    {
        $url = dirname(__DIR__, 1) . "/admin/excel.tmp";
        move_uploaded_file($_FILES['excel']['tmp_name'], $url);
        return $url;
    }
    public function getSheets($urlFile)
    {
        // Identifico extencion del excel, lo leo y lo guardo en una variable.
        $spreadsheet = new Spreadsheet();
        $inputFileType = IOFactory::identify($urlFile);
        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($urlFile);

        return $spreadsheet->getSheetNames();
    }

    public function importProduct($urlFile, $sheet)
    {
        // Identifico extencion del excel, lo leo y lo guardo en una variable.

        $spreadsheet = new Spreadsheet();
        $inputFileType = IOFactory::identify($urlFile);
        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($urlFile);

        $sheetData = $spreadsheet->getSheet($sheet)->toArray(null, true, true, true);


        // Extraigo del excel la primer fila con los nombres de las variables para luego.

        $attr =  array_map('trim', $sheetData[1]);

        foreach ($sheetData as $key => $data) {
            foreach ($data as $key_ => $value) {
                $val = trim($value);
                $importData[strtolower(str_replace(" ", "_", $attr[$key_]))] = $val;
            }
            $importArray[] = $importData;
            unset($importData);
        }
        array_shift($importArray);
        $_SESSION['import'] = $importArray;

        return $importArray[0];
    }


    function view($table, $attr, $value, $idioma)
    {
        $sql = "SELECT cod FROM `$table` WHERE `$attr` = '$value' AND `idioma` = '$idioma'";
        $var = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($var);
        $response = (!empty($row)) ? $row['cod'] : false;
        return $response;
    }

    public function addPDO($array)
    {
        $attr = implode(",", array_keys($array));
        $values = ":" . str_replace(",", ",:", $attr);
        $sql = "INSERT INTO productos ($attr) VALUES ($values)";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
    }

    public function editPDO($array, $cod)
    {
        $query = implode(", ", array_map(function ($v) {
            return "$v=:$v";
        }, array_keys($array)));
        $condition = implode(' AND ', $cod);
        $sql = "UPDATE productos SET $query WHERE $condition";
        $stmt = $this->con->conPDO()->prepare($sql);
        $stmt->execute($array);
    }



    function checkCategory($table, $title, $attr, $value, $idioma = '')
    {
        $sql = "SELECT $table.cod FROM $table WHERE titulo = '$title' AND $attr = '$value' AND idioma = '$idioma'";
        $query = $this->con->sqlReturn($sql);
        if ($query) {
            $rowSearch = mysqli_fetch_assoc($query);
            if (!empty($rowSearch)) {
                $cod = $rowSearch['cod'];
            } else {
                $cod = substr(md5(uniqid(rand())), 0, 11);
                $attr = "cod , titulo , " . $attr . ", idioma";
                $values = "'$cod', '$title', '$value','$idioma'";
                $this->add($table, $attr, $values);
            }
            return $cod;
        }
    }


    public function import($table) // pendiente de eliminar, tiene cosas utiles que pueden servir para el excel de usuarios
    {

        if (!empty($table)) {
            // Identifico extencion del excel, lo leo y lo guardo en una variable.
            $spreadsheet = new Spreadsheet();
            $inputFileType = IOFactory::identify($_FILES['excel']['tmp_name']);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($_FILES['excel']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            // Extraigo del excel la primer fila con los nombres de las variables para luego.
            $attr = array_shift($sheetData);
            // Busco keys especificas para validar que tipo de excel se importo.
            $keyTitle = array_search('titulo', $attr);
            $keyCat = array_search('categoria', $attr);
            $keySubCat = array_search('subcategoria', $attr);
            $keyEmail = array_search('email', $attr);
            $keyPassword = array_search('password', $attr);
            $keyIdioma = array_search('idioma', $attr);
            $keyFecha = array_search('fecha', $attr);
            $idioma = [];
            $allCod = [];
            foreach ($sheetData as $key => $data) {

                $data = array_map('trim', $data);

                if (!empty($data[$keyTitle]) || !empty($data[$keyEmail])) {
                    if (!isset($keyFecha)) {
                        $data["fecha"] = "NOW()";
                    }
                    if ($data[$keyIdioma]) {
                        $data[$keyIdioma] = strtolower($data[$keyIdioma]);
                    }
                    // Si tiene categoria/subcategoria busca por titulo, si lo encuentra me devuelve el codigo sino crea una nueva y me devuelve el nuevo cod.
                    if ($keyCat || $keySubCat) {
                        $cod_cat = $this->checkCategory("categorias", $data[$keyCat], $table, $data[$keyIdioma]);
                        $cod_subCat = $this->checkCategory("subcategorias", $data[$keySubCat], $cod_cat, $data[$keyIdioma]);
                        $data = array_replace($data, [$keyCat => $cod_cat, $keySubCat => $cod_subCat]);
                    }

                    $newCod = substr(md5(uniqid(rand())), 0, 10);
                    $allCod[] = $newCod;
                    $idioma[] = $data[$keyIdioma];

                    // Valida que el email no este en uso
                    // Si el email esta en uso en otra cuenta, salto la fila y si corresponde a la misma cuenta lo edita
                    if ($keyEmail) {
                        $validate = $this->validateEmail($data[$keyEmail], $data['A']);
                        if (!$validate['status']) {
                            echo $validate['message'];
                            continue;
                        }
                    }

                    foreach ($data as $key_ => $value) {
                        if (is_string($value) && $data[$key_] != $data['A']) {
                            $data[$key_] = "'" . $value . "'";
                        }
                        if ($value == '') {
                            $data[$key_] = "NULL";
                        }
                    }

                    if ($this->view($table, $attr['A'], $data['A'], $data[$keyIdioma])) { // Busco por codigo
                        $data['A'] = "'" . $data['A'] . "'";
                        $value = array_combine($attr, $data);
                        if ($this->edit($table, $value, $data[$keyIdioma])) { // Si lo encuentro edito 
                            echo  $data['A'] . " - editado<hr>";
                        } else {
                            echo "ocurrio un error edit<hr>";
                        }
                    } else { // Sino agrego
                        $cod = array('A' => $newCod);
                        $data = array_replace($data, $cod);
                        $data['A'] = "'" . $data['A'] . "'";
                        if ($keyEmail) { // valido si es una tabla de usuarios
                            $data[] = 0; // Defino que no es usuario invitado porque en la base de datos esta prdefinido que se cargue como invitado.
                            if ($keyPassword) { // si se cargo un password en el excel lo hashea sino genera uno nuevo.
                                if (empty($data[$keyPassword])) {
                                    $data[$keyPassword] = "'" . hash('sha256', $this->generatePassword($data[$keyEmail]) . SALT) . "'";
                                } else {
                                    $data[$keyPassword] = "'" . hash('sha256', $data[$keyPassword] . SALT) . "'";
                                }
                            } else {
                                $data[] = "'" . hash('sha256', $this->generatePassword($data[$keyEmail]) . SALT) . "'";
                            }
                        }
                        $arrayAdd[$key] =  implode(" , ", $data); // Uno todos los datos que se van a crear en un solo string.
                    }
                }
            }
            if (isset($arrayAdd)) { // Valido si existen datos para agregar.
                if ($keyEmail) { // Genero atributo de invitado y si no existe el de contraseña.
                    $attr[] = "invitado";
                    if (!$keyPassword) {
                        $attr[] = "password";
                    }
                }
                if ($this->add($table, $attr, $arrayAdd)) { // Envio una sola peticion con todo lo que hay que agregar
                    echo "<hr>";
                    echo "Se agregaron " . count($arrayAdd) . " " . ucfirst($table) . " en el sistema.";
                } else {
                    echo "Ocurrio un error al agregar " . $table;
                }
                echo "<hr/>";
            }
        }
    }

    public function exportPedido($array)
    {
        $detalle = json_decode($array['data']['detalle'], true);


        $spreadsheet = new Spreadsheet();

        // NOMBRE/RUTA Y PROPIEDADES DEL DOCUMENTO
        $folder = dirname(__DIR__, 1) . "/export/";
        $filename = "Pedido " . $array['data']['cod'];
        $finalPath = $folder . $filename . '.xlsx';

        $spreadsheet->getProperties()->setCreator('Estudio Rocha & Asoc.')
            ->setLastModifiedBy(TITULO)
            ->setTitle($filename)
            ->setSubject($filename)
            ->setDescription('Pedido ' . $array['data']['cod'])
            ->setKeywords($filename . ' , ' . $array['data']['cod'])
            ->setCategory('Pedido ' . $array['data']['cod']);
        //------------------------

        // ENCABEZADO DE TABLA DE INFORMACION
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'INFORMACION');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'NOMBRE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'APELLIDO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'DNI');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'EMAIL');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'DOMICILIO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', 'LOCALIDAD');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'PROVINCIA');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'TELEFONO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', 'CELULAR');
        //------------------------

        // DATOS USUARIO
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'USUARIO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $array['user']['data']['nombre']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', $array['user']['data']['apellido']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D2', $array['user']['data']['doc']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E2', $array['user']['data']['email']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F2', $array['user']['data']['direccion']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G2', $array['user']['data']['localidad']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H2', $array['user']['data']['provincia']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I2', $array['user']['data']['telefono']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J2', $array['user']['data']['celular']);
        //------------------------

        // INFORMACION DE ENVIO
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'ENVIO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $detalle['envio']['nombre']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C3', $detalle['envio']['apellido']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D3', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E3', $detalle['envio']['email']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F3', $detalle['envio']['direccion']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G3', $detalle['envio']['localidad']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H3', $detalle['envio']['provincia']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I3', $detalle['envio']['telefono']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J3', '');
        //------------------------

        // INFORMACION DE FACTURACION
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'FACTURACION');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', $detalle['pago']['nombre']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', $detalle['pago']['apellido']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', $detalle['pago']['dni']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', $detalle['pago']['email']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', $detalle['pago']['direccion']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', $detalle['pago']['localidad']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', $detalle['pago']['provincia']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', $detalle['pago']['telefono']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', '');
        //------------------------

        // OBSERVACIONES
        $factura = ($detalle['pago']['factura']) ? $detalle['pago']['dni'] : 'NO';
        $similar = ($detalle['envio']['similar']) ? 'SI' : 'NO';
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A6', 'OBSERVACIONES');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A7', 'FORMA DE ENVIO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B7', $detalle['envio']['tipo']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A8', 'FORMA DE PAGO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B8', $array['data']['pago']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A9', 'FACTURA A');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B9',  $factura);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A10', 'PRODUCTO SIMILAR');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B10', $similar);
        //------------------------

        // DETALLE DE LA COMPRA
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A12', 'CODIGO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B12', 'PRODUCTO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C12', 'CANTIDAD');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D12', 'PRECIO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E12', 'PRECIO FINAL');
        $detailIndex = 13;
        foreach ($array['detail'] as $detail) {
            $descuento = ($detail["descuento"] != "null") ? '*' : '';
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $detailIndex, $detail['producto_cod']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $detailIndex, $detail['producto'] . $descuento);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $detailIndex, $detail['cantidad']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $detailIndex, $detail['precio']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $detailIndex, $detail['precio'] * $detail['cantidad']);
            $detailIndex++;
            if ($detail["descuento"] != "null") $discount = $detail;
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $detailIndex, 'TOTAL DE LA COMPRA');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $detailIndex, '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $detailIndex, '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $detailIndex, '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $detailIndex, $array['data']['total']);
        //------------------------
        if (isset($discount)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . ($detailIndex + 2),  '* ' . $discount['producto']);
            $detalleDescuento = json_decode($discount['descuento'], true);
            $index = ($detailIndex + 3);
            if (isset($detalleDescuento['products'])) {
                foreach ($detalleDescuento['products'] as $detalle) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $index, $detalle['titulo']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $index, $detalle['monto']);
                    $index++;
                }
            }
        }



        // DAR ESTILOS A LAS CELDAS
        $styleArray = [
            'borders' => [
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],

                'color' => ['argb' => '00000000']
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:J4')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A6:B10')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A6:B6')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A12:E' . $detailIndex)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A12:E12')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A' . $detailIndex . ':E' . $detailIndex)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A' . ($detailIndex + 2) . ':B' . ($index - 1))->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('A' . ($detailIndex + 2) . ':B' . ($detailIndex + 2))->applyFromArray($styleArray);
        //------------------------


        // Defino nombre de la hoja del excel
        $spreadsheet->getActiveSheet()->setTitle('Pedido ' . $array['data']['cod']);
        $spreadsheet->setActiveSheetIndex(0);
        //------------------------

        // Genero extencion del excel, Guardo el archivo en el servidor y fuerzo que se descargue.
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($finalPath);
        // $this->f->headerMove($finalPath);
        return $filename . '.xlsx';
        //------------------------
    }



    public function export($table, $attr)
    {
        if (!empty($table) && !empty($attr)) {
            array_unshift($attr, "cod"); // Forzamos el campo de COD para luego poder importar 

            $spreadsheet = new Spreadsheet();
            $wizard = new HtmlHelper();
            $data = $this->listExport($table, $attr); // Listo los datos segun la tabla y los atributos que pase de la vista

            // Genero ruta y nombre del excel a exportar 
            $folder = "../export/$table/";
            $filename = "Lista de " . ucfirst($table) . " " . date("d-m-Y");
            $finalPath = $folder . $filename . '.xlsx';

            //Defino las propiedades del documento
            $spreadsheet->getProperties()->setCreator('Estudio Rocha & Asoc.')
                ->setLastModifiedBy(TITULO)
                ->setTitle($filename)
                ->setSubject($filename)
                ->setDescription('Listado de ' . $table)
                ->setKeywords($filename . ' , ' . $table)
                ->setCategory($table);



            $letter = 'A';
            // Tomo los attr que pase desde la vista y los recorro generando la primer fila del excel.
            foreach ($attr as $attr_) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue($letter . '1', $attr_);
                $letter++;
            }
            $rowCount = 2;
            // Recorro los datos que traje de la base de datos
            foreach ($data as $value) {
                $letter = 'A';
                foreach ($value['data'] as $key => $val) {
                    if ($key != "password") { // Evito que me extraiga los datos de la contraseña si es la tabla de usuarios. Para que quede el campo vacio por si la desean modificar.
                        if ($key == "desarrollo") { // Si el attr es Desarrollo genera la celda con un poco mas de formato.
                            $richText = $wizard->toRichTextObject(isset($val) ? $val : '');
                            $spreadsheet->getActiveSheet()
                                ->setCellValue($letter . $rowCount, $richText);
                            $spreadsheet->getActiveSheet()
                                ->getColumnDimension($letter)
                                ->setWidth(60);
                            $spreadsheet->getActiveSheet()
                                ->getRowDimension(1)
                                ->setRowHeight(-1);
                            $spreadsheet->getActiveSheet()->getStyle($letter . $rowCount)
                                ->getAlignment()
                                ->setWrapText(true);
                        } else { // Sino agrega la celda simple
                            $spreadsheet->setActiveSheetIndex(0)
                                ->setCellValue($letter . $rowCount, isset($val) ? $val : '');
                        }
                    }
                    $letter++;
                }
                $rowCount++;
            }

            // Defino nombre y hoja del excel
            $spreadsheet->getActiveSheet()->setTitle($table);
            $spreadsheet->setActiveSheetIndex(0);

            // Genero extencion del excel, Guardo el archivo en el servidor y fuerzo que se descargue.
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($finalPath);
            $this->f->headerMove($finalPath);
        }
    }

    function listExport($table, $attr)
    {
        // Si existe "categoria" o "Subcategoria" agrega el left join correspondiente para que me traiga solo el titulo y agilizar la consulta
        if (in_array('categoria', $attr)) {
            $join_data = $this->f->leftJoin($table, "categorias", "cod", "categoria", "titulo");
            // Genero el atributo "categorias.titulo" con uan bandera de "%" que me servira mas abajo y lo cambio por el atrr categoria que me devolveria el cod.
            $replace = [array_search('categoria', $attr) => "%" . $join_data['show']];
            $attr = array_replace($attr, $replace);
            // guardo en un array el LEFT JOIN
            $join[] = $join_data['join'];
        }
        if (in_array('subcategoria', $attr)) {
            $join_data = $this->f->leftJoin($table, "subcategorias", "cod", "subcategoria", "titulo");
            $replace = [array_search('subcategoria', $attr) => "%" . $join_data['show']];
            $attr = array_replace($attr, $replace);
            $join[] = $join_data['join'];
        }

        // Genero en 1 string cada atributo que me va a traer con su respectiva tabla. Para no generar conflicto ambiguo en la base de datos

        $attr = implode(" , " . $table . ".", $attr);
        if (!empty($join)) {
            $join =  implode(" ", $join);
            // Elimino la tabla principal con la bandera que puse antes de categoria y subcategoria, sino quedaria ej: "productos.categorias.titulo".
            $attr = str_replace("$table.%", "", $attr);
        } else {
            $join = '';
        }

        $sql = "SELECT $table.$attr FROM $table $join";

        $var = $this->con->sqlReturn($sql);
        if ($var) {
            while ($row = mysqli_fetch_assoc($var)) {
                $array[] = ["data" => $row];
            }
        }
        return $array;
    }

    public function add($table, $attr, $values)
    {
        $attr =  (is_array($attr)) ? implode(",", $attr) : $attr;
        $values =  (is_array($values)) ? implode(") , (", $values) : $values;

        $sql = "INSERT INTO $table ($attr) VALUES ($values)";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    function edit($table, $attr, $idioma)
    {
        if (!isset($attr["password"])) {
            unset($attr["password"]);
        }

        foreach ($attr as $key => $value) {
            if ($key == "password") {
                $data[$key] = "`" . $key . "` = '" . hash('sha256', $value . SALT) . "'";
            } else {
                $data[$key] = "`" . $key . "` = " . $value;
            }
        }


        $cod = array_shift($data);
        $attr =  implode(" , ", $data);

        $sql = "UPDATE $table SET $attr WHERE $cod AND `idioma` = $idioma";
        if ($this->con->sqlReturn($sql)) {
            return true;
        } else {
            return false;
        }
    }

    function editSimple($table, $attr, $cod)
    {
        $sql = "UPDATE $table SET $attr WHERE $cod";

        if ($this->con->sqlReturn($sql)) {
            return true;
        } else {
            return false;
        }
    }

    function validateEmail($email, $cod)
    {
        $this->usuarios->set("email", $email);
        $validate = $this->usuarios->validate();

        if (!$validate['status']) {
            $return = array("status" => true);
        } else {
            if ($validate['data']['cod'] == $cod) {
                $return = array("status" => true, "message" => "Edit");
            } else {
                $return = array("status" => false, "message" => "El email " . $email . " ya se encuentra en uso en la cuenta de: " . $validate['data']['nombre'] . " " . $validate['data']['apellido'] . ".<hr>");
            }
        }
        return $return;
    }

    function generatePassword($email)
    {
        $arrayEmail = explode("@", $email);
        $pass1 = substr($arrayEmail[0], 0, 2);
        $pass2 = ucfirst(substr($arrayEmail[1], 0, 2));
        $password = $pass1 . $pass2 . date("Y");

        return $password;
    }
}
