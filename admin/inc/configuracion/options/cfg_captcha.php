<?php
if (isset($_POST["agregar-captcha"])) {
    $config->set("captcha_key", isset($_POST["c-key"]) ? $funciones->antihack_mysqli($_POST["c-key"]) : '');
    $config->set("captcha_secret", isset($_POST["c-secret"]) ? $funciones->antihack_mysqli($_POST["c-secret"]) : '');
    $error = $config->addCaptcha();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=captcha-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
?>
<div class="">
    <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=captcha-tab">
        <div class="row ">
            <label class="col-md-12">
                CAPTCHA KEY:<br />
                <input type="text" class="form-control" name="c-key" value="<?= $captchaData['data']["captcha_key"] ? $captchaData['data']["captcha_key"] : '' ?>" required />
            </label>
            <label class="col-md-12 mt-10">
                CAPTCHA SECRET:<br />
                <input type="text" class="form-control" name="c-secret" value="<?= $captchaData['data']["captcha_secret"] ? $captchaData['data']["captcha_secret"] : '' ?>" required />
            </label>
            <div class="col-md-12 mt-10">
                <button class="btn btn-primary btn-block" type="submit" name="agregar-captcha">Guardar cambios</button>
            </div>
        </div>
</div>