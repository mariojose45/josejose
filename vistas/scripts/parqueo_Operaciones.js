var tabla;

// Función para actualizar el reloj en vivo
function actualizarReloj() {
    var fecha = new Date();
    var horas = fecha.getHours();
    var minutos = fecha.getMinutes();
    var segundos = fecha.getSeconds();
    var ampm = horas >= 12 ? 'PM' : 'AM';

    // Formato 12 horas
    horas = horas % 12;
    horas = horas ? horas : 12;

    // Añadir ceros a la izquierda
    horas = horas < 10 ? '0' + horas : horas;
    minutos = minutos < 10 ? '0' + minutos : minutos;
    segundos = segundos < 10 ? '0' + segundos : segundos;

    var timeString = '<i class="fa fa-clock-o"></i> ' + horas + ':' + minutos + ':' + segundos + ' ' + ampm;
    if ($("#reloj_en_vivo").length > 0) {
        $("#reloj_en_vivo").html(timeString);
    }
}

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    // listar();
    // listarFac();

    // Iniciar reloj
    actualizarReloj();
    setInterval(actualizarReloj, 1000);

    $("#btnGuardarIngreso").click(function (e) {
        guardarIngreso(e);
    });

    $("#btnGuardarCobro").click(function (e) {
        guardarCobro(e);
    });

}

//Función limpiar
function limpiar() {

}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        $("#btnTour").hide();
    }
    else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnTour").show();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función Listar
function listar() {
    var fecha_inicio_lectura = $("#fecha_inicio_lectura").val();
    var fecha_fin_lectura = $("#fecha_fin_lectura").val();
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
                url: '../ajax/parqueo_Operaciones.php?op=listar',
                data: { fecha_inicio_lectura: fecha_inicio_lectura, fecha_fin_lectura: fecha_fin_lectura },
                type: "get",
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

function listarFac() {
    var fecha_inicio_lectura_fac = $("#fecha_inicio_lectura_fac").val();
    var fecha_fin_lectura_fac = $("#fecha_fin_lectura_fac").val();
    tabla = $('#tbllistadoFac').dataTable(
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
                url: '../ajax/parqueo_Operaciones.php?op=listarFac',
                data: { fecha_inicio_lectura_fac: fecha_inicio_lectura_fac, fecha_fin_lectura_fac: fecha_fin_lectura_fac },
                type: "get",
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

function guardarIngreso(e) {
    e.preventDefault(); // Mover aquí arriba para detener el recargo nativo del form desde el principio

    var tipo_vehiculo = $("#tipo_vehiculo").val();
    var placa = $("#placa").val();
    var fecha_ingreso = $("#fecha_ingreso").val();

    if (!tipo_vehiculo) {
        Swal.fire({
            title: 'Error!',
            text: 'Debe seleccionar un tipo de vehículo',
            icon: 'error',
            timer: 2000
        });
        return;
    }
    if (!placa) {
        Swal.fire({
            title: 'Error!',
            text: 'Debe ingresar una placa',
            icon: 'error',
            timer: 2000
        });
        return;
    }

    $("#btnGuardarIngreso").prop("disabled", true);
    // 👉 Aquí creas FormData SIN formulario
    var formData = new FormData();
    formData.append("tipo_vehiculo", tipo_vehiculo);
    formData.append("placa", placa);
    formData.append("fecha_ingreso", fecha_ingreso);


    load();
    $.ajax({
        url: "../ajax/parqueo_Operaciones.php?op=guardarIngreso",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.close();
            console.log(datos);
            Swal.fire({
                title: '¡Éxito!',
                text: '¿Deseas imprimir el ticket?',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, imprimir',
                cancelButtonText: 'No, solo guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Abrir la impresión en una nueva pestaña
                    window.open("../reportes/exTicket_LecturaParqueo.php?id=" + datos, '_blank');
                }
                // En ambos casos (imprima o no), recargamos solo la tabla de datos
                if (tabla) {
                    tabla.ajax.reload(null, false); // El "false" mantiene la paginación actual
                }
                // Cerramos el modal de ingreso
                $('#modalIngresoVehiculo').modal('hide');
                $("#btnGuardarIngreso").prop("disabled", false);
                // Limpiamos los campos visualmente
                limpiarmodalIngresoVehiculo();

            });

        }

    });
}

function limpiarmodalIngresoVehiculo() {
    $("#tipo_vehiculo").val("");
    $("#placa").val("");
    $("#fecha_ingreso").val("");
}



//Función para desactivar (anular) registros
function desactivar(idlectura) {
    Swal.fire({
        title: '¿Está seguro de anular este ticket?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/parqueo_Operaciones.php?op=desactivar", { idlectura: idlectura }, function (e) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: e, // e contendrá el mensaje del servidor "Lectura Desactivada"
                    showConfirmButton: false,
                    timer: 1500
                });
                tabla.ajax.reload(); // Solo recarga la tabla, y no toda la página
            });
        }
    });
}


