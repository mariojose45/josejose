var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(true);
    listar();
    $('#MenuVentas').addClass("treeview active");
    $('#Ventas').addClass("active");

    listarArticulos();
    // listarArticulos_v2();
    // listarArticulosxcategoria();
    $("#div_formapago").hide();
    $("#tipo_combus").hide();
    $("#no_galo").hide();
    $("#div_facCambiaria").hide();

    $("#div_formapago_formapago").hide();
    $("#div_facCambiaria_formapago").hide();


    $.post("../ajax/cotizaciones.php?op=selectVendedor", function (r) {
        $("#idvendedor").html(r);
        $('#idvendedor').selectpicker('refresh');
    });

    $.post("../ajax/cotizaciones.php?op=selectVendedor", function (r) {
        $("#idvendedor_formapago").html(r);
        $('#idvendedor_formapago').selectpicker('refresh');
    });


    $(document).on('keydown', function (event) {
        let tipo_entrega = $("#tipo_entrega").val();
        console.log("tipo_entrega ", tipo_entrega);
        if (tipo_entrega == "Transporte") {
            $("#selectMensajero").hide();
            $("#selectTransporte").show();
        } else if (tipo_entrega == "Mensajero") {
            $("#selectTransporte").hide();
            $("#selectMensajero").show();
        } else {
            $("#selectMensajero").hide();
            $("#selectTransporte").hide();
        }
        if (event.which == 120) { // F9 para abrir el modal de venta
            $("#btnProcesar").click();
        }
    });

    $(document).on('keydown', function (event) {
        if (event.which == 117) { // F6 guardar
            $("#btnGuardar").trigger("click");
        }
    });


    $(document).on('keydown', function (event) {
        if (event.which == 118) { // F7 cancelar
            $("#btnCancelar").click();

        }
    });

    $(document).on('keydown', function (event) {
        if (event.which == 119) { // F8 para abrir el modal de venta
            $("#btnagregar").click();
        }
    });



    $(document).ready(function () {
        // Evento del botón
        $("#btnProcesar").click(function () {
            // modificarSubotototales();

            let tipo_entrega = $("#tipo_entrega").val();
            console.log("tipo_entrega 2 ", tipo_entrega);

            if (tipo_entrega == "Transporte") {
                $("#selectMensajero").hide();
                $("#selectTransporte").show();
            } else if (tipo_entrega == "Mensajero") {
                $("#selectTransporte").hide();
                $("#selectMensajero").show();
            } else {
                $("#selectMensajero").hide();
                $("#selectTransporte").hide();
            }

            var total = $("#total_venta_r").val();
            $("#vistatotal").html(total);
        });

        // Evento cuando el modal ya está completamente visible
        $('#myModal23').on('shown.bs.modal', function () {
            $("#cefectivo").focus().val("");      // enfoca y limpia
            setInputActivo('cefectivo');          // activa la calculadora
        });
    });



    $.post("../ajax/articulo.php?op=selectCategoria", function (r) {
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');

    });




    $("#btnGuardar").click(function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });

    $("#btnGuardarformularioarticulosProductosSolicitar").click(function (e) {
        $('#myModalProductosSolicitar').modal('hide');
        guardaryeditarProductosSolicitar(e);
    });




    let cotizacionCargada = false; // Bandera para controlar si ya se cargó una cotización

    /*
    $("#btncargar").click(function () {

        var idcotizacion = $("#idcotizacion").val();
        if (idcotizacion == "") {
            alert("Debe Colocar un Id de Cotizacion Valido")
            return;
        }

        obtenerClienteCotizacion(idcotizacion);

    });
    */

    $("#btnGuardarApertura").click(function (e) {
        guardaryeditaraperturacaja(e);
    });

    $("#btnGuardarCierre").click(function (e) {
        guardaryeditarCierre(e);
    });


    $("#btnAgregarArt").click(function (e) {
        listarArticulos(e);
    });

    // ** TRANSPORTE **
    $.post("../ajax/venta.php?op=selecttransporte25", function (r) {
        $("#idtransporte").html(r);
        $('#idtransporte').selectpicker('refresh');

        // Aquí cambiamos a un selector más específico
        $("#idtransporte").siblings(".dropdown-menu").find(".bs-searchbox input").keyup(function () {
            var valor = $(this).val();
            var textooption = $(".no-results").text();
            var newText = textooption.replace("No results matched", "Crear Nuevo Transporte");
            $(".no-results").text(newText);

            $(".no-results").css("cursor", "pointer");

            $(".no-results").click(function () {
                $("#myModal2").modal('show');
            });
        });
    });

    $("#btnGuardarT").click(function (e) {
        guardaryeditart(e);
    });

    // ** MENSAJERO **
    $.post("../ajax/venta.php?op=selectMensajero", function (r) {
        $("#idmensajero").html(r);
        $('#idmensajero').selectpicker('refresh');

        // Aquí cambiamos a un selector más específico
        $("#idmensajero").siblings(".dropdown-menu").find(".bs-searchbox input").keyup(function () {
            var valor = $(this).val();
            var textooption = $(".no-results").text();
            var newText = textooption.replace("No results matched", "Crear Nuevo Mensajero");
            $(".no-results").text(newText);

            $(".no-results").css("cursor", "pointer");

            $(".no-results").click(function () {
                $("#myModal2M").modal('show');
            });
        });
    });

    $("#btnGuardarm").click(function (e) {
        guardaryeditarM(e);
    });

    //TALLER
    $("#btncargarTaller").click(function () {
        var idtaller = $("#idtaller").val();
        if (idtaller == "") {
            alert("Debe Colocar un Id de Taller Valido")
            return;
        }
        obtenerClienteTaller(idtaller);
    });

    $("#btncargar").click(function () {
        var rawInput = $("#idcotizacion").val().trim();
        if (rawInput === "") {
            Swal.fire("Atención", "Debe ingresar al menos un ID de Cotización", "warning");
            return;
        }

        // Convertimos la cadena "2, 5, 8" en un array ["2", "5", "8"]
        var ids = rawInput.split(',').map(item => item.trim()).filter(item => item !== "");

        if (ids.length === 0) return;

        Swal.fire({
            title: '¿Procesar cotizaciones?',
            text: `Se crearán ${ids.length} ventas independientes.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, procesar todas',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                procesarCotizacionesEnLote(ids);
            }
        });
    });
}

/*
async function procesarCotizacionesEnLote(cotizaciones) {
    let exitosas = 0;
    let fallidas = 0;
    let errores = [];

    // Mostramos cargando con SweetAlert2
    Swal.fire({
        title: 'Procesando ventas...',
        html: 'Por favor espere mientras se generan las ventas.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    for (let id of cotizaciones) {
        await new Promise((resolve) => {
            $.post("../ajax/venta.php?op=procesar_venta_directa", { idcotizacion: id }, function (data) {
                try {
                    let res = JSON.parse(data);
                    if (res.status === 'ok') {
                        exitosas++;
                    } else {
                        fallidas++;
                        errores.push(`Coti #${id}: ${res.msg}`);
                    }
                } catch (e) {
                    fallidas++;
                    errores.push(`Coti #${id}: Error de respuesta del servidor`);
                }
                resolve();
            });
        });
    }

    // Al finalizar todas las ventas:
    let mensajeHtml = `<p><b>Exitosas:</b> ${exitosas}</p><p><b>Fallidas:</b> ${fallidas}</p>`;
    if (errores.length > 0) {
        mensajeHtml += `<br><small style="color:red">${errores.join('<br>')}</small>`;
    }

    Swal.fire({
        icon: fallidas === 0 ? 'success' : 'warning',
        title: 'Proceso finalizado',
        html: mensajeHtml,
        confirmButtonText: 'Aceptar'
    }).then(() => {
        // Opcional: Recargar la tabla/lista principal si existe
        if (typeof listar === 'function') {
            listar();
        }
    });
}
*/
async function procesarCotizacionesEnLote(cotizaciones) {
    let exitosas = 0;
    let fallidas = 0;
    let errores = [];

    // 1. GENERAR EL LOTE ÚNICO AQUÍ (Ej: LOTE_22_07_2026_095833_113)
    let ahora = new Date();
    let dia = String(ahora.getDate()).padStart(2, '0');
    let mes = String(ahora.getMonth() + 1).padStart(2, '0');
    let anio = ahora.getFullYear();
    let hora = String(ahora.getHours()).padStart(2, '0') +
        String(ahora.getMinutes()).padStart(2, '0') +
        String(ahora.getSeconds()).padStart(2, '0');

    // Usamos el idusuario disponible en el entorno JS (o de la sesión)
    let idusuario_js = $("#idusuario_session").val() || ""; // Cambia por tu variable de usuario si aplica
    let loteUnico = `LOTE_${dia}_${mes}_${anio}_${hora}_${idusuario_js}`;

    Swal.fire({
        title: 'Procesando ventas...',
        html: 'Por favor espere mientras se generan las ventas.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    for (let id of cotizaciones) {
        await new Promise((resolve) => {
            // 2. ENVIAMOS 'venta_lote' JUNTO CON EL 'idcotizacion'
            $.post("../ajax/venta.php?op=procesar_venta_directa", {
                idcotizacion: id,
                venta_lote: loteUnico
            }, function (data) {
                try {
                    let res = JSON.parse(data);
                    if (res.status === 'ok') {
                        exitosas++;
                    } else {
                        fallidas++;
                        errores.push(`Coti #${id}: ${res.msg}`);
                    }
                } catch (e) {
                    fallidas++;
                    errores.push(`Coti #${id}: Error de respuesta del servidor`);
                }
                resolve();
            });
        });
    }

    // Al finalizar todas las ventas:
    let mensajeHtml = `<p><b>Exitosas:</b> ${exitosas}</p><p><b>Fallidas:</b> ${fallidas}</p>`;
    if (errores.length > 0) {
        mensajeHtml += `<br><small style="color:red">${errores.join('<br>')}</small>`;
    }

    Swal.fire({
        icon: fallidas === 0 ? 'success' : 'warning',
        title: 'Proceso finalizado',
        html: mensajeHtml,
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-print"></i> Imprimir Reporte Lote',
        cancelButtonText: 'Aceptar',
        confirmButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("../reportes/ex_venta_lote.php?lote=" + loteUnico, "_blank");
        }

        if (typeof listar === 'function') {
            listar();
        }
    });
}

function obtenerClienteTaller(idcotizacion) {
    load();
    $.post("../ajax/cotizaciones.php?op=mostrarTaller", { idcotizacion: idcotizacion }, function (data, status) {
        // Parseamos la data
        data = JSON.parse(data);
        //console.log("Data ", data);
        if (data.estado == "Anulado") {
            $("#idtaller").val("");
            Swal.fire({
                title: "Atención!",
                text: "Este Ingreso de Vehículo esta Anulado, no se puede procesar.",
                icon: "warning"
            }).then(() => {
                window.location.reload();
            });
        } else {
            if (data.facturado == "1") {
                $("#idtaller").val("");
                Swal.fire({
                    title: "Atención!",
                    text: "Este Ingreso de Vehículo ya se ha Facturado, no se puede procesar.",
                    icon: "warning"
                }).then(() => {
                    window.location.reload();
                });

            } else {
                // Si la data es válida, mostramos el formulario y asignamos los valores
                mostrarform(true);
                $("#trabajos_detalle_area").val(data.trabajos_detalle);
                $("#idtaller").val(data.idingreso_vehiculo);
                $("#codigo_cliente").val(data.codigo_cliente);
                $("#nit").val(data.nit);
                $("#nombre_cliente").val(data.nombre_cliente);
                $("#telefono_cliente").val(data.telefono_cliente);
                $("#direccion_cliente").val(data.direccion_cliente);
                $("#correo_cliente").val(data.correo_cliente);
                $("#tipo_cliente").val(data.tipo_cliente);
                $("#tipo_cliente").selectpicker('refresh');
                $("#idcliente").val(data.idcliente);

                $("#tipo_documento_cliente").val(data.tipo_documento_cliente);
                $("#tipo_documento_cliente").selectpicker('refresh');
                // Llamamos a la función para obtener el detalle de la cotización
                obtenerdetalleTaller(idcotizacion);

            }
        }
    });
}

function obtenerdetalleTaller(idcotizacion) {
    $.post("../ajax/cotizaciones.php?op=detalle_taller", { idcotizacion: idcotizacion }, function (data) {
        //console.log(data);
        data = JSON.parse(data);
        Swal.close()

        $.each(data, function (i, item) {
            agregarDetalleTaller(
                item.idarticulo,
                item.nombre || '', // Check if nombre is defined
                item.precio_venta || 0,
                item.cantidad || 0,
                item.stock || 0,
                item.descuento || 0,
                item.stock_unidad || 0,
                item.precio_unidad || 0,
                item.stock_blister || 0,
                item.precio_blister || 0,
                item.stock_caja || 0,
                item.precio_caja || 0,
                item.stock_fardo || 0,
                item.precio_fardo || 0,
                item.stock_sacos || 0,
                item.precio_sacos || 0,
                item.stock_paquete || 0,
                item.precio_paquete || 0,
                item.precio_rango1 || 0,
                item.precio_rango2 || 0,
                item.precio_rango3 || 0,
                item.precio_compra || 0,
                item.precio_activado || 0,
                item.precio_rango1_Dos || 0,
                item.precio_rango2_Dos || 0,
                item.precio_rango3_Dos || 0,
                item.precio_rango1_Mecanico || 0,
                item.precio_rango2_MecanicoDos || 0,
                item.precio_rango3_MecanicoTres || 0,
                item.precio_rango1_Distribuidor || 0,
                item.precio_rango2_DistribuidorDos || 0,
                item.precio_rango3_DistribuidorTres || 0,
                item.precio_rango1_Mayorista || 0,
                item.precio_rango2_MayoristaDos || 0,
                item.precio_rango3_MayoristaTres || 0,
                1,
                1,
                0,
                item.precio_venta || 0,
                item.precio_venta || 0,
                item.precio_venta || 0,
                item.descuento || 0,
                item.descuento || 0,
                item.descuento || 0,
                item.descripcion_detalle || '' // Check if descripcion_detalle is defined
            );
        });
    })
}

function agregarDetalleTaller(idarticulo, nombre, precio_venta, cantidad, stock, descuento, stock_unidad, precio_unidad,
    stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos, precio_sacos,
    stock_paquete, precio_paquete, precio_rango1, precio_rango2, precio_rango3, precio_compra, precio_activado, precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, cantidadpresentacion,
    totalcantidadpresentacion, presen, precio_ventaSistema, precio_ventaSistema2, q_ref,
    precio_recargoQRef, precio_recargoPV, descuento_permitido, descripcion_detalle) {

    precio_activado = precio_activado.toString().trim();
    //console.log("q_ref ", q_ref);

    var cantidad = cantidad;
    var stockinven = stock;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        var exist = false;
        $('#detalles').children("tbody").children("tr").each(function (index) {
            var idart = $(this).attr("data-id")
            if (idart == idarticulo) {
                exist = true;
            }
        })

        if (!exist) {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
                onchange="presentacionoculatardatos(`+ cont + `,` + precio_venta + `,` + stock_unidad + `,` + precio_unidad + `,` + stock_blister + `,` + precio_blister + `,
                                                    `+ stock_caja + `,` + precio_caja + `,` + stock_fardo + `,` + precio_fardo + `,` + stock_sacos + `,` + precio_sacos + `,` + stock_paquete + `,` + precio_paquete + `)" >
                    `+ (parseInt(stock_unidad) > 0 ? `<option value="UNIDAD" ` + (presen == 'UNIDAD' ? "selected" : "") + `>P.U</option>` : ``) + ` 
                    `+ (parseInt(stock_blister) > 0 ? `<option value="BLISTER" ` + (presen == 'BLISTER' ? "selected" : "") + `>P.BLI</option>` : ``) + ` 
                    `+ (parseInt(stock_caja) > 0 ? `<option value="CAJA" ` + (presen == 'CAJA' ? "selected" : "") + `>P.CAJA.</option>` : ``) + ` 
                    `+ (parseInt(stock_fardo) > 0 ? `<option value="FARDO" ` + (presen == 'FARDO' ? "selected" : "") + `>P.FARDO</option>` : ``) + ` 
                    `+ (parseInt(stock_sacos) > 0 ? `<option value="SACOS" ` + (presen == 'SACOS' ? "selected" : "") + `>P.SACOS</option>` : ``) + ` 
                    `+ (parseInt(stock_paquete) > 0 ? `<option value="PAQUETE" ` + (presen == 'PAQUETE' ? "selected" : "") + `>P.PAQUETE</option>` : ``) + ` 

                </select>
            </td>`+
                `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_ventaSistema + `">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_ventaSistema2 + `">
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + q_ref + `"> 
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="` + precio_recargoPV + `" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="` + precio_recargoQRef + `" ></td>` +
                '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_permitido + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="' + descuento + '"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="' + descripcion_detalle + '"></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
            $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}




$(document).ready(function () {
    $("#SaveGastoaVenta").click(function (e) {
        $('#ModalGastoVenta').modal('hide');
        guardarGastoaVenta(e);
    });
});


function listarProductosaSolicitar() {
    $('#myModalListarProductosSolicitar').modal('show');
}

function GenerarSolicitudPedido() {
    $('#myModalProductosSolicitar').modal('show');
    $(".filasSolicitudProducto").remove();
    GenerarSolicitudPedidoTb();
}


function GenerarSolicitudPedidoTb() {

    $.post("../ajax/consultas.php?op=GenerarSolicitudPedidoTb", function (data) {
        data = JSON.parse(data);
        Swal.close()

        $.each(data, function (i, item) {
            agregarDetalleGenerarSolicitudPedidoTb(item.idarticulo, item.nombre, item.precio_venta, item.stock,
                item.descuento_porcentaje, item.stock_unidad, item.precio_unidad,
                item.stock_blister, item.precio_blister, item.stock_caja, item.precio_caja, item.stock_fardo,
                item.precio_fardo, item.stock_sacos, item.precio_sacos,
                item.stock_paquete, item.precio_paquete, item.precio_rango1, item.precio_rango2,
                item.precio_rango3, item.precio_compra, item.precio_activado, item.precio_rango1_Dos,
                item.precio_rango2_Dos, item.precio_rango3_Dos, item.precio_rango1_Mecanico,
                item.precio_rango2_MecanicoDos, item.precio_rango3_MecanicoTres, item.precio_rango1_Distribuidor,
                item.precio_rango2_DistribuidorDos, item.precio_rango3_DistribuidorTres, item.precio_rango1_Mayorista,
                item.precio_rango2_MayoristaDos, item.precio_rango3_MayoristaTres, item.cantidad_faltante);
        });
    })

}

function agregarDetalleGenerarSolicitudPedidoTb(idarticulo, nombre, precio_venta, stock, descuento_porcentaje,
    stock_unidad, precio_unidad, stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo,
    stock_sacos, precio_sacos, stock_paquete, precio_paquete, precio_rango1, precio_rango2, precio_rango3,
    precio_compra, precio_activado, precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, cantidad) {


    precio_activado = precio_activado.toString().trim();

    var stockinven = stock;
    var presen = 'UNIDAD';
    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        var exist = false;
        $('#detalles').children("tbody").children("tr").each(function (index) {
            var idart = $(this).attr("data-id")
            if (idart == idarticulo) {
                exist = true;
            }
        })

        if (!exist) {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filasSolicitudProducto" data-id="' + idarticulo + '" id="filasSolicitudProducto' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalleProductoSolicitudPedido(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo_SP[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="precio_compra_SP[]" value="' + precio_compra + '"><input  type="hidden"  name="presen_SP[]" id="presen_SP' + cont + '"value="' + presen + '" "><input type="hidden" name="stockinven_SP[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion_SP' + cont + '" name="cantidadpresentacion_SP[]" value="' + cantidadpresentacion + '" onchange="modificarSubototalesSolicitudProducto()"><input style="width:60px" class="form-control"   onchange="modificarSubototalesSolicitudProducto()"  type="number" step="any"   id="cxcantidad_SP' + idarticulo + '" name="cantidad_SP[]" id="cantidad_SP' + cont + '" value="' + cantidad + '"></td>' +
                `<td>
                    <select class="form-control" style="width:125px" name="presentacion_SP[]" id="presentacionselect_SP`+ cont + `" 
                    onchange="presentacionoculatardatosSolicitudProducto(`+ cont + `,` + precio_venta + `,` + stock_unidad + `,` + precio_unidad + `,` + stock_blister + `,` + precio_blister + `,
                                                        `+ stock_caja + `,` + precio_caja + `,` + stock_fardo + `,` + precio_fardo + `,` + stock_sacos + `,` + precio_sacos + `,` + stock_paquete + `,` + precio_paquete + `)" >
                        `+ (parseInt(stock_unidad) > 0 ? `<option value="UNIDAD">P.U</option>` : ``) + ` 
                        `+ (parseInt(stock_blister) > 0 ? `<option value="BLISTER">P.BLI</option>` : ``) + ` 
                        `+ (parseInt(stock_caja) > 0 ? `<option value="CAJA">P.CAJA.</option>` : ``) + ` 
                        `+ (parseInt(stock_fardo) > 0 ? `<option value="FARDO">P.FARDO</option>` : ``) + ` 
                        `+ (parseInt(stock_sacos) > 0 ? `<option value="SACOS">P.SACOS</option>` : ``) + ` 
                        `+ (parseInt(stock_paquete) > 0 ? `<option value="PAQUETE">P.PAQUETE</option>` : ``) + ` 

                    </select>
                </td>`+
                '<td><input  type="hidden"  name="presen_SP[]" id="presen_SP' + cont + '"value="' + presen + '" "><input style="width:60px"  type="number" step="any" id="totalcantidadpresentacion_SP' + cont + '" name="totalcantidadpresentacion_SP[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototalesSolicitudProducto()" readonly></td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#tblarticulosProductosSolicitar');
        } else {
            var cxcantidad = parseInt($("#cxcantidad_SP" + idarticulo).val()) + 1
            $("#cxcantidad_SP" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad_SP" + idarticulo)[0]);
        }

        modificarSubototalesSolicitudProducto();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function modificarSubototalesSolicitudProducto() {
    var cantpre = (document.getElementsByName("cantidadpresentacion_SP[]"));
    var cant = document.getElementsByName("cantidad_SP[]");
    var tprese = (document.getElementsByName("totalcantidadpresentacion_SP[]"));



    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpCpre = cantpre[i];
        var inpTpres = tprese[i];


        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion_SP[]")[i].innerHTML = inpTpres.value;




    }

}

