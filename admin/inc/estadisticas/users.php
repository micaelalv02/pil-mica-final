<ul class="nav nav-tabs nav-fill" id="myTab-2" role="tablist">
    <li class="nav-item">
        <a onclick="getNewUsers();hiddenGeneralUsers()" class="nav-link active" data-toggle="tab" href="#new_users" role="tab" id="new_users_" aria-controls="new_users" aria-selected="true">
            Nuevos Usuarios
        </a>
    </li>
    <li class="nav-item">
        <a onclick="getAllUsers();hiddenNewUsers()" class="nav-link" data-toggle="tab" href="#general_users" id="general_users_" role="tab" aria-controls="general_users" aria-selected="false">
            Usuarios General
        </a>
    </li>
</ul>

<div class="tab-pane" id="new_users" role="tabpanel" aria-labelledby="new_users_">
    <form id="filter-newUsers">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a onclick="changeTypeValue('mayorista')" class="nav-link active" id="home-tab" data-toggle="tab" href="#new-mayorista" aria-controls="home" role="tab" aria-selected="true">
                    <i class="bx bx-truck align-middle"></i>
                    <span class="align-middle" id="countUsersMayoristas"></span>
                </a>
            </li>
            <li class="nav-item">
                <a onclick="changeTypeValue('minorista')" class="nav-link" id="profile-tab" data-toggle="tab" href="#new-minorista" aria-controls="profile" role="tab" aria-selected="false">
                    <i class="bx bx-user align-middle"></i>
                    <span class="align-middle" id="countUsersMinoristas"></span>
                </a>
            </li>
            <li>
                <div class="mt-10"> (Ultimos 30 días)</div>
            </li>
        </ul>
        <input type="hidden" name="typeNewUser" value="mayorista">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block mr-2 mb-1">
                        <fieldset>
                            <div class="radio radio-shadow radio-success radio-glow">
                                <input type="radio" id="ambos_" name="realizo-compra" value="3" checked onchange="getNewUsers()">
                                <label for="ambos_">Ambos</label>
                            </div>
                        </fieldset>
                    </li>
                    <li class="d-inline-block mr-2 mb-1">
                        <fieldset>
                            <div class="radio radio-shadow radio-warning radio-glow">
                                <input type="radio" id="compro_" name="realizo-compra" value="1" onchange="getNewUsers()">
                                <label for="compro_">Realizó compra</label>
                            </div>
                        </fieldset>
                    </li>
                    <li class="d-inline-block mr-2 mb-1">
                        <fieldset>
                            <div class="radio radio-shadow radio-warning radio-glow">
                                <input type="radio" id="noCompro_" name="realizo-compra" value="2" onchange="getNewUsers()">
                                <label for="noCompro_">No realizó compra</label>
                            </div>
                        </fieldset>
                    </li>
                </ul>
            </div>
        </div>
    </form>
    <div class="tab-content">
        <div class="tab-pane active" aria-labelledby="home-tab" role="tabpanel">
            <div class="table-responsive">
                <table class="table  mb-0">
                    <thead>
                        <th style="padding: 1.15rem 1.15rem">Nombre</th>
                        <th style="padding: 1.15rem 1.15rem">Apellido</th>
                        <th style="padding: 1.15rem 1.15rem">Email</th>
                        <th style="padding: 1.15rem 1.15rem">Teléfono</th>
                        <th style="padding: 1.15rem 1.15rem">Localidad</th>
                        <th style="padding: 1.15rem 1.15rem">Fecha</th>
                    </thead>
                    <tbody id="grid-new-users"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="tab-pane d-none" id="general_users" role="tabpanel" aria-labelledby="general_users_">
    <form id="filter-allUsers">
        <div class="row">
            <div class="col-md-4">
                <fieldset class="form-group  has-icon-left">
                    <input type="text" name="date-all-users" class="form-control dateSelectRange" placeholder="Select Date" onchange="getAllUsers()">
                    <div class="form-control-position"><i class='bx bx-calendar-check'></i></div>
                </fieldset>
            </div>
            <div class="col-md-3">
                <select name="provincia_users" onchange="getAllUsers()" data-url="<?= URL ?>">
                    <option value="" selected> --- Seleccionar Provincia ---</option>
                    <?php $funciones->provincias(); ?>
                </select>
            </div>
        </div>
        <table class="table zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr role="row">
                    <th style="padding: 1.15rem 1.15rem">Nombre</th>
                    <th style="padding: 1.15rem 1.15rem">Email</th>
                    <th style="padding: 1.15rem 1.15rem">Provincia</th>
                    <th style="padding: 1.15rem 1.15rem">Cant. Pedidos</th>
                    <th style="padding: 1.15rem 1.15rem">Gastado</th>
                </tr>
            </thead>
            <tbody id="allUsersBody"></tbody>
        </table>
    </form>

</div>


<script>
    function hiddenGeneralUsers() {
        $('#general_users').removeClass("d-none");
        $('#general_users').addClass("d-none");
        $('#new_users').removeClass("d-none");
    }

    function hiddenNewUsers() {
        $('#new_users').removeClass("d-none");
        $('#new_users').addClass("d-none");
        $('#general_users').removeClass("d-none");
    }
</script>