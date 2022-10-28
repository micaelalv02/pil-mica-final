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
$lang = json_decode(file_get_contents(dirname(__DIR__, 3)  . '/lang/' . $idiomaGet . '.json', false, stream_context_create($arrContextOptions)), true);

if (isset($_POST["modificar"])) {
    unset($_POST["modificar"]);
    file_put_contents(dirname(__DIR__, 3) . '/lang/' . $idiomaGet . '.json', json_encode($_POST));
    $f->headerMove(CANONICAL);
} ?>
<div class="content-wrapper">
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <h4 class="mt-20 pull-left">Modificar</h4>
                <div class="clearfix"></div>
                <hr />
                <form method="post">
                    <?= $idiomas->recursive($lang); ?>
                    <button type="submit" name="modificar" class="btn-block btn btn-primary mt-20 mb-100">ENVIAR FORMULARIO</button>
                </form>
            </div>
        </section>
    </div>
</div>