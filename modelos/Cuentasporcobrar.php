<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Cuentasporcobrar
{
    //Implementamos nuestro constructor 
    public function __construct() {}

    //Implementamos un método para insertar registros
    public function insertar($idcliente)
    {
        // $sql="INSERT INTO categoria (nombre,descripcion,condicion) 
        //  VALUES ('$nombre','$descripcion','1')";
        //  return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idventa, $idcliente, $total_venta, $total_abono, $saldo_venta, $tipo_pago, $fechapago, $tipo_banco, $numero_boleta, $recibo_caja_numero, $descripcion, $idusuario)
    {
        if ($saldo_venta == '0') {
            $estadopago = 'Pago Aplicado';
        } else {
            $estadopago = 'Pendiente Pago';
        }
        $sql = "UPDATE venta SET 
                    total_abono=total_abono +'$total_abono',
                    saldo_venta='$saldo_venta',
                    tipo_pago='$tipo_pago',
                    estadopago='$estadopago',
                    cta_cobrar_descripcion='$descripcion'
            WHERE idventa='$idventa'";
        ejecutarConsulta($sql);

        $sqlCtaxcobrar = "INSERT INTO cta_cobrar (idventa,idcliente,total_venta,total_abono,saldo_venta,
            tipo_pago,fechapago,tipo_banco,numero_boleta,recibo_caja_numero,descripcion,idusuario,condicion)
            VALUES ('$idventa','$idcliente','$total_venta','$total_abono','$saldo_venta','$tipo_pago','$fechapago','$tipo_banco','$numero_boleta','$recibo_caja_numero','$descripcion','$idusuario','1')";
        return ejecutarConsulta($sqlCtaxcobrar);
    }

    public function guardaryeditarxlote($idventa_lote, $idcliente_lote, $total_venta_lote, $total_abono_lote, $saldo_venta_lote, $tipo_pago_lote, $fechapago_lote, $tipo_banco_lote, $numero_boleta_lote, $recibo_caja_numero_lote, $descripcion_lote)
    {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($idventa_lote)) {
            $sql_detallePagCtasxCobrarLote = "INSERT INTO cta_cobrar(idventa,
                                                    idcliente,
                                                    total_venta,
                                                    total_abono,
                                                    saldo_venta,
                                                    tipo_pago,
                                                    fechapago,
                                                    tipo_banco,
                                                    numero_boleta,
                                                    recibo_caja_numero,
                                                    descripcion,
                                                    idusuario,
                                                    condicion,idsucursal,fecha_creacion) 
                                            VALUES ('$idventa_lote[$num_elementos]',
                                                    '$idcliente_lote[$num_elementos]',
                                                    '$total_venta_lote[$num_elementos]',
                                                    '$total_abono_lote[$num_elementos]',
                                                    '$saldo_venta_lote[$num_elementos]',
                                                    '$tipo_pago_lote[$num_elementos]',
                                                    '$fechapago_lote[$num_elementos]',
                                                    '$tipo_banco_lote[$num_elementos]',
                                                    '$numero_boleta_lote[$num_elementos]',
                                                    '$recibo_caja_numero_lote[$num_elementos]',
                                                    '$descripcion_lote[$num_elementos]',
                                                    '" . $_SESSION["idusuario"] . "',
                                                    '1','" . $_SESSION["idsucursal"] . "','$fechaHora' )";
            ejecutarConsulta($sql_detallePagCtasxCobrarLote) or $sw = false;

            if ($saldo_venta_lote[$num_elementos] == '0') {
                $estadopago = 'Pago Aplicado';
            } else {
                $estadopago = 'Pendiente Pago';
            }
            $sql = "UPDATE venta SET 
                    total_abono=total_abono +'$total_abono_lote[$num_elementos]',
                    saldo_venta='$saldo_venta_lote[$num_elementos]',
                    cta_cobrar_descripcion='$descripcion_lote[$num_elementos]',
                    estadopago='$estadopago'
            WHERE idventa='$idventa_lote[$num_elementos]'";


            ejecutarConsulta($sql);



            $num_elementos = $num_elementos + 1;
        }
    }

    //Implementamos un método para desactivar categorías 
    public function desactivar($idventa)
    {
        $sql1 = "UPDATE venta SET estadopago=' ' WHERE idventa='$idventa'";
        return ejecutarConsulta($sql1);

        $sql1 = "UPDATE cheque SET condicion='0' WHERE idventa='$idventa'";
        return ejecutarConsulta($sql1);

        $sqlidcuenta = "SELECT * FROM cheque WHERE idventa='$idventa'";
        $res = ejecutarConsulta($sqlidcuenta);
        $residcuenta = $res->fetch_object();
        $idcuenta = $residcuenta->idcuenta;
        $valor_cheque = $residcuenta->valor_cheque;

        $sql = "UPDATE cuenta SET saldo_cuenta=saldo_cuenta+'$valor_cheque' WHERE idcuenta='$idcuenta'";
        ejecutarConsulta($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idventa)
    {
        $sql = "SELECT 
                v.idventa,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                DATE(v.fecha_hora) as fecha_hora_factura,
                v.total_venta,
                v.total_abono,
                v.saldo_venta,
                v.condicion,
                v.estado,
                v.idcliente,
                p.nombre as nombre_cliente, 
                p.telefono as telefono_cliente, 
                v.estadopago,
                v.tipo_banco,
                v.recibo_caja_numero,
                v.idcuenta FROM venta v INNER JOIN persona p  ON v.idcliente=p.idpersona WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listarFacturas($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                v.idventa,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_abono,
                v.saldo_venta,
                v.condicion, 
                v.estado,
                v.numero_pagos,
                p.nombre as nombre_cliente, 
                p.telefono as telefono_cliente, 
                v.numero_boleta,
                DATE(v.fechapago) as fechapago,
                v.estadopago, 
                DATE(v.fecha_hora) as fecha,
                v.tipo_banco,
                v.recibo_caja_numero,
                (SELECT COUNT(c.idcta_cobrar) FROM cta_cobrar c WHERE c.idventa=v.idventa AND c.condicion=1 LIMIT 1) AS numerodeabonos,
                u.nombre as usuario_creacion,
                s.nombre as sucursal
                FROM venta v 
                INNER JOIN persona p  ON v.idcliente=p.idpersona 
                inner join usuario u on u.idusuario=v.idusuario
                inner join sucursal s on s.idsucursal=v.idsucursal
                where v.estado <> 'Anulado' and v.forma_pago='Credito' 
                and DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' 
                order by idventa DESC ";
        return ejecutarConsulta($sql);
    }

    public function listarAbonos($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                c.idcta_cobrar,
                c.total_venta,
                c.total_abono,
                c.saldo_venta,
                c.idventa,
                c.condicion,
                c.fecha_creacion,
                u.nombre AS user,
                s.nombre AS sucursales,
                p.nombre AS clientes
             FROM cta_cobrar c
             INNER JOIN usuario u ON u.idusuario=c.idusuario
             INNER JOIN sucursal s ON s.idsucursal=c.idsucursal
             INNER JOIN persona p ON p.idpersona=c.idcliente
                where  DATE(c.fecha_creacion)>='$fecha_inicio' AND DATE(c.fecha_creacion)<='$fecha_fin' 
                order by c.idcta_cobrar DESC ";
        return ejecutarConsulta($sql);
    }



    public function listarVentaxlote($idsector, $idruta)
    {
        $sql = "SELECT 
                v.idventa,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_abono,
                v.saldo_venta,
                v.condicion, 
                v.estado,
                v.idcliente,
                v.numero_pagos,
                p.nombre as nombre_cliente, 
                p.telefono as telefono_cliente, 
                v.numero_boleta,
                DATE(v.fechapago) as fechapago,
                v.estadopago, 
                DATE(v.fecha_hora) as fecha,
                v.tipo_banco,
                v.recibo_caja_numero,
                v.numero_ecoFactura,
                u.nombre as nomusuario,
                s.nombre as nomSucursal,
                r.nombre AS nombRuta,
                sec.nombre AS nomSector
                FROM venta v 
                INNER JOIN persona p  ON v.idcliente=p.idpersona 
                inner join usuario u on u.idusuario=v.idusuario
                inner join sucursal s on s.idsucursal=v.idsucursal
                LEFT JOIN ruta_visita r ON r.idruta=p.idruta
                LEFT JOIN sector sec ON sec.idsector=p.idsector
                where v.estado <> 'Anulado' and v.forma_pago='Credito' and v.estadopago <> 'Pago Aplicado'
                   and  p.idsector='$idsector' and p.idruta='$idruta' 
                 order by idventa DESC ";
        return ejecutarConsulta($sql);
    }


    public function listarVentaxloteGeneral()
    {
        $sql = "SELECT 
                v.idventa,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_abono,
                v.saldo_venta,
                v.condicion, 
                v.estado,
                v.idcliente,
                v.numero_pagos,
                p.nombre as nombre_cliente, 
                p.telefono as telefono_cliente, 
                v.numero_boleta,
                DATE(v.fechapago) as fechapago,
                v.estadopago, 
                DATE(v.fecha_hora) as fecha,
                v.tipo_banco,
                v.recibo_caja_numero,
                v.numero_ecoFactura,
                u.nombre as nomusuario,
                s.nombre as nomSucursal,
                r.nombre AS nombRuta,
                sec.nombre AS nomSector
                FROM venta v 
                INNER JOIN persona p  ON v.idcliente=p.idpersona 
                inner join usuario u on u.idusuario=v.idusuario
                inner join sucursal s on s.idsucursal=v.idsucursal
                LEFT JOIN ruta_visita r ON r.idruta=p.idruta
                LEFT JOIN sector sec ON sec.idsector=p.idsector                
                where v.estado <> 'Anulado' and v.forma_pago='Credito' and v.estadopago <> 'Pago Aplicado'
                 order by idventa DESC ";
        return ejecutarConsulta($sql);
    }

    public function anular_abono($idventa, $idcta_cobrar)
    {
        $sqlCta = "UPDATE cta_cobrar SET condicion='0', idusuario_anulacion='" . $_SESSION["idusuario"] . "', fecha_hora_anulacion=NOW() WHERE idcta_cobrar='$idcta_cobrar'";
        ejecutarConsulta($sqlCta);

        $sqlMontoAbono = "SELECT total_abono FROM cta_cobrar WHERE idcta_cobrar='$idcta_cobrar'";
        $monto_abono_anulado = ejecutarConsultaSimpleFila($sqlMontoAbono);
        $monto_anulado = $monto_abono_anulado["total_abono"];

        $sqlVenta = "SELECT total_abono, saldo_venta FROM venta WHERE idventa='$idventa'";
        $datosventa = ejecutarConsultaSimpleFila($sqlVenta);
        $total_abono_venta_actual = $datosventa["total_abono"];
        $saldo_venta_actual = $datosventa["saldo_venta"];

        $nuevo_total_abono = $total_abono_venta_actual - $monto_anulado;
        $nuevo_saldo_venta = $saldo_venta_actual + $monto_anulado;

        $nuevo_estadopago = '';
        if ($nuevo_saldo_venta > 0) {
            $nuevo_estadopago = 'Pendiente Pago';
        } else {
            $nuevo_estadopago = 'Pago Aplicado';
        }

        $sqlVentaUpdate = "UPDATE venta SET total_abono='$nuevo_total_abono', saldo_venta='$nuevo_saldo_venta', estadopago='$nuevo_estadopago' WHERE idventa='$idventa'";
        return ejecutarConsulta($sqlVentaUpdate);
    }

    public function validarBoleta($numero_boleta)
    {
        $sql = "SELECT * FROM cta_cobrar WHERE numero_boleta='$numero_boleta'";
        return ejecutarConsultaSimpleFila($sql);
    }
}
