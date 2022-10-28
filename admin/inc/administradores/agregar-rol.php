<?php
$roles = new Clases\Roles();
$menu = new Clases\Menu();
$f = new Clases\PublicFunction();

$menuList = $menu->list(["ISNULL(padre)", "area = 'admin'"], "es", "");
$cod = substr(md5(uniqid(rand())), 0, 10);
if (isset($_POST["agregar"])) {
    $roles->set("nombre", isset($_POST["nombre"]) ? $f->antihack_mysqli($_POST["nombre"]) : '');
    $roles->set("cod", $cod);
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
    $f->headerMove(URL_ADMIN . "/index.php?op=administradores");
}
?>
<div class="mt-20 card">
    <div class="card ">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header">
                    <h4 class="card-title text-uppercase text-center">
                        Crear un nuevo Rol
                    </h4>
                    <hr style="border-style: dashed;">
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="post">
                            <div class="row ">
                                <div class="col-md-12">
                                    <label for="nombre" class="mb-20 d-block"  >Titulo<hr/>
                                        <input type="text" name="nombre" id="nombre" placeholder="Titulo">
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <form method="post">
                                        <div class="row mb-10">
                                            <div class="col-md-8"><label>BRINDAR ACCESO A:</label><hr/></div>
                                            <div class="col-md-4">
                                                <i class="bx bx-plus pull-right mr-30"></i>
                                                <i class="bx bx-pencil pull-right mr-23"></i>
                                                <i class="bx bx-trash pull-right mr-27"></i>
                                            </div>
                                        </div>
                                        <div class="accordion product_bar pr-20 " id="accordionExample">
                                            <div class="row">
                                                <?php $menu->build_rol_edit($cod); ?>
                                            </div>
                                        </div>
                                        <div class=" mt-20">
                                            <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Crear un nuevo rol" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>