var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(true);
    listar();
    $('#MenuCotizacion').addClass("treeview active");
    $('#Cotizaciones').addClass("active");

    //listarArticulos();
    $("#div_formapago").hide();



    $(document).on('keydown', function (event) {
        if (event.which == 120) { // F9 para abrir el modal de venta
            $("#btnProcesar").click();
            //$("#cefectivo").focus() 

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


    $("#btnGuardar").click(function (e) {
        guardaryeditar(e);
    });

    $("#btnAgregarArt").click(function (e) {
        listarArticulos(e);
    });



    $.post("../ajax/cotizaciones.php?op=selectVendedor", function (r) {
        $("#idvendedor").html(r);
        $('#idvendedor').selectpicker('refresh');
    });



    $("#btnGuardarCierre").click(function (e) {
        guardaryeditarCierre(e);
    });

    $.post("../ajax/articulo.php?op=selectCategoria", function (r) {
        // Primera opción: Seleccione una categoría
        // Segunda opción: TODO
        // Luego lo que devuelve PHP
        $("#idcategoria").html(
            '<option value="">Seleccione una categoría</option>' +
            '<option value="0">TODO</option>' +
            r
        );

        $('#idcategoria').selectpicker('refresh');
    });

    $.post("../ajax/articulo.php?op=selectCategoria", function (r) {
        // 🔹 Botón "TODO" fijo al inicio
        let botones = `
          <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="listarArticulosxcategoria(0, 'TODO')">TODO</button>
        `;

        // 🔹 Convertir las opciones <option> del select en botones
        let tempDiv = document.createElement("div");
        tempDiv.innerHTML = r.trim();

        $(tempDiv)
            .find("option")
            .each(function () {
                let id = $(this).val();
                let nombre = $(this).text().trim();

                if (id) {
                    botones += `
                <button type="button" class="btn btn-outline-primary btn-sm me-2" 
                        onclick="listarArticulosxcategoria(${id}, '${nombre.replace(/'/g, "\\'")}')">
                  ${nombre}
                </button>
              `;
                }
            });

        // 🔹 Inyectar los botones en el contenedor
        $("#contenedor-categorias").html(botones);
    });
}




/////CLIENTE NUEVO

function validarnit() {


    var nit = $("#nit").val().trim();
    var tipo = $("#tipo_documento_cliente").val();

    if (tipo == "NIT") {

        // Eliminar espacios y guiones dentro del NIT
        nit = $("#nit").val().replace(/[\s-]+/g, "");

    } else if (tipo == "DPI") {

        // Prefijo fijo
        let cui = "CUI";

        // Quitar espacios y guiones del DPI
        let dpiNumero = $("#nit").val().replace(/[\s-]+/g, "");

        // Concatenar CUI + numero DPI
        nit = cui + dpiNumero;
    }


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

                // buscarnitenSistemaparaIdcliente(nit);
            } else {
                $("#idcliente").val('0');
                $("#codigo_cliente").val('');
                $("#correo_cliente").val("sincorreo@gmail.com");
                $("#telefono_cliente").val("0");
                $("#tipo_documento_cliente").val("NIT");
                $("#tipo_documento_cliente").selectpicker('refresh');
                $("#descuento_cliente").val("0");

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
                $("#descuento_cliente").val("0");

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
                $("#descuento_cliente").val(data.descuento_cliente);

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
                $("#tipo_cliente").val("PUBLICO");
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val("0");

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
                $("#tipo_cliente").val(data.tipo_cliente);
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val(data.descuento_cliente);

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
                $("#tipo_cliente").val("PUBLICO");
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val("0");


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
                $("#tipo_cliente").val(data.tipo_cliente);
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val(data.descuento_cliente);

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
    tipo_documento, codigo_cliente, tipo_cliente, descuento_cliente) {
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
    $("#tipo_cliente").val(tipo_cliente);
    $("#tipo_cliente").selectpicker('refresh');
    $("#descuento_cliente").val(descuento_cliente);
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
    $("#tipo_cliente").val("PUBLICO");
    $("#tipo_cliente").selectpicker('refresh');
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




function obtenerClienteCotizacion(idcotizacion) {
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
        $("#tipo_cliente").val(data.tipo_cliente);
        $("#tipo_cliente").selectpicker('refresh');
        $("#idcliente").val(data.idcliente);

        $("#tipo_documento_cliente").val(data.tipo_documento_cliente);
        $("#tipo_documento_cliente").selectpicker('refresh');

        $("#fecha_hora").val(data.fecha);

        $("#forma_pago").val(data.forma_pago);
        $("#forma_pago").selectpicker('refresh');

        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#tipo_comprobante").selectpicker('refresh');

        $("#tipo_pagoBacVisaNet").val(data.tipo_pagoBacVisaNet);
        $("#tipo_pagoBacVisaNet").selectpicker('refresh');

        $("#opcionesAdicionales").val(data.opcionesAdicionales);
        $("#opcionesAdicionales").selectpicker('refresh');

        $("#total_venta").val(data.total_venta);
        $("#total_ventades").val(data.total_ventades);

        $("#idvendedor").val(data.idvendedor);
        $("#idvendedor").selectpicker('refresh');

        $("#forma_productos").val(data.forma_productos);
        $("#forma_productos").selectpicker('refresh');
        $("#comentario_cotizacion").val(data.comentario_cotizacion);

        $("#destino").val(data.destino);
        $("#destino").selectpicker('refresh');

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


    $('#myModalImpresionFAc').modal('hide');

    $("#total_venta").val("");
    $("#total_ventades").val("");

    $("#cefectivo").val("0");
    $("#rescambio").val("0");

    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);

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
                url: '../ajax/cotizaciones.php?op=listar',
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte },
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
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
                url: '../ajax/venta.php?op=listarArticulosVentaCantidad',
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

function guardaryeditar(e) {

    if (detalles > 0) {

    }
    else {
        alert("No se puede Guardar porque no has agregado item a tu venta ");
        return;
    }
    agruparDatos();
    let datosArticuloscot = JSON.parse(localStorage.getItem('datosArticuloscot'));
    var forma_pago = document.getElementById('forma_pago').value;
    var tipo_pagoBacVisaNet = document.getElementById('tipo_pagoBacVisaNet').value;


    var tipo_comprobante1 = $("#tipo_comprobante").val();
    e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#formulario")[0]);
    formData.append("datosArticuloscot", JSON.stringify(datosArticuloscot));
    load();
    $.ajax({
        url: "../ajax/cotizaciones.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log("Datos ", datos);
            Swal.close();

            $('#myModalImpresionFAc').modal('show');
            $("#idventa_impresion").val(datos);
            /// window.location.reload(); 

            var tipo_comprobante = $("#tipo_comprobante").val();
            //bootbox.alert("Cotizacion Registrada Exitosamente");

            listar();
            if (confirm("Desea Imprimir su Cotizacion")) {
                if (tipo_comprobante == "Factura") {
                    window.open("../reportes/exTicket_Fel.php?id=" + datos, '_blank');
                    window.location.reload();
                }
                else if (tipo_comprobante == "Envio") {
                    window.open("../reportes/exTicket.php?id=" + datos, '_blank');
                    window.location.reload();

                }
            }
            else {
                window.location.reload();
            }

        }

    });


}

// 🔹 Detectar cierre del modal de impresión o clic fuera
$(document).ready(function () {
    // Cuando el modal se cierra (por la X o clic en el fondo)
    $('#myModalImpresionFAc').on('hidden.bs.modal', function () {
        // 🔄 Recargar la vista completa para limpiar todo
        window.location.reload();
    });


});

function impresionticket58mm() {
    var url = "../reportes/exCotizacion58mm.php?id=" + $("#idventa_impresion").val();
    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario)
}

function impresionticket79mm() {
    var url = "../reportes/exCotizacion.php?id=" + $("#idventa_impresion").val();
    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario)
}

