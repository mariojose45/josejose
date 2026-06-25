var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });

    $("#btnGuardar2").click(function (e) {
        guardaryeditar2(e);
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


    $("#idorden_compra").val("");
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



}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        listarArticulos();

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
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax":
            {
                url: '../ajax/ordenes_compra.php?op=listar_ingreso_orden_compra',
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
                url: '../ajax/ordenes_compra.php?op=listarArticulos',
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
//Función para guardar o editar

function agruparDatos() {
    const datos = {
        articulos: {
            idarticulo: [],
            stockinven: [],
            fechavencimiento: [],
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
            descripcion_detalle: [],
            iddetalle_orden_compra: []
        }
    };

    $('#detalles .filas').each(function () {
        const idarticulo = $(this).find('input[name="idarticulo[]"]').val();
        const stockinven = $(this).find('input[name="stockinven[]"]').val();
        const fechavencimiento = $(this).find('input[name="fechavencimiento[]"]').val();
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
        const descripcion_detalle = $(this).find('input[name="descripcion_detalle[]"]').val();
        const iddetalle_orden_compra = $(this).find('input[name="iddetalle_orden_compra[]"]').val();


        datos.articulos.idarticulo.push(idarticulo);
        datos.articulos.stockinven.push(stockinven);
        datos.articulos.fechavencimiento.push(fechavencimiento);
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
        datos.articulos.descripcion_detalle.push(descripcion_detalle);
        datos.articulos.iddetalle_orden_compra.push(iddetalle_orden_compra);
    });

    const datosJSON = JSON.stringify(datos);

    localStorage.setItem('datosArticulosC', datosJSON);
    //console.log(datosJSON);
}


function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    let estado_orden_compra = $("#estado_orden_compra").val();
    if (estado_orden_compra == "NA") {
        Swal.fire({
            title: 'Atención!',
            text: "Seleccione un estado válido",
            icon: 'warning',
        });
        return;
    }
    agruparDatos();
    var formData = new FormData($("#formulario")[0]);
    let datosArticulosC = JSON.parse(localStorage.getItem('datosArticulosC'));
    formData.append("datosArticulosC", JSON.stringify(datosArticulosC));
    load();
    $.ajax({
        url: "../ajax/ordenes_compra.php?op=guardaryeditar_ingreso_orden_compra",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {

            // console.log(datos)

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

/*
function agruparDatos() {
    const datos = {
        stockinven: [],
        idarticulo: [],
        fechavencimiento: [],
        cantidad: [],
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
        cantidadpresentacion: [],
        totalcantidadpresentacion: [],
        presentacion:[]
    };
    $('#detalles .filas').each(function () {
        datos.stockinven.push($(this).find('input[name="stockinven[]"]').val());
        datos.idarticulo.push($(this).find('input[name="idarticulo[]"]').val());
        datos.fechavencimiento.push($(this).find('input[name="fechavencimiento[]"]').val());
        datos.cantidad.push($(this).find('input[name="cantidad[]"]').val());
        datos.precio_compra.push($(this).find('input[name="precio_compra[]"]').val());
        datos.descuento_porcentaje.push($(this).find('input[name="descuento_porcentaje[]"]').val());
        datos.precio_venta.push($(this).find('input[name="precio_venta[]"]').val());

        datos.precio_ventaNocturno.push($(this).find('input[name="precio_ventaNocturno[]"]').val());
        datos.precio_rango1.push($(this).find('input[name="precio_rango1[]"]').val());
        datos.precio_rango2.push($(this).find('input[name="precio_rango2[]"]').val());
        datos.precio_rango3.push($(this).find('input[name="precio_rango3[]"]').val());
        datos.precio_unidad.push($(this).find('input[name="precio_unidad[]"]').val());
        datos.precio_blister.push($(this).find('input[name="precio_blister[]"]').val());
        datos.precio_caja.push($(this).find('input[name="precio_caja[]"]').val());
        datos.precio_fardo.push($(this).find('input[name="precio_fardo[]"]').val());
        datos.precio_sacos.push($(this).find('input[name="precio_sacos[]"]').val());
        datos.precio_paquete.push($(this).find('input[name="precio_paquete[]"]').val());
        datos.cantidadpresentacion.push($(this).find('input[name="cantidadpresentacion[]"]').val());
        datos.totalcantidadpresentacion.push($(this).find('input[name="totalcantidadpresentacion[]"]').val());
        datos.presentacion.push($(this).find('select[name="presentacion[]"]').val()); 

    });
    const datosJSON = JSON.stringify(datos);
    $("#datos1").val(datosJSON);
}
*/

function mostrar(idorden_compra) {
    $.post("../ajax/sucursal.php?op=obtenerClaveOrdenes", function (data, status) {
        data = JSON.parse(data);
        let clave_ordenes_ajax = data.clave_ordenes;

        Swal.fire({
            title: 'Ingresa la contraseña de Autorización',
            input: 'password', // Tipo de campo 'password' para ocultar la entrada
            inputAttributes: {
                autocapitalize: 'off',
                autocomplete: 'off', // Desactiva el autocompletado
                placeholder: 'Contraseña',
                maxlength: 100,
                required: true
            },
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                if (password !== clave_ordenes_ajax) { // Validar contraseña
                    Swal.showValidationMessage('Contraseña no válida');
                    return false;
                }
                return password;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Continuar con la petición AJAX
                $.post("../ajax/ordenes_compra.php?op=mostrar", { idorden_compra: idorden_compra }, function (response, status) {
                    console.log("Respuesta del servidor:", response); // Verifica qué está devolviendo el servidor

                    try {
                        // Parsear la respuesta JSON
                        let data = JSON.parse(response);

                        // Verificar si hay errores en la respuesta
                        if (data.status === false) {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'No se pudo procesar la solicitud.',
                                icon: 'error',
                                timer: 5000,
                                timerProgressBar: true
                            });
                            return; // Detener la ejecución si hay un error
                        }

                        //console.log("data ", data);
                        if (data.estado_compra == "Ingreso de Compra") {
                            Swal.fire({
                                title: 'ATENCIÓN!',
                                text: "Orden de compra no se puede editar porque ya fue validada",
                                icon: 'info',
                            });
                            return;
                        }
                        // Continuar con el llenado de datos (si la respuesta no tiene un campo `status`, trabaja directamente con `data`)
                        mostrarform(true);

                        $("#idorden_compra").val(data.idorden_compra);
                        $("#codigo_cliente").val(data.codigo_cliente);
                        $("#nit").val(data.nit);
                        $("#nombre_cliente").val(data.nombre_cliente);
                        $("#telefono_cliente").val(data.telefono_cliente);
                        $("#direccion_cliente").val(data.direccion_cliente);
                        $("#correo_cliente").val(data.correo_cliente);
                        $("#idcliente").val(data.idcliente);

                        $("#tipo_documento_cliente").val(data.tipo_documento_cliente);
                        $("#tipo_documento_cliente").selectpicker("refresh");

                        $("#tipo_ingreso_producion").val(data.tipo_ingreso || ''); // Manejar posibles valores faltantes
                        $("#tipo_ingreso_producion").selectpicker("refresh");

                        $("#fecha_hora").val(data.fecha);

                        $("#tipo_comprobante").val(data.tipo_comprobante);
                        $("#tipo_comprobante").selectpicker("refresh");

                        $("#serie_comprobante").val(data.serie_comprobante);
                        $("#num_comprobante").val(data.num_comprobante);

                        $("#impuesto").val(data.impuesto);

                        $("#total_compra").val(data.total_compra);
                        $("#total_comprades").val(data.total_comprades);

                        $("#total_compra_r").val(data.total_compra);
                        $("#total_comprades_r").val(data.total_comprades);

                        $("#forma_pago").val(data.forma_pago);
                        $("#forma_pago").selectpicker("refresh");

                        $("#dias_credito").val(data.dias_credito);
                        $("#fecha_hora_pago_credito").val(data.fechahorapagocredito || ''); // Manejar valores nulos
                        $("#direccion_entrega_orden_compra").val(data.direccion_entrega_orden_compra);

                        $("#fecha_entrega_orden_compra").val(data.fechaentregaordencompra || ''); // Manejar valores nulos
                        $("#observacion_orden_compra").val(data.observacion_orden_compra);

                        // Llamar a obtenerdetalleingreso para cargar detalles adicionales
                        obtenerdetalleingreso(idorden_compra);
                    } catch (e) {
                        console.error("Error al procesar los datos:", e);

                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al procesar la respuesta del servidor.',
                            icon: 'error'
                        });
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Manejar errores de la petición AJAX
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo completar la solicitud. Por favor, inténtalo de nuevo.',
                        icon: 'error'
                    });
                });
            }
        });
    });
}



