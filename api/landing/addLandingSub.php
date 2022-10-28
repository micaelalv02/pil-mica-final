<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();

$landingSub = new Clases\LandingSubs();

$array = [
    "landing_cod" => isset($_POST['landing_cod']) ? $_POST['landing_cod'] : null,
    "nombre" => isset($_POST['nombre']) ? $_POST['nombre'] : null,
    "apellido" => isset($_POST['apellido']) ? $_POST['apellido'] : null,
    "celular" => isset($_POST['celular']) ? $_POST['celular'] : null,
    "telefono" => isset($_POST['telefono']) ? $_POST['telefono'] : null,
    "email" => isset($_POST['email']) ? $_POST['email'] : null,
    "dni" => isset($_POST['dni']) ? $_POST['dni'] : null,
    "cuit" => isset($_POST['cuit']) ? $_POST['cuit'] : null,
    "provincia" => isset($_POST['provincia']) ? $_POST['provincia'] : null,
    "localidad" => isset($_POST['localidad']) ? $_POST['localidad'] : null,
    "pais" => isset($_POST['pais']) ? $_POST['pais'] : null,
    "direccion" => isset($_POST['direccion']) ? $_POST['direccion'] : null,
    "empresa" => isset($_POST['empresa']) ? $_POST['empresa'] : null,
    "cargo" => isset($_POST['cargo']) ? $_POST['cargo'] : null,
    "razon_social" => isset($_POST['razon_social']) ? $_POST['razon_social'] : null,
    "mensaje" => isset($_POST['mensaje']) ? $_POST['mensaje'] : null

];

$landingSub->add($array);