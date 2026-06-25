<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Cotizaciones
{
    //Implementamos nuestro constructor  
    public function __construct() 
    {
   
    }      
   
    //Implementamos un método para insertar registros
    public function insertar($idcotizacion,$idcliente,$idusuario,$fecha_hora,$nombre_empresa,$telefono_empresa,$total_venta,$total_ventades,$idarticulo,$cantidad,$precio_venta,$descuento_porcentaje,$descripcion_detalle,$idsucursal)
    {

        $sqlcorrelativo="UPDATE add_correlativo SET num_cotizacion=num_cotizacion+1 WHERE idsucursal='".$_SESSION["idsucursal"]."'";
         ejecutarConsulta($sqlcorrelativo); 

         $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'";
        $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
        $corre=$correlativo["num_cotizacion"]; 

        $sql="INSERT INTO cotizacion (idcliente,idusuario,fecha_hora,nombre_empresa,telefono_empresa,total_venta,total_ventades,estado,tipo_comprobante,num_comprobante,idsucursal)
        VALUES ('$idcliente','$idusuario','$fecha_hora','$nombre_empresa','$telefono_empresa','$total_venta','$total_ventades','Aceptado','Cotizacion','$corre','$idsucursal')";
        //return ejecutarConsulta($sql);
        $idcotizacionnew=ejecutarConsulta_retornarID($sql); 
 
        $num_elementos=0; 
        $sw=true; 
 
        while ($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_cotizacion(idcotizacion,idarticulo,cantidad,precio_venta,descuento,descripcion_detalle) VALUES ('$idcotizacionnew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento_porcentaje[$num_elementos]','$descripcion_detalle[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }
  
        return $sw; 
    } 

    public function editar($idcotizacion,$idcliente,$idusuario,$fecha_hora,$nombre_empresa,$telefono_empresa,$total_venta,$total_ventades,$idarticulo,$cantidad,$precio_venta,$descuento_porcentaje,$descripcion_detalle,$idsucursal)
    {
        $sqlUpdate="UPDATE cotizacion SET idcliente='$idcliente',idusuario='$idusuario',fecha_hora='$fecha_hora',nombre_empresa='$nombre_empresa',telefono_empresa='$telefono_empresa',total_venta='$total_venta',total_ventades='$total_ventades',idsucursal='$idsucursal' WHERE idcotizacion='$idcotizacion'";
        ejecutarConsulta($sqlUpdate);  

        
        $sql="DELETE from detalle_cotizacion where idcotizacion=".$idcotizacion."";
        ejecutarConsulta($sql);
 
        $num_elementos=0; 
        $sw=true; 
 
        while ($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_cotizacion(idcotizacion,idarticulo,cantidad,precio_venta,descuento,descripcion_detalle) VALUES ('$idcotizacion', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento_porcentaje[$num_elementos]','$descripcion_detalle[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }
 
        return $sw; 
    }     
 
     
    //Implementamos un método para anular la venta
    public function anular($idcotizacion)
    {
        $sql="UPDATE cotizacion SET estado='Anulado' WHERE idcotizacion='$idcotizacion'";
        


        return ejecutarConsulta($sql);             
    }
 
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcotizacion)
    {
        $sql="SELECT 
                c.idcotizacion,
                c.idcliente,
                c.idusuario,
                c.nombre_empresa,
                c.telefono_empresa,
                DATE(c.fecha_hora) as fecha,
                c.impuesto,
                c.total_venta,
                c.total_ventades,
                c.estado,
                c.tipo_comprobante,
                p.nombre AS cliente,
                p.num_documento,
                p.direccion,
                p.telefono,
                p.email
                FROM cotizacion c 
                INNER JOIN persona p ON p.idpersona=c.idcliente
                 WHERE c.idcotizacion='$idcotizacion'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
 
    public function listarDetalle($idcotizacion)
    {
        $sql="SELECT 
            dc.iddetalle_cotizacion,
            dc.idcotizacion,
            dc.idarticulo,
            a.nombre,
            dc.cantidad,
            dc.precio_venta,
            dc.descuento,
            (dc.cantidad*dc.precio_venta-dc.descuento) as subtotal,
            dc.descripcion_detalle
            FROM detalle_cotizacion dc 
            INNER JOIN articulo a on dc.idarticulo=a.idarticulo
            where dc.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                c.idcotizacion,
                c.idcliente,
                p.nombre as cliente,
                c.idusuario,
                u.nombre as usuario,
                c.nombre_empresa,
                c.telefono_empresa,
                DATE(c.fecha_hora) as fecha,
                c.impuesto,
                c.total_venta,
                c.total_ventades,
                c.estado,
                c.tipo_comprobante,
                c.num_comprobante,
                s.nombre AS nombre_sucursal
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona=c.idcliente
                INNER JOIN usuario u ON u.idusuario=c.idusuario
                INNER JOIN sucursal s ON s.idsucursal=u.idsucursal
                ORDER by c.idcotizacion desc";
        return ejecutarConsulta($sql);      
    }




    public function cotizacioncabecera($idcotizacion){
        $sql="SELECT 
                c.idcotizacion,
                c.idcliente,
                p.tipo_persona,
                p.nombre as cliente,
                p.tipo_documento,
                p.num_documento,
                p.direccion,
                p.telefono,
                p.email,
                p.tipo_cliente,
                c.idusuario,
                u.nombre as usuario,
                u.telefono as  usuario_telefono,
                u.email as usuario_email,
                c.nombre_empresa,
                c.telefono_empresa,
                DATE(c.fecha_hora) as fecha,
                c.impuesto,
                c.total_venta,
                c.total_ventades,
                c.estado,
                c.tipo_comprobante,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                c.num_comprobante
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona=c.idcliente
                INNER JOIN usuario u ON u.idusuario=c.idusuario
                INNER JOIN sucursal s ON s.idsucursal=u.idsucursal
            WHERE c.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }

    public function cotizaciondetalle($idcotizacion){
        $sql="SELECT 
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                d.precio_venta,
                d.descuento,
                round(((d.precio_venta-((d.precio_venta*d.descuento)/100))*d.cantidad),2) as subtotal,
                d.descripcion_detalle
                FROM detalle_cotizacion d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo
            WHERE d.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    } 



    public function obtenerdetallecotizacion($idcotizacion){

        $sqldetalle="SELECT 
                    a.idarticulo,
                    a.nombre as articulo,
                    dc.precio_venta as precio_venta,
                    dc.cantidad as stock, 
                    dc.descuento as descuento_porcentaje,
                    a.stock as stockreal,
                    dc.descripcion_detalle
                    from detalle_cotizacion dc 
                    INNER JOIN articulo a on dc.idarticulo=a.idarticulo where dc.idcotizacion=".$idcotizacion;

        #echo $sql;
        $rspta=ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg=$rspta->fetch_object()){
            $rows[] = $reg;
        }
        return $rows;
    }



    public function mostrarVentaAdministrador($idventa_administrador)
    {

        $sql="SELECT 
            p.nombre AS nombrecliente,
            p.num_documento AS numdocumentocliente,
            p.direccion AS direccioncliente,
            p.telefono AS telefonocliente, 
            p.email AS emailtelefono,
            date(v.fecha_hora) AS fecha,v.idventa,v.*
            FROM venta v  
            INNER JOIN persona p ON p.idpersona=v.idcliente
            WHERE v.forma_pago='Credito' and v.estadopago='Pago Aplicado'   and v.saldo_venta='0' and v.cuentaliquidada='0'   and  v.idventa='$idventa_administrador'";
        return ejecutarConsultaSimpleFila($sql);
    }   

    public function detallecotizacionparaventaadministrador($idventa_administrador){

        $sqldetalle="SELECT 
                dv.iddetalle_venta,
            dv.idventa,
            dv.idarticulo,
            dv.cantidad as stock,
            dv.precio_venta,
            dv.descuento as descuento_porcentaje,
            dv.descripcion_detalle,
            a.nombre AS articulo,
            (SELECT axt.stocksucursal FROM articuloxsucursal axt WHERE axt.idarticulo=dv.idarticulo LIMIT 1) AS stockreal
             FROM detalle_venta dv
             INNER JOIN articulo a ON a.idarticulo=dv.idarticulo
              where dv.idventa=".$idventa_administrador;

        #echo $sql;
        $rspta=ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg=$rspta->fetch_object()){
            $rows[] = $reg;
        }
        return $rows;
    }    

    public function ventacabecera2($idventa){
        $sql="SELECT 
v.idventa,
v.idcliente,
p.nombre as cliente,
p.tipo_documento,
p.num_documento,
p.direccion,
p.telefono,
p.email,
v.idusuario,
v.tipo_comprobante,
v.serie_comprobante,
v.num_comprobante,
DATE(v.fecha_hora) as fecha,
v.impuesto,
v.total_venta,
v.total_ventades,
v.estado,
v.condicion,
u.nombre as usuario,
v.forma_pago,
(v.total_venta+v.total_ventades) AS subtotal,
                s.idsucursal,
                s.nombre as sucursal_nombre, 
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                v.autorizacionEcoFactura,
                v.serie_ecoFactura,
                v.numero_ecoFactura,
                v.fechaCertificacion_ecoFactura,
                v.nombre_vendedor,
                v.numero_pagos,
                date(v.fecha_hora_pago) as fechahorapago,
                v.monto_abono
from venta v
INNER JOIN persona p ON p.idpersona=v.idcliente
inner join usuario u on u.idusuario=v.idusuario
INNER JOIN sucursal s ON s.idsucursal=u.idsucursal
        WHERE v.idventa='$idventa' ";
        return ejecutarConsulta($sql);
    }

    public function ventadetalle2($idventa){
        $sql="SELECT 
dv.iddetalle_venta,
dv.idventa,
dv.idarticulo,
a.nombre as articulo,
a.codigo,
dv.cantidad,
dv.precio_venta,
dv.descuento,
ROUND((dv.cantidad*dv.precio_venta)-dv.descuento,2) as subtotal,
dv.descripcion_detalle
from detalle_venta dv
INNER JOIN articulo a ON dv.idarticulo=a.idarticulo 
WHERE dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    } 

    public function ventadetalle3($idventa){
        $sql="SELECT 
            cc.idcta_cobrar,
            cc.idventa,
            cc.idcliente,
            cc.total_venta,
            cc.total_abono,
            cc.saldo_venta,
            cc.tipo_pago,
            DATE(cc.fechapago) as fechapago,
            cc.tipo_banco,
            cc.numero_boleta,
            cc.recibo_caja_numero,
            cc.descripcion,
            cc.condicion,
            cc.idusuario
            FROM cta_cobrar cc
            WHERE cc.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }     



    public function ventacabecera5($idventa){
        $sql="SELECT 
                i.idingreso,
                i.idproveedor,
                p.nombre as proveedor,
                p.direccion,
                p.telefono,
                p.num_documento,
                p.email,
                i.idusuario,
                u.nombre as usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                DATE(i.fecha_hora) as fecha,
                i.impuesto,
                i.total_compra,
                i.estado,
                i.forma_pago,
                i.dias_credito,
                i.direccion_entrega_orden_compra,
                DATE(i.fecha_entrega_orden_compra) as fechaentregaordencompra,
                i.observacion_orden_compra,
                DATE(i.fecha_hora_pago_credito) as fechahorapagocredito,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion                
                FROM ingreso i
                INNER JOIN persona p ON p.idpersona=i.idproveedor
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                INNER JOIN sucursal s ON s.idsucursal=u.idsucursal
                WHERE i.idingreso='$idventa'";
        return ejecutarConsulta($sql);
    }   

        public function ventadetalle6($idventa){
        $sql="SELECT 
                d.iddetalle_ingreso,
                d.idingreso,
                a.nombre as articulo,
                a.codigo,
                d.cantidad,
                d.precio_compra,
                (d.cantidad*d.precio_compra) as subtotal 
                FROM detalle_ingreso d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo  
                WHERE d.idingreso='$idventa'";
        return ejecutarConsulta($sql);
    }     


                
}
?>