<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$contenidos = new Clases\Contenidos();

$data_inicio = [
    "images" => true,
    "filter" => ["contenidos.area = 'inicio'"],
];
$empresaData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='empresa'"]], $_SESSION['lang'], false);

$inicio = $contenidos->list($data_inicio, $_SESSION['lang']);
$equipoData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='equipo'"]], $_SESSION['lang'], false);
$testimoniosData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='testimonios'"]], $_SESSION['lang'], false);


$template->themeInit();
?>


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="banner-section two inner">
    <div class="banner-element-four two">
        <img src="<?= $empresaData["banner-empresa"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-five two">
        <img src="<?= $empresaData["banner-empresa"]["images"][1]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-nineteen two">
        <img src="<?= $empresaData["banner-empresa"]["images"][2]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-twenty-two two">
        <img src="<?= $empresaData["banner-empresa"]["images"][3]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-twenty-three two">
        <img src="<?= $empresaData["banner-empresa"]["images"][4]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center align-items-center mb-30-none">
            <div class="col-xl-12 mb-30">
                <div class="banner-content two">
                    <div class="banner-content-header">
                        <h2 class="title"><?= $empresaData["banner-empresa"]["data"]["titulo"] ?></h2>
                        <div class="breadcrumb-area">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= $empresaData["banner-empresa"]["data"]["link"] ?>"><?= $empresaData["banner-empresa"]["data"]["subtitulo"] ?></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $empresaData["banner-empresa"]["data"]["titulo"] ?></li>
                                </ol>
                            </nav>
                        </div>
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
    Start About
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="about-section ptb-120">
    <div class="about-element-one two">
        <img src="<?= $empresaData["about-empresa"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="about-content two">
                    <h3 class="title"><?= $empresaData["about-empresa"]["data"]["titulo"] ?></h3>
                    <p class="para"><?= $empresaData["about-empresa"]["data"]["contenido"] ?></p>
                    <div class="about-btn two">
                        <a href="<?= URL ?>contacto.php" class="btn--base"><?= $empresaData["contacto-empresa"]["data"]["titulo"] ?></a>
                        <span><?= $empresaData["contacto-empresa"]["data"]["contenido"] ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="about-thumb two">
                    <img src="<?= $empresaData["video-empresa"]["images"][0]["url"] ?>" alt="element">
                    <div class="about-thumb-element-one">
                        <img src="<?= $empresaData["video-empresa"]["images"][1]["url"] ?>" alt="element">
                    </div>
                    <div class="about-thumb-element-two">
                        <img src="<?= $empresaData["video-empresa"]["images"][2]["url"] ?>" alt="element">
                    </div>
                    <div class="about-thumb-video">
                        <div class="circle">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="300px" height="300px" viewBox="0 0 300 300" enable-background="new 0 0 300 300" xml:space="preserve">
                                <defs>
                                    <path id="circlePath" d=" M 150, 150 m -60, 0 a 60,60 0 0,1 120,0 a 60,60 0 0,1 -120,0 " />
                                </defs>
                                <circle cx="150" cy="100" r="75" fill="none" />
                                <g>
                                    <use xlink:href="#circlePath" fill="none" />
                                    <text fill="#ffffff">
                                        <textPath xlink:href="#circlePath"><?= $empresaData["video-empresa"]["data"]["titulo"] ?></textPath>
                                    </text>
                                </g>
                            </svg>
                        </div>
                        <div class="video-main">
                            <a class="video-icon video" data-rel="lightcase:myCollection" href="<?= $empresaData["video-empresa"]["data"]["link"] ?>">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End About
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Agency
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="agency-section ptb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="agency-content text-center">
                    <div class="agency-logo-text">
                        <span><?= $empresaData["agencia-empresa"]["data"]["subtitulo"] ?></span>
                    </div>
                    <h2 class="title"><?= $empresaData["agencia-empresa"]["data"]["titulo"] ?></h2>
                </div>
                <div class="agency-statistics-area">
                    <div class="row justify-content-center mb-30-none">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                            <div class="statistics-item">
                                <div class="statistics-content">
                                    <div class="odo-area">
                                        <h3 class="odo-title odometer" data-odometer-final="12"><?= $empresaData["experiencia-empresa"]["data"]["titulo"] ?></h3>
                                        <h3 class="title"><?= $empresaData["experiencia-empresa"]["data"]["subtitulo"] ?></h3>
                                    </div>
                                    <p><?= $empresaData["experiencia-empresa"]["data"]["contenido"] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                            <div class="statistics-item">
                                <div class="statistics-content">
                                    <div class="odo-area">
                                        <h3 class="odo-title odometer" data-odometer-final="256"><?= $empresaData["proyectos-empresa"]["data"]["titulo"] ?></h3>
                                        <h3 class="title"><?= $empresaData["proyectos-empresa"]["data"]["subtitulo"] ?></h3>
                                    </div>
                                    <p><?= $empresaData["proyectos-empresa"]["data"]["contenido"] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                            <div class="statistics-item">
                                <div class="statistics-content">
                                    <div class="odo-area">
                                        <h3 class="odo-title odometer" data-odometer-final="65"><?= $empresaData["especialistas-empresa"]["data"]["titulo"] ?></h3>
                                        <h3 class="title"><?= $empresaData["especialistas-empresa"]["data"]["subtitulo"] ?></h3>
                                    </div>
                                    <p><?= $empresaData["especialistas-empresa"]["data"]["contenido"] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                            <div class="statistics-item">
                                <div class="statistics-content">
                                    <div class="odo-area">
                                        <h3 class="odo-title odometer" data-odometer-final="25"><?= $empresaData["alcance-empresa"]["data"]["titulo"] ?></h3>
                                        <h3 class="title"><?= $empresaData["alcance-empresa"]["data"]["subtitulo"] ?></h3>
                                    </div>
                                    <p><?= $empresaData["alcance-empresa"]["data"]["contenido"] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Agency
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start About
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="about-section pt-120">
    <div class="about-element-one two">
        <img src="<?= $empresaData["about-emp"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="about-area">
            <div class="row justify-content-center align-items-center mb-30-none">
                <div class="col-xl-6 col-lg-6 mb-30">
                    <div class="box-wrapper two">
                        <div class="box3"></div>
                        <div class="box1">
                            <div class="box-element-one">
                                <img src="<?= $empresaData["about-emp"]["images"][1]["url"] ?>" alt="element">
                            </div>
                            <div class="box-element-two">
                                <img src="<?= $empresaData["about-emp"]["images"][2]["url"] ?>" alt="element">
                            </div>
                        </div>
                        <div class="box2">
                            <div class="box-element-five">
                                <img src="<?= $empresaData["about-emp"]["images"][3]["url"] ?>" alt="element">
                            </div>
                            <div class="box-element-six">
                                <img src="<?= $empresaData["about-emp"]["images"][4]["url"] ?>" alt="element">
                            </div>
                        </div>
                    </div>
                    <div class="about-thumb">
                        <img src="<?= $empresaData["about-emp"]["images"][5]["url"] ?>" alt="element">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 mb-30">
                    <div class="about-content">
                        <h2 class="title"><?= $empresaData["about-emp"]["data"]["titulo"] ?><span class="text--base"><?= $empresaData["about-emp"]["data"]["subtitulo"] ?></span></h2>
                        <p class="para"><?= $empresaData["about-emp"]["data"]["contenido"] ?></p>
                        <p><?= $empresaData["about-emp"]["data"]["description"] ?></p>
                        <div class="about-btn">
                            <a href="<?= $empresaData["about-emp"]["data"]["link"] ?>" class="btn--base">Enviar Mensaje</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End About
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Team
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="team-section two ptb-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="section-header-wrapper">
                    <div class="section-header">
                        <h2><?= $inicio["team-inicio"]["data"]["titulo"] ?></h2>
                        <p><?= $inicio["team-inicio"]["data"]["contenido"] ?></p>
                    </div>
                    <div class="slider-nav-area">
                        <div class="slider-prev">
                            <i class="fas fa-chevron-left"></i>
                        </div>
                        <div class="slider-next">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-12">
                <div class="team-slider-area two">
                    <div class="team-slider two">
                        <div class="swiper-wrapper">
                            <?php foreach ($equipoData as $equipo) { ?>
                                <div class="swiper-slide">
                                    <div class="team-item">
                                        <div class="team-thumb">
                                            <img src="<?= $equipo["images"][0]["url"] ?>" alt="team">
                                            <div class="team-social-area">
                                                <ul class="team-social">
                                                    <li><a href="#0"><i class="fab fa-facebook-f"></i></a></li>
                                                    <li><a href="#0"><i class="fab fa-twitter"></i></a></li>
                                                    <li><a href="#0"><i class="fab fa-google-plus-g"></i></a></li>
                                                    <li><a href="#0"><i class="fab fa-instagram"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="team-content">
                                            <h3 class="title"><?= $equipo["data"]["titulo"] ?></h3>
                                            <span class="sub-title"><?= $equipo["data"]["subtitulo"] ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Team
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Testimonios
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="client-section two pb-120">
    <div class="client-element-one two">
        <img src="<?= $testimoniosData["blog-testimonios"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="client-element-two two">
        <img src="<?= $testimoniosData["blog-testimonios"]["images"][1]["url"] ?>" alt="element">
    </div>
    <div class="client-element-three">
        <img src="<?= $testimoniosData["blog-testimonios"]["images"][2]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 text-center">
                <div class="section-header">
                    <h2><?= $testimoniosData["blog-testimonios"]["data"]["titulo"] ?></h2>
                    <p><?= $testimoniosData["blog-testimonios"]["data"]["subtitulo"] ?></p>
                </div>
            </div>
        </div>
        <div class="client-area">
            <div class="row justify-content-center align-items-end mb-30-none">
                <div class="col-xl-8 col-lg-8 col-md-6 mb-30">
                    <div class="client-slider-area two">
                        <div class="client-slider-two">
                            <div class="swiper-wrapper">
                                <?php foreach ($testimoniosData as $testimonios) {
                                    if ($testimonios["data"]["cod"] == "blog-testimonios" || $testimonios["data"]["cod"] == "video-testimonios") continue; ?>
                                    <div class="swiper-testimonios">
                                        <div class="client-item">
                                            <div class="client-header">
                                                <div class="client-quote">
                                                    <img src="<?= $testimonios["images"][0]["url"] ?>" alt="client">
                                                </div>
                                                <div class="client-thumb">
                                                    <img src="<?= $testimonios["images"][1]["url"] ?>" alt="client">
                                                </div>
                                            </div>
                                            <div class="client-content">
                                                <p><?= $testimonios["data"]["contenido"] ?></p>
                                            </div>
                                            <div class="client-footer">
                                                <div class="client-footer-left">
                                                    <h4><?= $testimonios["data"]["titulo"] ?></h4>
                                                    <span class="sub-title"><?= $testimonios["data"]["subtitulo"] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
                    <div class="client-right-thumb">
                        <img src="<?= $testimoniosData["video-testimonios"]["images"][0]["url"] ?>" alt="client">
                        <div class="client-thumb-element">
                            <img src="<?= $testimoniosData["video-testimonios"]["images"][1]["url"] ?>" alt="element">
                        </div>
                        <div class="client-thumb-overlay">
                            <div class="client-thumb-video">
                                <div class="video-main">
                                    <div class="promo-video">
                                        <div class="waves-block">
                                            <div class="waves wave-1"></div>
                                            <div class="waves wave-2"></div>
                                            <div class="waves wave-3"></div>
                                        </div>
                                    </div>
                                    <a class="video-icon video" data-rel="lightcase:myCollection" href="<?= $testimoniosData["video-testimonios"]["data"]["link"] ?>">
                                        <i class="las la-play"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="client-pagination"></div>
                <div class="slider-prev">
                    <i class="las la-long-arrow-alt-left"></i>
                </div>
                <div class="slider-next">
                    <i class="las la-long-arrow-alt-right"></i>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Testimonios
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->



<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Brand
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="brand-section pt-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="brand-slider-area">
                    <div class="brand-slider">
                        <div class="swiper-wrapper">
                            <?php foreach ($inicio["brand-inicio"]["images"] as $imagen) { ?>
                                <div class="swiper-slide">
                                    <div class="brand-item">
                                        <img src="<?= $imagen["url"] ?>" alt="brand">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Brand
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<?php $template->themeEnd() ?>