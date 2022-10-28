var url = $("#url-adm").attr("data-url");

var formatter = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS', });

function getNewUsers() {
    $('#grid-new-users').html('');
    $.ajax({
        url: url + "/api/estadisticas/newUsers.php",
        type: "GET",
        data: $('#filter-newUsers').serialize(),
        beforeSend: () => {
            $('#grid-new-users').attr('style', 'position: relative;height: 100px;');
            $('#grid-new-users').html('');
            $('#grid-new-users').append('<div style="position: absolute;left: 47%;top: 20%;"><div class="spinner-border text-primary" style="width: 70px;height: 70px;" role="status"><span class="sr-only ">Cargando...</span></div></div>');
        },
        success: async(data) => {
            $('#grid-new-users').html('');
            data = JSON.parse(data);
            let userType = $("input[name=typeNewUser]").val();
            $("#countUsersMinoristas").html("Minorista (" + data["minorista"].length + ")");
            $("#countUsersMayoristas").html("Mayorista (" + data["mayorista"].length + ")");
            for (let newUser of data[userType]) {
                let nombre = (newUser["nombre"] != null) ? newUser["nombre"] : "";
                let apellido = (newUser["apellido"] != null) ? newUser["apellido"] : "";
                let email = (newUser["email"] != null) ? newUser["email"] : "";
                let telefono = (newUser["telefono"] != null) ? newUser["telefono"] : "";
                let localidad = (newUser["localidad"] != null) ? newUser["localidad"] : "";
                let provincia = (newUser["provincia"] != null) ? newUser["provincia"] : "";
                let fecha = (newUser["fecha"] != null) ? newUser["fecha"] : "";
                fecha = fecha.split(" ")[0].split("-").reverse().join("/");
                let tableData = `
                        <tr>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + nombre + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + apellido + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + email + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + telefono + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + localidad + `,` + provincia + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + fecha + `</div>
                            </td>
                        </tr>`;
                $('#grid-new-users').append(tableData);
            }
        }
    })
}

function getAllUsers() {
    $.ajax({
        url: url + "/api/estadisticas/allUsers.php",
        type: "GET",
        data: $('#filter-allUsers').serialize(),
        beforeSend: () => {
            $('#allUsersBody').attr('style', 'position: relative;height: 100px;');
            $('#allUsersBody').html('');
            $('#allUsersBody').append('<div style="position: absolute;left: 47%;top: 20%;"><div class="spinner-border text-primary" style="width: 70px;height: 70px;" role="status"><span class="sr-only ">Cargando...</span></div></div>');
        },
        success: async(data) => {
            $('#allUsersBody').html('');
            data = JSON.parse(data);
            for (let newUser of data) {
                let nombre = (newUser["user"]["data"]["nombre"] != null) ? newUser["user"]["data"]["nombre"] : "";
                let apellido = (newUser["user"]["data"]["apellido"] != null) ? newUser["user"]["data"]["apellido"] : "";
                let email = (newUser["user"]["data"]["email"] != null) ? newUser["user"]["data"]["email"] : "";
                let provincia = (newUser["user"]["data"]["provincia"] != null) ? newUser["user"]["data"]["provincia"] : "";
                let cantidad_gastada = (newUser["data"]["cantidad_gastada"] != null) ? newUser["data"]["cantidad_gastada"] : 0;
                let cantidad_pedidos = (newUser["data"]["cantidad_pedidos"] != null) ? newUser["data"]["cantidad_pedidos"] : 0;
                let gastado = cantidad_gastada > 0 ? formatter.format(cantidad_gastada) : "0";
                let tableData = `
                        <tr>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + nombre + " " + apellido + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + email + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + provincia + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + cantidad_pedidos + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                                <div class="text-bold-500">` + gastado + `</div>
                            </td>
                        </tr>`;
                $('#allUsersBody').append(tableData);
            }
        }
    })
}


function getAllProducts() {
    $('#grid-all-products').html('');
    $.ajax({
        url: url + "/api/estadisticas/allProducts.php",
        type: "GET",
        data: $('#filter-allProducts').serialize(),
        beforeSend: () => {
            $('#grid-all-products').attr('style', 'position: relative;height: 100px;');
            $('#grid-all-products').html('');
            $('#grid-all-products').append('<div style="position: absolute;left: 47%;top: 20%;"><div class="spinner-border text-primary" style="width: 70px;height: 70px;" role="status"><span class="sr-only ">Cargando...</span></div></div>');
        },
        success: async(data) => {
            $('#grid-all-products').html('');
            data = JSON.parse(data);
            for (let products of data) {
                let cantidad_pedidos = (products["data"]["cantidad_pedidos"] != null) ? products["data"]["cantidad_pedidos"] : "";
                let cantidad_vendida = (products["data"]["cantidad_vendida"] != null) ? products["data"]["cantidad_vendida"] : "";
                let producto_cod = (products["data"]["producto_cod"] != null) ? products["data"]["producto_cod"] : "";
                let producto = (products["data"]["producto"] != null) ? products["data"]["producto"] : "";
                let provincia = (products["data"]["provincia"] != null) ? products["data"]["provincia"] : "";
                let tableData = `
                        <tr>
                            <td style="padding: 1.15rem 1.15rem">
                            ` + provincia + `
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                            <div class="text-bold-500">` + producto_cod + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                            <div class="text-bold-500">` + producto + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                            <div class="text-bold-500">` + cantidad_vendida + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                            <div class="text-bold-500">` + cantidad_pedidos + `</div>
                            </td>
                            <td style="padding: 1.15rem 1.15rem">
                            </td>
                        </tr>`;
                $('#grid-all-products').append(tableData);
            }
        }
    })
}