function presentacionoculatardatosSolicitudProducto(id, precio_venta, stock_unidad, precio_unidad, stock_blister, precio_blister,
    stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos,
    precio_sacos, stock_paquete, precio_paquete) {
    var presentacion = $("#presentacionselect_SP" + id).val();
    if (presentacion == 'UNIDAD') {
        var precioventaunidad = 0;
        if (stock_unidad > 0) {
            precioventaunidad = (precio_unidad / stock_unidad); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion_SP" + id).val(stock_unidad);
        $("#precio_venta_SP" + id).val(precioventaunidad);
        $("#q_ref_SP" + id).val(precio_unidad);
        $("#presen_SP" + id).val("UNIDAD");

        $("#precio_ventaSistema_SP" + id).val(precioventaunidad);
        $("#precio_ventaSistema2_SP" + id).val(precio_unidad);
    }
    else if (presentacion == 'BLISTER') {
        var precioblister = 0;
        if (stock_blister > 0) {
            // Calcula el precio por unidad y redondea a 2 decimales
            precioblister = ((precio_blister / stock_blister));
        }
        $("#cantidadpresentacion_SP" + id).val(stock_blister);
        $("#precio_venta_SP" + id).val(precioblister);
        $("#q_ref_SP" + id).val(precio_blister);
        $("#presen_SP" + id).val("BLISTER");

        $("#precio_ventaSistema_SP" + id).val(precioblister);
        $("#precio_ventaSistema2_SP" + id).val(precio_blister);
    }
    else if (presentacion == 'CAJA') {
        var precioventacaja = 0;
        if (stock_caja > 0) {
            precioventacaja = (precio_caja / stock_caja); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion_SP" + id).val(stock_caja);
        $("#precio_venta_SP" + id).val(precioventacaja);
        $("#q_ref_SP" + id).val(precio_caja);
        $("#presen_SP" + id).val("CAJA");

        $("#precio_ventaSistema_SP" + id).val(precioventacaja);
        $("#precio_ventaSistema2_SP" + id).val(precio_caja);
    }
    else if (presentacion == 'FARDO') {

        var precioventafardo = 0;
        if (stock_fardo > 0) {
            precioventafardo = (precio_fardo / stock_fardo); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion_SP" + id).val(stock_fardo);
        $("#precio_venta_SP" + id).val(precioventafardo);
        $("#q_ref_SP" + id).val(precio_fardo);
        $("#presen_SP" + id).val("FARDO");

        $("#precio_ventaSistema_SP" + id).val(precioventafardo);
        $("#precio_ventaSistema2_SP" + id).val(precio_fardo);
    }

    else if (presentacion == 'SACOS') {
        var precioventasacos = 0;
        if (stock_sacos > 0) {
            precioventasacos = (precio_sacos / stock_sacos); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion_SP" + id).val(stock_sacos);
        $("#precio_venta_SP" + id).val(precioventasacos);
        $("#q_ref_SP" + id).val(precio_sacos);
        $("#presen_SP" + id).val("SACOS");

        $("#precio_ventaSistema_SP" + id).val(precioventasacos);
        $("#precio_ventaSistema2_SP" + id).val(precio_sacos);
    }
    else if (presentacion == 'PAQUETE') {
        var precioventapaquete = 0;
        if (stock_paquete > 0) {
            precioventapaquete = (precio_paquete / stock_paquete); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion_SP" + id).val(stock_paquete);
        $("#precio_venta_SP" + id).val(precioventapaquete);
        $("#q_ref_SP" + id).val(precio_paquete);
        $("#presen_SP" + id).val("PAQUETE");


        $("#precio_ventaSistema_SP" + id).val(precioventapaquete);
        $("#precio_ventaSistema2_SP" + id).val(precio_paquete);
    }

    modificarSubototalesSolicitudProducto();
}

function eliminarDetalleProductoSolicitudPedido(indice) {
    $("#filasSolicitudProducto" + indice).remove();
    detalles = detalles - 1;
}

function guardaryeditarProductosSolicitar(e) {

    if (detalles > 0) {

    }
    else {
        alert("No se puede Guardar porque no has agregado item ");
        return;
    }
    agruparDatosSolicitudProductos();

    e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#formularioarticulosProductosSolicitar")[0]);
    let datosArticulosC_SP = JSON.parse(localStorage.getItem('datosArticulosC_SP'));
    formData.append("datosArticulosC_SP", JSON.stringify(datosArticulosC_SP));
    console.log(datosArticulosC_SP);
    load();
    $.ajax({
        url: "../ajax/venta.php?op=guardaryeditarSolicitudProductos",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.close();
            // console.log(datos)
            //window.location.reload(); 
        }

    });
}

function agruparDatosSolicitudProductos() {
    const datos = {
        articulos: {
            idarticulo: [],
            precio_compra: [],
            stockinven: [],
            cantidadpresentacion: [],
            cantidad: [],
            presentacion: [],
            totalcantidadpresentacion: []
        }
    };

    $('#tblarticulosProductosSolicitar .filasSolicitudProducto').each(function () {
        const idarticulo = $(this).find('input[name="idarticulo_SP[]"]').val();
        const precio_compra = $(this).find('input[name="precio_compra_SP[]"]').val();
        const stockinven = $(this).find('input[name="stockinven_SP[]"]').val();
        const cantidadpresentacion = $(this).find('input[name="cantidadpresentacion_SP[]"]').val();
        const cantidad = $(this).find('input[name="cantidad_SP[]"]').val();
        const presentacion = $(this).find('input[name="presen_SP[]"]').val();
        const totalcantidadpresentacion = $(this).find('input[name="totalcantidadpresentacion_SP[]"]').val();


        datos.articulos.idarticulo.push(idarticulo);
        datos.articulos.precio_compra.push(precio_compra);
        datos.articulos.stockinven.push(stockinven);
        datos.articulos.cantidadpresentacion.push(cantidadpresentacion);
        datos.articulos.cantidad.push(cantidad);
        datos.articulos.presentacion.push(presentacion);
        datos.articulos.totalcantidadpresentacion.push(totalcantidadpresentacion);


    });

    const datosJSON = JSON.stringify(datos);

    localStorage.setItem('datosArticulosC_SP', datosJSON);
    //console.log(datosJSON);
}


function MostrarPedidosHechos() {
    var fecha_inicio_reporte = $("#fecha_inicio_reporteMPH").val();
    var fecha_fin_reporte = $("#fecha_fin_reporteMPH").val();
    tabla = $('#tblSolicitarProductosGenerados').dataTable(
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
                url: '../ajax/venta.php?op=MostrarPedidosHechos',
                type: "get",
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte },
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



function guardarGastoaVenta(e) {

    e.preventDefault(); // Prevenir la acción predeterminada del formulario
    // Lógica para guardar el formulario si pasa la validación
    var formData = new FormData($("#formulariogastoventa")[0]);
    load();
    $.ajax({
        url: "../ajax/venta.php?op=guardarGastoaVenta",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            Swal.close();
            Swal.fire({
                title: '¡Éxito!',
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


function guardaryeditaraperturacaja(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioapertura")[0]);

    $.ajax({
        url: "../ajax/cuadres_caja.php?op=guardaryeditar2",
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
    limpiarapertura();
}

//Función cancelarform
function cancelarformaperturacaja() {
    limpiarapertura();
}

function validaringresoefectivo() {
    var valorqGastoaVenta = parseFloat($("#valor_q_GastoaVenta").val()) || 0;
    var totalefectivoGastoaVenta = parseFloat($("#disponibleparaGastos_GastoaVenta").val()) || 0;


    if (valorqGastoaVenta > totalefectivoGastoaVenta) {
        Swal.fire({
            title: '¡Advertencia!',
            text: "Tu gasto ya superó lo que tienes en efectivo disponible.",
            icon: 'warning', // Cambiado a un ícono más adecuado
            confirmButtonText: 'Regresar',
            timer: 2000, // 2 segundos
            timerProgressBar: true
        }).then(() => {
            // Aquí puedes asegurarte de que el modal no se cierre
            $("#modalID").modal('show'); // Reemplaza `modalID` con el ID real de tu modal
        });
        $("#valor_q_GastoaVenta").val(0);
        return;
    }

}

function limpiarapertura() {
    $("#total_efectivo").val("");
    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora_apertura').val(today);

}



function guardaryeditarCierre(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);

    var total_efectivocierre = $("#total_efectivocierre").val();
    // Validar que no esté vacío
    if (total_efectivocierre === "" || total_efectivocierre === null || total_efectivocierre === undefined) {
        Swal.fire({
            icon: 'error',
            title: 'Error de Validación',
            text: 'El campo "Total de Efectivo" no puede estar vacío.',
            confirmButtonText: 'Aceptar'
        });
        $("#total_efectivocierre").focus();
        return false;
    }
    var formData = new FormData($("#formulariocierre")[0]);
    load();
    $.ajax({
        url: "../ajax/cuadres_caja_cierre.php?op=guardaryeditar2",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {

            Swal.close();
            $('#myModalCierre').modal('hide');
            if (confirm("Desea Imprimir su Cierre de caja")) {
                window.location.href = "../reportes/excuadreCajaCierre_78mm.php?id=" + datos, '_blank';
            }
            else {
                window.location.reload();
            }
        }

    });
    limpiarCierre();
}



//Función cancelarform
function cancelarformCierre() {
    limpiarCierre();
}

function limpiarCierre() {
    $("#total_efectivocierre").val("");
    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora_cierre').val(today);

}

function calcularcaja() {



    var res_total_ventas_diarias = $("#total_ventas_diarias").val();
    var res_total_efectivo = $("#total_efectivocierre").val();
    var res_total_ventas_diarias_efectivo = $("#total_ventas_diarias_efectivo").val();
    var res_total_efectivo_inicio = $("#total_efectivo_inicio").val();

    var total_ventas_gastosEfectivo = $("#total_ventas_gastosEfectivo").val();
    var total_ventas_AbonosVentas = $("#total_ventas_AbonosVentas").val();
    var total_ventas_NCVentas = $("#total_ventas_NCVentas").val();

    calculo1 = parseFloat(res_total_efectivo) - ((parseFloat(res_total_efectivo_inicio) +
        parseFloat(res_total_ventas_diarias_efectivo) +
        parseFloat(total_ventas_AbonosVentas)
    ) - (parseFloat(total_ventas_gastosEfectivo) + parseFloat(total_ventas_NCVentas)))

    console.log("calculo1: " + calculo1)
    console.log("total_ventas_gastosEfectivo" + total_ventas_gastosEfectivo)

    calculo2 = (parseFloat(total_ventas_gastosEfectivo) - calculo1)
    $("#total_efectivo_cierre_operaciones").val(calculo1);





}

function calcularefectivo() {
    // $("#cefectivo").focus() 
    try {


        var total = parseFloat($("#total").html().replace('Q.', '').replace("Q.", "").trim());
        var efectivo = parseFloat($("#cefectivo").val());
        var cefectivo_tarjeta = parseFloat($("#ctarjeta").val());
        var cefectivo_transferencia = parseFloat($("#ctransferencia").val());
        var cefectivo_credito = parseFloat($("#ccredito").val());

        var cambio1 = (efectivo + cefectivo_tarjeta + cefectivo_transferencia + cefectivo_credito);
        var cambio2 = cambio1 - total

        $("#cambio").html("Q." + cambio2.toFixed(2));
        $("#rescambio").val(cambio2.toFixed(2));


    } catch (ex) {

    }
};


///////CLIENTE NUEVO DE GASTOS
function validarnit2() {


    var nit = $("#nit_no_GastoaVenta").val();

    if (nit == "") {
        alert("Debe Colocar un nit mayor a 6 caracteres");
        return;
    }

    $.post("../ajax/consultas.php?op=validarnit", { nit: nit }, function (data) {

        try {
            data = JSON.parse(data);


            // Validar si nombre no es nulo, indefinido o vacío
            if (data["receptor"] && data["receptor"]["nombre"] != null && data["receptor"]["nombre"].trim() !== "") {
                let nombre = data["receptor"]["nombre"];
                let direccion = data["receptor"]["direccion"] != null ? data["receptor"]["direccion"] : "CIUDAD";

                $("#nombreproveedor_GastoaVenta").val(nombre);
                $("#direccion__GastoaVenta").val(direccion);

                buscarnitenSistemaparaIdcliente2(nit);
            } else {
                $("#idcliente_GastoaVenta").val('0');
                $("#tipo_documento_cliente_GastoaVenta").val("NIT");
                $("#tipo_documento_cliente_GastoaVenta").selectpicker('refresh');

                Swal.fire({
                    title: 'Mensaje!',
                    text: "Nit No Existe volver a consultar su nit o se creara como cliente nuevo",
                    icon: 'success',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });

            }
        } catch (error) {
            Swal.fire({
                title: 'Mensaje!',
                text: "Error al procesar la respuesta del servidor. Intente nuevamente.",
                icon: 'success',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
        }
    });
}
function buscarnitenSistemaparaIdcliente2(nit) {
    var nombre_cliente = $("#nombre_cliente").val();
    $.post("../ajax/consultas.php?op=buscarnitenSistemaparaIdcliente", { nit: nit }, function (data, status) {
        //console.log(data)
        try {
            data = JSON.parse(data);

            // Verifica si el objeto data está vacío o nulo
            if (data === null || !data.idpersona) {
                $("#idcliente_GastoaVenta").val('0');
                $("#tipo_documento_cliente_GastoaVenta").val("NIT");
                $("#tipo_documento_cliente_GastoaVenta").selectpicker('refresh');

                Swal.fire({
                    title: 'Mensaje!',
                    text: 'Se Creara Nuevo Cliente.',
                    icon: 'warning',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });
            } else {
                // Si el cliente existe, llena los campos
                $("#idcliente_GastoaVenta").val(data.idpersona);
                $("#nit_no_GastoaVenta").val(data.num_documento);
                $("#tipo_documento_cliente_GastoaVenta").val(data.tipo_documento);
                $("#tipo_documento_cliente_GastoaVenta").selectpicker('refresh');

            }
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Error en la respuesta del servidor.',
                icon: 'error',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
        }

    })
}
///////


/////CLIENTE NUEVO

function validarnit() {


    var nit = $("#nit").val().trim();
    var tipo = $("#tipo_documento_cliente").val();

    if (tipo == "NIT") {

        // Eliminar espacios y guiones dentro del NIT
        nit = $("#nit").val().replace(/[\s-]+/g, "");

    } else if (tipo == "DPI") {

        // Prefijo fijo
        let cui = "CUI";

        // Quitar espacios y guiones del DPI
        let dpiNumero = $("#nit").val().replace(/[\s-]+/g, "");

        // Concatenar CUI + numero DPI
        nit = cui + dpiNumero;
    }


    if (nit == "") {
        alert("Debe Colocar un nit mayor a 6 caracteres");
        return;
    }

    $.post("../ajax/consultas.php?op=validarnit", { nit: nit }, function (data) {

        try {
            data = JSON.parse(data);


            // Validar si nombre no es nulo, indefinido o vacío
            if (data["receptor"] && data["receptor"]["nombre"] != null && data["receptor"]["nombre"].trim() !== "") {
                let nombre = data["receptor"]["nombre"];
                let direccion = data["receptor"]["direccion"] != null ? data["receptor"]["direccion"] : "CIUDAD";

                $("#nombre_cliente").val(nombre);
                $("#direccion_cliente").val(direccion);

                buscarnitenSistemaparaIdcliente(nit);
            } else {
                $("#idcliente").val('0');
                $("#codigo_cliente").val('');
                $("#correo_cliente").val("sincorreo@gmail.com");
                $("#telefono_cliente").val("0");

                let nitValor = nit.toString().trim();
                let largo = nitValor.length;


                $("#tipo_cliente").val("PUBLICO");
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val("0");

                Swal.fire({
                    title: 'Mensaje!',
                    text: "Nit No Existe volver a consultar su nit o se creara como cliente nuevo",
                    icon: 'success',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });

                $("#nit").val("CF");
                $("#nit").focus()

            }
        } catch (error) {
            Swal.fire({
                title: 'Mensaje!',
                text: "Error al procesar la respuesta del servidor. Intente nuevamente.",
                icon: 'success',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
        }
    });
}

function buscarnitenSistemaparaIdcliente(nit) {
    var nombre_cliente = $("#nombre_cliente").val();
    $.post("../ajax/consultas.php?op=buscarnitenSistemaparaIdcliente", { nit: nit }, function (data, status) {
        //console.log(data)
        try {
            data = JSON.parse(data);

            // Verifica si el objeto data está vacío o nulo
            if (data === null || !data.idpersona) {
                $("#idcliente").val('0');
                $("#codigo_cliente").val('');
                $("#correo_cliente").val("sincorreo@gmail.com");
                $("#telefono_cliente").val("0");
                let nitValor = nit.toString().trim();
                let largo = nitValor.length;

                if (largo > 15) {
                    $("#tipo_documento_cliente").val("DPI");
                    $("#tipo_documento_cliente").selectpicker('refresh');

                } else if (largo <= 10) {
                    $("#tipo_documento_cliente").val("NIT");
                    $("#tipo_documento_cliente").selectpicker('refresh');
                }
                $("#tipo_cliente").val("PUBLICO");
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val("0");

                Swal.fire({
                    title: 'Mensaje!',
                    text: 'Se Creara Nuevo Cliente.',
                    icon: 'warning',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });
            } else {
                // Si el cliente existe, llena los campos
                $("#idcliente").val(data.idpersona);
                $("#nit").val(data.num_documento);
                $("#codigo_cliente").val(data.codigo_cliente);
                $("#telefono_cliente").val(data.telefono);
                $("#correo_cliente").val(data.email);
                $("#tipo_documento_cliente").val(data.tipo_documento);
                $("#tipo_documento_cliente").selectpicker('refresh');
                $("#tipo_cliente").val(data.tipo_cliente);
                $("#tipo_cliente").selectpicker('refresh');
                $("#txtbusquedaartcodebar").val("");
                $("#txtbusquedaartcodebar").focus()
                $("#descuento_cliente").val(data.descuento_cliente);

            }
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Error en la respuesta del servidor.',
                icon: 'error',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
        }

    })
}


function validarnitNombre() {

    var nombre_cliente = $("#nombre_cliente").val();

    $.post("../ajax/consultas.php?op=validarnitNombre", { nombre_cliente: nombre_cliente }, function (data, status) {
        // console.log(data);

        try {
            // Solo parsear una vez
            data = JSON.parse(data);

            // Verifica si el objeto data está vacío o nulo
            if (data === null || !data.idpersona) {
                $("#codigo_cliente").val('');
                $("#idcliente").val('0');
                $("#nit").val("C/F");
                $("#direccion_cliente").val("CIUDAD");
                $("#correo_cliente").val("sincorreo@gmail.com");
                $("#telefono_cliente").val("0");
                $("#tipo_documento_cliente").val("NIT");
                $("#tipo_documento_cliente").selectpicker('refresh');
                $("#tipo_cliente").val("Publico");
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val("0");

                Swal.fire({
                    title: 'Mensaje!',
                    text: 'Cliente no existe, se puede crear como nuevo.',
                    icon: 'warning',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });
            } else {
                // Si el cliente existe, llena los campos
                $("#idcliente").val(data.idpersona);
                $("#nit").val(data.num_documento);
                $("#codigo_cliente").val(data.codigo_cliente);
                $("#telefono_cliente").val(data.telefono);
                $("#direccion_cliente").val(data.direccion);
                $("#correo_cliente").val(data.email);
                $("#tipo_documento_cliente").val(data.tipo_documento);
                $("#tipo_documento_cliente").selectpicker('refresh');
                $("#tipo_cliente").val(data.tipo_cliente);
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val(data.descuento_cliente);

            }
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Error en la respuesta del servidor.',
                icon: 'error',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
        }
    });
}


function validarCodigo() {

    var codigo_cliente = $("#codigo_cliente").val();
    $.post("../ajax/consultas.php?op=validarCodigo", { codigo_cliente: codigo_cliente }, function (data, status) {

        try {
            data = JSON.parse(data);


            // Verifica si el objeto data está vacío o nulo
            if (data === null || !data.idpersona) {

                $("#idcliente").val('0');
                $("#nit").val("C/F");
                $("#direccion_cliente").val("CIUDAD");
                $("#nombre_cliente").val("CONSUMIDOR FINAL");
                $("#correo_cliente").val("sincorreo@gmail.com");
                $("#telefono_cliente").val("0");
                $("#tipo_documento_cliente").val("NIT");
                $("#tipo_documento_cliente").selectpicker('refresh');
                $("#tipo_cliente").val("PUBLICO");
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val("0");


                Swal.fire({
                    title: 'Mensaje!',
                    text: 'Cliente no existe se puede crear como nuevo.',
                    icon: 'warning',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                });
            } else {
                // Si el cliente existe, llena los campos
                $("#nit").val(data.num_documento);
                $("#nombre_cliente").val(data.nombre);
                $("#telefono_cliente").val(data.telefono);
                $("#direccion_cliente").val(data.direccion);
                $("#correo_cliente").val(data.email);
                $("#tipo_documento_cliente").val(data.tipo_documento);
                $("#tipo_documento_cliente").selectpicker('refresh');
                $("#tipo_cliente").val(data.tipo_cliente);
                $("#tipo_cliente").selectpicker('refresh');
                $("#descuento_cliente").val(data.descuento_cliente);

            }
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Error en la respuesta del servidor.',
                icon: 'error',
                timer: 2000, // 2 segundos
                timerProgressBar: true
            });
        }

    })
}


function listartbBusquedaCliente() {


    $('#myModalBusquedacliente').modal('show');
    tabla = $('#tbBusquedaCliente').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/consultas.php?op=listartbBusquedaCliente',
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


function RespuestavalidarnitNombre(idpersona, nombre, num_documento, direccion, telefono, email,
    tipo_documento, codigo_cliente, tipo_cliente, descuento_cliente) {
    Swal.fire({
        title: 'Mensaje!',
        text: "Cliente Agregado de Forma correcta",
        icon: 'success',
        timer: 2000, // 2 segundos
    });

    limpiardatoscliente();
    $("#codigo_cliente").val(codigo_cliente);
    $("#nit").val(num_documento);
    $("#nombre_cliente").val(nombre);
    $("#telefono_cliente").val(telefono);
    $("#direccion_cliente").val(direccion);
    $("#correo_cliente").val(email);
    $("#tipo_documento_cliente").val(tipo_documento);
    $("#tipo_documento_cliente").selectpicker('refresh');
    $("#idcliente").val(idpersona);
    $("#tipo_cliente").val(tipo_cliente);
    $("#tipo_cliente").selectpicker('refresh');
    $("#descuento_cliente").val(descuento_cliente);
}


function limpiardatoscliente() {
    $("#codigo_cliente").val("0");
    $("#nit").val("C/F");
    $("#nombre_cliente").val("CONSUMIDOR FINAL");
    $("#telefono_cliente").val("000-0000");
    $("#direccion_cliente").val("N/A");
    $("#correo_cliente").val("sincorreo@dominio.com");
    $("#idcliente").val("3");
    $("#tipo_documento_cliente").val("NIT");
    $("#tipo_documento_cliente").selectpicker('refresh');
    $("#tipo_cliente").val("PUBLICO");
    $("#tipo_cliente").selectpicker('refresh');
    $("#descuento_cliente").val("0");
}




////fin de cliente  



function mostrarOpcionesAdicionales() {
    const tipoPago = document.getElementById("tipo_pagoBacVisaNet").value;
    const opcionesAdicionalesDiv = document.getElementById("opcionesAdicionalesDiv");
    const opcionesAdicionales = document.getElementById("opcionesAdicionales");

    // Limpiar opciones previas
    opcionesAdicionales.innerHTML = "";

    // Verificar qué opción fue seleccionada y agregar las opciones correspondientes
    if (tipoPago in opcionesPorTipoPago) {
        opcionesAdicionalesDiv.style.display = "block";
        opcionesPorTipoPago[tipoPago].forEach(opcion => {
            opcionesAdicionales.innerHTML += `<option value="${opcion}">${opcion}</option>`;
        });
    } else {
        opcionesAdicionalesDiv.style.display = "none";
    }
}


// Mapeo de tipos de pago a sus opciones adicionales
const opcionesPorTipoPago = {
    VISANET: [
        "Pago Directo",
        "2 cuotas",
        "3 cuotas",
        "6 cuotas",
        "10 cuotas",
        "12 cuotas",
        "15 cuotas",
        "18 cuotas"
    ],
    BAC: [
        "Pago Directo"
    ]
};


$("#opcionesAdicionales").change(FopcionesAdicionales);

function FopcionesAdicionales() {
    var opcionesAdicionales = $("#opcionesAdicionales option:selected").text();
    if (opcionesAdicionales == 'Pago Directo') {
        $("#valor_tarjeta").val("0");
        modificarSubototalesTarjetaEfectivo();

    }
    else if (opcionesAdicionales == '2 cuotas') {
        $("#valor_tarjeta").val("5.25");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '3 cuotas') {
        $("#valor_tarjeta").val("5.75");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '6 cuotas') {
        $("#valor_tarjeta").val("7");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '10 cuotas') {
        $("#valor_tarjeta").val("7.25");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '12 cuotas') {
        $("#valor_tarjeta").val("8");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '15 cuotas') {
        $("#valor_tarjeta").val("10");
        modificarSubototalesTarjetaEfectivo();
    }
    else if (opcionesAdicionales == '18 cuotas') {
        $("#valor_tarjeta").val("12");
        modificarSubototalesTarjetaEfectivo();
    }
    else {
        $("#valor_tarjeta").val("0");
        modificarSubototalesTarjetaEfectivo();
    }
}

$("#valor_descuentoGeneral").change(descuentoGeneralPorcentajeCAlculo);

$("#descuento_general").change(descuentoGeneralPorcentaje);

function descuentoGeneralPorcentaje() {
    if (detalles > 0) {
    }
    else {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "No puede aplicar el descuento General porque no tienes productos agregados",
            showConfirmButton: false,
            timer: 1500,
        });
        $("#descuento_general").val("NO APLICA");
        $("#descuento_general").selectpicker('refresh');
        return;

    }

    var descuento_general = $("#descuento_general option:selected").text();
    if (descuento_general == 'NO APLICA') {
        $("#valor_descuentoGeneral").val("0");
        $("#valor_descuentoGeneral").prop('readonly', true); // Agregar readonly
        modificarSubototales();

    }
    else if (descuento_general == 'DESCUENTO GENERAL') {
        $("#valor_descuentoGeneral").val("0");
        $("#valor_descuentoGeneral").prop('readonly', false); // Quitar readonly
        modificarSubototales();
    }
    else {
        $("#valor_descuentoGeneral").val("0");
        $("#valor_descuentoGeneral").prop('readonly', true); // Agregar readonly
        modificarSubototales();
    }
}
function descuentoGeneralPorcentajeCAlculo() {
    if (detalles > 0) {
    }
    else {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "No puede aplicar el descuento General porque no tienes productos agregados",
            showConfirmButton: false,
            timer: 1500,
        });
        $("#descuento_general").val("NO APLICA");
        $("#descuento_general").selectpicker('refresh');
        return;

    }

    var descuento_general = $("#descuento_general option:selected").text();
    if (descuento_general == 'NO APLICA') {
        $("#valor_descuentoGeneral").val("0");
        $("#valor_descuentoGeneral").prop('readonly', true); // Agregar readonly
        modificarSubototales();

    }
    else if (descuento_general == 'DESCUENTO GENERAL') {
        $("#valor_descuentoGeneral").prop('readonly', false); // Quitar readonly
        modificarSubototales();
    }
    else {
        $("#valor_descuentoGeneral").val("0");
        $("#valor_descuentoGeneral").prop('readonly', true); // Agregar readonly
        modificarSubototales();
    }
}







function calculo() {

    var numeropagos = document.getElementById('numero_pagos').value;
    var totalventa = document.getElementById('ccredito').value;

    var resmontoabono = (totalventa / numeropagos);

    document.getElementById('monto_abono').innerHTML = resmontoabono;
    $("#monto_abono").val(resmontoabono.toFixed(2));

    ////////////

    const fechaPagoInput = $("#fecha_hora_pago").val(); // Fecha como cadena
    const fechaPago = new Date(fechaPagoInput); // Convertir a objeto Date

    // Calcular fecha de vencimiento en base a número de pagos
    if (!isNaN(numeropagos) && numeropagos > 0) {
        const fechaVencimiento = new Date(fechaPago); // Copiar la fecha inicial
        fechaVencimiento.setDate(fechaPago.getDate() + numeropagos * 30); // Sumar días (30 por cada pago)

        // Formatear la fecha en formato YYYY-MM-DD
        const fechaVencimientoFormateada = fechaVencimiento.toISOString().split("T")[0];

        // Actualizar el valor en el campo de vencimiento
        $("#fecha_hora_vencimiento_factura").val(fechaVencimientoFormateada);
    }

}


function obtenerClienteCotizacion(idcotizacion) {
    load();
    $.post("../ajax/cotizaciones.php?op=mostrar", { idcotizacion: idcotizacion }, function (data, status) {


        // Parseamos la data
        data = JSON.parse(data);

        // Verificamos si la data viene vacía o es nula
        if (!data || !data.idcotizacion) {
            Swal.fire({
                icon: 'info',
                title: 'Cotización cobrada',
                text: 'La cotización ya fue cobrada o no está disponible.',
                confirmButtonText: 'Aceptar'
            });
            return; // Detenemos la ejecución si la cotización ya fue cobrada o los datos están vacíos
        }
        cotizacionCargada = true; // Marcamos la bandera como true después de cargar
        //load();

        // Si la data es válida, mostramos el formulario y asignamos los valores
        mostrarform(true);

        $("#idcotizacion").val(data.idcotizacion);
        $("#codigo_cliente").val(data.codigo_cliente);
        $("#nit").val(data.nit);
        $("#nombre_cliente").val(data.nombre_cliente);
        $("#telefono_cliente").val(data.telefono_cliente);
        $("#direccion_cliente").val(data.direccion_cliente);
        $("#correo_cliente").val(data.correo_cliente);
        $("#tipo_cliente").val(data.tipo_cliente);
        $("#tipo_cliente").selectpicker('refresh');
        $("#idcliente").val(data.idcliente);

        $("#tipo_documento_cliente").val(data.tipo_documento_cliente);
        $("#tipo_documento_cliente").selectpicker('refresh');

        $("#total_venta").val(data.total_venta);
        $("#total_ventades").val(data.total_ventades);

        $("#total_venta_r").val(data.total_venta);
        $("#total_ventades_r").val(data.total_ventades);

        $("#forma_pago").val(data.forma_pago);
        $("#forma_pago").selectpicker('refresh');

        // Llamamos a la función para obtener el detalle de la cotización
        obtenerdetallecotizacion(idcotizacion);
    });
}







$("#forma_pago").change(mostrarFormaPago);

function mostrarFormaPago() {
    var forma_pago = $("#forma_pago option:selected").text();
    if (forma_pago == 'Credito') {
        $("#div_formapago").hide();
        $("#valor_tarjeta").val(0);
        modificarSubototalesTarjetaEfectivo();

    }
    else if (forma_pago == 'Efectivo/Tarjeta') {
        $("#div_formapago").show();
    }
    else if (forma_pago == 'Tarjeta') {
        $("#div_formapago").show();
    }
    else if (forma_pago == 'Efectivo') {
        $("#tipo_pagoBacVisaNet").val("Seleccione Uno");
        $("#tipo_pagoBacVisaNet").selectpicker('refresh');

        $("#opcionesAdicionales").val("Pago Directo");
        $("#opcionesAdicionales").selectpicker('refresh');

        $("#div_formapago").hide();
        $("#valor_tarjeta").val(0);
        modificarSubototalesTarjetaEfectivo();
    }
}

$("#tipo_compra_GastoaVenta").change(mostrarFormaCombustible);

function mostrarFormaCombustible() {
    var tipo_compra_GastoaVenta = $("#tipo_compra_GastoaVenta option:selected").text();
    if (tipo_compra_GastoaVenta == 'Combustible') {
        $("#tipo_combus").show();
        $("#no_galo").show();

    }

    else {
        $("#tipo_combus").hide();
        $("#no_galo").hide();
    }
}

//Función limpiar
function limpiar() {
    $("#idventa").val("");
    $("#idcategoria").val("1");
    $("#datos1").val("");
    $("#codigo_cliente").val("");
    $("#nit").val("CF");
    $("#nombre_cliente").val("CONSUMIDOR FINAL");
    $("#telefono_cliente").val("0");
    $("#direccion_cliente").val("CIUDAD");
    $("#correo_cliente").val("soporte@gmail.com");
    $("#idcliente").val("1");
    $("#tipo_documento_cliente").val("NIT");
    $("#tipo_documento_cliente").selectpicker('refresh');


    $("#idcotizacion").val("");
    $("#forma_pago").val("Efectivo");
    $("#forma_pago").selectpicker('refresh');

    $("#tipo_comprobante").val("Envio");
    $("#tipo_comprobante").selectpicker('refresh');

    $("#total_venta").val("");
    $("#total_ventades").val("");

    $("#total_venta_r").val("");
    $("#total_ventades_r").val("");

    $("#cefectivo").val("0");
    $("#ccredito").val("0");
    $("#ctransferencia").val("0");
    $("#ctarjeta").val("0");
    $("#rescambio").val("0");

    $('#myModalImpresionFAc').modal('hide');

    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);
    $('#fecha_hora_GastoaVenta').val(today);
    $('#fecha_hora_vencimiento_factura').val(today);
    $('#fecha_hora_pago').val(today);


    $('#numero_pagos').val("0");
    $('#monto_abono').val("0");


}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        // listarArticulos();

        $("#btnGuardar").hide();
        // $("#btnGuardar").disabled();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        // $("#fecha_hora_cobro2").hide(); 
        detalles = 0;
    }
    else {
        //$("#txtbusquedaarticulo").focus() 
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnGuardar").hide();
        // $("#btnGuardar").disabled();
        $("#fecha_hora_cobro2").hide();
        $("#dias_credito2").hide();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función Listar
function listar() {
    var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
    var fecha_fin_reporte = $("#fecha_fin_reporte").val();

    tabla = $('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                {
                    extend: 'pdfHtml5',
                    text: 'Exportar PDF',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 7;
                        doc.styles.tableHeader.fontSize = 8;
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;
                        table.widths = new Array(columnCount).fill('*');
                    }
                },
                'colvis'   // 👉 botón para que el cliente elija qué columnas mostrar
            ],
            "ajax":
            {
                url: '../ajax/venta.php?op=listar',
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte },
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                total = api
                    .column(6)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total7 = api
                    .column(7)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total9 = api
                    .column(9)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total10 = api
                    .column(10)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total11 = api
                    .column(11)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total12 = api
                    .column(12)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal7 = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal9 = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal10 = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal11 = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal12 = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);


                // Update footer
                $(api.column(6).footer(0)).html(
                    pageTotal.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(7).footer(0)).html(
                    pageTotal7.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(9).footer(0)).html(
                    pageTotal9.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(10).footer(0)).html(
                    pageTotal10.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(11).footer(0)).html(
                    pageTotal11.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );


                $(api.column(12).footer(0)).html(
                    pageTotal12.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[1, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}





//Función ListarArticulos
function listarArticulos() {
    var descuento_cliente = $("#descuento_cliente").val();
    var idcliente = $("#idcliente").val();

    if (descuento_cliente == "0") {
        tabla = $('#tblarticulos').dataTable(
            {
                "aProcessing": true,//Activamos el procesamiento del datatables
                "aServerSide": true,//Paginación y filtrado realizados por el servidor
                dom: 'Bfrtip',//Definimos los elementos del control de tabla
                buttons: [

                ],
                "ajax":
                {
                    url: '../ajax/venta.php?op=listarArticulosVentaCantidad',
                    type: "get",
                    dataType: "json",
                    error: function (e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 10,//Paginación
                "order": [[0, "desc"]]//Ordenar (columna,orden)
            }).DataTable();
    } else if (descuento_cliente > "0") {
        tabla = $('#tblarticulos').dataTable(
            {
                "aProcessing": true,//Activamos el procesamiento del datatables
                "aServerSide": true,//Paginación y filtrado realizados por el servidor
                dom: 'Bfrtip',//Definimos los elementos del control de tabla
                buttons: [

                ],
                "ajax":
                {
                    url: '../ajax/venta.php?op=listarArticulosVentaCantidad_descuento',
                    type: "get",
                    data: { idcliente: idcliente, descuento_cliente: descuento_cliente },
                    dataType: "json",
                    error: function (e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 10,//Paginación
                "order": [[0, "desc"]]//Ordenar (columna,orden)
            }).DataTable();
    }



}




//Función para guardar o editar

function agruparDatos() {
    const datos = {
        articulos: {
            idarticulo: [],
            precio_compra: [],
            stockinven: [],
            cantidadpresentacion: [],
            cantidad: [],
            totalcantidadpresentacion: [],
            presentacion: [],
            presen: [],
            precio_ventaSistema: [],
            precio_ventaSistema2: [],
            q_ref: [],
            precio_venta: [],
            precio_recargoPV: [],
            precio_recargoQRef: [],
            descuento_permitido: [],
            descuento_porcentaje: [],
            descripcion_detalle: [],
            subtotal1: [],
            subtotaldes1: [],
            check_extras: [],
            idarticuloExtra_extras: [],
            cantidad_extra: [],
            idproducto_extra: [],
            tipo_item_extras: []
        }
    };

    $('#detalles .filas').each(function () {
        const idarticulo = $(this).find('input[name="idarticulo[]"]').val();
        const precio_compra = $(this).find('input[name="precio_compra[]"]').val();
        const stockinven = $(this).find('input[name="stockinven[]"]').val();
        const cantidadpresentacion = $(this).find('input[name="cantidadpresentacion[]"]').val();
        const cantidad = $(this).find('input[name="cantidad[]"]').val();
        const totalcantidadpresentacion = $(this).find('input[name="totalcantidadpresentacion[]"]').val();
        const presentacion = $(this).find('select[name="presentacion[]"]').val();
        const presen = $(this).find('input[name="presen[]"]').val();
        const precio_ventaSistema = $(this).find('input[name="precio_ventaSistema[]"]').val();
        const precio_ventaSistema2 = $(this).find('input[name="precio_ventaSistema2[]"]').val();
        const q_ref = $(this).find('input[name="q_ref[]"]').val();
        const precio_venta = $(this).find('input[name="precio_venta[]"]').val();
        const precio_recargoPV = $(this).find('input[name="precio_recargoPV[]"]').val();
        const precio_recargoQRef = $(this).find('input[name="precio_recargoQRef[]"]').val();
        const descuento_permitido = $(this).find('input[name="descuento_permitido[]"]').val();
        const descuento_porcentaje = $(this).find('input[name="descuento_porcentaje[]"]').val();
        const descripcion_detalle = $(this).find('input[name="descripcion_detalle[]"]').val();
        const subtotal1 = $(this).find('input[name="subtotal1[]"]').val();
        const subtotaldes1 = $(this).find('input[name="subtotaldes1[]"]').val();


        datos.articulos.idarticulo.push(idarticulo);
        datos.articulos.precio_compra.push(precio_compra);
        datos.articulos.stockinven.push(stockinven);
        datos.articulos.cantidadpresentacion.push(cantidadpresentacion);
        datos.articulos.cantidad.push(cantidad);
        datos.articulos.totalcantidadpresentacion.push(totalcantidadpresentacion);
        datos.articulos.presentacion.push(presentacion);
        datos.articulos.presen.push(presen);
        datos.articulos.precio_ventaSistema.push(precio_ventaSistema);
        datos.articulos.precio_ventaSistema2.push(precio_ventaSistema2);
        datos.articulos.q_ref.push(q_ref);
        datos.articulos.precio_venta.push(precio_venta);
        datos.articulos.precio_recargoPV.push(precio_recargoPV);
        datos.articulos.precio_recargoQRef.push(precio_recargoQRef);
        datos.articulos.descuento_permitido.push(descuento_permitido);
        datos.articulos.descuento_porcentaje.push(descuento_porcentaje);
        datos.articulos.descripcion_detalle.push(descripcion_detalle);
        datos.articulos.subtotal1.push(subtotal1);
        datos.articulos.subtotaldes1.push(subtotaldes1);
    });

    // Recolectamos los extras seleccionados
    $('#detalles .extras-row').each(function () {
        const checkboxes = $(this).find('input[type="checkbox"]:checked');
        checkboxes.each(function () {
            const checkbox = $(this);
            datos.articulos.check_extras.push(checkbox.val());
            datos.articulos.idarticuloExtra_extras.push(checkbox.data('idarticulo-extra'));
            datos.articulos.cantidad_extra.push(checkbox.data('cantidad-extra'));
            datos.articulos.idproducto_extra.push(checkbox.data('idproducto-extra'));
            datos.articulos.tipo_item_extras.push(checkbox.data('tipo-item'));
        });
    });

    // Si no hay extras seleccionados, vaciar los arrays de extras por seguridad
    if (datos.articulos.check_extras.length === 0) {
        datos.articulos.check_extras = [];
        datos.articulos.idarticuloExtra_extras = [];
        datos.articulos.cantidad_extra = [];
        datos.articulos.idproducto_extra = [];
    }
    const datosJSON = JSON.stringify(datos);

    localStorage.setItem('datosArticulosv', datosJSON);
    //console.log(datosJSON);
}


function guardaryeditar(e) {


    if (detalles > 0) {

    }
    else {
        alert("No se puede Guardar porque no has agregado item a tu venta ");
        return;
    }
    agruparDatos();
    let datosArticulosv = JSON.parse(localStorage.getItem('datosArticulosv'));
    var forma_pago = document.getElementById('forma_pago').value;
    var tipo_pagoBacVisaNet = document.getElementById('tipo_pagoBacVisaNet').value;

    const cefectivoCredito = parseFloat($("#ccredito").val().trim());
    const cefectivo_tarjeta = parseFloat($("#ctarjeta").val().trim());
    const cefectivoTransferencia = parseFloat($("#ctransferencia").val().trim());
    const cefectivo = parseFloat($("#cefectivo").val().trim());



    const numero_pagos = parseFloat($("#numero_pagos").val().trim());
    const fechaPagoInput = $("#fecha_hora_pago").val(); // Fecha como cadena
    const fechaPago = new Date(fechaPagoInput); // Convertir a objeto Date    
    const fecha_hora_vencimiento_factura = $("#fecha_hora_vencimiento_factura").val();
    const monto_abono = parseFloat($("#monto_abono").val().trim());
    const rescambio = parseFloat($("#rescambio").val().trim());

    var tipo_comprobante = document.getElementById('tipo_comprobante').value.trim();

    // Obtener la fecha de hoy
    const hoy = new Date();
    const fechaActual = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate()); // Fecha sin hora

    if (tipo_comprobante == 'Factura') {
        if (forma_pago == "Credito") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Quiere Generar una Factura con forma de pago Credito no se puede tienes que generar una Cambiaria ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

    }


    if (tipo_comprobante == 'Cambiaria') {
        if (forma_pago != "Credito") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Quiere Generar una Factura Cambiaria Pero no tiene seleccionado La forma credito, cambia a Forma de pago Credito para poder seguir  ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivoCredito) || cefectivoCredito <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ingresa el valor en Credito no lo puedes dejar en blanco ni con valor negativo ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(numero_pagos) || numero_pagos <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ingresa el numero de pagos a aplicar a Facturacion Cambiaria ",
                footer: '<a >Venta.</a>'
            });
            return;
        }
        if (isNaN(monto_abono) || monto_abono <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "El monto abono no lo puedes dejar en blanco ",
                footer: '<a >Venta.</a>'
            });
            return;
        }
    }

    if (forma_pago == "Credito") {


        if (isNaN(cefectivoTransferencia)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Transferencia ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        // Validación individual para que ambos valores sean mayores a 0
        if (isNaN(cefectivo)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Efectivo",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivo_tarjeta)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Tarjeta ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivoCredito) || cefectivoCredito <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ingresa el valor en Credito no lo puedes dejar en blanco ni con valor negativo ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

    }
    else if (forma_pago == "Efectivo") {
        if (isNaN(cefectivoTransferencia)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Transferencia  ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        // Validación individual para que ambos valores sean mayores a 0
        if (isNaN(cefectivo) || cefectivo <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ingresa el valor en Efectivo no lo puedes dejar en blanco ni con valor negativo",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivo_tarjeta)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Tarjeta ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivoCredito) || cefectivoCredito > 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Credito, y no puedes poner una cantidad en credito",
                footer: '<a >Venta.</a>'
            });
            return;
        }
    }
    else if (forma_pago === "Efectivo/Tarjeta") {

        if (isNaN(cefectivoCredito) || cefectivoCredito > 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Credito, y no puedes poner una cantidad en credito",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivoTransferencia)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Transferencia",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        // Validación individual para que ambos valores sean mayores a 0
        if (isNaN(cefectivo) || cefectivo <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ingresa el valor en Efectivo no lo puedes dejar en blanco ni con valor negativo",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivo_tarjeta) || cefectivo_tarjeta <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ingresa el valor en Tarjeta no lo puedes dejar en blanco ni con valor negativo",
                footer: '<a >Venta.</a>'
            });
            return;
        }
    }

    else if (forma_pago == "Transferencia") {
        if (isNaN(cefectivoCredito)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Credito",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivo)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Efectivo ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivo_tarjeta)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Tarjeta ",
                footer: '<a >Venta.</a>'
            });
            return;
        }

        if (isNaN(cefectivoTransferencia) || cefectivoTransferencia <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ingresa el valor en Transferencia no lo puedes dejar en blanco ni con valor negativo",
                footer: '<a >Venta.</a>'
            });
            return;
        }

    }

    else if (forma_pago == 'Tarjeta') {
        if (cefectivo_tarjeta == "" || cefectivo_tarjeta <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Puedes dejar vacio ni con 0 en Tarjeta tienes que ingrsar la cantidad recibida",
                footer: '<a >Venta.</a>'
            });
            //alert("No Puedes dejar vacio ni con 0 en Tarjeta tienes que ingrsar la cantidad recibida ");
            return;
        }
        if (isNaN(cefectivo) || isNaN(cefectivoTransferencia)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puede dejar vacio en Efectivo, Credito y Transferencia si no sabes que poner ingresa 0",
                footer: '<a >Venta.</a>'
            });
            //alert("Solo Tarjeta debe contener valor en Tarjeta no en Efectivo, Credito o Transferencia ");
            return;
        }
        if (isNaN(cefectivoCredito) || cefectivoCredito > 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No puedes dejar vacio Credito, y no puedes poner una cantidad en credito",
                footer: '<a >Venta.</a>'
            });
            return;
        }
    }

    if ($("#aperturaCaja").val().trim() == "") {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No tienes Apertura de Caja hecho, realiza una apertura y luego puedes hacer tu venta!",
            footer: '<a >Debes de Aperturar tu caja.</a>'
        });
        return; // Detenemos el proceso sin recargar la página
    }

    if (rescambio < 0) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Tienes que ingresar la distribucion de dinero de forma correcta!",
            footer: '<a >Error en Efectivo.</a>'
        });
        return; // Detenemos el proceso sin recargar la página
    }





    //var totalventa = document.getElementById('total_venta').value;
    var totalventa = document.getElementById('total_venta_r').value;
    var numdocumentocliente = document.getElementById('nit').value.trim();


    if (tipo_comprobante != 'Envio') {
        if (parseFloat(totalventa) >= 2500) {
            if (numdocumentocliente != 'CF' && numdocumentocliente != 'C/F' && numdocumentocliente != 'cf' && numdocumentocliente != 'c/f') {
                var tipo_comprobante1 = $("#tipo_comprobante").val();
                e.preventDefault(); //No se activará la acción predeterminada del evento
                var formData = new FormData($("#formulario")[0]);
                //PARA LOS CREDITOS
                const detallesCreditoJSON = JSON.stringify(detallesCredito);
                formData.append("detalles_credito", detallesCreditoJSON);
                //
                formData.append("datosArticulosv", JSON.stringify(datosArticulosv));
                load();
                $.ajax({
                    url: "../ajax/venta.php?op=guardaryeditar",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function (datos) {
                        console.log("datos 1 ", datos);
                        Swal.close();
                        var datos = JSON.parse(datos); // Parsea el JSON recibido

                        // 🚨 BLOQUEA FONDO, BLOQUEA ESC, BLOQUEA CLIC AFUERA
                        $('#myModalImpresionFAc').off('keydown'); // Previene cerrar con ESC

                        // Prevenir que el modal se cierre al hacer clic en el backdrop
                        $('#myModalImpresionFAc').off('hide.bs.modal');
                        $('#myModalImpresionFAc').on('hide.bs.modal', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        });

                        // Prevenir clics en el backdrop
                        $('.modal-backdrop').off('click');
                        $(document).off('click', '.modal-backdrop');

                        $('#myModalImpresionFAc').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });

                        // Después de mostrar el modal, prevenir clics en el backdrop nuevamente
                        $('#myModalImpresionFAc').on('shown.bs.modal', function () {
                            $('.modal-backdrop').off('click').on('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            });
                        });
                        $("#idventa_impresion").val(datos.idventanew);
                        $("#tipo_comprobante_impresion").val(datos.tipo_comprobante);

                    }

                });



            } else {
                alert("su Factura tiene que llevar Nit o Dpi no puede generar con cf")
            }

        } else {
            e.preventDefault(); //No se activará la acción predeterminada del evento
            var formData = new FormData($("#formulario")[0]);
            //PARA LOS CREDITOS
            const detallesCreditoJSON = JSON.stringify(detallesCredito);
            formData.append("detalles_credito", detallesCreditoJSON);
            //
            formData.append("datosArticulosv", JSON.stringify(datosArticulosv));
            load();
            $.ajax({
                url: "../ajax/venta.php?op=guardaryeditar",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function (datos) {
                    console.log("datos 2 ", datos);
                    Swal.close();
                    var datos = JSON.parse(datos); // Parsea el JSON recibido
                    // console.log(datos)

                    // 🚨 BLOQUEA FONDO, BLOQUEA ESC, BLOQUEA CLIC AFUERA
                    $('#myModalImpresionFAc').off('keydown'); // Previene cerrar con ESC

                    // Prevenir que el modal se cierre al hacer clic en el backdrop
                    $('#myModalImpresionFAc').off('hide.bs.modal');
                    $('#myModalImpresionFAc').on('hide.bs.modal', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    });

                    // Prevenir clics en el backdrop
                    $('.modal-backdrop').off('click');
                    $(document).off('click', '.modal-backdrop');

                    $('#myModalImpresionFAc').modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });

                    // Después de mostrar el modal, prevenir clics en el backdrop nuevamente
                    $('#myModalImpresionFAc').on('shown.bs.modal', function () {
                        $('.modal-backdrop').off('click').on('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        });
                    });
                    $("#idventa_impresion").val(datos.idventanew);
                    $("#tipo_comprobante_impresion").val(datos.tipo_comprobante);

                }

            });
        }
    } else {

        e.preventDefault(); //No se activará la acción predeterminada del evento
        var formData = new FormData($("#formulario")[0]);
        //PARA LOS CREDITOS
        const detallesCreditoJSON = JSON.stringify(detallesCredito);
        formData.append("detalles_credito", detallesCreditoJSON);
        //
        formData.append("datosArticulosv", JSON.stringify(datosArticulosv));
        load();
        $.ajax({
            url: "../ajax/venta.php?op=guardaryeditar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function (datos) {
                console.log("datos 3 ", datos);
                Swal.close();
                var datos = JSON.parse(datos); // Parsea el JSON recibido

                // 🚨 BLOQUEA FONDO, BLOQUEA ESC, BLOQUEA CLIC AFUERA
                $('#myModalImpresionFAc').off('keydown'); // Previene cerrar con ESC

                // Prevenir que el modal se cierre al hacer clic en el backdrop
                $('#myModalImpresionFAc').off('hide.bs.modal');
                $('#myModalImpresionFAc').on('hide.bs.modal', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                // Prevenir clics en el backdrop
                $('.modal-backdrop').off('click');
                $(document).off('click', '.modal-backdrop');

                $('#myModalImpresionFAc').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });

                // Después de mostrar el modal, prevenir clics en el backdrop nuevamente
                $('#myModalImpresionFAc').on('shown.bs.modal', function () {
                    $('.modal-backdrop').off('click').on('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    });
                });
                $("#idventa_impresion").val(datos.idventanew);
                $("#tipo_comprobante_impresion").val(datos.tipo_comprobante);


            }

        });
    }

}

