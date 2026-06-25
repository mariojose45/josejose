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
    $("#idnosotros").val("");
    $("#historia_empresa").val("");
    $("#imagen_nosotros").val("");
    $("#imagenmuestra_nosotros").attr("src", "");
    $("#imagenactual_nosotros").val("");
    $("#mision_nosotros").val("");
    $("#vision_nosotros").val("");
    $("#diferencia_nosotros").val("");

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
                url: '../ajax/tienda_web_nosotros.php?op=listar',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
                "columnDefs": [
                {
                    // Columnas de títulos (1, 5, 9) - renderizar HTML
                    "targets": [1, 5],
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
        url: "../ajax/tienda_web_nosotros.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log(datos);
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

function mostrar(idnosotros) {
    $.post("../ajax/tienda_web_nosotros.php?op=mostrar", { idnosotros: idnosotros }, function (data, status) {
        // jQuery ya parsea automáticamente el JSON cuando el Content-Type es application/json
        // No es necesario hacer JSON.parse() de nuevo
        mostrarform(true);
        
        $("#idnosotros").val(data.idnosotros);
        $("#historia_empresa").val(data.historia_empresa);
        $("#mision_nosotros").val(data.mision_nosotros);
        $("#vision_nosotros").val(data.vision_nosotros);
        $("#diferencia_nosotros").val(data.diferencia_nosotros);
        $("#imagenmuestra_nosotros").show();
        $("#imagenmuestra_nosotros").attr("src", "../files/articulos/" + data.imagen_1);
        $("#imagenactual_nosotros").val(data.imagen_nosotros);

    }, "json") // Especificar que esperamos JSON
}








init();