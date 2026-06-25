<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class CajaChica
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($aperturaCaja, $fecha_hora, $idusuario, $observacionesCaja)
    {

        $aperturaCajaN = ($aperturaCaja);
        $sql = "INSERT INTO caja_chica (fecha,descripcion,valor,estado,id_usuario,diferencia,idsucursal)
        VALUES ('$fecha_hora','$observacionesCaja','$aperturaCajaN','1','$idusuario','$aperturaCajaN','" . $_SESSION["idsucursal"] . "')";
        //return ejecutarConsulta($sql);
        $IdCajaChicaN = ejecutarConsulta_retornarID($sql);
        $sql = "INSERT INTO caja_chica_detalle (Id_CajaChica,IdUsuario,fecha,descripcion, valor)
        VALUES ('$IdCajaChicaN','$idusuario','$fecha_hora','$observacionesCaja', '$aperturaCajaN')";
        ejecutarConsulta($sql);


    }






    public function listarCajasChicas()
    {
        $sql = "SELECT *, usuario.nombre AS NombreUsuarioCreador FROM caja_chica chica LEFT JOIN usuario usuario ON chica.id_usuario=usuario.idusuario ";
        return ejecutarConsulta($sql);
    }







    //Implementamos un método para anular la venta
    public function anular($Id)
    {
        $sql = "UPDATE caja_chica SET estado=0 WHERE Id='$Id'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para terminar caja la venta
    public function terminarcaja($Id)
    {
        $sql = "UPDATE caja_chica SET estado=2 WHERE Id='$Id'";
        return ejecutarConsulta($sql);
    }



    public function quitarPago($IdCajaDetalle)
    {

        $sql = "SELECT * FROM caja_chica_detalle WHERE IdCajaDetalle='$IdCajaDetalle'";
        $res = ejecutarConsulta($sql);
        while ($req = $res->fetch_object()) {


            $sql = "UPDATE caja_chica SET total_items=total_items-'" . $req->valor . "'  WHERE Id='" . $req->Id_CajaChica . "'";
            ejecutarConsulta($sql);

            $sql = "UPDATE caja_chica SET diferencia=valor-total_items  WHERE Id='" . $req->Id_CajaChica . "'";
            ejecutarConsulta($sql);
        }



        $sql = "DELETE FROM  caja_chica_detalle WHERE IdCajaDetalle='$IdCajaDetalle'";
        return ejecutarConsulta($sql);
    }






    public function abonar($Id, $AbonoCajaChica, $Fechaabono, $ConceptoAbono, $tipo_comprobante, $idusuario)
    {

        //  $idusuario=$_SESSION["idusuario"];
        $sql = "INSERT INTO caja_chica_detalle (Id_CajaChica, IdUsuario, fecha, descripcion, valor,tipo_comprobante,condicion) VALUES ('$Id', '$idusuario', '$Fechaabono', '$ConceptoAbono', '$AbonoCajaChica','$tipo_comprobante','1')";
        ejecutarConsulta($sql);

        //  $AbonoCajaChicaN=number_format($AbonoCajaChica);
        // $AbonoCajaChicaN=number_format($AbonoCajaChica);
        $sql = "SELECT *, (valor)-" . $AbonoCajaChica . " AS diferencia FROM caja_chica WHERE Id='" . $Id . "'";
        $res = ejecutarConsulta($sql);
        while ($req = $res->fetch_object()) {

            $sql = "UPDATE caja_chica  SET total_items=total_items+" . $AbonoCajaChica . ", diferencia=diferencia-" . $AbonoCajaChica . " WHERE Id='" . $Id . "'";
            return ejecutarConsulta($sql);
        }






    }



    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT *, usuario.nombre AS NombreUsuarioCreador FROM caja_chica chica INNER JOIN usuario usuario ON chica.id_usuario=usuario.idusuario ";
        return ejecutarConsulta($sql);
    }





    public function detalle($Id)
    {
        $sql = "SELECT chica.Id_CajaChica,chica.IdUsuario,chica.fecha,chica.descripcion,chica.valor,chica.IdCajaDetalle,chica.tipo_comprobante as tipodocumento,usuario.idusuario,usuario.nombre AS NombreUsuarioCreador, usuario.tipo_documento,usuario.num_documento,usuario.direccion,usuario.telefono,usuario.email,chica.condicion   FROM caja_chica_detalle chica INNER JOIN usuario usuario ON chica.IdUsuario=usuario.idusuario  WHERE chica.Id_CajaChica='$Id' and chica.valor<>' '";
        return ejecutarConsulta($sql);
    }


    public function ventacabecera($Id)
    {
        $sql = "SELECT 
                v.Id,
                v.id_usuario,
                u.nombre as usuario,
                date(v.fecha) as fecha,
                v.valor as valorcaja,
                s.idsucursal,
                s.nombre as sucursal_nombre,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion 
            FROM caja_chica v 
            INNER JOIN usuario u ON v.id_usuario=u.idusuario 
            left JOIN sucursal s ON s.idsucursal=v.idsucursal
            WHERE v.Id='$Id'";
        return ejecutarConsulta($sql);
    }

    public function ventadetalle($Id)
    {
        $sql = "SELECT d.descripcion,d.fecha as fechaDetalle,d.IdCajaDetalle,d.IdUsuario,d.Id_CajaChica,d.tipo_comprobante,d.valor as valorDetalle from caja_chica_detalle d inner JOIN caja_chica c ON c.Id=d.Id_CajaChica where c.Id='$Id' and d.valor<>' '";
        return ejecutarConsulta($sql);
    }


}

?>