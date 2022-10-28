<?php
require_once dirname(__DIR__, 3) . "/Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();
if (isset($_POST["remove-all"])) {
    $r = false;
    foreach ($_POST["img"] as $img) {
        $url = dirname(__DIR__, 3) . str_replace([URL,"'"], "", $img);
        if (unlink($url)) {
            $r = true;
        }
    }
    if ($r) echo "<script>window.close();</script>";
}

if (isset($_GET["url"])) {
    $url = dirname(__DIR__, 3) . str_replace(URL, "", $_GET["url"]);
    if (unlink($url)) {
        echo "<script>window.close();</script>";
    }
}

