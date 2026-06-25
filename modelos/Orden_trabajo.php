<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class OrdenTRbajo
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($idcliente,$fecha_hora,$modelo,$serie,$descripcion_equipo,$reparacion_equipo,$idusuario)
    {
        $sql="INSERT INTO orden (idcliente,fecha_hora,modelo,serie,descripcion_equipo,reparacion_equipo,idusuario,condicion)
        VALUES ('$idcliente','$fecha_hora','$modelo','$serie','$descripcion_equipo','$reparacion_equipo','$idusuario','1')";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para editar registros
    public function editar($idorden,$idcliente,$fecha_hora,$modelo,$serie,$descripcion_equipo,$reparacion_equipo,$idusuario)
    {
        $sql="UPDATE orden SET idcliente='$idcliente',fecha_hora='$fecha_hora',modelo='$modelo',serie='$serie',descripcion_equipo='$descripcion_equipo',reparacion_equipo='$reparacion_equipo',idusuario_modificacion='$idusuario' WHERE idorden='$idorden'";
        return ejecutarConsulta($sql);
    }



    public function editar_3($idorden2,$detalle_tecnico,$tipo_status,$tecnico,$fecha_hora_detalle)
    {
        $sql="UPDATE orden SET detalle_tecnico='$detalle_tecnico',tipo_status='$tipo_status',tecnico='$tecnico',fecha_hora_detalle='$fecha_hora_detalle' WHERE idorden='$idorden2'";
        return ejecutarConsulta($sql);
    }    
 

  
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idorden)
    {
        $sql="SELECT 
                o.idorden,
                o.idcliente,
                DATE(o.fecha_hora) as fecha,
                o.modelo,
                o.serie,
                o.descripcion_equipo,
                o.reparacion_equipo,
                o.idusuario,
                o.condicion,
                o.idusuario_modificacion,
                DATE(o.fecha_hora_modificacion) as fechamodi,
                o.diagnostico_reparacion,
                o.idusuario_diagnostico_reparacion,
                DATE(o.fecha_hora_diagnostico_reparacion) as fecha_diagnostico
                FROM orden o WHERE o.idorden='$idorden'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function mostrar_idtecnico($idorden)
    {
        $sql="SELECT 
                o.idorden,
                o.idcliente,
                DATE(o.fecha_hora) as fecha,
                o.modelo,
                o.serie,
                o.descripcion_equipo,
                o.reparacion_equipo,
                o.idusuario,
                o.condicion,
                o.idusuario_modificacion,
                DATE(o.fecha_hora_modificacion) as fechamodi,
                o.diagnostico_reparacion,
                o.idusuario_diagnostico_reparacion,
                DATE(o.fecha_hora_diagnostico_reparacion) as fecha_diagnostico
                FROM orden o WHERE o.idorden='$idorden'";
        return ejecutarConsultaSimpleFila($sql);
    }    

    public function activar($idorden)
    {
        $sql="UPDATE orden SET condicion='0' WHERE idorden='$idorden'";
        return ejecutarConsulta($sql);
    }

 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
o.idorden,
o.idcliente,
p.nombre as cliente_nombre,
p.telefono as cliente_telefono,
DATE(o.fecha_hora) as fecha,
o.modelo,
o.serie,
o.descripcion_equipo,
o.reparacion_equipo,
o.idusuario,
u.nombre as usuario,
o.condicion,
o.idusuario_modificacion,
DATE(o.fecha_hora_modificacion) as fechamodi,
o.diagnostico_reparacion,
o.idusuario_diagnostico_reparacion,
DATE(o.fecha_hora_diagnostico_reparacion) as fecha_diagnostico,
o.detalle_tecnico,
o.tipo_status,
o.tecnico,
DATE(o.fecha_hora_detalle) as fechahoratecnico
FROM orden o 
INNER JOIN usuario u ON u.idusuario=o.idusuario
INNER JOIN persona p ON p.idpersona=o.idcliente";
        return ejecutarConsulta($sql);      
    }

    public function cabecera($idorden)
    {
        $sql="SELECT 
                o.idorden,
                o.idcliente,
                p.nombre as cliente_nombre,
                p.telefono as cliente_telefono,
                DATE(o.fecha_hora) as fecha,
                o.modelo,
                o.serie,
                o.descripcion_equipo,
                o.reparacion_equipo,
                o.idusuario,
                u.nombre as usuario,
                o.condicion,
                o.idusuario_modificacion,
                DATE(o.fecha_hora_modificacion) as fecha_modifique
                FROM orden o 
                INNER JOIN usuario u ON u.idusuario=o.idusuario
                INNER JOIN persona p ON p.idpersona=o.idcliente
                WHERE o.idorden='$idorden'";
        return ejecutarConsulta($sql);      
    }

}
 
?>