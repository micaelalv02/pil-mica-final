<section>
    <div class="card">
        <div class="card-content">
            <div class="card-body p-0">
                <form id="filter-pedidos_order">
                    <div class="row">
                        <div class="col-md-2" style="margin-top:22px">
                            <h4 class="btn btn-secondary fs-22" id="count-pedidos">Pedidos: 0</h4>
                        </div>
                        <div class="col-md-3" style="margin-top:22px">
                            <h4 class="btn btn-secondary fs-22" id="monto-pedidos">Monto: 0</h4>
                        </div>
                        <div class="col-md-2 col-sm-12" style="margin-top:22px">
                            <fieldset class="form-group  has-icon-left">
                                <input type="text" name="data-range-pick" class="form-control dateSelectRange" placeholder="Select Date" onchange="getAllOrders()">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>

                        </div>
                        <div class="col-md-3">
                            Provincia:
                            <select name="provincia_orders" id='provincia_orders' onchange="getAllOrders()" data-url="<?= URL ?>">
                                <option value="" selected> --- Seleccionar Provincia ---</option>
                                <?php $funciones->provincias(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Estado Pedido:
                            <select name="order-status" onchange="getAllOrders()">
                                <option value="all">Todos</option>
                                <?php foreach ($estadosAceptados as $key => $estado) { ?>
                                    <option value="<?= $estado['data']['id'] ?>"><?= $estado['data']['titulo'] ?></option>
                                <?php  }  ?>
                            </select>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive" id="facturacion_order">
            <table class="table mb-0 dataTable no-footer">
                <thead>
                    <tr>
                        <th style="padding: 1.15rem 1.15rem">Código</th>
                        <th style="padding: 1.15rem 1.15rem">Usuario</th>
                        <th style="padding: 1.15rem 1.15rem">Compra</th>
                        <th style="padding: 1.15rem 1.15rem">Provincia</th>
                        <th style="padding: 1.15rem 1.15rem">Fecha</th>
                    </tr>
                </thead>
                <tbody style="position:relative;min-height: 50px;" id="grid-pedidos"></tbody>
            </table>
        </div>
    </div>
</section>