function impresionticketCarta() {
    var url = "../reportes/exCotizacionCarta.php?id=" + $("#idventa_impresion").val();
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


function agruparDatos() {
    const datos = {
        articulos: {
            idarticulo: [],
            precio_compra: [],
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
            descuento_permitido: [],
            descuento_porcentaje: [],
            descripcion_detalle: [],
            subtotal1: [],
            subtotaldes1: [],
            check_extras: [],
            idarticuloExtra_extras: [],
            cantidad_extra: [],
            idproducto_extra: [],
            tipo_item_extras: []
        }
    };

    $('#detalles .filas').each(function () {
        const fila = $(this);
        datos.articulos.idarticulo.push(fila.find('input[name="idarticulo[]"]').val());
        datos.articulos.precio_compra.push(fila.find('input[name="precio_compra[]"]').val());
        datos.articulos.stockinven.push(fila.find('input[name="stockinven[]"]').val());
        datos.articulos.cantidadpresentacion.push(fila.find('input[name="cantidadpresentacion[]"]').val());
        datos.articulos.cantidad.push(fila.find('input[name="cantidad[]"]').val());
        datos.articulos.totalcantidadpresentacion.push(fila.find('input[name="totalcantidadpresentacion[]"]').val());
        datos.articulos.presentacion.push(fila.find('select[name="presentacion[]"]').val());
        datos.articulos.presen.push(fila.find('input[name="presen[]"]').val());
        datos.articulos.precio_ventaSistema.push(fila.find('input[name="precio_ventaSistema[]"]').val());
        datos.articulos.precio_ventaSistema2.push(fila.find('input[name="precio_ventaSistema2[]"]').val());
        datos.articulos.q_ref.push(fila.find('input[name="q_ref[]"]').val());
        datos.articulos.precio_venta.push(fila.find('input[name="precio_venta[]"]').val());
        datos.articulos.precio_recargoPV.push(fila.find('input[name="precio_recargoPV[]"]').val());
        datos.articulos.precio_recargoQRef.push(fila.find('input[name="precio_recargoQRef[]"]').val());
        datos.articulos.descuento_permitido.push(fila.find('input[name="descuento_permitido[]"]').val());
        datos.articulos.descuento_porcentaje.push(fila.find('input[name="descuento_porcentaje[]"]').val());
        datos.articulos.descripcion_detalle.push(fila.find('input[name="descripcion_detalle[]"]').val());
        datos.articulos.subtotal1.push(fila.find('input[name="subtotal1[]"]').val());
        datos.articulos.subtotaldes1.push(fila.find('input[name="subtotaldes1[]"]').val());
    });

    // Recolectamos los extras seleccionados
    $('#detalles .extras-row').each(function () {
        const checkboxes = $(this).find('input[type="checkbox"]:checked');
        checkboxes.each(function () {
            const checkbox = $(this);
            datos.articulos.check_extras.push(checkbox.val());
            datos.articulos.idarticuloExtra_extras.push(checkbox.data('idarticulo-extra'));
            datos.articulos.cantidad_extra.push(checkbox.data('cantidad-extra'));
            datos.articulos.idproducto_extra.push(checkbox.data('idproducto-extra'));
            datos.articulos.tipo_item_extras.push(checkbox.data('tipo-item'));
        });
    });

    // Si no hay extras seleccionados, vaciar los arrays de extras por seguridad
    if (datos.articulos.check_extras.length === 0) {
        datos.articulos.check_extras = [];
        datos.articulos.idarticuloExtra_extras = [];
        datos.articulos.cantidad_extra = [];
        datos.articulos.idproducto_extra = [];
    }

    const datosJSON = JSON.stringify(datos);
    localStorage.setItem('datosArticuloscot', datosJSON);
}

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
function anular(idcotizacion) {
    var valor = prompt("Ingresa la contraseña de de Autorizacion", "");
    if (valor == "5820005710") {

        bootbox.confirm("¿Está Seguro de anular la venta?", function (result) {
            if (result) {
                $.post("../ajax/cotizaciones.php?op=anular", { idcotizacion: idcotizacion }, function (e) {
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


function agregarDetalleCantidad(idarticulo, nombre, precio_venta, stock, descuento_porcentaje, stock_unidad, precio_unidad,
    stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos, precio_sacos,
    stock_paquete, precio_paquete, precio_rango1, precio_rango2, precio_rango3, precio_compra, precio_activado, precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, tipo_producto, cantidad) {

    if (cantidad == 0 || cantidad == null) {
        cantidad = 1;
    } else {
        cantidad = cantidad;
    }

    if (tipo_producto == "Productos") {
        if (parseFloat(cantidad) > parseFloat(stock)) {
            Swal.fire({
                title: 'Mensaje!',
                text: "La cantidad no puede ser mayor al stock",
                icon: 'warning',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
            return;
        }
    }

    precio_activado = precio_activado.toString().trim();

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
                '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + precio_venta + `" ${precio_activado.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
                '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '</tr>' +
                '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
                '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
                '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
                '</td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
            $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}





function agregarDetalle(idarticulo, nombre, precio_venta, stock, descuento_porcentaje,
    precio_rango1, precio_rango1_Dos,
    precio_rango2, precio_rango2_Dos,
    precio_rango3, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango1_Distribuidor, precio_rango1_Mayorista,
    precio_rango2_MecanicoDos, precio_rango2_DistribuidorDos, precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres, precio_rango3_DistribuidorTres, precio_rango3_MayoristaTres,
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
    precio_activado, facturar_cero, precio_compra) {

    if (facturar_cero == 'NO') {
        // Validar si la cantidad es mayor al stock disponible
        if (parseInt(cantidad) > parseInt(stock)) {
            alert("La cantidad ingresada es mayor al stock disponible");
            return;
        }
    }

    // ✅ Mensaje superior derecho
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Producto agregado',
        text: nombre,
        showConfirmButton: false,
        timer: 1000, // 1.5 segundos
        timerProgressBar: true
    });
    console.log("Este primero");

    let forma_productos = $("#forma_productos").val();
    precio_activado = precio_activado.toString().trim();

    var cantidad = 1;
    var stockinven = stock;
    var presen = 'UNIDAD';
    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;


    if (forma_productos === "Agrupado") {
        ///
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
                    '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                    '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                    `<td>
                        <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                                `+ (parseFloat(stock_unidad) > 0 ? `<option value="${nombre_01}">${nombre_01}</option>` : ``) + ` 
                                `+ (parseFloat(stock_blister) > 0 ? `<option value="${nombre_02}">${nombre_02}</option>` : ``) + ` 
                                `+ (parseFloat(stock_caja) > 0 ? `<option value="${nombre_03}">${nombre_03}</option>` : ``) + ` 
                                `+ (parseFloat(stock_fardo) > 0 ? `<option value="${nombre_04}">${nombre_04}</option>` : ``) + ` 
                                `+ (parseFloat(stock_sacos) > 0 ? `<option value="${nombre_05}">${nombre_05}</option>` : ``) + ` 
                                `+ (parseFloat(stock_paquete) > 0 ? `<option value="${nombre_06}">${nombre_06}</option>` : ``) + ` 
                                `+ (parseFloat(stock_paquete) > 0 ? `<option value="${nombre_06}">${nombre_06}</option>` : ``) + ` 
                                `+ (parseFloat(stock_07) > 0 ? `<option value="${nombre_07}">${nombre_07}</option>` : ``) + ` 
                                `+ (parseFloat(stock_08) > 0 ? `<option value="${nombre_08}">${nombre_08}</option>` : ``) + ` 
                                `+ (parseFloat(stock_09) > 0 ? `<option value="${nombre_09}">${nombre_09}</option>` : ``) + ` 
                                `+ (parseFloat(stock_10) > 0 ? `<option value="${nombre_10}">${nombre_10}</option>` : ``) + ` 
                                `+ (parseFloat(stock_11) > 0 ? `<option value="${nombre_11}">${nombre_11}</option>` : ``) + ` 
                                `+ (parseFloat(stock_12) > 0 ? `<option value="${nombre_12}">${nombre_12}</option>` : ``) + ` 
                                `+ (parseFloat(stock_13) > 0 ? `<option value="${nombre_13}">${nombre_13}</option>` : ``) + ` 
                                `+ (parseFloat(stock_14) > 0 ? `<option value="${nombre_14}">${nombre_14}</option>` : ``) + ` 
                                `+ (parseFloat(stock_15) > 0 ? `<option value="${nombre_15}">${nombre_15}</option>` : ``) + ` 
                                `+ (parseFloat(stock_16) > 0 ? `<option value="${nombre_16}">${nombre_16}</option>` : ``) + ` 
                                `+ (parseFloat(stock_17) > 0 ? `<option value="${nombre_17}">${nombre_17}</option>` : ``) + ` 
                                `+ (parseFloat(stock_18) > 0 ? `<option value="${nombre_18}">${nombre_18}</option>` : ``) + ` 
                                `+ (parseFloat(stock_19) > 0 ? `<option value="${nombre_19}">${nombre_19}</option>` : ``) + ` 
                                `+ (parseFloat(stock_20) > 0 ? `<option value="${nombre_20}">${nombre_20}</option>` : ``) + ` 

                        </select>
                    </td>`+
                    `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                        <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_venta + `">
                        <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_venta + `">
                        <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + precio_venta + `" ${precio_activado.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                            <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                            <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                            <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
                    '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
                    '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                    '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                    '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
                    '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                    '<td><button type="button" onclick="mostrarextras(' + cont + ', \'' + idarticulo + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
                    '</tr>' +
                    '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
                    '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
                    '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
                    '</td>' +
                    '</tr>';
                cont++;
                detalles = detalles + 1;
                $(fila).prependTo('#detalles');
            } else {
                var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
                $("#cxcantidad" + idarticulo).val(cxcantidad)
                // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
                modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                    precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                    precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
            }

            modificarSubototales();
        }
        else {
            alert("Error al ingresar el detalle, revisar los datos del artículo");
        }
        ///
        // aquí tu lógica para agrupado
    } else if (forma_productos === "Detallado") {
        ///
        if (idarticulo != "") {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                        <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                                `+ (parseFloat(stock_unidad) > 0 ? `<option value="${nombre_01}">${nombre_01}</option>` : ``) + ` 
                                `+ (parseFloat(stock_blister) > 0 ? `<option value="${nombre_02}">${nombre_02}</option>` : ``) + ` 
                                `+ (parseFloat(stock_caja) > 0 ? `<option value="${nombre_03}">${nombre_03}</option>` : ``) + ` 
                                `+ (parseFloat(stock_fardo) > 0 ? `<option value="${nombre_04}">${nombre_04}</option>` : ``) + ` 
                                `+ (parseFloat(stock_sacos) > 0 ? `<option value="${nombre_05}">${nombre_05}</option>` : ``) + ` 
                                `+ (parseFloat(stock_paquete) > 0 ? `<option value="${nombre_06}">${nombre_06}</option>` : ``) + ` 
                                `+ (parseFloat(stock_paquete) > 0 ? `<option value="${nombre_06}">${nombre_06}</option>` : ``) + ` 
                                `+ (parseFloat(stock_07) > 0 ? `<option value="${nombre_07}">${nombre_07}</option>` : ``) + ` 
                                `+ (parseFloat(stock_08) > 0 ? `<option value="${nombre_08}">${nombre_08}</option>` : ``) + ` 
                                `+ (parseFloat(stock_09) > 0 ? `<option value="${nombre_09}">${nombre_09}</option>` : ``) + ` 
                                `+ (parseFloat(stock_10) > 0 ? `<option value="${nombre_10}">${nombre_10}</option>` : ``) + ` 
                                `+ (parseFloat(stock_11) > 0 ? `<option value="${nombre_11}">${nombre_11}</option>` : ``) + ` 
                                `+ (parseFloat(stock_12) > 0 ? `<option value="${nombre_12}">${nombre_12}</option>` : ``) + ` 
                                `+ (parseFloat(stock_13) > 0 ? `<option value="${nombre_13}">${nombre_13}</option>` : ``) + ` 
                                `+ (parseFloat(stock_14) > 0 ? `<option value="${nombre_14}">${nombre_14}</option>` : ``) + ` 
                                `+ (parseFloat(stock_15) > 0 ? `<option value="${nombre_15}">${nombre_15}</option>` : ``) + ` 
                                `+ (parseFloat(stock_16) > 0 ? `<option value="${nombre_16}">${nombre_16}</option>` : ``) + ` 
                                `+ (parseFloat(stock_17) > 0 ? `<option value="${nombre_17}">${nombre_17}</option>` : ``) + ` 
                                `+ (parseFloat(stock_18) > 0 ? `<option value="${nombre_18}">${nombre_18}</option>` : ``) + ` 
                                `+ (parseFloat(stock_19) > 0 ? `<option value="${nombre_19}">${nombre_19}</option>` : ``) + ` 
                                `+ (parseFloat(stock_20) > 0 ? `<option value="${nombre_20}">${nombre_20}</option>` : ``) + ` 

                        </select>
                    </td>`+
                `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                        <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_venta + `">
                        <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_venta + `">
                        <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + precio_venta + `" ${precio_activado.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                            <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                            <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                            <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
                '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '<td><button type="button" onclick="mostrarextras(' + cont + ', \'' + idarticulo + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
                '</tr>' +
                '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
                '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
                '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
                '</td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');

            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);


            modificarSubototales();
        }
        else {
            alert("Error al ingresar el detalle, revisar los datos del artículo");
        }
        ///
        // aquí tu lógica para detallado
    } else {
        console.log("No seleccionaste nada");
    }

}

let ultimaSeleccion = $("#forma_productos").val(); // valor inicial

$("#forma_productos").on("change", function () {
    let nuevaSeleccion = $(this).val();

    // Validar si ya hay filas en la tabla
    if ($("#detalles tbody tr").length > 0) {
        // Si intenta cambiar después de haber agregado productos
        alert("⚠️ No puedes cambiar la forma de productos porque ya hay artículos agregados.");

        // Volver al valor anterior
        $(this).val(ultimaSeleccion).selectpicker('refresh');
    } else {
        // Si aún no hay filas, sí se puede cambiar
        ultimaSeleccion = nuevaSeleccion;
    }
});


function obtenerdetallecotizacion(idcotizacion) {
    $.post("../ajax/cotizaciones.php?op=paraventa", { idcotizacion: idcotizacion }, function (data) {
        console.log(data);
        data = JSON.parse(data);
        Swal.close()

        $.each(data, function (i, item) {
            agregarDetalle2(item.idarticulo, item.nombre, item.precio_venta, item.stock, item.descuento_porcentaje,
                item.precio_rango1, item.precio_rango1_Dos,
                item.precio_rango2, item.precio_rango2_Dos,
                item.precio_rango3, item.precio_rango3_Dos,
                item.precio_rango1_Mecanico, item.precio_rango1_Distribuidor, item.precio_rango1_Mayorista,
                item.precio_rango2_MecanicoDos, item.precio_rango2_DistribuidorDos, item.precio_rango2_MayoristaDos,
                item.precio_rango3_MecanicoTres, item.precio_rango3_DistribuidorTres, item.precio_rango3_MayoristaTres,
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
                item.precio_activado, item.facturar_cero, item.precio_compra,
                item.cantidadpresentacion, item.totalcantidadpresentacion, item.presen,
                item.precio_ventaSistema, item.precio_ventaSistema2, item.q_ref,
                item.precio_recargoPV, item.precio_recargoQRef, item.descuento, item.descripcion_detalle, item.cantidad);
        });
    })
}


function agregarDetalle2(idarticulo, nombre, precio_venta, stock, descuento_porcentaje,
    precio_rango1, precio_rango1_Dos,
    precio_rango2, precio_rango2_Dos,
    precio_rango3, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango1_Distribuidor, precio_rango1_Mayorista,
    precio_rango2_MecanicoDos, precio_rango2_DistribuidorDos, precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres, precio_rango3_DistribuidorTres, precio_rango3_MayoristaTres,
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
    precio_activado, facturar_cero, precio_compra, cantidadpresentacion,
    totalcantidadpresentacion, presen, precio_ventaSistema, precio_ventaSistema2,
    q_ref, precio_recargoPV, precio_recargoQRef, descuento, descripcion_detalle, cantidad) {
    if (facturar_cero == 'NO') {
        // Validar si la cantidad es mayor al stock disponible
        if (parseInt(cantidad) > parseInt(stock)) {
            alert("La cantidad ingresada es mayor al stock disponible");
            return;
        }
    }

    // ✅ Mensaje superior derecho
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Producto agregado',
        text: nombre,
        showConfirmButton: false,
        timer: 1000, // 1.5 segundos
        timerProgressBar: true
    });
    console.log("Este segundo");

    let forma_productos = $("#forma_productos").val();
    let idcotizacion_r = $("#idcotizacion").val();

    precio_activado = precio_activado.toString().trim();

    var cantidad = cantidad;
    var stockinven = stock;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;

    if (forma_productos === "Agrupado") {
        /////
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
                    '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                    '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                    `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                                '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)"  >
                `+ (parseFloat(stock_unidad) > 0 ? `<option value="` + nombre_01 + `" ` + (presen === nombre_01 ? 'selected' : '') + `>${nombre_01}</option>` : ``) + ` 
                `+ (parseFloat(stock_blister) > 0 ? `<option value="` + nombre_02 + `" ` + (presen === nombre_02 ? 'selected' : '') + `>${nombre_02}</option>` : ``) + ` 
                `+ (parseFloat(stock_caja) > 0 ? `<option value="` + nombre_03 + `" ` + (presen === nombre_03 ? 'selected' : '') + `>${nombre_03}</option>` : ``) + ` 
                `+ (parseFloat(stock_fardo) > 0 ? `<option value="` + nombre_04 + `" ` + (presen === nombre_04 ? 'selected' : '') + `>${nombre_04}</option>` : ``) + ` 
                `+ (parseFloat(stock_sacos) > 0 ? `<option value="` + nombre_05 + `" ` + (presen === nombre_05 ? 'selected' : '') + `>${nombre_05}</option>` : ``) + ` 
                `+ (parseFloat(stock_paquete) > 0 ? `<option value="` + nombre_06 + `" ` + (presen === nombre_06 ? 'selected' : '') + `>${nombre_06}</option>` : ``) + ` 
                `+ (parseFloat(stock_07) > 0 ? `<option value="` + nombre_07 + `" ` + (presen === nombre_07 ? 'selected' : '') + `>${nombre_07}</option>` : ``) + ` 
                `+ (parseFloat(stock_08) > 0 ? `<option value="` + nombre_08 + `" ` + (presen === nombre_08 ? 'selected' : '') + `>${nombre_08}</option>` : ``) + ` 
                `+ (parseFloat(stock_09) > 0 ? `<option value="` + nombre_09 + `" ` + (presen === nombre_09 ? 'selected' : '') + `>${nombre_09}</option>` : ``) + ` 
                `+ (parseFloat(stock_10) > 0 ? `<option value="` + nombre_10 + `" ` + (presen === nombre_10 ? 'selected' : '') + `>${nombre_10}</option>` : ``) + ` 
                `+ (parseFloat(stock_11) > 0 ? `<option value="` + nombre_11 + `" ` + (presen === nombre_11 ? 'selected' : '') + `>${nombre_11}</option>` : ``) + ` 
                `+ (parseFloat(stock_12) > 0 ? `<option value="` + nombre_12 + `" ` + (presen === nombre_12 ? 'selected' : '') + `>${nombre_12}</option>` : ``) + ` 
                `+ (parseFloat(stock_13) > 0 ? `<option value="` + nombre_13 + `" ` + (presen === nombre_13 ? 'selected' : '') + `>${nombre_13}</option>` : ``) + ` 
                `+ (parseFloat(stock_14) > 0 ? `<option value="` + nombre_14 + `" ` + (presen === nombre_14 ? 'selected' : '') + `>${nombre_14}</option>` : ``) + ` 
                `+ (parseFloat(stock_15) > 0 ? `<option value="` + nombre_15 + `" ` + (presen === nombre_15 ? 'selected' : '') + `>${nombre_15}</option>` : ``) + ` 
                `+ (parseFloat(stock_16) > 0 ? `<option value="` + nombre_16 + `" ` + (presen === nombre_16 ? 'selected' : '') + `>${nombre_16}</option>` : ``) + ` 
                `+ (parseFloat(stock_17) > 0 ? `<option value="` + nombre_17 + `" ` + (presen === nombre_17 ? 'selected' : '') + `>${nombre_17}</option>` : ``) + ` 
                `+ (parseFloat(stock_18) > 0 ? `<option value="` + nombre_18 + `" ` + (presen === nombre_18 ? 'selected' : '') + `>${nombre_18}</option>` : ``) + ` 
                `+ (parseFloat(stock_19) > 0 ? `<option value="` + nombre_19 + `" ` + (presen === nombre_19 ? 'selected' : '') + `>${nombre_19}</option>` : ``) + ` 
                `+ (parseFloat(stock_20) > 0 ? `<option value="` + nombre_20 + `" ` + (presen === nombre_20 ? 'selected' : '') + `>${nombre_20}</option>` : ``) + `  

                </select>
            </td>`+
                    `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                    <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_ventaSistema + `">
                    <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_ventaSistema2 + `">
                    <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + q_ref + `" ${q_ref.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                        <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                        <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="` + precio_recargoPV + `" >
                        <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="` + precio_recargoQRef + `" ></td>` +
                    '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="' + descuento + '"></td>' +
                    '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                    '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                    '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="' + descripcion_detalle + '"></td>' +
                    '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                    '<td><button type="button" onclick="mostrarextras_Extras(' + cont + ', \'' + idarticulo + '\',\'' + idcotizacion_r + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
                    '</tr>' +
                    '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
                    '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
                    '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
                    '</td>' +
                    '</tr>';
                cont++;
                detalles = detalles + 1;
                $(fila).prependTo('#detalles');
                mostrarextras_Extras(cont - 1, idarticulo, idcotizacion_r);
            } else {
                var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
                $("#cxcantidad" + idarticulo).val(cxcantidad)
                // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
                modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                    precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                    precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
            }

            modificarSubototales();
        }
        else {
            alert("Error al ingresar el detalle, revisar los datos del artículo");
        }
        /////

        // aquí tu lógica para agrupado
    }
    else if (forma_productos === "Detallado") {

        if (idarticulo != "") {


            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                                '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)"  >
                `+ (parseFloat(stock_unidad) > 0 ? `<option value="` + nombre_01 + `" ` + (presen === nombre_01 ? 'selected' : '') + `>${nombre_01}</option>` : ``) + ` 
                `+ (parseFloat(stock_blister) > 0 ? `<option value="` + nombre_02 + `" ` + (presen === nombre_02 ? 'selected' : '') + `>${nombre_02}</option>` : ``) + ` 
                `+ (parseFloat(stock_caja) > 0 ? `<option value="` + nombre_03 + `" ` + (presen === nombre_03 ? 'selected' : '') + `>${nombre_03}</option>` : ``) + ` 
                `+ (parseFloat(stock_fardo) > 0 ? `<option value="` + nombre_04 + `" ` + (presen === nombre_04 ? 'selected' : '') + `>${nombre_04}</option>` : ``) + ` 
                `+ (parseFloat(stock_sacos) > 0 ? `<option value="` + nombre_05 + `" ` + (presen === nombre_05 ? 'selected' : '') + `>${nombre_05}</option>` : ``) + ` 
                `+ (parseFloat(stock_paquete) > 0 ? `<option value="` + nombre_06 + `" ` + (presen === nombre_06 ? 'selected' : '') + `>${nombre_06}</option>` : ``) + ` 
                `+ (parseFloat(stock_07) > 0 ? `<option value="` + nombre_07 + `" ` + (presen === nombre_07 ? 'selected' : '') + `>${nombre_07}</option>` : ``) + ` 
                `+ (parseFloat(stock_08) > 0 ? `<option value="` + nombre_08 + `" ` + (presen === nombre_08 ? 'selected' : '') + `>${nombre_08}</option>` : ``) + ` 
                `+ (parseFloat(stock_09) > 0 ? `<option value="` + nombre_09 + `" ` + (presen === nombre_09 ? 'selected' : '') + `>${nombre_09}</option>` : ``) + ` 
                `+ (parseFloat(stock_10) > 0 ? `<option value="` + nombre_10 + `" ` + (presen === nombre_10 ? 'selected' : '') + `>${nombre_10}</option>` : ``) + ` 
                `+ (parseFloat(stock_11) > 0 ? `<option value="` + nombre_11 + `" ` + (presen === nombre_11 ? 'selected' : '') + `>${nombre_11}</option>` : ``) + ` 
                `+ (parseFloat(stock_12) > 0 ? `<option value="` + nombre_12 + `" ` + (presen === nombre_12 ? 'selected' : '') + `>${nombre_12}</option>` : ``) + ` 
                `+ (parseFloat(stock_13) > 0 ? `<option value="` + nombre_13 + `" ` + (presen === nombre_13 ? 'selected' : '') + `>${nombre_13}</option>` : ``) + ` 
                `+ (parseFloat(stock_14) > 0 ? `<option value="` + nombre_14 + `" ` + (presen === nombre_14 ? 'selected' : '') + `>${nombre_14}</option>` : ``) + ` 
                `+ (parseFloat(stock_15) > 0 ? `<option value="` + nombre_15 + `" ` + (presen === nombre_15 ? 'selected' : '') + `>${nombre_15}</option>` : ``) + ` 
                `+ (parseFloat(stock_16) > 0 ? `<option value="` + nombre_16 + `" ` + (presen === nombre_16 ? 'selected' : '') + `>${nombre_16}</option>` : ``) + ` 
                `+ (parseFloat(stock_17) > 0 ? `<option value="` + nombre_17 + `" ` + (presen === nombre_17 ? 'selected' : '') + `>${nombre_17}</option>` : ``) + ` 
                `+ (parseFloat(stock_18) > 0 ? `<option value="` + nombre_18 + `" ` + (presen === nombre_18 ? 'selected' : '') + `>${nombre_18}</option>` : ``) + ` 
                `+ (parseFloat(stock_19) > 0 ? `<option value="` + nombre_19 + `" ` + (presen === nombre_19 ? 'selected' : '') + `>${nombre_19}</option>` : ``) + ` 
                `+ (parseFloat(stock_20) > 0 ? `<option value="` + nombre_20 + `" ` + (presen === nombre_20 ? 'selected' : '') + `>${nombre_20}</option>` : ``) + `  

                </select>
            </td>`+
                `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                    <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_ventaSistema + `">
                    <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_ventaSistema2 + `">
                    <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + q_ref + `" ${q_ref.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                        <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                        <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="` + precio_recargoPV + `" >
                        <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="` + precio_recargoQRef + `" ></td>` +
                '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="' + descuento + '"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="' + descripcion_detalle + '"></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '<td><button type="button" onclick="mostrarextras_Extras(' + cont + ', \'' + idarticulo + '\',\'' + idcotizacion_r + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
                '</tr>' +
                '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
                '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
                '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
                '</td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');
            mostrarextras_Extras(cont - 1, idarticulo, idcotizacion_r);

            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);



            modificarSubototales();
        }
        else {
            alert("Error al ingresar el detalle, revisar los datos del artículo");
        }

        // aquí tu lógica para detallado
    } else {
        console.log("No seleccionaste nada");
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
        var precioventapaquete = 0;
        if (stock_07 > 0) {
            precioventapaquete = (precio_07 / stock_07); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_07);
        $("#precio_venta" + id).val(precioventapaquete);
        $("#q_ref" + id).val(precio_07);
        $("#presen" + id).val(nombre_07);


        $("#precio_ventaSistema" + id).val(precioventapaquete);
        $("#precio_ventaSistema2" + id).val(precio_07);
    }
    else if (presentacion == nombre_08) {
        var precioventapaquete = 0;
        if (stock_08 > 0) {
            precioventapaquete = (precio_08 / stock_08); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_08);
        $("#precio_venta" + id).val(precioventapaquete);
        $("#q_ref" + id).val(precio_08);
        $("#presen" + id).val(nombre_08);


        $("#precio_ventaSistema" + id).val(precioventapaquete);
        $("#precio_ventaSistema2" + id).val(precio_08);
    }

    else if (presentacion == nombre_09) {
        var precioventapaquete = 0;
        if (stock_09 > 0) {
            precioventapaquete = (precio_09 / stock_09); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_09);
        $("#precio_venta" + id).val(precioventapaquete);
        $("#q_ref" + id).val(precio_09);
        $("#presen" + id).val(nombre_09);


        $("#precio_ventaSistema" + id).val(precioventapaquete);
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
        $("#precio_ventaSistema2" + id).val(precio_venta);

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

        console.log("precSistema2" + inpPSistema2.value)



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


function modificarSubototalespreciopresentacion() {

    var cant = document.getElementsByName("cantidad[]");
    var cantpre = document.getElementsByName("cantidadpresentacion[]");
    var prec = document.getElementsByName("precio_venta[]");
    var preqref = document.getElementsByName("q_ref[]");

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpCpre = cantpre[i];
        var inpP = prec[i];
        var inpPreqref = preqref[i];
        inpP.value = (parseFloat(inpPreqref.value) / parseFloat(inpCpre.value));
        document.getElementsByName("precio_venta[]")[i].innerHTML = inpP.value;
        document.getElementsByName("precio_ventaSistema2[]")[i].value = inpPreqref.value;
        document.getElementsByName("precio_ventaSistema[]")[i].value = inpP.value;


    }
    calcularTotales();
    calcularTotalesdes();
    modificarSubototales();
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
    var descuentopermitido = (document.getElementsByName("descuento_permitido[]"));
    var calculo_descuento = $("#calculo_descuento").val();
    var stockinven = document.getElementsByName("stockinven[]");

    /////
    var valortarjeta = $("#valor_descuentoGeneral").val();
    var descuento_general = $("#descuento_general option:selected").text();


    /////   

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
        var inpDescuentopermitido = descuentopermitido[i];
        var descuentoPorProducto = 0;
        var inpStock = stockinven[i];


        if (parseFloat(inpC.value) == 0 || parseFloat(inpC.value) == null) {
            inpC.value = 1;
        } else {
            inpC.value = inpC.value;
        }



        if (descuento_general == 'DESCUENTO GENERAL') {

            descuentoPorProducto = parseFloat(valortarjeta) / cant.length;
            document.getElementsByName("descuento_porcentaje[]")[i].value = parseFloat(descuentoPorProducto).toFixed(2);

        } else {
            descuentoPorProducto = 0;
            if (parseFloat(inpD.value) > parseFloat(inpDescuentopermitido.value)) {
                console.log("descuento no permitido");
                Swal.fire({
                    title: 'Descuento no permitido',
                    text: 'El descuento ingresado supera lo permitido',
                    icon: 'error',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                }).then(() => {
                    // Establecer el valor después de que se cierre la alerta
                    document.getElementsByName("descuento_porcentaje[]")[i].value = 0;
                    inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
                    document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;


                    inpS.value = (inpTpres.value * (inpP.value - ((inpP.value * inpD.value) / 100)));
                    document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
                    inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

                    inpSdes.value = (((inpP.value * inpD.value) / 100) * inpTpres.value);
                    document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
                    inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
                    //console.log(inpSdes.value);                    
                });

                return; // Detenemos la ejecución si el descuento es inválido
            }
        }


        if (calculo_descuento === 'QUETZALES') {

            inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
            document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;


            inpS.value = (inpTpres.value * (inpP.value - inpD.value));
            document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
            inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

            inpSdes.value = (inpD.value * inpTpres.value);
            document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
            inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
            //console.log(inpSdes.value);

        }
        else if (calculo_descuento === 'PORCENTAJE') {
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

    $("#totaldes").html("Q/. " + total.toFixed(2)); // Formatear el total a dos decimales
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

    //console.log('subtotal'+total)

    $('.subtotal-extra').each(function () {
        // Obtenemos el texto del subtotal de extras y lo parseamos
        const valorExtra = parseFloat($(this).text());
        if (!isNaN(valorExtra)) {
            total += valorExtra;
        }
    });

    $("#total").html("Q/. " + total.toFixed(2)); // Mostrar el total en el HTML
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

                console.log(arrayproduc);

                if (arrayproduc[0] != "undefined") {
                    agregarDetalle(arrayproduc[0], arrayproduc[1], arrayproduc[2], arrayproduc[3],
                        arrayproduc[4], arrayproduc[5], arrayproduc[6], arrayproduc[7], arrayproduc[8],
                        arrayproduc[9], arrayproduc[10], arrayproduc[11], arrayproduc[12], arrayproduc[13],
                        arrayproduc[14], arrayproduc[15], arrayproduc[16], arrayproduc[17], arrayproduc[18],
                        arrayproduc[19], arrayproduc[20], arrayproduc[21], arrayproduc[22], arrayproduc[23],
                        arrayproduc[24], arrayproduc[25], arrayproduc[26], arrayproduc[27], arrayproduc[28],
                        arrayproduc[29], arrayproduc[30], arrayproduc[31], arrayproduc[32], arrayproduc[33]);
                }
                $("#txtbusquedaartcodebar").val("");
                $("#txtbusquedaartcodebar").focus()

            })
        }

    }, 200)
    listarArticulos();
})

