var tabla;
 
//Función que se ejecuta al inicio
function init(){
    listar();
    $("#fecha_inicio").change(listar);
    $("#fecha_fin").change(listar);
    $("#idcuenta").change(listar);
    $.post("../ajax/pagos_empleados.php?op=selectBanco", function(r){
                $("#idcuenta").html(r); 
                $('#idcuenta').selectpicker('refresh'); 
                
    });      
}
 
 
//Función Listar
function listar()
{
    var fecha_inicio = $("#fecha_inicio").val(); 
    var fecha_fin = $("#fecha_fin").val();
    var idcuenta = $("#idcuenta").val();
 
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
                    url: '../ajax/rpt_cuentaxfecha.php?op=cuentasxfecha',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin, idcuenta: idcuenta}, 
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
 
 
init();