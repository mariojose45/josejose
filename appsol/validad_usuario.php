<?php
include 'conexion.php';
$usu_usuario=$_POST['usuario'];
$usu_password=$_POST['password'];



$clavehash=hash("SHA256",$usu_password);

$sentencia=$conexion->prepare("SELECT * FROM usuario WHERE login=? AND clave=?");
$sentencia->bind_param('ss',$usu_usuario,$clavehash);
$sentencia->execute();

$resultado = $sentencia->get_result();
if ($fila = $resultado->fetch_assoc()) {
         echo json_encode($fila,JSON_UNESCAPED_UNICODE);     
}
$sentencia->close();
$conexion->close();
?>