$(function () {
    $("#codigo_ingresar").on("change", function () {
        var valoractual = $("#codigo_ingresar").val();

        if (valoractual != "") {
            load();
            $.get("../ajax/venta.php?op=buscararticulocodebar&codigo=" + valoractual,
                { op: "buscararticulocodebar", codigo: valoractual },
                function (res) {
                    var arrayproduc = res.split("@");
                    // Verificar si el array está vacío o si el primer elemento es undefined
                    if (arrayproduc[0] === '  ' || arrayproduc[0] === "undefined") {
                        console.log("entra");
                        Swal.close();
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "El código ingresado no existe, favor ingresar uno correcto",
                        }).then((result) => {
                            // Este código se ejecutará después de que el usuario cierre el alert
                            $("#codigo_ingresar").val("");
                            $("#codigo_ingresar").focus();
                        });
                        return;
                    }


                    if (arrayproduc[3] <= 0) {

                        Swal.close();
                        let cod = $("#codigo_ingresar").val();
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: `No se puede agregar un producto con stock 0: ${cod}`,
                        });
                        $("#codigo_ingresar").val("");
                        return;
                    } else {
                        var cantidadIngresada = $("#cantidad_ingreso").val();
                        $("#cantidad_ingreso").val("");

                        //console.log(cantidadIngresada);
                        // Validamos si el resultado es válido
                        if (arrayproduc[0] !== "undefined") {
                            Swal.close();

                            // Asignamos los valores a los inputs correspondientes
                            $("#idarticulo_ingreso").val(arrayproduc[0]);
                            $("#articulo_ingresar").val(arrayproduc[1]);
                            $("#precio_venta_ingreso").val(arrayproduc[2]);
                            //$("#cantidad_ingreso").val("");

                            // Guardamos el precio de venta inicial para usarlo en caso de no modificación
                            var precioVentaInicial = arrayproduc[2];

                            $("#cantidad_ingreso").focus();

                            // Configuramos el evento de pérdida de foco o cambio en precio_venta_ingreso
                            $("#precio_venta_ingreso").off("change").on("change blur", function () {
                                // Verificamos si el precio no fue modificado, y en ese caso, tomamos el precio inicial
                                var precioIngresado = $(this).val();
                                if (precioIngresado === "") {
                                    precioIngresado = precioVentaInicial;  // Si no se modifica, usamos el precio por defecto
                                }

                                if (precioIngresado === "") {
                                    precioIngresado = precioVentaInicial;  // Si no se modifica, usamos el precio por defecto
                                }

                                // Obtenemos el valor de la cantidad (aquí debes asegurarte de que la cantidad esté correctamente capturada)
                                var cantidadIngresada = $("#cantidad_ingreso").val();
                                // console.log("Cantidad Ingresada: ", cantidadIngresada); // Agrega un log para verificar el valor

                                // Ahora enviamos todos los datos a la función agregarDetalle
                                agregarDetalleCodigo(
                                    arrayproduc[0], // idarticulo
                                    arrayproduc[1], // nombre
                                    precioIngresado,    // precio_venta (desde el input o el valor por defecto)
                                    arrayproduc[3], // stock
                                    arrayproduc[4], // descuento
                                    arrayproduc[5], // stock_unidad
                                    arrayproduc[6], // precio_unidad
                                    arrayproduc[7], // stock_blister
                                    arrayproduc[8], // precio_blister
                                    arrayproduc[9], // stock_caja
                                    arrayproduc[10], // precio_caja
                                    arrayproduc[11], // stock_fardo
                                    arrayproduc[12], // precio_fardo
                                    arrayproduc[13], // stock_sacos
                                    arrayproduc[14], // precio_sacos
                                    arrayproduc[15], // stock_paquete
                                    arrayproduc[16], // precio_paquete
                                    arrayproduc[17], // precio_rango1
                                    arrayproduc[18], // precio_rango2
                                    arrayproduc[19], // precio_rango3
                                    arrayproduc[20], // precio_compra
                                    arrayproduc[21],  // precio_activado
                                    cantidadIngresada, // cantidad (desde el input)
                                    valoractual, //codigo
                                    arrayproduc[22],  // precio_rango1_Dos
                                    arrayproduc[23],  // precio_rango2_Dos
                                    arrayproduc[24],  // precio_rango3_Dos
                                    arrayproduc[25],  // precio_rango1_Mecanico
                                    arrayproduc[26],  // precio_rango2_MecanicoDos
                                    arrayproduc[27],  // precio_rango3_MecanicoTres
                                    arrayproduc[28],  // precio_rango1_Distribuidor
                                    arrayproduc[29],  // precio_rango2_DistribuidorDos
                                    arrayproduc[30],  // precio_rango3_DistribuidorTres
                                    arrayproduc[31],  // precio_rango1_Mayorista
                                    arrayproduc[32],  // precio_rango2_MayoristaDos
                                    arrayproduc[33]  // precio_rango3_MayoristaTres
                                );
                                limpiarCampos();

                            });

                        }
                    }
                });

        }

    });



    // Función para limpiar los campos después de agregar
    function limpiarCampos() {
        $("#idarticulo_ingreso").val("");
        $("#articulo_ingresar").val("");
        $("#precio_venta_ingreso").val("");
        $("#cantidad_ingreso").focus();
        $("#codigo_ingresar").val("");
        $("#codigo_ingresar").focus(); // Devolvemos el foco al primer campo
    }



})

