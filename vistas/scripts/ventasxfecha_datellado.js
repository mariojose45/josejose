var tabla;
 
//Función que se ejecuta al inicio
function init(){
    $('#MenuConsultaVentas').addClass("treeview active");
    $('#CDetallado').addClass("active");
    listar();
    $("#fecha_inicio").change(listar);
    $("#fecha_fin").change(listar);

    $.post("../ajax/usuario.php?op=selectEmpresa", function(r){
                $("#idsucursal").html(r);
                $('#idsucursal').selectpicker('refresh');
             
    });   
}
 
 
//Función Listar
function listar() 

{
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idsucursal = $("#idsucursal").val();

    tabla=$('#tbllistado').dataTable(
    {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [                
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'
        ], 
        "ajax":
        {
            url: '../ajax/ventasxfecha_detalle.php?op=ventasdetalle',
            data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal:idsucursal},
            type : "get",
            dataType : "json",                      
            error: function(e){
                console.log(e.responseText);    
            }
        },
        "footerCallback": function ( row, data, start, end, display ) 
        {
            var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                    i : 0;
                };

                // Total over all pages
                total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                total7 = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );  

                total8 = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );   

                                                              

                // Total over this page
                pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                pageTotal7 = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                pageTotal8 = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );    


                // Update footer
                $( api.column(6).footer(0) ).html(
                    pageTotal.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );

                $( api.column(7).footer(0) ).html(
                    pageTotal7.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );   

                $( api.column(8).footer(0) ).html(
                    pageTotal8.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );    


            },                 
            "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}
 
 
init();