function cancelarOperacion() {
    window.location.reload();
}


function impresionticket58mm() {
    var tipo_comprobante_impresion = $("#tipo_comprobante_impresion").val();

    if (tipo_comprobante_impresion == "Envio") {
        var url = "../reportes/exTicket58mm.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Factura") {
        var url = "../reportes/exTicket_Fel58mm.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Cambiaria") {
        var url = "../reportes/exTicket_Fel_FCAM58mm.php?id=" + $("#idventa_impresion").val();
    }

    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario)
}

function impresionticket79mm() {
    var tipo_comprobante_impresion = $("#tipo_comprobante_impresion").val();

    if (tipo_comprobante_impresion == "Envio") {
        var url = "../reportes/exTicket.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Factura") {
        var url = "../reportes/exTicket_Fel.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Cambiaria") {
        var url = "../reportes/exTicket_FelFCAM.php?id=" + $("#idventa_impresion").val();
    }

    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario)
}

function impresionticketCarta() {
    var tipo_comprobante_impresion = $("#tipo_comprobante_impresion").val();

    if (tipo_comprobante_impresion == "Envio") {
        var url = "../reportes/exVentaFormatoCarta.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Factura") {
        var url = "../reportes/exVentaFormatoCarta_Fel.php?id=" + $("#idventa_impresion").val();
    }
    else if (tipo_comprobante_impresion == "Cambiaria") {
        var url = "../reportes/exVentaFormatoCarta_FelFCAM.php?id=" + $("#idventa_impresion").val();
    }

    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario)
}


