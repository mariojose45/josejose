var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    $('#MenuCotizacion').addClass("treeview active");
    $('#Cotizaciones').addClass("active");

    listarArticulos();
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

    $("#btnGuardarCierre").click(function (e) {
        guardaryeditarCierre(e);
    });

    $.post("../ajax/ingreso_vehiculo.php?op=selectMarca", function (r) {
        $("#idvendedor").html(r);
        $('#idvendedor').selectpicker('refresh');
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
                $("#tipo_cliente").val("PUBLICO");
                $("#tipo_cliente").selectpicker('refresh');

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
    $.post("../ajax/consultas.php?op=validarCodigo", { codigo_cliente: codigo_cliente }, function (data, status) {
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
    tipo_documento, codigo_cliente, tipo_cliente) {
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


//Función limpiar
function limpiar() {
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

        //$("#btnGuardar").hide();
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
        //$("#btnGuardar").hide();
        // $("#btnGuardar").disabled();
        $("#fecha_hora_cobro2").hide();
        $("#dias_credito2").hide();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
    document.getElementById('formulario').reset();
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
                url: '../ajax/ingreso_vehiculo.php?op=listar',
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
    e.preventDefault(); // Evita que el formulario se envíe de forma predeterminada
    load();

    var formData = new FormData($("#formulario")[0]);
    var revisiones = [];
    $("#tblRevisiones tbody tr").each(function () {
        var fila = $(this);
        var idRevision = fila.data("id");
        var nombre = fila.find('td:first').text().trim();
        var estados = {
            '100': false,
            '75': false,
            '50': false,
            'sugerido': false
        };

        fila.find("td input[type='checkbox']").each(function (index) {
            var estadoKey;
            switch (index) {
                case 0: estadoKey = '100'; break;
                case 1: estadoKey = '75'; break;
                case 2: estadoKey = '50'; break;
                case 3: estadoKey = 'sugerido'; break;
            }
            if ($(this).is(':checked')) {
                estados[estadoKey] = true;
            }
        });
        revisiones.push({
            id: idRevision,
            nombre: nombre,
            estado100: estados['100'],
            estado75: estados['75'],
            estado50: estados['50'],
            cambioSugerido: estados['sugerido']
        });
    });

    formData.append("revisiones_json", JSON.stringify(revisiones));
    //console.log("Datos de revisiones a enviar: ", revisiones);
    $.ajax({
        url: "../ajax/ingreso_vehiculo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            //console.log("Datos ", datos);
            Swal.fire({
                title: "Operación exitosa",
                text: "Datos del Vehículo registrado correctamente.",
                icon: "success"
            }).then(() => {
                window.location.reload();
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX: ", textStatus, errorThrown);
        }
    });
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
function anular(idingreso_vehiculo) {
    // Primer SweetAlert: Solicita la contraseña
    Swal.fire({
        title: 'Ingresa la contraseña de autorización',
        input: 'password', // Usa 'password' para ocultar la entrada
        inputPlaceholder: 'Contraseña...',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        customClass: {
            // Clases de Tailwind para estilo si se están usando
            confirmButton: 'bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full',
            cancelButton: 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full ml-2'
        },
        buttonsStyling: false,
        inputValidator: (value) => {
            if (!value) {
                return '¡Necesitas ingresar una contraseña!';
            }
        }
    }).then((result) => {
        // Se ejecuta si el usuario presiona "Aceptar" en la primera alerta
        if (result.isConfirmed) {
            const valor = result.value;

            // Verifica la contraseña
            if (valor === "5820005710") {
                // Segundo SweetAlert: Pide confirmación para la anulación
                Swal.fire({
                    title: '¿Estás seguro de anular el Ingreso del Vehiculo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, anular',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    // Se ejecuta si el usuario confirma la anulación
                    if (result.isConfirmed) {
                        $.post("../ajax/ingreso_vehiculo.php?op=anular", { idingreso_vehiculo: idingreso_vehiculo }, function (e) {
                            Swal.fire({
                                title: 'Mensaje!',
                                text: e,
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                willClose: () => {
                                    window.location.reload();
                                }
                            });
                        });
                    }
                });
            } else {
                // Muestra un mensaje de error si la contraseña no es válida
                Swal.fire({
                    icon: 'error',
                    title: 'Error de autenticación',
                    text: 'Contraseña no válida: [' + valor + ']',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        }
    });
}

init();

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    calcularTotales();
    detalles = detalles - 1;
    evaluar()
}


function mostrar(idingreso_vehiculo) {
    load();
    $.post("../ajax/ingreso_vehiculo.php?op=mostrar", { idingreso_vehiculo: idingreso_vehiculo }, function (data, status) {
        data = JSON.parse(data);
        //console.log("Data para mostrar ", data);
        $("#idingreso_vehiculo").val(data.idingreso_vehiculo);
        //datos cliente
        $("#codigo_cliente").val(data.codigo_cliente);
        $("#nit").val(data.nit);
        $("#nombre_cliente").val(data.nombre_cliente);
        $("#telefono_cliente").val(data.telefono_cliente);
        $("#direccion_cliente").val(data.direccion_cliente);
        $("#correo_cliente").val(data.correo_cliente);
        $("#tipo_cliente").val(data.tipo_cliente);
        $("#tipo_cliente").selectpicker('refresh');
        $("#idcliente").val(data.idpersona);

        for (let i = 1; i <= 8; i++) {
            $("#imagenactual" + i).val(data["imagen" + i]);
            $("#imagenmuestra" + i).attr("src", "../files/articulos/" + data["imagen" + i]);
            $("#descripcion" + i).val(data["descripcion" + i]);
        }

        $("#trabajos_detalle").val(data.trabajos_detalle);
        $("#observaciones_adicionales").val(data.observaciones_adicionales);
        //
        $("#no_placa").val(data.no_placa);
        $("#no_chasis").val(data.no_chasis);
        $("#serie").val(data.serie);
        $("#no_motor").val(data.no_motor);
        $("#modelo").val(data.modelo);
        $("#km").val(data.km);
        $("#idvendedor").val(data.idmarca);
        $("#idvendedor").selectpicker('refresh');
        //
        mostrarform(true);
        Swal.close();
    });
    //CARGAR DETALLES DE REVISIONES
    $.ajax({
        url: "../ajax/mecanico.php?op=mostrar_detalles",
        type: "POST",
        data: { idingreso_vehiculo: idingreso_vehiculo },
        success: function (response) {
            response = JSON.parse(response);
            //console.log("mostrar detalles respuesta ", response);
            if (response.revisiones && response.revisiones.length > 0) {
                $('#tblRevisiones tbody').empty();
                response.revisiones.forEach(function (item) {
                    var fila_revision = '<tr data-id="' + item.iddetalle_revision_vehiculo + '">' +
                        '<td>' + item.nombre_revision + '</td>' +
                        '<td><input type="checkbox" class="form-check-input check-revisionVehiculo"' + (item.estado_100 == 1 ? ' checked' : '') + '></td>' +
                        '<td><input type="checkbox" class="form-check-input check-revisionVehiculo"' + (item.estado_75 == 1 ? ' checked' : '') + '></td>' +
                        '<td><input type="checkbox" class="form-check-input check-revisionVehiculo"' + (item.estado_50 == 1 ? ' checked' : '') + '></td>' +
                        '<td><input type="checkbox" class="form-check-input check-revisionVehiculo"' + (item.cambio_sugerido == 1 ? ' checked' : '') + '></td>' +
                        '</tr>';
                    $('#tblRevisiones tbody').append(fila_revision);
                });
            } else {

            }

        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los datos:", error);
        }
    });
}

function previewImage(event, imgId) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById(imgId);
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function clearImage(inputId, imgId, hiddenId) {
    const input = document.getElementById(inputId);
    const output = document.getElementById(imgId);
    const hiddenInput = document.getElementById(hiddenId);
    input.value = null;
    output.src = "";
    hiddenInput.value = "";
}
