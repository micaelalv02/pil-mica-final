<?php
require_once "../Config/Autoload.php";
require_once '../Clases/Meli.php';
Config\Autoload::run();

$template = new Clases\TemplateAdmin();
$template->set("title", "Admin");
$template->favicon = 'img/favicon.png';
$template->themeInit();
$admin = new Clases\Admin();
$funciones = new Clases\PublicFunction();

if (!isset($_SESSION["admin"])) {
    $admin->loginForm();
} else {
    $op = isset($_GET["op"]) ? $_GET["op"] : 'inicio';
    $accion = isset($_GET["accion"]) ? $_GET["accion"] : 'ver';
    $area = isset($_GET["area"]) ? $_GET["area"] : '';

    if ($op != '') {
        if ($op == "salir") {
            session_destroy();
            $funciones->headerMove(URL_ADMIN . "/index.php");
        } else {
            $admin->refreshSession();
            $config = new Clases\Config();
            $meli = new Meli($config->meli["data"]["app_id"], $config->meli["data"]["app_secret"]);
            $success = false;
            foreach ($_SESSION["admin"]["rol"]["permissions"][0] as $permissions) {
                if (!empty($area)) {
                    if ($permissions["opciones"] == 1) {
                        if (strstr($permissions["link"], "area=" . $area)) {
                            if ($permissions["crear"] == 1 && $accion == "agregar") $success = true;
                            if ($accion == "ver") $success = true;
                            if ($permissions["editar"] == 1 && $accion == "modificar") $success = true;
                            if ($permissions["eliminar"] == 1 && $accion == "eliminar") $success = true;
                        }
                    } else {
                        $success = true;
                    }
                } else {
                    if (strstr($permissions["link"], "op=" . $op)) $success = true;
                }
            }
            if (!$success) {
                if (CANONICAL != URL_ADMIN && CANONICAL != URL_ADMIN . "/") $funciones->headerMove(URL_ADMIN);
            }
            $_SESSION["admin"]["crud"] = $funciones->getPermissions(["area" => $area, "op" => $op], $_SESSION["admin"]["rol"]["permissions"][0]);
            include "inc/" . $op . "/" . $accion . ".php";
        }
    }
}

$template->themeEnd();
