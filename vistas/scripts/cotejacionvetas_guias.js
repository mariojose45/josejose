var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    //listar();
 
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);   
 

    $("#btncargar").click(function(){

        var idguias_excel=$("#idguias_excel").val();
        if(idguias_excel==""){
            alert("Debe Colocar un Id de Carga de Guia Excel Valido")
            return;
        }  

        obtenercabeceracargaguias(idguias_excel); 

    });    
 
    $("#btnGuardar").click(function(e)    
    {
        guardaryeditar(e);   
    });             
}  
 
function obtenercabeceracargaguias(idguias_excel)
{
    $.post("../ajax/cotejacionvetas_guias.php?op=mostrar",{idguias_excel:idguias_excel},function(data){
        //console.log(data); 
        data = JSON.parse(data);         
 
        $("#idguias_excel2").val(data.idguias_excel);
        obtenerdetallescargaguia(idguias_excel);
    })
}

function obtenerdetallescargaguia(idguias_excel) {
    $.post("../ajax/cotejacionvetas_guias.php?op=mostrardetalle", { idguias_excel: idguias_excel }, function (data) {
        //console.log(data);

        // Verificar si la respuesta es válida
        if (!data || data === "null" || data.trim() === "" || data === "[]" || data === "{}") {
            Swal.fire({
                position: "top-end",
                icon: "warning",
                title: "No tienes ventas pendientes por liquidar con este número de guía. Ingresa otro, por favor.",
                showConfirmButton: false,
                timer: 2000,
                 willClose: () => {
                  window.location.reload();
                  }

            });
            return; // Detener la ejecución si no hay datos
        }

        try {
            data = JSON.parse(data);

            // Verificar si el array de datos está vacío
            if (!Array.isArray(data) || data.length === 0) {
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "No tienes ventas pendientes por liquidar con este número de guía. Ingresa otro, por favor.",
                    showConfirmButton: false,
                    timer: 2000,
                     willClose: () => {
                      window.location.reload();
                      }
                });
                return;
            }

            // Si hay datos, recorrer el array y agregarlos
            $.each(data, function (i, item) {
                agregarDetalle(
                    item.idventa, item.fechaventa, item.total_venta, item.guia_transporte, 
                    item.idguia, item.iddetalle_guias_excel, item.mventa, item.comision, 
                    item.vcomision, item.mliquido, item.autorizacion, item.ctabanco, 
                    item.vflete, item.idtransporte, item.transportes, item.estado_venta
                );
            });
        } catch (error) {
            console.error("Error al procesar la respuesta JSON:", error);
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Hubo un error al procesar la información.",
                showConfirmButton: false,
                timer: 2000,
                 willClose: () => {
                  window.location.reload();
                  }
            });
        }
    });
}

