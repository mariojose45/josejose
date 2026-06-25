var tabla;

//Función que se ejecuta al inicio
function init(){
   listar();

   $('#MenuReportes').addClass("treeview active");
   $('#AEntradaProductosDetalle').addClass("active");
 
    //Cargamos los items al select cliente
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
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: [                
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdf' 
                ],
        "ajax":
                {
                    url: '../ajax/entrada_pro_sucursal.php?op=listarxfechasucursalDetalle',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: idsucursal},
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "language": {
            "lengthMenu": "Mostrar : _MENU_ registros",
            "buttons": {
            "copyTitle": "Tabla Copiada",
            "copySuccess": {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },        
        "bDestroy": true,
        "iDisplayLength": 5,//Paginación
        "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}



init();