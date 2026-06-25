<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";




class ParqueoOperaciones
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertarLectura($tipo_vehiculo, $placa, $fecha_ingreso)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        $sql = "INSERT INTO lectura (tipo_vehiculo, placa, fecha_ingreso,fecha_creacion,idusuario,idsucursal,condicion,estado)
            VALUES ('$tipo_vehiculo', '$placa', '$fecha_ingreso','$fechaHora','$_SESSION[idusuario]','$_SESSION[idsucursal]','1','LECTURA')";
        $idlecturanew = ejecutarConsulta_retornarID($sql);

        return $idlecturanew;

    }

    public function guardarCobro(
        $idlectura,
        $fecha_ingreso_cobro,
        $tiempo_gracia_ticket_cobro,
        $p_fraccion,
        $p_hora,
        $tarifa_dia,
        $tarifa_noche,
        $tarifa_evento,
        $tipo_vehiculo_cobro,
        $numero_ticket,
        $tip_evento_cobro,
        $numeroplacaEvento,
        $tipo_vehiculoEventos,
        $fecha_cobro,
        $tiempo_transcurrido_horas,
        $tiempo_transcurrido_minutos,
        $nit,
        $nombre_cliente,
        $direccion_cliente,
        $idcliente,
        $tipo_documento_cliente,
        $total_venta,
        $cefectivo,
        $ctarjeta,
        $ctransferencia,
        $ccredito,
        $observacion_credito,
        $rescambio
    ) {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        $sql = "INSERT INTO cobros_tickets (
            idlectura,
            fecha_ingreso_cobro,
            tiempo_gracia_ticket_cobro,
            p_fraccion,
            p_hora,
            tarifa_dia,
            tarifa_noche,
            tarifa_evento,
            tipo_vehiculo_cobro,
            numero_ticket,
            tip_evento_cobro,
            numeroplacaEvento,
            tipo_vehiculoEventos,
            fecha_cobro,
            tiempo_transcurrido_horas,
            tiempo_transcurrido_minutos,
            idcliente,
            tipo_documento_cliente,
            total_venta,
            cefectivo,
            ctarjeta,
            ctransferencia,
            ccredito,
            observacion_credito,
            rescambio,
            idusuario,
            idsucursal,
            fecha_creacion,
            condicion,
            estado
        )
            VALUES ('$idlectura',
            '$fecha_ingreso_cobro',
            '$tiempo_gracia_ticket_cobro',
            '$p_fraccion',
            '$p_hora',
            '$tarifa_dia',
            '$tarifa_noche',
            '$tarifa_evento',
            '$tipo_vehiculo_cobro',
            '$numero_ticket',
            '$tip_evento_cobro',
            '$numeroplacaEvento',
            '$tipo_vehiculoEventos',
            '$fecha_cobro',
            '$tiempo_transcurrido_horas',
            '$tiempo_transcurrido_minutos',
            '$idcliente',
            '$tipo_documento_cliente',
            '$total_venta',
            '$cefectivo',
            '$ctarjeta',
            '$ctransferencia',
            '$ccredito',
            '$observacion_credito',
            '$rescambio',
            '" . $_SESSION["idusuario"] . "',
            '" . $_SESSION["idsucursal"] . "',
            '$fechaHora',
            '1',
            'Aceptado'
        )";
        $idcobros_ticketsnew = ejecutarConsulta_retornarID($sql);

        return $idcobros_ticketsnew;

    }


    //Implementamos un método para desactivar categorías
    public function desactivar($idlectura)
    {
        $sql = "UPDATE lectura SET condicion='0' WHERE idlectura='$idlectura'";
        return ejecutarConsulta($sql);
    }



    //Implementar un método para mostrar los datos de un registro a modificar
    public function validarnumero_ticket($numero_ticket)
    {
        $sql = "SELECT 
                l.*,
                t.*
            FROM lectura l 
            INNER JOIN tarifas_precios t ON t.idsucursal=l.idsucursal
            WHERE l.estado='LECTURA' AND
            l.idlectura='$numero_ticket'  AND l.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function tarifadia()
    {
        $sql = "SELECT 
                t.*
            FROM tarifas_precios t
            WHERE t.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar($fecha_inicio_lectura, $fecha_fin_lectura)
    {
        $sql = "SELECT 
                    l.idlectura,
                    l.tipo_vehiculo,
                    l.placa,
                    l.fecha_ingreso,
                    l.idusuario,
                    l.idsucursal,
                    u.nombre AS usuario,
                    l.condicion
                FROM lectura l
                INNER JOIN usuario u ON u.idusuario=l.idusuario
                WHERE  l.idsucursal='" . $_SESSION["idsucursal"] . "' and l.idusuario='" . $_SESSION["idusuario"] . "'
                AND  DATE(l.fecha_ingreso)>='$fecha_inicio_lectura' 
                AND DATE(l.fecha_ingreso)<='$fecha_fin_lectura' AND l.estado='LECTURA' ";
        return ejecutarConsulta($sql);
    }

    public function listarxsucursalParqueo($fecha_inicio_lectura, $fecha_fin_lectura, $idsucursal2)
    {
        $sql = "SELECT 
                    l.idlectura,
                    l.tipo_vehiculo,
                    l.placa,
                    l.fecha_ingreso,
                    l.idusuario,
                    l.idsucursal,
                    u.nombre AS usuario,
                    l.condicion
                FROM lectura l
                INNER JOIN usuario u ON u.idusuario=l.idusuario
                WHERE  l.idsucursal='$idsucursal2'
                AND  DATE(l.fecha_ingreso)>='$fecha_inicio_lectura' 
                AND DATE(l.fecha_ingreso)<='$fecha_fin_lectura' AND l.estado='LECTURA' ";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function lecturacabecera($idlectura)
    {
        $sql = "SELECT 
                    l.idlectura,
                    l.tipo_vehiculo,
                    l.placa,
                    l.fecha_ingreso,
                    l.idusuario,
                    l.idsucursal,
                    u.nombre AS usuario,
                    l.condicion,
                    s.idsucursal,
                    s.nombre as sucursal_nombre,
                    s.nombre_comercial,
                    s.nombre_fel,
                    s.direccion as sucursal_direccion,
                    s.direccion_fiscal,
                    s.telefono as sucursal_telefono,
                    s.nit as sucursal_nit,
                    s.email as sucursal_email,
                    s.imagen as sucursal_imagen,
                    s.condicion as sucursal_condicion
                FROM lectura l
                INNER JOIN usuario u ON u.idusuario=l.idusuario
                inner join sucursal s on s.idsucursal=l.idsucursal
                where l.idlectura='$idlectura'";
        return ejecutarConsulta($sql);
    }

    public function informaciontickets()
    {
        $sql = "SELECT * FROM informacion_ticket";
        return ejecutarConsulta($sql);
    }



    public function cobrocabecera($idcobros_tickets)
    {
        $sql = "SELECT 
            c.idcobros_tickets,
            c.idlectura,
            c.fecha_ingreso_cobro,
            c.tiempo_gracia_ticket_cobro,
            c.p_fraccion,
            c.p_hora,
            c.tarifa_dia,
            c.tarifa_noche,
            c.tarifa_evento,
            c.tipo_vehiculo_cobro,
            c.numero_ticket,
            c.tip_evento_cobro,
            c.numeroplacaEvento,
            c.tipo_vehiculoEventos,
            c.fecha_cobro,
            c.tiempo_transcurrido_horas,
            c.tiempo_transcurrido_minutos,
            c.idcliente,
            c.tipo_documento_cliente,
            c.total_venta,
            c.cefectivo,
            c.ctarjeta,
            c.ctransferencia,
            c.ccredito,
            c.observacion_credito,
            c.rescambio,
            c.idusuario,
            c.idusuario_anulacion,
            (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=c.idusuario_anulacion LIMIT 1) AS usuarioAnulacion,
            c.idsucursal,
            c.fecha_creacion,
            c.fecha_anulacion,
            c.condicion,
            c.estado,
            c.tipo_operacion,
            c.idcuadre_caja,
            s.idsucursal,
            s.nombre as sucursal_nombre,
            s.nombre_comercial,
            s.nombre_fel,
            s.direccion as sucursal_direccion,
            s.direccion_fiscal,
            s.telefono as sucursal_telefono,
            s.nit as sucursal_nit,
            s.email as sucursal_email,
            s.imagen as sucursal_imagen,
            s.condicion as sucursal_condicion,
            l.placa,
            p.nombre AS nombre_cliente,
            p.tipo_documento AS tipo_documentoCliente,
            p.num_documento AS num_docCliente,
            p.direccion AS direccionCliente,
            p.telefono AS telesCliente       
            FROM cobros_tickets c 
            INNER JOIN persona p ON p.idpersona=c.idcliente
            iNNER JOIN usuario u ON u.idusuario=c.idusuario
            inner join sucursal s on s.idsucursal=c.idsucursal
            left JOIN lectura l ON l.idlectura=c.idlectura
            where c.idcobros_tickets='$idcobros_tickets'";
        return ejecutarConsulta($sql);
    }


    public function listarFac($fecha_inicio_lectura_fac, $fecha_fin_lectura_fac)
    {
        $sql = "SELECT 
                c.idcobros_tickets,
                c.idlectura,
                c.fecha_ingreso_cobro,
                c.tiempo_gracia_ticket_cobro,
                c.p_fraccion,
                c.p_hora,
                c.tarifa_dia,
                c.tarifa_noche,
                c.tarifa_evento,
                c.tipo_vehiculo_cobro,
                c.numero_ticket,
                c.tip_evento_cobro,
                c.numeroplacaEvento,
                c.tipo_vehiculoEventos,
                c.fecha_cobro,
                c.tiempo_transcurrido_horas,
                c.tiempo_transcurrido_minutos,
                c.idcliente,
                p.nombre AS nombre_cliente,
                p.tipo_documento AS tipo_documentoCliente,
                p.num_documento AS num_docCliente,
                p.direccion AS direccionCliente,
                p.telefono AS telesCliente,
                c.tipo_documento_cliente,
                c.total_venta,
                c.cefectivo,
                c.ctarjeta,
                c.ctransferencia,
                c.ccredito,
                c.observacion_credito,
                c.rescambio,
                c.idusuario,
                c.idusuario_anulacion,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=c.idusuario_anulacion LIMIT 1) AS usuarioAnulacion,
                c.idsucursal,
                c.fecha_creacion,
                c.fecha_anulacion,
                c.condicion,
                c.estado,
                c.tipo_operacion,
                c.idcuadre_caja,
                s.idsucursal,
	             s.nombre as sucursal_nombre,
	             s.nombre_comercial,
	             s.nombre_fel,
	             s.direccion as sucursal_direccion,
	             s.direccion_fiscal,
	             s.telefono as sucursal_telefono,
	             s.nit as sucursal_nit,
	             s.email as sucursal_email,
	             s.imagen as sucursal_imagen,
	             s.condicion as sucursal_condicion,
	             l.tipo_vehiculo,
	             l.placa,
                 u.nombre as nombreUsuario,
                 c.autorizacionEcoFactura,
	             c.serie_ecoFactura,
	             c.numero_ecoFactura,
	             c.fechaCertificacion_ecoFactura
            FROM cobros_tickets c 
            INNER JOIN persona p ON p.idpersona=c.idcliente
            iNNER JOIN usuario u ON u.idusuario=c.idusuario
            inner join sucursal s on s.idsucursal=c.idsucursal
            INNER JOIN lectura l ON l.idlectura=c.idlectura
                WHERE  c.idsucursal='" . $_SESSION["idsucursal"] . "'
                AND  DATE(c.fecha_cobro)>='$fecha_inicio_lectura_fac' 
                AND DATE(c.fecha_cobro)<='$fecha_fin_lectura_fac'";
        return ejecutarConsulta($sql);
    }


    public function listarFacxSucursal($fecha_inicio_lectura_fac, $fecha_fin_lectura_fac, $idsucursal3)
    {
        $sql = "SELECT 
                c.idcobros_tickets,
                c.idlectura,
                c.fecha_ingreso_cobro,
                c.tiempo_gracia_ticket_cobro,
                c.p_fraccion,
                c.p_hora,
                c.tarifa_dia,
                c.tarifa_noche,
                c.tarifa_evento,
                c.tipo_vehiculo_cobro,
                c.numero_ticket,
                c.tip_evento_cobro,
                c.numeroplacaEvento,
                c.tipo_vehiculoEventos,
                c.fecha_cobro,
                c.tiempo_transcurrido_horas,
                c.tiempo_transcurrido_minutos,
                c.idcliente,
                p.nombre AS nombre_cliente,
                p.tipo_documento AS tipo_documentoCliente,
                p.num_documento AS num_docCliente,
                p.direccion AS direccionCliente,
                p.telefono AS telesCliente,
                c.tipo_documento_cliente,
                c.total_venta,
                c.cefectivo,
                c.ctarjeta,
                c.ctransferencia,
                c.ccredito,
                c.observacion_credito,
                c.rescambio,
                c.idusuario,
                c.idusuario_anulacion,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=c.idusuario_anulacion LIMIT 1) AS usuarioAnulacion,
                c.idsucursal,
                c.fecha_creacion,
                c.fecha_anulacion,
                c.condicion,
                c.estado,
                c.tipo_operacion,
                c.idcuadre_caja,
                s.idsucursal,
	             s.nombre as sucursal_nombre,
	             s.nombre_comercial,
	             s.nombre_fel,
	             s.direccion as sucursal_direccion,
	             s.direccion_fiscal,
	             s.telefono as sucursal_telefono,
	             s.nit as sucursal_nit,
	             s.email as sucursal_email,
	             s.imagen as sucursal_imagen,
	             s.condicion as sucursal_condicion,
	             l.tipo_vehiculo,
	             l.placa,
                 u.nombre as nombreUsuario,
                 c.autorizacionEcoFactura,
	             c.serie_ecoFactura,
	             c.numero_ecoFactura,
	             c.fechaCertificacion_ecoFactura
            FROM cobros_tickets c 
            INNER JOIN persona p ON p.idpersona=c.idcliente
            iNNER JOIN usuario u ON u.idusuario=c.idusuario
            inner join sucursal s on s.idsucursal=c.idsucursal
            INNER JOIN lectura l ON l.idlectura=c.idlectura
                WHERE  c.idsucursal='$idsucursal3'
                AND  DATE(c.fecha_cobro)>='$fecha_inicio_lectura_fac' 
                AND DATE(c.fecha_cobro)<='$fecha_fin_lectura_fac'";
        return ejecutarConsulta($sql);
    }

    public function totalesDashboardParqueo($idsucursal, $fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
            (SELECT IFNULL(SUM(total_venta),0) FROM cobros_tickets WHERE DATE(fecha_cobro)>='$fecha_inicio' AND DATE(fecha_cobro)<='$fecha_fin' AND estado='Aceptado' AND (idsucursal='$idsucursal' OR '$idsucursal'='' OR '$idsucursal'='0')) as total_ingresos,
            (SELECT COUNT(*) FROM cobros_tickets WHERE DATE(fecha_cobro)>='$fecha_inicio' AND DATE(fecha_cobro)<='$fecha_fin' AND estado='Aceptado' AND (idsucursal='$idsucursal' OR '$idsucursal'='' OR '$idsucursal'='0')) as vehiculos_cobrados,
            (SELECT COUNT(*) FROM lectura WHERE DATE(fecha_ingreso)>='$fecha_inicio' AND DATE(fecha_ingreso)<='$fecha_fin' AND estado='LECTURA' AND condicion='1' AND (idsucursal='$idsucursal' OR '$idsucursal'='' OR '$idsucursal'='0')) as lecturas_pendientes,
            (SELECT IFNULL(AVG(TIMESTAMPDIFF(MINUTE, fecha_ingreso_cobro, fecha_cobro)),0) FROM cobros_tickets WHERE DATE(fecha_cobro)>='$fecha_inicio' AND DATE(fecha_cobro)<='$fecha_fin' AND estado='Aceptado' AND (idsucursal='$idsucursal' OR '$idsucursal'='' OR '$idsucursal'='0')) as tiempo_promedio_minutos
        ";
        return ejecutarConsulta($sql);
    }

    public function graficoLecturasParqueo($idsucursal, $fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE_FORMAT(fecha_ingreso, '%d/%m/%Y') as fecha, COUNT(*) as total
                FROM lectura
                WHERE DATE(fecha_ingreso)>='$fecha_inicio' AND DATE(fecha_ingreso)<='$fecha_fin' 
                AND condicion='1' 
                AND (idsucursal='$idsucursal' OR '$idsucursal'='' OR '$idsucursal'='0')
                GROUP BY DATE_FORMAT(fecha_ingreso, '%d/%m/%Y'), DATE(fecha_ingreso)
                ORDER BY DATE(fecha_ingreso) ASC";
        return ejecutarConsulta($sql);
    }

    public function graficoCobrosParqueo($idsucursal, $fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE_FORMAT(fecha_cobro, '%d/%m/%Y') as fecha, SUM(total_venta) as total
                FROM cobros_tickets
                WHERE DATE(fecha_cobro)>='$fecha_inicio' AND DATE(fecha_cobro)<='$fecha_fin' 
                AND estado='Aceptado'
                AND (idsucursal='$idsucursal' OR '$idsucursal'='' OR '$idsucursal'='0')
                GROUP BY DATE_FORMAT(fecha_cobro, '%d/%m/%Y'), DATE(fecha_cobro)
                ORDER BY DATE(fecha_cobro) ASC";
        return ejecutarConsulta($sql);
    }

    public function listarTotalUsuariosParqueo($idsucursal, $fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                    u.nombre AS usuario, 
                    s.nombre AS sucursal, 
                    SUM(c.total_venta) AS total_venta
                FROM cobros_tickets c
                INNER JOIN usuario u ON c.idusuario = u.idusuario
                INNER JOIN sucursal s ON c.idsucursal = s.idsucursal
                WHERE DATE(c.fecha_cobro) >= '$fecha_inicio' AND DATE(c.fecha_cobro) <= '$fecha_fin'
                AND c.estado = 'Aceptado'
                AND (c.idsucursal = '$idsucursal' OR '$idsucursal' = '' OR '$idsucursal' = '0')
                GROUP BY u.idusuario, s.idsucursal
                ORDER BY total_venta DESC";
        return ejecutarConsulta($sql);
    }

    public function Cierreabecera($idcuadre_caja)
    {
        $sql = "SELECT 
                c.idcuadre_caja,
                c.fecha_hora_inicio,
                c.idusuario,
                c.idsucursal,
                c.total_efectivo_inicio AS efectivoApertura,
                c.total_ventas_diarias AS total_ventas,
                c.total_ventas_diarias_efectivo AS totalVentasEfectivo,
                c.total_ventas_diarias_tarjeta AS totalVentasTarjeta,
                c.total_ventas_diarias_credito AS totalVentasCredito,
                c.total_ventas_diarias_transferencia AS totalVentasTransferencia,
                c.total_efectivo_cierre_operaciones AS totalSobranteFaltante,
                s.nombre as sucursal_nombre,
                s.nombre_comercial,
                s.nombre_fel,
                s.direccion as sucursal_direccion,
                s.direccion_fiscal,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                u.nombre AS nombreUsuario,
                (SELECT COUNT(cc.idcobros_tickets) FROM cobros_tickets cc 
            WHERE cc.idcuadre_caja=c.idcuadre_caja  ) AS NumCarro
            FROM cuadre_cajas c
            INNER JOIN sucursal s ON s.idsucursal=c.idsucursal
            INNER JOIN usuario u ON u.idusuario=c.idusuario
            WHERE c.idusuario=34 AND c.idsucursal=4 
            AND c.idcuadre_caja='$idcuadre_caja' ";
        return ejecutarConsulta($sql);
    }



}

?>