/*
function mostrar(idorden_compra) 
{
    $.post("../ajax/ordenes_compra.php?op=mostrar", { idorden_compra: idorden_compra }, function (data, status) {
         
        
        // Parseamos la data
        data = JSON.parse(data); 
    
                    mostrarform(true);

                    $("#idorden_compra").val(data.idorden_compra);
                    $("#codigo_cliente").val(data.codigo_cliente);
                    $("#nit").val(data.nit);
                    $("#nombre_cliente").val(data.nombre_cliente);
                    $("#telefono_cliente").val(data.telefono_cliente);
                    $("#direccion_cliente").val(data.direccion_cliente);
                    $("#correo_cliente").val(data.correo_cliente);
                    $("#idcliente").val(data.idcliente);

                    $("#tipo_documento_cliente").val(data.tipo_documento_cliente);
                    $("#tipo_documento_cliente").selectpicker("refresh");

                    $("#tipo_ingreso_producion").val(data.tipo_ingreso || ''); // Manejar posibles valores faltantes
                    $("#tipo_ingreso_producion").selectpicker("refresh");

                    $("#fecha_hora").val(data.fecha);

                    $("#tipo_comprobante").val(data.tipo_comprobante);
                    $("#tipo_comprobante").selectpicker("refresh");

                    $("#serie_comprobante").val(data.serie_comprobante);
                    $("#num_comprobante").val(data.num_comprobante);

                    $("#impuesto").val(data.impuesto);

                    $("#total_compra").val(data.total_compra);
                    $("#total_comprades").val(data.total_comprades);

                    $("#forma_pago").val(data.forma_pago);
                    $("#forma_pago").selectpicker("refresh");

                    $("#dias_credito").val(data.dias_credito);
                    $("#fecha_hora_pago_credito").val(data.fechahorapagocredito || ''); // Manejar valores nulos
                    $("#direccion_entrega_orden_compra").val(data.direccion_entrega_orden_compra);

                    $("#fecha_entrega_orden_compra").val(data.fechaentregaordencompra || ''); // Manejar valores nulos
                    $("#observacion_orden_compra").val(data.observacion_orden_compra);

                    // Llamar a obtenerdetalleingreso para cargar detalles adicionales
                    obtenerdetalleingreso(idorden_compra);
    });
}
*/

