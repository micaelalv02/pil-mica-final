<?php

namespace Clases;

class Menu
{
    //Atributos
    public $id;
    public $padre;
    public $titulo;
    public $icono;
    public $link;
    public $target;
    public $orden;
    public $categoria;
    public $options;
    public $total;
    public $area;
    public $opciones;
    public $habilitado;
    public $idioma;
    private $con;

    //Metodos
    public function __construct($admin = false)
    {
        $this->con = new Conexion();
        $this->f = new PublicFunction();
        $this->idiomas = new Idiomas();
        $this->categorias = new Categorias();
        $this->areas = new Area();
        $this->total = $this->listByLanguage($admin);
    }

    public function list($filter = [], $idioma, $single = false)
    {
        $filter[] = "`idioma` = '" . $idioma . "' ";

        $filterSql = "";
        $filterSql = (is_array($filter)) ? 'WHERE ' . implode(' AND ', $filter) : '';

        $sql = "SELECT * FROM `menu` $filterSql  ORDER BY `orden` ASC";
        $menu = $this->con->sqlReturn($sql);
        $array = [];
        if ($menu->num_rows) {
            while ($row = mysqli_fetch_assoc($menu)) {
                if ($single) {
                    $array[] = $row;
                } else {
                    $array[$row['id']] = $row;
                }
            }
        }
        return $array;
    }
    public function listByLanguage($admin)
    {
        $filter = (!$admin) ? "WHERE `idioma` = '" . $_SESSION['lang'] . "'" : '';
        $sql = "SELECT * FROM `menu` $filter ORDER BY `idioma`,`orden`  ASC ";
        $menu = $this->con->sqlReturn($sql);
        $array = [];

        if ($menu->num_rows) {
            while ($row = mysqli_fetch_assoc($menu)) {
                $array[$row['id']] = $row;
            }
        }
        return $array;
    }


