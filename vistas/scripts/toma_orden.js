var tabla;

//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listarMesas();
    listarOrddenes();

    listarArticulos(); 
    listarArticulosxcategoria();
     $("#div_formapago").hide();
 


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



    $("#btnProcesar").click(function () {

        var total = $("#total").html().replace('Q.', '').trim();

        $("#vistatotal").html($("#total").html())



    });      


    $.post("../ajax/articulo.php?op=selectCategoria", function(r) {
        // Agrega la opción predeterminada al principio del contenido HTML
        $("#idcategoria").html('<option value="">Seleccione una categoría</option>' + r);
        $('#idcategoria').selectpicker('refresh');
    });



    $("#btnGuardar").click(function(e) 
    {
        guardaryeditar(e);  
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




function obtenerOrden(id_add_orden) 
{
     load(); 
    $.post("../ajax/toma_orden.php?op=mostrar", { id_add_orden: id_add_orden }, function (data, status) {
         
        
        // Parseamos la data
        data = JSON.parse(data); 

      
        
        // Si la data es válida, mostramos el formulario y asignamos los valores
        mostrarform(true);

        $("#id_add_orden").val(data.id_add_orden);

        $("#codigo_cliente").val(data.codigo_cliente);
        $("#nit").val(data.nit);
        $("#nombre_cliente").val(data.nombre_cliente);       
        $("#direccion_cliente").val(data.direccion_cliente);
        $("#correo_cliente").val(data.correo_cliente);
        $("#idcliente").val(data.idcliente);

        $("#valor_tarjeta").val(data.valor_tarjeta);
        
        $("#tipo_documento_cliente").val(data.tipo_documento_cliente); 
        $("#tipo_documento_cliente").selectpicker('refresh');
        $("#fecha_hora").val(data.fecha);
        $("#propina").val(data.propina);
        $("#total_venta").val(data.total_venta);
        $("#total_ventades").val(data.total_ventades);


        // Llamamos a la función para obtener el detalle
        obtenerdetalleOrden(id_add_orden);
    });
}



//Función limpiar
function limpiar() 
{
    $("#id_add_orden").val("");
    $("#idcategoria").val("1");
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


    $("#idcotizacion").val("");
    $("#forma_pago").val("Efectivo");
    $("#forma_pago").selectpicker('refresh'); 

    $("#tipo_comprobante").val("Envio");
    $("#tipo_comprobante").selectpicker('refresh');

    $("#total_venta").val("");
    $("#total_ventades").val("");

    $("#cefectivo").val("0");
    $("#ccredito").val("0");
    $("#ctransferencia").val("0");
    $("#ctarjeta").val("0");      
    $("#rescambio").val("0");  

    $('#myModalImpresionFAc').modal('hide');

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
function listarMesas() 
{
    tabla=$('#tbllistadoMesas').dataTable(
    {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [                

                ], 
        "ajax": 
                {
                    url: '../ajax/toma_orden.php?op=listarMesas', 
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

function listarOrddenes() 
{
    tabla=$('#tbllistadoOrdenes').dataTable(
    {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [                
  
                ],
        "ajax": 
                {
                    url: '../ajax/toma_orden.php?op=listarOrddenes',
                    type : "get", 
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 4, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}


function asignaciontomaorden(idmesa)
{
        mostrarform(true);
        $("#idmesa").val(idmesa);
 
}



// ... existing code ...

function listarArticulosxcategoria() {
    let idCategoria = $("#idcategoria").val();
    console.log("ID Categoría seleccionado:", idCategoria);

    $.get("../ajax/venta.php?op=listarArticulosxcategoria", { idcategoria: idCategoria },
        function(data) {
            try {
                var html = "Busqueda:<input type='text' class='form-control search-input' onkeyup='busquedaArticulo(this)' placeholder='Buscar artículo...' ><br><br>";
                html += '<div id="productos-container">'; // Agregamos un contenedor para los productos
                var json = JSON.parse(data);

                json.forEach(element => {
                    html += `<div class="producto-grid col-lg-4 col-sm-4 col-md-4 col-xs-4" data-name="${element[1].toLowerCase()}">
                        ${element[3]}<br>
                        ${element[1]} <br>${element[2]}<br>
                        ${element[0]}
                    </div>`;
                });
                
                html += '</div>'; // Cerramos el contenedor
                $("#listadoregistros2").html(html);
            } catch (e) {
                console.error("Error al procesar los datos:", e);
            }
        }
    );
}

function busquedaArticulo(input) {
    const searchText = input.value.toLowerCase();
    const productos = document.querySelectorAll('#productos-container .producto-grid');
    
    productos.forEach(producto => {
        const nombre = producto.getAttribute('data-name');
        if (nombre.includes(searchText)) {
            producto.style.display = '';
        } else {
            producto.style.display = 'none';
        }
    });
}

// ... existing code ...


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

    $.post("../ajax/venta.php?op=listarArticulosVenta2",{},function(data){
         // console.table(data)
      $("#txtbusquedaarticulo").autocomplete({
          minLength: 0, 
          source: function( request, response ) {
                response($.grep(JSON.parse(data), function (value) {
                    try {
                        // Convertimos ambas cadenas a minúsculas para comparar
                        var termLower = request.term.toLowerCase();
                        var valueLower = value.value.toLowerCase();

                        var splitterm = termLower.split(" ");
                        if (splitterm.length == 1) {
                            if (valueLower.indexOf(splitterm[0]) != -1) {
                                return value;
                            }
                        }
                        if (splitterm.length == 2) {
                            var bol1 = valueLower.indexOf(splitterm[0]) != -1;
                            var bol2 = valueLower.indexOf(splitterm[1]) != -1;
                            if (bol1 && bol2) {
                                return value;
                            }
                        }
                        if (splitterm.length == 3) {
                            var bol1 = valueLower.indexOf(splitterm[0]) != -1;
                            var bol2 = valueLower.indexOf(splitterm[1]) != -1;
                            var bol3 = valueLower.indexOf(splitterm[2]) != -1;
                            if (bol1 && bol2 && bol3) {
                                return value;
                            }
                        }
                        if (splitterm.length == 4) {
                            var bol1 = valueLower.indexOf(splitterm[0]) != -1;
                            var bol2 = valueLower.indexOf(splitterm[1]) != -1;
                            var bol3 = valueLower.indexOf(splitterm[2]) != -1;
                            var bol4 = valueLower.indexOf(splitterm[3]) != -1;
                            if (bol1 && bol2 && bol3 && bol4) {
                                return value;
                            }
                        }
                    } catch (ex) {
                        alert(ex);
                    }
                }));
          },
          html: true, 
          focus: function( event, ui ) {
          },
          select: function (event, ui) {
            agregarDetalle(ui.item.idarticulo,ui.item.nombre,
                            ui.item.precio_venta,
                            ui.item.stock,
                            ui.item.descuento_porcentaje,
                            ui.item.stock_unidad,
                            ui.item.precio_unidad,
                            ui.item.stock_blister,
                            ui.item.precio_blister,
                            ui.item.stock_caja,
                            ui.item.precio_caja,
                            ui.item.stock_fardo,
                            ui.item.precio_fardo,
                            ui.item.stock_sacos,
                            ui.item.precio_sacos,
                            ui.item.stock_paquete,
                            ui.item.precio_paquete,
                            ui.item.precio_rango1,
                            ui.item.precio_rango2,
                            ui.item.precio_rango3);
        setTimeout(function() {
                    $("#txtbusquedaarticulo").val("");
                }, 300);
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            // Si no hay imagen, usa la imagen por defecto 'nofoto.jpg'
            var imgSrc = item.img ? '../files/articulos/' + item.img : '../files/articulos/nofoto.jpg';
            return $("<li>")
                .append("<div><img src='" + imgSrc + "' style='width:100px'> " + item.nombre + "</div>")
                .appendTo(ul);
        };
    });


}


//Función para guardar o editar

function guardaryeditar(e)
{

    if (detalles>0)
    {
      
    }
    else
    {
        alert("No se puede Guardar porque no has agregado item a tu venta ");
        return;
    }

    agruparDatos();
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    formData.append('datos1', $("#datos1").val());
    load(); 
    $.ajax({
        url: "../ajax/toma_orden.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {


            Swal.fire({
                title: 'Mensaje!',
                text: "Operacion Realizada",
                icon: 'success',
                timer: 2000, // 2 segundos
                timerProgressBar: true,
                willClose: () => {
                    Swal.close(); 
                    window.location.reload();
                }
            });

        }

    });

}
function impresiondeformatos(id_add_orden) {

      $('#myModalImpresionFAc').modal('show'); 
        $("#idventa_impresion").val(id_add_orden);

}


function impresionticket58mm() {

            var url = "../reportes/exTicket58mm_tomaOrden.php?id=" + $("#idventa_impresion").val();



    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function() {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario)
}




function abrirVentanaetiqueta(url) {
    // Tamaño deseado para la ventana (ajústalo según tus necesidades)
    var ancho = 800;
    var alto = 600;

    // Calcular la posición central de la pantalla
    var left = (screen.width / 2) - (ancho / 2);
    var top = (screen.height / 2) - (alto / 2);

    // Abrir la ventana con el tamaño y posición especificados
    window.open(url, '_blank', 'width=' + ancho + ',height=' + alto + ',left=' + left + ',top=' + top);
}

function agruparDatos() {
    const datos = {
        idarticulo: [],
        stockinven: [],        
        cantidadpresentacion: [],
        cantidad: [],
        totalcantidadpresentacion: [],
        presen: [],
        precio_ventaSistema: [],
        precio_ventaSistema2: [],
        q_ref: [],
        precio_venta: [],
        precio_recargoPV: [],
        precio_recargoQRef: [],
        descuento_porcentaje: [],
        subtotal1: [],
        subtotaldes1: [],
        comentarios: []
    };
    $('#detalles .filas').each(function() {
        datos.idarticulo.push($(this).find('input[name="idarticulo[]"]').val());
        datos.stockinven.push($(this).find('input[name="stockinven[]"]').val());        
        datos.cantidadpresentacion.push($(this).find('input[name="cantidadpresentacion[]"]').val());
        datos.cantidad.push($(this).find('input[name="cantidad[]"]').val());
        datos.totalcantidadpresentacion.push($(this).find('input[name="totalcantidadpresentacion[]"]').val());
        datos.presen.push($(this).find('input[name="presen[]"]').val());
        datos.precio_ventaSistema.push($(this).find('input[name="precio_ventaSistema[]"]').val());
        datos.precio_ventaSistema2.push($(this).find('input[name="precio_ventaSistema2[]"]').val());
        datos.q_ref.push($(this).find('input[name="q_ref[]"]').val());
        datos.precio_venta.push($(this).find('input[name="precio_venta[]"]').val());
        datos.precio_recargoPV.push($(this).find('input[name="precio_recargoPV[]"]').val());
        datos.precio_recargoQRef.push($(this).find('input[name="precio_recargoQRef[]"]').val());
        datos.descuento_porcentaje.push($(this).find('input[name="descuento_porcentaje[]"]').val());
        datos.subtotal1.push($(this).find('input[name="subtotal1[]"]').val());
        datos.subtotaldes1.push($(this).find('input[name="subtotaldes1[]"]').val());
        datos.comentarios.push($(this).find('input[name="comentarios[]"]').val());

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




// Función para anular registros
function anular(id_add_orden) {
    // Mostrar el modal de SweetAlert para solicitar la contraseña
    Swal.fire({
        title: 'Ingresa la contraseña de autorización',
        input: 'password', // Tipo de entrada: contraseña
        inputPlaceholder: 'Contraseña',
        inputAttributes: {
            maxlength: 20,
            autocapitalize: 'off',
            autocorrect: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes ingresar una contraseña';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const valor = result.value;

            // Verificar si la contraseña es correcta
            if (valor === "5820005710") {
                // Solicitar el motivo de anulación
                Swal.fire({
                    title: 'Motivo de anulación',
                    input: 'text',
                    inputPlaceholder: 'Ingresa el motivo de la anulación',
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Debes ingresar un motivo para anular la orden';
                        }
                    }
                }).then((motivoResult) => {
                    if (motivoResult.isConfirmed) {
                        const motivo = motivoResult.value;

                        // Confirmar la anulación con SweetAlert
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: `Esta acción anulará la orden de la mesa. Motivo: ${motivo}`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, anular',
                            cancelButtonText: 'Cancelar'
                        }).then((confirmResult) => {
                            if (confirmResult.isConfirmed) {
                                // Mostrar un mensaje de carga mientras se procesa la anulación
                                Swal.fire({
                                    title: 'Anulando...',
                                    text: 'Por favor, espera.',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                // Hacer la solicitud al servidor para anular la venta
                                $.post("../ajax/toma_orden.php?op=anular", 
                                    { id_add_orden: id_add_orden, motivo: motivo }, 
                                    function(e) {
                                        Swal.fire({
                                            title: 'Mensaje!',
                                            text: e,
                                            icon: 'success',
                                            timer: 2000, // 2 segundos
                                            timerProgressBar: true,
                                            willClose: () => {
                                                // Recargar la página después del éxito
                                                window.location.reload();
                                            }
                                        });
                                    }).fail(function() {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'Ocurrió un problema al anular la orden.',
                                            icon: 'error'
                                        });
                                    });
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Contraseña no válida',
                    text: 'La contraseña ingresada no es correcta.',
                    icon: 'error'
                });
            }
        }
    });
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



function agregarDetalle(idarticulo,nombre,precio_venta,stock,descuento_porcentaje,stock_unidad,precio_unidad,
    stock_blister,precio_blister,stock_caja,precio_caja,stock_fardo,precio_fardo,stock_sacos,precio_sacos,stock_paquete,precio_paquete,precio_rango1,precio_rango2,precio_rango3)
{

    var cantidad=1; 
    var stockinven=stock;
    var presen='UNIDAD';
    var cantidadpresentacion=1; 
    var totalcantidadpresentacion=1; 
    idarticulo=idarticulo.toString().trim(); 
    var subtotaldes=0;    
    if (idarticulo!="")
    { 
        var exist=false;
        $('#detalles').children("tbody").children("tr").each(function(index){
           var idart=$(this).attr("data-id")
           if(idart==idarticulo){
            exist=true;
           }
        })      

        if(!exist) 
        {
            var subtotal=cantidad*precio_venta;
            var fila='<tr class="filas" data-id="'+idarticulo+'" id="fila'+cont+'">'+
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button><button type="button" class="btn btn-warning" onclick="agregarcomentarioplatillo('+cont+')">+</button></td>'+
            '<td><input type="hidden" name="stockinven[]" value="'+stockinven+'"><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+nombre+'</td>'+
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion'+cont+'" name="cantidadpresentacion[]" value="'+cantidadpresentacion+'" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango('+cont+','+precio_rango1+','+precio_rango2+','+precio_rango3+',this)"  type="number" step="any"   id="cxcantidad'+idarticulo+'" name="cantidad[]" id="cantidad'+cont+'" value="'+cantidad+'"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion'+cont+'" name="totalcantidadpresentacion[]" value="'+totalcantidadpresentacion+'" onchange="modificarSubototales()"></td>'+
        
            `<td><input  type="hidden"  name="presen[]" id="presen`+cont+`"value="`+presen+`" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+cont+`" value="`+precio_venta+`">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+cont+`" value="`+precio_venta+`">
                <input class="form-control" style="width:75px" type="number" step="any" name="q_ref[]" id="q_ref`+cont+`" value="`+precio_venta+`"  readonly >
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+cont+`" value="`+precio_venta+`" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+cont+`" value="0" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+cont+`" value="0" ></td>`+              
            '<td><input onchange="modificarSubototales()" class="form-control" type="hidden" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+        
            '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes'+cont+'">'+subtotaldes+'</span></td>'+
            '<td><input type="hidden" name="comentarios[]" id="comentario' + cont + '"><span id="comentarioTexto' + cont + '">Sin comentario</span></td>' +
            '</tr>'; 
            cont++;
            detalles=detalles+1; 
            $('#detalles').append(fila);
        }else{
            var cxcantidad=parseInt($("#cxcantidad"+idarticulo).val())+1
            $("#cxcantidad"+idarticulo).val(cxcantidad)
              // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}



function obtenerdetalleOrden(id_add_orden)
{
    $.post("../ajax/toma_orden.php?op=detalleorden",{id_add_orden:id_add_orden},function(data){
        //console.log(data);
        data = JSON.parse(data);           
        Swal.close() 

        $.each(data, function(i, item) {
            agregarDetalle2(item.idarticulo,item.nombre,item.precio_venta,item.stock,item.descuento,item.stock_unidad,item.precio_unidad,
                item.stock_blister,item.precio_blister,item.stock_caja,item.precio_caja,item.stock_fardo,item.precio_fardo,item.stock_sacos,
                item.precio_sacos,item.stock_paquete,item.precio_paquete,item.cantidad,item.presen,item.cantidadpresentacion,item.totalcantidadpresentacion,
                item.precio_ventaSistema,item.precio_ventaSistema2,item.q_ref,item.precio_recargoPV,item.precio_recargoQRef,item.precio_rango1,
                item.precio_rango2,item.precio_rango3,item.iddetalle_add_orden,item.comentarios);
        });
    })
}

function agregarDetalle2(idarticulo,nombre,precio_venta,stock,descuento_porcentaje,stock_unidad,precio_unidad,
    stock_blister,precio_blister,stock_caja,precio_caja,stock_fardo,precio_fardo,stock_sacos,precio_sacos,
    stock_paquete,precio_paquete,cantidad,presen,cantidadpresentacion,totalcantidadpresentacion,precio_ventaSistema,
    precio_ventaSistema2,q_ref,precio_recargoPV,precio_recargoQRef,precio_rango1,precio_rango2,precio_rango3,iddetalle_add_orden,comentarios)
{

    comentarios = comentarios || ""; // Aseguramos un valor por defecto
    comentarios = comentarios.replace(/"/g, '&quot;').replace(/'/g, '&#39;'); // Escapamos caracteres especiales


    var stockinven=stock;
    idarticulo=idarticulo.toString().trim(); 
    var subtotaldes=0;    
    if (idarticulo!="")
    { 
        var exist=false;
        $('#detalles').children("tbody").children("tr").each(function(index){
           var idart=$(this).attr("data-id")
           if(idart==idarticulo){
            exist=true;
           }
        })      

        if(!exist) 
        {
            var subtotal=cantidad*precio_venta;
            var fila='<tr class="filas" data-id="'+idarticulo+'" id="fila'+cont+'">'+
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle2('+cont+','+iddetalle_add_orden+')">X</button><button type="button" class="btn btn-warning" onclick="agregarcomentarioplatillo('+cont+')">+</button></td>'+
            '<td><input type="hidden" name="stockinven[]" value="'+stockinven+'"><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+nombre+'</td>'+
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion'+cont+'" name="cantidadpresentacion[]" value="'+cantidadpresentacion+'" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango('+cont+','+precio_rango1+','+precio_rango2+','+precio_rango3+',this)"  type="number" step="any"   id="cxcantidad'+idarticulo+'" name="cantidad[]" id="cantidad'+cont+'" value="'+cantidad+'"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion'+cont+'" name="totalcantidadpresentacion[]" value="'+totalcantidadpresentacion+'" onchange="modificarSubototales()"></td>'+
        
            `<td><input  type="hidden"  name="presen[]" id="presen`+cont+`"value="`+presen+`" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+cont+`" value="`+precio_ventaSistema+`">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+cont+`" value="`+precio_ventaSistema2+`">
                <input class="form-control" style="width:75px" type="number" step="any" name="q_ref[]" id="q_ref`+cont+`" value="`+q_ref+`"  readonly >
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+cont+`" value="`+precio_venta+`" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+cont+`" value="`+precio_recargoPV+`" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+cont+`" value="`+precio_recargoQRef+`"></td>`+              
            '<td><input onchange="modificarSubototales()" class="form-control" type="hidden" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+        
            '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes'+cont+'">'+subtotaldes+'</span></td>'+
            '<td><input type="hidden" name="comentarios[]" id="comentario' + cont + '" value="' + comentarios + '"><span id="comentarioTexto' + cont + '">' + (comentarios || 'Sin comentario') + '</span></td>' +

            '</tr>'; 
            cont++;
            detalles=detalles+1; 
            $('#detalles').append(fila);
        }else{
            var cxcantidad=parseInt($("#cxcantidad"+idarticulo).val())+1
            $("#cxcantidad"+idarticulo).val(cxcantidad)
              // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function agregarcomentarioplatillo(cont) {
    Swal.fire({
        title: 'Agregar comentario',
        input: 'text',
        inputLabel: 'Escribe un comentario a Item:',
        inputPlaceholder: 'Ej. Nota importante',
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var comentario = result.value || "Sin comentario";
            $('#comentario' + cont).val(comentario); // Guarda el comentario en el input oculto
            $('#comentarioTexto' + cont).text(comentario); // Muestra el comentario en la fila
        }
    });
}



function presentacionoculatardatos(id,precio_venta,stock_unidad,precio_unidad,stock_blister,precio_blister,
                                stock_caja,precio_caja,stock_fardo,precio_fardo,stock_sacos,
                                precio_sacos,stock_paquete,precio_paquete) 
{
    var presentacion=$("#presentacionselect"+id).val();
    if (presentacion=='UNIDAD') 
    {
        var precioventaunidad = 0;
        if(stock_unidad>0) 
        {
            precioventaunidad = (precio_unidad / stock_unidad); // Redondear a 2 decimales
        }        
        $("#cantidadpresentacion"+id).val(stock_unidad);
        $("#precio_venta"+id).val(precioventaunidad);
        $("#q_ref"+id).val(precio_unidad);
        $("#presen"+id).val("UNIDAD");

         $("#precio_ventaSistema"+id).val(precioventaunidad);        
         $("#precio_ventaSistema2"+id).val(precio_unidad);        
    } 
    else if (presentacion=='BLISTER') 
    {
        var precioblister = 0;
        if(stock_blister>0) 
        {
            // Calcula el precio por unidad y redondea a 2 decimales
            precioblister = ((precio_blister / stock_blister));
        }  
        $("#cantidadpresentacion"+id).val(stock_blister);
        $("#precio_venta"+id).val(precioblister);
        $("#q_ref"+id).val(precio_blister);
        $("#presen"+id).val("BLISTER");

         $("#precio_ventaSistema"+id).val(precioblister);        
         $("#precio_ventaSistema2"+id).val(precio_blister);           
    }  
    else if (presentacion=='CAJA') 
    {
        var precioventacaja = 0;
        if(stock_caja>0) 
        {
            precioventacaja = (precio_caja / stock_caja); // Redondear a 2 decimales
        }        
        $("#cantidadpresentacion"+id).val(stock_caja);
        $("#precio_venta"+id).val(precioventacaja);
        $("#q_ref"+id).val(precio_caja);
        $("#presen"+id).val("CAJA");

         $("#precio_ventaSistema"+id).val(precioventacaja);        
         $("#precio_ventaSistema2"+id).val(precio_caja);             
    }  
    else if (presentacion=='FARDO') 
    {

        var precioventafardo = 0;
        if(stock_fardo>0) 
        {
            precioventafardo = (precio_fardo / stock_fardo); // Redondear a 2 decimales
        }        
        $("#cantidadpresentacion"+id).val(stock_fardo);
        $("#precio_venta"+id).val(precioventafardo);
        $("#q_ref"+id).val(precio_fardo);
        $("#presen"+id).val("FARDO");

         $("#precio_ventaSistema"+id).val(precioventafardo);        
         $("#precio_ventaSistema2"+id).val(precio_fardo);            
    }   

    else if (presentacion=='SACOS') 
    {
        var precioventasacos = 0;
        if(stock_sacos>0) 
        {
            precioventasacos = (precio_sacos / stock_sacos); // Redondear a 2 decimales
        }        
        $("#cantidadpresentacion"+id).val(stock_sacos);
        $("#precio_venta"+id).val(precioventasacos);
        $("#q_ref"+id).val(precio_sacos);
        $("#presen"+id).val("SACOS");

         $("#precio_ventaSistema"+id).val(precioventasacos);        
         $("#precio_ventaSistema2"+id).val(precio_sacos);           
    }    
    else if (presentacion=='PAQUETE') 
    {
        var precioventapaquete = 0;
        if(stock_paquete>0) 
        {
            precioventapaquete = (precio_paquete / stock_paquete); // Redondear a 2 decimales
        }        
        $("#cantidadpresentacion"+id).val(stock_paquete);
        $("#precio_venta"+id).val(precioventapaquete);
        $("#q_ref"+id).val(precio_paquete);
        $("#presen"+id).val("PAQUETE");

        
         $("#precio_ventaSistema"+id).val(precioventapaquete);        
         $("#precio_ventaSistema2"+id).val(precio_paquete);           
    }                                      

    modificarSubototalesTarjetaEfectivo();
}


function modificarSubototalesTarjetaEfectivo()
{
    var cant = document.getElementsByName("cantidad[]");
    var qref = document.getElementsByName("q_ref[]");
    var precioventa = document.getElementsByName("precio_venta[]");
    var precSistema = document.getElementsByName("precio_ventaSistema[]");
    var precSistema2 = document.getElementsByName("precio_ventaSistema2[]");
    var valortarjeta = $("#valor_tarjeta").val();


    for (var i = 0; i <cant.length; i++) {
        var inpC=cant[i];
        var inpP=qref[i];
        var inpPPV=precioventa[i];
        var inpPSistema=precSistema[i]; 
        var inpPSistema2=precSistema2[i]; 

  

        if (parseFloat(valortarjeta)>0) {
            ////calculo de qref
            var resCanculotarjeta1=(((inpPSistema2.value *valortarjeta)/100));
            var resutaltadoCanculotarjeta1=(parseFloat(resCanculotarjeta1))
            document.getElementsByName("precio_recargoQRef[]")[i].value = resutaltadoCanculotarjeta1;

            document.getElementsByName("q_ref[]")[i].value = (parseFloat(inpPSistema2.value) +parseFloat(resutaltadoCanculotarjeta1));


            ///calculo de precio venta
            var resCanculotarjeta2=(((inpPSistema.value *valortarjeta)/100));
            var resutaltadoCanculotarjeta2=(parseFloat(resCanculotarjeta2))
            document.getElementsByName("precio_recargoPV[]")[i].value = resutaltadoCanculotarjeta2;  

            document.getElementsByName("precio_venta[]")[i].value =  (parseFloat(inpPSistema.value) +parseFloat(resutaltadoCanculotarjeta2)); 


        }else{

            ////calculo de qref
            var resCanculotarjeta11=inpPSistema2.value;
            var resutaltadoCanculotarjeta11=(parseFloat(resCanculotarjeta11))
            document.getElementsByName("precio_recargoQRef[]")[i].value = 0;
            document.getElementsByName("q_ref[]")[i].value = (parseFloat(resutaltadoCanculotarjeta11));


            ///calculo de precio venta
            var resCanculotarjeta22=inpPSistema.value;
            var resutaltadoCanculotarjeta22=(parseFloat(resCanculotarjeta22))
            document.getElementsByName("precio_recargoPV[]")[i].value = 0;  
            document.getElementsByName("precio_venta[]")[i].value = (parseFloat(resutaltadoCanculotarjeta22)); 
        }

   }

   modificarSubototales();
} 



function modificarSubototalesxrango(id,precio_rango1,precio_rango2,precio_rango3)
{


    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var qref = document.getElementsByName("q_ref[]");
    var precSistema = document.getElementsByName("precio_ventaSistema[]");
    var precioventaSistema2 = document.getElementsByName("precio_ventaSistema2[]");
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");    
    var subdes1 = document.getElementsByName("subtotaldes1[]");
    var sub1 = document.getElementsByName("subtotal1[]");   
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]")); 
    var pRecargo = (document.getElementsByName("precio_recargoQRef[]")); 
    var presen = (document.getElementsByName("presen[]")); 

    for (var i = 0; i <cant.length; i++) {
        var inpC=cant[i];
        var inpP=prec[i];
        var inpPqref=qref[i];
        var inpPSistema=precSistema[i];
        var inpPSistema2=precioventaSistema2[i];
        var inpD=desc[i];
        var inpS=sub[i];
        var inpSdes=subdes[i];        
        var inpSdes1=subdes1[i];  
        var inpS1=sub1[i];  
        var inpCpre=cantpre[i]; 
        var inpTpres=tprese[i];        
        var inppRecargo=pRecargo[i];   
        var inpPresen=presen[i];   

        inpTpres.value=parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;
        


        if (inpPresen.value === 'UNIDAD') {
            // Ajustamos el rango para utilizar condiciones AND (`&&`) en lugar de OR (`||`)
            if (parseFloat(inpTpres.value) <=1 ) 
            {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;


            } else if (parseFloat(inpTpres.value) > 1 && parseFloat(inpTpres.value) <= 3) {



                if (parseFloat(precio_rango1)>0) {

                    document.getElementsByName("q_ref[]")[i].value = precio_rango1;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango1;
                }else{
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }


            }  
            else if (parseFloat(inpTpres.value) > 3 && parseFloat(inpTpres.value) <= 6) 
            {


                if (parseFloat(precio_rango2)>0) {

                document.getElementsByName("q_ref[]")[i].value = precio_rango2;
                document.getElementsByName("precio_venta[]")[i].value = precio_rango2;
                }else{
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }


            } 
            else if (parseFloat(inpTpres.value) > 6) 
            {


                if (parseFloat(precio_rango3)>0) {

                document.getElementsByName("q_ref[]")[i].value = precio_rango3;
                document.getElementsByName("precio_venta[]")[i].value = precio_rango3;
                }else{
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }

            }
        } else {
            // Este `else` solo se ejecutará si `presen` no es `UNIDAD`
            document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
            document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
        }


 




        inpS.value=(inpTpres.value* (inpP.value - ((inpP.value *inpD.value)/100)));
        document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
        inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

        inpSdes.value=(((inpP.value *inpD.value)/100)*inpTpres.value);
        document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
        inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
       //console.log(inpSdes.value);
   }

   calcularTotales();
   calcularTotalesdes();
}


function modificarSubototales()
{
    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var qref = document.getElementsByName("q_ref[]");
    var precSistema = document.getElementsByName("precio_ventaSistema[]");
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");    
    var subdes1 = document.getElementsByName("subtotaldes1[]");
    var sub1 = document.getElementsByName("subtotal1[]");   
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]")); 
    var pRecargo = (document.getElementsByName("precio_recargoQRef[]")); 
    


 

    for (var i = 0; i <cant.length; i++) {
        var inpC=cant[i];
        var inpP=prec[i];
        var inpPqref=qref[i];
        var inpPSistema=precSistema[i];
        var inpD=desc[i];
        var inpS=sub[i];
        var inpSdes=subdes[i];        
        var inpSdes1=subdes1[i];  
        var inpS1=sub1[i];  

        var inpCpre=cantpre[i]; 
        var inpTpres=tprese[i];        

        var inppRecargo=pRecargo[i];        

        inpTpres.value=parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;


        inpS.value=(inpTpres.value* (inpP.value - ((inpP.value *inpD.value)/100)));
        document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
        inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

        inpSdes.value=(((inpP.value *inpD.value)/100)*inpTpres.value);
        document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
        inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
       //console.log(inpSdes.value);
   }

   calcularTotales();
   calcularTotalesdes();
} 

function calcularTotalesdes() {
    var chks = document.getElementsByName('subtotaldes');
    var total = 0.0;

    for(var i = 0; i < chks.length; i++) {
        var valor = parseFloat(chks[i].textContent || chks[i].innerHTML); // Obtener el texto de la etiqueta y convertirlo a número
        if (!isNaN(valor)) {
            total += valor;
        }
    }

   // console.log('subtotaldes'+total)

    $("#totaldes").html("Q. " + total.toFixed(2)); // Formatear el total a dos decimales
    $("#total_ventades").val(total.toFixed(2)); // Asignar el valor formateado al campo de entrada
    evaluar(); // Llamar la función evaluar si es necesario
}



function calcularTotales() {

    var propina=$("#propina").val();
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        var valor = parseFloat(sub[i].textContent || sub[i].innerHTML); // Obtener el valor del subtotal como texto y convertirlo a número
        if (!isNaN(valor)) {
            total += valor;
        }
    }

   // console.log('subtotal'+total)

    var totalconpropina=parseFloat(propina)+total;

    $("#total").html("Q. " + totalconpropina.toFixed(2)); // Mostrar el total en el HTML
    $("#total_venta").val(totalconpropina.toFixed(2)); // Asignar el valor formateado al campo de entrada
    evaluar(); // Llamar la función evaluar si es necesario
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

function eliminarDetalle(indice)
{
 

        $("#fila" + indice).remove();
        calcularTotales();
        detalles=detalles-1;
        evaluar()
      
}


function eliminarDetalle2(indice,iddetalle_add_orden)
{

     var valor = prompt("Ingresa la contraseña de de Autorizacion", "");
    if( valor == "5820005710" )
    {   

        $("#fila" + indice).remove();
        calcularTotales();
        detalles=detalles-1;
        evaluar()
        var valormotivo = prompt("Motivo de anulacion Orden", "");  
        eliminarOrdendetallebitacora(iddetalle_add_orden,valormotivo) 
    }
     else
    {
        alert("Contraseña no válida: [" + valor + "]");
    }        
}


function eliminarOrdendetallebitacora(iddetalle_add_orden,valormotivo)
{

            $.post("../ajax/toma_orden.php?op=eliminarOrdendetallebitacora", {iddetalle_add_orden : iddetalle_add_orden,valormotivo : valormotivo}, function(e){
                bootbox.alert(e);
            }); 
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
               
                var arrayproduc= res.split("@");

                 //console.log(arrayproduc);

                if(arrayproduc[0]!="undefined"){
                 agregarDetalle(arrayproduc[0],arrayproduc[1],arrayproduc[2],arrayproduc[3],arrayproduc[4],arrayproduc[5],arrayproduc[6],arrayproduc[7],arrayproduc[8],arrayproduc[9],arrayproduc[10]
                    ,arrayproduc[11],arrayproduc[12],arrayproduc[13],arrayproduc[14],arrayproduc[15],arrayproduc[16],arrayproduc[17],arrayproduc[18],arrayproduc[19]); 
             }
             $("#txtbusquedaartcodebar").val("");
             $("#txtbusquedaartcodebar").focus() 

         })
        }

    }, 200)


    listarArticulos();



})