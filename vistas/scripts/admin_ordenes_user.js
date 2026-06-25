var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    $("#div_cambiarEstado").hide();


    $('#Menuordenesservicio').addClass("treeview active");
    $('#Listaordenesservicio').addClass("active");

    $.post("../ajax/tecnico.php?op=selectTeccnico", function (r) {
        $("#idtecnico").html(` <option value="SELECCIONE TECNICO">SELECCIONE TECNICO</option> ` + r);
        $('#idtecnico').selectpicker('refresh');

        // No results matched - 
        $(".tecnicoselect .bs-searchbox input").keyup(function () {
            var valor = $(this).val();
            var textooption = $(".tecnicoselect .no-results").text();
            var newText = textooption.replace("No results matched", "Crear Nuevo Tecnico");
            $(".tecnicoselect .no-results").text(newText);
            $(".tecnicoselect .no-results").css("cursor", "pointer");
        });

        // Crear Nuevo 
        $(document).on('click', '.tecnicoselect > div > div > ul > li.no-results', function () {
            $("#myModalTecnico").modal('show');
        });
    });

    $.post("../ajax/venta_2.php?op=selectCliente", function (r) {
        $("#idcliente").html(` <option value="SELECCIONE CLIENTE">SELECCIONE CLIENTE</option> ` + r);
        $('#idcliente').selectpicker('refresh');

        // No results matched - 
        $(".clienteselect .bs-searchbox input").keyup(function () {
            var valor = $(this).val();
            var textooption = $(".clienteselect .no-results").text();
            var newText = textooption.replace("No results matched", "Crear Nuevo Cliente");
            $(".clienteselect .no-results").text(newText);
            $(".clienteselect .no-results").css("cursor", "pointer");
        });

        // Crear Nuevo 
        $(document).on('click', '.clienteselect > div > div > ul > li.no-results', function () {
            $("#myModalCliente").modal('show');
        });
    });

    $.post("../ajax/marca.php?op=selectMarca", function (r) {
        $("#idmarca").html(` <option value="SELECCIONE MARCA">SELECCIONE MARCA</option> ` + r);
        $('#idmarca').selectpicker('refresh');

        // No results matched - 
        $(".marcaselect .bs-searchbox input").keyup(function () {
            var valor = $(this).val();
            var textooption = $(".marcaselect .no-results").text();
            var newText = textooption.replace("No results matched", "Crear Nueva Marca");
            $(".marcaselect .no-results").text(newText);
            $(".marcaselect .no-results").css("cursor", "pointer");
        });

        // Crear Nuevo 
        $(document).on('click', '.marcaselect > div > div > ul > li.no-results', function () {
            $("#myModalMarca").modal('show');
        });
    });


    $.post("../ajax/modelo.php?op=selectModelo", function (r) {
        $("#idmodelo").html(` <option value="SELECCIONE MODELO">SELECCIONE MODELO</option> ` + r);
        $('#idmodelo').selectpicker('refresh');

        // No results matched - 
        $(".modeloselect .bs-searchbox input").keyup(function () {
            var valor = $(this).val();
            var textooption = $(".modeloselect .no-results").text();
            var newText = textooption.replace("No results matched", "Crear Nuevo Modelo");
            $(".modeloselect .no-results").text(newText);
            $(".modeloselect .no-results").css("cursor", "pointer");
        });

        // Crear Nuevo 
        $(document).on('click', '.modeloselect > div > div > ul > li.no-results', function () {
            $("#myModalModelo").modal('show');
        });
    });

    $.post("../ajax/tipo_equipo.php?op=selectTipoequipo", function (r) {
        $("#idtipo_equipo").html(` <option value="SELECCIONE TIPO EQUIPO">SELECCIONE TIPO EQUIPO</option> ` + r);
        $('#idtipo_equipo').selectpicker('refresh');

        // No results matched - 
        $(".tipoequiposelect .bs-searchbox input").keyup(function () {
            var valor = $(this).val();
            var textooption = $(".tipoequiposelect .no-results").text();
            var newText = textooption.replace("No results matched", "Crear Nuevo Tipo Equipo");
            $(".tipoequiposelect .no-results").text(newText);
            $(".tipoequiposelect .no-results").css("cursor", "pointer");
        });

        // Crear Nuevo 
        $(document).on('click', '.tipoequiposelect > div > div > ul > li.no-results', function () {
            $("#myModalTipoequipo").modal('show');
        });
    });

    $.post("../ajax/colores.php?op=selectColores", function (r) {
        $("#idcolor").html(` <option value="SELECCIONE COLOR">SELECCIONE COLOR</option> ` + r);
        $('#idcolor').selectpicker('refresh');

        // No results matched - 
        $(".coloresselect .bs-searchbox input").keyup(function () {
            var valor = $(this).val();
            var textooption = $(".coloresselect .no-results").text();
            var newText = textooption.replace("No results matched", "Crear Nuevo Color");
            $(".coloresselect .no-results").text(newText);
            $(".coloresselect .no-results").css("cursor", "pointer");
        });

        // Crear Nuevo 
        $(document).on('click', '.coloresselect > div > div > ul > li.no-results', function () {
            $("#myModalColores").modal('show');
        });
    });


    /*
       $.post("../ajax/marca.php?op=selectMarca", function(r){
                   $("#idmarca").html(r);
                   $('#idmarca').selectpicker('refresh');
   
                   $(".bs-searchbox").children("input").keyup(function(){
                       //No results matched
                       var valor=$(this).val();
                       var textooption=$(".no-results").text();
                       var newText=textooption.replace("No results matched","Crear Nuevo");
                       $(".no-results").text(newText);
   
                       $(".no-results").css("cursor","pointer");
   
                       $(".marcaselect > div > div >ul >li.no-results").click(function(){
                           $("#myModalMarca").modal('show');
                       })
   
                   });               
       }); 
    
       $.post("../ajax/modelo.php?op=selectModelo", function(r){
                   $("#idmodelo").html(r);
                   $('#idmodelo').selectpicker('refresh'); 
   
                   $(".bs-searchbox").children("input").keyup(function(){
                       //No results matched
                       var valor=$(this).val();
                       var textooption=$(".no-results").text();
                       var newText=textooption.replace("No results matched","Crear Nuevo");
                       $(".no-results").text(newText);
   
                       $(".no-results").css("cursor","pointer");
   
                       $(".modeloselect > div > div >ul >li.no-results").click(function(){
                           $("#myModalModelo").modal('show');
                       })
   
                   });                
       }); 
   
       $.post("../ajax/tipo_equipo.php?op=selectTipoequipo", function(r){
                   $("#idtipo_equipo").html(r);
                   $('#idtipo_equipo').selectpicker('refresh');
   
                   $(".bs-searchbox").children("input").keyup(function(){
                       //No results matched
                       var valor=$(this).val();
                       var textooption=$(".no-results").text();
                       var newText=textooption.replace("No results matched","Crear Nuevo");
                       $(".no-results").text(newText);
   
                       $(".no-results").css("cursor","pointer");
   
                       $(".tipoequiposelect > div > div >ul >li.no-results").click(function(){
                           $("#myModalTipoequipo").modal('show');
                       })
   
                   });               
       }); 
    
       $.post("../ajax/colores.php?op=selectColores", function(r){
                   $("#idcolor").html(r);
                   $('#idcolor').selectpicker('refresh');
   
                   $(".bs-searchbox").children("input").keyup(function(){
                       //No results matched
                       var valor=$(this).val();
                       var textooption=$(".no-results").text();
                       var newText=textooption.replace("No results matched","Crear Nuevo");
                       $(".no-results").text(newText);
   
                       $(".no-results").css("cursor","pointer");
   
                       $(".coloresselect > div > div >ul >li.no-results").click(function(){
                           $("#myModalColores").modal('show');
                       })
   
                   });               
       });      */

    $("#btnGuardarTecnico").click(function (e) {
        guardaryeditarTecnico(e);
    });

    $("#btnGuardarCliente").click(function (e) {
        guardaryeditarCliente(e);
    });


    $("#btnGuardarMarca").click(function (e) {
        guardaryeditarMarca(e);
    });

    $("#btnGuardarModelo").click(function (e) {
        guardaryeditarModelo(e);
    });

    $("#btnGuardarTipoequipo").click(function (e) {
        guardaryeditarTipoequipo(e);
    });

    $("#btnGuardarColor").click(function (e) {
        guardaryeditarColor(e);
    });

    $("#btnGuardarNuevaOrden").click(function (e) {
        guardaryeditarNuevaOrden(e);
    });

    $("#btnGuardarCambiarestado").click(function (e) {
        guardaryeditarCambiarEstado(e);
    });

}


