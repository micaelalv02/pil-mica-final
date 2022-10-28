<?php
$envios = new Clases\Envios();
$idiomas = new Clases\Idiomas();
$imagenes = new Clases\Imagenes();
$funciones = new Clases\PublicFunction();
$idiomaGet = isset($_GET['idioma']) ? $funciones->antihack_mysqli($_GET['idioma']) : '';
$idiomasData = $idiomas->list(["cod != '$idiomaGet'"], "", "");
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);
    $envios->set("cod", $cod);
    $envios->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $envios->set("descripcion", isset($_POST["descripcion"]) ? $funciones->antihack_mysqli($_POST["descripcion"]) : '');
    $envios->set("opciones", isset($_POST["opciones"]) ? $funciones->antihack_mysqli($_POST["opciones"]) : '');
    $envios->set("peso", isset($_POST["peso"]) ? $funciones->antihack_mysqli($_POST["peso"]) : '');
    $envios->set("precio", isset($_POST["precio"]) ? $funciones->antihack_mysqli($_POST["precio"]) : '');
    $envios->set("estado", isset($_POST["estado"]) ? $funciones->antihack_mysqli($_POST["estado"]) : '');
    $envios->set("limite", isset($_POST["limite"]) ? $funciones->antihack_mysqli($_POST["limite"]) : '');
    $envios->set("tipo_usuario", isset($_POST["tipo_usuario"]) ? $funciones->antihack_mysqli($_POST["tipo_usuario"]) : "'0'");
    $envios->set("idioma", $idiomaGet);
    $envios->add();

    if (!empty($_FILES['files']['name'][0])){
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($_POST["titulo"])), [$idiomaGet]);
    }
    if (isset($_POST["idiomasInput"])) {
        foreach ($_POST["idiomasInput"] as $idioma_) {
            $envios->set("idioma", $idioma_);
            $envios->add();
            if (!empty($_FILES['files']['name'][0])){
                $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link(strip_tags($_POST["titulo"])), $idioma_);
            }
        }
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=envios&accion=ver&idioma=$idiomaGet");
}
?>
<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Envios
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
            <form method="post" class="row" enctype="multipart/form-data">
                    <label class="col-md-4">Título:<br />
                        <input type="text" name="titulo" required>
                    </label>
                    <label class="col-md-4">Estado:<br />
                        <select name="estado" required>
                            <option value="1">Activado</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </label>
                    <label class="col-md-4">Tipo de Usuarios:<br />
                        <select name="tipo_usuario" required>
                            <option value="0" selected>Ambos</option>
                            <option value="1">Minorista</option>
                            <option value="2">Mayorista</option>
                        </select>
                    </label>
                    <label class="col-md-3">Peso:<br />
                        <input value="0" min="0" name="peso" type="text" required />
                    </label>
                    <label class="col-md-3">Precio:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="float" class="form-control" min="0" name="precio" required>
                        </div>
                    </label>
                    <label class="col-md-3">Limite:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="float" class="form-control" name="limite">
                        </div>
                    </label>
                    <label class="col-md-3">Pedir datos adicionales:<br />
                        <select name="opciones" required>
                            <option value="0" selected>Desactivado</option>
                            <option value="2">Hora y Fecha especifica</option>
                            <option value="3">Hora y Rango Fecha</option>
                        </select>
                    </label>
                    <label class="col-md-12">Descripción:<br />
                        <input type="text" name="descripcion">
                    </label>
                    <?php if (count($idiomasData) >= 1) { ?>
                        <div class="col-md-12">
                            <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar metodo de pago en otros idiomas</div>
                            <div class="row" id="idiomasCheckBox">
                                <?php foreach ($idiomasData as $idiomaItem) { ?>
                                    <div class="ml-10">
                                        <label for="idioma<?= $idiomaItem['data']['cod'] ?>">
                                            <input type="checkbox" name="idiomasInput[]" value="<?= $idiomaItem['data']['cod'] ?>" id="idioma<?= $idiomaItem['data']['cod'] ?>"> <?= $idiomaItem['data']['titulo'] ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-12">
                        <h6 class="invoice-to">Imagen</h6>
                        <input name="files[]" class="form-control form-control-md" style="border:none" type="file" id="file" accept="image/*" />
                    </div>
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Crear Envio" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#idiomasCheckBox').hide();

    $("#pes").inputSpinner()
</script>