var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    $('#MenuAlmacen').addClass("treeview active");
    $('#CombosCrear').addClass("active");

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/ingreso_produccion.php?op=selectMateriaPrima", function (r) {

        let productoOptions = '<option value="">Seleccione un Combo</option>' + r;
        $("#idproducto").html(productoOptions);
        $("#idcuenta23").html(r);
        $('#idproducto').selectpicker('refresh');
    });

    $('#idproducto').change(function () {
        $("#idcuenta23").val($(this).val())
        var resprecio_compra = $("#idcuenta23 option:selected").attr("data-precio_compra");
        var resprecio_venta = $("#idcuenta23 option:selected").attr("data-precio_venta");

        $("#precio_compraProducto").val(resprecio_compra);
        $("#precio_ventaProducto").val(resprecio_venta);

    });

}

//Función limpiar
function limpiar() {
    $("#idproducto").val("");
    $("#cantidad_materia").val("");

    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);

    $("#idproduccion").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        listarArticulos();

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles = 0;
        $("#btnAgregarArt").show();
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
                url: '../ajax/ingreso_produccion.php?op=listar',
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


//Función ListarArticulos
function listarArticulos() {
    tabla = $('#tblarticulos').dataTable(
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
                url: '../ajax/ingreso_produccion.php?op=listarArticulos',
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
//Función para guardar o editar

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);

    var detalles = []; // creo un array para guardar los detalles vacio
    $("tr.filas").each(function () {
        var fila = $(this);
        detalles.push({
            idarticulo: fila.find("input[name='idarticulo[]']").val(),
            cantidad: fila.find("input[name='cantidad[]']").val(),
            precio_compra: fila.find("input[name='precio_compra[]']").val(),
            precio_venta: fila.find("input[name='precio_venta[]']").val(),
            subtotal: fila.find("input[name='subtotal[]']").val(),
            tipo_item: fila.find("select[name='tipo_item[]']").val() // ✅ cambio aquí
        });
    });

    var formData = new FormData($("#formulario")[0]);
    formData.append("idproduccion", $("#idproduccion").val());//Agrega al FormData 
    formData.append("idproducto", $("#idproducto").val());//Agrega al FormData
    formData.append("fecha_hora", $("#fecha_hora").val());//Agrega al FormData
    formData.append("precio_compraProducto", $("#precio_compraProducto").val());//Agrega al FormData
    formData.append("ganacia_producto", $("#ganacia_producto").val());//Agrega al FormData
    formData.append("precio_ventaProducto", $("#precio_ventaProducto").val());//Agrega al FormData
    formData.append("subtotalprecioCompra", $("#subtotalprecioCompra").val());//Agrega al FormData 
    formData.append("detalles_json", JSON.stringify(detalles));/// Convierte el arreglo detalles a texto en formato JSON y lo envía como una sola variable llamada "detalles_json".


    $.ajax({
        url: "../ajax/ingreso_produccion.php?op=guardaryeditar",
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
}

function mostrar(idproduccion) {
    $.post("../ajax/ingreso_produccion.php?op=mostrar", { idproduccion: idproduccion }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        load();

        $("#idproduccion").val(data.idproduccion);
        $("#idproducto").val(data.idproducto);
        $("#idproducto").selectpicker('refresh');
        $("#fecha_hora").val(data.fecha);

        $("#precio_compraProducto").val(data.precio_compra);
        $("#ganacia_producto").val(data.ganacia_producto);
        $("#precio_ventaProducto").val(data.precio_venta);

        $("#subtotalprecioCompra").val(data.sub_total);

        obtenerdetalle(idproduccion);
    });


}

//Función para anular registros
function anular(idproduccion) {
    bootbox.confirm("¿Está Seguro de anular el ingreso?", function (result) {
        if (result) {
            $.post("../ajax/ingreso_produccion.php?op=anular", { idproduccion: idproduccion }, function (e) {
                console.log(e);
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
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


//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();


function agregarDetalle(idarticulo, articulo, precio_compra, precio_venta) {
    var cantidad = 1;
    var subtotal = 0;
    //var precio_compra=1;
    //var precio_venta=1;

    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input type="number" class="form-control" onchange="modificarSubototales()" step="any" style="width:75px"  name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
            '<td><input type="number" class="form-control" onchange="modificarSubototales()" step="any" style="width:75px" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td><input type="number" class="form-control" onchange="modificarSubototales()" step="any" style="width:75px" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '"></td>' +
            '<td><input type="number" class="form-control" step="any" name="subtotal[]" style="width:75px" id="subtotal' + cont + '" value="' + subtotal + '"  readonly></td>' +
            '<td>' +
            '<select name="tipo_item[]" class="form-control input-sm" style="width: 120px; margin-top: 5px;">' +
            '<option value="Producto">Producto</option>' +
            '<option value="Extra">Extra</option>' +
            '<option value="Topping">Topping</option>' +
            '</select>' +
            '</td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        modificarSubototales();
        evaluar();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function modificarSubototales() {
    var cant = document.getElementsByName("cantidad[]");
    var precV = document.getElementsByName("precio_venta[]");
    var precC = document.getElementsByName("precio_compra[]");
    var subdes1 = document.getElementsByName("subtotal[]");




    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpPV = precV[i];
        var inpPC = precC[i];
        var inpS = subdes1[i];


        inpS.value = (inpC.value * inpPC.value);
        document.getElementsByName("subtotal[]")[i].innerHTML = parseFloat(inpS.value).toFixed(3);

    }

    calcularTotales();
}

function calcularTotales() {
    var sub = document.getElementsByName("subtotal[]");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        var valor = parseFloat(sub[i].textContent || sub[i].innerHTML); // Obtener el valor del subtotal como texto y convertirlo a número
        if (!isNaN(valor)) {
            total += valor;
        }
    }

    // console.log('subtotal'+total)

    $("#subtotalprecioCompra").val(total.toFixed(2)); // Asignar el valor formateado al campo de entrada
    $("#precio_compraProducto").val(total.toFixed(2)); // Asignar el valor formateado al campo de entrada 

    caculardescuento();
}

function caculardescuento() {
    var pc_producto = parseFloat($("#precio_compraProducto").val());
    var ganacia_p = parseFloat($("#ganacia_producto").val());

    // Calculamos el resultado y lo limitamos a 2 decimales
    var res = (pc_producto + ganacia_p).toFixed(2);

    // Asignamos el valor a #precio_descuento
    $("#precio_ventaProducto").val(res);
}


function obtenerdetalle(idproduccion) {
    $.post("../ajax/ingreso_produccion.php?op=obtenerdetalle", { idproduccion: idproduccion }, function (data) {

        data = JSON.parse(data);
        Swal.close()

        $.each(data, function (i, item) {
            agregarDetalle2(item.idarticulo, item.articulo, item.cantidad, item.precio_compra, item.precio_venta, item.subtotal, item.tipo_item);
        });
    })
}

function agregarDetalle2(idarticulo, articulo, cantidad, precio_compra, precio_venta, subtotal, tipo_item) {

    //var precio_compra=1;
    //var precio_venta=1;

    if (idarticulo != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input type="number" class="form-control" onchange="modificarSubototales()" step="any" style="width:75px"  name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
            '<td><input type="number" class="form-control" onchange="modificarSubototales()" step="any" style="width:75px" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td><input type="number" class="form-control" onchange="modificarSubototales()" step="any" style="width:75px" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '"></td>' +
            '<td><input type="number" class="form-control" step="any" name="subtotal[]" style="width:75px" id="subtotal' + cont + '" value="' + subtotal + '"  readonly></td>' +
            '<td>' +
            '<select name="tipo_item[]" class="form-control input-sm" style="width: 120px; margin-top: 5px;">' +
            '<option value="Producto" ' + (tipo_item === 'Producto' ? 'selected' : '') + '>Producto</option>' +
            '<option value="Extra" ' + (tipo_item === 'Extra' ? 'selected' : '') + '>Extra</option>' +
            '<option value="Topping" ' + (tipo_item === 'Topping' ? 'selected' : '') + '>Topping</option>' +
            '</select>' +
            '</td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        modificarSubototales();
        evaluar();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}



function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    }
    else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    // calcularTotales();
    detalles = detalles - 1;
    evaluar();
}

init();