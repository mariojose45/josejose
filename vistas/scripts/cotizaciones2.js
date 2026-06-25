var tabla;

//Función que se ejecuta al inicio
function init(){

    $('#MenuReportes').addClass("treeview active");
    $('#AConsultaCotizacion').addClass("active");

    $('#MenuReportes').addClass("treeview active");
    $('#AConsutaCotizacionDetalle').addClass("active");
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


    $("#btnProcesar").click(function(){

        var total=$("#total").html().replace('Q/.','').trim();

        $("#vistatotal").html($("#total").html())

    });


    $("#btnGuardar").click(function(e) 
    {
        $('#myModal22').modal('hide');
        guardaryeditar(e);  
    });


    $.post("../ajax/usuario.php?op=selectEmpresa", function(r){
                $("#idsucursal").html(r);
                $('#idsucursal').selectpicker('refresh');
             
    });     


}





/////CLIENTE NUEVO

function validarnit()  
{
    

    var nit = $("#nit").val();

    if (nit == "") { 
        alert("Debe Colocar un nit mayor a 6 caracteres");
        return;
    } 

    $.post("../ajax/consultas.php?op=validarnit", { nit: nit }, function (data) {

        try {
            data = JSON.parse(data);


                // Validar si nombre no es nulo, indefinido o vacío
                if (data["receptor"] && data["receptor"]["nombre"] != null && data["receptor"]["nombre"].trim() !== "") {
                    let nombre = data["receptor"]["nombre"];
                    let direccion = data["receptor"]["direccion"] != null ? data["receptor"]["direccion"] : "CIUDAD";

                    $("#nombre_cliente").val(nombre);
                    $("#direccion_cliente").val(direccion);

                    buscarnitenSistemaparaIdcliente(nit);
                } else {
                    $("#idcliente").val('0');
                    $("#codigo_cliente").val('');
                    $("#correo_cliente").val("sincorreo@gmail.com"); 
                    $("#telefono_cliente").val("0"); 
                    $("#tipo_documento_cliente").val("NIT");
                    $("#tipo_documento_cliente").selectpicker('refresh'); 

                    Swal.fire({
                       title: 'Mensaje!',
                       text: "Nit No Existe volver a consultar su nit o se creara como cliente nuevo",
                       icon: 'success',
                         timer: 2000, // 2 segundos
                         timerProgressBar: true
                     });

                }
            } catch (error) {
               Swal.fire({
                   title: 'Mensaje!',
                   text: "Error al procesar la respuesta del servidor. Intente nuevamente.",
                   icon: 'success',
                         timer: 2000, // 2 segundos
                         timerProgressBar: true
                     });                
           }
       });
}

function buscarnitenSistemaparaIdcliente(nit) 
{
    var nombre_cliente=$("#nombre_cliente").val();
    $.post("../ajax/consultas.php?op=buscarnitenSistemaparaIdcliente",{nit:nit},function(data, status){
                //console.log(data)
                try { 
                    data = JSON.parse(data);

                // Verifica si el objeto data está vacío o nulo
                if (data === null || !data.idpersona) {
                    $("#idcliente").val('0');
                    $("#codigo_cliente").val('');
                    $("#correo_cliente").val("sincorreo@gmail.com"); 
                    $("#telefono_cliente").val("0"); 
                    $("#tipo_documento_cliente").val("NIT");
                    $("#tipo_documento_cliente").selectpicker('refresh'); 

                    Swal.fire({
                        title: 'Mensaje!',
                        text: 'Se Creara Nuevo Cliente.',
                        icon: 'warning',
                        timer: 2000, // 2 segundos
                        timerProgressBar: true
                    });
                } else {
                    // Si el cliente existe, llena los campos
                    $("#idcliente").val(data.idpersona);
                    $("#nit").val(data.num_documento);
                    $("#codigo_cliente").val(data.codigo_cliente);
                    $("#telefono_cliente").val(data.telefono); 
                    $("#correo_cliente").val(data.email); 
                    $("#tipo_documento_cliente").val(data.tipo_documento);
                    $("#tipo_documento_cliente").selectpicker('refresh'); 

                }
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error en la respuesta del servidor.',
                    icon: 'error',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });
            }         
            
        })
}


