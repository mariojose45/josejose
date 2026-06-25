var tabla;

//Función que se ejecuta al inicio
function init() {
    listar();
    $("#fecha_inicio").change(listar);
    $("#fecha_fin").change(listar);
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

function renerargraficas() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

    if (!fecha_inicio || !fecha_fin) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Debe seleccionar ambas fechas.'
        });
        return;
    }

    var inicio = new Date(fecha_inicio);
    var fin = new Date(fecha_fin);

    var diferenciaDias = (fin - inicio) / (1000 * 60 * 60 * 24);

    if (diferenciaDias > 90) {
        Swal.fire({
            icon: 'error',
            title: 'Rango muy amplio',
            text: 'No puede consultar más de 90 días a la vez.'
        });
        return;
    }

    if (inicio > fin) {
        Swal.fire({
            icon: 'error',
            title: 'Rango de fechas inválido',
            text: 'La fecha de inicio no puede ser mayor que la fecha de fin.'
        });
        return;
    }

    cargarGraficas(fecha_inicio, fecha_fin);
}

function cargarGraficas(fecha_inicio, fecha_fin) {
    // Clientes Nuevos
    $.getJSON("../ajax/consultas.php?op=clientesnuevosxfecha&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin, function (data) {
        let fechas = data.map(item => item.fecha);
        let totales = data.map(item => item.total);

        let ctx1 = document.getElementById("compras_Clientes_Nuevos").getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: fechas,
                datasets: [{
                    label: '# Clientes nuevos',
                    data: totales,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });

    // Seguimientos
    $.getJSON("../ajax/consultas.php?op=seguimientosxfecha&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin, function (data) {
        let fechas = data.map(item => item.fecha);
        let totales = data.map(item => item.total);

        let ctx2 = document.getElementById("ventas_Seguimientos").getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: fechas,
                datasets: [{
                    label: '# Seguimientos',
                    data: totales,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
}

init();