function impresionBlanco() {

    var url = "../reportes/exVentaBlanco.php?id=" + $("#idventa_impresion").val();

    abrirVentanaetiqueta(url);
    $('#myModalImpresionFAc').modal('hide');
    // Espera un breve tiempo antes de recargar
    setTimeout(function () {
        window.location.reload();
    }, 700); // Espera 500 ms (ajusta el tiempo según sea necesario)
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
function anular(idventa) {
    var valor = prompt("Ingresa la contraseña de de Autorizacion", "");
    if (valor == "5820005710") {

        bootbox.confirm("¿Está Seguro de anular la venta?", function (result) {
            load();
            if (result) {
                $.post("../ajax/venta.php?op=anular", { idventa: idventa }, function (e) {
                    Swal.fire({
                        title: 'Mensaje!',
                        text: e,
                        icon: 'success',
                        //  timer: 2000, // 2 segundos
                        timerProgressBar: true,
                        willClose: () => {

                            Swal.close();
                            window.location.reload();
                        }
                    });

                });
            }
        })
    }
    else {
        alert("Contraseña no válida: [" + valor + "]");
    }
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto = 12;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto() {
    var tipo_comprobante = $("#tipo_comprobante option:selected").text();
    let forma_pago = $("#forma_pago").val();
    if (tipo_comprobante == 'Factura') {
        $("#impuesto").val("0");
        $("#div_facCambiaria").hide();
    }
    else if (tipo_comprobante == 'Envio') {
        $("#impuesto").val("0");
        if (forma_pago == "Credito") {
            $("#div_facCambiaria").show();
        } else {
            $("#div_facCambiaria").hide();
        }
    }
    else if (tipo_comprobante == 'Cambiaria') {
        $("#impuesto").val("0");
        $("#div_facCambiaria").show();
    } else {
        $("#impuesto").val("0");
        $("#div_facCambiaria").hide();
    }
}


function agregarDetalleCantidadRapida(idarticulo, nombre, precio_venta, stock, descuento_porcentaje,
    precio_rango1, precio_rango1_Dos,
    precio_rango2, precio_rango2_Dos,
    precio_rango3, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango1_Distribuidor, precio_rango1_Mayorista,
    precio_rango2_MecanicoDos, precio_rango2_DistribuidorDos, precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres, precio_rango3_DistribuidorTres, precio_rango3_MayoristaTres,
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
    precio_activado, facturar_cero, precio_compra, cantidad, cantidadpresentacion) {

    if (facturar_cero == 'NO') {
        // Validar si la cantidad es mayor al stock disponible
        if (parseInt(cantidad) > parseInt(stock)) {
            alert("La cantidad ingresada es mayor al stock disponible");
            return;
        }
    }
    if (cantidad == 0 || cantidad == null) {
        var cantidad = 1;
    } else {
        var cantidad = cantidad;
    }

    precio_activado = precio_activado.toString().trim();

    var stockinven = stock;
    var presen = 'UNIDAD';
    var totalcantidadpresentacion = cantidad * cantidadpresentacion;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        /*
        var exist = false;
        $('#detalles').children("tbody").children("tr").each(function (index) {
            var idart = $(this).attr("data-id")
            if (idart == idarticulo) {
                exist = true;
            }
        })

        if (!exist) {
        */
        var subtotal = cantidad * precio_venta;
        var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
            '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cantidad' + cont + '" name="cantidad[]" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                                '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)"  >
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
            `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_venta + `">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_venta + `">
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + precio_venta + `" ${precio_activado.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
            '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '<td><button type="button" onclick="mostrarextras(' + cont + ', \'' + idarticulo + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
            '</tr>' +
            '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
            '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
            '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
            '</td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $(fila).prependTo('#detalles');
        /*
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
            $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
        }
        */

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}




function agregarDetalleCantidad(idarticulo, nombre, precio_venta, stock, descuento_porcentaje,
    precio_rango1, precio_rango1_Dos,
    precio_rango2, precio_rango2_Dos,
    precio_rango3, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango1_Distribuidor, precio_rango1_Mayorista,
    precio_rango2_MecanicoDos, precio_rango2_DistribuidorDos, precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres, precio_rango3_DistribuidorTres, precio_rango3_MayoristaTres,
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
    precio_activado, facturar_cero, precio_compra, cantidad) {

    if (facturar_cero == 'NO') {
        // Validar si la cantidad es mayor al stock disponible
        if (parseInt(cantidad) > parseInt(stock)) {
            alert("La cantidad ingresada es mayor al stock disponible");
            return;
        }
    }
    if (cantidad == 0 || cantidad == null) {
        var cantidad = 1;
    } else {
        var cantidad = cantidad;
    }

    precio_activado = precio_activado.toString().trim();

    var stockinven = stock;
    var presen = 'UNIDAD';
    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        // Quitamos la validación 'exist' para que siempre agregue una nueva fila (separado)
        var subtotal = cantidad * precio_venta;
        var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
            '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cantidad' + cont + '"  name="cantidad[]" value="' + cantidad + '"><input style="width:100px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
            <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                            '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)"  >
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
            `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
            <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_venta + `">
            <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_venta + `">
            <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + precio_venta + `" ${precio_activado.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
            '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '<td><button type="button" onclick="mostrarextras(' + cont + ', \'' + idarticulo + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
            '</tr>' +
            '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
            '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
            '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
            '</td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $(fila).prependTo('#detalles');
        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}




function agregarDetalle(idarticulo, nombre, precio_venta, stock, descuento_porcentaje,
    precio_rango1, precio_rango1_Dos,
    precio_rango2, precio_rango2_Dos,
    precio_rango3, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango1_Distribuidor, precio_rango1_Mayorista,
    precio_rango2_MecanicoDos, precio_rango2_DistribuidorDos, precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres, precio_rango3_DistribuidorTres, precio_rango3_MayoristaTres,
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
    precio_activado, facturar_cero, precio_compra) {


    precio_activado = precio_activado.toString().trim();

    var cantidad = 1;
    var stockinven = stock;
    var presen = 'UNIDAD';
    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        var exist = false;
        /*  $('#detalles').children("tbody").children("tr").each(function (index) {
              var idart = $(this).attr("data-id")
              if (idart == idarticulo) {
                  exist = true;
              }
          })
  
          if (!exist) {*/
        var subtotal = cantidad * precio_venta;
        var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
            '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                                '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)">
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
            `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_venta + `">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_venta + `">
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + precio_venta + `" ${precio_activado.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
            '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '<td><button type="button" onclick="mostrarextras(' + cont + ', \'' + idarticulo + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
            '</tr>' +
            '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
            '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
            '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
            '</td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $(fila).prependTo('#detalles');
        /*
        } else {
           // var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
          //  $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
        }
        */

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function obtenerdetallecotizacion(idcotizacion) {
    $.post("../ajax/cotizaciones.php?op=paraventa", { idcotizacion: idcotizacion }, function (data) {

        data = JSON.parse(data);
        Swal.close()

        $.each(data, function (i, item) {
            agregarDetalle2(item.idarticulo, item.nombre, item.precio_venta, item.stock, item.descuento_porcentaje,
                item.precio_rango1, item.precio_rango1_Dos,
                item.precio_rango2, item.precio_rango2_Dos,
                item.precio_rango3, item.precio_rango3_Dos,
                item.precio_rango1_Mecanico, item.precio_rango1_Distribuidor, item.precio_rango1_Mayorista,
                item.precio_rango2_MecanicoDos, item.precio_rango2_DistribuidorDos, item.precio_rango2_MayoristaDos,
                item.precio_rango3_MecanicoTres, item.precio_rango3_DistribuidorTres, item.precio_rango3_MayoristaTres,
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
                item.precio_activado, item.facturar_cero, item.precio_compra,
                item.cantidadpresentacion, item.totalcantidadpresentacion, item.presen,
                item.precio_ventaSistema, item.precio_ventaSistema2, item.q_ref,
                item.precio_recargoPV, item.precio_recargoQRef, item.descuento, item.descripcion_detalle, item.cantidad);
        });
    })
}


function agregarDetalle2(idarticulo, nombre, precio_venta, stock, descuento_porcentaje,
    precio_rango1, precio_rango1_Dos,
    precio_rango2, precio_rango2_Dos,
    precio_rango3, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango1_Distribuidor, precio_rango1_Mayorista,
    precio_rango2_MecanicoDos, precio_rango2_DistribuidorDos, precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres, precio_rango3_DistribuidorTres, precio_rango3_MayoristaTres,
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
    precio_activado, facturar_cero, precio_compra, cantidadpresentacion,
    totalcantidadpresentacion, presen, precio_ventaSistema, precio_ventaSistema2,
    q_ref, precio_recargoPV, precio_recargoQRef, descuento, descripcion_detalle, cantidad) {

    console.log(presen + 'presen');
    let idcotizacion_r = $("#idcotizacion").val();

    precio_activado = precio_activado.toString().trim();
    //console.log("q_ref ", q_ref);

    var cantidad = cantidad;
    var stockinven = stock;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        var exist = false;
        $('#detalles').children("tbody").children("tr").each(function (index) {
            var idart = $(this).attr("data-id")
            if (idart == idarticulo) {
                exist = true;
            }
        })

        if (!exist) {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                                '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)"  >
                `+ (parseFloat(stock_unidad) > 0 ? `<option value="` + nombre_01 + `" ` + (presen === nombre_01 ? 'selected' : '') + `>${nombre_01}</option>` : ``) + ` 
                `+ (parseFloat(stock_blister) > 0 ? `<option value="` + nombre_02 + `" ` + (presen === nombre_02 ? 'selected' : '') + `>${nombre_02}</option>` : ``) + ` 
                `+ (parseFloat(stock_caja) > 0 ? `<option value="` + nombre_03 + `" ` + (presen === nombre_03 ? 'selected' : '') + `>${nombre_03}</option>` : ``) + ` 
                `+ (parseFloat(stock_fardo) > 0 ? `<option value="` + nombre_04 + `" ` + (presen === nombre_04 ? 'selected' : '') + `>${nombre_04}</option>` : ``) + ` 
                `+ (parseFloat(stock_sacos) > 0 ? `<option value="` + nombre_05 + `" ` + (presen === nombre_05 ? 'selected' : '') + `>${nombre_05}</option>` : ``) + ` 
                `+ (parseFloat(stock_paquete) > 0 ? `<option value="` + nombre_06 + `" ` + (presen === nombre_06 ? 'selected' : '') + `>${nombre_06}</option>` : ``) + ` 
                `+ (parseFloat(stock_07) > 0 ? `<option value="` + nombre_07 + `" ` + (presen === nombre_07 ? 'selected' : '') + `>${nombre_07}</option>` : ``) + ` 
                `+ (parseFloat(stock_08) > 0 ? `<option value="` + nombre_08 + `" ` + (presen === nombre_08 ? 'selected' : '') + `>${nombre_08}</option>` : ``) + ` 
                `+ (parseFloat(stock_09) > 0 ? `<option value="` + nombre_09 + `" ` + (presen === nombre_09 ? 'selected' : '') + `>${nombre_09}</option>` : ``) + ` 
                `+ (parseFloat(stock_10) > 0 ? `<option value="` + nombre_10 + `" ` + (presen === nombre_10 ? 'selected' : '') + `>${nombre_10}</option>` : ``) + ` 
                `+ (parseFloat(stock_11) > 0 ? `<option value="` + nombre_11 + `" ` + (presen === nombre_11 ? 'selected' : '') + `>${nombre_11}</option>` : ``) + ` 
                `+ (parseFloat(stock_12) > 0 ? `<option value="` + nombre_12 + `" ` + (presen === nombre_12 ? 'selected' : '') + `>${nombre_12}</option>` : ``) + ` 
                `+ (parseFloat(stock_13) > 0 ? `<option value="` + nombre_13 + `" ` + (presen === nombre_13 ? 'selected' : '') + `>${nombre_13}</option>` : ``) + ` 
                `+ (parseFloat(stock_14) > 0 ? `<option value="` + nombre_14 + `" ` + (presen === nombre_14 ? 'selected' : '') + `>${nombre_14}</option>` : ``) + ` 
                `+ (parseFloat(stock_15) > 0 ? `<option value="` + nombre_15 + `" ` + (presen === nombre_15 ? 'selected' : '') + `>${nombre_15}</option>` : ``) + ` 
                `+ (parseFloat(stock_16) > 0 ? `<option value="` + nombre_16 + `" ` + (presen === nombre_16 ? 'selected' : '') + `>${nombre_16}</option>` : ``) + ` 
                `+ (parseFloat(stock_17) > 0 ? `<option value="` + nombre_17 + `" ` + (presen === nombre_17 ? 'selected' : '') + `>${nombre_17}</option>` : ``) + ` 
                `+ (parseFloat(stock_18) > 0 ? `<option value="` + nombre_18 + `" ` + (presen === nombre_18 ? 'selected' : '') + `>${nombre_18}</option>` : ``) + ` 
                `+ (parseFloat(stock_19) > 0 ? `<option value="` + nombre_19 + `" ` + (presen === nombre_19 ? 'selected' : '') + `>${nombre_19}</option>` : ``) + ` 
                `+ (parseFloat(stock_20) > 0 ? `<option value="` + nombre_20 + `" ` + (presen === nombre_20 ? 'selected' : '') + `>${nombre_20}</option>` : ``) + `  

                </select>
            </td>`+
                `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_ventaSistema + `">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_ventaSistema2 + `">
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + q_ref + `" ${q_ref.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="` + precio_recargoPV + `" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="` + precio_recargoQRef + `" ></td>` +
                '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="' + descuento + '"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="' + descripcion_detalle + '"></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '<td><button type="button" onclick="mostrarextras_Extras(' + cont + ', \'' + idarticulo + '\',\'' + idcotizacion_r + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
                '</tr>' +
                '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
                '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
                '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
                '</td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');
            mostrarextras_Extras(cont - 1, idarticulo, idcotizacion_r);
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
            $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function agregarDetalleFormapago(idarticulo, nombre, precio_venta, stock, descuento_porcentaje,
    precio_rango1, precio_rango1_Dos,
    precio_rango2, precio_rango2_Dos,
    precio_rango3, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango1_Distribuidor, precio_rango1_Mayorista,
    precio_rango2_MecanicoDos, precio_rango2_DistribuidorDos, precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres, precio_rango3_DistribuidorTres, precio_rango3_MayoristaTres,
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
    precio_activado, facturar_cero, precio_compra, cantidadpresentacion,
    totalcantidadpresentacion, presen, precio_ventaSistema, precio_ventaSistema2,
    q_ref, precio_recargoPV, precio_recargoQRef, descuento, descripcion_detalle, cantidad) {

    console.log(presen + 'presen');
    let idcotizacion_r = $("#idcotizacion").val();

    precio_activado = precio_activado.toString().trim();
    //console.log("q_ref ", q_ref);

    var cantidad = cantidad;
    var stockinven = stock;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        var exist = false;
        $('#detalles').children("tbody").children("tr").each(function (index) {
            var idart = $(this).attr("data-id")
            if (idart == idarticulo) {
                exist = true;
            }
        })

        if (!exist) {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
                                '` + nombre_20 + `',` + stock_20 + `,` + precio_20 + `)"  >
                `+ (parseFloat(stock_unidad) > 0 ? `<option value="` + nombre_01 + `" ` + (presen === nombre_01 ? 'selected' : '') + `>${nombre_01}</option>` : ``) + ` 
                `+ (parseFloat(stock_blister) > 0 ? `<option value="` + nombre_02 + `" ` + (presen === nombre_02 ? 'selected' : '') + `>${nombre_02}</option>` : ``) + ` 
                `+ (parseFloat(stock_caja) > 0 ? `<option value="` + nombre_03 + `" ` + (presen === nombre_03 ? 'selected' : '') + `>${nombre_03}</option>` : ``) + ` 
                `+ (parseFloat(stock_fardo) > 0 ? `<option value="` + nombre_04 + `" ` + (presen === nombre_04 ? 'selected' : '') + `>${nombre_04}</option>` : ``) + ` 
                `+ (parseFloat(stock_sacos) > 0 ? `<option value="` + nombre_05 + `" ` + (presen === nombre_05 ? 'selected' : '') + `>${nombre_05}</option>` : ``) + ` 
                `+ (parseFloat(stock_paquete) > 0 ? `<option value="` + nombre_06 + `" ` + (presen === nombre_06 ? 'selected' : '') + `>${nombre_06}</option>` : ``) + ` 
                `+ (parseFloat(stock_07) > 0 ? `<option value="` + nombre_07 + `" ` + (presen === nombre_07 ? 'selected' : '') + `>${nombre_07}</option>` : ``) + ` 
                `+ (parseFloat(stock_08) > 0 ? `<option value="` + nombre_08 + `" ` + (presen === nombre_08 ? 'selected' : '') + `>${nombre_08}</option>` : ``) + ` 
                `+ (parseFloat(stock_09) > 0 ? `<option value="` + nombre_09 + `" ` + (presen === nombre_09 ? 'selected' : '') + `>${nombre_09}</option>` : ``) + ` 
                `+ (parseFloat(stock_10) > 0 ? `<option value="` + nombre_10 + `" ` + (presen === nombre_10 ? 'selected' : '') + `>${nombre_10}</option>` : ``) + ` 
                `+ (parseFloat(stock_11) > 0 ? `<option value="` + nombre_11 + `" ` + (presen === nombre_11 ? 'selected' : '') + `>${nombre_11}</option>` : ``) + ` 
                `+ (parseFloat(stock_12) > 0 ? `<option value="` + nombre_12 + `" ` + (presen === nombre_12 ? 'selected' : '') + `>${nombre_12}</option>` : ``) + ` 
                `+ (parseFloat(stock_13) > 0 ? `<option value="` + nombre_13 + `" ` + (presen === nombre_13 ? 'selected' : '') + `>${nombre_13}</option>` : ``) + ` 
                `+ (parseFloat(stock_14) > 0 ? `<option value="` + nombre_14 + `" ` + (presen === nombre_14 ? 'selected' : '') + `>${nombre_14}</option>` : ``) + ` 
                `+ (parseFloat(stock_15) > 0 ? `<option value="` + nombre_15 + `" ` + (presen === nombre_15 ? 'selected' : '') + `>${nombre_15}</option>` : ``) + ` 
                `+ (parseFloat(stock_16) > 0 ? `<option value="` + nombre_16 + `" ` + (presen === nombre_16 ? 'selected' : '') + `>${nombre_16}</option>` : ``) + ` 
                `+ (parseFloat(stock_17) > 0 ? `<option value="` + nombre_17 + `" ` + (presen === nombre_17 ? 'selected' : '') + `>${nombre_17}</option>` : ``) + ` 
                `+ (parseFloat(stock_18) > 0 ? `<option value="` + nombre_18 + `" ` + (presen === nombre_18 ? 'selected' : '') + `>${nombre_18}</option>` : ``) + ` 
                `+ (parseFloat(stock_19) > 0 ? `<option value="` + nombre_19 + `" ` + (presen === nombre_19 ? 'selected' : '') + `>${nombre_19}</option>` : ``) + ` 
                `+ (parseFloat(stock_20) > 0 ? `<option value="` + nombre_20 + `" ` + (presen === nombre_20 ? 'selected' : '') + `>${nombre_20}</option>` : ``) + `  

                </select>
            </td>`+
                `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_ventaSistema + `">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_ventaSistema2 + `">
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + q_ref + `" ${q_ref.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="` + precio_recargoPV + `" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="` + precio_recargoQRef + `" ></td>` +
                '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="' + descuento + '"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="' + descripcion_detalle + '"></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '<td><button type="button" onclick="mostrarextras_Extras(' + cont + ', \'' + idarticulo + '\',\'' + idcotizacion_r + '\')" class="btn btn-info"><i class="fa fa-plus"></i></button></td>' +
                '</tr>' +
                '<tr id="extras-row-' + cont + '" class="extras-row" style="display:none">' +
                '<td colspan="10">' + // Ajustado a 10 columnas según tu tabla
                '<div class="extras-container" id="extras-container-' + cont + '"></div>' +
                '</td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');
            mostrarextras_Extras(cont - 1, idarticulo, idcotizacion_r);
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val()) + 1
            $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3,
                precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
                precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
                precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}


