<?php
$config = new Clases\Config();
$meli = new Clases\MercadoLibre();
$productos = new Clases\Productos();
$meliConfig = $config->viewExportadorMeli();

$productosData = $productos->list('', $_SESSION['lang']);

?>
<div class="row">
    <div class="col-md-12 mb-20 mt-20">
        <div class="text-center">
            <h1>Vincular productos a MercadoLibre
                <small style="font-size: 30%">v0.2.4</small>
            </h1>
        </div>
    </div>
    <div class="col col-xs-12 col-sm-12 col-md-5">
        <div class="mt-20">
            <div class="col-lg-12 col-md-12">
                <h4>
                    Productos Vinculados
                    <button class="btn btn-success pull-right" data-toggle="modal" data-target="#modalAdd" onclick="meliModal()">
                        AGREGAR VINCULO
                    </button>
                </h4>
                <hr />
                <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
                <hr />
                <table class="table  table-bordered  ">
                    <thead>
                        <th style="padding: 5px 5px 5px 5px !important">
                            Codigo Producto
                        </th>
                        <th style="padding: 5px 5px 5px 5px !important">
                            Codigo Mercadolibre
                        </th>
                        <th>
                        </th>
                    </thead>
                    <tbody id="listMeli">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="col col-xs-12 col-sm-12 col-md-7 mt-5">
        <form class="form-inline text-uppercase mt-10 mb-10" id="formMeli" method="POST" style="margin: auto;place-content: center" onsubmit="sync()">
            <input type="hidden" value="<?= $meliConfig["data"]["clasica"] ?>" id="cfg-classic" />
            <input type="hidden" value="<?= $meliConfig["data"]["premium"] ?>" id="cfg-premium" />
            <input type="hidden" value="<?= $meliConfig["data"]["calcular_envio"] ?>" id="cfg-premium" />

            <div>
                Tipo de Publicación
                <select id="type" class=" ml-5 ">
                    <option value="gold_special">Clásica</option>
                    <option value="gold_pro">Premium</option>
                </select>
            </div>
            <div class="ml-10">
                <button class="btn btn-success">SINCRONIZAR</button>
            </div>
        </form>
        <div class="mr-10" id="info">

        </div>

        <div class="row mt-10 mr-10" id="results">
            <table class='table text-center'>
                <thead class='thead-dark' style="border: 1px white;">
                    <tr>
                        <th class='text-center'>CÓDIGO</th>
                        <th class='text-center'>PRECIO</th>
                        <th class='text-center'>ESTADO</th>
                        <th class='text-center' style="width:200px">MENSAJE</th>
                    </tr>
                </thead>
                <tbody id='resultsRow'>
                </tbody>
            </table>
        </div>
    </div>
</div>



<?php
include dirname(__DIR__, 1) . "/mercadolibre/modal.inc.php";

if (isset($_GET["borrar"])) {
    $cod = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $codProduct = isset($_GET["codProduct"]) ? $funciones->antihack_mysqli($_GET["codProduct"]) : '';
    $meli->set("code", $cod);
    $meli->remove();
    $check = $meli->checkProduct($codProduct);
    if (!$check) {
        $productos->set("cod", $codProduct);
        $productos->editSingle('meli', 0);
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=mercadolibre&accion=importar");
}
?>

<script src="<?= URL_ADMIN ?>/js/meli.js"></script>
<script>
    $(document).ready(function() {
        refreshlistMeli();
    });
</script>