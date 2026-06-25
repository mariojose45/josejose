var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    $('#MenuAlmacen').addClass("treeview active");
    $('#CategoriasCrear').addClass("active");

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
}

//Función limpiar
function limpiar() {
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#idsector").val("");
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
                url: '../ajax/sector.php?op=listar',
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
        url: "../ajax/sector.php?op=guardaryeditar",
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
    limpiar();
}

function mostrar(idsector) {
    $.post("../ajax/sector.php?op=mostrar", { idsector: idsector }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#tipo_descuento").val(data.tipo_descuento);
        $('#tipo_descuento').selectpicker('refresh');
        $("#valor_descuento").val(data.valor_descuento);
        $("#idsector").val(data.idsector);

    })
}

//Función para desactivar registros
function desactivar(idsector) {
    bootbox.confirm("¿Está Seguro de desactivar la Sector?", function (result) {
        if (result) {
            $.post("../ajax/sector.php?op=desactivar", { idsector: idsector }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idsector) {
    bootbox.confirm("¿Está Seguro de activar la Sector?", function (result) {
        if (result) {
            $.post("../ajax/sector.php?op=activar", { idsector: idsector }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}


init();