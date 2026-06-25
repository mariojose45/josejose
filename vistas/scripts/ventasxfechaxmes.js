var tabla;
 
//Función que se ejecuta al inicio
function init(){
    listar();
    $("#fecha_inicio").change(listar);
    $("#fecha_fin").change(listar);

    listarInventarioxSucusal();
    listartbllistadoVentasxSucusal();
    listartbllistadoVentasxSucusalMes();
    listartbllistadoVentasxSucusalMesGanacia();
}
 
 
//Función Listar 
function listar() 
{
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val(); 
 
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
                    url: '../ajax/consultas.php?op=ventasxfechaxmes',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin},
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}
 
function listarInventarioxSucusal()   
{

 
    tabla=$('#tbllistadoInventarioxSucusal').dataTable( 
    {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [                
                     
                ],
        "ajax": 
                {
                    url: '../ajax/consultas.php?op=InventarioxSucusal',
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },              
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}


function listartbllistadoVentasxSucusal()   
{

 
    tabla=$('#tbllistadoVentasxSucusal').dataTable(
    {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [                
                     
                ], 
        "ajax": 
                {
                    url: '../ajax/consultas.php?op=VentasxSucusal',
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },              
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}


function listartbllistadoVentasxSucusalMes()   
{

 
    tabla=$('#tbllistadoVentasxSucusalMes').dataTable(
    {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [                
                    
                ],
        "ajax": 
                {
                    url: '../ajax/consultas.php?op=VentasxSucusalMes',
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },              
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}


function listartbllistadoVentasxSucusalMesGanacia()   
{

 
    tabla=$('#tbllistadoVentasxSucusalMesGanacia').dataTable(
    {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla 
        buttons: [                
                    
                ], 
        "ajax":  
                {
                    url: '../ajax/consultas.php?op=VentasxSucusalMesGanacia',
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
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                total3 = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );  

                total4 = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );   
                                                                              

                // Total over this page
                pageTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                pageTotal3 = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                pageTotal4 = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );    



                // Update footer
                $( api.column(2).footer(0) ).html(
                    pageTotal.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );

                $( api.column(3).footer(0) ).html(
                    pageTotal3.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );   

                $( api.column(4).footer(0) ).html(
                    pageTotal4.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                    );    
                                                                          

            },                 
            "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}

 
 
init();