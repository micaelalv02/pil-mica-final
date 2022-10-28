<?php
require_once "Config/Autoload.php";
Config\Autoload::run();

$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$contenidos = new Clases\Contenidos();

$filter = [];
$get = $f->antihackMulti($_GET);
foreach ($get as $key => $get_) {
    (isset($_GET[$key]) && $key != 'pagina') ?  $filter[] = "contenidos.$key = '" . $get_ . "'" : '';
}
$area = isset($get['area']) ? $get['area'] : '';

$pagina = isset($_GET['pagina']) ? $f->antihack_mysqli($_GET['pagina']) : 1;
$limite = 12;
$data = [
    "filter" => $filter,
    "images" => true,
    "gallery" => true,
    "limit" => ($limite * ($pagina - 1)) . "," . $limite
];
#List de contenidos (al ser único el título, solo trae un resultado)
$contenidoData = $contenidos->list($data, $_SESSION["lang"], false);
if (empty($contenidoData)) $f->headerMove(URL);
#Si se encontro el contenido se almacena y sino se redirecciona al inicio


$paginador = $contenidos->paginador(URL . '/c/' . $area, $filter, $limite, $pagina, 1);

#Información de cabecera
$template->set("title", $contenidoData[array_key_first($contenidoData)]['area']["data"]['titulo'] . " | " . TITULO);
$template->set("description", "");
$template->set("keywords", "");
$template->set("imagen", LOGO);
$template->themeInit();
?>

<div class="page-title-area pt-150 pb-55">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-titel-detalis  ">
                    <div class="section-title">
                        <h2><?= $contenidoData[array_key_first($contenidoData)]['area']["data"]['titulo'] ?></h2>
                    </div>
                    <div class="page-bc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= URL ?>"><?= $_SESSION["lang-txt"]["general"]["inicio"] ?></a></li>
                                <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= CANONICAL ?>"><?= $contenidoData[array_key_first($contenidoData)]['area']["data"]['titulo'] ?></a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="blog-area blog-ls blog-rs pt-120">
    <div class="container">
        <div class="row">
            <?php
            foreach ($contenidoData as $contentItem) {
                $link = URL . "/c/" . $contentItem['data']['area'] . "/" . $f->normalizar_link($contentItem['data']['titulo']) . "/" . $contentItem['data']['cod'];
            ?>
                <div class="col-md-4">
                    <div class="news-items mb-30">
                        <div class="news-img">
                            <a href="<?= $link ?>"><img src="<?= $contentItem['images'][0]["url"] ?>" style="width:100%;height:350px;object-fit:cover" alt="img1"></a>
                        </div>
                        <span class="d-block pt-25"><?= $contentItem["data"]["fecha"] ?></span>
                        <div class="news-details pt-5">
                            <div class="news-title">
                                <a href="<?= $link ?>"><?= $contentItem['data']['titulo'] ?></a>
                            </div>
                            <a class="slider-btn d-inline-block position-relative mt-10 fs-14" href="<?= $link ?>"><?= $_SESSION["lang-txt"]["general"]["ver_mas"] ?></a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="col-12 mb-50">
                <?= $paginador ?>
            </div>
        </div>
    </div>
</div>
<?php
$template->themeEnd();
?>