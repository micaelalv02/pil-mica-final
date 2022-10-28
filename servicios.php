<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$contenidos = new Clases\Contenidos();

$data_inicio = [
    "images" => true,
    "filter" => ["contenidos.area = 'inicio'"],
];
//$empresaData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='empresa'"]], $_SESSION['lang'], false);

$inicio = $contenidos->list($data_inicio, $_SESSION['lang']);
//$equipoData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='equipo'"]], $_SESSION['lang'], false);
$serviciosData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='servicios'"]], $_SESSION['lang'], false);


$template->themeInit();
?>


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="banner-section two inner">
    <div class="banner-element-four two">
        <img src="<?= $serviciosData["banner-servicios"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-five two">
        <img src="<?= $serviciosData["banner-servicios"]["images"][1]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-nineteen two">
        <img src="<?= $serviciosData["banner-servicios"]["images"][2]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-twenty-two two">
        <img src="<?= $serviciosData["banner-servicios"]["images"][3]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-twenty-three two">
        <img src="<?= $serviciosData["banner-servicios"]["images"][4]["url"] ?>" alt="element">
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center mb-30-none">
            <div class="col-xl-12 mb-30">
                <div class="banner-content two">
                    <div class="banner-content-header">
                        <h2 class="title"><?= $serviciosData["banner-servicios"]["data"]["titulo"] ?></h2>
                        <div class="breadcrumb-area">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= $serviciosData["banner-servicios"]["data"]["link"] ?>"><?= $serviciosData["banner-servicios"]["data"]["subtitulo"] ?></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $serviciosData["banner-servicios"]["data"]["titulo"] ?></li>
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
    Start Service
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="service-section two pb-120">
    <div class="service-element-one">
        <div class="service-element-one">
            <img src="<?= $serviciosData["all-servicios"]["images"][0]["url"] ?>" alt="element">
        </div>
        <div class="service-element-two">
            <img src="<?= $serviciosData["all-servicios"]["images"][1]["url"] ?>" alt="element">
        </div>
        <div class="service-element-three">
            <img src="<?= $serviciosData["all-servicios"]["images"][2]["url"] ?>" alt="element">
        </div>
        <div class="service-element-four">
            <img src="<?= $serviciosData["all-servicios"]["images"][3]["url"] ?>" alt="element">
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

