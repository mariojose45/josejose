<?php 
if (strlen(session_id()) < 1) 
session_start();
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class NominaPagos14Aguinaldo
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($fecha_inicio,$fecha_fin,$tipo_operacion,$descripcion,$detalles_json)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 

        $sql="INSERT INTO nomina_pagos_14_aguinaldo (fecha_inicio,fecha_fin,descripcion,idusuario,fecha_creacion,tipo_operacion)
        VALUES ('$fecha_inicio','$fecha_fin','$descripcion','".$_SESSION["idusuario"]."','$fechaHora','$tipo_operacion')";
         $idnomina_pagos_14_aguinaldonew = ejecutarConsulta_retornarID($sql);

         $sw = true;
         foreach ($detalles_json as $i => $detalle) {
             $sql_detalle = "INSERT INTO detalle_nomina_pagos_14_aguinaldo (idnomina_pagos_14_aguinaldo, 
                                                                idempleado, 
                                                                dias_trabajados, 
                                                                salario_base, 
                                                                fecha_del,
                                                                fecha_al,
                                                                total_devengado,
                                                                anticipos_bono_14,
                                                                liquido_recibir )
                             VALUES ('$idnomina_pagos_14_aguinaldonew',
                                     '{$detalle['idempleado']}',
                                     '{$detalle['dias_trabajados']}',
                                     '{$detalle['salario_base']}',
                                     '{$detalle['fecha_del']}',
                                     '{$detalle['fecha_al']}',
                                     '{$detalle['total_devengado']}',
                                     '{$detalle['anticipos_bono_14']}',
                                     '{$detalle['liquido_recibir']}')";
             if (!ejecutarConsulta($sql_detalle)) {
                 $sw = false;
             }
         }
 
         return $sw ? "Nomina pagos registrada correctamente" : "Error al registrar la nomina pagos";          
    }
 
    //Implementamos un método para editar registros
    public function editar($idnomina_pagos_14_aguinaldo,$fecha_inicio,$fecha_fin,$tipo_operacion,$descripcion,$detalles_json)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');         
        $sql="UPDATE nomina_pagos_14_aguinaldo SET 
                        fecha_inicio='$fecha_inicio',
                        fecha_fin='$fecha_fin',
                        descripcion='$descripcion' ,
                        idusuario_modificacion='".$_SESSION["idusuario"]."',
                        fecha_modificacion='$fechaHora',
                        tipo_operacion='$tipo_operacion'
                        WHERE idnomina_pagos_14_aguinaldo='$idnomina_pagos_14_aguinaldo'";
         ejecutarConsulta($sql);

         //eliminar los detalles de la nomina pagos
         $sql_detalle = "DELETE FROM detalle_nomina_pagos_14_aguinaldo WHERE idnomina_pagos_14_aguinaldo='$idnomina_pagos_14_aguinaldo'";
         ejecutarConsulta($sql_detalle);
         $sw = true;
         foreach ($detalles_json as $i => $detalle) {
             $sql_detalle = "INSERT INTO detalle_nomina_pagos_14_aguinaldo (idnomina_pagos_14_aguinaldo, 
                                                                idempleado, 
                                                                dias_trabajados, 
                                                                salario_base, 
                                                                fecha_del,
                                                                fecha_al,
                                                                total_devengado,
                                                                anticipos_bono_14,
                                                                liquido_recibir )
                             VALUES ('$idnomina_pagos_14_aguinaldo',
                                     '{$detalle['idempleado']}',
                                     '{$detalle['dias_trabajados']}',
                                     '{$detalle['salario_base']}',
                                     '{$detalle['fecha_del']}',
                                     '{$detalle['fecha_al']}',
                                     '{$detalle['total_devengado']}',
                                     '{$detalle['anticipos_bono_14']}',
                                     '{$detalle['liquido_recibir']}')";
             if (!ejecutarConsulta($sql_detalle)) {
                 $sw = false;
             }
         }
 
         return $sw ? "Nomina pagos registrada correctamente" : "Error al registrar la nomina pagos";     
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idnomina_pagos_14_aguinaldo)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');         
        $sql="UPDATE nomina_pagos_14_aguinaldo SET 
                            condicion='0' ,
                            idusuario_delete='".$_SESSION["idusuario"]."',
                            fecha_delete='$fechaHora'
                            WHERE idnomina_pagos_14_aguinaldo='$idnomina_pagos_14_aguinaldo'";
        return ejecutarConsulta($sql);
    }
 

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idnomina_pagos_14_aguinaldo)
    {
        $sql="SELECT 
            n.idnomina_pagos_14_aguinaldo,
            date(n.fecha_inicio) AS fechainicio,
            date(n.fecha_fin) AS fechafin,
            n.descripcion,
            n.condicion,
            n.idusuario,
            n.fecha_creacion,
            n.idusuario_modificacion,
            n.fecha_modificacion,
            n.idusuario_delete,
            n.fecha_delete,
            n.fecha_delete,
            n.tipo_operacion
        FROM nomina_pagos_14_aguinaldo n WHERE n.idnomina_pagos_14_aguinaldo='$idnomina_pagos_14_aguinaldo'";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function detallenominapagos($idnomina_pagos_14_aguinaldo)
    {

        $sqldetalle="SELECT 
            d.iddetalle_nomina_pagos_14_aguinaldo,
            d.idnomina_pagos_14_aguinaldo,
            d.idempleado,
            e.nombres AS nombre_empleado,
            e.cui AS cui_empleado,
            e.puesto as puesto_empleado,
            d.dias_trabajados,
            d.salario_base,
            d.total_devengado,
            d.liquido_recibir,
            date(d.fecha_del) as fecha_del,
            date(d.fecha_al) as fecha_al,
            d.dias_trabajados,
            d.total_devengado,
            d.anticipos_bono_14,
            d.liquido_recibir
        FROM detalle_nomina_pagos_14_aguinaldo d
        INNER JOIN empleados e ON e.idempleado=d.idempleado
        WHERE d.idnomina_pagos_14_aguinaldo='$idnomina_pagos_14_aguinaldo'";

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
            n.idnomina_pagos_14_aguinaldo,
            date(n.fecha_inicio) AS fechainicio,
            date(n.fecha_fin) AS fechafin,
            n.descripcion,
            n.condicion,
            n.idusuario,
            u.nombre AS nombre_usuario,
            n.fecha_creacion,
            n.idusuario_modificacion,
            (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=n.idusuario_modificacion LIMIT 1) AS usuario_mod,
            n.fecha_modificacion,
            n.idusuario_delete,
            (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=n.idusuario_modificacion LIMIT 1) AS usuario_delete,
            n.fecha_delete,
            n.tipo_operacion
        FROM nomina_pagos_14_aguinaldo n
        INNER JOIN usuario u ON u.idusuario=n.idusuario";
        return ejecutarConsulta($sql);      
    }


    public function listarEmpleados2($fecha_inicio, $fecha_fin)
    {
        $sqlEmpleados="SELECT 
                e.idempleado,
                e.nombres,
                e.cui,
                e.nit,
                e.direccion,
                e.fecha_nacimiento,
                e.hijos,
                e.sexo,
                e.estado_civil,
                e.nacionalidad,
                e.nivel_educativo,
                e.telefono,
                e.edad,
                e.puesto,
                e.fecha_inicio_laboral,
                e.fecha_fin_laboral,

                -- 💰 Campos base con redondeo
                ROUND(e.salario_base, 2) AS salario_base,
                ROUND(e.bonificacion, 2) AS bonificacion,
                ROUND(e.bono_productividad, 2) AS bono_productividad,
                ROUND(e.salario_extra, 2) AS salario_extra,
                ROUND(e.descuento_igss, 2) AS descuento_igss,
                ROUND(e.descuento_isr, 2) AS descuento_isr,
                ROUND(e.descuento_prestamo, 2) AS descuento_prestamo,
                ROUND(e.otros_descuentos, 2) AS otros_descuentos,
                ROUND(e.anticipo_salarial, 2) AS anticipo_salarial,
                e.condicion,
                -- 💰 Bono 14
                ROUND(IFNULL((
                    SELECT SUM(o.abono_prestamo)
                    FROM abono_otrosdecuentos_empleado o 
                    WHERE o.idempleado = e.idempleado
                    AND DATE(o.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin'
                    AND o.tipo_operacion = 'BONO 14'
                    AND o.condicion = 1
                ), 0), 2) AS abono_14,

                -- 🎄 Aguinaldo
                ROUND(IFNULL((
                    SELECT SUM(o.abono_prestamo)
                    FROM abono_otrosdecuentos_empleado o 
                    WHERE o.idempleado = e.idempleado
                    AND DATE(o.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin'
                    AND o.tipo_operacion = 'AGUINALDO'
                    AND o.condicion = 1
                ), 0), 2) AS abono_aguinaldo,
                e.puesto,
                DATEDIFF(
                    CURDATE(),
                    GREATEST(e.fecha_inicio_laboral, CONCAT(YEAR(CURDATE()), '-01-01'))
                ) AS dias_trabajados_en_anio

            FROM empleados e
            WHERE e.condicion = 1";
        $rspta=ejecutarConsulta($sqlEmpleados);
        $rows = array();
        while ($reg=$rspta->fetch_object()){
            $rows[] = $reg;
        }
        return $rows;     
    }


    public function cabeceranominapagos($idnomina_pagos_14_aguinaldo)
    {
        $sql="SELECT 
            n.idnomina_pagos_14_aguinaldo,
            date(n.fecha_inicio) AS fechainicio,
            date(n.fecha_fin) AS fechafin,
            n.descripcion,
            n.condicion,
            n.idusuario,
            u.nombre as nombre_usuario,
            n.fecha_creacion,
            n.idusuario_modificacion,
            n.fecha_modificacion,
            n.idusuario_delete,
            n.fecha_delete,
            n.fecha_delete,
            s.imagen AS sucursal_imagen,
            s.nombre AS sucursal_nombre,
            s.nit AS sucursal_nit,
            s.direccion AS sucursal_direccion,
            s.telefono AS sucursal_telefono,
            s.email AS sucursal_email,
            n.tipo_operacion
        FROM nomina_pagos_14_aguinaldo n 
        INNER JOIN usuario u ON u.idusuario=n.idusuario
        LEFT join sucursal s on s.idsucursal=u.idsucursal
        WHERE n.idnomina_pagos_14_aguinaldo='$idnomina_pagos_14_aguinaldo'";
        return ejecutarConsulta($sql);
    }

    public function detallecabeceranominapagos($idnomina_pagos_14_aguinaldo)
    {
        $sql="SELECT 
                d.iddetalle_nomina_pagos_14_aguinaldo,
                d.idnomina_pagos_14_aguinaldo,
                d.idempleado,
                e.nombres AS nombre_empleado,
                e.cui,
                d.dias_trabajados,
                d.diario,
                d.salario_base,
                d.total_devengado,
                d.anticipos_bono_14,
                d.liquido_recibir,
                e.puesto,
                date(d.fecha_del) AS fecha_del,
                date(d.fecha_al) AS fecha_al
            FROM detalle_nomina_pagos_14_aguinaldo d
            INNER JOIN empleados e ON e.idempleado=d.idempleado
            WHERE d.idnomina_pagos_14_aguinaldo='$idnomina_pagos_14_aguinaldo'";
        return ejecutarConsulta($sql);
    }    

}
 
?>