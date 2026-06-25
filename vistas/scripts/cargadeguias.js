var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    //listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    });  

    $.post("../ajax/venta.php?op=selecttransporte", function(r){
                console.log(r)
                $("#idtransporte").html(r);
                $('#idtransporte').selectpicker('refresh');

    });  
        
} 
 
//Función limpiar 
function limpiar()
{ 
     //Obtenemos la fecha actual 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_cargaExcel').val(today);    
}
 
function cargaexcel() {
    var excel = $("#txt_archivo").val();
    if (excel === "") {
        return Swal.fire("Mensaje de Advertencia", "Seleccione un archivo de Excel", "warning");
    }

    var formData = new FormData(); 
    var files = $("#txt_archivo")[0].files[0];
    var idtransporte = $("#idtransporte").val(); // Capturar el ID del transporte

    if (idtransporte === "") {
        return Swal.fire("Mensaje de Advertencia", "Seleccione un transporte", "warning");
    }

    formData.append('archivoexcel', files);
    formData.append('idtransporte', idtransporte); // Agregar idtransporte a la petición

    $.ajax({
        url: '../ajax/cargadeguias.php?op=cargarexcel',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(resp) {
            $("#div_table").html(resp);
            document.getElementById('btn_guardarregistros').disabled = false;
        }
    });

    return false;
}

/*
function cargaexcel()
{
    var excel = $("#txt_archivo").val();
    if (excel==="") {
        return Swal.fire("Mensaje de Adventencia","Selecine un archio de Excel ","warning");     
    }
    var formDate = new FormData();
    var files =$("#txt_archivo")[0].files[0];
    formDate.append('archivoexcel',files);
    $.ajax({
        url:'../ajax/cargadeguias.php?op=cargarexcel',
        type:'post',
        data:formDate,
        contentType:false,
        processData:false,
        success: function (resp){
            $("#div_table").html(resp);
            document.getElementById('btn_guardarregistros').disabled=false;

        }
    });
    return false;

}*/

function GuardarExcel(){
    var idtransporte = $("#idtransporte").val();
    var obervacioncargaexcel = $("#obervacioncargaexcel").val();
    var fecha_cargaExcel = $("#fecha_cargaExcel").val();     
    var contador=0;
    var arreglo_IDguia = new Array();
    var arreglo_Fechaliqui = new Array();

    var arreglo_Mventa = new Array();
    var arreglo_Comision = new Array();
    var arreglo_Vcomision = new Array();
    var arreglo_Mliquido = new Array();
    var arreglo_Autorizacion = new Array();
    var arreglo_Ctabanco = new Array();
    var arreglo_Vflete = new Array();
    var arreglo_Codigo = new Array();
    $("#table_detalle tbody#tbody_table_detalle tr").each(function(){
        arreglo_IDguia.push($(this).find('td').eq(0).text());
        arreglo_Fechaliqui.push($(this).find('td').eq(1).text());

        arreglo_Mventa.push($(this).find('td').eq(2).text());
        arreglo_Comision.push($(this).find('td').eq(3).text());
        arreglo_Vcomision.push($(this).find('td').eq(4).text());
        arreglo_Mliquido.push($(this).find('td').eq(5).text());
        arreglo_Autorizacion.push($(this).find('td').eq(6).text());
        arreglo_Ctabanco.push($(this).find('td').eq(7).text());
        arreglo_Vflete.push($(this).find('td').eq(8).text());
        arreglo_Codigo.push($(this).find('td').eq(9).text());
        contador++;
    })
    //alert(contador);
    if (contador==0) {
        return Swal.fire("Mensaje de Adventencia", "La table tiene que tener como minimo 1 dato", "warning");
    }

    //alert (arreglo_IDguia+" - "+arreglo_Fechaliqui);
    var idguia = arreglo_IDguia.toString();
    var fechaliqui = arreglo_Fechaliqui.toString();

    var mventa = arreglo_Mventa.toString();
    var comision = arreglo_Comision.toString();
    var vcomision = arreglo_Vcomision.toString();
    var mliquido = arreglo_Mliquido.toString();
    var autorizacion = arreglo_Autorizacion.toString();
    var ctabanco = arreglo_Ctabanco.toString();
    var vflete = arreglo_Vflete.toString();
    var codigo = arreglo_Codigo.toString();

        $.ajax({
        url:'../ajax/cargadeguias.php?op=guardarexcel',
        type:'post',
        data:{
            idguia:idguia,
            fechaliqui:fechaliqui,

            mventa:mventa,
            comision:comision,
            vcomision:vcomision,
            mliquido:mliquido,
            autorizacion:autorizacion,
            ctabanco:ctabanco,
            vflete:vflete,
            idtransporte:idtransporte,
            obervacioncargaexcel:obervacioncargaexcel,
            fecha_cargaExcel:fecha_cargaExcel,
            codigo:codigo


        }
    }).done(function(resp){
        //alert(resp);
        window.location.reload();
    })

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
                    url: '../ajax/cargadeguias.php?op=listar',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin},
                    type : "get", 
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
        "order": [[ 5, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}
//Función para guardar o editar
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/categoria.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {                    
                swal("Mensaje!", datos, "success");            
              mostrarform(false);
              tabla.ajax.reload();
        }
 
    });
    limpiar();
}
 

function anular(id)
{
    Swal.fire({
        title: '¿Está seguro de anular la guía?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../ajax/cargadeguias.php?op=anular',
                type: 'GET',
                data: { id: id },
                success: function (data) {
                    Swal.fire(
                        'Anulado',
                        data,
                        'success'
                    );
                    tabla.ajax.reload();
                }
            });
        }
    });
}

 
 
init();