var url = $("#url-adm").attr("data-url");
getOpcionesVariables();

function getOpcionesVariables() {
    $('#opcionesVariablesBody').html('');
    let filter = $("#filter-opcionesVariables option:selected").val();
    let idioma = $("#idiomaGet").val();
    let eliminar_permiso = $("#p_el").val();
    let editar_permiso = $("#p_ed").val();
    $.ajax({
        url: url + "/api/opcionesVariables/list.php",
        type: "GET",
        data: {
            filter: filter,
            idioma: idioma
        },
        success: (data) => {
            data = JSON.parse(data);
            for (data_ in data) {
                let titulo = (data[data_]["data"]["titulo"] != null) ? data[data_]["data"]["titulo"] : '';
                let tipo_mostrar = (data[data_]["data"]["tipo_mostrar"] != null) ? data[data_]["data"]["tipo_mostrar"] : '';
                let area = (data[data_]["data"]["area"] != null) ? data[data_]["data"]["area"] : '';
                let cod = (data[data_]["data"]["cod"] != null) ? data[data_]["data"]["cod"] : '';
                let editar = "";

                if (editar_permiso == "1") {
                    editar = `<a data-toggle="tooltip" data-placement="top" class="btn btn-default" title="Modificar" href="` + url + `/index.php?op=opciones-variables&accion=modificar&cod=` + cod + `&idioma=` + idioma + `">
                    <div class="fonticon-wrap">
                    <i class="bx bx-cog fs-20"></i>
                    </div>
                    </a>`;
                }
                let eliminar = "";
                if (eliminar_permiso == "1") {
                    eliminar = ` <a data-toggle="tooltip" class="deleteConfirm btn btn-danger" data-placement="top" title="Eliminar" href="` + url + `/index.php?op=opciones-variables&accion=ver&borrar=` + cod + `&idioma=` + idioma + `">
                                    <div class="fonticon-wrap">
                                        <i class="bx bx-trash fs-20"></i>
                                    </div>
                                </a>`;
                }
                let tableData = `
                    <tr>
                        <td width="50%">
                            <span class="invoice-customer">` + titulo + `</span>
                        </td>
                    <td width="30%">
                        <span class="invoice-customer">` + tipo_mostrar + `</span>
                    </td>
                    <td width="30%">
                        <span class="invoice-customer">` + area + `</span>
                    </td>
                    <td width="20%">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            ` + editar + `
                            ` + eliminar + `
                        </div>
                    </td>
                </tr>     `;
                $('#opcionesVariablesBody').append(tableData);
            }



        }
    })
}