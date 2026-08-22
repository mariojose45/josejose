var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    // listar();
    $('#MenuAlmacen').addClass("treeview active");
    $('#ArticulosCrear').addClass("active");

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });

    //Cargamos los items al select categoria
    $.post("../ajax/articulo.php?op=selectCategoria", function (r) {
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');

    });

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
                // console.log("Respuesta del servidor:", r);
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

    /*$.post("../ajax/articulo.php?op=selectSubCategoria", function(r){
                $("#idsubcategoria").html(r);
                $('#idsubcategoria').selectpicker('refresh');
 
    });  */

    $.post("../ajax/articulo.php?op=selectEmpresa", function (r) {
        $("#idempresa").html(r);
        $('#idempresa').selectpicker('refresh');
    });


    $("#imagenmuestra").hide();
}

$("#descuento_porcentaje").change(caculardescuento);
function caculardescuento() {
    var precioventa = $("#precio_venta").val();
    var porcentaje = $("#descuento_porcentaje").val();

    var res = precioventa - ((precioventa * porcentaje) / 100);
    $("#precio_descuento").val(res);

}








//Función limpiar
function limpiar() {
    $("#idarticulo").val("");
    $("#nombre").val("");

    $("#facturar_cero").val("SI");
    $("#facturar_cero").selectpicker('refresh');

    $("#idcategoria").val("");
    $("#idsubcategoria").val("");
    $("#descripcion").val("");
    $("#descripcion_2").val("");


    $("#aplica_comision").val("SI");
    $("#aplica_comision").selectpicker('refresh');

    $("#producto_consignacion").val("SI");
    $("#producto_consignacion").selectpicker('refresh');

    $("#aplica_impuestos").val("SI");
    $("#aplica_impuestos").selectpicker('refresh');

    $("#stock").val("0");
    $("#stockminimo").val("0");
    $("#stockmaximo").val("0");

    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#print").hide();
    $("#codigo").val("");
    $("#codigo_sku").val("");

    $("#precio_compra").val("0");
    $("#ganacia_articulo").val("0");



    $("#precio_venta").val("0");
    $("#precio_ventaNocturno").val("0");
    $("#descuento_porcentaje").val("0");

    $("#tipo_descuento").val("PORCENTAJE");
    $("#tipo_descuento").selectpicker('refresh');

    $("#precio_descuento").val("0");

    $("#precio_rango1").val("0");
    $("#precio_rango1_Dos").val("0");
    $("#precio_rango2").val("0");
    $("#precio_rango2_Dos").val("0");
    $("#precio_rango3").val("0");
    $("#precio_rango3_Dos").val("0");

    $("#precio_rango1_Mecanico").val("0");
    $("#precio_rango2_MecanicoDos").val("0");
    $("#precio_rango3_MecanicoTres").val("0");

    $("#precio_rango1_Distribuidor").val("0");
    $("#precio_rango2_DistribuidorDos").val("0");
    $("#precio_rango3_DistribuidorTres").val("0");

    $("#precio_rango1_Mayorista").val("0");
    $("#precio_rango2_MayoristaDos").val("0");
    $("#precio_rango3_MayoristaTres").val("0");



    $("#tipo_producto").val("Productos");
    $("#tipo_producto").selectpicker('refresh');

    $("#stock_unidad").val("0");
    $("#precio_unidad").val("0");

    $("#stock_blister").val("0");
    $("#precio_blister").val("0");

    $("#stock_caja").val("0");
    $("#precio_caja").val("0");

    $("#stock_fardo").val("0");
    $("#precio_fardo").val("0");

    $("#stock_sacos").val("0");
    $("#precio_sacos").val("0");

    $("#stock_paquete").val("0");
    $("#precio_paquete").val("0");

    $("#stock_07").val("0");
    $("#precio_07").val("0");

    $("#stock_08").val("0");
    $("#precio_08").val("0");

    $("#stock_09").val("0");
    $("#precio_09").val("0");

    $("#stock_10").val("0");
    $("#precio_10").val("0");

    $("#stock_11").val("0");
    $("#precio_11").val("0");

    $("#stock_12").val("0");
    $("#precio_12").val("0");

    $("#stock_13").val("0");
    $("#precio_13").val("0");

    $("#stock_14").val("0");
    $("#precio_14").val("0");

    $("#stock_15").val("0");
    $("#precio_15").val("0");

    $("#stock_16").val("0");
    $("#precio_16").val("0");

    $("#stock_17").val("0");
    $("#precio_17").val("0");

    $("#stock_18").val("0");
    $("#precio_18").val("0");

    $("#stock_19").val("0");
    $("#precio_19").val("0");

    $("#stock_20").val("0");
    $("#precio_20").val("0");

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
    load();
    var filtro_estado = $("#filtro_estado").val();

    tabla = $('#tbllistado').DataTable({
        "processing": true, // 🔥 IMPORTANTE (nuevo nombre)
        "serverSide": true, // 🔥 CLAVE para paginación real

        dom: 'Bfrtip',

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

        "fnDrawCallback": function (oSettings) {
            Swal.close();
        },

        "ajax": {
            url: '../ajax/articulo.php?op=listar',
            type: "GET",
            data: function (d) {
                d.estadofiltro = $("#filtro_estado").val();
            },
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },

        "destroy": true, // 🔥 reemplaza bDestroy
        "pageLength": 20, // 🔥 reemplaza iDisplayLength

        "order": [[1, "desc"]]
    });
}

