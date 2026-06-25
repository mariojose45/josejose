<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Cotijamiento
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar(
        $idguias_excel,
        $idguias_excel2,
        $fecha_hora,
        $total_liquidacion,
        $descripcion,
        $idventa,
        $fechaventa,
        $total_venta,
        $guia_transporte,
        $iddetalle_guias_excel,
        $idguia,
        $mventa,
        $comision,
        $vcomision,
        $mliquido,
        $autorizacion,
        $ctabanco,
        $vflete,
        $idusuario,
        $idtransporte,
        $subtotal,
        $estado_venta
    ) {
        $sql1 = "INSERT INTO cotejamientoventas_guias (idguias_excel,idguias_excel2,fecha_hora,total_liquidacion,descripcion,condicion,idusuario,idsucursal)
        VALUES ('$idguias_excel','$idguias_excel2','$fecha_hora','$total_liquidacion','$descripcion','1','$idusuario','" . $_SESSION["idsucursal"] . "' )";
        //  print_r($sql1);
        $idcotejamientoventas_guiasnew = ejecutarConsulta_retornarID($sql1);


        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($idventa)) {
            $sql_detalle = "INSERT INTO detalle_cotejamientoventas_guias(idcotejamientoventas_guias,idventa,fechaventa,total_venta,guia_transporte,
                iddetalle_guias_excel,idguia,mventa,comision,vcomision,mliquido,autorizacion,ctabanco,vflete,idtransporte,subtotal) VALUES ('$idcotejamientoventas_guiasnew', '$idventa[$num_elementos]','$fechaventa[$num_elementos]','$total_venta[$num_elementos]','$guia_transporte[$num_elementos]','$iddetalle_guias_excel[$num_elementos]','$idguia[$num_elementos]','$mventa[$num_elementos]','$comision[$num_elementos]','$vcomision[$num_elementos]','$mliquido[$num_elementos]','$autorizacion[$num_elementos]','$ctabanco[$num_elementos]','$vflete[$num_elementos]','$idtransporte[$num_elementos]','$subtotal[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            if ($estado_venta[$num_elementos] == 'COMPLETO') {
                print_r($estado_venta[$num_elementos]);
                # code...
                if ($subtotal[$num_elementos] <= '0') {
                    # code...
                    $sql = "UPDATE venta SET estadoguia='LIQUIDADOGUIA' WHERE idventa='$idventa[$num_elementos]'";
                    ejecutarConsulta($sql);
                }

            }



            $num_elementos = $num_elementos + 1;
        }

        return $idcotejamientoventas_guiasnew;
    }

    public function cabeceracotejacientoventas($idcotejamientoventas_guias)
    {
        $sql = "SELECT 
                c.idcotejamientoventas_guias,
                c.idguias_excel,
                c.idguias_excel2,
                date(c.fecha_hora) as fecha,
                c.total_liquidacion,
                c.descripcion,
                c.condicion,
                c.idusuario,
                u.nombre AS nombre_usuario,
                s.imagen AS sucursal_imagen,
                s.nombre AS sucursal_nombre,
                s.direccion AS sucursal_direccion,
                s.nit AS sucursal_nit,
                s.telefono AS sucursal_telefono,
                s.email AS sucursal_email
             FROM cotejamientoventas_guias c
             INNER JOIN usuario u ON u.idusuario=c.idusuario
             INNER JOIN sucursal s ON s.idsucursal=c.idsucursal
            where c.idcotejamientoventas_guias='$idcotejamientoventas_guias'
            ";
        return ejecutarConsulta($sql);
    }

    public function reporteeceldetallecotejacionventas($idcotejamientoventas_guias)
    {
        $sql = "SELECT 
                    d.iddetalle_cotejamientoventas_guias,
                    d.idcotejamientoventas_guias,
                    d.idventa,
                    date(d.fechaventa) as fecha,
                    d.total_venta,
                    d.guia_transporte,
                    d.iddetalle_guias_excel,
                    d.idguia,
                    d.mventa,
                    d.comision,
                    d.mliquido,
                    d.vcomision,
                    d.autorizacion,
                    d.ctabanco,
                    d.vflete,
                    (d.total_venta-d.mliquido) AS restan,
                    case  when t.nombre='' then 0 else  IFNULL(t.nombre,0) end   as transportes
                 FROM detalle_cotejamientoventas_guias d
                 LEFT JOIN transporte t ON t.idtransporte=d.idtransporte
            where d.idcotejamientoventas_guias='$idcotejamientoventas_guias'
            ";
        return ejecutarConsulta($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idguias_excel)
    {
        $sql = "SELECT 
                g.idguias_excel,
                g.idtransporte,
                g.obervacioncargaexcel,
                g.fecha_cargaExcel,
                g.estado,
                t.nombre
             FROM guias_excel g 
            INNER JOIN transporte t ON g.idtransporte=g.idtransporte
             WHERE  g.idguias_excel='$idguias_excel'  ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function mostrardetalle($idguias_excel)
    {

        $sqldetalle = "SELECT 
                d.iddetalle_guias_excel,
                d.idguias_excel,
                d.idguia,
                Date(d.fechaliqui) as fechaguia,
                d.mventa,
                d.comision,
                d.vcomision,
                d.mliquido,
                d.autorizacion,  
                d.ctabanco,
                d.vflete,
                v.idventa,
                v.idcliente,
                date(v.fecha_hora) as fechaventa,
                v.total_venta,
                v.guia_transporte,
                d.idtransporte,
                t.nombre AS transportes,
                v.estado_venta
                 FROM detalle_guias_excel d
                INNER JOIN venta v ON v.guia_transporte=d.idguia
                LEFT JOIN transporte t ON t.idtransporte=d.idtransporte
              where v.estado='Aceptado' AND v.estadoguia='PENDIENTEGUIA' and  d.idguias_excel=" . $idguias_excel;

        #echo $sql;
        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    //Implementar un método para listar los registros
    public function listar($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                c.idcotejamientoventas_guias,
                c.idguias_excel,
                c.idguias_excel2,
                date(c.fecha_hora) as fecha,
                c.total_liquidacion,
                c.descripcion,
                c.condicion,
                c.idusuario,
                u.nombre AS nombre_usuario
             FROM cotejamientoventas_guias c
             INNER JOIN usuario u ON u.idusuario=c.idusuario
            where c.idusuario='" . $_SESSION["idusuario"] . "' and DATE(c.fecha_hora)>='$fecha_inicio' AND DATE(c.fecha_hora)<='$fecha_fin' 
             ";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql = "SELECT * FROM categoria where condicion=1";
        return ejecutarConsulta($sql);
    }


    public function listarVentasparcotijamientoxfecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT  
                v.idventa,
                DATE(v.fecha_hora) as fecha,
                v.idcliente,
                p.nombre as cliente, 
                u.idusuario,
                u.nombre as usuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta as total_venta2,
                (v.total_venta-(SELECT SUM(d1.mliquido) FROM detalle_cotejamientoventas_guias d1 WHERE d1.idventa=v.idventa LIMIT 1)) AS total_venta,
                v.impuesto,
                v.estado,
                v.cefectivo,
                v.rescambio,
                v.nombre_vendedor,
                v.autorizacionEcoFactura, 
                v.serie_ecoFactura,
                v.numero_ecoFactura, 
                v.fechaCertificacion_ecoFactura,
                v.modu_ventas_envio,
                v.forma_pago, 
                v.tarjeta_numero_autorizacion,
                v.cefectivo_tarjeta,
                v.guia_transporte,
                v.entregado_peniente_otro,
                v.telefono_entregaproductos,
                v.direcciion_entregaproductos, 
                v.correlativo_trasporte,
                (SELECT r.nombre FROM red_tipo r WHERE r.idred=v.idred LIMIT 1) AS red,
                (SELECT t.nombre FROM transporte t WHERE t.idtransporte=v.idtransporte LIMIT 1) AS transportes                              
            FROM venta v 
                INNER JOIN persona p ON v.idcliente=p.idpersona 
                INNER JOIN usuario u ON v.idusuario=u.idusuario
                WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin'  
                and  v.tipo_comprobante<>'Operacionpendiente' and  v.tipo_comprobante<>'OPERACION FINALIZADA' and v.idtransporte<>''
                AND  v.estadoguia='PENDIENTEGUIA' and v.estado<>'Anulado'
                ";
        return ejecutarConsulta($sql);
    }




}

?>