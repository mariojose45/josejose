var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
    //Cargamos los items al select cliente
    $.post("../ajax/salida_pro_sucursal.php?op=selectSucursal", function (r) {
        var options = '<option value="">Seleccione una sucursal...</option>' + r;
        $("#idsucursal").html(options);
        $('#idsucursal').selectpicker('refresh');

        $("#idsucursalOrigen").html(options);
        $('#idsucursalOrigen').selectpicker('refresh');
    });

    // Bloquear seleccionar la misma sucursal en ambos selects
    $("#idsucursalOrigen").on("change", function () {
        var valorOrigen = $(this).val();
        var valorDestino = $("#idsucursal").val();

        // Habilitar todas las opciones en el select de destino primero
        $("#idsucursal option").prop("disabled", false);

        if (valorOrigen) {
            // Deshabilitar la sucursal seleccionada en el origen para el destino
            $("#idsucursal option[value='" + valorOrigen + "']").prop("disabled", true);
            // Si estaba seleccionada la misma, se limpia
            if (valorOrigen === valorDestino) {
                $("#idsucursal").val("");
            }
        }
        $('#idsucursal').selectpicker('refresh');
    });

    $("#idsucursal").on("change", function () {
        var valorDestino = $(this).val();
        var valorOrigen = $("#idsucursalOrigen").val();

        // Habilitar todas las opciones en el select de origen primero
        $("#idsucursalOrigen option").prop("disabled", false);

        if (valorDestino) {
            // Deshabilitar la sucursal seleccionada en el destino para el origen
            $("#idsucursalOrigen option[value='" + valorDestino + "']").prop("disabled", true);
            // Si estaba seleccionada la misma, se limpia
            if (valorDestino === valorOrigen) {
                $("#idsucursalOrigen").val("");
            }
        }
        $('#idsucursalOrigen').selectpicker('refresh');
    });
}

//Función limpiar   
function limpiar() {
    $("#idsucursal").val("");
    $("#idtraladosucursal").val("");

    $("#descripcion_salida_producto").val("");
    $(".filas").remove();

    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);

}