function verDetallesArticulo(idarticulo, tipo_producto,
    aplica_comision, precio_ventaNocturno, descuento_porcentaje,
    precio_descuento, precio_rango1, precio_rango1_Dos, precio_rango2,
    precio_rango2_Dos, precio_rango3, precio_rango3_Dos, precio_rango1_Mecanico,
    precio_rango2_MecanicoDos, precio_rango3_MecanicoTres,
    precio_rango1_Distribuidor, precio_rango2_DistribuidorDos,
    precio_rango3_DistribuidorTres, precio_rango1_Mayorista, precio_rango2_MayoristaDos,
    precio_rango3_MayoristaTres, stock_unidad, precio_unidad, stock_blister,
    precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos,
    precio_sacos, stock_paquete, precio_paquete) {

    // Crear el contenido HTML de la tabla
    let contenidoTabla = `
     <div class="table-responsive">
         <table class="table table-bordered table-striped">
             <thead>
                 <tr>
                     <th colspan="2" class="text-center">Detalles del Artículo</th>
                 </tr>
             </thead>
             <tbody>
                 <tr>
                     <td><strong>Tipo de Producto</strong></td>
                     <td>${tipo_producto}</td>
                 </tr>
                 <tr>
                     <td><strong>Aplica Comisión</strong></td>
                     <td>${aplica_comision}</td>
                 </tr>
                 <tr>
                     <td><strong>Precio Venta Nocturno</strong></td>
                     <td>${precio_ventaNocturno}</td>
                 </tr>
                 <tr>
                     <td><strong>Descuento (%)</strong></td>
                     <td>${descuento_porcentaje}</td>
                 </tr>
                 <tr>
                     <td><strong>Precio con Descuento</strong></td>
                     <td>${precio_descuento}</td>
                 </tr>
                 <tr class="info">
                     <td colspan="2"><strong>Precios por Rango</strong></td>
                 </tr>
                 <tr>
                     <td>Rango 1</td>
                     <td>${precio_rango1}</td>
                 </tr>
                 <tr>
                     <td>Rango 1 Dos</td>
                     <td>${precio_rango1_Dos}</td>
                 </tr>
                 <tr>
                     <td>Rango 2</td>
                     <td>${precio_rango2}</td>
                 </tr>
                 <tr>
                     <td>Rango 2 Dos</td>
                     <td>${precio_rango2_Dos}</td>
                 </tr>
                 <tr>
                     <td>Rango 3</td>
                     <td>${precio_rango3}</td>
                 </tr>
                 <tr>
                     <td>Rango 3 Dos</td>
                     <td>${precio_rango3_Dos}</td>
                 </tr>
                 <tr class="info">
                     <td colspan="2"><strong>Precios Mecánico</strong></td>
                 </tr>
                 <tr>
                     <td>Rango 1</td>
                     <td>${precio_rango1_Mecanico}</td>
                 </tr>
                 <tr>
                     <td>Rango 2</td>
                     <td>${precio_rango2_MecanicoDos}</td>
                 </tr>
                 <tr>
                     <td>Rango 3</td>
                     <td>${precio_rango3_MecanicoTres}</td>
                 </tr>
                 <tr class="info">
                     <td colspan="2"><strong>Precios Distribuidor</strong></td>
                 </tr>
                 <tr>
                     <td>Rango 1</td>
                     <td>${precio_rango1_Distribuidor}</td>
                 </tr>
                 <tr>
                     <td>Rango 2</td>
                     <td>${precio_rango2_DistribuidorDos}</td>
                 </tr>
                 <tr>
                     <td>Rango 3</td>
                     <td>${precio_rango3_DistribuidorTres}</td>
                 </tr>
                 <tr class="info">
                     <td colspan="2"><strong>Precios Mayorista</strong></td>
                 </tr>
                 <tr>
                     <td>Rango 1</td>
                     <td>${precio_rango1_Mayorista}</td>
                 </tr>
                 <tr>
                     <td>Rango 2</td>
                     <td>${precio_rango2_MayoristaDos}</td>
                 </tr>
                 <tr>
                     <td>Rango 3</td>
                     <td>${precio_rango3_MayoristaTres}</td>
                 </tr>
                 <tr class="info">
                     <td colspan="2"><strong>Stock y Precios por Unidad</strong></td>
                 </tr>
                 <tr>
                     <td>Stock Unidad</td>
                     <td>${stock_unidad}</td>
                 </tr>
                 <tr>
                     <td>Precio Unidad</td>
                     <td>${precio_unidad}</td>
                 </tr>
                 <tr>
                     <td>Stock Blister</td>
                     <td>${stock_blister}</td>
                 </tr>
                 <tr>
                     <td>Precio Blister</td>
                     <td>${precio_blister}</td>
                 </tr>
                 <tr>
                     <td>Stock Caja</td>
                     <td>${stock_caja}</td>
                 </tr>
                 <tr>
                     <td>Precio Caja</td>
                     <td>${precio_caja}</td>
                 </tr>
                 <tr>
                     <td>Stock Fardo</td>
                     <td>${stock_fardo}</td>
                 </tr>
                 <tr>
                     <td>Precio Fardo</td>
                     <td>${precio_fardo}</td>
                 </tr>
                 <tr>
                     <td>Stock Sacos</td>
                     <td>${stock_sacos}</td>
                 </tr>
                 <tr>
                     <td>Precio Sacos</td>
                     <td>${precio_sacos}</td>
                 </tr>
                 <tr>
                     <td>Stock Paquete</td>
                     <td>${stock_paquete}</td>
                 </tr>
                 <tr>
                     <td>Precio Paquete</td>
                     <td>${precio_paquete}</td>
                 </tr>
             </tbody>
         </table>
     </div>
 `;

    // Mostrar la tabla en un modal de Bootstrap
    bootbox.dialog({
        title: 'Detalles del Artículo',
        message: contenidoTabla,
        size: 'large',
        buttons: {
            ok: {
                label: 'Cerrar',
                className: 'btn-primary'
            }
        }
    });
}

