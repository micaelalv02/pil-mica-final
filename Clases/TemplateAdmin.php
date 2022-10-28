<?php

namespace Clases;

class TemplateAdmin
{

    public $title;
    public $keywords;
    public $description;
    public $favicon;
    public $canonical;

    public function themeInit()
    {
        echo '<!DOCTYPE html>';
        echo '<html class="loading" lang="es" data-textdirection="ltr">';
        echo '<head>';
        echo ' <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        echo '  <meta http-equiv="X-UA-Compatible" content="IE=edge">';
        echo '  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">';
        echo "<link rel='shortcut icon' href='$this->favicon'>";
        include("inc/header.inc.php");
        $log = (empty($_SESSION["admin"]["rol"]["permissions"])) ? "bg-full-screen-image" : '';
        echo "</head><body data-url=" . URL_ADMIN . " class='$log  vertical-layout vertical-menu-modern 2-columns navbar-sticky footer-static menu-collapsed' style='background-color: #fff' data-open='click' data-menu='vertical-menu-modern' data-col='2-columns'><div class='header-navbar-shadow'></div>";
        (!empty($_SESSION["admin"]["rol"]["permissions"])) ? include "inc/nav.inc.php" : '';
    }

    public function themeEnd()
    {
        echo '</div></div></div></div>
        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>';
        echo '</body>';
        include("inc/footer.inc.php");

        echo '</html >';
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
}
