<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Ingresoproduccion 
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    } 
  
    //Implementamos un método para insertar registros
    public function insertar($idusuario,$idproducto,$fecha_hora,$precio_compraProducto,$ganacia_producto,
    $precio_ventaProducto,$subtotalprecioCompra,$detalles_json)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sql="INSERT INTO produccion (idproducto,cantidad_materia,idusuario,fecha_hora,precio_compra,ganacia_producto,precio_venta,sub_total,estado,
                            idsucursal,fecha_creacion)
                            VALUES ('$idproducto','1','$idusuario','$fecha_hora','$precio_compraProducto','$ganacia_producto','$precio_ventaProducto','$subtotalprecioCompra','Aceptado',
                                    '".$_SESSION["idsucursal"]."',
                                    '$fechaHora')";
        //return ejecutarConsulta($sql);
        $idingresonew=ejecutarConsulta_retornarID($sql);


            $sqlart="UPDATE articuloxsucursal asu SET 
                            asu.precio_compra='$precio_compraProducto',
                            asu.precio_venta='$precio_ventaProducto',
                            asu.stock_unidad='1', 
                            asu.ganacia_articulo='$ganacia_producto',
                            asu.tipo_ganacia='QUETZALES',
                            asu.precio_unidad='$precio_ventaProducto'
                            where asu.idarticulo='".$idproducto."' and asu.idsucursal='".$_SESSION["idsucursal"]."'  ";
                           // print_r($sqlart);
            ejecutarConsulta($sqlart);  
        /*  $num_elementos=0;
            $sw=true;
    
            while ($num_elementos < count($idarticulo))   
            {
                $sql_detalle = "INSERT INTO detalle_produccion(idproduccion,idarticulo,cantidad,precio_compra,precio_venta,subtotal) 
                VALUES ('$idingresonew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]','$subtotal[$num_elementos]')";
                ejecutarConsulta($sql_detalle) or $sw = false;

                $num_elementos=$num_elementos + 1;
            }
            return $sw; 
        */
        $sw = true;
        foreach ($detalles_json as $i => $detalle) {
            $sql_detalle = "INSERT INTO detalle_produccion (idproduccion,idarticulo,cantidad,precio_compra,precio_venta,subtotal,tipo_item)
                            VALUES ('$idingresonew',
                                    '{$detalle['idarticulo']}',
                                    '{$detalle['cantidad']}',
                                    '{$detalle['precio_compra']}',
                                    '{$detalle['precio_venta']}',
                                    '{$detalle['subtotal']}',
                                    '{$detalle['tipo_item']}')";
            if (!ejecutarConsulta($sql_detalle)) {
                $sw = false;
            }
        }

        return $sw ? "ok" : "error";         
    }  

    public function editar($idproduccion,$idusuario,$idproducto,$fecha_hora,$precio_compraProducto,$ganacia_producto,
    $precio_ventaProducto,$subtotalprecioCompra,$detalles_json)
    {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sql="UPDATE produccion
                SET 
                    idproducto = '$idproducto',
                    idusuario_update = '$idusuario',
                    fecha_modificacion = 'fechaHora',
                    precio_compra='$precio_compraProducto',
                    ganacia_producto='$ganacia_producto',
                    precio_venta='$precio_ventaProducto',
                    sub_total='$subtotalprecioCompra'
                WHERE idproduccion = '$idproduccion' AND idsucursal = '".$_SESSION["idsucursal"]."'";
        //return ejecutarConsulta($sql);
        $idingresonew=ejecutarConsulta_retornarID($sql);

            $sqlart="UPDATE articuloxsucursal asu SET 
                            asu.precio_compra='$precio_compraProducto',
                            asu.precio_venta='$precio_ventaProducto',
                            asu.stock_unidad='1', 
                            asu.ganacia_articulo='$ganacia_producto',
                            asu.tipo_ganacia='QUETZALES',
                            asu.precio_unidad='$precio_ventaProducto'
                            where asu.idarticulo='".$idproducto."' and asu.idsucursal='".$_SESSION["idsucursal"]."'  ";
                           // print_r($sqlart);
            ejecutarConsulta($sqlart);              

        $sqlDetalleIngresoElimminar="DELETE from detalle_produccion where idproduccion=".$idproduccion."";
        ejecutarConsulta($sqlDetalleIngresoElimminar);        
  
    /*    $num_elementos=0;
        $sw=true;
   
        while ($num_elementos < count($idarticulo))   
        {
            $sql_detalle = "INSERT INTO detalle_produccion(idproduccion,idarticulo,cantidad,precio_compra,precio_venta,subtotal) 
            VALUES ('$idproduccion', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]','$subtotal[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos=$num_elementos + 1;
        }
        return $sw;  
    */

        $sw = true;
        foreach ($detalles_json as $i => $detalle) {
            $sql_detalle = "INSERT INTO detalle_produccion (idproduccion,idarticulo,cantidad,precio_compra,precio_venta,subtotal,tipo_item)
                            VALUES ('$idproduccion',
                                    '{$detalle['idarticulo']}',
                                    '{$detalle['cantidad']}',
                                    '{$detalle['precio_compra']}',
                                    '{$detalle['precio_venta']}',
                                    '{$detalle['subtotal']}',
                                    '{$detalle['tipo_item']}')";
            if (!ejecutarConsulta($sql_detalle)) {
                $sw = false;
            }
        }

    return $sw ? "ok" : "error"; 
    }     
 
     
     public function anular($idproduccion)
     {
        $sql="UPDATE produccion SET estado='Anulado' WHERE idproduccion='$idproduccion'";
         return ejecutarConsulta($sql);
     } 
 





 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idproduccion)
    {
        $sql="SELECT 
                pro.idproduccion,
                pro.idproducto,
                a.nombre as articulo, 
                pro.cantidad_materia,
                DATE(pro.fecha_hora) as fecha,
                pro.precio_compra,
                pro.ganacia_producto,
                pro.precio_venta,
                pro.sub_total
                FROM produccion pro
                INNER JOIN articulo a ON pro.idproducto=a.idarticulo
                INNER JOIN usuario u ON pro.idusuario=u.idusuario
                WHERE pro.idproduccion='$idproduccion'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function obtenerdetalle($idproduccion){

        $sqldetalle="SELECT 
                    d.iddetalle_produccion,
                    d.idproduccion,
                    d.idarticulo,
                    a.nombre as articulo,
                    d.cantidad,
                    d.precio_compra,
                    d.precio_venta,
                    d.subtotal,
                    d.tipo_item
                 FROM detalle_produccion d 
                 INNER JOIN articulo a ON a.idarticulo=d.idarticulo
                  where d.idproduccion=".$idproduccion;

        #echo $sql;
        $rspta=ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg=$rspta->fetch_object()){
            $rows[] = $reg;
        }
        return $rows;
    }    
 

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                pro.idproduccion,
                pro.idproducto,
                a.nombre as nombrearticulo,
                pro.cantidad_materia,
                pro.idusuario,
                u.nombre as usuario,
                DATE(pro.fecha_hora) as fecha,
                pro.estado,
                pro.sub_total
                FROM produccion pro
                INNER JOIN usuario u ON pro.idusuario=u.idusuario
                inner join articulo a on a.idarticulo=pro.idproducto
                where pro.idsucursal = '".$_SESSION["idsucursal"]."'
                ORDER BY pro.idproduccion DESC";
        return ejecutarConsulta($sql);       
    }

    public function selectproduccion()
    {
        $sql="SELECT 
                p.idproduccion,
                p.idproducto,
                a.nombre as articulo,
                a.idarticulo,
                p.cantidad_materia,
                p.idusuario,
                p.fecha_hora,
                p.precio_compra,
                p.precio_venta,
                p.sub_total,
                p.estado,
                p.tipo_ingreso_producion
                from produccion p
                INNER JOIN articulo a ON p.idproducto=a.idarticulo WHERE a.condicion=1";
        return ejecutarConsulta($sql);      
    }    
     
}
 
?>