// 🔥 Convertir cualquier imagen a JPG antes de enviar
document.addEventListener("DOMContentLoaded", function () {

    const inputImagen = document.querySelector('input[name="imagen"]');

    if (!inputImagen) return;

    inputImagen.addEventListener("change", function (e) {

        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = function (event) {

            const img = new Image();

            img.onload = function () {

                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;

                const ctx = canvas.getContext('2d');

                // Fondo blanco (evita fondo negro en PNG)
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                ctx.drawImage(img, 0, 0);

                canvas.toBlob(function (blob) {

                    const newFile = new File(
                        [blob],
                        Date.now() + ".jpg",
                        { type: "image/jpeg" }
                    );

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(newFile);

                    inputImagen.files = dataTransfer.files;

                }, "image/jpeg", 0.9);

            };

            img.src = event.target.result;
        };

        reader.readAsDataURL(file);

    });

});

//Función para guardar o editar 

function guardaryeditar(e) {
    e.preventDefault(); // Evitar la acción predeterminada del evento

    // Obtener valores de los campos
    const nombre = $("#nombre").val().trim();
    const descripcion = $("#descripcion").val().trim();
    const descripcion2 = $("#descripcion_2").val().trim();
    const codigo = $("#codigo").val().trim();
    const precio_compra = parseFloat($("#precio_compra").val().trim());
    const precio_venta = parseFloat($("#precio_venta").val().trim());
    const stockminimo = parseFloat($("#stockminimo").val().trim());
    //const regexNoSpecialChars = /^[a-zA-Z0-9\s\/\-,.%+]+$/;
    const regexNoSpecialChars = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s,.%+\/-]+$/;



    // Validar tipo de ganancia
    const pocentajeganacia = document.getElementById("pocentaje_ganacia").value;

    if (pocentajeganacia === "" || pocentajeganacia < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No puede dejar su Ganacia vacio ni menor a 0.',
        });
        return false;
    }



    // Validaciones
    if (nombre === "" || descripcion === "" || descripcion2 === "" || stockminimo === ""
        || codigo === "" || precio_compra === "" || precio_venta === "" || codigo === "") {
        Swal.fire({
            title: "Error",
            text: "Los campos Nombre, Descripción y Descripción 2, Stock Minimo, Codigo son obligatorios.",
            icon: "error",
        });
        return;
    }

    if (!regexNoSpecialChars.test(nombre)) {
        Swal.fire({
            title: "Error",
            text: "El campo Nombre no debe contener caracteres especiales.",
            icon: "error",
        });
        return;
    }

    if (!regexNoSpecialChars.test(descripcion)) {
        Swal.fire({
            title: "Error",
            text: "El campo Descripción no debe contener caracteres especiales.",
            icon: "error",
        });
        return;
    }

    if (!regexNoSpecialChars.test(descripcion2)) {
        Swal.fire({
            title: "Error",
            text: "El campo Descripción 2 no debe contener caracteres especiales.",
            icon: "error",
        });
        return;
    }

    if (!regexNoSpecialChars.test(codigo)) {
        Swal.fire({
            title: "Error",
            text: "El campo Código no debe contener caracteres especiales.",
            icon: "error",
        });
        return;
    }

    if (codigo.length >= 18) {
        Swal.fire({
            title: "Error",
            text: "El campo Código debe tener menos de 18 caracteres.",
            icon: "error",
        });
        return;
    }

    // Validar que todos los demás campos requeridos no estén vacíos
    const requiredFields = ["facturar_cero", "idcategoria", "idsubcategoria", "aplica_comision", "stock", "stockminimo", "precio_compra", "ganacia_articulo", "precio_venta", "precio_ventaNocturno", "descuento_porcentaje", "precio_descuento"];
    for (const field of requiredFields) {
        if ($(`#${field}`).val().trim() === "") {
            Swal.fire({
                title: "Error",
                text: `El campo ${field} es obligatorio.`,
                icon: "error",
            });
            return;
        }
    }

    // Validar precios
    if (precio_compra <= 0) {
        Swal.fire({
            title: "Error",
            text: "El campo Precio Compra no puede ser menor a cero.",
            icon: "error",
        });
        return;
    }

    if (precio_venta <= 0) {
        Swal.fire({
            title: "Error",
            text: "El campo Precio Venta no puede ser menor a cero.",
            icon: "error",
        });
        return;
    }

    // Llamar a la validación de presentaciones
    if (!validarPresentaciones()) {
        return; // Detener si la validación falla
    }
    // Validar rangos antes de continuar con las demás validaciones
    /*if (!validarRangos()) {
        return;
    }*/




    // Si pasa las validaciones, proceder con el envío del formulario
    const formData = new FormData($("#formulario")[0]);
    load();
    $.ajax({
        url: "../ajax/articulo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            Swal.close();
            //console.log(datos);
            Swal.fire({
                title: "Mensaje!",
                text: datos,
                icon: "success",
                timer: 3000, // 2 segundos
                timerProgressBar: true,
                willClose: () => {
                    Swal.close();
                    window.location.reload();
                },
            });
        },
    });
}


