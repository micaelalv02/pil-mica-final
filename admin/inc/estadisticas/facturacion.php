<section>
    <div class="card">
        <div class="card-content">
            <div class="card-body pb-0">
                <form id="filter-pedidos">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            Tipo usuario:
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow radio-success radio-glow">
                                            <input type="radio" id="ambos" name="type-user" value="2" checked onchange="getPedidos()">
                                            <label for="ambos">Ambos</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow radio-warning radio-glow">
                                            <input type="radio" id="mayorista" name="type-user" value="0" onchange="getPedidos()">
                                            <label for="mayorista">Mayorista</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1" >
                                    <fieldset>
                                        <div class="radio radio-shadow radio-warning radio-glow">
                                            <input type="radio" id="minorista" name="type-user" value="1" onchange="getPedidos()">
                                            <label for="minorista">Minorista</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            Estado Pedido:
                            <select name="type-order" onchange="getPedidos()">
                                <option value="all">Todos</option>
                                <?php foreach ($estadosAceptados as $key => $estado) { ?>
                                    <option value="<?= $estado['data']['id'] ?>"><?= $estado['data']['titulo'] ?></option>
                                <?php  }  ?>
                            </select>
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-12" style="margin-top:20px">
                            <fieldset class="form-group  has-icon-left">
                                <input type="text" name="data-range-pick" class="form-control dateSelectRange" placeholder="Select Date" onchange="getPedidos()">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-2 col-sm-12" style="margin-top:20px">
                            <li class="d-inline-block mr-2 mb-1"><input class="btn btn-primary" onclick="exportTable('facturacion', '<?= URL ?>')" value="Exportar"></input></li>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive" id="facturacion">
            <table class="table mb-0 dataTable no-footer">
                <thead>
                    <tr>
                        <th style="padding: 1.15rem 1.15rem">Código</th>
                        <th style="padding: 1.15rem 1.15rem">Compra</th>
                        <th style="padding: 1.15rem 1.15rem">Usuario</th>
                        <th style="padding: 1.15rem 1.15rem">Tipo Pago</th>
                        <th style="padding: 1.15rem 1.15rem">Localidad</th>
                        <th style="padding: 1.15rem 1.15rem">Fecha</th>
                    </tr>
                </thead>
                <tbody style="position:relative;min-height: 50px;" id="grid-orders"></tbody>
            </table>
        </div>
    </div>
</section>