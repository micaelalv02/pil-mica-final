<?php
require_once "Config/Autoload.php";
Config\Autoload::run();

$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$landing = new Clases\Landing();
$contenidos = new Clases\Contenidos();
$config = new Clases\Config();

#Variables GET
$cod = isset($_GET["cod"]) ? $f->antihack_mysqli($_GET["cod"]) : '';

$filter = ["`contenidos`.`area` = 'landing-area'"];

// isset($_GET["area"]) ?  $filter[] = "`contenidos`.`area` = '" . $f->antihack_mysqli($_GET["area"]) . "'" : '';
isset($_GET["cod"]) ?  $filter[] = "`contenidos`.`cod` = '" . $f->antihack_mysqli($_GET["cod"]) . "'" : '';

$data = [
    "filter" => $filter,
    "images" => true,
    "category" => true,
    "gallery" => true
];

//traer data formulario
$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);
$form = json_decode(file_get_contents(__DIR__ . '/admin/inc/landing/campos-form.json', false, stream_context_create($arrContextOptions)), true);

#List de contenidos (al ser único el título, solo trae un resultado)
$landingData = $contenidos->list($data, $_SESSION["lang"], true);

#Se carga la configuración de contacto
$dataContact = $config->viewContact();

#Fecha normalizada de la landing actual
$fecha = strftime("%u de %B de %Y", strtotime($landingData['data']['fecha']));

#Información de cabecera
$template->set("title", ucfirst(mb_strtolower($landingData['data']['titulo'])) . ' | ' . TITULO);
$template->themeInit();

?>

<div class="page-title-area pt-150 pb-55">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-titel-detalis  ">
                    <div class="section-title">
                        <h2><?= $landingData['data']['titulo'] ?></h2>
                    </div>
                    <div class="page-bc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= URL ?>"><?= $_SESSION["lang-txt"]["general"]["inicio"] ?></a></li>
                                <li class="breadcrumb-item position-relative active text-capitalize"><a href="<?= URL ?>/c/<?= $landingData['data']['area'] ?>"><?= $landingData['data']['area'] ?></a></li>
                                <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= CANONICAL ?>"><?= $landingData['data']['titulo'] ?></a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="content" class="site-content mt-50 mb-50" tabindex="-1">
    <div class="container">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <div class="row">

                    <div class="col-7">
                        <article class="has-post-thumbnail hentry">
                            <div class="product-images-wrapper">
                                <?php
                                if (!empty($landingData['images'])) {
                                ?>
                                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <?php
                                            foreach ($landingData['images'] as $key => $img) {
                                            ?>
                                                <div class="carousel-item <?php if ($key == 0) {
                                                                                echo "active";
                                                                            } ?>">
                                                    <div style="height:500px;background:url(<?= $img['url'] ?>)center/cover;"></div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        if (@count($landingData['images']) > 1) {
                                        ?>
                                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                <?php
                                }
                                ?>
                            </div><!-- /.product-images-wrapper -->
                            <hr />
                            <header class="entry-header">
                                <div class="entry-meta">
                                    <span class="posted-on"><a href="#" rel="bookmark"><i class="fa fa-calendar" aria-hidden="true"></i> <?= $fecha ?></a></span>
                                </div>
                                <h2 class="entry-title" itemprop="name headline"><?= $landingData['data']['titulo']; ?></h2>
                            </header><!-- .entry-header -->
                            <hr />
                            <p class="fs-20">
                                <?= $landingData['data']['contenido']; ?>
                            </p>
                        </article>
                    </div>
                    <div class="col-4">
                        <?php foreach ($form as $formData) {
                            if ($formData['landing'] == $landingData['data']['cod']) { ?>
                                <div class="blogs-page ">
                                    <div>
                                        <h3><?= $formData['titulo'] ?></h3>
                                        <hr />
                                        <form method="post" class="row" id="formulario-landing" onsubmit="sendLandingSub()">
                                            <input type="hidden" name="landing_cod" value="<?= $landingData['data']["cod"] ?>" />
                                            <?php foreach ($formData['data'] as $formItem) {
                                                if ($formItem['campo'] != "mensaje") { ?>
                                                    <label class="col-xs-12 col-sm-<?= $formItem['columnas'] ?> col-md-<?= $formItem['columnas'] ?>">
                                                        <?= $formItem['campo'] ?> <?= $formItem['requerido'] == true ? '<span style="color:red">(*)</span>' : "" ?>:<br />
                                                        <input type="<?= $formItem['type'] ?>" name="<?= $formItem['campo'] ?>" class="form-control" <?= $formItem['requerido'] == true ? "required" : "" ?> />
                                                    </label>
                                                <?php } else { ?>
                                                    <label class="col-xs-12 col-sm-12 col-md-12">
                                                        <?= $formItem['campo'] ?> <?= $formItem['requerido'] == true ? '<span style="color:red">(*)</span>' : "" ?>:<br />
                                                        <textarea name="<?= $formItem['campo'] ?>" class="form-control"></textarea>
                                                    </label>
                                            <?php }
                                            } ?>
                                            <label class="col-xs-12 col-sm-12  col-md-12">
                                                <input type="submit" name="enviar" class="btn btn-block btn-success" value="<?= $formData['submit'] ?>" />
                                            </label>
                                        </form>
                                        <hr />
                                    </div>
                                    <div class="mt-40 text-center">
                                        <h5><b>Comunicate también por:</b></h5>
                                        <div>
                                            <a target="_blank" href="https://api.whatsapp.com/send?phone=549<?= $dataContact['data']['whatsapp'] ?>" class="btn btn-block btn-success fs-18">
                                                <i class="ifoot fa fa-whatsapp" aria-hidden="true"></i> WhatsApp
                                            </a>
                                            <a target="_blank" href="tel:<?= $dataContact['data']['telefono'] ?>" class="btn btn-block btn-info fs-19">
                                                <i class="ifoot fa fa-phone" aria-hidden="true"></i> <?= $dataContact['data']['telefono'] ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
    function sendLandingSub() {
        $.ajax({
            url: '<?= URL ?>' + '/api/landing/addLandingSub.php',
            type: "POST",
            data: $('#formulario-landing').serialize(),
            success: (data) => {
                location.href = '<?= URL ?>' + '/gracias.php';
            }
        });
    };
</script>

<?php $template->themeEnd(); ?>