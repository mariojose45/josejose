$(function(){
    $("#btnIngresar").on("click", function(){
        var logina=$("#logina").val();
        var clavea=$("#clavea").val();
        
        // Función para mostrar alertas
        function mostrarAlerta(mensaje) {
            if (typeof bootbox !== 'undefined') {
                bootbox.alert(mensaje);
            } else {
                alert(mensaje);
            }
        }
        
        // Validaciones de seguridad
        if(logina.trim() == "" || clavea.trim() == ""){
            mostrarAlerta("Los campos no pueden estar vacíos");
            return false;
        }

        // Validar longitud mínima
        if(clavea.length < 5){
            mostrarAlerta("La contraseña debe tener al menos 5 caracteres");
            return false;
        }

        // Validar caracteres especiales
        var regex = /^[a-zA-Z0-9@._]+$/;
        if(!regex.test(logina)){
            mostrarAlerta("El usuario solo puede contener letras, números y los caracteres @._");
            return false;
        }

        // Validar longitud máxima
        if(logina.length > 50 || clavea.length > 50){
            mostrarAlerta("Los campos exceden la longitud máxima permitida");
            return false;
        }

        $.ajax({
            url: "../ajax/usuario.php?op=verificar",
            type: "POST",
            data: {logina:logina, clavea:clavea},
            dataType: "json",
            success: function(e){
                if (e=="0"){
                    mostrarAlerta("Usuario y/o Password incorrectos");
                }
                else{
                    window.location="../vistas/escritorio.php";          
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición:", error);
                mostrarAlerta("Error al conectar con el servidor");
            }
        });
    });
}); 