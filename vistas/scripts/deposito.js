var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })
    //Cargamos los items al select proveedor
    $.post("../ajax/deposito.php?op=selectCliente", function(r){
                $("#idcliente").html(r);
                $('#idcliente').selectpicker('refresh');
                
    });      
    //Cargamos los items al select proveedor
    $.post("../ajax/pagos_empleados.php?op=selectBanco", function(r){
                $("#idcuenta").html(r); 
                $("#idcuenta23").html(r);
                $('#idcuenta').selectpicker('refresh'); 
                
    });   

    $('#idcuenta').change(function()
    {   
         $("#idcuenta23").val($(this).val()) 
         var ressaldo_cuenta=$("#idcuenta23 option:selected").attr("data-saldo_cuenta");

         $("#saldo_cuenta").val(ressaldo_cuenta);

    });        
}  
 
//Función limpiar
function limpiar()
{
    $("#idcliente").val(""); 
    $("#idcuenta").val("");
    //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);
    //Marcamos el primer tipo_documento
    $("#tipo_banco").val("Seleccion Banco");
    $("#tipo_banco").selectpicker('refresh');  

    $("#nombre_agencia").val("");
    $("#deposito_no").val("");
    $("#valor_deposito").val("");
    $("#descripcion").val("");
    $("#saldo_cuenta").val("");


    $("#iddeposito").val("");
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
                    url: '../ajax/deposito.php?op=listar',
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
        url: "../ajax/deposito.php?op=guardaryeditar", 
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {                    
              bootbox.alert(datos);           
              mostrarform(false);
              //tabla.ajax.reload();
              location.reload();
        }
 
    });
    limpiar();
}
 

 
//Función para desactivar registros
function desactivar(iddeposito)
{
    bootbox.confirm("¿Está Seguro de desactivar la Categoría?", function(result){
        if(result)
        {
            $.post("../ajax/deposito.php?op=desactivar", {iddeposito : iddeposito}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 

 
init();