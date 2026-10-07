var tabla;

//Función que se ejecuta al inicio
function init() {
    //Cargamos los items al select sucursal
    $.post("../ajax/sucursal.php?op=selectSucursalOpciones", function (r) {
        $("#idsucursal").html(r);
        $('#idsucursal').selectpicker('refresh');
    });

    // listar();


}

//Función mostrar formulario


//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(true);
}

//Función Listar 
function listar() {
    tabla = $('#tbllistado').DataTable({
        "aProcessing": true, // Activamos el procesamiento del DataTables
        "aServerSide": true, // Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', // Definimos los elementos del control de tabla
        buttons: [
            {
                extend: 'colvis',
                text: 'Column visibility',
                attr: { id: 'btnColvis' }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> <strong> Exportar a Excel</strong>',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i> <strong> Exportar a PDF</strong>',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'portrait',
                title: function () {
                    var sucursal = $("#idsucursal option:selected").text();
                    var filtro = $("#filtro_stock option:selected").text();
                    var date = new Date();
                    var fechaHora = date.toLocaleDateString('es-ES') + ' ' + date.toLocaleTimeString('es-ES');
                    return 'SOL | Sistema de Operaciones en Linea\nSucursal: ' + sucursal + ' | Filtro de Stock: ' + filtro + '\nGenerado el: ' + fechaHora;
                },
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    // Reducir el tamaño de la letra general y del encabezado
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;

                    // Ajustar anchos dinámicamente según la cantidad de columnas visibles
                    var colCount = doc.content[1].table.body[0].length;
                    var widths = [];
                    for(var i=0; i<colCount; i++) {
                        widths.push('*'); // Reparte el espacio equitativamente
                    }
                    doc.content[1].table.widths = widths;
                }
            }
        ],
        "ajax": {
            url: '../ajax/articulo.php?op=listarxGeneral',
            type: 'GET',
            data: function (d) {
                d.idsucursal = $("#idsucursal").val();
                d.filtro_stock = $("#filtro_stock").val();
            },
            dataType: 'json',
            error: function (e) {
                console.log(e.responseText);
            },
        },
        "bDestroy": true,
        "iDisplayLength": 20, // Paginación
        "order": [[1, "desc"]], // Ordenar (columna, orden)
        "columnDefs": [
            {
                "targets": [7, 8, 10], // Stock (7), Stock Minimo (8), Precio venta (10)
                "render": function(data, type, row) {
                    if (data !== null && data !== undefined && data !== '') {
                        var num = parseFloat(data);
                        return isNaN(num) ? data : num.toFixed(2);
                    }
                    return data;
                }
            }
        ]
    });
}

//Función para guardar o editar


init();