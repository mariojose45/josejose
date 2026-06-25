var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
    //Cargamos los items al select cliente
    $.post("../ajax/salida_pro_sucursal.php?op=selectSucursal", function (r) {
        $("#idsucursal").html(r);
        $('#idsucursal').selectpicker('refresh');
    });
    var idCargado = null;
    $("#btncargar").click(function () {

        var idtraladosucursal = $("#idtraladosucursal").val();
        if (idtraladosucursal == "") {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Debe Colocar un Id de Cotizacion Valido",
                showConfirmButton: false,
                timer: 1500,
            }); 
           // alert("Debe Colocar un Id de Cotizacion Valido")
            return;
        }
            // Verifica si el ID ya fue cargado
        if (idCargado === idtraladosucursal) {
            //alert("Ya se ha cargado la información para este ID.");
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Ya se ha cargado la información para este ID.",
                showConfirmButton: false,
                timer: 1500,
            });            
            return;
        }
        // Actualiza la variable de control
        idCargado = idtraladosucursal;
        obtenercabezeratrasladosucursal(idtraladosucursal);
    });
}

function obtenercabezeratrasladosucursal(traladosucursal) {
    $.post("../ajax/entrada_pro_sucursal.php?op=mostrar", { idtraladosucursal: traladosucursal }, function (data) {
        // Parsear la respuesta
        data = JSON.parse(data);

        // Validar si la respuesta es nula o vacía
        if (!data || data === null || Object.keys(data).length === 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "La operación fue anulada, o no se puede transferir a la sucursal actual",
                showConfirmButton: false,
                timer: 1500,
            });

            // Salir de la función
            return;
        }

        // Si los datos son válidos, rellenar los campos
        $("#nombresucursaldestino").val(data.nombresucursaldestino);
        $("#idsucursaldestino").val(data.idsucursaldestino);

        $("#fecha_hora").val(data.fecha);
        $("#idsucursalorigen").val(data.idsucursalorigen);
        $("#descripcion_salida_producto").val(data.descripcion_salida_producto);

        // Llamar a la función detalle
        obtenercabezeratrasladosucursaldetalle(traladosucursal);
    });
}


function obtenercabezeratrasladosucursaldetalle(traladosucursal) {
    $.post("../ajax/entrada_pro_sucursal.php?op=paratrasladodetalle", { idtraladosucursal: traladosucursal }, function (data) {
        //console.log(data);
        data = JSON.parse(data);

        $.each(data, function (i, item) {
            // console.log(item);
            agregarDetalle(item.idarticulo, item.articulo, item.cantidad, item.descripcion_detalle, item.precio_venta,
                item.cantidadpresentacion, item.totalcantidadpresentacion, item.presentacion, item.descripcion
            );
        });
    })
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
    $("#nombresucursaldestino").val("");


}

//Función mostrar formulario
function mostrarform(flag) {
    //limpiar();
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
}

//Función Listar
function listar() {
    tabla = $('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> <strong> Exportar a Excel</strong>',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-success btn-sm'
                }
            ],
            "ajax":
            {
                url: '../ajax/entrada_pro_sucursal.php?op=listar',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "language": {
                "lengthMenu": "Mostrar : _MENU_ registros",
                "buttons": {
                    "copyTitle": "Tabla Copiada",
                    "copySuccess": {
                        _: '%d líneas copiadas',
                        1: '1 línea copiada'
                    }
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
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

            ],
            "ajax":
            {
                url: '../ajax/salida_pro_sucursal.php?op=listarArticulosVenta',
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

function agruparDatos() {
    const datos = {
        articulos: {
            idarticulo: [],
            cantidadpresentacion: [],
            cantidad: [],
            totalcantidadpresentacion: [],
            presentacion: [],
            descripcion_detalle: [],
            precio_venta: [],
            fecha_vencimiento: []
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
        const fecha_vencimiento = $(this).find('input[name="fecha_vencimiento[]"]').val();

        datos.articulos.idarticulo.push(idarticulo);
        datos.articulos.cantidadpresentacion.push(cantidadpresentacion);
        datos.articulos.cantidad.push(cantidad);
        datos.articulos.totalcantidadpresentacion.push(totalcantidadpresentacion);
        datos.articulos.presentacion.push(presentacion);
        datos.articulos.descripcion_detalle.push(descripcion_detalle);
        datos.articulos.precio_venta.push(precio_venta);
        datos.articulos.fecha_vencimiento.push(fecha_vencimiento);
    });

    const datosJSON = JSON.stringify(datos);

    localStorage.setItem('datosArticulosE', datosJSON);
    //console.log(datosJSON);
}
function guardaryeditar(e) {

    // como vdalido que mi fecha de vencimiento no balla en blanco 
    var fechaVencimiento = $("#fecha_vencimiento").val();
    if (fechaVencimiento == "") {
        Swal.fire({
            title: 'Error!',
            text: 'La fecha de vencimiento no puede estar en blanco',
            icon: 'error',
            timer: 2000, // 2 segundos
            timerProgressBar: true,
            willClose: () => {
                window.location.reload();
            }
        });
        return;
    }
    agruparDatos();
    let datosArticulosE = JSON.parse(localStorage.getItem('datosArticulosE'));
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    formData.append("datosArticulosE", JSON.stringify(datosArticulosE));
    load();
    $.ajax({
        url: "../ajax/entrada_pro_sucursal.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            Swal.close();
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
    bootbox.confirm("¿Está Seguro de anular la venta?", function (result) {
        if (result) {
            $.post("../ajax/salida_pro_sucursal.php?op=anular", { idventa: idventa }, function (e) {
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



function agregarDetalle(idarticulo, articulo, cantidad, descripcion_detalle, precio_venta, cantidadpresentacion, totalcantidadpresentacion, presentacion, descripcion) {


    if (idarticulo != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idarticulo[]" style="width:100px" value="' + idarticulo + '">' + articulo + ' ' + descripcion + '</td>' +
            '<td><input type="number" name="cantidad[]" class="form-control" style="width:100px" id="cantidad[]" value="' + cantidad + '" readonly></td>' +
            '<td><input type="text" name="presentacion[]" class="form-control" style="width:100px" id="presentacion[]" value="' + presentacion + '" readonly><input type="hidden" name="cantidadpresentacion[]" style="width:100px" value="' + cantidadpresentacion + '"><input type="hidden" name="totalcantidadpresentacion[]" style="width:100px" value="' + totalcantidadpresentacion + '"></td>' +
            '<td><input  type="text"   name="descripcion_detalle[]" class="form-control"  style="width:100px" id="descripcion_detalle[]" value="' + descripcion_detalle + '" ></td>' +
            '<td><input  type="text"   name="precio_venta[]" class="form-control" id="precio_venta[]" style="width:100px" value="' + precio_venta + '" readonly></td>' +
            '<td><input  type="date"   name="fecha_vencimiento[]" class="form-control" id="fecha_vencimiento[]" style="width:150px"  ></td>' +
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

    calcularTotales();

}
function calcularTotales() {

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