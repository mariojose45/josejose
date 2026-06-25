var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

 
    $("#formulario").on("submit",function(e)
    {
        $('#myModal22').modal('hide');
        guardaryeditar(e);   
    });
     

    listarArticulos();



    $("#btnGuardar2").click(function(e) 
    {
        guardaryeditar2(e);   
    });    

    $.post("../ajax/usuario.php?op=selectEmpresa", function(r){
                $("#idsucursal").html(r);
                $('#idsucursal').selectpicker('refresh');
             
    }); 


   
} 

function validarnit()
{

    var nit=$("#nit").val();  

        if(nit==""){
            alert("Debe Colocar un nit mayor a 6 caracteres")
            return;
        }
    $.post("../ajax/venta.php?op=validarnit",{nit:nit},function(data){
        console.log(data); 
      //  data = JSON.parse(data);  

       // console.log(data.nombre);  
 
         $("#nombre_cliente").val(data);
    
       

    })
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

//Función cancelarform
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
    $("#idcotizacion").val("");
    $("#idcliente").val("1");
    $("#cliente").val(""); 
    $("#idsucursal").val(""); 
    $("#serie_comprobante").val(""); 
    $("#num_comprobante").val("");
    $("#impuesto").val("0");
 
    $("#total_venta").val("");
    $(".filas").remove();
    $("#total").html("0");
 
    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);
 
    //Marcamos el primer tipo_documento
    $("#tipo_comprobante").val("Boleta");
    $("#tipo_comprobante").selectpicker('refresh');
    
}
 
