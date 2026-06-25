<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

/*define('_CLIENTE_', '114282226');
define('_USUARIO_', '114282226');
define('_PASS_', 'LKjuVTStNmkydZGSkC7IB4S');
define('_NIT_', '114282226');*/


/*

Password: sUSwj20xHwlSTDF@
*/


Class TomaoOrdenes
{ 
    //Implementamos nuestro constructor 
    public function __construct()
    {  
     
    }           
      
    //Implementamos un método para insertar registros
    public function insertar($idmesa,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$idcotizacion,$fecha_hora,$propina,$forma_pago,$tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,$rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,$observacion_credito,$idarticulo,$stockinven,$cantidadpresentacion,$cantidad,$totalcantidadpresentacion,$presen,$precio_ventaSistema,$precio_ventaSistema2,$q_ref,$precio_venta,$precio_recargoPV,$precio_recargoQRef,$descuento_porcentaje,$subtotal1,$subtotaldes1,$comentarios)
    { 
 
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
            if ($idcliente == '0') 
            {
                $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                ejecutarConsulta($sqlcorrelativo); 

                $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["codigo_cliente"]; 
                $codigo_cliente = 'COD' . $corre;

                $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre,
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono,
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente,fechaCreacion)
                                            VALUES ('Cliente',
                                                    '$nombre_cliente',
                                                    '$tipo_documento_cliente',
                                                    '$nit',
                                                    '$direccion_cliente',
                                                    '$telefono_cliente',
                                                    '$correo_cliente',
                                                    'PUBLICO',
                                                    '$codigo_cliente','$fechaHora')";
                $residcliente = ejecutarConsulta_retornarID($sqlcliente); 

                if (!$residcliente) {
                    throw new Exception("Error al insertar nuevo cliente.");
                }
            } else  
            {
                $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente'";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["codigo_cliente"]; 

                               // Verificamos si $corre es '0', está vacío o es null
                if (empty($corre) || $corre == '0') {
                    // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente
  
                    $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlcorrelativo); 

                    $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                    $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                    $corress = $correlativos["codigo_cliente"]; 
                    $codigo_clientes = 'COD' . $corress;    

                    $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente'";
                    ejecutarConsulta($sqlupdadtepersona);                         
                }

                $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion_cliente',
                                            telefono='$telefono_cliente',
                                            email='$correo_cliente',
                                            tipo_documento='$tipo_documento_cliente',
                                            nombre='$nombre_cliente'
                                    WHERE idpersona='$idcliente'";

                ejecutarConsulta($sqlcorrelativo);  
                $residcliente = $idcliente;
            }  
        ///////   

        ////datos establecimiento y persona
            $sqlEstablecimientoNum="SELECT * FROM sucursal WHERE idsucursal='".$_SESSION["idsucursal"]."'";
            $numestable= ejecutarConsultaSimpleFila($sqlEstablecimientoNum);
            $numestablecimiento=$numestable["num_establecimiento"];  
            $nombre_fel=$numestable["nombre_fel"];     
            $nombre_comercial=$numestable["nombre_comercial"];     
            $direccion_fiscal=$numestable["direccion_fiscal"];     
            $_CLIENTE_=$numestable["_CLIENTE_"];  
            $_USUARIO_=$numestable["_USUARIO_"];  
            $_PASS_=$numestable["_PASS_"];  
            $_NIT_=$numestable["_NIT_"];   

            $sqlPersona="SELECT * FROM persona WHERE idpersona='$residcliente'";
            $Persona= ejecutarConsultaSimpleFila($sqlPersona);
            #echo json_encode($Persona);
            $nit="CF";
            if($Persona["num_documento"]=="C/F"){}
            else{
               $flagNit=str_replace("-", "", $Persona["num_documento"]);
               if(strlen($flagNit)<= 15){
                    $nit=$Persona["num_documento"];
               }else{
                $nit="CF";
               }
            }
        ////fin datos establecimiento y persona        

            $sqlcorrelativo="UPDATE add_correlativo SET num_ordenesMesa=num_ordenesMesa+1 WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
             ejecutarConsulta($sqlcorrelativo); 

             $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'";
            $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
            $corre=$correlativo["num_ordenesMesa"];        

           $sql="INSERT INTO add_orden (idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
           tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,idmesa,propina,add_fecha_hora)
            VALUES ('$residcliente','$idusuario','".$_SESSION["idsucursal"]."','$tipo_comprobante','$corre','$fecha_hora','$total_venta','PENDIENTE','$cefectivo','$rescambio','$forma_pago','$total_ventades',
            '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$idmesa','$propina','$fechaHora')";

            $id_add_ordennew=ejecutarConsulta_retornarID($sql);  


                $sqlupdatemesa="UPDATE mesa SET condicion='2' WHERE idmesa='$idmesa'";    
                ejecutarConsulta($sqlupdatemesa); 

                $num_elementos=0; 
                $sw=true;   
                          

                while ($num_elementos < count($idarticulo))
                {
                    $sql_detalle = "INSERT INTO detalle_add_orden(id_add_orden,
                                                idarticulo,
                                                cantidad,
                                                precio_venta,
                                                descuento,
                                                stockinven,
                                                subtotaldes1,
                                                precio_ventaSistema,
                                                precio_ventaSistema2,
                                                subtotal1,
                                                cantidadpresentacion,
                                                totalcantidadpresentacion,
                                                presen,
                                                precio_recargo,
                                                q_ref,
                                                precio_recargoPV,
                                                precio_recargoQRef,comentarios) 
                                        VALUES ('$id_add_ordennew',
                                                '$idarticulo[$num_elementos]',
                                                '$cantidad[$num_elementos]',
                                                '$precio_venta[$num_elementos]',
                                                '$descuento_porcentaje[$num_elementos]',
                                                '$stockinven[$num_elementos]',
                                                '$subtotaldes1[$num_elementos]',
                                                '$precio_ventaSistema[$num_elementos]',
                                                '$precio_ventaSistema2[$num_elementos]',
                                                '$subtotal1[$num_elementos]',
                                                '$cantidadpresentacion[$num_elementos]',
                                                '$totalcantidadpresentacion[$num_elementos]',
                                                '$presen[$num_elementos]',
                                                '0',
                                                '$q_ref[$num_elementos]',
                                                '$precio_recargoPV[$num_elementos]',
                                                '$precio_recargoQRef[$num_elementos]',
                                                '$comentarios[$num_elementos]')";
                    ejecutarConsulta($sql_detalle) or $sw = false;                                                                                       


                    $num_elementos=$num_elementos + 1;
                }                       

   

 
 
        //return $idventanew;  

        return [
            'idventanew' => $id_add_ordennew,
            'tipo_comprobante' => $tipo_comprobante
        ]; 
    } 
 



    //Implementamos un método para insertar registros
    public function editar($id_add_orden,$idmesa,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$idcotizacion,
                $fecha_hora,$propina,$forma_pago,$tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,$rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,$observacion_credito,$idarticulo,$stockinven,$cantidadpresentacion,$cantidad,$totalcantidadpresentacion,$presen,$precio_ventaSistema,$precio_ventaSistema2,$q_ref,$precio_venta,$precio_recargoPV,
                $precio_recargoQRef,$descuento_porcentaje,$subtotal1,$subtotaldes1,$comentarios)
    { 
 
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
            if ($idcliente == '0') 
            {
                $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                ejecutarConsulta($sqlcorrelativo); 

                $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["codigo_cliente"]; 
                $codigo_cliente = 'COD' . $corre;

                $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre,
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono,
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente,fechaCreacion)
                                            VALUES ('Cliente',
                                                    '$nombre_cliente',
                                                    '$tipo_documento_cliente',
                                                    '$nit',
                                                    '$direccion_cliente',
                                                    '$telefono_cliente',
                                                    '$correo_cliente',
                                                    'PUBLICO',
                                                    '$codigo_cliente','$fechaHora')";
                $residcliente = ejecutarConsulta_retornarID($sqlcliente); 

                if (!$residcliente) {
                    throw new Exception("Error al insertar nuevo cliente.");
                }
            } else  
            {
                $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente'";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["codigo_cliente"]; 

                               // Verificamos si $corre es '0', está vacío o es null
                if (empty($corre) || $corre == '0') {
                    // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente
  
                    $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlcorrelativo); 

                    $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                    $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                    $corress = $correlativos["codigo_cliente"]; 
                    $codigo_clientes = 'COD' . $corress;    

                    $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente'";
                    ejecutarConsulta($sqlupdadtepersona);                         
                }

                $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion_cliente',
                                            telefono='$telefono_cliente',
                                            email='$correo_cliente',
                                            tipo_documento='$tipo_documento_cliente',
                                            nombre='$nombre_cliente'
                                    WHERE idpersona='$idcliente'";

                ejecutarConsulta($sqlcorrelativo);  
                $residcliente = $idcliente;
            }  
        ///////   

        ////datos establecimiento y persona
            $sqlEstablecimientoNum="SELECT * FROM sucursal WHERE idsucursal='".$_SESSION["idsucursal"]."'";
            $numestable= ejecutarConsultaSimpleFila($sqlEstablecimientoNum);
            $numestablecimiento=$numestable["num_establecimiento"];  
            $nombre_fel=$numestable["nombre_fel"];     
            $nombre_comercial=$numestable["nombre_comercial"];     
            $direccion_fiscal=$numestable["direccion_fiscal"];     
            $_CLIENTE_=$numestable["_CLIENTE_"];  
            $_USUARIO_=$numestable["_USUARIO_"];  
            $_PASS_=$numestable["_PASS_"];  
            $_NIT_=$numestable["_NIT_"];   

            $sqlPersona="SELECT * FROM persona WHERE idpersona='$residcliente'";
            $Persona= ejecutarConsultaSimpleFila($sqlPersona);
            #echo json_encode($Persona);
            $nit="CF";
            if($Persona["num_documento"]=="C/F"){}
            else{
               $flagNit=str_replace("-", "", $Persona["num_documento"]);
               if(strlen($flagNit)<= 15){
                    $nit=$Persona["num_documento"];
               }else{
                $nit="CF";
               }
            }
        ////fin datos establecimiento y persona        
     
            $sqlUpdateOrden = "UPDATE add_orden 
                    SET 
                        idcliente = '$residcliente',
                        total_venta = '$total_venta',
                        cefectivo = '$cefectivo',
                        rescambio = '$rescambio',
                        total_ventades = '$total_ventades',
                        tipo_pagoBacVisaNet = '$tipo_pagoBacVisaNet',
                        opcionesAdicionales = '$opcionesAdicionales',
                        valor_tarjeta = '$valor_tarjeta',
                        ccredito = '$ccredito',
                        observacion_credito = '$observacion_credito',
                        ctarjeta = '$ctarjeta',
                        ctransferencia = '$ctransferencia',
                        propina = '$propina'
                    WHERE id_add_orden = '$id_add_orden'";
                    ejecutarConsulta($sqlUpdateOrden);  

                $sql="DELETE from detalle_add_orden where id_add_orden=".$id_add_orden."";
                ejecutarConsulta($sql);

                $num_elementos=0; 
                $sw=true;   
                          

                while ($num_elementos < count($idarticulo))
                {
                    $sql_detalle = "INSERT INTO detalle_add_orden(id_add_orden,
                                                idarticulo,
                                                cantidad,
                                                precio_venta,
                                                descuento,
                                                stockinven,
                                                subtotaldes1,
                                                precio_ventaSistema,
                                                precio_ventaSistema2,
                                                subtotal1,
                                                cantidadpresentacion,
                                                totalcantidadpresentacion,
                                                presen,
                                                precio_recargo,
                                                q_ref,
                                                precio_recargoPV,
                                                precio_recargoQRef,comentarios) 
                                        VALUES ('$id_add_orden',
                                                '$idarticulo[$num_elementos]',
                                                '$cantidad[$num_elementos]',
                                                '$precio_venta[$num_elementos]',
                                                '$descuento_porcentaje[$num_elementos]',
                                                '$stockinven[$num_elementos]',
                                                '$subtotaldes1[$num_elementos]',
                                                '$precio_ventaSistema[$num_elementos]',
                                                '$precio_ventaSistema2[$num_elementos]',
                                                '$subtotal1[$num_elementos]',
                                                '$cantidadpresentacion[$num_elementos]',
                                                '$totalcantidadpresentacion[$num_elementos]',
                                                '$presen[$num_elementos]',
                                                '0',
                                                '$q_ref[$num_elementos]',
                                                '$precio_recargoPV[$num_elementos]',
                                                '$precio_recargoQRef[$num_elementos]',
                                                '$comentarios[$num_elementos]')";
                    ejecutarConsulta($sql_detalle) or $sw = false;

                    $num_elementos=$num_elementos + 1;
                }                       

   

 
 
        //return $idventanew;  

        return [
            'idventanew' => $id_add_orden,
            'tipo_comprobante' => $tipo_comprobante
        ]; 
    } 
 



    //Implementamos un método para anular la venta 
    public function anular($id_add_orden,$motivo)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 

        $sql="UPDATE add_orden SET estado='ANULADO',motivo='$motivo',delete_fecha_hora='$fechaHora',idusuario_delete='".$_SESSION["idusuario"]."' WHERE id_add_orden='$id_add_orden'";
        ejecutarConsulta($sql); 


        $sqlCorre="SELECT * FROM add_orden WHERE id_add_orden='$id_add_orden'";
        $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
        $idmesa=$correlativo["idmesa"];    

        $sqlupdatemesa="UPDATE mesa SET condicion='1' WHERE idmesa='$idmesa'";    
        ejecutarConsulta($sqlupdatemesa); 


        $sqlDetalleventa="SELECT * FROM detalle_add_orden WHERE id_add_orden='$id_add_orden'";
        $Detalle=ejecutarConsulta($sqlDetalleventa);


            while ($reg = $Detalle->fetch_object())
            {
                $updateArticuloDetalle="UPDATE articuloxsucursal SET stocksucursal=stocksucursal+".$reg->totalcantidadpresentacion." WHERE idarticulo=".$reg->idarticulo." and  idsucursal='".$_SESSION["idsucursal"]."' ";
                ejecutarConsulta($updateArticuloDetalle);
            }


        return ($sql);             
    }


    public function cabeceraOrden($id_add_orden){
        $sql="SELECT 
                c.id_add_orden,
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
                DATE(c.fecha_hora) as fecha,
                c.impuesto,
                c.total_venta,
                c.total_ventades,
                (c.total_venta + c.total_ventades) as total_general,
                c.forma_pago,
                c.estado,
                c.tipo_comprobante,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.nombre_comercial,
                s.nombre_fel,
                s.direccion_fiscal,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                c.num_comprobante,
                c.propina
                FROM add_orden c
                INNER JOIN persona p ON p.idpersona=c.idcliente
                INNER JOIN usuario u ON u.idusuario=c.idusuario
                INNER JOIN sucursal s ON s.idsucursal=c.idsucursal
            WHERE c.id_add_orden='$id_add_orden'";
        return ejecutarConsulta($sql);
    }  
    
    public function detalleOrdenMesa($id_add_orden){
        $sql="SELECT 
                a.nombre as articulo,
                a.codigo,
                round(d.cantidad,2) as cantidad,
                d.precio_venta,
                d.descuento,
                d.presen,
                round(d.q_ref,2) as q_ref,
                round(d.subtotal1,2) as subtotal,
                d.descripcion_detalle
                FROM detalle_add_orden d 
                INNER JOIN articulo a ON d.idarticulo=a.idarticulo 
            WHERE d.id_add_orden='$id_add_orden'";
        return ejecutarConsulta($sql);
    }       
 
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($id_add_orden)
    {
        $sql="SELECT 
            c.id_add_orden,
            c.idcliente,
            date(c.fecha_hora) AS fecha,
            c.total_venta,
            c.tipo_comprobante,
            c.total_ventades,
            c.tipo_pagoBacVisaNet,
            c.opcionesAdicionales,
            c.forma_pago,
            p.codigo_cliente,
            p.num_documento AS nit,
            p.nombre as nombre_cliente,
            p.telefono AS telefono_cliente,
            p.direccion AS direccion_cliente,
            p.email AS correo_cliente,
            p.tipo_documento AS tipo_documento_cliente,
            c.estado,
            c.valor_tarjeta,
            c.propina
         FROM add_orden c
         INNER JOIN persona p ON p.idpersona=c.idcliente
         WHERE  c.estado='PENDIENTE' and  c.cobradosino='NO' AND c.id_add_orden='$id_add_orden'";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function detalleorden($id_add_orden)
    {

        $sqldetalle="SELECT 
        dc.iddetalle_add_orden,
        dc.id_add_orden,
        dc.idarticulo,
        dc.cantidad,
        dc.precio_venta,
        dc.descuento,
        dc.descripcion_detalle,
        dc.stockinven,
        dc.cantidadpresentacion,
        dc.totalcantidadpresentacion,
        dc.presen,
        dc.precio_ventaSistema,
        dc.precio_ventaSistema2,
        dc.q_ref,
        dc.precio_recargoPV,
        dc.precio_recargoQRef,
        dc.subtotal1,
        dc.subtotaldes1,
        dc.comentarios,
        a.nombre,
        asu.idsucursal,
        asu.stocksucursal as stock,
        asu.stockminimo,
        asu.precio_compra,
        asu.precio_ventaNocturno,
        asu.precio_descuento,
        asu.precio_rango1,
        asu.precio_rango2,
        asu.precio_rango3,
        asu.stock_unidad,
        asu.precio_unidad,
        asu.stock_blister,
        asu.precio_blister,
        asu.stock_caja,
        asu.precio_caja,
        asu.stock_fardo,
        asu.precio_fardo,
        asu.stock_sacos,
        asu.precio_sacos,
        asu.stock_paquete,
        asu.precio_paquete,
        asu.precio_rango1,
        asu.precio_rango2,
        asu.precio_rango3,
        asu.condicion                  
        from detalle_add_orden dc 
        INNER JOIN articulo a on dc.idarticulo=a.idarticulo 
        INNER JOIN articuloxsucursal asu ON asu.idarticulo=a.idarticulo
        where asu.idsucursal='".$_SESSION["idsucursal"]."'  and dc.id_add_orden=".$id_add_orden;

#echo $sql;
        $rspta=ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg=$rspta->fetch_object()){
            $rows[] = $reg;
        }
        return $rows;
    }

 
    public function listarDetalle($idventa)
    {
        $sql="SELECT dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,
        dv.descuento,
                        ROUND((dv.cantidad*(dv.precio_venta-((dv.precio_venta*dv.descuento)/100))),2) as subtotal FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar($fecha_inicio_reporte,$fecha_fin_reporte)
    {
        $sql="SELECT 
                v.idventa,
                DATE(v.fecha_hora) as fecha,
                v.idcliente,
                p.nombre as cliente,
                u.idusuario,
                u.nombre as usuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_ventades,
                v.impuesto,  
                v.estado,
                v.cefectivo,
                v.rescambio,
                v.forma_pago,
                v.tipo_pagoBacVisaNet,
                v.opcionesAdicionales,
                ROUND(v.valor_tarjeta, 2) AS valor_tarjeta,  -- Redondeamos valor_tarjeta a 2 decimales
                v.ctarjeta,
                v.ccredito,
                v.ctransferencia,
                v.nombre_vendedor,
                v.autorizacionEcoFactura, 
                v.serie_ecoFactura,
                v.numero_ecoFactura, 
                v.fechaCertificacion_ecoFactura
            FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario
            where u.idusuario='".$_SESSION["idusuario"]."' and  DATE(v.fecha_hora)>='$fecha_inicio_reporte' AND DATE(v.fecha_hora)<='$fecha_fin_reporte'
            order by  v.idventa DESC   "; 
        return ejecutarConsulta($sql);      
    } 


    public function listarVentasCierre($idcuadre_caja)
    {
        date_default_timezone_set('America/Guatemala');
        $sql="SELECT 
                v.idventa,
                DATE(v.fecha_hora) as fecha,
                v.idcliente,
                p.nombre as cliente,
                u.idusuario,
                u.nombre as usuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_ventades,
                v.impuesto,
                v.estado,
                v.cefectivo,
                v.rescambio,
                v.forma_pago,
                v.nombre_vendedor,
                v.autorizacionEcoFactura, 
                v.serie_ecoFactura,
                v.numero_ecoFactura, 
                v.fechaCertificacion_ecoFactura
            FROM venta v 
            INNER JOIN persona p ON v.idcliente=p.idpersona 
            INNER JOIN usuario u ON v.idusuario=u.idusuario
            where u.idusuario='".$_SESSION["idusuario"]."' and DATE(v.fecha_hora)=curdate() and v.tipo_operacion='CIERRE' and v.tipo_comprobante='Envio'  "; 
        return ejecutarConsulta($sql);      
    }     


    public function ventacabecera($idventa)
    {
        $sql="SELECT 
        v.idventa,
        v.idcliente,
        p.nombre as cliente,
        p.direccion,
        p.tipo_documento,
        p.num_documento,
        p.email,p.telefono,v.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,date(v.fecha_hora) as fecha,v.impuesto,v.total_venta, v.total_ventades FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=U.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function ventadetalle($idventa){
        $sql="SELECT 
            a.nombre as articulo,
            a.codigo,
            d.cantidad,
            d.precio_venta,
            d.descuento,
            d.descripcion_detalle,
            ROUND((d.cantidad*(d.precio_venta-((d.precio_venta*d.descuento)/100))),2) as subtotal,
            ((d.precio_venta*d.descuento)/100) as total_descuento FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo  WHERE d.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function ventadetalletotalpeso($idventa)
    {
        $sql="SELECT SUM(d.cantidad*a.peso_producto) as peso FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function listarMesas()
    {
        $sql="SELECT * FROM mesa WHERE condicion=1";
        return ejecutarConsulta($sql);      
    }

    public function listarOrddenes()
    {
        $sql="SELECT 
            ao.id_add_orden,
            mer.nombre,
            ao.total_venta,
            ao.condicion,
            ao.idmesa
             FROM add_orden ao
             INNER JOIN mesa mer ON mer.idmesa=ao.idmesa
             WHERE mer.condicion=2 and ao.estado='PENDIENTE' 
             "; 
        return ejecutarConsulta($sql);      
    } 


   public function eliminarOrdendetallebitacora($iddetalle_add_orden,$valormotivo)
    {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 

        $sqldetalleaddorden="SELECT 
                        d.id_add_orden,
                        d.idarticulo,
                        d.cantidad,
                        d.q_ref
                     FROM detalle_add_orden d WHERE iddetalle_add_orden='$iddetalle_add_orden' ";
        $detalleaddorden= ejecutarConsultaSimpleFila($sqldetalleaddorden);
        $id_add_orden=$detalleaddorden["id_add_orden"];  
        $idarticulo=$detalleaddorden["idarticulo"];  
        $cantidad=$detalleaddorden["cantidad"];  

        $sqldetalleaddordencabezara="SELECT 
                                a.idmesa,
                                a.idcliente,
                                a.fecha_hora,
                                a.total_venta,
                                a.propina,
                                a.idusuario,
                                a.estado,
                                a.add_fecha_hora
                     FROM add_orden a WHERE id_add_orden='$id_add_orden' ";
        $detalleaddordencabeza= ejecutarConsultaSimpleFila($sqldetalleaddordencabezara);
        $idmesa=$detalleaddordencabeza["idmesa"]; 
        $idcliente=$detalleaddordencabeza["idcliente"]; 
        $fecha_hora_orden=$detalleaddordencabeza["fecha_hora"]; 
        $total_venta=$detalleaddordencabeza["total_venta"];
        $propina_sugerida=$detalleaddordencabeza["propina"];
        $idusuario_orden=$detalleaddordencabeza["idusuario"];
        $estado_orden=$detalleaddordencabeza["estado"];             
        $add_fecha_hora_orden=$detalleaddordencabeza["add_fecha_hora"];             
        

           $sql="INSERT INTO detalle_add_ordenanulacion (id_add_orden,
                                                        idarticulo,
                                                        cantidad,
                                                        iddetalle_add_orden,
                                                        valormotivo,
                                                        estado,
                                                        idusuario,
                                                        idsucursal,
                                                        idmesa,
                                                        idcliente,
                                                        fecha_hora_orden,
                                                        total_venta,
                                                        propina_sugerida,
                                                        idusuario_orden,
                                                        estado_orden,
                                                        add_fecha_hora_orden,
                                                        delete_fecha_hora)
                                                VALUES ('$id_add_orden',
                                                        '$idarticulo',
                                                        '$cantidad',
                                                        '$iddetalle_add_orden',
                                                        '$valormotivo',
                                                        'ANULADO',
                                                        '".$_SESSION["idusuario"]."',
                                                        '".$_SESSION["idsucursal"]."',
                                                        '$idmesa',
                                                        '$idcliente',
                                                        '$fecha_hora_orden',
                                                        '$total_venta',
                                                        '$propina_sugerida',
                                                        '$idusuario_orden',
                                                        '$estado_orden',
                                                        '$add_fecha_hora_orden','$fechaHora')";
              ejecutarConsulta($sql); 


        $sqldelete="DELETE FROM detalle_add_orden WHERE iddetalle_add_orden='$iddetalle_add_orden'";
       return  ejecutarConsulta($sqldelete);
    }        



}
?>