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

    $.post("../ajax/articulo.php?op=selectCategoria", function (r) {
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');

    });    

}

    // Evento change para categoria
    $("#idcategoria").change(function () {
        var idcategoria = $(this).val();

        // Validar que tengamos un valor válido
        if (!idcategoria) {
            console.log("No se seleccionó una categoría válida");
            return;
        }

        // Mostrar indicador de carga
        $("#idsubcategoria").html('<option value="">Cargando...</option>');
        $('#idsubcategoria').selectpicker('refresh');

        $.ajax({
            url: "../ajax/articulo.php?op=selectSubcategoria",
            type: "POST",
            data: { idcategoria: idcategoria },
            success: function (r) {
                console.log("Respuesta del servidor:", r);
                $("#idsubcategoria").html(r);
                $('#idsubcategoria').selectpicker('refresh');
            },
            error: function (xhr, status, error) {
                console.error("Error en la petición:", error);
                $("#idsubcategoria").html('<option value="">Error al cargar subcategorías</option>');
                $('#idsubcategoria').selectpicker('refresh');
            }
        });
    });

//Función limpiar
function limpiar() {
    $("#idarticulo").val("");
    $("#nombre").val("");
    $("#codigo").val("");
    
    // Recargar categorías
    $.post("../ajax/articulo.php?op=selectCategoria", function (r) {
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');
    });
    
    // Limpiar subcategorías
    $("#idsubcategoria").html('<option value="">Seleccione una subcategoría</option>');
    $('#idsubcategoria').selectpicker('refresh');
    
    $("#descripcion_articulo").val("");
    
    $("#imagen").val("");
    $("#imagenactual").val("");
    $("#imagenmuestra").attr("src","");
    // Limpiar imágenes y preview
    $("#imagenes").val("");
    $("#previewImagenes").html("");
    
    // Limpiar imágenes existentes guardadas
    $("input[name='imagenes_existentes[]']").remove();
    $("input[name='imagenes_eliminar[]']").remove();

    
    $("#stock").val("");
    $("#stock_pv").val("");
    $("#stock_pv_oferta").val("");
    
    $("#meta_titulo").val("");
    $("#meta_descripcion").val("");
    $("#meta_keywords").val("");
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
                url: '../ajax/tienda_web_articulos.php?op=listar',
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
 
    var formData = new FormData(); 
    formData.append("idarticulo", $("#idarticulo").val());
    formData.append("nombre", $("#nombre").val());
    formData.append("codigo", $("#codigo").val());
    formData.append("idcategoria", $("#idcategoria").val());
    formData.append("idsubcategoria", $("#idsubcategoria").val());
    formData.append("descripcion_articulo", $("#descripcion_articulo").val());
    formData.append("tipo_promocion", $("#tipo_promocion").val());
    let imagenFile = $("#imagen")[0].files[0];
    if (imagenFile) {
        formData.append("imagen", imagenFile);
    }
    formData.append("imagenactual", $("#imagenactual").val());

    formData.append("precio_compra", $("#precio_compra").val());
    formData.append("stock", $("#stock").val());
    formData.append("stock_pv", $("#stock_pv").val());
    formData.append("stock_pv_oferta", $("#stock_pv_oferta").val());

    formData.append("meta_titulo", $("#meta_titulo").val());
    formData.append("meta_descripcion", $("#meta_descripcion").val());
    formData.append("meta_keywords", $("#meta_keywords").val());
    
    
    // 👉 AGREGAR TODAS LAS IMÁGENES DEL INPUT
    var files = $("#imagenes")[0].files;
    for (var i = 0; i < files.length; i++) {
        formData.append("imagenes[]", files[i]);
    }
    
    // 👉 AGREGAR IDs DE IMÁGENES A ELIMINAR
    $("input[name='imagenes_eliminar[]']").each(function() {
        formData.append("imagenes_eliminar[]", $(this).val());
    });

    $.ajax({
        url: "../ajax/tienda_web_articulos.php?op=guardaryeditar",
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

function mostrar(idarticulo) {
    $.post("../ajax/tienda_web_articulos.php?op=mostrar", { idarticulo: idarticulo }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idarticulo").val(data.idarticulo);
        $("#nombre").val(data.nombre);
        $("#codigo").val(data.codigo);
        $("#descripcion_articulo").val(data.descripcion_articulo);

        $("#stock").val(data.stocksucursal);
        $("#precio_compra").val(data.precio_compra);
        $("#stock_pv").val(data.precio_venta);
        $("#stock_pv_oferta").val(data.precio_venta_oferta);
        $("#meta_titulo").val(data.meta_titulo);
        $("#meta_descripcion").val(data.meta_descripcion);
        $("#meta_keywords").val(data.meta_keywords);
        
        // Mostrar imagen principal
        if (data.imagen && data.imagen !== "") {
            $("#imagenactual").val(data.imagen);
            $("#imagenmuestra").attr("src", "../files/articulos/" + data.imagen);
        } else {
            $("#imagenactual").val("");
            $("#imagenmuestra").attr("src", "");
        }
        
        // Guardar el valor de subcategoría para establecerlo después
        var idsubcategoriaSeleccionada = data.idsubcategoria;
        
        // Establecer categoría y cargar subcategorías
        $("#idcategoria").val(data.idcategoria);
        $('#idcategoria').selectpicker('refresh');
        
        // Cargar subcategorías y luego establecer el valor
        if (data.idcategoria) {
            $("#idsubcategoria").html('<option value="">Cargando...</option>');
            $('#idsubcategoria').selectpicker('refresh');
            
            $.ajax({
                url: "../ajax/articulo.php?op=selectSubcategoria",
                type: "POST",
                data: { idcategoria: data.idcategoria },
                success: function (r) {
                    console.log("Respuesta del servidor:", r);
                    $("#idsubcategoria").html(r);
                    $('#idsubcategoria').selectpicker('refresh');
                    
                    // Ahora establecer el valor de subcategoría después de cargar las opciones
                    if (idsubcategoriaSeleccionada) {
                        $("#idsubcategoria").val(idsubcategoriaSeleccionada);
                        $('#idsubcategoria').selectpicker('refresh');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la petición:", error);
                    $("#idsubcategoria").html('<option value="">Error al cargar subcategorías</option>');
                    $('#idsubcategoria').selectpicker('refresh');
                }
            });
        }
        
        obtenerimagenesdetalle(idarticulo);
    })

}

function obtenerimagenesdetalle(idarticulo) {
    $.post("../ajax/tienda_web_articulos.php?op=obtenerImagenes", { idarticulo: idarticulo }, function (data) {
        console.log("Imágenes obtenidas:", data);
        data = JSON.parse(data);
        
        // Limpiar preview anterior
        $("#previewImagenes").html("");
        
        // Mostrar imágenes existentes
        if (data.length > 0) {
            $.each(data, function (i, item) {
                var rutaCompleta = "../" + item.ruta;
                var htmlImagen = `
                    <div class="preview-imagen-item" style="position: relative; margin: 5px; display: inline-block;">
                        <img src="${rutaCompleta}" 
                             style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px; padding: 2px;">
                        <button type="button" 
                                class="btn btn-xs btn-danger eliminar-imagen-existente" 
                                data-id="${item.id}"
                                style="position: absolute; top: 0; right: 0; padding: 2px 5px; font-size: 12px;">
                            <i class="fa fa-times"></i>
                        </button>
                        <input type="hidden" name="imagenes_existentes[]" value="${item.id}">
                    </div>
                `;
                $("#previewImagenes").append(htmlImagen);
            });
        }
    });
}

// Función para eliminar imagen existente
$(document).on('click', '.eliminar-imagen-existente', function() {
    var idImagen = $(this).data('id');
    var item = $(this).closest('.preview-imagen-item');
    
    // Agregar campo oculto para marcar la imagen como eliminada
    if (item.find('input[name="imagenes_eliminar[]"]').length === 0) {
        item.append('<input type="hidden" name="imagenes_eliminar[]" value="' + idImagen + '">');
    }
    
    // Remover el input de imágenes existentes
    item.find('input[name="imagenes_existentes[]"]').remove();
    
    // Ocultar o eliminar el elemento
    item.fadeOut(300, function() {
        $(this).remove();
    });
});


//Función para desactivar registros
function desactivar(idarticulo) {
    bootbox.confirm("¿Está Seguro de desactivar el Artículo?", function (result) {
        if (result) {
            $.post("../ajax/tienda_web_articulos.php?op=desactivar", { idcategoria: idcategoria }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idarticulo) {
    bootbox.confirm("¿Está Seguro de activar el Artículo?", function (result) {
        if (result) {
            $.post("../ajax/tienda_web_articulos.php?op=activar", { idcategoria: idcategoria }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}


// Preview de imágenes cuando se seleccionan archivos
$("#imagenes").on("change", function(e) {
    var files = e.target.files;
    var preview = $("#previewImagenes");
    
    // Contar imágenes existentes
    var imagenesExistentes = $(".preview-imagen-item").length;
    var maxImagenes = 4;
    
    // Validar cantidad máxima
    if (imagenesExistentes + files.length > maxImagenes) {
        Swal.fire({
            title: 'Advertencia',
            text: `Solo puedes subir máximo ${maxImagenes} imágenes. Ya tienes ${imagenesExistentes} imagen(es).`,
            icon: 'warning',
            timer: 3000
        });
        $(this).val("");
        return;
    }
    
    // Mostrar preview de nuevas imágenes
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        
        // Validar que sea una imagen
        if (!file.type.match('image.*')) {
            continue;
        }
        
        var reader = new FileReader();
        reader.onload = (function(file) {
            return function(e) {
                var htmlPreview = `
                    <div class="preview-imagen-item" style="position: relative; margin: 5px; display: inline-block;">
                        <img src="${e.target.result}" 
                             style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px; padding: 2px;">
                        <button type="button" 
                                class="btn btn-xs btn-danger eliminar-imagen-preview" 
                                style="position: absolute; top: 0; right: 0; padding: 2px 5px; font-size: 12px;">
                            <i class="fa fa-times"></i>
                        </button>
                        <small style="display: block; text-align: center; font-size: 10px; margin-top: 2px;">${file.name}</small>
                    </div>
                `;
                preview.append(htmlPreview);
            };
        })(file);
        reader.readAsDataURL(file);
    }
});

// Eliminar imagen del preview (nueva)
$(document).on('click', '.eliminar-imagen-preview', function() {
    $(this).closest('.preview-imagen-item').remove();
    
    // Actualizar el input file
    var input = $("#imagenes")[0];
    var files = Array.from(input.files);
    var index = $(this).closest('.preview-imagen-item').index();
    files.splice(index, 1);
    
    // Crear un nuevo DataTransfer y actualizar el input
    var dt = new DataTransfer();
    files.forEach(function(file) {
        dt.items.add(file);
    });
    input.files = dt.files;
});

init();