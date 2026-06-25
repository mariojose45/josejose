<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Sucursal
{
    //Implementamos nuestro constructor
    public function __construct() {}

    //Implementamos un método para insertar registros
    public function insertar($nombre, $direccion, $telefono, $nit, $email, $imagen, $clave_ordenes, $clave_ingresos, $clave_ventas, $calculo_descuento)
    {
        try {
            $fecha_creacion = date("Y-m-d H:i:s");
            // Intentamos ejecutar la consulta de inserción en la tabla sucursal
            $sql = "INSERT INTO sucursal (nombre, direccion, telefono, nit, email, 
            imagen, condicion, clave_ordenes, clave_ingresos, clave_ventas,
            fecha_creacion,calculo_descuento)
                    VALUES ('$nombre', '$direccion', '$telefono', '$nit', '$email', 
                    '1590204245.jpg', '1', '$clave_ordenes', '$clave_ingresos', 
                    '$clave_ventas','$fecha_creacion','$calculo_descuento')";
            $idcorrelativonew = ejecutarConsulta_retornarID($sql);

            $sqlCorrelativoNuevo = "INSERT INTO add_correlativo (idsucursal)
                VALUES ('$idcorrelativonew')";
            ejecutarConsulta($sqlCorrelativoNuevo);

            $idsucursal = $idcorrelativonew;

            $sql = "
                    INSERT INTO certificador (
                        idsucursal,
                        prueba_produccion,
                        _CLIENTE_,
                        _USUARIO_,
                        _PASS_,
                        _NIT_,
                        fecha_creacion,
                        condicion,
                        dato_sat,
                        nombrecertificador,
                        empresadesarrollo
                    ) VALUES
                    (
                        '$idsucursal',
                        'PRUEBAS',
                        '0',
                        '0',
                        '0',
                        '0',
                        NOW(),
                        1,
                        'Sujeto a retención',
                        'CERTIFICADOR',
                        'Developed / +502 2293-4153 / WhatsApp: +502 5622-2080'
                    ),
                    (
                        '$idsucursal',
                        'PRODUCCION',
                        '0',
                        '0',
                        '0',
                        '0',
                        NOW(),
                        0,
                        'Sujeto a retención',
                        'CERTIFICADOR',
                        'Developed / +502 2293-4153 / WhatsApp: +502 5622-2080'
                    )";

            ejecutarConsulta($sql);


            return ($idcorrelativonew);
        } catch (Exception $e) {
            // Si ocurre un error, registramos en la tabla logs
            $descripcion_error = "Error sucursal Creacion: " . $e->getMessage();
            $fecha_error = date("Y-m-d H:i:s");
            $idusuario = $_SESSION['idusuario'] ?? 0; // Opcional: Obtén el usuario desde la sesión
            $idsucursal = 0; // Asigna un valor predeterminado, si es necesario

            // Registro en la tabla logs
            $sql_log = "INSERT INTO logs ( idusuario, idsucursal, descripcion_error, fecha_error)
                        VALUES ( '" . $_SESSION["idusuario"] . "', '" . $_SESSION["idsucursal"] . "', '$descripcion_error', '$fecha_error')";
            ejecutarConsulta($sql_log);

            // Retorna un mensaje de error
            return "Error: " . $descripcion_error;
        }
    }


    //Implementamos un método para editar registros
    public function editar($idsucursal, $nombre, $direccion, $telefono, $nit, $email, $imagen, $clave_ordenes, $clave_ingresos, $clave_ventas, $calculo_descuento)
    {
        try {
            // Intentamos ejecutar la consulta de actualización en la tabla sucursal
            $sql = "UPDATE sucursal SET 
                        nombre = '$nombre',
                        direccion = '$direccion',
                        telefono = '$telefono',
                        nit = '$nit',
                        email = '$email',
                        clave_ordenes = '$clave_ordenes',
                        clave_ingresos = '$clave_ingresos',
                        clave_ventas = '$clave_ventas',
                        calculo_descuento='$calculo_descuento'
                    WHERE idsucursal = '$idsucursal'";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            // Si ocurre un error, registramos en la tabla logs
            $descripcion_error = "Error sucursal Editar: " . $e->getMessage();
            $fecha_error = date("Y-m-d H:i:s");
            $idusuario = $_SESSION['idusuario'] ?? 0; // Opcional: Obtén el usuario desde la sesión
            $idsucursal_log = $idsucursal ?? 0; // Si no hay valor para idsucursal, asignamos 0 como predeterminado

            // Registro en la tabla logs
            $sql_log = "INSERT INTO logs (idusuario, idsucursal, descripcion_error, fecha_error)
                        VALUES ('" . $_SESSION["idusuario"] . "', '" . $_SESSION["idsucursal"] . "', '$descripcion_error', '$fecha_error')";
            ejecutarConsulta($sql_log);

            // Retorna un mensaje de error
            return "Error: " . $descripcion_error;
        }
    }


    //Implementamos un método para desactivar categorías
    public function desactivar($idsucursal)
    {
        $sql = "UPDATE sucursal SET condicion='0' WHERE idsucursal='$idsucursal'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idsucursal)
    {
        $sql = "UPDATE sucursal SET condicion='1' WHERE idsucursal='$idsucursal'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idsucursal)
    {
        $sql = "SELECT * FROM sucursal WHERE idsucursal='$idsucursal'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM sucursal";
        return ejecutarConsulta($sql);
    }


    public function guardarSession($idsucursal, $idusuario, $fechaHora)
    {

        $sql = "INSERT INTO session_usuarios (idsucursal,idusuario,fechaHora,estado)
                    VALUES ('$idsucursal','$idusuario','$fechaHora','1')";
        ejecutarConsulta($sql);
    }

    public function obtenerClaveOrdenes($idsucursal)
    {
        $sql = "SELECT s.clave_ordenes 
                FROM sucursal s
                WHERE s.idsucursal = '$idsucursal' LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function obtenerClaveIngresos($idsucursal)
    {
        $sql = "SELECT s.clave_ingresos 
                FROM sucursal s
                WHERE s.idsucursal = '$idsucursal' LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);
    }
}
