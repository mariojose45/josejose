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


Class Devolucio
{ 
    //Implementamos nuestro constructor 
    public function __construct()
    {  
     
    }           
      
    //Implementamos un método para insertar registros
    public function insertar($idventa,$idcliente,$codigo_cliente,$nit,$nombre_cliente,$telefono_cliente,$direccion_cliente,$correo_cliente,$tipo_documento_cliente,$idusuario,$idcotizacion,$fecha_hora,$forma_pago,$tipo_comprobante,$total_venta,$total_ventades,$cefectivo,$ccredito,$ctarjeta,$ctransferencia,$rescambio,$valor_tarjeta,$tipo_pagoBacVisaNet,$opcionesAdicionales,$observacion_credito,
                $idarticulo,
                $stockinven,
                $cantidadpresentacion,
                $cantidad,
                $totalcantidadpresentacion,
                $presen,
                $precio_ventaSistema,
                $precio_ventaSistema2,
                $q_ref,
                $precio_venta,
                $precio_recargoPV,
                $precio_recargoQRef,
                $descuento_porcentaje,
                $subtotal1,
                $subtotaldes1,$fecha_horaNC,$razon_devolucion,$tipo_comprobanteNC,$cNotaCredito)
    { 
 
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 


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

        ////fin datos establecimiento y persona        


       if($tipo_comprobante=="Envio" )
        {

            $sqlcorrelativo="UPDATE add_correlativo SET num_devolucion=num_devolucion+1 WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
             ejecutarConsulta($sqlcorrelativo); 

             $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'";
            $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
            $corre=$correlativo["num_envio"];        

           $sql="INSERT INTO nota_credito (idventa,idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
           tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,cNotaCredito,tipo_comprobanteNC,fecha_horaNC,razon_devolucion)
            VALUES ('$idventa','$idcliente','$idusuario','".$_SESSION["idsucursal"]."','$tipo_comprobante','$corre','$fecha_hora','$total_venta','Aceptado','$cefectivo','$rescambio','$forma_pago','$total_ventades',
            '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$cNotaCredito','$tipo_comprobanteNC','$fecha_horaNC','$razon_devolucion')";
            $idnota_creditonew=ejecutarConsulta_retornarID($sql);  
         

                $num_elementos=0; 
                $sw=true;   
                          

                while ($num_elementos < count($idarticulo))
                {
                    $sql_detalle = "INSERT INTO detalle_nota_credito(idnota_credito,
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
                                                precio_recargoQRef) 
                                        VALUES ('$idnota_creditonew',
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
                                                '$precio_recargoQRef[$num_elementos]')";
                    ejecutarConsulta($sql_detalle) or $sw = false;

                    $sqlArticuloStock="UPDATE articuloxsucursal SET stocksucursal = stocksucursal + ".$totalcantidadpresentacion[$num_elementos]." WHERE idarticulo =$idarticulo[$num_elementos]  and idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlArticuloStock);



                    $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,
                                                                                    cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,
                                                                                    idsucursal) 
                                                                            VALUES ('0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$idnota_creditonew',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$totalcantidadpresentacion[$num_elementos]',
                                                                                    '0',
                                                                                    '$stockinven[$num_elementos]',
                                                                                    '$fechaHora',
                                                                                    '$idarticulo[$num_elementos]',
                                                                                    '$idusuario',
                                                                                    '".$_SESSION["idsucursal"]."')";
                                                                                    ejecutarConsulta($sql_detalleoperaciones);   

                    //valida el descuento de la materia prima
                    $sqlVerificacionExistencia="SELECT 
                                    p.idproducto,
                                    dp.cantidad as cantmateriaprima,
                                    dp.idarticulo as idarticulo_costo
                                 FROM produccion p 
                                 INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                 WHERE p.idproducto='$idarticulo[$num_elementos]' ";
                    $EXIS=ejecutarConsulta($sqlVerificacionExistencia);

                        $numexis=0; 
 
                    while($reeeq=$EXIS->fetch_object())
                        {
                            $updateArticuloDetalle="UPDATE articuloxsucursal SET stocksucursal=stocksucursal+('".$cantidad[$num_elementos]."'*'".$reeeq->cantmateriaprima."') 
                            WHERE idarticulo='".$reeeq->idarticulo_costo."'   and idsucursal='".$_SESSION["idsucursal"]."' ";
                            ejecutarConsulta($updateArticuloDetalle);
                            $numexis++;
                        }
                        if($numexis==0){
                        }
                    //fin de validacion del descuento de la materia prima                                                                                                  


                    $num_elementos=$num_elementos + 1;
                }                       

        }
        elseif($tipo_comprobante=="Factura" ) 
        {
                $sqlcorrelativo="UPDATE add_correlativo SET num_factura=num_factura+1 WHERE idsucursal='".$_SESSION["idsucursal"]."'";
                 ejecutarConsulta($sqlcorrelativo); 

                 $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'";
                $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
                $corre=$correlativo["num_factura"];        


               $sql="INSERT INTO nota_credito (idventa,idcliente,idusuario,idsucursal,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,cefectivo,rescambio,forma_pago,total_ventades,
               tipo_pagoBacVisaNet,opcionesAdicionales,valor_tarjeta,ccredito,observacion_credito,ctarjeta,ctransferencia,cNotaCredito,tipo_comprobanteNC,fecha_horaNC,razon_devolucion)
                VALUES ('$idventa','$idcliente','$idusuario','".$_SESSION["idsucursal"]."','$tipo_comprobante','$corre','$fecha_hora','$total_venta','Aceptado','$cefectivo','$rescambio','$forma_pago','$total_ventades',
                '$tipo_pagoBacVisaNet','$opcionesAdicionales','$valor_tarjeta','$ccredito','$observacion_credito','$ctarjeta','$ctransferencia','$cNotaCredito','$tipo_comprobanteNC','$fecha_horaNC','$razon_devolucion')";

           
                date_default_timezone_set("America/Guatemala");
                        $fechaTransaccion=str_replace("CST","T",str_replace("UTC","T",date("Y-m-dTH:i:s")));

                       
    
                $JsonIntegracionEcoFactura='{
                    "usuario":"'.$_CLIENTE_.'",
                    "clave":"'.$_PASS_.'",   
                    "nit":"'.$_NIT_.'",                                      
                    "tipoDocumento": "FACT",
                    "establecimiento": '.$numestablecimiento.',
                    "tipoDocumentoInt": 1,
                    "direccionCliente": "'.$Persona["direccion"].'",
                    "direccionEmisor": "'.$direccion_fiscal.'",
                    "emisorNombreComercial":"'.$nombre_comercial.'",
                    "emisorNombre":"'.$nombre_fel.'",
                    "maquina": "1",
                    "tipoVenta": "B", 
                    "bruto": "'.$total_venta.'",
                    "descuento": "'.$total_ventades.'",
                    "exento":"0",
                    "otros":"0",
                    "neto":"0",
                    "isr":"0",
                    "total":"'.$total_venta.'",                   
                    "numeroTransaccion": "'.$idventanew.'",
                    "fechaTransaccion": "'.$fechaTransaccion.'",
                    "tipoMoneda":"GTQ",
                    "nitCliente": "'.$nit.'",
                    "codigoCliente": "'.$Persona["idpersona"].'",
                    "nombreCliente": "'.$Persona["nombre"].'",
                    "direccionCliente": "'.$Persona["direccion"].'",
                    "correoCliente": "'.$Persona["email"].'",
                    "detallesDocumento":[{DetalleFactura}],
                    "TrnEstNum":"'.$numestablecimiento.'"
                }';
         
                $num_elementos=0;  
                $sw=true;
                $JsonDetalleFacturaIntegracion="";
                while ($num_elementos < count($idarticulo))
                {
                    $sql_detalle = "INSERT INTO detalle_nota_credito(idnota_credito,
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
                                                precio_recargoQRef) 
                                        VALUES ('$idnota_creditonew',
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
                                                '$precio_recargoQRef[$num_elementos]')";
                    ejecutarConsulta($sql_detalle) or $sw = false;

                    $sqlArticulo="SELECT * FROM articulo WHERE idarticulo='$idarticulo[$num_elementos]'";
                    $Articulo= ejecutarConsultaSimpleFila($sqlArticulo);

                    $sqlArticuloStock="UPDATE articuloxsucursal SET stocksucursal = stocksucursal + ".$totalcantidadpresentacion[$num_elementos]." WHERE idarticulo =$idarticulo[$num_elementos]  and idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlArticuloStock);

                        $resvalidarimpuesto='false'; 



                    if($num_elementos==0){
                        $JsonDetalleFacturaIntegracion.='{
                            "numeroLinea": "'.($num_elementos+1).'",
                            "codigoArticulo": "'.$Articulo["codigo"].'",
                            "nombreArticulo": "'.$Articulo["nombre"].'",
                            "cantidadArticulo": "'.$cantidad[$num_elementos].'",
                            "valorUnitario": "'.$q_ref[$num_elementos].'",
                            "unidadMedida": "Unidad",
                            "valorDescuento": "'.$subtotaldes1[$num_elementos].'",
                            "tipoItem": "B",
                            "impBruto": "0",
                            "impDescuento": "'.$subtotaldes1[$num_elementos].'",
                            "impExento": "0",
                            "impOtros": "0",
                            "impTotal": "'.$q_ref[$num_elementos].'",
                            "isExcepto": '.$resvalidarimpuesto.'

                        }';
                    }else{
                        $JsonDetalleFacturaIntegracion.=',{
                            "numeroLinea": "'.($num_elementos+1).'",
                            "codigoArticulo": "'.$Articulo["codigo"].'",
                            "nombreArticulo": "'.$Articulo["nombre"].'",
                            "cantidadArticulo": "'.$cantidad[$num_elementos].'",
                            "valorUnitario": "'.$q_ref[$num_elementos].'",
                            "unidadMedida": "Unidad",
                            "valorDescuento": "'.$subtotaldes1[$num_elementos].'",
                            "tipoItem": "B",
                            "impBruto": "0",
                            "impDescuento": "'.$subtotaldes1[$num_elementos].'",
                            "impExento": "0",
                            "impOtros": "0",
                            "impTotal": "'.$q_ref[$num_elementos].'",
                            "isExcepto": '.$resvalidarimpuesto.'                        
                        }';
                    }

                    $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,iddevolucion,cantidad_compras,cantidad_ventas,
                                                                                    cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,
                                                                                    idsucursal) 
                                                                            VALUES ('0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$idnota_creditonew',
                                                                                    '0',
                                                                                    '0',
                                                                                    '0',
                                                                                    '$totalcantidadpresentacion[$num_elementos]',
                                                                                    '0',
                                                                                    '$stockinven[$num_elementos]',
                                                                                    '$fechaHora',
                                                                                    '$idarticulo[$num_elementos]',
                                                                                    '$idusuario',
                                                                                    '".$_SESSION["idsucursal"]."')";
                                                                                    ejecutarConsulta($sql_detalleoperaciones);     
                    //valida el descuento de la materia prima
                    $sqlVerificacionExistencia="SELECT 
                                    p.idproducto,
                                    dp.cantidad as cantmateriaprima,
                                    dp.idarticulo as idarticulo_costo
                                 FROM produccion p 
                                 INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                 WHERE p.idproducto='$idarticulo[$num_elementos]' ";
                    $EXIS=ejecutarConsulta($sqlVerificacionExistencia);

                        $numexis=0; 
 
                    while($reeeq=$EXIS->fetch_object())
                        {
                            $updateArticuloDetalle="UPDATE articuloxsucursal SET stocksucursal=stocksucursal+('".$cantidad[$num_elementos]."'*'".$reeeq->cantmateriaprima."') 
                            WHERE idarticulo='".$reeeq->idarticulo_costo."'   and idsucursal='".$_SESSION["idsucursal"]."' ";
                            ejecutarConsulta($updateArticuloDetalle);
                            $numexis++;
                        }
                        if($numexis==0){
                        }
                    //fin de validacion del descuento de la materia prima                                                                                                  

                       
         
                    $num_elementos=$num_elementos + 1;
                }


                $JsonIntegracionEcoFactura=str_replace("{DetalleFactura}", $JsonDetalleFacturaIntegracion, $JsonIntegracionEcoFactura);                

            }     
 
               

