<?php
if (isset($_POST["p1-guardar"])) {
    $config->set("variable1", isset($_POST["p1-v1"]) ? $funciones->antihack_mysqli($_POST["p1-v1"]) : '');
    $config->set("variable2", isset($_POST["p1-v2"]) ? $funciones->antihack_mysqli($_POST["p1-v2"]) : '');
    $config->set("variable3", isset($_POST["p1-v3"]) ? $funciones->antihack_mysqli($_POST["p1-v3"]) : '');
    $config->set("id", 1);
    $error = $config->updatePayment();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
if (isset($_POST["p2-guardar"])) {
    $config->set("variable1", isset($_POST["p2-v1"]) ? $funciones->antihack_mysqli($_POST["p2-v1"]) : '');
    $config->set("variable2", isset($_POST["p2-v2"]) ? $funciones->antihack_mysqli($_POST["p2-v2"]) : '');
    $config->set("variable3", isset($_POST["p2-v3"]) ? $funciones->antihack_mysqli($_POST["p2-v3"]) : '');
    $config->set("id", 2);
    $error = $config->updatePayment();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
if (isset($_POST["p3-guardar"])) {
    $config->set("variable1", isset($_POST["p3-v1"]) ? $funciones->antihack_mysqli($_POST["p3-v1"]) : '');
    $config->set("variable2", isset($_POST["p3-v2"]) ? $funciones->antihack_mysqli($_POST["p3-v2"]) : '');
    $config->set("variable3", isset($_POST["p3-v3"]) ? $funciones->antihack_mysqli($_POST["p3-v3"]) : '');
    $config->set("id", 3);
    $error = $config->updatePayment();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
if (isset($_POST["p4-guardar"])) {
    $config->set("variable1", isset($_POST["p4-v1"]) ? $funciones->antihack_mysqli($_POST["p4-v1"]) : '');
    $config->set("variable2", isset($_POST["p4-v2"]) ? $funciones->antihack_mysqli($_POST["p4-v2"]) : '');
    $config->set("variable3", isset($_POST["p4-v3"]) ? $funciones->antihack_mysqli($_POST["p4-v3"]) : '');
    $config->set("id", 4);
    $error = $config->updatePayment();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
if (isset($_POST["p5-guardar"])) {
    $config->set("variable1", isset($_POST["p5-v1"]) ? $funciones->antihack_mysqli($_POST["p5-v1"]) : '');
    $config->set("variable2", isset($_POST["p5-v2"]) ? $funciones->antihack_mysqli($_POST["p5-v2"]) : '');
    $config->set("variable3", isset($_POST["p5-v3"]) ? $funciones->antihack_mysqli($_POST["p5-v3"]) : '');
    $config->set("id", 5);
    $error = $config->updatePayment();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
$empresa1 = $pagosData1['data']["empresa"] ? $pagosData1['data']["empresa"] : '';
$empresa2 = $pagosData2['data']["empresa"] ? $pagosData2['data']["empresa"] : '';
$empresa3 = $pagosData3['data']["empresa"] ? $pagosData3['data']["empresa"] : '';
$empresa4 = $pagosData4['data']["empresa"] ? $pagosData4['data']["empresa"] : '';
$empresa5 = $pagosData5['data']["empresa"] ? $pagosData5['data']["empresa"] : '';
?>
<div>
    <div class="row">
        <div class="col-md-12">
            <div class="divider divider-light">
                <div class="divider-text"><b class="fs-16"><?= $empresa1 ?></b></div>
            </div>
            <form method="post" class="row" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab">
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa1 ?>" readonly>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p1-v1" value="<?= $pagosData1['data']["variable1"] ? $pagosData1['data']["variable1"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p1-v2" value="<?= $pagosData1['data']["variable2"] ? $pagosData1['data']["variable2"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p1-v3" value="<?= $pagosData1['data']["variable3"] ? $pagosData1['data']["variable3"] : '' ?>">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary mb-2 btn-block" name="p1-guardar">Guardar</button>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-10">
            <div class="divider divider-light">
                <div class="divider-text"><b class="fs-16"><?= $empresa2 ?></b></div>
            </div>
            <form method="post" class="row" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab">
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa2 ?>" readonly>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p2-v1" value="<?= $pagosData2['data']["variable1"] ? $pagosData2['data']["variable1"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p2-v2" value="<?= $pagosData2['data']["variable2"] ? $pagosData2['data']["variable2"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p2-v3" value="<?= $pagosData2['data']["variable3"] ? $pagosData2['data']["variable3"] : '' ?>">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary mb-2 btn-block" name="p2-guardar">Guardar</button>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-10">
            <div class="divider divider-light">
                <div class="divider-text"><b class="fs-16"><?= $empresa3 ?></b></div>
            </div>
            <form method="post" class="row" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab">
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa3 ?>" readonly>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p3-v1" value="<?= $pagosData3['data']["variable1"] ? $pagosData3['data']["variable1"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p3-v2" value="<?= $pagosData3['data']["variable2"] ? $pagosData3['data']["variable2"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p3-v3" value="<?= $pagosData3['data']["variable3"] ? $pagosData3['data']["variable3"] : '' ?>">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary mb-2 btn-block" name="p3-guardar">Guardar</button>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-10">
            <div class="divider divider-light">
                <div class="divider-text"><b class="fs-16"><?= $empresa4 ?></b></div>
            </div>
            <form method="post" class="row" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab">
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa4 ?>" readonly>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p4-v1" value="<?= $pagosData4['data']["variable1"] ? $pagosData4['data']["variable1"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p4-v2" value="<?= $pagosData4['data']["variable2"] ? $pagosData4['data']["variable2"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p4-v3" value="<?= $pagosData4['data']["variable3"] ? $pagosData4['data']["variable3"] : '' ?>">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block" name="p4-guardar">Guardar</button>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-10">
            <div class="divider divider-light">
                <div class="divider-text"><b class="fs-16"><?= $empresa5 ?></b></div>
            </div>
            <form method="post" class="row" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificarTec&tab=pagos-tab">
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="<?= $empresa5 ?>" readonly>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="KEY" name="p5-v1" value="<?= $pagosData5['data']["variable1"] ? $pagosData5['data']["variable1"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="SECRET" name="p5-v2" value="<?= $pagosData5['data']["variable2"] ? $pagosData5['data']["variable2"] : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="" name="p5-v3" value="<?= $pagosData5['data']["variable3"] ? $pagosData5['data']["variable3"] : '' ?>">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block" name="p5-guardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>