function validarRangos() {
    // Validar rangos principales
    const rangos = [
        { valor: $("#precio_rango1").val(), nombre: "Rango 1" },
        { valor: $("#precio_rango1_Dos").val(), nombre: "Rango 1 Dos" },
        { valor: $("#precio_rango2").val(), nombre: "Rango 2" },
        { valor: $("#precio_rango2_Dos").val(), nombre: "Rango 2 Dos" },
        { valor: $("#precio_rango3").val(), nombre: "Rango 3" },
        { valor: $("#precio_rango3_Dos").val(), nombre: "Rango 3 Dos" }
    ];

    for (const rango of rangos) {
        const valor = parseFloat(rango.valor);
        if (valor > 0 && valor <= 3) {
            Swal.fire({
                title: "Error",
                text: `El ${rango.nombre} debe ser mayor a 3 o igual a 0`,
                icon: "error"
            });
            return false;
        }
    }

    // Validar rangos de Taller Mecánico
    const rangosMecanico = [
        { valor: $("#precio_rango1_Mecanico").val(), nombre: "Taller Mecánico Rango 1" },
        { valor: $("#precio_rango2_MecanicoDos").val(), nombre: "Taller Mecánico Rango 2" },
        { valor: $("#precio_rango3_MecanicoTres").val(), nombre: "Taller Mecánico Rango 3" }
    ];

    for (const rango of rangosMecanico) {
        const valor = parseFloat(rango.valor);
        if (valor > 0 && valor <= 3) {
            Swal.fire({
                title: "Error",
                text: `El ${rango.nombre} debe ser mayor a 3 o igual a 0`,
                icon: "error"
            });
            return false;
        }
    }

    // Validar rangos de Distribuidor
    const rangosDistribuidor = [
        { valor: $("#precio_rango1_Distribuidor").val(), nombre: "Distribuidor Rango 1" },
        { valor: $("#precio_rango2_DistribuidorDos").val(), nombre: "Distribuidor Rango 2" },
        { valor: $("#precio_rango3_DistribuidorTres").val(), nombre: "Distribuidor Rango 3" }
    ];

    for (const rango of rangosDistribuidor) {
        const valor = parseFloat(rango.valor);
        if (valor > 0 && valor <= 3) {
            Swal.fire({
                title: "Error",
                text: `El ${rango.nombre} debe ser mayor a 3 o igual a 0`,
                icon: "error"
            });
            return false;
        }
    }

    // Validar rangos de Mayorista
    const rangosMayorista = [
        { valor: $("#precio_rango1_Mayorista").val(), nombre: "Mayorista Rango 1" },
        { valor: $("#precio_rango2_MayoristaDos").val(), nombre: "Mayorista Rango 2" },
        { valor: $("#precio_rango3_MayoristaTres").val(), nombre: "Mayorista Rango 3" }
    ];


    for (const rango of rangosMayorista) {
        const valor = parseFloat(rango.valor);
        if (valor > 0 && valor <= 3) {
            Swal.fire({
                title: "Error",
                text: `El ${rango.nombre} debe ser mayor a 3 o igual a 0`,
                icon: "error"
            });
            return false;
        }
    }

    return true;
}