function validarnitNombre() 
{
    
    var nombre_cliente = $("#nombre_cliente").val();

    $.post("../ajax/consultas.php?op=validarnitNombre", {nombre_cliente: nombre_cliente}, function(data, status){
           // console.log(data);

           try {
                // Solo parsear una vez
                data = JSON.parse(data);

                // Verifica si el objeto data está vacío o nulo
                if (data === null || !data.idpersona) {
                    $("#codigo_cliente").val('');
                    $("#idcliente").val('0');
                    $("#nit").val("C/F");
                    $("#direccion_cliente").val("CIUDAD");
                    $("#correo_cliente").val("sincorreo@gmail.com"); 
                    $("#telefono_cliente").val("0"); 
                    $("#tipo_documento_cliente").val("NIT");
                    $("#tipo_documento_cliente").selectpicker('refresh'); 

                    Swal.fire({
                        title: 'Mensaje!',
                        text: 'Cliente no existe, se puede crear como nuevo.',
                        icon: 'warning',
                        timer: 2000, // 2 segundos
                        timerProgressBar: true
                    });
                } else {
                    // Si el cliente existe, llena los campos
                    $("#idcliente").val(data.idpersona);
                    $("#nit").val(data.num_documento);
                    $("#codigo_cliente").val(data.codigo_cliente);
                    $("#telefono_cliente").val(data.telefono); 
                    $("#direccion_cliente").val(data.direccion); 
                    $("#correo_cliente").val(data.email); 
                    $("#tipo_documento_cliente").val(data.tipo_documento);
                    $("#tipo_documento_cliente").selectpicker('refresh');                  

                } 
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error en la respuesta del servidor.',
                    icon: 'error',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });
            }
        });
}


function validarCodigo()  
{
    
    var codigo_cliente=$("#codigo_cliente").val();
    $.post("../ajax/venta.php?op=validarCodigo",{codigo_cliente:codigo_cliente},function(data, status){
            // console.log(data)
            //console.log(data)
            try {
                data = JSON.parse(data); 

                
                // Verifica si el objeto data está vacío o nulo
                if (data === null || !data.idpersona) {

                    $("#idcliente").val('0');
                    $("#nit").val("C/F");
                    $("#direccion_cliente").val("CIUDAD");
                    $("#nombre_cliente").val("CONSUMIDOR FINAL");
                    $("#correo_cliente").val("sincorreo@gmail.com"); 
                    $("#telefono_cliente").val("0"); 
                    $("#tipo_documento_cliente").val("NIT");
                    $("#tipo_documento_cliente").selectpicker('refresh'); 


                    Swal.fire({
                        title: 'Mensaje!',
                        text: 'Cliente no existe se puede crear como nuevo.',
                        icon: 'warning',
                        timer: 2000, // 2 segundos
                        timerProgressBar: true
                    }); 
                } else {
                    // Si el cliente existe, llena los campos
                    $("#nit").val(data.num_documento);
                    $("#nombre_cliente").val(data.nombre);
                    $("#telefono_cliente").val(data.telefono); 
                    $("#direccion_cliente").val(data.direccion); 
                    $("#correo_cliente").val(data.email); 
                    $("#tipo_documento_cliente").val(data.tipo_documento);
                    $("#tipo_documento_cliente").selectpicker('refresh');  

                }
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error en la respuesta del servidor.',
                    icon: 'error',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });
            }      
            
        })
}


