var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    $('#MenuAcceso').addClass("treeview active");
    $('#Sucursales').addClass("active");

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
}

//Función limpiar 
function limpiar() {
    $("#idcobradores").val("");
    $("#nombre").val("0");
    $("#descripcion").val("0");
    $("#telefono").val("0");
    $("#email").val("sincorreo@gmail.com");

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
                url: '../ajax/cobradores.php?op=listar',
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
        url: "../ajax/cobradores.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.fire({
                title: 'Mensaje!',
                text: datos,
                icon: 'success',
                timer: 3000, // 2 segundos
                timerProgressBar: true,
                willClose: () => {
                    window.location.reload();
                }
            });

        }

    });
    limpiar();
}

function mostrar(idcobradores) {
    $.post("../ajax/cobradores.php?op=mostrar", { idcobradores: idcobradores }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#idcobradores").val(data.idcobradores);
    })
}

//Función para desactivar registros
function desactivar(idcobradores) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas desactivar el Cobrador?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/cobradores.php?op=desactivar", { idcobradores: idcobradores }, function (e) {
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
    });
}

function activar(idcobradores) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas Activar el Cobrador?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/cobradores.php?op=activar", { idcobradores: idcobradores }, function (e) {
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
    });
}





init();