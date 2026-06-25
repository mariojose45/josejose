var tabla;
var tablaSucursales;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar(); 
    $('#MenuAcceso').addClass("treeview active");
    $('#Usuarios').addClass("active");
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })
  
    $("#imagenmuestra").hide();
    //Mostramos los permisos
    $.post("../ajax/usuario.php?op=permisos&id=",function(r){
            $("#permisos").html(r);
    });


      
} 

const permisosPorRol = {
    "ADMINISTRADOR": [
      // Todos los permisos (1 al 61 en tu caso, aquí solo pondré algunos de ejemplo)
      1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 
      24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 
      45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80
    ],
    "VENDEDOR": [
     37, 59, 60, 10, 14, 50, 51, 49, 21, 1, 24, 22, 13, 18, 15, 61, 4, 35, 38, 36
    ],
    "SUPERVISOR": [
      6, 44, 45, 7, 55, 54, 53, 9, 46, 48, 47, 14, 50, 51, 49, 22, 13, 12, 61
    ],
    "BODEGA": [
      2, 52, 26, 28, 27, 3, 33, 31, 29, 32, 34, 30, 6, 44, 45, 16, 22, 13, 12, 15, 61
    ]
  };    

$.post("../ajax/usuario.php?op=sucursales&id=",function(r){
    $("#sucursales").html(r);
});    


$("#cargo").change(mostrarPermisos);

function mostrarPermisos() {
    const rol = $(this).val();
    const permisos = permisosPorRol[rol] || [];

    // Primero desmarcamos todos
    $('input[name="permiso[]"]').prop('checked', false);

    // Luego marcamos solo los que correspondan
    permisos.forEach(id => {
        $('input[name="permiso[]"][value="' + id + '"]').prop('checked', true);
    });
}
  
//Función limpiar 
function limpiar()
{
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("CUIDAD");
    $("#telefono").val("0");
    $("#email").val("na@gmail.com");
    $("#cargo").val(".");
    $("#cargo").selectpicker('refresh');
    $("#login").val("");
    $("#clave").val("");
    $("#imagenmuestra").attr("src","");
    $("#imagenactual").val("");
    $("#idsucursal").val("");
    $("#idusuario").val("");
    $("#comision").val("0");
    $("#meta").val("0");
    // Desmarcar los checkboxes
    $("#permisos input[type='checkbox']").prop("checked", false);
    $("#sucursales input[type='checkbox']").prop("checked", false);
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
                    url: '../ajax/usuario.php?op=listar',
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

    // ✅ Validación: al menos un checkbox en permisos y sucursales
    const permisosSeleccionados = $('#permisos input[type=checkbox]:checked').length;
    const sucursalesSeleccionadas = $('#sucursales input[type=checkbox]:checked').length;

    if (permisosSeleccionados === 0) {
        bootbox.alert("Debe seleccionar al menos un permiso.");
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    if (sucursalesSeleccionadas === 0) {
        bootbox.alert("Debe seleccionar al menos una sucursal.");
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // ✅ Si pasa validación, procedemos con AJAX    

    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditar",
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


 
 
function mostrar(idusuario)
{
    $.post("../ajax/usuario.php?op=mostrar",{idusuario : idusuario}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);
 
        $("#idusuario").val(data.idusuario);
        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#cargo").val(data.cargo);
        $("#cargo").selectpicker('refresh');
        $("#login").val(data.login);
        $("#clave").prop("disabled", true); // Bloquear campo clave al editar
        
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#comision").val(data.comision);
        $("#meta").val(data.meta);
        $("#idsucursal").val(data.idsucursal);
        $("#idsucursal").selectpicker('refresh');        
 
    });
    $.post("../ajax/usuario.php?op=permisos&id="+idusuario,function(r){
            $("#permisos").html(r);
    });

    $.post("../ajax/usuario.php?op=sucursales&id="+idusuario,function(r){
            $("#sucursales").html(r);
    });
}
 
//Función para desactivar registros
function desactivar(idusuario)
{
    bootbox.confirm("¿Está Seguro de desactivar el usuario?", function(result){
        if(result)
        {
            $.post("../ajax/usuario.php?op=desactivar", {idusuario : idusuario}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
//Función para activar registros
function activar(idusuario)
{
    bootbox.confirm("¿Está Seguro de activar el Usuario?", function(result){
        if(result)
        {
            $.post("../ajax/usuario.php?op=activar", {idusuario : idusuario}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}



function mostrarSucursalesModal(idusuario) {
    console.log(idusuario);
    $("#myModalSucursales").modal("show");
    tabla=$('#tblsucursales').dataTable(
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
                        url: '../ajax/usuario.php?op=mostrarSucursales',
                        type : "get",
                        data: {idusuario: idusuario},
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

function cambiar_usuario(idusuario) {
    $("#myModalCambiarClave").modal("show");
    $("#idusuarioClave").val(idusuario);
}


$("#btnGuardarClave").click(function (e) {
    $('#myModalCambiarClave').modal('hide');
    guardaryeditarClave(e);
});

function guardaryeditarClave(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardarClave").prop("disabled",true);


    nueva_clave = $("#nueva_clave").val();
    if (idusuario === "" || nueva_clave === "") {
        bootbox.alert("colocar una nueva clave.");
        $("#btnGuardarClave").prop("disabled", false);
        return;
    }

    // ✅ Si pasa validación, procedemos con AJAX    

    var formData = new FormData($("#formularioClave")[0]);
    load();
    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditarClave",
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
                    tabla.ajax.reload();
                }
            });

        }
 
    });
    limpiarClave();
} 

function limpiarClave()
{
    $("#idusuarioClave").val("");
    $("#nueva_claveClave").val("");
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