<?php
$cssConverter = new Clases\CssConverter();

$page_customization = json_decode(file_get_contents(dirname(__DIR__, 4)  . '/json/page-customization.json'), true);

//CSS TO JSON
$cssraw = file_get_contents(dirname(__DIR__, 4) . '/assets/css/custom-rocha.css');
$cssobj = $cssConverter->css2obj($cssraw);
$cssobj = (array) $cssobj;
//JSON TO CSS

$cssArray = [];
$generateCss = false;

#EDITO LOS ESTILOS DE LA CLASE
if (isset($_POST["editarEstilos"])) {
    $generateCss = true;
    unset($_POST["editarEstilos"]);
    foreach ($_POST["class"] as $key => $postItem) {
        $keyTransform = str_replace([".", " "], "_", $key);
        if (!isset($_POST[$key])) {
            $_POST[$key] = [];
        }
        $cssArray[$key] = $_POST[$keyTransform];
    }
    unset($_POST["class"]);
}
#AGREGO UN ESTILO A UNA CLASE
if (isset($_POST["agregarEstilo"])) {
    $generateCss = true;
    unset($_POST["agregarEstilo"]);
    $clases = isset($_POST["clases"]) ? $funcion->antihack_mysqli($_POST["clases"]) : '';
    $estilo = isset($_POST["estilo"]) ? $funcion->antihack_mysqli($_POST["estilo"]) : '';
    $valor = isset($_POST["valor"]) ? $funcion->antihack_mysqli($_POST["valor"]) : '';
    $cssobj[$clases][$estilo] = $valor;
    $cssArray = $cssobj;
}
#GENERO EL .CSS
if ($generateCss) {
    $css = $cssConverter->obj2css($cssArray);
    file_put_contents(dirname(__DIR__, 4) . '/assets/css/custom-rocha.css', $css);
    $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificar&tab=personalizacion-tab');
}
#CAMBIAR LOGO Y FAVICON
if (isset($_POST["cambiarLogo"])) {
    unset($_POST["cambiarLogo"]);
    if (isset($_FILES["logo"]) && !empty($_FILES["logo"]["tmp_name"])) {
        @unlink(dirname(__DIR__, 4)  . $page_customization["logo"]);
        $extencion = end(explode(".", $_FILES["logo"]["name"]));
        move_uploaded_file($_FILES["logo"]["tmp_name"], dirname(__DIR__, 4)  . "/assets/images/logo." . $extencion);
        $page_customization["logo"] = "/assets/images/logo." . $extencion;
        unset($_POST["logo"]);
    }
    if (isset($_FILES["favicon"]) && !empty($_FILES['favicon']["tmp_name"])) {
        @unlink(dirname(__DIR__, 4)  . $page_customization["favicon"]);
        $extencion = end(explode(".", $_FILES["favicon"]["name"]));
        move_uploaded_file($_FILES["favicon"]["tmp_name"], dirname(__DIR__, 4)  . "/assets/images/favicon." . $extencion);
        $page_customization["favicon"] = "/assets/images/favicon." . $extencion;
        unset($_POST["favicon"]);
    }
    file_put_contents(dirname(__DIR__, 4) . '/json/page-customization.json', json_encode($page_customization));
    $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificar&tab=personalizacion-tab');
}

