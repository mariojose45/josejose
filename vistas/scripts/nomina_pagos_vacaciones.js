var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#btnGuardar").click(function (e) {
        guardaryeditar(e);
    });

    $.post("../ajax/nomina_empleado.php?op=selectEmpleado", function (r) {
        // Agregamos opción por defecto antes de las demás
        $("#idempleado").html('<option value="">Seleccione un empleado</option>' + r);
        $('#idempleado').selectpicker('refresh');
    });
}

//Función limpiar
function limpiar() {
    $("#idvacacion").val("");
    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_solicitud').val(today);
    $('#fecha_inicio').val(today);
    $('#fecha_fin').val(today);
    $("#idempleado").val("");
    $("#dias_solicitados").val("0");
    $("#motivo").val("");
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
                url: '../ajax/nomina_pagos_vacaciones.php?op=listar',
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

    let inicio = $("#fecha_inicio").val();
    let fin = $("#fecha_fin").val();
    let dias = $("#dias_solicitados").val();

    if (!inicio || !fin) {
        Swal.fire("Fechas requeridas");
        return;
    }

    if (new Date(inicio) > new Date(fin)) {
        Swal.fire("La fecha inicial no puede ser mayor que la final");
        return;
    }

    if (dias <= 0) {
        Swal.fire("Debe ingresar días válidos");
        return;
    }
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/nomina_pagos_vacaciones.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.fire({
                title: "Mensaje",
                text: datos,
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });
            mostrarform(false);
            tabla.ajax.reload();
        }

    });
    limpiar();
}

/*function mostrar(idcategoria) {
    $.post("../ajax/categoria.php?op=mostrar", { idcategoria: idcategoria }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#idcategoria").val(data.idcategoria);

    })
}*/

//Función para desactivar registros
function desactivar(idvacacion) {
    bootbox.confirm("¿Está Seguro de desactivar las Vacaciones?", function (result) {
        if (result) {
            $.post("../ajax/nomina_pagos_vacaciones.php?op=desactivar", { idvacacion: idvacacion }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}



// =================== INFO VACACIONES ===================
$(document).on("change", "#idempleado", function () {

    let idempleado = $(this).val();
    if (!idempleado) {
        $("#infoVacaciones").hide();
        return;
    }

    $.post(
        "../ajax/nomina_pagos_vacaciones.php?op=infoVacaciones",
        { idempleado: idempleado },
        function (response) {

            //  console.log("Respuesta vacaciones:", response);

            let info = {};

            try {
                info = JSON.parse(response);
            } catch (e) {
                console.error("Error al parsear JSON:", e);
                Swal.fire("Error", "No se pudo obtener la información de vacaciones.", "error");
                return;
            }

            // 🔹 Si viene error desde PHP
            if (info.error) {
                $("#infoVacaciones").hide();
                Swal.fire("Advertencia", info.mensaje, "warning");
                return;
            }

            // 🔹 Mostrar div de información
            $("#infoVacaciones").show();

            $("#periodo_vac").text(info.periodo);
            $("#dias_generados").text(info.dias_generados);
            $("#dias_gozados").text(info.dias_gozados);
            $("#dias_pendientes").text(info.dias_pendientes);

            // 🔹 Advertencia si no tiene días
            if (info.dias_pendientes <= 0) {
                Swal.fire(
                    "Sin días disponibles",
                    "El empleado ya no tiene días de vacaciones pendientes.",
                    "warning"
                );
            }
        }
    );
});



function mostrar(idvacacion) {
    $.post("../ajax/nomina_pagos_vacaciones.php?op=mostrar", { idvacacion: idvacacion }, function (data, status) {
        data = JSON.parse(data);
        console.log("Data:", data);
        mostrarform(true);

        $("#idvacacion").val(data.idvacacion);
        $("#fecha_solicitud").val(data.fechasolicitud);
        $("#idempleado").val(data.idempleado);
        $("#idempleado").selectpicker('refresh');
        $("#fecha_inicio").val(data.fechainicio);
        $("#fecha_fin").val(data.fechafin);
        $("#dias_solicitados").val(data.dias_solicitados);
        $("#motivo").val(data.motivo);

    })
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