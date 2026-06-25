var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    });
    //Cargamos los items al select producto
    $.post("../ajax/articulos_procesos.php?op=selectproduccion", function(r){
                $("#idarticulo").html(r);
                $('#idarticulo').selectpicker('refresh');
 
    });  
   
}
 
//Función limpiar
function limpiar()
{
    $("#idproduccion").val("");
    $("#proceso").val("");
    $("#descripcion").val("");
    $("#idarticulos_proceso").val("");
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
                    url: '../ajax/articulos_procesos.php?op=listar',
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
        url: "../ajax/articulos_procesos.php?op=guardaryeditar",
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
 
function mostrar(idarticulos_proceso)
{
    $.post("../ajax/articulos_procesos.php?op=mostrar",{idarticulos_proceso : idarticulos_proceso}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idarticulo").val(data.idarticulo);
        $('#idarticulo').selectpicker('refresh');
        $("#proceso").val(data.proceso);
        $("#descripcion").val(data.descripcion);
        $("#idarticulos_proceso").val(data.idarticulos_proceso);
 
    })
}
 
//Función para desactivar registros
function desactivar(idarticulos_proceso)
{
    bootbox.confirm("¿Está Seguro de desactivar la Hora?", function(result){
        if(result)
        {
            $.post("../ajax/articulos_procesos.php?op=desactivar", {idarticulos_proceso : idarticulos_proceso}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Función para activar registros
function activar(idarticulos_proceso)
{
    bootbox.confirm("¿Está Seguro de activar la Hora?", function(result){
        if(result)
        {
            $.post("../ajax/articulos_procesos.php?op=activar", {idarticulos_proceso : idarticulos_proceso}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
 
init();