var tabla;
 
//Función que se ejecuta al inicio
function init(){
    $('#MenuReportes').addClass("treeview active");
    $('#Akardex').addClass("active");
    //Cargamos los items al select cliente
    $.post("../ajax/usuario.php?op=selectEmpresa", function(r){
                $("#idsucursal").html('<option value="TODO">TODAS LAS SUCURSALES</option>' + r);
                $('#idsucursal').selectpicker('refresh');
    }); 
    listarArticulos();   
}
 
 
//Función Listar 
function listar()     
{
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idsucursal = $("#idsucursal").val();
    var codigo_pro = $("#codigo_pro").val(); 
 
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
                    url: '../ajax/kardex.php?op=listarDetallado',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: idsucursal,codigo_pro: codigo_pro},
                    type : "get",                    
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
}



function listarTodosArticulos()     
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
                    url: '../ajax/kardex.php?op=listarTodo',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: 
                        idsucursal},
                    type : "get",                    
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
}





function listarArticulos() 
{

    tabla = $('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            { 
                url: '../ajax/venta.php?op=listarArticulosVentaKardex',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}

function agregarDetalleKardex(codigo) {
        Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Artículo agregado con éxito",
        showConfirmButton: false,
        timer: 1500,
    });

    $("#codigo_pro").val(codigo);
}


 
init();