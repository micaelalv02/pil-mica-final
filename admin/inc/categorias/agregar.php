 <?php
    $categoria = new Clases\Categorias();
    $area = new Clases\Area();
    $imagen = new Clases\Imagenes();
    $idiomas = new Clases\Idiomas();
    $funciones = new Clases\PublicFunction();

    $cod = substr(md5(uniqid(rand())), 0, 10);
    $idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];
    $areas = $area->list([], "", "", $idiomaGet);
    $idiomasData = $idiomas->list(["cod != '$idiomaGet'"], "", "");
    if (isset($_POST["agregar"])) {
        unset($_POST["agregar"]);
        if (isset($_POST["idiomasInput"])) {
            $idiomasInputPost =  $_POST["idiomasInput"];
            $idiomasInputPost[] = $idiomaGet;
        } else {
            $idiomasInputPost = [$idiomaGet];
        }
        unset($_POST["idiomasInput"]);
        $cod = $funciones->antihack_mysqli($_POST["cod"]);
        $array = $funciones->antihackMulti($_POST);
        if (isset($idiomasInputPost) && !empty($idiomasInputPost)) {
            foreach ($idiomasInputPost as $idiomasInputItem) {
                $array["idioma"] = $idiomasInputItem;
                $categoria->add($array);
            }
        }

        if (!empty($_FILES['files']['name'][0])){
            $imagen->resizeImages($cod, $_FILES['files'], "assets/archivos/recortadas", $funciones->normalizar_link($array["titulo"]), $idiomasInputPost);
        }
        $funciones->headerMove(URL_ADMIN . "/index.php?op=categorias&accion=ver&idioma=$idiomaGet");
    }
    ?>

 <div class="mt-20 ">
     <div class="card">
         <div class="card-header">
             <h4 class="card-title text-uppercase text-center">
                 Categorías
             </h4>
             <hr style="border-style: dashed;">
         </div>
         <div class="card-content">
             <div class="card-body">
                 <form method="post" class="row" enctype="multipart/form-data">
                     <input type="hidden" name="idioma" value="<?= $idiomaGet ?>">
                     <label class="col-md-4">Código:<br />
                         <input type="text" name="cod" value="<?= $cod ?>" required>
                     </label>
                     <label class="col-md-4">Título:<br />
                         <input type="text" name="titulo" required>
                     </label>
                     <label class="col-md-4">Área:<br />
                         <select name="area" required>
                             <option value="" disabled selected>-- categorías --</option>
                             <?php
                                if (isset($areas)) {
                                    foreach ($areas as $areaItem) { ?>
                                     <option value="<?= $areaItem['data']['cod'] ?>"><?= ucwords($areaItem['data']['titulo']) ?></option>
                             <?php }
                                }
                                ?>
                             <option value="banners">Banners</option>
                             <option value="productos">Productos</option>
                             <option value="landing">Landing</option>
                             <option value="menu">Menu</option>
                             <option value="opciones">Opciones</option>
                         </select>
                     </label>
                     <label class="col-md-12 mt-10">Descripción:<br />
                         <textarea class="form-control" name="descripcion"></textarea>
                     </label>
                     <div class="col-md-12">
                         <hr style="border-style: dashed;">
                     </div>
                     <label class="col-md-12">Imágenes:<br />
                         <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
                     </label>
                     <div class="clearfix"></div>
                     <?php if (count($idiomasData) >= 1) { ?>
                         <div class="col-md-12">
                             <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar categoria en otros idiomas</div>
                             <div id="idiomasCheckBox">
                                 <?php foreach ($idiomasData as $idiomaItem) { ?>
                                     <div class="ml-10">
                                         <label for="idioma<?= $idiomaItem['data']['cod'] ?>">
                                             <input type="checkbox" name="idiomasInput[]" value="<?= $idiomaItem['data']['cod'] ?>" id="idioma<?= $idiomaItem['data']['cod'] ?>"> <?= $idiomaItem['data']['titulo'] ?>
                                         </label>
                                     </div>
                                 <?php } ?>
                             </div>
                         </div>
                     <?php } ?>
                     <div class="col-md-12 mt-20">
                         <input type="submit" class="btn btn-primary btn-block" name="agregar" value="Crear Categoría" />
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <script>
     $('#idiomasCheckBox').hide();
 </script>