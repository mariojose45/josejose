var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listarFacturas();


    $("#btnGuardarComentariosMensajero").click(function(e) 
    {
        $('#myModalComentariosMensajero').modal('hide');
        guardaryeditar_ComentariosMensaje(e);  
    });    



    /*$("#btnGuardarFacxLotes").click(function (e) {
        $('#myModalVentasxlote').modal('hide');
        guardaryeditarxlote(e);
    });*/
}  

function agregarCheklistMensajero(idventa) {
    // Mostrar el modal
    $("#myModalCheklistMensajero").modal('show');
    $("#idventa_Mensajero").val(idventa);

    // Esperar a que el modal se abra completamente
    $('#myModalCheklistMensajero').on('shown.bs.modal', function () {
        var canvas = document.getElementById("signatureCanvas");

        if (canvas) {
            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: "rgb(255,255,255)" // Fondo blanco
            });

            // Ajustar tamaño del canvas dinámicamente
            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = 150 * ratio;  // Mantiene altura fija
                canvas.getContext("2d").scale(ratio, ratio);
            }

            resizeCanvas(); // Ajustar tamaño

            // Agregar botón de borrar firma si no existe
            if (!document.getElementById("clearSignatureButton")) {
                var clearButton = document.createElement("button");
                clearButton.id = "clearSignatureButton";
                clearButton.textContent = "Borrar Firma";
                clearButton.classList.add("btn", "btn-warning", "btn-sm");
                clearButton.style.marginTop = "10px";
                clearButton.onclick = function () {
                    signaturePad.clear();
                };
                canvas.parentNode.appendChild(clearButton);
            }

            // Guardar firma al hacer clic en el botón de guardar
            document.getElementById("btnGuardarComentariosMensajero").addEventListener("click", function () {
                if (!signaturePad.isEmpty()) {
                    var firmaData = signaturePad.toDataURL(); // Convierte la firma en base64
                    console.log("Firma guardada:", firmaData);
                } else {
                    alert("Por favor, firma antes de guardar.");
                }
            });
        }
    });
}


  
function guardaryeditar_ComentariosMensaje(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#formulario_comentarioMensajero")[0]);
    load(); 
    $.ajax({
        url: "../ajax/ventas_mensajero.php?op=guardaryeditarComentariosMensajero",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false, 

        success: function (datos) {
            Swal.close() 
            Swal.fire({
                title: 'Mensaje!',
                text: "Comentario Guardado",
                icon: 'success',
                timer: 2000, // 2 segundos
                timerProgressBar: true,
                willClose: () => {
                    limpiarModalComentarioMensajero();
                }
            });

        }

    });
}

function limpiarModalComentarioMensajero() {

    $("#idventa_Mensajero").val("");
    $("#comentario_mensajero").val("");


}


function load() {
    Swal.fire({
        title: 'Espere un momento . . . ',
        allowOutsideClick:false,
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
                url: '../ajax/ventas_mensajero.php?op=listarVentasMensajero',
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

function agregarDetalle(idventa, idcliente, nombre_cliente, tipo_comprobante, numero_ecoFactura, fecha, total_venta, total_abono, saldo_venta) {



    if (idventa != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idventa_lote[]" value="' + idventa + '"><input type="hidden" name="idcliente_lote[]" value="' + idcliente + '">' + nombre_cliente + '</td>' +
            '<td><input type="date"  style="width:100px"  class="form-control"  value="' + fecha + '" readonly=""></td>' +
            `<td>
            <select class="form-control" style="width:100px" name="tipo_pago_lote[]" id="tipo_pago_lote`+ cont + `" >
                    <option value="Efectivo">Efectivo</option>
                    <option value="Efectivo/Tarjeta">Efectivo/Tarjeta</option>
                    <option value="Tarjeta">Tarjeta</option>                                 
                    <option value="Credito">Credito</option>
                    <option value="Transferencia">Transferencia</option>
            </select>
        </td>`+
            '<td><input type="date"  style="width:100px"  class="form-control" name="fechapago_lote[]" id="fechapago_lote[]"></td>' +
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
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
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
                url: '../ajax/ventas_mensajero.php?op=listarFacturasMensajeroxusuario',
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

        success: function (datos) 
        {
            console.log(datos)
            /*bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();*/
        }

    });
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






init();