var tabla;
listar();
//Función que se ejecuta al inicio
function init() {
    mostrarform(false);

    $("#btnGuardar").click(function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });

    $.post("../ajax/sucursal.php?op=selectSucursal", function (r) {
        var json = JSON.parse(r);
        var html = "";
        $.each(json, function (i, item) {
            html += "<option value='" + item.idsucursal + "'>" + item.nombre + "</option>";
        });
        $("#idsucursal").html(html);
        $('#idsucursal').selectpicker('refresh');
    });

}

//Función limpiar
function limpiar() {
    $("#id_tariasprecios").val("");
    $("#precio_fraccion_carro").val("0");
    $("#precio_hora_carro").val("0");
    $("#tarifa_dia_carro").val("0");
    $("#tarifa_noche_carro").val("0");
    $("#tarifa_evento_carro").val("0");
    $("#precio_fraccion_moto").val("0");
    $("#precio_hora_moto").val("0");
    $("#tarifa_dia_moto").val("0");
    $("#tarifa_noche_moto").val("0");
    $("#tarifa_evento_moto").val("0");
    $("#precio_fraccion_camion").val("0");
    $("#precio_hora_camion").val("0");
    $("#tarifa_dia_camion").val("0");
    $("#tarifa_noche_camion").val("0");
    $("#tarifa_evento_camion").val("0");


    $("#no_correlativo_ticket").val("0");
    $("#valor_ticket_extraviado").val("0");
    $("#tiempo_gracia_ticket").val("0");

    $("#cantidad_parqueos").val("0");
    $("#idsucursal").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        $("#btnTour").hide();
    }
    else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnTour").show();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función Listar
function listar() {
    tabla = $('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    attr: { id: 'btnExcel' }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Exportar PDF',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    attr: { id: 'btnPdf' },
                    exportOptions: { columns: ':visible' },
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 7;
                        doc.styles.tableHeader.fontSize = 8;
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;
                        table.widths = new Array(columnCount).fill('*');
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Column visibility',
                    attr: { id: 'btnColvis' }
                }
            ],
            "ajax":
            {
                url: '../ajax/parqueo_tarifas.php?op=listar',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    load();
    $.ajax({
        url: "../ajax/parqueo_tarifas.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //console.log(datos);
            Swal.close();
            Swal.fire({
                title: 'Mensaje!',
                text: datos,
                icon: 'success',
                timer: 2000, // 2 segundos
                timerProgressBar: true,
                willClose: () => {
                    window.location.reload();
                }
            });

        }

    });
    limpiar();
}

function mostrar(id_tariasprecios) {
    load();
    $.post("../ajax/parqueo_tarifas.php?op=mostrar", { id_tariasprecios: id_tariasprecios }, function (data, status) {
        data = JSON.parse(data);
        Swal.close();
        mostrarform(true);

        $("#id_tariasprecios").val(data.id_tariasprecios);
        $("#precio_fraccion_carro").val(data.precio_fraccion_carro);
        $("#precio_hora_carro").val(data.precio_hora_carro);
        $("#tarifa_dia_carro").val(data.tarifa_dia_carro);
        $("#tarifa_noche_carro").val(data.tarifa_noche_carro);
        $("#tarifa_evento_carro").val(data.tarifa_evento_carro);
        $("#precio_fraccion_moto").val(data.precio_fraccion_moto);
        $("#precio_hora_moto").val(data.precio_hora_moto);
        $("#tarifa_dia_moto").val(data.tarifa_dia_moto);
        $("#tarifa_noche_moto").val(data.tarifa_noche_moto);
        $("#tarifa_evento_moto").val(data.tarifa_evento_moto);
        $("#precio_fraccion_camion").val(data.precio_fraccion_camion);
        $("#precio_hora_camion").val(data.precio_hora_camion);
        $("#tarifa_dia_camion").val(data.tarifa_dia_camion);
        $("#tarifa_noche_camion").val(data.tarifa_noche_camion);
        $("#tarifa_evento_camion").val(data.tarifa_evento_camion);
        $("#no_correlativo_ticket").val(data.no_correlativo_ticket);
        $("#valor_ticket_extraviado").val(data.valor_ticket_extraviado);
        $("#tiempo_gracia_ticket").val(data.tiempo_gracia_ticket);
        $("#cantidad_parqueos").val(data.cantidad_parqueos);
        // primero cargar sucursales
        $.post("../ajax/sucursal.php?op=selectSucursal", function (r) {
            var json = JSON.parse(r);
            var html = "";
            $.each(json, function (i, item) {
                html += "<option value='" + item.idsucursal + "'>" + item.nombre + "</option>";
            });
            $("#idsucursal").html(html);

            // 👇 ESTA ES LA CLAVE
            $("#idsucursal").selectpicker('val', data.idsucursal);
            $("#idsucursal").selectpicker('refresh');
        });

    })
}

function load() {
    Swal.fire({
        title: 'Espere un momento . . . ',
        allowOutsideClick: false,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
        willClose: () => {
            Swal.close()
        }
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer')
        }
    })
}








init();