function filterProducts() {
    event.preventDefault();
    let provincia = $("select[name=provincias]").val();
    let categoria = $("select[name=categorias]").val();

    $("#filter-allProducts").append('<input type="hidden" name="filter-provincia" value="' + provincia + '">');
    $("#filter-allProducts").append('<input type="hidden" name="filter-categoria" value="' + categoria + '">');

    //TODO: make filter and get all products
    // getAllProducts();
}

function changeTypeValue(type) {
    $("input[name=typeNewUser]").val(type);
    getNewUsers();
}

function getPedidos() {
    $('#grid-orders').html('');

    $.ajax({
        url: url + "/api/estadisticas/pedidos.php",
        type: "GET",
        data: $('#filter-pedidos').serialize(),
        beforeSend: () => {
            $('#grid-orders').attr('style', 'position: relative;height: 100px;');
            $('#grid-orders').html('');
            $('#grid-orders').append('<div style="position: absolute;left: 47%;top: 20%;"><div class="spinner-border text-primary" style="width: 70px;height: 70px;" role="status"><span class="sr-only ">Cargando...</span></div></div>');
        },
        success: async(data) => {
            $('#grid-orders').html('');
            data = JSON.parse(data);
            if (data.length > 0) {
                for (let elementOrder of data) {
                    if (!!elementOrder['data']['detalle']) {
                        let detalle_ = await this.separateString(elementOrder['data']['detalle']);
                        let detalle = JSON.parse("{" + detalle_, true);
                        let fecha = elementOrder['data']['fecha'].split(" ")[0].split("-").reverse().join("/");
                        let pago = (elementOrder['data']['pago'] != null) ? elementOrder['data']['pago'] : 'SIN DEFINIR';
                        let nombre = (detalle['pago']['apellido'] != '' || detalle['pago']['nombre'] != '') ? detalle['pago']['nombre'] + ` ` + detalle['pago']['apellido'] : detalle['envio']['nombre'] + ` ` + detalle['envio']['apellido'];
                        let tableData = `
                <tr>
                    <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + elementOrder['data']['cod'] + `</td>
                    <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + formatter.format(elementOrder['data']['total']) + `</td>
                    <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + nombre + `</td>
                    <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + pago + `</td>
                    <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + detalle['envio']['localidad'] + `, ` + detalle['envio']['provincia'] + `</td>
                    <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + fecha + `</td>
                </tr>`;
                        $('#grid-orders').append(tableData);
                    }
                };
            } else {
                $('#grid-orders').attr('style', 'position:relative;height: 100px;');
                $('#grid-orders').append('<div style="position: absolute;left: 42%;top: 10%;"><span class="fs-20">No se encontraron resultados</span></div>');
            }
        }
    })
}

function separateString(string) {
    let array = string.split(',');
    array.splice(0, 1);
    array = array.join(',');
    return array;
}

