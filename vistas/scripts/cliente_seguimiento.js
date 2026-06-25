var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    $('#MenuVentas').addClass("treeview active");
    $('#Clientes').addClass("active");

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })

    $("#btnGuardarSeguimiento").click(function (e) {
        $('#myModalSeguimiento').modal('hide');
        guardaryeditarSeguimiento(e);
    });

    $("#btnGuardarTareas").click(function (e) {
        $('#myModalTareas').modal('hide');
        guardaryeditarTareas(e);
    });

    $("#btnGuardarEventos").click(function (e) {
        $('#myModalEventos').modal('hide');
        guardaryeditarEventos(e);
    });
}

//Función limpiar
function limpiar() {
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#idpersona").val("");
    $("#codigo_cliente").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    }
    else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función Listar
function listar() {
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
                url: '../ajax/persona.php?op=listarseguimiento',
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

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/persona.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //console.log(datos)              
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

function mostrar(idpersona) {
    $.post("../ajax/persona.php?op=mostrar", { idpersona: idpersona }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);


        //alert(data.tipo_cliente);

        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").val(data.num_documento);
        $("#tipo_cliente").val(data.tipo_cliente);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#idpersona").val(data.idpersona);
        $("#codigo_cliente").val(data.codigo_cliente);
        $("#ubicacioncliente").val(data.ubicacion_maps);


    })
}

//Función para eliminar registros
function eliminar(idpersona) {
    bootbox.confirm("¿Está Seguro de eliminar el cliente?", function (result) {
        if (result) {
            $.post("../ajax/persona.php?op=eliminar", { idpersona: idpersona }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}


function capturarUbicacion() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const latitud = position.coords.latitude;
                const longitud = position.coords.longitude;
                const ubicacion = `${latitud},${longitud}`;
                document.getElementById('ubicacioncliente').value = ubicacion;

                // Actualiza el enlace de Google Maps
                const enlaceMapa = `https://www.google.com/maps?q=${latitud},${longitud}`;
                const botonMapa = document.getElementById('verEnMapa');
                botonMapa.href = enlaceMapa;
                botonMapa.style.display = 'inline'; // Mostrar el botón

                Swal.fire({
                    icon: 'success',
                    title: 'Ubicación capturada',
                    text: `Latitud: ${latitud}, Longitud: ${longitud}`,
                    timer: 3000,
                });
            },
            function (error) {
                let mensaje = '';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        mensaje = 'Permiso denegado para obtener la ubicación.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        mensaje = 'Información de ubicación no disponible.';
                        break;
                    case error.TIMEOUT:
                        mensaje = 'La solicitud de ubicación expiró.';
                        break;
                    case error.UNKNOWN_ERROR:
                        mensaje = 'Error desconocido al obtener la ubicación.';
                        break;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error al capturar ubicación',
                    text: mensaje,
                });
            }
        );
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Geolocalización no soportada',
            text: 'Tu navegador no soporta la API de geolocalización.',
        });
    }
}


//SEGUIMIENTO DE CLIENTES
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

function seguimiento(idcliente) {
    $("#idcliente_seguimiento").val(idcliente);
    $("#myModalSeguimiento").modal('show');
}

function guardaryeditarSeguimiento(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //VALIDSAR QUE SELECCIONE UN TIPO CORRECT
    let tipo_seguimiento = $("#tipo_seguimiento").val();
    if (tipo_seguimiento == "SELECCIONE") {
        Swal.fire({
            title: "ATENCIÓN!",
            text: "Seleccione un tipo de seguimiento válido.",
            icon: "warning"
        });
        return;
    } else {
        var formData = new FormData($("#formulario_seguimiento")[0]);
        load();
        $.ajax({
            url: "../ajax/persona.php?op=guardaryeditarSeguimiento",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function (datos) {
                //console.log("datos ", datos);
                Swal.close();
                Swal.fire({
                    title: 'Seguimiento!',
                    text: "Datos de Cliente guardados correctamente.",
                    icon: 'success',
                }).then(() => {
                    window.location.reload();
                });

            }

        });
    }
}

function tareas(idcliente) {
    $("#idcliente_tarea").val(idcliente);
    $("#myModalTareas").modal('show');
}

