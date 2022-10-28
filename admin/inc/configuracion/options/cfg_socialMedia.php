<?php
$f = new Clases\PublicFunction();

if (isset($_POST["agregar-redes"])) {
    $config->set("facebook", isset($_POST["s-facebook"]) ? $funciones->antihack_mysqli($_POST["s-facebook"]) : '');
    $config->set("twitter", isset($_POST["s-twitter"]) ? $funciones->antihack_mysqli($_POST["s-twitter"]) : '');
    $config->set("instagram", isset($_POST["s-instagram"]) ? $funciones->antihack_mysqli($_POST["s-instagram"]) : '');
    $config->set("linkedin", isset($_POST["s-linkedin"]) ? $funciones->antihack_mysqli($_POST["s-linkedin"]) : '');
    $config->set("youtube", isset($_POST["s-youtube"]) ? $funciones->antihack_mysqli($_POST["s-youtube"]) : '');
    $config->set("googleplus", isset($_POST["s-google"]) ? $funciones->antihack_mysqli($_POST["s-google"]) : '');
    $error = $config->addSocial();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificar&tab=social-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
$arrContextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];
$contentRedes = json_decode(file_get_contents(dirname(__DIR__, 4) . '/json/redes.json', false, stream_context_create($arrContextOptions)), true);
if (isset($_POST["modificar"])) {
    unset($_POST["modificar"]);
    $array = array_combine($_POST['boton'], $_POST['link']);
    unset($array['']);
    file_put_contents(dirname(__DIR__, 4) . '/json/redes.json', json_encode($array));
    $f->headerMove(CANONICAL);
}
?>
<section id="nav-filled">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-fill" data-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">
                                Redes sociales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-fill" data-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">
                                Linktree
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content pt-1">
                        <div class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                            <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificar&tab=social-tab">
                                <div class="row">
                                    <label class="col-md-12">
                                        Facebook:<br />
                                        <input type="text" class="form-control" name="s-facebook" value="<?= $socialData['data']["facebook"] ? $socialData['data']["facebook"] : '' ?>" />
                                    </label>
                                    <label class="col-md-12">
                                        Twitter:<br />
                                        <input type="text" class="form-control" name="s-twitter" value="<?= $socialData['data']["twitter"] ? $socialData['data']["twitter"] : '' ?>" />
                                    </label>
                                    <label class="col-md-12">
                                        Instragram:<br />
                                        <input type="text" class="form-control" name="s-instagram" value="<?= $socialData['data']["instagram"] ? $socialData['data']["instagram"] : '' ?>" />
                                    </label>
                                    <label class="col-md-12">
                                        Linkedin:<br />
                                        <input type="text" class="form-control" name="s-linkedin" value="<?= $socialData['data']["linkedin"] ? $socialData['data']["linkedin"] : '' ?>" />
                                    </label>
                                    <label class="col-md-12">
                                        YouTube:<br />
                                        <input type="text" class="form-control" name="s-youtube" value="<?= $socialData['data']["youtube"] ? $socialData['data']["youtube"] : '' ?>" />
                                    </label>
                                    <label class="col-md-12">
                                        Google Plus:<br />
                                        <input type="text" class="form-control" name="s-google" value="<?= $socialData['data']["googleplus"] ? $socialData['data']["googleplus"] : '' ?>" />
                                    </label>
                                    <div class="col-md-12 mt-20">
                                        <button class="btn btn-primary btn-block" type="submit" name="agregar-redes">Guardar cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                            <div class="content-body">
                                <section class="users-list-wrapper">
                                    <div class="users-list-table">
                                        <h4 class="mt-20 pull-left fs-16">AGREGAR LINKS</h4>
                                        <div class="clearfix"></div>
                                        <hr />
                                        <div class="tab-content" id="myTabContent">
                                            <form method="post" class="tab-pane fade show active" aria-selected="true" aria-labelledby="email-tab" id="email" role="tabpanel" aria-labelledby="email-tab">
                                                <div class="row">
                                                    <label class="col-4">
                                                        Título:
                                                        <input type="hidden" name="<?= $key + 1 ?>" value="<?= $key + 1 ?>">
                                                        <input type="text" placeholder="Nombre" name="boton[]" value="">
                                                    </label>
                                                    <label class="col-6">
                                                        URL:
                                                        <input type="text" placeholder="URL" name="link[]" value="">
                                                    </label>
                                                    <label class="col-2">
                                                        <br />
                                                        <button type="submit" name="modificar" class="btn-block btn btn-primary">AGREGAR</button>
                                                    </label>
                                                    <h4 class="mt-20 col-12 fs-16">EDITAR LINKS
                                                        <hr />
                                                    </h4>

                                                    <?php foreach ($contentRedes as $key => $value) { ?>
                                                        <label class="col-4">
                                                            Título:
                                                            <input type="text" name="boton[]" value="<?= $key ?>">
                                                        </label>
                                                        <label class="col-8">
                                                            URL:
                                                            <input type="text" name="link[]" value="<?= $value ?>">
                                                        </label>
                                                        <br />
                                                    <?php } ?>
                                                    <button type="submit" name="modificar" class="btn-block btn btn-primary mt-20 mb-100">GUARDAR CAMBIOS</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>