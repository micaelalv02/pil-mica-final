<?php
$descuento = new Clases\Descuentos();
$productos = new Clases\Productos();
$categorias = new Clases\Categorias();
$carrito = new Clases\Carrito();
$idiomas = new Clases\Idiomas();

$idiomaGet = isset($_GET['idioma']) ? $funciones->antihack_mysqli($_GET['idioma']) : '';
$idiomasData = $idiomas->list(["cod != '$idiomaGet'"], "", "");
//lista de categorias y subcategorias
$categorias->idioma = $idiomaGet;
$categoriasArray = $categorias->listForDiscount();
$subcategoriasArray = [];
if ($categoriasArray) {
    foreach ($categoriasArray as $categoria) {
        foreach ($categoria["subcategories"] as $subcategoria) {
            $subcategoria["categoriaTitulo"] = $categoria['data']['titulo'];
            $subcategoriasArray[] = $subcategoria;
        }
    }
}
$categoriasArrayJson = json_encode(["status" => true, "category" => $categoriasArray]);
$subcategoriasArrayJson = json_encode(["status" => true, "subcategory" => $subcategoriasArray]);

//agregar
if (isset($_POST["agregar"])) {
    $descuento->set("cod", isset($_POST["cod"]) ? $funciones->antihack_mysqli($_POST["cod"]) : '');
    $descuento->set("titulo", isset($_POST["titulo"]) ? $funciones->antihack_mysqli($_POST["titulo"]) : '');
    $descuento->set("tipo", isset($_POST["tipo"]) ? $funciones->antihack_mysqli($_POST["tipo"]) : '');
    $descuento->set("monto", isset($_POST["monto"]) ? $funciones->antihack_mysqli($_POST["monto"]) : '');
    $descuento->set("productos_cod", isset($_POST["productTags"]) ? $funciones->antihack_mysqli($_POST["productTags"]) : '');
    $descuento->set("categorias_cod", isset($_POST["categoryTags"]) ? $funciones->antihack_mysqli($_POST["categoryTags"]) : '');
    $descuento->set("subcategorias_cod", isset($_POST["subcategoryTags"]) ? $funciones->antihack_mysqli($_POST["subcategoryTags"]) : '');
    $descuento->set("sector", isset($_POST["sector"]) ? $funciones->antihack_mysqli($_POST["sector"]) : '');
    $descuento->set("fecha_inicio", isset($_POST["fecha-inicio"]) ? $funciones->antihack_mysqli($_POST["fecha-inicio"]) : '');
    $descuento->set("fecha_fin", isset($_POST["fecha-fin"]) ? $funciones->antihack_mysqli($_POST["fecha-fin"]) : '');
    $descuento->set("todosProductos", isset($_POST["todos-productos"]) ? $funciones->antihack_mysqli($_POST["todos-productos"]) : 0);
    $descuento->set("todasCategorias", isset($_POST["todas-categorias"]) ? $funciones->antihack_mysqli($_POST["todas-categorias"]) : 0);
    $descuento->set("todasSubcategorias", isset($_POST["todas-subcategorias"]) ? $funciones->antihack_mysqli($_POST["todas-subcategorias"]) : 0);
    $descuento->acumular =  isset($_POST["acumular"]) ? $funciones->antihack_mysqli($_POST["acumular"]) : 0;
    $descuento->set("idioma", $idiomaGet);
    $descuento->add();

    if (isset($_POST["idiomasInput"])) {
        foreach ($_POST["idiomasInput"] as $idioma_) {
            $descuento->set("idioma", $idioma_);
            $descuento->add();
        }
    }
    $funciones->headerMove(URL_ADMIN . "/index.php?op=descuentos&accion=ver&idioma=$idiomaGet");
}
?>
<div class="mt-20 ">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title text-uppercase text-center">
                Descuentos
            </h4>
            <hr style="border-style: dashed;">
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="post" id="form-descuento" class="row" enctype="multipart/form-data">
                    <label class="col-md-4">Titulo:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><b>T</b></span>
                            </div>
                            <input type="text" name="titulo" value="" required>
                        </div>
                    </label>
                    <label class="col-md-4">Código descuento:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-star"></i></span>
                            </div>
                            <input type="text" name="cod" value="" required>
                        </div>
                    </label>
                    <label class="col-md-2">Tipo:<br />
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect"><b>$ / %</b></label>
                            </div>
                            <select name="tipo" class="custom-select" id="inputGroupSelect" required>
                                <option></option>
                                <option value="0">Efectivo</option>
                                <option value="1">Porcentaje</option>
                            </select>
                        </div>
                    </label>
                    <label class="col-md-2">Monto:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><b>M</b></span>
                            </div>
                            <input type="number" name="monto" value="" required>
                        </div>
                    </label>
                    <label class="col-md-4">Aplica a:<br />
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect"><i class="fas fa-users"></i></label>
                            </div>
                            <select name="sector" class="custom-select" id="inputGroupSelect">
                                <option selected disabled>Seleccionar...</option>
                                <option value="0">Todos los usuarios</option>
                                <option value="1">Solo a usuarios que no posean descuento</option>
                                <option value="2">Solo a usuarios que ya posean descuento</option>
                            </select>
                        </div>
                    </label>
                    <label class="col-md-4">Desde:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="date" name="fecha-inicio" value="">
                        </div>
                    </label>
                    <label class="col-md-4">Hasta:<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="date" name="fecha-fin" value="">
                        </div>
                    </label>
                    <label class="col-md-5">Buscar productos: (<input type="checkbox" value="1" name="todos-productos"> Todos los productos)<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" list="productList" id="product">
                        </div>
                        <datalist id="productList"></datalist>
                    </label>
                    <label class="col-md-12">
                        <input data-beautify="false" name="productTags" type="text" class="productTags">
                    </label>
                    <label class="col-md-5 mt-10">Buscar categorias: (<input type="checkbox" value="1" name="todas-categorias"> Todas las categorias)<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" list="categoryList" id="category">
                        </div>
                        <datalist id="categoryList"></datalist>
                    </label>
                    <label class="col-md-12">
                        <input data-beautify="false" name="categoryTags" type="text" class="categoryTags">
                    </label>
                    <label class="col-md-5 mt-10">Buscar subcategorias: (<input type="checkbox" value="1" name="todas-subcategorias"> Todas las subcategorias)<br />
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" list="subcategoryList" id="subcategory">
                        </div>
                        <datalist id="subcategoryList"></datalist>
                    </label>
                    <label class="col-md-12">
                        <input data-beautify="false" name="subcategoryTags" type="text" class="subcategoryTags">
                    </label>
                    <div class="col-md-12 mt-10">
                        <div class="custom-control custom-switch custom-switch-glow ml-10 ">
                            <span class="invoice-terms-title"> Aplicar descuento acumulable</span>
                            <input name="acumular" type="checkbox" id="acumular" class="custom-control-input" value="1">
                            <label class="custom-control-label" for="acumular">
                            </label>
                            <i class="fs-14 d-block text-normal" style="color: red">* Al seleccionar esta opción el descuento ejecutará beneficios extras si el producto ya posee descuentos, es decir que acumulará más descuentos.</i>
                        </div>
                    </div>
                    <?php if (count($idiomasData) >= 1) { ?>
                        <div class="col-md-12">
                            <div class="btn btn-primary mt-10 mb-10" onclick="$('#idiomasCheckBox').show()">Republicar descuento en otros idiomas</div>
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
                        <input type="submit" class="btn btn-primary btn-block" id="agregar" name="agregar" value="Crear Descuento" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#idiomasCheckBox').hide();

    $(function() {
        $('.productTags').tokenfield();
        $('.categoryTags').tokenfield();
        $('.subcategoryTags').tokenfield();

        var productTags = [];
        var categoryTags = [];
        var subcategoryTags = [];

        $("#product").keyup(function() {
            if ($(this).val().length == 3) {
                $("#productList").empty();
                refreshProductData($(this).val());
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
                url: url + "/api/descuentos/productos.php",
                type: "POST",
                data: {
                    string: currentValue
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data['status'] == true) {
                        $.each(data.productos, function(index, value) {
                            $("#productList").append('<option value="' + value.data.cod + '" label="' + value.data.titulo + ' | ' + value.data.cod_producto + '">');
                        });

                    } else {
                        console.log('error');
                    }
                }
            });
        }

        $("#category").keyup(function() {
            if ($(this).val().length == 3) {
                $("#categoryList").empty();
                refreshCategoryData();
            }

            $('#categoryList option').each(function() {
                if ($(this).val() == $("#category").val()) {
                    $("#category").val('');
                    categoryTags = $('.categoryTags').tokenfield('getTokens');
                    categoryTags.push({
                        value: $(this).val(),
                        label: $(this).attr('label')
                    });
                    $('.categoryTags').tokenfield('setTokens', categoryTags);
                    console.log(categoryTags);
                }
            });

        });



        function refreshCategoryData() {
            data = JSON.parse('<?= $categoriasArrayJson ?>');
            if (data['status'] == true) {
                $.each(data.category, function(index, value) {
                    $("#categoryList").append('<option value="' + value.data.cod + '" label="' + value.data.titulo + '">');
                });
            } else {
                console.log('error');
            }
        }

        $("#subcategory").keyup(function() {
            if ($(this).val().length == 3) {
                $("#subcategoryList").empty();
                refreshSubcategoryData();
            }

            $('#subcategoryList option').each(function() {
                if ($(this).val() == $("#subcategory").val()) {
                    $("#subcategory").val('');
                    subcategoryTags = $('.subcategoryTags').tokenfield('getTokens');
                    subcategoryTags.push({
                        value: $(this).val(),
                        label: $(this).attr('label')
                    });
                    $('.subcategoryTags').tokenfield('setTokens', subcategoryTags);
                    console.log(subcategoryTags);
                }
            });

        });

        function refreshSubcategoryData() {
            data = JSON.parse('<?= $subcategoriasArrayJson ?>');

            if (data['status'] == true) {
                $.each(data.subcategory, function(index, value) {
                    $("#subcategoryList").append('<option value="' + value.data.cod + '" label="' + value.categoriaTitulo + ' | ' + value.data.titulo + '">');
                });

            } else {
                console.log('error');
            }
        }

    });
</script>