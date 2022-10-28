<?php

if (isset($_POST["agregar-email"])) {
    $config->set("remitente", isset($_POST["e-remitente"]) ? $funciones->antihack_mysqli($_POST["e-remitente"]) : '');
    $config->set("smtp", isset($_POST["e-smtp"]) ? $funciones->antihack_mysqli($_POST["e-smtp"]) : '');
    $config->set("smtp_secure", isset($_POST["e-smtp-secure"]) ? $funciones->antihack_mysqli($_POST["e-smtp-secure"]) : '');
    $config->set("puerto", isset($_POST["e-puerto"]) ? $funciones->antihack_mysqli($_POST["e-puerto"]) : '');
    $config->set("email_", isset($_POST["e-email"]) ? $funciones->antihack_mysqli($_POST["e-email"]) : '');
    $config->set("password", isset($_POST["e-password"]) ? $funciones->antihack_mysqli($_POST["e-password"]) : '');
    $error = $config->addEmail();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=email-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
?>
<div class="">
    <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=email-tab">
        <div class="row">
            <label class="col-md-6">
                Remitente:<br />
                <input type="email" class="form-control" name="e-remitente" value="<?= $emailData['data']["remitente"] ? $emailData['data']["remitente"] : '' ?>" required />
            </label>
            <label class="col-md-6">
                Email:<br />
                <input type="email" class="form-control" name="e-email" value="<?= $emailData['data']["email"] ? $emailData['data']["email"] : '' ?>" required />
            </label>
            <div class="col-md-12 mt-10"></div>
            <label class="col-md-4">
                SMTP Server:<br />
                <input type="text" class="form-control" name="e-smtp" value="<?= $emailData['data']["smtp"] ? $emailData['data']["smtp"] : '' ?>" required />
            </label>
            <label class="col-md-2">
                SMTP Secure:<br />
                <select name="e-smtp-secure" required>
                    <?php
                    if (!empty($emailData['data']['smtp_secure'])) {
                        $secure = $emailData['data']['smtp_secure'];
                    ?>
                        <option value="tls" <?php if ($secure == "tls") {
                                                echo "selected";
                                            } ?>>
                            TLS
                        </option>
                        <option value="ssl" <?php if ($secure == "ssl") {
                                                echo "selected";
                                            } ?>>
                            SSL
                        </option>
                    <?php
                    } else {
                    ?>
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                    <?php
                    }
                    ?>
                </select>
            </label>
            <label class="col-md-2">
                Puerto:<br />
                <input type="number" class="form-control" name="e-puerto" value="<?= $emailData['data']["puerto"] ? $emailData['data']["puerto"] : '' ?>" required />
            </label>
            <label class="col-md-4">
                Password:<br />
                <input type="password" class="form-control" name="e-password" value="<?= $emailData['data']["password"] ? $emailData['data']["password"] : '' ?>" required />
            </label>
            <div class="col-md-12 mt-20">
                <button class="btn btn-primary btn-block" type="submit" name="agregar-email">Guardar cambios</button>
            </div>
        </div>
    </form>
</div> 