<?php
$public = new Clases\PublicFunction();
$usuarios = new Clases\Usuarios();

include "../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";


$usuariosData = $usuarios->list("", "", "");

$folder = "../export/users/";     
$filename = $folder."Lista de usuarios " . strtotime("now") . ".xls";

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);



$objPHPExcel->getActiveSheet()->SetCellValue("A1", "Codigo de Usuario");
$objPHPExcel->getActiveSheet()->SetCellValue("B1", "Nombre");
$objPHPExcel->getActiveSheet()->SetCellValue("C1", "Apellido");
$objPHPExcel->getActiveSheet()->SetCellValue("D1", "Documento");
$objPHPExcel->getActiveSheet()->SetCellValue("E1", "Email");
$objPHPExcel->getActiveSheet()->SetCellValue("F1", "Contraseña");
$objPHPExcel->getActiveSheet()->SetCellValue("G1", "Telefono");
$objPHPExcel->getActiveSheet()->SetCellValue("H1", "Celular");
$objPHPExcel->getActiveSheet()->SetCellValue("I1", "Direccion");
$objPHPExcel->getActiveSheet()->SetCellValue("J1", "Localidad");
$objPHPExcel->getActiveSheet()->SetCellValue("K1", "Provincia");
$objPHPExcel->getActiveSheet()->SetCellValue("L1", "Pais");
$objPHPExcel->getActiveSheet()->SetCellValue("M1", "C. Postal");
$objPHPExcel->getActiveSheet()->SetCellValue("N1", "Tipo");
$objPHPExcel->getActiveSheet()->SetCellValue("O1", "Descuento");
$objPHPExcel->getActiveSheet()->SetCellValue("P1", "Estado");




$rowCount = 2;
foreach ($usuariosData as $usuario) {

    $tipo = ($usuario['data']['minorista'] == 1) ? 'Minorista' : 'Mayorista';
    $estado = ($usuario['data']['estado'] == 1) ? 'Activo' : 'Desactivado';

    $objPHPExcel->getActiveSheet()->SetCellValue("A" . $rowCount, $usuario['data']['cod']);
    $objPHPExcel->getActiveSheet()->SetCellValue("B" . $rowCount, $usuario['data']['nombre']);
    $objPHPExcel->getActiveSheet()->SetCellValue("C" . $rowCount, $usuario["data"]["apellido"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("D" . $rowCount, $usuario["data"]["doc"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("E" . $rowCount, $usuario["data"]["email"]);
    // F CONTRASEÑA
    $objPHPExcel->getActiveSheet()->SetCellValue("G" . $rowCount, $usuario['data']['telefono']);
    $objPHPExcel->getActiveSheet()->SetCellValue("H" . $rowCount, $usuario['data']['celular']);
    $objPHPExcel->getActiveSheet()->SetCellValue("I" . $rowCount, $usuario['data']['direccion']);
    $objPHPExcel->getActiveSheet()->SetCellValue("J" . $rowCount, $usuario['data']['localidad']);
    $objPHPExcel->getActiveSheet()->SetCellValue("K" . $rowCount, $usuario['data']['provincia']);
    $objPHPExcel->getActiveSheet()->SetCellValue("L" . $rowCount, $usuario['data']['pais']);
    $objPHPExcel->getActiveSheet()->SetCellValue("M" . $rowCount, $usuario['data']['postal']);
    $objPHPExcel->getActiveSheet()->SetCellValue("N" . $rowCount, $tipo);
    $objPHPExcel->getActiveSheet()->SetCellValue("O" . $rowCount, $usuario['data']['descuento'] . " %");
    $objPHPExcel->getActiveSheet()->SetCellValue("P" . $rowCount, $estado);



    $rowCount++;
}
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save($filename);
$public->headerMove($filename);
