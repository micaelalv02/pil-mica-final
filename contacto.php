<?php
require_once "Config/Autoload.php";
Config\Autoload::run();

$template = new Clases\TemplateSite();
$f = new Clases\PublicFunction();
$contenidos = new Clases\Contenidos();
$config = new Clases\Config();

$captchaData = $config->viewCaptcha();
#Se carga la configuración de email
$data = [
    "filter" => ['contenidos.area = "contacto"'],
];
$contenidoContacto = $contenidos->list($data, $_SESSION['lang']);
#Información de cabecera
$template->set("title", "Contacto | " . TITULO);
$template->set("description", "Envianos tus dudas y nosotros te asesoramos");
$template->set("keywords", "");
$template->themeInit();
?>
<div class="page-title-area pt-150 pb-55">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-titel-detalis  ">
                    <div class="section-title">
                        <h2><?= $_SESSION["lang-txt"]["general"]["contacto"] ?></h2>
                    </div>
                    <div class="page-bc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= URL ?>"><?= $_SESSION["lang-txt"]["general"]["inicio"] ?></a></li>
                                <li class="breadcrumb-item position-relative active" aria-current="page"><a href="<?= CANONICAL ?>"><?= $_SESSION["lang-txt"]["general"]["contacto"] ?></a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="contact_us pt-110">
    <div class="container">
        <?= $contenidoContacto["info"]["data"]["contenido"] ?>
    </div>
</div>
<div class="map-area">
    <div class="container">
        <?= $contenidoContacto["mapa"]["data"]["contenido"] ?>
    </div>
</div>

<div class="contact-form-area pt-95 pb-20">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9 col-md-10">
                <div class="contact-title text-center">
                    <h3><?= $_SESSION["lang-txt"]["contacto"]["formulario_contacto"] ?></h3>
                </div>
            </div>
        </div>
        <div id="response"></div>
        <form class="contact-form-style" id="contactForm" data-url="<?= URL ?>" data-captcha="<?= $captchaData["data"]["captcha_key"] ?>" method="post">
            <div class="row">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-input">
                        <input name="nombre" id="f-name" placeholder="<?= $_SESSION['lang-txt']['usuarios']['nombre'] ?>*" type="text" required />
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-input">
                        <input name="apellido" id="l-name" placeholder="<?= $_SESSION['lang-txt']['usuarios']['apellido'] ?>*" type="text" required />
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-input">
                        <input name="email" id="email" placeholder="<?= $_SESSION['lang-txt']['usuarios']['email'] ?>*" type="email" required />
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-input">
                        <input id="phone" name="telefono" placeholder="<?= $_SESSION['lang-txt']['usuarios']['telefono'] ?>" type="number" required />
                    </div>
                </div>
                <div class="col-lg-12" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-input">
                        <textarea name="mensaje" style="border-radius: 5px;" id="message" placeholder="<?= $_SESSION['lang-txt']['usuarios']['mensaje'] ?>*" required></textarea>
                    </div>
                    <input name="captcha-response" type="hidden" value="">
                    <button class="g-recaptcha p-btn border-0 mt-75" id="sendContact" data-aos="fade-up" data-aos-delay="200" data-sitekey="<?= $captchaData["data"]["captcha_key"] ?>" data-callback="sendContact" data-action='submit' name="enviar"><?= $_SESSION['lang-txt']['contacto']['enviar'] ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $template->themeEnd() ?>