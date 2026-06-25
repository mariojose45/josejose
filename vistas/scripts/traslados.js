var tabla;
var sucursalesHTML="";
var ArrayArticulos=[];
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

function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/traslados.php?op=trasladar", 
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
    getSucursales();
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
        //console.log(data);
        var json=JSON.parse(data);
        var html="";
        $.each(json, function(i, item) {
            html+="<option  value='"+item.idmarca+"'>"+item.nombre+" </option>";
        });

        $("#idmarca").html(html);
        $("#idmarca").selectpicker('refresh'); 

    });
} 

function getSucursales() {
    $.post("../ajax/sucursal.php?op=selectSucursal",function(data){ 
        console.log(data);
        var json=JSON.parse(data);
        var html="";
        $.each(json, function(i, item) {
            html+="<option  value='"+item.idsucursal+"'>"+item.nombre+" </option>";
        });

        $("#idsucursal").html(html);
        sucursalesHTML=html;
        $("#idsucursal").selectpicker('refresh'); 

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
                    url: '../ajax/traslados.php?op=listar', 
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


function verificacionSeleccionados(){
    var listaArticulos = '';
    var index=0;
    $("input[name=articulosSelecionados]").each(function (index) {  
       if($(this).is(':checked')){
           if(index==0){
                listaArticulos =$(this).val()+'\n';
           }else{
                listaArticulos += '*'+$(this).val()+'\n';
           }
           index++;
       }
    });
    ArrayArticulos=[];
    if(listaArticulos!=""){
        ArrayArticulos=listaArticulos.split("*");
    }
    if(ArrayArticulos.length==0){
        alert("No ha seleccionado ningun articulo");
    }else{
        $("#articulosdiv").html("");
        ArrayArticulos.forEach(element => {


            var datosArticulo=element.split("@")
            var nombre=datosArticulo[1];
            var id=datosArticulo[0];
            $("#articulosdiv").append(`
                <div class="row">
                    <fieldset>
                    <legend>Sucursal Destino</legend>
                    
                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Articulo:</label>
                        <input type="text" value="`+nombre+`" id="TxtArti`+id+`" disabled class="form-control" />
                        </div> 
                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Sucursal Destino(*):</label>
                        <select id="SelectArti`+id+`"  class="form-control selectpicker classsucursal" data-live-search="true" required>`+sucursalesHTML+`</select>
                        </div> 
        
                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Cantidad a Trasladar(*):</label>
                        <input  id="CantidadArti`+id+`" value="0"  class="form-control" type="number" min="1" required></select>
                        </div> 
                    </fieldset>
                </div>  
            `)
              
        });


        $('#modalTraslados').modal('toggle');
        $(".classsucursal").selectpicker('refresh'); 
    }
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
        $("#cantidadt").attr("max",data.stock);
        $("#stockminimo").val(data.stockminimo);
        $("#descripcion").val(data.descripcion);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#idarticulo").val(data.idarticulo);
        $("#precio_venta").val(data.precio_venta);
        $("#descuento_porcentaje").val(data.descuento_porcentaje);
        $("#precio_descuento").val(data.precio_descuento);
        $("#tipo_producto").val(data.tipo_producto);
        $("#tipo_producto").selectpicker('refresh');
        generarbarcode();
 
    })
}

function RealizarTraslado()
{
    errores=0;
    ArrayArticulos.forEach(element => {
        var datosArticulo=element.split("@")
        var nombre=datosArticulo[1];
        var id=datosArticulo[0];
        var cantidad=$(`#CantidadArti`+id+``).val();
        if(cantidad==0){
            errores++;
        }
    });

    if(errores>0){
        alert("Debe colocar la cantidad a trasladar");
        return;
    }
    var index=1;
    ArrayArticulos.forEach(element => {
        var datosArticulo=element.split("@")
        var nombre=datosArticulo[1];
        var id=datosArticulo[0];
        if(index<ArrayArticulos.length){
            $.post("../ajax/traslados.php?op=trasladar",{
                idarticulo:id,
                idsucursal:$(`#SelectArti`+id+``).val(),
                cantidadt:$(`#CantidadArti`+id+``).val()
            },function (datos) {
            })
        }
        else {
            $.post("../ajax/traslados.php?op=trasladar",{
                idarticulo:id,
                idsucursal:$(`#SelectArti`+id+``).val(),
                cantidadt:$(`#CantidadArti`+id+``).val()
            },function (datos) {
                bootbox.alert(datos);           
                mostrarform(false);
                tabla.ajax.reload(); 
            })
        }
        index++;
    });
    $('#modalTraslados').modal('toggle');
}

 
init(); 