function modificarSubototalespreciopresentacion() {

    var cant = document.getElementsByName("cantidad[]");
    var cantpre = document.getElementsByName("cantidadpresentacion[]");
    var prec = document.getElementsByName("precio_venta[]");
    var preqref = document.getElementsByName("q_ref[]");

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpCpre = cantpre[i];
        var inpP = prec[i];
        var inpPreqref = preqref[i];
        inpP.value = (parseFloat(inpPreqref.value) / parseFloat(inpCpre.value));
        document.getElementsByName("precio_venta[]")[i].innerHTML = inpP.value;
        document.getElementsByName("precio_ventaSistema2[]")[i].value = inpPreqref.value;
        document.getElementsByName("precio_ventaSistema[]")[i].value = inpP.value;


    }
    calcularTotales();
    calcularTotalesdes();
    modificarSubototales();
}


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
        var precioventacaja = 0;
        if (stock_caja > 0) {
            precioventacaja = (precio_caja / stock_caja); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_caja);
        $("#precio_venta" + id).val(precioventacaja);
        $("#q_ref" + id).val(precio_caja);
        $("#presen" + id).val(nombre_03);

        $("#precio_ventaSistema" + id).val(precioventacaja);
        $("#precio_ventaSistema2" + id).val(precio_caja);
    }
    else if (presentacion == nombre_04) {

        var precioventafardo = 0;
        if (stock_fardo > 0) {
            precioventafardo = (precio_fardo / stock_fardo); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_fardo);
        $("#precio_venta" + id).val(precioventafardo);
        $("#q_ref" + id).val(precio_fardo);
        $("#presen" + id).val(nombre_04);

        $("#precio_ventaSistema" + id).val(precioventafardo);
        $("#precio_ventaSistema2" + id).val(precio_fardo);
    }

    else if (presentacion == nombre_05) {
        var precioventasacos = 0;
        if (stock_sacos > 0) {
            precioventasacos = (precio_sacos / stock_sacos); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_sacos);
        $("#precio_venta" + id).val(precioventasacos);
        $("#q_ref" + id).val(precio_sacos);
        $("#presen" + id).val(nombre_05);

        $("#precio_ventaSistema" + id).val(precioventasacos);
        $("#precio_ventaSistema2" + id).val(precio_sacos);
    }
    else if (presentacion == nombre_06) {
        var precioventapaquete = 0;
        if (stock_paquete > 0) {
            precioventapaquete = (precio_paquete / stock_paquete); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_paquete);
        $("#precio_venta" + id).val(precioventapaquete);
        $("#q_ref" + id).val(precio_paquete);
        $("#presen" + id).val(nombre_06);


        $("#precio_ventaSistema" + id).val(precioventapaquete);
        $("#precio_ventaSistema2" + id).val(precio_paquete);
    }
    else if (presentacion == nombre_07) {
        var precioventapaquete = 0;
        if (stock_07 > 0) {
            precioventapaquete = (precio_07 / stock_07); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_07);
        $("#precio_venta" + id).val(precioventapaquete);
        $("#q_ref" + id).val(precio_07);
        $("#presen" + id).val(nombre_07);


        $("#precio_ventaSistema" + id).val(precioventapaquete);
        $("#precio_ventaSistema2" + id).val(precio_07);
    }
    else if (presentacion == nombre_08) {
        var precioventapaquete = 0;
        if (stock_08 > 0) {
            precioventapaquete = (precio_08 / stock_08); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_08);
        $("#precio_venta" + id).val(precioventapaquete);
        $("#q_ref" + id).val(precio_08);
        $("#presen" + id).val(nombre_08);


        $("#precio_ventaSistema" + id).val(precioventapaquete);
        $("#precio_ventaSistema2" + id).val(precio_08);
    }

    else if (presentacion == nombre_09) {
        var precioventapaquete = 0;
        if (stock_09 > 0) {
            precioventapaquete = (precio_09 / stock_09); // Redondear a 2 decimales
        }
        $("#cantidadpresentacion" + id).val(stock_09);
        $("#precio_venta" + id).val(precioventapaquete);
        $("#q_ref" + id).val(precio_09);
        $("#presen" + id).val(nombre_09);


        $("#precio_ventaSistema" + id).val(precioventapaquete);
        $("#precio_ventaSistema2" + id).val(precio_09);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_10);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_11);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_12);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_13);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_14);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_15);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_16);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_17);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_18);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_19);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_20);
    }

    modificarSubototalesTarjetaEfectivo();
}


function modificarSubototalesTarjetaEfectivo() {
    var cant = document.getElementsByName("cantidad[]");
    var qref = document.getElementsByName("q_ref[]");
    var precioventa = document.getElementsByName("precio_venta[]");
    var precSistema = document.getElementsByName("precio_ventaSistema[]");
    var precSistema2 = document.getElementsByName("precio_ventaSistema2[]");
    var valortarjeta = $("#valor_tarjeta").val();


    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = qref[i];
        var inpPPV = precioventa[i];
        var inpPSistema = precSistema[i];
        var inpPSistema2 = precSistema2[i];



        if (parseFloat(valortarjeta) > 0) {
            ////calculo de qref
            var resCanculotarjeta1 = (((inpPSistema2.value * valortarjeta) / 100));
            var resutaltadoCanculotarjeta1 = (parseFloat(resCanculotarjeta1))
            document.getElementsByName("precio_recargoQRef[]")[i].value = resutaltadoCanculotarjeta1;

            document.getElementsByName("q_ref[]")[i].value = (parseFloat(inpPSistema2.value) + parseFloat(resutaltadoCanculotarjeta1));


            ///calculo de precio venta
            var resCanculotarjeta2 = (((inpPSistema.value * valortarjeta) / 100));
            var resutaltadoCanculotarjeta2 = (parseFloat(resCanculotarjeta2))
            document.getElementsByName("precio_recargoPV[]")[i].value = resutaltadoCanculotarjeta2;

            document.getElementsByName("precio_venta[]")[i].value = (parseFloat(inpPSistema.value) + parseFloat(resutaltadoCanculotarjeta2));


        } else {

            ////calculo de qref
            var resCanculotarjeta11 = inpPSistema2.value;
            var resutaltadoCanculotarjeta11 = (parseFloat(resCanculotarjeta11))
            document.getElementsByName("precio_recargoQRef[]")[i].value = 0;
            document.getElementsByName("q_ref[]")[i].value = (parseFloat(resutaltadoCanculotarjeta11));


            ///calculo de precio venta
            var resCanculotarjeta22 = inpPSistema.value;
            var resutaltadoCanculotarjeta22 = (parseFloat(resCanculotarjeta22))
            document.getElementsByName("precio_recargoPV[]")[i].value = 0;
            document.getElementsByName("precio_venta[]")[i].value = (parseFloat(resutaltadoCanculotarjeta22));
        }

    }

    modificarSubototales();
}



