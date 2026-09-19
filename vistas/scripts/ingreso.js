var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    listar_preciosxproveedor();



    /*$("#formulario").on("submit", function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });*/

    $("#btnGuardar").click(function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });

    $("#btnGuardar2").click(function (e) {
        guardaryeditar2(e);
    });

    $("#btncargar").click(function () {

        var idcotizacion = $("#idcotizacion").val();
        if (idcotizacion == "") {
            alert("Debe Colocar un Id de Cotizacion Valido")
            return;
        }

        obtenerClienteCotizacion(idcotizacion);

    });

    $.post("../ajax/ordenes_compra.php?op=selectSucursal", function (r) {
        var options = '<option value="">Seleccione una Sucursal</option>' + r;
        $("#idsucursalOrigen").html(options).selectpicker('refresh');
        $("#idsucursalOrigen").html(options).selectpicker('refresh');
    });

}

function obtenerClienteCotizacion(idorden_compra) {
    $.post("../ajax/sucursal.php?op=obtenerClaveIngresos", function (data, status) {
        data = JSON.parse(data);
        let clave_ingresos_ajax = data.clave_ingresos;

        Swal.fire({
            title: 'Ingresa la contraseña de Autorización',
            input: 'password', // Tipo de campo 'password' para ocultar la entrada
            inputAttributes: {
                autocapitalize: 'off',
                autocomplete: 'off', // Desactiva el autocompletado
                placeholder: 'Contraseña',
                maxlength: 100,
                required: true
            },
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                if (password !== clave_ingresos_ajax) { // Validar contraseña
                    Swal.showValidationMessage('Contraseña no válida');
                    return false;
                }
                return password;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Continuar con la petición AJAX
                $.post("../ajax/ordenes_compra.php?op=mostrar", { idorden_compra: idorden_compra }, function (response, status) {
                    console.log("Respuesta del servidor:", response); // Verifica qué está devolviendo el servidor

                    try {
                        // Parsear la respuesta JSON
                        let data = JSON.parse(response);

                        // Verificar si hay errores en la respuesta
                        if (data.status === false) {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'No se pudo procesar la solicitud.',
                                icon: 'error',
                                timer: 5000,
                                timerProgressBar: true
                            });
                            return; // Detener la ejecución si hay un error
                        }

                        if (data.estado_compra == "Orden de compra") {
                            Swal.fire({
                                title: 'Error',
                                text: "Solo puedes Ingresar las Ordenes de Compra en estado: Ingresar Orden de Compra",
                                icon: 'error',
                                timerProgressBar: true
                            });
                            return;
                        }
                        // Continuar con el llenado de datos (si la respuesta no tiene un campo `status`, trabaja directamente con `data`)
                        mostrarform(true);

                        $("#idorden_compra").val(data.idorden_compra);
                        $("#codigo_cliente").val(data.codigo_cliente);
                        $("#nit").val(data.nit);
                        $("#nombre_cliente").val(data.nombre_cliente);
                        $("#telefono_cliente").val(data.telefono_cliente);
                        $("#direccion_cliente").val(data.direccion_cliente);
                        $("#correo_cliente").val(data.correo_cliente);
                        $("#idcliente").val(data.idcliente);

                        $("#tipo_documento_cliente").val(data.tipo_documento_cliente);
                        $("#tipo_documento_cliente").selectpicker("refresh");

                        $("#tipo_ingreso_producion").val(data.tipo_ingreso || ''); // Manejar posibles valores faltantes
                        $("#tipo_ingreso_producion").selectpicker("refresh");

                        $("#fecha_hora").val(data.fecha);

                        $("#tipo_comprobante").val(data.tipo_comprobante);
                        $("#tipo_comprobante").selectpicker("refresh");

                        $("#serie_comprobante").val(data.serie_comprobante);
                        $("#num_comprobante").val(data.num_comprobante);

                        $("#impuesto").val(data.impuesto);

                        $("#total_compra").val(data.total_compra);
                        $("#total_comprades").val(data.total_comprades);

                        $("#total_compra_r").val(data.total_compra);
                        $("#total_comprades_r").val(data.total_comprades);

                        $("#forma_pago").val(data.forma_pago);
                        $("#forma_pago").selectpicker("refresh");

                        $("#dias_credito").val(data.dias_credito);
                        $("#fecha_hora_pago_credito").val(data.fechahorapagocredito || ''); // Manejar valores nulos
                        $("#direccion_entrega_orden_compra").val(data.direccion_entrega_orden_compra);

                        $("#fecha_entrega_orden_compra").val(data.fechaentregaordencompra || ''); // Manejar valores nulos
                        $("#observacion_orden_compra").val(data.observacion_orden_compra);

                        // Llamar a obtenerdetalleingreso para cargar detalles adicionales
                        obtenerdetalleingreso_orden_compra(idorden_compra);
                    } catch (e) {
                        console.error("Error al procesar los datos:", e);

                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al procesar la respuesta del servidor.',
                            icon: 'error'
                        });
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Manejar errores de la petición AJAX
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo completar la solicitud. Por favor, inténtalo de nuevo.',
                        icon: 'error'
                    });
                });
            }
        });
    });
}



function obtenerdetalleingreso_orden_compra(idorden_compra) {
    $.post("../ajax/ordenes_compra.php?op=detalleingreso", { idorden_compra: idorden_compra }, function (data) {
        //console.log(data);
        data = JSON.parse(data);
        Swal.close()
        $.each(data, function (i, item) {
            agregarDetalle_orden_compra(item.idarticulo, item.articulo, item.precio_venta, item.precio_compra, item.stock, item.cantidad,
                item.descuento_porcentaje,
                item.precio_ventaNocturno,
                item.detalle_precio_rango1_Mecanico,
                item.detalle_precio_rango1_Distribuidor,
                item.detalle_precio_rango1_Mayorista,
                item.detalle_precio_rango2_MecanicoDos,
                item.detalle_precio_rango2_DistribuidorDos,
                item.detalle_precio_rango2_MayoristaDos,
                item.detalle_precio_rango3_MecanicoTres,
                item.detalle_precio_rango3_DistribuidorTres,
                item.detalle_precio_rango3_MayoristaTres,
                item.detalle_precio_unidad,
                item.detalle_precio_blister,
                item.detalle_precio_caja,
                item.detalle_precio_fardo,
                item.detalle_precio_sacos,
                item.detalle_precio_paquete,
                item.detalle_precio_07,
                item.detalle_precio_08,
                item.detalle_precio_09,
                item.detalle_precio_10,
                item.detalle_precio_11,
                item.detalle_precio_12,
                item.detalle_precio_13,
                item.detalle_precio_14,
                item.detalle_precio_15,
                item.detalle_precio_16,
                item.detalle_precio_17,
                item.detalle_precio_18,
                item.detalle_precio_19,
                item.detalle_precio_20,
                item.cantidadpresentacion, item.totalcantidadpresentacion, item.presentacion, item.fechavencimiento,
                item.precio_rango1_Mecanico,
                item.precio_rango1_Distribuidor,
                item.precio_rango1_Mayorista,
                item.precio_rango2_MecanicoDos,
                item.precio_rango2_DistribuidorDos,
                item.precio_rango2_MayoristaDos,
                item.precio_rango3_MecanicoTres,
                item.precio_rango3_DistribuidorTres,
                item.precio_rango3_MayoristaTres,
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
                item.nombre_20, item.stock_20, item.precio_20, item.facturar_cero, item.descripcion_detalle, item.idsucursalDestino, item.sucursalDestino);
        });
    })
}

