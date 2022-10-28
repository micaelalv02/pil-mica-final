<?php
$idiomas = new Clases\Idiomas();
$f = new Clases\PublicFunction();

$cod = isset($_GET["cod"]) ? $funciones->antihack_mysqli($_GET["cod"]) : '';
$data = $idiomas->list();

if (isset($_POST["duplicar"])) {
    if (isset($_POST["idiomaBase"]) && isset($_POST["idiomas"]) && (isset($_POST["productos"]) || isset($_POST["contenidos"]) || isset($_POST["ecommerce"]))) {
        $idiomaBase = $f->antihack_mysqli($_POST["idiomaBase"]);
        $idiomasSeleccionados = $f->antihackMulti($_POST["idiomas"]);
        $table = [];
        $where = '';
        if (isset($_POST["productos"])) {
            $table = array_merge($table, ["categorias", "subcategorias","tercercategorias",   "atributos", "combinaciones", "detalle_combinaciones", "productos"]);
            $where = "AND area = 'productos'";
        }
        if (isset($_POST["contenidos"])) {
            $table = array_merge($table, ["area", "categorias", "subcategorias","tercercategorias", "banners", "contenidos",  "menu", "seo"]);
            $where = "AND area != 'productos'";
            if (isset($_POST["productos"])) $where = '';
        }
        if (isset($_POST["ecommerce"])) {
            $table = array_merge($table, ["estados_pedidos", "envios", "pagos"]);
        }
        if (isset($_POST["productos"]) || isset($_POST["contenidos"])) {
            $table = array_merge($table, ["imagenes"]);
        }
        $f->duplicate($table, $idiomaBase, $idiomasSeleccionados, $where);
    }
}
?>
<style>
    .imgGris {
        filter: grayscale(1);
    }
</style>
<div class="mt-20 card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Duplicar por Idiomas
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" class="row"  onsubmit="$('#loader').show()">
                    <label class="col-md-4">Idioma desde el cual duplicar:<br />
                        <select name="idiomaBase" onchange="displayNone('idioma-'+$(this).val());">
                            <option selected>---- SELECCIONA UN IDIOMA ----</option>
                            <?php foreach ($data as $idiomaItem) { ?>
                                <option value="<?= $idiomaItem["data"]["cod"] ?>"><?= $idiomaItem["data"]["titulo"] ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="col-md-8">A los idiomas:<br />
                        <?php
                        foreach ($data as $idiomaItem) {
                            $id = "idioma-" . $idiomaItem['data']['cod'];
                        ?>
                            <label onclick="changeStyle('<?= $id ?>')" for="<?= $id ?>" class="fs-14 text-uppercase idioma" id="label<?= $id ?>">
                                <img id="img<?= $id ?>" class="imgGris" src="<?= URL_ADMIN ?>/img/idiomas/<?= $idiomaItem["data"]["cod"] ?>.png" width="40" />
                                <input id="<?= $id ?>" class="hidden" value="<?= $idiomaItem['data']['cod'] ?>" name="idiomas[]" type="checkbox">
                                <?= $idiomaItem['data']['titulo'] ?>
                            </label>
                        <?php } ?>
                    </label>
                    <div class="col-md-12">
                        <hr>
                        <h5>Selecciona los datos que deseas duplicar</h5>
                        <hr>
                        <label for="productos" class="fs-14 text-uppercase">
                            <input id="productos" value="productos" name="productos" type="checkbox">
                            Productos/Categorias/Subcategorias/Atributos/Combinaciones/detalle_combinaciones
                        </label><br>
                        <label for="contenidos" class="fs-14 text-uppercase">
                            <input id="contenidos" value="contenidos" name="contenidos" type="checkbox">
                            Banners/Contenidos/Area/Categorias/Subcategorias/Menu/Seo
                        </label><br>
                        <label for="ecommerce" class="fs-14 text-uppercase">
                            <input id="ecommerce" value="ecommerce" name="ecommerce" type="checkbox">
                            Metodos de envio/metodos de pago/estados de pedido
                        </label>
                    </div>
                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" name="duplicar" value="Duplicar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function changeStyle(id) {
        let check = $('input[id=' + id + ']:checked');
        if (check.length == 0) {
            $('#img' + id).addClass('imgGris');
        } else {
            $('#img' + id).removeClass('imgGris');
        }
    }
    function displayNone(id) {
        console.log(id);
        $('.idioma').removeClass('hidden');
        $('#label' + id).addClass('hidden');
    }
</script>