// Función para anular registros
function anular(idorden_compra) {
    bootbox.confirm("¿Está seguro de anular el ingreso?", function (result) {
        if (result) {
            load(); // Mostrar un indicador de carga (si está implementado)
            $.post("../ajax/ordenes_compra.php?op=anular", { idorden_compra: idorden_compra }, function (response) {
                try {
                    // Parsear la respuesta como JSON
                    const data = JSON.parse(response);

                    // Mostrar un mensaje según el estado
                    if (data.status) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                Swal.close();
                                // Actualizar la tabla o recargar la página si es necesario
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta del servidor:", error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al procesar la solicitud.',
                        icon: 'error'
                    });
                }
            });
        }
    });
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
    //for (var i = 0; i < 50; i++) {
    var cantidad = 1;
    var subtotaldes = 0;
    // Obtén la fecha actual en formato YYYY-MM-DD
    var today = new Date();
    var fechaActual = today.toISOString().split('T')[0];

    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="stockinven[]" value="' + stock + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input  class="form-control"  onchange="modificarSubototales()"  type="date"   name="fechavencimiento[]" id="fechavencimiento' + cont + '"  style="width:100px" value="' + fechaActual + '"></td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input  class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '" style="width:100px"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                    <select class="form-control" style="width:100px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:75px" step="any" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td><input class="form-control"  onchange="modificarSubototales()" type="number" step="any" style="width:50px"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" step="any" name="precio_venta[]" style="width:75px" value="' + precio_venta + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_ventaNocturno[]" style="width:75px"  value="' + precio_ventaNocturno + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1[]" style="width:75px" value="' + precio_rango1 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2[]" style="width:75px" value="' + precio_rango2 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3[]" style="width:75px" value="' + precio_rango3 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_unidad[]" style="width:75px" value="' + precio_unidad + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_blister[]" style="width:75px" value="' + precio_blister + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_caja[]" style="width:75px" value="' + precio_caja + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_fardo[]" style="width:75px" value="' + precio_fardo + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_sacos[]" style="width:75px" value="' + precio_sacos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_paquete[]" style="width:75px" value="' + precio_paquete + '"></td>' +
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
    //}

}

