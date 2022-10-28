const url_data_meli = $("#url-data-meli").attr("data-url-meli");

$(".porcentaje").inputSpinner();

let classic = $('#cfg-classic').val();
let premium = $('#cfg-premium').val();
let shipping = $('#cfg-shipping').val();

function delay() {
    return new Promise(resolve => setTimeout(resolve, 1000));
}


function addMeli(flag = false) {
    event.preventDefault();
    let codProduct = $('#codProductInput').val();
    let codMeli = $('#codMeli').val();
    let typeMeli = $('input:radio[name=typeMeli]:checked').val();

    $.ajax({
        url: url_data_meli + "/api/ml/add-meli.php",
        type: "POST",
        data: {
            codProduct: codProduct,
            codMeli: codMeli,
            typeMeli: typeMeli
        },
        success: async function(data) {
            console.log(data);
            data = JSON.parse(data);
            if (data['status']) {
                console.log(flag);
                if (!flag) {
                    $('#listMeli').html('');
                    refreshlistMeli();
                    console.log("a");
                } else {
                    $('#btn-add-modal-' + codProduct).addClass('d-none');
                    $('#btn-delete-modal-' + codProduct).removeClass('d-none');
                    console.log(codProduct);
                }
                successMessage(data['message']);
            } else {
                errorMessage(data['message']);
            }
        }
    });
}


function refreshlistMeli() {
    $.ajax({
        url: url_data_meli + "/api/ml/refresh-list-meli.php",
        type: "POST",
        success: function(data) {
            data = JSON.parse(data);
            data.forEach(meliData => {
                var meliPrint = `
                            <tr >
                            <td style="padding: 5px 5px 5px 5px !important">` + meliData['data']['cod_producto'] + `<br/> <span class='fs-12'>` + meliData['data']['type'] + `</span></td>
                            <td style="padding: 5px 5px 5px 5px !important">` + meliData['data']['code'] + `</td>
                            <td class='text-center' style="padding: 5px 5px 5px 5px !important">
                            <a class="btn btn-danger" style="margin-right: 0" data-toggle="tooltip" data-placement="top" title="Eliminar" href="` + url_data_meli + `/index.php?op=mercadolibre&accion=importar&borrar=` + meliData['data']['code'] + `&codProduct=` + meliData['data']['product'] + `">
                    <i class="fa fa-trash"></i></a>
                            </td>
                            </tr>
                        }
                    }
                    ?>`;
                $('#listMeli').append(meliPrint);

            });
        }
    });
}



function sync() {
    event.preventDefault();
    $('#info').html('');
    $('#resultsRow').html('');
    $.ajax({
        url: url_data_meli + "/api/ml/get-products.php",
        type: "POST",
        success: async function(data) {

            data = JSON.parse(data);
            if (data['status']) {
                var total = data['products'].length;
                $('#info').append("<h5 class='text-center'>Los productos se estan subiendo/actualizando en MercadoLibre, por favor aguarde y no cierre esta página.</h5>");
                $('#info').append("<progress id='progress-bar' class='prb' max='" + total + "' value='0'></progress>");
                $('#info').append("<input type='number' id='numTotal' value='0' /> / <input type='number' value='" + total + "' /><br>");
                MeliTry(data['products']);
            }
        }
    });
}

async function MeliTry(products) {

    var a = 0;
    products.forEach(async(response) => {
        a++;
        await sendML(response, $('#type').val());
        console.log(a);
        if (a == 20) {
            await delay();
            a = 0
        }
    });
}

function sendML(product, type) {
    const form = $('#formMeli').serialize()
    return $.ajax({
        url: url_data_meli + "/api/ml/to-meli.php",
        type: "POST",
        data: {
            product: product,
            type: type,
            form: {
                'cfg-title': 0,
                'cfg-price': 1,
                'cfg-stock': 1,
                'cfg-description': 0,
                'cfg-images': 0
            }
        },
        success: function(data) {
            console.log(data)
            data = JSON.parse(data);
            data.forEach((response) => {
                var error = '';
                if (!response["status"]) {
                    response["error"].forEach((error_) => {
                        console.log(error_["message"]);
                        error = error_["message"];
                    });
                }
                $('#progress-bar').val($('#progress-bar').val() + 1);
                $('#numTotal').val(Number($('#numTotal').val()) + 1);
                classTr = (response['data']['status']) ? "bg-success" : "bg-danger";
                statusIcon = (response['data']['status']) ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-remove'></i>";
                $('#resultsRow').append("<tr class='" + classTr + " mr-10'><td>" + response["data"]["id"] + "</td><td>$" + response["data"]["price"] + "</td><td>" + statusIcon + "</td><td>" + error + "</td></tr>");
            });
        },
        error: function(e) {
            console.log(e);
        }
    });
}

function meliModal(product = '') {
    event.preventDefault();
    $('#modalData').html('');

    modal = `      
             <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <b>Codigo del Producto:   </b>
                            <div class="form-group">
 

                                <input type="text" class="form-control" list="codProduct" id="codProductInput" name="codProduct" />
                                    <datalist id="codProduct" name="codProduct" required>  </datalist>

                                </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-10">
                        <div>
                            <b>Codigo de Mercadolibre:</b>
                            <input class="form-control" id="codMeli" name="codMeli" type="text" />
                        </div>
                    </div>
                    <div class="col-md-12 mt-10">
                        <div>
                            <b>Tipo de publicación:</b><br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="typeClassic" name="typeMeli" class="custom-control-input" value="gold_special">
                                <label class="custom-control-label" for="typeClassic">Clásica</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="typePremium" name="typeMeli" class="custom-control-input" value="gold_pro">
                                <label class="custom-control-label" for="typePremium">Premium</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addMeli(` + ((product) ? true : false) + `)">Agregar</button>
            </div>
            `;

    $('#modalData').append(modal);
    doSelectOptionsMeli(product)

}



$("#codProductInput").keyup(function() {
    var search = $(this).val();
    if (search.length > 3) {
        $.ajax({
            url: url_data_meli + "/api/ml/get-list-modal.php",
            type: "POST",
            data: { product: product },
            success: async function(data) {
                $("#codProduct").append(modal);
                var onclickData = (product != '') ? "'true'" : "'false'";
                data = JSON.parse(data);
                console.log(data);
                modal = '';
                data.forEach(options => {
                    var checkOption = (options['data']['cod_producto'] == product) ? "selected" : "";
                    modal += '<option value="' + options['data']['cod_producto'] + '" ' + checkOption + '>' + options['data']['cod_producto'] + ' - ' + options['data']['titulo'] + '</option>';
                });
                $("#codProduct").append(modal);

            }
        });
    }
});

function doSelectOptionsMeli(product = '') {

    $.ajax({
        url: url_data_meli + "/api/ml/get-list-modal.php",
        type: "POST",
        data: { product: product },
        success: async function(data) {
            var onclickData = (product != '') ? "'true'" : "'false'";
            data = JSON.parse(data);
            console.log(data);
            modal = '';
            data.forEach(options => {
                var checkOption = (options['data']['cod_producto'] == product) ? "selected" : "";
                modal += '<option value="' + options['data']['cod_producto'] + '" ' + checkOption + '>' + options['data']['cod_producto'] + ' - ' + options['data']['titulo'] + '</option>';
            });
            $("#codProduct").append(modal);

        }
    });
}