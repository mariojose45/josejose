var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    });
    $.post("../ajax/orden_trabajo.php?op=selectCliente", function(r){
                $("#idcliente").html(r);
                $('#idcliente').selectpicker('refresh');

                $(".bs-searchbox").children("input").keyup(function(){
                    //No results matched
                    var valor=$(this).val();
                    var textooption=$(".no-results").text();
                    var newText=textooption.replace("No results matched","Crear Nuevo Cliente");
                    $(".no-results").text(newText);

                    $(".no-results").css("cursor","pointer");

                    $(".no-results").click(function(){
                        $("#myModal2").modal('show');
                    })

                });                
    });   

    $("#btnGuardar2").click(function(e) 
        {
            guardaryeditar2(e);  
        });    
    
    $("#btnGuardar3").click(function(e) 
        {
            guardaryeditar3(e);  
        });    

    


}

function guardaryeditar3(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
   // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario3")[0]);
 
    $.ajax({
        url: "../ajax/orden_trabajo.php?op=guardaryeditar3", 
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {               
            
            alert("Detalle Agregado Correctamente")  
            $('#ModalDetalleTecnico').modal('hide');
            mostrarform(false);
            tabla.ajax.reload();
        }
 
    });
    limpiar3();
}

function limpiar3()
{
    $("#idorden2").val("");
    $("#detalle_tecnico").val("");
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora_detalle').val(today);

}

function guardaryeditar2(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
   // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario2")[0]);
 
    $.ajax({
        url: "../ajax/persona.php?op=guardaryeditar2", 
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {               
            $("#idcliente").append(datos).selectpicker('refresh');
            alert("Cliente Agregado Correctamente")  
            $('#myModal2').modal('hide');
        }
 
    });
    limpiar2();
}

function cancelarform2()
{
    limpiar2();
}

function limpiar2()
{
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#tipo_cliente").val("Publico");
    $("#tipo_cliente").selectpicker('refresh');    
    $("#idpersona").val(""); 
}

 
//Función limpiar
function limpiar()
{
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#idcategoria").val("");
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);
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
                    url: '../ajax/orden_trabajo.php?op=listar',
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
        url: "../ajax/orden_trabajo.php?op=guardaryeditar",
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
 
function mostrar(idorden)
{
    $.post("../ajax/orden_trabajo.php?op=mostrar",{idorden : idorden}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idcliente").val(data.idcliente);
        $("#idcliente").selectpicker('refresh');

        $("#fecha_hora").val(data.fecha);
        $("#modelo").val(data.modelo);
        $("#serie").val(data.serie);
        $("#descripcion_equipo").val(data.descripcion_equipo);
        $("#reparacion_equipo").val(data.reparacion_equipo);
        $("#idorden").val(data.idorden);
 
    })
}

function detalle_tenico(idorden)
{
        $('#ModalDetalleTecnico').modal('show');

        $.post("../ajax/orden_trabajo.php?op=mostrar_idtecnico",{idorden : idorden}, function(data, status)
            {
                data = JSON.parse(data);        
               // mostrarform(true);
                $("#idorden2").val(data.idorden);
         
            })
} 

function activar(idorden)
{
    bootbox.confirm("¿Está Seguro procedera a entregar el articulo?", function(result){
        if(result)
        {
            $.post("../ajax/orden_trabajo.php?op=activar", {idorden : idorden}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}

     

 

 
init();