function agregarDetalleCodigo(idarticulo, nombre, precio_venta, stock, descuento_porcentaje, stock_unidad, precio_unidad,
    stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos, precio_sacos,
    stock_paquete, precio_paquete, precio_rango1, precio_rango2, precio_rango3, precio_compra,
    precio_activado, cantidad, codigo, precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres) {


    precio_activado = precio_activado.toString().trim();

    var cantidad = cantidad;
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
                '<td><input type="hidden" name="precio_compra[]" value="' + stockinven + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + precio_venta + `" ${precio_activado.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
                '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '</tr>' +
                '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
                '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
                '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
                '</td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val())
            $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3, precio_rango1_Dos,
                precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico, precio_rango2_MecanicoDos, precio_rango3_MecanicoTres,
                precio_rango1_Distribuidor, precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function modificarSubototalesxrango(id, precio_rango1, precio_rango2, precio_rango3,
    precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
    precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres) {
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
    var descuentopermitido = (document.getElementsByName("descuento_permitido[]"));
    var stock = (document.getElementsByName("stockinven[]"));
    var calculo_descuento = $("#calculo_descuento").val();
    var tipo_cliente = $("#tipo_cliente").val();

    var precio_rango1 = parseFloat(precio_rango1);
    var precio_rango2 = parseFloat(precio_rango2);
    var precio_rango3 = parseFloat(precio_rango3);
    var precio_rango1_Dos = parseFloat(precio_rango1_Dos);
    var precio_rango2_Dos = parseFloat(precio_rango2_Dos);
    var precio_rango3_Dos = parseFloat(precio_rango3_Dos);
    var precio_rango1_Mecanico = parseFloat(precio_rango1_Mecanico);
    var precio_rango2_MecanicoDos = parseFloat(precio_rango2_MecanicoDos);
    var precio_rango3_MecanicoTres = parseFloat(precio_rango3_MecanicoTres);
    var precio_rango1_Distribuidor = parseFloat(precio_rango1_Distribuidor);
    var precio_rango2_DistribuidorDos = parseFloat(precio_rango2_DistribuidorDos);
    var precio_rango3_DistribuidorTres = parseFloat(precio_rango3_DistribuidorTres);
    var precio_rango1_Mayorista = parseFloat(precio_rango1_Mayorista);
    var precio_rango2_MayoristaDos = parseFloat(precio_rango2_MayoristaDos);
    var precio_rango3_MayoristaTres = parseFloat(precio_rango3_MayoristaTres);


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
        var inpDescuentopermitido = descuentopermitido[i];
        var inpStock = stock[i];


        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;



        if (parseFloat(inpD.value) > parseFloat(inpDescuentopermitido.value)) {
            Swal.fire({
                title: 'Descuento no permitido',
                text: 'El descuento ingresado supera lo permitido',
                icon: 'error',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            }).then(() => {
                inpD.value = 0; // Resetear valor
                modificarSubototales(); // Recalcular totales
            });
            inpD.value = 0; // Asegurar reset inmediato visualmente

            return; // Detenemos la ejecución si el descuento es inválido
        }

        if (inpPresen.value === 'UNIDAD') {
            // Ajustamos el rango para utilizar condiciones AND (`&&`) en lugar de OR (`||`)
            if (parseFloat(inpTpres.value) <= 3) {
                console.log("sin rango");
                document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
            }
            else if (precio_rango1 <= parseFloat(inpTpres.value) <= precio_rango1_Dos) {
                console.log("rango 1");
                if (tipo_cliente == 'DISTRIBUIDOR') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango1_Distribuidor;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango1_Distribuidor;
                }
                else if (tipo_cliente == 'MAYORISTA') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango1_Mayorista;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango1_Mayorista;
                }
                else if (tipo_cliente == 'TALLER') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango1_Mecanico;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango1_Mecanico;
                }
                else if (tipo_cliente == 'PUBLICO') {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }
            }
            else if (precio_rango2 <= parseFloat(inpTpres.value) <= precio_rango2_Dos) {
                console.log("rango 2");

                if (tipo_cliente == 'DISTRIBUIDOR') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango2_DistribuidorDos;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango2_DistribuidorDos;
                }
                else if (tipo_cliente == 'MAYORISTA') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango2_MayoristaDos;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango2_MayoristaDos;
                }
                else if (tipo_cliente == 'TALLER') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango2_MecanicoDos;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango2_MecanicoDos;
                }
                else if (tipo_cliente == 'PUBLICO') {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }
            }
            else if (precio_rango3 <= parseFloat(inpTpres.value) <= precio_rango3_Dos) {
                console.log("rango 3");

                if (tipo_cliente == 'DISTRIBUIDOR') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango3_DistribuidorTres;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango3_DistribuidorTres;
                }
                else if (tipo_cliente == 'MAYORISTA') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango3_MayoristaTres;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango3_MayoristaTres;
                }
                else if (tipo_cliente == 'TALLER') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango3_MecanicoTres;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango3_MecanicoTres;
                }
                else if (tipo_cliente == 'PUBLICO') {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }


            }
        }



        if (calculo_descuento.value === 'QUETZALES') {

            inpS.value = (inpTpres.value * (inpP.value - inpD.value));
            document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
            inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

            inpSdes.value = (inpD.value * inpTpres.value);
            document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
            inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
            //console.log(inpSdes.value);                

        }
        else if (calculo_descuento.value === 'PORCENTAJE') {

            inpS.value = (inpTpres.value * (inpP.value - ((inpP.value * inpD.value) / 100)));
            document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
            inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

            inpSdes.value = (((inpP.value * inpD.value) / 100) * inpTpres.value);
            document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
            inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
            //console.log(inpSdes.value);                
        }




    }

    calcularTotales();
    calcularTotalesdes();
    modificarSubototales();
}

function listarArticulosxcategoria(idCategoria, nombreCategoria = "") {
    // ✅ Resalta el botón activo
    $("#contenedor-categorias button").removeClass("btn-primary").addClass("btn-outline-primary");
    $(`#contenedor-categorias button:contains('${nombreCategoria}')`)
        .removeClass("btn-outline-primary")
        .addClass("btn-primary");

    var idcliente = $("#idcliente").val();
    var desccuento_cliente = $("#descuento_cliente").val();

    if (desccuento_cliente == "0") {

        // ✅ Llamada AJAX al backend
        $.get("../ajax/venta.php?op=listarActivosVentacategoria22", { idcategoria: idCategoria }, function (data) {
            try {
                // 🔍 Campo de búsqueda
                $("#contenedor-buscador").html(`
                    <input type='text' class='form-control search-input' onkeyup='busquedaArticulo(this)' placeholder='Buscar artículo...'>
                `);

                let html = ``;
                const json = JSON.parse(data);

                json.forEach(element => {
                    html += `
                    <div class="articulo-card">
                        <div class="card custom-card">
                        <div class="card-body text-center">
                            <h5 class="card-title" title="${element[1]}">${element[1]}</h5>
                            <p class="card-text">${element[2]}</p>
                            ${element[3]}
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            ${element[4]} ${element[0]} ${element[5]} <br> ${element[6]}
                        </div>
                        </div>
                    </div>
                    `;
                });

                $("#listadoregistros2").html(html);
            } catch (e) {
                console.error("Error al procesar los datos:", e);
                $("#listadoregistros2").html("<div class='col-12 text-danger'>Error al cargar los artículos.</div>");
            }
        });


    } else if (desccuento_cliente > 0) {


        // ✅ Llamada AJAX al backend
        $.get("../ajax/venta.php?op=listarActivosVentacategoria22_descuento", { idcategoria: idCategoria, idcliente: idcliente }, function (data) {
            try {
                // 🔍 Campo de búsqueda
                $("#contenedor-buscador").html(`
                        <input type='text' class='form-control search-input' onkeyup='busquedaArticulo(this)' placeholder='Buscar artículo...'>
                    `);

                let html = ``;
                const json = JSON.parse(data);

                json.forEach(element => {
                    html += `
                        <div class="articulo-card">
                            <div class="card custom-card">
                            <div class="card-body text-center">
                                <h5 class="card-title" title="${element[1]}">${element[1]}</h5>
                                <p class="card-text">${element[2]}</p>
                                ${element[3]}
                            </div>
                            <div class="card-footer bg-transparent border-0 text-center">
                                ${element[4]} ${element[0]} ${element[5]} <br> ${element[6]}
                            </div>
                            </div>
                        </div>
                        `;
                });

                $("#listadoregistros2").html(html);
            } catch (e) {
                console.error("Error al procesar los datos:", e);
                $("#listadoregistros2").html("<div class='col-12 text-danger'>Error al cargar los artículos.</div>");
            }
        });
    }



}


