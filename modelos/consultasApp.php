<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

/*
Datos de acceso a portal de guatefacturas produccion
https://dte.guatefacturas.com/app/gf/f?p=FEL

Usuario: AD_120503999
Clave: AD_120503999
Clave nueva 01: SOL_120503999
*/


 

Class Venta  
{ 
    //Implementamos nuestro constructor 
    public function __construct()
    {  

    }           



            public function listarDasboardVentasMensajeroApp($idusuario)
            {
                $sql="SELECT 
                    v.estadoventamensajero AS estado,
                    COUNT(*) AS total
                    FROM venta v
                    INNER JOIN mensajero m ON v.idmensajero = m.idmensajero
                    INNER JOIN usuario u ON m.idusuario = u.idusuario
                    WHERE v.estado = 'Aceptado'
                        AND v.tipo_entrega = 'Mensajero'
                        AND u.idusuario ='$idusuario'
                        GROUP BY v.estadoventamensajero ";
                return ejecutarConsulta($sql);
            } 


            public function listarVentasMensajeroApp($idusuario)
            {
                $sql="SELECT 
                v.idventa,
                DATE(v.fecha_hora) as fecha,
                p.nombre as cliente,
                p.direccion AS direccion,
                p.telefono AS telefono,
                u.nombre as usuario,
                v.num_comprobante,
                v.total_venta,
                v.total_ventades,
                v.ctransferencia,
                v.estadoventamensajero
                FROM venta v 
                INNER JOIN mensajero m ON v.idmensajero = m.idmensajero
                INNER JOIN persona p ON v.idcliente=p.idpersona 
                INNER JOIN usuario u ON m.idusuario=u.idusuario
                where   v.estado='Aceptado'
                        and v.tipo_entrega='Mensajero' AND  v.estadoventamensajero IN ('PENDIENTE', 'ENTREGADO', 'CANCELADO')
                        AND u.idusuario='$idusuario'
                order by  v.idventa DESC ";
                return ejecutarConsulta($sql);
            }      
            
            public function GuadarEntregaMensajeroApp($estadoventamensajero,$idventa,$comentarioVentaEntregaMensajero,$ubicacionmaps,$firma)
            {
                $sql="UPDATE venta v SET estadoventamensajero='$estadoventamensajero',comentarioVentaEntregaMensajero='$comentarioVentaEntregaMensajero'
                    WHERE idventa='$idventa'";
                 ejecutarConsulta($sql);

                    $sqlVenta="SELECT * FROM venta WHERE idventa='$idventa'";
                    $Venta= ejecutarConsultaSimpleFila($sqlVenta);

                    $sql="UPDATE persona p SET ubicacion_maps='$ubicacionmaps',firma='$firma'
                    WHERE idpersona='$Venta[idcliente]'";
                 ejecutarConsulta($sql);

            }             
                     
        }
        ?>