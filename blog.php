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
//$serviciosData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='servicios'"]], $_SESSION['lang'], false);
$blogData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='blog'"]], $_SESSION['lang'], false);

$template->themeInit();
?>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="banner-section two inner">
    <div class="banner-element-four two">
        <img src="<?= $blogData["banner-blog"]["images"][0]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-five two">
        <img src="<?= $blogData["banner-blog"]["images"][1]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-nineteen two">
        <img src="<?= $blogData["banner-blog"]["images"][2]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-twenty-two two">
        <img src="<?= $blogData["banner-blog"]["images"][3]["url"] ?>" alt="element">
    </div>
    <div class="banner-element-twenty-three two">
        <img src="<?= $blogData["banner-blog"]["images"][4]["url"] ?>" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center align-items-center mb-30-none">
            <div class="col-xl-12 mb-30">
                <div class="banner-content two">
                    <div class="banner-content-header">
                        <h2 class="title"><?= $blogData["banner-blog"]["data"]["titulo"] ?></h2>
                        <div class="breadcrumb-area">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="<?= $blogData["banner-blog"]["data"]["link"] ?>">
                                            <?= $blogData["banner-blog"]["data"]["subtitulo"] ?>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $blogData["banner-blog"]["data"]["titulo"] ?></li>
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
    Start Blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

<section class="blog-section ptb-120">
    <div class="container">
        <div class="row justify-content-center mb-60-none">
            <div class="col-xl-8 col-lg-8 mb-60">

                <?php foreach ($blogData as $blog) {
                    if ($blog["data"]["destacado"] != 1) continue; ?>
                    <div class="row justify-content-center mb-60-none">
                        <div class="col-xl-12 mb-60">
                            <div class="blog-item">
                                <div class="blog-thumb">
                                    <img src="<?= $blog["images"][0]["url"] ?>" alt="blog">
                                </div>
                                <div class="blog-content">
                                    <div class="blog-post-meta">
                                        <span class="user"><?= $blog["data"]["subtitulo"] ?></span>
                                        <span class="date"><?= $blog["data"]["description"] ?></span>
                                    </div>
                                    <h3 class="title"><?= $blog["data"]["titulo"] ?></a></h3>
                                    <p><?= $blog["data"]["contenido"] ?></p>
                                    <div class="blog-btn">
                                        <a href="<?= $blog["data"]["titulo"] ?>" class="custom-btn">
                                            Read More
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>














        <!-- <div class="col-xl-4 col-lg-4 mb-60">
                <div class="sidebar">
                    <div class="widget-box mb-30">
                        <h4 class="widget-title">Search</h4>
                        <div class="search-widget-box">
                            <form class="search-form">
                                <input type="text" name="search" class="form--control" placeholder="Search">
                                <button type="submit"><i class="icon-Search"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="widget-box mb-30">
                        <h4 class="widget-title">Recent Posts</h4>
                        <div class="popular-widget-box">
                            <div class="single-popular-item d-flex flex-wrap align-items-center">
                                <div class="popular-item-thumb">
                                    <img src="assets/images/blog/blog-1.png" alt="blog">
                                </div>
                                <div class="popular-item-content">
                                    <span class="blog-date">24th March, 2022</span>
                                    <h5 class="title"><a href="blog-details.html">What Is Going On In South West London.</a></h5>
                                </div>
                            </div>
                            <div class="single-popular-item d-flex flex-wrap align-items-center">
                                <div class="popular-item-thumb">
                                    <img src="assets/images/blog/blog-5.png" alt="blog">
                                </div>
                                <div class="popular-item-content">
                                    <span class="blog-date">24th March, 2022</span>
                                    <h5 class="title"><a href="blog-details.html">What Is Going On In South West London.</a></h5>
                                </div>
                            </div>
                            <div class="single-popular-item d-flex flex-wrap align-items-center">
                                <div class="popular-item-thumb">
                                    <img src="assets/images/blog/blog-7.png" alt="blog">
                                </div>
                                <div class="popular-item-content">
                                    <span class="blog-date">24th March, 2022</span>
                                    <h5 class="title"><a href="blog-details.html">What Is Going On In South West London.</a></h5>
                                </div>
                            </div>
                            <div class="single-popular-item d-flex flex-wrap align-items-center">
                                <div class="popular-item-thumb">
                                    <img src="assets/images/blog/blog-4.png" alt="blog">
                                </div>
                                <div class="popular-item-content">
                                    <span class="blog-date">24th March, 2022</span>
                                    <h5 class="title"><a href="blog-details.html">What Is Going On In South West London.</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="widget-box mb-30">
                        <h4 class="widget-title">Categories</h4>
                        <div class="category-widget-box">
                            <ul class="category-list">
                                <li><a href="#0"><i class="fas fa-chevron-right"></i> Business Analysis <span>4</span></a></li>
                                <li><a href="#0"><i class="fas fa-chevron-right"></i> Business Strategy <span>5</span></a></li>
                                <li><a href="#0"><i class="fas fa-chevron-right"></i> Stock Investment <span>1</span></a></li>
                                <li><a href="#0"><i class="fas fa-chevron-right"></i> Business Analysis <span>4</span></a></li>
                                <li><a href="#0"><i class="fas fa-chevron-right"></i> Business Analysis <span>4</span></a></li>
                                <li><a href="#0"><i class="fas fa-chevron-right"></i> Business Analysis <span>4</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="widget-box">
                        <h4 class="widget-title">Tags</h4>
                        <div class="tag-widget-box">
                            <ul class="tag-list">
                                <li><a href="#0">infobpn</a></li>
                                <li><a href="#0">driver</a></li>
                                <li><a href="#0">newdriver</a></li>
                                <li><a href="#0">Gallery</a></li>
                                <li><a href="#0">manual</a></li>
                                <li><a href="#0">Office</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav>
            <ul class="pagination">
                <li class="page-item prev">
                    <a class="page-link" href="#" rel="prev" aria-label="Prev &raquo;">PREV</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">01</a></li>
                <li class="page-item active" aria-current="page"><span class="page-link">02</span></li>
                <li class="page-item"><a class="page-link" href="#">03</a></li>
                <li class="page-item"><a class="page-link" href="#">04</a></li>
                <li class="page-item"><a class="page-link" href="#">05</a></li>
                <li class="page-item next">
                    <a class="page-link" href="#" rel="next" aria-label="Next &raquo;">NEXT</a>
                </li>
            </ul>
        </nav> -->
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->














<?php $template->themeEnd() ?>