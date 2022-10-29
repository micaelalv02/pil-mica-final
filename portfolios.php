<?php
require_once "Config/Autoload.php";
Config\Autoload::run();

$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$contenidos = new Clases\Contenidos();
$categoria = new Clases\Categorias();

$portfolioData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='portfolio'"]], $_SESSION['lang'], false);

$pagina = isset($_GET['pagina']) ? $f->antihack_mysqli($_GET['pagina']) : 1;
$limite = 12;
//var_dump($_GET);
#List de contenidos (al ser único el título, solo trae un resultado)


$categoriaList = $categoria->list(["filter" => "area = 'portfolio'"], 'titulo ASC', '', "es", false,  false);


if (empty($portfolioData)) $f->headerMove(URL);
#Si se encontro el contenido se almacena y sino se redirecciona al inicio


$paginador = $contenidos->paginador(URL . '/portfolios/', ["contenidos.area='portfolio'"], $limite, $pagina, 1);

#Información de cabecera
$template->set("title", $portfolioData[array_key_first($portfolioData)]['area']["data"]['titulo'] . " | " . TITULO);
$template->set("description", "");
$template->set("keywords", "");
$template->set("imagen", LOGO);

$template->themeInit();
?>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="banner-section two inner">
    <div class="banner-element-four two">
        <img src="<?= $portfolioData["banner-portfolio"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-five two">
        <img src="<?= $portfolioData["banner-portfolio"]["images"][1]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-nineteen two">
        <img src="<?= $portfolioData["banner-portfolio"]["images"][2]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-twenty-two two">
        <img src="<?= $portfolioData["banner-portfolio"]["images"][3]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-twenty-three two">
        <img src="<?= $portfolioData["banner-portfolio"]["images"][4]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center align-items-center mb-30-none">
            <div class="col-xl-12 mb-30">
                <div class="banner-content two">
                    <div class="banner-content-header">
                        <h2><?= $portfolioData[array_key_first($portfolioData)]["area"]["data"]["titulo"] ?></h2>
                        <div class="breadcrumb-area">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <!-- CAMBIAR HOME -->
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $portfolioData[array_key_first($portfolioData)]["area"]["data"]["titulo"] ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Scroll-To-Top
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<a href="#" class="scrollToTop"><i class="las la-angle-double-up"></i></a>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Scroll-To-Top
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Gallery
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="gallery-section ptb-120">
    <div class="container">
        <div class="gallery-filter-wrapper">
            <div class="button-group filter-btn-group">
                <button class="active" data-filter="*"><?=$portfolioData["portfolio-all"]["data"]["titulo"]?></button>
                <button data-filter=".design"><?=$portfolioData["portfolio1"]["data"]["titulo"]?></button>
                <button data-filter=".webdev"><?=$portfolioData["portfolio2"]["data"]["titulo"]?></button>
                <button data-filter=".marketing"><?=$portfolioData["portfolio3"]["data"]["titulo"]?></button>
                <button data-filter=".appdev"><?=$portfolioData["portfolio-4"]["data"]["titulo"]?></button>
            </div>
            <div class="grid">
                <!-- FALTAN CONTENIDOS -->
                <div class="grid-item design marketing">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?= $portfolioData["portfolio1"]["images"][0]["url"]?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?= $portfolioData["portfolio1"]["images"][0]["url"]?>">
                                    <img src="<?= $portfolioData["portfolio1"]["images"][4]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-item webdev marketing">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?= $portfolioData["portfolio2"]["images"][0]["url"]?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?= $portfolioData["portfolio2"]["images"][0]["url"]?>">
                                    <img src="<?= $portfolioData["portfolio2"]["images"][3]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-item appdev design">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?=$portfolioData["portfolio-4"]["images"][0]["url"]?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?=$portfolioData["portfolio-4"]["images"][1]["url"]?>">
                                    <img src="<?=$portfolioData["portfolio-4"]["images"][2]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-item webdev">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?= $portfolioData["portfolio2"]["images"][1]["url"]?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?= URL ?><?= $portfolioData["portfolio2"]["images"][1]["url"]?>">
                                    <img src="<?= $portfolioData["portfolio2"]["images"][3]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-item marketing">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?= $portfolioData["portfolio3"]["images"][3]["url"]?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?= $portfolioData["portfolio3"]["images"][3]["url"]?>">
                                    <img src="<?= $portfolioData["portfolio3"]["images"][4]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-item design">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?= $portfolioData["portfolio1"]["images"][2]["url"]?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?= $portfolioData["portfolio1"]["images"][2]["url"]?>">
                                    <img src="<?= $portfolioData["portfolio1"]["images"][4]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-item webdev">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?= $portfolioData["portfolio2"]["images"][2]["url"]?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?= $portfolioData["portfolio2"]["images"][2]["url"]?>">
                                    <img src="<?= $portfolioData["portfolio2"]["images"][3]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-item marketing">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?= $portfolioData["portfolio3"]["images"][3]["url"]?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?= $portfolioData["portfolio3"]["images"][3]["url"]?>">
                                    <img src="<?= $portfolioData["portfolio3"]["images"][4]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-item design appdev">
                    <div class="gallery-item">
                        <div class="gallery-thumb">
                            <img src="<?= $portfolioData["portfolio-4"]["images"][1]["url"] ?>" alt="gallery">
                            <div class="gallery-thumb-overlay">
                                <div class="gallery-icon">
                                    <a class="img-popup" data-rel="lightcase:myCollection" href="<?= $portfolioData["portfolio-4"]["images"][1]["url"] ?>">
                                    <img src="<?= $portfolioData["portfolio-4"]["images"][2]["url"]?>" alt="icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Gallery
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->





















<?php $template->themeEnd() ?>