var tabla;
var chartArticulosTop = null;
var chartCompras = null;
var chartVentas = null;
var chartClientesNuevos = null;
var chartProveedoresNuevos = null;
var chartPersonasNuevas = null;
var chartVentasComp = null;

//Función que se ejecuta al inicio
function init() {
    listar();
    $("#fecha_inicio").change(listar);
    $("#fecha_fin").change(listar);

    // Cargar sucursales en el select
    $.post("../ajax/sucursal.php?op=selectSucursal", function (r) {
        var data = JSON.parse(r);
        var options = '<option value="">Todas las sucursales</option>';
        for (var i = 0; i < data.length; i++) {
            options += '<option value="' + data[i].idsucursal + '">' + data[i].nombre + '</option>';
        }
        $("#idsucursal_filtro").html(options);
        $("#idsucursal_filtro").val("").selectpicker('refresh');

    });
}

// Cuando se presione el botón generar, recargar las tablas
$("#btnGenerar").click(function () {
    var idsucursal_filtro = $("#idsucursal_filtro").val();

    cargarTotalesSuperiores(idsucursal_filtro);
    cargarGraficos(idsucursal_filtro);
    listarInventarioxSucusal(idsucursal_filtro);
    listartbllistadoVentasxSucusal(idsucursal_filtro);
    listartbllistadoVentasxSucusalMes(idsucursal_filtro);
    listartbllistadoVentasxSucusalMesGanacia(idsucursal_filtro);
    listarDetalleCuentasCobrar(idsucursal_filtro);
    listarDetalleCuentasPagar(idsucursal_filtro);
});

// Función para cargar las cajas superiores
function cargarTotalesSuperiores(idsucursal_filtro) {
    $.getJSON("../ajax/consultas.php?op=totales_cajas_superiores", { idsucursal_filtro: idsucursal_filtro }, function (data) {

        console.log(data)
        $("#lbl_totalc").text(data.totalc);
        $("#lbl_totalv").text(data.totalv);
        $("#lbl_totalvm").text(data.totalvm);
        $("#lbl_totalitem").text(data.totalitem);
        $("#lbl_totalcobrar").text(data.totalcobrar);
        $("#lbl_totalabonos").text(data.totalabonos);
        $("#lbl_totalcapital").text(data.totalcapital);
        $("#lbl_totalitem_pagar").text(data.totalitem_pagar);
        $("#lbl_totalpagar").text(data.totalpagar);
        $("#lbl_totalpagos_pagar").text(data.totalpagos_pagar);
    });
}