function listarVentasparcotijamientoxfecha()
{
    var fecha_inicio = $("#fecha_inicio_reporte").val();
    var fecha_fin = $("#fecha_fin_reporte").val();    
    tabla=$('#tblarticulos').dataTable(
    {  
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [                
                      
                ],  
        "ajax": 
                {
                    url: '../ajax/cotejacionvetas_guias.php?op=listarVentasparcotijamientoxfecha',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin},
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 5,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}

var cont=0;
var detalles=0;
  function agregarDetalle(idventa,fechaventa,total_venta,guia_transporte,
    idguia,iddetalle_guias_excel,mventa,comision,vcomision,mliquido,autorizacion,
    ctabanco,vflete,idtransporte,transportes,estado_venta) 
  {
    resestadoventa='';
    if (estado_venta !== 'COMPLETO') {
        resestadoventa = 'Tiene que liquidar tu venta, VENTAS DESPACHO de lo contrario no se tomará en cuenta';
    }
    var subtotal=0;  

    if (iddetalle_guias_excel!="")
    {
        var subtotal=total_venta-mliquido;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idventa[]" value="'+idventa+'"><input type="hidden" name="estado_venta[]" value="'+estado_venta+'">'+idventa+' -- '+resestadoventa+'</td>'+
        '<td><input type="hidden" name="fechaventa[]" value="'+fechaventa+'">'+fechaventa+'</td>'+  
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="total_venta[]" id="total_venta'+cont+'" value="'+total_venta+'" readonly=""></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control" type="text" style="width:100px"  name="guia_transporte[]" id="guia_transporte[]" value="'+guia_transporte+'" readonly=""></td>'+
        '<td><input type="hidden" name="iddetalle_guias_excel[]" class="form-control"  value="'+iddetalle_guias_excel+'"><input onchange="modificarSubototales()" type="text" style="width:100px"  class="form-control" name="idguia[]" id="idguia[]" value="'+idguia+'" readonly=""></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="mventa[]" id="mventa" value="'+mventa+'" readonly=""></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="comision[]" id="comision" value="'+comision+'" ></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="vcomision[]" id="vcomision[]" value="'+vcomision+'" ></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="mliquido[]" id="mliquido'+cont+'" value="'+mliquido+'" ></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="autorizacion[]" id="autorizacion[]" value="'+autorizacion+'" readonly=""></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="ctabanco[]" id="ctabanco[]" value="'+ctabanco+'" readonly=""></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="vflete[]" id="vflete[]" value="'+vflete+'" ></td>'+
        '<td><input onchange="modificarSubototales()" class="form-control"  type="number" style="width:100px" step="any"  name="subtotal[]" id="subtotal'+cont+'"" value="'+subtotal+'" readonly=""></td>'+
        '<td><input type="hidden" name="idtransporte[]" value="'+idtransporte+'">'+transportes+'</td>'+  

        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        modificarSubototales();
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
  }

  function agregarDetalle2(idventa,fechaventa,total_venta,transportes,correlativo_trasporte,total_venta2) 
  {
    var idguia=0;
    var iddetalle_guias_excel=0;
    var subtotal=0;  
    var mventa=0;
    var comision=0;
    var vcomision=0;
    var mliquido=0;
    var autorizacion=0;
    var ctabanco=0;
    var vflete=0;

    if (idventa!="")
    {
        var subtotal=total_venta-mliquido;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idventa[]" value="'+idventa+'">'+idventa+'</td>'+
        '<td><input type="hidden" name="fechaventa[]" value="'+fechaventa+'">'+fechaventa+'</td>'+  
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="total_venta[]" id="total_venta'+cont+'" value="'+total_venta2+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="text" style="width:100px"  name="guia_transporte[]" id="guia_transporte[]" value="'+transportes+' '+correlativo_trasporte+'"></td>'+
        '<td><input type="hidden" name="iddetalle_guias_excel[]" value="'+iddetalle_guias_excel+'"><input onchange="modificarSubototales()" type="text" style="width:100px"  name="idguia[]" id="idguia[]" value="'+idguia+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="mventa[]" id="mventa" value="'+mventa+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="comision[]" id="comision" value="'+comision+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="vcomision[]" id="vcomision'+cont+'" value="'+vcomision+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="mliquido[]" id="mliquido'+cont+'" value="'+mliquido+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="autorizacion[]" id="autorizacion[]" value="'+autorizacion+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="ctabanco[]" id="ctabanco[]" value="'+ctabanco+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="vflete[]" id="vflete[]" value="'+vflete+'"></td>'+
        '<td><input onchange="modificarSubototales()" type="number" style="width:100px" step="any"  name="subtotal[]" id="subtotal'+cont+'"" value="'+subtotal+'"></td>'+

        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        modificarSubototales();
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
  }  

  function eliminarDetalle(indice){
    $("#fila" + indice).remove();

    calcularTotales(); 
    calcularTotalventa();
    calcularTotalmventa();
    calcularTotalvcomision();
    calcularTotalmliquidado();
    calcularTotalvflete();
    detalles=detalles-1;
    evaluar()
  }

  function modificarSubototales()
  {
    var cant = document.getElementsByName("mliquido[]");
    var vflete = document.getElementsByName("vflete[]");
    var vcomision = document.getElementsByName("vcomision[]");
    var prec = document.getElementsByName("total_venta[]");
    var sub = document.getElementsByName("subtotal[]");;    
 
    for (var i = 0; i <cant.length; i++) {
        var inpC=cant[i];
        var inpVcomision=vcomision[i];
        var inpP=prec[i];
        var inpS=sub[i];   
        var inpSvflete=vflete[i];       
 
 
        inpS.value=(parseFloat(inpP.value) - (parseFloat(inpC.value) +  parseFloat(inpVcomision.value) +  parseFloat(inpSvflete.value) )).toFixed(2);
        document.getElementsByName("subtotal[]")[i].value = inpS.value;

    }

    calcularTotales(); 
    calcularTotalventa();
    calcularTotalmventa();
    calcularTotalvcomision();
    calcularTotalmliquidado();
    calcularTotalvflete();

  }

    function calcularTotales()
    {

        var sub = document.getElementsByName("mliquido[]");
        var restotal=0.0;

        var total=0
        for (var i = 0; i <sub.length; i++) {
            total += parseFloat(document.getElementsByName("mliquido[]")[i].value);//asdfasdf
        }

         restotal=parseFloat(total).toFixed(2);
        $("#total_liquidacion").val(restotal);


    }

    function calcularTotalventa()
    {

        var sub = document.getElementsByName("total_venta[]");
        var restotal=0.0;

        var total=0
        for (var i = 0; i <sub.length; i++) {
            total += parseFloat(document.getElementsByName("total_venta[]")[i].value);//asdfasdf
        }

         restotal=parseFloat(total).toFixed(2);
        $("#t_total_venta").val(restotal);
    }  

    function calcularTotalmventa()
    {

        var sub = document.getElementsByName("mventa[]");
        var restotal=0.0;


        var total=0
        for (var i = 0; i <sub.length; i++) {
            total += parseFloat(document.getElementsByName("mventa[]")[i].value);//asdfasdf
        }

         restotal=parseFloat(total).toFixed(2);
        $("#t_mventa").val(restotal);
    }  

    function calcularTotalvcomision()
    {

        var sub = document.getElementsByName("vcomision[]");
        var restotal=0.0;

        var total=0
        for (var i = 0; i <sub.length; i++) {
            total += parseFloat(document.getElementsByName("vcomision[]")[i].value);//asdfasdf
        }

         restotal=parseFloat(total).toFixed(2);
        $("#t_vcomision").val(restotal);
    } 

    function calcularTotalmliquidado()
    {

        var sub = document.getElementsByName("mliquido[]");
        var restotal=0.0;

        var total=0
        for (var i = 0; i <sub.length; i++) {
            total += parseFloat(document.getElementsByName("mliquido[]")[i].value);//asdfasdf
        }

         restotal=parseFloat(total).toFixed(2);
        $("#t_mliquidado").val(restotal);
    }  

    function calcularTotalvflete()
    {

        var sub = document.getElementsByName("vflete[]");
        var restotal=0.0;

        var total=0
        for (var i = 0; i <sub.length; i++) {
            total += parseFloat(document.getElementsByName("vflete[]")[i].value);//asdfasdf
        }

         restotal=parseFloat(total).toFixed(2);
        $("#t_vflete").val(restotal);
    }                


 

 
//Función limpiar
function limpiar()
{

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
                    url: '../ajax/cotejacionvetas_guias.php?op=listar',
                    data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin},
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

    if (detalles > 0) {

    }
    else {
        Swal.fire({
        position: "top-end",
        icon: "success",
        title: "No tiene Nada Agregado al detalle",
        showConfirmButton: false,
        timer: 1500,
            });

        return; 
    }    

    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/cotejacionvetas_guias.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {         
        //console.log(datos);           
                    
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


 
 
init();