?>
<div class="content-body">
    <section class="users-list-wrapper">
        <div class="users-list-table">
            <div class="card">
                <div class="card-content">
                    <div class="card-body pt-10">
                        <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificar&tab=personalizacion-tab" enctype="multipart/form-data">
                            <div class="row ">
                                <div class="col-md-6 pull-left">
                                    <h2 class="text-center fs-16">LOGO</h2><br>
                                    <img class="text-center" src="<?= LOGO ?>" width="100%" height="100px" style="object-fit:contain">
                                    <br>
                                    <input type="file" class="my-3" name="logo" id="logo">
                                </div>
                                <div class="col-md-6 pull-right">
                                    <h2 class="text-center fs-16">FAVICON</h2><br>
                                    <img class="text-center" src="<?= FAVICON ?>" width="100%" height="100px" style="object-fit:contain">
                                    <br>
                                    <input type="file" class="my-3" name="favicon" id="favicon">
                                </div>
                                <div class="col-md-12 mt-20">
                                    <button class="btn btn-primary btn-block mb-60" type="submit" name="cambiarLogo">ACTUALIZAR IMÁGENES</button>
                                </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <h4 class="mt-20 d-block text-uppercase text-center">Mejoras de CSS
                                    <hr />
                                </h4>
                                <div class="clearfix"></div>
                            </div>
                            <form method="post" class="col-md-8 " action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificar&tab=personalizacion-tab">

                                <div class="row">
                                    <?php foreach ($cssobj as $key => $customItem) {
                                        if (!$customItem) continue;
                                    ?>
                                        <div class="col-md-12 text-center">
                                            <input type="hidden" name="class[<?= $key ?>]">
                                            <label class="fs-14"><?= $key ?></label>
                                            <hr class="mt-6 mb-6" />
                                            <div class="row">
                                                <?php
                                                foreach ($customItem as $key_ => $value_) {
                                                    $rand = hash("sha256", rand(0, 9999));
                                                ?>
                                                    <div class="col-3 " id="<?= $rand ?>">
                                                        <label class="  d-block  font-weight-normal fs-12" for="<?= $rand . "_" ?>"><?= $key_ ?></label>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control fs-13" id="<?= $rand . "_" ?>" value="<?= isset($customItem[$key_]) ? $customItem[$key_] : '' ?>" name="<?= $key ?>[<?= $key_ ?>]" aria-describedby="basic-addon2">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-sm px-1 btn-danger " onclick="deleteInput('<?= $rand ?>')" type="button"><i class="fa fa-times fs-12" aria-hidden="true"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <button class="btn btn-block btn-success mt-10" name="editarEstilos">Editar</button>
                            </form>
                            <form class="col-md-4" method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificar&tab=personalizacion-tab">
                                <div class="jumbotron pt-40 pb-40">
                                    <div class=" row ">
                                    <div class="col-12 mt-10 text-center">
                                        <label class="fs-16 white" for="clases"> Selecciona un selector para agregar un estilo</label>
                                        <hr/>
                                        <select name="clases">
                                            <?php foreach ($cssobj as $key => $selector) { ?>
                                                <option value="<?= $key ?>"><?= $key ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                     <div class="col-6 mt-10">
                                        <select name="estilo" onchange="$('#valorCSS').attr('type',$('option:selected', this).attr('typeInput'))">
                                            <option typeInput="text" value="font-size">Tamaño de Fuente</option>
                                            <option typeInput="color" value="color">Color de Fuente</option>
                                            <option typeInput="text" value="font-weight">Grosor de Fuente</option>
                                            <option typeInput="color" value="background">Color de Fondo</option>
                                            <option typeInput="text" value="background-image">Fondo de imagen</option>
                                            <option typeInput="text" value="margin">Margen (alto ancho)</option>
                                            <option typeInput="text" value="padding">Padding (alto ancho)</option>
                                            <option typeInput="text" value="height">Alto en px/%</option>
                                            <option typeInput="text" value="width">Ancho en px/%</option>
                                            <option typeInput="text" value="max-height">Alto Máximo en px/%</option>
                                            <option typeInput="text" value="min-height">Alto Mínimo en px/%</option>
                                            <option typeInput="text" value="max-width">Ancho Máximo en px/%</option>
                                            <option typeInput="text" value="min-width">Ancho Mínimo en px/%</option>
                                            <option typeInput="text" value="box-shadow">Sombra</option>
                                            <option typeInput="text" value="border-radius">Redondear</option>
                                        </select>
                                    </div>
                                    <div class="col-6 mt-10">
                                        <input type="text" name="valor" id="valorCSS" placeholder="Valor" value="">
                                    </div>
                                     <div class="col-12 mt-10 text-center">
                                        <button class="btn btn-block btn-success mt-10" name="agregarEstilo">Agregar</button>
                                    </div>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function deleteInput(id) {
        $('#' + id).remove();
    }

    function getColor() {
        var x = document.getElementById("color").value;
        document.getElementById("demo").innerHTML = x;
    }
</script>