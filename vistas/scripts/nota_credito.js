var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(true);
    listar();
    $('#MenuVentas').addClass("treeview active");
    $('#Ventas').addClass("active");

    listarArticulos();
    // listarArticulosxcategoria();
    $("#div_formapago").hide();



    $(document).on('keydown', function (event) {
        if (event.which == 120) { // F9 para abrir el modal de venta
            $("#btnProcesar").click();
            //$("#cefectivo").focus() 

        }
    });

    $(document).on('keydown', function (event) {
        if (event.which == 117) { // F6 guardar
            $("#btnGuardar").trigger("click");
        }
    });


    $(document).on('keydown', function (event) {
        if (event.which == 118) { // F7 cancelar
            $("#btnCancelar").click();

        }
    });

    $(document).on('keydown', function (event) {
        if (event.which == 119) { // F8 para abrir el modal de venta
            $("#btnagregar").click();
        }
    });



    $("#btnProcesar").click(function () {
        var total = $("#total_venta").val(); // Obtiene el valor del input
        $("#vistatotal").html(total); // Muestra el valor en #vistatotal
    });


    $.post("../ajax/articulo.php?op=selectCategoria", function (r) {
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');

    });



    $("#btnGuardar").click(function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });



    $("#btncargar").click(function () {

        var idcotizacion = $("#idcotizacion").val();
        if (idcotizacion == "") {
            alert("Debe Colocar un Id de Cotizacion Valido")
            return;
        }

        obtenerClienteCotizacion(idcotizacion);

    });

    $("#btnGuardarApertura").click(function (e) {
        guardaryeditaraperturacaja(e);
    });

    $("#btnGuardarCierre").click(function (e) {
        guardaryeditarCierre(e);
    });



}