$("#cambiar_estado").change(mostrarFormaPago);

function mostrarFormaPago() {
    var cambiar_estado = $("#cambiar_estado option:selected").text();
    if (cambiar_estado == 'ENTREGADO') {
        var idnueva_orden_cambiar_estado = $("#idnueva_orden_cambiar_estado").val();
        $("#div_cambiarEstado").show();
        $.post("../ajax/admin_ordenes.php?op=mostrarSaldoOrden", { idnueva_orden_cambiar_estado: idnueva_orden_cambiar_estado }, function (data) {
            //alert(data); 
            data = JSON.parse(data);
            if (data != null) {


                $("#Entrega_presupuesto").val(data.presupuesto);
                $("#Entrega_repuestos").val(data.repuestos);
                $("#Entrega_anticipo").val(data.anticipo);
                $("#Entrega_total_orden").val(data.total_orden);
                calcularEntregaPresupuesto();
            }
            else {
                alert("No se puede mostrar la informacion solicitada");
                $("#div_cambiarEstado").hide();
                //$("#myModalCambiarestado").hide(); 
                return;
            }


        })
    }
    else {
        $("#div_cambiarEstado").hide();
    }
}


function calcularEntregaPresupuesto() {
    //alert("ingreso aka")
    var Entrega_presupuesto = $("#Entrega_presupuesto").val();
    var Entrega_repuestos = $("#Entrega_repuestos").val();
    var Entrega_anticipo = $("#Entrega_anticipo").val();
    var Entrega_total_orden = $("#Entrega_total_orden").val();
    var Entrega_SaldoPendientexpagar = $("#Entrega_SaldoPendientexpagar").val();


    rescambio = ((parseFloat(Entrega_presupuesto) - parseFloat(Entrega_anticipo)) - parseFloat(Entrega_SaldoPendientexpagar));
    $("#Entrega_total_orden").val(rescambio);


}


