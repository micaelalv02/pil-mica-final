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
$form = json_decode(file_get_contents(dirname(__DIR__) . '/landing/campos-form.json', false, stream_context_create($arrContextOptions)), true);
if (empty($form)) $form = [];
if (isset($_POST["agregarCampos"])) {
    unset($_POST["agregarCampos"]);
    foreach ($_POST["data"] as $key => $campo) {
        $_POST["data"][$key]["requerido"] = (isset($campo["requerido"])) ? true : false;
    }
    array_push($form, $_POST);
    unset($_POST);
    file_put_contents(dirname(__DIR__) . '/landing/campos-form.json', json_encode($form));
    $funciones->headerMove(URL_ADMIN . "/index.php?op=landing&accion=ver-form");
}
?>
<input type="hidden" id="contador" value="0">
<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Agregar Formulario
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <label for="titulo" class="col-md-4">
                            <input type="text" placeholder="Nombre del formulario" name="titulo" required>
                        </label>
                        <label for="titulo" class="col-md-4">
                            <input type="text" placeholder="Texto boton submit" name="submit" required>
                        </label>
                        <label for="landing" class="col-md-4">
                            <select name="landing">
                                <option value="">Seleccione una landing</option>
                                <?php
                                $landingList = $contenido->list(["filter" => ["contenidos.area = 'landing-area'"]], $idiomaGet);
                                if (is_array($landingList)) {
                                    foreach ($landingList as $landingItem) {
                                ?>
                                        <option value="<?= $landingItem['data']["cod"] ?>"><?= $landingItem['data']["titulo"] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" name="cod" value="<?= substr(md5(uniqid(rand())), 0, 10) ?>">
                    <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                    <div class="jumbotron pt-20 pb-20 mt-10">
                        <div class="row">
                            <div class="col-12 mt-10 text-center">
                                <label class="fs-16 white" for="clases"> Agregar Campos al formulario</label>
                                <hr />
                            </div>
                        </div>
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

<script>
    var contador = document.getElementById('contador').value;
    $(document).ready(agregarCampo());

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
                <option value="mensaje">Mensaje</option>
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
      `)
        contador++;
        $('#contador').val(contador);
    }
</script>