<?php
$funciones = new Clases\PublicFunction();
$contenido = new Clases\Contenidos();
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : "";
$form = json_decode(file_get_contents(dirname(__DIR__) . '/landing/campos-form.json', false, stream_context_create($arrContextOptions)), true);
if (empty($form)) $form = [];

if (isset($_POST["agregarCampos"])) {
    unset($_POST["agregarCampos"]);
    foreach ($form as $key => $formItem) {
        if ($formItem["cod"] == $cod) unset($form[$key]);
    }
    foreach ($_POST["data"] as $key => $campo) {
        $_POST["data"][$key]["requerido"] = (isset($campo["requerido"])) ? true : false;
    }
    if (!empty($form)) {
        array_push($form, $_POST);
    } else {
        $form = [$_POST];
    }
    unset($_POST);
    file_put_contents(dirname(__DIR__) . '/landing/campos-form.json', json_encode($form));
    $funciones->headerMove(URL_ADMIN . "/index.php?op=landing&accion=ver-form");
}
$countKey = 0;
?>
<?php foreach ($form as $key => $campo) {
    if ($campo["cod"] != $cod) continue; ?>
    <input type="hidden" id="contador" value="<?= count($form[$key]["data"]) + 1 ?>">
    <div class="mt-20 ">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-uppercase text-center">
                    Modificar Formulario
                </h4>
                <hr style="border-style: dashed;">
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <label for="titulo" class="col-md-4">
                                <input type="text" name="titulo" value="<?= $form[$key]["titulo"] ?>" required>
                            </label>
                            <label for="titulo" class="col-md-4">
                                <input type="text" placeholder="Texto boton submit" name="submit" value="<?= $form[$key]["submit"] ?>" required>
                            </label>
                            <select name="landing" class="col-md-4">
                                <option value="">Seleccione una landing</option>
                                <?php
                                $landingList = $contenido->list(["filter" => ["contenidos.area = 'landing-area'"]], $idiomaGet);
                                if (is_array($landingList)) {
                                    foreach ($landingList as $landingItem) {
                                ?>
                                        <option value="<?= $landingItem['data']["cod"] ?>" <?= ($landingItem['data']["cod"] == $campo["landing"]) ? "selected" : "" ?>><?= $landingItem['data']["titulo"] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="cod" value="<?= $form[$key]["cod"] ?>">
                        <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                        <div class="jumbotron pt-20 pb-20 mt-10">
                            <div class="row">
                                <div class="col-12 mt-10 text-center">
                                    <label class="fs-16 white" for="clases"> Modificar Campos al formulario</label>
                                    <hr />
                                </div>
                            </div>
                            <?php
                            foreach ($campo["data"] as $campoItem) {
                            ?>
                                <div class="row mb-10" id="<?= $countKey ?>">
                                    <div class="col-md-2 mt-10">
                                        <label class="fs-14 white" for="campo">Campo</label>
                                        <select class="form-control" name="data[<?= $countKey ?>][campo]" required>
                                            <option value="nombre" <?= ($campoItem["campo"] == "nombre") ?  "selected" : '' ?>>Nombre</option>
                                            <option value="apellido" <?= ($campoItem["campo"] == "apellido") ?  "selected" : '' ?>>Apellido</option>
                                            <option value="email" <?= ($campoItem["campo"] == "email") ?  "selected" : '' ?>>Email</option>
                                            <option value="celular" <?= ($campoItem["campo"] == "dni") ?  "selected" : '' ?>>Celular</option>
                                            <option value="dni" <?= ($campoItem["campo"] == "celular") ?  "selected" : '' ?>>Dni</option>
                                            <option value="telefono" <?= ($campoItem["campo"] == "telefono") ?  "selected" : '' ?>>Teléfono</option>
                                            <option value="cuit" <?= ($campoItem["campo"] == "cuit") ?  "selected" : '' ?>>Cuit</option>
                                            <option value="provincia" <?= ($campoItem["campo"] == "provincia") ?  "selected" : '' ?>>Provincia</option>
                                            <option value="localidad" <?= ($campoItem["campo"] == "localidad") ?  "selected" : '' ?>>Localidad</option>
                                            <option value="direccion" <?= ($campoItem["campo"] == "direccion") ?  "selected" : '' ?>>Direccion</option>
                                            <option value="pais" <?= ($campoItem["campo"] == "pais") ?  "selected" : '' ?>>País</option>
                                            <option value="empresa" <?= ($campoItem["campo"] == "empresa") ?  "selected" : '' ?>>Empresa</option>
                                            <option value="cargo" <?= ($campoItem["campo"] == "cargo") ?  "selected" : '' ?>>Cargo</option>
                                            <option value="razon_social" <?= ($campoItem["campo"] == "razon_social") ?  "selected" : '' ?>>Razon Social</option>
                                            <option value="mensaje" <?= ($campoItem["campo"] == "mensaje") ?  "selected" : '' ?>>Mensaje</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-10">
                                        <label class="fs-14 white" for="orden">Orden</label>
                                        <input class="form-control" value="<?= $campoItem["orden"] ?>" type="text" name="data[<?= $countKey ?>][orden]" placeholder="0" required>
                                    </div>
                                    <div class="col-md-2 mt-10">
                                        <label class="fs-14 white" for="columnas"> Columnas</label>
                                        <select class="form-control" name="data[<?= $countKey ?>][columnas]" required>
                                            <option value="6" <?= ($campoItem["columnas"] == "6") ?  "selected" : '' ?>>Columna de 6</option>
                                            <option value="12" <?= ($campoItem["columnas"] == "12") ?  "selected" : '' ?>>Columna de 12</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-10">
                                        <label class="fs-14 white" for="type"> Tipo de campo</label>
                                        <select class="form-control" name="data[<?= $countKey ?>][type]" required>
                                            <option value="text" <?= ($campoItem["type"] == "text") ?  "selected" : '' ?>>Texto</option>
                                            <option value="number" <?= ($campoItem["type"] == "number") ?  "selected" : '' ?>>Númerico</option>
                                            <option value="tel" <?= ($campoItem["type"] == "tel") ?  "selected" : '' ?>>Telefono/Celular</option>
                                            <option value="email" <?= ($campoItem["type"] == "email") ?  "selected" : '' ?>>Email</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-30 row">
                                        <label class="fs-14 white  mt-10 col-md-10" for="requerido<?= $countKey ?>">
                                            Obligatorio <input type="checkbox" <?= ($campoItem["requerido"] == true) ? "checked" : '' ?> name="data[<?= $countKey ?>][requerido]" id="requerido<?= $countKey ?>">
                                        </label>
                                        <a class="btn btn-danger col-md-2" style="max-height:37px" type="text" onclick="deleteRow('<?= $countKey ?>')"><i class="fa fa-trash" style="color:white" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            <?php
                                $countKey++;
                            } ?>
                            <div id="campos"></div>
                            <div class="row">
                                <div class="col-6 text-center">
                                    <a class="btn btn-block btn-success mt-10" style="color:white" onclick="agregarCampo()">Agregar Campo</a>
                                </div>
                                <div class="col-6 text-center">
                                    <button class="btn btn-block btn-info mt-10" name="agregarCampos">Guardar Formulario</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    var contador = document.getElementById('contador').value;

    function deleteRow(id) {
        $('#' + id).html("");
    }

    function agregarCampo() {
        $("#campos").append(`
      <div class="row mb-10" id="${contador}">
          <div class="col-md-2 mt-10">
              <label class="fs-14 white" for="campo">Campo</label>
              <select class="form-control" name="data[${contador}][campo]" required>
              <option value="nombre">Nombre</option>
                <option value="apellido">Apellido</option>
                <option value="email">Email</option>
                <option value="telefono">Teléfono</option>
                <option value="celular">Celular</option>
                <option value="dni">Dni</option>
                <option value="cuit">Cuit</option>
                <option value="provincia">Provincia</option>
                <option value="localidad">Localidad</option>
                <option value="direccion">Direccion</option>
                <option value="pais">País</option>
                <option value="empresa">Empresa</option>
                <option value="cargo">Cargo</option>
                <option value="razon_social">Razon Social</option>
                <option value="mensaje">Mensaje</option>F
              </select>
          </div>
          <div class="col-md-2 mt-10">
              <label class="fs-14 white" for="orden">Orden</label>
              <input class="form-control" type="text" name="data[${contador}][orden]" placeholder="0" required>
          </div>
          <div class="col-md-2 mt-10">
              <label class="fs-14 white" for="columnas"> Columnas</label>
              <select class="form-control" name="data[${contador}][columnas]" required>
                <option value="6">Columna de 6</option>
                <option value="12">Columna de 12</option>
              </select>
          </div>
          <div class="col-md-2 mt-10">
              <label class="fs-14 white" for="type"> Tipo de campo</label>
              <select class="form-control" name="data[${contador}][type]" required>
                <option value="text">Texto</option>
                <option value="number">Númerico</option>
                <option value="tel">Telefono/Celular</option>
                <option value="email">Email</option>
              </select>
          </div>
          <div class="col-md-4 mt-30 row">
          <label class="fs-14 white  mt-10 col-md-10" for="requerido${contador}">
          Obligatorio <input type="checkbox" name="data[${contador}][requerido]" id="requerido${contador}">
          </label>
          <a class="btn btn-danger col-md-2" style="max-height:37px" type="text" onclick="deleteRow('${contador}')"><i class="fa fa-trash" style="color:white" aria-hidden="true"></i></a>
          </div>
      </div>
      `);
        contador++;
        $('#contador').val(contador);
    }
</script>