//Función mostrar formulario
function mostrarform(flag)
{
    limpiar();
    if (flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide(); 
       // listarArticulos();
 
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
       // $("#fecha_hora_cobro2").hide(); 
        detalles=0;
    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#fecha_hora_cobro2").hide(); 
        $("#dias_credito2").hide(); 
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
                    url: '../ajax/cotizaciones_movil.php?op=listar',
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
 
 
//Función ListarArticulos
function listarArticulos()
{
    console.log($("#idcliente").val())
    tabla=$('#tblarticulos').dataTable(
    {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [                
                     
                ],
        "ajax": 
                {
                    url: '../ajax/cotizaciones_movil.php?op=listarArticulosVenta',
                    type : "get",
                    data:{idcliente:$("#idcliente").val()},
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 10,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();

    $.post("../ajax/cotizaciones_movil.php?op=listarArticulosVenta2",{
        idcliente:$("#idcliente").val()
      },function(data){
      $("#txtbusquedaarticulo").autocomplete({
          minLength: 0, 
          source: JSON.parse(data),
          html: true, 
          focus: function( event, ui ) { 
          },
          select: function (event, ui) {
            agregarDetalle(ui.item.idarticulo,ui.item.nombre,ui.item.precio_venta,ui.item.stock,ui.item.stock,ui.item.descuento_porcentaje);
            setTimeout(function(){
                $("#txtbusquedaarticulo").val(""); 
            },300)
          }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            //alert(1)
            return $( "<li>" )
              .append( "<div> <img src='../files/articulos/"+item.img+"' style='width:100px'> " + item.nombre + "</div>" )
              .appendTo( ul );
          };

//console.log(item.img);
    }); 

}
//Función para guardar o editar
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/cotizaciones_movil.php?op=guardaryeditar", 
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {                    
              bootbox.alert(datos);           
              mostrarform(false);
              listar();
        }
 
    });
    limpiar();
}
 
function mostrar(idcotizacion)
{
    $.post("../ajax/cotizaciones_movil.php?op=mostrar",{idcotizacion : idcotizacion}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idcotizacion").val(data.idcotizacion);
        $("#idcliente").val(data.idcliente);
        $("#nit").val(data.num_documento);
        $("#nombre_cliente").val(data.cliente);
        $("#direccion_cliente").val(data.direccion); 
        $("#telefono_cliente").val(data.telefono);
        $("#correo_cliente").val(data.email);
        $("#fecha_hora").val(data.fecha);
        $("#idsucursal").val(data.idsucursal);
        $('#idsucursal').selectpicker('refresh');  
                
        
 
        //Ocultar y mostrar los botones
        obtenerdetallecotizacion(idcotizacion);
    });
 

}



 
//Función para anular registros
function anular(idcotizacion)
{
    bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
        if(result)
        {
            $.post("../ajax/cotizaciones_movil.php?op=anular", {idcotizacion : idcotizacion}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=12;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);
 
function marcarImpuesto()
  {
    var tipo_comprobante=$("#tipo_comprobante option:selected").text();
    if (tipo_comprobante=='Factura')
    {
        $("#impuesto").val(impuesto); 
    }
    else
    {
        $("#impuesto").val("0"); 
    }
  } 

function obtenerdetallecotizacion(idcotizacion)
{
    $.post("../ajax/cotizaciones_movil.php?op=paraventa",{idcotizacion:idcotizacion},function(data){
        //console.log(data);
        data = JSON.parse(data);        
 
        $.each(data, function(i, item) {
           // console.log(item);
            agregarDetalle2(item.idarticulo,item.articulo,item.precio_venta,item.stock,item.stockreal,item.descuento_porcentaje);
        });
    })
}  
 
function agregarDetalle(idarticulo,articulo,precio_venta,$stock,$stockreal,descuento_porcentaje)
  {
    var cantidad=($stock<0?0:1); 
    var stockinven=$stock;
    //var descuento=0;
    var subtotaldes=0;    

            //console.log(descuento_porcentaje);    
 
    if (idarticulo!="")
    {
        var subtotal=cantidad*precio_venta;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
        '<td>'+stockinven+'</td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input  type="text"   name="descripcion_detalle[]" style="width:100px" id="descripcion_detalle[]" value="." ></td>'+
        '<td><input type="number" onchange="modificarSubototales()" step="any" name="precio_venta[]" style="width:100px" id="precio_venta[]" value="'+precio_venta+'" readonly=""></td>'+
        '<td><input onchange="modificarSubototales()" type="number"  style="width:100px" step="any"   name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="'+descuento_porcentaje+'"  ></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'" style="width:100px">'+subtotal+'</span></td>'+        
        '<td><span name="subtotaldes" id="subtotaldes'+cont+'">'+subtotaldes+'</span></td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        modificarSubototales();
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
  }

  function agregarDetalle2(idarticulo,articulo,precio_venta,$stock,$stockreal,descuento_porcentaje) 
  {
    var cantidad=$stock;//($stock<0?0:1);
    //var descuento=0;
    var subtotaldes=0; 
    var stockinven=$stock;    

 
    if (idarticulo!="") 
    {

        var subtotal=cantidad*precio_venta;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
        '<td>'+stockinven+'</td>'+        
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input  type="text"   name="descripcion_detalle[]" style="width:100px" id="descripcion_detalle[]" ></td>'+
        '<td><input type="number" onchange="modificarSubototales()" step="any" name="precio_venta[]" style="width:100px" id="precio_venta[]" value="'+precio_venta+'" readonly=""></td>'+
        '<td><input onchange="modificarSubototales();" type="number" style="width:100px" step="any"   name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="'+descuento_porcentaje+'" ></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'" style="width:100px">'+subtotal+'</span></td>'+        
        '<td><span name="subtotaldes" id="subtotaldes'+cont+'">'+subtotaldes+'</span></td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        modificarSubototales();
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
  }
 
  function modificarSubototales()
  {
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");    
 
    for (var i = 0; i <cant.length; i++) {
        var inpC=cant[i];
        var inpP=prec[i];
        var inpD=desc[i];
        var inpS=sub[i];
        var inpSdes=subdes[i];        
 
        inpS.value=(inpC.value * inpP.value)-inpD.value;
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;

        inpSdes.value=inpD.value;
        document.getElementsByName("subtotaldes")[i].innerHTML = inpSdes.value;         
       //console.log(inpSdes.value);
    }

    calcularTotales();
    calcularTotalesdes();


  
  }

  function calcularTotalesdes() {
    var chks = document.getElementsByName('descuento_porcentaje[]');
    var total = 0.0;
    for(var i = 0; i < chks.length; i++) {
        var valor = parseFloat(chks[i].value);
        if(isNaN(valor) == false) {
            total += parseFloat(chks[i].value);
        }
    }
   // alert("la suma es, " + total);
        $("#totaldes").html("Q/. " + total);   
       $("#total_ventades").val(total);
       evaluar();
}




  function calcularTotales(){
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i <sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("Q/. " + total);
    $("#total_venta").val(total);

 

   evaluar();
  }
 
  function evaluar(){
    if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").hide(); 
      cont=0;
    }
  }
 
  function eliminarDetalle(indice){
    $("#fila" + indice).remove();
    calcularTotales();
    detalles=detalles-1;
    evaluar()
  }

  function eliminarDetalleCotizacion(indice){
    $("#fila" + indice).remove();
    calcularTotales();
    detalles=detalles-1;
    evaluar()
  }
 
init();

$.fn.delayPasteKeyUp = function(fn, ms)
{
 var timer = 0;
 $(this).on("propertychange input", function()
 {
  clearTimeout(timer);
  timer = setTimeout(fn, ms);
 });
};

$(function(){
    $("#txtbusquedaartcodebar").delayPasteKeyUp(function(){

        var valoractual=$("#txtbusquedaartcodebar").val();

        if(valoractual!=""){
            $.get("../ajax/cotizaciones_movil.php?op=buscararticulocodebar&codigo="+valoractual+"",{op:"buscararticulocodebar",codigo:valoractual},function(res){
                console.log(res);
               var arrayproduc= res.split("@");

               if(arrayproduc[0]!="undefined"){
               agregarDetalle(arrayproduc[0],arrayproduc[1],arrayproduc[2],arrayproduc[3],arrayproduc[4],arrayproduc[5]);
               }
               $("#txtbusquedaartcodebar").val("");
               $("#txtbusquedaartcodebar").focus() 

            })
        }

    }, 200)

        $("#idcliente").change(function(){

            listarArticulos();
            

        })

})