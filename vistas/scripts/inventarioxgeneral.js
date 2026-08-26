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
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> <strong> Exportar a Excel</strong>',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success btn-sm'
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