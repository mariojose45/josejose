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
    $("#nombre").val("");
    $("#direccion").val("");
    $("#dpi_no").val("");
    $("#telefono").val("");
    $("#celular").val("");
    $("#tipo_sexo").val("");
    $("#estado_civil").val("");
    $("#forma_pago").val("");
    $("#email").val(""); 

    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_nacimiento').val(today);
    $('#fecha_ingreso').val(today);
    $('#fecha_finalizacion_labora_1').val(today);
    $('#fecha_finalizacion_labora_2').val(today);
    $('#fecha_finalizacion_labora_3').val(today);
    $('#fecha_finalizacion_labora_4').val(today); 

    $("#cta_no").val("");
    $("#estado_civil").val("");
    $("#sueldo_base").val("");
    $("#bonificacion").val("");

    $("#jefe_immediato_1").val("");
    $("#tiempo_trabajo_1").val("");
    $("#telefono_1").val("");
    $("#jefe_immediato_2").val("");
    $("#tiempo_trabajo_2").val("");
    $("#telefono_2").val("");
    $("#jefe_immediato_3").val("");
    $("#tiempo_trabajo_3").val("");
    $("#telefono_3").val("");
    $("#jefe_immediato_4").val("");
    $("#tiempo_trabajo_3").val("");

    $("#nombre_refe_laboral_1").val("");
    $("#telefono_refe_laboral_1").val("");
    $("#parentesco_refe_laboral_1").val("");
    $("#nombre_refe_laboral_2").val("");
    $("#telefono_refe_laboral_2").val("");
    $("#parentesco_refe_laboral_2").val("");
    $("#nombre_refe_laboral_3").val("");
    $("#telefono_refe_laboral_3").val("");
    $("#parentesco_refe_laboral_3").val("");

    //Dpi Lado 1
    $("#dpi_lado1_imagenmuestra").attr("src","");
    $("#dpi_lado1_imagenactual").val("");
    //Dpi Lado 2
    $("#dpi_lado1_imagenmuestra").attr("src","");
    $("#dpi_lado1_imagenactual").val(""); 
    //carta_recomendacion1_imagenmuestra
    $("#carta_recomendacion1_imagenmuestra").attr("src","");
    $("#carta_recomendacion1_imagenactual").val("");        
    //carta_recomendacion2_imagenmuestra
    $("#carta_recomendacion2_imagenmuestra").attr("src","");
    $("#carta_recomendacion2_imagenactual").val("");        

    //carta_recomendacion3_imagenmuestra
    $("#carta_recomendacion3_imagenmuestra").attr("src","");
    $("#carta_recomendacion3_imagenactual").val(""); 

    //carta_trabajo1_imagenmuestra
    $("#carta_trabajo1_imagenmuestra").attr("src","");
    $("#carta_trabajo1_imagenactual").val(""); 

    //carta_trabajo2_imagenmuestra
    $("#carta_trabajo2_imagenmuestra").attr("src","");
    $("#carta_trabajo2_imagenactual").val(""); 

    //carta_trabajo3_imagenmuestra
    $("#carta_trabajo3_imagenmuestra").attr("src","");
    $("#carta_trabajo3_imagenactual").val("");    
    
    $("#horaentrada").val("");  
    $("#horarefaccion").val("");  
    $("#horaalmuerzo").val("");  
    $("#horasalida").val("");  


    $("#idficha_empleado").val("");
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
                    url: '../ajax/ficha_empleado.php?op=listar',
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
        url: "../ajax/ficha_empleado.php?op=guardaryeditar", 
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
 
function mostrar(idficha_empleado)
{
    $.post("../ajax/ficha_empleado.php?op=mostrar",{idficha_empleado : idficha_empleado}, function(data, status)
    {
       // alert(121212)
       console.log(data)
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#cod_empleado").val(data.cod_empleado);
        $("#nombre").val(data.nombre);
        $("#direccion").val(data.direccion);
        $("#dpi_no").val(data.dpi_no);
        $("#telefono").val(data.telefono);
        $("#celular").val(data.celular);

        $("#horaext").val(data.horaextra);

        $("#tipo_sexo").val(data.tipo_sexo);
        $("#tipo_sexo").selectpicker('refresh');

        $("#estado_civil").val(data.estado_civil);
        $("#estado_civil").selectpicker('refresh');

        $("#forma_pago").val(data.forma_pago);
        $("#forma_pago").selectpicker('refresh');                


        $("#email").val(data.email);
        $("#fecha_nacimiento").val(data.fechanacimiento);
        $("#cta_no").val(data.cta_no);

        $("#tipo_banco").val(data.tipo_banco);
        $("#tipo_banco").selectpicker('refresh'); 

        $("#sueldo_base").val(data.sueldo_base);         
        $("#bonificacion").val(data.bonificacion);         
        $("#fecha_ingreso").val(data.fechaingreso);   

        //alert(data.hora_entrada);
        
        $("#horaentrada").val(data.hora_entrada);  
        $("#horarefaccion").val(data.hora_refaccion);  
        $("#horaalmuerzo").val(data.hora_almuerzo);  
        $("#horasalida").val(data.hora_salida);  


        //$("#dpi_lado1_imagenmuestra").show();
        $("#dpi_lado1_imagenmuestra").attr("src",data.dpi_Lado1_imagen);
        $("#dpi_lado2_imagenmuestra").attr("src",data.dpi_lado2_imagen);
        $("#carta_recomendacion1_imagenmuestra").attr("src",data.carta_recomendacion1_imagen);
        $("#carta_recomendacion2_imagenmuestra").attr("src",data.carta_recomendacion2_imagen);
        $("#carta_recomendacion3_imagenmuestra").attr("src",data.carta_recomendacion3_imagen);
        $("#carta_trabajo1_imagenmuestra").attr("src",data.carta_trabajo1_imagen);
        $("#carta_trabajo2_imagenmuestra").attr("src",data.carta_trabajo2_imagen);
        $("#carta_trabajo3_imagenmuestra").attr("src",data.carta_trabajo3_imagen);

        
        $("#idficha_empleado").val(data.idficha_empleado);
 
    })
}
 
//Función para desactivar registros
function desactivar(idcategoria)
{
    bootbox.confirm("¿Está Seguro de desactivar la Categoría?", function(result){
        if(result)
        {
            $.post("../ajax/categoria.php?op=desactivar", {idcategoria : idcategoria}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Función para activar registros
function activar(idcategoria)
{
    bootbox.confirm("¿Está Seguro de activar la Categoría?", function(result){
        if(result)
        {
            $.post("../ajax/categoria.php?op=activar", {idcategoria : idcategoria}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
 
init();