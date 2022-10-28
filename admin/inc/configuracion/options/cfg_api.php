<?php
if (isset($_POST["agregar-api-ml"])) {
    $config->set("app_id", isset($_POST["ml-id"]) ? $funciones->antihack_mysqli($_POST["ml-id"]) : '');
    $config->set("app_secret", isset($_POST["ml-secret"]) ? $funciones->antihack_mysqli($_POST["ml-secret"]) : '');
    $error = $config->addMercadoLibre();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=api-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
if (isset($_POST["agregar-api-hubspot"])) {
    $config->set("api_key", isset($_POST["api_key"]) ? $funciones->antihack_mysqli($_POST["api_key"]) : '');
    $error = $config->addHubspot();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=api-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
if (isset($_POST["agregar-api-andreani"])) {
    $config->set("usuario", isset($_POST["api-andreani-usuario"]) ? $funciones->antihack_mysqli($_POST["api-andreani-usuario"]) : '');
    $config->set("contrasenia", isset($_POST["api-andreani-contraseña"]) ? $funciones->antihack_mysqli($_POST["api-andreani-contraseña"]) : '');
    $config->set("codCliente", isset($_POST["api-andreani-cod"]) ? $funciones->antihack_mysqli($_POST["api-andreani-cod"]) : '');
    $config->set("envioSucursal", isset($_POST["api-andreani-enviosucursal"]) ? $funciones->antihack_mysqli($_POST["api-andreani-enviosucursal"]) : '');
    $config->set("envioDomicilio", isset($_POST["api-andreani-enviodomicilio"]) ? $funciones->antihack_mysqli($_POST["api-andreani-enviodomicilio"]) : '');
    $config->set("envioUrgente", isset($_POST["api-andreani-enviourgente"]) ? $funciones->antihack_mysqli($_POST["api-andreani-enviourgente"]) : '');
    $error = $config->addAndreani();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=api-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
?>
<div id="accordion">
    <div class="card mb-10 mt-5">
        <a class="btn btn-primary ml-20" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            <div class="card-header" id="headingOne" style="border-bottom: unset">
                <h5 class="mb-0" style="color: white">
                    Mercado Libre
                </h5>
            </div>
        </a>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=api-tab">
                    <div class="row">
                        <label class="col-md-12">
                            APP ID:<br />
                            <input type="text" class="form-control" name="ml-id" value="<?= $mercadoLibreData['data']["app_id"] ? $mercadoLibreData['data']["app_id"] : '' ?>" required />
                        </label>
                        <label class="col-md-12 mt-10">
                            APP SECRET:<br />
                            <input type="text" class="form-control" name="ml-secret" value="<?= $mercadoLibreData['data']["app_secret"] ? $mercadoLibreData['data']["app_secret"] : '' ?>" required />
                        </label>
                        <div class="col-md-12 mt-10">
                            <button class="btn btn-primary" type="submit" name="agregar-api-ml">Guardar cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card mb-10">
        <a class="btn btn-primary collapsed ml-20" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            <div class="card-header" id="headingTwo" style="border-bottom: unset">
                <h5 class="mb-0" style="color: white">
                    Andreani
                </h5>
            </div>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
                <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=api-tab">
                    <div class="row">
                        <label class="col-md-4">
                            Nombre de usuario:<br />
                            <input type="text" class="form-control" name="api-andreani-usuario" value="<?= $andreaniData['data']["usuario"] ? $andreaniData['data']["usuario"] : '' ?>" required />
                        </label>
                        <label class="col-md-4">
                            Contraseña:<br />
                            <input type="text" class="form-control" name="api-andreani-contraseña" value="<?= $andreaniData['data']["contraseña"] ? $andreaniData['data']["contraseña"] : '' ?>" required />
                        </label>
                        <label class="col-md-4">
                            Código de cliente:<br />
                            <input type="text" class="form-control" name="api-andreani-cod" value="<?= $andreaniData['data']["cod"] ? $andreaniData['data']["cod"] : '' ?>" required />
                        </label>
                        <label class="col-md-4 mt-10">
                            Contrato para envíos a sucursal:<br />
                            <input type="text" class="form-control" name="api-andreani-enviosucursal" value="<?= $andreaniData['data']["envio_sucursal"] ? $andreaniData['data']["envio_sucursal"] : '' ?>" required />
                        </label>
                        <label class="col-md-4 mt-10">
                            Contrato para envíos estándar a domicilio:<br />
                            <input type="text" class="form-control" name="api-andreani-enviodomicilio" value="<?= $andreaniData['data']["envio_domicilio"] ? $andreaniData['data']["envio_domicilio"] : '' ?>" required />
                        </label>
                        <label class="col-md-4 mt-10">
                            Contrato para envíos urgentes a domicilio:<br />
                            <input type="text" class="form-control" name="api-andreani-enviourgente" value="<?= $andreaniData['data']["envio_urgente"] ? $andreaniData['data']["envio_urgente"] : '' ?>" required />
                        </label>
                        <div class="col-md-12 mt-10">
                            <button class="btn btn-primary" type="submit" name="agregar-api-andreani">Guardar cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card mb-10">
        <a class="btn btn-primary ml-20" data-toggle="collapse" data-target="#collapseHubspot" aria-expanded="false" aria-controls="collapseHubspot">
            <div class="card-header" id="headingOne" style="border-bottom: unset">
                <h5 class="mb-0" style="color: white">
                    Hubspot
                </h5>
            </div>
        </a>
        <div id="collapseHubspot" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=api-tab">
                    <div class="row">
                        <label class="col-md-12">
                            API KEY:<br />
                            <input type="text" class="form-control" name="api_key" value="<?= $hubspotData['data']["api_key"] ? $hubspotData['data']["api_key"] : '' ?>" required />
                        </label>
                        <div class="col-md-12 mt-10">
                            <button class="btn btn-primary" type="submit" name="agregar-api-hubspot">Guardar cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>