function guardaryeditaraperturacaja(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioapertura")[0]);

    $.ajax({
        url: "../ajax/cuadres_caja.php?op=guardaryeditar2",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
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
    limpiarapertura();
}

//Función cancelarform
function cancelarformaperturacaja() {
    limpiarapertura();
}

function limpiarapertura() {
    $("#total_efectivo").val("");
    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora_apertura').val(today);

}



function guardaryeditarCierre(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulariocierre")[0]);
    load();
    $.ajax({
        url: "../ajax/cuadres_caja_cierre.php?op=guardaryeditar2",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.close();
            $('#myModalCierre').modal('hide');
            if (confirm("Desea Imprimir su Cierre de caja")) {
                window.location.href = "../reportes/excuadreCajaCierre.php?id=" + datos, '_blank';
            }
            else {
                window.location.reload();
            }
        }

    });
    limpiarCierre();
}



//Función cancelarform
function cancelarformCierre() {
    limpiarCierre();
}

function limpiarCierre() {
    $("#total_efectivocierre").val("");
    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora_cierre').val(today);

}

function calcularcaja() {



    var res_total_ventas_diarias = $("#total_ventas_diarias").val();
    var res_total_efectivo = $("#total_efectivocierre").val();
    var res_total_ventas_diarias_efectivo = $("#total_ventas_diarias_efectivo").val();
    var res_total_efectivo_inicio = $("#total_efectivo_inicio").val();

    $("#total_efectivo_cierre_operaciones").val(parseFloat(res_total_efectivo) - (parseFloat(res_total_efectivo_inicio) + parseFloat(res_total_ventas_diarias_efectivo)));



}

function calcularefectivo() {
    // $("#cefectivo").focus() 
    try {


        var total = parseFloat($("#total").html().replace('Q.', '').replace("Q.", "").trim());
        var efectivo = parseFloat($("#cefectivo").val());
        var cefectivo_tarjeta = parseFloat($("#ctarjeta").val());
        var cefectivo_transferencia = parseFloat($("#ctransferencia").val());
        var cefectivo_credito = parseFloat($("#ccredito").val());

        var cambio1 = (efectivo + cefectivo_tarjeta + cefectivo_transferencia + cefectivo_credito);
        var cambio2 = cambio1 - total

        $("#cambio").html("Q." + cambio2.toFixed(2));
        $("#rescambio").val(cambio2.toFixed(2));


    } catch (ex) {

    }
};





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




////fin de cliente  



function mostrarOpcionesAdicionales() {
    const tipoPago = document.getElementById("tipo_pagoBacVisaNet").value;
    const opcionesAdicionalesDiv = document.getElementById("opcionesAdicionalesDiv");
    const opcionesAdicionales = document.getElementById("opcionesAdicionales");

    // Limpiar opciones previas
    opcionesAdicionales.innerHTML = "";

    // Verificar qué opción fue seleccionada y agregar las opciones correspondientes
    if (tipoPago in opcionesPorTipoPago) {
        opcionesAdicionalesDiv.style.display = "block";
        opcionesPorTipoPago[tipoPago].forEach(opcion => {
            opcionesAdicionales.innerHTML += `<option value="${opcion}">${opcion}</option>`;
        });
    } else {
        opcionesAdicionalesDiv.style.display = "none";
    }
}


// Mapeo de tipos de pago a sus opciones adicionales
const opcionesPorTipoPago = {
    VISANET: [
        "Pago Directo",
        "2 cuotas",
        "3 cuotas",
        "6 cuotas",
        "10 cuotas",
        "12 cuotas",
        "15 cuotas",
        "18 cuotas"
    ],
    BAC: [
        "Pago Directo"
    ]
};


$("#opcionesAdicionales").change(FopcionesAdicionales);

function FopcionesAdicionales() {
    var opcionesAdicionales = $("#opcionesAdicionales option:selected").text();
    if (opcionesAdicionales == 'Pago Directo') {
        $("#valor_tarjeta").val("0");
        modificarSubototalesTarjetaEfectivo();

    }
    else if (opcionesAdicionales == '2 cuotas') {
        $("#valor_tarjeta").val("5.25");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '3 cuotas') {
        $("#valor_tarjeta").val("5.75");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '6 cuotas') {
        $("#valor_tarjeta").val("7");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '10 cuotas') {
        $("#valor_tarjeta").val("7.25");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '12 cuotas') {
        $("#valor_tarjeta").val("8");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '15 cuotas') {
        $("#valor_tarjeta").val("10");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '18 cuotas') {
        $("#valor_tarjeta").val("12");
        modificarSubototalesTarjetaEfectivo();
    }
    else {
        $("#valor_tarjeta").val("0");
        modificarSubototalesTarjetaEfectivo();
    }
}








function calculo() {

    var numeropagos = document.getElementById('numero_pagos').value;
    var totalventa = document.getElementById('total_venta').value;

    var resmontoabono = (totalventa / numeropagos);


    document.getElementById('monto_abono').innerHTML = resmontoabono;
    $("#monto_abono").val(resmontoabono.toFixed(2));


}


function obtenerClienteCotizacion(idcotizacion) {

    $.post("../ajax/cotizaciones.php?op=mostrarVenta", { idcotizacion: idcotizacion }, function (data, status) {


        // Parseamos la data
        data = JSON.parse(data);

        // Verificamos si la data viene vacía o es nula
        if (!data || !data.idventa) {
            Swal.fire({
                icon: 'info',
                title: 'Venta se aplico NC',
                text: 'La Venta ya fue aplicada NC o no está disponible.',
                confirmButtonText: 'Aceptar'
            });
            return; // Detenemos la ejecución si la cotización ya fue cobrada o los datos están vacíos
        }
        load();

        // Si la data es válida, mostramos el formulario y asignamos los valores
        mostrarform(true);

        $("#idventa").val(data.idventa);
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
        $("#forma_pago").val(data.forma_pago);
        $("#tipo_comprobante").val(data.tipo_comprobante);

        $("#autorizacionEcoFactura_venta").val(data.autorizacionEcoFactura);
        $("#serie_comprobante_venta").val(data.serie_ecoFactura);
        $("#numero_ecoFactura_venta").val(data.numero_ecoFactura);

        $("#total_venta").val(data.total_venta);
        $("#total_ventades").val(data.total_ventades);







        // Llamamos a la función para obtener el detalle de la cotización
        obtenerdetallecotizacion(idcotizacion);
    });
}







$("#forma_pago").change(mostrarFormaPago);

function mostrarFormaPago() {
    var forma_pago = $("#forma_pago option:selected").text();
    if (forma_pago == 'Credito') {
        $("#div_formapago").hide();
        $("#valor_tarjeta").val(0);
        modificarSubototalesTarjetaEfectivo();

    }
    else if (forma_pago == 'Efectivo/Tarjeta') {
        $("#div_formapago").show();
    }
    else if (forma_pago == 'Tarjeta') {
        $("#div_formapago").show();
    }
    else if (forma_pago == 'Efectivo') {
        $("#tipo_pagoBacVisaNet").val("Seleccione Uno");
        $("#tipo_pagoBacVisaNet").selectpicker('refresh');

        $("#opcionesAdicionales").val("Pago Directo");
        $("#opcionesAdicionales").selectpicker('refresh');

        $("#div_formapago").hide();
        $("#valor_tarjeta").val(0);
        modificarSubototalesTarjetaEfectivo();
    }
}

//Función limpiar
function limpiar() {
    $("#idventa").val("");
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
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);
    $('#fecha_hora_nc').val(today);

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
        // $("#btnGuardar").disabled();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        // $("#fecha_hora_cobro2").hide(); 
        detalles = 0;
    }
    else {
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
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax":
            {
                url: '../ajax/venta.php?op=listarNC',
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte },
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                total = api
                    .column(6)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total7 = api
                    .column(7)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total9 = api
                    .column(9)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total10 = api
                    .column(10)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total11 = api
                    .column(11)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total12 = api
                    .column(12)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal7 = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal9 = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal10 = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal11 = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal12 = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);


                // Update footer
                $(api.column(6).footer(0)).html(
                    pageTotal.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(7).footer(0)).html(
                    pageTotal7.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(9).footer(0)).html(
                    pageTotal9.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(10).footer(0)).html(
                    pageTotal10.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(11).footer(0)).html(
                    pageTotal11.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );


                $(api.column(12).footer(0)).html(
                    pageTotal12.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[1, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}





//Función ListarArticulos
function listarArticulos() {

    tabla = $('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/venta.php?op=listarArticulosVenta',
                type: "get",
                data: { idcliente: $("#idcliente").val() },
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();



}


//Función para guardar o editar
function agruparDatos() {
    const datos = {
        articulos: {
            idarticulo: [],
            descripcion_detalle: [],
            stockinven: [],
            cantidadpresentacion: [],
            cantidad: [],
            totalcantidadpresentacion: [],
            presentacion: [],
            presen: [],
            precio_ventaSistema: [],
            precio_ventaSistema2: [],
            q_ref: [],
            precio_venta: [],
            precio_recargoPV: [],
            precio_recargoQRef: [],
            descuento_porcentaje: [],
            subtotal1: [],
            subtotaldes1: []
        }
    };

    $('#detalles .filas').each(function () {
        const idarticulo = $(this).find('input[name="idarticulo[]"]').val();
        const descripcion_detalle = $(this).find('input[name="descripcion_detalle[]"]').val();
        const stockinven = $(this).find('input[name="stockinven[]"]').val();
        const cantidadpresentacion = $(this).find('input[name="cantidadpresentacion[]"]').val();
        const cantidad = $(this).find('input[name="cantidad[]"]').val();
        const totalcantidadpresentacion = $(this).find('input[name="totalcantidadpresentacion[]"]').val();
        const presentacion = $(this).find('select[name="presentacion[]"]').val();
        const presen = $(this).find('input[name="presen[]"]').val();
        const precio_ventaSistema = $(this).find('input[name="precio_ventaSistema[]"]').val();
        const precio_ventaSistema2 = $(this).find('input[name="precio_ventaSistema2[]"]').val();
        const q_ref = $(this).find('input[name="q_ref[]"]').val();
        const precio_venta = $(this).find('input[name="precio_venta[]"]').val();
        const precio_recargoPV = $(this).find('input[name="precio_recargoPV[]"]').val();
        const precio_recargoQRef = $(this).find('input[name="precio_recargoQRef[]"]').val();
        const descuento_porcentaje = $(this).find('input[name="descuento_porcentaje[]"]').val();
        const subtotal1 = $(this).find('input[name="subtotal1[]"]').val();
        const subtotaldes1 = $(this).find('input[name="subtotaldes1[]"]').val();

        datos.articulos.idarticulo.push(idarticulo);
        datos.articulos.descripcion_detalle.push(descripcion_detalle);
        datos.articulos.stockinven.push(stockinven);
        datos.articulos.cantidadpresentacion.push(cantidadpresentacion);
        datos.articulos.cantidad.push(cantidad);
        datos.articulos.totalcantidadpresentacion.push(totalcantidadpresentacion);
        datos.articulos.presentacion.push(presentacion);
        datos.articulos.presen.push(presen);
        datos.articulos.precio_ventaSistema.push(precio_ventaSistema);
        datos.articulos.precio_ventaSistema2.push(precio_ventaSistema2);
        datos.articulos.q_ref.push(q_ref);
        datos.articulos.precio_venta.push(precio_venta);
        datos.articulos.precio_recargoPV.push(precio_recargoPV);
        datos.articulos.precio_recargoQRef.push(precio_recargoQRef);
        datos.articulos.descuento_porcentaje.push(descuento_porcentaje);
        datos.articulos.subtotal1.push(subtotal1);
        datos.articulos.subtotaldes1.push(subtotaldes1);
    });

    const datosJSON = JSON.stringify(datos);

    localStorage.setItem('datosArticulosNC', datosJSON);

    // console.log(datosJSON);
}


function guardaryeditar(e) {

    if (detalles > 0) {

    }
    else {
        alert("No se puede Guardar porque no has agregado item a tu venta ");
        return;
    }
    agruparDatos();
    var forma_pago = document.getElementById('forma_pago').value;
    var tipo_pagoBacVisaNet = document.getElementById('tipo_pagoBacVisaNet').value;

    const cefectivoCredito = parseFloat($("#ccredito").val().trim());
    const cefectivo_tarjeta = parseFloat($("#ctarjeta").val().trim());
    const cefectivoTransferencia = parseFloat($("#ctransferencia").val().trim());
    const cefectivo = parseFloat($("#cefectivo").val().trim());




    if ($("#aperturaCaja").val().trim() == "") {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No tienes Apertura de Caja hecho, realiza una apertura y luego puedes hacer tu venta!",
            footer: '<a >Debes de Aperturar tu caja.</a>'
        });
        return; // Detenemos el proceso sin recargar la página
    }





    var totalventa = document.getElementById('total_venta').value;
    var numdocumentocliente = document.getElementById('nit').value.trim();
    var tipo_comprobantecliente = document.getElementById('tipo_comprobante').value.trim();
    if (tipo_comprobantecliente != 'Envio') {
        if (parseFloat(totalventa) >= 2500) {
            if (numdocumentocliente != 'CF' && numdocumentocliente != 'C/F' && numdocumentocliente != 'cf' && numdocumentocliente != 'c/f') {
                var tipo_comprobante1 = $("#tipo_comprobante").val();
                e.preventDefault(); //No se activará la acción predeterminada del evento
                var formData = new FormData($("#formulario")[0]);
                let datosArticulosNC = JSON.parse(localStorage.getItem('datosArticulosNC'));
                formData.append("datosArticulosNC", JSON.stringify(datosArticulosNC));
                load();
                $.ajax({
                    url: "../ajax/venta.php?op=guardaryeditarnc",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function (datos) {
                        Swal.close();
                        var datos = JSON.parse(datos); // Parsea el JSON recibido

                        $('#myModalImpresionFAc').modal('show');
                        $("#idventa_impresion").val(datos.idventanew);
                        $("#tipo_comprobante_impresion").val(datos.tipo_comprobante);

                    }

                });



            } else {
                alert("su Factura tiene que llevar Nit o Dpi no puede generar con cf")
            }

        } else {
            e.preventDefault(); //No se activará la acción predeterminada del evento
            var formData = new FormData($("#formulario")[0]);
            let datosArticulosNC = JSON.parse(localStorage.getItem('datosArticulosNC'));
            formData.append("datosArticulosNC", JSON.stringify(datosArticulosNC));
            load();
            $.ajax({
                url: "../ajax/venta.php?op=guardaryeditarnc",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function (datos) {
                    Swal.close();
                    var datos = JSON.parse(datos); // Parsea el JSON recibido
                    //console.log(datos)

                    $('#myModalImpresionFAc').modal('show');
                    $("#idventa_impresion").val(datos.idventanew);
                    $("#tipo_comprobante_impresion").val(datos.tipo_comprobante);

                }

            });
        }
    } else {

        var tipo_comprobante1 = $("#tipo_comprobante").val();
        e.preventDefault(); //No se activará la acción predeterminada del evento
        var formData = new FormData($("#formulario")[0]);
        let datosArticulosNC = JSON.parse(localStorage.getItem('datosArticulosNC'));
        formData.append("datosArticulosNC", JSON.stringify(datosArticulosNC));
        load();
        $.ajax({
            url: "../ajax/venta.php?op=guardaryeditarnc",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function (datos) {
                Swal.close();
               // console.log(datos)
                var datos = JSON.parse(datos); // Parsea el JSON recibido

                $('#myModalImpresionFAc').modal('show');
                $("#idventa_impresion").val(datos.idventanew);
                $("#tipo_comprobante_impresion").val(datos.tipo_comprobante);


            }

        });
    }

}

