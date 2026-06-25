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
    $("#idcompra").val("");  
    $("#idcliente_GastoaVenta").val("1");
    $("#serie_no").val("");
    
    
    $("#factura_no").val("");

    $("#mes_a_contabilizar").val("Enero");
    $("#mes_a_contabilizar").selectpicker('refresh');  

    $("#ano_contabilizar").val("2024");
    $("#ano_contabilizar").selectpicker('refresh');      

    $("#tipo_factura").val("Grabada");
    $("#tipo_factura").selectpicker('refresh'); 

    $("#tipo_documento_cliente_GastoaVenta").val("NIT");
    $("#tipo_documento_cliente_GastoaVenta").selectpicker('refresh');     

    $("#nit_no").val("CF");
    $("#proveedor").val("CONSUMIDOR FINAL");
    $("#direccion").val("CIUDAD"); 
    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);    
    
    $("#valor_q").val("0");

    $("#tipo_compra").val("Seleccion uno");
    $("#tipo_compra").selectpicker('refresh'); 


}

///////CLIENTE NUEVO DE GASTOS
    function validarnit2()  
    {


        var nit = $("#nit_no").val();

        if (nit == "") { 
            alert("Debe Colocar un nit mayor a 6 caracteres");
            return;
        } 

        $.post("../ajax/consultas.php?op=validarnit", { nit: nit }, function (data) {

            try {
                data = JSON.parse(data);


                    // Validar si nombre no es nulo, indefinido o vacío
                    if (data["receptor"] && data["receptor"]["nombre"] != null && data["receptor"]["nombre"].trim() !== "") {
                        let nombre = data["receptor"]["nombre"];
                        let direccion = data["receptor"]["direccion"] != null ? data["receptor"]["direccion"] : "CIUDAD";

                        $("#proveedor").val(nombre);
                        $("#direccion").val(direccion);

                        buscarnitenSistemaparaIdcliente2(nit);
                    } else {
                        $("#idcliente_GastoaVenta").val('0');
                        $("#tipo_documento_cliente_GastoaVenta").val("NIT");
                        $("#tipo_documento_cliente_GastoaVenta").selectpicker('refresh'); 

                        Swal.fire({
                           title: 'Mensaje!',
                           text: "Nit No Existe volver a consultar su nit o se creara como cliente nuevo",
                           icon: 'success',
                             timer: 2000, // 2 segundos
                             timerProgressBar: true
                         });

                    }
                } catch (error) {
                   Swal.fire({
                       title: 'Mensaje!',
                       text: "Error al procesar la respuesta del servidor. Intente nuevamente.",
                       icon: 'success',
                             timer: 2000, // 2 segundos
                             timerProgressBar: true
                         });                
               }
           });
    }
    function buscarnitenSistemaparaIdcliente2(nit) 
    {
        var nombre_cliente=$("#nombre_cliente").val();
        $.post("../ajax/consultas.php?op=buscarnitenSistemaparaIdcliente",{nit:nit},function(data, status){
                    //console.log(data)
                    try { 
                        data = JSON.parse(data);

                    // Verifica si el objeto data está vacío o nulo
                    if (data === null || !data.idpersona) {
                        $("#idcliente_GastoaVenta").val('0');
                        $("#tipo_documento_cliente_GastoaVenta").val("NIT");
                        $("#tipo_documento_cliente_GastoaVenta").selectpicker('refresh'); 

                        Swal.fire({
                            title: 'Mensaje!',
                            text: 'Se Creara Nuevo Cliente.',
                            icon: 'warning',
                            timer: 2000, // 2 segundos
                            timerProgressBar: true
                        });
                    } else {
                        // Si el cliente existe, llena los campos
                        $("#idcliente_GastoaVenta").val(data.idpersona);
                        $("#nit_no_GastoaVenta").val(data.num_documento);
                        $("#tipo_documento_cliente_GastoaVenta").val(data.tipo_documento);
                        $("#tipo_documento_cliente_GastoaVenta").selectpicker('refresh'); 

                    }
                } catch (error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error en la respuesta del servidor.',
                        icon: 'error',
                        timer: 2000, // 2 segundos
                        timerProgressBar: true
                    });
                }         
                
            })
    }    
///////

 
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
                    url: '../ajax/compras.php?op=listar',
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
 
    $.ajax({
        url: "../ajax/compras.php?op=guardaryeditar", 
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
              window.location.reload();
              }
              });

        }
 
    });
    limpiar();
}
 
function mostrar(idcompra)
{
    $.post("../ajax/compras.php?op=mostrar",{idcompra : idcompra}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idcompra").val(data.idcompra);
        $("#idcliente_GastoaVenta").val(data.idpersona); 
        $("#serie_no").val(data.serie_no); 
        $("#factura_no").val(data.factura_no);
        $("#mes_a_contabilizar").val(data.mes_a_contabilizar);
        $("#mes_a_contabilizar").selectpicker('refresh');
        $("#ano_contabilizar").val(data.ano_contabilizar);
        $("#ano_contabilizar").selectpicker('refresh');  
        $("#tipo_factura").val(data.tipo_factura);
        $("#tipo_factura").selectpicker('refresh');    
        $("#tipo_documento_cliente_GastoaVenta").val(data.tipo_documento);
        $("#tipo_documento_cliente_GastoaVenta").selectpicker('refresh');                        
        $("#nit_no").val(data.num_documento);
        $("#proveedor").val(data.proveedor);
        $("#direccion").val(data.proveedor_direccion);
        $("#fecha_hora").val(data.fecha);
        $("#valor_q").val(data.valor_q);
        $("#tipo_compra").val(data.tipo_compra);
        $("#tipo_compra").selectpicker('refresh'); 
        
 
    })
}
 
//Función para desactivar registros
function desactivar(idcompra) {
    Swal.fire({
        title: '¿Está Seguro de Eliminar la Compra?',
        text: "¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/compras.php?op=desactivar", { idcompra: idcompra }, function (e) {
                Swal.fire({
                    title: 'Mensaje!',
                    text: e,
                    icon: 'success',
                    timer: 2000, // 2 segundos
                    timerProgressBar: true,
                    willClose: () => {
                        window.location.reload();
                    }
                });
            });
        }
    });
}

 

// Obtener el elemento select
const selectAno = document.getElementById("ano_contabilizar");
const anoInicio = 2021; // Año de inicio
const anoActual = new Date().getFullYear(); // Año actual

// Generar las opciones del select
for (let ano = anoInicio; ano <= anoActual; ano++) {
  const option = document.createElement("option");
  option.value = ano;
  option.textContent = ano;
  selectAno.appendChild(option);
}


// Obtener el elemento select
const selectMes = document.getElementById("mes_a_contabilizar");

// Lista de nombres de los meses
const meses = [
    "Enero", "Febrero", "Marzo", "Abril", 
    "Mayo", "Junio", "Julio", "Agosto", 
    "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

// Generar las opciones del select
meses.forEach((mes, index) => {
    const option = document.createElement("option");
    option.value = mes; // El valor será el nombre del mes
    option.textContent = mes; // El texto visible será también el nombre del mes
    selectMes.appendChild(option);
});
 
 
init();