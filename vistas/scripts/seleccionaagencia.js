var tabla;
 
//Función que se ejecuta al inicio
function init(){  
 
    $.post("../ajax/usuario.php?op=selectEmpresass", function(r){
                $("#idsucursalseleccion").html(r);
                $('#idsucursalseleccion').selectpicker('refresh');
 
    });  
}   
    
    $("#btnGuardar").click(function(e)  
    {
        var idsucursalseleccion = $("#idsucursalseleccion").val();
        
        // Verificar si idsucursalseleccion es null o undefined
        if (idsucursalseleccion !== null && idsucursalseleccion !== undefined) {
            // Primero hacemos la llamada AJAX para establecer la sesión
            $.ajax({
                url: "../ajax/seleccionar_sucursal.php?op=seleccionar",
                type: "POST",
                data: {idsucursal: idsucursalseleccion},
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        // Si la sesión se estableció correctamente, redirigimos
                        window.location.href = "escritorio.php?idsucursal=" + idsucursalseleccion;
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert("Error al establecer la sucursal");
                }
            });
        } else {
            alert("No has seleccionado ninguna sucursal.");
        }
    });     
 
 
 
init();