function guardaryeditarTareas(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //VALIDSAR QUE SELECCIONE UN TIPO CORRECT
    let tarea_prioridad = $("#tarea_prioridad").val();
    if (tarea_prioridad == "SELECCIONE") {
        Swal.fire({
            title: "ATENCIÓN!",
            text: "Seleccione un tipo de Prioridad válido.",
            icon: "warning"
        });
        return;
    } else {
        var formData = new FormData($("#formulario_tareas")[0]);
        load();
        $.ajax({
            url: "../ajax/persona.php?op=guardaryeditarTarea",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function (datos) {
                //console.log("datos ", datos);
                Swal.close();
                Swal.fire({
                    title: 'Tarea!',
                    text: "Datos de Cliente guardados correctamente.",
                    icon: 'success',
                }).then(() => {
                    window.location.reload();
                });

            }

        });
    }
}

function eventos(idcliente) {
    $("#idcliente_evento").val(idcliente);
    $("#myModalEventos").modal('show');
}

function guardaryeditarEventos(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#formulario_eventos")[0]);
    load();
    $.ajax({
        url: "../ajax/persona.php?op=guardaryeditarEvento",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //console.log("datos ", datos);
            Swal.close();
            Swal.fire({
                title: 'Evento!',
                text: "Datos de Cliente guardados correctamente.",
                icon: 'success',
            }).then(() => {
                window.location.reload();
            });

        }

    });
}

function capturarUbicacion() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const latitud = position.coords.latitude;
                const longitud = position.coords.longitude;
                const ubicacion = `${latitud},${longitud}`;
                document.getElementById('evento_ubicacion').value = ubicacion;

                // Actualiza el enlace de Google Maps
                const enlaceMapa = `https://www.google.com/maps?q=${latitud},${longitud}`;
                const botonMapa = document.getElementById('verEnMapa');
                botonMapa.href = enlaceMapa;
                botonMapa.style.display = 'inline'; // Mostrar el botón

                Swal.fire({
                    icon: 'success',
                    title: 'Ubicación capturada',
                    text: `Latitud: ${latitud}, Longitud: ${longitud}`,
                    timer: 3000,
                });
            },
            function (error) {
                let mensaje = '';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        mensaje = 'Permiso denegado para obtener la ubicación.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        mensaje = 'Información de ubicación no disponible.';
                        break;
                    case error.TIMEOUT:
                        mensaje = 'La solicitud de ubicación expiró.';
                        break;
                    case error.UNKNOWN_ERROR:
                        mensaje = 'Error desconocido al obtener la ubicación.';
                        break;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error al capturar ubicación',
                    text: mensaje,
                });
            }
        );
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Geolocalización no soportada',
            text: 'Tu navegador no soporta la API de geolocalización.',
        });
    }
}

function capturarUbicacion2() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function (position) {
        const latitud = position.coords.latitude;
        const longitud = position.coords.longitude;
        const ubicacion = `${latitud},${longitud}`;
        document.getElementById('ubicacioncliente2').value = ubicacion;

        // Actualiza el enlace de Google Maps
        const enlaceMapa = `https://www.google.com/maps?q=${latitud},${longitud}`;
        const botonMapa = document.getElementById('verEnMapa2');
        botonMapa.href = enlaceMapa;
        botonMapa.style.display = 'inline'; // Mostrar el botón

        Swal.fire({
          icon: 'success',
          title: 'Ubicación capturada',
          text: `Latitud: ${latitud}, Longitud: ${longitud}`,
          timer: 3000,
        });
      },
      function (error) {
        let mensaje = '';
        switch (error.code) {
          case error.PERMISSION_DENIED:
            mensaje = 'Permiso denegado para obtener la ubicación.';
            break;
          case error.POSITION_UNAVAILABLE:
            mensaje = 'Información de ubicación no disponible.';
            break;
          case error.TIMEOUT:
            mensaje = 'La solicitud de ubicación expiró.';
            break;
          case error.UNKNOWN_ERROR:
            mensaje = 'Error desconocido al obtener la ubicación.';
            break;
        }
        Swal.fire({
          icon: 'error',
          title: 'Error al capturar ubicación',
          text: mensaje,
        });
      }
    );
  } else {
    Swal.fire({
      icon: 'warning',
      title: 'Geolocalización no soportada',
      text: 'Tu navegador no soporta la API de geolocalización.',
    });
  }
}

init();