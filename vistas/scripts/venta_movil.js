var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(true);
    listar();
     
  

                listarArticulos(); 
 
 
$(document).on('keydown', function(event) {  
  if(event.which == 120) { // F9 para abrir el modal de venta
    $("#btnProcesar").click();
    //$("#cefectivo").focus() 
    
  }
});    

$(document).on('keydown', function(event) {  
  if(event.which == 117) { // F6 guardar
    $("#btnGuardar").trigger("click");
  }
}); 
 
  
$(document).on('keydown', function(event) {  
  if(event.which == 118) { // F7 cancelar
    $("#btnCancelar").click(); 
    
  } 
}); 
$(document).on('keydown', function(event) {  
  if(event.which == 119) { // F8 para abrir el modal de venta
    $("#btnagregar").click();
  }
});   

 /*   $("#formulario").on("submit",function(e)
    { 
        $('#myModal22').modal('hide');
        guardaryeditar(e);  
    });*/
    $("#btnProcesar").click(function(){

        var total=$("#total").html().replace('Q/.','').trim();
 
        $("#vistatotal").html($("#total").html())


 
    });

    $("#cefectivo").change(function(){
       // $("#cefectivo").focus() 
        try{
            
            var total=parseFloat($("#total").html().replace('Q/.','').trim());
            var efectivo=parseFloat($("#cefectivo").val());
            var cambio=efectivo-total;

            $("#cambio").html("Q/. "+cambio.toFixed(2));
            $("#rescambio").html("Q/. "+cambio.toFixed(2));


        }catch(ex){
  
        }  
    });


    $("#btnGuardar").click(function(e) 
    {
        $('#myModal22').modal('hide');
        guardaryeditar(e);  
    });
 
    


    $("#btnGuardar2").click(function(e) 
    {
        guardaryeditar2(e);  
    });    


    $("#btncargar").click(function(){

        var idcotizacion=$("#idcotizacion").val();
        if(idcotizacion==""){
            alert("Debe Colocar un Id de Cotizacion Valido")
            return;
        }

        obtenerClienteCotizacion(idcotizacion);

    });

    $("#btncargarVenta").click(function()
    {

   
            var idventa_administrador=$("#idcotizacion").val();
            if(idventa_administrador==""){
                alert("Debe Colocar un Id de Venta Valido")
                return;
            }

            obtenerClienteVenta(idventa_administrador);



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


function calculo() {

    var numeropagos = document.getElementById('numero_pagos').value;
    var totalventa = document.getElementById('total_venta').value;

    var resmontoabono=(totalventa/numeropagos);


    document.getElementById('monto_abono').innerHTML = resmontoabono;
    $("#monto_abono").val(resmontoabono.toFixed(2));            


}


function obtenerClienteCotizacion(cotizacion)
{
    $.post("../ajax/cotizaciones.php?op=mostrar",{idcotizacion:cotizacion},function(data){
        //console.log(data); 
        data = JSON.parse(data);        
 
        $("#idcliente").val(data.idcliente);
        $("#nombre_cliente").val(data.cliente); 
        $("#nit").val(data.num_documento);
        $("#direccion_cliente").val(data.direccion);
        $("#telefono_cliente").val(data.telefono);
        $("#correo_cliente").val(data.email);

        $("#idcotizacion_2").val(cotizacion);

        
        obtenerdetallecotizacion(cotizacion);
    })
}

function obtenerClienteVenta(idventa_administrador)
{

    $.post("../ajax/cotizaciones.php?op=mostrarVentaAdministrador",{idventa_administrador:idventa_administrador},function(data){
        //console.log(data); 
        data = JSON.parse(data);    
        //console.log(data);    
 
        $("#idcliente").val(data.idcliente);
        $("#nombre_cliente").val(data.nombrecliente);
        $("#nit").val(data.numdocumentocliente);
        $("#direccion_cliente").val(data.direccioncliente);
        $("#telefono_cliente").val(data.telefonocliente);
        $("#correo_cliente").val(data.emailtelefono);

        $("#idventa_2").val(idventa_administrador);

        
        obtenerdetalleVentaadminitrador(idventa_administrador);
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


$("#forma_pago").change(mostrarFormaPago);
 
function mostrarFormaPago() 
  {
    var forma_pago=$("#forma_pago option:selected").text(); 
    if (forma_pago=='Credito')
    {
        $("#dias_credito2").show();  
        $("#fecha_hora_cobro2").show(); 
    }
    else
    {
        $("#dias_credito2").hide(); 
        $("#fecha_hora_cobro2").hide(); 
    }
  } 
 
//Función limpiar
function limpiar() 
{
    $("#idcliente").val("1");
    $("#idcotizacion_2").val("");
    $("#idventa_2").val("");
     
    $("#nit").val("CF");
    $("#nombre_cliente").val("CONSUMIDOR FINAL");
    $("#direccion_cliente").val("CIUDAD");
    $("#telefono_cliente").val("+502-000-0000"); 
    $("#correo_cliente").val("soporte@gmail.com");        
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
    $('#fecha_hora_pago').val(today);
    $('#fecha_hora_vencimiento_factura').val(today);
 


    $("#forma_pago").val("Efectivo");
    $("#forma_pago").selectpicker('refresh'); 
    $("#nombre_vendedor").val("");
    $("#nombre_vendedor").selectpicker('refresh');     
    $("#fecha_hora_siguiente_pago").val("");       
    $("#observacion_credito").val("");   

    $("#numero_pagos").val("");   
    $("#monto_abono").val("");    

    $("#cefectivo").val("");           
    $("#rescambio").val("");  
    $("#idcotizacion").val("");  
    $("#idventa").val("");           
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
       // $("#btnGuardar").disabled();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
       // $("#fecha_hora_cobro2").hide(); 
        detalles=0;
    }
    else
    {
        //$("#txtbusquedaarticulo").focus() 
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnGuardar").hide();
       // $("#btnGuardar").disabled();
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
    var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
    var fecha_fin_reporte = $("#fecha_fin_reporte").val();

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
                    url: '../ajax/venta.php?op=listar',
                    data:{fecha_inicio_reporte: fecha_inicio_reporte,fecha_fin_reporte: fecha_fin_reporte},
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "footerCallback": function ( row, data, start, end, display ) 
        {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                total = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Total over this page
                pageTotal = api
                    .column( 1, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );


                // Update footer
                $( api.column(1).footer(0) ).html(
                    pageTotal.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

            },                 
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 2, "desc" ]]//Ordenar (columna,orden)
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
                    url: '../ajax/venta.php?op=listarArticulosVenta',
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

    $.post("../ajax/venta.php?op=listarArticulosVenta2",{
        idcliente:$("#idcliente").val()
      },function(data){
         // console.table(data)
      $("#txtbusquedaarticulo").autocomplete({
          minLength: 0, 
          source: function( request, response ) {
            response($.grep(JSON.parse(data), function(value) {

                try{

                    var splitterm=request.term.split(" ");
                    if(splitterm.length==1){
                        if(value.value.indexOf(splitterm[0])!=-1){
                            return value;
                        }
                    }
                    if(splitterm.length==2){

                        var bol1=value.value.indexOf(splitterm[0])!=-1?true:false;
                        var bol2=value.value.indexOf(splitterm[1])!=-1?true:false;

                        if(bol1 && bol2){
                            return value;
                        }
                    }

                    
                    if(splitterm.length==3){

                        var bol1=value.value.indexOf(splitterm[0])!=-1?true:false;
                        var bol2=value.value.indexOf(splitterm[1])!=-1?true:false;
                        var bol3=value.value.indexOf(splitterm[2])!=-1?true:false;
                        if(bol1 && bol2 && bol3){
                            return value;
                        }
                    }

                    if(splitterm.length==4){

                        var bol1=value.value.indexOf(splitterm[0])!=-1?true:false;
                        var bol2=value.value.indexOf(splitterm[1])!=-1?true:false;
                        var bol3=value.value.indexOf(splitterm[2])!=-1?true:false;
                        var bol4=value.value.indexOf(splitterm[3])!=-1?true:false;

                        if(bol1 && bol2 && bol3 && bol4){
                            return value;
                        }
                    }

                }
                catch(ex){
                        alert(ex)
                }

            }));
          },
          html: true, 
          focus: function( event, ui ) {
          },
          select: function (event, ui) {
            agregarDetalle(ui.item.idarticulo,ui.item.nombre,ui.item.precio_venta,ui.item.stock,ui.item.descuento_porcentaje);
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


    }); 

}
//Función para guardar o editar
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    console.log(formData);
      load();
    $.ajax({
        url: "../ajax/venta.php?op=guardaryeditar", 
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)  
        {
            Swal.close() 
            var tipo_comprobante=$("#tipo_comprobante").val();      
          //  console.log(datos);              
                $('#myModal23').removeClass('in');
                $('.myModal23').addClass('out');
                $('.myModal23').hide();            
              bootbox.alert("Venta Registrada Exitosamente");            
             
              listar();

                if(confirm("Desea Imprimir su Factura")){
                    if(tipo_comprobante=="Factura"){
                         window.location.href="../reportes/exFactura_elec_fel.php?id="+datos,'_blank';
                    }                                      
                    else if(tipo_comprobante=="Envio"){
                        window.location.href="../reportes/exFactura_elec.php?id="+datos,'_blank';
                    }
                   //window.open("../reportes/exTicket.php?id="+datos,'_blank');
                } else{
                    window.location.reload();
                }
              
        }
 
    });
 //window.location.reload();
    
}

function load() {
    Swal.fire({
        title: 'Espere un momento . . . ',
        allowOutsideClick:false,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
        },
        willClose: () => {
         Swal.close()
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log('I was closed by the timer')
        }
      })
}
 
function mostrar(idventa)
{
    $.post("../ajax/venta.php?op=mostrar",{idventa : idventa}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idcliente").val(data.idcliente);
        $("#idcliente").selectpicker('refresh');
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#tipo_comprobante").selectpicker('refresh');
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);
        $("#impuesto").val(data.impuesto);
        $("#idventa").val(data.idventa);
        $("#forma_pago").val(data.forma_pago);
        $("#forma_pago").selectpicker('refresh'); 
        $("#fecha_hora_siguiente_pago").val(data.fechahorasiguientepago);               
        $("#observacion_credito").val(data.observacion_credito);               
 
        //Ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });
 
    $.post("../ajax/venta.php?op=listarDetalle&id="+idventa,function(r){
            $("#detalles").html(r);
    }); 
}
 
//Función para anular registros
function anular(idventa)
{
    var valor = prompt("Ingresa la contraseña de de Autorizacion", "");
    if( valor == "51957356" )
    {    

        bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
            if(result)
            {
                $.post("../ajax/venta.php?op=anular", {idventa : idventa}, function(e){
                    bootbox.alert(e);
                    location.reload();
                }); 
            }
        })
    }
     else
    {
        alert("Contraseña no válida: [" + valor + "]");
    }
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
 
function agregarDetalle(idarticulo,articulo,precio_venta,$stock,$stockreal,descuento_porcentaje)
  {
    var cantidad=($stock<0?0:1); 
    var stockinven=$stock;
    console.log($stock);
    //var descuento=0;
    var subtotaldes=0;    
    if (idarticulo!="")
    { 
        var subtotal=cantidad*precio_venta;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo.replace('t.t','"')+'</td>'+
        '<td><input  type="text"   name="descripcion_detalle[]" style="width:100px" id="descripcion_detalle[]" value="." ></td>'+
        '<td><input type="hidden" name="stockinven[]" value="'+stockinven+'">'+stockinven+'</td>'+
        '<td><input onchange="modificarSubototales()" style="width:100px" type="number" step="any" min="0" max="'+($stock<0?0:$stock)+'" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" step="any" style="width:100px" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'" readonly></td>'+
        '<td><input onchange="modificarSubototales()" style="width:100px" type="number" step="any" max="'+(descuento_porcentaje<0?0:descuento_porcentaje)+'"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0" ></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+        
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
function obtenerdetallecotizacion(cotizacion)
{
    $.post("../ajax/cotizaciones.php?op=paraventa",{idcotizacion:cotizacion},function(data){
        //console.log(data);
        data = JSON.parse(data);        
     if (data.length === 0) 
    {
        alert("COTIZACION YA FUE FACTURADA FAVOR INGRESAR OTRO NUMERO PARA CARGAR SU FACTURACION");
    }
    else
    { 
        $.each(data, function(i, item) {
           // console.log(item);
            agregarDetalle2(item.idarticulo,item.articulo,item.precio_venta,item.stock,item.stockreal,item.descuento_porcentaje);
        });//
    }    
    })
}
function obtenerdetalleVentaadminitrador(idventa_administrador)
{
    $.post("../ajax/cotizaciones.php?op=paraventaAdministrador",{idventa_administrador:idventa_administrador},function(data){
        //console.log(data);
        //5820005710
        data = JSON.parse(data);        
 
        $.each(data, function(i, item) {
           // console.log(item);
            agregarDetalle2(item.idarticulo,item.articulo,item.precio_venta,item.stock,item.stockreal,item.descuento_porcentaje);
        });
    })
}
  function agregarDetalle2(idarticulo,articulo,precio_venta,$stock,$stockreal,descuento_porcentaje) 
  {
    var cantidad=$stock;//($stock<0?0:1);
    var subtotaldes=0; 
    var stockinven=$stockreal;    
    if (idarticulo!="")
    {

        var subtotal=cantidad*precio_venta;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo.replace('t.t','"')+'</td>'+
        '<td><input  type="text"   name="descripcion_detalle[]" style="width:100px" id="descripcion_detalle[]" value="."></td>'+
        '<td><input type="hidden" name="stockinven[]" value="'+stockinven+'">'+stockinven+'</td>'+        
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any" min="0" max="'+($stockreal<0?0:$stockreal)+'" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" step="any" name="precio_venta[]"  style="width:100px" id="precio_venta[]" value="'+precio_venta+'" readonly ></td>'+
        '<td><input onchange="modificarSubototales();" type="number" style="width:100px"     step="any" max="'+(descuento_porcentaje<0?0:descuento_porcentaje)+'"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0" ></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+        
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
    var chks = document.getElementsByName('subtotaldes');
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
            $.get("../ajax/venta.php?op=buscararticulocodebar&codigo="+valoractual+"",{op:"buscararticulocodebar",codigo:valoractual},function(res){
                //console.log(res);
               var arrayproduc= res.split("@");

               if(arrayproduc[0]!="undefined"){
               agregarDetalle(arrayproduc[0],arrayproduc[1],arrayproduc[2],arrayproduc[3],arrayproduc[4],arrayproduc[5]); 
               }
               $("#txtbusquedaartcodebar").val("");
               $("#txtbusquedaarticulo").focus() 

            })
        }

    }, 200)

        $("#idcliente").change(function(){

            listarArticulos();
            

        })

})