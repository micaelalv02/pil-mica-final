<?php
require_once "Config/Autoload.php";
Config\Autoload::run();

$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$contenidos = new Clases\Contenidos();

$filter = [];

isset($_GET["area"]) ?  $filter[] = "contenidos.area = '" . $f->antihack_mysqli($_GET["area"]) . "'" : '';
isset($_GET["cod"]) ?  $filter[] = "contenidos.cod = '" . $f->antihack_mysqli($_GET["cod"]) . "'" : '';


$data = [
    "filter" => $filter,
    "images" => true,
    "category" => true,
    "gallery" => true,
];

#List de contenidos (al ser único el título, solo trae un resultado)
$contenidoData = $contenidos->list($data, $_SESSION["lang"], true);
$novedadesRelacionadas = $contenidos->list(["filter" => ["contenidos.area = '" . $f->antihack_mysqli($_GET["area"]) . "'", "contenidos.cod != '" . $f->antihack_mysqli($_GET["cod"]) . "'"], "images" => true, "limit" => 3], $_SESSION["lang"]);

#Si se encontro el contenido se almacena y sino se redirecciona al inicio
if (empty($contenidoData)) $f->headerMove(URL);
#Información de cabecera
$template->set("title", $contenidoData['data']['titulo'] . " | " . TITULO);
$template->set("description", $contenidoData['data']['description']);
$template->set("keywords", $contenidoData['data']['keywords']);
$template->set("imagen", isset($contenidoData['data']['images'][0]['url']) ? $contenidoData['data']['images'][0]['url'] : LOGO);
$template->themeInit();
?>
<div class="page-title-area pt-150 pb-55">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-titel-detalis  ">
                    <div class="section-title">
                        <h2><?= $contenidoData['data']['titulo'] ?></h2>
                    </div>
                    <div class="page-bc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= URL ?>"><?= $_SESSION["lang-txt"]["general"]["inicio"] ?></a></li>
                                <li class="breadcrumb-item position-relative active text-capitalize"><a href="<?= URL ?>/c/<?= $contenidoData['data']['area'] ?>"><?= $contenidoData['data']['area'] ?></a></li>
                                <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= CANONICAL ?>"><?= $contenidoData['data']['titulo'] ?></a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="blog-area blog-ls blog-rs news-details-area pt-120">
    <div class="container">
        <div class="news-details">
            <div class="news-img overflow-hidden text-center">
                <img src="<?= $contenidoData["images"][0]["url"] ?>" alt="<?= $contenidoData["data"]["titulo"] ?>">
            </div>
            <div class="news-title">
                <h4><?= $contenidoData["data"]["titulo"] ?></h4>
            </div>
            <div class="news-text">
                <p><?= $contenidoData["data"]["contenido"] ?></p>
            </div>
            <?php
            if (count($contenidoData["images"]) > 1) { ?>
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        unset($contenidoData["images"][0]);
                        foreach ($contenidoData["images"] as $key => $contentItem) {
                        ?>
                            <div class="carousel-item <?= $key == 1 ? 'active' : '' ?>">
                                <img style="object-fit:contain;width:100%;height:500px" src="<?= $contentItem["url"] ?>" alt="<?= $contenidoData['data']['titulo'] ?>">
                            </div>
                        <?php } ?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            <?php }
            if (isset($novedadesRelacionadas) && count($novedadesRelacionadas) >= 1) { ?>
                <div class="related-post pt-115">
                    <div class="section-title pb-25">
                        <h6><?= $_SESSION["lang-txt"]["novedades"]["relacionadas"] ?></h6>
                    </div>
                    <div class="row">
                        <?php foreach ($novedadesRelacionadas as $novedadItem) {
                            $link = URL . "/c/" . $novedadItem['data']['area'] . "/" . $f->normalizar_link($novedadItem['data']['titulo']) . "/" . $novedadItem['data']['cod'];
                        ?>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                                <div class="news-items mb-30">
                                    <div class="news-img">
                                        <a href="<?= $link ?>"><img src="<?= $novedadItem['images'][0]["url"] ?>" style="width:100%;height:350px;object-fit:cover" alt="img1"></a>
                                    </div>
                                    <span class="d-block pt-25"><?= $novedadItem["data"]["fecha"] ?></span>
                                    <div class="news-details pt-5">
                                        <div class="news-title">
                                            <a href="<?= $link ?>"><?= $novedadItem['data']['titulo'] ?></a>
                                        </div>
                                        <a class="slider-btn d-inline-block position-relative mt-10" href="<?= $link ?>"><?= $_SESSION["lang-txt"]["general"]["ver_mas"] ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php
$template->themeEnd();
?>