function presentacionoculatardatos(id, precio_venta, stock_unidad, precio_unidad, stock_blister, precio_blister,
    stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos,
    precio_sacos, stock_paquete, precio_paquete) {
    var presentacion = $("#presentacionselect" + id).val();
    var cantidad = parseFloat($("#cantidad" + id).val());
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
        var precioventaunidad = 0;
        if (stock_caja > 0) {
            precioventaunidad = (precio_caja / stock_caja); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_caja);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_caja);
        $("#presen" + id).val("CAJA");

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_caja);
    }
    else if (presentacion == 'FARDO') {
        var precioventaunidad = 0;
        if (stock_fardo > 0) {
            precioventaunidad = (precio_fardo / stock_fardo); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_fardo);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_fardo);
        $("#presen" + id).val("FARDO");
    }
    else if (presentacion == 'SACOS') {
        var precioventaunidad = 0;
        if (stock_sacos > 0) {
            precioventaunidad = (precio_sacos / stock_sacos); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_sacos);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_sacos);
        $("#presen" + id).val("SACOS");
    }
    else if (presentacion == 'PAQUETE') {
        var precioventaunidad = 0;
        if (stock_paquete > 0) {
            precioventaunidad = (precio_paquete / stock_paquete); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_paquete);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_paquete);
        $("#presen" + id).val("PAQUETE");
    }
    else {

        $("#cantidadpresentacion" + id).val(1);
        $("#precio_venta" + id).val(precio_venta);
        $("#q_ref" + id).val(precio_venta);
        $("#presen" + id).val("UNIDAD");

    }
    modificarSubototales();
}
function obtenerdetalleingreso(idorden_compra) {
    $.post("../ajax/ordenes_compra.php?op=detalleingreso", { idorden_compra: idorden_compra }, function (data) {
        //console.log(data);
        data = JSON.parse(data);
        Swal.close()
        $.each(data, function (i, item) {
            agregarDetalle2(item.idarticulo, item.articulo, item.precio_venta, item.precio_compra, item.stock, item.cantidad, item.descuento_porcentaje,
                item.precio_ventaNocturno, item.precio_rango1, item.precio_rango2, item.precio_rango3, item.precio_unidad, item.precio_blister,
                item.precio_caja, item.precio_fardo, item.precio_sacos, item.precio_paquete,
                item.stock_unidad, item.stock_blister, item.stock_caja, item.stock_fardo, item.stock_sacos, item.stock_paquete,
                item.cantidadpresentacion, item.totalcantidadpresentacion, item.presentacion, item.fechavencimiento, item.iddetalle_orden_compra);
        });
    })
}


