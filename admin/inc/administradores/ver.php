<?php
$admin = new Clases\Admin();
$roles = new Clases\Roles();
$f = new Clases\PublicFunction();
$rolesData = $roles->list("", "GROUP BY cod", "");
?>
<div class="">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row ">
                                <h4 class="mt-20  col-md-6 pull-left ">Administradores</h4>
                                <div class="col-md-6 mb-0 pb-0 ">
                                    <div class="btn-group pull-right text-right" role="group" aria-label="Basic example">
                                        <?php if ($_SESSION["admin"]["crud"]["editar"]) { ?>

                                            <a class="btn btn-success py-1 my-1" href="<?= URL_ADMIN ?>/index.php?op=administradores&accion=agregar">
                                                AGREGAR ADMINISTRADOR
                                            </a>

                                        <?php } ?>
                                        <?php if ($_SESSION["admin"]["crud"]["eliminar"]) { ?>
                                            <a class="btn btn-primary py-1 my-1" href="<?= URL_ADMIN ?>/index.php?op=administradores&accion=ver-roles">
                                                ROLES
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="mb-1    " />
                            <fieldset class="form-group position-relative  has-icon-left m-0">
                                <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
                                <div class="form-control-position">
                                    <i class="bx bx-search"></i>
                                </div>
                            </fieldset>
                            <div class="clearfix"></div>
                            <hr />
                            <div class="table-responsive">
                                <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <th>Usuario</th>
                                        <th>Rol</th>
                                        <th class="text-right"><i class="fa fa-wrench"></i></th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_POST["id"])) {
                                            $id = $f->antihack_mysqli($_POST["id"]);
                                            $rol = isset($_POST["rol"]) ? $_POST["rol"] : array();
                                            $admin->set("id", $id);
                                            $adminData = $admin->view();
                                            if (empty($adminData["data"]["rol"])) {
                                                $admin->set("rol", "'" . $rol . "'");
                                                $admin->addRolAdmin();
                                            } else {
                                                $admin->set("rol", "'" . $rol . "'");
                                                $admin->editRolAdmin();
                                            }
                                        }
                                        $adminArray = $admin->list(["usuarios.admin = 1"], "", "");
                                        if (is_array($adminArray)) {
                                            foreach ($adminArray as $data) {
                                        ?>
                                                <tr>
                                                    <td><?= mb_strtolower($data['data']["email"]) ?></td>
                                                    <form method="post">
                                                        <td>
                                                            <select name="rol" id="rol" style="width:auto" class="text-uppercase" onchange="this.form.submit()">
                                                                <option selected>--- Seleccionar ---</option>
                                                                <?php foreach ($rolesData as $rolData) {
                                                                    if (strtolower($rolData["data"]["nombre"]) == "desarrollador" && $_ENV["DEVELOPMENT"] == 0) continue; ?>
                                                                    <option <?= in_array($rolData["data"]["cod"], $data["data"]["rol"]) ? "selected" : "" ?> value="<?= $rolData["data"]["cod"] ?>"><?= $rolData["data"]["nombre"] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <a data-toggle="tooltip" class="btn btn-default" data-placement="top" title="Modificar" href="<?= URL_ADMIN . '/index.php?op=administradores&accion=modificar&id=' . $data['data']['id'] ?>">
                                                                    <div class="fonticon-wrap">
                                                                        <i class="bx bx-cog fs-16"></i>
                                                                    </div>
                                                                </a>
                                                                <a class="deleteConfirm btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL_ADMIN . '/index.php?op=administradores&accion=ver&borrar=' . $data['data']['id'] ?>">
                                                                    <div class="fonticon-wrap">
                                                                        <i class="bx bx-trash fs-16"></i>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <input type="hidden" name="id" value="<?= $data["data"]["id"] ?>">
                                                    </form>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
if (isset($_GET["borrar"])) {
    $id = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $admin->set("id", $id);
    $admin->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=administradores");
}
?>