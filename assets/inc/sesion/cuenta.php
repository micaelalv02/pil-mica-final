<?php
//Clases
$hub = new Clases\Hubspot();
$usuario->set("cod", $_SESSION["usuarios"]["cod"]);
$usuarioData = $usuario->view();
?>
<div class="col-md-12 mb-10">
    <?php
    if (isset($_POST["guardar"])) :
        $pass = false;
        $error = '';

        $nombre = !empty($_POST["nombre"]) ? $f->antihack_mysqli($_POST["nombre"]) : '';
        $apellido = !empty($_POST["apellido"]) ? $f->antihack_mysqli($_POST["apellido"]) : '';
        $email = !empty($_POST["email"]) ? $f->antihack_mysqli($_POST["email"]) : '';
        $password = !empty($_POST["password"]) ? $f->antihack_mysqli($_POST["password"]) : '';
        $provincia = !empty($_POST["provincia"]) ? $f->antihack_mysqli($_POST["provincia"]) : '';
        $localidad = !empty($_POST["localidad"]) ? $f->antihack_mysqli($_POST["localidad"]) : '';
        $direccion = !empty($_POST["direccion"]) ? $f->antihack_mysqli($_POST["direccion"]) : '';
        $telefono = !empty($_POST["telefono"]) ? $f->antihack_mysqli($_POST["telefono"]) : '';
        $celular = !empty($_POST["celular"]) ? $f->antihack_mysqli($_POST["celular"]) : '';
        $postal = !empty($_POST["postal"]) ? $f->antihack_mysqli($_POST["postal"]) : '';

        if (!empty($_POST["password"]) && !empty($_POST["password2"])) {
            if ($_POST["password"] == $_POST['password2']) {
                $pass = true;
                $password = $f->antihack_mysqli($_POST["password"]);
            } else {
                $error = '<div class="alert alert-warning" role="alert">' . $_SESSION["lang-txt"]["usuarios"]["error_password"] . '</div>';
            }
        }

        if (empty($error)) {
            $usuario->set("cod", $usuarioData['data']['cod']);
            $usuario->set("email", $email);

            $usuario->editSingle("nombre", $nombre);
            $usuario->editSingle("apellido", $apellido);
            $usuario->editSingle("email", $email);
            $usuario->editSingle("provincia", $provincia);
            $usuario->editSingle("localidad", $localidad);
            $usuario->editSingle("direccion", $direccion);
            $usuario->editSingle("telefono", $telefono);
            $usuario->editSingle("celular", $celular);
            $usuario->editSingle("postal", $postal);
            if ($pass) $usuario->editSingle("password", $password);
            $usuario->editSingle("fecha", $usuarioData['data']['fecha']);
            $f->headerMove(URL . '/sesion/cuenta');
        }
    endif;
    ?>
    <br>
    <form class="login_form" id="registro" method="post" autocomplete="off">
        <div class="row">
            <?= !empty($error) ? $error : '' ?>
  
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["nombre"] ?>
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['nombre'] ?>" type="text" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["nombre"] ?>" name="nombre" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-user"></i></span>
                </div>
            </div>
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["apellido"] ?>
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['apellido'] ?>" type="text" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["apellido"] ?>" name="apellido" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-user"></i></span>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12"><?= $_SESSION["lang-txt"]["usuarios"]["email"] ?>
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['email'] ?>" type="email" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["email"] ?>" name="email" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-envelope"></i></span>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["telefono"] ?>
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['telefono'] ?>" type="number" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["telefono"] ?>" name="telefono" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-phone"></i></span>
                </div>
            </div>
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["celular"] ?>
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['celular'] ?>" type="number" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["celular"] ?>" name="celular" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-phone"></i></span>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["provincia"] ?>
                <div class="input-group">
                    <select class="pull-right form-control h40" name="provincia" data-url="<?= URL ?>" id="provincia" required>
                        <option value="<?= $usuarioData['data']['provincia'] ?>" selected><?= $usuarioData['data']['provincia'] ?></option>
                        <option value="" disabled><?= $_SESSION["lang-txt"]["usuarios"]["provincia"] ?></option>
                        <?php $f->provincias() ?>
                    </select>
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["localidad"] ?>
                <div class="input-group">
                    <select class="form-control h40" name="localidad" id="localidad" required>
                        <option value="<?= $usuarioData['data']['localidad'] ?>" selected><?= $usuarioData['data']['localidad'] ?></option>
                        <option value="" disabled><?= $_SESSION["lang-txt"]["usuarios"]["localidad"] ?></option>
                    </select>
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["direccion"] ?>
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['direccion'] ?>" type="text" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["direccion"] ?>" name="direccion" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["codigo_postal"] ?>
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['postal'] ?>" type="text" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["codigo_postal"] ?>" name="postal" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
        </div>
        <br />
        <hr>
        <br />
        <sup><?= $_SESSION["lang-txt"]["usuarios"]["cambiar_password"] ?></sup>
        <br>
        <div class="row">
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["password"] ?>
                <input type="password" name="password" id="password_fake" class="hidden" autocomplete="off" style="display: none;">
                <div class="input-group">
                    <input autocomplete="off" class="form-control h40" value="" type="password" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["password"] ?>" name="password" />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-lock"></i></span>
                </div>
            </div>
            <div class="col-md-6"><?= $_SESSION["lang-txt"]["usuarios"]["re_password"] ?>
                <div class="input-group">
                    <input class="form-control h40" value="" type="password" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["re_password"] ?>" name="password2" />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-lock"></i></span>
                </div>
            </div>
        </div>
        <br />
        <button style="width: 100%;" type="submit" name="guardar" class="btn btn-success"><?= $_SESSION["lang-txt"]["usuarios"]["guardar"] ?></button>
    </form>
    <br>
</div>