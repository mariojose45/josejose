var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    });
    $('#MenuAlmacen').addClass("treeview active");
    $('#ListaTipoequipo').addClass("active");       
}
 
//Función limpiar
function limpiar()
{
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#idtipo_equipo").val("");
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
                    url: '../ajax/tipo_equipo.php?op=listar',
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
        url: "../ajax/tipo_equipo.php?op=guardaryeditar",
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
  
function mostrar(idtipo_equipo) 
{
    $.post("../ajax/tipo_equipo.php?op=mostrar",{idtipo_equipo : idtipo_equipo}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#idtipo_equipo").val(data.idtipo_equipo);
 
    })
}
 
//Función para desactivar registros
function desactivar(idtipo_equipo)
{
    bootbox.confirm("¿Está Seguro de desactivar el Tipo Modelo?", function(result){
        if(result)
        {
            $.post("../ajax/tipo_equipo.php?op=desactivar", {idtipo_equipo : idtipo_equipo}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Función para activar registros
function activar(idtipo_equipo)
{
    bootbox.confirm("¿Está Seguro de activar el Tipo Modelo?", function(result){
        if(result)
        {
            $.post("../ajax/tipo_equipo.php?op=activar", {idtipo_equipo : idtipo_equipo}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
 
init();