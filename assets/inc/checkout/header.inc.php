<?php
$config = new Clases\Config();

#Se carga la configuración de marketing
$marketing = $config->viewMarketing();

#Se carga la configuración del header y se la muestra 
$captchaData = $config->viewCaptcha(); 

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

<!-- Styles CMS -->
<script src="https://use.fontawesome.com/13c1d037b3.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<link rel="stylesheet" href="<?= URL ?>/assets/css/checkout/style.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="<?= URL ?>/assets/css/main-rocha.css" media="none" onload="if(media!='all')media='all'"> 
<link rel="stylesheet" href="<?= URL ?>/assets/css/estilos-rocha.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="<?= URL ?>/assets/css/select2.min.css" media="none" onload="if(media!='all')media='all'">
<link href="<?= URL ?>/assets/css/progress-wizard.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<!-- Fin Styles CMS -->

<script src="https://www.google.com/recaptcha/api.js?render=<?= $captchaData['data']['captcha_key'] ?>"></script>