var tabla;
 
//Función que se ejecuta al inicio
function init(){
    $('#MenuReportes').addClass("treeview active");
    $('#ACajaCierre').addClass("active");
    mostrarform(false);
    $.post("../ajax/usuario.php?op=selectEmpresa", function(r){
                $("#idsucursal").html(r);
                $('#idsucursal').selectpicker('refresh');
             
    });      
}
 
//Función limpiar v
function limpiar() 
{
    $("#idcuadre_caja").val("");
    $("#update_idcuadre_caja").val("");
    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora_inicio').val(today);

    $("#centavos_1").val("");
    $("#centavos_5").val("");
    $("#centavos_10").val("");
    $("#centavos_25").val("");
    $("#centavos_50").val("");
    $("#centavos_1").val("");
    $("#quetzal_5").val("");
    $("#quetzal_10").val("");
    $("#quetzal_20").val("");
    $("#quetzal_50").val("");
    $("#quetzal_100").val("");
    $("#quetzal_200").val("");
    $("#total_efectivo").val("");

    $("#total_efectivo_cierre_operaciones").val("");

    $("#operacion_efectvio").val("");
    $("#descripcion_numero_boleta").val("");
    $("#valor_operacion_efectivo").val("0");
    $("#saldo_final_cierre_caja").val("0");
}


//Función mostrar formulario
function mostrarform(flag)
{
    limpiar();
    if (flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}
 
//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
}
 
//Función Listar
function listar() 
{

    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idsucursal = $("#idsucursal").val();

    tabla=$('#tbllistado').dataTable(
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
                    url: '../ajax/cuadres_caja_cierre.php?op=listar',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idsucursal: idsucursal},
                    type : "get", 
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}
//Función para guardar o editar
 

//Función para anular registros
function anular(idcuadre_caja) {
    var valor = prompt("Ingresa la contraseña de de Autorizacion", "");
    if (valor == "5820005710") {

        bootbox.confirm("¿Está Seguro de anular el Cierre?", function (result) {
            load();
            if (result) {
                $.post("../ajax/cuadres_caja_cierre.php?op=anular", { idcuadre_caja: idcuadre_caja }, function (e) {
                    Swal.fire({
                        title: 'Mensaje!',
                        text: e,
                        icon: 'success',
                        //  timer: 2000, // 2 segundos
                        timerProgressBar: true,
                        willClose: () => {

                            Swal.close();
                            window.location.reload();
                        }
                    });

                });
            }
        })
    }
    else {
        alert("Contraseña no válida: [" + valor + "]");
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

 
init();