var tabla;

//Función que se ejecuta al inicio
function init() {
    listar();
    $.post("../ajax/usuario.php?op=selectEmpresa", function(r){
        // Agregamos manualmente la opción de TODAS las sucursales al inicio
        var opcionTodas = '<option value="0" selected>🌎 General (Todas)</option>';
        $("#idsucursal").html(opcionTodas + r);
        $('#idsucursal').selectpicker('refresh');
    });    


}




var chartLecturas = null;
var chartCobros = null;
var tablaUsuarios = null;

//Función Listar para actualizar las tarjetas y gráficas
function listar() {
    load();
    var idsucursal = $("#idsucursal").val();
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

    // Hacemos una petición AJAX para obtener los totales
    $.post("../ajax/parqueo_Operaciones.php?op=totalesDashboardParqueo", { idsucursal: idsucursal, fecha_inicio: fecha_inicio, 
        fecha_fin: fecha_fin }, function(data, status) {
        data = JSON.parse(data);

        // Actualizamos cada tarjeta independiente con su respectivo valor
        $("#ingresos").html("Q/ " + data.ingresos);
        $("#vehiculos").html(data.vehiculos);
        $("#ocupacion").html(data.ocupacion);
        $("#tiempo").html(data.tiempo);
    });

    // 📊 LECTURAS Petición AJAX
    $.post("../ajax/parqueo_Operaciones.php?op=graficoLecturasParqueo", { idsucursal: idsucursal, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(data, status) {
        data = JSON.parse(data);
        if(chartLecturas) { chartLecturas.destroy(); }
        chartLecturas = new Chart(document.getElementById("graficaLecturas"), {
            type: 'line',
            data: {
                labels: data.fechas,
                datasets: [{
                    label: 'Lecturas Pendientes por Fecha',
                    data: data.totales,
                    borderWidth: 3,
                    borderColor: '#f39c12',
                    backgroundColor: 'rgba(243, 156, 18, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            }
        });
    });

    // 💰 COBROS Petición AJAX
    $.post("../ajax/parqueo_Operaciones.php?op=graficoCobrosParqueo", { idsucursal: idsucursal, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(data, status) {
        data = JSON.parse(data);
        if(chartCobros) { chartCobros.destroy(); }
        chartCobros = new Chart(document.getElementById("graficaCobros"), {
            type: 'bar',
            data: {
                labels: data.fechas,
                datasets: [{
                    label: 'Total de Cobrado (Q/.) por Fecha',
                    data: data.totales,
                    backgroundColor: '#00a65a',
                    borderWidth: 1
                }]
            }
        });
        
        // Inicializar DataTable
        tablaUsuarios = $('#tblTotalesUsuarios').dataTable(
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
                    url: '../ajax/parqueo_Operaciones.php?op=listarTotalUsuariosParqueo',
                    data: { idsucursal: idsucursal, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
                    type: "get",
                    dataType: "json",
                    error: function (e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 20,//Paginación
                "order": [[2, "desc"]]//Ordenar (columna,orden)
            }).DataTable();

        Swal.close();
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