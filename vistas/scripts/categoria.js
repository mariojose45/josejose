var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    $('#MenuAlmacen').addClass("treeview active");
    $('#CategoriasCrear').addClass("active");

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
    tabla = $('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    attr: { id: 'btnExcel' }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Exportar PDF',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    attr: { id: 'btnPdf' },
                    exportOptions: { columns: ':visible' },
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 7;
                        doc.styles.tableHeader.fontSize = 8;
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;
                        table.widths = new Array(columnCount).fill('*');
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Column visibility',
                    attr: { id: 'btnColvis' }
                }
            ],
            "ajax":
            {
                url: '../ajax/categoria.php?op=listar',
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
                    Swal.close();
                    mostrarform(false);
                    tabla.ajax.reload();
                }
            });

        }

    });
    limpiar();
}

function mostrar(idcategoria) {
    load();
    $.post("../ajax/categoria.php?op=mostrar", { idcategoria: idcategoria }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        Swal.close();
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#tipo_descuento").val(data.tipo_descuento);
        $('#tipo_descuento').selectpicker('refresh');
        $("#valor_descuento").val(data.valor_descuento);
        $("#idcategoria").val(data.idcategoria);
        $("#mostrar_en_venta").val(data.mostrar_en_venta);
        $("#mostrar_en_venta").selectpicker('refresh');

    })
}

//Función para desactivar registros
function desactivar(idcategoria) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Está Seguro de desactivar la Categoría?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            load();
            $.post("../ajax/categoria.php?op=desactivar", { idcategoria: idcategoria }, function (e) {
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

//Función para activar registros
function activar(idcategoria) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Está Seguro de activar la Categoría?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            load();
            $.post("../ajax/categoria.php?op=activar", { idcategoria: idcategoria }, function (e) {
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



function mostrar_sucursales(idcategoria) {
    $("#idcategoria_modal").val(idcategoria);

    $("#sucursales_container").html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><p>Cargando Sucursales...</p></div>');

    $.post("../ajax/categoria.php?op=listarSucursalesPorCategoria", { idcategoria: idcategoria }, function (r) {

        try {
            var data = JSON.parse(r);
        } catch (e) {
            $("#sucursales_container").html('<p class="alert alert-danger">Error al procesar los datos del servidor. (JSON inválido)</p>');
            $('#modalSucursales').modal('show');
            return;
        }

        var html_sucursales = '';

        if (data.length > 0) {
            html_sucursales += '<div class="list-group">';

            data.forEach(function (reg) {
                var is_checked = (reg.mostrar === 'Si');
                var checked_attr = is_checked ? 'checked' : '';
                var color_clase = is_checked ? 'list-group-item-success' : '';

                html_sucursales += '<div class="list-group-item ' + color_clase + ' d-flex justify-content-between align-items-center">';

                html_sucursales += '<span>' + reg.nombre_sucursal + '</span>';

                html_sucursales += '<label class="switch">';
                html_sucursales += '<input type="checkbox" ';
                html_sucursales += 'name="sucursal_id[]" ';
                html_sucursales += 'value="' + reg.idcategoria_sucursal + '" ';
                html_sucursales += checked_attr;
                html_sucursales += ' data-idcategoria_sucursal="' + reg.idcategoria_sucursal + '"';
                html_sucursales += ' data-estado="' + (is_checked ? 'Si' : 'No') + '"';
                html_sucursales += ' onchange="toggleMostrarEstado(this)"';
                html_sucursales += '>';

                html_sucursales += '<span class="slider round"></span>';
                html_sucursales += '</label>';

                html_sucursales += '</div>';

                html_sucursales += '<input type="hidden" name="idcategoria_sucursal_all[]" value="' + reg.idcategoria_sucursal + '">';

                html_sucursales += '<input type="hidden" id="estado_' + reg.idcategoria_sucursal + '" name="estado_mostrar[]" value="' + (is_checked ? 'Si' : 'No') + '">';

            });

            html_sucursales += '</div>';
        } else {
            html_sucursales = '<p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> No hay sucursales asociadas para esta categoría.</p>';
        }

        $("#sucursales_container").html(html_sucursales);
        $('#modalSucursales').modal('show');
    });
}


function toggleMostrarEstado(checkbox) {
    var id = checkbox.getAttribute('data-idcategoria_sucursal');
    var nuevoEstado = checkbox.checked ? 'Si' : 'No';

    $("#estado_" + id).val(nuevoEstado);

    var item = $(checkbox).closest('.list-group-item');
    if (checkbox.checked) {
        item.addClass('list-group-item-success');
    } else {
        item.removeClass('list-group-item-success');
    }
}

$("#formulario_sucursales").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('idcategoria_principal', $("#idcategoria_modal").val());
    $.ajax({
        url: "../ajax/categoria.php?op=actualizarSucursales",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            //alert(datos);
            Swal.fire({
                title: 'Operación exitosa!',
                text: datos,
                icon: 'success',
                timer: 2000, // 2 segundos
                timerProgressBar: true,
                willClose: () => {
                    window.location.reload();
                }
            });
            $('#modalSucursales').modal('hide');
        }
    });
});


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