function validarPresentaciones() {
    // Lista de IDs de los campos
    const campos = [
        { stockId: "stock_unidad", precioId: "precio_unidad" },
        { stockId: "stock_blister", precioId: "precio_blister" },
        { stockId: "stock_caja", precioId: "precio_caja" },
        { stockId: "stock_fardo", precioId: "precio_fardo" },
        { stockId: "stock_sacos", precioId: "precio_sacos" },
        { stockId: "stock_paquete", precioId: "precio_paquete" },
        { stockId: "stock_07", precioId: "precio_07" },
        { stockId: "stock_08", precioId: "precio_08" },
        { stockId: "stock_09", precioId: "precio_09" },
        { stockId: "stock_10", precioId: "precio_10" },
        { stockId: "stock_11", precioId: "precio_11" },
        { stockId: "stock_12", precioId: "precio_12" },
        { stockId: "stock_13", precioId: "precio_13" },
        { stockId: "stock_14", precioId: "precio_14" },
        { stockId: "stock_15", precioId: "precio_15" },
        { stockId: "stock_16", precioId: "precio_16" },
        { stockId: "stock_17", precioId: "precio_17" },
        { stockId: "stock_18", precioId: "precio_18" },
        { stockId: "stock_19", precioId: "precio_19" },
        { stockId: "stock_20", precioId: "precio_20" }
    ];

    let tieneParValido = false;

    for (const campo of campos) {
        let stockInput = document.getElementById(campo.stockId);
        let precioInput = document.getElementById(campo.precioId);

        let stock = parseFloat(stockInput.value) || 0;
        let precio = parseFloat(precioInput.value) || 0;

        // 🔹 Corregir negativos o vacíos → poner 0
        if (stock < 0 || stockInput.value.trim() === "") {
            stock = 0;
            stockInput.value = 0;
        }
        if (precio < 0 || precioInput.value.trim() === "") {
            precio = 0;
            precioInput.value = 0;
        }

        // Guardar valores corregidos
        stockInput.value = stock;
        precioInput.value = precio;

        // Validar si hay al menos un par válido
        if (stock > 0 && precio > 0) {
            tieneParValido = true;
        }
    }

    if (!tieneParValido) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Al menos una presentación debe tener un stock y un precio mayores a 0.',
        });
        return false;
    }

    return true; // ✅ Validación exitosa
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
    load();
    $.post("../ajax/articulo.php?op=mostrar", { idarticulo: idarticulo }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        Swal.close();
        $("#idarticulo").val(data.idarticulo);
        $("#nombre").val(data.nombre);

        $("#facturar_cero").val(data.facturar_cero);
        $('#facturar_cero').selectpicker('refresh');

        $("#idcategoria").val(data.idcategoria);
        $('#idcategoria').selectpicker('refresh');

        $("#idempresa").val(data.idempresa);
        console.log("data.idempresa ", data.idempresa);
        $('#idempresa').selectpicker('refresh');

        // Creamos una promesa para cargar las subcategorías
        new Promise((resolve) => {
            $("#idcategoria").trigger('change');

            // Guardamos la función original de éxito
            const originalSuccess = $("#idcategoria").data('success');

            // Reemplazamos temporalmente el success handler
            $.ajax({
                url: "../ajax/articulo.php?op=selectSubcategoria",
                type: "POST",
                data: { idcategoria: data.idcategoria },
                success: function (r) {
                    $("#idsubcategoria").html(r);
                    $('#idsubcategoria').selectpicker('refresh');
                    $("#idsubcategoria").val(data.idsubcategoria);
                    $('#idsubcategoria').selectpicker('refresh');
                    resolve();
                }
            });
        });

        $("#descripcion").val(data.descripcion);
        $("#descripcion_2").val(data.descripcion_2);

        $("#crearArticuloSucursal").val(data.crearArticuloSucursal);
        $("#crearArticuloSucursal").selectpicker('refresh');

        $("#aplica_comision").val(data.aplica_comision);
        $("#aplica_comision").selectpicker('refresh');

        $("#stock").val(data.stock);
        $("#stockminimo").val(data.stockminimo);
        $("#stockmaximo").val(data.stockmaximo);

        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/articulos/" + data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#codigo").val(data.codigo);
        $("#codigo_sku").val(data.codigo_sku);

        $("#precio_compra").val(data.precio_compra);
        $("#ganacia_articulo").val(data.ganacia_articulo);

        $("#pocentaje_ganacia").val(data.pocentaje_ganacia);


        $("#producto_consignacion").val(data.producto_consignacion);
        $('#producto_consignacion').selectpicker('refresh');

        $("#aplica_impuestos").val(data.aplica_impuestos);
        $('#aplica_impuestos').selectpicker('refresh');

        $("#precio_venta").val(data.precio_venta);

        $("#precio_ventaNocturno").val(data.precio_ventaNocturno);

        $("#descuento_porcentaje").val(data.descuento_porcentaje);

        $("#precio_descuento").val(data.precio_descuento);

        $("#precio_rango1").val(data.precio_rango1);
        $("#precio_rango1_Dos").val(data.precio_rango1_Dos);
        $("#precio_rango2").val(data.precio_rango2);
        $("#precio_rango2_Dos").val(data.precio_rango2_Dos);
        $("#precio_rango3").val(data.precio_rango3);
        $("#precio_rango3_Dos").val(data.precio_rango3_Dos);

        $("#precio_rango1_Mecanico").val(data.precio_rango1_Mecanico);
        $("#precio_rango2_MecanicoDos").val(data.precio_rango2_MecanicoDos);
        $("#precio_rango3_MecanicoTres").val(data.precio_rango3_MecanicoTres);

        $("#precio_rango1_Distribuidor").val(data.precio_rango1_Distribuidor);
        $("#precio_rango2_DistribuidorDos").val(data.precio_rango2_DistribuidorDos);
        $("#precio_rango3_DistribuidorTres").val(data.precio_rango3_DistribuidorTres);

        $("#precio_rango1_Mayorista").val(data.precio_rango1_Mayorista);
        $("#precio_rango2_MayoristaDos").val(data.precio_rango2_MayoristaDos);
        $("#precio_rango3_MayoristaTres").val(data.precio_rango3_MayoristaTres);

        $("#tipo_producto").val(data.tipo_producto);
        $("#tipo_producto").selectpicker('refresh');


        $("#stock_unidad").val(data.stock_unidad);
        $("#precio_unidad").val(data.precio_unidad);

        $("#stock_blister").val(data.stock_blister);
        $("#precio_blister").val(data.precio_blister);

        $("#stock_caja").val(data.stock_caja);
        $("#precio_caja").val(data.precio_caja);

        $("#stock_fardo").val(data.stock_fardo);
        $("#precio_fardo").val(data.precio_fardo);

        $("#stock_sacos").val(data.stock_sacos);
        $("#precio_sacos").val(data.precio_sacos);

        $("#stock_paquete").val(data.stock_paquete);
        $("#precio_paquete").val(data.precio_paquete);

        $("#stock_07").val(data.stock_07);
        $("#precio_07").val(data.precio_07);

        $("#stock_08").val(data.stock_08);
        $("#precio_08").val(data.precio_08);

        $("#stock_09").val(data.stock_09);
        $("#precio_09").val(data.precio_09);

        $("#stock_10").val(data.stock_10);
        $("#precio_10").val(data.precio_10);

        $("#stock_11").val(data.stock_11);
        $("#precio_11").val(data.precio_11);

        $("#stock_12").val(data.stock_12);
        $("#precio_12").val(data.precio_12);

        $("#stock_13").val(data.stock_13);
        $("#precio_13").val(data.precio_13);

        $("#stock_14").val(data.stock_14);
        $("#precio_14").val(data.precio_14);

        $("#stock_15").val(data.stock_15);
        $("#precio_15").val(data.precio_15);

        $("#stock_16").val(data.stock_16);
        $("#precio_16").val(data.precio_16);

        $("#stock_17").val(data.stock_17);
        $("#precio_17").val(data.precio_17);

        $("#stock_18").val(data.stock_18);
        $("#precio_18").val(data.precio_18);

        $("#stock_19").val(data.stock_19);
        $("#precio_19").val(data.precio_19);

        $("#stock_20").val(data.stock_20);
        $("#precio_20").val(data.precio_20);

        $("#precio_activo_si_no").val(data.precio_activado);
        $('#precio_activo_si_no').selectpicker('refresh');

        generarbarcode();

    })
}