function agregarDetalle2(idarticulo, articulo, precio_venta, precio_compra, stock, cantidad, descuento_porcentaje, precio_ventaNocturno,
    precio_rango1, precio_rango2, precio_rango3, precio_unidad, precio_blister, precio_caja, precio_fardo, precio_sacos, precio_paquete,
    stock_unidad, stock_blister, stock_caja, stock_fardo, stock_sacos, stock_paquete,
    cantidadpresentacion, totalcantidadpresentacion, presentacion, fechavencimiento, iddetalle_orden_compra) {

    var subtotaldes = 0;

    //console.log("presentacion ", presentacion);
    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger">X</button></td>' +
            '<td><input type="hidden" name="iddetalle_orden_compra[]" value="' + iddetalle_orden_compra + '"><input type="hidden" name="stockinven[]" value="' + stock + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +

            '<td><input  class="form-control"  onchange="modificarSubototales()"  type="date"   name="fechavencimiento[]" id="fechavencimiento' + cont + '"  style="width:150px" value="' + fechavencimiento + '" ></td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '" readonly><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                <select class="form-control" style="width:100px" name="presentacion[]" id="presentacionselect` + cont + `"  readonly
                onchange="presentacionoculatardatos(` + cont + `,` + precio_venta + `,` + stock_unidad + `,` + precio_unidad + `,` + stock_blister + `,` + precio_blister + `,
                                                    ` + stock_caja + `,` + precio_caja + `,` + stock_fardo + `,` + precio_fardo + `,` + stock_sacos + `,` + precio_sacos + `,` + stock_paquete + `,` + precio_paquete + `)" >
                    `+ (parseInt(stock_unidad) > 0 ? `<option value="UNIDAD" ` + (presentacion === 'UNIDAD' ? 'selected' : '') + `>P.U</option>` : ``) + ` 
                    `+ (parseInt(stock_blister) > 0 ? `<option value="BLISTER" ` + (presentacion === 'BLISTER' ? 'selected' : '') + `>P.BLI</option>` : ``) + ` 
                    `+ (parseInt(stock_caja) > 0 ? `<option value="CAJA" ` + (presentacion === 'CAJA' ? 'selected' : '') + `>P.CAJA</option>` : ``) + ` 
                    `+ (parseInt(stock_fardo) > 0 ? `<option value="FARDO" ` + (presentacion === 'FARDO' ? 'selected' : '') + `>P.FARDO</option>` : ``) + ` 
                    `+ (parseInt(stock_sacos) > 0 ? `<option value="SACOS" ` + (presentacion === 'SACOS' ? 'selected' : '') + `>P.SACOS</option>` : ``) + ` 
                    `+ (parseInt(stock_paquete) > 0 ? `<option value="PAQUETE" ` + (presentacion === 'PAQUETE' ? 'selected' : '') + `>P.PAQUETE</option>` : ``) + ` 
                </select>
            </td>` +
            '<td><input style="width:100px" class="form-control"  type="text"  name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
            '<td style="display:none"><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:75px" step="any" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td style="display:none"><input class="form-control"  onchange="modificarSubototales()" type="number" style="width:50px" step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="' + descuento_porcentaje + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" onchange="modificarSubototales()" step="any" name="precio_venta[]" style="width:75px" value="' + precio_venta + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_ventaNocturno[]" style="width:75px"  value="' + precio_ventaNocturno + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_rango1[]" style="width:75px" value="' + precio_rango1 + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_rango2[]" style="width:75px" value="' + precio_rango2 + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_rango3[]" style="width:75px" value="' + precio_rango3 + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_unidad[]" style="width:75px" value="' + precio_unidad + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_blister[]" style="width:75px" value="' + precio_blister + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_caja[]" style="width:75px" value="' + precio_caja + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_fardo[]" style="width:75px" value="' + precio_fardo + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_sacos[]" style="width:75px" value="' + precio_sacos + '"></td>' +
            '<td style="display:none"><input class="form-control"  type="number" step="any" name="precio_paquete[]" style="width:75px" value="' + precio_paquete + '"></td>' +
            '<td style="display:none"><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td style="display:none"><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td style="display:none"><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
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
    var p_venta = document.getElementsByName("precio_venta[]");
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]"));
    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));
    var p_unidad = (document.getElementsByName("precio_unidad[]"));

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpD = desc[i];
        var inpS = sub[i];
        var inpSdes = subdes[i];
        var inpTpres = tprese[i];
        var inpCpre = cantpre[i];
        var inpCpre_uni = p_unidad[i];
        var inpCpre_venta = p_venta[i];

        inpS.value = (inpTpres.value * (inpP.value - ((inpP.value * inpD.value) / 100)));
        document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(2);

        inpCpre_uni.value = inpCpre_venta.value;
        document.getElementsByName("precio_unidad[]")[i].innerHTML = parseFloat(inpCpre_uni.value).toFixed(2);


        inpSdes.value = ((inpP.value * inpD.value) / 100) * inpC.value;
        document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(2);
        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = parseFloat(inpTpres.value).toFixed(2);
    }
    calcularTotales();
    calcularTotalesdes();

}
function calcularTotales() {
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        total += parseFloat(document.getElementsByName("subtotal")[i].value) || 0; // Asegúrate de manejar valores no numéricos
    }
    total = parseFloat(total.toFixed(2)); // Redondea a 2 decimales
    //$("#total").html("Q/. " + total);
    var total_texto = "SIN PERMISOS";
    $("#total").html("Q/. " + total_texto);
    $("#total_compra").val(total);
    $("#total_compra_r").val(total);
    evaluar();
}


function calcularTotalesdes() {
    var chks = document.getElementsByName('subtotaldes');
    var total = 0.0;

    for (var i = 0; i < chks.length; i++) {
        var valor = parseFloat(chks[i].value) || 0; // Maneja valores no numéricos
        total += valor;
    }
    total = parseFloat(total.toFixed(2)); // Redondea a 2 decimales
    $("#totaldes").html("Q/. " + total);
    $("#total_comprades").val(total);
    $("#total_comprades_r").val(total);
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
            $.get("../ajax/venta.php?op=buscararticulocodebarCompras&codigo=" + valoractual + "", { op: "buscararticulocodebarCompras", codigo: valoractual }, function (res) {

                var arrayproduc = res.split("@");

                if (arrayproduc[0] != "undefined") {
                    agregarDetalle(arrayproduc[0], arrayproduc[1], arrayproduc[2], arrayproduc[3], arrayproduc[4], arrayproduc[5], arrayproduc[6], arrayproduc[7], arrayproduc[8], arrayproduc[9], arrayproduc[10]
                        , arrayproduc[11], arrayproduc[12], arrayproduc[13], arrayproduc[14], arrayproduc[15], arrayproduc[16],
                        arrayproduc[17], arrayproduc[18], arrayproduc[19], arrayproduc[20]);
                }
                $("#txtbusquedaartcodebar").val("");
                $("#txtbusquedaartcodebar").focus()

            })
        }

    }, 200)
    listarArticulos();
})




init();