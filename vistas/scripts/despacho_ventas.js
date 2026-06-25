var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    $('#MenuVentas').addClass("treeview active");
    $('#Ventas').addClass("active");

    listarArticulos();
    // listarArticulosxcategoria();
    $("#div_formapago").hide();
    $("#tipo_combus").hide();
    $("#no_galo").hide();
    $("#div_facCambiaria").hide();



 


    $.post("../ajax/articulo.php?op=selectCategoria", function (r) {
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');

    }); 


    $.post("../ajax/venta.php?op=selectTransporte", function (r) {
        $("#idtransporte").html(r);
        $('#idtransporte').selectpicker('refresh');
    });

    $.post("../ajax/venta.php?op=selectMensajero", function (r) {
        $("#idmensajero").html(r);
        $('#idmensajero').selectpicker('refresh');
    });

    $("#btnGuardar").click(function (e) {
        $('#myModal22').modal('hide');
        guardaryeditar(e);
    });



    $("#btncargar").click(function () {

        var idcotizacion = $("#idcotizacion").val();
        if (idcotizacion == "") {
            alert("Debe Colocar un Id de Cotizacion Valido")
            return;
        }

        obtenerClienteCotizacion(idcotizacion);

    });

    $("#btnGuardarApertura").click(function (e) {
        guardaryeditaraperturacaja(e);
    });

    $("#btnGuardarCierre").click(function (e) {
        guardaryeditarCierre(e);
    });






}

//Función cancelarform
function cancelarformaperturacaja() {
    limpiarapertura();
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
    var tipo_entrega = $("#tipo_entrega").val();
 
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
                url: '../ajax/venta.php?op=listar_despacho',
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte,tipo_entrega:tipo_entrega },
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

function cambiarestado(idventa) {
    bootbox.confirm("¿Está Seguro de Cambiar el estado a Venta Despachada?", function (result) {
        if (result) {  // Solo si el usuario acepta
            load();  // Aquí sí ejecutamos load()
            
            $.post("../ajax/venta.php?op=cambiarestadodespacho", { idventa: idventa }, function (e) {
                Swal.fire({
                    title: 'Mensaje!',
                    text: e,
                    icon: 'success',
                    timerProgressBar: true,
                    willClose: () => {
                        Swal.close();
                        window.location.reload();
                    }
                });
            });
        } else {
            Swal.close();  // Si cancela, aseguramos que Swal esté cerrado
        }
    });
}

 
function cambiarestadoVenta(idventa,despachosino){

    if (despachosino=='SI') {
        bootbox.confirm("¿Está Seguro de aplicar Venta liquida?", function (result) {
            if (result) {  // Solo si el usuario acepta
            load(); 
            if (result) {
                $.post("../ajax/venta.php?op=cambiarestadodespachoVenta", { idventa: idventa }, function (e) {
                    Swal.fire({
                        title: 'Mensaje!',
                        text: e,
                        icon: 'success',
                        //  timer: 2000, // 2 segundos
                        timerProgressBar: true,
                        willClose: () => {

                            Swal.close();
                             tabla.ajax.reload();
                        }
                    });

                });
            } 
        } else {
            Swal.close();  // Si cancela, aseguramos que Swal esté cerrado
        }
        })        
    } else {
        Swal.fire({
        position: "top-end",
        icon: "success",
        title: "No puedes liquidar la venta, tiene que aplicar Venta despacho a la venta",
        showConfirmButton: false,
        timer: 1500,
    });


    }


}
