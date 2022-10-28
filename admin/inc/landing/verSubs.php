<?php
$Contenidos = new Clases\Contenidos();
$landingSubs = new Clases\LandingSubs();
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '0';
$filter = array();
$landingSubsList = $landingSubs->list("`landing_cod` = '$cod'");

$landingData = $Contenidos->list(["filter" => ["`contenidos`.`cod` = '$cod'"]], "", true);
$landingSubs->set("landingCod", $cod);

$winner = $landingSubs->searchWinner();
if (isset($_POST["winner"])) {
    $limit = $funciones->antihack_mysqli(isset($_POST["winner"]) ? $_POST["winner"] : '');
    $ganador = $landingSubs->selectWinner($limit);
    foreach ($ganador as $key => $ganador_) {
        $landingSubs->set("id", $ganador_['id']);
        $landingSubs->set("ganador", $key + 1);
        $landingSubs->updateWinner();
    }
    $funciones->headerMove(URL_ADMIN . '/index.php?op=landing&accion=verSubs&cod=' . $cod);
}
if (isset($_POST["reset"])) {
    $landingSubs->resetWinner();
    $funciones->headerMove(URL_ADMIN . '/index.php?op=landing&accion=verSubs&cod=' . $cod);
}

$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);
$formList = json_decode(file_get_contents(dirname(__DIR__) . '/landing/campos-form.json', false, stream_context_create($arrContextOptions)), true);

$formData = "";
foreach ($formList as $formitem) {
    if ($formitem['landing'] == $cod) {
        $formData = $formitem;
        break;
    }
}

// echo "<pre>";
// var_dump($formList);
// echo "</pre>";

?>


<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">


                <h4 class="mt-20 pull-left">Peticiones</h4>
                <?php
                if (empty($winner) && !empty($landingSubsList)) {
                ?>
                    <div class="dropdown pull-right">
                        <button class="btn btn-secondary pull-right glow  dropdown-toggle " type="button" id="dropdownMenuButton" data-toggle="dropdown">
                            Seleccionar Ganadores
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <form method="post" class="inline-block">
                                <input type="hidden" name="winner" value="1">
                                <button name="winner1" type="submit" style="background-color:#fff;border:none" class="mt-10 fs-16 ">
                                    Seleccionar 1 Ganador
                                </button>
                            </form>
                            <form method="post" class="inline-block">
                                <input type="hidden" name="winner" value="2">
                                <button name="winner2" type="submit" style="background-color:#fff;border:none" class="mt-10 fs-16 ">
                                    Seleccionar 2 Ganadores
                                </button>
                            </form>
                            <form method="post" class="inline-block">
                                <input type="hidden" name="winner" value="3">
                                <button name="winner3" type="submit" style="background-color:#fff;border:none" class="mt-10 fs-16 ">
                                    Seleccionar 3 Ganadores
                                </button>
                            </form>
                            <form method="post" class="inline-block">
                                <input type="hidden" name="winner" value="4">
                                <button name="winner4" type="submit" style="background-color:#fff;border:none" class="mt-10 fs-16">
                                    Seleccionar 4 Ganadores
                                </button>
                            </form>
                            <form method="post" class="inline-block">
                                <input type="hidden" name="winner" value="5">
                                <button name="winner5" type="submit" style="background-color:#fff;border:none" class="mt-10 fs-16">
                                    Seleccionar 5 Ganadores
                                </button>
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>
                <hr />

                <?php
                if (!empty($winner)) {
                ?>
                    <div class="alert alert-success text-center">
                        <?php
                        if (@count($winner) == 1) {
                            echo "<h2>Ganador/a:</h2><br>";
                        } else {
                            echo "<h2>Ganadores/as:</h2><br>";
                        }
                        foreach ($winner as $winner_) {
                        ?>
                            <div class="inline-block mr-10 text-center">
                                <h5><?= $winner_['ganador'] ?>º</h5>
                                <?php foreach ($formData['data'] as $formItem) { ?>
                                    <b><?= $formItem['campo'] ?> </b><?= $winner_[$formItem['campo']] ?><br>
                                <?php } ?>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="mt-10" style="text-align: right">
                            <form method="post">
                                <button name="reset" type="submit" class="btn btn-warning ml-10">
                                    REINICIAR
                                </button>
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>

                <!-- <div class="clearfix"></div>
                <hr />
                <fieldset class="form-group position-relative has-icon-left mb-20">
                    <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
                    <div class="form-control-position">
                        <i class="bx bx-search"></i>
                    </div>
                </fieldset>
                <div class="clearfix"></div>
                <hr />

                <br>
                <input class="form-control" id="myInput" type="text" placeholder="Buscar.."> -->
                <br>
                <hr />
                <table class="table  table-bordered  ">
                    <thead>
                        <?php
                        foreach ($formData['data'] as $formDataItem) { ?>
                            <th>
                                <?= $formDataItem['campo'] ?>
                            </th>
                        <?php } ?>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($landingSubsList as $landingSubsItem) { ?>
                            <tr>
                                <?php foreach ($landingSubsItem as $key => $subsAtribute) {
                                    foreach ($formData['data'] as $formDataItem) {
                                        if ($key == $formDataItem['campo']) { ?>
                                            <td><?= $subsAtribute ?> </td>
                                <?php }
                                    }
                                } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
        </section>
    </div>
</div>