function modificarSubototalesxrango(id, precio_rango1, precio_rango2, precio_rango3,
    precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico,
    precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres) {
    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var qref = document.getElementsByName("q_ref[]");
    var precSistema = document.getElementsByName("precio_ventaSistema[]");
    var precioventaSistema2 = document.getElementsByName("precio_ventaSistema2[]");
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");
    var subdes1 = document.getElementsByName("subtotaldes1[]");
    var sub1 = document.getElementsByName("subtotal1[]");
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]"));
    var pRecargo = (document.getElementsByName("precio_recargoQRef[]"));
    var presen = (document.getElementsByName("presen[]"));
    var descuentopermitido = (document.getElementsByName("descuento_permitido[]"));
    var calculo_descuento = $("#calculo_descuento").val();
    var tipo_cliente = $("#tipo_cliente").val();

    var precio_rango1 = parseFloat(precio_rango1);
    var precio_rango2 = parseFloat(precio_rango2);
    var precio_rango3 = parseFloat(precio_rango3);
    var precio_rango1_Dos = parseFloat(precio_rango1_Dos);
    var precio_rango2_Dos = parseFloat(precio_rango2_Dos);
    var precio_rango3_Dos = parseFloat(precio_rango3_Dos);
    var precio_rango1_Mecanico = parseFloat(precio_rango1_Mecanico);
    var precio_rango2_MecanicoDos = parseFloat(precio_rango2_MecanicoDos);
    var precio_rango3_MecanicoTres = parseFloat(precio_rango3_MecanicoTres);
    var precio_rango1_Distribuidor = parseFloat(precio_rango1_Distribuidor);
    var precio_rango2_DistribuidorDos = parseFloat(precio_rango2_DistribuidorDos);
    var precio_rango3_DistribuidorTres = parseFloat(precio_rango3_DistribuidorTres);
    var precio_rango1_Mayorista = parseFloat(precio_rango1_Mayorista);
    var precio_rango2_MayoristaDos = parseFloat(precio_rango2_MayoristaDos);
    var precio_rango3_MayoristaTres = parseFloat(precio_rango3_MayoristaTres);


    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpPqref = qref[i];
        var inpPSistema = precSistema[i];
        var inpPSistema2 = precioventaSistema2[i];
        var inpD = desc[i];
        var inpS = sub[i];
        var inpSdes = subdes[i];
        var inpSdes1 = subdes1[i];
        var inpS1 = sub1[i];
        var inpCpre = cantpre[i];
        var inpTpres = tprese[i];
        var inppRecargo = pRecargo[i];
        var inpPresen = presen[i];
        var inpDescuentopermitido = descuentopermitido[i];


        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;

        /*  if (parseFloat(inpD.value) > parseFloat(inpDescuentopermitido.value)) {
              Swal.fire({
                  title: 'Descuento no permitido',
                  text: 'El descuento ingresado supera lo permitido',
                  icon: 'error',
                  timer: 2000, // 2 segundos
                  timerProgressBar: true
              }).then(() => {
                  // Establecer el valor después de que se cierre la alerta
                  document.getElementsByName("descuento_porcentaje[]")[i].value = 0;
              });
  
              return; // Detenemos la ejecución si el descuento es inválido
          }*/

        if (inpPresen.value === 'UNIDAD') {
            // Ajustamos el rango para utilizar condiciones AND (`&&`) en lugar de OR (`||`)
            if (parseFloat(inpTpres.value) <= 3) {
                console.log("sin rango");
                document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
            }
            else if (precio_rango1 <= parseFloat(inpTpres.value) <= precio_rango1_Dos) {
                console.log("rango 1");
                if (tipo_cliente == 'DISTRIBUIDOR') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango1_Distribuidor;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango1_Distribuidor;
                }
                else if (tipo_cliente == 'MAYORISTA') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango1_Mayorista;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango1_Mayorista;
                }
                else if (tipo_cliente == 'TALLER') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango1_Mecanico;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango1_Mecanico;
                }
                else if (tipo_cliente == 'PUBLICO') {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }
            }
            else if (precio_rango2 <= parseFloat(inpTpres.value) <= precio_rango2_Dos) {


                if (tipo_cliente == 'DISTRIBUIDOR') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango2_DistribuidorDos;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango2_DistribuidorDos;
                }
                else if (tipo_cliente == 'MAYORISTA') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango2_MayoristaDos;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango2_MayoristaDos;
                }
                else if (tipo_cliente == 'TALLER') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango2_MecanicoDos;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango2_MecanicoDos;
                }
                else if (tipo_cliente == 'PUBLICO') {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }
            }
            else if (precio_rango3 <= parseFloat(inpTpres.value) <= precio_rango3_Dos) {
                console.log("rango 3");

                if (tipo_cliente == 'DISTRIBUIDOR') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango3_DistribuidorTres;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango3_DistribuidorTres;
                }
                else if (tipo_cliente == 'MAYORISTA') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango3_MayoristaTres;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango3_MayoristaTres;
                }
                else if (tipo_cliente == 'TALLER') {
                    document.getElementsByName("q_ref[]")[i].value = precio_rango3_MecanicoTres;
                    document.getElementsByName("precio_venta[]")[i].value = precio_rango3_MecanicoTres;
                }
                else if (tipo_cliente == 'PUBLICO') {
                    document.getElementsByName("q_ref[]")[i].value = inpPSistema.value;
                    document.getElementsByName("precio_venta[]")[i].value = inpPSistema2.value;
                }


            }
        }



        if (calculo_descuento.value === 'QUETZALES') {

            inpS.value = (inpTpres.value * (inpP.value - inpD.value));
            document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
            inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

            inpSdes.value = (inpD.value * inpTpres.value);
            document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
            inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
            //console.log(inpSdes.value);                

        }
        else if (calculo_descuento.value === 'PORCENTAJE') {

            inpS.value = (inpTpres.value * (inpP.value - ((inpP.value * inpD.value) / 100)));
            document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
            inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

            inpSdes.value = (((inpP.value * inpD.value) / 100) * inpTpres.value);
            document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
            inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
            //console.log(inpSdes.value);                
        }




    }

    calcularTotales();
    calcularTotalesdes();
    modificarSubototales();
}


function modificarSubototales() {
    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var qref = document.getElementsByName("q_ref[]");
    var precSistema = document.getElementsByName("precio_ventaSistema[]");
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");
    var subdes1 = document.getElementsByName("subtotaldes1[]");
    var sub1 = document.getElementsByName("subtotal1[]");
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]"));
    var pRecargo = (document.getElementsByName("precio_recargoQRef[]"));
    var descuentopermitido = (document.getElementsByName("descuento_permitido[]"));
    var calculo_descuento = $("#calculo_descuento").val();

    /////
    var valortarjeta = $("#valor_descuentoGeneral").val();
    var descuento_general = $("#descuento_general option:selected").text();


    /////    

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpPqref = qref[i];
        var inpPSistema = precSistema[i];
        var inpD = desc[i];
        var inpS = sub[i];
        var inpSdes = subdes[i];
        var inpSdes1 = subdes1[i];
        var inpS1 = sub1[i];
        var inpCpre = cantpre[i];
        var inpTpres = tprese[i];
        var inppRecargo = pRecargo[i];
        var inpDescuentopermitido = descuentopermitido[i];

        var descuentoPorProducto = 0;

        if (descuento_general == 'DESCUENTO GENERAL') {

            descuentoPorProducto = parseFloat(valortarjeta) / cant.length;
            document.getElementsByName("descuento_porcentaje[]")[i].value = parseFloat(descuentoPorProducto).toFixed(2);

        } else {
            descuentoPorProducto = 0;
            if (parseFloat(inpD.value) > parseFloat(inpDescuentopermitido.value)) {
                Swal.fire({
                    title: 'Descuento no permitido',
                    text: 'El descuento ingresado supera lo permitido',
                    icon: 'error',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true
                }).then(() => {
                    // Establecer el valor después de que se cierre la alerta
                    document.getElementsByName("descuento_porcentaje[]")[i].value = 0;
                    inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
                    document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;


                    inpS.value = (inpTpres.value * (inpP.value - ((inpP.value * inpD.value) / 100)));
                    document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
                    inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

                    inpSdes.value = (((inpP.value * inpD.value) / 100) * inpTpres.value);
                    document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
                    inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
                    //console.log(inpSdes.value);

                });

                return; // Detenemos la ejecución si el descuento es inválido
            }
        }


        if (calculo_descuento === 'QUETZALES') {

            inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
            document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;


            inpS.value = (inpTpres.value * (inpP.value - inpD.value));
            document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
            inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

            inpSdes.value = (inpD.value * inpTpres.value);
            document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
            inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
            //console.log(inpSdes.value);

        }
        else if (calculo_descuento === 'PORCENTAJE') {
            inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
            document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = inpTpres.value;


            inpS.value = (inpTpres.value * (inpP.value - ((inpP.value * inpD.value) / 100)));
            document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(3);
            inpS1.value = parseFloat(inpS.value).toFixed(3); // Asignamos el valor al input   

            inpSdes.value = (((inpP.value * inpD.value) / 100) * inpTpres.value);
            document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(3); // Aplicamos .toFixed(2) también aquí
            inpSdes1.value = parseFloat(inpSdes.value).toFixed(2); // Asignamos el valor al input      
            //console.log(inpSdes.value);

        }


    }

    calcularTotales();
    calcularTotalesdes();
}

function calcularTotalesdes() {
    var chks = document.getElementsByName('subtotaldes');
    var total = 0.0;

    for (var i = 0; i < chks.length; i++) {
        var valor = parseFloat(chks[i].textContent || chks[i].innerHTML); // Obtener el texto de la etiqueta y convertirlo a número
        if (!isNaN(valor)) {
            total += valor;
        }
    }

    // console.log('subtotaldes'+total)

    $("#totaldes").html("Q. " + total.toFixed(2)); // Formatear el total a dos decimales
    $("#total_ventades").val(total.toFixed(2)); // Asignar el valor formateado al campo de entrada
    $("#total_ventades_r").val(total.toFixed(2));
    evaluar(); // Llamar la función evaluar si es necesario
}



function calcularTotales() {
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        var valor = parseFloat(sub[i].textContent || sub[i].innerHTML);
        if (!isNaN(valor)) {
            total += valor;
        }
    }

    $('.subtotal-extra').each(function () {
        // Obtenemos el texto del subtotal de extras y lo parseamos
        const valorExtra = parseFloat($(this).text());
        console.log("valorExtra ", valorExtra);
        if (!isNaN(valorExtra)) {
            total += valorExtra;
        }
    });


    $("#total").html("Q. " + total.toFixed(2));
    $("#total_venta").val(total.toFixed(2));
    $("#total_venta_r").val(total.toFixed(2));
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

init();

$.fn.delayPasteKeyUp = function (fn, ms) {
    var timer = 0;
    $(this).on("propertychange input", function () {
        clearTimeout(timer);
        timer = setTimeout(fn, ms);
    });
};



$(function () {
    $("#txtbusquedaartcodebar").delayPasteKeyUp(function () {
        var idcliente = $("#idcliente").val();
        var descuento_cliente = $("#descuento_cliente").val();

        if (descuento_cliente == "0") {
            var valoractual = $("#txtbusquedaartcodebar").val();

            if (valoractual != "") {
                $.get("../ajax/venta.php?op=buscararticulocodebar&codigo=" + valoractual + "", { op: "buscararticulocodebar", codigo: valoractual }, function (res) {

                    var arrayproduc = res.split("@");

                    console.log(arrayproduc);

                    if (arrayproduc[0] != "undefined") {
                        agregarDetalle(arrayproduc[0], arrayproduc[1], arrayproduc[2], arrayproduc[3],
                            arrayproduc[4], arrayproduc[5], arrayproduc[6], arrayproduc[7], arrayproduc[8],
                            arrayproduc[9], arrayproduc[10], arrayproduc[11], arrayproduc[12], arrayproduc[13],
                            arrayproduc[14], arrayproduc[15], arrayproduc[16], arrayproduc[17], arrayproduc[18],
                            arrayproduc[19], arrayproduc[20], arrayproduc[21], arrayproduc[22], arrayproduc[23],
                            arrayproduc[24], arrayproduc[25], arrayproduc[26], arrayproduc[27], arrayproduc[28],
                            arrayproduc[29], arrayproduc[30], arrayproduc[31], arrayproduc[32], arrayproduc[33],
                            arrayproduc[34], arrayproduc[35], arrayproduc[36], arrayproduc[37], arrayproduc[38],
                            arrayproduc[39], arrayproduc[40], arrayproduc[41], arrayproduc[42], arrayproduc[43],
                            arrayproduc[44], arrayproduc[45], arrayproduc[46], arrayproduc[47], arrayproduc[48],
                            arrayproduc[49], arrayproduc[50], arrayproduc[51], arrayproduc[52], arrayproduc[53],
                            arrayproduc[54], arrayproduc[55], arrayproduc[56], arrayproduc[57], arrayproduc[58],
                            arrayproduc[59], arrayproduc[60], arrayproduc[61], arrayproduc[62], arrayproduc[63],
                            arrayproduc[64], arrayproduc[65], arrayproduc[66], arrayproduc[67], arrayproduc[68],
                            arrayproduc[69], arrayproduc[70], arrayproduc[71], arrayproduc[72], arrayproduc[73],
                            arrayproduc[74], arrayproduc[75], arrayproduc[76], arrayproduc[77], arrayproduc[78],
                            arrayproduc[79], arrayproduc[80], arrayproduc[81], arrayproduc[82], arrayproduc[83]);
                    }
                    $("#txtbusquedaartcodebar").val("");
                    $("#txtbusquedaartcodebar").focus()

                })
            }
        }
        else if (descuento_cliente > "0") {
            var valoractual = $("#txtbusquedaartcodebar").val();

            if (valoractual != "") {
                $.get("../ajax/venta.php?op=buscararticulocodebar_descuento&codigo=" + valoractual + "", { op: "buscararticulocodebar_descuento", codigo: valoractual, idcliente: idcliente, descuento_cliente: descuento_cliente }, function (res) {

                    var arrayproduc = res.split("@");

                    console.log(arrayproduc);

                    if (arrayproduc[0] != "undefined") {
                        agregarDetalle(arrayproduc[0], arrayproduc[1], arrayproduc[2], arrayproduc[3],
                            arrayproduc[4], arrayproduc[5], arrayproduc[6], arrayproduc[7], arrayproduc[8],
                            arrayproduc[9], arrayproduc[10], arrayproduc[11], arrayproduc[12], arrayproduc[13],
                            arrayproduc[14], arrayproduc[15], arrayproduc[16], arrayproduc[17], arrayproduc[18],
                            arrayproduc[19], arrayproduc[20], arrayproduc[21], arrayproduc[22], arrayproduc[23],
                            arrayproduc[24], arrayproduc[25], arrayproduc[26], arrayproduc[27], arrayproduc[28],
                            arrayproduc[29], arrayproduc[30], arrayproduc[31], arrayproduc[32], arrayproduc[33],
                            arrayproduc[34], arrayproduc[35], arrayproduc[36], arrayproduc[37], arrayproduc[38],
                            arrayproduc[39], arrayproduc[40], arrayproduc[41], arrayproduc[42], arrayproduc[43],
                            arrayproduc[44], arrayproduc[45], arrayproduc[46], arrayproduc[47], arrayproduc[48],
                            arrayproduc[49], arrayproduc[50], arrayproduc[51], arrayproduc[52], arrayproduc[53],
                            arrayproduc[54], arrayproduc[55], arrayproduc[56], arrayproduc[57], arrayproduc[58],
                            arrayproduc[59], arrayproduc[60], arrayproduc[61], arrayproduc[62], arrayproduc[63],
                            arrayproduc[64], arrayproduc[65], arrayproduc[66], arrayproduc[67], arrayproduc[68],
                            arrayproduc[69], arrayproduc[70], arrayproduc[71], arrayproduc[72], arrayproduc[73],
                            arrayproduc[74], arrayproduc[75], arrayproduc[76], arrayproduc[77], arrayproduc[78],
                            arrayproduc[79], arrayproduc[80], arrayproduc[81], arrayproduc[82], arrayproduc[83]);
                    }
                    $("#txtbusquedaartcodebar").val("");
                    $("#txtbusquedaartcodebar").focus()

                })
            }
        }



    }, 200)
    listarArticulos();
})

function guardaryeditart(e) {

    e.preventDefault();
    var formData = new FormData($("#formulario2t")[0]);

    $.ajax({
        url: "../ajax/transporte.php?op=guardaryeditar2",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //console.log("datos de nuevo ", datos);
            Swal.fire({
                title: "Transporte Agregado Correctamente!",
                icon: "success"
            });
            $("#idtransporte").append(datos).selectpicker('refresh');
        }

    });
    limpiar();
}


function guardaryeditarM(e) {
    e.preventDefault();
    var formData = new FormData($("#formulariom")[0]);

    $.ajax({
        url: "../ajax/mensajero.php?op=guardaryeditar2",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.fire({
                title: "Mensajero Agregado Correctamente!",
                icon: "success"
            });
            $("#idmensajero").append(datos).selectpicker('refresh');
        }

    });
    limpiar();
}


$(function () {
    /*   $("#codigo_ingresar").on("change", function () {
           var valoractual = $("#codigo_ingresar").val();
   
           if (valoractual != "") {
               load();
               $.get("../ajax/venta.php?op=buscararticulocodebar&codigo=" + valoractual,
                   { op: "buscararticulocodebar", codigo: valoractual },
                   function (res) {
                       var arrayproduc = res.split("@");
                       // Verificar si el array está vacío o si el primer elemento es undefined
                       if (arrayproduc[0] === '  ' || arrayproduc[0] === "undefined") {
                           console.log("entra");
                           Swal.close();
                           Swal.fire({
                               icon: "error",
                               title: "Oops...",
                               text: "El código ingresado no existe, favor ingresar uno correcto",
                           }).then((result) => {
                               // Este código se ejecutará después de que el usuario cierre el alert
                               $("#codigo_ingresar").val("");
                               $("#codigo_ingresar").focus();
                           });
                           return;
                       }
   
   
                       if (arrayproduc[3] <= 0) {
   
                           Swal.close();
                           let cod = $("#codigo_ingresar").val();
                           Swal.fire({
                               icon: "error",
                               title: "Oops...",
                               text: `No se puede agregar un producto con stock 0: ${cod}`,
                           });
                           $("#codigo_ingresar").val("");
                           return;
                       } else {
                           var cantidadIngresada = $("#cantidad_ingreso").val();
                           $("#cantidad_ingreso").val("");
   
                           //console.log(cantidadIngresada);
                           // Validamos si el resultado es válido
                           if (arrayproduc[0] !== "undefined") {
                               Swal.close();
   
                               // Asignamos los valores a los inputs correspondientes
                               $("#idarticulo_ingreso").val(arrayproduc[0]);
                               $("#articulo_ingresar").val(arrayproduc[1]);
                               $("#precio_venta_ingreso").val(arrayproduc[2]);
                               //$("#cantidad_ingreso").val("");
   
                               // Guardamos el precio de venta inicial para usarlo en caso de no modificación
                               var precioVentaInicial = arrayproduc[2];
   
                               $("#cantidad_ingreso").focus();
   
                               // Configuramos el evento de pérdida de foco o cambio en precio_venta_ingreso
                               $("#precio_venta_ingreso").off("change").on("change blur", function () {
                                   // Verificamos si el precio no fue modificado, y en ese caso, tomamos el precio inicial
                                   var precioIngresado = $(this).val();
                                   if (precioIngresado === "") {
                                       precioIngresado = precioVentaInicial;  // Si no se modifica, usamos el precio por defecto
                                   }
   
                                   if (precioIngresado === "") {
                                       precioIngresado = precioVentaInicial;  // Si no se modifica, usamos el precio por defecto
                                   }
   
                                   // Obtenemos el valor de la cantidad (aquí debes asegurarte de que la cantidad esté correctamente capturada)
                                   var cantidadIngresada = $("#cantidad_ingreso").val();
                                   // console.log("Cantidad Ingresada: ", cantidadIngresada); // Agrega un log para verificar el valor
   
                                   // Ahora enviamos todos los datos a la función agregarDetalle
                                   agregarDetalleCodigo(
                                       arrayproduc[0], // idarticulo
                                       arrayproduc[1], // nombre
                                       precioIngresado,    // precio_venta (desde el input o el valor por defecto)
                                       arrayproduc[3], // stock
                                       arrayproduc[4], // descuento
                                       arrayproduc[5], // stock_unidad
                                       arrayproduc[6], // precio_unidad
                                       arrayproduc[7], // stock_blister
                                       arrayproduc[8], // precio_blister
                                       arrayproduc[9], // stock_caja
                                       arrayproduc[10], // precio_caja
                                       arrayproduc[11], // stock_fardo
                                       arrayproduc[12], // precio_fardo
                                       arrayproduc[13], // stock_sacos
                                       arrayproduc[14], // precio_sacos
                                       arrayproduc[15], // stock_paquete
                                       arrayproduc[16], // precio_paquete
                                       arrayproduc[17], // precio_rango1
                                       arrayproduc[18], // precio_rango2
                                       arrayproduc[19], // precio_rango3
                                       arrayproduc[20], // precio_compra
                                       arrayproduc[21],  // precio_activado
                                       cantidadIngresada, // cantidad (desde el input)
                                       valoractual, //codigo
                                       arrayproduc[22],  // precio_rango1_Dos
                                       arrayproduc[23],  // precio_rango2_Dos
                                       arrayproduc[24],  // precio_rango3_Dos
                                       arrayproduc[25],  // precio_rango1_Mecanico
                                       arrayproduc[26],  // precio_rango2_MecanicoDos
                                       arrayproduc[27],  // precio_rango3_MecanicoTres
                                       arrayproduc[28],  // precio_rango1_Distribuidor
                                       arrayproduc[29],  // precio_rango2_DistribuidorDos
                                       arrayproduc[30],  // precio_rango3_DistribuidorTres
                                       arrayproduc[31],  // precio_rango1_Mayorista
                                       arrayproduc[32],  // precio_rango2_MayoristaDos
                                       arrayproduc[33]  // precio_rango3_MayoristaTres
                                   );
                                   limpiarCampos();
   
                               });
   
                           }
                       }
                   });
   
           }
   
       });
   
       // Función para limpiar los campos después de agregar
       function limpiarCampos() {
           $("#idarticulo_ingreso").val("");
           $("#articulo_ingresar").val("");
           $("#precio_venta_ingreso").val("");
           $("#cantidad_ingreso").focus();
           $("#codigo_ingresar").val("");
           $("#codigo_ingresar").focus(); // Devolvemos el foco al primer campo
       }*/
})

