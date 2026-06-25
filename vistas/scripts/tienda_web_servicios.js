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
    $("#idservicios").val("");
    $("#nombre").val("");
    $("#tipo").val("Testimonio");
    $("#tipo").selectpicker('refresh');
    $("#descripcion_servicio").val("");
    $("#imagen_servicio").val("");
    $("#imagenmuestra_servicio").attr("src", "");
    $("#imagenactual_servicio").val("");

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
    // Verificar que la tabla exista antes de inicializar
    if ($('#tbllistado').length === 0) {
        console.error('La tabla #tbllistado no existe en el DOM');
        return;
    }
    
    // Destruir la tabla si ya existe
    if ($.fn.DataTable.isDataTable('#tbllistado')) {
        $('#tbllistado').DataTable().destroy();
    }
    
    tabla = $('#tbllistado').DataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": false,//Paginación y filtrado realizados por el cliente
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
        "ajax": {
            url: '../ajax/tienda_web_servicios.php?op=listar',
            type: "get",
            dataType: "json",
            dataSrc: function(json) {
                return json.aaData || [];
            },
            error: function (xhr, error, thrown) {
                console.error('Error en DataTables:', error);
                console.error('Respuesta del servidor:', xhr.responseText);
            }
        },
        "columnDefs": [
            {
                // Columnas que contienen HTML (1, 2) - renderizar HTML
                "targets": [1, 2],
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
    });
}
//Función para guardar o editar

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/tienda_web_servicios.php?op=guardaryeditar",
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

function mostrar(idservicios) {
    $.post("../ajax/tienda_web_servicios.php?op=mostrar", { idservicios: idservicios }, function (data, status) {
        // jQuery ya parsea automáticamente el JSON cuando el Content-Type es application/json
        // No es necesario hacer JSON.parse() de nuevo
        mostrarform(true);
        
        $("#idservicios").val(data.idservicios);
        $("#nombre").val(data.nombre);
        $("#tipo").val(data.tipo);
        $("#tipo").selectpicker('refresh');
        $("#descripcion_servicio").val(data.descripcion_servicio);
        $("#imagenmuestra_servicio").show();
        $("#imagenmuestra_servicio").attr("src", "../files/articulos/" + data.imagen_servicio);
        $("#imagenactual_servicio").val(data.imagen_servicio);


    }, "json") // Especificar que esperamos JSON
}








// Inicializar cuando el DOM esté listo
$(document).ready(function() {
    // Pequeño delay para asegurar que todo esté cargado
    setTimeout(function() {
        init();
    }, 100);
});