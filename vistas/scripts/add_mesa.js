var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })
}
 
//Función limpiar
function limpiar()
{
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#idmesa").val("");
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
                    url: '../ajax/add_mesa.php?op=listar',
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
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    load(); 
    $.ajax({
        url: "../ajax/add_mesa.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {                    
            Swal.fire({
                title: 'Mensaje!',
                text: datos,
                icon: 'success',
                timer: 2000, // 2 segundos
                timerProgressBar: true,
                willClose: () => {
                    Swal.close(); 
                    window.location.reload();
                }
            });


        }
 
    });
    limpiar();
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


function mostrar(idmesa)
{
    load(); 
    $.post("../ajax/add_mesa.php?op=mostrar",{idmesa : idmesa}, function(data, status)
    {
        Swal.close(); 
        data = JSON.parse(data);        
        mostrarform(true);
        
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#idmesa").val(data.idmesa);
 
    })
}
 
//Función para desactivar registros
function desactivar(idmesa)
{
    load();
    bootbox.confirm("¿Está Seguro de desactivar la Mesa?", function(result){
        if(result)
        {
            $.post("../ajax/add_mesa.php?op=desactivar", {idmesa : idmesa}, function(e){
                Swal.fire({
                    title: 'Mensaje!',
                    text: e,
                    icon: 'success',
                    timer: 2000, // 2 segundos
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
 
//Función para activar registros
function activar(idmesa)
{
    load();
    bootbox.confirm("¿Está Seguro de activar la Mesa?", function(result){
        if(result)
        {
            $.post("../ajax/add_mesa.php?op=activar", {idmesa : idmesa}, function(e){
                Swal.fire({
                    title: 'Mensaje!',
                    text: e,
                    icon: 'success',
                    timer: 2000, // 2 segundos
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
 
 
init();