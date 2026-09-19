<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class CuadreInicio
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }


    //Implementamos un método para insertar registros
    public function insertar2($total_efectivo_inicio, $fecha_hora_cierre, $total_efectivocierre, $idusuario, $hora_inicio, $total_ventas_diarias, $total_efectivo_cierre_operaciones, $operacion_efectvio, $total_ventas_diarias_efectivo, $total_ventas_diarias_tarjeta, $total_ventas_diarias_credito, $total_ventas_diarias_transferencia, $total_ventas_gastosEfectivo, $total_ventas_AbonosVentas, $total_ventas_NCVentas)
    {
        $sql = "INSERT INTO cuadre_cajas (total_efectivo_inicio,fecha_hora_inicio,centavos_1,centavos_5,centavos_10,centavos_25,centavos_50,
        quetzal_1,quetzal_5,quetzal_10,quetzal_20,quetzal_50,quetzal_100,quetzal_200,total_efectivo,idusuario,hora_inicio,total_ventas_diarias,
        total_efectivo_cierre_operaciones,operacion_efectvio,descripcion_numero_boleta,valor_operacion_efectivo,saldo_final_cierre_caja,condicion,
        tipo_operacion,total_ventas_diarias_efectivo,total_ventas_diarias_tarjeta,total_ventas_diarias_credito,total_ventas_diarias_transferencia,
        total_ventas_gastosEfectivo,total_ventas_AbonosVentas,total_ventas_NCVentas,idsucursal)

        VALUES ('$total_efectivo_inicio','$fecha_hora_cierre','0','0','0','0','0','0','0','0','0','0','0','0',
        '$total_efectivocierre','$idusuario','$hora_inicio','$total_ventas_diarias','$total_efectivo_cierre_operaciones',
        '$operacion_efectvio','0','','0','1','CIERRE CAJA','$total_ventas_diarias_efectivo','$total_ventas_diarias_tarjeta',
        '$total_ventas_diarias_credito','$total_ventas_diarias_transferencia','$total_ventas_gastosEfectivo',
        '$total_ventas_AbonosVentas','$total_ventas_NCVentas','" . $_SESSION["idsucursal"] . "')";
        //  print_r($sql);
        $idnew = ejecutarConsulta_retornarID($sql);

        $sqloperacion = "SELECT IFNULL(SUM(total_efectivo),0) as totalefectivo,idcuadre_caja 
        FROM cuadre_cajas WHERE DATE(fecha_hora_inicio)<=curdate() AND tipo_operacion='APERTURA' and idsucursal='" . $_SESSION["idsucursal"] . "' ";
        $residcajas = ejecutarConsultaSimpleFila($sqloperacion);
        $update_idcuadre_caja = $residcajas["idcuadre_caja"];

        $sqlupdate = "UPDATE cuadre_cajas SET tipo_operacion='CIERRE',idcuadre_caja_cierre='$idnew' 
        WHERE idcuadre_caja='$update_idcuadre_caja'";
        ejecutarConsulta($sqlupdate);

        $sqlupdateventa = "UPDATE venta SET tipo_operacion='CIERRE', idcuadre_caja='$idnew' 
        WHERE DATE(fecha_hora)<=curdate() and idsucursal='" . $_SESSION["idsucursal"] . "' AND tipo_operacion='APERTURA' ";
        ejecutarConsulta($sqlupdateventa);

        $sqlupdateCompras = "UPDATE compras SET tipo_operacion='CIERRE', idcuadre_caja='$idnew' 
        WHERE DATE(fecha_creacion)<=curdate() and idsucursal='" . $_SESSION["idsucursal"] . "' AND tipo_operacion='APERTURA' ";
        ejecutarConsulta($sqlupdateCompras);


        $sqlupdateCtacobrar = "UPDATE cta_cobrar SET tipo_operacion='CIERRE', idcuadre_caja='$idnew' 
        WHERE DATE(fecha_creacion)<=curdate() and idsucursal='" . $_SESSION["idsucursal"] . "' AND tipo_operacion='APERTURA' ";
        ejecutarConsulta($sqlupdateCtacobrar);

        $sqlupdateNC = "UPDATE nota_credito SET tipo_operacion='CIERRE', idcuadre_caja='$idnew' 
        WHERE DATE(fecha_creacion)<=curdate() and idsucursal='" . $_SESSION["idsucursal"] . "' AND tipo_operacion='APERTURA' ";
        ejecutarConsulta($sqlupdateNC);

        return ($idnew);

    }


    public function guardaryeditar2Parqueo(
        $total_efectivo_inicio,
        $fecha_hora_cierre,
        $total_efectivocierre,
        $idusuario,
        $hora_inicio,
        $total_ventas_diarias,
        $total_efectivo_cierre_operaciones,
        $operacion_efectvio,
        $total_ventas_diarias_efectivo,
        $total_ventas_diarias_tarjeta,
        $total_ventas_diarias_credito,
        $total_ventas_diarias_transferencia,
        $total_ventas_gastosEfectivo,
        $total_ventas_AbonosVentas,
        $total_ventas_NCVentas
    ) {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sql = "INSERT INTO cuadre_cajas (total_efectivo_inicio,
        fecha_hora_inicio,
        total_efectivo,
        idusuario,
        hora_inicio,
        total_ventas_diarias,
        total_efectivo_cierre_operaciones,
        operacion_efectvio,
        valor_operacion_efectivo,
        saldo_final_cierre_caja,
        condicion,
        tipo_operacion,
        total_ventas_diarias_efectivo,
        total_ventas_diarias_tarjeta,
        total_ventas_diarias_credito,
        total_ventas_diarias_transferencia,
        total_ventas_gastosEfectivo,
        total_ventas_AbonosVentas,
        total_ventas_NCVentas,
        idsucursal,
        fecha_hora_cierre)

        VALUES ('$total_efectivo_inicio',
        '$fecha_hora_cierre',
        '$total_efectivocierre',
        '$idusuario',
        '$hora_inicio',
        '$total_ventas_diarias',
        '$total_efectivo_cierre_operaciones',
        '$operacion_efectvio',
        '0',
        '0',
        '1',
        'CIERRE CAJA',
        '$total_ventas_diarias_efectivo',
        '$total_ventas_diarias_tarjeta',
        '$total_ventas_diarias_credito',
        '$total_ventas_diarias_transferencia',
        '$total_ventas_gastosEfectivo',
        '$total_ventas_AbonosVentas',
        '$total_ventas_NCVentas',
        '" . $_SESSION["idsucursal"] . "',
        '$fechaHora')";

        $idnew = ejecutarConsulta_retornarID($sql);

        $sqloperacion = "SELECT IFNULL(SUM(total_efectivo),0) as totalefectivo,idcuadre_caja FROM cuadre_cajas 
        WHERE  tipo_operacion='APERTURA' and idusuario='" . $_SESSION["idusuario"] . "' and idsucursal='" . $_SESSION["idsucursal"] . "' ";
        $residcajas = ejecutarConsultaSimpleFila($sqloperacion);
        //print_r($residcajas);
        $update_idcuadre_caja = $residcajas["idcuadre_caja"];

        $sqlupdate = "UPDATE cuadre_cajas SET tipo_operacion='CIERRE',idcuadre_caja_cierre='$idnew' WHERE idcuadre_caja='$update_idcuadre_caja'";
        ejecutarConsulta($sqlupdate);

        $sqlupdateventa = "UPDATE cobros_tickets SET tipo_operacion='CIERRE', idcuadre_caja='$idnew' 
        WHERE  idusuario='" . $_SESSION["idusuario"] . "' and idsucursal='" . $_SESSION["idsucursal"] . "' ";
        ejecutarConsulta($sqlupdateventa);

        return ($idnew);

    }

    public function anular($idcuadre_caja)
    {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sqlupdate = "UPDATE cuadre_cajas SET 
                                tipo_operacion='ANULADO',
                                fecha_anulacion='$fechaHora',
                                idusuario_update='" . $_SESSION["idsucursal"] . "' 
                    WHERE idcuadre_caja='$idcuadre_caja'";
        ejecutarConsulta($sqlupdate);

        $sqlupdateventa = "UPDATE venta SET tipo_operacion='APERTURA'  WHERE idcuadre_caja='$idcuadre_caja' ";
        ejecutarConsulta($sqlupdateventa);

        $sqlupdateCompras = "UPDATE compras SET tipo_operacion='APERTURA' WHERE idcuadre_caja='$idcuadre_caja' ";
        ejecutarConsulta($sqlupdateCompras);

        $sqlupdateCtacobrar = "UPDATE cta_cobrar SET tipo_operacion='APERTURA'WHERE idcuadre_caja='$idcuadre_caja' ";
        ejecutarConsulta($sqlupdateCtacobrar);

        $sqlupdateNC = "UPDATE nota_credito SET tipo_operacion='APERTURA' WHERE idcuadre_caja='$idcuadre_caja' ";
        ejecutarConsulta($sqlupdateNC);

        $sqlupdateCaja = "UPDATE cuadre_cajas SET tipo_operacion='APERTURA' WHERE idcuadre_caja_cierre='$idcuadre_caja'";
        ejecutarConsulta($sqlupdateCaja);

        return ($sqlupdate);
    }



    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcuadre_caja)
    {
        $sql = "SELECT *,DATE(cuadre_cajas.fecha_hora_inicio) as fechainicio FROM cuadre_cajas WHERE idcuadre_caja='$idcuadre_caja'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
            cc.idcuadre_caja,
            DATE(cc.fecha_hora_inicio) as fechainicio,
            cc.total_efectivo,
            cc.idusuario,
            u.nombre as usuario,
            cc.condicion, 
            cc.hora_inicio,
            cc.total_efectivo_inicio,
            cc.total_ventas_diarias,
            cc.total_efectivo_cierre_operaciones,
            cc.operacion_efectvio,
            cc.valor_operacion_efectivo,
            cc.saldo_final_cierre_caja,
            cc.descripcion_numero_boleta,
            cc.tipo_operacion
            FROM cuadre_cajas cc
            INNER JOIN usuario u on cc.idusuario=u.idusuario 
            where cc.tipo_operacion IN ('CIERRE CAJA', 'ANULADO') and DATE(cc.fecha_hora_inicio)>='$fecha_inicio' AND DATE(cc.fecha_hora_inicio)<='$fecha_fin'
            and  cc.idsucursal='$idsucursal'   ";
        return ejecutarConsulta($sql);
    }

    public function cuadrecajacabeceracierre($idcuadre_caja)
    {
        $sql = "SELECT 
        cc.idcuadre_caja,
        cc.fecha_hora_inicio,
        cc.total_efectivo,
        cc.idusuario,
        u.nombre as usuario,
        cc.condicion, 
        cc.hora_inicio,
        cc.total_efectivo_inicio,
        cc.total_ventas_diarias,
        cc.total_efectivo_cierre_operaciones,
        cc.operacion_efectvio,
        cc.valor_operacion_efectivo,
        cc.saldo_final_cierre_caja,
        cc.descripcion_numero_boleta,
        cc.centavos_1,
        cc.centavos_5,
        cc.centavos_10,
        cc.centavos_25,
        cc.centavos_50,
        cc.quetzal_1,
        cc.quetzal_5,
        cc.quetzal_10,
        cc.quetzal_20,
        cc.quetzal_50,
        cc.quetzal_100,
        cc.quetzal_200,
        cc.tipo_operacion,
        s.nombre as sucursal_nombre,
        s.direccion as sucursal_direccion,
        s.telefono as sucursal_telefono,
        s.nit as sucursal_nit,
        s.email as sucursal_email,
        s.imagen as sucursal_imagen,
        s.condicion as sucursal_condicion,
        s.nombre_fel,
        cc.total_ventas_diarias_efectivo,
        cc.total_ventas_diarias_tarjeta, 
        cc.total_ventas_diarias_credito,
        cc.total_ventas_diarias_transferencia,
        cc.total_ventas_AbonosVentas,
        cc.total_ventas_gastosEfectivo,
        cc.total_ventas_NCVentas        
        FROM cuadre_cajas cc
        INNER JOIN usuario u on cc.idusuario=u.idusuario 
        INNER JOIN sucursal s ON s.idsucursal=cc.idsucursal
        where cc.idcuadre_caja='$idcuadre_caja'";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select

}

?>