function listartbBusquedaCliente() 
{ 

    
    $('#myModalBusquedacliente').modal('show');
    tabla=$('#tbBusquedaCliente').dataTable(
    {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [                

            ],
            "ajax": 
            {
                url: '../ajax/consultas.php?op=listartbBusquedaCliente',
                type : "get",
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


function RespuestavalidarnitNombre(idpersona,nombre,num_documento,direccion,telefono,email,
    tipo_documento,codigo_cliente)
{
    Swal.fire({
        title: 'Mensaje!',
        text: "Cliente Agregado de Forma correcta",
        icon: 'success',
                            timer: 2000, // 2 segundos
                        });

    limpiardatoscliente();
    $("#codigo_cliente").val(codigo_cliente);
    $("#nit").val(num_documento);
    $("#nombre_cliente").val(nombre);
    $("#telefono_cliente").val(telefono); 
    $("#direccion_cliente").val(direccion); 
    $("#correo_cliente").val(email); 
    $("#tipo_documento_cliente").val(tipo_documento);
    $("#tipo_documento_cliente").selectpicker('refresh');  
    $("#idcliente").val(idpersona);  
}


function limpiardatoscliente() 
{
    $("#codigo_cliente").val("0");
    $("#nit").val("C/F");
    $("#nombre_cliente").val("CONSUMIDOR FINAL"); 
    $("#telefono_cliente").val("000-0000"); 
    $("#direccion_cliente").val("N/A");
    $("#correo_cliente").val("sincorreo@dominio.com");
    $("#idcliente").val("3");
    $("#tipo_documento_cliente").val("NIT");
    $("#tipo_documento_cliente").selectpicker('refresh');            
}


   
////fin de cliente  






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
    $("#idcotizacion").val("");
    $("#datos1").val("");   
    $("#codigo_cliente").val("");   
    $("#nit").val("CF");   
    $("#nombre_cliente").val("CONSUMIDOR FINAL");   
    $("#telefono_cliente").val("0");   
    $("#direccion_cliente").val("CIUDAD");   
    $("#correo_cliente").val("soporte@gmail.com");   
    $("#idcliente").val("1");  
    $("#tipo_documento_cliente").val("NIT");
    $("#tipo_documento_cliente").selectpicker('refresh');    


    $("#forma_pago").val("Efectivo");
    $("#forma_pago").selectpicker('refresh'); 


    $("#total_venta").val("");
    $("#total_ventades").val("");


    $(".filas").remove();
    $("#total").html("0");

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
            url: '../ajax/cotizaciones.php?op=listar',
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
        "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}




function listarxfechasucursal()

{
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idsucursal = $("#idsucursal").val();

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
            url: '../ajax/cotizaciones.php?op=listarxfechasucursal',
            data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: idsucursal},
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
        "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}


function listarxfechasucursalDetalle()

{
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idsucursal = $("#idsucursal").val();

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
            url: '../ajax/cotizaciones.php?op=listarxfechasucursalDetalle',
            data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: idsucursal},
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










//Función ListarArticulos
function listarArticulos()
{

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



}
//Función para guardar o editar

function guardaryeditar(e)
{
    agruparDatos();
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    formData.append('datos1', $("#datos1").val());
 
    $.ajax({
        url: "../ajax/cotizaciones.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {      

         Swal.fire({
             title: 'Mensaje!',
             text: datos,
             icon: 'success',
             timer: 2000, // 2 segundos
             timerProgressBar: true,
             willClose: () => {
              window.location.reload();
              }
          });

        }
 
    });
}


function agruparDatos() {
    const datos = {
        idarticulo: [],
        stockinven: [],        
        cantidad: [],
        precio_venta: [],
        descuento_porcentaje: []
    };
    $('#detalles .filas').each(function() {
        datos.idarticulo.push($(this).find('input[name="idarticulo[]"]').val());
        datos.stockinven.push($(this).find('input[name="stockinven[]"]').val());        
        datos.cantidad.push($(this).find('input[name="cantidad[]"]').val());
        datos.precio_venta.push($(this).find('input[name="precio_venta[]"]').val());
        datos.descuento_porcentaje.push($(this).find('input[name="descuento_porcentaje[]"]').val());

    });
    const datosJSON = JSON.stringify(datos);
    $("#datos1").val(datosJSON);
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




//Función para anular registros
function anular(idventa)
{
    var valor = prompt("Ingresa la contraseña de de Autorizacion", "");
    if( valor == "5820005710" )
    {    

        bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
            if(result)
            {
                $.post("../ajax/cotizaciones.php?op=anular", {idventa : idventa}, function(e){
                 Swal.fire({
                 title: 'Mensaje!',
                 text: e,
                 icon: 'success',
                 timer: 2000, // 2 segundos
                 timerProgressBar: true,
                 willClose: () => {
                  window.location.reload();
                  }
                  });

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
        '<td><input type="hidden" name="stockinven[]" value="'+stockinven+'">'+stockinven+'</td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px"  step="any" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input type="number" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta[]" value="'+precio_venta+'" readonly></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+        
        '<td><span name="subtotaldes" id="subtotaldes'+cont+'">'+subtotaldes+'</span></td>'+
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

function mostrar(idcotizacion) {
    $.post("../ajax/cotizaciones.php?op=mostrar", { idcotizacion: idcotizacion }, function (data, status) {
         
        
        // Parseamos la data
        data = JSON.parse(data); 

        // Verificamos si la data viene vacía o es nula
        if (!data || !data.idcotizacion) {
            Swal.fire({
                icon: 'info',
                title: 'Cotización cobrada',
                text: 'La cotización ya fue cobrada o no está disponible.',
                confirmButtonText: 'Aceptar'
            });
            return; // Detenemos la ejecución si la cotización ya fue cobrada o los datos están vacíos
        }
        load(); 
        
        // Si la data es válida, mostramos el formulario y asignamos los valores
        mostrarform(true);

        $("#idcotizacion").val(data.idcotizacion);
        $("#codigo_cliente").val(data.codigo_cliente);
        $("#nit").val(data.nit);
        $("#nombre_cliente").val(data.nombre_cliente);                
        $("#telefono_cliente").val(data.telefono_cliente);
        $("#direccion_cliente").val(data.direccion_cliente);
        $("#correo_cliente").val(data.correo_cliente);
        $("#idcliente").val(data.idcliente);
        
        $("#tipo_documento_cliente").val(data.tipo_documento_cliente); 
        $("#tipo_documento_cliente").selectpicker('refresh');

        $("#fecha_hora").val(data.fecha);

        $("#total_venta").val(data.total_venta);
        $("#total_ventades").val(data.total_ventades);

        $("#forma_pago").val(data.forma_pago);  
        $("#forma_pago").selectpicker('refresh');

        // Llamamos a la función para obtener el detalle de la cotización
        obtenerdetallecotizacion(idcotizacion);
    });
}


  

 
function obtenerdetallecotizacion(idcotizacion)
{
    $.post("../ajax/cotizaciones.php?op=paraventa",{idcotizacion:idcotizacion},function(data){
        //console.log(data);
        data = JSON.parse(data);          
        Swal.close() 

        $.each(data, function(i, item) {
            agregarDetalle2(item.idarticulo,item.articulo,item.precio_venta,item.stock,item.stockreal,item.descuento_porcentaje);
        });
    })
}



function agregarDetalle2(idarticulo,articulo,precio_venta,stock,stockreal,descuento_porcentaje)
{

    //var descuento=0;
    var subtotaldes=0;    
    if (idarticulo!="")
    { 
        var subtotal=stock*precio_venta;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo.replace('t.t','"')+'</td>'+
        '<td><input type="hidden" name="stockinven[]" value="'+stockreal+'">'+stockreal+'</td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px"  step="any" name="cantidad[]" id="cantidad[]" value="'+stock+'"></td>'+
        '<td><input type="number" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta[]" value="'+precio_venta+'" readonly></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="'+descuento_porcentaje+'"></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+        
        '<td><span name="subtotaldes" id="subtotaldes'+cont+'">'+subtotaldes+'</span></td>'+
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
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");    

    for (var i = 0; i <cant.length; i++) {
        var inpC=cant[i];
        var inpP=prec[i];
        var inpD=desc[i];
        var inpS=sub[i];
        var inpSdes=subdes[i];        


        inpS.value=(inpC.value * (inpP.value - ((inpP.value *inpD.value)/100)));
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;

        inpSdes.value=((inpP.value *inpD.value)/100)*inpC.value;
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


    listarArticulos();



})