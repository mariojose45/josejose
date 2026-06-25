<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Cuentasporpagar
{
    //Implementamos nuestro constructor 
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombre)
    {
        // $sql="INSERT INTO categoria (nombre,descripcion,condicion)  
        //  VALUES ('$nombre','$descripcion','1')";
        //  return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idingreso, $idusuario, $idcliente, $serie_comprobante, $num_comprobante, $fecha_operacion, $idcuenta, $total_compra, $valor_pagar, $saldo_ingreso, $tipo_pago, $tipo_banco, $numero_boleta, $recibo_caja_numero, $no_cheque, $fecha_hora_generacion_pago, $desp_cheque)
    {
        if ($saldo_ingreso <= '00') {
            $resestado = 'Pago Aplicado';
            # code...
        } else {
            $resestado = 'Pago con Saldo';
        }

        $sql = "UPDATE ingreso SET valor_pagar='$valor_pagar',saldo_ingreso='$saldo_ingreso',no_cheque='$no_cheque',tipo_pago='$tipo_pago',fecha_hora_generacion_pago='$fecha_hora_generacion_pago',estado='$resestado',tipo_banco='$tipo_banco',numero_boleta='$numero_boleta',recibo_caja_numero='$recibo_caja_numero',serie_comprobante='$serie_comprobante',num_comprobante='$num_comprobante' WHERE idingreso='$idingreso'";
        ejecutarConsulta($sql);
        $sql1 = "INSERT INTO cta_pagar (idingreso,idusuario,idcliente,idcuenta,total_compra,valor_pagar,saldo_ingreso,tipo_pago,tipo_banco,numero_boleta,recibo_caja_numero,no_cheque,fecha_hora_generacion_pago,desp_cheque,estado)
                    VALUES ('$idingreso','$idusuario','$idcliente','$idcuenta','$total_compra','$valor_pagar','$saldo_ingreso','$tipo_pago','$tipo_banco','$numero_boleta','$recibo_caja_numero','$no_cheque','$fecha_hora_generacion_pago','$desp_cheque','$resestado')";
        return ejecutarConsulta($sql1);

    }



    //Implementar un método para mostrar los datos de un registro a modificar 
    public function mostrar($idingreso)
    {
        $sql = "SELECT 
                i.idingreso,
                i.idproveedor,
                p.nombre AS proveedor,
                p.telefono,
                i.fecha_hora as fecha_operacion,
                p.num_documento,
                i.idusuario,
                u.nombre AS usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                DATE(i.fecha_hora) as fechaingreso,
                i.impuesto,
                i.total_compra,
                i.estado,
                i.forma_pago,
                i.dias_credito,
                DATE(i.fecha_hora_pago_credito) as fechapago,
                i.valor_pagar,
                i.saldo_ingreso
                from ingreso i
                INNER JOIN persona p ON p.idpersona=i.idproveedor
                INNER JOIN usuario u ON u.idusuario=i.idusuario WHERE i.idingreso='$idingreso'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT 
                i.idingreso,
                i.idproveedor,
                p.nombre AS proveedor,
                p.telefono,
                i.idusuario,
                u.nombre AS usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                DATE(i.fecha_hora) as fechaingreso,
                i.impuesto,
                i.total_compra,
                i.estado,
                i.forma_pago,
                i.dias_credito,
                i.tipo_pago,
                i.no_cheque,
                DATE(i.fecha_hora_generacion_pago) as fecha_hora_generacion_pago,
                DATE(i.fecha_hora_pago_credito) as fechapago,
                i.valor_pagar,
                i.saldo_ingreso                
                from ingreso i
                INNER JOIN persona p ON p.idpersona=i.idproveedor
                INNER JOIN usuario u ON u.idusuario=i.idusuario where i.estado <> 'Anulado'  and i.forma_pago='Credito' order by i.idingreso DESC ";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql = "SELECT * FROM categoria where condicion=1";
        return ejecutarConsulta($sql);
    }


    public function ctasxpagar($idingreso)
    {
        $sql = "SELECT 
                i.idingreso,
                i.idproveedor,
                p.nombre as cliente,
                i.idusuario,
                u.nombre as usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                DATE(i.fecha_hora) as fechafac, 
                i.impuesto,
                i.total_compra,
                i.estado,
                i.forma_pago,
                i.dias_credito,
                i.fecha_hora_pago_credito,
                i.valor_pagar,
                i.saldo_ingreso,
                i.tipo_pago,
                i.no_cheque,
                i.fecha_hora_generacion_pago,
                i.tipo_banco,
                i.numero_boleta,
                i.recibo_caja_numero,
                i.direccion_entrega_orden_compra,
                i.fecha_entrega_orden_compra,
                i.observacion_orden_compra,
                s.nombre as sucursal_nombre, 
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion                   
                from ingreso i  
                INNER join persona p ON i.idproveedor=p.idpersona
                INNER join usuario u on i.idusuario=u.idusuario
                INNER JOIN sucursal s ON s.idsucursal=i.idsucursal
                WHERE i.idingreso='$idingreso' ";
        return ejecutarConsulta($sql);
    }


}

?>