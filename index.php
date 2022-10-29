<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$contenidos = new Clases\Contenidos();

$data_inicio = [
    "images" => true,
    "filter" => ["contenidos.area = 'inicio'"],
];

$inicio = $contenidos->list($data_inicio, $_SESSION['lang']);

// $inicio = $contenidos->list(["filter" => ["contenidos.cod='slide-inicio'"]], $_SESSION['lang'], true);

$empresaData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='empresa'"]], $_SESSION['lang'], false);

$testimoniosData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='testimonios'"]], $_SESSION['lang'], false);

$serviciosData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='servicios'"]], $_SESSION['lang'], false);

$portfolioData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='portfolio'"]], $_SESSION['lang'], false);

$equipoData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='equipo'"]], $_SESSION['lang'], false);


/*echo"<pre>";
var_dump($portfolioData);
echo"</pre>";*/

//var_dump(URL);
$template->themeInit();
?>


<!--Inicio-->
<section class="banner-section two">
    <div class="banner-element-four">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-6.png" alt="element">
    </div>
    <div class="banner-element-five">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-7.png" alt="element">
    </div>
    <div class="banner-element-eightteen">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-53.png" alt="element">
    </div>
    <div class="banner-element-nineteen">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-6.png" alt="element">
    </div>
    <div class="banner-element-twenty">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-7.png" alt="element">
    </div>
    <div class="banner-element-twenty-one">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-7.png" alt="element">
    </div>
    <div class="banner-element-twenty-two">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-54.png" alt="element">
    </div>
    <div class="banner-element-twenty-three">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-55.png" alt="element">
    </div>
    <div class="banner-element-twenty-four">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-56.png" alt="element">
    </div>
    <div class="banner-element-twenty-six">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-64.png" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center align-items-center mb-30-none">
            <div class="col-xl-12 mb-30">
                <div class="banner-content two">
                    <div class="banner-content-header" data-aos="fade-up" data-aos-duration="1200">
                        <h1><?= $inicio["slide-inicio"]["data"]["titulo"] ?></h1>
                        <h1><?= $inicio["slide-inicio"]["data"]["subtitulo"] ?></h1>
                    </div>
                    <div class="banner-area">
                        <div class="banner-text">
                            <span><?= $inicio["tech-inicio"]["data"]["titulo"] ?></span>
                        </div>
                        <div class="banner-left-content">
                            <div class="banner-left-video">
                                <div class="circle">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="300px" height="300px" viewBox="0 0 300 300" enable-background="new 0 0 300 300" xml:space="preserve">
                                        <defs>
                                            <path id="circlePath" d=" M 150, 150 m -60, 0 a 60,60 0 0,1 120,0 a 60,60 0 0,1 -120,0 " />
                                        </defs>
                                        <circle cx="150" cy="100" r="75" fill="none" />
                                        <g>
                                            <use xlink:href="#circlePath" fill="none" />
                                            <text fill="#3249b3">
                                                <textPath xlink:href="#circlePath"><?= $inicio["tech-inicio"]["data"]["description"] ?></textPath>
                                            </text>
                                        </g>
                                    </svg>
                                </div>
                                <div class="video-main">
                                    <div class="promo-video">
                                        <div class="waves-block">
                                            <div class="waves wave-1"></div>
                                            <div class="waves wave-2"></div>
                                            <div class="waves wave-3"></div>
                                        </div>
                                    </div>
                                    <a class="video-icon video" data-rel="lightcase:myCollection" href="https://www.youtube.com/embed/LRhrNC-OC0Y">
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="banner-left-arrow">
                                <img src="<?= URL ?>/assets/theme/assets/images/element/element-1.png" alt="element">
                            </div>
                            <div class="banner-left-intro">
                                <span>Play Intro</span>
                            </div>
                            <div class="banner-left-footer">
                                <h3 class="title"><?= $inicio["tech-inicio"]["data"]["subtitulo"] ?></h3>
                                <p><?= $inicio["tech-inicio"]["data"]["contenido"] ?></p>
                                <div class="banner-left-btn">
                                    <a href="<?= $inicio["tech-inicio"]["data"]["link"] ?>" class="btn--base active">Contactanos</a>
                                </div>
                            </div>
                        </div>
                        <div class="banner-thumb">
                            <img src="<?= URL ?>/assets/theme/assets/images/element/element-52.png" alt="element">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Inicio
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Scroll-To-Top
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<a href="#" class="scrollToTop"><i class="las la-angle-double-up"></i></a>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Scroll-To-Top
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   Empresa
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="statistics-section pb-120">
    <div class="statistics-element-one">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-39.png" alt="element">
    </div>
    <div class="statistics-element-two">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-60.png" alt="element">
    </div>
    <div class="statistics-element-three">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-26.png" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="statistics-left-content">
                    <h2><?= $inicio['empresa-inicio']["data"]["titulo"] ?></h2>
                    <p><?= $inicio['servicios-inicio']["data"]["contenido"] ?></p>
                    <div class="statistics-left-btn">
                        <a href="<?= $inicio['servicios-inicio']["data"]["link"] ?>" class="custom-btn">Ver más</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="statistics-item-area">
                    <div class="row mb-30-none">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-30">
                            <div class="statistics-item">
                                <div class="statistics-icon">
                                    <img src="<?= $inicio['experience-inicio']["images"][0]["url"] ?>" alt="icon">
                                </div>
                                <div class="statistics-content">
                                    <div class="odo-area">
                                        <h3 class="odo-title odometer" data-odometer-final="10"><?= $inicio['experience-inicio']["data"]["subtitulo"] ?></h3>
                                        <h3 class="title"><?= $inicio['experience-inicio']["data"]["subtitulo"] ?></h3>
                                    </div>
                                    <p><?= $inicio['experience-inicio']["data"]["contenido"] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-30">
                            <div class="statistics-item">
                                <div class="statistics-icon">
                                    <img src="<?= $inicio['employees-inicio']["images"][0]["url"] ?>" alt="icon">
                                </div>
                                <div class="statistics-content">
                                    <div class="odo-area">
                                        <h3 class="odo-title odometer" data-odometer-final="120"><?= $inicio['employees-inicio']["data"]["subtitulo"] ?></h3>
                                        <h3 class="title"><?= $inicio['employees-inicio']["data"]["subtitulo"] ?></h3>
                                    </div>
                                    <p><?= $inicio['employees-inicio']["data"]["contenido"] ?></p>
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
    End Empresa
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Todos Los Servicios
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="overview-section pb-120">
    <div class="overview-element">
        <img src="<?= $serviciosData["inicio-servicios"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="overview-thumb">
                    <img src="<?= $serviciosData["inicio-servicios"]["images"][1]["url"] ?>" alt="element">
                    <div class="overview-thumb-element">
                        <img src="<?= $serviciosData["inicio-servicios"]["images"][2]["url"] ?>" alt="element">
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="overview-content">
                    <h2><?= $serviciosData["inicio-servicios"]["data"]["titulo"] ?></h2>
                    <p><?= $serviciosData["inicio-servicios"]["data"]["contenido"] ?></p>
                    <div class="overview-btn">
                        <a href="<?= $serviciosData["inicio-servicios"]["data"]["link"] ?>" class="btn--base active"><?= $serviciosData["inicio-servicios"]["data"]["description"] ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Todos Los Servicios
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Service
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="service-section two pb-120">
    <div class="service-element-one two">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-23.pn" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center mb-60-none">
            <?php foreach ($serviciosData as $servicios) {
                if ($servicios["data"]["cod"] == "inicio-servicios") continue; ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-60">
                    <div class="service-item two">
                        <div class="service-icon">
                            <img src="<?= $servicios["images"][0]["url"] ?>" alt="icon">
                        </div>
                        <div class="service-content">
                            <h3><?= $servicios["data"]["titulo"] ?></h3>
                            <p><?= $servicios["data"]["contenido"] ?></p>
                            <div class="service-btn">
                                <a href="<?= $servicios["data"]["link"] ?>" class="custom-btn">Ver más<i class="icon-Group-2361 ml-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Service
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Project
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="project-section two">
    <div class="project-element-one two">
        <img src="<?= $inicio["proyectos-inicio"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="project-element-two two">
        <img src="<?= $inicio["proyectos-inicio"]["images"][1]["url"] ?>" alt="element">
    </div>
    <div class="container-fluid p-0">
        <div class="project-area">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-2 offset-xl-2 col-md-12 col-lg-4 mb-30">
                    <div class="project-left-content">
                        <div class="logo-icon">
                            <img src="<?= $inicio["proyectos-inicio"]["images"][2]["url"] ?>" alt="favicon">
                        </div>
                        <h2><?= $inicio["proyectos-inicio"]["data"]["titulo"] ?></h2>
                        <p><?= $inicio["proyectos-inicio"]["data"]["contenido"] ?></p>
                        <div class="project-left-btn">
                            <a href="<?= $inicio["proyectos-inicio"]["data"]["link"] ?>" class="btn--base active">Ver todos los proyectos</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-xxl-8 col-lg-8 mb-30">
                    <div class="project-slider-area">
                        <div class="slider-prev">
                            <i class="fas fa-chevron-left"></i>
                        </div>
                        <div class="slider-next">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="project-slider">
                            <div class="swiper-wrapper">
                                <?php foreach ($portfolioData as $portfolio) { ?>
                                    <div class="swiper-slide">
                                        <div class="project-thumb two">
                                            <img src="<?= $portfolio["images"][0]["url"] ?>" alt="project">
                                            <div class="project-overlay">
                                                <div class="overlay-content">
                                                    <h4><a href="<?= $portfolio["data"]["link"] ?>"><?= $portfolio["data"]["titulo"] ?></a></h4>
                                                </div>
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
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Project
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start About
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="about-section pt-120">
    <div class="about-element-one two">
        <img src="<?= $inicio["about-inicio"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="about-area">
            <div class="row justify-content-center align-items-center mb-30-none">
                <div class="col-xl-6 col-lg-6 mb-30">
                    <div class="box-wrapper two">
                        <div class="box3"></div>
                        <div class="box1">
                            <div class="box-element-one">
                                <img src="<?= $inicio["about-inicio"]["images"][1]["url"] ?>" alt="element">
                            </div>
                            <div class="box-element-two">
                                <img src="<?= $inicio["about-inicio"]["images"][2]["url"] ?>" alt="element">
                            </div>
                        </div>
                        <div class="box2">
                            <div class="box-element-five">
                                <img src="<?= $inicio["about-inicio"]["images"][3]["url"] ?>" alt="element">
                            </div>
                            <div class="box-element-six">
                                <img src="<?= $inicio["about-inicio"]["images"][4]["url"] ?>" alt="element">
                            </div>
                        </div>
                    </div>
                    <div class="about-thumb">
                        <img src="<?= $inicio["about-inicio"]["images"][5]["url"] ?>" alt="element">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 mb-30">
                    <div class="about-content">
                        <h2 class="title"><?= $inicio["about-inicio"]["data"]["titulo"] ?><span class="text--base"><?= $inicio["about-inicio"]["data"]["subtitulo"] ?></span></h2>
                        <p class="para"><?= $inicio["about-inicio"]["data"]["contenido"] ?></p>
                        <p><?= $inicio["about-inicio"]["data"]["description"] ?></p>
                        <div class="about-btn">
                            <a href="<?= $inicio["about-inicio"]["data"]["link"] ?>" class="btn--base">Enviar Mensaje</a>
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
   Contacto
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--<section class="contact-section two pb-120">
    <div class="container">
        <div class="contact-element-one">
            <img src="<?= URL ?>/assets/theme/assets/images/element/element-6.png" alt="element">
        </div>
        <div class="contact-element-two">
            <img src="<?= URL ?>/assets/theme/assets/images/element/element-39.png" alt="element">
        </div>
        <div class="contact-element-three">
            <img src="<?= URL ?>/assets/theme/assets/images/element/element-26.png" alt="element">
        </div>
        <div class="contact-element-four">
            <img src="<?= URL ?>/assets/theme/assets/images/element/element-7.png" alt="element">
        </div>
        <div class="contact-area">
            <div class="contact-element-five">
                <img src="<?= URL ?>/assets/theme/assets/images/element/element-60.png" alt="element">
            </div>
            <div class="contact-element-six">
                <img src="<?= URL ?>/assets/theme/assets/images/element/element-60.png" alt="element">
            </div>
            <div class="row mb-30-none">
                <div class="col-xl-5 col-lg-5 mb-30">
                    <div class="contact-thumb">
                        <img src="<?= URL ?>/assets/theme/assets/images/contact.png" alt="contact">
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7 mb-30">
                    <div class="contact-form-area">
                        <div class="contact-form-header">
                            <div class="left">
                                <h2 class="title">Get in Touch <span class="text--base">Let's Talk</span></h2>
                                <p>Credibly grow premier ideas rather than bricks-and-clicks strategic theme areas.</p>
                            </div>
                            <div class="right">
                                <div class="circle">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="300px" height="300px" viewBox="0 0 300 300" enable-background="new 0 0 300 300" xml:space="preserve">
                                        <defs>
                                            <path id="circlePathtwo" d=" M 150, 150 m -60, 0 a 60,60 0 0,1 120,0 a 60,60 0 0,1 -120,0 " />
                                        </defs>
                                        <circle cx="150" cy="100" r="75" fill="none" />
                                        <g>
                                            <use xlink:href="#circlePathtwo" fill="none" />
                                            <text fill="#3249b3">
                                                <textPath xlink:href="#circlePathtwo">Softim it solution Softim it solution Softim it solution Softim it solution</textPath>
                                            </text>
                                        </g>
                                    </svg>
                                </div>
                                <div class="contact-logo">
                                    <img src="<?= URL ?>/assets/theme/assets/images/fav.png" alt="favicon">
                                </div>
                            </div>
                        </div>
                        <form class="contact-form">
                            <div class="row justify-content-center mb-30-none">
                                <div class="col-xl-6 col-lg-6 form-group">
                                    <input type="text" class="form--control" placeholder="Your Name">
                                </div>
                                <div class="col-xl-6 col-lg-6 form-group">
                                    <input type="email" class="form--control" placeholder="Your Email">
                                </div>
                                <div class="col-xl-6 col-lg-6 form-group">
                                    <input type="text" class="form--control" placeholder="Phone Number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                </div>
                                <div class="col-xl-6 col-lg-6 form-group">
                                    <div class="contact-select">
                                        <select class="form--control">
                                            <option value="1">Service Required</option>
                                            <option value="2">Web Design</option>
                                            <option value="3">Digital Marketing</option>
                                            <option value="4">Search SEO</option>
                                            <option value="5">Web Development</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-12 form-group">
                                    <textarea class="form--control" placeholder="Write Message..."></textarea>
                                </div>
                                <div class="col-xl-12 form-group custom-form-group mt-20">
                                    <div class="form-group custom-check-group">
                                        <input type="checkbox" id="level-1">
                                        <label for="level-1">I'm Agree With <a href="#0" class="text--base">Terms & Conditions</a></label>
                                    </div>
                                    <button type="submit" class="btn--base">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Contacto
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     Blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--<section class="blog-section pb-120">
    <div class="blog-element">
        <img src="<?= URL ?>/assets/theme/assets/images/element/element-47.png" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 text-center">
                <div class="section-header">
                    <h2 class="section-title">Softim Latest Posts</h2>
                    <p>Credibly grow premier ideas rather than bricks-and-clicks strategic theme areas distributed for stand-alone web-readiness.</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="blog-item">
                    <div class="blog-thumb">
                        <img src="<?= URL ?>/assets/theme/assets/images/blog/blog-1.png" alt="blog">
                    </div>
                    <div class="blog-content">
                        <div class="blog-category">
                            <span>Business</span>
                        </div>
                        <h3 class="title"><a href="blog-details.html">It was popularised in the 1960s
                                with the release</a></h3>
                        <p>We teach martial arts because we love it — not because we want to make</p>
                        <div class="blog-post-meta two">
                            <span class="user">By : Smith Roy</span>
                            <span class="date">24th March, 2022</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="blog-item">
                    <div class="blog-thumb">
                        <img src="<?= URL ?>/assets/theme/assets/images/blog/blog-2.png" alt="blog">
                    </div>
                    <div class="blog-content">
                        <div class="blog-category">
                            <span>Software</span>
                        </div>
                        <h3 class="title"><a href="blog-details.html">Making it look like readable
                                English Language.</a></h3>
                        <p>We teach martial arts because we love it — not because we want to make</p>
                        <div class="blog-post-meta two">
                            <span class="user">By : Smith Roy</span>
                            <span class="date">24th March, 2022</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="blog-item">
                    <div class="blog-thumb">
                        <img src="<?= URL ?>/assets/theme/assets/images/blog/blog-3.png" alt="blog">
                    </div>
                    <div class="blog-content">
                        <div class="blog-category">
                            <span>Design</span>
                        </div>
                        <h3 class="title"><a href="blog-details.html">It is a long established fact that a
                                reader will be</a></h3>
                        <p>We teach martial arts because we love it — not because we want to make</p>
                        <div class="blog-post-meta two">
                            <span class="user">By : Smith Roy</span>
                            <span class="date">24th March, 2022</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

<?php $template->themeEnd() ?>