function rptordenesEquiposReparados() {

    tabla = $('#tblarticulosequiposreparados').dataTable(
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
                url: '../ajax/admin_ordenes.php?op=ordenesxcliente_reparacion_user',

                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 50,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden) 
        }).DataTable();
}

function rptordenesEquiposESPERA() {

    tabla = $('#tblarticulosequiposEspera').dataTable(
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
                url: '../ajax/admin_ordenes.php?op=ordenesxcliente_espera_user',

                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 50,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden) 
        }).DataTable();
}

function rptordenesEquiposENTREGADOS() {

    tabla = $('#tblarticulosequiposEntregados').dataTable(
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
                url: '../ajax/admin_ordenes.php?op=ordenesxcliente_entregados_user',

                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 50,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden) 
        }).DataTable();
}

function rptordenesEquiposGARANTIA() {

    tabla = $('#tblarticulosequiposGarantia').dataTable(
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
                url: '../ajax/admin_ordenes.php?op=ordenesxcliente_garantia_user',

                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 50,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden) 
        }).DataTable();
}


function rptordenesEquiposSINREPARAR() {

    tabla = $('#tblarticulosequiposSinreparar').dataTable(
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
                url: '../ajax/admin_ordenes.php?op=ordenesxcliente_SINREPARAR_user',

                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 50,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden) 
        }).DataTable();
}

function rptordenesEquiposREPARADOSS() {

    tabla = $('#tblarticulosequiposReparadoss').dataTable(
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
                url: '../ajax/admin_ordenes.php?op=ordenesxcliente_REPADOSS_user',

                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 50,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden) 
        }).DataTable();
}



