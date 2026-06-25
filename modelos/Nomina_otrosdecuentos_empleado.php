<?php 
if (strlen(session_id()) < 1) 
session_start();
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Nominaotrosdecuentosempleado
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($idempleado,$monto_prestamo,$no_cuotas,$fecha_prestamo,
    $fecha_ultimo_abono,$concepto_prestamo,$no_cuota,$fecha_abono,$monto_abono,$tipo_operacion)
    {
        $sql="INSERT INTO otrosdecuentos_empleado (idempleado,monto_prestamo,no_cuotas,fecha_prestamo,
        fecha_ultimo_abono,concepto_prestamo,condicion,fecha_creacion,idusuario,saldo_prestamo,tipo_operacion)
        VALUES ('$idempleado','$monto_prestamo','$no_cuotas','$fecha_prestamo',
            '$fecha_ultimo_abono','$concepto_prestamo','1',DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'),'".$_SESSION["idusuario"]."','$monto_prestamo','$tipo_operacion')";
         $idprestamoempleadonew=ejecutarConsulta_retornarID($sql);

        $num_elementos=0;  
        $sw=true; 
 
        while ($num_elementos < count($no_cuota)) 
        {
            $sql_detalle = "INSERT INTO detalleOtrosdecuentos_empleado(idotrosdecuentosempleado, no_cuota,fecha_abono,monto_abono) 
            VALUES ('$idprestamoempleadonew', '$no_cuota[$num_elementos]','$fecha_abono[$num_elementos]','$monto_abono[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos=$num_elementos + 1;
        }
        return $sql;
    }

 
    //Implementamos un método para editar registros
    public function editar($idotrosdecuentosempleado,$idempleado,$monto_prestamo,$no_cuotas,$fecha_prestamo,
    $fecha_ultimo_abono,$concepto_prestamo,$no_cuota,$fecha_abono,$monto_abono,$tipo_operacion)
    {

        $sql="UPDATE otrosdecuentos_empleado SET 
            idempleado='$idempleado',
            monto_prestamo='$monto_prestamo',
            no_cuotas='$no_cuotas',
            fecha_prestamo='$fecha_prestamo',
            fecha_ultimo_abono='$fecha_ultimo_abono',
            concepto_prestamo='$concepto_prestamo',
            fecha_modificacion=DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'),
            idusuario_modificacion='".$_SESSION["idsucursal"]."',
            saldo_prestamo='$monto_prestamo',
            tipo_operacion='$tipo_operacion'
         WHERE idotrosdecuentosempleado='$idotrosdecuentosempleado'";
        ejecutarConsulta($sql);

        $sql_detalle = "DELETE FROM detalleOtrosdecuentos_empleado WHERE idotrosdecuentosempleado = '$idotrosdecuentosempleado'";
        ejecutarConsulta($sql_detalle);

        $num_elementos=0;  
        $sw=true; 
 
        while ($num_elementos < count($no_cuota)) 
        {
            $sql_detalle = "INSERT INTO detalleOtrosdecuentos_empleado(idotrosdecuentosempleado, no_cuota,fecha_abono,monto_abono) 
            VALUES ('$idotrosdecuentosempleado', '$no_cuota[$num_elementos]','$fecha_abono[$num_elementos]','$monto_abono[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos=$num_elementos + 1;
        }
        return $sw;        

        
    }

    public function insertarAbonoPrestamo($idotrosdecuentosempleado_abono,$idempleadoAbono,
    $monto_prestamoAbono,$monto_abonoAbono,$saldo_prestamoAbono,$fecha_abono,$idcuenta,$descripcion_abono,$tipo_operacion_abono)
    {
        try {
            // 1️⃣ Insertar abono en la tabla de abonos
            $sql = "INSERT INTO abono_otrosdecuentos_empleado (
                        idotrosdecuentosempleado,
                        idempleado,
                        monto_prestamo,
                        abono_prestamo,
                        saldo_prestamo,
                        idusuario,
                        fecha_creacion,
                        condicion,
                        fecha_hora,
                        idcuenta,
                        descripcion,
                        tipo_operacion
                    ) VALUES (
                        '$idotrosdecuentosempleado_abono',
                        '$idempleadoAbono',
                        '$monto_prestamoAbono',
                        '$monto_abonoAbono',
                        '$saldo_prestamoAbono',
                        '" . $_SESSION["idusuario"] . "',
                        DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'),
                        '1',
                        '$fecha_abono',
                        '$idcuenta',
                        '$descripcion_abono',
                        '$tipo_operacion_abono'
                    )";
    
            $insertar = ejecutarConsulta($sql);
    
            // Verificar si falló la inserción
            if (!$insertar) {
                return "❌ Error al registrar el abono. Por favor, intenta nuevamente.";
            }
    
            // 2️⃣ Actualizar saldo en la tabla de préstamos
            $sqlPrestamo = "UPDATE otrosdecuentos_empleado 
                            SET saldo_prestamo = '$saldo_prestamoAbono'
                            WHERE idotrosdecuentosempleado = '$idotrosdecuentosempleado_abono'";
    
            $actualizar = ejecutarConsulta($sqlPrestamo);
    
            // Verificar si falló la actualización
            if (!$actualizar) {
                return "⚠️ Abono registrado, pero no se pudo actualizar el saldo del préstamo.";
            }
    
            // 3️⃣ Si todo fue bien
            return "✅ Abono registrado correctamente.";
    
        } catch (Exception $e) {
            // Capturar cualquier error inesperado
            return "🚫 Error inesperado: " . $e->getMessage();
        }
    }
    
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idotrosdecuentosempleado)
    {
        // 1️⃣ Verificar si el préstamo tiene abonos registrados activos
        $sql_verificar = "
            SELECT COUNT(*) AS total_abonos
            FROM abono_otrosdecuentos_empleado
            WHERE idotrosdecuentosempleado = '$idotrosdecuentosempleado'
            AND condicion = 1
        ";
    
        $verificacion = ejecutarConsultaSimpleFila($sql_verificar);
    
        if ($verificacion && $verificacion['total_abonos'] > 0) {
            // 2️⃣ Si ya tiene abonos, no se puede desactivar
            return "❌ No se puede eliminar o desactivar este préstamo porque ya tiene abonos registrados.";
        }
    
        // 3️⃣ Si no tiene abonos, permitir desactivar
        $sql = "UPDATE otrosdecuentos_empleado 
                SET condicion = '0',
                idusuario_delete='".$_SESSION["idusuario"]."',
                fecha_delete=DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i')
                WHERE idotrosdecuentosempleado = '$idotrosdecuentosempleado'";
    
         ejecutarConsulta($sql);
        return ("Prestamo Desactivado");
    }
    
 
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idotrosdecuentosempleado)
    {

        // Si el parámetro viene vacío, retornamos un error claro
        if (empty($idotrosdecuentosempleado)) {
            return [
                "error" => true,
                "mensaje" => "⚠️ No se recibió el ID del préstamo correctamente."
            ];
        }
    
        // Verificar si tiene abonos activos
        $sql_verificar = "
            SELECT COUNT(*) AS total_abonos
            FROM abono_otrosdecuentos_empleado
            WHERE idotrosdecuentosempleado = '$idotrosdecuentosempleado'
            AND condicion = 1
        ";
        $verificacion = ejecutarConsultaSimpleFila($sql_verificar);
    
        if ($verificacion && $verificacion['total_abonos'] > 0) {
            return [
                "error" => true,
                "mensaje" => "❌ No se puede editar este préstamo porque ya tiene abonos registrados."
            ];
        }
    
        // Mostrar los datos del préstamo
        $sql = "
            SELECT 
                p.idotrosdecuentosempleado,
                p.idempleado,
                p.monto_prestamo,
                p.no_cuotas,
                DATE(p.fecha_prestamo) AS fecha_prestamo,
                DATE(p.fecha_ultimo_abono) AS fecha_ultimo_abono,
                p.concepto_prestamo,
                p.fecha_creacion,
                p.condicion,
                p.idusuario,
                p.fecha_modificacion,
                p.idusuario_modificacion,
                p.tipo_operacion
            FROM otrosdecuentos_empleado p
            WHERE p.idotrosdecuentosempleado = '$idotrosdecuentosempleado'
            LIMIT 1
        ";
        $resultado = ejecutarConsultaSimpleFila($sql);
        if (!$resultado) {
            return [
                "error" => true,
                "mensaje" => "⚠️ No se encontró información del préstamo solicitado."
            ];
        }
    
        // Si todo sale bien
        $resultado['error'] = false;
        return $resultado;
    }
    
    
    


    public function mostrarAbonoPrestamo($idotrosdecuentosempleado)
    {

        // 3️⃣ Si no hay abonos, mostrar los datos del préstamo normalmente
        $sql = "SELECT 
                p.idotrosdecuentosempleado,
                p.idempleado,
                p.monto_prestamo,
                p.saldo_prestamo,
                p.tipo_operacion,
                p.tipo_operacion              
            FROM otrosdecuentos_empleado p
            WHERE p.idotrosdecuentosempleado = '$idotrosdecuentosempleado'";
        return  ejecutarConsultaSimpleFila($sql);
    
    }    

    public function estadoCtaprestamo($idotrosdecuentosempleado)
    {

        // 3️⃣ Si no hay abonos, mostrar los datos del préstamo normalmente
        $sql = "SELECT 
                p.idotrosdecuentosempleado,
                p.idempleado,
                e.nombres AS nombre_empleado,
                e.direccion  AS direccion_empleado,
                e.telefono AS telefono_empleado,
                e.cui AS cui_empleado,
                p.monto_prestamo,
                p.tipo_operacion,
                -- 🔹 Total abonado
                (
                    SELECT IFNULL(SUM(a.abono_prestamo), 0)
                    FROM abono_otrosdecuentos_empleado a
                    WHERE a.idotrosdecuentosempleado = p.idotrosdecuentosempleado
                    AND a.condicion = 1
                ) AS abono_prestamo,

                -- 🔹 Último saldo (si no hay registros, muestra 0)
                (
                    SELECT IFNULL(
                        (
                            SELECT a.saldo_prestamo
                            FROM abono_otrosdecuentos_empleado a
                            WHERE a.idotrosdecuentosempleado = p.idotrosdecuentosempleado
                            AND a.condicion = 1
                            ORDER BY a.idotrosdecuentosempleado DESC
                            LIMIT 1
                        ), 
                    0)
                ) AS saldo_prestamos,

                -- 🔹 Cuotas pagadas
                (
                    SELECT COUNT(a.idotrosdecuentosempleado)
                    FROM abono_otrosdecuentos_empleado a
                    WHERE a.idotrosdecuentosempleado = p.idotrosdecuentosempleado
                    AND a.condicion = 1
                ) AS no_cuotas_pagadas,

                p.no_cuotas,
                p.fecha_prestamo,
                p.fecha_ultimo_abono,
                p.concepto_prestamo,
                p.fecha_creacion,
                p.condicion,
                p.idusuario,
                p.fecha_modificacion,
                p.idusuario_modificacion,
                s.imagen AS sucursal_imagen,
                s.nombre AS sucursal_nombre,
                s.nit AS sucursal_nit,
                s.direccion AS sucursal_direccion,
                s.telefono AS sucursal_telefono,
                s.email AS sucursal_email,
                u.nombre AS usuario
            FROM otrosdecuentos_empleado p
            INNER JOIN empleados e ON e.idempleado = p.idempleado
            left JOIN usuario u ON u.idusuario=p.idusuario
            left JOIN sucursal s ON s.idsucursal=u.idsucursal
            WHERE p.idotrosdecuentosempleado ='$idotrosdecuentosempleado' limit 1";
        return  ejecutarConsulta($sql);
    } 
    
    public function DetalleestadoCtaprestamo($idotrosdecuentosempleado)
    {

        // 3️⃣ Si no hay abonos, mostrar los datos del préstamo normalmente
        $sql = "SELECT 
                a.idabono_otrosdecuentos_empleado,
                a.idotrosdecuentosempleado,
                a.idempleado,
                e.nombres AS nombre_empleado,
                a.monto_prestamo,
                a.abono_prestamo,
                a.saldo_prestamo,
                date(a.fecha_hora) AS fecha_hora,
                a.idusuario,
                u.nombre AS usuario,
                a.fecha_creacion,
                a.condicion,
                c.cta_nombre,
                a.descripcion
            FROM abono_otrosdecuentos_empleado a
            INNER JOIN empleados e ON e.idempleado = a.idempleado
            INNER JOIN cuenta c ON c.idcuenta=a.idcuenta
            inner join usuario u on u.idusuario=a.idusuario
            WHERE a.idotrosdecuentosempleado = '$idotrosdecuentosempleado'";
        return  ejecutarConsulta($sql);
    }  
    
    
    public function DetalleestadoCtaprestamoAbono($idabono_otrosdecuentos_empleado)
    {

        // 3️⃣ Si no hay abonos, mostrar los datos del préstamo normalmente
        $sql = "SELECT 
                a.idabono_otrosdecuentos_empleado,
                a.idotrosdecuentosempleado,
                a.idempleado,
                e.nombres AS nombre_empleado,
                a.monto_prestamo,
                a.abono_prestamo,
                a.saldo_prestamo,
                date(a.fecha_hora) AS fecha_hora,
                a.idusuario,
                u.nombre AS usuario,
                a.fecha_creacion,
                a.condicion,
                c.cta_nombre,
                a.descripcion
            FROM abono_otrosdecuentos_empleado a
            INNER JOIN empleados e ON e.idempleado = a.idempleado
            INNER JOIN cuenta c ON c.idcuenta=a.idcuenta
            inner join usuario u on u.idusuario=a.idusuario
            WHERE a.idabono_otrosdecuentos_empleado = '$idabono_otrosdecuentos_empleado'";
        return  ejecutarConsulta($sql);
    }      

    public function listarDetallecuotas($idotrosdecuentosempleado)
    {
        $sql = "SELECT 
                no_cuota, 
                DATE(fecha_abono) as fecha_abono,
                monto_abono
            FROM detalleOtrosdecuentos_empleado
            WHERE idotrosdecuentosempleado = '$idotrosdecuentosempleado'
            ORDER BY no_cuota ASC";
    
    return ejecutarConsulta($sql);
    
         
    }
       

    public function listarDetalle($idotrosdecuentosempleado)
    {
        $sql = "SELECT 
                no_cuota, 
                DATE(fecha_abono) as fecha_abono,
                monto_abono
            FROM detalleOtrosdecuentos_empleado
            WHERE idotrosdecuentosempleado = '$idotrosdecuentosempleado'
            ORDER BY no_cuota ASC";
    
        $query = ejecutarConsulta($sql);
    
        $data = [];
        while ($reg = $query->fetch_assoc()) {
            $data[] = $reg;
        }
    
        return $data;
    }
    
    
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                p.idotrosdecuentosempleado,
                p.idempleado,
                e.nombres AS nombre_empleado,
                p.monto_prestamo,
                -- 🔹 Total abonado
                (
                    SELECT IFNULL(SUM(a.abono_prestamo), 0)
                    FROM abono_otrosdecuentos_empleado a
                    WHERE a.idotrosdecuentosempleado = p.idotrosdecuentosempleado
                    AND a.condicion = 1
                ) AS abono_prestamo,

                -- 🔹 Último saldo (si no hay registros, muestra 0)
                (
                    SELECT IFNULL(
                        (
                            SELECT a.saldo_prestamo
                            FROM abono_otrosdecuentos_empleado a
                            WHERE a.idotrosdecuentosempleado = p.idotrosdecuentosempleado
                            AND a.condicion = 1
                            ORDER BY a.idabono_otrosdecuentos_empleado DESC
                            LIMIT 1
                        ), 
                    0)
                ) AS saldo_prestamos,

                -- 🔹 Cuotas pagadas
                (
                    SELECT COUNT(a.idabono_otrosdecuentos_empleado)
                    FROM abono_otrosdecuentos_empleado a
                    WHERE a.idotrosdecuentosempleado = p.idotrosdecuentosempleado
                    AND a.condicion = 1
                ) AS no_cuotas_pagadas,

                p.no_cuotas,
                p.fecha_prestamo,
                p.fecha_ultimo_abono,
                p.concepto_prestamo,
                p.fecha_creacion,
                p.condicion,
                p.idusuario,
                p.fecha_modificacion,
                p.idusuario_modificacion,
                p.tipo_operacion
            FROM otrosdecuentos_empleado p
            INNER JOIN empleados e ON e.idempleado = p.idempleado
            INNER JOIN usuario u ON u.idusuario=p.idusuario
            ORDER BY p.idotrosdecuentosempleado DESC";
        return ejecutarConsulta($sql);      
    }


    public function listarAbonosPrestamo($fecha_inicio_reporte,$fecha_fin_reporte)
    {
        $sql="SELECT 
            a.idabono_otrosdecuentos_empleado,
            a.idotrosdecuentosempleado,
            a.idempleado,
            e.nombres AS nombre_empleado,
            a.monto_prestamo,
            a.abono_prestamo,
            a.saldo_prestamo as saldo_prestamos,
            date(a.fecha_hora) AS fecha_hora,
            a.idusuario,
            a.fecha_creacion,
            a.condicion,
            a.tipo_operacion
        FROM abono_otrosdecuentos_empleado a
        INNER JOIN empleados e ON e.idempleado = a.idempleado
        where DATE(a.fecha_hora)>='$fecha_inicio_reporte' 
                AND DATE(a.fecha_hora)<='$fecha_fin_reporte' ";
        return ejecutarConsulta($sql);      
    }

    public function estadoAbonoprestamo($idotrosdecuentosempleado)
    {
        $sql="SELECT 
                p.idotrosdecuentosempleado,
                s.imagen AS sucursal_imagen,
                s.nombre AS sucursal_nombre,
                s.nit AS sucursal_nit,
                s.direccion AS sucursal_direccion,
                s.telefono AS sucursal_telefono,
                s.email AS sucursal_email,
                u.nombre AS usuario,
                e.nombres AS nombre_empleado,
                e.direccion AS direccion_empleado,
                e.telefono AS telefono_empleado,
                e.cui AS cui_empleado
            FROM otrosdecuentos_empleado p
            INNER JOIN usuario u ON u.idusuario=p.idusuario
            INNER JOIN sucursal s ON s.idsucursal=u.idsucursal
            INNER JOIN empleados e ON e.idempleado=p.idempleado
            WHERE p.idotrosdecuentosempleado='$idotrosdecuentosempleado' limit 1";
            //print_r($sql);
        return ejecutarConsulta($sql);      
    } 

    public function abonosPrestamoEmpleado($idabono_otrosdecuentos_empleado)
    {
        $sql="SELECT 
                a.*,date(a.fecha_hora) AS fechahora
                FROM  abono_otrosdecuentos_empleado a
                WHERE a.idabono_otrosdecuentos_empleado='$idabono_otrosdecuentos_empleado' limit 1";
        return ejecutarConsulta($sql);      
    }     
    
    
}
 
?>