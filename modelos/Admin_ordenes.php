<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
        date_default_timezone_set('America/Guatemala');        
 
Class AdminOrdenes 
{  
    //Implementamos nuestro constructor
    public function __construct() 
    {  
  
    } 
 
    //Implementamos un método para insertar registros
    public function insertar($idtecnico,$idcliente,$fecha_hora,$imei_cel,$idmarca,$idmodelo,$idtipo_equipo,$idcolor,$enciende,$golpes,$puerto_carga,$password_orden,$falla_equipo,$diagnostico_equipo,$presupuesto,$repuestos,$anticipo,$total_orden,$codigo_ordennueva,$idusuario)

    {
        $sqlcorrelativo="UPDATE add_correlativo SET modu_orden_trabajo=modu_orden_trabajo+1 WHERE idsucursal='".$_SESSION["idsucursal"]."'";
        ejecutarConsulta($sqlcorrelativo);  

        $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'  ";
        $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
        $corre=$correlativo["modu_orden_trabajo"];  

        $sqlOrdenes="INSERT INTO ordenes (idtecnico,idcliente,fecha_hora,imei_cel,idmarca,idmodelo,idtipo_equipo,idcolor,enciende,golpes,puerto_carga,password_orden,falla_equipo,diagnostico_equipo,presupuesto,
        repuestos,anticipo,total_orden,codigo_ordennueva,idusuario,condicion,num_nueva_orden,estado,fecha_entrega,fecha_creacion_ingreso,idsucursal)
        VALUES ('$idtecnico','$idcliente','$fecha_hora','$imei_cel','$idmarca','$idmodelo','$idtipo_equipo','$idcolor','$enciende','$golpes','$puerto_carga','$password_orden','$falla_equipo',
        '$diagnostico_equipo','$presupuesto','$repuestos','$anticipo','$total_orden','$codigo_ordennueva','$idusuario','1','$corre','EN REPARACION','$fecha_hora',NOW(),'".$_SESSION["idsucursal"]."')";
         $idordenesnew=ejecutarConsulta_retornarID($sqlOrdenes); 


            $sqlOrdenes="SELECT o.*,t.nombre as tecnico FROM ordenes o
                        inner join tecnico t on t.idtecnico=o.idtecnico
                                WHERE  idnueva_orden='$idordenesnew' ";
            $res_Ordenes= ejecutarConsultaSimpleFila($sqlOrdenes);
            $idcliente=$res_Ordenes["idcliente"];  
            $tecnico=$res_Ordenes["tecnico"];  
            $falla_equipo=$res_Ordenes["falla_equipo"];  
            $diagnostico_equipo=$res_Ordenes["diagnostico_equipo"];            

    ////insert de venta
            if ($anticipo>0) {
                # code...
                $sqlcorrelativo="UPDATE add_correlativo SET num_envio=num_envio+1 WHERE idsucursal='".$_SESSION["idsucursal"]."'";
                ejecutarConsulta($sqlcorrelativo);  

                $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'  ";
                $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
                $corre=$correlativo["num_envio"];                   

                $sql="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_venta,estado,observacion_credito,
                fecha_hora_siguiente_pago,cefectivo,rescambio,nombre_vendedor,forma_pago,numero_pagos,fecha_hora_pago,fecha_hora_vencimiento_factura,monto_abono,
                num_establecimiento,idcotizacion,total_ventades,tipo_operacion,ccredito,ctransferencia,ctarjeta,idnueva_orden,idsucursal)

                VALUES ('$idcliente','$idusuario','Envio','0','$corre','$fecha_hora','0','$anticipo','Aceptado','0','0','$anticipo','0','$tecnico', 'Efectivo','0','0','0','0','0','0','$anticipo','APERTURA','0','0','0','$idordenesnew','".$_SESSION["idsucursal"]."')";
                $idventanew=ejecutarConsulta_retornarID($sql); 


                $sql_detalle = "INSERT INTO detalle_venta (idventa, idarticulo, cantidad, precio_venta, descuento, descripcion_detalle, precio_compra, presentacion, cantidadpresentacion, totalcantidadpresentacion, presen, q_ref) 
                    VALUES ('$idventanew', '10', '1', '$anticipo', '0', 'Falla: $falla_equipo Diag: $falla_equipo', '0', 'UNIDAD', '0', '0', 'UNIDAD', '$anticipo')";

                ejecutarConsulta($sql_detalle);                
            }



    ///////        

        return $idordenesnew;  



    }
 
    //Implementamos un método para editar registros
    public function editar($idnueva_orden,$idtecnico,$idcliente,$fecha_hora,$imei_cel,$idmarca,$idmodelo,$idtipo_equipo,$idcolor,$enciende,$golpes,$puerto_carga,$password_orden,$falla_equipo,$diagnostico_equipo,$presupuesto,$repuestos,$anticipo,$total_orden,$codigo_ordennueva,$idusuario)
    {
        $sql="UPDATE ordenes SET idtecnico='$idtecnico',idcliente='$idcliente',fecha_update='$fecha_hora',imei_cel='$imei_cel',idmarca='$idmarca',idmodelo='$idmodelo',idtipo_equipo='$idtipo_equipo',idcolor='$idcolor',enciende='$enciende',golpes='$golpes',puerto_carga='$puerto_carga',password_orden='$password_orden',falla_equipo='$falla_equipo',diagnostico_equipo='$diagnostico_equipo',presupuesto='$presupuesto',repuestos='$repuestos',anticipo='$anticipo',total_orden='$total_orden',codigo_ordennueva='$codigo_ordennueva',idusuario_update='$idusuario' WHERE idnueva_orden='$idnueva_orden'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idnueva_orden)
    {
        $sql="UPDATE ordenes SET condicion='0' WHERE idnueva_orden='$idnueva_orden'";
        return ejecutarConsulta($sql);
    }

    public function guardaryeditarCambiarEstado($idnueva_orden_cambiar_estado,$cambiar_estado,$idusuario,$fechaHoraActual,$Entrega_presupuesto,$Entrega_repuestos,$Entrega_anticipo,
        $Entrega_SaldoPendientexpagar,$Entrega_total_ordenl,$forma_pago,$presupuesto)
    {
        /**
         * Hacer la siguiente validacion para PRESUPUESTO
         *  Cuando sea el estado de ENTREGADO, entonces obtener la variable de Entrega_presupuesto
         *      Caso contrario, mantener la variable de presupuesto para no cambiar el Presupuestp
         *      
         */
        if($cambiar_estado === 'ENTREGADO'){
            $sql="UPDATE ordenes SET estado='$cambiar_estado',idusuario_update='$idusuario',fecha_entrega='$fechaHoraActual',presupuesto='$Entrega_presupuesto' WHERE idnueva_orden='$idnueva_orden_cambiar_estado'";
            ejecutarConsulta($sql);  
            //print_r($sql);

        }else{
            $sql2="UPDATE ordenes SET estado='$cambiar_estado',idusuario_update='$idusuario',fecha_entrega='$fechaHoraActual' WHERE idnueva_orden='$idnueva_orden_cambiar_estado'";
            ejecutarConsulta($sql2); 
            //print_r($sql2); 

        }
        
        //$sql="UPDATE ordenes SET estado='$cambiar_estado',idusuario_update='$idusuario',fecha_entrega='$fechaHoraActual',presupuesto='$Entrega_presupuesto' WHERE idnueva_orden='$idnueva_orden_cambiar_estado'";
        //ejecutarConsulta($sql);  

        
            $sqlOrdenes="SELECT o.*,t.nombre as tecnico FROM ordenes o
                        inner join tecnico t on t.idtecnico=o.idtecnico
                                WHERE  idnueva_orden='$idnueva_orden_cambiar_estado' ";
            $res_Ordenes= ejecutarConsultaSimpleFila($sqlOrdenes);
            $idcliente=$res_Ordenes["idcliente"];  
            $tecnico=$res_Ordenes["tecnico"];  
            $falla_equipo=$res_Ordenes["falla_equipo"];  
            $diagnostico_equipo=$res_Ordenes["diagnostico_equipo"];            

    ////insert de venta
            $sqlcorrelativo="UPDATE add_correlativo SET num_envio=num_envio+1 WHERE idsucursal='".$_SESSION["idsucursal"]."'";
            ejecutarConsulta($sqlcorrelativo);  

            $sqlCorre="SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."'  ";
            $correlativo= ejecutarConsultaSimpleFila($sqlCorre);
            $corre=$correlativo["num_envio"];                   

            $sql="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_venta,estado,observacion_credito,
            fecha_hora_siguiente_pago,cefectivo,rescambio,nombre_vendedor,forma_pago,numero_pagos,fecha_hora_pago,fecha_hora_vencimiento_factura,monto_abono,num_establecimiento,idcotizacion,total_ventades,tipo_operacion,ccredito,ctransferencia,ctarjeta,idnueva_orden)

            VALUES ('$idcliente','$idusuario','Envio','0','$corre','$fechaHoraActual','0','$Entrega_SaldoPendientexpagar','Aceptado','0','0','$Entrega_SaldoPendientexpagar','0','$tecnico', '$forma_pago','0','0','0','0','0','0','$Entrega_SaldoPendientexpagar','APERTURA','0','0','0','$idnueva_orden_cambiar_estado')";
            $idventanew=ejecutarConsulta_retornarID($sql); 


            $sql_detalle = "INSERT INTO detalle_venta (idventa, idarticulo, cantidad, precio_venta, descuento, descripcion_detalle, precio_compra, presentacion, cantidadpresentacion, totalcantidadpresentacion, presen, q_ref) 
                VALUES ('$idventanew', '10', '1', '$Entrega_SaldoPendientexpagar', '0', 'Falla: $falla_equipo Diag: $falla_equipo', '0', 'UNIDAD', '0', '0', 'UNIDAD', '$Entrega_SaldoPendientexpagar')";

            ejecutarConsulta($sql_detalle);
        

    ///////        

        return $idventanew;  
    }    
 

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idnueva_orden)
    {
        $sql="SELECT 
        o.idnueva_orden,
        o.idtecnico,
        t.nombre AS nombre_tecnico,
        o.idcliente,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        o.idmarca,
        m.nombre AS nombre_marca,
        o.idmodelo,
        mo.nombre AS nombre_modelo,
        o.idtipo_equipo,
        te.nombre AS nombre_tipoequipo,
        o.idcolor,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes,
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        o.idusuario,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.idusuario_update,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE o.idnueva_orden='$idnueva_orden'";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function mostrarSaldoOrden($idnueva_orden_cambiar_estado)
    {
        $sql="SELECT 
        o.idnueva_orden,
        round(o.presupuesto,2) as presupuesto,
        round(o.repuestos,2) as repuestos,
        round(o.anticipo,2) as anticipo,
        round(o.total_orden,2) as total_orden
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE o.idnueva_orden='$idnueva_orden_cambiar_estado' and o.estado<>'ENTREGADO'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar_cambiarestado($idnueva_orden_cambiar_estado)
    {
        $sql="SELECT 
        o.idnueva_orden,
        o.estado
        FROM ordenes o
        WHERE o.idnueva_orden='$idnueva_orden_cambiar_estado'";
        return ejecutarConsultaSimpleFila($sql);
    }    

    //Implementar un método para mostrar los datos de un registro a modificar
    public function cabeceranuevaorden($idnueva_orden)
    {
        $sql="SELECT 
            o.idnueva_orden,
            o.idtecnico,
            t.nombre AS nombre_tecnico,
            o.idcliente,
            p.nombre AS nombre_cliente,
            p.telefono AS nombre_telefono,
            p.direccion as nombre_direccion,
            p.tipo_documento,
            p.num_documento,
            p.email,
            date(o.fecha_hora) AS fecha_creacion,
            o.imei_cel,
            o.idmarca,
            m.nombre AS nombre_marca,
            o.idmodelo,
            mo.nombre AS nombre_modelo,
            o.idtipo_equipo,
            te.nombre AS nombre_tipoequipo,
            o.idcolor,
            c.nombre AS nombre_color,
            o.enciende,
            o.golpes,
            o.puerto_carga,
            o.password_orden,
            o.falla_equipo,
            o.diagnostico_equipo,
            o.presupuesto,
            o.repuestos,
            o.anticipo,
            o.total_orden,
            o.codigo_ordennueva,
            uu.nombre AS nombre_usuariocreacion,
            o.idusuario,
            (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
            o.idusuario_update,
            o.fecha_update,
            o.fecha_add,
            o.condicion,
            o.estado,
            s.nombre AS nombre_sucursal,
            s.direccion AS direccion_sucursal,
            s.telefono AS tels_sucursal,
            s.nit AS nit_sucursal,
            s.email AS correo_sucursal,
            s.imagen as sucursal_imagen,
            s.color_r,
            s.color_g,
            s.color_b,
            s.color_r_texto,
            s.color_g_texto,
            s.color_b_texto,
            o.num_nueva_orden
            FROM ordenes o
            left JOIN tecnico t ON t.idtecnico=o.idtecnico
            left JOIN persona p ON p.idpersona=o.idcliente
            left JOIN marca m ON m.idmarca=o.idmarca
            left JOIN modelo mo ON mo.idmodelo=o.idmodelo
            left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
            left JOIN color c ON c.idcolor=o.idcolor
            left JOIN usuario uu ON uu.idusuario=o.idusuario
            left JOIN sucursal s ON s.idsucursal=uu.idsucursal
            WHERE o.idnueva_orden='$idnueva_orden'";
        return ejecutarConsulta($sql);
    }     

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        p.telefono AS nombre_telefono,
        o.enciende,
        o.golpes,
        o.puerto_carga, 
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos, 
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,  
        o.estado,
        o.fecha_creacion_ingreso
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor 
        where o.condicion<>'0'
        order by o.idnueva_orden desc";
        return ejecutarConsulta($sql);     
    }

    public function listarxusuario()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        p.telefono AS nombre_telefono,
        o.enciende,
        o.golpes,
        o.puerto_carga, 
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos, 
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,  
        o.estado,
        o.fecha_creacion_ingreso
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        where o.idusuario='".$_SESSION["idusuario"]."' 
        order by o.idnueva_orden desc ";
        return ejecutarConsulta($sql);     
    }    

    public function ordenesenreparacion()
    { 
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='EN REPARACION' ";
        return ejecutarConsulta($sql);
    }  

    public function ordenesenreparacionxusuario()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='EN REPARACION'
             and o.idusuario='".$_SESSION["idusuario"]."'  ";
        return ejecutarConsulta($sql);
    }        

    public function ordenesespera()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='ESPERA' ";
        return ejecutarConsulta($sql);
    }  

    public function ordenesesperaxusuario() 
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='ESPERA' 
             and o.idusuario='".$_SESSION["idusuario"]."'  ";
        return ejecutarConsulta($sql);
    }       

    public function ordenesentregado()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='ENTREGADO' ";
        return ejecutarConsulta($sql);
    } 

    public function ordenesentregadoxusuario()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='ENTREGADO' 
             and o.idusuario='".$_SESSION["idusuario"]."'  ";
        return ejecutarConsulta($sql);
    }      

    public function ordenesgarantia()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='GARANTIA' ";
        return ejecutarConsulta($sql);
    }  

    public function ordenesgarantiaxusuario()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='GARANTIA' 
              and o.idusuario='".$_SESSION["idusuario"]."'  ";
        return ejecutarConsulta($sql);
    }      

    public function ordenessinreparar()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='SIN REPARACION' ";
        return ejecutarConsulta($sql);
    }  

    public function ordenessinrepararxusuario()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='SIN REPARACION' 
              and o.idusuario='".$_SESSION["idusuario"]."'  ";
        return ejecutarConsulta($sql);
    }  

    public function ordenesreparadas()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='REPARADOS' ";
        return ejecutarConsulta($sql);
    }  

    public function ordenesreparadasxusuario()
    {
        $sql="SELECT 
            COUNT(o.idnueva_orden) AS num_ordenes
             FROM ordenes o WHERE o.estado='REPARADOS' 
              and o.idusuario='".$_SESSION["idusuario"]."'  ";
        return ejecutarConsulta($sql);
    }          

    

    public function ordenesxfecha($fecha_inicio,$fecha_fin,$cambiar_estado)
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo, 
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado,
        o.fecha_entrega
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE DATE(o.fecha_entrega)>='$fecha_inicio' AND DATE(o.fecha_entrega)<='$fecha_fin' and o.estado='$cambiar_estado' ";
        return ejecutarConsulta($sql);      
    }  
    public function ordenesxfechaxtecnico($fecha_inicio,$fecha_fin,$idtecnico,$cambiar_estado)
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE DATE(o.fecha_entrega)>='$fecha_inicio' AND DATE(o.fecha_entrega)<='$fecha_fin' and o.idtecnico='$idtecnico' and o.estado='$cambiar_estado' ";
        return ejecutarConsulta($sql);      
    }                          


    public function ordenesxcliente($fecha_inicio,$fecha_fin,$idcliente)
    {
        $sql="SELECT 
        o.idnueva_orden,  
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        COALESCE(mo.nombre, '0') AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes,
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado,
        o.fecha_entrega
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE o.idcliente='$idcliente' and DATE(o.fecha_entrega)>='$fecha_inicio' AND DATE(o.fecha_entrega)<='$fecha_fin' ";
        return ejecutarConsulta($sql);      
    } 


    public function ordenesxcliente_xestado($fecha_inicio,$fecha_fin,$idcliente,$cambiar_estado)
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes,
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado,
        o.fecha_entrega
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE o.idcliente='$idcliente' and DATE(o.fecha_entrega)>='$fecha_inicio' AND DATE(o.fecha_entrega)<='$fecha_fin' and o.estado='$cambiar_estado' ";
        return ejecutarConsulta($sql);      
    } 


