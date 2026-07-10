<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Persona
{
    //Implementamos nuestro constructor
    public function __construct() {}

    //Implementamos un método para insertar registros
    public function insertar(
        $tipo_persona,
        $nombre,
        $tipo_documento,
        $num_documento,
        $direccion,
        $telefono,
        $email,
        $tipo_cliente,
        $trabajo,
        $idsector,
        $descuento_cliente
    ) {
        $sql = "INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email,tipo_cliente,trabajo,idsector,descuento_cliente)
        VALUES ('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$tipo_cliente','$trabajo','$idsector','$descuento_cliente')";
        return ejecutarConsulta($sql);
    }





    //Implementamos un método para editar registros
    public function editar(
        $idpersona,
        $tipo_persona,
        $nombre,
        $tipo_documento,
        $num_documento,
        $direccion,
        $telefono,
        $email,
        $tipo_cliente,
        $ubicacioncliente,
        $trabajo,
        $idsector,
        $descuento_cliente
    ) {
        $sql = "UPDATE persona SET tipo_persona='$tipo_persona',nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',
        telefono='$telefono',email='$email',tipo_cliente='$tipo_cliente',
        ubicacion_maps='$ubicacioncliente',trabajo='$trabajo',idsector='$idsector',descuento_cliente='$descuento_cliente'
        WHERE idpersona='$idpersona'";
        //print_r($sql);
        return ejecutarConsulta($sql);
    }


    public function insertarCliente(
        $tipo_persona,
        $nombre,
        $nombre_comercial,
        $tipo_documento,
        $num_documento,
        $direccion,
        $direccion_comercial,
        $telefono,
        $email,
        $trabajo,
        $idsector,
        $idruta,
        $tipo_cliente,
        $codigo_cliente,
        $ubicacioncliente,
        $descuento_cliente
    ) {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 
            WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
        ejecutarConsulta($sqlcorrelativo);

        $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='" . $_SESSION["idsucursal"] . "' ";
        $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
        $corre = $correlativo["codigo_cliente"];
        $codigocliente = 'COD' . $corre;

        $sql = "INSERT INTO persona (tipo_persona,nombre,nombre_comercial,tipo_documento,num_documento,direccion,direccion_comercial,
        telefono,email,trabajo,idsector,idruta,tipo_cliente,codigo_cliente,ubicacion_maps,descuento_cliente,condicion,fechaCreacion,idusuario,idsucursal)
        VALUES ('$tipo_persona','$nombre','$nombre_comercial','$tipo_documento','$num_documento','$direccion','$direccion_comercial',
        '$telefono','$email','$trabajo','$idsector','$idruta','$tipo_cliente','$codigocliente','$ubicacioncliente','$descuento_cliente','1','$fechaHora','" . $_SESSION["idusuario"] . "','" . $_SESSION["idsucursal"] . "')";
        return ejecutarConsulta($sql);
    }





    //Implementamos un método para editar registros
    public function editarCliente(
        $idpersona,
        $tipo_persona,
        $nombre,
        $nombre_comercial,
        $tipo_documento,
        $num_documento,
        $direccion,
        $direccion_comercial,
        $telefono,
        $email,
        $trabajo,
        $idsector,
        $idruta,
        $tipo_cliente,
        $codigo_cliente,
        $ubicacioncliente,
        $descuento_cliente
    ) {
        $sql = "UPDATE persona SET 
                        tipo_persona='$tipo_persona',
                        nombre='$nombre',
                        nombre_comercial='$nombre_comercial',
                        tipo_documento='$tipo_documento',
                        num_documento='$num_documento',
                        direccion='$direccion',
                        direccion_comercial='$direccion_comercial',
                        telefono='$telefono',
                        email='$email',
                        trabajo='$trabajo',
                        idsector='$idsector',
                        idruta='$idruta',
                        tipo_cliente='$tipo_cliente',
                        ubicacion_maps='$ubicacioncliente',
                        descuento_cliente='$descuento_cliente'
        WHERE idpersona='$idpersona'";
        //print_r($sql);
        return ejecutarConsulta($sql);
    }







    public function insertar2($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $tipo_cliente)
    {
        $sql = "INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email,tipo_cliente)
        VALUES ('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$tipo_cliente')";
        $new = ejecutarConsulta_retornarID($sql);
        $option = '<option value="' . $new . '" selected>' . $nombre . '---' . $direccion . '</option>';
        return $option;
    }

    public function insertar3($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $tipo_cliente)
    {
        $sql = "INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email,tipo_cliente)
        VALUES ('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$tipo_cliente')";
        $new = ejecutarConsulta_retornarID($sql);
        $option = '<option value="' . $new . '">' . $nombre . '</option>';
        return $option;
    }

    //Implementamos un método para eliminar categorías
    public function eliminar($idpersona)
    {
        $sql = "UPDATE persona SET condicion='0' WHERE idpersona='$idpersona'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idpersona)
    {
        $sql = "SELECT * FROM persona WHERE idpersona='$idpersona'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listarp()
    {
        $sql = "SELECT * FROM persona WHERE tipo_persona='Proveedor' ORDER BY idpersona ASC ";
        return ejecutarConsulta($sql);
    }

    public function listarp2()
    {
        $sql = "SELECT * FROM persona   ORDER BY idpersona ASC ";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros 
    public function listarc()
    {
        $sql = "SELECT 
            p.*,

            -- Total de ventas aceptadas
            IFNULL((
                SELECT SUM(v.total_venta) 
                FROM venta v
                WHERE v.idcliente = p.idpersona
                AND v.estado = 'Aceptado'
            ), 0) AS t_vendido,
            
            -- Última fecha de venta aceptada
            IFNULL((
                SELECT v.fecha_hora 
                FROM venta v
                WHERE v.idcliente = p.idpersona
                AND v.estado = 'Aceptado'
                ORDER BY v.fecha_hora DESC
                LIMIT 1
            ), 0) AS f_ultima_venta,    

            -- Último seguimiento
            IFNULL((
                SELECT s.fecha 
                FROM seguimiento s
                WHERE s.idcliente = p.idpersona
                ORDER BY s.fecha DESC
                LIMIT 1
            ), 0) AS f_ultimo_seguimiento,

            -- Última tarea asignada
            IFNULL((
                SELECT t.fecha_creacion
                FROM tarea t
                WHERE t.idcliente = p.idpersona
                ORDER BY t.fecha_creacion DESC
                LIMIT 1
            ), 0) AS f_ultima_tarea,

            -- Último evento
            IFNULL((
                SELECT e.fecha_evento
                FROM evento e
                WHERE e.idcliente = p.idpersona
                ORDER BY e.fecha_evento DESC
                LIMIT 1
            ), 0) AS f_ultimo_evento

        FROM persona p
        WHERE p.tipo_persona = 'Cliente'
        ORDER BY p.idpersona DESC";
        return ejecutarConsulta($sql);
    }


    public function listarProveedor()
    {
        $sql = "SELECT 
            p.*,

            -- Total de ventas aceptadas
            IFNULL((
                SELECT SUM(v.total_venta) 
                FROM venta v
                WHERE v.idcliente = p.idpersona
                AND v.estado = 'Aceptado'
            ), 0) AS t_vendido,
            
            -- Última fecha de venta aceptada
            IFNULL((
                SELECT v.fecha_hora 
                FROM venta v
                WHERE v.idcliente = p.idpersona
                AND v.estado = 'Aceptado'
                ORDER BY v.fecha_hora DESC
                LIMIT 1
            ), 0) AS f_ultima_venta,    

            -- Último seguimiento
            IFNULL((
                SELECT s.fecha 
                FROM seguimiento s
                WHERE s.idcliente = p.idpersona
                ORDER BY s.fecha DESC
                LIMIT 1
            ), 0) AS f_ultimo_seguimiento,

            -- Última tarea asignada
            IFNULL((
                SELECT t.fecha_creacion
                FROM tarea t
                WHERE t.idcliente = p.idpersona
                ORDER BY t.fecha_creacion DESC
                LIMIT 1
            ), 0) AS f_ultima_tarea,

            -- Último evento
            IFNULL((
                SELECT e.fecha_evento
                FROM evento e
                WHERE e.idcliente = p.idpersona
                ORDER BY e.fecha_evento DESC
                LIMIT 1
            ), 0) AS f_ultimo_evento

        FROM persona p
        where p.condicion=1
        ORDER BY p.idpersona DESC";
        return ejecutarConsulta($sql);
    }


    //Implementar un método para listar los registros 
    public function listarCp()
    {
        $sql = "SELECT * FROM persona ";
        return ejecutarConsulta($sql);
    }

    public function listarclientes()
    {
        $sql = "SELECT * FROM persona WHERE tipo_persona='Cliente' ";
        return ejecutarConsulta($sql);
    }

    public function guardaryeditarSeguimiento($idcliente_seguimiento, $tipo_seguimiento, $notas_seguimiento)
    {
        $sqlseguimiento = "INSERT INTO seguimiento (idcliente,fecha,tipo,notas,idusuario,idsucursal)
        VALUES ('$idcliente_seguimiento',NOW(),'$tipo_seguimiento','$notas_seguimiento','" . $_SESSION["idusuario"] . "','" . $_SESSION["idsucursal"] . "')";
        //print_r($sqlseguimiento);
        ejecutarConsulta($sqlseguimiento);
    }

    public function guardaryeditarTarea($idcliente_tarea, $tarea_titulo, $tarea_desc, $tarea_fecha_limite, $tarea_prioridad)
    {
        $sqltarea = "INSERT INTO tarea (idcliente,titulo,descripcion,fecha_limite,prioridad,idusuario,idsucursal,fecha_creacion)
        VALUES ('$idcliente_tarea','$tarea_titulo','$tarea_desc','$tarea_fecha_limite','$tarea_prioridad','" . $_SESSION['idusuario'] . "','" . $_SESSION["idsucursal"] . "',NOW())";
        //print_r($sqltarea);
        ejecutarConsulta($sqltarea);
    }

    public function guardaryeditarEvento($idcliente_evento, $evento_titulo, $evento_desc, $evento_fecha_evento, $evento_ubicacion)
    {
        $sqlenvento = "INSERT INTO evento (idcliente,titulo,descripcion,fecha_evento,ubicacion,idusuario,idsucursal)
        VALUES ('$idcliente_evento','$evento_titulo','$evento_desc','$evento_fecha_evento','$evento_ubicacion','" . $_SESSION['idusuario'] . "','" . $_SESSION["idsucursal"] . "')";
        //print_r($sqlenvento);
        ejecutarConsulta($sqlenvento);
    }

    public function listar_seguimiento_reporte($fecha_inicio_reporte, $fecha_fin_reporte, $idcliente)
    {
        $sql = "SELECT
            s.idseguimiento,
            s.idcliente,
            DATE(s.fecha) AS fecha,
            s.tipo,
            s.notas,
            s.idusuario,
            s.idsucursal,
            ss.nombre AS sucursal,
            p.nombre AS cliente,
            u.nombre AS usuario
        FROM seguimiento s
        INNER JOIN sucursal ss ON s.idsucursal = ss.idsucursal
        INNER JOIN persona p ON s.idcliente = p.idpersona
        INNER JOIN usuario u ON s.idusuario = u.idusuario
        WHERE DATE(s.fecha)>='$fecha_inicio_reporte' AND DATE(s.fecha)<='$fecha_fin_reporte'
        AND s.idcliente = '$idcliente'";
        return ejecutarConsulta($sql);
    }

    public function listar_seguimiento_reporte_rango($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT
            s.idseguimiento,
            s.idcliente,
            DATE(s.fecha) AS fecha,
            s.tipo,
            s.notas,
            s.idusuario,
            s.idsucursal,
            ss.nombre AS sucursal,
            p.nombre AS cliente,
            u.nombre AS usuario
        FROM seguimiento s
        INNER JOIN sucursal ss ON s.idsucursal = ss.idsucursal
        INNER JOIN persona p ON s.idcliente = p.idpersona
        INNER JOIN usuario u ON s.idusuario = u.idusuario
        WHERE DATE(s.fecha)>='$fecha_inicio_reporte' AND DATE(s.fecha)<='$fecha_fin_reporte' ";
        return ejecutarConsulta($sql);
    }


    public function listar_tareas_reporte($fecha_inicio_reporte, $fecha_fin_reporte, $idcliente)
    {
        $sql = "SELECT
            t.titulo,
            t.descripcion,
            DATE(t.fecha_limite) AS fecha_limite,
            DATE(t.fecha_creacion) AS fecha,
            t.prioridad,
            t.completado,
            ss.nombre AS sucursal,
            p.nombre AS cliente,
            u.nombre AS usuario
        FROM tarea t
        INNER JOIN sucursal ss ON t.idsucursal = ss.idsucursal
        INNER JOIN persona p ON t.idcliente = p.idpersona
        INNER JOIN usuario u ON t.idusuario = u.idusuario
        WHERE DATE(t.fecha_creacion)>='$fecha_inicio_reporte' AND DATE(t.fecha_creacion)<='$fecha_fin_reporte'
        AND t.idcliente = '$idcliente'";
        return ejecutarConsulta($sql);
    }



    public function listar_tareas_reporte_rango($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT
            t.titulo,
            t.descripcion,
            DATE(t.fecha_limite) AS fecha_limite,
            DATE(t.fecha_creacion) AS fecha,
            t.prioridad,
            t.completado,
            ss.nombre AS sucursal,
            p.nombre AS cliente,
            u.nombre AS usuario
        FROM tarea t
        INNER JOIN sucursal ss ON t.idsucursal = ss.idsucursal
        INNER JOIN persona p ON t.idcliente = p.idpersona
        INNER JOIN usuario u ON t.idusuario = u.idusuario
        WHERE DATE(t.fecha_creacion)>='$fecha_inicio_reporte' AND DATE(t.fecha_creacion)<='$fecha_fin_reporte' ";
        return ejecutarConsulta($sql);
    }

    public function listar_eventos_reporte($fecha_inicio_reporte, $fecha_fin_reporte, $idcliente)
    {
        $sql = "SELECT
                e.idevento,
                e.idcliente,
                e.titulo,
                e.descripcion,
                DATE(e.fecha_evento) AS fecha,
                e.ubicacion,
                ss.nombre AS sucursal,
                p.nombre AS cliente,
                u.nombre AS usuario
            FROM evento e
            INNER JOIN sucursal ss ON e.idsucursal = ss.idsucursal
            INNER JOIN persona p ON e.idcliente = p.idpersona
            INNER JOIN usuario u ON e.idusuario = u.idusuario
            WHERE DATE(e.fecha_evento)>='$fecha_inicio_reporte' AND DATE(e.fecha_evento)<='$fecha_fin_reporte'
            AND e.idcliente = '$idcliente'";
        return ejecutarConsulta($sql);
    }

    public function listar_eventos_reporte_rango($fecha_inicio_reporte, $fecha_fin_reporte)
    {
        $sql = "SELECT
                e.idevento,
                e.idcliente,
                e.titulo,
                e.descripcion,
                DATE(e.fecha_evento) AS fecha,
                e.ubicacion,
                ss.nombre AS sucursal,
                p.nombre AS cliente,
                u.nombre AS usuario
            FROM evento e
            INNER JOIN sucursal ss ON e.idsucursal = ss.idsucursal
            INNER JOIN persona p ON e.idcliente = p.idpersona
            INNER JOIN usuario u ON e.idusuario = u.idusuario
            WHERE DATE(e.fecha_evento)>='$fecha_inicio_reporte' AND DATE(e.fecha_evento)<='$fecha_fin_reporte' ";
        return ejecutarConsulta($sql);
    }

    public function agregar_fiador($idpersona_fiador, $idfiador)
    {
        $sql = "UPDATE persona SET idfiador='$idfiador' WHERE idpersona='$idpersona_fiador'";
        //print_r($sql);
        return ejecutarConsulta($sql);
    }

    public function selectSector()
    {
        $sql = "SELECT * FROM sector WHERE condicion = 1";
        return ejecutarConsulta($sql);
    }

    public function selectRuta()
    {
        $sql = "SELECT * FROM ruta_visita WHERE condicion = 1";
        return ejecutarConsulta($sql);
    }

    public function guardaryeditarModal(
        $tipo_persona_cliente,
        $nombre_cliente,
        $tipo_documento_cliente,
        $num_documento_cliente,
        $direccion_cliente,
        $telefono_cliente,
        $email_cliente,
        $tipo_cliente_cliente
    ) {
        $sql = "INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email,tipo_cliente)
        VALUES ('$tipo_persona_cliente','$nombre_cliente','$tipo_documento_cliente','$num_documento_cliente','$direccion_cliente','$telefono_cliente','$email_cliente','$tipo_cliente_cliente')";
        $new = ejecutarConsulta_retornarID($sql);
        $option = '<option value="' . $new . '" selected>' . $nombre_cliente . '---' . $direccion_cliente . '</option>';
        return $option;
    }



    public function validarnit($nit)
    {
        $url = 'http://api.fel.olintech.com/api/EcoFactura/receptorInfo';
        $json = '{
              "cliente": "94398097",
              "usuario": "ADMIN",
              "clave": "Larecic1@d0r@20",
              "receptorId": "' . $nit . '", 
              "informacion": "string"
          }';

        $resultado = $this->callAPI("POST", $url, $json);

        return $resultado;
    }

    private function callAPI($method, $url, $data)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30000);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (!$result) {
            return false;
        }
        curl_close($curl);
        return $result;
    }
}
