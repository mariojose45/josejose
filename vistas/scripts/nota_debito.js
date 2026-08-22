var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(true);
    listar();

    $("#formulario").on("submit", function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });

    $("#btnGuardar2").click(function (e) {
        guardaryeditar2(e);
    });
    var idCargado = null;
    $("#btncargar").click(function () {

        var idcotizacion = $("#idcotizacion").val();
        if (idcotizacion == "") {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Debe Colocar un Id de Cotizacion Valido",
                showConfirmButton: false,
                timer: 1500,
            });
            // alert("Debe Colocar un Id de Cotizacion Valido")
            return;
        }

        // Verifica si el ID ya fue cargado
        if (idCargado === idcotizacion) {
            //alert("Ya se ha cargado la información para este ID.");
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Ya se ha cargado la información para este ID.",
                showConfirmButton: false,
                timer: 1500,
            });
            return;
        }

        // Actualiza la variable de control
        idCargado = idcotizacion;
        obtenerIngreso(idcotizacion);

    });

}


/////CLIENTE NUEVO

function validarnit() {


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

function buscarnitenSistemaparaIdcliente(nit) {
    var nombre_cliente = $("#nombre_cliente").val();
    $.post("../ajax/consultas.php?op=buscarnitenSistemaparaIdcliente", { nit: nit }, function (data, status) {
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


function validarnitNombre() {

    var nombre_cliente = $("#nombre_cliente").val();

    $.post("../ajax/consultas.php?op=validarnitNombre", { nombre_cliente: nombre_cliente }, function (data, status) {
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


function validarCodigo() {

    var codigo_cliente = $("#codigo_cliente").val();
    $.post("../ajax/venta.php?op=validarCodigo", { codigo_cliente: codigo_cliente }, function (data, status) {
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


function listartbBusquedaCliente() {


    $('#myModalBusquedacliente').modal('show');
    tabla = $('#tbBusquedaCliente').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/consultas.php?op=listartbBusquedaCliente',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();

}


function RespuestavalidarnitNombre(idpersona, nombre, num_documento, direccion, telefono, email,
    tipo_documento, codigo_cliente) {
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


function limpiardatoscliente() {
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

function validarnitTelefono() {
    var telefono_cliente = $("#telefono_cliente").val();
    $.post("../ajax/venta.php?op=validarnitTelefono", { telefono_cliente: telefono_cliente }, function (data, status) {
        // console.log(data)

        data = JSON.parse(data);

        $("#nit").val(data.num_documento);
        $("#nombre_cliente").val(data.nombre);
        $("#direccion_cliente").val(data.direccion);
        $("#correo_cliente").val(data.email);
        $("#tipo_documento_cliente").val(data.tipo_documento);
        $("#tipo_documento_cliente").selectpicker('refresh');

    })
}


////fin de cliente  

function load() {
    Swal.fire({
        title: 'Espere un momento . . . ',
        allowOutsideClick: false,
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

function calculardiascredito() {

    var numero = document.getElementById('dias_credito');
    //la fecha
    var TuFecha = new Date();

    //dias a sumar
    var dias = parseInt(numero.value);

    //nueva fecha sumada
    TuFecha.setDate(TuFecha.getDate() + dias);
    //formato de salida para la fecha
    var resultado = TuFecha.getDate() + '/' +
        (TuFecha.getMonth() + 1) + '/' + TuFecha.getFullYear();
    $("#fecha_hora_pago_credito").val(resultado);

}



//Función limpiar   
function limpiar() {


    $("#idingreso").val("");
    $("#datos1").val("");
    $("#codigo_cliente").val("");
    $("#nit").val("CF");
    $("#nombre_cliente").val("CONSUMIDOR FINAL");
    $("#telefono_cliente").val("");
    $("#correo_cliente").val("0");
    $("#idcliente").val("1");
    $("#tipo_documento_cliente").val("NIT");
    $("#tipo_documento_cliente").selectpicker('refresh');
    $("#tipo_ingreso_producion").val("Producto");
    $("#tipo_ingreso_producion").selectpicker('refresh');

    $("#tipo_comprobante").val("Poliza");
    $("#tipo_comprobante").selectpicker('refresh');

    $("#serie_comprobante").val("0");
    $("#num_comprobante").val("0");
    $("#impuesto").val("0");
    $("#total_compra").val("0");
    $("#total_comprades").val("0");

    $("#forma_pago").val("Efectivo");
    $("#forma_pago").selectpicker('refresh');

    $("#dias_credito").val("0");
    $("#direccion_entrega_orden_compra").val("0");
    $("#observacion_orden_compra").val("0");



    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);
    $('#fecha_hora_pago_credito').val(today);
    $('#fecha_entrega_orden_compra').val(today);
    $('#fecha_hora_ND').val(today);



}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        // listarArticulos();

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles = 0;
        $("#btnAgregarArt").show();
    }
    else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        //$("#btnGuardar").show();
    }

}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función Listar
function listar() {
    var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
    var fecha_fin_reporte = $("#fecha_fin_reporte").val();
    tabla = $('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    attr: { id: 'btnExcel' }
                },

                {
                    extend: 'pdfHtml5',
                    text: 'Exportar PDF',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    attr: { id: 'btnPdf' },
                    exportOptions: { columns: ':visible' },
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 7;
                        doc.styles.tableHeader.fontSize = 8;
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;
                        table.widths = new Array(columnCount).fill('*');
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Column visibility',
                    attr: { id: 'btnColvis' }
                }
            ],
            "ajax":
            {
                url: '../ajax/ingreso.php?op=listarND',
                type: "get",
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte },
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}





//Función para guardar o editar
function agruparDatos() {
    const datos = {
        articulos: {
            idarticulo: [],
            stockinven: [],
            cantidadpresentacion: [],
            cantidad: [],
            totalcantidadpresentacion: [],
            presentacion: [],
            precio_compra: [],
            descuento_porcentaje: [],
            precio_venta: [],
            precio_ventaNocturno: [],
            precio_rango1: [],
            precio_rango2: [],
            precio_rango3: [],
            precio_unidad: [],
            precio_blister: [],
            precio_caja: [],
            precio_fardo: [],
            precio_sacos: [],
            precio_paquete: [],
            idsucursalDestino: []
        }
    };

    $('#detalles .filas').each(function () {
        const idarticulo = $(this).find('input[name="idarticulo[]"]').val();
        const stockinven = $(this).find('input[name="stockinven[]"]').val();
        const cantidadpresentacion = $(this).find('input[name="cantidadpresentacion[]"]').val();
        const cantidad = $(this).find('input[name="cantidad[]"]').val();
        const totalcantidadpresentacion = $(this).find('input[name="totalcantidadpresentacion[]"]').val();
        const presentacion = $(this).find('select[name="presentacion[]"]').val();
        const precio_compra = $(this).find('input[name="precio_compra[]"]').val();
        const descuento_porcentaje = $(this).find('input[name="descuento_porcentaje[]"]').val();
        const precio_venta = $(this).find('input[name="precio_venta[]"]').val();
        const precio_ventaNocturno = $(this).find('input[name="precio_ventaNocturno[]"]').val();
        const precio_rango1 = $(this).find('input[name="precio_rango1[]"]').val();
        const precio_rango2 = $(this).find('input[name="precio_rango2[]"]').val();
        const precio_rango3 = $(this).find('input[name="precio_rango3[]"]').val();
        const precio_unidad = $(this).find('input[name="precio_unidad[]"]').val();
        const precio_blister = $(this).find('input[name="precio_blister[]"]').val();
        const precio_caja = $(this).find('input[name="precio_caja[]"]').val();
        const precio_fardo = $(this).find('input[name="precio_fardo[]"]').val();
        const precio_sacos = $(this).find('input[name="precio_sacos[]"]').val();
        const precio_paquete = $(this).find('input[name="precio_paquete[]"]').val();
        const idsucursalDestino = $(this).find('input[name="idsucursalDestino[]"]').val();


        datos.articulos.idarticulo.push(idarticulo);
        datos.articulos.stockinven.push(stockinven);
        datos.articulos.cantidadpresentacion.push(cantidadpresentacion);
        datos.articulos.cantidad.push(cantidad);
        datos.articulos.totalcantidadpresentacion.push(totalcantidadpresentacion);
        datos.articulos.presentacion.push(presentacion);
        datos.articulos.precio_compra.push(precio_compra);
        datos.articulos.descuento_porcentaje.push(descuento_porcentaje);
        datos.articulos.precio_venta.push(precio_venta);
        datos.articulos.precio_ventaNocturno.push(precio_ventaNocturno);
        datos.articulos.precio_rango1.push(precio_rango1);
        datos.articulos.precio_rango2.push(precio_rango2);
        datos.articulos.precio_rango3.push(precio_rango3);
        datos.articulos.precio_unidad.push(precio_unidad);
        datos.articulos.precio_blister.push(precio_blister);
        datos.articulos.precio_caja.push(precio_caja);
        datos.articulos.precio_fardo.push(precio_fardo);
        datos.articulos.precio_sacos.push(precio_sacos);
        datos.articulos.precio_paquete.push(precio_paquete);
        datos.articulos.idsucursalDestino.push(idsucursalDestino);
    });

    const datosJSON = JSON.stringify(datos);

    localStorage.setItem('datosArticulosND', datosJSON);
    //console.log(datosJSON);
}


function guardaryeditar(e) {
    agruparDatos();
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    let datosArticulosND = JSON.parse(localStorage.getItem('datosArticulosND'));
    formData.append("datosArticulosND", JSON.stringify(datosArticulosND));
    load();
    $.ajax({
        url: "../ajax/ingreso.php?op=guardaryeditarND",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log(datos)
            Swal.fire({
                title: 'Mensaje!',
                text: datos,
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



function obtenerIngreso(idingreso) {
    $.post("../ajax/ingreso.php?op=mostrar", { idingreso: idingreso }, function (data, status) {
        load();
        console.log(data);

        //  {"status":false,"message":"No se puede mostrar la operaci\u00f3n porque ya existe un ingreso posterior registrado."}
        try {
            // Intentamos parsear los datos recibidos
            data = JSON.parse(data);

            // Validar si vino un error (status: false)
            if (data == null) {

            } else if (data.status === false) {
                Swal.fire({
                    title: 'Aviso',
                    text: data.message || "No se puede procesar este ingreso.",
                    icon: 'warning',
                    timer: 2500,
                    timerProgressBar: true,
                });
                return;
            }


            // Validar si el objeto data está vacío o nulo
            if (!data || data === null || Object.keys(data).length === 0) {
                Swal.fire({
                    title: 'Mensaje!',
                    text: "El ingreso no se puede procesar porque ya tiene una nota de débito.",
                    icon: 'success',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true,
                    willClose: () => {
                        window.location.reload();
                    }
                });

                return;
            }

            // Continuar con el llenado de datos si la validación pasa
            mostrarform(true);

            $("#idingreso").val(data.idingreso);
            $("#codigo_cliente").val(data.codigo_cliente);
            $("#nit").val(data.nit);
            $("#nombre_cliente").val(data.nombre_cliente);
            $("#telefono_cliente").val(data.telefono_cliente);
            $("#direccion_cliente").val(data.direccion_cliente);
            $("#correo_cliente").val(data.correo_cliente);
            $("#idcliente").val(data.idcliente);
            $("#tipo_documento_cliente").val(data.tipo_documento_cliente);
            $("#tipo_ingreso_producion").val(data.tipo_ingreso);

            $("#fecha_hora").val(data.fecha);

            $("#tipo_comprobante").val(data.tipo_comprobante);

            $("#serie_comprobante").val(data.serie_comprobante);
            $("#num_comprobante").val(data.num_comprobante);

            $("#impuesto").val(data.impuesto);

            $("#total_compra").val(data.total_compra);
            $("#total_comprades").val(data.total_comprades);

            $("#forma_pago").val(data.forma_pago);

            $("#dias_credito").val(data.dias_credito);
            $("#fecha_hora_pago_credito").val(data.fechahorapagocredito);
            $("#direccion_entrega_orden_compra").val(data.direccion_entrega_orden_compra);

            $("#fecha_entrega_orden_compra").val(data.fechaentregaordencompra);
            $("#observacion_orden_compra").val(data.observacion_orden_compra);

            obtenerdetalleingreso(idingreso);
        } catch (e) {
            // Capturar y manejar errores en el proceso de parseo o ejecución
            alert("Error al procesar los datos recibidos.");
            console.error("Error:", e);
        }
    });
}







//Función para anular registros 
function anular(idingreso) {
    bootbox.confirm("¿Está Seguro de anular el ingreso?", function (result) {
        load();
        if (result) {
            $.post("../ajax/ingreso.php?op=anular", { idingreso: idingreso }, function (e) {
                Swal.fire({
                    title: 'Mensaje!',
                    text: e,
                    icon: 'success',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true,
                    willClose: () => {

                        Swal.close();
                        window.location.reload();
                    }
                });

            });
        }
    })
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto() {
    var tipo_comprobante = $("#tipo_comprobante option:selected").text();
    if (tipo_comprobante == 'Factura') {
        $("#impuesto").val(impuesto);
    }
    else {
        $("#impuesto").val("0");
    }
}



function agregarDetalle(idarticulo, articulo, precio_venta, precio_compra, stock, precio_ventaNocturno,
    precio_rango1, precio_rango2, precio_rango3, precio_unidad, precio_blister, precio_caja, precio_fardo, precio_sacos, precio_paquete,
    stock_unidad, stock_blister, stock_caja, stock_fardo, stock_sacos, stock_paquete) {
    var cantidad = 1;
    var subtotaldes = 0;


    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="stockinven[]" value="' + stock + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                <select class="form-control" style="width:150px" name="presentacion[]" id="presentacionselect`+ cont + `" 
                onchange="presentacionoculatardatos(`+ cont + `,` + precio_venta + `,` + stock_unidad + `,` + precio_unidad + `,` + stock_blister + `,` + precio_blister + `,
                                                    `+ stock_caja + `,` + precio_caja + `,` + stock_fardo + `,` + precio_fardo + `,` + stock_sacos + `,` + precio_sacos + `,` + stock_paquete + `,` + precio_paquete + `)" >
                    `+ (parseInt(stock_unidad) > 0 ? `<option value="UNIDAD">P.U</option>` : ``) + ` 
                    `+ (parseInt(stock_blister) > 0 ? `<option value="BLISTER">P.BLI</option>` : ``) + ` 
                    `+ (parseInt(stock_caja) > 0 ? `<option value="CAJA">P.CAJA.</option>` : ``) + ` 
                    `+ (parseInt(stock_fardo) > 0 ? `<option value="FARDO">P.FARDO</option>` : ``) + ` 
                    `+ (parseInt(stock_sacos) > 0 ? `<option value="SACOS">P.SACOS</option>` : ``) + ` 
                    `+ (parseInt(stock_paquete) > 0 ? `<option value="PAQUETE">P.PAQUETE</option>` : ``) + ` 

                </select>
            </td>`+
            '<td><input type="number" onchange="modificarSubototales()" style="width:75px" step="any" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td><input onchange="modificarSubototales()" type="number" step="any" style="width:50px"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input type="number" step="any" name="precio_venta[]" style="width:75px" value="' + precio_venta + '"></td>' +
            '<td><input type="number" step="any" name="precio_ventaNocturno[]" style="width:75px"  value="' + precio_ventaNocturno + '"></td>' +
            '<td><input type="number" step="any" name="precio_rango1[]" style="width:75px" value="' + precio_rango1 + '"></td>' +
            '<td><input type="number" step="any" name="precio_rango2[]" style="width:75px" value="' + precio_rango2 + '"></td>' +
            '<td><input type="number" step="any" name="precio_rango3[]" style="width:75px" value="' + precio_rango3 + '"></td>' +
            '<td><input type="number" step="any" name="precio_unidad[]" style="width:75px" value="' + precio_unidad + '"></td>' +
            '<td><input type="number" step="any" name="precio_blister[]" style="width:75px" value="' + precio_blister + '"></td>' +
            '<td><input type="number" step="any" name="precio_caja[]" style="width:75px" value="' + precio_caja + '"></td>' +
            '<td><input type="number" step="any" name="precio_fardo[]" style="width:75px" value="' + precio_fardo + '"></td>' +
            '<td><input type="number" step="any" name="precio_sacos[]" style="width:75px" value="' + precio_sacos + '"></td>' +
            '<td><input type="number" step="any" name="precio_paquete[]" style="width:75px" value="' + precio_paquete + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function presentacionoculatardatos(id, precio_venta,
    nombre_01, stock_unidad, precio_unidad,
    nombre_02, stock_blister, precio_blister,
    nombre_03, stock_caja, precio_caja,
    nombre_04, stock_fardo, precio_fardo,
    nombre_05, stock_sacos, precio_sacos,
    nombre_06, stock_paquete, precio_paquete,
    nombre_07, stock_07, precio_07,
    nombre_08, stock_08, precio_08,
    nombre_09, stock_09, precio_09,
    nombre_10, stock_10, precio_10,
    nombre_11, stock_11, precio_11,
    nombre_12, stock_12, precio_12,
    nombre_13, stock_13, precio_13,
    nombre_14, stock_14, precio_14,
    nombre_15, stock_15, precio_15,
    nombre_16, stock_16, precio_16,
    nombre_17, stock_17, precio_17,
    nombre_18, stock_18, precio_18,
    nombre_19, stock_19, precio_19,
    nombre_20, stock_20, precio_20) {
    var presentacion = $("#presentacionselect" + id).val();
    var cantidad = parseFloat($("#cantidad" + id).val());
    if (presentacion == nombre_01) {
        var precioventaunidad = 0;
        if (stock_unidad > 0) {
            precioventaunidad = (precio_unidad / stock_unidad); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_unidad);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_unidad);
        $("#presen" + id).val(nombre_01);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_unidad);
    }
    else if (presentacion == nombre_02) {
        var precioblister = 0;
        if (stock_blister > 0) {
            // Calcula el precio por unidad y redondea a 2 decimales
            precioblister = ((precio_blister / stock_blister));
        }
        $("#cantidadpresentacion" + id).val(stock_blister);
        $("#precio_venta" + id).val(precioblister);
        $("#q_ref" + id).val(precio_blister);
        $("#presen" + id).val(nombre_02);

        $("#precio_ventaSistema" + id).val(precioblister);
        $("#precio_ventaSistema2" + id).val(precio_blister);
    }
    else if (presentacion == nombre_03) {
        var precioventaunidad = 0;
        if (stock_caja > 0) {
            precioventaunidad = (precio_caja / stock_caja); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_caja);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_caja);
        $("#presen" + id).val(nombre_03);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_caja);
    }
    else if (presentacion == nombre_04) {
        var precioventaunidad = 0;
        if (stock_fardo > 0) {
            precioventaunidad = (precio_fardo / stock_fardo); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_fardo);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_fardo);
        $("#presen" + id).val(nombre_04);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_fardo);
    }
    else if (presentacion == nombre_05) {
        var precioventaunidad = 0;
        if (stock_sacos > 0) {
            precioventaunidad = (precio_sacos / stock_sacos); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_sacos);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_sacos);
        $("#presen" + id).val(nombre_05);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_sacos);
    }
    else if (presentacion == nombre_06) {
        var precioventaunidad = 0;
        if (stock_paquete > 0) {
            precioventaunidad = (precio_paquete / stock_paquete); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_paquete);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_paquete);
        $("#presen" + id).val(nombre_06);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_paquete);
    }
    else if (presentacion == nombre_07) {
        var precioventaunidad = 0;
        if (stock_07 > 0) {
            precioventaunidad = (precio_07 / stock_07); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_07);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_07);
        $("#presen" + id).val(nombre_07);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_07);
    }

    else if (presentacion == nombre_07) {
        var precioventaunidad = 0;
        if (stock_08 > 0) {
            precioventaunidad = (precio_08 / stock_08); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_08);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_08);
        $("#presen" + id).val(nombre_08);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_08);
    }
    else if (presentacion == nombre_09) {
        var precioventaunidad = 0;
        if (stock_09 > 0) {
            precioventaunidad = (precio_09 / stock_09); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_09);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_09);
        $("#presen" + id).val(nombre_09);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_09);
    }
    else if (presentacion == nombre_10) {
        var precioventaunidad = 0;
        if (stock_10 > 0) {
            precioventaunidad = (precio_10 / stock_10); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_10);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_10);
        $("#presen" + id).val(nombre_10);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_10);
    }
    else if (presentacion == nombre_11) {
        var precioventaunidad = 0;
        if (stock_11 > 0) {
            precioventaunidad = (precio_11 / stock_11); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_11);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_11);
        $("#presen" + id).val(nombre_11);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_11);
    }
    else if (presentacion == nombre_12) {
        var precioventaunidad = 0;
        if (stock_12 > 0) {
            precioventaunidad = (precio_12 / stock_12); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_12);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_12);
        $("#presen" + id).val(nombre_12);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_12);
    }
    else if (presentacion == nombre_13) {
        var precioventaunidad = 0;
        if (stock_13 > 0) {
            precioventaunidad = (precio_13 / stock_13); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_13);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_13);
        $("#presen" + id).val(nombre_13);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_13);
    }
    else if (presentacion == nombre_14) {
        var precioventaunidad = 0;
        if (stock_14 > 0) {
            precioventaunidad = (precio_14 / stock_14); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_14);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_14);
        $("#presen" + id).val(nombre_14);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_14);
    }
    else if (presentacion == nombre_15) {
        var precioventaunidad = 0;
        if (stock_15 > 0) {
            precioventaunidad = (precio_15 / stock_15); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_15);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_15);
        $("#presen" + id).val(nombre_15);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_15);
    }
    else if (presentacion == nombre_16) {
        var precioventaunidad = 0;
        if (stock_16 > 0) {
            precioventaunidad = (precio_16 / stock_16); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_16);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_16);
        $("#presen" + id).val(nombre_16);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_16);
    }
    else if (presentacion == nombre_17) {
        var precioventaunidad = 0;
        if (stock_17 > 0) {
            precioventaunidad = (precio_17 / stock_17); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_17);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_17);
        $("#presen" + id).val(nombre_17);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_17);
    }
    else if (presentacion == nombre_18) {
        var precioventaunidad = 0;
        if (stock_18 > 0) {
            precioventaunidad = (precio_18 / stock_18); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_18);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_18);
        $("#presen" + id).val(nombre_18);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_18);
    }
    else if (presentacion == nombre_19) {
        var precioventaunidad = 0;
        if (stock_19 > 0) {
            precioventaunidad = (precio_19 / stock_19); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_19);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_19);
        $("#presen" + id).val(nombre_19);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_19);
    }
    else if (presentacion == nombre_20) {
        var precioventaunidad = 0;
        if (stock_20 > 0) {
            precioventaunidad = (precio_20 / stock_20); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_20);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_20);
        $("#presen" + id).val(nombre_20);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_20);
    }
    else {

        $("#cantidadpresentacion" + id).val(1);
        $("#precio_venta" + id).val(precio_venta);
        $("#q_ref" + id).val(precio_venta);
        $("#presen" + id).val(nombre_01);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_unidad);

    }
    modificarSubototales();
}
function obtenerdetalleingreso(idingreso) {
    $.post("../ajax/ingreso.php?op=detalleingreso", { idingreso: idingreso }, function (data) {
        //  console.log(data);
        data = JSON.parse(data);
        Swal.close()
        $.each(data, function (i, item) {
            agregarDetalle2(item.idarticulo, item.articulo, item.precio_venta, item.precio_compra, item.stock, item.cantidad, item.descuento_porcentaje,
                item.precio_ventaNocturno, item.precio_rango1, item.precio_rango2, item.precio_rango3,
                item.nombre_01, item.stock_unidad, item.precio_unidad,
                item.nombre_02, item.stock_blister, item.precio_blister,
                item.nombre_03, item.stock_caja, item.precio_caja,
                item.nombre_04, item.stock_fardo, item.precio_fardo,
                item.nombre_05, item.stock_sacos, item.precio_sacos,
                item.nombre_06, item.stock_paquete, item.precio_paquete,
                item.nombre_07, item.stock_07, item.precio_07,
                item.nombre_08, item.stock_08, item.precio_08,
                item.nombre_09, item.stock_09, item.precio_09,
                item.nombre_10, item.stock_10, item.precio_10,
                item.nombre_11, item.stock_11, item.precio_11,
                item.nombre_12, item.stock_12, item.precio_12,
                item.nombre_13, item.stock_13, item.precio_13,
                item.nombre_14, item.stock_14, item.precio_14,
                item.nombre_15, item.stock_15, item.precio_15,
                item.nombre_16, item.stock_16, item.precio_16,
                item.nombre_17, item.stock_17, item.precio_17,
                item.nombre_18, item.stock_18, item.precio_18,
                item.nombre_19, item.stock_19, item.precio_19,
                item.nombre_20, item.stock_20, item.precio_20,
                item.cantidadpresentacion, item.totalcantidadpresentacion, item.presentacion, item.idsucursalDestino);
        });
    })
}


function agregarDetalle2(idarticulo, articulo, precio_venta, precio_compra, stock, cantidad, descuento_porcentaje,
    precio_ventaNocturno, precio_rango1, precio_rango2, precio_rango3,
    nombre_01, stock_unidad, precio_unidad,
    nombre_02, stock_blister, precio_blister,
    nombre_03, stock_caja, precio_caja,
    nombre_04, stock_fardo, precio_fardo,
    nombre_05, stock_sacos, precio_sacos,
    nombre_06, stock_paquete, precio_paquete,
    nombre_07, stock_07, precio_07,
    nombre_08, stock_08, precio_08,
    nombre_09, stock_09, precio_09,
    nombre_10, stock_10, precio_10,
    nombre_11, stock_11, precio_11,
    nombre_12, stock_12, precio_12,
    nombre_13, stock_13, precio_13,
    nombre_14, stock_14, precio_14,
    nombre_15, stock_15, precio_15,
    nombre_16, stock_16, precio_16,
    nombre_17, stock_17, precio_17,
    nombre_18, stock_18, precio_18,
    nombre_19, stock_19, precio_19,
    nombre_20, stock_20, precio_20,
    cantidadpresentacion, totalcantidadpresentacion, presentacion, idsucursalDestino) {

    var subtotaldes = 0;

    // console.log("presentacion ", presentacion);
    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idsucursalDestino[]" value="' + idsucursalDestino + '"><input type="hidden" name="stockinven[]" value="' + stock + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                <select class="form-control" style="width:100px" name="presentacion[]" id="presentacionselect` + cont + `" 
                onchange="presentacionoculatardatos(`+ cont + `,` + precio_venta + `,
                        '` + nombre_01 + `',` + stock_unidad + `,` + precio_unidad + `,
                        '` + nombre_02 + `',` + stock_blister + `,` + precio_blister + `,
                        '` + nombre_03 + `',` + stock_caja + `,` + precio_caja + `,
                        '` + nombre_04 + `',` + stock_fardo + `,` + precio_fardo + `,
                        '` + nombre_05 + `',` + stock_sacos + `,` + precio_sacos + `,
                        '` + nombre_06 + `',` + stock_paquete + `,` + precio_paquete + `,
                        '` + nombre_07 + `',` + stock_07 + `,` + precio_07 + `,
                        '` + nombre_08 + `',` + stock_08 + `,` + precio_08 + `,
                        '` + nombre_09 + `',` + stock_09 + `,` + precio_09 + `,
                        '` + nombre_10 + `',` + stock_10 + `,` + precio_10 + `,
                        '` + nombre_11 + `',` + stock_11 + `,` + precio_11 + `,
                        '` + nombre_12 + `',` + stock_12 + `,` + precio_12 + `,
                        '` + nombre_13 + `',` + stock_13 + `,` + precio_13 + `,
                        '` + nombre_14 + `',` + stock_14 + `,` + precio_14 + `,
                        '` + nombre_15 + `',` + stock_15 + `,` + precio_15 + `,
                        '` + nombre_16 + `',` + stock_16 + `,` + precio_16 + `,
                        '` + nombre_17 + `',` + stock_17 + `,` + precio_17 + `,
                        '` + nombre_18 + `',` + stock_18 + `,` + precio_18 + `,
                        '` + nombre_19 + `',` + stock_19 + `,` + precio_19 + `,
                        '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)" >
                    `+ (parseInt(stock_unidad) > 0 ? `<option value="${nombre_01}" ` + (presentacion === nombre_01 ? 'selected' : '') + `>${nombre_01}</option>` : ``) + ` 
                    `+ (parseInt(stock_blister) > 0 ? `<option value="${nombre_02}" ` + (presentacion === nombre_02 ? 'selected' : '') + `>${nombre_02}</option>` : ``) + ` 
                    `+ (parseInt(stock_caja) > 0 ? `<option value="${nombre_03}" ` + (presentacion === nombre_03 ? 'selected' : '') + `>${nombre_03}</option>` : ``) + ` 
                    `+ (parseInt(stock_fardo) > 0 ? `<option value="${nombre_04}" ` + (presentacion === nombre_04 ? 'selected' : '') + `>${nombre_04}</option>` : ``) + ` 
                    `+ (parseInt(stock_sacos) > 0 ? `<option value="${nombre_05}" ` + (presentacion === nombre_05 ? 'selected' : '') + `>${nombre_05}</option>` : ``) + ` 
                    `+ (parseInt(stock_paquete) > 0 ? `<option value="${nombre_06}" ` + (presentacion === nombre_06 ? 'selected' : '') + `>${nombre_06}</option>` : ``) + ` 
                    `+ (parseInt(stock_07) > 0 ? `<option value="${nombre_07}" ` + (presentacion === nombre_07 ? 'selected' : '') + `>${nombre_07}</option>` : ``) + ` 
                    `+ (parseInt(stock_08) > 0 ? `<option value="${nombre_08}" ` + (presentacion === nombre_08 ? 'selected' : '') + `>${nombre_08}</option>` : ``) + ` 
                    `+ (parseInt(stock_09) > 0 ? `<option value="${nombre_09}" ` + (presentacion === nombre_09 ? 'selected' : '') + `>${nombre_09}</option>` : ``) + ` 
                    `+ (parseInt(stock_10) > 0 ? `<option value="${nombre_10}" ` + (presentacion === nombre_10 ? 'selected' : '') + `>${nombre_10}</option>` : ``) + ` 
                    `+ (parseInt(stock_11) > 0 ? `<option value="${nombre_11}" ` + (presentacion === nombre_11 ? 'selected' : '') + `>${nombre_11}</option>` : ``) + ` 
                    `+ (parseInt(stock_12) > 0 ? `<option value="${nombre_12}" ` + (presentacion === nombre_12 ? 'selected' : '') + `>${nombre_12}</option>` : ``) + ` 
                    `+ (parseInt(stock_13) > 0 ? `<option value="${nombre_13}" ` + (presentacion === nombre_13 ? 'selected' : '') + `>${nombre_13}</option>` : ``) + ` 
                    `+ (parseInt(stock_14) > 0 ? `<option value="${nombre_14}" ` + (presentacion === nombre_14 ? 'selected' : '') + `>${nombre_14}</option>` : ``) + ` 
                    `+ (parseInt(stock_15) > 0 ? `<option value="${nombre_15}" ` + (presentacion === nombre_15 ? 'selected' : '') + `>${nombre_15}</option>` : ``) + ` 
                    `+ (parseInt(stock_16) > 0 ? `<option value="${nombre_16}" ` + (presentacion === nombre_16 ? 'selected' : '') + `>${nombre_16}</option>` : ``) + ` 
                    `+ (parseInt(stock_17) > 0 ? `<option value="${nombre_17}" ` + (presentacion === nombre_17 ? 'selected' : '') + `>${nombre_17}</option>` : ``) + ` 
                    `+ (parseInt(stock_18) > 0 ? `<option value="${nombre_18}" ` + (presentacion === nombre_18 ? 'selected' : '') + `>${nombre_18}</option>` : ``) + ` 
                    `+ (parseInt(stock_19) > 0 ? `<option value="${nombre_19}" ` + (presentacion === nombre_19 ? 'selected' : '') + `>${nombre_19}</option>` : ``) + ` 
                    `+ (parseInt(stock_20) > 0 ? `<option value="${nombre_20}" ` + (presentacion === nombre_20 ? 'selected' : '') + `>${nombre_20}</option>` : ``) + ` 
                </select>
            </td>` +
            '<td><input type="number" class="form-control"  style="width:75px" onchange="modificarSubototales()"  step="any" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  style="width:50px" step="any" onchange="modificarSubototales()"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="' + descuento_porcentaje + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_venta[]" style="width:75px" value="' + precio_venta + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_ventaNocturno[]" style="width:75px"  value="' + precio_ventaNocturno + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_rango1[]" style="width:75px" value="' + precio_rango1 + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_rango2[]" style="width:75px" value="' + precio_rango2 + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_rango3[]" style="width:75px" value="' + precio_rango3 + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_unidad[]" style="width:75px" value="' + precio_unidad + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_blister[]" style="width:75px" value="' + precio_blister + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_caja[]" style="width:75px" value="' + precio_caja + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_fardo[]" style="width:75px" value="' + precio_fardo + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_sacos[]" style="width:75px" value="' + precio_sacos + '" readonly=""></td>' +
            '<td><input type="number" class="form-control"  step="any" name="precio_paquete[]" style="width:75px" value="' + precio_paquete + '" readonly=""></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}


function modificarSubototales() {
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_compra[]");
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]"));
    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpD = desc[i];
        var inpS = sub[i];
        var inpSdes = subdes[i];
        var inpTpres = tprese[i];
        var inpCpre = cantpre[i];

        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;

        inpS.value = ((inpC.value * inpCpre.value) * (inpP.value - ((inpP.value * inpD.value) / 100)));
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;

        inpSdes.value = ((inpP.value * inpD.value) / 100) * inpC.value;
        document.getElementsByName("subtotaldes")[i].innerHTML = inpSdes.value;

    }
    calcularTotales();
    calcularTotalesdes();

}
function calcularTotales() {
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("Q/. " + total);
    $("#total_compra").val(total);
    evaluar();
}

function calcularTotalesdes() {
    var chks = document.getElementsByName('subtotaldes');
    var total = 0.0;
    for (var i = 0; i < chks.length; i++) {
        var valor = parseFloat(chks[i].value);
        if (isNaN(valor) == false) {
            total += parseFloat(chks[i].value);
        }
    }
    // alert("la suma es, " + total);
    $("#totaldes").html("Q/. " + total);
    $("#total_comprades").val(total);
    evaluar();
}

function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    }
    else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    calcularTotales();
    detalles = detalles - 1;
    evaluar();
}

init();