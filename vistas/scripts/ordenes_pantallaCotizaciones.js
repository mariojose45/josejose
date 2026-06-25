var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    /*$("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })*/
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
        $("#btnTour").hide();
    }
    else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnTour").show();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función Listar
function procesarDetalles(detalles) {
    const articulosPadre = [];
    const padresMap = new Map();

    detalles.forEach(det => {
        if (det.idarticulopadre == 0) {
            const padre = {
                idarticulo: det.idarticulo,
                cantidad: det.cantidad,
                nombre_articulo: det.nombre_articulo,
                descripcion_detalle: det.descripcion_detalle,
                hijos: []
            };
            articulosPadre.push(padre);
            padresMap.set(padre.idarticulo, padre);
        }
    });

    detalles.forEach(det => {
        if (det.idarticulopadre > 0) {
            const idPadre = det.idarticulopadre;
            const padreEncontrado = padresMap.get(idPadre);

            if (padreEncontrado) {
                padreEncontrado.hijos.push(det);
            }
        }
    });

    return articulosPadre;
}


function listar() {
    let fecha_inicio = $("#fecha_inicio_reporte").val();
    let fecha_fin = $("#fecha_fin_reporte").val();
    let tipo_envioPedidos = $("#tipo_envioPedidos").val();

    $.ajax({
        url: "../ajax/cotizaciones.php?op=listarpantallacotizaciones",
        type: "GET",
        data: { fecha_inicio, fecha_fin, tipo_envioPedidos },
        success: function (response) {
            let data;
            try {
                data = JSON.parse(response);
            } catch (e) {
                console.error("Error al parsear la respuesta JSON:", e);
                $("#contenedor-ordenes").html('<div class="col-12"><p class="text-danger">Error al cargar datos. Revise el log.</p></div>');
                return;
            }

            // console.log("Data de productos procesada: ", data);

            let html = "";

            data.forEach(item => {
                // LLamada a la función corregida
                const detallesProcesados = procesarDetalles(item.detalle);

                html += `
                <div class="col-md-3 col-sm-6">
                    <div class="orden-card card mb-2">
                        <div class="orden-header card-header bg-light p-2">
                            <small><b>Cotización #${item.idcotizacion}</b></small>
                        </div>
                        <div class="orden-body card-body p-2">
                            <p style="margin:0; font-size:12px;"><b>Fecha:</b> ${item.fecha}</p>
                            <p style="margin:0; font-size:12px;"><b>Cliente:</b> ${item.nombre}</p>
                            <p style="margin:0; font-size:12px;"><b>Dirección:</b> ${item.direccion}</p>
                            <p style="margin:0; font-size:12px;"><b>Teléfono:</b> ${item.telefono}</p>
                        </div>
                        <table class="table table-bordered table-sm" style="font-size:12px; margin-bottom:5px;">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Cant</th>
                                    <th>Desc</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${detallesProcesados.map(padre => {
                    let filaPadre = `
                                            <tr>
                                                <td>${padre.cantidad}</td>
                                                <td>
                                                    <strong>${padre.nombre_articulo}</strong> 
                                                    <small class="text-muted">${padre.descripcion_detalle ? `(${padre.descripcion_detalle})` : ''}</small>
                                                </td>
                                            </tr>
                                        `;

                    let filasHijas = padre.hijos.map(hijo => `
                                            <tr>
                                                <td></td> 
                                                <td style="padding-left: 20px;">
                                                    <span class="text-secondary">↳ ${hijo.nombre_articulo}</span>
                                                    <span class="badge badge-info float-right" style="font-size: 80%;">${hijo.tipo.toUpperCase()}</span>
                                                </td>
                                            </tr>
                                        `).join("");

                    return filaPadre + filasHijas;
                }).join("")}
                            </tbody>
                        </table>
                        <div class="card-footer text-center">
                            <button class="btn btn-success btn-block btn-listo" 
                                onclick="listo(${item.idcotizacion}, this)">
                                <i class="fa fa-check-circle"></i> Listo
                            </button>
                        </div>
                    </div>
                </div>`;
            });

            $("#contenedor-ordenes").html(html);
        }
    });
}

setInterval(function () {
    listar();
}, 5000);

