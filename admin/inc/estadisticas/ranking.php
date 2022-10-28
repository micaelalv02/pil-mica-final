<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="true">
            <i class="bx bx-shopping-bag align-middle"></i>
            <span class="align-middle">Productos mas vendidos</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#top-mayorista" aria-controls="profile" role="tab" aria-selected="false">
            <i class="bx bx-truck align-middle"></i>
            <span class="align-middle">Mayoristas con mas compras</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#top-minorista" aria-controls="profile" role="tab" aria-selected="false">
            <i class="bx bx-user-plus align-middle"></i>
            <span class="align-middle">Minoristas con mas compras</span>
        </a>
    </li>

</ul>
<div>
    <p class="fs-12">* Se calcula sobre todos los pedidos con estado distinto a rechazado *</p>
</div>
<div class="tab-content">
    <div class="tab-pane active" id="home" aria-labelledby="home-tab" role="tabpanel">
        <div class="table-responsive" id="productos-top">
            <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead>
                    <tr role="row">
                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">
                            Codigo</th>
                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">
                            Titulo</th>
                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">
                            Cant. Vendida</th>
                        <th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">
                            Cant. Pedidos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($topTenAprobado as $dataTop) {
                    ?>
                        <tr role="row" class="odd">
                            <td><?= isset($dataTop['data']['producto_cod']) ? $dataTop['data']['producto_cod'] : '' ?></td>
                            <td><?= strtoupper($dataTop['data']['producto']) ?></td>
                            <td><?= $dataTop['data']['cantidad_vendida'] ?></td>
                            <td><?= $dataTop['data']['cantidad_pedidos'] ?></td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="top-minorista" aria-labelledby="profile-tab" role="tabpanel">
        <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr role="row">
                    <th style="padding: 1.15rem 1.15rem">Nombre</th>
                    <th style="padding: 1.15rem 1.15rem">Email</th>
                    <th style="padding: 1.15rem 1.15rem">Teléfono</th>
                    <th style="padding: 1.15rem 1.15rem">Localidad</th>
                    <th style="padding: 1.15rem 1.15rem">Cant. Pedidos</th>
                    <th style="padding: 1.15rem 1.15rem">Gastado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userDataTop as $key => $topUser) {
                    if ($topUser["user"]["data"]["minorista"] != 1) continue;
                ?>
                    <tr role="row" class="odd">
                        <td style="padding: 1.15rem 1.15rem">
                            <?= (!empty($topUser["user"]["data"]["nombre"])) ? strtoupper($topUser["user"]["data"]["nombre"]) : '' ?>
                            <?= (!empty($topUser["user"]["data"]["apellido"])) ? strtoupper($topUser["user"]["data"]["apellido"]) : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem"><?= (!empty($topUser["user"]["data"]["email"])) ? $topUser["user"]["data"]["email"] : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem"><?= (!empty($topUser["user"]["data"]["telefono"])) ? $topUser["user"]["data"]["telefono"] : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem"><?= (!empty($topUser["user"]["data"]["localidad"])) ? $topUser["user"]["data"]["localidad"] : '' ?>,
                            <?= (!empty($topUser["user"]["data"]["provincia"])) ? $topUser["user"]["data"]["provincia"] : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem"><?= (!empty($topUser["data"]["cantidad_pedidos"])) ? $topUser["data"]["cantidad_pedidos"] : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem">
                            $<?= !empty($topUser["data"]["cantidad_gastada"]) ? number_format($topUser["data"]["cantidad_gastada"], 2, ",", ".") : '' ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="tab-pane" id="top-mayorista" aria-labelledby="profile-tab" role="tabpanel">
        <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr role="row">
                    <th style="padding: 1.15rem 1.15rem">Nombre</th>
                    <th style="padding: 1.15rem 1.15rem">Email</th>
                    <th style="padding: 1.15rem 1.15rem">Teléfono</th>
                    <th style="padding: 1.15rem 1.15rem">Localidad</th>
                    <th style="padding: 1.15rem 1.15rem">Cant. Pedidos</th>
                    <th style="padding: 1.15rem 1.15rem">Gastado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userDataTop as $key => $topUser) {
                    if ($topUser["user"]["data"]["minorista"] != 0) continue;
                ?>
                    <tr role="row" class="odd">
                        <td style="padding: 1.15rem 1.15rem">
                            <?= (!empty($topUser["user"]["data"]["nombre"])) ? strtoupper($topUser["user"]["data"]["nombre"]) : '' ?>
                            <?= (!empty($topUser["user"]["data"]["apellido"])) ? strtoupper($topUser["user"]["data"]["apellido"]) : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem"><?= (!empty($topUser["user"]["data"]["email"])) ? $topUser["user"]["data"]["email"] : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem"><?= (!empty($topUser["user"]["data"]["telefono"])) ? $topUser["user"]["data"]["telefono"] : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem"><?= (!empty($topUser["user"]["data"]["localidad"])) ? $topUser["user"]["data"]["localidad"] : '' ?>,
                            <?= (!empty($topUser["user"]["data"]["provincia"])) ? $topUser["user"]["data"]["provincia"] : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem"><?= (!empty($topUser["data"]["cantidad_pedidos"])) ? $topUser["data"]["cantidad_pedidos"] : '' ?></td>
                        <td style="padding: 1.15rem 1.15rem">
                            $<?= !empty($topUser["data"]["cantidad_gastada"]) ? number_format($topUser["data"]["cantidad_gastada"], 2, ",", ".") : '' ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>