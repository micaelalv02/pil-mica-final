<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$backup = new Clases\Backup();
$backup->create();