function guardaryeditarCambiarEstado(e) {

    var cambiar_estado = $("#cambiar_estado").val();
    var Entrega_presupuesto = $("#Entrega_presupuesto").val();
    var Entrega_total_orden = $("#Entrega_total_orden").val();

    if (cambiar_estado == 'ENTREGADO') {
        if (parseFloat(Entrega_total_orden) > 0) {
            alert("Para pasar a estado ENTREGADO. tiene que liquidar la orden")
            return;
        }
    }

    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioCambiarestado")[0]);

    $.ajax({
        url: "../ajax/admin_ordenes.php?op=guardaryeditarCambiarEstado",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log(datos)
            $("#myModalCambiarestado").modal('hide');
            swal("Mensaje!", datos, "success");
            tabla.ajax.reload();

            if (cambiar_estado == 'ENTREGADO') {
                if (confirm("Desea Imprimir su Comprobante")) {
                    window.open("../reportes/exTicket.php?id=" + datos);
                    //window.location.href="../reportes/exEnvio.php?id="+datos,'_blank';
                    window.location.reload();
                    //window.open("../reportes/exTicket.php?id="+datos,'_blank');
                }
                else {
                    window.location.reload();
                }
            }
        }

    });
    limpiarCambiarestado();
}

//Función cancelarform
function cancelarformCambiarestado() {
    limpiarCambiarestado();
}

function limpiarCambiarestado() {

    $("#idnueva_orden_cambiar_estado").val("");
    $("#cambiar_estado").val("ESPERA");
    $("#cambiar_estado").selectpicker('refresh');


}


function guardaryeditarColor(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioColor")[0]);

    $.ajax({
        url: "../ajax/colores.php?op=guardaryeditarModal",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            $("#idcolor").append(datos).selectpicker('refresh');
            alert("Agregado Correctamente")
            $('#myModalColores').modal('hide');
        }

    });
    limpiarColor();
}

//Función cancelarform
function cancelarformColor() {
    limpiarColor();
}

function limpiarColor() {

    $("#nombre_color").val("");
    $("#descripcion_color").val("");
}


function guardaryeditarTipoequipo(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioTipoequipo")[0]);

    $.ajax({
        url: "../ajax/tipo_equipo.php?op=guardaryeditarModal",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            $("#idtipo_equipo").append(datos).selectpicker('refresh');
            alert("Agregado Correctamente")
            $('#myModalTipoequipo').modal('hide');
        }

    });
    limpiarTipoequipo();
}

//Función cancelarform
function cancelarformTipomodelo() {
    limpiarTipoequipo();
}

function limpiarTipoequipo() {

    $("#nombre_tipoequipo").val("");
    $("#descripcion_tipoequipo").val("");
}


function guardaryeditarModelo(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioModelo")[0]);

    $.ajax({
        url: "../ajax/modelo.php?op=guardaryeditarModal",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            $("#idmodelo").append(datos).selectpicker('refresh');
            alert("Agregado Correctamente")
            $('#myModalModelo').modal('hide');
        }

    });
    limpiarModelo();
}

//Función cancelarform
function cancelarformModelo() {
    limpiarModelo();
}

function limpiarModelo() {

    $("#nombre_modelo").val("");
    $("#descripcion_modelo").val("");
}



function guardaryeditarMarca(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioMarca")[0]);

    $.ajax({
        url: "../ajax/marca.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log(datos)
            /*$("#idmarca").append(datos).selectpicker('refresh');
            alert("Agregado Correctamente")
            $('#myModalMarca').modal('hide');*/
        }

    });
    limpiarMarca();
}

//Función cancelarform
function cancelarformMarca() {
    limpiarMarca();
}

function limpiarMarca() {

    $("#codigo_marca").val("");
    $("#nombre_marca").val("");
    $("#descripcion_marca").val("");
}

function guardaryeditarTecnico(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulariotecnico")[0]);

    $.ajax({
        url: "../ajax/tecnico.php?op=guardaryeditarModal",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            //console.log(datos)
            $("#idtecnico").append(datos).selectpicker('refresh');
            alert("Agregado Correctamente")
            $('#myModalTecnico').modal('hide');
        }

    });
    limpiarTecnico();
}

//Función cancelarform
function cancelarformTecnico() {
    limpiarTecnico();
}

function limpiarTecnico() {

    $("#nombre_tecnico").val("");
    $("#descripcion_tecnico").val("");
    $("#comision_tecnico").val("");
}

function guardaryeditarCliente(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioCliente")[0]);

    $.ajax({
        url: "../ajax/persona.php?op=guardaryeditarModal",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            $("#idcliente").append(datos).selectpicker('refresh');
            alert("Agregado Correctamente")
            $('#myModalCliente').modal('hide');
        }

    });
    limpiarCliente();
}

//Función cancelarform
function cancelarformCliente() {
    limpiarCliente();
}

