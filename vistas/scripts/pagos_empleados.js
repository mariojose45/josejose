var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/pagos_empleados.php?op=selectEmpleado", function(r){
                $("#idficha_empleado").html(r);
                $("#idficha_empleado23").html(r);
                $('#idficha_empleado').selectpicker('refresh');   
                
    }); 
    //Cargamos los items al select proveedor
    $.post("../ajax/pagos_empleados.php?op=selectBanco", function(r){
                $("#idcuenta").html(r);
                $('#idcuenta').selectpicker('refresh'); 
                
    });   
    $('#idficha_empleado').change(function(){   
         $("#idficha_empleado23").val($(this).val()) 
         var sueldo_base_r=$("#idficha_empleado23 option:selected").attr("data-sueldo_base")
         var bonificacion_r=$("#idficha_empleado23 option:selected").attr("data-bonificacion") 
         var hodaextra=$("#idficha_empleado23 option:selected").attr("data-hora-extra") 

         $("#valorhoraextra").val(hodaextra);

         var sueldoBase=parseFloat(sueldo_base_r); 
         var sueldoDiario=(45/7);
         var sueldoDiarioMes=sueldoDiario*30;
         var sueldoHora=sueldoBase/sueldoDiarioMes;

         console.log(sueldoHora);

         $("#valor_hora").val(sueldoHora.toFixed(2)); 

         $("#sueldo_pagar").val(sueldo_base_r);
         //$("#bonificacion").val(bonificacion_r);

         try{
            var calcu=(bonificacion_r)/30;
            var fecha1 = moment($("#fecha_hora_ini").val());
            var fecha2 = moment($("#fecha_hora_fin").val());
            var days=fecha2.diff(fecha1, 'days');
            days=days+1;
            //alert(days);
            calcu=days*calcu;
            $("#bonificacion").val(calcu.toFixed(2));
        }
        catch(ex){
            console.log(ex);
        }

         $("#descuento").val("0");
         $("#bonificacion_extra").val("0");  

         $("#dtrabajados").val("0"); 
         $("#bono14").val("0"); 
         $("#aguinaldo").val("0"); 
         $("#vacaciones").val("0"); 
         $("#prestacion_a_sumar").val("0"); 

    }); 

    $("#consultarregistros").click(function(){
        var empleado=$("#idficha_empleado").val();
        var fini=$("#fecha_hora_ini").val();
        var ffin=$("#fecha_hora_fin").val();

        $.get("../ajax/pagos_empleados.php?fechaini="+fini+"&fechafin="+ffin+"&idempleado="+empleado+"&op=registroshoras",function(res){

            $("#datosregistros").html(res);

        }); 

    });   


    $("#btnguardarregistro").click(function(){
        var codigo=$("#codigo33").val();
        var tipo=$("#tipo33").val();
        var fecha=$("#fecha33").val();
        var hora=$("#hora33").val();

        $.post("../ajax/pagos_empleados.php?op=addregistro",{
            codigo:codigo,
            tipo:tipo,
            fecha:fecha,
            hora:hora
        },function(res){
            console.log("Result: ",res);
            alert("Registro Agregado")
            $("#consultarregistros").trigger("click")
        });

        

    });


    
    $("#btnconsultar").click(function(){
        var empleado=$("#idficha_empleado").val();
        var fini=$("#fecha_hora_ini").val();
        var ffin=$("#fecha_hora_fin").val();

        if(empleado==null){
            alert("Debes Elegir el Un Empelado");
            return;
        }

        //alert(empleado+" "+fini+" "+ffin);


        $.get("../ajax/pagos_empleados.php?fechaini="+fini+"&fechafin="+ffin+"&idempleado="+empleado+"&op=ConsultaHora",function(res){
            console.log(res);
            var datos=res.split("@");

            $("#horas_acumuladas").val(datos[0].replace("HorasAcumuladas:",""));
            $("#horas_tarde").val(datos[1].replace("HorasTarde:",""));
            $("#horas_extra").val(datos[2].replace("HorasExtrasHueco:",""));

            var arrayhe=datos[2].replace("HorasExtrasHueco:","").split(":");
            var he=parseFloat(arrayhe[0]);

            var valhe=parseFloat($("#valorhoraextra").val())


            var recibirval=$("#sueldo_liquido_recibir").val()==""?"0":$("#sueldo_liquido_recibir").val();
            var recibir=parseFloat(recibirval);

            var total=he*valhe;
            $("#sueldo_liquido_recibir").val(total.toFixed(2))
        });
        

    });
    
} 