function agregarDetalle_orden_compra(idarticulo, articulo, precio_venta, precio_compra, stock, cantidad,
    descuento_porcentaje,
    precio_ventaNocturno,
    detalle_precio_rango1_Mecanico,
    detalle_precio_rango1_Distribuidor,
    detalle_precio_rango1_Mayorista,
    detalle_precio_rango2_MecanicoDos,
    detalle_precio_rango2_DistribuidorDos,
    detalle_precio_rango2_MayoristaDos,
    detalle_precio_rango3_MecanicoTres,
    detalle_precio_rango3_DistribuidorTres,
    detalle_precio_rango3_MayoristaTres,
    detalle_precio_unidad,
    detalle_precio_blister,
    detalle_precio_caja,
    detalle_precio_fardo,
    detalle_precio_sacos,
    detalle_precio_paquete,
    detalle_precio_07,
    detalle_precio_08,
    detalle_precio_09,
    detalle_precio_11,
    detalle_precio_10,
    detalle_precio_12,
    detalle_precio_13,
    detalle_precio_14,
    detalle_precio_15,
    detalle_precio_16,
    detalle_precio_17,
    detalle_precio_18,
    detalle_precio_19,
    detalle_precio_20,
    cantidadpresentacion, totalcantidadpresentacion, presentacion, fechavencimiento,
    precio_rango1_Mecanico,
    precio_rango1_Distribuidor,
    precio_rango1_Mayorista,
    precio_rango2_MecanicoDos,
    precio_rango2_DistribuidorDos,
    precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres,
    precio_rango3_DistribuidorTres,
    precio_rango3_MayoristaTres,
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
    nombre_20, stock_20, precio_20, facturar_cero, descripcion_detalle, idsucursalDestino, sucursalDestino) {

    var subtotaldes = 0;

    //console.log("presentacion ", presentacion);
    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idsucursalDestino[]" value="' + idsucursalDestino + '"><input type="hidden" name="stockinven[]" value="' + stock + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + ' --Sucursal: ' + sucursalDestino + '</td>' +

            '<td><input  class="form-control"  onchange="modificarSubototales()"  type="date"   name="fechavencimiento[]" id="fechavencimiento' + cont + '"  style="width:100px" value="' + fechavencimiento + '"></td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
            <select class="form-control" style="width:100px" name="presentacion[]" id="presentacionselect` + cont + `" 
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
                `+ (parseFloat(stock_unidad) > 0 ? `<option value="` + nombre_01 + `" ` + (presentacion === nombre_01 ? 'selected' : '') + `>${nombre_01}</option>` : ``) + ` 
                `+ (parseFloat(stock_blister) > 0 ? `<option value="` + nombre_02 + `" ` + (presentacion === nombre_02 ? 'selected' : '') + `>${nombre_02}</option>` : ``) + ` 
                `+ (parseFloat(stock_caja) > 0 ? `<option value="` + nombre_03 + `" ` + (presentacion === nombre_03 ? 'selected' : '') + `>${nombre_03}</option>` : ``) + ` 
                `+ (parseFloat(stock_fardo) > 0 ? `<option value="` + nombre_04 + `" ` + (presentacion === nombre_04 ? 'selected' : '') + `>${nombre_04}</option>` : ``) + ` 
                `+ (parseFloat(stock_sacos) > 0 ? `<option value="` + nombre_05 + `" ` + (presentacion === nombre_05 ? 'selected' : '') + `>${nombre_05}</option>` : ``) + ` 
                `+ (parseFloat(stock_paquete) > 0 ? `<option value="` + nombre_06 + `" ` + (presentacion === nombre_06 ? 'selected' : '') + `>${nombre_06}</option>` : ``) + ` 
                `+ (parseFloat(stock_07) > 0 ? `<option value="` + nombre_07 + `" ` + (presentacion === nombre_07 ? 'selected' : '') + `>${nombre_07}</option>` : ``) + ` 
                `+ (parseFloat(stock_08) > 0 ? `<option value="` + nombre_08 + `" ` + (presentacion === nombre_08 ? 'selected' : '') + `>${nombre_08}</option>` : ``) + ` 
                `+ (parseFloat(stock_09) > 0 ? `<option value="` + nombre_09 + `" ` + (presentacion === nombre_09 ? 'selected' : '') + `>${nombre_09}</option>` : ``) + ` 
                `+ (parseFloat(stock_10) > 0 ? `<option value="` + nombre_10 + `" ` + (presentacion === nombre_10 ? 'selected' : '') + `>${nombre_10}</option>` : ``) + ` 
                `+ (parseFloat(stock_11) > 0 ? `<option value="` + nombre_11 + `" ` + (presentacion === nombre_11 ? 'selected' : '') + `>${nombre_11}</option>` : ``) + ` 
                `+ (parseFloat(stock_12) > 0 ? `<option value="` + nombre_12 + `" ` + (presentacion === nombre_12 ? 'selected' : '') + `>${nombre_12}</option>` : ``) + ` 
                `+ (parseFloat(stock_13) > 0 ? `<option value="` + nombre_13 + `" ` + (presentacion === nombre_13 ? 'selected' : '') + `>${nombre_13}</option>` : ``) + ` 
                `+ (parseFloat(stock_14) > 0 ? `<option value="` + nombre_14 + `" ` + (presentacion === nombre_14 ? 'selected' : '') + `>${nombre_14}</option>` : ``) + ` 
                `+ (parseFloat(stock_15) > 0 ? `<option value="` + nombre_15 + `" ` + (presentacion === nombre_15 ? 'selected' : '') + `>${nombre_15}</option>` : ``) + ` 
                `+ (parseFloat(stock_16) > 0 ? `<option value="` + nombre_16 + `" ` + (presentacion === nombre_16 ? 'selected' : '') + `>${nombre_16}</option>` : ``) + ` 
                `+ (parseFloat(stock_17) > 0 ? `<option value="` + nombre_17 + `" ` + (presentacion === nombre_17 ? 'selected' : '') + `>${nombre_17}</option>` : ``) + ` 
                `+ (parseFloat(stock_18) > 0 ? `<option value="` + nombre_18 + `" ` + (presentacion === nombre_18 ? 'selected' : '') + `>${nombre_18}</option>` : ``) + ` 
                `+ (parseFloat(stock_19) > 0 ? `<option value="` + nombre_19 + `" ` + (presentacion === nombre_19 ? 'selected' : '') + `>${nombre_19}</option>` : ``) + ` 
                `+ (parseFloat(stock_20) > 0 ? `<option value="` + nombre_20 + `" ` + (presentacion === nombre_20 ? 'selected' : '') + `>${nombre_20}</option>` : ``) + `  
            </select>
        </td>` +
            '<td><input style="width:100px" class="form-control"  type="text"  name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="' + descripcion_detalle + '"></td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:100px" step="any" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td><input class="form-control"  onchange="modificarSubototales()" type="number" step="any" style="width:50px"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" step="any" name="precio_venta[]" style="width:75px" value="' + precio_venta + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_ventaNocturno[]" style="width:75px"  value="' + precio_ventaNocturno + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Mecanico[]" style="width:75px" value="' + detalle_precio_rango1_Mecanico + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Distribuidor[]" style="width:75px" value="' + detalle_precio_rango1_Distribuidor + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Mayorista[]" style="width:75px" value="' + detalle_precio_rango1_Mayorista + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_MecanicoDos[]" style="width:75px" value="' + detalle_precio_rango2_MecanicoDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_DistribuidorDos[]" style="width:75px" value="' + detalle_precio_rango2_DistribuidorDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_MayoristaDos[]" style="width:75px" value="' + detalle_precio_rango2_MayoristaDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_MecanicoTres[]" style="width:75px" value="' + detalle_precio_rango3_MecanicoTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_DistribuidorTres[]" style="width:75px" value="' + detalle_precio_rango3_DistribuidorTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_MayoristaTres[]" style="width:75px" value="' + detalle_precio_rango3_MayoristaTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_unidad[]" style="width:75px" value="' + detalle_precio_unidad + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_blister[]" style="width:75px" value="' + detalle_precio_blister + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_caja[]" style="width:75px" value="' + detalle_precio_caja + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_fardo[]" style="width:75px" value="' + detalle_precio_fardo + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_sacos[]" style="width:75px" value="' + detalle_precio_sacos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_paquete[]" style="width:75px" value="' + detalle_precio_paquete + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_07[]" style="width:75px" value="' + detalle_precio_07 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_08[]" style="width:75px" value="' + detalle_precio_08 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_09[]" style="width:75px" value="' + detalle_precio_09 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_10[]" style="width:75px" value="' + detalle_precio_10 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_11[]" style="width:75px" value="' + detalle_precio_11 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_12[]" style="width:75px" value="' + detalle_precio_12 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_13[]" style="width:75px" value="' + detalle_precio_13 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_14[]" style="width:75px" value="' + detalle_precio_14 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_15[]" style="width:75px" value="' + detalle_precio_15 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_16[]" style="width:75px" value="' + detalle_precio_16 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_17[]" style="width:75px" value="' + detalle_precio_17 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_18[]" style="width:75px" value="' + detalle_precio_18 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_19[]" style="width:75px" value="' + detalle_precio_19 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_20[]" style="width:75px" value="' + detalle_precio_20 + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
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

function eliminarDetalle2(indice) {
    Swal.fire({
        title: 'ATENCIÓN!',
        text: "No puedes eliminar datos de una Orden de compra, solo puedes ingresar",
        icon: 'info',
    });
}

/////CLIENTE NUEVO

function validarnit() {


    var nit = $("#nit").val();

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
                $("#tipo_documento_cliente").val("NIT");
                $("#tipo_documento_cliente").selectpicker('refresh');

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
                $("#tipo_documento_cliente").val("NIT");
                $("#tipo_documento_cliente").selectpicker('refresh');

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
    $.post("../ajax/venta.php?op=validarCodigo", { codigo_cliente: codigo_cliente }, function (data, status) {
        // console.log(data)
        //console.log(data)
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
                url: '../ajax/consultas.php?op=listartbBusquedaProveedor',
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
    tipo_documento, codigo_cliente) {
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
}

function validarnitTelefono() {
    var telefono_cliente = $("#telefono_cliente").val();
    $.post("../ajax/venta.php?op=validarnitTelefono", { telefono_cliente: telefono_cliente }, function (data, status) {
        // console.log(data)

        data = JSON.parse(data);

        $("#nit").val(data.num_documento);
        $("#nombre_cliente").val(data.nombre);
        $("#direccion_cliente").val(data.direccion);
        $("#correo_cliente").val(data.email);
        $("#tipo_documento_cliente").val(data.tipo_documento);
        $("#tipo_documento_cliente").selectpicker('refresh');

    })
}


////fin de cliente  

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

function calculardiascredito() {

    var numero = document.getElementById('dias_credito');
    //la fecha
    var TuFecha = new Date();

    //dias a sumar
    var dias = parseInt(numero.value);

    //nueva fecha sumada
    TuFecha.setDate(TuFecha.getDate() + dias);
    //formato de salida para la fecha
    var resultado = TuFecha.getDate() + '/' +
        (TuFecha.getMonth() + 1) + '/' + TuFecha.getFullYear();
    $("#fecha_hora_pago_credito").val(resultado);

}



//Función limpiar   
function limpiar() {


    $("#idingreso").val("");
    $("#datos1").val("");
    $("#codigo_cliente").val("");
    $("#nit").val("CF");
    $("#nombre_cliente").val("CONSUMIDOR FINAL");
    $("#telefono_cliente").val("");
    $("#correo_cliente").val("0");
    $("#idcliente").val("1");
    $("#tipo_documento_cliente").val("NIT");
    $("#tipo_documento_cliente").selectpicker('refresh');
    $("#tipo_ingreso_producion").val("Producto");
    $("#tipo_ingreso_producion").selectpicker('refresh');

    $("#tipo_comprobante").val("Poliza");
    $("#tipo_comprobante").selectpicker('refresh');

    $("#serie_comprobante").val("0");
    $("#num_comprobante").val("0");
    $("#impuesto").val("0");
    $("#total_compra").val("0");
    $("#total_comprades").val("0");

    $("#forma_pago").val("Efectivo");
    $("#forma_pago").selectpicker('refresh');

    $("#dias_credito").val("0");
    $("#direccion_entrega_orden_compra").val("0");
    $("#observacion_orden_compra").val("0");



    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);
    $('#fecha_hora_pago_credito').val(today);
    $('#fecha_entrega_orden_compra').val(today);



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
        $("#btnCancelar").show();
        detalles = 0;
        $("#btnAgregarArt").show();
    }
    else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        //$("#btnGuardar").show();
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
                'pdf'
            ],
            "ajax":
            {
                url: '../ajax/ingreso.php?op=listar',
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

function listar_preciosxproveedor() {
    tabla = $('#tblarticulos_xproveedor').dataTable(
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
                url: '../ajax/ingreso.php?op=listar_preciosxproveedor',
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
    var idsucursalOrigen = $("#idsucursalOrigen").val();

    if (idsucursalOrigen == "" || idsucursalOrigen == null) {
        alert("Por favor, seleccione una sucursal de origen");
        return false;
    } else {
        $("#myModal").modal('show');
    }

    tabla = $('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/ingreso.php?op=listarArticulos',
                type: "get",
                data: { idsucursalOrigen: idsucursalOrigen },
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
            stockinven: [],
            fechavencimiento: [],
            cantidadpresentacion: [],
            cantidad: [],
            totalcantidadpresentacion: [],
            presentacion: [],
            descripcion_detalle: [],
            precio_compra: [],
            descuento_porcentaje: [],
            precio_venta: [],
            precio_ventaNocturno: [],
            precio_rango1_Mecanico: [],
            precio_rango1_Distribuidor: [],
            precio_rango1_Mayorista: [],
            precio_rango2_MecanicoDos: [],
            precio_rango2_DistribuidorDos: [],
            precio_rango2_MayoristaDos: [],
            precio_rango3_MecanicoTres: [],
            precio_rango3_DistribuidorTres: [],
            precio_rango3_MayoristaTres: [],
            precio_unidad: [],
            precio_blister: [],
            precio_caja: [],
            precio_fardo: [],
            precio_sacos: [],
            precio_paquete: [],
            precio_07: [],
            precio_08: [],
            precio_09: [],
            precio_10: [],
            precio_11: [],
            precio_12: [],
            precio_13: [],
            precio_14: [],
            precio_15: [],
            precio_16: [],
            precio_17: [],
            precio_18: [],
            precio_19: [],
            precio_20: [],
            idsucursalDestino: []
        }
    };

    $('#detalles .filas').each(function () {
        const idarticulo = $(this).find('input[name="idarticulo[]"]').val();
        const stockinven = $(this).find('input[name="stockinven[]"]').val();
        const fechavencimiento = $(this).find('input[name="fechavencimiento[]"]').val();
        const cantidadpresentacion = $(this).find('input[name="cantidadpresentacion[]"]').val();
        const cantidad = $(this).find('input[name="cantidad[]"]').val();
        const totalcantidadpresentacion = $(this).find('input[name="totalcantidadpresentacion[]"]').val();
        const presentacion = $(this).find('select[name="presentacion[]"]').val();
        const descripcion_detalle = $(this).find('input[name="descripcion_detalle[]"]').val();
        const precio_compra = $(this).find('input[name="precio_compra[]"]').val();
        const descuento_porcentaje = $(this).find('input[name="descuento_porcentaje[]"]').val();
        const precio_venta = $(this).find('input[name="precio_venta[]"]').val();
        const precio_ventaNocturno = $(this).find('input[name="precio_ventaNocturno[]"]').val();
        const precio_rango1_Mecanico = $(this).find('input[name="precio_rango1_Mecanico[]"]').val();
        const precio_rango1_Distribuidor = $(this).find('input[name="precio_rango1_Distribuidor[]"]').val();
        const precio_rango1_Mayorista = $(this).find('input[name="precio_rango1_Mayorista[]"]').val();
        const precio_rango2_MecanicoDos = $(this).find('input[name="precio_rango2_MecanicoDos[]"]').val();
        const precio_rango2_DistribuidorDos = $(this).find('input[name="precio_rango2_DistribuidorDos[]"]').val();
        const precio_rango2_MayoristaDos = $(this).find('input[name="precio_rango2_MayoristaDos[]"]').val();
        const precio_rango3_MecanicoTres = $(this).find('input[name="precio_rango3_MecanicoTres[]"]').val();
        const precio_rango3_DistribuidorTres = $(this).find('input[name="precio_rango3_DistribuidorTres[]"]').val();
        const precio_rango3_MayoristaTres = $(this).find('input[name="precio_rango3_MayoristaTres[]"]').val();
        const precio_unidad = $(this).find('input[name="precio_unidad[]"]').val();
        const precio_blister = $(this).find('input[name="precio_blister[]"]').val();
        const precio_caja = $(this).find('input[name="precio_caja[]"]').val();
        const precio_fardo = $(this).find('input[name="precio_fardo[]"]').val();
        const precio_sacos = $(this).find('input[name="precio_sacos[]"]').val();
        const precio_paquete = $(this).find('input[name="precio_paquete[]"]').val();
        const precio_07 = $(this).find('input[name="precio_07[]"]').val();
        const precio_08 = $(this).find('input[name="precio_08[]"]').val();
        const precio_09 = $(this).find('input[name="precio_09[]"]').val();
        const precio_10 = $(this).find('input[name="precio_10[]"]').val();
        const precio_11 = $(this).find('input[name="precio_11[]"]').val();
        const precio_12 = $(this).find('input[name="precio_12[]"]').val();
        const precio_13 = $(this).find('input[name="precio_13[]"]').val();
        const precio_14 = $(this).find('input[name="precio_14[]"]').val();
        const precio_15 = $(this).find('input[name="precio_15[]"]').val();
        const precio_16 = $(this).find('input[name="precio_16[]"]').val();
        const precio_17 = $(this).find('input[name="precio_17[]"]').val();
        const precio_18 = $(this).find('input[name="precio_18[]"]').val();
        const precio_19 = $(this).find('input[name="precio_19[]"]').val();
        const precio_20 = $(this).find('input[name="precio_20[]"]').val();
        const idsucursalDestino = $(this).find('input[name="idsucursalDestino[]"]').val();



        datos.articulos.idarticulo.push(idarticulo);
        datos.articulos.stockinven.push(stockinven);
        datos.articulos.fechavencimiento.push(fechavencimiento);
        datos.articulos.cantidadpresentacion.push(cantidadpresentacion);
        datos.articulos.cantidad.push(cantidad);
        datos.articulos.totalcantidadpresentacion.push(totalcantidadpresentacion);
        datos.articulos.presentacion.push(presentacion);
        datos.articulos.descripcion_detalle.push(descripcion_detalle);
        datos.articulos.precio_compra.push(precio_compra);
        datos.articulos.descuento_porcentaje.push(descuento_porcentaje);
        datos.articulos.precio_venta.push(precio_venta);
        datos.articulos.precio_ventaNocturno.push(precio_ventaNocturno);
        datos.articulos.precio_rango1_Mecanico.push(precio_rango1_Mecanico);
        datos.articulos.precio_rango1_Distribuidor.push(precio_rango1_Distribuidor);
        datos.articulos.precio_rango1_Mayorista.push(precio_rango1_Mayorista);
        datos.articulos.precio_rango2_MecanicoDos.push(precio_rango2_MecanicoDos);
        datos.articulos.precio_rango2_DistribuidorDos.push(precio_rango2_DistribuidorDos);
        datos.articulos.precio_rango2_MayoristaDos.push(precio_rango2_MayoristaDos);
        datos.articulos.precio_rango3_MecanicoTres.push(precio_rango3_MecanicoTres);
        datos.articulos.precio_rango3_DistribuidorTres.push(precio_rango3_DistribuidorTres);
        datos.articulos.precio_rango3_MayoristaTres.push(precio_rango3_MayoristaTres);
        datos.articulos.precio_unidad.push(precio_unidad);
        datos.articulos.precio_blister.push(precio_blister);
        datos.articulos.precio_caja.push(precio_caja);
        datos.articulos.precio_fardo.push(precio_fardo);
        datos.articulos.precio_sacos.push(precio_sacos);
        datos.articulos.precio_paquete.push(precio_paquete);
        datos.articulos.precio_07.push(precio_07);
        datos.articulos.precio_08.push(precio_08);
        datos.articulos.precio_09.push(precio_09);
        datos.articulos.precio_10.push(precio_10);
        datos.articulos.precio_11.push(precio_11);
        datos.articulos.precio_12.push(precio_12);
        datos.articulos.precio_13.push(precio_13);
        datos.articulos.precio_14.push(precio_14);
        datos.articulos.precio_15.push(precio_15);
        datos.articulos.precio_16.push(precio_16);
        datos.articulos.precio_17.push(precio_17);
        datos.articulos.precio_18.push(precio_18);
        datos.articulos.precio_19.push(precio_19);
        datos.articulos.precio_20.push(precio_20);
        datos.articulos.idsucursalDestino.push(idsucursalDestino);

    });

    const datosJSON = JSON.stringify(datos);

    localStorage.setItem('datosArticulosC', datosJSON);
    //console.log(datosJSON);
}


function guardaryeditar(e) {
    if (detalles > 0) {
    }
    else {
        alert("No hay detalles para guardar");
        return;
    }

    agruparDatos();
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    let datosArticulosC = JSON.parse(localStorage.getItem('datosArticulosC'));
    formData.append("datosArticulosC", JSON.stringify(datosArticulosC));
    load();
    $.ajax({
        url: "../ajax/ingreso.php?op=guardaryeditar",
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
}



function mostrar(idingreso) {
    $.post("../ajax/sucursal.php?op=obtenerClaveIngresos", function (data, status) {
        data = JSON.parse(data);
        let clave_ingresos_ajax = data.clave_ingresos;

        Swal.fire({
            title: 'Ingresa la contraseña de Autorización',
            input: 'password', // Tipo de campo 'password' para ocultar la entrada
            inputAttributes: {
                autocapitalize: 'off',
                autocomplete: 'off', // Desactiva el autocompletado
                placeholder: 'Contraseña',
                maxlength: 100,
                required: true
            },
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                if (password !== clave_ingresos_ajax) { // Validar contraseña
                    Swal.showValidationMessage('Contraseña no válida');
                    return false;
                }
                return password;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Continuar con la petición AJAX
                $.post("../ajax/ingreso.php?op=mostrar", { idingreso: idingreso }, function (response, status) {
                    //console.log("Respuesta del servidor:", response); // Verifica qué está devolviendo el servidor

                    try {
                        // Parsear la respuesta JSON
                        let data = JSON.parse(response);

                        // Verificar si hay errores en la respuesta
                        if (data.status === false) {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'No se pudo procesar la solicitud.',
                                icon: 'error',
                                timer: 5000,
                                timerProgressBar: true
                            });
                            return; // Detener la ejecución si hay un error
                        }

                        // Continuar con el llenado de datos (si la respuesta no tiene un campo `status`, trabaja directamente con `data`)
                        mostrarform(true);

                        $("#idingreso").val(data.idingreso);
                        $("#codigo_cliente").val(data.codigo_cliente);
                        $("#nit").val(data.nit);
                        $("#nombre_cliente").val(data.nombre_cliente);
                        $("#telefono_cliente").val(data.telefono_cliente);
                        $("#direccion_cliente").val(data.direccion_cliente);
                        $("#correo_cliente").val(data.correo_cliente);
                        $("#idcliente").val(data.idcliente);

                        $("#tipo_documento_cliente").val(data.tipo_documento_cliente);
                        $("#tipo_documento_cliente").selectpicker("refresh");

                        $("#tipo_ingreso_producion").val(data.tipo_ingreso || ''); // Manejar posibles valores faltantes
                        $("#tipo_ingreso_producion").selectpicker("refresh");

                        $("#fecha_hora").val(data.fecha);

                        $("#tipo_comprobante").val(data.tipo_comprobante);
                        $("#tipo_comprobante").selectpicker("refresh");

                        $("#serie_comprobante").val(data.serie_comprobante);
                        $("#num_comprobante").val(data.num_comprobante);

                        $("#impuesto").val(data.impuesto);

                        $("#total_compra").val(data.total_compra);
                        $("#total_comprades").val(data.total_comprades);

                        $("#total_compra_r").val(data.total_compra);
                        $("#total_comprades_r").val(data.total_comprades);

                        $("#forma_pago").val(data.forma_pago);
                        $("#forma_pago").selectpicker("refresh");

                        $("#dias_credito").val(data.dias_credito);
                        $("#fecha_hora_pago_credito").val(data.fechahorapagocredito || ''); // Manejar valores nulos
                        $("#direccion_entrega_orden_compra").val(data.direccion_entrega_orden_compra);

                        $("#fecha_entrega_orden_compra").val(data.fechaentregaordencompra || ''); // Manejar valores nulos
                        $("#observacion_orden_compra").val(data.observacion_orden_compra);

                        // Llamar a obtenerdetalleingreso para cargar detalles adicionales
                        obtenerdetalleingreso(idingreso);
                    } catch (e) {
                        console.error("Error al procesar los datos:", e);

                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al procesar la respuesta del servidor.',
                            icon: 'error'
                        });
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Manejar errores de la petición AJAX
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo completar la solicitud. Por favor, inténtalo de nuevo.',
                        icon: 'error'
                    });
                });
            }
        });
    });
}



// Función para anular registros
function anular(idingreso) {
    bootbox.confirm("¿Está seguro de anular el ingreso?", function (result) {
        if (result) {
            load(); // Mostrar un indicador de carga (si está implementado)
            $.post("../ajax/ingreso.php?op=anular", { idingreso: idingreso }, function (response) {
                try {
                    // Parsear la respuesta como JSON
                    const data = JSON.parse(response);

                    // Mostrar un mensaje según el estado
                    if (data.status) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                Swal.close();
                                // Actualizar la tabla o recargar la página si es necesario
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta del servidor:", error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al procesar la solicitud.',
                        icon: 'error'
                    });
                }
            });
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
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto() {
    var tipo_comprobante = $("#tipo_comprobante option:selected").text();
    if (tipo_comprobante == 'Factura') {
        $("#impuesto").val(impuesto);
    }
    else {
        $("#impuesto").val("0");
    }
}





function agregarDetalleCanta(idarticulo, articulo, descripcion, precio_venta, precio_compra, stock, precio_ventaNocturno,
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
    idsucursal, nom_sucursal,
    cantidad) {
    //for (var i = 0; i < 50; i++) {
    if (cantidad == 0 || cantidad == null) {
        var cantidad = 1;
    } else {
        var cantidad = cantidad;
    }
    var subtotaldes = 0;
    // Obtén la fecha actual en formato YYYY-MM-DD
    var today = new Date();
    var fechaActual = today.toISOString().split('T')[0];

    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idsucursalDestino[]" value="' + idsucursal + '"><input type="hidden" name="stockinven[]" value="' + stock + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + ' --Sucursal:  ' + nom_sucursal + '</td>' +
            '<td><input  class="form-control"  onchange="modificarSubototales()"  type="date"   name="fechavencimiento[]" id="fechavencimiento' + cont + '"  style="width:100px" value="' + fechaActual + '"></td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input  class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '" style="width:100px"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                    <select class="form-control" style="width:100px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
            '<td><input style="width:100px" class="form-control"  type="text"  name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:100px" step="any" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td><input class="form-control"  onchange="modificarSubototales()" type="number" step="any" style="width:50px"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" step="any" name="precio_venta[]" style="width:75px" value="' + precio_venta + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_ventaNocturno[]" style="width:75px"  value="' + precio_ventaNocturno + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Mecanico[]" style="width:75px" value="' + precio_rango1_Mecanico + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Distribuidor[]" style="width:75px" value="' + precio_rango1_Distribuidor + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Mayorista[]" style="width:75px" value="' + precio_rango1_Mayorista + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_MecanicoDos[]" style="width:75px" value="' + precio_rango2_MecanicoDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_DistribuidorDos[]" style="width:75px" value="' + precio_rango2_DistribuidorDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_MayoristaDos[]" style="width:75px" value="' + precio_rango2_MayoristaDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_MecanicoTres[]" style="width:75px" value="' + precio_rango3_MecanicoTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_DistribuidorTres[]" style="width:75px" value="' + precio_rango3_DistribuidorTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_MayoristaTres[]" style="width:75px" value="' + precio_rango3_MayoristaTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_unidad[]" style="width:75px" value="' + precio_unidad + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_blister[]" style="width:75px" value="' + precio_blister + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_caja[]" style="width:75px" value="' + precio_caja + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_fardo[]" style="width:75px" value="' + precio_fardo + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_sacos[]" style="width:75px" value="' + precio_sacos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_paquete[]" style="width:75px" value="' + precio_paquete + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_07[]" style="width:75px" value="' + precio_07 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_08[]" style="width:75px" value="' + precio_08 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_09[]" style="width:75px" value="' + precio_09 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_10[]" style="width:75px" value="' + precio_10 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_11[]" style="width:75px" value="' + precio_11 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_12[]" style="width:75px" value="' + precio_12 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_13[]" style="width:75px" value="' + precio_13 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_14[]" style="width:75px" value="' + precio_14 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_15[]" style="width:75px" value="' + precio_15 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_16[]" style="width:75px" value="' + precio_16 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_17[]" style="width:75px" value="' + precio_17 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_18[]" style="width:75px" value="' + precio_18 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_19[]" style="width:75px" value="' + precio_19 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_20[]" style="width:75px" value="' + precio_20 + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
    modificarSubototales();

}



function agregarDetalle(idarticulo, articulo, precio_venta, precio_compra, stock,
    precio_ventaNocturno,
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
    nombre_20, stock_20, precio_20, idsucursal, nom_sucursal) {

    resprecio_20 = parseFloat(precio_20);
    //for (var i = 0; i < 50; i++) {
    var cantidad = 1;
    var subtotaldes = 0;
    // Obtén la fecha actual en formato YYYY-MM-DD
    var today = new Date();
    var fechaActual = today.toISOString().split('T')[0];

    var cantidadpresentacion = 1;
    var totalcantidadpresentacion = 1;
    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idsucursalDestino[]" value="' + idsucursal + '"><input type="hidden" name="stockinven[]" value="' + stock + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + ' --Sucursal: ' + nom_sucursal + '</td>' +
            '<td><input  class="form-control"  onchange="modificarSubototales()"  type="date"   name="fechavencimiento[]" id="fechavencimiento' + cont + '"  style="width:100px" value="' + fechaActual + '"></td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input  class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '" style="width:100px"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
                <select class="form-control" style="width:100px" name="presentacion[]" id="presentacionselect`+ cont + `" 
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
            '<td><input style="width:100px" class="form-control"  type="text"  name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="."></td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:100px" step="any" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td><input class="form-control"  onchange="modificarSubototales()" type="number" step="any" style="width:50px"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" step="any" name="precio_venta[]" style="width:75px" value="' + precio_venta + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_ventaNocturno[]" style="width:75px"  value="' + precio_ventaNocturno + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Mecanico[]" style="width:75px" value="' + precio_rango1_Mecanico + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Distribuidor[]" style="width:75px" value="' + precio_rango1_Distribuidor + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Mayorista[]" style="width:75px" value="' + precio_rango1_Mayorista + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_MecanicoDos[]" style="width:75px" value="' + precio_rango2_MecanicoDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_DistribuidorDos[]" style="width:75px" value="' + precio_rango2_DistribuidorDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_MayoristaDos[]" style="width:75px" value="' + precio_rango2_MayoristaDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_MecanicoTres[]" style="width:75px" value="' + precio_rango3_MecanicoTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_DistribuidorTres[]" style="width:75px" value="' + precio_rango3_DistribuidorTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_MayoristaTres[]" style="width:75px" value="' + precio_rango3_MayoristaTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_unidad[]" style="width:75px" value="' + precio_unidad + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_blister[]" style="width:75px" value="' + precio_blister + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_caja[]" style="width:75px" value="' + precio_caja + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_fardo[]" style="width:75px" value="' + precio_fardo + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_sacos[]" style="width:75px" value="' + precio_sacos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_paquete[]" style="width:75px" value="' + precio_paquete + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_07[]" style="width:75px" value="' + precio_07 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_08[]" style="width:75px" value="' + precio_08 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_09[]" style="width:75px" value="' + precio_09 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_10[]" style="width:75px" value="' + precio_10 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_11[]" style="width:75px" value="' + precio_11 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_12[]" style="width:75px" value="' + precio_12 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_13[]" style="width:75px" value="' + precio_13 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_14[]" style="width:75px" value="' + precio_14 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_15[]" style="width:75px" value="' + precio_15 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_16[]" style="width:75px" value="' + precio_16 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_17[]" style="width:75px" value="' + precio_17 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_18[]" style="width:75px" value="' + precio_18 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_19[]" style="width:75px" value="' + precio_19 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_20[]" style="width:75px" value="' + resprecio_20 + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        modificarSubototales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
    //}

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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_sacos);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_paquete);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_07);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_08);
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

        $("#precio_ventaSistema" + id).val(precioventaunidad);
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
    else {

        $("#cantidadpresentacion" + id).val(1);
        $("#precio_venta" + id).val(precio_venta);
        $("#q_ref" + id).val(precio_venta);
        $("#presen" + id).val(nombre_01);

        $("#precio_ventaSistema" + id).val(precioventaunidad);
        $("#precio_ventaSistema2" + id).val(precio_venta);

    }
    modificarSubototales();
}
function obtenerdetalleingreso(idingreso) {
    $.post("../ajax/ingreso.php?op=detalleingreso", { idingreso: idingreso }, function (data) {
        //console.log(data);
        data = JSON.parse(data);
        Swal.close()
        $.each(data, function (i, item) {
            agregarDetalle2(item.idarticulo, item.articulo, item.precio_venta, item.precio_compra, item.stock, item.cantidad,
                item.descuento_porcentaje,
                item.precio_ventaNocturno,
                item.detalle_precio_rango1_Mecanico, item.detalle_precio_rango1_Distribuidor, item.detalle_precio_rango1_Mayorista,
                item.detalle_precio_rango2_MecanicoDos, item.detalle_precio_rango2_DistribuidorDos, item.detalle_precio_rango2_MayoristaDos,
                item.detalle_precio_rango3_MecanicoTres, item.detalle_precio_rango3_DistribuidorTres, item.detalle_precio_rango3_MayoristaTres,
                item.detalle_precio_unidad,
                item.detalle_precio_blister,
                item.detalle_precio_caja,
                item.detalle_precio_fardo,
                item.detalle_precio_sacos,
                item.detalle_precio_paquete,
                item.detalle_precio_07,
                item.detalle_precio_08,
                item.detalle_precio_09,
                item.detalle_precio_10,
                item.detalle_precio_11,
                item.detalle_precio_12,
                item.detalle_precio_13,
                item.detalle_precio_14,
                item.detalle_precio_15,
                item.detalle_precio_16,
                item.detalle_precio_17,
                item.detalle_precio_18,
                item.detalle_precio_19,
                item.detalle_precio_20,
                item.cantidadpresentacion, item.totalcantidadpresentacion, item.presentacion, item.fechavencimiento,
                item.precio_rango1_Mecanico,
                item.precio_rango1_Distribuidor,
                item.precio_rango1_Mayorista,
                item.precio_rango2_MecanicoDos,
                item.precio_rango2_DistribuidorDos,
                item.precio_rango2_MayoristaDos,
                item.precio_rango3_MecanicoTres,
                item.precio_rango3_DistribuidorTres,
                item.precio_rango3_MayoristaTres,
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
                item.nombre_20, item.stock_20, item.precio_20, item.descripcion_detalle, item.idsucursalDestino, item.sucursalDestino
            );
        });
    })
}

function agregarDetalle2(idarticulo, articulo, precio_venta, precio_compra, stock, cantidad,
    descuento_porcentaje,
    precio_ventaNocturno,
    detalle_precio_rango1_Mecanico,
    detalle_precio_rango1_Distribuidor,
    detalle_precio_rango1_Mayorista,
    detalle_precio_rango2_MecanicoDos,
    detalle_precio_rango2_DistribuidorDos,
    detalle_precio_rango2_MayoristaDos,
    detalle_precio_rango3_MecanicoTres,
    detalle_precio_rango3_DistribuidorTres,
    detalle_precio_rango3_MayoristaTres,
    detalle_precio_unidad,
    detalle_precio_blister,
    detalle_precio_caja,
    detalle_precio_fardo,
    detalle_precio_sacos,
    detalle_precio_paquete,
    detalle_precio_07,
    detalle_precio_08,
    detalle_precio_09,
    detalle_precio_11,
    detalle_precio_10,
    detalle_precio_12,
    detalle_precio_13,
    detalle_precio_14,
    detalle_precio_15,
    detalle_precio_16,
    detalle_precio_17,
    detalle_precio_18,
    detalle_precio_19,
    detalle_precio_20,
    cantidadpresentacion, totalcantidadpresentacion, presentacion, fechavencimiento,
    precio_rango1_Mecanico,
    precio_rango1_Distribuidor,
    precio_rango1_Mayorista,
    precio_rango2_MecanicoDos,
    precio_rango2_DistribuidorDos,
    precio_rango2_MayoristaDos,
    precio_rango3_MecanicoTres,
    precio_rango3_DistribuidorTres,
    precio_rango3_MayoristaTres,
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
    nombre_20, stock_20, precio_20, descripcion_detalle, idsucursalDestino, sucursalDestino) {

    var subtotaldes = 0;

    //console.log("presentacion ", presentacion);
    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idsucursalDestino[]" value="' + idsucursalDestino + '"><input type="hidden" name="stockinven[]" value="' + stock + '"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + ' --Sucursal: ' + sucursalDestino + '</td>' +

            '<td><input  class="form-control"  onchange="modificarSubototales()"  type="date"   name="fechavencimiento[]" id="fechavencimiento' + cont + '"  style="width:100px" value="' + fechavencimiento + '"></td>' +
            '<td><input style="width:60px" type="hidden" id="cantidadpresentacion' + cont + '" name="cantidadpresentacion[]" value="' + cantidadpresentacion + '" onchange="modificarSubototales()"><input style="width:100px" class="form-control"  onchange="modificarSubototales()"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"><input style="width:60px"  type="hidden" id="totalcantidadpresentacion' + cont + '" name="totalcantidadpresentacion[]" value="' + totalcantidadpresentacion + '" onchange="modificarSubototales()"></td>' +
            `<td>
            <select class="form-control" style="width:100px" name="presentacion[]" id="presentacionselect` + cont + `" 
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
                `+ (parseFloat(stock_unidad) > 0 ? `<option value="` + nombre_01 + `" ` + (presentacion === nombre_01 ? 'selected' : '') + `>${nombre_01}</option>` : ``) + ` 
                `+ (parseFloat(stock_blister) > 0 ? `<option value="` + nombre_02 + `" ` + (presentacion === nombre_02 ? 'selected' : '') + `>${nombre_02}</option>` : ``) + ` 
                `+ (parseFloat(stock_caja) > 0 ? `<option value="` + nombre_03 + `" ` + (presentacion === nombre_03 ? 'selected' : '') + `>${nombre_03}</option>` : ``) + ` 
                `+ (parseFloat(stock_fardo) > 0 ? `<option value="` + nombre_04 + `" ` + (presentacion === nombre_04 ? 'selected' : '') + `>${nombre_04}</option>` : ``) + ` 
                `+ (parseFloat(stock_sacos) > 0 ? `<option value="` + nombre_05 + `" ` + (presentacion === nombre_05 ? 'selected' : '') + `>${nombre_05}</option>` : ``) + ` 
                `+ (parseFloat(stock_paquete) > 0 ? `<option value="` + nombre_06 + `" ` + (presentacion === nombre_06 ? 'selected' : '') + `>${nombre_06}</option>` : ``) + ` 
                `+ (parseFloat(stock_07) > 0 ? `<option value="` + nombre_07 + `" ` + (presentacion === nombre_07 ? 'selected' : '') + `>${nombre_07}</option>` : ``) + ` 
                `+ (parseFloat(stock_08) > 0 ? `<option value="` + nombre_08 + `" ` + (presentacion === nombre_08 ? 'selected' : '') + `>${nombre_08}</option>` : ``) + ` 
                `+ (parseFloat(stock_09) > 0 ? `<option value="` + nombre_09 + `" ` + (presentacion === nombre_09 ? 'selected' : '') + `>${nombre_09}</option>` : ``) + ` 
                `+ (parseFloat(stock_10) > 0 ? `<option value="` + nombre_10 + `" ` + (presentacion === nombre_10 ? 'selected' : '') + `>${nombre_10}</option>` : ``) + ` 
                `+ (parseFloat(stock_11) > 0 ? `<option value="` + nombre_11 + `" ` + (presentacion === nombre_11 ? 'selected' : '') + `>${nombre_11}</option>` : ``) + ` 
                `+ (parseFloat(stock_12) > 0 ? `<option value="` + nombre_12 + `" ` + (presentacion === nombre_12 ? 'selected' : '') + `>${nombre_12}</option>` : ``) + ` 
                `+ (parseFloat(stock_13) > 0 ? `<option value="` + nombre_13 + `" ` + (presentacion === nombre_13 ? 'selected' : '') + `>${nombre_13}</option>` : ``) + ` 
                `+ (parseFloat(stock_14) > 0 ? `<option value="` + nombre_14 + `" ` + (presentacion === nombre_14 ? 'selected' : '') + `>${nombre_14}</option>` : ``) + ` 
                `+ (parseFloat(stock_15) > 0 ? `<option value="` + nombre_15 + `" ` + (presentacion === nombre_15 ? 'selected' : '') + `>${nombre_15}</option>` : ``) + ` 
                `+ (parseFloat(stock_16) > 0 ? `<option value="` + nombre_16 + `" ` + (presentacion === nombre_16 ? 'selected' : '') + `>${nombre_16}</option>` : ``) + ` 
                `+ (parseFloat(stock_17) > 0 ? `<option value="` + nombre_17 + `" ` + (presentacion === nombre_17 ? 'selected' : '') + `>${nombre_17}</option>` : ``) + ` 
                `+ (parseFloat(stock_18) > 0 ? `<option value="` + nombre_18 + `" ` + (presentacion === nombre_18 ? 'selected' : '') + `>${nombre_18}</option>` : ``) + ` 
                `+ (parseFloat(stock_19) > 0 ? `<option value="` + nombre_19 + `" ` + (presentacion === nombre_19 ? 'selected' : '') + `>${nombre_19}</option>` : ``) + ` 
                `+ (parseFloat(stock_20) > 0 ? `<option value="` + nombre_20 + `" ` + (presentacion === nombre_20 ? 'selected' : '') + `>${nombre_20}</option>` : ``) + `  
            </select>
        </td>` +
            '<td><input style="width:100px" class="form-control"  type="text"  name="descripcion_detalle[]" id="descripcion_detalle' + cont + '" value="' + descripcion_detalle + '"></td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:100px" step="any" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '"></td>' +
            '<td><input class="form-control"  onchange="modificarSubototales()" type="number" step="any" style="width:50px"  name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="0"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" step="any" name="precio_venta[]" style="width:75px" value="' + precio_venta + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_ventaNocturno[]" style="width:75px"  value="' + precio_ventaNocturno + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Mecanico[]" style="width:75px" value="' + detalle_precio_rango1_Mecanico + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Distribuidor[]" style="width:75px" value="' + detalle_precio_rango1_Distribuidor + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango1_Mayorista[]" style="width:75px" value="' + detalle_precio_rango1_Mayorista + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_MecanicoDos[]" style="width:75px" value="' + detalle_precio_rango2_MecanicoDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_DistribuidorDos[]" style="width:75px" value="' + detalle_precio_rango2_DistribuidorDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango2_MayoristaDos[]" style="width:75px" value="' + detalle_precio_rango2_MayoristaDos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_MecanicoTres[]" style="width:75px" value="' + detalle_precio_rango3_MecanicoTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_DistribuidorTres[]" style="width:75px" value="' + detalle_precio_rango3_DistribuidorTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_rango3_MayoristaTres[]" style="width:75px" value="' + detalle_precio_rango3_MayoristaTres + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_unidad[]" style="width:75px" value="' + detalle_precio_unidad + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_blister[]" style="width:75px" value="' + detalle_precio_blister + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_caja[]" style="width:75px" value="' + detalle_precio_caja + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_fardo[]" style="width:75px" value="' + detalle_precio_fardo + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_sacos[]" style="width:75px" value="' + detalle_precio_sacos + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_paquete[]" style="width:75px" value="' + detalle_precio_paquete + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_07[]" style="width:75px" value="' + detalle_precio_07 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_08[]" style="width:75px" value="' + detalle_precio_08 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_09[]" style="width:75px" value="' + detalle_precio_09 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_10[]" style="width:75px" value="' + detalle_precio_10 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_11[]" style="width:75px" value="' + detalle_precio_11 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_12[]" style="width:75px" value="' + detalle_precio_12 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_13[]" style="width:75px" value="' + detalle_precio_13 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_14[]" style="width:75px" value="' + detalle_precio_14 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_15[]" style="width:75px" value="' + detalle_precio_15 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_16[]" style="width:75px" value="' + detalle_precio_16 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_17[]" style="width:75px" value="' + detalle_precio_17 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_18[]" style="width:75px" value="' + detalle_precio_18 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_19[]" style="width:75px" value="' + detalle_precio_19 + '"></td>' +
            '<td><input class="form-control"  type="number" step="any" name="precio_20[]" style="width:75px" value="' + detalle_precio_20 + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><span name="subtotaldes" id="subtotaldes' + cont + '">' + subtotaldes + '</span></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);

    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
    modificarSubototales();
}


function modificarSubototales() {
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_compra[]");
    var p_venta = document.getElementsByName("precio_venta[]");
    var desc = document.getElementsByName("descuento_porcentaje[]");
    var sub = document.getElementsByName("subtotal");
    var subdes = document.getElementsByName("subtotaldes");
    var tprese = (document.getElementsByName("totalcantidadpresentacion[]"));
    var cantpre = (document.getElementsByName("cantidadpresentacion[]"));
    var p_unidad = (document.getElementsByName("precio_unidad[]"));

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpD = desc[i];
        var inpS = sub[i];
        var inpSdes = subdes[i];
        var inpTpres = tprese[i];
        var inpCpre = cantpre[i];
        var inpCpre_uni = p_unidad[i];
        var inpCpre_venta = p_venta[i];

        inpTpres.value = parseFloat(inpC.value * inpCpre.value).toFixed(3);
        document.getElementsByName("totalcantidadpresentacion[]")[i].innerHTML = parseFloat(inpTpres.value).toFixed(2);

        inpS.value = ((inpC.value * inpCpre.value) * (inpP.value - ((inpP.value * inpD.value) / 100)));
        document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(2);

        inpCpre_uni.value = inpCpre_venta.value;
        document.getElementsByName("precio_unidad[]")[i].innerHTML = parseFloat(inpCpre_uni.value).toFixed(2);


        inpSdes.value = ((inpP.value * inpD.value) / 100) * inpC.value;
        document.getElementsByName("subtotaldes")[i].innerHTML = parseFloat(inpSdes.value).toFixed(2);

    }
    calcularTotales();
    calcularTotalesdes();

}
function calcularTotales() {
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        total += parseFloat(document.getElementsByName("subtotal")[i].value) || 0; // Asegúrate de manejar valores no numéricos
    }
    total = parseFloat(total.toFixed(2)); // Redondea a 2 decimales
    $("#total").html("Q/. " + total);
    $("#total_compra").val(total);
    $("#total_compra_r").val(total);
    evaluar();
}


function calcularTotalesdes() {
    var chks = document.getElementsByName('subtotaldes');
    var total = 0.0;

    for (var i = 0; i < chks.length; i++) {
        var valor = parseFloat(chks[i].value) || 0; // Maneja valores no numéricos
        total += valor;
    }
    total = parseFloat(total.toFixed(2)); // Redondea a 2 decimales
    $("#totaldes").html("Q/. " + total);
    $("#total_comprades").val(total);
    $("#total_comprades_r").val(total);
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
    evaluar();
}

$.fn.delayPasteKeyUp = function (fn, ms) {
    var timer = 0;
    $(this).on("propertychange input", function () {
        clearTimeout(timer);
        timer = setTimeout(fn, ms);
    });
};


$(function () {
    $("#txtbusquedaartcodebar").delayPasteKeyUp(function () {
        var idsucursalOrigen = $("#idsucursalOrigen").val();

        if (idsucursalOrigen == "" || idsucursalOrigen == null) {
            alert("Por favor, seleccione una sucursal de origen");
            return false;
        }

        var valoractual = $("#txtbusquedaartcodebar").val();

        if (valoractual != "") {
            $.get("../ajax/venta.php?op=buscararticulocodebarComprasxSucursal", { codigo: valoractual, idsucursal: idsucursalOrigen }, function (res) {

                var arrayproduc = res.split("@");

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
                        arrayproduc[74], arrayproduc[75], arrayproduc[76]);
                }
                $("#txtbusquedaartcodebar").val("");
                $("#txtbusquedaartcodebar").focus()

            })
        }

    }, 200)
    // listarArticulos();
})




init();