<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


          require_once "../modelos/Add_orden.php";
         
        $addorden=new AddOrden();           
         
        $id_add_orden=isset($_POST["id_add_orden"])? limpiarCadena($_POST["id_add_orden"]):"";
        $idmesa=isset($_POST["idmesa"])? limpiarCadena($_POST["idmesa"]):"";
        $no_personas=isset($_POST["no_personas"])? limpiarCadena($_POST["no_personas"]):"";
        $idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
        $mesero=isset($_POST["mesero"])? limpiarCadena($_POST["mesero"]):"";
        $fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";        
        $total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
        $total=isset($_POST["total"])? limpiarCadena($_POST["total"]):"";
        $descuento_orden=isset($_POST["descuento_orden"])? limpiarCadena($_POST["descuento_orden"]):"";
        $propina_sugerida=isset($_POST["propina_sugerida"])? limpiarCadena($_POST["propina_sugerida"]):"";
        $total_final=isset($_POST["total_final"])? limpiarCadena($_POST["total_final"]):"";
        $forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
        $cefectivo=isset($_POST["cefectivo"])? limpiarCadena($_POST["cefectivo"]):"";        
        $cefectivo_tarjeta=isset($_POST["cefectivo_tarjeta"])? limpiarCadena($_POST["cefectivo_tarjeta"]):"";  
        $no_autorizacion_tarjeta=isset($_POST["no_autorizacion_tarjeta"])? limpiarCadena($_POST["no_autorizacion_tarjeta"]):""; 
        $rescambio=isset($_POST["rescambio"])? limpiarCadena($_POST["rescambio"]):"";                
        $propina_sugeridatotal=isset($_POST["propina_sugeridatotal"])? limpiarCadena($_POST["propina_sugeridatotal"]):"";                
        $idusuario=$_SESSION["idusuario"];        
        $id_detalle_add_orden= isset($_POST["id_detalle_add_orden"])?$_POST["id_detalle_add_orden"]:""; 
        $no_autorizacion_tarjeta2=isset($_POST["no_autorizacion_tarjeta2"])? limpiarCadena($_POST["no_autorizacion_tarjeta2"]):""; 

         
        switch ($_GET["op"]) 
        {    
            case 'get_ordenes':
                $rspta=$addorden->get_Ordenes();
                $dbdata = array();
                //Fetch into associative array
                while ( $row = $rspta->fetch_assoc())  {
                    $dbdata[]=$row;
                } 


                foreach ($dbdata as $key => $value) {
                    $rsptadetalle=$addorden->detalle_Add_Orden_cocina($dbdata[$key]["id_add_orden"]);

                    $dbdatadetalle = array();
                    //Fetch into associative array
                    while ( $row = $rsptadetalle->fetch_assoc())  {
                        $dbdatadetalle[]=$row;
                    }

                    $dbdata[$key]["detalle"]=$dbdatadetalle;
                }


                echo json_encode($dbdata);
            break;  

            case 'get_ordenes_bar':
                $rspta=$addorden->get_Ordenes();
                $dbdata = array();
                //Fetch into associative array
                while ( $row = $rspta->fetch_assoc())  {
                    $dbdata[]=$row;
                } 
 

                foreach ($dbdata as $key => $value) {
                    $rsptadetalle=$addorden->detalle_Add_Orden_cocina_bar($dbdata[$key]["id_add_orden"]);

                    $dbdatadetalle = array();
                    //Fetch into associative array
                    while ( $row = $rsptadetalle->fetch_assoc())  {
                        $dbdatadetalle[]=$row;
                    }

                    $dbdata[$key]["detalle"]=$dbdatadetalle;
                }


                echo json_encode($dbdata);
            break; 

            case 'get_ordenes_parrilla':
                $rspta=$addorden->get_Ordenes();
                $dbdata = array();
                //Fetch into associative array
                while ( $row = $rspta->fetch_assoc())  {
                    $dbdata[]=$row;
                } 


                foreach ($dbdata as $key => $value) { 
                    $rsptadetalle=$addorden->detalle_Add_Orden_cocina_parrilla($dbdata[$key]["id_add_orden"]);

                    $dbdatadetalle = array();
                    //Fetch into associative array
                    while ( $row = $rsptadetalle->fetch_assoc())  {
                        $dbdatadetalle[]=$row;
                    }

                    $dbdata[$key]["detalle"]=$dbdatadetalle;
                }
 

                echo json_encode($dbdata);
            break;  
            
            case 'get_ordenes_pergola':
                $rspta=$addorden->get_Ordenes();
                $dbdata = array();
                //Fetch into associative array
                while ( $row = $rspta->fetch_assoc())  {
                    $dbdata[]=$row;
                } 


                foreach ($dbdata as $key => $value) {
                    $rsptadetalle=$addorden->detalle_Add_Orden_cocina_pergola($dbdata[$key]["id_add_orden"]);

                    $dbdatadetalle = array();
                    //Fetch into associative array
                    while ( $row = $rsptadetalle->fetch_assoc())  {
                        $dbdatadetalle[]=$row;
                    }

                    $dbdata[$key]["detalle"]=$dbdatadetalle;
                }


                echo json_encode($dbdata);
            break;                         

 
            case 'complete_detalle': 
                $addorden->completed_detalle($_GET["iddetalle"]); 
            break;  
            case 'complete_orden':
                $addorden->completed_orden($_GET["idorden"]);
            break;                    
        }

  

?>