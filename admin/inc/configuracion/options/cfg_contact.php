<?php
if (isset($_POST["agregar-contacto"])) {
    $config->set("email", isset($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '');
    $config->set("telefono", isset($_POST["telefono"]) ? $funciones->antihack_mysqli($_POST["telefono"]) : '');
    $config->set("whatsapp", isset($_POST["whatsapp"]) ? $funciones->antihack_mysqli($_POST["whatsapp"]) : '');
    $config->set("messenger", isset($_POST["messenger"]) ? $funciones->antihack_mysqli($_POST["messenger"]) : '');
    $config->set("domicilio", isset($_POST["domicilio"]) ? $funciones->antihack_mysqli($_POST["domicilio"]) : '');
    $config->set("localidad", isset($_POST["localidad"]) ? $funciones->antihack_mysqli($_POST["localidad"]) : '');
    $config->set("provincia", isset($_POST["provincia"]) ? $funciones->antihack_mysqli($_POST["provincia"]) : '');
    $config->set("pais", isset($_POST["pais"]) ? $funciones->antihack_mysqli($_POST["pais"]) : '');
    $error = $config->addContact();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificar&tab=contact-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
?>
<div class="">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body pt-10">
                            <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificar&tab=contact-tab">
                                <div class="row ">
                                    <label class="col-md-12 ">
                                        Email:<br />
                                        <input type="email" class="form-control" name="email" value="<?= $contactoData['data']["email"] ? $contactoData['data']["email"] : '' ?>" required />
                                    </label>
                                    <label class="col-md-4 mt-10">
                                        Teléfono:<br />
                                        <input type="text" class="form-control" name="telefono" value="<?= $contactoData['data']["telefono"] ? $contactoData['data']["telefono"] : '' ?>" required />
                                    </label>
                                    <label class="col-md-4 mt-10">
                                        Whatsapp:<br />
                                        <input type="text" class="form-control" name="whatsapp" value="<?= $contactoData['data']["whatsapp"] ? $contactoData['data']["whatsapp"] : '' ?>" />
                                    </label>
                                    <label class="col-md-4 mt-10">
                                        Messenger:<br />
                                        <input type="text" class="form-control" name="messenger" value="<?= $contactoData['data']["messenger"] ? $contactoData['data']["messenger"] : '' ?>" />
                                    </label>
                                    <label class="col-md-3 mt-10">
                                        Domicilio:<br />
                                        <input type="text" class="form-control" name="domicilio" value="<?= $contactoData['data']["domicilio"] ? $contactoData['data']["domicilio"] : '' ?>" required />
                                    </label>
                                    <label class="col-md-3 mt-10">
                                        Localidad:<br />
                                        <input type="text" class="form-control" name="localidad" value="<?= $contactoData['data']["localidad"] ? $contactoData['data']["localidad"] : '' ?>" required />
                                    </label>
                                    <label class="col-md-3 mt-10">
                                        Provincia:<br />
                                        <input type="text" class="form-control" name="provincia" value="<?= $contactoData['data']["provincia"] ? $contactoData['data']["provincia"] : '' ?>" required />
                                    </label>
                                    <label class="col-md-3 mt-10">
                                        País:<br />
                                        <input type="text" class="form-control" name="pais" value="<?= $contactoData['data']["pais"] ? $contactoData['data']["pais"] : '' ?>" required />
                                    </label>
                                    <div class="col-md-12 mt-20">
                                        <button class="btn btn-primary btn-block" type="submit" name="agregar-contacto">Guardar cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>