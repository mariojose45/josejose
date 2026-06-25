var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    /*$("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })*/
    $("#btnGuardar").click(function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });

}

//Función limpiar
function limpiar() {
    $("#idinicio").val("");

    $("#titulo_1").val("");
    $("#sub_titulo_1").val("");
    $("#descripcion_titulo_1").val("");
    $("#imagen_1").val("");
    $("#imagenmuestra_1").attr("src", "");
    $("#imagenactual_1").val("");

    $("#titulo_2").val("");
    $("#sub_titulo_2").val("");
    $("#descripcion_titulo_2").val("");
    $("#imagen_2").val("");
    $("#imagenmuestra_2").attr("src","");
    $("#imagenactual_2").val("");

    $("#titulo_3").val("");
    $("#sub_titulo_3").val("");
    $("#descripcion_titulo_3").val("");
    $("#imagen_3").val("");
    $("#imagenmuestra_3").attr("src","");
    $("#imagenactual_3").val("");

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
                url: '../ajax/tienda_web_inicio.php?op=listar',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
                "columnDefs": [
                {
                    // Columnas de títulos (1, 5, 9) - renderizar HTML
                    "targets": [1, 5, 9],
                    "render": function (data, type, row) {
                        if (type === 'display' || type === 'type') {
                            return data; // Renderizar HTML sin escapar
                        }
                        return data;
                    }
                }
            ],
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

    $.ajax({
        url: "../ajax/tienda_web_inicio.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //console.log(datos);
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

function mostrar(idinicio) {
    $.post("../ajax/tienda_web_inicio.php?op=mostrar", { idinicio: idinicio }, function (data, status) {
        // jQuery ya parsea automáticamente el JSON cuando el Content-Type es application/json
        // No es necesario hacer JSON.parse() de nuevo
        mostrarform(true);
        
        $("#idinicio").val(data.idinicio);

        $("#titulo_1").val(data.titulo_1);
        $("#sub_titulo_1").val(data.sub_titulo_1);
        $("#descripcion_titulo_1").val(data.descripcion_titulo_1);
        $("#imagenmuestra_1").show();
        $("#imagenmuestra_1").attr("src", "../files/articulos/" + data.imagen_1);
        $("#imagenactual_1").val(data.imagen_1);

        $("#titulo_2").val(data.titulo_2);
        $("#sub_titulo_2").val(data.sub_titulo_2);
        $("#descripcion_titulo_2").val(data.descripcion_titulo_2);
        $("#imagenmuestra_2").show();
        $("#imagenmuestra_2").attr("src", "../files/articulos/" + data.imagen_2);
        $("#imagenactual_2").val(data.imagen_2);

        $("#titulo_3").val(data.titulo_3);
        $("#sub_titulo_3").val(data.sub_titulo_3);
        $("#descripcion_titulo_3").val(data.descripcion_titulo_3);
        $("#imagenmuestra_3").show();
        $("#imagenmuestra_3").attr("src", "../files/articulos/" + data.imagen_3);
        $("#imagenactual_3").val(data.imagen_3);
    }, "json") // Especificar que esperamos JSON
}

function activar(idinicio) {
    bootbox.confirm("¿Está Seguro de activar el Inicio?", function (result) {
        if (result) {
            $.post("../ajax/tienda_web_inicio.php?op=activar", { idinicio: idinicio }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

function activar(idinicio) {
    bootbox.confirm("¿Está Seguro de desactivar el Inicio?", function (result) {
        if (result) {
            $.post("../ajax/tienda_web_inicio.php?op=activar", { idinicio: idinicio }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}



init();