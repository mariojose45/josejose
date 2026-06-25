<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Formulario</title> <!-- Aquí va el título de la página -->

</head>

<body>
<?php

$Nombre = $_POST['Nombre'];
$Email = $_POST['Email'];
$Mensaje = $_POST['Mensaje'];
$archivo = $_FILES['adjunto'];

if ($Nombre=='' || $Email=='' || $Mensaje==''){

echo "<script>alert('Los campos marcados con * son obligatorios');location.href ='javascript:history.back()';</script>";

}else{


    require("class.phpmailer.php");
    require("class.smtp.php");
    require("class.pop3.php");
    $mail = new PHPMailer(true);

    $mail->From     = $Email;
    $mail->FromName = $Nombre; 
    $mail->AddAddress($Email); // Dirección a la que llegaran los mensajes.
   
// Aquí van los datos que apareceran en el correo que reciba
    //adjuntamos un archivo 
        //adjuntamos un archivo
            
    $mail->WordWrap = 50; 
    $mail->IsHTML(true);     
    $mail->Subject  =  "Contacto";
    $mail->Body     =  "Nombre: $Nombre \n<br />".    
    "Email: $Email \n<br />".    
    "Mensaje: $Mensaje \n<br />";
    $mail->AddAttachment($archivo['tmp_name'], $archivo['name']);
    
    
    

// Datos del servidor SMTP

    $mail->IsSMTP(); 
    $mail->Host = "mail.computecnologia.com.gt";  // Servidor de Salida.
    //$mail->Host = "ssl://smtp.gmail.com:465";  // Servidor de Salida.
    $mail->SMTPAuth = true; 
    $mail->Username = "info@computecnologia.com.gt";  // Correo Electrónico
    $mail->Password = "1rD1~WkG@QJg"; // Contraseña
    //Puerto de escucha del servidor
    $mail->Port = 587;
    
    if ($mail->Send())
    echo "<script>alert('Cotizacion envia exitosamente.');location.href ='javascript:history.back()';</script>";
    else
    echo "<script>alert('Error al enviar el correo');location.href ='javascript:history.back()';</script>";

}

?>
</body>
</html>