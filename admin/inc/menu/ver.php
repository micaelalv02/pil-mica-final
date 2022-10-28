<?php
$menu = new Clases\Menu(true);
$idiomas = new Clases\Idiomas();
$categorias = new Clases\Categorias();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli(strtolower($_GET["idioma"])) : '';
$categoryData = $categorias->list([], '', '', $idiomaGet);
?>
<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <h4 class="mt-20 pull-left">MENU</h4>
                <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>
                    <button class="btn btn-success pull-right text-uppercase mt-15 " onclick="$('#create').toggle()">AGREGAR</button>
                <?php } ?>
                <div class="clearfix"></div>
                <div class="card">
                    <div id="create" style="display:none">
                        <hr />
                        <?php
                        if (isset($_POST["create"]) && $_SESSION["admin"]["crud"]["crear"]) {
                            foreach ($_POST as $key => $value) {
                                if ($key == "create") continue;
                                $menu->set($key, $value);
                            }
                            $menu->set("area", "web");
                            $menu->opciones = 0;
                            $menu->add();
                            $funciones->headerMove(URL_ADMIN . "/index.php?op=menu&idioma=$idiomaGet");
                        }
                        ?>
                        <form method="POST">
                            <input type="hidden" value="1" name="habilitado">
                            <div class="form-row align-items-center mb-0">
                                <div class="col">
                                    <input type="text" class="fs-13 mb-2 layouttamaño" placeholder="titulo" name="titulo" required>
                                </div>

                                <div class="col">
                                    <input type="text" class="fs-13 mb-2 form-control layouttamaño" placeholder="link" list="link" name="link" />
                                    <datalist id="link" class="linkList" name="link" required>
                                        <? $menu->menuOptions($categoryData) ?>
                                    </datalist>
                                </div>
                                <div class="col">
                                    <input type="text" class="fs-13 mb-2 layouttamaño" placeholder="icono" name="icono">
                                </div>
                                <div class="col">
                                    <select class="fs-13 mb-2 layouttamaño" name="target" required>
                                        <option value="_self">Misma Ventana</option>
                                        <option value="_blank">Nueva Ventana</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <select class="fs-13 mb-2 layouttamaño" name="padre" required>
                                        <option value="0" selected>Menu Superior</option>
                                        <?php $menu->build_options("", "", $row["padre"], "web") ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="text" class="fs-13 mb-2 layouttamaño" placeholder="orden" name="orden" required>
                                </div>
                                <div class="col">
                                    <select name="idioma" class="fs-13 mb-2 layouttamaño" required>
                                        <?php
                                        foreach ($idiomas->list('', '', '') as $idioma) { ?>
                                            <option <?= $idioma['data']['cod'] == 'es' ? 'Selected' : '' ?> value="<?= $idioma['data']['cod'] ?>"><?= $idioma['data']['titulo'] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <button type="submit" name="create" class="  btn btn-primary mb-2"><i class="fa fa-save"></i> AGREGAR A MENU</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr />
                    <ul class="nav nav-tabs">
                        <?php
                        foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                            $cod = $idioma_["data"]["cod"];
                            $url =  URL_ADMIN . "/index.php?op=menu&accion=ver&idioma=$cod";
                        ?>
                            <a class="nav-link <?= strpos(CANONICAL, '&idioma=' . $cod) ? "active" :  '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
                        <?php } ?>
                    </ul>
                    <?php
                    $menu->build_admin("", 0, "web", $idiomaGet, $_SESSION["admin"]["crud"]);
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
if (isset($_POST["delete"]) && $_SESSION["admin"]["crud"]["eliminar"]) {
    $id = isset($_POST["id"]) ? $funciones->antihack_mysqli($_POST["id"]) : '';
    $menu->set("id", $id);
    $menu->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=menu&idioma=$idiomaGet");
}
?>