    public function truncate($db)
    {
        $sql = "TRUNCATE `$db`;";
        $query = $this->con->sql($sql);
        return $query;
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

    public function add()
    {
        if (!$this->id) {
            $sql = "INSERT INTO `menu`(`padre`,`titulo`,`icono`,`link`,`target`,`area`,`orden`,`opciones`,`habilitado`, `idioma`) 
                VALUES ({$this->padre},
                        {$this->titulo},
                        {$this->icono}, 
                        {$this->link},
                        {$this->target},
                        {$this->area},
                        {$this->orden},
                        {$this->opciones},
                        {$this->habilitado},
                        {$this->idioma})";
        } else {
            $sql = "INSERT INTO `menu`(`id`,`padre`,`titulo`,`icono`,`link`,`target`,`area`,`orden`,`opciones`,`habilitado`, `idioma`) 
                VALUES ({$this->id},
                        {$this->padre},
                        {$this->titulo},
                        {$this->icono}, 
                        {$this->link},
                        {$this->target},
                        {$this->area},
                        {$this->orden},
                        {$this->opciones},
                        {$this->habilitado},
                        {$this->idioma})";
        }
        $query = $this->con->sql($sql);

        if ($this->area == "'admin'") {
            $this->roles = new Roles();
            $this->roles->addDevPermissions($this->area, $this->titulo, $this->link, $this->idioma, 'admin-role');
        }
        return $query;
    }

    public function createAreaList($lang)
    {
        $padre  = $this->list(["link = '#areas'"], $lang, true);
        if ($padre) {
            $padre = $padre[0]["id"];
            $areaData = $this->areas->list([""], "", "", $lang);
            if (isset($areaData)) {
                foreach ($areaData as $area) {
                    $cod = $area["data"]["cod"];
                    $titulo = $area["data"]["titulo"];
                    $link = "/index.php?op=contenidos&accion=ver&area=$cod&idioma=es";
                    $exist = $this->list(["titulo = '$titulo'", "link = '$link'"], $lang, true);
                    $idioma = $area["data"]["idioma"];
                    if (!$exist) {
                        $this->set("padre", $padre);
                        $this->set("titulo", $titulo);
                        $this->set("link", $link);
                        $this->set("icono", "");
                        $this->set("target", "_self");
                        $this->set("area", "admin");
                        $this->orden = 0;
                        $this->opciones = 0;
                        $this->set("habilitado", 1);
                        $this->set("idioma", $idioma);
                        $this->add();
                        $_SESSION["admin"]["rol"]["permissions"][0][] = $this->list(["link = '$link'", "titulo = '$titulo'"], $idioma, true)[0];
                    }
                }
            }
        }
    }

    public function edit()
    {
        $sql = "UPDATE `menu` 
                SET `padre`  = {$this->padre},
                    `titulo`  = {$this->titulo},
                    `icono`  = {$this->icono}, 
                    `link`  = {$this->link},
                    `target`  = {$this->target},
                    `area`  = {$this->area},
                    `orden`  = {$this->orden},
                    `opciones`  = {$this->opciones},
                    `habilitado`  = {$this->habilitado},
                    `idioma`  = {$this->idioma}
                WHERE `id`= {$this->id}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `menu` WHERE `id` = {$this->id} OR `padre` = {$this->id}";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function build_nav($padre_id = "", $child, $area)
    {
        $ul_class = empty($padre_id) ? "" : "sub-menu pb-10 pt-10 pl-15 pr-15";

        $r = array_filter($this->total, function ($value) use ($padre_id) {
            return $value['padre'] == $padre_id;
        });
        if ($r) {
            echo "<ul class='$ul_class' data-in='#' data-out='#'>";
            foreach ($r as $value) {
                if ($value["area"] == $area) {
                    $preset_dropdown = ($value['link'] != '#' && strpos($value['link'], '#') !== false) ? $this->build_category($value['link']) : '';
                    $link = strpos($value['link'], 'http') === false ? URL . "/" . $value['link'] :  $value['link'];
                    $child = ($this->checkIfHave($value['id']) || !empty($preset_dropdown)) ? true : false;
                    $li_class = ($child) ? "" : "";
                    $a_class = ($child) ? "" : "";
                    $dataToggle = ($child) ? "dropdown" : "";
                    echo "<li class='$li_class'><a href='" . $link . "' data-toggle='" . $dataToggle . "' target='" . $value['target'] . "' class='$a_class'><i class='" . $value['icono'] . "' ></i> " . $value['titulo'] . "</a>";
                    echo $preset_dropdown;
                    $this->build_nav($value['id'], $child, $area);
                    echo "</li>";
                }
            }
            echo "</ul>";
        }
    }
    public function build_permissions_options($padre_id = "", $child = false)
    {
        $ul_class = empty($padre_id) ? "ulProducts" : "ulProductsDropdown pl-20 dropdown";
        $li_class = ($child) ? "list-style-none" : "mb-10 text-uppercase drop menu-item-has-children fs-12";
        $total = $this->list(["area = 'admin'"], "es");
        $menuFilter = array_filter($total, function ($value) use ($padre_id) {
            return $value['padre'] == $padre_id;
        });
        if ($menuFilter) {
            echo "<ul class='$ul_class'>";
            foreach ($menuFilter as $value) {
                $arrow_icon = ($this->checkIfHave($value['id'])) ? '<i class="fa fa-angle-down"></i>' : '';
                echo "<li class='$li_class'>
                        <div class='sidebar-widget-list-left '>
                            <label for='for" . $value["id"] . "' class='fs-12 text-uppercase'>
                                <input id='for" . $value["id"] . "' name='permissions[]' value='" . $value["id"] . "' type='checkbox'>" . $value['titulo'] . "$arrow_icon
                            </label>
                        </div>";
                $this->build_permissions_options($value['id'], true);
                echo "</li>";
            }
            echo "</ul>";
        }
    }


    public function build_rol_edit($rol, $padre_id = "", $child = false)
    {
        $this->roles = new Roles();
        $ul_class = empty($padre_id) ? "ulProducts w100" : "ulProductsDropdown pl-20 dropdown";
        $li_class = ($child) ? "list-style-none" : "mb-10 text-uppercase drop menu-item-has-children fs-12";
        $total = $this->list(["area = 'admin'"], "es");
        $rolData = $this->roles->list(["cod = '$rol'"], "", "");
        $r = array_filter($total, function ($value) use ($padre_id) {
            return $value['padre'] == $padre_id;
        });
        if ($r) {
            echo "<ul class='$ul_class'>";
            foreach ($r as $value) {
                $none = '';
                // if ($value["opciones"] == 0) $none  = "d-none";
                $id = $value['id'];
                $data = array_column($rolData, "data");
                $dataColumn =  array_column($data, "permisos");
                $exist = array_search($id, array_column($dataColumn, "id"));
                $agregar = 0;
                $editar = 0;
                $eliminar = 0;
                foreach ($dataColumn as $columnItem) {
                    if ($columnItem["id"] == $id) {
                        $agregar = (isset($columnItem["crear"]) && $columnItem["crear"] == 1) ? "checked" : '';
                        $editar = (isset($columnItem["editar"]) && $columnItem["editar"] == 1) ? "checked" : '';
                        $eliminar = (isset($columnItem["eliminar"]) && $columnItem["eliminar"] == 1) ? "checked" : '';
                    }
                }
                $checked = '';
                if ($exist !== false) {
                    $checked = 'checked';
                }
                echo "<li class='$li_class '>
                        <div class='sidebar-widget-list-left '>
                            <label for='for" . $id . "' class='fs-12 text-uppercase ml-20'>
                                <input class='mr-10' id='for" . $id . "' name='permissions[id][]' value='" . $id . "' " . $checked . " type='checkbox'>" . $value['titulo'] . "
                            </label>
                            <input type='checkbox' value='1' class='" . $none . " pull-right mr-20' " . $agregar  . "  name='permissions[$id][crear]'>
                            <input type='checkbox' value='1' class='" . $none . " pull-right mr-30' " . $editar  . "  name='permissions[$id][editar]'>
                            <input type='checkbox' value='1' class='" . $none . " pull-right mr-30' " . $eliminar  . "  name='permissions[$id][eliminar]'>
                        </div>";
                echo "</li>";
                $this->build_rol_edit($rol, $value['id'], true);
            }
            echo "</ul>";
        }
    }

    public function build_admin_nav($padre_id = "", $child = false)
    {
        $ul_class = empty($padre_id) ? "navigation navigation-main" : "";
        $li_class = ($child) ? "" : "nav-item";
        $span_class = ($child) ? "menu-title" : "";
        $r = array_filter($_SESSION["admin"]["rol"]["permissions"][0], function ($value) use ($padre_id) {
            return $value['padre'] == $padre_id;
        });
        if ($r) {
            echo "<ul class='$ul_class' id='main-menu-navigation' data-menu='menu-navigation' data-icon-style='lines'>";
            foreach ($r as $value) {
                if ($value["habilitado"] == 1) {
                    $icon = (!empty($value["icono"])) ? "<i class='menu-livicon' data-icon='" . $value["icono"] . "'></i>" : "";
                    echo "<li class='$li_class'>
                        <a href='" . URL_ADMIN . $value["link"] . "' target='" . $value["target"] . "'>
                            " . $icon . "
                            <span class='" . $span_class . "'>" . $value["titulo"] . "</span>
                        </a>";
                    $this->build_admin_nav($value['id'], true);
                    echo "</li>";
                }
            }
            echo "</ul>";
        }
    }

    public function build_category($type)
    {
        $types = explode('#', $type);
        $data = $this->categorias->list(["area = '" . $types[0] . "'"], '', '', $_SESSION['lang']);

        $opciones_ = '';


        if ($types[1] == "categorias") {
            $opciones_ .= "<ul class='dropdown-menu'>";
            foreach ($data as $value) {
                $opciones_ .= "<li class='nav__item'><a href='" . URL . "/productos/c/" . $this->f->normalizar_link($value['data']['titulo']) . "/" . $value['data']['cod'] . "' class='nav__item-link'> " . $value['data']['titulo'] . "</a></li>";
            }
            $opciones_ .= "</ul>";
        } else {
            $opciones_ .= "<ul class='dropdown-menu'>";
            foreach ($data as $value) {
                $link = URL . "/productos/c/" .  $this->f->normalizar_link($value['data']['titulo']) . "/" . $value['data']['cod'];
                $arrow_icon = (!empty($value["subcategories"])) ? '<i class="fa fa-angle-down"></i>' : '';
                $opciones_ .= "<li class='nav__item with-dropdown'><a href='" . $link . "' class='nav__item-link dropdown-toggle'>" . $value['data']['titulo'] . " " . $arrow_icon . "</a>";
                if (!empty($value["subcategories"])) {
                    $linkSub = str_replace("/c/", "/s/", $link);
                    $opciones_ .= "<ul class='dropdown-menu'>";
                    foreach ($value["subcategories"] as $value_) {
                        $opciones_ .= "<li class='nav__item'><a href='" . $linkSub . "/" . $this->f->normalizar_link($value_['data']['titulo']) . "/" . $value_['data']['cod'] . "' class='nav__item-link'> " . $value_['data']['titulo'] . "</a></li>";
                    }
                    $opciones_ .= "</ul>";
                }
                $opciones_ .= "</li>";
            }
            $opciones_ .= "</ul>";
        }


        return $opciones_;
    }

    public function build_nav_mobile($padre_id = "", $child = true, $area)
    {
        $ul_class = empty($padre_id) ? "has-children" : "offcanvas-submenu";
        $li_class = ($child) ? "" : "";
        $a_class = ($child) ? "" : "";

        $r = array_filter($this->total, function ($value) use ($padre_id) {
            return $value['padre'] == $padre_id;
        });
        if ($r) {
            echo "<ul class='$ul_class'>";
            foreach ($r as $value) {
                if ($value["area"] != $area) continue;
                $preset_dropdown = (strpos($value['link'], '#') !== false) ? $this->build_category($value['link']) : '';
                $arrow_icon = ($this->checkIfHave($value['id']) || !empty($preset_dropdown)) ? '<i class="fa fa-angle-down"></i>' : '';
                echo "<li class='$li_class'><a href='" . URL . "/" . $value['link'] . "' target='" . $value['target'] . "' class='$a_class'><i class='" . $value['icono'] . "' ></i> " . $value['titulo'] . " $arrow_icon</a>";

                echo $preset_dropdown;
                $this->build_nav($value['id'], true, $area);

                echo "</li>";
            }
            echo "</ul>";
        }
    }



    public function build_admin($padre_id = "", $margin, $area, $idioma, $permisos)
    {

        $r = array_filter($this->total, function ($value) use ($padre_id) {
            return $value['padre'] == $padre_id;
        });
        echo "<div class='" . $padre_id . "' style='" . (($margin != 0) ? "display:none" : "") . "'>";
        foreach ($r as $key => $row) {
            if ($row["area"] == $area && $row["idioma"] == $idioma) {
                if (($row["habilitado"] == 1)) {
                    $icon  = "bx-show";
                    $habilitar  = 0;
                } else {
                    $icon  = "bx-hide";
                    $habilitar = 1;
                }
                ($key == 0) ? $idiomaCheck = $row['idioma'] : '';
                echo (isset($idiomaCheck) && $idiomaCheck != $row['idioma']) ? "<hr>" : '';
                $idiomaCheck = $row['idioma'];
                $singleQuote = "'";
?>

                <div style="margin-left:<?= $margin ?>px">
                    <form method="POST">
                        <div class="row align-items-center mb-0 ">
                            <input type="hidden" name="idioma" value="<?= $row["idioma"] ?>">
                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                            <div class="col col-xs-12">
                                <input type="text" class="fs-13 mb-1 form-control layouttamaño  " style="" id="titulo<?= $row["id"] ?>" placeholder="titulo" <?= ($_SESSION["admin"]["crud"]["editar"]) ? 'onchange="editMenuItem(' . $singleQuote . $row['id'] . $singleQuote . ',' . $singleQuote . 'titulo' . $singleQuote . ',$(this).val())"' : 'readonly ' ?>name="titulo" value="<?= $row["titulo"] ?>">
                            </div>
                            <div class="col col-xs-12">
                                <input type="text" class="fs-13 mb-1 form-control layouttamaño layoutlink" placeholder="link" id="link<?= $row["id"] ?>" <?= ($_SESSION["admin"]["crud"]["editar"]) ? 'onchange="editMenuItem(' . $singleQuote . $row['id'] . $singleQuote . ',' . $singleQuote . 'link' . $singleQuote . ',$(this).val())"' : 'readonly ' ?>name="link" value="<?= $row["link"] ?>">
                            </div>
                            <div class="col col-xs-12">
                                <input type="text" class="fs-13 mb-1 form-control layouttamaño" placeholder="icono" id="icono<?= $row["id"] ?>" <?= ($_SESSION["admin"]["crud"]["editar"]) ? 'onchange="editMenuItem(' . $singleQuote . $row['id'] . $singleQuote . ',' . $singleQuote . 'icono' . $singleQuote . ',$(this).val())"' : 'readonly ' ?>name="icono" value="<?= $row["icono"] ?>">
                            </div>
                            <div class="col col-xs-12">
                                <select class="fs-13 mb-1 form-control layouttamaño layoutwindows" <?= ($_SESSION["admin"]["crud"]["editar"]) ? 'onchange="editMenuItem(' . $singleQuote . $row['id'] . $singleQuote . ',' . $singleQuote . 'target' . $singleQuote . ',$(this).val())"' : 'readonly ' ?>id="target<?= $row["id"] ?>" name="target">
                                    <option value="_blank" <?= ($row["target"] == "_blank") ? "selected" : "" ?>>Nueva Ventana</option>
                                    <option value="_self" <?= ($row["target"] == "_self") ? "selected" : "" ?>>Misma Ventana</option>
                                </select>
                            </div>
                            <div class="col col-xs-12">
                                <select class="fs-13 mb-1 layoutmenu  form-control layouttamaño layoutwindows" <?= ($_SESSION["admin"]["crud"]["editar"]) ? 'onchange="editMenuItem(' . $singleQuote . $row['id'] . $singleQuote . ',' . $singleQuote . 'padre' . $singleQuote . ',$(this).val())"' : 'readonly ' ?>id="padre<?= $row["id"] ?>" name="padre">
                                    <option selected disabled>Menu Superior</option>
                                    <?php $this->build_options("", "", $row["padre"], $area) ?>
                                </select>
                            </div>
                            <div class="col col-xs-12">
                                <input type="text" class="fs-13 mb-1 form-control layouttamaño layoutorder" placeholder="orden" id="orden<?= $row["id"] ?>" <?= ($_SESSION["admin"]["crud"]["editar"]) ? 'onchange="editMenuItem(' . $singleQuote . $row['id'] . $singleQuote . ',' . $singleQuote . 'orden' . $singleQuote . ',$(this).val())"' : 'readonly ' ?>name="orden" value="<?= $row["orden"] ?>">
                            </div>
                            <?php if ($row["area"] == "admin") { ?>
                                <div class="col col-xs-12">
                                    <label for="opciones<?= $row['id'] ?>">Opciones</label>
                                    <input type="checkbox" value="<?= $row['opciones'] == 1 ? "0" : "1" ?>" class="fs-13 mb-2" placeholder="opciones" id="opciones<?= $row['id'] ?>" <?= $row['opciones'] == 1 ? 'checked' : '' ?> <?= ($_SESSION["admin"]["crud"]["editar"]) ? 'onchange="editMenuItem(' . $singleQuote . $row['id'] . $singleQuote . ',' . $singleQuote . 'opciones' . $singleQuote . ',$(this).val())"' : 'readonly ' ?>name="opciones">
                                </div>
                                <div class="col col-xs-12">
                                    <a data-toggle="tooltip" data-placement="top" title="Habilitar/Deshabilitar" href="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&idioma=es&habilitar=<?= $habilitar ?>-<?= $row["id"] ?>">
                                        <span class="badge badge-light-primary">
                                            <div class="fonticon-wrap">
                                                <i class="bx <?= $icon ?> fs-20" style="color:#666;"></i>
                                            </div>
                                        </span>
                                    </a>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" value="1" name="habilitado">
                            <?php } ?>
                            <div class="col col-xs-12 text-right">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <?php if ($this->checkIfHave($row['id'])) { ?>
                                        <button type="button" onclick="$('.<?= $row['id'] ?>').toggle()" class="btn-small btn btn-info mobilestylebuttontoggle"><i class="fa fa-caret-down"></i></button>
                                    <?php }  ?>
                                    <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                        <button name="delete" class="btn-small btn btn-danger mobilestylebuttondelete"><i class="fa fa-times"></i></button>

                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <style>
                    @media (max-width: 411px) {

                        .mb-0,
                        .my-0 {
                            margin-bottom: 41px !important;
                        }
                    }

                    @media (max-width: 360px) {

                        .mb-0,
                        .my-0 {
                            margin-bottom: 60px !important;
                        }
                    }
                </style>
<?php

                $this->build_admin($row['id'], $margin + 20, $area, $idioma, $permisos);
            }
        }
        echo "</div>";
    }

    public function build_options($padre_id = "", $separador = "", $selected = "aa", $area)
    {
        echo $selected;

        $r = array_filter($this->total, function ($value) use ($padre_id) {
            return $value['padre'] == $padre_id;
        });
        foreach ($r as $value) {
            if ($value["area"] == $area) {
                $check_selected = ($selected == $value["id"]) ? "selected" : "";
                echo '<option value="' . $value["id"] . '" ' . $check_selected . ' >' . $separador . ' ' . $value["titulo"] . '</option>';
                $this->build_options($value['id'], $separador . "-", $selected, $area);
            }
        }
    }

    public function menuOptions($data, $subcat = false)
    {
        $options = '';
        $options .= $this->menuOptionsArea();
        foreach ($data as $categoria) {
            $link = strtolower($this->f->normalizar_link($categoria['data']['area'])) . "/c/" . $this->f->normalizar_link($categoria['data']['titulo']) . "/" . $categoria['data']['cod'];
            $options .= "<option value='" . $link . "'>" . $categoria['data']['titulo'] . "</option>";
            if ($subcat) {
                if (!empty($categoria["subcategories"])) {
                    $link = str_replace("/c/", "/s/", $link);
                    $options .= "<optgroup label='" . $categoria['data']['titulo'] . "'>";
                    foreach ($categoria['subcategories'] as $subcategoria) {
                        $linksub = $link . "/" . $this->f->normalizar_link($subcategoria['data']['titulo']) . "/" . $subcategoria['data']['cod'];
                        $options .= "<option value='" . $linksub . "'>" .  $categoria['data']['titulo'] . " - " . $subcategoria['data']['titulo'] . "</option>";
                    }
                    $options .= "</optgroup>";
                }
            }
        }
        return $options;
    }
    public function menuOptionsArea()
    {
        $categoryData = $this->categorias->listAreas('', '', '');
        $options = '';
        foreach ($categoryData as $category) {
            $options .= "<option value='" . $category['data']['area'] . "#categorias" . "'>Categorias de " . $category['data']['area'] . "</option>";
            $options .= "<option value='" . $category['data']['area'] . "#subcategorias" . "'>Categorias y Subcategorias de " . $category['data']['area'] . "</option>";
        }
        return $options;
    }

    public function checkIfHave($id)
    {
        $sql = "SELECT * FROM `menu` WHERE padre = $id ORDER BY `orden` ASC";
        $query = $this->con->sqlReturn($sql);
        if ($query->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public function editSingle($atribute, $value)
    {
        $sql = "UPDATE `menu` SET $atribute = '$value' WHERE `id`={$this->id}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }
}