function actualizarStock(idarticuloxsucursal, idarticulo, nuevoStock) {
    // console.log(idarticuloxsucursal, idarticulo, nuevoStock);
    bootbox.confirm("¿Está seguro de actualizar el stock del artículo?", function (result) {
        if (result) {
            $.post("../ajax/articulo.php?op=actualizarStock", {
                idarticuloxsucursal: idarticuloxsucursal,
                idarticulo: idarticulo,
                stock: nuevoStock
            }, function (e) {
                Swal.fire({
                    title: 'Mensaje!',
                    text: e,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    willClose: () => {
                        tabla.ajax.reload();
                    }
                });
            });
        }
    });
}


//Función para desactivar registros
function desactivar(idarticulo) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Está Seguro de desactivar el Artículo?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            load();
            $.post("../ajax/articulo.php?op=desactivar", { idarticulo: idarticulo }, function (e) {
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
function activar(idarticulo) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Está Seguro de activar el Artículo?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            load();
            $.post("../ajax/articulo.php?op=activar", { idarticulo: idarticulo }, function (e) {
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



//función para generar el código de barras
function generarbarcode() {
    codigo = $("#codigo").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
}




//Función para imprimir el Código de barras
function imprimir() {
    $("#print").printArea();
}


function imprimiCarta1() {
    const codigo = $("#codigo").val();
    const nombre = $("#descripcion").val(); // Asumiendo que tienes un input con id "nombre"
    const pv = $("#precio_venta").val(); // Asumiendo que tienes un input con id "pv"

    const url = "../reportes/rptcodigobarras.php?id=" + codigo +
        "&nombre=" + encodeURIComponent(nombre) +
        "&pv=" + encodeURIComponent(pv);
    abrirVentanaetiqueta(url);
}

function imprimiCarta2() {

    const codigo = $("#codigo").val();
    const nombre = $("#descripcion").val(); // Asumiendo que tienes un input con id "nombre"
    const pv = $("#precio_venta").val(); // Asumiendo que tienes un input con id "pv"    
    //const url = "../reportes/rptcodigobarras1.php?id=" + $("#codigo").val();
    const url = "../reportes/rptcodigobarras1.php?id=" + codigo +
        "&nombre=" + encodeURIComponent(nombre) +
        "&pv=" + encodeURIComponent(pv);
    abrirVentanaetiqueta(url);
}

function abrirVentanaetiqueta(url) {
    // Tamaño deseado para la ventana (ajústalo según tus necesidades)
    var ancho = 800;
    var alto = 600;

    // Calcular la posición central de la pantalla
    var left = (screen.width / 2) - (ancho / 2);
    var top = (screen.height / 2) - (alto / 2);

    // Abrir la ventana con el tamaño y posición especificados
    window.open(url, '_blank', 'width=' + ancho + ',height=' + alto + ',left=' + left + ',top=' + top);
}


function etiqueta() {
    window.open("../reportes/imprimirEtiquetav1.php?id=" + $("#codigo").val() +
        "&id2=" + $("#num_codigos").val() +
        "&id3=" + $("#precio_venta").val() +
        "&id4=" + $("#nombre").val() +
        "&id5=" + $("#idarticulo").val());
}

function imprimir2() {
    $("#print").show();
    $("#barcode").css({
        "transform": "rotate(90deg)",
        "transform-origin": "left bottom"
    });
    $("#print").printArea();

    $("#barcode").css({
        "transform": "none"
    });

    $("#print").hide();
}

function imprimirUno() {
    window.open("../reportes/imprimirUno.php?id=" + $("#codigo").val() +
        "&id2=" + $("#num_codigos").val() +
        "&id3=" + $("#precio_venta").val() +
        "&id4=" + $("#nombre").val() +
        "&id5=" + $("#idarticulo").val());
}

function imprimirUno1() {
    window.open("../reportes/imprimirUno1.php?id=" + $("#codigo").val() +
        "&id2=" + $("#num_codigos").val() +
        "&id3=" + $("#precio_venta").val() +
        "&id4=" + $("#nombre").val() +
        "&id5=" + $("#idarticulo").val());
}

function cambiarModoCalculo() {
    const modo = document.getElementById("modo_calculo").value;

    if (modo === "porcentaje") {
        // Habilitar % Ganancia, Deshabilitar P.V
        document.getElementById("pocentaje_ganacia").readOnly = false;
        document.getElementById("precio_venta").readOnly = true;
    } else {
        // Habilitar P.V, Deshabilitar % Ganancia
        document.getElementById("pocentaje_ganacia").readOnly = true;
        document.getElementById("precio_venta").readOnly = false;
    }

    // Recalcular con el modo seleccionado
    mostrarprecioventa();
}

function mostrarprecioventa() {
    const modo = document.getElementById("modo_calculo").value;
    const precioCompra = parseFloat(document.getElementById("precio_compra").value) || 0;

    let porcentajeGanancia = 0;
    let ganancia = 0;
    let precioVenta = 0;

    if (modo === "porcentaje") {
        // Calcular P.V basado en % ganancia iterando desde P.C
        porcentajeGanancia = parseFloat(document.getElementById("pocentaje_ganacia").value) || 0;
        ganancia = precioCompra * (porcentajeGanancia / 100);
        precioVenta = precioCompra + ganancia;

        // Asignar P.V
        document.getElementById("precio_venta").value = precioVenta.toFixed(2);
    } else {
        // Calcular % ganancia basado en P.C y P.V libre
        precioVenta = parseFloat(document.getElementById("precio_venta").value) || 0;

        if (precioCompra > 0) {
            ganancia = precioVenta - precioCompra;
            porcentajeGanancia = (ganancia / precioCompra) * 100;
        } else {
            ganancia = precioVenta; // is 100% margin technically
            porcentajeGanancia = (precioVenta > 0) ? 100 : 0;
        }

        // Evitar % negativos artificiales en la UI o marcarlos
        // Asignar %
        document.getElementById("pocentaje_ganacia").value = porcentajeGanancia.toFixed(2);
    }

    // Asignar ganancia Q
    document.getElementById("ganacia_articulo").value = ganancia.toFixed(2);

    // Valores extra que ya usabas
    $("#precio_unidad").val(precioVenta.toFixed(2));
    $("#stock_unidad").val(1);
}

$("#tipo_producto").on("change", validarTipoProducto);

function validarTipoProducto() {
    const tipo = $("#tipo_producto").val();

    if (tipo === "Servicios" || tipo === "Combos") {
        $("#stock").val(0).prop("disabled", true);
        $("#stockminimo").val(0).prop("disabled", true);
        $("#stockmaximo").val(0).prop("disabled", true);
    } else {
        $("#stock").val("").prop("disabled", false);
        $("#stockminimo").val("").prop("disabled", false);
        $("#stockmaximo").val("").prop("disabled", false);
    }
}





init();