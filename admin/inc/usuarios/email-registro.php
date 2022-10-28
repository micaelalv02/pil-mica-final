<?php
$idiomas = new Clases\Idiomas();
$f  = new Clases\PublicFunction();

$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

$arrContextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];
$contentEmail = json_decode(file_get_contents(dirname(__DIR__, 3)  . '/lang/emailSendRegister/email.json', false, stream_context_create($arrContextOptions)), true);
if (isset($_POST["modificar"])) {
    $langPost  = $_POST["idioma"];
    unset($_POST["modificar"]);
    unset($_POST["idioma"]);
    $contentEmail[$langPost] = $_POST;
    file_put_contents(dirname(__DIR__, 3) . '/lang/emailSendRegister/email.json', json_encode($contentEmail));
    $f->headerMove(CANONICAL);
} ?>
<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <h4 class="mt-20 pull-left">Modificar Email de Registro</h4>
                <div class="clearfix"></div>
                <hr />
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <?php
                    foreach ($idiomas->list("", "id ASC", "") as $key => $idioma_) {
                        $url =  URL_ADMIN . "/index.php?op=usuarios&accion=email-registro&idioma=" . $idioma_["data"]["cod"];
                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?= CANONICAL == $url ? "active" : '' ?>" id="<?= $idioma_["data"]["cod"] ?>-tab" data-toggle="tab" href="#<?= $idioma_["data"]["cod"] ?>" role="tab" aria-controls="<?= $idioma_["data"]["cod"] ?>" aria-selected="true"><?= $idioma_["data"]["titulo"] ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <?php foreach ($contentEmail as $key => $content) {
                        $url =  URL_ADMIN . "/index.php?op=usuarios&accion=email-registro&idioma=" . $key;
                    ?>
                        <form method="post" class="tab-pane fade <?= CANONICAL == $url ? "show active" : '' ?>" aria-selected="<?= CANONICAL == $url ? "true" : 'false' ?>" aria-labelledby="<?= $key ?>-tab" id="<?= $key ?>" role="tabpanel" aria-labelledby="<?= $key ?>-tab">
                            <label for="asunto">Asunto</label>
                            <input type="hidden" name="idioma" value="<?= $key ?>">
                            <input type="text" name="asunto" value="<?= $content["asunto"] ?>">
                            <label for="contenido">Contenido</label>
                            <textarea name="contenido" class="ckeditorTextarea" required><?= $content["contenido"] ?></textarea>
                            <label for="cc">Enviar con Copia (si este campo es vacio se interpreta que se envia sin copia)</label>
                            <input type="text" name="cc" value="<?= $content["cc"] ?>">
                            <button type="submit" name="modificar" class="btn-block btn btn-primary mt-20 mb-100">Modificar</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </section>
    </div>
</div>