//Función para desactivar registros
function listo(idcotizacion) {
    bootbox.confirm("¿Está Seguro de marcar como listo tu pedido?", function (result) {
        if (result) {
            $.post("../ajax/cotizaciones.php?op=listo", { idcotizacion: idcotizacion }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para guardar o editar

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/categoria.php?op=guardaryeditar",
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
    limpiar();
}

function mostrar(idcategoria) {
    $.post("../ajax/categoria.php?op=mostrar", { idcategoria: idcategoria }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#tipo_descuento").val(data.tipo_descuento);
        $('#tipo_descuento').selectpicker('refresh');
        $("#valor_descuento").val(data.valor_descuento);
        $("#idcategoria").val(data.idcategoria);

    })
}

//Función para desactivar registros
function desactivar(idcategoria) {
    bootbox.confirm("¿Está Seguro de desactivar la Categoría?", function (result) {
        if (result) {
            $.post("../ajax/categoria.php?op=desactivar", { idcategoria: idcategoria }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idcategoria) {
    bootbox.confirm("¿Está Seguro de activar la Categoría?", function (result) {
        if (result) {
            $.post("../ajax/categoria.php?op=activar", { idcategoria: idcategoria }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

function iniciarTour() {
    const driver = window.driver.js.driver;

    const tour = driver({
        showProgress: true,   // muestra el progreso (paso 1 de X)
        showButtons: ['next', 'previous', 'close'], // botones visibles
        steps: [
            {
                element: '#btnagregar',
                popover: {
                    title: 'Agregar categoría',
                    description: 'Haz clic aquí para registrar una nueva categoría en el sistema.',
                    side: 'bottom',
                    align: 'start'
                }
            },
            {
                element: '#tbllistado',
                popover: {
                    title: 'Listado de categorías',
                    description: 'Aquí verás todas las categorías registradas.' +
                        'Tambien tiene 2 botons uno de color amarrillo que te permite editar la fila y otro de otro rojo que te permite desactivar la fila.' +
                        'en esta misma tabla cuentas con un caja de texto que te permite buscar una categoría por su nombre.' +
                        'y cada una de las filas las puedes order de forma ascendente o descendente.' +
                        'sin olvidar el paginador que te permite navegar entre las paginas.',
                    side: 'top'
                }
            },
            {
                element: '#btnCopy',
                popover: {
                    title: 'Copiar',
                    description: 'Este botón copia los datos visibles de la tabla al portapapeles.',
                    side: 'bottom'
                }
            },
            {
                element: '#btnExcel',
                popover: {
                    title: 'Exportar a Excel',
                    description: 'Descarga la información de la tabla en formato Excel (.xlsx).',
                    side: 'bottom'
                }
            },
            {
                element: '#btnCsv',
                popover: {
                    title: 'Exportar a CSV',
                    description: 'Descarga la información en formato CSV, útil para abrir en Excel o Google Sheets.',
                    side: 'bottom'
                }
            },
            {
                element: '#btnPdf',
                popover: {
                    title: 'Exportar a PDF',
                    description: 'Genera un reporte en PDF en orientación horizontal y tamaño oficio.',
                    side: 'bottom'
                }
            },
            {
                element: '#btnColvis',
                popover: {
                    title: 'Visibilidad de columnas',
                    description: 'Permite elegir qué columnas deseas mostrar u ocultar en la tabla y exportaciones.',
                    side: 'bottom'
                }
            }
        ]
    });

    tour.drive();
}

// Vincular botón de ayuda con el tour
$("#btnTour").on("click", function () {
    iniciarTour();
});

function iniciarTourFormulario() {
    const driver = window.driver.js.driver;

    const tour = driver({
        showProgress: true,   // muestra el progreso (paso 1 de X)
        showButtons: ['next', 'previous', 'close'], // botones visibles
        steps: [

            {
                element: '#formulario',
                popover: {
                    title: 'Formulario',
                    description: 'Nota: categoría es una forma de agrupar cosas que comparten características parecidas. Ejemplos fáciles: En una tienda: Ropa, Zapatos, Electrónica → cada una es una categoría. En tu sistema: una categoría sirve para ordenar productos o servicios, de modo que sea más fácil buscarlos y administrarlos. Completa los campos para registrar o editar una categoría.',
                    side: 'top'
                }
            },
            {
                element: '#formulario',
                popover: {
                    title: 'Formulario',
                    description: 'Completa los campos para registrar o editar una categoría.',
                    side: 'top'
                }
            },
            {
                element: '#valor_descuento',
                popover: {
                    title: 'Descuento x Categoria',
                    description: 'Este descuento sirve para aplicar un descuento de forma general a todos los productos de la categoría, tomando en cuenta el tipo de descuento que se seleccione.',
                    side: 'top'
                }
            },
            {
                element: '#btnGuardar',
                popover: {
                    title: 'Guardar',
                    description: 'Haz clic aquí para guardar los cambios.',
                    side: 'top'
                }
            },
            {
                element: '#btnCancelar',
                popover: {
                    title: 'Cancelar',
                    description: 'Haz clic aquí para cancelar los cambios.',
                    side: 'top'
                }
            }
        ]
    });

    tour.drive();
}

// Vincular botón de ayuda con el tour
$("#btnHelpFloating").on("click", function () {
    iniciarTourFormulario();
});

init();