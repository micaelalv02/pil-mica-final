<?php
$roles = new Clases\Roles();
$menu = new Clases\Menu();
$f = new Clases\PublicFunction();
$admin = new Clases\Admin();


$rolCod = isset($_GET["rol"]) ? $f->antihack_mysqli($_GET["rol"]) : '';

if (isset($_GET["borrar"])) {
    $roles->set("cod", $f->antihack_mysqli($_GET["borrar"]));
    $roles->delete();
}
if (isset($_POST["editar"])) {
    $roles->set("nombre", isset($_POST["nombre"]) ? $f->antihack_mysqli($_POST["nombre"]) : '');
    $roles->set("cod", isset($_POST["rol"]) ? $f->antihack_mysqli($_POST["rol"]) : '');

    $admin->set("rol", "'" . $f->antihack_mysqli($_POST["rol"]) . "'");
    $users = $admin->listRolAdmin();
    $roles->delete();
    foreach ($_POST["permissions"]["id"] as $permission) {
        $roles->set("permisos", $permission);
        if (isset($_POST["permissions"][$permission])) {
            $roles->set("crear",  isset($_POST["permissions"][$permission]["crear"]) ? 1 : 0);
            $roles->set("editar",  isset($_POST["permissions"][$permission]["editar"]) ? 1 : 0);
            $roles->set("eliminar",  isset($_POST["permissions"][$permission]["eliminar"]) ? 1 : 0);
        } else {
            $roles->set("crear", 0);
            $roles->set("editar", 0);
            $roles->set("eliminar", 0);
        }
        $roles->add();
    }
    foreach ($users as $userItem) {
        $admin->set("id", $userItem["admin"]);
        $admin->addRolAdmin();
    }
}
$rolData = $roles->list("", "", "", "GROUP BY nombre");
$nombre = '';
foreach ($rolData as $rol_) {
    if ($rol_["data"]["cod"] == $rolCod) {
        $nombre = $rol_["data"]["nombre"];
    }
}
if ($nombre == '' && isset($rolData[1]["data"]["nombre"])) {
    $rolCod = $rolData[1]["data"]["cod"];
    $nombre  = $rolData[1]["data"]["nombre"];
}
?>
<div class="mt-20 card">
    <div class="card ">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header">
                    <h4 class="card-title text-uppercase text-center">
                        Administrar Roles
                    </h4>
                    <hr style="border-style: dashed;">
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="col-md-12">
                                    <div class="fs-14 text-center bold">ROLES CREADOS
                                        <hr />
                                    </div>

                                    <aside class="sidebar_widget mt-10 mt-lg-0 product_bar">
                                        <div class="container text-uppercase">
                                            <div class="widget-list mb-10 mt-20">
                                                <?php foreach ($rolData as $rolItem) {
                                                    $active = '';
                                                    if ($nombre == $rolItem["data"]["nombre"]) $active = "background-color: currentColor;";
                                                    if (strtolower($rolItem["data"]["nombre"]) == "desarrollador" && $_ENV["DEVELOPMENT"] == 0) continue;
                                                ?>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <p><a class="text-uppercase" href="<?= URL_ADMIN ?>/index.php?op=administradores&accion=ver-roles&rol=<?= $rolItem["data"]["cod"] ?>"><i class="fa fa-edit"></i> <?= strtolower($rolItem["data"]["nombre"]) ?></a></p>
                                                        </div>
                                                        <div class="col-md-4 text-right">
                                                            <div class="btn-group pull-right" role="group" aria-label="Basic example">
                                                                <a data-toggle="tooltip" class="btn-default btn" data-placement="top" title="Ver" href="<?= URL_ADMIN . '/index.php?op=administradores&accion=ver-roles&rol=' . $rolItem['data']['cod'] ?>">
                                                                    <div class="fonticon-wrap">
                                                                        <i class="fs-13 bx bx-show"></i>
                                                                    </div>
                                                                </a>
                                                                <a class="deleteConfirm btn-danger btn" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN . '/index.php?op=administradores&accion=ver-roles&rol=' . $rolItem['data']['cod'] . '&borrar=' . $rolItem['data']['cod'] ?>">
                                                                    <div class="fonticon-wrap">
                                                                        <i class="fs-13 bx bx-trash"></i>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <a href="<?= URL_ADMIN ?>/index.php?op=administradores&accion=agregar-rol" class="btn-block btn btn-success">Agregar Rol</a>
                                    </aside>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="col">
                                    <div class="fs-14 text-center bold">EDITAR ROL
                                        <hr />
                                    </div>
                                </div>
                                <form method="post" class="col-md-12">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label for="nombre" class="mb-20" style="width:100%">
                                                Titulo
                                                <input type="text" name="nombre" class="mt-10" id="nombre" value="<?= $nombre ?>" placeholder="Titulo">
                                            </label>
                                            <input type="hidden" name="rol" value="<?= $rolCod ?>">
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row mb-10">
                                                <div class="col-md-8 fs-13"><label>BRINDAR ACCESO A:</label></div>
                                                <div class="col-md-4">
                                                    <i class="bx bx-plus pull-right mr-30"></i>
                                                    <i class="bx bx-pencil pull-right mr-23"></i>
                                                    <i class="bx bx-trash pull-right mr-27"></i>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="accordion product_bar pr-20 " id="accordionExample">
                                                <div class="row">
                                                    <?php $menu->build_rol_edit($rolCod); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" mt-20">
                                        <input type="submit" class="btn btn-primary btn-block" name="editar" value="GUARDAR CAMBIOS" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>