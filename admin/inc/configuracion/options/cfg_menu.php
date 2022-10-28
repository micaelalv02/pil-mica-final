<?php
$categorias = new Clases\Categorias();
$idiomas = new Clases\Idiomas();
$menu = new Clases\Menu();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli(strtolower($_GET["idioma"])) : '';
$menuData = $menu->list(["area = 'admin'"], $idiomaGet);

if (isset($_POST["create"])) {
    foreach ($_POST as $key => $value) {
        if ($key == "create") continue;
        $menu->set($key, $value);
    }
    if (!isset($_POST["opciones"])) $menu->opciones = 0;
    $menu->set("area", "admin");
    $menu->add();
}
if (isset($_POST["delete"])) {
    $menu->set("id", $funciones->antihack_mysqli($_POST["id"]));
    $menu->delete();
}
if (isset($_GET["habilitar"])) {
    $getValue = $funciones->antihack_mysqli($_GET["habilitar"]);
    $getValue = explode("-", $getValue);
    $menu->set("id", $getValue[1]);
    $menu->editSingle("habilitado", $getValue[0]);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=configuracion&accion=modificarTec&idioma=es&tab=menu-tab");
}
?>
<div>
    <div class="row">
        <!-- <ul class="nav nav-tabs ml-20">
            <?php
            foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                $url =  URL_ADMIN . "/index.php?op=configuracion&accion=modificarTec&idioma=" . $idioma_["data"]["cod"] . "&tab=menu-tab";
            ?>
                <a class="nav-link <?= $idioma_["data"]["cod"] == $idiomaGet ? "active" : '' ?> " href="<?= $url ?>"><?= mb_strtoupper($idioma_["data"]["titulo"]) ?></a>
            <?php } ?>
        </ul> -->
        <div class="card col-md-12">
            <div class="card-body">
                <div class="row ">
                    <div class="col-md-12">
                        <h3 class="text-uppercase fs-20 text-center">Agregar Links al Menu del Administrador</h3>
                        <hr style="border-style: dashed;">

                        <form method="POST">
                            <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                            <input type="hidden" name="area" value="admin">
                            <input type="hidden" value="1" name="habilitado">
                            <div class="form-row align-items-center mb-0">
                                <div class="col">
                                    <input type="text" class="fs-13 mb-1 form-control layouttamaño" placeholder="titulo" name="titulo" required>
                                </div>
                                <div class="col">
                                    <input type="text" class="fs-13 mb-1 form-control layouttamaño" placeholder="link" list="link" name="link" />
                                    <datalist id="link" class="linkList" name="link" required>
                                        <?= $menu->menuOptions($categoryData) ?>
                                    </datalist>
                                </div>
                                <div class="col">
                                    <input type="text" class="fs-13 mb-1 form-control layouttamaño" placeholder="icono" name="icono">
                                </div>
                                <div class="col">
                                    <select class="fs-13 mb-1 form-control layouttamaño" name="target" required>
                                        <option value="_self">Misma Ventana</option>
                                        <option value="_blank">Nueva Ventana</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <select class="fs-13 mb-1 form-control layouttamaño" name="padre" required>
                                        <option value="0" selected>Menu Superior</option>
                                        <?php $menu->build_options("", "", $row["padre"], "admin") ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="text" class="fs-13 mb-1 form-control layouttamaño" placeholder="orden" name="orden" required>
                                </div>
                                <div class="col">
                                    <label for="opciones">Opciones
                                        <input type="checkbox" value="1" class="layouttamaño" placeholder="opciones" id="opciones" name="opciones">
                                    </label>
                                </div>
                                <div class="col">
                                    <button type="submit" name="create" class="btn-small btn btn-primary mb-2"><i class="fa fa-save"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($menuData) { ?>
            <div class="card col-md-12">
                <div class="card-body">
                    <div class="row ">
                        <div class="col-md-12">
                            <h3 class="text-center text-uppercase fs-20">Modificar</h3>
                            <hr style="border-style: dashed;">
                            <?php
                            $menu->build_admin("", 0, "admin", $idiomaGet, ["editar" => true, "crear" => true, "eliminar" => true]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>