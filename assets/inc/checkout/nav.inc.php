<?php
$config = new Clases\Config();
$captchaData = $config->viewCaptcha();
?>
<link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/css/pickers/pickadate/pickadate.css">
<link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/css/pickers/daterange/daterangepicker.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

<div class="g-recaptcha" data-sitekey="<?= $captchaData['data']['captcha_key'] ?>" data-callback="onSubmit" data-size="invisible"></div>

<div class="container-fluid">
    <img class="hidden-sm-down mt-20" width="20%" style="max-width:100px" src="<?= LOGO ?>">
    <div class="text-center">
        <img class="hidden-md-up mt-20 text-center" width="100px" src="<?= LOGO ?>">
    </div>
    <?php
    $op = isset($_GET["op"]) ? $_GET["op"] : '';
    if ($op != 'shipping' && CANONICAL != URL . "/login") {
    ?>
        <h3 class="pull-right fs-20 mt-35 hidden-md-down"><span><?= $_SESSION["lang-txt"]["checkout"]["navbar"]["pedido"] ?></span> Nº<?= $_SESSION["last_cod_pedido"] ?></h3>
        <h3 class="text-center fs-20 mt-35 mb-35 hidden-md-up"><span><?= $_SESSION["lang-txt"]["checkout"]["navbar"]["pedido"] ?></span> Nº<?= $_SESSION["last_cod_pedido"] ?></h3>
    <?php  } ?>
</div>
<hr />