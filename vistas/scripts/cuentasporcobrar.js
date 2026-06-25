var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    $('#MenuCuentasXcobrar').addClass("treeview active");
    $('#ACCOBRAR').addClass("active");


    //Cargamos los items al select proveedor
    $.post("../ajax/pagos_empleados.php?op=selectBanco", function (r) {
        $("#idcuenta").html(r);
        $('#idcuenta').selectpicker('refresh');

    });

    $("#btnGuardarFacxLotes").click(function (e) {
        $('#myModalVentasxlote').modal('hide');
        guardaryeditarxlote(e);
    });
}

function guardaryeditarxlote(e) {

    /*  var idventa = document.getElementsByName("idventa_lote[]");
      var totalVenta = document.getElementsByName("total_venta_lote[]");
      var totalAbono = document.getElementsByName("total_abono_lote[]");
      var saldototalVenta = document.getElementsByName("saldo_venta_lote[]");
  
      for (var i = 0; i < idventa.length; i++) {
          // Obtén los valores de venta y abono para cada fila
          var r_totalVenta = parseFloat(totalVenta[i].value) || 0;
          var r_totalAbono = parseFloat(totalAbono[i].value) || 0;
  
          // Calcula el saldo como la diferencia entre totalVenta y totalAbono
          var saldo = r_totalVenta - r_totalAbono;
          if (r_totalAbono>) 
              {
  
              } 
          else 
              {
  
              }
      }
      calcularTotalesAbono();
      calcularTotalesSaldoVenta();*/

    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formularioxlote")[0]);

    $.ajax({
        url: "../ajax/cuentasporcobrar.php?op=guardaryeditarxlote",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.fire({
                title: 'Operación exitosa!',
                text: "Abonos registrados correctamente.",
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


function limpiarmodal() {

    $("#total_ventades").val("");
    $(".filas").remove();
    $("#total").html("0");
    $("#totaldes").html("0");


}



function listarVentaxlote() {
    tabla = $('#tbllistadoventasxlote').dataTable(
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
                url: '../ajax/cuentasporcobrar.php?op=listarVentaxlote',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[1, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}

var impuesto = 12;
var cont = 0;
var detalles = 0;
/*
function agregarDetalle(idventa, idcliente, nombre_cliente, tipo_comprobante, numero_ecoFactura, fecha, total_venta, total_abono, saldo_venta) {
    //FECHA
    const now = new Date();

    // Create a date formatter for Guatemala's time zone
    const guatemalaDateFormatter = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'America/Guatemala',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });

    // Format the date to 'YYYY-MM-DD'
    const todayInGuatemala = guatemalaDateFormatter.format(now).replace(/\//g, '-');
    if (idventa != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idventa_lote[]" value="' + idventa + '"><input type="hidden" name="idcliente_lote[]" value="' + idcliente + '">' + nombre_cliente + '</td>' +
            '<td><input type="date"  style="width:100px"  class="form-control"  value="' + fecha + '" readonly=""></td>' +
            '<td>' + tipo_comprobante + '</td>' +
            '<td><input type="text"  style="width:100px" class="form-control"  value="' + numero_ecoFactura + '" readonly=""></td>' +
            '<td><input type="number" onchange="modificarSubototales()" class="form-control" style="width:100px" step="any" name="total_venta_lote[]" id="total_venta_lote[]" value="' + saldo_venta + '" readonly=""></td>' +
            '<td><input type="number" step="any" onchange="modificarSubototales()" class="form-control" style="width:100px" step="any" name="total_abono_lote[]" id="total_abono_lote[]" value="0"></td>' +
            '<td><input type="number" step="any" onchange="modificarSubototales()"  class="form-control" style="width:100px" step="any" name="saldo_venta_lote[]" id="[]" value="' + saldo_venta + '" readonly=""></td>' +
            `<td>
            <select class="form-control" style="width:100px" name="tipo_pago_lote[]" id="tipo_pago_lote`+ cont + `" >
            <option value="EFECTIVO">EFECTIVO</option>
            <option value="CHEQUE">CHEQUE</option>
            <option value="TRANSFERENCIA">TRANSFERENCIA</option>
            </select>
        </td>`+
            `<td><input type="date" style="width:100px" class="form-control" name="fechapago_lote[]" id="fechapago_lote[]" value="${todayInGuatemala}"></td>` +
            `<td>
            <select class="form-control" style="width:100px" name="tipo_banco_lote[]" id="tipo_banco_lote`+ cont + `" >
            <option value="BANRURAL">BANRURAL</option>
            <option value="BANCO AGRICOLA MERCANTIL">BANCO AGRICOLA MERCANTIL</option>
            <option value="INDUSTRIAL">INDUSTRIAL</option>
            <option value="BAC REFORMADOR">BAC REFORMADOR</option>
            <option value="BANCO GYT">BANCO GYT</option>
            <option value="BANCO PROMERICA">BANCO PROMERICA</option>
            <option value="BANCO FICOSA">BANCO FICOSA</option>
            <option value="INTERBANCO">INTERBANCO</option>
            </select>
        </td>`+
            '<td><input type="text" class="form-control" style="width:100px" step="any" name="numero_boleta_lote[]" id="numero_boleta_lote[]" value="0"  placeholder="Boleta #"></td>' +
            '<td><input type="text" class="form-control" style="width:100px" step="any" name="recibo_caja_numero_lote[]" id="recibo_caja_numero_lote[]" value="0" placeholder="Boleta #"></td>' +
            '<td><input type="text" class="form-control" style="width:100px" step="any" name="descripcion_lote[]" id="descripcion_lote[]" value="0" placeholder="Descripcion"></td>' +



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
*/
function agregarDetalle(idventa, idcliente, nombre_cliente, tipo_comprobante, numero_ecoFactura, fecha, total_venta, total_abono, saldo_venta) {
    const now = new Date();
    const guatemalaDateFormatter = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'America/Guatemala',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
    const todayInGuatemala = guatemalaDateFormatter.format(now).replace(/\//g, '-');

    if (idventa !== "") {
        const rowId = 'fila' + cont;
        const totalAbonoId = 'total_abono_lote' + cont;
        const saldoVentaId = 'saldo_venta_lote' + cont;
        const tipoPagoId = 'tipo_pago_lote' + cont;
        const tipoBancoId = 'tipo_banco_lote' + cont;
        const numeroBoletaId = 'numero_boleta_lote' + cont;
        const reciboCajaId = 'recibo_caja_numero_lote' + cont;
        const fechaPagoId = 'fechapago_lote' + cont;

        const fila = `
            <tr class="filas" id="${rowId}">
                <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
                <td><input type="hidden" name="idventa_lote[]" value="${idventa}"><input type="hidden" name="idcliente_lote[]" value="${idcliente}">${nombre_cliente}</td>
                <td><input type="date" style="width:100px" class="form-control" value="${fecha}" readonly=""></td>
                <td>${tipo_comprobante}</td>
                <td><input type="text" style="width:100px" class="form-control" value="${numero_ecoFactura}" readonly=""></td>
                <td><input type="number" onchange="modificarSubototales()" class="form-control" style="width:100px" step="any" name="total_venta_lote[]" id="total_venta_lote${cont}" value="${saldo_venta}" readonly=""></td>
                <td><input type="number" step="any" onchange="validarAbono(this)" class="form-control" style="width:100px" name="total_abono_lote[]" id="${totalAbonoId}" value="0"></td>
                <td><input type="number" step="any" onchange="modificarSubototales()" class="form-control" style="width:100px" name="saldo_venta_lote[]" id="${saldoVentaId}" value="${saldo_venta}" readonly=""></td>
                <td>
                    <select class="form-control" style="width:100px" name="tipo_pago_lote[]" id="${tipoPagoId}">
                        <option value="EFECTIVO">EFECTIVO</option>
                        <option value="CHEQUE">CHEQUE</option>
                        <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                    </select>
                </td>
                <td><input type="date" style="width:100px" class="form-control" name="fechapago_lote[]" id="${fechaPagoId}" value="${todayInGuatemala}"></td>
                <td>
                    <select class="form-control" style="width:100px" name="tipo_banco_lote[]" id="${tipoBancoId}">
                        <option value="BANRURAL">BANRURAL</option>
                        <option value="BANCO AGRICOLA MERCANTIL">BANCO AGRICOLA MERCANTIL</option>
                        <option value="INDUSTRIAL">INDUSTRIAL</option>
                        <option value="BAC REFORMADOR">BAC REFORMADOR</option>
                        <option value="BANCO GYT">BANCO GYT</option>
                        <option value="BANCO PROMERICA">BANCO PROMERICA</option>
                        <option value="BANCO FICOSA">BANCO FICOSA</option>
                        <option value="INTERBANCO">INTERBANCO</option>
                    </select>
                </td>
                <td><input type="text" class="form-control" style="width:100px" name="numero_boleta_lote[]" id="${numeroBoletaId}" value="" placeholder="Boleta #"></td>
                <td><input type="text" class="form-control" style="width:100px" name="recibo_caja_numero_lote[]" id="${reciboCajaId}" value="" placeholder="Recibo #"></td>
                <td><input type="text" class="form-control" style="width:100px" name="descripcion_lote[]" id="descripcion_lote${cont}" value="0" placeholder="Descripcion"></td>
            </tr>`;

        cont++;
        detalles++;
        $('#detalles').append(fila);
        modificarSubototales();
        $('#' + tipoPagoId).on('change', function () {
            handlePagoChange(tipoPagoId, tipoBancoId, numeroBoletaId, reciboCajaId);
        });
        handlePagoChange(tipoPagoId, tipoBancoId, numeroBoletaId, reciboCajaId);

    } else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function handlePagoChange(tipoPagoId, tipoBancoId, numeroBoletaId, reciboCajaId) {
    const tipoPago = $('#' + tipoPagoId).val();

    const bancoSelect = $('#' + tipoBancoId);
    const boletaInput = $('#' + numeroBoletaId);
    const reciboInput = $('#' + reciboCajaId);

    if (tipoPago === 'EFECTIVO') {
        bancoSelect.prop('disabled', true).val('BANRURAL');
        boletaInput.prop('disabled', true).val('');
        reciboInput.prop('disabled', true).val('');
    } else {
        bancoSelect.prop('disabled', false);
        boletaInput.prop('disabled', false);
        reciboInput.prop('disabled', false).val('');
    }
}


function modificarSubototales() {
    var idventa = document.getElementsByName("idventa_lote[]");
    var totalVenta = document.getElementsByName("total_venta_lote[]");
    var totalAbono = document.getElementsByName("total_abono_lote[]");
    var saldototalVenta = document.getElementsByName("saldo_venta_lote[]");

    for (var i = 0; i < idventa.length; i++) {
        // Obtén los valores de venta y abono para cada fila
        var r_totalVenta = parseFloat(totalVenta[i].value) || 0;
        var r_totalAbono = parseFloat(totalAbono[i].value) || 0;

        // Calcula el saldo como la diferencia entre totalVenta y totalAbono
        var saldo = r_totalVenta - r_totalAbono;

        // Asigna el valor del saldo a saldo_venta_lote para cada fila
        saldototalVenta[i].value = saldo.toFixed(2); // opcional: para limitar a dos decimales
    }
    calcularTotalesAbono();
    calcularTotalesSaldoVenta();
}

function validarAbono(elemento) {
    //console.log("Validar el Abono ", elemento);
    var r_totalAbono = parseFloat(elemento.value) || 0;
    var fila = elemento.closest('tr');
    var r_totalVenta = parseFloat(fila.querySelector('input[name="total_venta_lote[]"]').value) || 0;

    if (r_totalAbono < 0) {
        Swal.fire({
            title: "Atención!",
            text: "El abono no puede ser negativo. Se ha establecido a 0.",
            icon: "error"
        });
        elemento.value = 0;
        modificarSubototales();
        return;
    }
    if (r_totalAbono > r_totalVenta) {
        Swal.fire({
            title: "Atención!",
            text: "El abono no puede ser mayor que el saldo pendiente. Se ha ajustado al saldo total de la venta.",
            icon: "error"
        });
        elemento.value = r_totalVenta;
        modificarSubototales();
        return;
    }
    modificarSubototales();
}



function calcularTotalesAbono() {
    var sub = document.getElementsByName("total_abono_lote[]");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        total += parseFloat(document.getElementsByName("total_abono_lote[]")[i].value);
    }
    $("#totalAbonoGeneral").html("Q/. " + total.toFixed(2));
    $("#totalAbonoGeneral_t").val(total);

}

function calcularTotalesSaldoVenta() {
    var sub = document.getElementsByName("saldo_venta_lote[]");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        total += parseFloat(document.getElementsByName("saldo_venta_lote[]")[i].value);
    }
    $("#totalSaldoGeneral").html("Q/. " + total.toFixed(2));
    $("#totalSaldoGeneral_t").val(total);

}




function calculosaldoingreso() {

    var b1 = document.getElementById('total_venta').value;
    var b2 = document.getElementById('total_abono').value;


    var resultado_saldo_venta = parseFloat(b1) - parseFloat(b2);
    document.getElementById('saldo_venta').innerHTML = resultado_saldo_venta;
    $("#saldo_venta").val(resultado_saldo_venta);
}

//Función limpiar
function limpiar() {
    $("#idventa").val("");
    $("#idcliente").val("");
    $("#nombre").val("");
    $("#telefonocliente").val("");
    $("#fecha_hora_factura").val("");
    $("#total_venta").val("");
    $("#total_abono").val("");
    $("#saldo_venta").val("");
    $("#tipo_pago").val("");
    $("#fecha_hora").val("");
    $("#tipo_banco").val("");
    $("#numero_boleta").val("");
    $("#recibo_caja_numero").val("");
    $("#descripcion").val("");

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
function listarFacturas() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
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
                url: '../ajax/cuentasporcobrar.php?op=listarFacturas',
                type: "get",
                data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[1, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}


//Función Listar
function listarAbonos() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    tabla = $('#tbllistadoPagosEchos').dataTable(
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
                url: '../ajax/cuentasporcobrar.php?op=listarAbonos',
                type: "get",
                data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[1, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}


//Función para guardar o editar

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/cuentasporcobrar.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        }

    });
    limpiar();
}

function mostrar(idventa) {
    $.post("../ajax/cuentasporcobrar.php?op=mostrar", { idventa: idventa }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre_cliente);

        $("#idventa").val(data.idventa);
        $("#idcliente").val(data.idcliente);
        $("#telefonocliente").val(data.telefono_cliente);
        $("#tipodocumento").val(data.tipo_comprobante);
        $("#seriedocuemnto").val(data.serie_comprobante);
        $("#numerodocumento").val(data.num_comprobante);
        $("#fecha_hora_factura").val(data.fecha_hora_factura);
        $("#total_venta").val(data.saldo_venta);
        $("#saldo_venta").val(data.saldo_venta);

        $("#tipo_banco").val(data.tipo_banco);
        $("#tipo_banco").selectpicker('refresh');
        $("#recibo_caja_numero").val(data.recibo_caja_numero);
        $("#idcuenta").val(data.idcuenta);
        $("#idcuenta").selectpicker('refresh');

    })
}

//Función para desactivar registros
function desactivar(idventa) {
    bootbox.confirm("¿Está Seguro de Revertir el proceso de la cuenta por cobrar?", function (result) {
        if (result) {
            $.post("../ajax/cuentasporcobrar.php?op=desactivar", { idventa: idventa }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}


function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    detalles = detalles - 1;
    modificarSubototales();
}


function anular_abono(idventa, idcta_cobrar) {
    Swal.fire({
        title: "¿Está seguro de anular el abono?",
        text: "¡Si lo anula, el abono no se podrá recuperar!",
        icon: "warning",
        buttons: {
            cancel: "No, cancelar",
            confirm: {
                text: "Sí, anular!",
                value: true,
                className: "btn-danger"
            }
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            load()
            $.post("../ajax/cuentasporcobrar.php?op=anular_abono", {
                idventa: idventa,
                idcta_cobrar: idcta_cobrar
            }, function (e) {
                Swal.fire({
                    title: "Operación exitosa!",
                    text: "Abono anulado correctamente.",
                    icon: "success"
                });
                tabla.ajax.reload();
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