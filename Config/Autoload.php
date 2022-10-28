<?php

namespace Config;

use Clases\Idiomas;
use Clases\PublicFunction;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';
Dotenv::createImmutable(dirname(__DIR__, "1"))->load();

class Autoload
{
    public static function  run()
    {
        #Variables Globales
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];
        $page_customization = json_decode(file_get_contents(dirname(__DIR__, 1)  . '/json/page-customization.json', false, stream_context_create($arrContextOptions)), true);
        define('SALT', hash("sha256", $_ENV["SALT"]));
        define('URL', $_ENV["PROTOCOL"] . "://" . $_SERVER['HTTP_HOST'] . $_ENV["PROJECT"]);
        define('URL_ADMIN', $_ENV["PROTOCOL"] . "://" . $_SERVER['HTTP_HOST'] . $_ENV["PROJECT"] . "/admin");
        define('TITULO_ADMIN', $_ENV["TITLE_ADMIN"]);
        define('CANONICAL', $_ENV["PROTOCOL"] . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        define('LOGO', URL . $page_customization["logo"]);
        define('FAVICON', URL . $page_customization["favicon"]);
        define('TITULO', $_ENV["TITLE"]);

        #Autoload
        spl_autoload_register(
            function ($clase) {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                $pos = strpos($ruta, "Clases");
                if ($pos !== false) {
                    include_once dirname(__DIR__) . "/" . $ruta;
                }
            }
        );
        self::settings();
    }

    public static function settings()
    {
        #Se configura la zona horaria en Argentina
        setlocale(LC_ALL, $_ENV["LOCALE"]);
        date_default_timezone_set($_ENV["TIMEZONE"]);
        session_start();

        #Se mantiene siempre la sesión iniciada
        if ($_ENV["DEBUG"] == "1") {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }

        #Se define el idioma de la pagina
        $idioma = new Idiomas();
        // $f = new PublicFunction();
        // $f->changeRootContents("https://26.177.116.7/", "https://server.com/");
        if (isset($_SESSION["usuarios"]["idioma"])) {
            $_SESSION["lang"] = $_SESSION["usuarios"]["idioma"];
        } else {
            $_SESSION["lang"] = isset($_SESSION["lang"]) ? $_SESSION["lang"]  : $idioma->viewDefault()['data']['cod'];
        }
        $_SESSION["defaultLang"] = isset($_SESSION["defaultLang"]) ? $_SESSION["defaultLang"]  : $idioma->viewDefault()['data']['cod'];
        $_SESSION["lang-txt"] = json_decode(file_get_contents(dirname(__DIR__) . '/lang/' . $_SESSION["lang"] . '.json'), true);
        $_SESSION["cod_pedido"] = isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : strtoupper(substr(md5(uniqid(rand())), 0, 7));
        !isset($_SESSION['token']) ? $_SESSION['token'] = md5(uniqid(rand(), TRUE)) : null;
    }
}