function limpiarCliente() {

    $("#nombre_cliente").val("C/F");
    $("#tipo_documento_cliente").val("NIT");
    $("#tipo_documento_cliente").selectpicker('refresh');
    $("#num_documento_cliente").val("C/F");
    $("#direccion_cliente").val("CIUDAD");
    $("#telefono_cliente").val("0");
    $("#email_cliente").val("soporte@gmail.com");
    $("#tipo_cliente_cliente").val("PUBLICO");
    $("#tipo_cliente_cliente").selectpicker('refresh');
}



//Función limpiar
function limpiarNuevaOrden() {
    $("#idnueva_orden").val("");
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);

    $("#imei_cel").val("");
    $("#enciende").val("NO");
    $("#enciende").selectpicker('refresh');

    $("#golpes").val("NO");
    $("#golpes").selectpicker('refresh');

    $("#puerto_carga").val("NO");
    $("#puerto_carga").selectpicker('refresh');

    $("#password_orden").val("");
    $("#falla_equipo").val("");
    $("#diagnostico_equipo").val("");

    $("#presupuesto").val("0");
    $("#repuestos").val("0");
    $("#anticipo").val("0");
    $("#total_orden").val("0");
    $("#codigo_ordennueva").val("0");

    $("#idtecnico").val("SELECCIONE TECNICO");
    $("#idtecnico").selectpicker('refresh');

    $("#idcliente").val("SELECCIONE CLIENTE");
    $("#idcliente").selectpicker('refresh');

    $("#idmarca").val("SELECCIONE MARCA");
    $("#idmarca").selectpicker('refresh');

    $("#idmodelo").val("SELECCIONE MODELO");
    $("#idmodelo").selectpicker('refresh');

    $("#idtipo_equipo").val("SELECCIONE TIPO EQUIPO");
    $("#idmidtipo_equipoodelo").selectpicker('refresh');

    $("#idcolor").val("SELECCIONE COLOR");
    $("#idcolor").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiarNuevaOrden();
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
function cancelarformNuevaOrden() {
    limpiarNuevaOrden();
    $("#myModalNuevaorden").modal('hide');
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
                url: '../ajax/admin_ordenes.php?op=listarxusuario',
                type: "get",
                dataType: "json",
                error: function (e) {

                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 30,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}

$(document).ready(function () {
    var table = $('#tbllistado').DataTable();

    $('#tbllistado tbody').on('click', 'tr', function () {
        var tr = $(this);
        var row = table.row(tr);


        var ordenId = row.data()[0];
        detalles(ordenId);


        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(formatoDetalles(row.data())).show();
            //  alert(row.data())
            tr.addClass('shown');
        }
    });
});

function detalles(ordenId) {

    console.log("MOSTRAR DETALLES para la orden: " + ordenId);

}

//Funcion para dar formato a los Detalles
function formatoDetalles(d) {
    return (
        '<dl>' +
        '<dt>Falla:</dt>' +
        '<dd>' + d[10] + '</dd>' +
        '<dt>Diagnostico:</dt>' +
        '<dd>' + d[11] + '</dd>' +
        '<dt>User Creacion:</dt>' +
        '<dd>' + d[12] + '</dd>' +
        '<dt>Fecha/HJpra:</dt>' +
        '<dd>' + d[13] + '</dd>' +
        '<dt>Cod Orden:</dt>' +
        '<dd>' + d[14] + '</dd>' +
        '<dt># Orden:</dt>' +
        '<dd>' + d[15] + '</dd>' +
        '<dt>Presupuesto:</dt>' +
        '<dd>' + d[16] + '</dd>' +
        '<dt>Repuestos:</dt>' +
        '<dd>' + d[17] + '</dd>' +
        '<dt>Anticipo:</dt>' +
        '<dd>' + d[18] + '</dd>' +
        '<dt>Total Orden:</dt>' +
        '<dd>' + d[19] + '</dd>' +
        '<dt>Condicion:</dt>' +
        '<dd>' + d[20] + '</dd>' +
        '</dl>'
    );
}
//Función para guardar o editar