function cargarGraficos(idsucursal_filtro) {
    $.getJSON("../ajax/consultas.php?op=graficos_escritorio", { idsucursal_filtro: idsucursal_filtro }, function (data) {

        var bgColors = [
            'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)',
            'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'
        ];
        var borderColors = [
            'rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)',
            'rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
        ];

        if (chartArticulosTop != null) chartArticulosTop.destroy();
        var ctx1 = document.getElementById("articulostop").getContext('2d');
        chartArticulosTop = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: data.articulos_top.labels,
                datasets: [{
                    label: 'Top de 10 Articulos mas Vendidos General',
                    data: data.articulos_top.data,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: { scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
        });

        if (chartCompras != null) chartCompras.destroy();
        var ctx2 = document.getElementById("compras").getContext('2d');
        chartCompras = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: data.compras.labels,
                datasets: [{
                    label: '# Compras en Q/ de los últimos 10 días',
                    data: data.compras.data,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: { scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
        });

        if (chartVentas != null) chartVentas.destroy();
        var ctx3 = document.getElementById("ventas").getContext('2d');
        chartVentas = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: data.ventas.labels,
                datasets: [{
                    label: '# Ventas en Q/ de los últimos 12 meses',
                    data: data.ventas.data,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: { scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
        });

        if (chartClientesNuevos != null) chartClientesNuevos.destroy();
        var ctx4 = document.getElementById("integracionClientesnuevos12meses").getContext('2d');
        chartClientesNuevos = new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: data.clientes_nuevos.labels,
                datasets: [{
                    label: '# Clientes nuevos últimos 12 meses',
                    data: data.clientes_nuevos.data,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: { scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
        });

        if (chartProveedoresNuevos != null) chartProveedoresNuevos.destroy();
        var ctx5 = document.getElementById("integracionProveedornuevos12meses").getContext('2d');
        chartProveedoresNuevos = new Chart(ctx5, {
            type: 'bar',
            data: {
                labels: data.proveedores_nuevos.labels,
                datasets: [{
                    label: '# Proveedor nuevos últimos 12 meses',
                    data: data.proveedores_nuevos.data,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: { scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
        });

        if (chartPersonasNuevas != null) chartPersonasNuevas.destroy();
        var ctx6 = document.getElementById("integracionPersonanuevos12meses").getContext('2d');
        chartPersonasNuevas = new Chart(ctx6, {
            type: 'bar',
            data: {
                labels: data.personas_nuevas.labels,
                datasets: [{
                    label: '# Proveedor/Cliente nuevos últimos 12 meses',
                    data: data.personas_nuevas.data,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: { scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
        });

        if (typeof chartVentasComp !== 'undefined' && chartVentasComp != null) chartVentasComp.destroy();
        var ctx7 = document.getElementById("ventascomparativas");
        if (ctx7) {
            chartVentasComp = new Chart(ctx7.getContext('2d'), {
                type: 'line', 
                data: {
                    labels: data.ventas_comparativas.labels,
                    datasets: [
                        {
                            label: data.ventas_comparativas.label_current,
                            data: data.ventas_comparativas.data_current,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true
                        },
                        {
                            label: data.ventas_comparativas.label_prev,
                            data: data.ventas_comparativas.data_prev,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            fill: true
                        }
                    ]
                },
                options: { 
                    scales: { 
                        yAxes: [{ ticks: { beginAtZero: true } }] 
                    },
                    elements: {
                        line: {
                            tension: 0.4 
                        }
                    }
                }
            });
        }
    });
}

//Función Listar 
function listar() {
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
                url: '../ajax/consultas.php?op=ventasxfechaxmes',
                data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
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

function listarInventarioxSucusal(idsucursal_filtro) {


    tabla = $('#tbllistadoInventarioxSucusal').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/consultas.php?op=InventarioxSucusal',
                type: "get",
                data: { idsucursal_filtro: idsucursal_filtro },
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


function listartbllistadoVentasxSucusal(idsucursal_filtro) {


    tabla = $('#tbllistadoVentasxSucusal').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/consultas.php?op=VentasxSucusal',
                type: "get",
                data: { idsucursal_filtro: idsucursal_filtro },
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


function listartbllistadoVentasxSucusalMes(idsucursal_filtro) {


    tabla = $('#tbllistadoVentasxSucusalMes').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/consultas.php?op=VentasxSucusalMes',
                type: "get",
                data: { idsucursal_filtro: idsucursal_filtro },
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


function listartbllistadoVentasxSucusalMesGanacia(idsucursal_filtro) {


    tabla = $('#tbllistadoVentasxSucusalMesGanacia').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla 
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/consultas.php?op=VentasxSucusalMesGanacia',
                type: "get",
                data: { idsucursal_filtro: idsucursal_filtro },
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
                    .column(2)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total3 = api
                    .column(3)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                total4 = api
                    .column(4)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);


                // Total over this page
                pageTotal = api
                    .column(2, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal3 = api
                    .column(3, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTotal4 = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);



                // Update footer
                $(api.column(2).footer(0)).html(
                    pageTotal.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(3).footer(0)).html(
                    pageTotal3.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );

                $(api.column(4).footer(0)).html(
                    pageTotal4.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')// +' ( $'+ total.toFixed(2) +' total)'
                );


            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[1, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}

function listarDetalleCuentasCobrar(idsucursal_filtro) {
    tabla = $('#tblDetalleCuentasCobrar').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/consultas.php?op=listarDetalleCtasporCobrar',
            type: "get",
            data: { idsucursal_filtro: idsucursal_filtro },
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    }).DataTable();
}

function listarDetalleCuentasPagar(idsucursal_filtro) {
    tabla_cuentaspagar = $('#tbldetalle_cuentaspagar').dataTable({
        "aProcessing": true, // Activamos el procesamiento del datatables
        "aServerSide": true, // Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', // Definimos los elementos del control de tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/consultas.php?op=listarDetalleCtasporPagar',
            type: "get",
            data: { idsucursal_filtro: idsucursal_filtro },
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    }).DataTable();
}

init();