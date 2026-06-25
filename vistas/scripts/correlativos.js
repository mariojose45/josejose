var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    });
    $.post("../ajax/usuario.php?op=selectEmpresa", function(r){
                $("#idsucursal").html(r);
                $('#idsucursal').selectpicker('refresh');
             
    });     
}
 
//Función limpiar
function limpiar()
{
    $("#idcorrelativo").val("");
    $("#num_cotizacion").val("");
    $("#num_envio").val("");
    $("#idsucursal").val("");
}
 
//Función mostrar formulario
function mostrarform(flag)
{
    limpiar();
    if (flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}
 
//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
}
 
//Función Listar
function listar() 
{
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
                    url: '../ajax/correlativos.php?op=listar',
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
//Función para guardar o editar
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/correlativos.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {                    
                swal("Mensaje!", datos, "success");            
              mostrarform(false);
              tabla.ajax.reload();
        }
 
    });
    limpiar();
}
 
function mostrar(idcorrelativo)
{
    $.post("../ajax/correlativos.php?op=mostrar",{idcorrelativo : idcorrelativo}, function(data, status)
    {

        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idcorrelativo").val(data.idcorrelativo);
        $("#num_cotizacion").val(data.num_cotizacion);
        $("#num_envio").val(data.num_envio);
        $("#idsucursal").val(data.idsucursal);
        $("#idsucursal").selectpicker('refresh');
 
    })
}
 

 
//Función para desactivar registros

 
init();