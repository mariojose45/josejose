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
    $("#cta_cod").val("");
    $("#cta_nombre").val("");
    $("#num_cta").val("");
    $("#descripcion").val("");
    $("#saldo_inicial").val("");
    //Marcamos el primer tipo_documento
    $("#tipo_banco").val("Seleccion Banco");
    $("#tipo_banco").selectpicker('refresh');   
    $("#idcuenta").val(""); 
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
                    url: '../ajax/cta_bancaria.php?op=listar',
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
        url: "../ajax/cta_bancaria.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {                    
              bootbox.alert(datos);           
              mostrarform(false);
              tabla.ajax.reload();
        }
 
    });
    limpiar();
}
 
function mostrar(idcuenta)
{
    $.post("../ajax/cta_bancaria.php?op=mostrar",{idcuenta : idcuenta}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#cta_cod").val(data.cta_cod);
        $("#cta_nombre").val(data.cta_nombre);
        $("#num_cta").val(data.num_cta);
        $("#descripcion").val(data.descripcion);
        $("#saldo_inicial").val(data.saldo_inicial);
        $("#tipo_banco").val(data.tipo_banco);
        $("#tipo_banco").selectpicker('refresh'); 
        $("#idcuenta").val(data.idcuenta);
 
    })
}
 
//Función para desactivar registros
function desactivar(idcuenta)
{
    bootbox.confirm("¿Está Seguro de desactivar la Cuenta Bancaria?", function(result){
        if(result)
        {
            $.post("../ajax/cta_bancaria.php?op=desactivar", {idcuenta : idcuenta}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Función para activar registros
function activar(idcuenta)
{
    bootbox.confirm("¿Está Seguro de activar la Cuenta Bancaria?", function(result){
        if(result)
        {
            $.post("../ajax/cta_bancaria.php?op=activar", {idcuenta : idcuenta}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
 
init();