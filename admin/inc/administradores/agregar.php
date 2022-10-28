<?php
$admin = new Clases\Admin();
$usuarios = new Clases\Usuarios();
$usuariosData = $usuarios->list(["admin != 1"], "", "");

if (isset($_POST["createUserAdmin"])) {
    $usuarios->set("cod", $funciones->antihack_mysqli($_POST["user"]));
    $usuarios->editSingle("admin", 1);
    $funciones->headerMove(URL_ADMIN . "/index.php?op=administradores");
}
if (isset($_POST["agregar"])) {

    $admin->set("email", isset($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '');
    $admin->set("cod", substr(md5(uniqid(rand())), 0, 10));
    $admin->set("password", isset($_POST["pass"]) ? hash('sha256', $funciones->antihack_mysqli($_POST["pass"]) . SALT) : '');
    $admin->set("admin", 1);
    $admin->add();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=administradores");
}
$col = "12";
?>
<link href="<?= URL_ADMIN ?>/css/auto-complete.css" rel="stylesheet">

<div class="mt-20 card">
    <div class="card ">
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($usuariosData)) {
                    $col = "6"; ?>
                    <div class="card-header">
                        <h4 class="card-title text-uppercase text-center">
                            Seleccionar un usuario como Administrador
                        </h4>
                        <hr class="mb-0 pb-0" style="border-style: dashed;">
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" class="row" enctype="multipart/form-data">
                                <?php  ?>
                                <div class="mb-10 mr-10 mt-10 seachbar-block" style="width:100%!important" data-url="<?= URL_ADMIN ?>">
                                    <div class="searchbar">
                                        <input class="search_input form-control fs-14 pl-15 " id="search-bar-nav" type="text" name="user" placeholder="Buscar usuario">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-20">
                                    <input type="submit" class="btn btn-primary btn-block" name="createUserAdmin" value="Brindar permisos de Administrador" />
                                </div>
                            </form>
                        </div>
                    </div>
                <?php  } ?>
            </div>
            <div class="col-md-<?= $col ?>">
                <div class="card-header">
                    <h4 class="card-title text-uppercase text-center">
                        Crear un Administrador
                    </h4>
                    <hr class="mb-0 pb-0" style="border-style: dashed;">
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="post" class="row" enctype="multipart/form-data">
                            <label class="col-md-6">Email:<br />
                                <input type="text" name="email" value="" required>
                            </label>
                            <label class="col-md-6">Contraseña:<br />
                                <input type="text" name="pass" required>
                            </label>
                            <br />
                            <div class="col-md-12 mt-20">
                                <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Crear Administrador" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= URL_ADMIN ?>/js/jquery-ui.min.js"></script>
<script src="<?= URL_ADMIN ?>/js/search.js"></script>