var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/ventacrud.php?op=selectCliente", function(r){
                $("#idcliente").html(r);
                $('#idcliente').selectpicker('refresh');
                listarArticulos();
    }); 
}
   
//Función limpiar 
function limpiar() 
{
    $("#idcliente").val("");
    $("#cliente").val("");
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
        //listarArticulos();
 
        $("#btnGuardar").hide();
        $("#btnGuardarEditar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        detalles=0;
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
    $("#inputeditar").val("0")
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
                    url: '../ajax/ventacrud.php?op=listar',
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
                    url: '../ajax/ventacrud.php?op=listarArticulosVenta',
                    type : "get",
                    data:{idcliente:$("#idcliente").val()},                    
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 5,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}
//Función para guardar o editar
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/ventacrud.php?op=guardaryeditar",
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
 
function mostrar(idventa)
{
    $.post("../ajax/ventacrud.php?op=mostrar",{idventa : idventa}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idcliente").val(data.idcliente);
        $("#idcliente").selectpicker('refresh');
        $("#idclientecodigo").val(data.idcliente);
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#tipo_comprobante").selectpicker('refresh');
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);
        $("#impuesto").val(data.impuesto);
        $("#idventa").val(data.idventa);
 
        //Ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnGuardarEditar").show();
        $("#inputeditar").val("1")
        //$("#btnAgregarArt").hide();
    });
 
    $.post("../ajax/ventacrud.php?op=listarDetalle&id="+idventa,function(r){
            $("#detalles").html(r);
    }); 
}
 
//Función para anular registros
function anular(idventa)
{
    bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
        if(result)
        {
            $.post("../ajax/ventacrud.php?op=anular", {idventa : idventa}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=18;
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

function AddProductToInvoice()
{
    var serie_comprobante=$("#serie_comprobante").val();
    var fecha_hora=$("#fecha_hora").val();
    var num_comprobante=$("#num_comprobante").val();    
    var idventa=$("#idventa").val();    
    $(".productAdd").each(function(index){
        var factura="";
        var idarticulo="";
        var cantidad="";
        var precio_venta="";
        var descuento="";

        $(this).children("td").each(function(indextd){
            factura=$("#facturaparadetalle").val();

            switch(indextd){
                case 1:
                    idarticulo=$(this).children("input").val();
                break;
                case 2:
                    cantidad=$(this).children("input").val();
                break;
                case 3:
                    precio_venta=$(this).children("input").val();
                break;
                case 4:
                    descuento=$(this).children("input").val();
                break;
            }
        });

        $.post("../ajax/ventacrud.php?op=EditarFacturaAddDetalleFactura",{ 
            factura:factura,
            idarticulo:idarticulo,
            cantidad:cantidad,
            precio_venta:precio_venta,
            descuento:descuento
        },function(res){ 
            console.log(idarticulo+"idarticulo");
            console.log("guardado Exitoso!!!"+res);
        
        });

    });
    //Ppara actualizar los itmes de una factura
        $.post("../ajax/ventacrud.php?op=actualizarcuerpofac",{
            serie_comprobante:serie_comprobante,
            idventa:idventa,
            fecha_hora:fecha_hora,
            num_comprobante:num_comprobante

        },function(res){ 
            //console.log(idarticulo+"idarticulo");
            console.log(serie_comprobante);
            console.log(idventa);
            console.log("guardado Exitoso Actuzalizaicon Cuerpo Fac!!!"+res);
        
        });    
    bootbox.alert("El item ha sido Guardado de Forma Exitosa");
    mostrarform(false);
    listar();
}
 
function agregarDetalle(idarticulo,articulo,precio_venta)
  {
    var cantidad=1;
    var descuento=0;
 
    if (idarticulo!="")
    {
        var subtotal=cantidad*precio_venta;
        var fila='<tr class="filas productAdd" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
        '<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input type="number" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'"></td>'+
        '<td><input type="number" name="descuento[]" value="'+descuento+'"></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
        '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
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
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");
 
    for (var i = 0; i <cant.length; i++) {
        var inpC=cant[i];
        var inpP=prec[i];
        var inpD=desc[i];
        var inpS=sub[i];
 
        inpS.value=(inpC.value * inpP.value)-inpD.value;
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }
    calcularTotales();
 
  }
  function calcularTotales(){
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;
 
    for (var i = 0; i <sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("S/. " + total);
    $("#total_venta").val(total);
    evaluar();
  }
 
  function evaluar(){
    if (detalles>0)
    {
      if($("#inputeditar").val()!="1"){
        $("#btnGuardar").show();
        $("#btnGuardarEditar").hide();
      }
    }
    else
    {
        if($("#inputeditar").val()!="1"){
            $("#btnGuardar").hide(); 
            $("#btnGuardarEditar").hide();
            cont=0;
        }
    }
  }
 
  function eliminarDetalle(indice){
    $("#fila" + indice).remove();
    calcularTotales();
    detalles=detalles-1;
    evaluar()
  }
 
init();

$(function(){
    $("#txtbusquedaartcodebar").delayPasteKeyUp(function(){

        var valoractual=$("#txtbusquedaartcodebar").val();

        if(valoractual!=""){
            $.get("../ajax/ventacrud.php?op=buscararticulocodebar&codigo="+valoractual+"",{op:"buscararticulocodebar",codigo:valoractual},function(res){
                console.log(res);
               var arrayproduc= res.split("@");

               if(arrayproduc[0]!="undefined"){
               agregarDetalle(arrayproduc[0],arrayproduc[1],arrayproduc[2]);
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

function EliminarDetalleVenta(dom,cantidad,id,idarticulo)
{
    $(dom).remove();
    $.get("../ajax/ventacrud.php?op=eliminardetalle&id="+id+"&cantidad="+cantidad+"&idarticulo="+idarticulo+" ",{},function(){
        alert("Detalle Eliminado Exitosamente") 
    })
}


function UpdateDetalleVenta(dom,cantidad,nuevacantidad,id,idarticulo)
{
    var ncantidad=$(nuevacantidad).val()
    $.get("../ajax/ventacrud.php?op=updatedetalle&id="+id+"&cantidad="+cantidad+"&nuevacantidad="+ncantidad+"&idarticulo="+idarticulo+" ",{},function(){
        alert("Detalle Modificado Exitosamente")
    })
}