function getGestionLTV() {
    $('#grid-gestionLTV').html('');
    $.ajax({
        url: url + "/api/estadisticas/gestionLTV.php",
        type: "GET",
        data: $('#filter-gestionLTV').serialize(),
        success: async(data) => {
            data = JSON.parse(data);
            data.forEach(elementLTV => {
                let fecha = elementLTV['data']['ultima_compra'].split(" ")[0].split("-").reverse().join("/");
                let localidad = (elementLTV['data']['localidad'] != null) ? elementLTV['data']['localidad'] : '';
                let provincia = (elementLTV['data']['provincia'] != null) ? elementLTV['data']['provincia'] : '';
                let nombre = (elementLTV['data']['nombre'] != null) ? elementLTV['data']['nombre'] : '';
                let apellido = (elementLTV['data']['apellido'] != null) ? elementLTV['data']['apellido'] : '';

                let localidad2 = (localidad != '' && provincia != '') ? localidad + ', ' + provincia : localidad + provincia;
                let tableData = `
                <tr>
                <td style="padding: 1.15rem 1.1rem" class="text-bold-600">` + fecha + `</td>
                <td style="padding: 1.15rem 1.1rem" class="text-bold-600">` + elementLTV['data']['ultimo_dia'] + `</td>
                <td style="padding: 1.15rem 1.1rem" class="text-bold-600">` + elementLTV['data']['cantidad_pedidos'] + `</td>
                <td style="padding: 1.15rem 1.1rem" class="text-bold-600">` + nombre + ` ` + apellido + `</td>
                <td style="padding: 1.15rem 1.1rem" class="text-bold-600">` + elementLTV['data']['email'] + `</td>
                <td style="padding: 1.15rem 1.1rem" class="text-bold-600">` + elementLTV['data']['telefono'] + `
                </td>
                <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + localidad2 + `</td>
               
             </tr>
                `;
                $('#grid-gestionLTV').append(tableData);
            });



        }
    })
}

function getAllOrders() {
    $('#grid-pedidos').html('');
    $.ajax({
        url: url + "/api/estadisticas/orders.php",
        type: "GET",
        data: $('#filter-pedidos_order').serialize(),
        beforeSend: () => {
            $('#grid-pedidos').attr('style', 'position: relative;height: 100px;');
            $('#grid-pedidos').html('');
            $('#grid-pedidos').append('<div style="position: absolute;left: 47%;top: 20%;"><div class="spinner-border text-primary" style="width: 70px;height: 70px;" role="status"><span class="sr-only ">Cargando...</span></div></div>');
        },
        success: async(data) => {
            $('#grid-pedidos').html('');
            data = JSON.parse(data);
            if (data.length > 0) {
                $('#count-pedidos').html('Pedidos: ' + data.length);
                let monto = 0;
                for (let elementOrder of data) {
                    let cod = elementOrder["data"]["cod"];
                    let total = elementOrder["data"]["total"];
                    let fecha = elementOrder["data"]["fecha"];
                    let provincia = elementOrder["data"]["provincia"];
                    let nombre = elementOrder["data"]["nombre"] + " " + elementOrder["data"]["apellido"];
                    let tableData = `
                    <tr>
                        <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + cod + `</td>
                        <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + nombre + `</td>
                        <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + formatter.format(total) + `</td>
                        <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + provincia + `</td>
                        <td style="padding: 1.15rem 1.15rem" class="text-bold-600">` + fecha + `</td>
                    </tr>`;
                    $('#grid-pedidos').append(tableData);
                    monto = monto + parseFloat(total);
                };
                $('#monto-pedidos').html('Monto: ' + formatter.format(monto));
            } else {
                $('#count-pedidos').html('Pedidos: 0');
                $('#monto-pedidos').html('Monto: $0');
                $('#grid-pedidos').attr('style', 'position:relative;height: 100px;');
                $('#grid-pedidos').append('<div style="position: absolute;left: 42%;top: 10%;"><span class="fs-20">No se encontraron resultados</span></div>');
            }
        }
    })
}

function exportTable(id, urlOpen) {
    $.ajax({
        url: url + "/api/estadisticas/exportarTabla.php",
        type: "POST",
        data: {
            id: id,
            table: $('#' + id).html(),
        },
        success: (data) => {
            data = JSON.parse(data);
            window.open(urlOpen + "/export/estadisticas/" + data['fileName']);
        }
    })
}

// DATE RANGE PICKER
$('.dateSelectRange').daterangepicker({
    "showDropdowns": true,
    "timePicker": true,
    "timePicker24Hour": true,
    "timePickerSeconds": true,
    ranges: {
        'Hoy': [moment().startOf('day'), moment()],
        'Ultimos 7 Días': [moment().subtract(6, 'days').startOf('day'), moment()],
        'Ultimos 15 Días': [moment().subtract(14, 'days').startOf('day'), moment()],
        'Ultimos 30 Días': [moment().subtract(29, 'days').startOf('day'), moment()],
        'Este Mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Desde",
        "toLabel": "Hasta",
        "customRangeLabel": "Personalizado",
        "weekLabel": "W",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
        "firstDay": 1
    },
    "startDate": moment().startOf('month'),
    "endDate": moment(),
    "alwaysShowCalendars": true,
});