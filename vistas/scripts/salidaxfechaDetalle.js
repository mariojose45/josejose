var tabla;
 
//Función que se ejecuta al inicio
function init(){
    listar();

    $('#MenuReportes').addClass("treeview active");
    $('#ASalidaProductoDetalle').addClass("active");
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
            url: '../ajax/salida_pro_sucursal.php?op=listarxfechasucursalDetalle',
            data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal:idsucursal},
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