<?php
$f = new Clases\PublicFunction();
$path = dirname(__DIR__, "2") . '/.env';
if (file_exists($path)) {
    $env_file = file_get_contents($path);
    if (isset($_POST["submit"])) {
        foreach ($_POST as $key => $value) {
            if (strpos($env_file, $key)) {
                $env_file = str_replace($key . '="' . $_ENV[$key] . '"', $key . '="' . $value . '"', $env_file);
            }
        }
        if (file_put_contents($path, $env_file)) {
            $f->headerMove($_POST["PROTOCOL"] . "://" . $_SERVER['HTTP_HOST'] . $_POST["PROJECT"] . "/.install/index.php?step=2");
        }
    }
?>
    <div class="container text-center">
        <h2>
            Configurar Entorno de Desarrollo
            <hr />
        </h2>

        <?php if (isset($_GET["error"])) echo "<div class='alert alert-danger'>Ups! El sistema no puede iniciar conectarse a la base de datos</div>" ?>


        <form class="row" method="post">
            <?php
            foreach ($_ENV as $key => $value) {
                if (strpos($env_file, $key)) {
            ?>
                    <div class="col-4 mb-3">
                        <label for="<?= $key ?>" class="form-label"><b><?= $key ?></b></label><br />
                        <input class="form-control" type="text" name="<?= $key ?>" id="<?= $key ?>" value="<?= $value ?>">
                    </div>
            <?php
                }
            }
            ?>
            <input type="submit" name="submit" class="btn btn-success mt-4" value="Siguiente Paso >" />
        </form>
    </div>
<?php
}
?>