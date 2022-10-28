<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();
$banner = new Clases\Banners();


$banner->set('cod', '825ba10028');
$bannerData = $banner->view();

#Información de cabecera
$template->set("title", "Comunidad Digital | " . TITULO);
$template->set("description", 'Sección donde vas a encontrar las últimas novedades de nuestras redes.');
$template->set("keywords", "");
$template->themeInit();
?>
<section class="page-title page-title-layout1 bg-overlay bg-parallax">
    <div class="bg-img"><img src="<?= URL . "/" . $bannerData['image']['ruta'] ?>" alt="background"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-5">
                <?php if ($bannerData['data']['subtitulo_on']) { ?>
                    <span class="pagetitle__subheading"><?= $bannerData['data']['subtitulo'] ?></span>
                <?php } ?>
                <?php if ($bannerData['data']['titulo_on']) { ?>
                    <h1 class="pagetitle__heading"><?= $bannerData['data']['titulo'] ?></h1>
                <?php } ?>
            </div><!-- /.col-xl-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.page-title -->
<!-- end page-title -->
<!-- start blog-with-sidebar -->
<section class="blog-with-sidebar section-padding">
    <div class="container">
        <div class="row">
            <div class="col col-lg-12 col-md-12">
                <!-- Place <div> tag where you want the feed to appear -->
                <div id="curator-feed-default-feed-layout"></div>
                <!-- The Javascript can be moved to the end of the html page before the </body> tag -->
                <script type="text/javascript">
                    /* curator-feed-default-feed-layout */
                    (function() {
                        var i, e, d = document,
                            s = "script";
                        i = d.createElement("script");
                        i.async = 1;
                        i.src = "https://cdn.curator.io/published/cb1e0516-d7c3-4b7d-abd3-8c4cf34fb110.js";
                        e = d.getElementsByTagName(s)[0];
                        e.parentNode.insertBefore(i, e);
                    })();
                </script>
            </div>
        </div>
    </div>
</section>
<?php
$template->themeEnd();
?>

<script>
    function traducir() {
        try {
            document.getElementsByClassName('crt-load-more')[0].children[0].innerText = 'Cargar más';
        } catch (err) {
            setTimeout(traducir, 1000);
        }
    }
    traducir();
</script>