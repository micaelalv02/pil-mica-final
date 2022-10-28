<?php
$categoryList = $categorias->listIfHave('productos', "", "es");
?>

<form id="filter-allProducts">
    <div class="row">
        <div class="col-md-3">
            <label for="filter-provincia" class="ml-10">Provincias</label>
            <select name="filter-provincia" id='provincia' onchange="getAllProducts()" data-url="<?= URL ?>">
                <option value="" selected> --- Seleccionar Provincia ---</option>
                <?php $funciones->provincias(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filter-categoria" class="ml-10">Categorias</label>
            <select name="filter-categoria" onchange="getAllProducts()">
                <option value="" selected> --- Seleccionar Categoria ---</option>
                <?php foreach ($categoryList as $categoryItem) {
                    $subcategoryList = $subcategorias->list(["subcategorias.categoria = '" . $categoryItem["data"]["cod"] . "'"], "", "", "es");
                ?>
                    <option class="bold" value="cat-<?= $categoryItem["data"]["cod"] ?>"><?= $categoryItem["data"]["titulo"] ?></option>
                    <?php foreach ($subcategoryList as $subcategoryItem) { ?>
                        <option value="sub-<?= $subcategoryItem["data"]["cod"] ?>"><?= $subcategoryItem["data"]["titulo"] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-2" style="margin-top:22px">
            <fieldset class="form-group  has-icon-left">
                <input type="text" name="filter-fecha" class="form-control dateSelectRange" placeholder="Select Date" onchange="getAllProducts()">
                <div class="form-control-position">
                    <i class='bx bx-calendar-check'></i>
                </div>
            </fieldset>
        </div>
        <div class="col-md-2">
            <label for="filter_order_status">Estado de Pedido </label>
            <select name="filter_order_status" onchange="getAllProducts()">
                <option value="" selected>Todos</option>
                <?php foreach ($estadosAceptados as $estadoItem) { ?>
                    <option value="<?= $estadoItem["data"]["id"] ?>"><?=$estadoItem["data"]["titulo"]?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filter-limite">Cantidad</label>
            <select name="filter-limite" onchange="getAllProducts()">
                <option value="">-- Seleccionar la cantidad de productos --</option>
                <option value="100">100</option>
                <option value="200">200</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="all">TODOS</option>
            </select>
        </div>
    </div>
</form>
<div class="tab-content">
    <div class="tab-pane active" aria-labelledby="home-tab" role="tabpanel">
        <div class="table-responsive">
            <table class="table  mb-0">
                <thead>
                    <th style="padding: 1.15rem 1.15rem">PROVINCIA</th>
                    <th style="padding: 1.15rem 1.15rem">CODIGO</th>
                    <th style="padding: 1.15rem 1.15rem">TITULO</th>
                    <th style="padding: 1.15rem 1.15rem">CANT. VENDIDA</th>
                    <th style="padding: 1.15rem 1.15rem">CANT. PEDIDOS</th>
                </thead>
                <tbody id="grid-all-products"></tbody>
            </table>
        </div>
    </div>
</div>