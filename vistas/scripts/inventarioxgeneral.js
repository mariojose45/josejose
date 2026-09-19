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
                orientation: 'landscape',
                title: function () {
                    var sucursal = $("#idsucursal option:selected").text();
                    var filtro = $("#filtro_stock option:selected").text();
                    return 'SOL | Sistema de Operaciones en Linea\nSucursal: ' + sucursal + ' | Filtro de Stock: ' + filtro;
                },
                exportOptions: {
                    columns: [6, 1, 4, 7, 11]
                },
                customize: function(doc) {
                    // Asignamos anchos específicos a las 5 columnas que estamos exportando
                    // 'auto' ajusta al contenido, '*' reparte el espacio restante equitativamente
                    doc.content[1].table.widths = [
                        '15%',  // Código
                        '*',    // Nombre
                        '20%',  // Categoría
                        '15%',  // Stock
                        '20%'   // Sucursal
                    ];
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
    });
}

//Función para guardar o editar


init();