<!-- <section class="service-section two ptb-120">
    <div class="service-element-one">
        <img src="<?= $serviciosData["all-servicios"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="service-element-two">
        <img src="<?= $serviciosData["all-servicios"]["images"][1]["url"] ?>" alt="element">
    </div>
    <div class="service-element-three">
        <img src="<?= $serviciosData["all-servicios"]["images"][2]["url"] ?>" alt="element">
    </div>
    <div class="service-element-four">
        <img src="<?= $serviciosData["all-servicios"]["images"][3]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="service-item three">
                    <div class="service-icon">
                        <img src="assets/images/icon/icon-3.png" alt="icon">
                    </div>
                    <div class="service-content">
                        <h3 class="title"><a href="service-details.html">Web Design</a></h3>
                        <p>We rank among the best in the US, Argentina, and Ukraine. Our apps get</p>
                        <div class="service-btn">
                            <a href="service-details.html" class="custom-btn">Learn More <i class="icon-Group-2361 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="service-item three">
                    <div class="service-icon">
                        <img src="assets/images/icon/icon-4.png" alt="icon">
                    </div>
                    <div class="service-content">
                        <h3 class="title"><a href="service-details.html">Digital Marketing</a></h3>
                        <p>We rank among the best in the US, Argentina, and Ukraine. Our apps get</p>
                        <div class="service-btn">
                            <a href="service-details.html" class="custom-btn">Learn More <i class="icon-Group-2361 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="service-item three">
                    <div class="service-icon">
                        <img src="assets/images/icon/icon-5.png" alt="icon">
                    </div>
                    <div class="service-content">
                        <h3 class="title"><a href="service-details.html">Search SEO</a></h3>
                        <p>We rank among the best in the US, Argentina, and Ukraine. Our apps get</p>
                        <div class="service-btn">
                            <a href="service-details.html" class="custom-btn">Learn More <i class="icon-Group-2361 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="service-item three">
                    <div class="service-icon">
                        <img src="assets/images/icon/icon-6.png" alt="icon">
                    </div>
                    <div class="service-content">
                        <h3 class="title"><a href="service-details.html">Web Development</a></h3>
                        <p>We rank among the best in the US, Argentina, and Ukraine. Our apps get</p>
                        <div class="service-btn">
                            <a href="service-details.html" class="custom-btn">Learn More <i class="icon-Group-2361 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="service-item three">
                    <div class="service-icon">
                        <img src="assets/images/icon/icon-10.png" alt="icon">
                    </div>
                    <div class="service-content">
                        <h3 class="title"><a href="service-details.html">IT Management</a></h3>
                        <p>We rank among the best in the US, Argentina, and Ukraine. Our apps get</p>
                        <div class="service-btn">
                            <a href="service-details.html" class="custom-btn">Learn More <i class="icon-Group-2361 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="service-item three">
                    <div class="service-icon">
                        <img src="assets/images/icon/icon-11.png" alt="icon">
                    </div>
                    <div class="service-content">
                        <h3 class="title"><a href="service-details.html">Data Security</a></h3>
                        <p>We rank among the best in the US, Argentina, and Ukraine. Our apps get</p>
                        <div class="service-btn">
                            <a href="service-details.html" class="custom-btn">Learn More <i class="icon-Group-2361 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="service-item three">
                    <div class="service-icon">
                        <img src="assets/images/icon/icon-12.png" alt="icon">
                    </div>
                    <div class="service-content">
                        <h3 class="title"><a href="service-details.html">Business Analysis</a></h3>
                        <p>We rank among the best in the US, Argentina, and Ukraine. Our apps get</p>
                        <div class="service-btn">
                            <a href="service-details.html" class="custom-btn">Learn More <i class="icon-Group-2361 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                <div class="service-item three">
                    <div class="service-icon">
                        <img src="assets/images/icon/icon-13.png" alt="icon">
                    </div>
                    <div class="service-content">
                        <h3 class="title"><a href="service-details.html">QA & Testing</a></h3>
                        <p>We rank among the best in the US, Argentina, and Ukraine. Our apps get</p>
                        <div class="service-btn">
                            <a href="service-details.html" class="custom-btn">Learn More <i class="icon-Group-2361 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Service
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Agency
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!-- <section class="agency-section ptb-120">
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
</section> -->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Agency
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Process
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="process-section ptb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 text-center">
                <div class="section-header">
                    <h2 class="section-title mb-0">Our Development Process</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="process-item text-center">
                    <div class="process-icon-area">
                        <div class="process-element">
                            <div class="process-number">
                                <span>01</span>
                            </div>
                            <div class="process-dot">
                                <span></span>
                            </div>
                        </div>
                        <div class="process-icon">
                            <img src="assets/images/icon/icon-22.png" alt="icon">
                        </div>
                    </div>
                    <div class="process-content">
                        <h3 class="title">Discover</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="process-item text-center">
                    <div class="process-icon-area">
                        <div class="process-element">
                            <div class="process-number">
                                <span>02</span>
                            </div>
                            <div class="process-dot">
                                <span></span>
                            </div>
                        </div>
                        <div class="process-icon">
                            <img src="assets/images/icon/icon-23.png" alt="icon">
                        </div>
                    </div>
                    <div class="process-content">
                        <h3 class="title">Design</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="process-item text-center">
                    <div class="process-icon-area">
                        <div class="process-element">
                            <div class="process-number">
                                <span>03</span>
                            </div>
                            <div class="process-dot">
                                <span></span>
                            </div>
                        </div>
                        <div class="process-icon">
                            <img src="assets/images/icon/icon-24.png" alt="icon">
                        </div>
                    </div>
                    <div class="process-content">
                        <h3 class="title">Build</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="process-item text-center">
                    <div class="process-icon-area">
                        <div class="process-element">
                            <div class="process-number">
                                <span>04</span>
                            </div>
                            <div class="process-dot">
                                <span></span>
                            </div>
                        </div>
                        <div class="process-icon">
                            <img src="assets/images/icon/icon-25.png" alt="icon">
                        </div>
                    </div>
                    <div class="process-content">
                        <h3 class="title">Deliver</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Process
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->









<?php $template->themeEnd() ?>