var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $('#Menucuentasxpagar').addClass("treeview active");
    $('#generarCuentas').addClass("active");
 
    $("#formulario").on("submit",function(e) 
    {
        guardaryeditar(e);  
    });

    //Cargamos los items al select proveedor
    $.post("../ajax/cuentasporpagar.php?op=selectBanco", function(r){
                $("#idcuenta").html(r);
                $('#idcuenta').selectpicker('refresh');    
                
    });       
} 
 
 
$("#tipo_pago").change(mostrarFormaPago);   
 
function mostrarFormaPago() 
  {
    var tipo_pago=$("#tipo_pago option:selected").text();  
    if (tipo_pago=='Efectivo')
    {
        $("#tipo_bancodiv").hide(); 
        $("#numero_boletadiv").hide();
        $("#recibo_caja_numerodiv").hide(); 
        $("#idcuentadiv").hide(); 
        $("#no_chequediv").hide(); 
        $("#desp_chequediv").hide();   
        
    }
    else if (tipo_pago=='Cheque')
    {
        $("#tipo_bancodiv").hide(); 
        $("#numero_boletadiv").hide();
        $("#recibo_caja_numerodiv").show(); 
        $("#idcuentadiv").show(); 
        $("#no_chequediv").show(); 
        $("#desp_chequediv").show();   

    }
    else if (tipo_pago=='Transferencia')
    {
        $("#tipo_bancodiv").show(); 
        $("#numero_boletadiv").show();
        $("#recibo_caja_numerodiv").show(); 
        $("#idcuentadiv").show(); 
        $("#no_chequediv").hide(); 
        $("#desp_chequediv").hide();   

    }
    else  if (tipo_pago=='Deposito')
    {
        $("#tipo_bancodiv").show(); 
        $("#numero_boletadiv").show();
        $("#recibo_caja_numerodiv").show(); 
        $("#idcuentadiv").show(); 
        $("#no_chequediv").hide(); 
        $("#desp_chequediv").hide();   

    }  
    else 
    {
        $("#tipo_bancodiv").hide(); 
        $("#numero_boletadiv").hide();
        $("#recibo_caja_numerodiv").hide(); 
        $("#idcuentadiv").hide(); 
        $("#no_chequediv").hide(); 
        $("#desp_chequediv").hide();         

    }        
  } 

//Función limpiar
function limpiar()
{
    $("#idingreso").val("");
    $("#nombre").val(""); 
    $("#telefono").val("");
    $("#tipodocumento").val("");
    $("#seriedocuemnto").val("");
    $("#numerodocumento").val("");
    $("#total_compra").val("");
    $("#valor_pagar").val("");
    $("#saldo_ingreso").val("");
    $("#tipo_pago").val("Efectivo");
    $("#tipo_pago").selectpicker('refresh');
    $("#tipo_banco").val("BANRURAL");
    $("#tipo_banco").selectpicker('refresh');    
    $("#numero_boleta").val("");
    $("#recibo_caja_numero").val("");
   // $("#idcuenta").val("");
    $("#no_cheque").val("");
    $("#desp_cheque").val("");
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

        $("#tipo_bancodiv").hide();
        $("#numero_boletadiv").hide();
        $("#recibo_caja_numerodiv").hide(); 
        $("#idcuentadiv").hide(); 
        $("#no_chequediv").hide(); 
        $("#desp_chequediv").hide(); 
    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();

        $("#tipo_bancodiv").show();
        $("#numero_boletadiv").show();
        $("#recibo_caja_numerodiv").show(); 
        $("#idcuentadiv").show(); 
        $("#no_chequediv").show(); 
        $("#desp_chequediv").show();         
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
                    url: '../ajax/cuentasporpagar.php?op=listar',     
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}
//Función para guardar o editar
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/cuentasporpagar.php?op=guardaryeditar", 
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
 
function mostrar(idingreso)
{
    $.post("../ajax/cuentasporpagar.php?op=mostrar",{idingreso : idingreso}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 

        $("#idingreso").val(data.idingreso);  
        $("#idcliente").val(data.idproveedor); 
        $("#nombre").val(data.proveedor);  
        $("#telefono").val(data.telefono);  
        $("#fecha_operacion").val(data.fecha_operacion);  
        $("#serie_comprobante").val(data.serie_comprobante);  
        $("#num_comprobante").val(data.num_comprobante);  
        $("#forma_pago").val(data.forma_pago);  
        $("#dias_credito").val(data.dias_credito); 
        $("#fecha_hora_pago_credito").val(data.fecha_hora_pago_credito);  
        $("#total_compra").val(data.saldo_ingreso);  
        $("#saldo_ingreso").val(data.saldo_ingreso);  


        


 
    })
}

function calculosaldoingreso() {

    var b1 = document.getElementById('total_compra').value;
    var b2 = document.getElementById('valor_pagar').value;


    var resultado_saldo_ingreso = parseFloat(b1) - parseFloat(b2);
    document.getElementById('saldo_ingreso').innerHTML = resultado_saldo_ingreso;
    $("#saldo_ingreso").val(resultado_saldo_ingreso);
}
 
//Función para desactivar registros
function desactivar(idcategoria)
{
    bootbox.confirm("¿Está Seguro de desactivar la Categoría?", function(result){
        if(result)
        {
            $.post("../ajax/categoria.php?op=desactivar", {idcategoria : idcategoria}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Función para activar registros
function activar(idcategoria)
{
    bootbox.confirm("¿Está Seguro de activar la Categoría?", function(result){
        if(result)
        {
            $.post("../ajax/categoria.php?op=activar", {idcategoria : idcategoria}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
 
init();