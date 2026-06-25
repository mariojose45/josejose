var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })
}
 
//Función limpiar
function limpiar()
{
    $("#hora").val("");
    $("#hora2").val("");
    $("#descripcion").val("");
    $("#idhora_produccion").val("");
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
                    url: '../ajax/horarios_produccion.php?op=listar',
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
        url: "../ajax/horarios_produccion.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {                    
              bootbox.alert(datos);           
              mostrarform(false);
              tabla.ajax.reload();
        }
 
    });
    limpiar();
}
 
function mostrar(idhora_produccion)
{
    $.post("../ajax/horarios_produccion.php?op=mostrar",{idhora_produccion : idhora_produccion}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#hora").val(data.hora);
        $("#hora2").val(data.hora2);
        $("#descripcion").val(data.descripcion);
        $("#idhora_produccion").val(data.idhora_produccion);
 
    })
}
 
//Función para desactivar registros
function desactivar(idhora_produccion)
{
    bootbox.confirm("¿Está Seguro de desactivar la Hora?", function(result){
        if(result)
        {
            $.post("../ajax/horarios_produccion.php?op=desactivar", {idhora_produccion : idhora_produccion}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Función para activar registros
function activar(idhora_produccion)
{
    bootbox.confirm("¿Está Seguro de activar la Hora?", function(result){
        if(result)
        {
            $.post("../ajax/horarios_produccion.php?op=activar", {idhora_produccion : idhora_produccion}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
 
init();