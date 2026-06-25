var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    /*$("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })*/
    $("#btnGuardar").click(function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });

}

//Función limpiar
function limpiar() {
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#idcategoria").val("");
    $("#tipo_descuento").val("Quetzales");
    $("#tipo_descuento").selectpicker('refresh');
    $("#mostrar_en_venta").val("SI");
    $("#mostrar_en_venta").selectpicker('refresh');
    $("#valor_descuento").val("0");
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
    var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
    var fecha_fin_reporte = $("#fecha_fin_reporte").val();
    tabla = $('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [
            ],
            "ajax":
            {
                url: '../ajax/venta.php?op=listar_ventas_servicios_usuario',
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte },
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
        url: "../ajax/categoria.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log(datos);
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
    limpiar();
}

function capturarUbicacion() {
    load();
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

function mostrarModalServicio(idventa) {
    $('#formServicio')[0].reset();
    $('#idventa_servicio').val(idventa);
    $('#modalServicio').modal('show');
}

function guardarServicio() {
    var formData = new FormData($("#formServicio")[0]);
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se actualizará el estado del servicio",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../ajax/venta.php?op=registrar_venta_servicio",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Procesando...',
                        text: 'Espere un momento por favor',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (datos) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Operación Exitosa!',
                        text: 'El servicio se ha actualizado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalServicio').modal('hide');
                        location.reload();
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron guardar los datos. Intente de nuevo.'
                    });
                }
            });
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


init();