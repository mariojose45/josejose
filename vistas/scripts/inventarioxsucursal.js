var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);    
    });
     
    //Cargamos los items al select categoria
    $.post("../ajax/articulo.php?op=selectCategoria", function(r){
                $("#idcategoria").html(r);
                $('#idcategoria').selectpicker('refresh');
 
    });   
 

    $("#imagenmuestra").hide();  
}

$("#descuento_porcentaje").change(caculardescuento);
function caculardescuento()
  {
        var precioventa=$("#precio_venta").val();
        var porcentaje=$("#descuento_porcentaje").val();

        var res= precioventa-((precioventa*porcentaje)/100);
        $("#precio_descuento").val(res);

  }

  

$(function(){
    getmarca();
    $('#idmarca').change(function(){ 
        var patter=$("#idmarca option:selected");
        getlinea($('#idmarca').val());
    }); 

    $("#idlinea").change(function(){
        var patter=$("#idlinea option:selected");
        getseccion($('#idlinea').val());
    }); 
});

function getmarca() {
    $.post("../ajax/articulo.php?op=selectMarca",function(data){ 
       // console.log(data);
        var json=JSON.parse(data);
        var html="";
        $.each(json, function(i, item) {
            html+="<option  value='"+item.idmarca+"'>"+item.nombre+" </option>";
        });

        $("#idmarca").html(html);
        $("#idmarca").selectpicker('refresh'); 

    });
} 

function getlinea(idmarca) {
    $.post("../ajax/articulo.php?op=selectlinea",{idmarca:idmarca},function(data){
       // console.log(data);
        var json=JSON.parse(data);
        var html="<option value=''>Seleccione una Linea</option>";
        $.each(json, function(i, item) {
            html+="<option value='"+item.idlinea+"' > "+item.nombre+"</option>";
        });

        $("#idlinea").html(html);
        $("#idlinea").selectpicker('refresh');  

    });
}
 
//Función limpiar
function limpiar()  
{
    $("#idarticulo").val("");
    $("#nombre").val("");    
    $("#idcategoria").val("");
    $("#descripcion").val("");
    $("#stock").val("");
    $("#stockminimo").val("");    
    $("#imagenmuestra").attr("src","");
    $("#imagenactual").val("");
    $("#print").hide();
    $("#codigo").val("");
    $("#precio_venta").val("");
    $("#precio_descuento").val("");
    $("#precio_menudeo").val("");
    $("#precio_compra").val("");
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
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> <strong> Exportar a Excel</strong>',
                        titleAttr: 'Exportar a Excel',
                        className: 'btn btn-success btn-sm'
                    }
                ],  
        "ajax":
                {
                    url: '../ajax/articulo.php?op=listarxsucursal', 
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
//Función para guardar o editar
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/articulo.php?op=guardaryeditar", 
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
 
function mostrar(idarticulo)
{
    $.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idcategoria").val(data.idcategoria);
        $('#idcategoria').selectpicker('refresh');     
        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#stockminimo").val(data.stockminimo);
        $("#descripcion").val(data.descripcion);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#idarticulo").val(data.idarticulo);
        $("#precio_venta").val(data.precio_venta);
        $("#precio_compra").val(data.precio_compra);
        $("#descuento_porcentaje").val(data.descuento_porcentaje);
        $("#precio_descuento").val(data.precio_descuento);
        $("#tipo_producto").val(data.tipo_producto);
        $("#tipo_producto").selectpicker('refresh');
        generarbarcode();
 
    })
}
 
//Función para desactivar registros
function desactivar(idarticulo)
{
    bootbox.confirm("¿Está Seguro de desactivar el artículo?", function(result){
        if(result)
        {
            $.post("../ajax/articulo.php?op=desactivar", {idarticulo : idarticulo}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Función para activar registros
function activar(idarticulo)
{
    bootbox.confirm("¿Está Seguro de activar el Artículo?", function(result){
        if(result)
        {
            $.post("../ajax/articulo.php?op=activar", {idarticulo : idarticulo}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//función para generar el código de barras
function generarbarcode()
{
    codigo=$("#codigo").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
}
 
//Función para imprimir el Código de barras
function imprimir()
{
    $("#print").printArea();
}
 
init();