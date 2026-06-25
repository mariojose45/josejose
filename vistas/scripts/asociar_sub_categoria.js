var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    listarSubCategoria();

    $.post("../ajax/articulo.php?op=selectCategoriaSubCategoria", function (r) {
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');

    });

    $("#btnGuardar").click(function (e) {
        guardaryeditar(e);
    });


}

//Función limpiar
function limpiar() {
    $("#idasociar_subcategoria").val("");
    $("#idcategoria").val("");

    $(".filas").remove();

    // Recargar categorías (sin categoría actual)
    $.post("../ajax/articulo.php?op=selectCategoriaSubCategoria",
        { idcategoria_actual: 0 },
        function (r) {
            $("#idcategoria").html(r);
            $("#idcategoria").selectpicker("refresh");
        }
    );
}


//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        detalles = 0;
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

function listarSubCategoria() {
    tabla = $('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/sub_categoria.php?op=listarSubCategoria',
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
                url: '../ajax/asociar_sub_categoria.php?op=listar',
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
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/asociar_sub_categoria.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            // console.log(datos);
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

function mostrar(idasociar_subcategoria) {

    $.post("../ajax/asociar_sub_categoria.php?op=mostrar",
        { idasociar_subcategoria: idasociar_subcategoria },
        function (data, status) {

            load(); // indicador de carga

            if (!data || data.trim() === '') {
                Swal.close();
                Swal.fire({
                    title: 'Error',
                    text: 'No hay datos para mostrar',
                    icon: 'error',
                    timer: 5000,
                    timerProgressBar: true
                });
                return;
            }

            data = JSON.parse(data);

            mostrarform(true);
            Swal.close();

            $("#idasociar_subcategoria").val(data.idasociar_subcategoria);

            // 🔥 CARGAR SELECT Y SELECCIONAR VALOR CORRECTAMENTE
            $.post("../ajax/articulo.php?op=selectCategoriaSubCategoria",
                {
                    idcategoria_actual: data.idcategoria
                },
                function (r) {

                    $("#idcategoria").html(r);

                    // 🔥 IMPORTANTE: primero asignar valor
                    $("#idcategoria").val(data.idcategoria);

                    // 🔥 luego refrescar selectpicker
                    $('#idcategoria').selectpicker('refresh');

                });

            // 🔥 DETALLE
            obtenerDetalle(idasociar_subcategoria);
        });
}

function obtenerDetalle(idasociar_subcategoria) {
    $.post("../ajax/asociar_sub_categoria.php?op=obtenerDetalle", { idasociar_subcategoria: idasociar_subcategoria }, function (data) {
        //console.log(data);
        data = JSON.parse(data);
        Swal.close()
        $.each(data, function (i, item) {
            agregarDetalle2(item.idsubcategoria, item.nombreSubCategoria, item.descripcion);
        });
    })
}

function agregarDetalle2(idsubcategoria, nombre, descripcion) {

    if (idsubcategoria != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idsubcategoria[]" value="' + idsubcategoria + '">' + nombre + '</td>' +
            '<td>' + descripcion + '</td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos");
    }
}

//Función para desactivar registros
function desactivar(idasociar_subcategoria) {
    bootbox.confirm("¿Está Seguro de desactivar la Asociación de la Categoria y Sub Categoria?", function (result) {
        if (result) {
            $.post("../ajax/asociar_sub_categoria.php?op=desactivar", { idasociar_subcategoria: idasociar_subcategoria }, function (e) {
                bootbox.alert(e);
                window.location.reload();
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idasociar_subcategoria) {
    bootbox.confirm("¿Está Seguro de activar la Asociación de la Categoria y Sub Categoria?", function (result) {
        if (result) {
            $.post("../ajax/asociar_sub_categoria.php?op=activar", { idasociar_subcategoria: idasociar_subcategoria }, function (e) {
                bootbox.alert(e);
                window.location.reload();
                tabla.ajax.reload();
            });
        }
    })
}

var cont = 0;
var detalles = 0;

function agregarDetalle(idsubcategoria, nombre, descripcion) {

    if (idsubcategoria != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idsubcategoria[]" value="' + idsubcategoria + '">' + nombre + '</td>' +
            '<td>' + descripcion + '</td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos");
    }
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

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    detalles = detalles - 1;
}


init();