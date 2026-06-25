<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Mensajero
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$descripcion,$telefono,$email,$idusuario) {
        try {
            $sql = "INSERT INTO mensajero (nombre, descripcion, telefono, email,condicion,fecha_creacion,idusuario)
                    VALUES ('$nombre', '$descripcion', '$telefono', '$email', '1', NOW(),'$idusuario')";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            $descripcion_error = "Error Mensajero Creacion: " . $e->getMessage();
            $fecha_error = date("Y-m-d H:i:s");
            $idusuario = $_SESSION['idusuario'] ?? 0;
            $idsucursal = 0;
            $sql_log = "INSERT INTO logs ( idusuario, idsucursal, descripcion_error, fecha_error)
                        VALUES ( '".$_SESSION["idusuario"]."', '".$_SESSION["idsucursal"]."', '$descripcion_error', '$fecha_error')";
            ejecutarConsulta($sql_log);
            return "Error: " . $descripcion_error;
        }
    }

    public function insertar2($nombre,$descripcion,$telefono,$email) {
        try {
            $sql = "INSERT INTO mensajero (nombre, descripcion, telefono, email,condicion,fecha_creacion)
                    VALUES ('$nombre', '$descripcion', '$telefono', '$email', '1', NOW())";
            $new=ejecutarConsulta_retornarID($sql);
            $option='<option value=' . $new . '>' . $nombre . ' -- ' . $telefono . '</option>'; 
            return $option;
        } catch (Exception $e) {
            $descripcion_error = "Error Mensajero Creacion: " . $e->getMessage();
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
    public function editar($idmensajero,$nombre,$descripcion,$telefono,$email,$idusuario) {
        try {
            $sql = "UPDATE mensajero SET 
                        nombre = '$nombre',
                        descripcion = '$descripcion',
                        telefono = '$telefono',
                        email = '$email',
                        idusuario = '$idusuario'
                    WHERE idmensajero = '$idmensajero'";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            // Si ocurre un error, registramos en la tabla logs
            $descripcion_error = "Error Mensajero Editar: " . $e->getMessage();
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
    public function desactivar($idmensajero)
    {
        $sql="UPDATE mensajero SET condicion='0' WHERE idmensajero='$idmensajero'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idmensajero)
    {
        $sql="UPDATE mensajero SET condicion='1' WHERE idmensajero='$idmensajero'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idmensajero)
    {
        $sql="SELECT * FROM mensajero WHERE idmensajero='$idmensajero'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM mensajero";
        return ejecutarConsulta($sql);      
    }

    public function select()
    {
        $sql="SELECT * FROM mensajero where condicion=1";
        return ejecutarConsulta($sql);      
    }
}
 
?>