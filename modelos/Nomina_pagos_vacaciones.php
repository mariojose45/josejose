<?php 
if (strlen(session_id()) < 1) 
session_start();
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class nominapagosvacaciones
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($idempleado,$fecha_solicitud,$fecha_inicio,$fecha_fin,$dias,$motivo){
        $sql = "INSERT INTO vacaciones_empleado
                (idempleado,fecha_solicitud,fecha_inicio,fecha_fin,dias_solicitados,motivo,estado,idusuario,fecha_creacion)
                VALUES('$idempleado','$fecha_solicitud','$fecha_inicio','$fecha_fin','$dias','$motivo',
                'APROBADO','".$_SESSION["idusuario"]."',DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'))";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para editar registros
    public function editar($idvacacion,$idempleado,$fecha_solicitud,$fecha_inicio,$fecha_fin,$dias,$motivo){
        $sql = "UPDATE vacaciones_empleado 
                SET idempleado='$idempleado',
                    fecha_solicitud='$fecha_solicitud',
                    fecha_inicio='$fecha_inicio',
                    fecha_fin='$fecha_fin',
                    dias_solicitados='$dias',
                    motivo='$motivo',
                    idusuario_update='".$_SESSION["idusuario"]."',
                    fecha_update=DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i')
                WHERE idvacacion='$idvacacion'";
        return ejecutarConsulta($sql);
    }

 
    //Implementamos un método para desactivar categorías
    public function desactivar($idvacacion)
    {
        $sql="UPDATE vacaciones_empleado SET 
                    condicion='0',
                    estado='RECHAZADO',
                    idusuario_delete='".$_SESSION["idusuario"]."',
                    fecha_delete=DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i')
                WHERE idvacacion='$idvacacion'";
        return ejecutarConsulta($sql);
    }
 

 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idvacacion)
    {
        $sql="SELECT 
                v.idvacacion,
                v.idempleado,
                date(v.fecha_solicitud) AS fechasolicitud, 
                date(v.fecha_inicio) AS fechainicio,
                date(v.fecha_fin) AS fechafin,
                v.dias_solicitados,
                v.motivo,
                v.estado,
                v.fecha_registro,
                v.condicion
            FROM vacaciones_empleado v 
            WHERE v.idvacacion='$idvacacion'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar(){
        $sql = "SELECT v.*, e.nombres AS empleado 
                FROM vacaciones_empleado v
                INNER JOIN empleados e ON e.idempleado = v.idempleado
                ORDER BY v.idvacacion DESC";
        return ejecutarConsulta($sql);
    }


    // ================== DÍAS GENERADOS, GOZADOS Y PENDIENTES ====================
    public function infoVacaciones($idempleado)
    {
        // Obtener fecha de ingreso
        $sql1 = "SELECT fecha_inicio_laboral FROM empleados WHERE idempleado='$idempleado' LIMIT 1";
        $emp = ejecutarConsultaSimpleFila($sql1);
    
        if (!$emp) {
            return [
                "periodo" => "-",
                "dias_generados" => 0,
                "dias_gozados" => 0,
                "dias_pendientes" => 0
            ];
        }
    
        $fecha_ingreso = new DateTime($emp["fecha_inicio_laboral"]);
        $hoy = new DateTime();
    
        // Años completos trabajados
        $anios_completos = $fecha_ingreso->diff($hoy)->y;
    
        // Si no ha cumplido ni 1 año → no genera vacaciones
        if ($anios_completos <= 0) {
            //print_r($anios_completos);
            return [
                "periodo" => $fecha_ingreso->format("Y-m-d") . " al " . $fecha_ingreso->modify("+1 year")->format("Y-m-d"),
                "dias_generados" => 0,
                "dias_gozados" => 0,
                "dias_pendientes" => 0
            ];
        }
    
        // Días generados → 15 por año
        $dias_generados = $anios_completos * 15;
    
        // Días gozados
        $sql2 = "SELECT IFNULL(SUM(dias_solicitados), 0) AS total
                 FROM vacaciones_empleado
                 WHERE idempleado='$idempleado'
                   AND estado='APROBADO'";
        $usado = ejecutarConsultaSimpleFila($sql2);
        $dias_gozados = $usado['total'];
    
        // Días pendientes
        $dias_pendientes = $dias_generados - $dias_gozados;
    
        // Calcular último período cumplido
        $periodo_inicio = clone $fecha_ingreso;
        $periodo_inicio->modify("+".($anios_completos - 1)." year");
    
        $periodo_fin = clone $periodo_inicio;
        $periodo_fin->modify("+1 year");
    
        return [
            "periodo" => $periodo_inicio->format("Y-m-d") . " al " . $periodo_fin->format("Y-m-d"),
            "dias_generados" => $dias_generados,
            "dias_gozados" => $dias_gozados,
            "dias_pendientes" => $dias_pendientes
        ];
    }

    public function cabecera($idempleado){
        $sql = "SELECT 
		v.*, 
		e.nombres AS empleado,
        date(e.fecha_inicio_laboral) AS fechainiciolaboral,
		u.nombre AS usuario,
		s.imagen AS sucursal_imagen,
		s.nombre AS sucursal_nombre,
		s.nit AS sucursal_nit,
		s.direccion AS sucursal_direccion,
		s.telefono AS sucursal_telefono,
		s.email AS sucursal_email,
		u.nombre AS nombre_usuario
		
                FROM vacaciones_empleado v
                INNER JOIN empleados e ON e.idempleado = v.idempleado
                INNER JOIN usuario u ON u.idusuario=v.idusuario
                LEFT JOIN sucursal s ON s.idsucursal=u.idsucursal
                where v.idempleado='$idempleado'
                ORDER BY v.idvacacion DESC  LIMIT 1";
        return ejecutarConsulta($sql);
    }    

    public function detalleVacaciones($idempleado){
        $sql = "SELECT 
            v.*, 
            date(v.fecha_inicio) AS fechainicio,
            DATE(v.fecha_fin) AS fechafin,
            DATE(v.fecha_solicitud) AS fechasolicitud,
            e.nombres  AS empleado,
            u.nombre  AS usuario,
            s.imagen  AS sucursal_imagen,
            s.nombre  AS sucursal_nombre,
            s.nit  AS sucursal_nit,
            s.direccion  AS sucursal_direccion,
            s.telefono  AS sucursal_telefono,
            s.email  AS sucursal_email,
            u.nombre  AS nombre_usuario
        FROM vacaciones_empleado v
        INNER  JOIN empleados e  ON e.idempleado = v.idempleado
        INNER  JOIN usuario u  ON u.idusuario=v.idusuario
        INNER  JOIN sucursal s  ON s.idsucursal=u.idsucursal
        WHERE v.idempleado='$idempleado'
        ORDER  BY v.idvacacion  DESC ";
        return ejecutarConsulta($sql);
    }  

    public function reciboempleado($idvacacion){
        $sql = "SELECT 
		v.*, 
        date(v.fecha_inicio) AS fechainicio,
        DATE(v.fecha_fin) AS fechafin,
        DATE(v.fecha_solicitud) AS fechasolicitud,        
		e.nombres AS empleado,
        e.cui,
        e.puesto,
        date(e.fecha_inicio_laboral) AS fechainiciolaboral,
		u.nombre AS usuario,
		s.imagen AS sucursal_imagen,
		s.nombre AS sucursal_nombre,
		s.nit AS sucursal_nit,
		s.direccion AS sucursal_direccion,
		s.telefono AS sucursal_telefono,
		s.email AS sucursal_email,
		u.nombre AS nombre_usuario
		
                FROM vacaciones_empleado v
                INNER JOIN empleados e ON e.idempleado = v.idempleado
                INNER JOIN usuario u ON u.idusuario=v.idusuario
                LEFT JOIN sucursal s ON s.idsucursal=u.idsucursal
                where v.idvacacion='$idvacacion'
                ORDER BY v.idvacacion DESC  LIMIT 1";
        return ejecutarConsulta($sql);
    }    

       
}
 
?>