/*
       //API URL
        if($tipo_comprobante=="Factura"){
            //URLS de Desarrollo
            //$url = 'http://daocastro-001-site8.itempurl.com/api/Megaprint/generarDocumento'; //url de pruebas
           $url='http://api.fel.olintech.com/api/Megaprint/generarDocumento';//url de produccion
            $resultado=$this->callAPI("POST", $url, $JsonIntegracionEcoFactura);
            $ArrayResultado=json_decode($resultado, true);

            print_r($JsonIntegracionEcoFactura);
            print_r($resultado);
            print_r($ArrayResultado);
 
   

            try {
                $sqlUpdate="UPDATE venta SET autorizacionEcoFactura='".$ArrayResultado["numeroAutorizacion"]."',serie_ecoFactura='".$ArrayResultado["serie"]."',numero_ecoFactura='".$ArrayResultado["numero"]."',fechaCertificacion_ecoFactura='".$ArrayResultado["fechaCertificacion"]."',fecha_hora='".$fechaTransaccion."' WHERE idventa='$idventanew'";
                ejecutarConsulta($sqlUpdate); 

                $sqlCorre="SELECT * FROM venta WHERE  idventa='$idventanew'";
                $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
                $fechaCertificacion_ecoFactura=$correlativo["fechaCertificacion_ecoFactura"];        

               if ($fechaCertificacion_ecoFactura=="" || $fechaCertificacion_ecoFactura=="0000-00-00 00:00:00" ) 
                {
                    # code...
                    $sqlUpdatenovalidado="UPDATE venta SET tipo_comprobante='Envio' WHERE idventa='$idventanew'";
                    ejecutarConsulta($sqlUpdatenovalidado); 

                     $sqlLgs="INSERT INTO logs (idventa,idusuario,idsucursal,JsonIntegracionEcoFactura,resultado,ArrayResultado)
                        VALUES ('$idventanew','$idusuario','".$_SESSION["idsucursal"]."' ,'$JsonIntegracionEcoFactura','$resultado','$ArrayResultado')";
                        ejecutarConsulta($sqlLgs);

                }



            } catch (\Throwable $th) {
                
            }          
        }
*/
 
 
        //return $idventanew;  

        return [
            'idventanew' => $idnota_creditonew,
            'tipo_comprobante' => $tipo_comprobante
        ]; 
    } 
 




    function callAPI($method, $url, $data)
    {
        $curl = curl_init();
        switch ($method){
           case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                             
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30000);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'Content-type: application/json',
           'Accept: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_HEADER, false); 
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        #echo "Status Code: ".$http_status;
        if(!$result){
            die("Status Code".$http_status." Error:".curl_error($curl)." Connection Failure");
        }
        curl_close($curl);
        return $result;
     }
    //Implementamos un método para anular la venta 
    public function anular($idventa)
    {
        $sql="UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
        ejecutarConsulta($sql); 

        $sqlOperacionesCompraVenta="UPDATE operaciones_compras_ventas SET estado='Anulado' WHERE idventa='$idventa'";
        ejecutarConsulta($sqlOperacionesCompraVenta); 
 


        $sqlDetalleventa="SELECT * FROM detalle_venta WHERE idventa='$idventa'";
        $Detalle=ejecutarConsulta($sqlDetalleventa);


            while ($reg = $Detalle->fetch_object())
            {
                $updateArticuloDetalle="UPDATE articuloxsucursal SET stocksucursal=stocksucursal+".$reg->totalcantidadpresentacion." WHERE idarticulo=".$reg->idarticulo." and  idsucursal='".$_SESSION["idsucursal"]."' ";
                ejecutarConsulta($updateArticuloDetalle);
            }

        $sqlArticulo="SELECT * FROM venta WHERE idventa='$idventa'";
        $Venta= ejecutarConsultaSimpleFila($sqlArticulo);  


            if ($Venta["serie_ecoFactura"]<>"") {
                        try {
                        if($Venta["tipo_comprobante"]<>"ENVIO"){
                            $JsonAnulacionFactrua='{
                                "numeroAutorizacion": "'.$Venta["autorizacionEcoFactura"].'",
                                "motivoAnulacion": "Anulacion De Factura",
                                "cliente":"'._CLIENTE_.'",
                                "usuario":"'._USUARIO_.'",
                                "clave":"'._PASS_.'",
                                "nit":"'._NIT_.'",
                                "fechaTransaccion":"'.str_replace("CEST","T",str_replace("UTC","T",date("Y-m-dTH:i:s",strtotime($Venta["fecha_hora"])))).'",
                                "nitComprador": "'.$Venta["nitcomprador"].'",
                                "fechaAnulacion": "'.str_replace("CEST","T",str_replace("UTC","T",date("Y-m-dTH:i:s"))).'"

                            }';
                            //URL de desarrollo
                            $url = 'http://daocastro-001-site8.itempurl.com/api/Megaprint/anularDocumento';
                           //$url="http://api.fel.olintech.com/api/Megaprint/anularDocumento";
                            $resultado=$this->callAPI("POST", $url, $JsonAnulacionFactrua);
                            //echo $resultado;
                            $ArrayResultado=json_decode($resultado, true);
                          // print_r($JsonAnulacionFactrua);
                        }
                    } catch (\Throwable $th) {
                        #echo $th;
                    } 
                # code...
            }                       

        return ($sql);             
    }
 
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idventa)
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
                v.impuesto,
                v.estado,
                v.forma_pago,
                DATE(v.fecha_hora_siguiente_pago) as fechahorasiguientepago,
                v.observacion_credito
                FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
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
}
?>