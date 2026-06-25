var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
}

//Función limpiar
function limpiar() {
    $("#codigo_empleado").val("");
    $("#idempleado_r").val("");
    $("#nombres").val("");
    $("#cui").val("");
    $("#nit").val("");
    $("#direccion").val("");
    $("#fecha_nacimiento").val("");
    $("#hijos").val("");
    $("#sexo").val("Masculino");
    $('#sexo').selectpicker('refresh');
    $("#estado_civil").val("Soltero");
    $('#estado_civil').selectpicker('refresh');
    $("#edad").val("");
    $("#telefono").val("");
    $("#nacionalidad").val("Guatemalteco");
    $("#nivel_educativo").val("Primaria");
    $('#nivel_educativo').selectpicker('refresh');

    $("#puesto").val("");
    $("#fecha_inicio_laboral").val("");
    $("#salario_base").val("");
    $("#salario").val("");
    $("#bonificacion").val("");
    $("#bono_productividad").val("");
    $("#salario_extra").val("");
    $("#igss").val("");
    $("#isr").val("");
    $("#prestamo").val("");
    $("#otros_descuentos").val("");
    $("#anticipo_salarial").val("");
    $("#fecha_fin_laboral").val("");
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
                url: '../ajax/nomina_empleado.php?op=listar',
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
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/nomina_empleado.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //console.log("Datos del empleado ", datos);
            Swal.fire({
                title: '¡Operación Exitosa! 🎉',
                text: "Datos del Emplado registrados correctamente.",
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#28a745'
            });

            mostrarform(false);
            tabla.ajax.reload();

            $("#btnGuardar").prop("disabled", false);
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: '¡Error en el Servidor! 🚨',
                text: 'Hubo un problema al intentar guardar los datos. Inténtelo de nuevo.',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
            $("#btnGuardar").prop("disabled", false);
        }

    });
    limpiar();
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

function mostrar(idempleado) {
    load();
    $.post("../ajax/nomina_empleado.php?op=mostrar", { idempleado_r: idempleado }, function (data, status) {

        console.log("Data ", data);
        mostrarform(true);
        data = JSON.parse(data);
        $("#codigo_empleado").val(data.codigo);
        $("#idempleado_r").val(data.idempleado);
        $("#nombres").val(data.nombres);
        $("#cui").val(data.cui);
        $("#nit").val(data.nit);
        $("#edad").val(data.edad);
        $("#direccion").val(data.direccion);
        $("#fecha_nacimiento").val(data.fecha_nacimiento);
        $("#hijos").val(data.hijos);
        $("#sexo").val(data.sexo);
        $('#sexo').selectpicker('refresh');
        $("#estado_civil").val(data.estado_civil);
        $('#estado_civil').selectpicker('refresh');
        $("#nacionalidad").val(data.nacionalidad);
        $("#nivel_educativo").val(data.nivel_educativo);
        $('#nivel_educativo').selectpicker('refresh');
        $("#telefono").val(data.telefono);
        $("#puesto").val(data.puesto);
        $("#fecha_inicio_laboral").val(data.fecha_inicio_laboral);
        $("#nombfecha_fin_laboralre").val(data.fecha_fin_laboral);
        $("#salario_base").val(data.salario_base);
        $("#salario").val(data.salario_base);
        $("#bonificacion").val(data.bonificacion);
        $("#bono_productividad").val(data.bono_productividad);
        $("#salario_extra").val(data.salario_extra);
        $("#igss").val(data.descuento_igss);
        $("#isr").val(data.descuento_isr);
        $("#prestamo").val(data.descuento_prestamo);
        $("#otros_descuentos").val(data.otros_descuentos);
        $("#anticipo_salarial").val(data.anticipo_salarial);

    });
    Swal.close();
}

//Función para desactivar registros
function desactivar(idempleado) {
    bootbox.confirm("¿Está Seguro de desactivar la Categoría?", function (result) {
        if (result) {
            $.post("../ajax/nomina_empleado.php?op=desactivar", { idempleado_r: idempleado }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idempleado) {
    bootbox.confirm("¿Está Seguro de activar la Categoría?", function (result) {
        if (result) {
            $.post("../ajax/nomina_empleado.php?op=activar", { idempleado_r: idempleado }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}


init();