function abrirModalIngreso() {
    // 1. Limpiar el formulario completo (vaciamos la placa)
    $("#formulario_ingreso")[0].reset();

    // 2. Limpiar la selección previa de tipo de vehículo poniéndolos todos opacos de nuevo
    $("#tipo_vehiculo").val("");
    $(".btn-tipo-vehiculo").css("border", "none").css("opacity", "0.6");

    let now = new Date();

    let year = now.getFullYear();
    let month = String(now.getMonth() + 1).padStart(2, '0');
    let day = String(now.getDate()).padStart(2, '0');
    let hours = String(now.getHours()).padStart(2, '0');
    let minutes = String(now.getMinutes()).padStart(2, '0');

    let fecha = `${year}-${month}-${day}T${hours}:${minutes}`;

    $("#fecha_ingreso").val(fecha);

    // 4. Mostrar el modal en pantalla
    $('#modalIngresoVehiculo').modal('show');
}

$(document).on('click', '.btn-tipo-vehiculo', function () {

    let tipo = $(this).data('tipo');

    // llenar input
    $('#tipo_vehiculo').val(tipo.toUpperCase());

    // efecto visual (opcional 🔥)
    $('.btn-tipo-vehiculo').removeClass('active');
    $(this).addClass('active');

});

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

function abrirModalCobro() {
    // 1. Limpiar el formulario completo (vaciamos la placa)
    $("#formulario_cobro")[0].reset();


    let now = new Date();

    let year = now.getFullYear();
    let month = String(now.getMonth() + 1).padStart(2, '0');
    let day = String(now.getDate()).padStart(2, '0');
    let hours = String(now.getHours()).padStart(2, '0');
    let minutes = String(now.getMinutes()).padStart(2, '0');

    let fecha = `${year}-${month}-${day}T${hours}:${minutes}`;

    $("#fecha_cobro").val(fecha);

    // 2. Mostrar el modal en pantalla
    $('#modalCobroVehiculo').modal('show');
}



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

                buscarnitenSistemaparaIdcliente(nit);
            } else {
                $("#idcliente").val('0');
                $("#codigo_cliente").val('');
                $("#correo_cliente").val("sincorreo@gmail.com");
                $("#telefono_cliente").val("0");

                let nitValor = nit.toString().trim();
                let largo = nitValor.length;

                if (largo > 15) {
                    $("#tipo_documento_cliente").val("DPI");
                    $("#tipo_documento_cliente").selectpicker('refresh');

                } else if (largo <= 10) {
                    $("#tipo_documento_cliente").val("NIT");
                    $("#tipo_documento_cliente").selectpicker('refresh');
                }
                $("#tipo_cliente").val("PUBLICO");
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val("0");

                Swal.fire({
                    title: 'Mensaje!',
                    text: "Nit No Existe volver a consultar su nit o se creara como cliente nuevo",
                    icon: 'success',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });

                $("#nit").val("CF");
                $("#nit").focus()

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
                let nitValor = nit.toString().trim();
                let largo = nitValor.length;

                if (largo > 15) {
                    $("#tipo_documento_cliente").val("DPI");
                    $("#tipo_documento_cliente").selectpicker('refresh');

                } else if (largo <= 10) {
                    $("#tipo_documento_cliente").val("NIT");
                    $("#tipo_documento_cliente").selectpicker('refresh');
                }
                $("#tipo_cliente").val("PUBLICO");
                $("#tipo_cliente").selectpicker('refresh');
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
                $("#tipo_cliente").val(data.tipo_cliente);
                $("#tipo_cliente").selectpicker('refresh');
                $("#txtbusquedaartcodebar").val("");
                $("#txtbusquedaartcodebar").focus()
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
                $("#tipo_cliente").val("Publico");
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

$("#tip_evento_cobro").change(tip_evento_cobroEventos);
function tip_evento_cobroEventos() {
    var tip_evento_cobros = $("#tip_evento_cobro option:selected").text();
    if (tip_evento_cobros == 'TICKET') {
        $("#div_placaEvento").hide();
        $("#numero_ticket").prop("readonly", false);
        $("#numero_ticket").focus();

    } else {
        $("#div_placaEvento").show();
        $("#numero_ticket").val(""); // Limpiar lo que haya escrito
        $("#numero_ticket").prop("readonly", true); // Bloquear el campo
        $("#numeroplacaEvento").focus();
    }
}



function validarnumero_ticket() {
    limpiarModalCobro();
    var numero_ticket = $("#numero_ticket").val();
    var tip_evento_cobro = $("#tip_evento_cobro option:selected").text();
    if (tip_evento_cobro == "TICKET") {


        $("#div_placaEvento").hide();
        if (numero_ticket == "" || numero_ticket == null || numero_ticket == undefined || numero_ticket == 0) {
            Swal.fire({
                title: 'Mensaje!',
                text: 'Debe Colocar un Numero de Ticket Valido',
                icon: 'warning',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
            return;
        }
        $.post("../ajax/parqueo_Operaciones.php?op=validarnumero_ticket", { numero_ticket: numero_ticket }, function (data, status) {
            data = JSON.parse(data);
            //mostrarform(true);
            console.log(data);

            var idlectura = data.idlectura;
            var tipo_vehiculo = data.tipo_vehiculo;
            var placa = data.placa;
            var fecha_ingreso = data.fecha_ingreso;
            var fecha_creacion = data.fecha_creacion;
            //moto
            var precio_fraccion_moto = data.precio_fraccion_moto;
            var precio_hora_moto = data.precio_hora_moto;
            var tarifa_dia_moto = data.tarifa_dia_moto;
            var tarifa_noche_moto = data.tarifa_noche_moto;
            var tarifa_evento_moto = data.tarifa_evento_moto;
            //carro
            var precio_fraccion_carro = data.precio_fraccion_carro;
            var precio_hora_carro = data.precio_hora_carro;
            var tarifa_dia_carro = data.tarifa_dia_carro;
            var tarifa_noche_carro = data.tarifa_noche_carro;
            var tarifa_evento_carro = data.tarifa_evento_carro;
            //camion
            var precio_fraccion_camion = data.precio_fraccion_camion;
            var precio_hora_camion = data.precio_hora_camion;
            var tarifa_dia_camion = data.tarifa_dia_camion;
            var tarifa_noche_camion = data.tarifa_noche_camion;
            var tarifa_evento_camion = data.tarifa_evento_camion;
            //otros
            var valor_ticket_extraviado = data.valor_ticket_extraviado;
            var tiempo_gracia_ticket = data.tiempo_gracia_ticket;

            $("#idlectura").val(idlectura);
            $("#fecha_ingreso_cobro").val(fecha_ingreso);
            $("#tipo_vehiculo_cobro").val(tipo_vehiculo);
            $("#tiempo_gracia_ticket_cobro").val(tiempo_gracia_ticket);

            // Obtenemos del formulario la fecha actual (que se llenó al abrir el modal)
            var str_fecha_cobro = $("#fecha_cobro").val();

            if (tipo_vehiculo == "MOTO") {
                $("#p_fraccion").val(precio_fraccion_moto);
                $("#p_hora").val(precio_hora_moto);
                $("#tarifa_dia").val(tarifa_dia_moto);
                $("#tarifa_noche").val(tarifa_noche_moto);
                $("#tarifa_evento").val(tarifa_evento_moto);
            } else if (tipo_vehiculo == "CARRO") {
                $("#p_fraccion").val(precio_fraccion_carro);
                $("#p_hora").val(precio_hora_carro);
                $("#tarifa_dia").val(tarifa_dia_carro);
                $("#tarifa_noche").val(tarifa_noche_carro);
                $("#tarifa_evento").val(tarifa_evento_carro);
            } else if (tipo_vehiculo == "CAMION") {
                $("#p_fraccion").val(precio_fraccion_camion);
                $("#p_hora").val(precio_hora_camion);
                $("#tarifa_dia").val(tarifa_dia_camion);
                $("#tarifa_noche").val(tarifa_noche_camion);
                $("#tarifa_evento").val(tarifa_evento_camion);
            }
            //calcular tiempo
            // Convertimos ambas fechas a un objeto Date (reemplazamos espacio por 'T' para evitar errores de navegador)
            var date_ingreso = new Date(fecha_ingreso.replace(" ", "T"));
            var date_cobro = new Date(str_fecha_cobro);

            var diferencia_ms = date_cobro - date_ingreso; // Resultado en milisegundos

            if (diferencia_ms > 0) {
                var minutos_totales = Math.floor(diferencia_ms / 1000 / 60);

                var horas = Math.floor(minutos_totales / 60);
                var minutos_restantes = minutos_totales % 60; // Usar el módulo (%) para obtener los minutos que sobran de las horas

                // Asignamos a los nuevos text box
                $("#tiempo_transcurrido_horas").val(horas);
                $("#tiempo_transcurrido_minutos").val(minutos_restantes);
            } else {
                $("#tiempo_transcurrido_horas").val(0);
                $("#tiempo_transcurrido_minutos").val(0);
            }



            //valor a cobrar
            var valor_a_cobrar_minutos = minutos_restantes - tiempo_gracia_ticket;

            if (minutos_restantes > 0 && minutos_restantes <= 30) {
                if (tipo_vehiculo == "MOTO") {
                    valor_a_cobrar_minutos = precio_fraccion_moto;
                } else if (tipo_vehiculo == "CARRO") {
                    valor_a_cobrar_minutos = precio_fraccion_carro;
                } else if (tipo_vehiculo == "CAMION") {
                    valor_a_cobrar_minutos = precio_fraccion_camion;
                }
            } else if (minutos_restantes > 30 && minutos_restantes < 60) {
                if (tipo_vehiculo == "MOTO") {
                    valor_a_cobrar_minutos = precio_hora_moto;
                } else if (tipo_vehiculo == "CARRO") {
                    valor_a_cobrar_minutos = precio_hora_carro;
                } else if (tipo_vehiculo == "CAMION") {
                    valor_a_cobrar_minutos = precio_hora_camion;
                }
            }

            var valor_a_cobrar_horas = 0;

            if (horas > 0) {
                if (tipo_vehiculo == "MOTO") {
                    valor_a_cobrar_horas = horas * precio_hora_moto;
                } else if (tipo_vehiculo == "CARRO") {
                    valor_a_cobrar_horas = horas * precio_hora_carro;
                } else if (tipo_vehiculo == "CAMION") {
                    valor_a_cobrar_horas = horas * precio_hora_camion;
                }
            }



            var total_a_cobrar = parseFloat(valor_a_cobrar_minutos) + parseFloat(valor_a_cobrar_horas);
            $("#total_venta").val(total_a_cobrar);


        })

    } else {
        $("#div_placaEvento").show();
        $.post("../ajax/parqueo_Operaciones.php?op=tarifaDiaNocheEvento", {}, function (data, status) {
            data = JSON.parse(data);
            //mostrarform(true);
            console.log(data);

            //moto
            var precio_fraccion_moto = data.precio_fraccion_moto;
            var precio_hora_moto = data.precio_hora_moto;
            var tarifa_dia_moto = data.tarifa_dia_moto;
            var tarifa_noche_moto = data.tarifa_noche_moto;
            var tarifa_evento_moto = data.tarifa_evento_moto;
            //carro
            var precio_fraccion_carro = data.precio_fraccion_carro;
            var precio_hora_carro = data.precio_hora_carro;
            var tarifa_dia_carro = data.tarifa_dia_carro;
            var tarifa_noche_carro = data.tarifa_noche_carro;
            var tarifa_evento_carro = data.tarifa_evento_carro;
            //camion
            var precio_fraccion_camion = data.precio_fraccion_camion;
            var precio_hora_camion = data.precio_hora_camion;
            var tarifa_dia_camion = data.tarifa_dia_camion;
            var tarifa_noche_camion = data.tarifa_noche_camion;
            var tarifa_evento_camion = data.tarifa_evento_camion;
            //otros
            var valor_ticket_extraviado = data.valor_ticket_extraviado;
            var tiempo_gracia_ticket = data.tiempo_gracia_ticket;


            var tipo_vehiculoEventos = $("#tipo_vehiculoEventos option:selected").text();
            var numeroplacaEvento = $("#numeroplacaEvento").val();

            $("#tiempo_gracia_ticket_cobro").val(tiempo_gracia_ticket);

            // Obtenemos del formulario la fecha actual (que se llenó al abrir el modal)
            var str_fecha_cobro = $("#fecha_cobro").val();

            if (tipo_vehiculo == "MOTO") {
                $("#p_fraccion").val(precio_fraccion_moto);
                $("#p_hora").val(precio_hora_moto);
                $("#tarifa_dia").val(tarifa_dia_moto);
                $("#tarifa_noche").val(tarifa_noche_moto);
                $("#tarifa_evento").val(tarifa_evento_moto);
            } else if (tipo_vehiculo == "CARRO") {
                $("#p_fraccion").val(precio_fraccion_carro);
                $("#p_hora").val(precio_hora_carro);
                $("#tarifa_dia").val(tarifa_dia_carro);
                $("#tarifa_noche").val(tarifa_noche_carro);
                $("#tarifa_evento").val(tarifa_evento_carro);
            } else if (tipo_vehiculo == "CAMION") {
                $("#p_fraccion").val(precio_fraccion_camion);
                $("#p_hora").val(precio_hora_camion);
                $("#tarifa_dia").val(tarifa_dia_camion);
                $("#tarifa_noche").val(tarifa_noche_camion);
                $("#tarifa_evento").val(tarifa_evento_camion);
            }

            if (tip_evento_cobro == "DIA") {
                $("#numero_ticket").val();
                $("#tiempo_transcurrido_horas").val(12);
                $("#tiempo_transcurrido_minutos").val(0);
                if (tipo_vehiculoEventos == "MOTO") {
                    valor_a_cobrar_horas = tarifa_dia_moto;
                } else if (tipo_vehiculoEventos == "CARRO") {
                    valor_a_cobrar_horas = tarifa_dia_carro;
                } else if (tipo_vehiculoEventos == "CAMION") {
                    valor_a_cobrar_horas = tarifa_dia_camion;
                }

            } else if (tip_evento_cobro == "NOCHE") {
                $("#numero_ticket").val();
                $("#tiempo_transcurrido_horas").val(1);
                $("#tiempo_transcurrido_minutos").val(0);
                if (tipo_vehiculoEventos == "MOTO") {
                    valor_a_cobrar_horas = tarifa_noche_moto;
                } else if (tipo_vehiculoEventos == "CARRO") {
                    valor_a_cobrar_horas = tarifa_noche_carro;
                } else if (tipo_vehiculoEventos == "CAMION") {
                    valor_a_cobrar_horas = tarifa_noche_camion;
                }


            } else if (tip_evento_cobro == "EVENTO") {
                $("#numero_ticket").val();
                $("#tiempo_transcurrido_horas").val(1);
                $("#tiempo_transcurrido_minutos").val(0);
                if (tipo_vehiculoEventos == "MOTO") {
                    valor_a_cobrar_horas = tarifa_evento_moto;
                } else if (tipo_vehiculoEventos == "CARRO") {
                    valor_a_cobrar_horas = tarifa_evento_carro;
                } else if (tipo_vehiculoEventos == "CAMION") {
                    valor_a_cobrar_horas = tarifa_evento_camion;
                }

            }

            var total_a_cobrar = valor_a_cobrar_horas;

            $("#total_venta").val(total_a_cobrar);

        })

    }






}




function limpiarModalCobro() {
    var tip_evento_cobro = $("#tip_evento_cobro option:selected").text();
    if (tip_evento_cobro == "TICKET") {
        $("#idlectura").val("");
        $("#fecha_ingreso_cobro").val("");
        $("#tiempo_gracia_ticket_cobro").val("");
        $("#p_fraccion").val("");
        $("#p_hora").val("");
        $("#tarifa_dia").val("");
        $("#tarifa_noche").val("");
        $("#tarifa_evento").val("");
        $("#tipo_vehiculo_cobro").val("");

        $("#numeroplacaEvento").val("");
        $("#tiempo_transcurrido_horas").val("");
        $("#tiempo_transcurrido_minutos").val("");

        $("#nit").val("CF");
        $("#nombre_cliente").val("CONSUMIDOR FINAL");
        $("#direccion_cliente").val("CONSUMIDOR FINAL");
        $("#tipo_documento_cliente").val("NIT");
        $("#tipo_documento_cliente").selectpicker('refresh');
        $("#total_venta").val("0");
        $("#cefectivo").val("0");
        $("#ctarjeta").val("0");
        $("#ctransferencia").val("0");
        $("#ccredito").val("0");
        $("#cambio").val("0");

    } else {
        $("#idlectura").val("");
        $("#fecha_ingreso_cobro").val("");
        $("#tiempo_gracia_ticket_cobro").val("");
        $("#p_fraccion").val("");
        $("#p_hora").val("");
        $("#tarifa_dia").val("");
        $("#tarifa_noche").val("");
        $("#tarifa_evento").val("");
        $("#tipo_vehiculo_cobro").val("");

        $("#numero_ticket").val("");
        $("#tiempo_transcurrido_horas").val("");
        $("#tiempo_transcurrido_minutos").val("");

        $("#nit").val("CF");
        $("#nombre_cliente").val("CONSUMIDOR FINAL");
        $("#direccion_cliente").val("CONSUMIDOR FINAL");
        $("#tipo_documento_cliente").val("NIT");
        $("#tipo_documento_cliente").selectpicker('refresh');
        $("#total_venta").val("0");
        $("#cefectivo").val("0");
        $("#ctarjeta").val("0");
        $("#ctransferencia").val("0");
        $("#ccredito").val("0");
        $("#cambio").val("0");

    }
}

function calcularefectivo() {
    var total_venta = $("#total_venta").val();
    var cefectivo = $("#cefectivo").val();
    var ctarjeta = $("#ctarjeta").val();
    var ctransferencia = $("#ctransferencia").val();
    var ccredito = $("#ccredito").val();
    var cambio = parseFloat(cefectivo) + parseFloat(ctarjeta) + parseFloat(ctransferencia) + parseFloat(ccredito) - parseFloat(total_venta);
    $("#cambio").html("Q." + cambio.toFixed(2));
    $("#rescambio").val(cambio);

}

function guardarCobro(e) {
    e.preventDefault(); // Mover aquí arriba para detener el recargo nativo del form desde el principio

    var tip_evento_cobro = $("#tip_evento_cobro option:selected").text();
    var numero_ticket = $("#numero_ticket").val();
    var numeroplacaEvento = $("#numeroplacaEvento").val();
    var nit = $("#nit").val();
    var nombre_cliente = $("#nombre_cliente").val();
    var direccion_cliente = $("#direccion_cliente").val();

    var total_venta = $("#total_venta").val();
    var cefectivo = $("#cefectivo").val();
    var ctarjeta = $("#ctarjeta").val();
    var ctransferencia = $("#ctransferencia").val();
    var ccredito = $("#ccredito").val();
    var cambio = parseFloat(cefectivo) + parseFloat(ctarjeta) + parseFloat(ctransferencia) + parseFloat(ccredito) - parseFloat(total_venta);



    if (tip_evento_cobro == "TICKET") {
        if (!numero_ticket) {
            Swal.fire({
                title: 'Error!',
                text: 'Debe ingresar un número de ticket',
                icon: 'error',
                timer: 2000
            });
            return;
        }
    } else {
        if (!numeroplacaEvento) {
            Swal.fire({
                title: 'Error!',
                text: 'Debe ingresar una placa',
                icon: 'error',
                timer: 2000
            });
            return;
        }
    }
    if (!nit || nit.trim() === "" || nit === "0" || nit === 0) {
        Swal.fire({
            title: 'Error!',
            text: 'Debe ingresar un NIT válido',
            icon: 'error',
            timer: 2000
        });
        return;
    }

    if (!nombre_cliente || nombre_cliente.trim() === "" || nombre_cliente === "0" || nombre_cliente === 0) {
        Swal.fire({
            title: 'Error!',
            text: 'Debe ingresar un nombre de cliente válido',
            icon: 'error',
            timer: 2000
        });
        return;
    }

    if (!direccion_cliente || direccion_cliente.trim() === "" || direccion_cliente === "0" || direccion_cliente === 0) {
        Swal.fire({
            title: 'Error!',
            text: 'Debe ingresar una direccion de cliente válido',
            icon: 'error',
            timer: 2000
        });
        return;
    }

    // 🔴 VALIDAR CAMPOS OBLIGATORIOS (efectivo, transferencia, crédito)
    if (
        $("#cefectivo").val().trim() === "" ||
        $("#ctransferencia").val().trim() === "" ||
        $("#ccredito").val().trim() === "" ||
        $("#ctarjeta").val().trim() === ""
    ) {
        Swal.fire({
            title: 'Error!',
            text: 'Efectivo, transferencia, Tarjeta y crédito no pueden ir vacíos',
            icon: 'error',
            timer: 2000
        });
        return;
    }



    var total_pagado =
        (parseFloat(cefectivo) || 0) +
        (parseFloat(ctarjeta) || 0) +
        (parseFloat(ctransferencia) || 0) +
        (parseFloat(ccredito) || 0);

    var total = parseFloat(total_venta) || 0;

    if (total_pagado < total) {
        Swal.fire({
            title: 'Error!',
            text: 'El pago es menor al total de la venta',
            icon: 'error',
            timer: 2000
        });
        return;
    }

    $("#btnGuardarCobro").prop("disabled", true);
    // 👉 Aquí creas FormData SIN formulario
    var formData = new FormData($("#formulario_cobro")[0]);


    load();
    $.ajax({
        url: "../ajax/parqueo_Operaciones.php?op=guardarCobro",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.close();
            console.log(datos);
            Swal.fire({
                title: '¡Éxito!',
                text: '¿Deseas imprimir el su cobro?',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, imprimir',
                cancelButtonText: 'No, solo guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Abrir la impresión en una nueva pestaña
                    window.open("../reportes/exTicket_CobroParqueo.php?id=" + datos, '_blank');
                }
                // En ambos casos (imprima o no), recargamos solo la tabla de datos
                if (tabla) {
                    tabla.ajax.reload(null, false); // El "false" mantiene la paginación actual
                }
                // Cerramos el modal de ingreso
                $('#modalCobroVehiculo').modal('hide');
                $("#btnGuardarCobro").prop("disabled", false);
                // Limpiamos los campos visualmente
                limpiarModalCobrosGuardar();

            });

        }

    });
}

function limpiarModalCobrosGuardar() {
    $("#idlectura").val("");
    $("#fecha_ingreso_cobro").val("");
    $("#tiempo_gracia_ticket_cobro").val("");
    $("#p_fraccion").val("");
    $("#p_hora").val("");
    $("#tarifa_dia").val("");
    $("#tarifa_noche").val("");
    $("#tarifa_evento").val("");
    $("#tipo_vehiculo_cobro").val("");
    $("#numero_ticket").val("");
    $("#tip_evento_cobro").val("");
    $("#numeroplacaEvento").val("");
    $("#tipo_vehiculoEventos").val("");
    $("#tiempo_transcurrido_horas").val("");
    $("#tiempo_transcurrido_minutos").val("");

    $("#nit").val("CF");
    $("#nombre_cliente").val("CONSUMIDOR FINAL");
    $("#direccion_cliente").val("CONSUMIDOR FINAL");
    $("#tipo_documento_cliente").val("NIT");
    $("#tipo_documento_cliente").selectpicker('refresh');
    $("#total_venta").val("0");
    $("#cefectivo").val("0");
    $("#ctarjeta").val("0");
    $("#ctransferencia").val("0");
    $("#ccredito").val("0");
    $("#cambio").val("0");
}

function cancelarOperacion() {
    limpiarModalCobrosGuardar();
    $("#btnGuardarCobro").prop("disabled", false);
    $("#modalCobroVehiculo").modal("hide");
}

function abrirApertura() {
    $("#formularioApertura")[0].reset();
    $("#total_efectivo").val(0);
    $("#modalApertura").modal("show");

}

function abrirCierre() {
    $("#formularioCierre")[0].reset();
    $("#modalCierre").modal("show");

    setTimeout(() => {
        $("#total_efectivocierre").focus();
    }, 500);
}

function cancelarformaperturacaja() {
    $("#formularioApertura")[0].reset();
    $("#modalApertura").modal("hide");
}

function cancelarformcierrecaja() {
    $("#formularioCierre")[0].reset();
    $("#modalCierre").modal("hide");
}

$("#btnGuardarApertura").click(function (e) {
    guardaryeditaraperturacaja(e);
});

function guardaryeditaraperturacaja(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioApertura")[0]);

    $.ajax({
        url: "../ajax/cuadres_caja.php?op=guardaryeditar2",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log("RESPUESTA:", datos);
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

function cancelarformaperturacaja() {
    limpiarapertura();
}

function limpiarapertura() {
    $("#total_efectivo").val("0");
}


function calcularcaja() {
    var res_total_ventas_diarias = $("#total_ventas_diarias").val();
    var res_total_efectivo = $("#total_efectivocierre").val();
    var res_total_ventas_diarias_efectivo = $("#total_ventas_diarias_efectivo").val();
    var res_total_efectivo_inicio = $("#total_efectivo_inicio").val();
    var total_ventas_gastosEfectivo = $("#total_ventas_gastosEfectivo").val();
    var total_ventas_AbonosVentas = $("#total_ventas_AbonosVentas").val();
    var total_ventas_NCVentas = $("#total_ventas_NCVentas").val();

    calculo1 = parseFloat(res_total_efectivo) - ((parseFloat(res_total_efectivo_inicio) +
        parseFloat(res_total_ventas_diarias_efectivo) +
        parseFloat(total_ventas_AbonosVentas)
    ) - (parseFloat(total_ventas_gastosEfectivo) + parseFloat(total_ventas_NCVentas)))

    console.log("calculo1: " + calculo1)
    console.log("total_ventas_gastosEfectivo" + total_ventas_gastosEfectivo)

    calculo2 = (parseFloat(total_ventas_gastosEfectivo) - calculo1)
    $("#total_efectivo_cierre_operaciones").val(calculo1);

}

$("#btnGuardarCierre").click(function (e) {
    guardaryeditarCierre(e);
});


function guardaryeditarCierre(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);

    var total_efectivocierre = $("#total_efectivocierre").val();
    // Validar que no esté vacío
    if (total_efectivocierre === "" || total_efectivocierre === null || total_efectivocierre === undefined) {
        Swal.fire({
            icon: 'error',
            title: 'Error de Validación',
            text: 'El campo "Total de Efectivo" no puede estar vacío.',
            confirmButtonText: 'Aceptar'
        });
        $("#total_efectivocierre").focus();
        return false;
    }
    var formData = new FormData($("#formularioCierre")[0]);
    load();
    $.ajax({
        url: "../ajax/cuadres_caja_cierre.php?op=guardaryeditar2Parqueo",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.close();
            //console.log("datos: " + datos);
            $('#myModalCierre').modal('hide');
            if (confirm("Desea Imprimir su Cierre de caja")) {
                window.location.href = "../reportes/exTicket_CobroParqueoCierre.php?id=" + datos, '_blank';
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



init();