$("#prestacion_a_pagar").change(mostrarprestacionapagar);
                                     
           
function mostrarprestacionapagar() 
 {
    var presta1=document.getElementById('bono14').value;
    var presta2=document.getElementById('aguinaldo').value;
    var presta3=document.getElementById('vacaciones').value;
    var presta4=0;
    console.log(presta1);    
    var prestacion_a_pagar=$("#prestacion_a_pagar option:selected").text(); 
     if (prestacion_a_pagar=='bono14')
        {
            $("#prestacion_a_sumar").val(presta1);
        }
        else if (prestacion_a_pagar=='aguinaldo')
        {
        $("#prestacion_a_sumar").val(presta2);
        }    
        else if (prestacion_a_pagar=='vacaciones')
        {
        $("#prestacion_a_sumar").val(presta3);
        }     
        else
        {
        $("#prestacion_a_sumar").val(presta4);
        }

                                     //   $("#prestacion_a_sumar").val(restacionresarecibir);
}  
function eliminarRegistro(id)
{
    $.post("../ajax/pagos_empleados.php?op=deleteregistro",{
        id:id
    },function(res){
        console.log("Result: ",res);
        $("#consultarregistros").trigger("click")
    });
}

function modificarRegistro(id)
{
        var codigo=$("#codigo"+id).val();
        var tipo=$("#tipo"+id).val();
        var fecha=$("#fecha"+id).val();
        var hora=$("#hora"+id).val();


        $.post("../ajax/pagos_empleados.php?op=modificarregistro",{
            codigo:codigo,
            tipo:tipo,
            fecha:fecha,
            hora:hora,
            id:id
        },function(res){
            console.log("Result: ",res);
            alert("Registro Modificado")
            $("#myModal"+id).modal('hide');
            $(".modal-backdrop").remove()
            $("#consultarregistros").trigger("click")
        });

}


 
//Función limpiar
function limpiar()
{
    $("#idficha_empleado").val(""); 
     
 
    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora_ini').val(today);
    $('#fecha_hora_fin').val(today);
    $('#fecha_generacion_pago').val(today);

    $('#fecha_hora_de').val(today);
    $('#fecha_hora_asta').val(today);

    
    $("#descripcion").val("");
    $("#horas_acumuladas").val("");
    $("#horas_tarde").val(""); 
    $("#horas_extra").val("");
    $("#total_horas_pagar").val("");
    $("#valor_hora").val("");
    $("#sueldo_pagar").val("");
    $("#bonificacion").val("");

    $("#descuento").val("");
    $("#sueldo_liquido_recibir").val("");
    $("#idcuenta").val("");
    $("#forma_pago").val("Cheque");
    $("#forma_pago").selectpicker('refresh');  
    $("#idcuenta").val("");    

    $("#idpagoempleado").val("");
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
                    url: '../ajax/pagos_empleados.php?op=listar',
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
        url: "../ajax/pagos_empleados.php?op=guardaryeditar",
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
 
function mostrar(idpagoempleado)
{
    $.post("../ajax/pagos_empleados.php?op=mostrar",{idpagoempleado : idpagoempleado}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 

        $("#idpagoempleado").val(data.idpagoempleado);
        $("#idficha_empleado").val(data.idficha_empleado);
        $("#idficha_empleado").selectpicker('refresh');        
        $("#fecha_hora_ini").val(data.fecha_hora_ini);
        $("#fecha_hora_ini").val(data.fecha_hora_ini);

        $("#descripcion").val(data.descripcion);
       // $("#fecha_generacion_pago").val(data.fecha_generacion_pago);

        $("#horas_acumuladas").val(data.horas_acumuladas);
        $("#horas_tarde").val(data.horas_tarde);
        $("#horas_extra").val(data.horas_extra);
        $("#horas_feriado").val(data.horas_feriado);

        $("#total_horas_pagar").val(data.total_horas_pagar);
        $("#valor_hora").val(data.valor_hora);
        $("#sueldo_pagar").val(data.sueldo_pagar);
        $("#bonificacion").val(data.bonificacion);
        
        $("#fecha_hora_de").val(data.fecha_hora_de);
        $("#fecha_hora_asta").val(data.fecha_hora_asta);
        $("#dtrabajados").val(data.dtrabajados);
        $("#prestacion_a_pagar").val(data.prestacion_a_pagar);
        $("#prestacion_a_pagar").selectpicker('refresh');  
        $("#bono14").val(data.bono14);
        $("#aguinaldo").val(data.aguinaldo);
        $("#vacaciones").val(data.vacaciones);
        $("#descuento").val(data.descuento);
        $("#sueldo_liquido_recibir").val(data.sueldo_liquido_recibir);
        $("#idcuenta").val(data.idcuenta);
        $("#idcuenta").selectpicker('refresh');  
        $("#forma_pago").val(data.forma_pago);
        $("#forma_pago").selectpicker('refresh'); 
        $("#cheque_auto_no").val(data.cheque_auto_no);




 
    })
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