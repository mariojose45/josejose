var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    /*$("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })*/
    $("#btnGuardar").click(function (e) {
        guardaryeditar(e);
    });

}

//Función limpiar
function limpiar() {
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#idsubcategoria").val("");
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
                url: '../ajax/sub_categoria.php?op=listar',
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
    load();
    $.ajax({
        url: "../ajax/sub_categoria.php?op=guardaryeditar",
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
                    Swal.close();
                    mostrarform(false);
                    tabla.ajax.reload();
                }
            });

        }

    });
    limpiar();
}

function mostrar(idsubcategoria) {
    load();
    $.post("../ajax/sub_categoria.php?op=mostrar", { idsubcategoria: idsubcategoria }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        Swal.close();
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#idsubcategoria").val(data.idsubcategoria);

    })
}

//Función para desactivar registros
function desactivar(idsubcategoria) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Está Seguro de desactivar la Sub Categoría?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            load();
            $.post("../ajax/sub_categoria.php?op=desactivar", { idsubcategoria: idsubcategoria }, function (e) {
                tabla.ajax.reload();
                Swal.close();

                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: e,
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        }
    })
}

//Función para activar registros
function activar(idsubcategoria) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Está Seguro de activar la Sub Categoría?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            load();
            $.post("../ajax/sub_categoria.php?op=activar", { idsubcategoria: idsubcategoria }, function (e) {
                tabla.ajax.reload();
                Swal.close();

                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: e,
                    showConfirmButton: false,
                    timer: 1500
                });
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