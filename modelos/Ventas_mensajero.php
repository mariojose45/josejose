<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Ventas_mensajero
{
    //Implementamos nuestro constructor 
    public function __construct()
    {
 
    }

    public function guardaryeditarxlote($idventa_lote,$idcliente_lote,$total_venta_lote,$tipo_pago_lote,$fechapago_lote,$tipo_banco_lote,$numero_boleta_lote,
    $recibo_caja_numero_lote,$descripcion_lote){
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 

        $num_elementos=0;   
        $sw=true; 

        while ($num_elementos < count($idventa_lote)){
            $sql="UPDATE venta SET 
                        numero_boleta = '$numero_boleta_lote[$num_elementos]', 
                        usuariopago='".$_SESSION["idusuario"]."',
                        fechapago='$fechaHora',
                        tipo_banco='$tipo_banco_lote[$num_elementos]',
                        estado_venta='COMPLETO' 
                WHERE idventa='$idventa_lote[$num_elementos]'";
            ejecutarConsulta($sql);
            //print_r($sql);
            $num_elementos=$num_elementos + 1;
        }            

      
    }

    public function guardaryeditarComentariosMensajero($idventa_Mensajero, $comentario_mensajero)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 

        // Verificar si ya existe un comentario para esta venta
        $sqlVerificar = "SELECT COUNT(*) as total FROM mensajero_comentarios WHERE idventa = '$idventa_Mensajero'";
        $resultado = ejecutarConsultaSimpleFila($sqlVerificar);

        if ($resultado['total'] > 0) {
            // Si ya existe, hacemos un UPDATE
            $sql = "UPDATE mensajero_comentarios 
                    SET comentario_mensajero = '$comentario_mensajero'
                    WHERE idventa = '$idventa_Mensajero'";
        } else {
            // Si no existe, hacemos un INSERT
            $sql = "INSERT INTO mensajero_comentarios (idventa, comentario_mensajero, condicion, fecha_crecion, idusuario, idsucursal) 
                    VALUES ('$idventa_Mensajero', '$comentario_mensajero', '1', '$fechaHora', '".$_SESSION["idusuario"]."', '".$_SESSION["idsucursal"]."')";
        }

        return ejecutarConsulta($sql);
    }


    public function guardaryeditarComentariosMensajeroTransporte($idventa_MensajeroTransporte,$comentario_mensajero_transporte,$guia_transporte)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 

        // Verificar si ya existe un comentario para esta venta
        $sqlVerificar = "SELECT COUNT(*) as total FROM mensajero_comentarios WHERE idventa = '$idventa_MensajeroTransporte' ";
        $resultado = ejecutarConsultaSimpleFila($sqlVerificar);

        if ($resultado['total'] > 0) {
            // Si ya existe, hacemos un UPDATE 
            $sqlComentarios = "UPDATE mensajero_comentarios 
                    SET comentario_mensajero = '$comentario_mensajero_transporte'
                    WHERE idventa = '$idventa_MensajeroTransporte'"; 
                    ejecutarConsulta($sqlComentarios);

                   
        } else {
            // Si no existe, hacemos un INSERT
            $sqlComentarios = "INSERT INTO mensajero_comentarios (idventa, comentario_mensajero, condicion, fecha_crecion, idusuario, idsucursal) 
                    VALUES ('$idventa_MensajeroTransporte', '$comentario_mensajero_transporte', '1', '$fechaHora', '".$_SESSION["idusuario"]."', '".$_SESSION["idsucursal"]."')";

                    ejecutarConsulta($sqlComentarios);

                                     
        }

        
        $sqlVenta="UPDATE venta SET guia_transporte='$guia_transporte',fecha_hora_guia_transporte='$fechaHora' WHERE idventa = '$idventa_MensajeroTransporte'  ";
                    ejecutarConsulta($sqlVenta);   



        return ($sqlVenta);
    }    


    public function MostraragregarComentarioMensajero($idventa)
    {
        $sql="SELECT * FROM mensajero_comentarios WHERE idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }
 

    public function listarVentasMensajero(){
        $sql="SELECT 
                v.idventa,
                v.estado_venta,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_abono,
                v.saldo_venta,
                v.cta_cobrar_descripcion,
                v.condicion, 
                v.estado,
                v.idcliente,
                p.nombre as nombre_cliente, 
                p.telefono as telefono_cliente, 
                v.numero_boleta,
                DATE(v.fechapago) as fechapago,
                v.estadopago, 
                DATE(v.fecha_hora) as fecha,
                v.tipo_banco,
                v.recibo_caja_numero,
                v.numero_ecoFactura
                FROM venta v 
                INNER JOIN persona p  ON v.idcliente=p.idpersona 
                inner join usuario u on u.idusuario=v.idusuario
                WHERE v.tipo_entrega = 'Mensajero' AND v.estado_venta = 'ENPROCESO'
                 order by idventa DESC"; 
        return ejecutarConsulta($sql);       
    }


    public function listarFacturasMensajero($fecha_inicio,$fecha_fin)
    {
        $sql="SELECT 
            v.idventa,
            v.estado_venta,
            v.tipo_comprobante,
            v.serie_comprobante,
            v.num_comprobante,
            v.total_venta,
            v.total_abono,
            v.saldo_venta,
            v.cta_cobrar_descripcion,
            v.condicion, 
            v.estado,
            v.idcliente,
            p.nombre AS nombre_cliente, 
            p.telefono AS telefono_cliente, 
            v.numero_boleta,
            DATE(v.fechapago) AS fechapago,
            v.estadopago, 
            DATE(v.fecha_hora) AS fecha,
            v.tipo_banco,
            v.recibo_caja_numero,
            v.numero_ecoFactura,
            v.tipo_entrega,
            v.guia_transporte,
            CASE 
                WHEN mc.comentario_mensajero IS NULL OR mc.comentario_mensajero = '' 
                THEN 'SIN COMENTARIOS' 
                ELSE mc.comentario_mensajero 
            END AS comentario_mensajero
        FROM venta v 
        INNER JOIN persona p ON v.idcliente = p.idpersona 
        INNER JOIN usuario u ON u.idusuario = v.idusuario
        LEFT JOIN mensajero_comentarios mc ON mc.idventa = v.idventa
        WHERE v.tipo_entrega <> 'Tienda' 
        AND DATE(v.fecha_hora) >= '$fecha_inicio' 
        AND DATE(v.fecha_hora) <= '$fecha_fin'
        ORDER BY v.idventa DESC "; 
        return ejecutarConsulta($sql);       
    }

    public function listarFacturasMensajeroxusuario()
    {
        $sql="SELECT 
            v.idventa,
            v.estado_venta,
            v.tipo_comprobante,
            v.serie_comprobante,
            v.num_comprobante,
            v.total_venta,
            v.total_abono,
            v.saldo_venta,
            v.cta_cobrar_descripcion,
            v.condicion, 
            v.estado,
            v.idcliente,
            p.nombre AS nombre_cliente, 
            p.telefono AS telefono_cliente, 
            v.numero_boleta,
            DATE(v.fechapago) AS fechapago,
            v.estadopago, 
            DATE(v.fecha_hora) AS fecha, 
            v.tipo_banco,
            v.recibo_caja_numero,
            v.numero_ecoFactura,
            CASE 
                WHEN mc.comentario_mensajero IS NULL OR mc.comentario_mensajero = '' 
                THEN 'SIN COMENTARIOS' 
                ELSE mc.comentario_mensajero 
            END AS comentario_mensajero
        FROM venta v 
        INNER JOIN persona p ON v.idcliente = p.idpersona 
        INNER JOIN usuario u ON u.idusuario = v.idusuario
        LEFT JOIN mensajero_comentarios mc ON mc.idventa = v.idventa
        WHERE v.tipo_entrega = 'Mensajero' and   v.despachosino='NO' and v.idusuario='".$_SESSION["idusuario"]."'
         "; 
        return ejecutarConsulta($sql);       
    }    

}
 
?>