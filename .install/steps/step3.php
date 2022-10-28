<div class="container text-center">
    <h2>
        Crear Usuario Administrador
        <hr />
    </h2>
    <?php
    $f = new Clases\PublicFunction();
    $usuarios = new Clases\Usuarios();
    $admin = new Clases\Admin();
    if (isset($_POST["submit"])) {
        foreach ($_POST as $key => $value) {
            $usuarios->set($key, $value);
        }
        $usuarios->set("admin", "1");
        if ($usuarios->add()) {
            $usuarios->set("cod", "admin1");
            $userAdmin = $usuarios->view();
            $admin->set("rol", '"admin-role"');
            $admin->set("id", $userAdmin["data"]["id"]);
            $admin->addRolAdmin();
            $f->headerMove(URL_ADMIN);
        } else {
            echo "<div class='alert alert-danger'>Ups! No pudiste registrar un usuarios con estas credenciales</div>";
        }
    }
    ?>

    <form class="row" method="post">
        <div class="col-6 mb-3">
            <label for="nombre" class="text-uppercase form-label"><b>nombre</b></label><br />
            <input class="form-control" type="text" id="nombre" name="nombre" value="">
        </div>

        <div class="col-6 mb-3">
            <label for="apellido" class="text-uppercase form-label"><b>apellido</b></label><br />
            <input class="form-control" type="text" id="apellido" name="apellido" value="">
        </div>

        <div class="col-6 mb-3">
            <label for="email" class="text-uppercase form-label"><b>email</b></label><br />
            <input class="form-control" type="text" id="email" name="email" value="">
        </div>
        <div class="col-6 mb-3">
            <label for="contraseña" class="text-uppercase form-label"><b>contraseña</b></label><br />
            <input class="form-control" type="password" id="contraseña" name="password" value="">
        </div>

        <input type="hidden" name="cod" value="admin1">
        <input type="hidden" name="estado" value="1">
        <input type="hidden" name="idioma" value="es">

        <input type="submit" name="submit" class="btn btn-success mt-4" value="Siguiente Paso >" />
    </form>
</div>