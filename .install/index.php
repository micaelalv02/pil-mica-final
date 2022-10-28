<?php
if (!@file_get_contents('../.env')) {
    file_put_contents('../.env', file_get_contents('../.env_example'));
}
require_once "../Config/Autoload.php";
Config\Autoload::run(); 
$template = new Clases\TemplateAdmin();
$admin = new Clases\Admin();
$f = new Clases\PublicFunction();

$template->set("title", "Instalar Sitio Web");
$step = isset($_GET["step"]) ? $_GET["step"] : 1;
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="style.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


<body class="register">
    <div class="container ">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="logo.svg" />
                <h3>Bienvenido</h3>
                <p>Configura tu sitio en menos de 3 pasos</p>
                <a href="<?= URL_ADMIN ?>" class="btn btn-light">IR AL ADMINISTRADOR</a>
            </div>
            <div class="col-md-9 register-right">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active   " style="padding:50px;" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <?php
                        include("steps/step" . $step . ".php");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>