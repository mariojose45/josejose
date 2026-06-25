<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
 

define('_CLIENTE_', '94073058');
define('_USUARIO_', 'ADMIN');
define('_PASS_', 'T@c0sl@ch');
define('_NIT_', '94073058');
 
Class AddOrden   
{
    //Implementamos nuestro constructor
    public function __construct() 
    {  
  
    }  
 
    //Implementamos un método para insertar registros
    public function insertar($idmesa,$no_personas,$idcliente,$mesero,$fecha_hora,$total_venta,$total,$descuento_orden,$propina_sugerida,$total_final,$idusuario,$idarticulo,$cantidad,$descripcion_detalle,$precio_venta,$forma_pago,$cefectivo,$cefectivo_tarjeta,$no_autorizacion_tarjeta,$rescambio,$propina_sugeridatotal)
        {
                $sql="INSERT INTO add_orden (idmesa,no_personas,idcliente,mesero,fecha_hora,total_venta,total,descuento_orden,propina_sugerida,total_final,idusuario,estado,forma_pago,propina_sugeridatotal)
                VALUES ('$idmesa','$no_personas','$idcliente','$mesero','$fecha_hora','$total_venta','$total','$descuento_orden','$propina_sugerida','$total_final','$idusuario','ORDEN PENDIENTE','$forma_pago','$propina_sugeridatotal')";
                //return ejecutarConsulta($sql);
                $idingresonew=ejecutarConsulta_retornarID($sql);
         
                $num_elementos=0;  
                $sw=true; 
         
                while ($num_elementos < count($idarticulo))  
                {
                    $sql_detalle = "INSERT INTO detalle_add_orden(id_add_orden, idarticulo,cantidad,descripcion_detalle,precio_venta) VALUES ('$idingresonew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$descripcion_detalle[$num_elementos]','$precio_venta[$num_elementos]')";
                    ejecutarConsulta($sql_detalle) or $sw = false;
                    $num_elementos=$num_elementos + 1;
                } 

                $sqlupdatemesa="UPDATE mesa SET condicion='2' WHERE idmesa='$idmesa'";    
                ejecutarConsulta($sqlupdatemesa);            

                return $sw; 
        }
     
    //Implementamos un método para editar registros
    public function editar($id_add_orden,$idmesa,$no_personas,$idcliente,$mesero,$fecha_hora,$total_venta,$total,$descuento_orden,$propina_sugerida,$total_final,$idusuario,$idarticulo,$cantidad,$descripcion_detalle,$precio_venta,$forma_pago,$cefectivo,$cefectivo_tarjeta,$no_autorizacion_tarjeta,$rescambio,$propina_sugeridatotal,$separar)
    {
        $sql="UPDATE add_orden SET idmesa='$idmesa',no_personas='$no_personas',idcliente='$idcliente',mesero='$mesero',fecha_hora='$fecha_hora',total_venta='$total_venta',total='$total',descuento_orden='$descuento_orden',propina_sugerida='$propina_sugerida',total_final='$total_final',idusuario='$idusuario',forma_pago='$forma_pago',propina_sugeridatotal='$propina_sugeridatotal' WHERE id_add_orden='$id_add_orden'";
         ejecutarConsulta($sql); 

                /*$sqlDetalledelete="DELETE FROM detalle_add_orden  WHERE id_add_orden='$id_add_orden'";
                ejecutarConsulta($sqlDetalledelete);*/

                $idingresonew=$id_add_orden;
         
                $num_elementos=0;  
                $sw=true; 
         
                while ($num_elementos < count($idarticulo))   
                {
                    if($separar[$num_elementos]==0){
                        #echo "elemento".$separar[$num_elementos];
                        $sql_detalle = "INSERT INTO detalle_add_orden(id_add_orden, idarticulo,cantidad,descripcion_detalle,precio_venta) VALUES ('$idingresonew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$descripcion_detalle[$num_elementos]','$precio_venta[$num_elementos]')";
                        ejecutarConsulta($sql_detalle) or $sw = false;
                    }
                    $num_elementos=$num_elementos + 1;
                }          

                return $sw;            

                       
    }

    public function guardaryeditarCobrar($id_add_orden,$idmesa,$no_personas,$idcliente,$mesero,$fecha_hora,$total_venta,$total,$descuento_orden,$propina_sugerida,$total_final,$idusuario,$idarticulo,$cantidad,$descripcion_detalle,$precio_venta,$forma_pago,$cefectivo,$cefectivo_tarjeta,$no_autorizacion_tarjeta,$rescambio,$propina_sugeridatotal,$no_autorizacion_tarjeta2,$tipo_comprobante,$tipo_tarjeta)
    {
        $sql="UPDATE add_orden SET idmesa='$idmesa',no_personas='$no_personas',idcliente='$idcliente',mesero='$mesero',fecha_hora='$fecha_hora',total_venta='$total_venta',total='$total',descuento_orden='$descuento_orden',propina_sugerida='$propina_sugerida',total_final='$total_final',idusuario='$idusuario',estado='ORDEN COBRADA',forma_pago='$forma_pago',propina_sugeridatotal='$propina_sugeridatotal',tipo_tarjeta='$tipo_tarjeta' WHERE id_add_orden='$id_add_orden'";
         ejecutarConsulta($sql); 

                $sqlupdatemesa="UPDATE mesa SET condicion='1' WHERE idmesa='$idmesa'";    
                ejecutarConsulta($sqlupdatemesa);           


                $idingresonew=$id_add_orden;
         
                $num_elementos=0;  
                $sw=true;                    

                $sqlPersona="SELECT * FROM persona WHERE idpersona='$idcliente'";
                $Persona= ejecutarConsultaSimpleFila($sqlPersona);
                #echo json_encode($Persona);
                $nit="CF";
                if($Persona["num_documento"]=="C/F"){}
                else{
                   $flagNit=str_replace("-", "", $Persona["num_documento"]);
                  //  if(strlen($flagNit)==8 || strlen($flagNit)==6){
                   if(strlen($flagNit)<= 15){
                        $nit=$Persona["num_documento"];
                   }else{ 
                    $nit="CF";
                   }
                }


                $sqlVenta="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,fecha_hora,total_venta,estado,total_ventades,cefectivo_tarjeta,cefectivo,rescambio,nombre_vendedor,forma_pago,saldo_venta,id_add_orden,propina_sugerida,propina_sugeridatotal,observacion_credito,tipo_tarjeta)

                VALUES ('$idcliente','$idusuario','$tipo_comprobante','$fecha_hora','$total_final','Aceptado','$descuento_orden','$cefectivo_tarjeta','$cefectivo','$rescambio','$mesero','$forma_pago','$total_final','$id_add_orden','$propina_sugerida','$propina_sugeridatotal','$no_autorizacion_tarjeta','$tipo_tarjeta')";
               
                $idventanew=ejecutarConsulta_retornarID($sqlVenta);  

 

       if($tipo_comprobante=="Factura" )
            {
    
                $JsonIntegracionEcoFactura='{
                    "tipoDocumento": "FACT",
                    "numeroTransaccion": "'.$idventanew.'",
                    "fechaTransaccion": "'.$fecha_hora.'",
                    "tipoMoneda": "GTQ",
                    "nitCliente": "'.$nit.'",
                    "codigoCliente": "'.$Persona["idpersona"].'",
                    "nombreCliente": "'.$Persona["nombre"].'",
                    "direccionCliente": "'.$Persona["direccion"].'",
                    "observacion": "0",
                    "correoCliente": "'.$Persona["email"].'",
                    "detallesDocumento":[{DetalleFactura}],
                    "cliente":"'._CLIENTE_.'",
                    "usuario":"'._USUARIO_.'",
                    "clave":"'._PASS_.'",
                    "nit":"'._NIT_.'",
                            "TrnExp":"0",
                            "TrnExento":"0",
                            "TrnFraseTipo":"0",
                            "TrnEscCod":"0",
                             "TrnEstNum":"2"
                }';

               $num_elementosV=0; 
                $swv=true;
                $JsonDetalleFacturaIntegracion="";

                $consulta_mierda="select * from detalle_add_orden where id_add_orden=".$id_add_orden." and estado='0' ";
                $puto=ejecutarConsulta($consulta_mierda);
                $cerote=0;
                while ($putoreq=$puto->fetch_object())
                {
                    $sql_detalle = "INSERT INTO detalle_venta(idventa, idarticulo,cantidad,precio_venta,descripcion_detalle) VALUES ('$idventanew', '".$putoreq->idarticulo."','".$putoreq->cantidad."','".$putoreq->precio_venta."','".$putoreq->descripcion_detalle."')";
                    ejecutarConsulta($sql_detalle) or $swv = false;

                    $sqlArticulo="SELECT * FROM articulo WHERE idarticulo='".$putoreq->idarticulo."'";
                    $Articulo= ejecutarConsultaSimpleFila($sqlArticulo);

                    $sqlArticuloStock="UPDATE articuloxsucursal SET stocksucursal = stocksucursal - ".$putoreq->cantidad." WHERE idarticulo =".$putoreq->idarticulo."  and idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlArticuloStock);

                    //valida el descuento de la materia prima
                    $sqlVerificacionExistencia="SELECT 
                                p.idarticulo,
                                (SELECT articulo.nombre FROM articulo WHERE articulo.idarticulo=p.idarticulo) AS nombre_mene,
                                p.cantidad_producto,
                                dp.iddetalle_produccion,
                                dp.idproduccion,
                                dp.idarticulo_costo,
                                (SELECT a.nombre FROM articulo a WHERE a.idarticulo=dp.idarticulo_costo) AS articulo_materiaprima,
                                dp.cantidad,
                                dp.idarticulo
                                 FROM produccion p 
                                 INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                 INNER JOIN articuloxsucursal al ON al.idarticulo=p.idarticulo
                                 WHERE p.idarticulo=".$putoreq->idarticulo." ";
                        //echo $sqlVerificacionExistencia;
                    $EXIS=ejecutarConsulta($sqlVerificacionExistencia);

                        $numexis=0; 
 
                    while($reeeq=$EXIS->fetch_object())
                        {
                            $updateArticuloDetalle="UPDATE articuloxsucursal SET stocksucursal=stocksucursal-('".$putoreq->cantidad."'*'".$reeeq->cantidad."') 
                            WHERE idarticulo='".$reeeq->idarticulo_costo."'  ";
                            ejecutarConsulta($updateArticuloDetalle);
                            $numexis++;
                        }
                        if($numexis==0){
                        }
                    //fin de validacion del descuento de la materia prima


                    if($num_elementosV==0){
                        $JsonDetalleFacturaIntegracion.='{
                            "numeroLinea": "'.($num_elementosV+1).'",
                            "codigoArticulo": "'.$Articulo["codigo"].'",
                            "nombreArticulo": "'.$Articulo["nombre"].' '.$putoreq->descripcion_detalle.'",
                            "cantidadArticulo": "'.$putoreq->cantidad.'",
                            "valorUnitario": "'.$putoreq->precio_venta.'",
                            "unidadMedida": "Unidad",
                            "valorDescuento": "0",
                            "tipoItem": "B",
                            "impuestoAdicional": "0",
                            "adicionalGrabable": "0",
                            "impuestoMontoAdicional": "0"
                        }';
                    }else{
                        $JsonDetalleFacturaIntegracion.=',{
                            "numeroLinea": "'.($num_elementosV+1).'",
                            "codigoArticulo": "'.$Articulo["codigo"].'",
                            "nombreArticulo": "'.$Articulo["nombre"].'",
                            "cantidadArticulo": "'.$putoreq->cantidad.'",
                            "valorUnitario": "'.$putoreq->precio_venta.'",
                            "unidadMedida": "Unidad",
                            "valorDescuento": "0",
                            "tipoItem": "B",
                            "impuestoAdicional": "0",
                            "adicionalGrabable": "0",
                            "impuestoMontoAdicional": "0"                           
                        }';
                    }

                    $num_elementosV=$num_elementosV + 1; 
                }
                   $JsonIntegracionEcoFactura=str_replace("{DetalleFactura}", $JsonDetalleFacturaIntegracion, $JsonIntegracionEcoFactura);    

                    $sqlDetalledelete="DELETE FROM detalle_add_orden  WHERE id_add_orden='$id_add_orden'";
                    ejecutarConsulta($sqlDetalledelete);

                    $sqlcompletada="UPDATE  add_orden set completed=1
                        where id_add_orden='$id_add_orden' ";
                     ejecutarConsulta($sqlcompletada);                   

            }else
            {
               $num_elementosV=0; 
                $swv=true;
                $JsonDetalleFacturaIntegracion="";
                $consulta_mierda="select * from detalle_add_orden where id_add_orden=".$id_add_orden." and estado='0' ";
                $puto=ejecutarConsulta($consulta_mierda);

                while ($putoreq=$puto->fetch_object())
                {
                    $sql_detalle = "INSERT INTO detalle_venta(idventa, idarticulo,cantidad,precio_venta,descripcion_detalle) VALUES ('$idventanew', '".$putoreq->idarticulo."','".$putoreq->cantidad."','".$putoreq->precio_venta."','".$putoreq->descripcion_detalle."')";
                    ejecutarConsulta($sql_detalle) or $swv = false;

                    $sqlArticulo="SELECT * FROM articulo WHERE idarticulo='".$putoreq->idarticulo."'";
                    $Articulo= ejecutarConsultaSimpleFila($sqlArticulo);

                    $sqlArticuloStock="UPDATE articuloxsucursal SET stocksucursal = stocksucursal - ".$putoreq->cantidad." WHERE idarticulo =".$putoreq->idarticulo."  and idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlArticuloStock);

                    //valida el descuento de la materia prima
                    $sqlVerificacionExistencia="SELECT 
                                p.idarticulo,
                                (SELECT articulo.nombre FROM articulo WHERE articulo.idarticulo=p.idarticulo) AS nombre_mene,
                                p.cantidad_producto,
                                dp.iddetalle_produccion,
                                dp.idproduccion,
                                dp.idarticulo_costo,
                                (SELECT a.nombre FROM articulo a WHERE a.idarticulo=dp.idarticulo_costo) AS articulo_materiaprima,
                                dp.cantidad,
                                dp.idarticulo
                                 FROM produccion p 
                                 INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                 INNER JOIN articuloxsucursal al ON al.idarticulo=p.idarticulo
                                 WHERE p.idarticulo=".$putoreq->idarticulo." ";
                        //echo $sqlVerificacionExistencia;
                        $EXIS=ejecutarConsulta($sqlVerificacionExistencia);

                        $numexis=0; 
 
                    while($reeeq=$EXIS->fetch_object())
                        {
                            $updateArticuloDetalle="UPDATE articuloxsucursal SET stocksucursal=stocksucursal-('".$putoreq->cantidad."'*'".$reeeq->cantidad."') 
                            WHERE idarticulo='".$reeeq->idarticulo_costo."'  ";
                            ejecutarConsulta($updateArticuloDetalle);
                            $numexis++;
                        }
                        if($numexis==0){
                        }
                    //fin de validacion del descuento de la materia prima



                    $num_elementosV=$num_elementosV + 1; 
                }

                    $sqlDetalledelete="DELETE FROM detalle_add_orden  WHERE id_add_orden='$id_add_orden'";
                    ejecutarConsulta($sqlDetalledelete);

                    $sqlcompletada="UPDATE  add_orden set completed=1
                        where id_add_orden='$id_add_orden' ";
                     ejecutarConsulta($sqlcompletada);                   
            }


   /*     if($tipo_comprobante=="Factura"){
            //URLS de Desarrollo
            $url = 'http://daocastro-001-site8.itempurl.com/api/EcoFactura/generarDocumento'; //url de pruebas
           //$url='http://facturacionelecprod.daocastro.com/api/EcoFactura/generarDocumento';//url de produccion
            $resultado=$this->callAPI("POST", $url, $JsonIntegracionEcoFactura);
            $ArrayResultado=json_decode($resultado, true);



          //  print_r($ArrayResultado);

            try {
                $sqlUpdate="UPDATE venta SET autorizacionEcoFactura='".$ArrayResultado["dte"]["numeroAutorizacion"]."',serie_ecoFactura='".$ArrayResultado["dte"]["serie"]."',numero_ecoFactura='".$ArrayResultado["dte"]["numero"]."',fechaCertificacion_ecoFactura='".$ArrayResultado["dte"]["fechaCertificacion"]."' WHERE idventa='$idventanew'";
                ejecutarConsulta($sqlUpdate); 



            } catch (\Throwable $th) {
            }
               $sqlUpdatecliente="UPDATE persona SET nombre='".$ArrayResultado["dte"]["nombreCliente"]."' WHERE idpersona='$idcliente'";
                ejecutarConsulta($sqlUpdatecliente);          
        }  */   

            return $idventanew;                                                                       
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
 

    public function obtenerOrden($id_add_orden)
    {
        $sql="SELECT 
                ao.id_add_orden,
                ao.idmesa,
                ao.no_personas,
                ao.idcliente,
                ao.mesero,
                date(ao.fecha_hora) as fechahora,
                ao.total_venta,
                ao.total,
                ao.descuento_orden,
                ao.propina_sugerida,
                ao.total_final,
                ao.idusuario,
                ao.estado
                 FROM add_orden ao
                   WHERE ao.id_add_orden='$id_add_orden'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function obtenerdetalleorden($id_add_orden){

        $sqldetalle="SELECT 
                dao.iddetalle_add_orden,
                dao.id_add_orden,
                dao.idarticulo,
                a.nombre,
                dao.precio_venta,                
                dao.cantidad,
                dao.descripcion_detalle

                 FROM detalle_add_orden dao
                 INNER JOIN articulo a ON dao.idarticulo=a.idarticulo
                  where dao.id_add_orden=".$id_add_orden." and estado=0";

        #echo $sql;
        $rspta=ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg=$rspta->fetch_object()){
            $rows[] = $reg;
        }
        return $rows;
    }

    public function CobrardetalleordenSeparada($id_add_orden,$idventanew){

                $consultaactualizar="UPDATE detalle_add_orden set estado=1 where iddetalle_add_orden=".$id_add_orden." ";
                ejecutarConsulta($consultaactualizar);


                $consultaparavalidar="SELECT * from detalle_add_orden where iddetalle_add_orden=".$id_add_orden." and estado=1";
                $validar=ejecutarConsulta($consultaparavalidar);


                while ($putoreq=$validar->fetch_object())
                {


                    $sql_detalle = "INSERT INTO detalle_venta_ordenseparada(idventa_ordenseparada, idarticulo,cantidad,precio_venta,descripcion_detalle) VALUES ('$idventanew', '".$putoreq->idarticulo."','".$putoreq->cantidad."','".$putoreq->precio_venta."','".$putoreq->descripcion_detalle."')";
                    ejecutarConsulta($sql_detalle) or $swv = false;
                   $sqlArticulo="SELECT * FROM articulo WHERE idarticulo='".$putoreq->idarticulo."'";
                    $Articulo= ejecutarConsultaSimpleFila($sqlArticulo);

                    $sqlArticuloStock="UPDATE articuloxsucursal SET stocksucursal = stocksucursal - ".$putoreq->cantidad." WHERE idarticulo =".$putoreq->idarticulo."  and idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlArticuloStock);

                    //valida el descuento de la materia prima
                    $sqlVerificacionExistencia="SELECT 
                                p.idarticulo,
                                (SELECT articulo.nombre FROM articulo WHERE articulo.idarticulo=p.idarticulo) AS nombre_mene,
                                p.cantidad_producto,
                                dp.iddetalle_produccion,
                                dp.idproduccion,
                                dp.idarticulo_costo,
                                (SELECT a.nombre FROM articulo a WHERE a.idarticulo=dp.idarticulo_costo) AS articulo_materiaprima,
                                dp.cantidad,
                                dp.idarticulo
                                 FROM produccion p 
                                 INNER JOIN detalle_produccion dp ON p.idproduccion=dp.idproduccion
                                 INNER JOIN articuloxsucursal al ON al.idarticulo=p.idarticulo
                                 WHERE p.idarticulo=".$putoreq->idarticulo." ";
                        //echo $sqlVerificacionExistencia;
                        $EXIS=ejecutarConsulta($sqlVerificacionExistencia);

                        $numexis=0; 
 
                    while($reeeq=$EXIS->fetch_object())
                        {
                            $updateArticuloDetalle="UPDATE articuloxsucursal SET stocksucursal=stocksucursal-('".$putoreq->cantidad."'*'".$reeeq->cantidad."') 
                            WHERE idarticulo='".$reeeq->idarticulo_costo."'  ";
                            ejecutarConsulta($updateArticuloDetalle);
                            $numexis++;
                        }
                        if($numexis==0){
                        }
                    //fin de validacion del descuento de la materia prima
                }

                $sqldetalle="update  detalle_add_orden set estado=2 where iddetalle_add_orden=".$id_add_orden."";
                $rspta=ejecutarConsulta($sqldetalle);


        return $idventanew;
    }

    public function CobrardetalleordenSeparadaGuardar($idcliente,$fecha_hora,$total_final,$descuento_orden,$cefectivo_tarjeta,$cefectivo,$rescambio,$mesero,$forma_pago,$propina_sugerida,$propina_sugeridatotal,$no_autorizacion_tarjeta)
    {
        $grantotal=$total_final+$propina_sugeridatotal;
 
        $sqlVenta="INSERT INTO venta_ordenseparada (idcliente,idusuario,tipo_comprobante,fecha_hora,total_venta,estado,total_ventades,cefectivo_tarjeta,cefectivo,rescambio,nombre_vendedor,forma_pago,saldo_venta,id_add_orden,propina_sugerida,propina_sugeridatotal,observacion_credito)

        VALUES ('$idcliente','".$_SESSION["idusuario"]."','Envio','$fecha_hora','$grantotal','Aceptado','$descuento_orden','$cefectivo_tarjeta','$cefectivo','$rescambio','$mesero','$forma_pago','$total_final','0','$propina_sugerida','$propina_sugeridatotal','$no_autorizacion_tarjeta')";
        $idventanew=ejecutarConsulta_retornarID($sqlVenta);             

        return $idventanew;
    }

 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idmesa)
    {
        $sql="SELECT * FROM mesa WHERE idmesa='$idmesa'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM mesa WHERE condicion=1";
        return ejecutarConsulta($sql);      
    }

    public function listar1()
    {
        $sql="SELECT 
            ao.idmesa,
            ao.id_add_orden,
            ao.total,
            mer.condicion,
            mer.nombre,
            ao.idusuario
             FROM add_orden ao
             INNER JOIN mesa mer ON mer.idmesa=ao.idmesa
             WHERE mer.condicion=2 and ao.estado='ORDEN PENDIENTE'
             and ao.idusuario='".$_SESSION["idusuario"]."' 
             "; 
        return ejecutarConsulta($sql);      
    } 

    public function listar11()
    {
        $sql="SELECT 
            ao.idmesa,
            ao.id_add_orden,
            ao.total,
            mer.condicion,
            mer.nombre,
            ao.idusuario
             FROM add_orden ao
             INNER JOIN mesa mer ON mer.idmesa=ao.idmesa
             WHERE mer.condicion=2 and ao.estado='ORDEN PENDIENTE' 
             "; 
        return ejecutarConsulta($sql);      
    }        
    //Implementar un método para listar los registros y mostrar en el select

    public function select()
    {
        $sql="SELECT * FROM categoria where condicion=1";
        return ejecutarConsulta($sql);      
    }

    public function cabecera_Add_Orden($id_add_orden)
    {
        $sql="SELECT 
                ao.id_add_orden,
                ao.idmesa,
                ao.no_personas,
                ao.idcliente,
                p.nombre as cliente,
                p.direccion,
                p.telefono,
                ao.mesero,
                DATE(ao.fecha_hora) as fecha,
                ao.total_venta,
                ao.total,
                ao.descuento_orden,
                ao.propina_sugerida,
                ao.total_final,
                ao.idusuario,
                ao.estado,
                ao.add_fecha_hora,
                ao.forma_pago,
                s.idsucursal,
                s.nombre as sucursal_nombre, 
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion,
                u.nombre as usuario,
                ao.propina_sugeridatotal,
                m.nombre AS numeromesa
             FROM add_orden ao
             inner join usuario u on u.idusuario=ao.idusuario
            INNER JOIN sucursal s ON s.idsucursal=u.idsucursal 
            INNER JOIN persona p on p.idpersona=ao.idcliente
            INNER JOIN mesa m ON m.idmesa=ao.idmesa
            where ao.id_add_orden='$id_add_orden'";
        return ejecutarConsulta($sql);      
    }

    public function detalle_Add_Orden($id_add_orden)
    {
        $sql="SELECT 
            dao.iddetalle_add_orden,
            dao.id_add_orden,
            dao.idarticulo,
            a.nombre AS nombre_articulo,
            dao.cantidad,
            dao.descripcion_detalle,
            dao.precio_venta,
            ROUND((dao.cantidad*dao.precio_venta),2) as subtotal
             FROM detalle_add_orden dao
             INNER JOIN articulo a ON a.idarticulo=dao.idarticulo
            where dao.id_add_orden='$id_add_orden' and dao.estado<>'2' ";
        return ejecutarConsulta($sql);      
    } 

         public function detalle_Add_OrdenCocina($id_add_orden)
    {
        $sql="SELECT 
            dao.iddetalle_add_orden,
            dao.id_add_orden, 
            dao.idarticulo,
            a.nombre AS nombre_articulo,
            dao.cantidad,
            dao.descripcion_detalle,
            dao.precio_venta,
            ROUND((dao.cantidad*dao.precio_venta),2) as subtotal
             FROM detalle_add_orden dao
             INNER JOIN articulo a ON a.idarticulo=dao.idarticulo
            where dao.id_add_orden='$id_add_orden' and dao.estado<>'2' and dao.completed=0 and a.area_producto='COCINA' ";
        return ejecutarConsulta($sql);      
    } 

         public function detalle_Add_OrdenBar($id_add_orden)
    {
        $sql="SELECT 
            dao.iddetalle_add_orden,
            dao.id_add_orden,
            dao.idarticulo,
            a.nombre AS nombre_articulo,
            dao.cantidad,
            dao.descripcion_detalle,
            dao.precio_venta,
            ROUND((dao.cantidad*dao.precio_venta),2) as subtotal
             FROM detalle_add_orden dao
             INNER JOIN articulo a ON a.idarticulo=dao.idarticulo
            where dao.id_add_orden='$id_add_orden' and dao.estado<>'2' and dao.completed=0 and a.area_producto='BAR' ";
        return ejecutarConsulta($sql);      
    } 

         public function detalle_Add_OrdenParrilla($id_add_orden)
    {
        $sql="SELECT 
            dao.iddetalle_add_orden,
            dao.id_add_orden,
            dao.idarticulo,
            a.nombre AS nombre_articulo,
            dao.cantidad,
            dao.descripcion_detalle,
            dao.precio_venta,
            ROUND((dao.cantidad*dao.precio_venta),2) as subtotal
             FROM detalle_add_orden dao
             INNER JOIN articulo a ON a.idarticulo=dao.idarticulo
            where dao.id_add_orden='$id_add_orden' and dao.estado<>'2' and dao.completed=0 and a.area_producto='PARRILLA' ";
        return ejecutarConsulta($sql);      
    } 

         public function detalle_Add_OrdenPergola($id_add_orden)
    {
        $sql="SELECT 
            dao.iddetalle_add_orden,
            dao.id_add_orden,
            dao.idarticulo,
            a.nombre AS nombre_articulo,
            dao.cantidad,
            dao.descripcion_detalle,
            dao.precio_venta,
            ROUND((dao.cantidad*dao.precio_venta),2) as subtotal
             FROM detalle_add_orden dao
             INNER JOIN articulo a ON a.idarticulo=dao.idarticulo
            where dao.id_add_orden='$id_add_orden' and dao.estado<>'2' and dao.completed=0 and a.area_producto='PERGOLA' ";
        return ejecutarConsulta($sql);      
    }             




    public function detalle_Add_Orden_cocina_bar($id_add_orden)
    {
        $sql="SELECT 
            dao.iddetalle_add_orden,
            dao.id_add_orden,
            dao.idarticulo,
            a.nombre AS nombre_articulo,
            dao.cantidad,
            dao.descripcion_detalle,
            dao.precio_venta,
            dao.comentarios,
            ROUND((dao.cantidad*dao.precio_venta),2) as subtotal
             FROM detalle_add_orden dao
             INNER JOIN articulo a ON a.idarticulo=dao.idarticulo
            where dao.id_add_orden='$id_add_orden'  ";
        return ejecutarConsulta($sql);      
    }
    public function detalle_Add_Orden_cocina_parrilla($id_add_orden)
    {
        $sql="SELECT 
            dao.iddetalle_add_orden,
            dao.id_add_orden,
            dao.idarticulo,
            a.nombre AS nombre_articulo,
            dao.cantidad,
            dao.descripcion_detalle,
            dao.precio_venta,
            ROUND((dao.cantidad*dao.precio_venta),2) as subtotal
             FROM detalle_add_orden dao
             INNER JOIN articulo a ON a.idarticulo=dao.idarticulo
            where dao.id_add_orden='$id_add_orden' and dao.completed=0 and a.area_producto='PARRILLA' ";
        return ejecutarConsulta($sql);      
    } 
    public function detalle_Add_Orden_cocina_pergola($id_add_orden)
    {
        $sql="SELECT 
            dao.iddetalle_add_orden,
            dao.id_add_orden,
            dao.idarticulo,
            a.nombre AS nombre_articulo,
            dao.cantidad,
            dao.descripcion_detalle,
            dao.precio_venta,
            ROUND((dao.cantidad*dao.precio_venta),2) as subtotal
             FROM detalle_add_orden dao
             INNER JOIN articulo a ON a.idarticulo=dao.idarticulo
            where dao.id_add_orden='$id_add_orden' and dao.completed=0 and dao.completed=0 and a.area_producto='PERGOLA' ";
        return ejecutarConsulta($sql);      
    }           

    public function completed_detalle($id_add_orden)
    {
        $sql="update  detalle_add_orden set completed=1
            where iddetalle_add_orden='$id_add_orden' ";
        return ejecutarConsulta($sql);      
    }

    public function completed_orden($id_add_orden)
    {
        $sql="update  add_orden set completed=1
            where id_add_orden='$id_add_orden' ";
        return ejecutarConsulta($sql);      
    }

    public function get_Ordenes()
    {
        $sql="SELECT 
                ao.add_fecha_hora AS add_fecha_hora,
            p.nombre,
            p.direccion,
            p.telefono,
            ao.id_add_orden,
            m.nombre as mesa
             FROM add_orden ao 
             INNER JOIN persona p ON p.idpersona=ao.idcliente and ao.estado='PENDIENTE'
             INNER JOIN mesa m on ao.idmesa=m.idmesa
             ";
        return ejecutarConsulta($sql);      
    } 

    public function eliminarOrden($id_add_orden,$idmesa,$valormotivoorden)
    {

     /*   $sqlBitacoraVenta="SELECT * FROM add_orden WHERE id_add_orden=".$id_add_orden."";
        $bitacoraVenta= ejecutarConsultaSimpleFila($sqlBitacoraVenta);
        $residcliente=$bitacoraVenta["idcliente"];
        $residusuario=$bitacoraVenta["idusuario"];
        $resfecha_hora=$bitacoraVenta["fecha_hora"];
        $restotal_final=$bitacoraVenta["total_final"];
        $respropina_sugeridatotal=$bitacoraVenta["propina_sugeridatotal"];
        $resdescuento_orden=$bitacoraVenta["descuento_orden"];
        $resmesero=$bitacoraVenta["mesero"];

       $resgrantotal=$restotal_final+$respropina_sugeridatotal;
 
        $sqlVenta="INSERT INTO bitacora_venta (idcliente,idusuario,tipo_comprobante,fecha_hora,total_venta,estado,total_ventades,nombre_vendedor)

        VALUES ('$residcliente','$residusuario','Envio','$resfecha_hora','$resgrantotal','Aceptado','$resdescuento_orden','$resmesero')";
        $idventanew=ejecutarConsulta_retornarID($sqlVenta); */         


        $sql="UPDATE add_orden SET estado='ANULADO',valormotivoorden='$valormotivoorden' WHERE id_add_orden=".$id_add_orden."";
        ejecutarConsulta($sql);

        $sqlDetalle="DELETE from detalle_add_orden where id_add_orden=".$id_add_orden."";
        ejecutarConsulta($sqlDetalle);   

        $sqlupdatemesa="UPDATE mesa SET condicion='1' WHERE idmesa='$idmesa'";    
        ejecutarConsulta($sqlupdatemesa);       

        return $sql; 
    }   



   public function eliminarOrdendetallebitacora($iddetalle_add_orden,$valormotivo)
    {

  

        $sqldetalleaddorden="SELECT 
                        d.id_add_orden,
                        d.idarticulo,
                        d.cantidad,
                        d.descripcion_detalle,
                        d.precio_venta,
                        d.estado,
                        d.completed
                     FROM detalle_add_orden d WHERE iddetalle_add_orden='$iddetalle_add_orden' ";
        $detalleaddorden= ejecutarConsultaSimpleFila($sqldetalleaddorden);
        $id_add_orden=$detalleaddorden["id_add_orden"];  
        $idarticulo=$detalleaddorden["idarticulo"];  
        $cantidad=$detalleaddorden["cantidad"];  
        $descripcion_detalle=$detalleaddorden["descripcion_detalle"]; 

        $sqldetalleaddordencabezara="SELECT 
                                a.idmesa,
                                a.no_personas,
                                a.idcliente,
                                a.mesero,
                                a.fecha_hora,
                                a.total_venta,
                                a.total,
                                a.descuento_orden,
                                a.propina_sugerida,
                                a.total_final,
                                a.idusuario,
                                a.estado,
                                a.add_fecha_hora
                     FROM add_orden a WHERE id_add_orden='$id_add_orden' ";
        $detalleaddordencabeza= ejecutarConsultaSimpleFila($sqldetalleaddordencabezara);
        $idmesa=$detalleaddordencabeza["idmesa"]; 
        $no_personas=$detalleaddordencabeza["no_personas"]; 
        $idcliente=$detalleaddordencabeza["idcliente"]; 
        $mesero=$detalleaddordencabeza["mesero"]; 
        $fecha_hora_orden=$detalleaddordencabeza["fecha_hora"]; 
        $total_venta=$detalleaddordencabeza["total_venta"];
        $total=$detalleaddordencabeza["total"];
        $descuento_orden=$detalleaddordencabeza["descuento_orden"];
        $propina_sugerida=$detalleaddordencabeza["propina_sugerida"];
        $idusuario_orden=$detalleaddordencabeza["idusuario"];
        $estado_orden=$detalleaddordencabeza["estado"];             
        $add_fecha_hora_orden=$detalleaddordencabeza["add_fecha_hora"];             
        

           $sql="INSERT INTO detalle_add_ordenanulacion (id_add_orden,idarticulo,cantidad,descripcion_detalle,iddetalle_add_orden,valormotivo,estado,idusuario,idsucursal,idmesa,no_personas,idcliente,mesero,fecha_hora_orden,total_venta,total,descuento_orden,propina_sugerida,idusuario_orden,estado_orden,add_fecha_hora_orden)

            VALUES ('$id_add_orden','$idarticulo','$cantidad','$descripcion_detalle','$iddetalle_add_orden','$valormotivo','ANULADO','".$_SESSION["idusuario"]."','".$_SESSION["idsucursal"]."','$idmesa','$no_personas','$idcliente','$mesero','$fecha_hora_orden','$total_venta','$total','$descuento_orden','$propina_sugerida','$idusuario_orden','$estado_orden','$add_fecha_hora_orden')";
              ejecutarConsulta($sql); 


                         $sqldelete="DELETE FROM detalle_add_orden WHERE iddetalle_add_orden='$iddetalle_add_orden'";
       return  ejecutarConsulta($sqldelete);
    }   



         
}
 
?>