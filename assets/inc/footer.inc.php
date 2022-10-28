<?php

$contenidos = new Clases\Contenidos();

$footerData = $contenidos->list(["images" => true, "filter" => ["contenidos.area='piedepagina'"]], $_SESSION['lang'], false);

?>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Footer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<footer class="footer-section two">
  <div class="footer-element-three">
    <img src="<?= $footerData["piedepagina-footer"]["images"][0]["url"] ?>" alt="element">
  </div>
  <div class="footer-element-seven two">
    <img src="<?= $footerData["piedepagina-footer"]["images"][1]["url"] ?>" alt="element">
  </div>
  <div class="footer-element-eight">
    <img src="<?= $footerData["piedepagina-footer"]["images"][2]["url"] ?>" alt="element">
  </div>
  <div class="footer-area ptb-120">
    <div class="footer-area-element">
      <img src="<?= $footerData["piedepagina-footer"]["images"][3]["url"] ?>" alt="element">
    </div>
    <div class="container">
      <div class="footer-top-area">
        <div class="row mb-30-none">
          <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
            <div class="footer-widget">
              <ul class="footer-contact-list">
                <li>
                  <span class="sub-title"><?= $footerData["contacto-footer"]["data"]["subtitulo"] ?></span>
                  <h4 class="link-title"><a href="tel:0369569032"><?= $footerData["contacto-footer"]["data"]["titulo"] ?></a></h4>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
            <div class="footer-widget">
              <ul class="footer-contact-list">
                <li>
                  <span class="sub-title"><?= $footerData["escribinos-footer"]["data"]["subtitulo"] ?></span>
                  <h4 class="link-title"><a href="mailto:"><?= $footerData["escribinos-footer"]["data"]["titulo"] ?></a></h4>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
            <div class="footer-widget">
              <ul class="footer-contact-list">
                <li>
                  <span class="sub-title"><?= $footerData["horarios-footer"]["data"]["subtitulo"] ?></span>
                  <h4 class="link-title"><?= $footerData["horarios-footer"]["data"]["titulo"] ?></h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom-area">
        <div class="row mb-30-none">
          <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
            <div class="footer-widget">
              <h5><?= $footerData["about-footer"]["data"]["titulo"] ?></h5>
              <p><?= $footerData["about-footer"]["data"]["contenido"] ?></p>
              <ul class="footer-social">
                <li><a href="#0"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#0"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#0"><i class="fab fa-google-plus-g"></i></a></li>
                <li><a href="#0"><i class="fab fa-instagram"></i></a></li>
              </ul>
            </div>
          </div>
          <!-- FALTA EDITAR-->
          <div class="col-xl-2 col-lg-2 col-md-6 mb-30">
            <div class="footer-widget">
              <h4 class="title">Explore Softim</h4>
              <ul class="footer-list">
                <li><a href="#0">Account</a></li>
                <li><a href="#0">Privacy Policy</a></li>
                <li><a href="#0">Affilitate</a></li>
                <li><a href="#0">Program</a></li>
                <li><a href="#0">Our Partner</a></li>
              </ul>
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-6 mb-30">
            <div class="footer-widget">
              <h5 class="title">Quick Links</h5>
              <ul class="footer-list">
                <li><a href="#0">Account</a></li>
                <li><a href="#0">Privacy Policy</a></li>
                <li><a href="#0">Affilitate</a></li>
                <li><a href="#0">Program</a></li>
                <li><a href="#0">Our Partner</a></li>
              </ul>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
            <div class="footer-widget">
              <h5 class="title">Sign up Newsletter</h5>
              <form class="footer-subscribe-form">
                <input type="email" class="form--control" placeholder="Enter Mail">
                <button type="submit"><i class="las la-angle-right"></i></button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright-wrapper two">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-12 text-center">
          <div class="copyright-area">
            <div class="footer-logo">
              <a class="site-logo site-title" href="index.html"><img src="<?= $footerData["piedepagina-footer"]["images"][4]["url"] ?>" alt="site-logo"></a>
            </div>
            <p><?= $footerData["piedepagina-footer"]["data"]["contenido"] ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Footer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->




<!-- scripts template, no cambiar -->
<script src="<?= URL ?>/assets/theme/assets/js/jquery-3.6.0.min.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/bootstrap.min.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/swiper.min.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/lightcase.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/odometer.min.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/viewport.jquery.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/aos.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/jquery.nice-select.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/isotope.pkgd.min.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/TweenMax.min.js"></script>
<script src="<?= URL ?>/assets/theme/assets/js/main.js"></script>
<!-- fin scripts template, POR FAVOR, no cambiar -->


<!-- Scripts CMS -->
<script src="<?= URL ?>/assets/js/services/lang.js"></script>
<script src="<?= URL ?>/assets/js/lightbox.js"></script>
<script src="<?= URL ?>/assets/js/jquery-ui.min.js"></script>
<script src="<?= URL ?>/assets/js/select2.min.js"></script>
<script src="<?= URL ?>/assets/js/bootstrap-notify.min.js"></script>
<script src="<?= URL ?>/assets/js/toastr.min.js"></script>
<script src="<?= URL ?>/assets/js/services/services.js"></script>
<script src="<?= URL ?>/assets/js/services/email.js"></script>
<script src="<?= URL ?>/assets/js/services/search.js"></script>
<script src="<?= URL ?>/assets/js/services/products.js"></script>
<script src="<?= URL ?>/assets/js/services/user.js"></script>
<script src="<?= URL ?>/assets/js/services/cart.js"></script>
<script src="<?= URL ?>/assets/js/sticky/sticky-sidebar.min.js"></script>
<!-- Fin Scripts CMS -->