////////
    public function ordenesxcliente_reparacion()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='EN REPARACION' ";
        return ejecutarConsulta($sql);      
    }  

    public function ordenesxcliente_espera()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='ESPERA' ";
        return ejecutarConsulta($sql);      
    }

    public function ordenesxcliente_entregado()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='ENTREGADO' ";
        return ejecutarConsulta($sql);      
    }             


    public function ordenesxcliente_garantia()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='GARANTIA' ";
        return ejecutarConsulta($sql);      
    }             


    public function ordenesxcliente_SINREPARAR()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='SIN REPARACION' ";
        return ejecutarConsulta($sql);      
    }       



    public function ordenesxcliente_REPADOSS()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='REPARADOS' ";
        return ejecutarConsulta($sql);      
    }                 
///////



////////
    public function ordenesxcliente_reparacion_user()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='EN REPARACION' and o.idusuario='".$_SESSION["idusuario"]."'  ";
        return ejecutarConsulta($sql);      
    }  

    public function ordenesxcliente_espera_user()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='ESPERA' and o.idusuario='".$_SESSION["idusuario"]."' ";
        return ejecutarConsulta($sql);      
    }

    public function ordenesxcliente_entregado_user()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='ENTREGADO' and o.idusuario='".$_SESSION["idusuario"]."' ";
        return ejecutarConsulta($sql);      
    }             


    public function ordenesxcliente_garantia_user()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='GARANTIA' and o.idusuario='".$_SESSION["idusuario"]."' ";
        return ejecutarConsulta($sql);      
    }             


    public function ordenesxcliente_SINREPARAR_user()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='SIN REPARACION' and o.idusuario='".$_SESSION["idusuario"]."' ";
        return ejecutarConsulta($sql);      
    }       



    public function ordenesxcliente_REPADOSS_user()
    {
        $sql="SELECT 
        o.idnueva_orden, 
        t.nombre AS nombre_tecnico,
        p.nombre AS nombre_cliente,
        date(o.fecha_hora) AS fecha_creacion,
        o.imei_cel,
        m.nombre AS nombre_marca,
        mo.nombre AS nombre_modelo,
        te.nombre AS nombre_tipoequipo,
        c.nombre AS nombre_color,
        o.enciende,
        o.golpes, 
        o.puerto_carga,
        o.password_orden,
        o.falla_equipo,
        o.diagnostico_equipo,
        o.presupuesto,
        o.repuestos,
        o.anticipo,
        o.total_orden,
        o.num_nueva_orden,
        o.codigo_ordennueva,
        (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=o.idusuario LIMIT 1) AS nombre_usuariocreacion,
        (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=o.idusuario_update LIMIT 1) AS nombre_usuarioupdate,
        o.fecha_update,
        o.fecha_add,
        o.condicion,
        o.estado
        FROM ordenes o
        left JOIN tecnico t ON t.idtecnico=o.idtecnico
        left JOIN persona p ON p.idpersona=o.idcliente
        left JOIN marca m ON m.idmarca=o.idmarca
        left JOIN modelo mo ON mo.idmodelo=o.idmodelo
        left JOIN tipo_equipo te ON te.idtipo_equipo=o.idtipo_equipo
        left JOIN color c ON c.idcolor=o.idcolor
        WHERE  o.estado='REPARADOS' and o.idusuario='".$_SESSION["idusuario"]."' ";
        return ejecutarConsulta($sql);      
    }                 
///////



    public function encabezadoordenes()
    {
        $sql="SELECT 
                c.idcotizacion,
                c.idcliente,
                p.tipo_persona,
                p.nombre as cliente,
                p.idpersona as codcliente,
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
                c.observacion_cotizacion,
                c.nombre_vendedor,
                c.cobrado,
                c.fecha_add,
                c.tipo_consi,
                c.correlativo,
                s.color_r, 
                s.color_g,
                s.color_b, 
                s.color_r_texto,
                s.color_g_texto, 
                s.color_b_texto                 
                FROM cotizacion c
                INNER JOIN persona p ON p.idpersona=c.idcliente
                INNER JOIN usuario u ON u.idusuario=c.idusuario
                INNER JOIN sucursal s ON s.idsucursal=u.idsucursal";
        return ejecutarConsulta($sql);      
    }  


    public function mostrarclieente($idpersona)
    {
        $sql="SELECT * FROM persona WHERE idpersona='$idpersona'";
        return ejecutarConsulta($sql);
    }


}
 
?>