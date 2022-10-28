<?php
$idiomas = new Clases\Idiomas();
$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$idiomas->set("cod", $cod);
$data = $idiomas->view();

if (isset($_POST["editar"])) {
    $idiomas->set("cod", isset($_POST["cod"]) ? $funciones->antihack_mysqli($_POST["cod"]) : '');
    $idiomas->set("id", isset($_POST["id"]) ? $funciones->antihack_mysqli($_POST["id"]) : '');
    $idiomas->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $idiomas->set("default", isset($_POST["default"]) ? $funciones->antihack_mysqli($_POST["default"]) : '');
    if ($idiomas->edit()) {
        $funciones->headerMove(URL_ADMIN . "/index.php?op=idiomas");
    }
}
?>

<div class="mt-20 card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Idiomas
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row">
                    <input type="hidden" name="default" value="0">
                    <input type="hidden" name="id" value="<?= $data['data']['id'] ?>">
                    <label class="col-md-6">Título:<br />
                        <input type="text" value="<?= $data['data']["titulo"] ?>" name="titulo" required>
                    </label>
                    <label class="col-md-6">Codigo:<br />
                        <select name="cod">
                            <option value="es" <?= ($data["data"]["cod"] == "es") ? "selected" : "" ?>>Español</option>
                            <option value="en" <?= ($data["data"]["cod"] == "en") ? "selected" : "" ?>>Inglés</option>
                            <option value="de" <?= ($data["data"]["cod"] == "de") ? "selected" : "" ?>>Aleman</option>
                            <option value="pt" <?= ($data["data"]["cod"] == "pt") ? "selected" : "" ?>>Portugues</option>
                            <option value="it" <?= ($data["data"]["cod"] == "it") ? "selected" : "" ?>>Italiano</option>
                            <option value="fr" <?= ($data["data"]["cod"] == "fr") ? "selected" : "" ?>>Francés</option>
                            <option value="zh" <?= ($data["data"]["cod"] == "zh") ? "selected" : "" ?>>Chino</option>
                        </select>
                    </label>
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="editar" value="Modificar Idioma" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>