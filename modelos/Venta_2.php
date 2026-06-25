<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Venta2
{
    //Implementamos nuestro constructor 
    public function __construct()
    {
  
    }    
  
    //Implementamos un método para insertar registros
    public function insertar($idcliente,$idusuario,$fecha_hora,$impuesto,$total_venta,$idarticulo,$cantidad,$precio_venta,$descuento_porcentaje,$total_ventades,$forma_pago,$dias_credito,$fecha_hora_cobro)
    {
        $sql="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,serie_comprobante,fecha_hora,impuesto,total_venta,estado,total_ventades,forma_pago,dias_credito,fecha_hora_cobro)
        VALUES ('$idcliente','$idusuario','Factura','A','$fecha_hora','$impuesto','$total_venta','Aceptado','$total_ventades','$forma_pago','$dias_credito','$fecha_hora_cobro')";
        //return ejecutarConsulta($sql);
        $idventanew=ejecutarConsulta_retornarID($sql); 
 
        $num_elementos=0; 
        $sw=true;
 
        while ($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_venta(idventa, idarticulo,cantidad,precio_venta,descuento) VALUES ('$idventanew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento_porcentaje[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }
 
        return $sw; 
    } 
 

     public function insertDetail($factura,$idarticulo,$cantidad,$precio_venta,$descuento)
    {
        $sql_detalle = "INSERT INTO detalle_venta(idventa, idarticulo,cantidad,precio_venta,descuento) VALUES ('$factura', '$idarticulo','$cantidad','".str_replace(",",".",$precio_venta)."','$descuento')";
        echo $sql_detalle;
        ejecutarConsulta($sql_detalle);
        //$resultt=$precio_venta*$cantidad;
        $resultt=($cantidad*($precio_venta-($precio_venta*$descuento)/100));
        $resultt2=(($precio_venta*$descuento)/100);
        $sqlventa="UPDATE venta SET total_venta=total_venta+'$resultt',total_ventades=total_ventades+'$resultt2' WHERE idventa='$factura'";
        return ejecutarConsulta($sqlventa);        
    }

    public function updatecuerpofac($idventa,$fecha_hora)
    {
        $sqlventa="UPDATE venta SET fecha_hora='$fecha_hora' WHERE idventa='$idventa'";
        return ejecutarConsulta($sqlventa);

    }        
     
    //Implementamos un método para anular la venta
    public function anular($idventa)
    {
        $sql="UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
        


        $sqlDetalleventa="SELECT * FROM detalle_venta WHERE idventa='$idventa'";
        $Detalle=ejecutarConsulta($sqlDetalleventa);


        while ($reg = $Detalle->fetch_object())
        {
            $updateArticuloDetalle="UPDATE articulo SET stock=stock+".$reg->cantidad." WHERE idarticulo=".$reg->idarticulo." ";
            ejecutarConsulta($updateArticuloDetalle);
        }   

        return ejecutarConsulta($sql);             
    }
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idventa)
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    public function listarDetalle($idventa)
    {
        $sql="SELECT
                dv.iddetalle_venta, 
                dv.idventa,
                dv.idarticulo,
                a.nombre,
                a.stock as stockinvent,
                dv.cantidad,
                dv.precio_venta,
                dv.descuento,
                ROUND((dv.cantidad*(dv.precio_venta-((dv.precio_venta*dv.descuento)/100))),2) as subtotal,
                ((dv.precio_venta*dv.descuento)/100) as total_descuento 
                FROM detalle_venta dv 
                inner join articulo a on dv.idarticulo=a.idarticulo 
                where dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER by v.idventa desc";
        return ejecutarConsulta($sql);      
    } 


    public function ventacabecera($idventa){
        $sql="SELECT 
        v.idventa,
        v.idcliente,
        p.nombre as cliente,
        p.direccion,
        p.tipo_documento,
        p.num_documento,
        p.email,p.telefono,v.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,date(v.fecha_hora) as fecha,v.impuesto,v.total_venta, v.total_ventades FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=v.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function ventadetalle($idventa){
        $sql="SELECT 
a.nombre as articulo,
a.codigo,
d.cantidad,
d.precio_venta,
d.descuento,
ROUND((d.cantidad*(d.precio_venta-((d.precio_venta*d.descuento)/100))),2) as subtotal,
((d.precio_venta*d.descuento)/100) as total_descuento FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo  WHERE d.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function ventadetalletotalpeso($idventa){
        $sql="SELECT SUM(d.cantidad*a.peso_producto) as peso FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }       
}
?>