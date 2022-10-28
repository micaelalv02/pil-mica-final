<?php
$admin = new Clases\Admin();
$id = isset($_GET["id"]) ? $funciones->antihack_mysqli($_GET["id"]) : '';
$admin->set("id", $id);
$data = $admin->view();

if (isset($_POST["agregar"])) {
    $admin->set("id", $id);
    $admin->set("email", isset($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '');
    $admin->set("password", isset($_POST["contraseña"]) ? hash('sha256', $funciones->antihack_mysqli($_POST["contraseña"]) . SALT) : '');

    if ($admin->edit()) {
        $funciones->headerMove(URL_ADMIN . "/index.php?op=administradores");
    }
}
?>
<div class="mt-20 card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Administradores
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <label class="col-md-6">Email:<br />
                        <input type="text" value="<?= $data['data']["email"] ?>" name="email" required>
                    </label>
                    <label class="col-md-6">Contraseña:<br />
                        <input type="text" value="<?= $data['data']["password"] ?>" name="contraseña" required>
                    </label>
                    <br />
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Modificar Administrador" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>