function impresionticket58mm() {
    var tipo_comprobante_impresion = $("#tipo_comprobante_impresion").val();

    if (tipo_comprobante_impresion == "Envio") {
        var url = "../reportes/exTicket58mmNC.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Factura") {
        var url = "../reportes/exTicket_Fel58mm_NC.php?id=" + $("#idventa_impresion").val();
    }

    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario) 
}

function impresionticket79mm() {
    var tipo_comprobante_impresion = $("#tipo_comprobante_impresion").val();

    if (tipo_comprobante_impresion == "Envio") {
        var url = "../reportes/exTicket_NC.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Factura") {
        var url = "../reportes/exTicket_Fel_NC.php?id=" + $("#idventa_impresion").val();
    }

    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario)
}

function impresionticketCarta() {
    var tipo_comprobante_impresion = $("#tipo_comprobante_impresion").val();

    if (tipo_comprobante_impresion == "Envio") {
        var url = "../reportes/exVentaFormatoCartaNC.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Factura") {
        var url = "../reportes/exVentaFormatoCarta_Fel_NC.php?id=" + $("#idventa_impresion").val();
    }

    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
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

/*
//ESTE NO ES
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
        descripcion_detalle: []
    };
    $('#detalles .filas').each(function () {
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
        datos.descripcion_detalle.push($(this).find('input[name="descripcion_detalle[]"]').val());

    });
    const datosJSON = JSON.stringify(datos);
    $("#datos1").val(datosJSON);
}
*/

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




//Función para anular registros
function anular(idventa) {
    var valor = prompt("Ingresa la contraseña de de Autorizacion", "");
    if (valor == "5820005710") {

        bootbox.confirm("¿Está Seguro de anular la venta?", function (result) {
            load();
            if (result) {
                $.post("../ajax/venta.php?op=anular", { idventa: idventa }, function (e) {
                    Swal.fire({
                        title: 'Mensaje!',
                        text: e,
                        icon: 'success',
                        //  timer: 2000, // 2 segundos
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
    else {
        alert("Contraseña no válida: [" + valor + "]");
    }
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto = 12;
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



function agregarDetalle(idarticulo, nombre, precio_venta, stock, descuento_porcentaje, stock_unidad, precio_unidad,
    stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos, precio_sacos, stock_paquete, precio_paquete, precio_rango1, precio_rango2, precio_rango3) {

    var cantidad = 1;
    var stockinven = stock;
    var presen = 'UNIDAD';
    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        var exist = false;
        $('#detalles').children("tbody").children("tr").each(function (index) {
            var idart = $(this).attr("data-id")
            if (idart == idarticulo) {
                exist = true;
            }
        })

        if (!exist) {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="descripcion_detalle[]" value="."><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
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
                `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_venta + `">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_venta + `">
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `" value="` + precio_venta + `"  readonly >
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
                '<td><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $('#detalles').append(fila);
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
            $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function obtenerdetallecotizacion(idcotizacion) {
    $.post("../ajax/cotizaciones.php?op=paraventaVenta", { idcotizacion: idcotizacion }, function (data) {
        //console.log(data);
        data = JSON.parse(data);
        Swal.close()

        $.each(data, function (i, item) {
            agregarDetalle2(item.idarticulo, item.nombre, item.precio_venta, item.stock, item.descuento, item.stock_unidad, item.precio_unidad,
                item.stock_blister, item.precio_blister, item.stock_caja, item.precio_caja, item.stock_fardo, item.precio_fardo, item.stock_sacos, item.precio_sacos, item.stock_paquete, item.precio_paquete,
                item.cantidad, item.presen, item.cantidadpresentacion, item.totalcantidadpresentacion, item.precio_ventaSistema, item.precio_ventaSistema2, item.q_ref, item.precio_recargoPV,
                item.precio_recargoQRef, item.precio_rango1, item.precio_rango2, item.precio_rango3);
        });
    })
}


function agregarDetalle2(idarticulo, nombre, precio_venta, stock, descuento, stock_unidad, precio_unidad,
    stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos, precio_sacos, stock_paquete, precio_paquete,
    cantidad, presen, cantidadpresentacion, totalcantidadpresentacion, precio_ventaSistema, precio_ventaSistema2, q_ref, precio_recargoPV, precio_recargoQRef, precio_rango1, precio_rango2, precio_rango3) {


    var stockinven = stock;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        var exist = false;
        $('#detalles').children("tbody").children("tr").each(function (index) {
            var idart = $(this).attr("data-id")
            if (idart == idarticulo) {
                exist = true;
            }
        })

        if (!exist) {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="descripcion_detalle[]" value="."><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                <select class="form-control" style="width:150px" name="presentacion[]" id="presentacionselect`+ cont + `" 
                onchange="presentacionoculatardatos(`+ cont + `,` + precio_venta + `,` + stock_unidad + `,` + precio_unidad + `,` + stock_blister + `,` + precio_blister + `,
                                                    `+ stock_caja + `,` + precio_caja + `,` + stock_fardo + `,` + precio_fardo + `,` + stock_sacos + `,` + precio_sacos + `,` + stock_paquete + `,` + precio_paquete + `)" >
                    `+ (parseInt(stock_unidad) > 0 ? `<option value="UNIDAD" ` + (presen == 'UNIDAD' ? "selected" : "") + `>P.U</option>` : ``) + ` 
                    `+ (parseInt(stock_blister) > 0 ? `<option value="BLISTER" ` + (presen == 'BLISTER' ? "selected" : "") + `>P.BLI</option>` : ``) + ` 
                    `+ (parseInt(stock_caja) > 0 ? `<option value="CAJA" ` + (presen == 'CAJA' ? "selected" : "") + `>P.CAJA.</option>` : ``) + ` 
                    `+ (parseInt(stock_fardo) > 0 ? `<option value="FARDO" ` + (presen == 'FARDO' ? "selected" : "") + `>P.FARDO</option>` : ``) + ` 
                    `+ (parseInt(stock_sacos) > 0 ? `<option value="SACOS" ` + (presen == 'SACOS' ? "selected" : "") + `>P.SACOS</option>` : ``) + ` 
                    `+ (parseInt(stock_paquete) > 0 ? `<option value="PAQUETE" ` + (presen == 'PAQUETE' ? "selected" : "") + `>P.PAQUETE</option>` : ``) + ` 

                </select>
            </td>`+
                `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_venta + `">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_venta + `">
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `" value="` + precio_venta + `"  readonly >
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
                '<td><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $('#detalles').append(fila);
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
            $("#cxcantidad" + idarticulo).val(cxcantidad)

            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3, $("#cxcantidad" + idarticulo)[0]);
        }
        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}



function presentacionoculatardatos(id, precio_venta, stock_unidad, precio_unidad, stock_blister, precio_blister,
    stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos,
    precio_sacos, stock_paquete, precio_paquete) {
    var presentacion = $("#presentacionselect" + id).val();
    if (presentacion == 'UNIDAD') {
        var precioventaunidad = 0;
        if (stock_unidad > 0) {
            precioventaunidad = (precio_unidad / stock_unidad); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_unidad);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_unidad);
        $("#presen" + id).val("UNIDAD");

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_unidad);
    }
    else if (presentacion == 'BLISTER') {
        var precioblister = 0;
        if (stock_blister > 0) {
            // Calcula el precio por unidad y redondea a 2 decimales
            precioblister = ((precio_blister / stock_blister));
        }
        $("#cantidadpresentacion" + id).val(stock_blister);
        $("#precio_venta" + id).val(precioblister);
        $("#q_ref" + id).val(precio_blister);
        $("#presen" + id).val("BLISTER");

        $("#precio_ventaSistema" + id).val(precioblister);
        $("#precio_ventaSistema2" + id).val(precio_blister);
    }
    else if (presentacion == 'CAJA') {
        var precioventacaja = 0;
        if (stock_caja > 0) {
            precioventacaja = (precio_caja / stock_caja); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_caja);
        $("#precio_venta" + id).val(precioventacaja);
        $("#q_ref" + id).val(precio_caja);
        $("#presen" + id).val("CAJA");

        $("#precio_ventaSistema" + id).val(precioventacaja);
        $("#precio_ventaSistema2" + id).val(precio_caja);
    }
    else if (presentacion == 'FARDO') {

        var precioventafardo = 0;
        if (stock_fardo > 0) {
            precioventafardo = (precio_fardo / stock_fardo); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_fardo);
        $("#precio_venta" + id).val(precioventafardo);
        $("#q_ref" + id).val(precio_fardo);
        $("#presen" + id).val("FARDO");

        $("#precio_ventaSistema" + id).val(precioventafardo);
        $("#precio_ventaSistema2" + id).val(precio_fardo);
    }

    else if (presentacion == 'SACOS') {
        var precioventasacos = 0;
        if (stock_sacos > 0) {
            precioventasacos = (precio_sacos / stock_sacos); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_sacos);
        $("#precio_venta" + id).val(precioventasacos);
        $("#q_ref" + id).val(precio_sacos);
        $("#presen" + id).val("SACOS");

        $("#precio_ventaSistema" + id).val(precioventasacos);
        $("#precio_ventaSistema2" + id).val(precio_sacos);
    }
    else if (presentacion == 'PAQUETE') {
        var precioventapaquete = 0;
        if (stock_paquete > 0) {
            precioventapaquete = (precio_paquete / stock_paquete); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_paquete);
        $("#precio_venta" + id).val(precioventapaquete);
        $("#q_ref" + id).val(precio_paquete);
        $("#presen" + id).val("PAQUETE");


        $("#precio_ventaSistema" + id).val(precioventapaquete);
        $("#precio_ventaSistema2" + id).val(precio_paquete);
    }

    modificarSubototalesTarjetaEfectivo();
}


function modificarSubototalesTarjetaEfectivo() {
    var cant = document.getElementsByName("cantidad[]");
    var qref = document.getElementsByName("q_ref[]");
    var precioventa = document.getElementsByName("precio_venta[]");
    var precSistema = document.getElementsByName("precio_ventaSistema[]");
    var precSistema2 = document.getElementsByName("precio_ventaSistema2[]");
    var valortarjeta = $("#valor_tarjeta").val();


    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = qref[i];
        var inpPPV = precioventa[i];
        var inpPSistema = precSistema[i];
        var inpPSistema2 = precSistema2[i];



        if (parseFloat(valortarjeta) > 0) {
            ////calculo de qref
            var resCanculotarjeta1 = (((inpPSistema2.value * valortarjeta) / 100));
            var resutaltadoCanculotarjeta1 = (parseFloat(resCanculotarjeta1))
            document.getElementsByName("precio_recargoQRef[]")[i].value = resutaltadoCanculotarjeta1;

            document.getElementsByName("q_ref[]")[i].value = (parseFloat(inpPSistema2.value) + parseFloat(resutaltadoCanculotarjeta1));


            ///calculo de precio venta
            var resCanculotarjeta2 = (((inpPSistema.value * valortarjeta) / 100));
            var resutaltadoCanculotarjeta2 = (parseFloat(resCanculotarjeta2))
            document.getElementsByName("precio_recargoPV[]")[i].value = resutaltadoCanculotarjeta2;

            document.getElementsByName("precio_venta[]")[i].value = (parseFloat(inpPSistema.value) + parseFloat(resutaltadoCanculotarjeta2));


        } else {

            ////calculo de qref
            var resCanculotarjeta11 = inpPSistema2.value;
            var resutaltadoCanculotarjeta11 = (parseFloat(resCanculotarjeta11))
            document.getElementsByName("precio_recargoQRef[]")[i].value = 0;
            document.getElementsByName("q_ref[]")[i].value = (parseFloat(resutaltadoCanculotarjeta11));


            ///calculo de precio venta
            var resCanculotarjeta22 = inpPSistema.value;
            var resutaltadoCanculotarjeta22 = (parseFloat(resCanculotarjeta22))
            document.getElementsByName("precio_recargoPV[]")[i].value = 0;
            document.getElementsByName("precio_venta[]")[i].value = (parseFloat(resutaltadoCanculotarjeta22));
        }

    }

    modificarSubototales();
}



function modificarSubototalesxrango(id, precio_rango1, precio_rango2, precio_rango3) {


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

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpPqref = qref[i];
        var inpPSistema = precSistema[i];
        var inpPSistema2 = precioventaSistema2[i];
        var inpD = desc[i];
        var inpS = sub[i];
        var inpSdes = subdes[i];
        var inpSdes1 = subdes1[i];
        var inpS1 = sub1[i];
        var inpCpre = cantpre[i];
        var inpTpres = tprese[i];
        var inppRecargo = pRecargo[i];
        var inpPresen = presen[i];

        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;



        if (inpPresen.value === 'UNIDAD') {
            // Ajustamos el rango para utilizar condiciones AND (`&&`) en lugar de OR (`||`)
            if (parseFloat(inpTpres.value) <= 1) {
                document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;


            } else if (parseFloat(inpTpres.value) > 1 && parseFloat(inpTpres.value) <= 3) {



                if (parseFloat(precio_rango1) > 0) {

                    document.getElementsByName("q_ref[]")[i].value = precio_rango1;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango1;
                } else {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }


            }
            else if (parseFloat(inpTpres.value) > 3 && parseFloat(inpTpres.value) <= 6) {


                if (parseFloat(precio_rango2) > 0) {

                    document.getElementsByName("q_ref[]")[i].value = precio_rango2;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango2;
                } else {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }


            }
            else if (parseFloat(inpTpres.value) > 6) {


                if (parseFloat(precio_rango3) > 0) {

                    document.getElementsByName("q_ref[]")[i].value = precio_rango3;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango3;
                } else {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }

            }
        } else {
            // Este `else` solo se ejecutará si `presen` no es `UNIDAD`
            document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
            document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
        }







        inpS.value = (inpTpres.value * (inpP.value - ((inpP.value * inpD.value) / 100)));
        document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
        inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

        inpSdes.value = (((inpP.value * inpD.value) / 100) * inpTpres.value);
        document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
        inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
        //console.log(inpSdes.value);
    }

    calcularTotales();
    calcularTotalesdes();
}


function modificarSubototales() {
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




    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpPqref = qref[i];
        var inpPSistema = precSistema[i];
        var inpD = desc[i];
        var inpS = sub[i];
        var inpSdes = subdes[i];
        var inpSdes1 = subdes1[i];
        var inpS1 = sub1[i];

        var inpCpre = cantpre[i];
        var inpTpres = tprese[i];

        var inppRecargo = pRecargo[i];

        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;


        inpS.value = (inpTpres.value * (inpP.value - ((inpP.value * inpD.value) / 100)));
        document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
        inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

        inpSdes.value = (((inpP.value * inpD.value) / 100) * inpTpres.value);
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

    for (var i = 0; i < chks.length; i++) {
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
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        var valor = parseFloat(sub[i].textContent || sub[i].innerHTML); // Obtener el valor del subtotal como texto y convertirlo a número
        if (!isNaN(valor)) {
            total += valor;
        }
    }

    // console.log('subtotal'+total)

    $("#total").html("Q. " + total.toFixed(2)); // Mostrar el total en el HTML
    $("#total_venta").val(total.toFixed(2)); // Asignar el valor formateado al campo de entrada
    evaluar(); // Llamar la función evaluar si es necesario
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
    evaluar()
}

init();

$.fn.delayPasteKeyUp = function (fn, ms) {
    var timer = 0;
    $(this).on("propertychange input", function () {
        clearTimeout(timer);
        timer = setTimeout(fn, ms);
    });
};



$(function () {
    $("#txtbusquedaartcodebar").delayPasteKeyUp(function () {

        var valoractual = $("#txtbusquedaartcodebar").val();

        if (valoractual != "") {
            $.get("../ajax/venta.php?op=buscararticulocodebar&codigo=" + valoractual + "", { op: "buscararticulocodebar", codigo: valoractual }, function (res) {

                var arrayproduc = res.split("@");

                //console.log(arrayproduc);

                if (arrayproduc[0] != "undefined") {
                    agregarDetalle(arrayproduc[0], arrayproduc[1], arrayproduc[2], arrayproduc[3], arrayproduc[4], arrayproduc[5], arrayproduc[6], arrayproduc[7], arrayproduc[8], arrayproduc[9], arrayproduc[10]
                        , arrayproduc[11], arrayproduc[12], arrayproduc[13], arrayproduc[14], arrayproduc[15], arrayproduc[16], arrayproduc[17], arrayproduc[18], arrayproduc[19]);
                }
                $("#txtbusquedaartcodebar").val("");
                $("#txtbusquedaartcodebar").focus()

            })
        }

    }, 200)


    listarArticulos();



})