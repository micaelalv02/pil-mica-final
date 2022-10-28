<?php
$config = new Clases\Config();
$meli = new Clases\MercadoLibre();
$productos = new Clases\Productos();
$meliConfig = $config->viewExportadorMeli();

$productosData = $productos->list('',$_SESSION['lang']);

?>
<div id="url-data-meli" data-url-meli="<?= URL_ADMIN ?>"></div>
<!-- MODALES -->
<div class="modal fade" id="modalAdd" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Vinculo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalData"></div>
        </div>
    </div>
</div>

