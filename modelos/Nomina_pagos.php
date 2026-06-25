<?php
if (strlen(session_id()) < 1)
    session_start();
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class NominaPagos
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($fecha_inicio, $fecha_fin, $descripcion, $detalles_json)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        // 1) INSERTAR NOMINA PRINCIPAL
        $sql = "INSERT INTO nomina_pagos (fecha_inicio,fecha_fin,descripcion,idusuario,fecha_creacion)
        VALUES ('$fecha_inicio','$fecha_fin','$descripcion','" . $_SESSION["idusuario"] . "','$fechaHora')";

        $idnomina_pagosnew = ejecutarConsulta_retornarID($sql);

        /* ===================================================================
           2) RECORRER DETALLES DE EMPLEADOS Y GUARDAR
        =================================================================== */
        $sw = true;


        foreach ($detalles_json as $i => $detalle) {

            // Insertar detalle normal
            $sql_detalle = "INSERT INTO detalle_nomina_pagos (
                idnomina_pagos, 
                idempleado, 
                dias_trabajados, 
                horas_trabajados,
                diario, 
                salario_base,
                total_salario,
                salario_extra, 
                total_devengado, 
                bonificacion_ley, 
                bonificacion_productividad,
                descuento_igss, 
                descuento_isr, 
                abono_prestamo, 
                abono_otrosDescuentos,
                abono_adelantoQuincenal, 
                abono_adelantoSalarial, 
                total_deducciones,
                liquido_recibir
            ) VALUES (
                '$idnomina_pagosnew',
                '{$detalle['idempleado']}',
                '{$detalle['dias_trabajados']}',
                '{$detalle['horas_trabajados']}',
                '{$detalle['diario']}',
                '{$detalle['salario_base']}',
                '{$detalle['total_salario']}',
                '{$detalle['salario_extra']}',
                '{$detalle['total_devengado']}',
                '{$detalle['bonificacion_ley']}',
                '{$detalle['bonificacion_productividad']}',
                '{$detalle['descuento_igss']}',
                '{$detalle['descuento_isr']}',
                '{$detalle['abono_prestamo']}',
                '{$detalle['abono_otrosDescuentos']}',
                '{$detalle['abono_adelantoQuincenal']}',
                '{$detalle['abono_adelantoSalarial']}',
                '{$detalle['total_deducciones']}',
                '{$detalle['liquido_recibir']}'
            )";

            if (!ejecutarConsulta($sql_detalle)) {
                $sw = false;
            }


        }



        return $sw ? "Nómina registrada y partida contable generada." : "Error al registrar la nómina.";
    }


    //Implementamos un método para editar registros
    public function editar($idnomina_pagos, $fecha_inicio, $fecha_fin, $descripcion, $detalles_json)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        $sql = "UPDATE nomina_pagos SET 
                        fecha_inicio='$fecha_inicio',
                        fecha_fin='$fecha_fin',
                        descripcion='$descripcion' ,
                        idusuario_modificacion='" . $_SESSION["idusuario"] . "',
                        fecha_modificacion='$fechaHora'
                        WHERE idnomina_pagos='$idnomina_pagos'";
        ejecutarConsulta($sql);

        //eliminar los detalles de la nomina pagos
        $sql_detalle = "DELETE FROM detalle_nomina_pagos WHERE idnomina_pagos='$idnomina_pagos'";
        ejecutarConsulta($sql_detalle);
        $sw = true;




        foreach ($detalles_json as $i => $detalle) {
            $sql_detalle = "INSERT INTO detalle_nomina_pagos (
                idnomina_pagos, 
                idempleado, 
                dias_trabajados, 
                horas_trabajados,
                diario, 
                salario_base,
                total_salario,
                salario_extra, 
                total_devengado, 
                bonificacion_ley, 
                bonificacion_productividad,
                descuento_igss, 
                descuento_isr, 
                abono_prestamo, 
                abono_otrosDescuentos,
                abono_adelantoQuincenal, 
                abono_adelantoSalarial, 
                total_deducciones,
                liquido_recibir
            ) VALUES (
                '$idnomina_pagos',
                '{$detalle['idempleado']}',
                '{$detalle['dias_trabajados']}',
                '{$detalle['horas_trabajados']}',
                '{$detalle['diario']}',
                '{$detalle['salario_base']}',
                '{$detalle['total_salario']}',
                '{$detalle['salario_extra']}',
                '{$detalle['total_devengado']}',
                '{$detalle['bonificacion_ley']}',
                '{$detalle['bonificacion_productividad']}',
                '{$detalle['descuento_igss']}',
                '{$detalle['descuento_isr']}',
                '{$detalle['abono_prestamo']}',
                '{$detalle['abono_otrosDescuentos']}',
                '{$detalle['abono_adelantoQuincenal']}',
                '{$detalle['abono_adelantoSalarial']}',
                '{$detalle['total_deducciones']}',
                '{$detalle['liquido_recibir']}'
            )";
            if (!ejecutarConsulta($sql_detalle)) {
                $sw = false;
            }

  
        }



        return $sw ? "Nomina pagos registrada correctamente" : "Error al registrar la nomina pagos";
    }

    public function insertar_adelantos_quincenal($fecha_inicio, $fecha_fin, $descripcion, $detalles_json)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sql = "INSERT INTO nomina_pagos_quincenales (fecha_inicio,fecha_fin,descripcion,idusuario,fecha_creacion)
        VALUES ('$fecha_inicio','$fecha_fin','$descripcion','" . $_SESSION["idusuario"] . "','$fechaHora')";

        $idnomina_pagosnew = ejecutarConsulta_retornarID($sql);
        $sw = true;

        foreach ($detalles_json as $i => $detalle) {
            $sql_detalle = "INSERT INTO detalle_nomina_pagos_quincenales (
                idnomina_pagos_quincenales, idempleado, dias_trabajados, abono_adelantoQuincenal
            ) VALUES (
                '$idnomina_pagosnew',
                '{$detalle['idempleado']}',
                '{$detalle['dias_trabajados']}',
                '{$detalle['abono_adelantoQuincenal']}'
            )";

            if (!ejecutarConsulta($sql_detalle)) {
                $sw = false;
            }
        }
        return $sw;
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idnomina_pagos)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        $sql = "UPDATE nomina_pagos SET 
                            condicion='0' ,
                            idusuario_delete='" . $_SESSION["idusuario"] . "',
                            fecha_delete='$fechaHora'
                            WHERE idnomina_pagos='$idnomina_pagos'";
        return ejecutarConsulta($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idnomina_pagos)
    {
        $sql = "SELECT 
            n.idnomina_pagos,
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
            n.fecha_delete
        FROM nomina_pagos n WHERE n.idnomina_pagos='$idnomina_pagos'";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function detallenominapagos($idnomina_pagos)
    {

        $sqldetalle = "SELECT 
            d.iddetalle_nomina_pagos,
            d.idnomina_pagos,
            d.idempleado,
            e.nombres AS nombre_empleado,
            e.cui AS cui_empleado,
            d.dias_trabajados,
            d.horas_trabajados,
            d.diario,
            d.salario_base,
            d.total_salario,
            d.salario_extra,
            d.total_devengado,
            d.bonificacion_ley,
            d.bonificacion_productividad,
            d.descuento_igss,
            d.descuento_isr,
            d.abono_prestamo,
            d.abono_otrosDescuentos,
            d.abono_adelantoQuincenal,
            d.abono_adelantoSalarial,
            d.total_deducciones,
            d.liquido_recibir
        FROM detalle_nomina_pagos d
        INNER JOIN empleados e ON e.idempleado=d.idempleado
        WHERE d.idnomina_pagos='$idnomina_pagos'";

        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    //Implementar un método para listar los registros
    public function listar($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
            n.idnomina_pagos,
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
            n.fecha_delete
        FROM nomina_pagos n
        INNER JOIN usuario u ON u.idusuario=n.idusuario
        WHERE DATE(n.fecha_creacion)>='$fecha_inicio' AND DATE(n.fecha_creacion)<='$fecha_fin'
        ";
        return ejecutarConsulta($sql);
    }


    //Implementar un método para listar los registros y mostrar en el select
    public function listarEmpleados($fecha_inicio, $fecha_fin)
    {
        $sqlEmpleados = "SELECT 
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

                    -- 💰 Campos base
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

                    -- 💰 Total préstamos
                    ROUND(IFNULL((
                        SELECT SUM(o.abono_prestamo)
                        FROM abono_otrosdecuentos_empleado o
                        WHERE o.idempleado = e.idempleado
                        AND DATE(o.fecha_hora)>='$fecha_inicio' AND DATE(o.fecha_hora)<='$fecha_fin'
                        AND o.tipo_operacion = 'PRESTAMO'
                        AND o.condicion = 1
                    ),0),2) AS abono_prestamo,

                    -- 💳 Otros descuentos
                    ROUND(IFNULL((
                        SELECT SUM(o.abono_prestamo)
                        FROM abono_otrosdecuentos_empleado o
                        WHERE o.idempleado = e.idempleado
                        AND DATE(o.fecha_hora)>='$fecha_inicio' AND DATE(o.fecha_hora)<='$fecha_fin'
                        AND o.tipo_operacion = 'OTROS DESCUENTOS'
                        AND o.condicion = 1
                    ),0),2) AS abono_otrosDescuentos,

                    -- 💵 Adelanto quincenal
                    ROUND(IFNULL((
                        SELECT SUM(o.abono_prestamo)
                        FROM abono_otrosdecuentos_empleado o
                        WHERE o.idempleado = e.idempleado
                        AND DATE(o.fecha_hora)>='$fecha_inicio' AND DATE(o.fecha_hora)<='$fecha_fin'
                        AND o.tipo_operacion = 'ADELANTO QUINCENAL'
                        AND o.condicion = 1
                    ),0),2) AS abono_adelantoQuincenal,

                    ROUND(IFNULL((
                        SELECT d.abono_adelantoQuincenal
                        FROM nomina_pagos_quincenales n
                        INNER JOIN detalle_nomina_pagos_quincenales d
                            ON n.idnomina_pagos_quincenales = d.idnomina_pagos_quincenales
                        WHERE d.idempleado = e.idempleado
                        AND DATE(n.fecha_inicio)>='$fecha_inicio' AND DATE(n.fecha_inicio)<='$fecha_fin'
                        AND n.condicion = 1
                        LIMIT 1
                    ),0),2) AS adelanto_quincena,

                    -- 💰 Anticipo salarial
                    ROUND(IFNULL((
                        SELECT SUM(o.abono_prestamo)
                        FROM abono_otrosdecuentos_empleado o
                        WHERE o.idempleado = e.idempleado
                        AND DATE(o.fecha_hora)>='$fecha_inicio' AND DATE(o.fecha_hora)<='$fecha_fin'
                        AND o.tipo_operacion = 'ANTICIPO SALARIAL'
                        AND o.condicion = 1
                    ),0),2) AS abono_adelantoSalarial,

                    -- ⏱️ TOTAL HORAS TRABAJADAS
                    ROUND(
                        IFNULL((
                            SELECT SUM(
                                TIMESTAMPDIFF(
                                    SECOND,
                                    r1.fecha_creacion,
                                    r2.fecha_creacion
                                )
                            ) / 3600
                            FROM registro_app r1
                            JOIN registro_app r2
                                ON r2.codigo = r1.codigo
                            AND r2.tipo_salida_entrada = 1
                            AND r2.fecha_creacion > r1.fecha_creacion
                            AND NOT EXISTS (
                                SELECT 1
                                FROM registro_app r3
                                WHERE r3.codigo = r1.codigo
                                    AND r3.tipo_salida_entrada = 0
                                    AND r3.fecha_creacion > r1.fecha_creacion
                                    AND r3.fecha_creacion < r2.fecha_creacion
                            )
                            WHERE r1.tipo_salida_entrada = 0
                            AND r1.codigo = e.codigo
                            AND DATE(r1.fecha_creacion)>='$fecha_inicio' AND DATE(r1.fecha_creacion)<='$fecha_fin'
                        ),0),
                    2
                    ) AS horas_trabajadas

                FROM empleados e
                WHERE e.condicion = 1";
        $rspta = ejecutarConsulta($sqlEmpleados);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    public function cabeceranominapagos($idnomina_pagos)
    {
        $sql = "SELECT 
            n.idnomina_pagos,
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
            s.email AS sucursal_email
        FROM nomina_pagos n 
        INNER JOIN usuario u ON u.idusuario=n.idusuario
        LEFT join sucursal s on s.idsucursal=u.idsucursal
        WHERE n.idnomina_pagos='$idnomina_pagos'";
        return ejecutarConsulta($sql);
    }


    public function cabeceranominapagosPDF()
    {
        $sql = "SELECT 
            u.nombre as nombre_usuario,
            s.imagen AS sucursal_imagen,
            s.nombre AS sucursal_nombre,
            s.nit AS sucursal_nit,
            s.direccion AS sucursal_direccion,
            s.telefono AS sucursal_telefono,
            s.email AS sucursal_email
        FROM usuario u
        inner join sucursal s on s.idsucursal=u.idsucursal limit 1";
        return ejecutarConsulta($sql);
    }


    public function detallecabeceranominapagos($idnomina_pagos)
    {
        $sql = "SELECT 
                d.iddetalle_nomina_pagos,
                d.idnomina_pagos,
                d.idempleado,
                e.nombres AS nombre_empleado,
                e.cui,
                d.dias_trabajados,
                d.horas_trabajados,
                d.diario,
                d.salario_base,
                d.total_salario,
                d.salario_extra,
                d.total_devengado,
                d.bonificacion_ley,
                d.bonificacion_productividad,
                d.descuento_igss,
                d.descuento_isr,
                d.abono_prestamo,
                d.abono_otrosDescuentos,
                d.abono_adelantoQuincenal,
                d.abono_adelantoSalarial,
                d.total_deducciones,
                d.liquido_recibir
            FROM detalle_nomina_pagos d
            INNER JOIN empleados e ON e.idempleado=d.idempleado
            WHERE d.idnomina_pagos='$idnomina_pagos'";
        return ejecutarConsulta($sql);
    }


    public function detallecabeceranominapagosPDF($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                d.iddetalle_nomina_pagos,
                d.idnomina_pagos,
                d.idempleado,
                e.nombres AS nombre_empleado,
                e.cui,
                e.nit,
                d.dias_trabajados,
                d.diario,
                sum(d.salario_base) AS salario_base,
                sum(d.salario_extra) AS salario_extra,
                sum(d.total_devengado) AS total_devengado,
                sum(d.bonificacion_ley) AS bonificacion_ley,
                sum(d.bonificacion_productividad) AS bonificacion_productividad,
                sum(d.descuento_igss) AS descuento_igss,
                sum(d.descuento_isr) AS descuento_isr,
                sum(d.abono_prestamo) AS abono_prestamo,
                sum(d.abono_otrosDescuentos) AS abono_otrosDescuentos,
                sum(d.abono_adelantoQuincenal) AS abono_adelantoQuincenal,
                sum(d.abono_adelantoSalarial) AS abono_adelantoSalarial,
                sum(d.total_deducciones) AS total_deducciones,
                sum(d.liquido_recibir) AS liquido_recibir
            FROM detalle_nomina_pagos d
            INNER JOIN empleados e ON e.idempleado=d.idempleado
            INNER JOIN nomina_pagos n ON n.idnomina_pagos=d.idnomina_pagos
            WHERE DATE(n.fecha_creacion)>='$fecha_inicio' AND DATE(n.fecha_creacion)<='$fecha_fin'
            GROUP BY  d.idempleado";
        return ejecutarConsulta($sql);
    }

    public function listar_adelantos_quincenal($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
            n.idnomina_pagos_quincenales,
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
            n.fecha_delete
        FROM nomina_pagos_quincenales n
        INNER JOIN usuario u ON u.idusuario=n.idusuario
        WHERE DATE(n.fecha_inicio)>='$fecha_inicio' AND DATE(n.fecha_fin)<='$fecha_fin'";
        return ejecutarConsulta($sql);
    }

    public function desactivar_adelanto_quincenal($idnomina_pagos_quincenales)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        $sql = "UPDATE nomina_pagos_quincenales SET 
                            condicion='0' ,
                            idusuario_delete='" . $_SESSION["idusuario"] . "',
                            fecha_delete='$fechaHora'
                            WHERE idnomina_pagos_quincenales='$idnomina_pagos_quincenales'";
        return ejecutarConsulta($sql);
    }

    /*
    public function detallecabeceranominapagos_adelanto_quincenal($idnomina_pagos_quincenales){
        $sql="SELECT 
                d.iddetalle_nomina_pagos_quincenales,
                d.idnomina_pagos_quincenales,
                d.idempleado,
                e.nombres AS nombre_empleado,
                e.cui,
                d.dias_trabajados,
                d.diario,
                d.salario_base,
                d.salario_extra,
                d.total_devengado,
                d.bonificacion_ley,
                d.bonificacion_productividad,
                d.descuento_igss,
                d.descuento_isr,
                d.abono_prestamo,
                d.abono_otrosDescuentos,
                d.abono_adelantoQuincenal,
                d.abono_adelantoSalarial,
                d.total_deducciones,
                d.liquido_recibir
            FROM detalle_nomina_pagos_quincenales d
            INNER JOIN empleados e ON e.idempleado=d.idempleado
            WHERE d.idnomina_pagos_quincenales='$idnomina_pagos_quincenales'";
        return ejecutarConsulta($sql);
    }
    */
    public function detallecabeceranominapagos_adelanto_quincenal($idnomina_pagos_quincenales)
    {
        $sql_fechas = "SELECT fecha_inicio, fecha_fin FROM nomina_pagos_quincenales WHERE idnomina_pagos_quincenales = '$idnomina_pagos_quincenales'";
        $res_f = ejecutarConsultaSimpleFila($sql_fechas);
        $f_inicio = $res_f['fecha_inicio'];
        $f_fin = $res_f['fecha_fin'];

        $sql = "SELECT 
                    d.iddetalle_nomina_pagos_quincenales,
                    d.idnomina_pagos_quincenales,
                    d.idempleado,
                    e.nombres AS nombre_empleado,
                    e.cui,
                    d.dias_trabajados,
                    d.diario,
                    d.salario_base,
                    d.salario_extra,
                    d.total_devengado,
                    d.bonificacion_ley,
                    d.bonificacion_productividad,
                    d.descuento_igss,
                    d.descuento_isr,
                    d.abono_prestamo,
                    d.abono_otrosDescuentos,
                    d.abono_adelantoQuincenal,
                    d.abono_adelantoSalarial,
                    d.total_deducciones,
                    d.liquido_recibir,
                    IFNULL((
                        SELECT SUM(deto.monto_abono) 
                        FROM detalleOtrosdecuentos_empleado deto
                        INNER JOIN otrosdecuentos_empleado o ON o.idotrosdecuentosempleado = deto.idotrosdecuentosempleado
                        WHERE o.idempleado = d.idempleado 
                        AND deto.fecha_abono >= '$f_inicio' 
                        AND deto.fecha_abono <= '$f_fin'
                    ), 0) AS total_otros_descuentos_periodo
                FROM detalle_nomina_pagos_quincenales d
                INNER JOIN empleados e ON e.idempleado = d.idempleado
                WHERE d.idnomina_pagos_quincenales = '$idnomina_pagos_quincenales'";

        return ejecutarConsulta($sql);
    }

    public function cabeceranominapagos_adelanto_quincenal($idnomina_pagos_quincenales)
    {
        $sql = "SELECT 
            n.idnomina_pagos_quincenales,
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
            s.email AS sucursal_email
        FROM nomina_pagos_quincenales n 
        INNER JOIN usuario u ON u.idusuario=n.idusuario
        inner join sucursal s on s.idsucursal=u.idsucursal
        WHERE n.idnomina_pagos_quincenales='$idnomina_pagos_quincenales'";
        return ejecutarConsulta($sql);
    }

}

?>