function guardaryeditarNuevaOrden(e) {
    var idtecnico = $("#idtecnico option:selected").text();
    if (idtecnico == 'SELECCIONE TECNICO') {
        alert("Tienes que seleccionar un Tecnico no puede generar su orden ");
        return;
    }
    var idcliente = $("#idcliente option:selected").text();
    if (idcliente == 'SELECCIONE CLIENTE') {
        alert("Tienes que seleccionar un Cliente no puede generar su orden ");
        return;
    }

    var idmarca = $("#idmarca option:selected").text();
    if (idmarca == 'SELECCIONE MARCA') {
        alert("Tienes que seleccionar una Marca no puede generar su orden ");
        return;
    }

    var idmodelo = $("#idmodelo option:selected").text();
    if (idmodelo == 'SELECCIONE MODELO') {
        alert("Tienes que seleccionar un Modelo no puede generar su orden ");
        return;
    }

    var idtipo_equipo = $("#idtipo_equipo option:selected").text();
    if (idtipo_equipo == 'SELECCIONE TIPO EQUIPO') {
        alert("Tienes que seleccionar un Tipo Equipo no puede generar su orden ");
        return;
    }


    var idcolor = $("#idcolor option:selected").text();
    if (idcolor == 'SELECCIONE COLOR') {
        alert("Tienes que seleccionar un Color no puede generar su orden ");
        return;
    }

    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formularioNuevaorden")[0]);

    console.log(formData)

    $.ajax({
        url: "../ajax/admin_ordenes.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.fire({
                title: 'Operación exitosa!',
                text: "Datos de Orden registrados correctamente",
                icon: 'success',
                timerProgressBar: true,
                willClose: () => {
                    Swal.close();
                    window.location.reload();
                }
            });
        }

    });
    limpiarNuevaOrden();
}

function mostrar(idnueva_orden) {
    $("#myModalNuevaorden").modal('show');
    $.post("../ajax/admin_ordenes.php?op=mostrar", { idnueva_orden: idnueva_orden }, function (data, status) {
        data = JSON.parse(data);



        $("#idtecnico").val(data.idtecnico);
        $('#idtecnico').selectpicker('refresh');

        $("#idcliente").val(data.idcliente);
        $('#idcliente').selectpicker('refresh');

        $("#imei_cel").val(data.imei_cel);

        $("#idmarca").val(data.idmarca);
        $('#idmarca').selectpicker('refresh');

        $("#idmodelo").val(data.idmodelo);
        $('#idmodelo').selectpicker('refresh');

        $("#idtipo_equipo").val(data.idtipo_equipo);
        $('#idtipo_equipo').selectpicker('refresh');

        $("#idcolor").val(data.idcolor);
        $('#idcolor').selectpicker('refresh');

        $("#enciende").val(data.enciende);
        $('#enciende').selectpicker('refresh');

        $("#golpes").val(data.golpes);
        $('#golpes').selectpicker('refresh');

        $("#puerto_carga").val(data.puerto_carga);
        $('#puerto_carga').selectpicker('refresh');

        $("#password_orden").val(data.password_orden);
        $("#falla_equipo").val(data.falla_equipo);
        $("#diagnostico_equipo").val(data.diagnostico_equipo);

        $("#presupuesto").val(data.presupuesto);
        $("#repuestos").val(data.repuestos);
        $("#anticipo").val(data.anticipo);
        $("#total_orden").val(data.total_orden);
        $("#codigo_ordennueva").val(data.codigo_ordennueva);


        $("#idnueva_orden").val(data.idnueva_orden);

    })
}

function cambiarestado(idnueva_orden) {
    $("#myModalCambiarestado").modal('show');
    $("#idnueva_orden_cambiar_estado").val(idnueva_orden);
    $.post("../ajax/admin_ordenes.php?op=mostrar_cambiarestado", { idnueva_orden: idnueva_orden }, function (data, status) {

        data = JSON.parse(data);
        console.log("data de cambiar estado ", data);
        $("#cambiar_estado").val(data.estado);
        $("#cambiar_estado").selectpicker('refresh');

    })
}


//Función para desactivar registros
function desactivar(idnueva_orden) {
    bootbox.confirm("¿Está Seguro de Anular la Orden?", function (result) {
        if (result) {
            $.post("../ajax/admin_ordenes.php?op=desactivar", { idnueva_orden: idnueva_orden }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}



function generarbarcode() {
    codigo = $("#codigo_ordennueva").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
}

//Función para imprimir el Código de barras
function imprimir() {
    $("#print").printArea();
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


init();