function agregarDetalleCodigo(idarticulo, nombre, precio_venta, stock, descuento_porcentaje, stock_unidad, precio_unidad,
    stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos, precio_sacos,
    stock_paquete, precio_paquete, precio_rango1, precio_rango2, precio_rango3, precio_compra,
    precio_activado, cantidad, codigo, precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres) {
    console.log("cantidad" + cantidad)

    precio_activado = precio_activado.toString().trim();

    var cantidad = cantidad;
    var stockinven = stock;
    var presen = 'UNIDAD';
    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    idarticulo = idarticulo.toString().trim();
    var subtotaldes = 0;
    if (idarticulo != "") {
        var exist = false;
        $('#detalles').children("tbody").children("tr").each(function (index) {
            var idart = $(this).attr("data-id")
            if (idart == idarticulo) {
                exist = true;
            }
        })

        if (!exist) {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" data-id="' + idarticulo + '" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
                '<td><input type="hidden" name="precio_compra[]" value="' + precio_compra + '"><input type="hidden" name="stockinven[]" value="' + stockinven + '">' + stockinven + '</td>' +
                '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:60px" class="form-control"  onchange="modificarSubototalesxrango(' + cont + ',' + precio_rango1 + ',' + precio_rango2 + ',' + precio_rango3 + ',' + precio_rango1_Dos + ',' + precio_rango2_Dos + ',' + precio_rango3_Dos + ',' + precio_rango1_Mecanico + ',' + precio_rango2_MecanicoDos + ',' + precio_rango3_MecanicoTres + ',' + precio_rango1_Distribuidor + ',' + precio_rango2_DistribuidorDos + ',' + precio_rango3_DistribuidorTres + ',' + precio_rango1_Mayorista + ',' + precio_rango2_MayoristaDos + ',' + precio_rango3_MayoristaTres + ',this)"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
                `<td>
                <select class="form-control" style="width:125px" name="presentacion[]" id="presentacionselect`+ cont + `" 
                onchange="presentacionoculatardatos(`+ cont + `,` + precio_venta + `,` + stock_unidad + `,` + precio_unidad + `,` + stock_blister + `,` + precio_blister + `,
                                                    `+ stock_caja + `,` + precio_caja + `,` + stock_fardo + `,` + precio_fardo + `,` + stock_sacos + `,` + precio_sacos + `,` + stock_paquete + `,` + precio_paquete + `)" >
                    `+ (parseFloat(stock_unidad) > 0 ? `<option value="UNIDAD">P.U</option>` : ``) + ` 
                    `+ (parseFloat(stock_blister) > 0 ? `<option value="BLISTER">P.BLI</option>` : ``) + ` 
                    `+ (parseFloat(stock_caja) > 0 ? `<option value="CAJA">P.CAJA.</option>` : ``) + ` 
                    `+ (parseFloat(stock_fardo) > 0 ? `<option value="FARDO">P.FARDO</option>` : ``) + ` 
                    `+ (parseFloat(stock_sacos) > 0 ? `<option value="SACOS">P.SACOS</option>` : ``) + ` 
                    `+ (parseFloat(stock_paquete) > 0 ? `<option value="PAQUETE">P.PAQUETE</option>` : ``) + ` 

                </select>
            </td>`+
                `<td><input  type="hidden"  name="presen[]" id="presen` + cont + `"value="` + presen + `" ">
                <input type="hidden" name="precio_ventaSistema[]" id="precio_ventaSistema`+ cont + `" value="` + precio_venta + `">
                <input type="hidden" name="precio_ventaSistema2[]" id="precio_ventaSistema2`+ cont + `" value="` + precio_venta + `">
                <input class="form-control" style="width:100px" type="number" step="any" name="q_ref[]" id="q_ref`+ cont + `"   onchange="modificarSubototalespreciopresentacion()" value="` + precio_venta + `" ${precio_activado.trim().toUpperCase() === "SI" ? 'readonly' : ''}> 
                    <input type="hidden" step="any" name="precio_venta[]" style="width:100px"  id="precio_venta`+ cont + `" value="` + precio_venta + `" >
                    <input type="hidden" step="any" name="precio_recargoPV[]"  id="precio_recargoPV`+ cont + `" value="0" >
                    <input type="hidden" name="precio_recargoQRef[]"   id="precio_recargoQRef`+ cont + `" value="0" ></td>` +
                '<td><input type="hidden" name="descuento_permitido[]"  id="descuento_permitido' + cont + '" value="' + descuento_porcentaje + '"><input onchange="modificarSubototales()" class="form-control" type="number" style="width:100px"  step="any"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
                '<td><input type="hidden" name="subtotal1[]" ><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td><input type="hidden" name="subtotaldes1[]" ><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
                '<td><input style="width:100px" class="form-control"  type="text"   name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
                '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');
        } else {
            var cxcantidad = parseInt($("#cxcantidad" + idarticulo).val())
            $("#cxcantidad" + idarticulo).val(cxcantidad)
            // Forzamos la ejecución de modificarSubototalesxrango para que se refleje el cambio en los subtotales
            modificarSubototalesxrango(cont, precio_rango1, precio_rango2, precio_rango3, precio_rango1_Dos,
                precio_rango2_Dos, precio_rango3_Dos, precio_rango1_Mecanico, precio_rango2_MecanicoDos, precio_rango3_MecanicoTres,
                precio_rango1_Distribuidor, precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
                precio_rango2_MayoristaDos, precio_rango3_MayoristaTres, $("#cxcantidad" + idarticulo)[0]);
        }

        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

// Variable global que guarda cuál input está activo
let inputActivo = "cefectivo"; // valor por defecto

function setInputActivo(idCampo) {
    inputActivo = idCampo;
    // Opcional: para depurar
    // console.log("Input activo:", idCampo);
}

function ingresarNumero(valor, e) {
    e.preventDefault(); // previene comportamiento del botón
    const input = document.getElementById(inputActivo); // dinámico
    if (input.value === "0" && valor !== ".") {
        input.value = valor;
    } else {
        input.value += valor;
    }
    calcularefectivo();
}

function borrarNumero(e) {
    e.preventDefault();
    const input = document.getElementById(inputActivo);
    input.value = input.value.slice(0, -1) || "0";
    calcularefectivo();
}

function limpiarEfectivo(e) {
    e.preventDefault();
    const input = document.getElementById(inputActivo);
    input.value = "0";
    calcularefectivo();
}

//DETALLES DE CREDITOS
function mostrarBotonGenerar() {
    const numPagos = document.getElementById('numero_pagos').value;
    const contenedorBoton = document.getElementById('contenedor-boton');
    const resultadosDiv = document.getElementById('resultados');

    resultadosDiv.innerHTML = '';
    contenedorBoton.innerHTML = '';

    if (numPagos > 0) {
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.textContent = 'Generar Detalles';
        boton.classList.add('btn', 'btn-primary');
        boton.onclick = generarDetalles;
        contenedorBoton.appendChild(boton);
    }
}
let detallesCredito = [];

function generarDetalles() {
    const numPagos = parseInt(document.getElementById('numero_pagos').value);
    const fechaPagoStr = document.getElementById('fecha_hora_pago').value;
    const montoAbono = parseFloat(document.getElementById('ccredito').value);

    const resultadosDiv = document.getElementById('resultados');

    if (numPagos <= 0 || isNaN(numPagos) || montoAbono <= 0 || isNaN(montoAbono) || !fechaPagoStr) {
        resultadosDiv.innerHTML = `<div class="alert alert-warning">Por favor, complete todos los campos con valores válidos.</div>`;
        return;
    }

    const valorCuota = montoAbono / numPagos;

    let tablaHTML = `
        <h3>Detalle de Pagos</h3>
        <table class="table table-bordered" id="detalle_credito">
            <thead>
                <tr>
                    <th>No de Pago</th>
                    <th>Fecha Pago</th>
                    <th>Valor Cuota</th>
                </tr>
            </thead>
            <tbody>
    `;

    // Limpiar el arreglo de detalles antes de volver a generarlos
    detallesCredito = [];
    let fechaActual = new Date(fechaPagoStr + 'T00:00:00');

    for (let i = 1; i <= numPagos; i++) {
        const fechaFormateada = fechaActual.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        // Crear un objeto para cada detalle del pago
        const pago = {
            num_pago: i,
            fecha: fechaActual.toISOString().slice(0, 10), // Formato YYYY-MM-DD para la base de datos
            valor_cuota: valorCuota.toFixed(2)
        };
        detallesCredito.push(pago); // Agregar el objeto al arreglo

        tablaHTML += `
            <tr>
                <td>${pago.num_pago}</td>
                <td>${fechaFormateada}</td>
                <td>Q ${pago.valor_cuota}</td>
            </tr>
        `;
        fechaActual.setDate(fechaActual.getDate() + 30);
    }

    tablaHTML += `</tbody></table>`;
    resultadosDiv.innerHTML = tablaHTML;

    // Aquí puedes agregar el botón para enviar los datos si lo deseas
    // O puedes llamar a la función de envío en el evento de guardar de tu formulario principal
}


function mostrarextras(cont, idarticulo) {
    const extrasContainer = $('#extras-container-' + cont);
    const extrasRow = $('#extras-row-' + cont);

    if (extrasRow.is(':visible')) {
        extrasRow.hide();
        return;
    }

    $.post("../ajax/venta_rapida.php?op=listarArticulosExtras", { idarticulo: idarticulo }, function (response) {
        console.log("data ", response);
        try {

            const data = typeof response === 'string' ? JSON.parse(response) : response;
            const articulos = data.aaData || [];

            const extras = articulos.filter(item => item[4] === 'Extra');
            const toppings = articulos.filter(item => item[4] === 'Topping');

            // --- Estructura HTML (sin cambios aquí) ---
            const html = `
                <div class="row p-2" style="background-color: #f8f9fa; margin: 0;">
                    <div class="col-md-6">
                        <h5>Extras</h5>
                        <div class="row extras-column" data-tipo="Extra" data-cont="${cont}">
                            ${extras.map(articulo => `
                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input extras-checkbox" type="checkbox"
                                            id="art-${cont}-${articulo[1]}"
                                            name="check_extras[]"
                                            value="${articulo[1]}"
                                            data-idarticulo-extra="${articulo[1]}"
                                            data-cantidad-extra="${articulo[3]}"
                                            data-idproducto-extra="${articulo[2]}"
                                            data-precio="${articulo[5]}"
                                            data-tipo-item="Extra"
                                            data-cont="${cont}">
                                        <label class="form-check-label" for="art-${cont}-${articulo[1]}">
                                            ${articulo[0]} || Q.${articulo[5]}
                                        </label>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        <div><strong>Subtotal Extras: Q.<span class="subtotal-extra" data-cont="${cont}">0.00</span></strong></div>
                    </div>

                    <div class="col-md-6">
                        <h5>Toppings</h5>
                        <div class="row toppings-column" data-tipo="Topping" data-cont="${cont}">
                            ${toppings.map(articulo => `
                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input extras-checkbox" type="checkbox"
                                            id="art-${cont}-${articulo[1]}"
                                            name="check_extras[]"
                                            value="${articulo[1]}"
                                            data-idarticulo-extra="${articulo[1]}"
                                            data-cantidad-extra="${articulo[3]}"
                                            data-idproducto-extra="${articulo[2]}"
                                            data-precio="${articulo[5]}"
                                            data-tipo-item="Topping"
                                            data-cont="${cont}">
                                        <label class="form-check-label" for="art-${cont}-${articulo[1]}">
                                            ${articulo[0]}
                                        </label>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
            // --- Fin Estructura HTML ---

            extrasContainer.html(html);
            extrasRow.show();

            // *** NUEVO: Manejador de eventos para los checkboxes de extras/toppings ***
            $(`#extras-container-${cont} .extras-checkbox`).on('change', function () {
                // Llama a la nueva función para actualizar los totales.
                actualizarSubtotalExtrasYTotal(cont);
            });
            // *************************************************************************

        } catch (error) {
            console.error('Error al procesar la respuesta:', error);
            console.log('Respuesta original:', response);
            extrasContainer.html('<div class="alert alert-danger">Error al cargar los artículos</div>');
            extrasRow.show();
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la petición AJAX:', textStatus, errorThrown);
        extrasContainer.html('<div class="alert alert-danger">Error al cargar los artículos</div>');
        extrasRow.show();
    });
}

function actualizarSubtotalExtrasYTotal(cont) {
    let subtotalExtras = 0.0;
    $(`#extras-container-${cont} .extras-checkbox:checked[data-tipo-item="Extra"]`).each(function () {
        const precio = parseFloat($(this).data('precio'));
        const cantidadextra = parseFloat($(this).data('cantidad-extra'));
        if (!isNaN(precio)) {
            console.log("cantidadextra ", cantidadextra);
            console.log("precio ", precio);
            let subtotal = cantidadextra * precio;
            subtotalExtras += subtotal;
        }
    });

    $(`.subtotal-extra[data-cont="${cont}"]`).text(subtotalExtras.toFixed(2));

    calcularTotales();
}


function mostrarextras_Extras(cont, idarticulo, idcotizacion) {
    console.log("mostrar extras cotizacion");
    const extrasContainer = $('#extras-container-' + cont);
    const extrasRow = $('#extras-row-' + cont);

    // 1. Ocultar si ya está visible
    if (extrasRow.is(':visible')) {
        extrasRow.hide();
        return;
    }

    // Limpiamos el contenedor mientras carga
    extrasContainer.html('<div class="text-center p-3">Cargando artículos...</div>');
    extrasRow.show(); // Mostramos el row para que se vea el mensaje de carga

    $.ajax({
        url: "../ajax/venta_rapida.php?op=listarArticulosExtrasYSeleccionados",
        type: "POST",
        data: {
            idarticulo: idarticulo,
            idcotizacion: idcotizacion
        },
        dataType: "json",

        success: function (data) {
            try {
                if (!data || !data.articulosDisponibles || !data.articulosDisponibles.aaData || !Array.isArray(data.extrasSeleccionados)) {
                    throw new Error("Estructura de datos JSON no válida o incompleta.");
                }

                const articulos = data.articulosDisponibles.aaData;
                const idsSeleccionados = data.extrasSeleccionados;

                const extras = articulos.filter(item => item[4] === 'Extra');
                const toppings = articulos.filter(item => item[4] === 'Topping');

                const isChecked = (articulo) => idsSeleccionados.includes(articulo[1]);

                let subtotalInicial = 0.00;

                const html = `
                    <div class="row p-2" style="background-color: #f8f9fa; margin: 0;">
                        <div class="col-md-6">
                            <h5>Extras</h5>
                            <div class="row extras-column" data-tipo="Extra" data-cont="${cont}">
                                ${extras.map(articulo => {
                    const checkedAttr = isChecked(articulo) ? 'checked' : '';
                    if (checkedAttr && articulo[4] === 'Extra') {
                        subtotalInicial += (parseFloat(articulo[5]) * parseFloat(articulo[3])) || 0;
                    }
                    return `
                                        <div class="col-md-12 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input extras-checkbox" type="checkbox"
                                                    id="art-${cont}-${articulo[1]}"
                                                    name="check_extras[]"
                                                    value="${articulo[1]}"
                                                    data-idarticulo-extra="${articulo[1]}"
                                                    data-cantidad-extra="${articulo[3]}"
                                                    data-idproducto-extra="${articulo[2]}"
                                                    data-precio="${articulo[5]}"
                                                    data-tipo-item="Extra"
                                                    data-cont="${cont}"
                                                    ${checkedAttr}>
                                                <label class="form-check-label" for="art-${cont}-${articulo[1]}">
                                                    ${articulo[0]} || Q.${articulo[5]}
                                                </label>
                                            </div>
                                        </div>
                                    `;
                }).join('')}
                            </div>
                            <div><strong>Subtotal Extras: Q.<span class="subtotal-extra" data-cont="${cont}">${subtotalInicial.toFixed(2)}</span></strong></div>
                        </div>

                        <div class="col-md-6">
                            <h5>Toppings</h5>
                            <div class="row toppings-column" data-tipo="Topping" data-cont="${cont}">
                                ${toppings.map(articulo => `
                                    <div class="col-md-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input extras-checkbox" type="checkbox"
                                                id="art-${cont}-${articulo[1]}"
                                                name="check_extras[]"
                                                value="${articulo[1]}"
                                                data-idarticulo-extra="${articulo[1]}"
                                                data-cantidad-extra="${articulo[3]}"
                                                data-idproducto-extra="${articulo[2]}"
                                                data-precio="${articulo[5]}"
                                                data-tipo-item="Topping"
                                                data-cont="${cont}"
                                                ${isChecked(articulo) ? 'checked' : ''}>
                                            <label class="form-check-label" for="art-${cont}-${articulo[1]}">
                                                ${articulo[0]} || Q.${articulo[5]}
                                            </label>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;

                extrasContainer.html(html);

                calcularTotales();

                $(`#extras-container-${cont} .extras-checkbox`).on('change', function () {
                    actualizarSubtotalExtrasYTotal(cont);
                });

            } catch (error) {
                console.error('Error al procesar la respuesta o estructura inválida:', error);
                console.log('Respuesta cruda:', data);
                extrasContainer.html(`<div class="alert alert-danger">Error al construir el HTML: ${error.message}</div>`);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error en la petición AJAX:', textStatus, errorThrown);
            extrasContainer.html('<div class="alert alert-danger">Error de red al cargar los artículos. Revise la consola para más detalles.</div>');
            extrasRow.show();
        }
    });
}

function cambiarformapago(idventa) {
    load();
    $.post("../ajax/venta.php?op=cambiarformapago", { idventa: idventa }, function (data, status) {


        // Parseamos la data
        data = JSON.parse(data);
        // console.log("data ", data);

        // Verificamos si la data viene vacía o es nula
        if (!data || !data.idventa) {
            Swal.fire({
                icon: 'info',
                title: 'No se puede cambiar forma de pago',
                text: 'La forma de pago no se puede cambiar porque no esta en el dia de la operacion o ya se genero el cierre correspondiente.',
                confirmButtonText: 'Aceptar'
            });
            return; // Detenemos la ejecución si la cotización ya fue cobrada o los datos están vacíos
        }
        Swal.close()

        // Si la data es válida, mostramos el formulario y asignamos los valores
        $("#myModalFormapago").modal("show");
        //
        $("#idventa_formapago").val(data.idventa);
        //


        $("#forma_pago_formapago").val(data.forma_pago);
        $("#forma_pago_formapago").selectpicker('refresh');

        $("#destino_formapago").val(data.destino);
        $("#destino_formapago").selectpicker('refresh');

        $("#tipo_entrega_formapago").val(data.tipo_entrega);
        $("#tipo_entrega_formapago").selectpicker('refresh');


        $("#idvendedor_formapago").val(data.idvendedor);
        $("#idvendedor_formapago").selectpicker('refresh');


        $("#tipo_pagoBacVisaNet_formapago").val(data.tipo_pagoBacVisaNet);
        $("#tipo_pagoBacVisaNet_formapago").selectpicker('refresh');

        $("#opcionesAdicionales_formapago").val(data.opcionesAdicionales);
        $("#opcionesAdicionales_formapago").selectpicker('refresh');


        $("#total_venta_formapago").val(data.total_venta);
        $("#total_ventades_formapago").val(data.total_ventades);

        // obtenerdetalleventarefacturado(data.idventa);

    });

    function obtenerdetalleventarefacturado(idventa) {
        $.post("../ajax/venta.php?op=obtenerdetalleventarefacturado", { idventa: idventa }, function (data) {
            //  console.log(data);
            data = JSON.parse(data);
            Swal.close()

            $.each(data, function (i, item) {
                agregarDetalleFormapago(item.idarticulo, item.nombre, item.precio_venta, item.stock, item.descuento_porcentaje,
                    item.precio_rango1, item.precio_rango1_Dos,
                    item.precio_rango2, item.precio_rango2_Dos,
                    item.precio_rango3, item.precio_rango3_Dos,
                    item.precio_rango1_Mecanico, item.precio_rango1_Distribuidor, item.precio_rango1_Mayorista,
                    item.precio_rango2_MecanicoDos, item.precio_rango2_DistribuidorDos, item.precio_rango2_MayoristaDos,
                    item.precio_rango3_MecanicoTres, item.precio_rango3_DistribuidorTres, item.precio_rango3_MayoristaTres,
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
                    item.precio_activado, item.facturar_cero, item.precio_compra,
                    item.cantidadpresentacion, item.totalcantidadpresentacion, item.presen,
                    item.precio_ventaSistema, item.precio_ventaSistema2, item.q_ref,
                    item.precio_recargoPV, item.precio_recargoQRef, item.descuento, item.descripcion_detalle, item.cantidad);
            });
        })
    }
}

$("#forma_pago_formapago").change(mostrarFormaPagoFormapago);

function mostrarFormaPagoFormapago() {
    var forma_pago = $("#forma_pago_formapago option:selected").text();
    if (forma_pago == 'Credito') {
        $("#div_formapago_formapago").hide();
        $("#valor_tarjeta_formapago").val(0);
        // modificarSubototalesTarjetaEfectivo();
        $("#div_facCambiaria_formapago").show();
    }
    else if (forma_pago == 'Efectivo/Tarjeta') {
        $("#div_formapago_formapago").show();
        $("#div_facCambiaria_formapago").hide();
    }
    else if (forma_pago == 'Tarjeta') {
        $("#div_formapago_formapago").show();
        $("#div_facCambiaria_formapago").hide();
    }
    else if (forma_pago == 'Efectivo') {
        $("#tipo_pagoBacVisaNet_formapago").val("Seleccione Uno");
        $("#tipo_pagoBacVisaNet_formapago").selectpicker('refresh');

        $("#opcionesAdicionales_formapago").val("Pago Directo");
        $("#opcionesAdicionales_formapago").selectpicker('refresh');

        $("#div_formapago_formapago").hide();
        $("#div_facCambiaria_formapago").hide();
    }
}

function mostrarOpcionesAdicionalesFormapago() {
    const tipoPago = document.getElementById("tipo_pagoBacVisaNet_formapago").value;
    const opcionesAdicionalesDiv = document.getElementById("opcionesAdicionalesDiv_formapago");
    const opcionesAdicionales = document.getElementById("opcionesAdicionales_formapago");

    // Limpiar opciones previas
    opcionesAdicionales.innerHTML = "";

    // Verificar qué opción fue seleccionada y agregar las opciones correspondientes
    if (tipoPago in opcionesPorTipoPago_formapago) {
        opcionesAdicionalesDiv.style.display = "block";
        opcionesPorTipoPago_formapago[tipoPago].forEach(opcion => {
            const opt = document.createElement("option");
            opt.value = opcion;
            opt.textContent = opcion;
            opcionesAdicionales.appendChild(opt);
        });

        // 🔄 Refrescar selectpicker (muy importante)
        $("#opcionesAdicionales_formapago").selectpicker("refresh");

    } else {
        opcionesAdicionalesDiv.style.display = "none";
        $("#opcionesAdicionales_formapago").selectpicker("refresh");
    }
}

// ✅ Mapeo de tipos de pago a sus opciones adicionales
const opcionesPorTipoPago_formapago = {
    VISANET: ["Pago Directo"],
    BAC: ["Pago Directo"]
};

// ✅ Evento al cambiar opción adicional
$("#opcionesAdicionales_formapago").on("change", function () {
    const opcionSeleccionada = $(this).find("option:selected").text();
    if (opcionSeleccionada === "Pago Directo") {
        $("#valor_tarjeta_formapago").val("0");
    }
});

function calcularefectivoFormapago() {
    // $("#cefectivo").focus() 
    try {


        var total = parseFloat($("#total_venta_formapago").val());
        var efectivo = parseFloat($("#cefectivo_formapago").val());
        var cefectivo_tarjeta = parseFloat($("#ctarjeta_formapago").val());
        var cefectivo_transferencia = parseFloat($("#ctransferencia_formapago").val());
        var cefectivo_credito = parseFloat($("#ccredito_formapago").val());

        var cambio1 = (efectivo + cefectivo_tarjeta + cefectivo_transferencia + cefectivo_credito);
        var cambio2 = cambio1 - total

        $("#cambio_formapago").html("Q." + cambio2.toFixed(2));
        $("#rescambio_formapago").val(cambio2.toFixed(2));


    } catch (ex) {

    }
};


function calculo_formapago() {

    var numeropagos = document.getElementById('numero_pagos_formapago').value;
    var totalventa = document.getElementById('ccredito_formapago').value;

    var resmontoabono = (totalventa / numeropagos);

    document.getElementById('monto_abono_formapago').innerHTML = resmontoabono;
    $("#monto_abono_formapago").val(resmontoabono.toFixed(2));

    ////////////

    const fechaPagoInput = $("#fecha_hora_pago_formapago").val(); // Fecha como cadena
    const fechaPago = new Date(fechaPagoInput); // Convertir a objeto Date

    // Calcular fecha de vencimiento en base a número de pagos
    if (!isNaN(numeropagos) && numeropagos > 0) {
        const fechaVencimiento = new Date(fechaPago); // Copiar la fecha inicial
        fechaVencimiento.setDate(fechaPago.getDate() + numeropagos * 30); // Sumar días (30 por cada pago)

        // Formatear la fecha en formato YYYY-MM-DD
        const fechaVencimientoFormateada = fechaVencimiento.toISOString().split("T")[0];

        // Actualizar el valor en el campo de vencimiento
        $("#fecha_hora_vencimiento_factura_formapago").val(fechaVencimientoFormateada);
    }

}

$("#btnGuardarm_formapago").click(function (e) {
    $('#myModalFormapago').modal('hide');
    guardaryeditar_formapago(e);
});

function guardaryeditar_formapago(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardarm_formapago").prop("disabled", true);
    var formData = new FormData($("#formulariom_formapago")[0]);

    $.ajax({
        url: "../ajax/venta.php?op=guardaryeditar_formapago",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //  console.log(datos);
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


// Variable global para controlar las cotizaciones en pantalla
var cotizacionesSeleccionadas = [];

// 1. Cargar el DataTable en el Modal
function listarCotizacionesPendientes() {
    $('#tbl_cotizaciones_modal').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [],
        "ajax": {
            url: '../ajax/cotizaciones.php?op=listarPendientes',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[1, "desc"]]
    });
}

// 2. Seleccionar / Desmarcar todos los checkboxes
function seleccionarTodosModal(source) {
    var checkboxes = document.querySelectorAll('#tbl_cotizaciones_modal .chk_cotizacion');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}

// 3. Pasar las cotizaciones seleccionadas a la tabla de la vista
function agregarSeleccionadasModal() {
    $('.chk_cotizacion:checked').each(function () {
        var id = $(this).val();
        var cliente = $(this).data('cliente');
        var total = $(this).data('total');

        // Evitar duplicados
        if (!cotizacionesSeleccionadas.includes(id)) {
            cotizacionesSeleccionadas.push(id);

            var fila = '<tr id="fila_' + id + '">' +
                '<td class="text-center"><button type="button" class="btn btn-danger btn-xs" onclick="eliminarSeleccionada(\'' + id + '\')"><i class="fa fa-trash"></i> Quitar</button></td>' +
                '<td class="text-center"><b>' + id + '</b><input type="hidden" name="cotizaciones[]" value="' + id + '"></td>' +
                '<td>' + cliente + '</td>' +
                '<td class="text-right">Q. ' + parseFloat(total).toFixed(2) + '</td>' +
                '</tr>';

            $('#tbody_seleccionadas').append(fila);
        }
    });

    $('#modalCotizaciones').modal('hide');
}

// 4. Quitar cotización si te equivocaste al agregarla
function eliminarSeleccionada(id) {
    $('#fila_' + id).remove();
    var index = cotizacionesSeleccionadas.indexOf(id);
    if (index > -1) {
        cotizacionesSeleccionadas.splice(index, 1);
    }
}

// 5. BOTÓN DE ACCIÓN: Dispara tu función asíncrona de procesamiento en lote
function ejecutarProcesoVenta() {
    if (cotizacionesSeleccionadas.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe seleccionar al menos una cotización para procesar.'
        });
        return;
    }

    // Ejecutamos la función asíncrona pasándole el array de IDs seleccionados
    procesarCotizacionesEnLote(cotizacionesSeleccionadas);
}

// 6. TU FUNCIÓN ASÍNCRONA (Totalmente compatible y conectada)
async function procesarCotizacionesEnLote(cotizaciones) {
    let exitosas = 0;
    let fallidas = 0;
    let errores = [];

    // Generar Lote Único
    let ahora = new Date();
    let dia = String(ahora.getDate()).padStart(2, '0');
    let mes = String(ahora.getMonth() + 1).padStart(2, '0');
    let anio = ahora.getFullYear();
    let hora = String(ahora.getHours()).padStart(2, '0') +
        String(ahora.getMinutes()).padStart(2, '0') +
        String(ahora.getSeconds()).padStart(2, '0');

    let idusuario_js = $("#idusuario_session").val() || "";
    let loteUnico = `LOTE_${dia}_${mes}_${anio}_${hora}_${idusuario_js}`;

    Swal.fire({
        title: 'Procesando ventas...',
        html: 'Por favor espere mientras se generan las ventas.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    for (let id of cotizaciones) {
        await new Promise((resolve) => {
            $.post("../ajax/venta.php?op=procesar_venta_directa", {
                idcotizacion: id,
                venta_lote: loteUnico
            }, function (data) {
                try {
                    let res = JSON.parse(data);
                    if (res.status === 'ok') {
                        exitosas++;
                    } else {
                        fallidas++;
                        errores.push(`Coti #${id}: ${res.msg}`);
                    }
                } catch (e) {
                    fallidas++;
                    errores.push(`Coti #${id}: Error de respuesta del servidor`);
                }
                resolve();
            });
        });
    }

    // Al finalizar todas las ventas:
    let mensajeHtml = `<p><b>Exitosas:</b> ${exitosas}</p><p><b>Fallidas:</b> ${fallidas}</p>`;
    if (errores.length > 0) {
        mensajeHtml += `<br><small style="color:red">${errores.join('<br>')}</small>`;
    }

    Swal.fire({
        icon: fallidas === 0 ? 'success' : 'warning',
        title: 'Proceso finalizado',
        html: mensajeHtml,
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-print"></i> Imprimir Reporte Lote',
        cancelButtonText: 'Aceptar',
        confirmButtonColor: '#3085d6'
    }).then((result) => {
        // Limpiamos la lista temporal tras el proceso
        $('#tbody_seleccionadas').html('');
        cotizacionesSeleccionadas = [];

        if (result.isConfirmed) {
            window.open("../reportes/ex_venta_lote.php?lote=" + loteUnico, "_blank");
        }

        if (typeof listar === 'function') {
            listar();
        }
    });
}


var articuloSeleccionado = null;

function listarArticulos_v2() {
    tabla = $('#tblarticulos_rapido').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [],
        "ajax": {
            url: '../ajax/venta.php?op=listarArticulosVentaCantidad_v2',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[1, "asc"]],
        "drawCallback": function () {
            $('#tblarticulos_rapido th').removeAttr('tabindex');
        }
    });
}

$(document).on('keydown', '#modalBuscarArticulos div.dataTables_filter input', function (e) {
    if (e.which === 9 || e.which === 13) {
        e.preventDefault();
        var primerBoton = $('#tblarticulos_rapido tbody tr:first-child .btn-seleccionar');

        if (primerBoton.length > 0) {
            primerBoton.focus();
        }
    }
});

$(document).on('keydown', '.btn-seleccionar', function (e) {
    var trActual = $(this).closest('tr');

    if (e.which === 40) {
        e.preventDefault();
        var siguienteBoton = trActual.next().find('.btn-seleccionar');
        if (siguienteBoton.length > 0) siguienteBoton.focus();
    } else if (e.which === 38) {
        e.preventDefault();
        var anteriorBoton = trActual.prev().find('.btn-seleccionar');
        if (anteriorBoton.length > 0) {
            anteriorBoton.focus();
        } else {
            $('#modalBuscarArticulos div.dataTables_filter input').focus().select();
        }
    }
});

$('#modalBuscarArticulos').on('shown.bs.modal', function () {
    var searchInput = $('#modalBuscarArticulos div.dataTables_filter input');
    searchInput.focus().select();
});

function abrirModalPresentacion(dataBase64) {
    var jsonString = atob(dataBase64);
    articuloSeleccionado = JSON.parse(jsonString);

    $('#conf_nombre_producto').text(articuloSeleccionado.nombre);
    $('#conf_cantidad').val(1);
    var selectPresentacion = $('#conf_presentacion');
    selectPresentacion.empty();

    var presentaciones = [
        { nombre: articuloSeleccionado.nombre_01, stock: articuloSeleccionado.stock_unidad, precio: articuloSeleccionado.precio_unidad },
        { nombre: articuloSeleccionado.nombre_02, stock: articuloSeleccionado.stock_blister, precio: articuloSeleccionado.precio_blister },
        { nombre: articuloSeleccionado.nombre_03, stock: articuloSeleccionado.stock_caja, precio: articuloSeleccionado.precio_caja },
        { nombre: articuloSeleccionado.nombre_04, stock: articuloSeleccionado.stock_fardo, precio: articuloSeleccionado.precio_fardo },
        { nombre: articuloSeleccionado.nombre_05, stock: articuloSeleccionado.stock_sacos, precio: articuloSeleccionado.precio_sacos },
        { nombre: articuloSeleccionado.nombre_06, stock: articuloSeleccionado.stock_paquete, precio: articuloSeleccionado.precio_paquete },
        { nombre: articuloSeleccionado.nombre_07, stock: articuloSeleccionado.stock_07, precio: articuloSeleccionado.precio_07 },
        { nombre: articuloSeleccionado.nombre_08, stock: articuloSeleccionado.stock_08, precio: articuloSeleccionado.precio_08 },
        { nombre: articuloSeleccionado.nombre_09, stock: articuloSeleccionado.stock_09, precio: articuloSeleccionado.precio_09 },
        { nombre: articuloSeleccionado.nombre_10, stock: articuloSeleccionado.stock_10, precio: articuloSeleccionado.precio_10 },
        { nombre: articuloSeleccionado.nombre_11, stock: articuloSeleccionado.stock_11, precio: articuloSeleccionado.precio_11 },
        { nombre: articuloSeleccionado.nombre_12, stock: articuloSeleccionado.stock_12, precio: articuloSeleccionado.precio_12 },
        { nombre: articuloSeleccionado.nombre_13, stock: articuloSeleccionado.stock_13, precio: articuloSeleccionado.precio_13 },
        { nombre: articuloSeleccionado.nombre_14, stock: articuloSeleccionado.stock_14, precio: articuloSeleccionado.precio_14 },
        { nombre: articuloSeleccionado.nombre_15, stock: articuloSeleccionado.stock_15, precio: articuloSeleccionado.precio_15 },
        { nombre: articuloSeleccionado.nombre_16, stock: articuloSeleccionado.stock_16, precio: articuloSeleccionado.precio_16 },
        { nombre: articuloSeleccionado.nombre_17, stock: articuloSeleccionado.stock_17, precio: articuloSeleccionado.precio_17 },
        { nombre: articuloSeleccionado.nombre_18, stock: articuloSeleccionado.stock_18, precio: articuloSeleccionado.precio_18 },
        { nombre: articuloSeleccionado.nombre_19, stock: articuloSeleccionado.stock_19, precio: articuloSeleccionado.precio_19 },
        { nombre: articuloSeleccionado.nombre_20, stock: articuloSeleccionado.stock_20, precio: articuloSeleccionado.precio_20 }
    ];

    var opcionesCreadas = 0;
    presentaciones.forEach(function (item) {
        if (item.nombre && parseFloat(item.stock) > 0) {
            selectPresentacion.append(new Option(item.nombre, item.nombre, false, false));
            var $lastOption = $(selectPresentacion.find('option').last());
            $lastOption.data('precio', item.precio);
            $lastOption.data('stock', item.stock);
            opcionesCreadas++;
        }
    });
    if (opcionesCreadas === 0) {
        selectPresentacion.append(new Option("UNIDAD", "UNIDAD", false, false));
        var $lastOption = $(selectPresentacion.find('option').last());
        $lastOption.data('precio', articuloSeleccionado.precio_venta);
        $lastOption.data('stock', articuloSeleccionado.stock); // Asignamos stock por defecto
    }

    actualizarPrecioPresentacion();
    // $('#modalBuscarArticulos').modal('hide');
    $('#modalConfigurarProducto').modal('show');
}

function actualizarPrecioPresentacion() {
    var selectedOption = $('#conf_presentacion option:selected');
    var precio = selectedOption.data('precio');
    var stock = selectedOption.data('stock');

    if (!precio || parseFloat(precio) === 0) {
        precio = articuloSeleccionado.precio_venta;
    }
    if (!stock) {
        stock = articuloSeleccionado.stock;
    }

    $('#conf_precio').val(precio);
    $('#conf_cantidadpresentacion').val(stock);

}

$('#modalConfigurarProducto').on('shown.bs.modal', function () {
    $('#conf_cantidad').focus().select();
});

$('#conf_cantidad, #conf_precio').on('keydown', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        confirmarAgregarAlDetalle();
    }
});

