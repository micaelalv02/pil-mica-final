<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$template = new Clases\TemplateSite();

$f = new Clases\PublicFunction();
$checkout = new Clases\Checkout();
$carrito = new Clases\Carrito();
$config = new Clases\Config();

$link = (!empty($_SESSION['usuarios'])) ? $checkout->checkSkip($_SESSION['usuarios']['minorista']) : '';

#Redireccionar al carrito si no se abrieron los stages (en carrito se abren los stages)
if (empty($_SESSION['stages']) || !($carrito->checkPaymentsLimits())) {
    $f->headerMove(URL . '/carrito');
} else {
    #Revisar si hay un usuario ya sea invitado o no
    if (!empty($_SESSION['usuarios'])) {
        #Si por alguna razón no se guardo el user_cod, se guarda
        if (empty($_SESSION['stages']['user_cod'])) {
            $checkout->user($_SESSION['usuarios']['cod'], 'USER');
            #Si ya tiene guardado el user_cod, se redirecciona a shipping
        } else {
            $f->headerMove(URL . "/" . $link);
        }
    }
}

#Variable que almacena el progeso de los stages
$progress = $checkout->progress();

$captchaData = $config->viewCaptcha();
#Información de cabecera
$template->set("title", 'Identificación | ' . TITULO);
$template->themeInitStages();

?>
<div class="checkout-estudiorocha">
    <?php
    if (!empty($_SESSION['stages'])) {
        if (empty($_SESSION['stages']['user_cod'])) {
    ?>
            <div class="container mt-40">
                <div class="row">
                    <div class="col-md-4 col-sm-12 ">
                        <div class="box mb-40 ">
                            <div class="text-center">
                                <?= $_SESSION["lang-txt"]["login"]["mensaje"] ?>
                                <br />
                                <i class="fa fa-arrow-down"></i>
                                <br />
                                <a href="<?= URL ?>/checkout/shipping" style="line-height: 2.333333;" class="fs-20 btn btn-primary btn-lg btn-block"><?= $_SESSION["lang-txt"]["login"]["comprar_invitado"] ?></a>
                            </div>
                            <hr />
                        </div>
                        <div class="box">
                            <div class="search-filter">
                                <div class="sidbar-widget pt-0">
                                    <h4 class="title fs-20"><?= $_SESSION["lang-txt"]["usuarios"]["ingresar"] ?></h4>
                                </div>
                            </div>
                            <div id="l-error"></div>
                            <form id="login" data-url="<?= URL ?>" data-link="<?= CANONICAL ?>" data-type="stages" data-captcha="<?= $captchaData["data"]["captcha_key"]  ?>">
                                <input name="captcha-response" type="hidden" value="">
                                <input class="form-control" type="hidden" name="stg-l" value="1">
                                <div class="form-fild">
                                    <input class="form-control" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["email"] ?>" name="l-user" value="" type="email" required>
                                </div>
                                <div class="form-fild mt-20 ">
                                    <input class="form-control" placeholder="<?= $_SESSION["lang-txt"]["usuarios"]["password"] ?>" name="l-pass" id="l-pass" value="" type="password" required>
                                </div>
                                <div id="btn-l" class="login-submit mt-20 mb-10">
                                    <button class="btn btn-secondary btn-lg g-recaptcha" data-sitekey="<?= $captchaData["data"]["captcha_key"] ?>" data-callback='loginUser'>INGRESAR</button>
                                </div>
                                <div class="lost-password">
                                    <a href="<?= URL ?>/recuperar"><?= $_SESSION["lang-txt"]["usuarios"]["olvidaste_password"] ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <hr class="hidden-md-up" />
                        <div class="box mb-40">
                            <div class="search-filter">
                                <div class="sidbar-widget pt-0">
                                    <h4 class="title"><?= $_SESSION["lang-txt"]["usuarios"]["registro"] ?></h4>
                                </div>
                            </div>
                            <div id="r-error"></div>
                            <form id="register" data-url="<?= URL ?>" data-link="<?= CANONICAL ?>" data-type="stages" data-captcha="<?= $captchaData["data"]["captcha_key"]  ?>" onsubmit="registerUser()">
                                <input name="captcha-response" type="hidden" value="">
                                <input class="form-control" type="hidden" name="stg-l" value="1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-fild mb-12">
                                            <span><label><?= $_SESSION["lang-txt"]["usuarios"]["nombre"] ?> <span class="required">*</span></label></span>
                                            <input class="form-control" name="firstname" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild mb-12">
                                            <span><label><?= $_SESSION["lang-txt"]["usuarios"]["apellido"] ?> <span class="required">*</span></label></span>
                                            <input class="form-control" name="lastname" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-fild mb-12">
                                            <span><label><?= $_SESSION["lang-txt"]["usuarios"]["email"] ?> <span class="required">*</span></label></span>
                                            <input class="form-control" name="email" value="" type="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild mb-12">
                                            <span><label><?= $_SESSION["lang-txt"]["usuarios"]["password"] ?> <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-password1" value="" type="password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild mb-12">
                                            <span><label><?= $_SESSION["lang-txt"]["usuarios"]["re_password"] ?> <span class="required">*</span></label></span>
                                            <input class="form-control" name="r-password2" value="" type="password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild mb-12">
                                            <span><label><?= $_SESSION["lang-txt"]["usuarios"]["telefono"] ?> <span class="required">*</span></label></span>
                                            <input class="form-control" name="phone" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-fild mb-12">
                                            <span><label><?= $_SESSION["lang-txt"]["usuarios"]["celular"] ?> <span class="required">*</span></label></span>
                                            <input class="form-control" name="mobilephone" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-fild mb-12">
                                            <span><label for="provincia"><?= $_SESSION["lang-txt"]["usuarios"]["provincia"] ?><span class="required">*</span></label></span>
                                            <select id='provincia' data-url="<?= URL ?>" class="form-control mb-3" name="provincia" required>
                                                <option value="" selected><?= $_SESSION["lang-txt"]["usuarios"]["provincia"] ?></option>
                                                <?php $f->provincias(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <span><label for="localidad"><?= $_SESSION["lang-txt"]["usuarios"]["localidad"] ?><span class="required">*</span></label></span>
                                        <select id='localidad' class="form-control mb-3" name="city" required>
                                            <option value="" selected><?= $_SESSION["lang-txt"]["usuarios"]["localidad"] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-fild mb-12">
                                            <span><label><?= $_SESSION["lang-txt"]["usuarios"]["direccion"] ?> <span class="required">*</span></label></span>
                                            <input class="form-control" name="address" value="" type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div id="btn-r" class="register-submit mt-10 mb-10">
                                    <button class="g-recaptcha btn btn-secondary btn-lg" data-sitekey="<?= $captchaData["data"]["captcha_key"] ?>" data-callback='registerUser'><?= $_SESSION["lang-txt"]["usuarios"]["registro"] ?></button>
                                </div>
                            </form>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
    <?php
        } else {
            $f->headerMove(URL . "/" . $link);
        }
    } else {
        $f->headerMove(URL . '/carrito');
    }
    ?>
</div>
<?php
$template->themeEndStages();
?>