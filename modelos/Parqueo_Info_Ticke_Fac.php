<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";




class Parquointicketfac
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }



    //Implementamos un método para editar registros
    public function editar($id_informacionticket, $instruccionesdeticket, $mensajefinal, $correlativo_ticket, $horario_atencion)
    {
        $sql = "UPDATE informacion_ticket SET instruccionesdeticket='$instruccionesdeticket',mensajefinal='$mensajefinal',
         correlativo_ticket_factura = '$correlativo_ticket', horario_atencion = '$horario_atencion'
        WHERE id_informacionticket='$id_informacionticket'";
        // print_r($sql);
        return ejecutarConsulta($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($id_informacionticket)
    {
        $sql = "SELECT * FROM informacion_ticket WHERE id_informacionticket='$id_informacionticket'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT 
                i.id_informacionticket,
                i.instruccionesdeticket,
                i.mensajefinal,
                i.correlativo_ticket_factura,
                i.condicion,
                s.nombre,
                i.horario_atencion
            FROM informacion_ticket i
            INNER JOIN sucursal s ON s.idsucursal=i.idsucursal";
        return ejecutarConsulta($sql);
    }



}

?>