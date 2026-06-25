var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/cotizaciones.php?op=selectCliente", function (r) {
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');
    });
}

//Función limpiar
function limpiar() {
    $("#idcotizacion").val("");
    $("#idcliente").val("");
    $("#impuesto").val("0");
    $("#AbonoCajaChica").val(" ");
    $("#observacionesCaja").val(" ");
    //Marcamos el primer tipo_documento
    $("#tipo_comprobante").val("Factura");
    $("#tipo_comprobante").selectpicker('refresh');
    $("#AbonoCajaChica").val(" ");
    $("#ConceptoAbono").val(" ");

    $("#total_venta").val("");
    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#Fechaabono').val(today);


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
    tabla.ajax.reload();
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
                url: '../ajax/caja_chica.php?op=listar',
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


//Función ListarArticulos
function listarArticulos() {
    tabla = $('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/caja_chica.php?op=listarArticulosCotizacion',
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
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/caja_chica.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            mostrarform(true);
            listar();
            tabla.ajax.reload();

        }

    });
    limpiar();
}





function mostrar(idcotizacion) {
    $.post("../ajax/cotizaciones.php?op=mostrar", { idcotizacion: idcotizacion }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcliente").val(data.idcliente);
        $("#idcliente").selectpicker('refresh');
        $("#nombre_empresa").val(data.nombre_empresa);
        $("#telefono_empresa").val(data.telefono_empresa);
        $("#fecha_hora").val(data.fecha);
        $("#impuesto").val(data.impuesto);
        $("#idventa").val(data.idventa);

        //Ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });

    $.post("../ajax/cotizaciones.php?op=listarDetalle&id=" + idcotizacion, function (r) {
        $("#detalles").html(r);
    });
}



function abonar(Id) {
    $('#ModalAbonoCaja').modal('show');



    $(document).ready(function (c) {
        $("#SaveAbono").on("click", function (c) {

            var AbonoCajaChica = document.getElementById("AbonoCajaChica").value;
            var tipo_comprobante = document.getElementById("tipo_comprobante").value;
            var Fechaabono = document.getElementById("Fechaabono").value;
            var ConceptoAbono = document.getElementById("ConceptoAbono").value;
            var data = "&AbonoCajaChica=" + AbonoCajaChica + "&Id=" + Id + "&Fechaabono=" + Fechaabono + "&ConceptoAbono=" + ConceptoAbono + "&tipo_comprobante=" + tipo_comprobante;
            $.ajax({
                type: "POST",
                url: "../ajax/caja_chica.php?op=abonar",
                data: "op=" + data,
                success: function (datos) {
                    $('#ModalAbonoCaja').modal('hide');
                    // bootbox.alert({ message: datos, className: "bb-alternate-modal" });
                    tabla.ajax.reload();



                }


            });
            limpiar();
        });
    });

}


function detalle(Id) {

    var data = "&Id=" + Id;
    $.ajax({
        type: "POST",
        url: "../ajax/caja_chica.php?op=detalle",
        data: "op=" + data,
        success: function (datos) {
            $('#tbllistadoAbonos').html(datos);
            $('#ModalDetalleCaja').modal('show');
            console.log(Id);



        }
    });

}











//Función para anular registros
function anular(Id) {
    bootbox.confirm("¿Está Seguro de anular la Caja Chica #" + Id + "?", function (result) {
        if (result) {
            $.post("../ajax/caja_chica.php?op=anular", { Id: Id }, function (e) {
                console.log(Id);
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}






function quitarPago(IdCajaDetalle, IdCajaChica) {
    bootbox.confirm("¿Está Seguro de anular el pago de  la Caja Chica #" + IdCajaDetalle + "?", function (result) {
        if (result) {
            $.post("../ajax/caja_chica.php?op=quitarPago", { IdCajaDetalle: IdCajaDetalle }, function (e) {
                console.log(IdCajaDetalle);
                bootbox.alert(e);
                tabla.ajax.reload();
                detalle(IdCajaChica);
            });
        }
    })
}






function terminar(Id) {
    bootbox.confirm("¿Está Seguro de terminar el proceso de Caja Chica #" + Id + "?", function (result) {
        if (result) {
            $.post("../ajax/caja_chica.php?op=terminar", { Id: Id }, function (e) {
                console.log(Id);
                bootbox.alert(e);
                tabla.ajax.reload();
            });
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

function agregarDetalle(idarticulo, articulo, precio_cotizacion) {
    var cantidad = 1;
    var descuento = 0;

    if (idarticulo != "") {
        var subtotal = cantidad * precio_cotizacion;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input type="number" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
            '<td><input type="number" name="precio_cotizacion[]" id="precio_cotizacion[]" value="' + precio_cotizacion + '"></td>' +
            '<td><input type="number" name="descuento[]" value="' + descuento + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
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

function modificarSubototales() {
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_cotizacion[]");
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpD = desc[i];
        var inpS = sub[i];

        inpS.value = (inpC.value * inpP.value) - inpD.value;
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
    $("#total_cotizacion").val(total);
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

        var valoractual = $("#txtbusquedaartcodebar").val();

        if (valoractual != "") {
            $.get("../ajax/venta.php?op=buscararticulocodebar&codigo=" + valoractual + "", { op: "buscararticulocodebar", codigo: valoractual }, function (res) {
                console.log(res);
                var arrayproduc = res.split("@");

                if (arrayproduc[0] != "undefined") {
                    agregarDetalle(arrayproduc[0], arrayproduc[1], arrayproduc[2]);
                }
                $("#txtbusquedaartcodebar").val("");
                $("#txtbusquedaartcodebar").focus()

            })
        }

    }, 200)

})
















$(document).ready(function (c) {
    $("#btnSave").on("click", function (c) {

        var aperturaCaja = document.getElementById("aperturaCaja").value;
        var fecha_hora = document.getElementById("fecha_hora").value;
        var observacionesCaja = document.getElementById("observacionesCaja").value;
        var data = "&aperturaCaja=" + aperturaCaja + "&fecha_hora=" + fecha_hora + "&observacionesCaja=" + observacionesCaja;
        $.ajax({
            type: "POST",
            url: "../ajax/caja_chica.php?op=guardaryeditar",
            data: "addCaja=" + data,
            success: function (html) {
                $("#btnRegresar").css("display", "inherit");
                bootbox.alert({ message: html, className: "bb-alternate-modal" });




            }


        });

    });


}
);



