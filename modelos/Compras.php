<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Compras
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar(
        $idcliente_GastoaVenta,
        $idusuario,
        $serie_no,
        $factura_no,
        $mes_a_contabilizar,
        $ano_contabilizar,
        $tipo_factura,
        $tipo_documento_cliente_GastoaVenta,
        $nit_no,
        $proveedor,
        $direccion,
        $fecha_hora,
        $valor_q,
        $tipo_compra
    ) {


        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
        if ($idcliente_GastoaVenta == '0') {
            $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];
            $codigo_cliente = 'COD' . $corre;

            $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre,
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono,
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente,
                                                    fechaCreacion)
                                            VALUES ('Cliente',
                                                    '$proveedor',
                                                    '$tipo_documento_cliente_GastoaVenta',
                                                    '$nit_no',
                                                    '$direccion',
                                                    '0',
                                                    'no@gmail.com',
                                                    'PUBLICO',
                                                    '$codigo_cliente','$fechaHora')";
            $residcliente = ejecutarConsulta_retornarID($sqlcliente);

            if (!$residcliente) {
                throw new Exception("Error al insertar nuevo cliente.");
            }
        } else {
            $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente_GastoaVenta'";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];

            // Verificamos si $corre es '0', está vacío o es null
            if (empty($corre) || $corre == '0') {
                // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente

                $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                ejecutarConsulta($sqlcorrelativo);

                $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                $corress = $correlativos["codigo_cliente"];
                $codigo_clientes = 'COD' . $corress;

                $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente_GastoaVenta'";
                ejecutarConsulta($sqlupdadtepersona);
            }

            $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion',
                                            telefono='0',
                                            email='$correo_cliente',
                                            tipo_documento='$tipo_documento_cliente_GastoaVenta',
                                            nombre='$proveedor'
                                    WHERE idpersona='$idcliente_GastoaVenta'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente_GastoaVenta;
        }
        ///////   

        $sql = "INSERT INTO compras (idusuario,serie_no,factura_no,mes_a_contabilizar,ano_contabilizar,tipo_factura,idpersona,fecha_hora,valor_q,tipo_compra,condicion,fecha_creacion,idsucursal)
        VALUES ('$idusuario','$serie_no','$factura_no','$mes_a_contabilizar','$ano_contabilizar','$tipo_factura','$residcliente','$fecha_hora','$valor_q','$tipo_compra','1','$fechaHora','" . $_SESSION["idsucursal"] . "')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar(
        $idcompra,
        $idcliente_GastoaVenta,
        $idusuario,
        $serie_no,
        $factura_no,
        $mes_a_contabilizar,
        $ano_contabilizar,
        $tipo_factura,
        $tipo_documento_cliente_GastoaVenta,
        $nit_no,
        $proveedor,
        $direccion,
        $fecha_hora,
        $valor_q,
        $tipo_compra
    ) {


        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
        if ($idcliente_GastoaVenta == '0') {
            $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            ejecutarConsulta($sqlcorrelativo);

            $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];
            $codigo_cliente = 'COD' . $corre;

            $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre,
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono,
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente,
                                                    fechaCreacion)
                                            VALUES ('Cliente',
                                                    '$proveedor',
                                                    '$tipo_documento_cliente_GastoaVenta',
                                                    '$nit_no',
                                                    '$direccion',
                                                    '0',
                                                    'no@gmail.com',
                                                    'PUBLICO',
                                                    '$codigo_cliente','$fechaHora')";
            $residcliente = ejecutarConsulta_retornarID($sqlcliente);

            if (!$residcliente) {
                throw new Exception("Error al insertar nuevo cliente.");
            }
        } else {
            $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente_GastoaVenta'";
            $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
            $corre = $correlativo["codigo_cliente"];

            // Verificamos si $corre es '0', está vacío o es null
            if (empty($corre) || $corre == '0') {
                // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente

                $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                ejecutarConsulta($sqlcorrelativo);

                $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
                $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                $corress = $correlativos["codigo_cliente"];
                $codigo_clientes = 'COD' . $corress;

                $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente_GastoaVenta'";
                ejecutarConsulta($sqlupdadtepersona);
            }

            $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion',
                                            telefono='0',
                                            email='no@gmail.com',
                                            tipo_documento='$tipo_documento_cliente_GastoaVenta',
                                            nombre='$proveedor'
                                    WHERE idpersona='$idcliente_GastoaVenta'";

            ejecutarConsulta($sqlcorrelativo);
            $residcliente = $idcliente_GastoaVenta;
        }
        ///////   


        $sql = "UPDATE compras 
            SET 
                idusuario_update = '$idusuario',
                serie_no = '$serie_no',
                factura_no = '$factura_no',
                mes_a_contabilizar = '$mes_a_contabilizar',
                ano_contabilizar = '$ano_contabilizar',
                tipo_factura = '$tipo_factura',
                idpersona = '$residcliente',
                fecha_hora = '$fecha_hora',
                valor_q = '$valor_q',
                tipo_compra = '$tipo_compra',
                fecha_update = '$fechaHora'
            WHERE idcompra = '$idcompra'";
        print_r($sql);
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idcompra)
    {
        $sql = "UPDATE compras SET condicion='0' WHERE idcompra='$idcompra'";
        return ejecutarConsulta($sql);
    }



    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcompra)
    {
        $sql = " SELECT c.*,
                DATE(c.fecha_hora) as fecha,
                p.nombre AS proveedor,
                p.direccion AS proveedor_direccion,
                p.tipo_documento,
                p.num_documento                
                FROM compras c 
                INNER JOIN persona p ON p.idpersona=c.idpersona
                 WHERE c.idcompra='$idcompra'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT 
                c.idusuario,
                u.nombre AS nombre_usuario,
                c.serie_no,
                c.factura_no,
                c.mes_a_contabilizar,
                c.ano_contabilizar,
                c.tipo_factura,
                c.nit_no,
                c.proveedor,
                c.idpersona,
                p.nombre AS nombre_persona,
                c.fecha_hora,
                c.valor_q,
                c.tipo_compra,
                c.condicion,
                c.tipo_comprobante,
                c.fecha_creacion,
                c.tipo_operacion,
                c.concepto_fac,
                c.idcompra
            FROM compras c
            INNER JOIN usuario u ON u.idusuario=c.idusuario
            INNER JOIN persona p ON p.idpersona=c.idpersona ";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql = "SELECT * FROM compras where condicion=1";
        return ejecutarConsulta($sql);
    }
}

?>