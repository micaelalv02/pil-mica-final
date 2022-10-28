<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$excel = new Clases\Excel();
$f = new Clases\PublicFunction();

$id = isset($_POST['id']) ?  $f->antihack_mysqli($_POST['id']) : '';
$table = $_POST['table'];


$folder = dirname(__DIR__, 3) ."/export/estadisticas/";
$filename = $id . " " . date("d-m-Y") . '.xlsx';
$finalPath = $folder . $filename;


$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
$spreadsheet = $reader->loadFromString($table);



$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($finalPath);
echo json_encode(["fileName" => $filename]);
?>