function abrirModalArticulo(idarticulo) {
    // Opcional: hacer una llamada AJAX para traer más info del artículo
    console.log("ID del artículo:", idarticulo);

    // Llenar un input hidden para enviar después
    $("#idArticuloModal").val(idarticulo);

    // Abrir el modal
    $("#modalArticulo").modal("show");

    tabla = $('#tbllistadoxsucursalxarticulo').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/venta.php?op=listarArticulosVentaCantidadVenta2',
                data: { idarticulo: idarticulo },
                type: "get",
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


function busquedaArticulo(input) {
    // Texto ingresado por el usuario
    let filtro = input.value.toLowerCase();

    // Obtener todos los artículos dentro de #listadoregistros2
    let articulos = document.querySelectorAll("#listadoregistros2 .articulo-card");

    articulos.forEach(card => {
        // Texto completo del artículo (nombre, precio, descripción, etc.)
        let texto = card.innerText.toLowerCase();

        // Mostrar u ocultar según el filtro
        if (texto.includes(filtro)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
}

function mostrarextras(cont, idarticulo) {
    const extrasContainer = $('#extras-container-' + cont);
    const extrasRow = $('#extras-row-' + cont);

    if (extrasRow.is(':visible')) {
        extrasRow.hide();
        return;
    }

    $.post("../ajax/venta_rapida.php?op=listarArticulosExtras", { idarticulo: idarticulo }, function (response) {
        console.log("data ", response);
        try {

            const data = typeof response === 'string' ? JSON.parse(response) : response;
            const articulos = data.aaData || [];

            const extras = articulos.filter(item => item[4] === 'Extra');
            const toppings = articulos.filter(item => item[4] === 'Topping');

            // --- Estructura HTML (sin cambios aquí) ---
            const html = `
                <div class="row p-2" style="background-color: #f8f9fa; margin: 0;">
                    <div class="col-md-6">
                        <h5>Extras</h5>
                        <div class="row extras-column" data-tipo="Extra" data-cont="${cont}">
                            ${extras.map(articulo => `
                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input extras-checkbox" type="checkbox"
                                            id="art-${cont}-${articulo[1]}"  name="check_extras[]" value="${articulo[1]}"
                                            data-idarticulo-extra="${articulo[1]}"
                                            data-cantidad-extra="${articulo[3]}"
                                            data-idproducto-extra="${articulo[2]}"
                                            data-precio="${articulo[5]}"
                                            data-tipo-item="Extra"
                                            data-cont="${cont}">
                                        <label class="form-check-label" for="art-${cont}-${articulo[1]}">
                                            ${articulo[0]} || Q.${articulo[5]}
                                        </label>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        <div><strong>Subtotal Extras: Q.<span class="subtotal-extra" data-cont="${cont}">0.00</span></strong></div>
                    </div>

                    <div class="col-md-6">
                        <h5>Toppings</h5>
                        <div class="row toppings-column" data-tipo="Topping" data-cont="${cont}">
                            ${toppings.map(articulo => `
                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input extras-checkbox" type="checkbox"
                                            id="art-${cont}-${articulo[1]}"
                                            name="check_extras[]"
                                            value="${articulo[1]}"
                                            data-idarticulo-extra="${articulo[1]}"
                                            data-cantidad-extra="${articulo[3]}"
                                            data-idproducto-extra="${articulo[2]}"
                                            data-precio="${articulo[5]}"
                                            data-tipo-item="Topping"
                                            data-cont="${cont}">
                                        <label class="form-check-label" for="art-${cont}-${articulo[1]}">
                                            ${articulo[0]}
                                        </label>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
            // --- Fin Estructura HTML ---

            extrasContainer.html(html);
            extrasRow.show();

            // *** NUEVO: Manejador de eventos para los checkboxes de extras/toppings ***
            $(`#extras-container-${cont} .extras-checkbox`).on('change', function () {
                // Llama a la nueva función para actualizar los totales.
                actualizarSubtotalExtrasYTotal(cont);
            });
            // *************************************************************************

        } catch (error) {
            console.error('Error al procesar la respuesta:', error);
            console.log('Respuesta original:', response);
            extrasContainer.html('<div class="alert alert-danger">Error al cargar los artículos</div>');
            extrasRow.show();
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la petición AJAX:', textStatus, errorThrown);
        extrasContainer.html('<div class="alert alert-danger">Error al cargar los artículos</div>');
        extrasRow.show();
    });
}

function actualizarSubtotalExtrasYTotal(cont) {
    let subtotalExtras = 0.0;
    $(`#extras-container-${cont} .extras-checkbox:checked[data-tipo-item="Extra"]`).each(function () {
        const precio = parseFloat($(this).data('precio'));
        const cantidadextra = parseFloat($(this).data('cantidad-extra'));
        if (!isNaN(precio)) {
            let subtotal = cantidadextra * precio;
            subtotalExtras += subtotal;
        }
    });

    $(`.subtotal-extra[data-cont="${cont}"]`).text(subtotalExtras.toFixed(2));

    calcularTotales();
}

function mostrarextras_Extras(cont, idarticulo, idcotizacion) {
    console.log("mostrar extras cotizacion");
    const extrasContainer = $('#extras-container-' + cont);
    const extrasRow = $('#extras-row-' + cont);

    // 1. Ocultar si ya está visible
    if (extrasRow.is(':visible')) {
        extrasRow.hide();
        return;
    }

    // Limpiamos el contenedor mientras carga
    extrasContainer.html('<div class="text-center p-3">Cargando artículos...</div>');
    extrasRow.show(); // Mostramos el row para que se vea el mensaje de carga

    $.ajax({
        url: "../ajax/venta_rapida.php?op=listarArticulosExtrasYSeleccionados",
        type: "POST",
        data: {
            idarticulo: idarticulo,
            idcotizacion: idcotizacion
        },
        dataType: "json",

        success: function (data) {
            try {
                if (!data || !data.articulosDisponibles || !data.articulosDisponibles.aaData || !Array.isArray(data.extrasSeleccionados)) {
                    throw new Error("Estructura de datos JSON no válida o incompleta.");
                }

                const articulos = data.articulosDisponibles.aaData;
                const idsSeleccionados = data.extrasSeleccionados;

                const extras = articulos.filter(item => item[4] === 'Extra');
                const toppings = articulos.filter(item => item[4] === 'Topping');

                const isChecked = (articulo) => idsSeleccionados.includes(articulo[1]);

                let subtotalInicial = 0.00;

                const html = `
                    <div class="row p-2" style="background-color: #f8f9fa; margin: 0;">
                        <div class="col-md-6">
                            <h5>Extras</h5>
                            <div class="row extras-column" data-tipo="Extra" data-cont="${cont}">
                                ${extras.map(articulo => {
                    const checkedAttr = isChecked(articulo) ? 'checked' : '';
                    if (checkedAttr && articulo[4] === 'Extra') {
                        subtotalInicial += (parseFloat(articulo[5]) * parseFloat(articulo[3])) || 0;
                    }
                    return `
                                        <div class="col-md-12 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input extras-checkbox" type="checkbox"
                                                    id="art-${cont}-${articulo[1]}"
                                                    name="check_extras[]"
                                                    value="${articulo[1]}"
                                                    data-idarticulo-extra="${articulo[1]}"
                                                    data-cantidad-extra="${articulo[3]}"
                                                    data-idproducto-extra="${articulo[2]}"
                                                    data-precio="${articulo[5]}"
                                                    data-tipo-item="Extra"
                                                    data-cont="${cont}"
                                                    ${checkedAttr}>
                                                <label class="form-check-label" for="art-${cont}-${articulo[1]}">
                                                    ${articulo[0]} || Q.${articulo[5]}
                                                </label>
                                            </div>
                                        </div>
                                    `;
                }).join('')}
                            </div>
                            <div><strong>Subtotal Extras: Q.<span class="subtotal-extra" data-cont="${cont}">${subtotalInicial.toFixed(2)}</span></strong></div>
                        </div>

                        <div class="col-md-6">
                            <h5>Toppings</h5>
                            <div class="row toppings-column" data-tipo="Topping" data-cont="${cont}">
                                ${toppings.map(articulo => `
                                    <div class="col-md-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input extras-checkbox" type="checkbox"
                                                id="art-${cont}-${articulo[1]}"
                                                name="check_extras[]"
                                                value="${articulo[1]}"
                                                data-idarticulo-extra="${articulo[1]}"
                                                data-cantidad-extra="${articulo[3]}"
                                                data-idproducto-extra="${articulo[2]}"
                                                data-precio="${articulo[5]}"
                                                data-tipo-item="Topping"
                                                data-cont="${cont}"
                                                ${isChecked(articulo) ? 'checked' : ''}>
                                            <label class="form-check-label" for="art-${cont}-${articulo[1]}">
                                                ${articulo[0]} || Q.${articulo[5]}
                                            </label>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;

                extrasContainer.html(html);

                calcularTotales();

                $(`#extras-container-${cont} .extras-checkbox`).on('change', function () {
                    actualizarSubtotalExtrasYTotal(cont);
                });

            } catch (error) {
                console.error('Error al procesar la respuesta o estructura inválida:', error);
                console.log('Respuesta cruda:', data);
                extrasContainer.html(`<div class="alert alert-danger">Error al construir el HTML: ${error.message}</div>`);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error en la petición AJAX:', textStatus, errorThrown);
            extrasContainer.html('<div class="alert alert-danger">Error de red al cargar los artículos. Revise la consola para más detalles.</div>');
            extrasRow.show();
        }
    });
}
