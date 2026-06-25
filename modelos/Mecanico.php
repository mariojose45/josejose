<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class Mecanico
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }

    public function listar($fecha_inicio_reporte,$fecha_fin_reporte)
    {
        $sql="SELECT
            i.idingreso_vehiculo,
            i.idcliente,
            i.idusuario,
            i.idsucursal,
            i.estado,
            i.fechaCreacion,
            i.no_placa,
            i.no_chasis,
            i.serie,
            i.no_motor,
            i.modelo,
            i.km,
            i.checkLado1,
            i.descripcionLado1,
            i.checkLado2,
            i.descripcionLado2,
            i.checkLado3,
            DATE(i.fechaCreacion) AS fecha,
            i.descripcionLado3,
            i.checkLado4,
            i.descripcionLado4,
            i.checkLado5,
            i.descripcionLado5,
            i.facturado,
            i.check_bateria,
            i.check_tcs,
            i.check_motor,
            i.check_aceite,
            i.check_airbag,
            i.check_tpms,
            i.check_abs,
            i.trabajos_detalle,
            i.observaciones_adicionales,
            i.idvendedor,
            p.nombre AS nombre_cliente,
            u.nombre AS nombre_usuario,
            m.nombre AS nombre_marca
        FROM
            ingreso_vehiculo i
            INNER JOIN persona p ON i.idcliente = p.idpersona
            INNER JOIN usuario u ON i.idusuario = u.idusuario
            INNER JOIN marca m ON i.idvendedor = m.idmarca
            WHERE DATE(i.fechaCreacion) >= '$fecha_inicio_reporte'
            AND DATE(i.fechaCreacion) <= '$fecha_fin_reporte'";
        return ejecutarConsulta($sql);      
    }

    public function insertar_detalles($idingreso_vehiculo, $detalles, $revisiones,
        $horaInicio,$horaFinalizada,$tecnico,$gradoAceite,$filtroAceite,$filtroAire,$filtroCombustible,$observaciones) {

        $sw = true;

        //eliminar detalles
        $sqlDetalles="DELETE from detalle_ingreso_vehiculo where idingreso_vehiculo=".$idingreso_vehiculo."";
        ejecutarConsulta($sqlDetalles);
        //eliminar revisiones
        $sqlRevisiones="DELETE from detalle_revision_vehiculo where idingreso_vehiculo=".$idingreso_vehiculo."";
        ejecutarConsulta($sqlRevisiones);

        foreach ($detalles as $detalle) {
            $sql_detalle = "INSERT INTO detalle_ingreso_vehiculo (idingreso_vehiculo,idarticulo,cantidad)
                            VALUES ('$idingreso_vehiculo',
                                    '{$detalle['idarticulo']}',
                                    '{$detalle['cantidad']}')";
            if (!ejecutarConsulta($sql_detalle)) {
                $sw = false;
            }
        }
        foreach ($revisiones as $revision) { 
            $nombre_revision = $revision['nombre'];
            $estado_100 = $revision['estado100'] ? 1 : 0;
            $estado_75 = $revision['estado75'] ? 1 : 0;
            $estado_50 = $revision['estado50'] ? 1 : 0;
            $cambio_sugerido = $revision['cambioSugerido'] ? 1 : 0;

            $sql_revision = "INSERT INTO detalle_revision_vehiculo (idingreso_vehiculo, nombre_revision, estado_100, estado_75, estado_50, cambio_sugerido)
                            VALUES ('$idingreso_vehiculo',
                                    '$nombre_revision',
                                    '$estado_100',
                                    '$estado_75',
                                    '$estado_50',
                                    '$cambio_sugerido')";

            if (!ejecutarConsulta($sql_revision)) {
                $sw = false;
            }
        }

        $sqlIngresoVehiculo="UPDATE ingreso_vehiculo SET horaInicio='$horaInicio',horaFinalizada='$horaFinalizada',tecnico='$tecnico',
            gradoAceite='$gradoAceite',filtroAceite='$filtroAceite',filtroAire='$filtroAire',filtroCombustible='$filtroCombustible',observaciones='$observaciones'
            WHERE idingreso_vehiculo='$idingreso_vehiculo'";
            ejecutarConsulta($sqlIngresoVehiculo);
        //return $sw;
    }

    public function mostrarDetalles($idingreso_vehiculo) {
        $sql_articulos = "SELECT d.iddetalle_ingreso_vehiculo, a.idarticulo, a.nombre, d.cantidad 
                        FROM detalle_ingreso_vehiculo d
                        INNER JOIN articulo a ON d.idarticulo = a.idarticulo
                        WHERE d.idingreso_vehiculo = '$idingreso_vehiculo'";
        //$detalles = ejecutarConsulta($sql_articulos);
        $rspta=ejecutarConsulta($sql_articulos);
        $rows_detalles = array();
        while ($reg=$rspta->fetch_object()){
            $rows_detalles[] = $reg;
        }
        $sql_revisiones = "SELECT iddetalle_revision_vehiculo, nombre_revision, estado_100, estado_75, estado_50, cambio_sugerido
                        FROM detalle_revision_vehiculo
                        WHERE idingreso_vehiculo = '$idingreso_vehiculo'";
        //$revisiones = ejecutarConsulta($sql_revisiones);
        $rspta_revisiones=ejecutarConsulta($sql_revisiones);
        $rows_revisiones = array();
        while ($reg=$rspta_revisiones->fetch_object()){
            $rows_revisiones[] = $reg;
        }

        $sql_datos = "SELECT horaInicio, horaFinalizada, tecnico, gradoAceite, filtroAceite, filtroAire, filtroCombustible, observaciones
                    FROM ingreso_vehiculo
                    WHERE idingreso_vehiculo = '$idingreso_vehiculo'";
        $datos_ingreso = ejecutarConsultaSimpleFila($sql_datos);

        return [
            'detalles' => $rows_detalles,
            'revisiones' => $rows_revisiones,
            'datos' => $datos_ingreso
        ];
    }

    public function mostrar($idingreso_vehiculo){
        $sql = "SELECT * FROM ingreso_vehiculo WHERE idingreso_vehiculo = '$idingreso_vehiculo'";
        return ejecutarConsultaSimpleFila($sql);
    }
}
 
?>