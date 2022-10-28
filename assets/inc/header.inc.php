<?php
$config = new Clases\Config();

#Se carga la configuración de marketing
$marketing = $config->viewMarketing();

#Se carga la configuración del header y se la muestra
$configHeader = $config->viewConfigHeader();
echo $configHeader["data"]["content_header"];
$captchaData = $config->viewCaptcha();

#Script One Signal
if (!empty($marketing['data']['onesignal'])) { ?>
    <link rel="manifest" href="/manifest.json" />
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "<?= $marketing["data"]["onesignal"] ?>",
            });
        });
    </script>
<?php }

#Script Google Analytics
if (!empty($marketing['data']['google_analytics'])) { ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-150839106-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '<?= $marketing["data"]["google_analytics"] ?>');
    </script>
<?php }

#Script Pixel Facebook
if (!empty($marketing['data']['facebook_pixel'])) { ?>
    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?= $marketing['data']['facebook_pixel'] ?>');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=<?= $marketing['data']['facebook_pixel'] ?>&ev=PageView
&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->

<?php }
#Script Hubspot
if (!empty($marketing['data']['hubspot'])) { ?>
    <!-- Start of HubSpot Embed Code -->
    <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/<?= $marketing['data']['hubspot'] ?>.js"></script>
    <!-- End of HubSpot Embed Code -->
<?php } ?>

<!-- Styles Theme -->

<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/fontawesome-all.min.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/bootstrap.min.css">
<link rel="shortcut icon" href="<?= URL ?>/assets/theme/assets/images/fav.png" type="image/x-icon">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/swiper.min.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/lightcase.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/odometer.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/icomoon.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/line-awesome.min.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/animate.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/aos.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/nice-select.css">
<link rel="stylesheet" href="<?= URL ?>/assets/theme/assets/css/style.css">
<!-- End Styles Theme -->


<!-- Styles CMS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" rel="stylesheet" />
<link rel="stylesheet" href="<?= URL ?>/assets/css/main-rocha.css">
<link rel="stylesheet" href="<?= URL ?>/assets/css/lightbox.css">
<link rel="stylesheet" href="<?= URL ?>/assets/css/estilos-rocha.css">
<link rel="stylesheet" href="<?= URL ?>/assets/css/select2.min.css">
<link rel="stylesheet" href="<?= URL ?>/assets/css/loading.css">
<link rel="stylesheet" href="<?= URL ?>/assets/css/auto-complete.css">
<link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/css/toastr.min.css">
<link href="<?= URL ?>/assets/css/progress-wizard.min.css" rel="stylesheet">
<!-- Fin Styles CMS -->

<link rel="stylesheet" href="<?= URL ?>/assets/css/custom-rocha.css">

<script src="https://www.google.com/recaptcha/api.js?render=<?= $captchaData['data']['captcha_key'] ?>"></script>
<script>
    window.captchaKey = '<?= $captchaData['data']['captcha_key'] ?>';
</script>