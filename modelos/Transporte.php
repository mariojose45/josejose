<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Transporte
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion,$telefono,$email,$placa,$modelo,$marca,$color,$tipo_transporte,$capacidad) {
        try {
            $sql = "INSERT INTO transporte (nombre, descripcion, telefono, email,placa,modelo,marca,color,tipo_transporte,capacidad,condicion,fecha_creacion)
                    VALUES ('$nombre', '$descripcion', '$telefono', '$email','$placa','$modelo','$marca','$color','$tipo_transporte','$capacidad','1',NOW())";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            $descripcion_error = "Error transporte Creacion: " . $e->getMessage();
            $fecha_error = date("Y-m-d H:i:s");
            $idusuario = $_SESSION['idusuario'] ?? 0;
            $idsucursal = 0;
            $sql_log = "INSERT INTO logs ( idusuario, idsucursal, descripcion_error, fecha_error)
                        VALUES ( '".$_SESSION["idusuario"]."', '".$_SESSION["idsucursal"]."', '$descripcion_error', '$fecha_error')";
            ejecutarConsulta($sql_log);
            return "Error: " . $descripcion_error;
        }
    }

    public function insertar2($nombre,$descripcion,$telefono,$email,$placa,$modelo,$marca,$color,$tipo_transporte,$capacidad) {
        try {
            $sql = "INSERT INTO transporte (nombre, descripcion, telefono, email,placa,modelo,marca,color,tipo_transporte,capacidad,condicion,fecha_creacion)
                    VALUES ('$nombre', '$descripcion', '$telefono', '$email','$placa','$modelo','$marca','$color','$tipo_transporte','$capacidad','1',NOW())";
            ejecutarConsulta($sql);
            $new=ejecutarConsulta_retornarID($sql);
            $option='<option value=' .$new. '>' .$nombre. ' -- ' .$tipo_transporte. '</option>'; 
            return $option;
        } catch (Exception $e) {
            $descripcion_error = "Error transporte Creacion: " . $e->getMessage();
            $fecha_error = date("Y-m-d H:i:s");
            $idusuario = $_SESSION['idusuario'] ?? 0;
            $idsucursal = 0;
            $sql_log = "INSERT INTO logs ( idusuario, idsucursal, descripcion_error, fecha_error)
                        VALUES ( '".$_SESSION["idusuario"]."', '".$_SESSION["idsucursal"]."', '$descripcion_error', '$fecha_error')";
            ejecutarConsulta($sql_log);
            return "Error: " . $descripcion_error;
        }
    }

 
    //Implementamos un método para editar registros
    public function editar($idtransporte,$nombre,$descripcion,$telefono,$email,$placa,$modelo,$marca,$color,$tipo_transporte,$capacidad) {
        try {
            $sql = "UPDATE transporte SET 
                        nombre = '$nombre',
                        descripcion = '$descripcion',
                        telefono = '$telefono',
                        email = '$email',
                        placa = '$placa',
                        modelo = '$modelo',
                        marca = '$marca',
                        color = '$color',
                        tipo_transporte = '$tipo_transporte',
                        capacidad = '$capacidad'
                    WHERE idtransporte = '$idtransporte'";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            // Si ocurre un error, registramos en la tabla logs
            $descripcion_error = "Error transporte Editar: " . $e->getMessage();
            $fecha_error = date("Y-m-d H:i:s");
            $idusuario = $_SESSION['idusuario'] ?? 0; // Opcional: Obtén el usuario desde la sesión
            $idsucursal_log = $idsucursal ?? 0; // Si no hay valor para idsucursal, asignamos 0 como predeterminado
            $sql_log = "INSERT INTO logs (idusuario, idsucursal, descripcion_error, fecha_error)
                        VALUES ('".$_SESSION["idusuario"]."', '".$_SESSION["idsucursal"]."', '$descripcion_error', '$fecha_error')";
            ejecutarConsulta($sql_log);
            return "Error: " . $descripcion_error;
        }
    }

 
    //Implementamos un método para desactivar categorías
    public function desactivar($idtransporte)
    {
        $sql="UPDATE transporte SET condicion='0' WHERE idtransporte='$idtransporte'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idtransporte)
    {
        $sql="UPDATE transporte SET condicion='1' WHERE idtransporte='$idtransporte'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtransporte)
    {
        $sql="SELECT * FROM transporte WHERE idtransporte='$idtransporte'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM transporte";
        return ejecutarConsulta($sql);      
    }

    public function select()
    {
        $sql="SELECT * FROM transporte where condicion=1";
        return ejecutarConsulta($sql);      
    }
}
 
?>