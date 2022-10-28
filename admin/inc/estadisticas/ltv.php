<section>
    <div class="card">
        <div class="card-content">
            <div class="card-body pb-0">
                <form id="filter-gestionLTV">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow radio-success radio-glow">
                                            <input type="radio" id="ambos-LTV" name="type-user-LTV" value="2" checked onchange="getGestionLTV()">
                                            <label for="ambos-LTV">Ambos</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow radio-warning radio-glow">
                                            <input type="radio" id="mayorista-LTV" name="type-user-LTV" value="0" onchange="getGestionLTV()">
                                            <label for="mayorista-LTV">Mayorista</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow radio-warning radio-glow">
                                            <input type="radio" id="minorista-LTV" name="type-user-LTV" value="1" onchange="getGestionLTV()">
                                            <label for="minorista-LTV">Minorista</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-4 col-sm-12 text-md-right" style="margin-top:22px">
                            <fieldset class="form-group  has-icon-left">
                                <input type="text" name="data-range-pick" class="form-control dateSelectRange" placeholder="Select Date" onchange="getGestionLTV()">
                                <div class="form-control-position"><i class='bx bx-calendar-check'></i></div>
                            </fieldset>
                        </div>
                        <div class="col-md-2">
                            <label for="type-order-status">Estado de Pedido </label>
                            <select name="type-order-status" onchange="getGestionLTV()">
                                <option value="" selected>Todos</option>
                                <?php foreach ($estadosAceptados as $estadoItem) { ?>
                                    <option value="<?= $estadoItem["data"]["id"] ?>"><?= $estadoItem["data"]["titulo"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12 text-md-right">
                            <li class="d-inline-block mr-2 mb-1">
                                <input class="btn btn-primary" onclick="exportTable('gestion-LTV', '<?= URL ?>')" value="Exportar"></input>
                            </li>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive" id="gestion-LTV">
            <table class="table mb-0 dataTable no-footer">
                <thead>
                    <tr>
                        <th style="padding: 1.15rem 1.15rem">Ultima Compra</th>
                        <th style="padding: 1.15rem 1.15rem">Dias<br>Trasc.</th>
                        <th style="padding: 1.15rem 1.15rem">Cant.<br>Pedidos</th>
                        <th style="padding: 1.15rem 1.15rem">Cliente</th>
                        <th style="padding: 1.15rem 1.15rem">Email</th>
                        <th style="padding: 1.15rem 1.15rem">Teléfono</th>
                        <th style="padding: 1.15rem 1.15rem">Localidad</th>
                    </tr>
                </thead>
                <tbody id="grid-gestionLTV">
                </tbody>
            </table>
        </div>
    </div>
</section>