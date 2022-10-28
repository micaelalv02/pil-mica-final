<?php
$productos_relacionados = new Clases\ProductosRelacionados();
//agregar
$idiomaGet = isset($_GET["idioma"]) ? $funciones->antihack_mysqli($_GET["idioma"]) : $_SESSION['lang'];

if (isset($_POST["agregar"])) {
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $productos_relacionados->set("cod", $cod);
    $productos_relacionados->set("titulo", isset($_POST["titulo"]) ?  $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $productos_relacionados->set("productos_cod", isset($_POST["productTags"]) ?  $funciones->antihack_mysqli($_POST["productTags"]) : '');
    $productos_relacionados->set("idioma", $idiomaGet);
    $productos_relacionados->add();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=productos-relacionados&accion=ver&idioma=$idiomaGet");
}
?>
<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                CREAR RELACIONES DE PRODUCTOS
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" id="form-productos_relacionados" class="row" enctype="multipart/form-data">
                    <label class="col-md-3">Titulo de la relación
                        <label class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><b>T</b></span>
                                </div>
                                <input name="titulo" type="text" class="titulo" required>
                            </div>
                        </label>
                    </label>

                    <label class="col-md-3">Buscar productos:
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" list="productList" id="product">
                        </div>
                        <datalist id="productList"></datalist>
                    </label>
                    <label class="col-md-6" style="margin-top:18px">
                        <input data-beautify="false" name="productTags" type="text" class="productTags">
                    </label>

                    <div class="col-md-12 mt-20">
                        <input type="submit" class="btn btn-primary btn-block" id="agregar" name="agregar" value="Crear Relación de productos" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {

        $('.productTags').tokenfield();

        var productTags = [];

        $("#product").keyup(function() {
            if ($(this).val().length == 3) {
                $("#productList").empty();
                refreshProductData($(this).val());
                console.log($(this).val());
            }

            $('#productList option').each(function() {
                if ($(this).val() == $("#product").val()) {
                    $("#product").val('');
                    productTags = $('.productTags').tokenfield('getTokens');
                    productTags.push({
                        value: $(this).val(),
                        label: $(this).attr('label')
                    });
                    $('.productTags').tokenfield('setTokens', productTags);
                }
            });

        });

        function refreshProductData(currentValue) {
            let url = '<?= URL_ADMIN ?>';
            $.ajax({
                url: url + "/api/productos/related-product.php",
                type: "POST",
                data: {
                    string: currentValue,
                    lang: '<?= $idiomaGet ?>'
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data['status'] == true) {
                        $.each(data.productos, function(index, value) {
                            $("#productList").append('<option value="' + value.data.cod_producto + '" label="' + value.data.titulo + ' | ' + value.data.cod_producto + '">');
                        });

                    } else {
                        console.log('error');
                    }
                }
            });
        }
    });
</script>