//Función mostrar formulario
function mostrarform(flag) {
    //limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        //  listarArticulos();

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
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

//Función Listar
function listar() {
    var fecha_inicio = $('#fecha_inicio').val();
    var fecha_fin = $('#fecha_fin').val();
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
                url: '../ajax/salida_pro_sucursal.php?op=listar',
                type: "get",
                data: {
                    fecha_inicio: fecha_inicio,
                    fecha_fin: fecha_fin
                },
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[1, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}


//Función ListarArticulos
function listarArticulos() {
    var idsucursalOrigen = $("#idsucursalOrigen").val();
    tabla = $('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/salida_pro_sucursal.php?op=listarArticulosVenta',
                type: "get",
                data: {
                    idsucursalOrigen: idsucursalOrigen
                },
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

function agruparDatos() {
    const datos = {
        articulos: {
            idarticulo: [],
            cantidadpresentacion: [],
            cantidad: [],
            totalcantidadpresentacion: [],
            presentacion: [],
            descripcion_detalle: [],
            precio_venta: []
        }
    };

    $('#detalles .filas').each(function () {
        const idarticulo = $(this).find('input[name="idarticulo[]"]').val();
        const cantidadpresentacion = $(this).find('input[name="cantidadpresentacion[]"]').val();
        const cantidad = $(this).find('input[name="cantidad[]"]').val();
        const totalcantidadpresentacion = $(this).find('input[name="totalcantidadpresentacion[]"]').val();
        const presentacion = $(this).find('select[name="presentacion[]"]').val();
        const descripcion_detalle = $(this).find('input[name="descripcion_detalle[]"]').val();
        const precio_venta = $(this).find('input[name="precio_venta[]"]').val();

        datos.articulos.idarticulo.push(idarticulo);
        datos.articulos.cantidadpresentacion.push(cantidadpresentacion);
        datos.articulos.cantidad.push(cantidad);
        datos.articulos.totalcantidadpresentacion.push(totalcantidadpresentacion);
        datos.articulos.presentacion.push(presentacion);
        datos.articulos.descripcion_detalle.push(descripcion_detalle);
        datos.articulos.precio_venta.push(precio_venta);
    });

    const datosJSON = JSON.stringify(datos);

    localStorage.setItem('datosArticulosS', datosJSON);
    //console.log(datosJSON);
}

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);

    //////validacion de stock
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var sub = document.getElementsByName("subtotal");
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]"));
    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));
    var stock = (document.getElementsByName("stock_inventario[]"));

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpS = sub[i];
        var inpTpres = tprese[i];
        var inpCpre = cantpre[i];
        var inpStock = stock[i];

        if (parseFloat(inpC.value) == 0 || parseFloat(inpC.value) == null) {
            inpC.value = 1;
        } else {
            inpC.value = inpC.value;
        }
        if (parseFloat(inpC.value) > parseFloat(inpStock.value)) {
            Swal.fire({
                title: 'Mensaje!',
                text: "La cantidad no puede ser mayor al stock",
                icon: 'warning',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
            return;
        }

        inpS.value = (inpC.value * inpP.value);
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;
    }

    calcularTotales();
    //////
    agruparDatos();
    let datosArticulosS = JSON.parse(localStorage.getItem('datosArticulosS'));
    var formData = new FormData($("#formulario")[0]);
    formData.append("datosArticulosS", JSON.stringify(datosArticulosS));
    load();
    $.ajax({
        url: "../ajax/salida_pro_sucursal.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //console.log("SALIDA:: ", datos);
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




//Función para anular registros
function anular(idtraladosucursal) {
    bootbox.prompt({
        title: "¿Está Seguro de anular la salida de producto de sucursal? Ingrese la contraseña:",
        inputType: 'password',
        callback: function (result) {
            if (result !== null) {
                if (result === 'admin123') {
                    load();
                    $.post("../ajax/salida_pro_sucursal.php?op=anular", { idtraladosucursal: idtraladosucursal }, function (e) {
                        Swal.fire({
                            title: 'Mensaje!',
                            text: e,
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                Swal.close();
                                window.location.reload();
                            }
                        });
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Contraseña incorrecta',
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            }
        }
    });
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();


function agregarDetalleCanta(idarticulo, articulo, descripcion, precio_venta, stock, descuento_porcentaje,
    nombre_01, stock_unidad, precio_unidad,
    nombre_02, stock_blister, precio_blister,
    nombre_03, stock_caja, precio_caja,
    nombre_04, stock_fardo, precio_fardo,
    nombre_05, stock_sacos, precio_sacos,
    nombre_06, stock_paquete, precio_paquete,
    nombre_07, stock_07, precio_07,
    nombre_08, stock_08, precio_08,
    nombre_09, stock_09, precio_09,
    nombre_10, stock_10, precio_10,
    nombre_11, stock_11, precio_11,
    nombre_12, stock_12, precio_12,
    nombre_13, stock_13, precio_13,
    nombre_14, stock_14, precio_14,
    nombre_15, stock_15, precio_15,
    nombre_16, stock_16, precio_16,
    nombre_17, stock_17, precio_17,
    nombre_18, stock_18, precio_18,
    nombre_19, stock_19, precio_19,
    nombre_20, stock_20, precio_20,
    facturar_cero, cantidad) {

    // Validar si el stock es negativo
    if (facturar_cero == 'NO') {
        if (parseInt(stock) < 0) {
            alert("No se puede agregar el artículo porque el stock es negativo");
            return;
        }
    }

    // Validar si la cantidad es mayor al stock disponible
    if (parseInt(cantidad) > parseInt(stock)) {
        alert("La cantidad ingresada es mayor al stock disponible");
        return;
    }

    if (cantidad == 0 || cantidad == null) {
        cantidad = 1;
    } else {
        cantidad = cantidad;
    }
    var precioventasucursalr = precio_venta;
    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;

    if (idarticulo != "") {
        var subtotal = cantidad * precio_venta;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" style="width:100px" value="' + idarticulo + '">' + articulo + ' ' + descripcion + '</td>' +
            '<td><input type="hidden" name="stock_inventario[]" style="width:100px" value="' + stock + '">' + stock + '</td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototales()"  type="number" step="any"  class="form-control"  id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                    <select class="form-control" style="width:150px" name="presentacion[]" id="presentacionselect`+ cont + `" 
                    onchange="presentacionoculatardatos(`+ cont + `,` + precio_venta + `,
                    '` + nombre_01 + `',` + stock_unidad + `,` + precio_unidad + `,
                    '` + nombre_02 + `',` + stock_blister + `,` + precio_blister + `,
                    '` + nombre_03 + `',` + stock_caja + `,` + precio_caja + `,
                    '` + nombre_04 + `',` + stock_fardo + `,` + precio_fardo + `,
                    '` + nombre_05 + `',` + stock_sacos + `,` + precio_sacos + `,
                    '` + nombre_06 + `',` + stock_paquete + `,` + precio_paquete + `,
                    '` + nombre_07 + `',` + stock_07 + `,` + precio_07 + `,
                    '` + nombre_08 + `',` + stock_08 + `,` + precio_08 + `,
                    '` + nombre_09 + `',` + stock_09 + `,` + precio_09 + `,
                    '` + nombre_10 + `',` + stock_10 + `,` + precio_10 + `,
                    '` + nombre_11 + `',` + stock_11 + `,` + precio_11 + `,
                    '` + nombre_12 + `',` + stock_12 + `,` + precio_12 + `,
                    '` + nombre_13 + `',` + stock_13 + `,` + precio_13 + `,
                    '` + nombre_14 + `',` + stock_14 + `,` + precio_14 + `,
                    '` + nombre_15 + `',` + stock_15 + `,` + precio_15 + `,
                    '` + nombre_16 + `',` + stock_16 + `,` + precio_16 + `,
                    '` + nombre_17 + `',` + stock_17 + `,` + precio_17 + `,
                    '` + nombre_18 + `',` + stock_18 + `,` + precio_18 + `,
                    '` + nombre_19 + `',` + stock_19 + `,` + precio_19 + `,
                    '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)" >
                        `+ (parseFloat(stock_unidad) > 0 ? `<option value="${nombre_01}">${nombre_01}</option>` : ``) + `
                        `+ (parseFloat(stock_blister) > 0 ? `<option value="${nombre_02}">${nombre_02}</option>` : ``) + `
                        `+ (parseFloat(stock_caja) > 0 ? `<option value="${nombre_03}">${nombre_03}</option>` : ``) + `
                        `+ (parseFloat(stock_fardo) > 0 ? `<option value="${nombre_04}">${nombre_04}</option>` : ``) + `
                        `+ (parseFloat(stock_sacos) > 0 ? `<option value="${nombre_05}">${nombre_05}</option>` : ``) + `
                        `+ (parseFloat(stock_paquete) > 0 ? `<option value="${nombre_06}">${nombre_06}</option>` : ``) + `
                        `+ (parseFloat(stock_07) > 0 ? `<option value="${nombre_07}">${nombre_07}</option>` : ``) + `
                        `+ (parseFloat(stock_08) > 0 ? `<option value="${nombre_08}">${nombre_08}</option>` : ``) + `
                        `+ (parseFloat(stock_09) > 0 ? `<option value="${nombre_09}">${nombre_09}</option>` : ``) + `
                        `+ (parseFloat(stock_10) > 0 ? `<option value="${nombre_10}">${nombre_10}</option>` : ``) + `
                        `+ (parseFloat(stock_11) > 0 ? `<option value="${nombre_11}">${nombre_11}</option>` : ``) + `
                        `+ (parseFloat(stock_12) > 0 ? `<option value="${nombre_12}">${nombre_12}</option>` : ``) + `
                        `+ (parseFloat(stock_13) > 0 ? `<option value="${nombre_13}">${nombre_13}</option>` : ``) + `
                        `+ (parseFloat(stock_14) > 0 ? `<option value="${nombre_14}">${nombre_14}</option>` : ``) + `
                        `+ (parseFloat(stock_15) > 0 ? `<option value="${nombre_15}">${nombre_15}</option>` : ``) + `
                        `+ (parseFloat(stock_16) > 0 ? `<option value="${nombre_16}">${nombre_16}</option>` : ``) + `
                        `+ (parseFloat(stock_17) > 0 ? `<option value="${nombre_17}">${nombre_17}</option>` : ``) + `
                        `+ (parseFloat(stock_18) > 0 ? `<option value="${nombre_18}">${nombre_18}</option>` : ``) + `
                        `+ (parseFloat(stock_19) > 0 ? `<option value="${nombre_19}">${nombre_19}</option>` : ``) + `
                        `+ (parseFloat(stock_20) > 0 ? `<option value="${nombre_20}">${nombre_20}</option>` : ``) + `
                    </select>
                </td>`+
            '<td><input  type="text" class="form-control" name="descripcion_detalle[]" style="width:100px" id="descripcion_detalle[]"  ></td>' +
            '<td>' + precioventasucursalr + '</td>' +
            '<td><input type="number" step="any" class="form-control" onchange="modificarSubototales()" style="width:100px" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '" ></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }


}

/*
    function agregarDetalle(idarticulo, articulo, precio_venta, stock, descuento_porcentaje, stock_unidad, precio_unidad,
        stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos, precio_sacos,
        stock_paquete, precio_paquete) 
    {
        //console.log("precio_venta ",precio_venta);
    // for (var i = 0; i < 50; i++){
            var cantidad = 1;
            var precioventasucursalr = precio_venta;
            var cantidadpresentacion = 1;
            var totalcantidadpresentacion = 1;
        
            if (idarticulo != "") {
                var subtotal = cantidad * precio_venta;
                var fila = '<tr class="filas" id="fila' + cont + '">' +
                    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                    '<td><input type="hidden" name="idarticulo[]" style="width:100px" value="' + idarticulo + '">' + articulo + '</td>' +
                    '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                    `<td>
                        <select class="form-control" style="width:150px" name="presentacion[]" id="presentacionselect`+ cont + `" 
                        onchange="presentacionoculatardatos(`+ cont + `,` + precio_venta + `,` + stock_unidad + `,` + precio_unidad + `,` + stock_blister + `,` + precio_blister + `,
                                                            `+ stock_caja + `,` + precio_caja + `,` + stock_fardo + `,` + precio_fardo + `,` + stock_sacos + `,` + precio_sacos + `,` + stock_paquete + `,` + precio_paquete + `)" >
                            `+ (parseInt(stock_unidad) > 0 ? `<option value="UNIDAD">P.U</option>` : ``) + `
                            `+ (parseInt(stock_blister) > 0 ? `<option value="BLISTER">P.BLI</option>` : ``) + ` 
                            `+ (parseInt(stock_caja) > 0 ? `<option value="CAJA">P.CAJA.</option>` : ``) + `
                            `+ (parseInt(stock_fardo) > 0 ? `<option value="FARDO">P.FARDO</option>` : ``) + ` 
                            `+ (parseInt(stock_sacos) > 0 ? `<option value="SACOS">P.SACOS</option>` : ``) + `
                            `+ (parseInt(stock_paquete) > 0 ? `<option value="PAQUETE">P.PAQUETE</option>` : ``) + `
                        </select>
                    </td>`+
                    '<td><input  type="text" class="form-contprecio_ventarol" name="descripcion_detalle[]" style="width:100px" id="descripcion_detalle[]"  ></td>' +
                    '<td>' + precioventasucursalr + '</td>' +
                    '<td><input type="number" step="any" onchange="modificarSubototales()" style="width:100px" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '" ></td>' +
                    '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                    '</tr>';
                cont++;
                detalles = detalles + 1;
                $('#detalles').append(fila);
                modificarSubototales();
            }
            else {
                alert("Error al ingresar el detalle, revisar los datos del artículo");
            }
    // }

    }
*/


function presentacionoculatardatos(id, precio_venta,
    nombre_01, stock_unidad, precio_unidad,
    nombre_02, stock_blister, precio_blister,
    nombre_03, stock_caja, precio_caja,
    nombre_04, stock_fardo, precio_fardo,
    nombre_05, stock_sacos, precio_sacos,
    nombre_06, stock_paquete, precio_paquete,
    nombre_07, stock_07, precio_07,
    nombre_08, stock_08, precio_08,
    nombre_09, stock_09, precio_09,
    nombre_10, stock_10, precio_10,
    nombre_11, stock_11, precio_11,
    nombre_12, stock_12, precio_12,
    nombre_13, stock_13, precio_13,
    nombre_14, stock_14, precio_14,
    nombre_15, stock_15, precio_15,
    nombre_16, stock_16, precio_16,
    nombre_17, stock_17, precio_17,
    nombre_18, stock_18, precio_18,
    nombre_19, stock_19, precio_19,
    nombre_20, stock_20, precio_20) {
    var presentacion = $("#presentacionselect" + id).val();
    var cantidad = parseFloat($("#cantidad" + id).val());
    if (presentacion == nombre_01) {
        var precioventaunidad = 0;
        if (stock_unidad > 0) {
            precioventaunidad = (precio_unidad / stock_unidad); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_unidad);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_unidad);
        $("#presen" + id).val(nombre_01);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_unidad);
    }
    else if (presentacion == nombre_02) {
        var precioblister = 0;
        if (stock_blister > 0) {
            // Calcula el precio por unidad y redondea a 2 decimales
            precioblister = ((precio_blister / stock_blister));
        }
        $("#cantidadpresentacion" + id).val(stock_blister);
        $("#precio_venta" + id).val(precioblister);
        $("#q_ref" + id).val(precio_blister);
        $("#presen" + id).val(nombre_02);

        $("#precio_ventaSistema" + id).val(precioblister);
        $("#precio_ventaSistema2" + id).val(precio_blister);
    }
    else if (presentacion == nombre_03) {
        var precioventaunidad = 0;
        if (stock_caja > 0) {
            precioventaunidad = (precio_caja / stock_caja); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_caja);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_caja);
        $("#presen" + id).val(nombre_03);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_caja);
    }
    else if (presentacion == nombre_04) {
        var precioventaunidad = 0;
        if (stock_fardo > 0) {
            precioventaunidad = (precio_fardo / stock_fardo); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_fardo);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_fardo);
        $("#presen" + id).val(nombre_04);
    }
    else if (presentacion == nombre_05) {
        var precioventaunidad = 0;
        if (stock_sacos > 0) {
            precioventaunidad = (precio_sacos / stock_sacos); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_sacos);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_sacos);
        $("#presen" + id).val(nombre_05);
    }
    else if (presentacion == nombre_06) {
        var precioventaunidad = 0;
        if (stock_paquete > 0) {
            precioventaunidad = (precio_paquete / stock_paquete); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_paquete);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_paquete);
        $("#presen" + id).val(nombre_06);
    }
    else if (presentacion == nombre_07) {
        var precioventaunidad = 0;
        if (stock_07 > 0) {
            precioventaunidad = (precio_07 / stock_07); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_07);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_07);
        $("#presen" + id).val(nombre_07);
    }
    else if (presentacion == nombre_08) {
        var precioventaunidad = 0;
        if (stock_08 > 0) {
            precioventaunidad = (precio_08 / stock_08); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_08);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_08);
        $("#presen" + id).val(nombre_08);
    }
    else if (presentacion == nombre_09) {
        var precioventaunidad = 0;
        if (stock_09 > 0) {
            precioventaunidad = (precio_09 / stock_09); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_09);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_09);
        $("#presen" + id).val(nombre_09);
    }
    else if (presentacion == nombre_10) {
        var precioventaunidad = 0;
        if (stock_10 > 0) {
            precioventaunidad = (precio_10 / stock_10); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_10);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_10);
        $("#presen" + id).val(nombre_10);
    }
    else if (presentacion == nombre_11) {
        var precioventaunidad = 0;
        if (stock_11 > 0) {
            precioventaunidad = (precio_11 / stock_11); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_11);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_11);
        $("#presen" + id).val(nombre_11);
    }
    else if (presentacion == nombre_12) {
        var precioventaunidad = 0;
        if (stock_12 > 0) {
            precioventaunidad = (precio_12 / stock_12); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_12);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_12);
        $("#presen" + id).val(nombre_12);
    }
    else if (presentacion == nombre_13) {
        var precioventaunidad = 0;
        if (stock_13 > 0) {
            precioventaunidad = (precio_13 / stock_13); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_13);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_13);
        $("#presen" + id).val(nombre_13);
    }
    else if (presentacion == nombre_14) {
        var precioventaunidad = 0;
        if (stock_14 > 0) {
            precioventaunidad = (precio_14 / stock_14); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_14);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_14);
        $("#presen" + id).val(nombre_14);
    }
    else if (presentacion == nombre_15) {
        var precioventaunidad = 0;
        if (stock_15 > 0) {
            precioventaunidad = (precio_15 / stock_15); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_15);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_15);
        $("#presen" + id).val(nombre_15);
    }
    else if (presentacion == nombre_16) {
        var precioventaunidad = 0;
        if (stock_16 > 0) {
            precioventaunidad = (precio_16 / stock_16); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_16);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_16);
        $("#presen" + id).val(nombre_16);
    }
    else if (presentacion == nombre_17) {
        var precioventaunidad = 0;
        if (stock_17 > 0) {
            precioventaunidad = (precio_17 / stock_17); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_17);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_17);
        $("#presen" + id).val(nombre_17);
    }
    else if (presentacion == nombre_18) {
        var precioventaunidad = 0;
        if (stock_18 > 0) {
            precioventaunidad = (precio_18 / stock_18); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_18);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_18);
        $("#presen" + id).val(nombre_18);
    }
    else if (presentacion == nombre_19) {
        var precioventaunidad = 0;
        if (stock_19 > 0) {
            precioventaunidad = (precio_19 / stock_19); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_19);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_19);
        $("#presen" + id).val(nombre_19);
    }
    else if (presentacion == nombre_20) {
        var precioventaunidad = 0;
        if (stock_20 > 0) {
            precioventaunidad = (precio_20 / stock_20); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_20);
        $("#precio_venta" + id).val(precioventaunidad);
        $("#q_ref" + id).val(precio_20);
        $("#presen" + id).val(nombre_20);
    }
    else {

        $("#cantidadpresentacion" + id).val(1);
        $("#precio_venta" + id).val(precio_venta);
        $("#q_ref" + id).val(precio_venta);
        $("#presen" + id).val(nombre_01);

    }
    var nuevaCantidadPresentacion = parseFloat($("#cantidadpresentacion" + id).val());
    $("#totalcantidadpresentacion" + id).val(nuevaCantidadPresentacion * cantidad);
    modificarSubototales();
}
function modificarSubototales() {

    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var sub = document.getElementsByName("subtotal");
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]"));
    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));
    var stock = (document.getElementsByName("stock_inventario[]"));

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpS = sub[i];
        var inpTpres = tprese[i];
        var inpCpre = cantpre[i];
        var inpStock = stock[i];

        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;

        if (parseFloat(inpC.value) == 0 || parseFloat(inpC.value) == null) {
            inpC.value = 1;
        } else {
            inpC.value = inpC.value;
        }
        /*  if( parseFloat(inpTpres.value) > parseFloat(inpStock.value))
          {
              Swal.fire({
                  title: 'Mensaje!',
                  text: "La cantidad no puede ser mayor al stock",
                  icon: 'warning',
                  timer: 2000, // 2 segundos
                  timerProgressBar: true
              });
              return;
          }*/

        inpS.value = (inpC.value * inpP.value);
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;

    }

    calcularTotales();
}
function calcularTotales() {

    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("Q/. " + total);
    $("#total_venta").val(total);
    $("#total_venta_r").val(total);

    evaluar();
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
    calcularTotales();
    detalles = detalles - 1;
    evaluar()
}


function mostrar(idtraladosucursal) {

    $.post("../ajax/salida_pro_sucursal.php?op=mostrar",
        { idtraladosucursal: idtraladosucursal },
        function (data) {

            data = JSON.parse(data);

            mostrarform(true);

            // Cargar nuevamente las sucursales
            $.post("../ajax/salida_pro_sucursal.php?op=selectSucursal", function (r) {

                var options = '<option value="">Seleccione una sucursal...</option>' + r;

                // Llenar ambos selects
                $("#idsucursal").html(options);
                $("#idsucursalOrigen").html(options);

                // Seleccionar los valores
                $("#idsucursalOrigen").val(data.idsucursalorigen);
                $("#idsucursal").val(data.idsucursaldestino);

                // Refrescar los selectpicker
                $("#idsucursalOrigen").selectpicker('refresh');
                $("#idsucursal").selectpicker('refresh');

                // Demás datos
                $("#fecha_hora").val(data.fecha);
                $("#idtraladosucursal").val(data.idtraladosucursal);
                $("#descripcion_salida_producto").val(data.descripcion_salida_producto);

                // Cargar detalle
                obtenerdetalletraslado(idtraladosucursal);

            });

        }
    );
}

function obtenerdetalletraslado(idtraladosucursal) {
    $.post("../ajax/salida_pro_sucursal.php?op=detalle_traslado", { idtraladosucursal: idtraladosucursal }, function (data) {
        data = JSON.parse(data);
        // console.log("data de detalle ", data);
        $.each(data, function (i, item) {
            agregarDetalle2(item.cantidad, item.cantidadpresentacion, item.descripcion_detalle, item.id_detalle_traslado_sucursal,
                item.idarticulo, item.idsucursaldestino, item.idsucursalorigen, item.idtraladosucursal, item.precio_venta,
                item.presentacion, item.totalcantidadpresentacion, item.nombre,
                item.nombre_01, item.stock_unidad, item.precio_unidad,
                item.nombre_02, item.stock_blister, item.precio_blister,
                item.nombre_03, item.stock_caja, item.precio_caja,
                item.nombre_04, item.stock_fardo, item.precio_fardo,
                item.nombre_05, item.stock_sacos, item.precio_sacos,
                item.nombre_06, item.stock_paquete, item.precio_paquete,
                item.nombre_07, item.stock_07, item.precio_07,
                item.nombre_08, item.stock_08, item.precio_08,
                item.nombre_09, item.stock_09, item.precio_09,
                item.nombre_10, item.stock_10, item.precio_10,
                item.nombre_11, item.stock_11, item.precio_11,
                item.nombre_12, item.stock_12, item.precio_12,
                item.nombre_13, item.stock_13, item.precio_13,
                item.nombre_14, item.stock_14, item.precio_14,
                item.nombre_15, item.stock_15, item.precio_15,
                item.nombre_16, item.stock_16, item.precio_16,
                item.nombre_17, item.stock_17, item.precio_17,
                item.nombre_18, item.stock_18, item.precio_18,
                item.nombre_19, item.stock_19, item.precio_19,
                item.nombre_20, item.stock_20, item.precio_20,
                item.facturar_cero,
                item.descripcion_detalle, item.stocksucursal, item.pv);
        });
    })
}

function agregarDetalle2(cantidad, cantidadpresentacion, descripcion_detalle, id_detalle_traslado_sucursal, idarticulo,
    idsucursaldestino, idsucursalorigen, idtraladosucursal, precio_venta, presentacion, totalcantidadpresentacion, articulo,
    nombre_01, stock_unidad, precio_unidad,
    nombre_02, stock_blister, precio_blister,
    nombre_03, stock_caja, precio_caja,
    nombre_04, stock_fardo, precio_fardo,
    nombre_05, stock_sacos, precio_sacos,
    nombre_06, stock_paquete, precio_paquete,
    nombre_07, stock_07, precio_07,
    nombre_08, stock_08, precio_08,
    nombre_09, stock_09, precio_09,
    nombre_10, stock_10, precio_10,
    nombre_11, stock_11, precio_11,
    nombre_12, stock_12, precio_12,
    nombre_13, stock_13, precio_13,
    nombre_14, stock_14, precio_14,
    nombre_15, stock_15, precio_15,
    nombre_16, stock_16, precio_16,
    nombre_17, stock_17, precio_17,
    nombre_18, stock_18, precio_18,
    nombre_19, stock_19, precio_19,
    nombre_20, stock_20, precio_20,
    facturar_cero,
    descripcion_detalle, stocksucursal, pv) {
    var precioventasucursalr = pv;
    if (idarticulo != "") {
        var subtotal = cantidad * precio_venta;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" style="width:100px" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input type="hidden" name="stock_inventario[]" style="width:100px" value="' + stocksucursal + '">' + stocksucursal + '</td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                <select class="form-control" style="width:150px" name="presentacion[]" id="presentacionselect` + cont + `" 
                onchange="presentacionoculatardatos(`+ cont + `,` + precio_venta + `,
                    '` + nombre_01 + `',` + stock_unidad + `,` + precio_unidad + `,
                    '` + nombre_02 + `',` + stock_blister + `,` + precio_blister + `,
                    '` + nombre_03 + `',` + stock_caja + `,` + precio_caja + `,
                    '` + nombre_04 + `',` + stock_fardo + `,` + precio_fardo + `,
                    '` + nombre_05 + `',` + stock_sacos + `,` + precio_sacos + `,
                    '` + nombre_06 + `',` + stock_paquete + `,` + precio_paquete + `,
                    '` + nombre_07 + `',` + stock_07 + `,` + precio_07 + `,
                    '` + nombre_08 + `',` + stock_08 + `,` + precio_08 + `,
                    '` + nombre_09 + `',` + stock_09 + `,` + precio_09 + `,
                    '` + nombre_10 + `',` + stock_10 + `,` + precio_10 + `,
                    '` + nombre_11 + `',` + stock_11 + `,` + precio_11 + `,
                    '` + nombre_12 + `',` + stock_12 + `,` + precio_12 + `,
                    '` + nombre_13 + `',` + stock_13 + `,` + precio_13 + `,
                    '` + nombre_14 + `',` + stock_14 + `,` + precio_14 + `,
                    '` + nombre_15 + `',` + stock_15 + `,` + precio_15 + `,
                    '` + nombre_16 + `',` + stock_16 + `,` + precio_16 + `,
                    '` + nombre_17 + `',` + stock_17 + `,` + precio_17 + `,
                    '` + nombre_18 + `',` + stock_18 + `,` + precio_18 + `,
                    '` + nombre_19 + `',` + stock_19 + `,` + precio_19 + `,
                    '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)" >
                    `+ (parseFloat(stock_unidad) > 0 ? `<option value="${nombre_01}" ` + (presentacion === nombre_01 ? 'selected' : '') + `>${nombre_01}</option>` : ``) + ` 
                    `+ (parseFloat(stock_blister) > 0 ? `<option value="${nombre_02}" ` + (presentacion === nombre_02 ? 'selected' : '') + `>${nombre_02}</option>` : ``) + ` 
                    `+ (parseFloat(stock_caja) > 0 ? `<option value="${nombre_03}" ` + (presentacion === nombre_03 ? 'selected' : '') + `>${nombre_03}</option>` : ``) + ` 
                    `+ (parseFloat(stock_fardo) > 0 ? `<option value="${nombre_04}" ` + (presentacion === nombre_04 ? 'selected' : '') + `>${nombre_04}</option>` : ``) + ` 
                    `+ (parseFloat(stock_sacos) > 0 ? `<option value="${nombre_05}" ` + (presentacion === nombre_05 ? 'selected' : '') + `>${nombre_05}</option>` : ``) + ` 
                    `+ (parseFloat(stock_paquete) > 0 ? `<option value="${nombre_06}" ` + (presentacion === nombre_06 ? 'selected' : '') + `>${nombre_06}</option>` : ``) + ` 
                    `+ (parseFloat(stock_07) > 0 ? `<option value="${nombre_07}" ` + (presentacion === nombre_07 ? 'selected' : '') + `>${nombre_07}</option>` : ``) + ` 
                    `+ (parseFloat(stock_08) > 0 ? `<option value="${nombre_08}" ` + (presentacion === nombre_08 ? 'selected' : '') + `>${nombre_08}</option>` : ``) + ` 
                    `+ (parseFloat(stock_09) > 0 ? `<option value="${nombre_09}" ` + (presentacion === nombre_09 ? 'selected' : '') + `>${nombre_09}</option>` : ``) + ` 
                    `+ (parseFloat(stock_10) > 0 ? `<option value="${nombre_10}" ` + (presentacion === nombre_10 ? 'selected' : '') + `>${nombre_10}</option>` : ``) + ` 
                    `+ (parseFloat(stock_11) > 0 ? `<option value="${nombre_11}" ` + (presentacion === nombre_11 ? 'selected' : '') + `>${nombre_11}</option>` : ``) + ` 
                    `+ (parseFloat(stock_12) > 0 ? `<option value="${nombre_12}" ` + (presentacion === nombre_12 ? 'selected' : '') + `>${nombre_12}</option>` : ``) + ` 
                    `+ (parseFloat(stock_13) > 0 ? `<option value="${nombre_13}" ` + (presentacion === nombre_13 ? 'selected' : '') + `>${nombre_13}</option>` : ``) + ` 
                    `+ (parseFloat(stock_14) > 0 ? `<option value="${nombre_14}" ` + (presentacion === nombre_14 ? 'selected' : '') + `>${nombre_14}</option>` : ``) + ` 
                    `+ (parseFloat(stock_15) > 0 ? `<option value="${nombre_15}" ` + (presentacion === nombre_15 ? 'selected' : '') + `>${nombre_15}</option>` : ``) + ` 
                    `+ (parseFloat(stock_16) > 0 ? `<option value="${nombre_16}" ` + (presentacion === nombre_16 ? 'selected' : '') + `>${nombre_16}</option>` : ``) + ` 
                    `+ (parseFloat(stock_17) > 0 ? `<option value="${nombre_17}" ` + (presentacion === nombre_17 ? 'selected' : '') + `>${nombre_17}</option>` : ``) + ` 
                    `+ (parseFloat(stock_18) > 0 ? `<option value="${nombre_18}" ` + (presentacion === nombre_18 ? 'selected' : '') + `>${nombre_18}</option>` : ``) + ` 
                    `+ (parseFloat(stock_19) > 0 ? `<option value="${nombre_19}" ` + (presentacion === nombre_19 ? 'selected' : '') + `>${nombre_19}</option>` : ``) + ` 
                    `+ (parseFloat(stock_20) > 0 ? `<option value="${nombre_20}" ` + (presentacion === nombre_20 ? 'selected' : '') + `>${nombre_20}</option>` : ``) + ` 
                </select>
            </td>` +
            '<td><input  type="text" class="form-control" name="descripcion_detalle[]" style="width:100px" id="descripcion_detalle[]"  value="' + descripcion_detalle + '"></td>' +
            '<td>' + precioventasucursalr + '</td>' +
            '<td><input type="number" step="any" class="form-control" onchange="modificarSubototales()" style="width:100px" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '" ></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}
init();