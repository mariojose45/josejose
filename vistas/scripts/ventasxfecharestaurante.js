var tabla;
 
//Función que se ejecuta al inicio
function init(){
    $('#MenuConsultaVentas').addClass("treeview active");
    $('#CVentas').addClass("active");
    $('#MenuReportes').addClass("treeview active");
    $('#AAgrupadasUsuario').addClass("active");
    //Cargamos los items al select cliente
    $.post("../ajax/usuario.php?op=selectEmpresa", function(r){
                $("#idsucursal").html(r);
                $('#idsucursal').selectpicker('refresh');
             
    });     
}
 
 /*
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
                    url: '../ajax/consultas.php?op=ventasxfecha',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: idsucursal},
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
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Total over this page
                pageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );


                // Update footer
                $( api.column(5).footer(0) ).html(
                    pageTotal.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

            },                
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}*/

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
            url: '../ajax/consultas.php?op=ventasxfechaRestaurante',
            data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: idsucursal},
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

                total9 = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );   

                total10 = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );  

                total11 = api
                .column( 11 )
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

                pageTotal9 = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );    

                pageTotal10 = api
                .column( 10, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );    

                pageTotal11 = api
                .column( 11, { page: 'current'} )
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

                $( api.column(9).footer(0) ).html(
                    pageTotal9.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );    

                $( api.column(10).footer(0) ).html(
                    pageTotal10.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );  

                $( api.column(11).footer(0) ).html(
                    pageTotal11.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );                                                                            

            },                 
            "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}


function ventasxfechaAgrupadas()

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
            url: '../ajax/consultas.php?op=ventasxfechaAgrupadas',
            data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: idsucursal},
            type : "get",
            dataType : "json",                      
            error: function(e){
                console.log(e.responseText);    
            }
        },              
            "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}


 
init();