function confirmarAgregarAlDetalle() {
    var cantidad = $('#conf_cantidad').val();
    var precioConfigurado = $('#conf_precio').val();
    var presentacionElegida = $('#conf_presentacion').val();
    var cantidadpresentacion = $('#conf_cantidadpresentacion').val();

    if (!cantidad || cantidad <= 0) {
        alert("Ingrese una cantidad válida.");
        return;
    }

    agregarDetalleCantidadRapida(
        articuloSeleccionado.idarticulo,
        articuloSeleccionado.nombre,
        precioConfigurado,
        articuloSeleccionado.stock,
        articuloSeleccionado.descuento_porcentaje,
        articuloSeleccionado.precio_rango1, articuloSeleccionado.precio_rango1_Dos,
        articuloSeleccionado.precio_rango2, articuloSeleccionado.precio_rango2_Dos,
        articuloSeleccionado.precio_rango3, articuloSeleccionado.precio_rango3_Dos,
        articuloSeleccionado.precio_rango1_Mecanico, articuloSeleccionado.precio_rango1_Distribuidor, articuloSeleccionado.precio_rango1_Mayorista,
        articuloSeleccionado.precio_rango2_MecanicoDos, articuloSeleccionado.precio_rango2_DistribuidorDos, articuloSeleccionado.precio_rango2_MayoristaDos,
        articuloSeleccionado.precio_rango3_MecanicoTres, articuloSeleccionado.precio_rango3_DistribuidorTres, articuloSeleccionado.precio_rango3_MayoristaTres,
        articuloSeleccionado.nombre_01, articuloSeleccionado.stock_unidad, articuloSeleccionado.precio_unidad,
        articuloSeleccionado.nombre_02, articuloSeleccionado.stock_blister, articuloSeleccionado.precio_blister,
        articuloSeleccionado.nombre_03, articuloSeleccionado.stock_caja, articuloSeleccionado.precio_caja,
        articuloSeleccionado.nombre_04, articuloSeleccionado.stock_fardo, articuloSeleccionado.precio_fardo,
        articuloSeleccionado.nombre_05, articuloSeleccionado.stock_sacos, articuloSeleccionado.precio_sacos,
        articuloSeleccionado.nombre_06, articuloSeleccionado.stock_paquete, articuloSeleccionado.precio_paquete,
        articuloSeleccionado.nombre_07, articuloSeleccionado.stock_07, articuloSeleccionado.precio_07,
        articuloSeleccionado.nombre_08, articuloSeleccionado.stock_08, articuloSeleccionado.precio_08,
        articuloSeleccionado.nombre_09, articuloSeleccionado.stock_09, articuloSeleccionado.precio_09,
        articuloSeleccionado.nombre_10, articuloSeleccionado.stock_10, articuloSeleccionado.precio_10,
        articuloSeleccionado.nombre_11, articuloSeleccionado.stock_11, articuloSeleccionado.precio_11,
        articuloSeleccionado.nombre_12, articuloSeleccionado.stock_12, articuloSeleccionado.precio_12,
        articuloSeleccionado.nombre_13, articuloSeleccionado.stock_13, articuloSeleccionado.precio_13,
        articuloSeleccionado.nombre_14, articuloSeleccionado.stock_14, articuloSeleccionado.precio_14,
        articuloSeleccionado.nombre_15, articuloSeleccionado.stock_15, articuloSeleccionado.precio_15,
        articuloSeleccionado.nombre_16, articuloSeleccionado.stock_16, articuloSeleccionado.precio_16,
        articuloSeleccionado.nombre_17, articuloSeleccionado.stock_17, articuloSeleccionado.precio_17,
        articuloSeleccionado.nombre_18, articuloSeleccionado.stock_18, articuloSeleccionado.precio_18,
        articuloSeleccionado.nombre_19, articuloSeleccionado.stock_19, articuloSeleccionado.precio_19,
        articuloSeleccionado.nombre_20, articuloSeleccionado.stock_20, articuloSeleccionado.precio_20,
        articuloSeleccionado.precio_activado, articuloSeleccionado.facturar_cero, articuloSeleccionado.precio_compra,
        cantidad, cantidadpresentacion
    );
    if (typeof cont !== 'undefined' && cont > 0) {
        var ultimoIndex = cont - 1;
        $('#presentacionselect' + ultimoIndex).val(presentacionElegida);
    }
    $('#modalConfigurarProducto').modal('hide');
}

$('#modalConfigurarProducto').on('hidden.bs.modal', function () {
    if ($('#modalBuscarArticulos').hasClass('in') || $('#modalBuscarArticulos').is(':visible')) {
        $('#modalBuscarArticulos div.dataTables_filter input').focus().select();
    }
});