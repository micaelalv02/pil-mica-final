<?php
$landing = new Clases\Landing();
$imagenes = new Clases\Imagenes();

$categorias = new Clases\Categorias();
$data = $categorias->list(["`categorias`.`area` = 'landing'"], '', '',$_SESSION['lang'],false,true);

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $landing->set("cod", $cod);
    $landing->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $landing->set("categoria", isset($_POST["categoria"]) ? $funciones->antihack_mysqli($_POST["categoria"]) : '');
    $landing->set("desarrollo", isset($_POST["desarrollo"]) ? $funciones->antihack_mysqli($_POST["desarrollo"]) : '');
    $landing->set("fecha", isset($_POST["fecha"]) ? $funciones->antihack_mysqli($_POST["fecha"]) : '');
    $landing->set("description", isset($_POST["description"]) ? $funciones->antihack_mysqli($_POST["description"]) : '');
    $landing->set("keywords", isset($_POST["keywords"]) ? $funciones->antihack_mysqli($_POST["keywords"]) : '');

    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link($_POST["titulo"]), $idiomasInputPost);
    }

    $landing->add();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=landing");
}
?>
<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Landing
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <label class="col-md-4">Título:<br />
                        <input type="text" name="titulo" required>
                    </label>
                    <label class="col-md-4">Categoría:<br />
                        <select name="categoria" required>
                            <?php
                            foreach ($data as $categoria) {
                                echo "<option value='" . $categoria['data']["cod"] . "'>" . $categoria['data']["titulo"] . "</option>";
                            }
                            ?>
                        </select>
                    </label>
                    <label class="col-md-4">Fecha:<br />
                        <input type="date" name="fecha">
                    </label>
                    <label class="col-md-12 mt-10">Desarrollo:<br />
                        <textarea name="desarrollo" class="ckeditorTextarea"></textarea>
                    </label>
                    <label class="col-md-12 mt-10">Palabras claves dividas por ,<br />
                        <input type="text" name="keywords">
                    </label>
                    <label class="col-md-12">Descripción breve<br />
                        <textarea name="description"></textarea>
                    </label>
                    <div class="col-md-12">
                        <hr style="border-style: dashed;">
                    </div>
                    <label class="col-md-7">Imágenes:<br />
                        <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                    </label>

                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Agregar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>