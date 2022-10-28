<?php
if (isset($_POST["agregar-exportadorMeli"])) {
    $config->set("clasica", isset($_POST["clasica"]) ? $funciones->antihack_mysqli($_POST["clasica"]) : '');
    $config->set("premium", isset($_POST["premium"]) ? $funciones->antihack_mysqli($_POST["premium"]) : '');
    $config->set("link_json", isset($_POST["link_json"]) ? $funciones->antihack_mysqli($_POST["link_json"]) : '');
    $config->set("carpeta_img", isset($_POST["carpeta_img"]) ? $funciones->antihack_mysqli($_POST["carpeta_img"]) : '');
    $config->calcular_envio = isset($_POST["calcular_envio"]) ? $funciones->antihack_mysqli($_POST["calcular_envio"]) : 0;
    if ($config->addExportadorMeli()) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=exportadorMeli-home');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
?>
<div>
    <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=exportadorMeli-tab">
        <div class="row">
            <div class="col-md-6">
                <label for="basic-URL_ADMIN">Porcentaje publicación clásica:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">%</span>
                    </div>
                    <input required class="form-control" name="clasica" min="-100" max="100" type="number" value="<?= $exportadorMeliData['data']["clasica"] ? $exportadorMeliData['data']["clasica"] : '0' ?>" />
                </div>
            </div>
            <div class="col-md-6">
                <label for="basic-URL_ADMIN">Porcentaje publicación premium:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">%</span>
                    </div>
                    <input required class="form-control" name="premium" min="-100" max="100" type="number" value="<?= $exportadorMeliData['data']["premium"] ? $exportadorMeliData['data']["premium"] : '0' ?>" />
                </div>
            </div>
            <div class="col-md-12">
                <br><input type="checkbox" name="calcular_envio" value="<?= $exportadorMeliData['data']["calcular_envio"] ? $exportadorMeliData['data']["calcular_envio"] : 0 ?>" <?= ($exportadorMeliData['data']["calcular_envio"] == 1) ? 'checked' : '' ?> />
                <label for="basic-URL_ADMIN">¿Calcular automaticamente el costo del envío por medio de MercadoLibre?:</label><br><br>
            </div>
            <div class="col-md-6">
                <label for="basic-URL_ADMIN">Link Json:</label>
                <div class="input-group">
                    <input class="form-control" name="link_json" type="url" value="<?= $exportadorMeliData['data']["link_json"] ? $exportadorMeliData['data']["link_json"] : '' ?>" />
                </div>
            </div>
            <div class="col-md-6">
                <label for="basic-URL_ADMIN">Carpeta Imágenes:</label>
                <div class="input-group">
                    <input class="form-control" name="carpeta_img" type="url" value="<?= $exportadorMeliData['data']["carpeta_img"] ? $exportadorMeliData['data']["carpeta_img"] : '' ?>" />
                </div>
            </div>
            <div class="col-md-12">
                <hr />
                <button class="btn btn-primary btn-block" type="submit" name="agregar-exportadorMeli">Guardar cambios</button>
            </div>
        </div>
    </form>
</div> 