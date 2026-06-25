var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    });



    //Cargamos los items al select ficha empleado    
    $.post("../ajax/avance_produccion.php?op=selectfichaempleado", function(r){
                $("#idficha_empleado").html(r);
                $('#idficha_empleado').selectpicker('refresh');
 
    });   

    //Cargamos los items al select ficha hora  
    $.post("../ajax/avance_produccion.php?op=selecthora", function(r){
                $("#idhora_produccion").html(r);
                $('#idhora_produccion').selectpicker('refresh');
 
    });            
}

$(function(){
    getcategoria();
    $('#idcategoria').change(function(){ 
        var patter=$("#idcategoria option:selected");
        getproducto($('#idcategoria').val());
    });

    $("#idarticulo").change(function(){
        var patter=$("#idarticulo option:selected");
    getarticulosproceso($('#idarticulo').val());
    });

    $("#idarticulos_proceso").change(function(){
        var patter=$("#idarticulos_proceso option:selected");
    });


});

function getcategoria() {
    $.post("../ajax/avance_produccion.php?op=selectcategoria",function(data){ 
        console.log(data);
        var json=JSON.parse(data);
        var html="";
        $.each(json, function(i, item) {
            html+="<option  value='"+item.idcategoria+"'>"+item.nombre+" </option>";
        });

        $("#idcategoria").html(html);
        $("#idcategoria").selectpicker('refresh'); 

    });
}

function getproducto(idcategoria) {
    $.post("../ajax/avance_produccion.php?op=selectarticulo",{categoria:idcategoria},function(data){
        console.log(data);
        var json=JSON.parse(data);
        var html="<option value=''>Seleccione un articulo</option>";
        $.each(json, function(i, item) {
            html+="<option value='"+item.idarticulo+"' >Cod:"+item.idarticulo+" Articulo: "+item.nombre+"</option>";
        });

        $("#idarticulo").html(html);

        $("#idarticulo").selectpicker('refresh');  

    });

}

function getarticulosproceso(idarticulo)
{
    $.post("../ajax/avance_produccion.php?op=selectavanceproduccion",{producto:idarticulo},function(data){
        console.log(data);
        var json=JSON.parse(data);
        var html="<option value=''>Seleccione un Proceso</option>";
        $.each(json, function(i, item) {
            html+="<option value='"+item.idarticulos_proceso+"' >Proceso "+item.proceso+"</option>";
        });

        $("#idarticulos_proceso").html(html);

        $("#idarticulos_proceso").selectpicker('refresh');  

    });

}
 
//Función limpiar
function limpiar()
{
    $("#idficha_empleado").val("");
    $("#idcategoria").val("");
    $("#idarticulo").val("");
    $("#idarticulos_proceso").val("");    
    $("#idhora_produccion").val("");        
    $("#cantidad").val("");            
    $("#descripcion").val("");                
    $("#idavance_produccion").val("");
    //Obtenemos la fecha actual 
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
                    url: '../ajax/avance_produccion.php?op=listar',
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
        url: "../ajax/avance_produccion.php?op=guardaryeditar",
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
 
init();