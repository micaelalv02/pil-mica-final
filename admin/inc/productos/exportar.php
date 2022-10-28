<?php
$excel = new Clases\Excel();
$idiomas = new Clases\Idiomas();
$f = new Clases\PublicFunction();
$productos = new Clases\Productos();

$idiomasList = $idiomas->list('', '', '');
$attrList = $productos->getAttrWithTitle();
if (isset($_POST["export"])) {
    $atributos = isset($_POST['attr_export']) ? $_POST['attr_export'] : '';
    $idioma = isset($_POST['idioma_export']) ? $_POST['idioma_export'] : $_SESSION['lang'];
    $path = $excel->exportProduct($atributos, $idioma);
    $link = URL . "/export/productos/$idioma/" . $path;
    $f->headerMove($link);
}

if (isset($_POST['import'])) {
    if ($_POST['import'] == 'check') {
        $urlFile = $excel->saveFile();
        $sheets = $excel->getSheets($urlFile);
        if (count($sheets) > 1) {
?>
            <div class="container-fluid">

                <div class="card mt-20">
                    <h3>
                        Seleccionar Hoja a Importar
                    </h3>
                    <hr>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="urlFile" value="<?= $urlFile ?>">
                        <select name="sheet">
                            <?php foreach ($sheets as $key => $sheet) { ?>
                                <option value="<?= $key ?>"><?= $sheet ?></option>
                            <?php } ?>
                        </select>
                        <button class="btn btn-primary pull-right mt-20" name="import" value="import" type="submit">Importar Hoja</button>
                    </form>
                </div>

            <?php }
    }
    if ($_POST['import'] == 'import' || ($_POST['import'] == 'check' && count($sheets) == 1)) {
        $sheet = isset($_POST['sheet']) ? $f->antihack_mysqli($_POST['sheet']) : '0';
        $urlFile = isset($_POST['urlFile']) ? $f->antihack_mysqli($_POST['urlFile']) : $urlFile;
        $arrayImport = $excel->importProduct($urlFile, $sheet);
            ?>
            <div class="container-fluid">

                <div class="card mt-20">

                    <h3>
                        Vincular columnas del Excel a nuestro sistema
                    </h3>
                    <hr>

                    <div class="table-responsive">
                        <form id="form_attr" method="POST">
                            <table class="table table-sm">
                                <thead>
                                    <th>Titulo</th>
                                    <th>Dato</th>
                                    <th>Vicular</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($arrayImport as $attr => $value) { ?>
                                        <tr>
                                            <td><?= $attr ?></td>
                                            <td><?= $value ?></td>
                                            <td><select id="<?= $attr ?>" name="<?= $attr ?>" onchange="checkType('<?= $attr ?>')">
                                                    <option value="1">No Vincular</option>
                                                    <?php foreach ($productos->getAttrWithTitle() as $attr_ => $title) { ?>
                                                        <option value="<?= $attr_ ?>"><?= $title ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button name="selectImport" class="btn btn-primary pull-right">Importar</button>
                            <h4 class="pull-right mr-10"> <span id="textIdioma"></span>
                                <button type="button" class="btn btn-outline-info block hidden" id="btnIdioma" data-toggle="modal" data-target="#modal-idiomas">
                                    Cambiar Idioma
                                </button>
                            </h4>
                            <!--BorderLess Modal Modal -->
                            <div class="modal fade text-left modal-borderless" id="modal-idiomas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" data-backdrop="static" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Seleccionar Idioma de los productos a importar</h3>
                                        </div>
                                        <div class="modal-body">
                                            <select name="modal-idioma-select" id="modal-idioma-select">
                                                <?php
                                                if (count($idiomasList) == 1) { ?>
                                                    <option selected value="<?= $idiomasList[0]['data']['cod'] ?>">
                                                        <?= $idiomasList[0]['data']['titulo'] ?>
                                                    </option>
                                                <?php } else { ?>
                                                    <option value="1">Seleccionar Idioma</option>
                                                    <?php foreach ($idiomasList as $idioma) { ?>
                                                        <option value="<?= $idioma['data']['cod'] ?>">
                                                            <?= $idioma['data']['titulo'] ?>
                                                        </option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" onclick="btnShow()">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Seleccionar</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function checkType(attr) {
                    var val = $('#' + attr).val();
                    if (val == 'cod_producto' || val == 'cod' || val == 'titulo') {
                        $('#search-' + attr).prop("disabled", false);
                    }
                }

                function btnShow() {
                    if ($('#modal-idioma-select').val() != 1) {
                        $('#textIdioma').html('Idioma Seleccionado: ' + $("#modal-idioma-select option:selected").text());
                        $('#btnIdioma').removeClass('hidden');
                    } else {
                        $('#textIdioma').html('');
                    }
                }


                $('#form_attr').submit(function(e) {
                    event.preventDefault();
                    form = $('#form_attr').serializeArray();
                    validCod = false;
                    validIdioma = false;
                    form.forEach(element => {
                        if (element['value'] == 'idioma') validIdioma = true;
                        if (element['value'] == 'cod_producto') validCod = true;
                    });
                    if (!validCod) {
                        errorMessage('Seleccionar vinculacion con codigo de producto para continuar');
                    }
                    if (!validIdioma) {
                        if ($('#modal-idioma-select').val() != 1) {
                            validIdioma = true;
                        } else {
                            $('#modal-idiomas').modal();
                        }
                    }
                    if (validIdioma && validCod) {
                        $.ajax({
                            url: "<?= URL_ADMIN ?>/api/excel/importExcel.php",
                            type: 'POST',
                            data: form,
                            beforeSend: function() {
                                $('#modal-waiting').modal();
                            },
                            success: function(data) {
                                data = JSON.parse(data);
                                $('#modal-waiting').modal('toggle');
                                $('#msg-finish').html(data['msg']);
                                $('#modal-finish').modal('toggle');
                            }
                        });
                    }
                });
            </script>
    <?php
    }
}
    ?>
    <?php
    if (empty($_POST)) {
    ?>
        <section class="invoice-edit-wrapper mt-40">
            <h4 class="mb-20">Exportar / Importar Productos</h4>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <h6>Seleccionar el idioma que desea exportar</h6>
                            <div class="form-group">
                                <select data-url="<?= URL_ADMIN ?>" class="select2-icons form-control" name="idioma_export" required>
                                    <?php
                                    if (count($idiomasList) == 1) { ?>
                                        <option selected value="<?= $idiomasList[0]['data']['cod'] ?>">
                                            <?= $idiomasList[0]['data']['titulo'] ?>
                                        </option>
                                    <?php } else { ?>
                                        <option data-icon="bx bx-shopping-bag" selected>--- Selecciona un Idioma ---</option>
                                        <?php foreach ($idiomasList as $idioma) { ?>
                                            <option value="<?= $idioma__['data']['cod'] ?>" <?= ($idioma__ == $_SESSION['lang']) ? 'selected' : '' ?> data-icon="bx bx-shopping-bag">
                                                <?= $idioma__['data']['titulo'] ?>
                                            </option>
                                    <?php }
                                    } ?>
                                    <?php foreach ($idiomasList as $idioma__) { ?>
                                    <?php } ?>

                                </select>
                            </div>
                            <p>Luego de seleccionar la tabla elija que atributos desea exportar al excel</p>
                        </div>
                        <div class="col-md-12">
                            <h6>Seleccione los atributos que desea exportar</h6>
                            <div class="form-group">
                                <select class="select2 form-control" multiple="multiple" name="attr_export[]" style="min-height: 200px;" required>
                                    <?php foreach ($attrList as $key => $attr) {
                                        if ($key != 'cod_producto') {   ?>
                                            <option value="<?= $key ?>" data-icon="bx bx-user"><?= $attr ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mt-10">
                            <button class="btn btn-primary pull-right" id="download" name="export" type="submit">Exportar
                                Listado</button>
                            <iframe id="downloader" src="" style="display:none;"></iframe>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">
                    <form method="POST" enctype="multipart/form-data">
                        <fieldset class="form-group">
                            <label for="basicInputFile">Cargue su archivo excel</label>

                            <div class="custom-file" style="margin-top: 5px;">
                                <input type="file" class="custom-file-input" name="excel" id="file_import">
                                <label class="custom-file-label" for="file_import"></label>
                            </div>

                            <button class="btn btn-primary pull-right mt-20" name="import" value="check" type="submit">Importar
                                Listado</button>

                            <?php foreach ($idiomasList as $idioma_) {
                                echo "<span><u>" . $idioma_['data']['titulo'] . "</u>:  " .  $idioma_['data']['cod'] . "</span><br>";
                            } ?>
                            </p>
                        </fieldset>
                    </form>
                </div>
            </div>
        </section>
    <?php } ?>

    <!--BorderLess Modal Modal -->
    <div class="modal fade text-left modal-borderless" id="modal-waiting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        <div class="spinner-border text-success mr-10" role="status">
                            <span class="sr-only">Loading... </span>
                        </div>
                        Procesando informacion
                    </h2>
                </div>
                <div class="modal-body fs-16">
                    Este proceso puede demorar varios minutos por favor <span class="bold fs-18 text-danger"><u>NO</u></span> cerrar la ventana hasta que el proceso finalice.
                </div>

            </div>
        </div>
    </div>

    <!--BorderLess Modal Modal -->
    <div class="modal fade text-left modal-borderless" id="modal-finish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        <i class="bx bx-check fs-20 text-success"></i>
                        Informacion Procesada
                    </h2>
                </div>
                <div class="modal-body fs-16" id="msg-finish">
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#modal-finish').on('click', function() {
            $('#modal-finish').on('hidden.bs.modal', function() {
                document.location.href =
                    '<?= URL_ADMIN ?>/index.php?op=productos&accion=ver&idioma=<?= $_SESSION['lang'] ?>';
            });
        });
    </script>