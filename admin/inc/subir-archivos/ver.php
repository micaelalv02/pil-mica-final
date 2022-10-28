<link href="subir-archivos/css/style.css" rel="stylesheet" />
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <a class="btn btn-primary" href="<?= URL_ADMIN ?>/index.php?op=subir-archivos&accion=ver-img&idioma=<?= $_GET["idioma"] ?>"><i class="fa fa-cog"></i> Administrar imágenes</a>
                    <a class="btn btn-warning" onclick="$('#modal').modal('show');" href="<?= URL_ADMIN ?>/index.php?op=subir-archivos&accion=match-to-product"> Mover las imagenes al producto</a>
                    <?php if ($_SESSION["admin"]["crud"]["crear"]) { ?>

                        <form id="upload" method="post" action="subir-archivos/upload.php" enctype="multipart/form-data">
                            <div id="drop">
                                Arrastrar imágenes aquí
                                <br />
                                <a>Buscar</a>
                                <input type="file" name="upl" multiple />
                            </div>

                            <ul>
                            </ul>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade hidden" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4> Estamos procesando las imágenes, este proceso puede demorar.</h4>
            </div>
        </div>
    </div>
</div>

<script src="subir-archivos/js/script.js"></script>