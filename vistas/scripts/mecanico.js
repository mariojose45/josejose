var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);

    $("#btnGuardarCambios").click(function (e) {
        guardaryeditar(e);
    });
}

//Función limpiar
function limpiar() {
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#idcategoria").val("");
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
                url: '../ajax/mecanico.php?op=listar',
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte },
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


function addDetalles(idingreso_vehiculo) {
    $('#detalles tbody').empty();
    //$('#tblRevisiones tbody').empty();
    $("#idingreso_vehiculo").val(idingreso_vehiculo);
    cargarDatos(idingreso_vehiculo);
    listarArticulos();

    //CARGAR DATOS YA REGISTRADOS
    $.ajax({
        url: "../ajax/mecanico.php?op=mostrar&idingreso_vehiculo=" + idingreso_vehiculo,
        type: "GET",
        dataType: "html",
        success: function (respuesta) {
            //console.log("respuesta ", respuesta);
            $("#contenido_tab_4").html(respuesta);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error al cargar los datos:", textStatus, errorThrown);
            $("#contenido_tab_4").html("<p>Hubo un error al cargar los datos del vehículo. Intente de nuevo más tarde.</p>");
        }
    });
    $('#modalActualizarAsistencia').modal('show');
}

function cerrarModal() {
    $('#modalDetalles').hide();
}

function listarArticulos() {

    tabla = $('#tblarticulos_detalles').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/venta.php?op=listarArticulosMecanico',
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

var impuesto = 12;
var cont = 0;
var detalles = 0;

function agregarDetalle(idarticulo, nombre, precio_venta, stock, descuento_porcentaje, stock_unidad, precio_unidad,
    stock_blister, precio_blister, stock_caja, precio_caja, stock_fardo, precio_fardo, stock_sacos, precio_sacos,
    stock_paquete, precio_paquete, precio_rango1, precio_rango2, precio_rango3, precio_compra, precio_activado, precio_rango1_Dos, precio_rango2_Dos, precio_rango3_Dos,
    precio_rango1_Mecanico, precio_rango2_MecanicoDos, precio_rango3_MecanicoTres, precio_rango1_Distribuidor,
    precio_rango2_DistribuidorDos, precio_rango3_DistribuidorTres, precio_rango1_Mayorista,
    precio_rango2_MayoristaDos, precio_rango3_MayoristaTres) {

    console.log("precio_activado" + precio_activado)

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
                '<td><input style="width:60px" class="form-control"  type="number" step="any"   id="cxcantidad' + idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '"></td>' +
                '</tr>';
            cont++;
            detalles = detalles + 1;
            $(fila).prependTo('#detalles');
        } else {
        }
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
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


function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    detalles = detalles - 1;
}

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento

    var detalles = [];
    $("tr.filas").each(function () {
        var fila = $(this);
        detalles.push({
            idarticulo: fila.find("input[name='idarticulo[]']").val(),
            cantidad: fila.find("input[name='cantidad[]']").val()
        });
    });

    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    formData.append("detalles_json", JSON.stringify(detalles));
    console.log("detalles ", detalles);

    //
    var revisiones = [];
    $("#tblRevisiones tbody tr").each(function () {
        var fila = $(this);
        var idRevision = fila.data("id");
        var nombre = fila.find('td:first').text().trim();
        var estados = {
            '100': false,
            '75': false,
            '50': false,
            'sugerido': false
        };

        fila.find("td input[type='checkbox']").each(function (index) {
            var estadoKey;
            switch (index) {
                case 0: estadoKey = '100'; break;
                case 1: estadoKey = '75'; break;
                case 2: estadoKey = '50'; break;
                case 3: estadoKey = 'sugerido'; break;
            }
            if ($(this).is(':checked')) {
                estados[estadoKey] = true;
            }
        });
        revisiones.push({
            id: idRevision,
            nombre: nombre,
            estado100: estados['100'],
            estado75: estados['75'],
            estado50: estados['50'],
            cambioSugerido: estados['sugerido']
        });
    });

    formData.append("revisiones_json", JSON.stringify(revisiones));
    console.log("Datos de revisiones a enviar: ", revisiones);

    //demas datos
    formData.append("idingreso_vehiculo", $("#idingreso_vehiculo").val());
    formData.append("horaInicio", $("#horaInicio").val());
    formData.append("horaFinalizada", $("#horaFinalizada").val());
    formData.append("tecnico", $("#tecnico").val());
    formData.append("gradoAceite", $("#gradoAceite").val());
    formData.append("filtroAceite", $("#filtroAceite").val());
    formData.append("filtroAire", $("#filtroAire").val());
    formData.append("filtroCombustible", $("#filtroCombustible").val());
    formData.append("observaciones", $("#observaciones").val());

    $.ajax({
        url: "../ajax/mecanico.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log("datos ", datos);
            Swal.fire({
                title: 'Mensaje!',
                text: "Datos registrador correctamente",
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


function cargarDatos(idingreso_vehiculo) {
    $.ajax({
        url: "../ajax/mecanico.php?op=mostrar_detalles",
        type: "POST",
        data: { idingreso_vehiculo: idingreso_vehiculo },
        success: function (response) {
            response = JSON.parse(response);
            console.log("mostrar detalles respuesta ", response);
            if (response.datos) {
                //console.log("response.datos ",response.datos);
                $("#horaInicio").val(response.datos.horaInicio);
                $("#horaFinalizada").val(response.datos.horaFinalizada);
                $("#tecnico").val(response.datos.tecnico);
                $("#gradoAceite").val(response.datos.gradoAceite);
                $("#filtroAceite").val(response.datos.filtroAceite);
                $("#filtroAire").val(response.datos.filtroAire);
                $("#filtroCombustible").val(response.datos.filtroCombustible);
                $("#observaciones").val(response.datos.observaciones);
            }


            if (response.detalles && response.detalles.length > 0) {
                console.log("response.detalles ", response.detalles);
                response.detalles.forEach(function (item) {
                    var fila = '<tr class="filas" data-id="' + item.idarticulo + '" id="fila' + cont + '">' +
                        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                        '<td><input type="hidden" name="idarticulo[]" value="' + item.idarticulo + '">' + item.nombre + '</td>' +
                        '<td><input style="width:60px" class="form-control"  type="number" step="any"   id="cxcantidad' + item.idarticulo + '" name="cantidad[]" id="cantidad' + cont + '" value="' + item.cantidad + '"></td>' +
                        '</tr>';
                    $('#detalles').append(fila);
                });
            } else {

            }


            if (response.revisiones && response.revisiones.length > 0) {
                $('#tblRevisiones tbody').empty();
                response.revisiones.forEach(function (item) {
                    var fila_revision = '<tr data-id="' + item.iddetalle_revision_vehiculo + '">' +
                        '<td>' + item.nombre_revision + '</td>' +
                        '<td><input type="checkbox" class="form-check-input check-revisionVehiculo"' + (item.estado_100 == 1 ? ' checked' : '') + '></td>' +
                        '<td><input type="checkbox" class="form-check-input check-revisionVehiculo"' + (item.estado_75 == 1 ? ' checked' : '') + '></td>' +
                        '<td><input type="checkbox" class="form-check-input check-revisionVehiculo"' + (item.estado_50 == 1 ? ' checked' : '') + '></td>' +
                        '<td><input type="checkbox" class="form-check-input check-revisionVehiculo"' + (item.cambio_sugerido == 1 ? ' checked' : '') + '></td>' +
                        '</tr>';
                    $('#tblRevisiones tbody').append(fila_revision);
                });
            } else {

            }

        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los datos:", error);
        }
    });
}

init();