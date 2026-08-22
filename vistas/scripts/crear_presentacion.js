
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
    $("#nombre_presentacion1").val("NA");
    $("#nombre_presentacion2").val("NA");
    $("#nombre_presentacion3").val("NA");
    $("#nombre_presentacion4").val("NA");
    $("#nombre_presentacion5").val("NA");
    $("#nombre_presentacion6").val("NA");
    $("#nombre_presentacion7").val("NA");
    $("#nombre_presentacion8").val("NA");
    $("#nombre_presentacion9").val("NA");
    $("#nombre_presentacion1").val("NA");
    $("#nombre_presentacion10").val("NA");
    $("#nombre_presentacion11").val("NA");
    $("#nombre_presentacion12").val("NA");
    $("#nombre_presentacion13").val("NA");
    $("#nombre_presentacion14").val("NA");
    $("#nombre_presentacion15").val("NA");
    $("#nombre_presentacion16").val("NA");
    $("#nombre_presentacion17").val("NA");
    $("#nombre_presentacion18").val("NA");
    $("#nombre_presentacion19").val("NA");
    $("#nombre_presentacion20").val("NA");
    $("#idpresentacion").val("");
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
                url: '../ajax/crear_presentacion.php?op=listar',
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
    e.preventDefault(); // Evita que el formulario recargue la página por defecto
    //e.preventDefault(); //No se activará la acción predeterminada del evento

    // Validar que los nombres no tengan espacios en blanco
    for (let i = 1; i <= 20; i++) {
        let nombre = $("#nombre_presentacion" + i).val();
        if (nombre && nombre.includes(" ")) {
            let num = i < 10 ? '0' + i : i;
            Swal.fire({
                title: 'Error en Nombre ' + num,
                text: 'Los nombres no pueden llevar espacios en blanco. Si desea separar palabras, use un guión (-) por favor.',
                icon: 'error'
            });
            return; // Detiene el guardado
        }
    }

    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    load();
    $.ajax({
        url: "../ajax/crear_presentacion.php?op=guardaryeditar",
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
                    Swal.close();
                    window.location.reload();
                }
            });

        }

    });
    limpiar();
}

function mostrar(idpresentacion) {
    load();
    $.post("../ajax/crear_presentacion.php?op=mostrar", { idpresentacion: idpresentacion }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        Swal.close();

        $("#nombre_presentacion1").val(data.nombre_presentacion1);
        $("#nombre_presentacion2").val(data.nombre_presentacion2);
        $("#nombre_presentacion3").val(data.nombre_presentacion3);
        $("#nombre_presentacion4").val(data.nombre_presentacion4);
        $("#nombre_presentacion5").val(data.nombre_presentacion5);
        $("#nombre_presentacion6").val(data.nombre_presentacion6);
        $("#nombre_presentacion7").val(data.nombre_presentacion7);
        $("#nombre_presentacion8").val(data.nombre_presentacion8);
        $("#nombre_presentacion9").val(data.nombre_presentacion9);
        $("#nombre_presentacion10").val(data.nombre_presentacion10);
        $("#nombre_presentacion11").val(data.nombre_presentacion11);
        $("#nombre_presentacion12").val(data.nombre_presentacion12);
        $("#nombre_presentacion13").val(data.nombre_presentacion13);
        $("#nombre_presentacion14").val(data.nombre_presentacion14);
        $("#nombre_presentacion15").val(data.nombre_presentacion15);
        $("#nombre_presentacion16").val(data.nombre_presentacion16);
        $("#nombre_presentacion17").val(data.nombre_presentacion17);
        $("#nombre_presentacion18").val(data.nombre_presentacion18);
        $("#nombre_presentacion19").val(data.nombre_presentacion19);
        $("#nombre_presentacion20").val(data.nombre_presentacion20);
        $("#idpresentacion").val(data.idpresentacion);

    })
}

//Función para desactivar registros
function desactivar(idpresentacion) {
    bootbox.confirm("¿Está Seguro de desactivar la Presentacion?", function (result) {
        if (result) {
            $.post("../ajax/crear_presentacion.php?op=desactivar", { idcatidpresentacionegoria: idpresentacion }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros


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