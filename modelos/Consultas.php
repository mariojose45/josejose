<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Consultas
{
    //Implementamos nuestro constructor
    public function __construct() {}

    public function countTicketsPendientes()
    {
        $idusuario = $_SESSION['idusuario'] ?? '';
        $idsucursal = $_SESSION['idsucursal'] ?? '';
        $sql = "SELECT COUNT(idlectura) as total FROM lectura WHERE estado='LECTURA' AND idsucursal='$idsucursal' AND idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function countTicketsCobrados()
    {
        $idusuario = $_SESSION['idusuario'] ?? '';
        $idsucursal = $_SESSION['idsucursal'] ?? '';
        $sql = "SELECT COUNT(idcobros_tickets) as total FROM cobros_tickets WHERE estado='Aceptado' AND DATE(fecha_cobro)=CURDATE() AND idsucursal='$idsucursal' AND idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    function validarnit($nit)
    {
        $url = 'http://api.fel.olintech.com/api/EcoFactura/receptorInfo'; //url de produccion
        $json = '{
              "cliente": "94398097",
              "usuario": "ADMIN",
              "clave": "Larecic1@d0r@20",
              "receptorId": "' . $nit . '", 
              "informacion": "string"
          }';
        $resultado = $this->callAPI("POST", $url, $json);

        $ArrayResultado = json_decode($resultado, true);

        return ($resultado);
    }

    public function validarnitapi($nit)
    {
        // 🔹 Paso 1: Llamar a la API FEL
        $url = 'http://api.fel.olintech.com/api/EcoFactura/receptorInfo';
        $json = '{
            "cliente": "94398097",
            "usuario": "ADMIN",
            "clave": "Larecic1@d0r@20",
            "receptorId": "' . $nit . '",
            "informacion": "string"
        }';

        $resultado = $this->callAPI("POST", $url, $json);
        $ArrayResultado = json_decode($resultado, true);

        // Extraer datos del FEL si existen
        $felNombre = $ArrayResultado['fel']['receptor']['nombre'] ?? '';
        $felDireccion = $ArrayResultado['fel']['receptor']['direccion'] ?? '';
        $felNit = $ArrayResultado['fel']['receptor']['nit'] ?? '';

        // 🔹 Paso 2: Buscar datos en la BD
        $sql = "SELECT * FROM persona WHERE num_documento = '$nit' LIMIT 1";
        $res = ejecutarConsultaSimpleFila($sql);

        // Si no existe en la base
        if (empty($res)) {
            return json_encode([
                "success" => true,
                "source" => "fel",
                "data" => [
                    "idpersona" => 0,
                    "nombre" => $felNombre ?: "N/D",
                    "num_documento" => $felNit ?: $nit,
                    "direccion" => $felDireccion ?: "N/D",
                    "telefono" => "",
                    "email" => "",
                    "tipo_cliente" => "PUBLICO",
                    "codigo_cliente" => "",
                ],
                "message" => "Cliente obtenido desde FEL (no existe en BD)"
            ]);
        }

        // 🔹 Paso 3: Combinar datos FEL + BD
        $idpersona = $res['idpersona'] ?? 0;
        $nombre = $felNombre ?: ($res['nombre'] ?? '');
        $direccion = $felDireccion ?: ($res['direccion'] ?? '');
        $telefono = $res['telefono'] ?? '';
        $email = $res['email'] ?? '';
        $tipo_cliente = $res['tipo_cliente'] ?? '';
        $codigo_cliente = $res['codigo_cliente'] ?? '';

        // 🔹 Paso 4: Estructura final del JSON
        $final = [
            "success" => true,
            "source" => "fel+bd",
            "data" => [
                "idpersona" => $idpersona,
                "nombre" => $nombre,
                "num_documento" => $felNit ?: $nit,
                "direccion" => $direccion,
                "telefono" => $telefono,
                "email" => $email,
                "tipo_cliente" => $tipo_cliente,
                "codigo_cliente" => $codigo_cliente,
            ],
            "message" => "Datos combinados FEL + BD"
        ];

        return json_encode($final, JSON_UNESCAPED_UNICODE);
    }




    public function validarnitNombre($nombrecliente)
    {
        $sqlPersonas = "SELECT * FROM persona 
    WHERE nombre like '%$nombrecliente%'   limit 1";
        $Persona = ejecutarConsultaSimpleFila($sqlPersonas);

        return ($Persona);
    }

    public function validarCodigo($codigo_cliente)
    {
        $sqlPersonas = "SELECT * FROM persona WHERE codigo_cliente like '%$codigo_cliente%'  limit 1";
        $Persona = ejecutarConsultaSimpleFila($sqlPersonas);

        return ($Persona);
    }

    public function validarxplacacarro($placa)
    {
        $sqlPlaca = "SELECT i.*,
                p.*,
                m.nombre AS nombre_marca,
                m.idmarca
                FROM ingreso_vehiculo i
                INNER JOIN persona p ON i.idcliente=p.idpersona
                INNER JOIN marca m ON m.idmarca=i.idvendedor
                WHERE i.no_placa like '%$placa%'  
                ORDER BY i.idingreso_vehiculo DESC
                limit 1";
        $resplaca = ejecutarConsultaSimpleFila($sqlPlaca);

        return ($resplaca);
    }



    public function buscarnitenSistemaparaIdcliente($nit)
    {
        $sqlPersonas = "SELECT * FROM persona WHERE num_documento like '%$nit%' limit 1";
        $Persona = ejecutarConsultaSimpleFila($sqlPersonas);

        return ($Persona);
    }


    public function totalefectivoiniciocaja()
    {

        $sql = "SELECT IFNULL(SUM(total_efectivo),0) as totalefectivo,tipo_operacion
        FROM cuadre_cajas WHERE DATE(fecha_hora_inicio)=curdate() 
        and tipo_operacion='APERTURA'  
        and idusuario='" . $_SESSION["idusuario"] . "' and idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }


    public function tipoCalculoDescuento()
    {

        $sql = "SELECT s.calculo_descuento
        FROM sucursal s WHERE  s.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }


    public function listarc()
    {
        $sql = "SELECT 
                p.*
             FROM persona  p 
             WHERE tipo_persona='Cliente' ORDER BY idpersona DESC";
        return ejecutarConsulta($sql);
    }





    //Implementar un método para listar los registros 
    public function listarmarca()
    {
        $sql = "SELECT 
            m.idmarca,
            m.nombre as nombre_marca
         FROM marca m  WHERE m.condicion=1";
        return ejecutarConsulta($sql);
    }




    function callAPI($method, $url, $data)
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
        // OPTIONS:
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
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        #echo "Status Code: ".$http_status;
        if (!$result) {
            die("Status Code" . $http_status . " Error:" . curl_error($curl) . " Connection Failure");
        }
        curl_close($curl);
        return $result;
    }


    public function ventasxfechaxmes($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
        DATE_FORMAT(fecha_hora,'%M') as fecha,
        SUM(total_venta) as total 
        FROM venta where estado ='Aceptado' and DATE(fecha_hora)>='$fecha_inicio' AND DATE(fecha_hora)<='$fecha_fin'
GROUP by MONTH(fecha_hora)";
        return ejecutarConsulta($sql);
    }

    public function comprasfecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                DATE(i.fecha_hora) as fecha,
                u.nombre as usuario,
                p.nombre as proveedor,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                i.total_compra,
                i.impuesto,
                i.estado,
                i.forma_pago,
                IFNULL((SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario = i.idusuario_update LIMIT 1), '0') AS usuariomod,
                i.fecha_modificacion,
                s.nombre as nombre_sucursal
            FROM ingreso i 
            INNER JOIN persona p ON i.idproveedor=p.idpersona 
            INNER JOIN usuario u ON i.idusuario=u.idusuario 
            inner join sucursal s on s.idsucursal=i.idsucursal
            WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' ";
        return ejecutarConsulta($sql);
    }

    public function comprasfechaDetalle($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
                i.idingreso,
                DATE(i.fecha_hora) as fecha,
                u.nombre as usuario,
                p.nombre as proveedor, 
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                i.total_compra,
                i.impuesto,
                i.estado,
                i.forma_pago,
                IFNULL((SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario = i.idusuario_update LIMIT 1), '0') AS usuariomod,
                i.fecha_modificacion,
                s.nombre as nombre_sucursal,
                a.codigo,
                a.nombre AS nombre_articulo,
                di.cantidad, 
                di.precio_compra,
                di.precio_venta,
                di.descuento_porcentaje,
                di.stock_inventario,
                ROUND(di.cantidad * (di.precio_compra - ((di.precio_compra * di.descuento_porcentaje) / 100)), 2) AS subtotal,
                asu.aplica_impuestos,
                asu.producto_consignacion
            FROM ingreso i 
            INNER JOIN detalle_ingreso di ON di.idingreso=i.idingreso
            INNER JOIN articulo a ON a.idarticulo=di.idarticulo
            inner join articuloxsucursal asu on di.idarticulo=asu.idarticulo
            INNER JOIN persona p ON i.idproveedor=p.idpersona 
            INNER JOIN usuario u ON i.idusuario=u.idusuario 
            inner join sucursal s on s.idsucursal=i.idsucursal
            WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin'  and asu.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }


    public function comprasfechaxsucursal($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                DATE(i.fecha_hora) as fecha,
                u.nombre as usuario,
                p.nombre as proveedor,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                i.total_compra,
                i.impuesto,
                i.estado,
                i.forma_pago,
                IFNULL((SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario = i.idusuario_update LIMIT 1), '0') AS usuariomod,
                i.fecha_modificacion,
                s.nombre as nombre_sucursal
            FROM ingreso i 
            INNER JOIN persona p ON i.idproveedor=p.idpersona 
            INNER JOIN usuario u ON i.idusuario=u.idusuario 
            inner join sucursal s on s.idsucursal=i.idsucursal
            WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' and i.idsucursal='$idsucursal' ";
        return ejecutarConsulta($sql);
    }


    public function comprasfechaxsucursalDetalle($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                i.idingreso, 
                DATE(i.fecha_hora) as fecha,
                u.nombre as usuario,
                p.nombre as proveedor,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                i.total_compra,
                i.impuesto,
                i.estado,
                i.forma_pago,
                IFNULL((SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario = i.idusuario_update LIMIT 1), '0') AS usuariomod,
                i.fecha_modificacion,
                s.nombre as nombre_sucursal,
                a.codigo,
                a.nombre AS nombre_articulo,
                di.cantidad,
                di.precio_compra,
                di.precio_venta,
                di.descuento_porcentaje,
                di.stock_inventario,
                di.totalcantidadpresentacion,
                date(di.fechavencimiento) AS fechavencimiento,
                ROUND(di.cantidad * (di.precio_compra - ((di.precio_compra * di.descuento_porcentaje) / 100)), 2) AS subtotal,
                asu.aplica_impuestos,
                asu.producto_consignacion
            FROM ingreso i 
            INNER JOIN detalle_ingreso di ON di.idingreso=i.idingreso
            INNER JOIN articulo a ON a.idarticulo=di.idarticulo
            inner join articuloxsucursal asu on asu.idarticulo=di.idarticulo
            INNER JOIN persona p ON i.idproveedor=p.idpersona 
            INNER JOIN usuario u ON i.idusuario=u.idusuario 
            inner join sucursal s on s.idsucursal=i.idsucursal
            WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' and i.idsucursal='$idsucursal' and asu.idsucursal='$idsucursal'  ";
        return ejecutarConsulta($sql);
    }


    public function rptarticuloscomprados($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
            SUM(d.cantidad) AS cant,
            a.codigo,
            a.nombre AS articulo,
            c.nombre AS cate
         FROM detalle_ingreso d
         INNER JOIN ingreso i ON i.idingreso=d.idingreso
         INNER JOIN articulo a ON a.idarticulo=d.idarticulo
         INNER JOIN categoria c ON c.idcategoria=a.idcategoria
         WHERE DATE(i.fecha_hora)>='$fecha_inicio' 
         AND DATE(i.fecha_hora)<='$fecha_fin' 
         and i.estado='Aceptado' AND i.idsucursal='$idsucursal'
         GROUP BY d.idarticulo  ";
        return ejecutarConsulta($sql);
    }


    public function rptarticulosvendidos($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
            SUM(d.cantidad) AS cant,
            a.codigo,
            a.nombre AS articulo,
            c.nombre AS cate
         FROM detalle_venta d
         INNER JOIN venta v ON v.idventa=d.idventa
         INNER JOIN articulo a ON a.idarticulo=d.idarticulo
         INNER JOIN categoria c ON c.idcategoria=a.idcategoria
         WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' and v.estado='Aceptado' and v.idsucursal='$idsucursal'
         GROUP BY d.idarticulo";
        return ejecutarConsulta($sql);
    }

    public function rptarticulosvendidosxcliente($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
            SUM(d.cantidad) AS cant,
            a.codigo,
            a.nombre AS articulo,
            c.nombre AS cate,
            p.nombre AS cliente
         FROM detalle_venta d
         INNER JOIN venta v ON v.idventa=d.idventa
         INNER JOIN persona p ON p.idpersona=v.idcliente
         INNER JOIN articulo a ON a.idarticulo=d.idarticulo
         INNER JOIN categoria c ON c.idcategoria=a.idcategoria
         WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' 
         and v.estado='Aceptado' and v.idsucursal='$idsucursal'
         GROUP BY d.idarticulo,v.idcliente";
        return ejecutarConsulta($sql);
    }



    public function rptarticulosvendidosxproveedor($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
            SUM(d.cantidad) AS cant,
            a.codigo,
            a.nombre AS articulo,
            c.nombre AS cate,
             p.nombre AS cliente
         FROM detalle_ingreso d
         INNER JOIN ingreso i ON i.idingreso=d.idingreso
         INNER JOIN persona p ON p.idpersona=i.idproveedor
         INNER JOIN articulo a ON a.idarticulo=d.idarticulo
         INNER JOIN categoria c ON c.idcategoria=a.idcategoria
         WHERE DATE(i.fecha_hora)>='$fecha_inicio' 
         AND DATE(i.fecha_hora)<='$fecha_fin' 
         and i.estado='Aceptado' AND i.idsucursal='$idsucursal'
         GROUP BY d.idarticulo,i.idproveedor";
        return ejecutarConsulta($sql);
    }



    public function ctaxcobrarfecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE(v.fechapago) as fecha,u.nombre as usuario, p.nombre as cliente,
        v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado 
        FROM venta v 
        INNER JOIN persona p ON v.idcliente=p.idpersona 
        INNER JOIN usuario u ON v.idusuario=u.idusuario  
        WHERE DATE(v.fechapago)>='$fecha_inicio' AND DATE(v.fechapago)<='$fecha_fin'";
        return ejecutarConsulta($sql);
    }

    public function ctaxcobrarfechapendientes($fecha_fin)
    {
        $sql = "SELECT DATE(v.fecha_hora_cobro) as fechacobro,
                DATE(v.fechapago) as fecha,
                u.nombre as usuario, 
                p.nombre as cliente,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,v.total_venta,v.impuesto,v.estado 
                FROM venta v 
                INNER JOIN persona p ON v.idcliente=p.idpersona 
                INNER JOIN usuario u ON v.idusuario=u.idusuario 
                WHERE DATE(v.fecha_hora_cobro)<='$fecha_fin' 
                and v.estadopago <>'Pago Aplicado' 
                and v.estado<>'Anulado' 
                ORDER by v.fecha_hora_cobro DESC";

        return ejecutarConsulta($sql);
    }

    public function ventasfechacliente($fecha_inicio, $fecha_fin, $idcliente)
    {
        $sql = "SELECT DATE(v.fecha_hora) as fecha,
                u.nombre as usuario, 
                p.nombre as cliente,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.impuesto,
                v.estado 
            FROM venta v 
            INNER JOIN persona p ON v.idcliente=p.idpersona 
            INNER JOIN usuario u ON v.idusuario=u.idusuario 
            WHERE DATE(v.fecha_hora)>='$fecha_inicio' 
            AND DATE(v.fecha_hora)<='$fecha_fin' 
            AND v.idcliente='$idcliente'";
        return ejecutarConsulta($sql);
    }

    public function totalcomprahoy($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND i.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT IFNULL(SUM(i.total_compra),0) as total_compra 
        FROM ingreso i
        inner join usuario u on u.idusuario=i.idusuario
        WHERE DATE(i.fecha_hora)=curdate()  and i.estado<>'Anulado' " . $filtro;
        return ejecutarConsulta($sql);
    }

    public function sucursal()
    {
        $sql = "SELECT s.nombre as nombre_sucursal from sucursal s where  s.idsucursal='" . $_SESSION["idsucursal"] . "'";
        return ejecutarConsulta($sql);
    }

    public function totalcotizaconesGeneral()
    {
        $sql = "SELECT 
                c.tipo_comprobante,
                COUNT(*) AS total_cotizaciones
            FROM cotizacion c
            WHERE c.cobradosino = 'NO' AND c.tipo_comprobante='cotizacion'
            GROUP BY c.tipo_comprobante";
        return ejecutarConsulta($sql);
    }

    public function totalcotizaconesGeneralDetalle()
    {
        $sql = "SELECT 
                c.idcotizacion,
                p.nombre,
                c.total_venta
            FROM cotizacion c
            INNER JOIN persona p ON p.idpersona=c.idcliente
            WHERE c.cobradosino = 'NO' AND c.tipo_comprobante='cotizacion'";
        return ejecutarConsulta($sql);
    }


    public function totalcotizaconesGeneral2()
    {
        $sql = "SELECT 
                c.tipo_comprobante,
                COUNT(*) AS total_cotizaciones
            FROM cotizacion c
            WHERE c.cobradosino = 'NO' AND c.tipo_comprobante='Cotizacion Tienda'
            GROUP BY c.tipo_comprobante";
        return ejecutarConsulta($sql);
    }

    public function totalcotizaconesGeneral2Detalle()
    {
        $sql = "SELECT 
                c.idcotizacion,
                p.nombre,
                c.total_venta
            FROM cotizacion c
            INNER JOIN persona p ON p.idpersona=c.idcliente
            WHERE c.cobradosino = 'NO' AND c.tipo_comprobante='Cotizacion Tienda'";
        return ejecutarConsulta($sql);
    }

    public function totalventahoy($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT IFNULL(SUM(total_venta),0) as total_venta 
        FROM venta WHERE DATE(fecha_hora)=curdate() 
        AND estado='Aceptado'  and tipo_operacion<>'CIERRE' and idusuario='" . $_SESSION["idusuario"] . "' " . $filtro;
        return ejecutarConsulta($sql);
    }


    public function totalventahoyxUsuario()
    {

        $sql = "SELECT IFNULL(SUM(total_venta),0) as total_venta 
        FROM venta 
        WHERE DATE(fecha_hora)=curdate() 
        AND estado='Aceptado'  and tipo_operacion<>'CIERRE'
        and idsucursal='" . $_SESSION["idsucursal"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function totalventahoyefectivo()
    {

        $sql = "SELECT IFNULL(SUM(cefectivo-rescambio),0) as total_venta 
        FROM venta 
        WHERE DATE(fecha_hora)=curdate() AND estado='Aceptado'  
        and tipo_operacion<>'CIERRE'  
        and idusuario='" . $_SESSION["idusuario"] . "'  ";
        return ejecutarConsulta($sql);
    }

    public function totalventahoycredito2()
    {
        $sql = "SELECT IFNULL(SUM(ccredito),0) as total_venta 
        FROM venta 
        WHERE DATE(fecha_hora)=curdate() AND estado='Aceptado'  
        and tipo_operacion<>'CIERRE'  AND forma_pago IN ('Credito') 
        and idusuario='" . $_SESSION["idusuario"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function totalventahoyTarjeta2()
    {
        $sql = "SELECT IFNULL(SUM(ctarjeta),0) as total_venta 
        FROM venta 
        WHERE DATE(fecha_hora)=curdate() 
        AND estado='Aceptado'  
        and tipo_operacion<>'CIERRE'  
        AND forma_pago IN ('Tarjeta','Efectivo/Tarjeta') 
        and idusuario='" . $_SESSION["idusuario"] . "' ";
        return ejecutarConsulta($sql);
    }


    public function GastosVenta()
    {
        $sql = "SELECT IFNULL(SUM(c.valor_q),0) as total_venta 
        FROM compras c  
        WHERE DATE(c.fecha_creacion)=curdate() 
        AND condicion='1'  
        and tipo_operacion='APERTURA' 
        and idusuario='" . $_SESSION["idusuario"] . "' ";
        return ejecutarConsulta($sql);
    }


    public function NCVenta()
    {
        $sql = "SELECT IFNULL(SUM(nc.total_venta),0) as total_venta 
        FROM nota_credito nc 
        WHERE DATE(nc.fecha_creacion)=curdate() 
        AND estado='Aceptado'  
        and tipo_operacion='APERTURA' 
        and idusuario='" . $_SESSION["idusuario"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function AbonosCtaCobrarVenta()
    {
        $sql = "SELECT IFNULL(SUM(c.total_abono),0) as total_venta 
        FROM cta_cobrar c  
        WHERE DATE(c.fecha_creacion)=curdate()        
        AND condicion='1'  
        and tipo_operacion='APERTURA' 
        and idusuario='" . $_SESSION["idusuario"] . "' ";
        return ejecutarConsulta($sql);
    }

    public function totalventahoytransferencia()
    {
        $sql = "SELECT IFNULL(SUM(ctransferencia),0) as total_venta 
        FROM venta 
        WHERE DATE(fecha_hora)=curdate() 
        AND estado='Aceptado'  
        and tipo_operacion<>'CIERRE'  
        AND forma_pago IN ('Transferencia') 
        and idusuario='" . $_SESSION["idusuario"] . "'  ";
        return ejecutarConsulta($sql);
    }

    public function totalventahoyCredito()
    {

        $sql = "SELECT IFNULL(SUM(total_venta),0) as total_venta 
            FROM venta 
            WHERE DATE(fecha_hora)=curdate() 
            AND estado='Aceptado'  and tipo_operacion<>'CIERRE' and forma_pago='Credito'
            AND idusuario='" . $_SESSION["idusuario"] . "'";
        return ejecutarConsulta($sql);
    }


    public function totalventahoyTarjeta()
    {

        $sql = "SELECT IFNULL(SUM(total_venta),0) as total_venta 
            FROM venta 
            WHERE DATE(fecha_hora)=curdate() 
            AND estado='Aceptado'  and tipo_operacion<>'CIERRE' and forma_pago='Tarjeta'
            AND idusuario='" . $_SESSION["idusuario"] . "'";
        return ejecutarConsulta($sql);
    }



    public function totalventaCobrar($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND v.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT 
                COUNT(v.idventa) as numerodeitems,
                SUM(v.saldo_venta) as totalcobrar,
                IFNULL(SUM(c.total_abono),0) AS total_abonos
                FROM venta v
                LEFT JOIN cta_cobrar c
                ON c.idventa = v.idventa 
                WHERE v.estadopago <>'Pago Aplicado' 
                and v.estado<>'Anulado' 
                and v.forma_pago='Credito' " . $filtro;
        return ejecutarConsulta($sql);
    }

    public function listarDetalleCtasporCobrar($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND v.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT 
                v.idventa,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_abono,
                v.saldo_venta,
                v.condicion, 
                v.estado,
                v.idcliente,
                v.numero_pagos,
                p.nombre as nombre_cliente, 
                p.telefono as telefono_cliente, 
                v.numero_boleta,
                DATE(v.fechapago) as fechapago,
                v.estadopago, 
                DATE(v.fecha_hora) as fecha,
                v.tipo_banco,
                v.recibo_caja_numero,
                v.numero_ecoFactura
                FROM venta v 
                INNER JOIN persona p  ON v.idcliente=p.idpersona 
                INNER JOIN usuario u ON u.idusuario=v.idusuario
                WHERE v.estado <> 'Anulado' 
                AND v.forma_pago='Credito' 
                AND v.estadopago <> 'Pago Aplicado' " . $filtro . " 
                ORDER BY v.idventa DESC";
        return ejecutarConsulta($sql);
    }

    public function totalcompraPagar($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND i.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT 
                COUNT(i.idingreso) as numerodeitems,
                SUM(i.saldo_ingreso) as totalpagar,
                IFNULL(SUM(c.valor_pagar),0) AS total_pagos
                FROM ingreso i
                LEFT JOIN cta_pagar c ON c.idingreso = i.idingreso 
                WHERE i.estadopago <> 'Pago Aplicado' 
                AND i.estado <> 'Anulado' 
                AND i.forma_pago = 'Credito' " . $filtro;
        return ejecutarConsulta($sql);
    }

    public function listarDetalleCtasporPagar($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND i.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT 
                i.idingreso,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                i.total_compra,
                i.valor_pagar,
                i.saldo_ingreso,
                i.condicion, 
                i.estado,
                i.idproveedor,
                p.nombre as nombre_proveedor, 
                p.telefono as telefono_proveedor, 
                DATE(i.fecha_hora) as fecha,
                i.estadopago
                FROM ingreso i 
                INNER JOIN persona p ON i.idproveedor=p.idpersona 
                INNER JOIN usuario u ON u.idusuario=i.idusuario
                WHERE i.estado <> 'Anulado' 
                AND i.forma_pago='Credito' 
                AND i.estadopago <> 'Pago Aplicado' " . $filtro . " 
                ORDER BY i.idingreso DESC";
        return ejecutarConsulta($sql);
    }

    public function totalventaM($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT DATE_FORMAT(fecha_hora,'%M') as fecha,
        SUM(total_venta) as total_venta FROM venta 
        where estado ='Aceptado' and Month(fecha_hora)=month(now()) AND  
        YEAR(fecha_hora)=YEAR(now()) " . $filtro;
        return ejecutarConsulta($sql);
    }

    public function capitalRecuperadoM($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND v.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT IFNULL(SUM(dv.totalcantidadpresentacion * dv.precio_compra), 0) as totalcapital 
                FROM venta v
                INNER JOIN detalle_venta dv ON v.idventa = dv.idventa
                WHERE MONTH(v.fecha_hora) = MONTH(CURDATE()) 
                AND YEAR(v.fecha_hora) = YEAR(CURDATE()) 
                AND v.estado = 'Aceptado' " . $filtro;

        return ejecutarConsulta($sql);
    }

    public function totalventaMxUsuario()
    {

        $sql = "SELECT DATE_FORMAT(fecha_hora,'%M') as fecha,
        SUM(total_venta) as total_venta FROM venta 
        where estado ='Aceptado' and Month(fecha_hora)=month(now()) AND  
        YEAR(fecha_hora)=YEAR(now()) and  idusuario='" . $_SESSION["idusuario"] . "'  ";
        return ejecutarConsulta($sql);
    }


    public function comprasultimos_10dias($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND idsucursal = '$idsucursal_filtro' ";
        } else {
            $filtro = " AND idsucursal='" . $_SESSION["idsucursal"] . "' ";
        }

        $sql = "SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) as fecha,SUM(total_compra) as total 
        FROM ingreso 
        where 1=1 " . $filtro . "
        GROUP by fecha_hora ORDER BY fecha_hora DESC limit 0,10";
        return ejecutarConsulta($sql);
    }

    public function ventasultimos_12meses($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND idsucursal = '$idsucursal_filtro' ";
        } else {
            $filtro = " AND idsucursal='" . $_SESSION["idsucursal"] . "' ";
        }

        $sql = "SELECT DATE_FORMAT(fecha_hora,'%M') as fecha,
            SUM(total_venta) as total 
        FROM venta 
        where estado ='Aceptado' " . $filtro . "
        GROUP by MONTH(fecha_hora) 
        ORDER BY fecha_hora DESC limit 0,12 ";
        return ejecutarConsulta($sql);
    }

    public function ventascomparativas($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND idsucursal = '$idsucursal_filtro' ";
        } else {
            $filtro = " AND idsucursal='" . $_SESSION["idsucursal"] . "' ";
        }

        $sql = "SELECT 
                    YEAR(fecha_hora) as anio,
                    MONTH(fecha_hora) as mes_num,
                    SUM(total_venta) as total 
                FROM venta 
                WHERE estado ='Aceptado' " . $filtro . "
                AND YEAR(fecha_hora) IN (YEAR(CURDATE()), YEAR(CURDATE())-1)
                GROUP BY YEAR(fecha_hora), MONTH(fecha_hora)
                ORDER BY YEAR(fecha_hora) ASC, MONTH(fecha_hora) ASC";
        return ejecutarConsulta($sql);
    }

    public function ventasultimos_12mesesxUsuario()
    {

        $sql = "SELECT DATE_FORMAT(fecha_hora,'%M') as fecha,
            SUM(total_venta) as total 
        FROM venta 
        where estado ='Aceptado' and idusuario='" . $_SESSION["idusuario"] . "' 
        GROUP by MONTH(fecha_hora) 
        ORDER BY fecha_hora DESC limit 0,12 ";
        return ejecutarConsulta($sql);
    }

    public function ventasvrsMetaxUsuario()
    {

        $sql = "SELECT DATE_FORMAT(v.fecha_hora,'%M') as fecha,
            SUM(v.total_venta) as total,
            u.meta,
            u.comision,
            (SUM(v.total_venta)*u.comision)/100 as comision_usuario
        FROM venta v
        inner join usuario u on u.idusuario=v.idusuario
        WHERE v.estado ='Aceptado' and u.idusuario='" . $_SESSION["idusuario"] . "' 
        GROUP by MONTH(v.fecha_hora) 
        ORDER BY v.fecha_hora DESC limit 0,12 ";
        return ejecutarConsulta($sql);
    }
    public function clientesnuevosultimos_12meses($idsucursal_filtro = "")
    {
        $filtro = "";
        // Nota: persona table currently doesn't seem to have idsucursal in this query.
        // We'll leave it as is if it doesn't apply, or apply if it exists.
        // The original query didn't have idsucursal filter.
        $sql = "SELECT DATE_FORMAT(fechaCreacion,'%M') as fecha,
            COUNT(idpersona) as total
        FROM persona 
        where condicion ='1' AND tipo_persona='Cliente'
        GROUP by MONTH(fechaCreacion) 
        ORDER BY fechaCreacion DESC limit 0,12";
        return ejecutarConsulta($sql);
    }

    public function proveedornuevosultimos_12meses($idsucursal_filtro = "")
    {
        $sql = "SELECT DATE_FORMAT(fechaCreacion,'%M') as fecha,
            COUNT(idpersona) as total
        FROM persona 
        where condicion ='1' AND tipo_persona='Proveedor'
        GROUP by MONTH(fechaCreacion) 
        ORDER BY fechaCreacion DESC limit 0,12";
        return ejecutarConsulta($sql);
    }


    public function personanuevosultimos_12meses($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT DATE_FORMAT(fechaCreacion,'%M') as fecha,
            COUNT(idpersona) as total
        FROM persona 
        where condicion ='1' " . $filtro . "
        GROUP by MONTH(fechaCreacion) 
        ORDER BY fechaCreacion DESC limit 0,12";
        return ejecutarConsulta($sql);
    }

    public function Articulosultimos_10dias($idsucursal_filtro = "")
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND v.idsucursal = '$idsucursal_filtro' ";
        } else {
            $filtro = " AND v.idsucursal='" . $_SESSION["idsucursal"] . "' ";
        }

        $sql = "SELECT 
        a.nombre as articulos,
        SUM(dv.cantidad) as total
        FROM detalle_venta dv
        INNER JOIN articulo a ON a.idarticulo = dv.idarticulo
        inner join venta v on v.idventa=dv.idventa
        where v.estado='Aceptado' " . $filtro . "
        GROUP BY a.idarticulo
        ORDER BY total DESC, dv.idventa DESC
        LIMIT 10;";
        return ejecutarConsulta($sql);
    }


    public function Articulosultimos_10diasxUsuario()
    {

        $sql = "SELECT a.nombre as articulos, 
            SUM(dv.cantidad) AS total 
        FROM detalle_venta dv
        INNER JOIN articulo a ON a.idarticulo = dv.idarticulo
        inner join venta v on v.idventa=dv.idventa
        where v.estado='Aceptado' and v.idusuario='" . $_SESSION["idusuario"] . "'
        GROUP BY a.idarticulo
        ORDER BY total DESC, dv.idventa DESC
        LIMIT 10;";
        return ejecutarConsulta($sql);
    }



    public function InventarioxSucursal($idsucursal_filtro)
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND asu.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT 
        IFNULL(SUM(asu.stocksucursal*asu.precio_compra),0) AS total_compra,
        IFNULL(SUM(asu.stocksucursal*asu.precio_venta),0) AS total_venta,
        s.nombre AS sucursal
        FROM articulo a
        INNER JOIN articuloxsucursal asu ON a.idarticulo=asu.idarticulo
        INNER JOIN sucursal s ON s.idsucursal=asu.idsucursal
        WHERE asu.condicion=1 " . $filtro . "
        GROUP BY s.idsucursal   ";
        return ejecutarConsulta($sql);
    }


    public function VentasxSucusal($idsucursal_filtro)
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND v.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT  
        sum(v.total_venta) AS total_venta,
        s.nombre AS sucursal
        FROM venta v  
        LEFT JOIN sucursal s ON s.idsucursal=v.idsucursal
        where v.estado='Aceptado'    
        and DATE(v.fecha_hora)=curdate() " . $filtro . "
        GROUP BY s.idsucursal   ";
        return ejecutarConsulta($sql);
    }


    public function totalVentasMG()
    {

        $sql = "SELECT  
        DATE_FORMAT(v.fecha_hora,'%M') as fecha,
        sum(v.total_venta) AS total_venta
        FROM venta v 
        where v.estado='Aceptado'
        and Month(v.fecha_hora)=month(now()) AND  YEAR(v.fecha_hora)=YEAR(now())
        order by  v.idventa desc                     ";
        return ejecutarConsulta($sql);
    }

    public function VentasxSucusalMes($idsucursal_filtro)
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND v.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT  
        DATE_FORMAT(v.fecha_hora,'%M') as fecha,
        sum(v.total_venta) AS total_venta,
        s.nombre AS sucursal
        FROM venta v 
        INNER JOIN sucursal s ON s.idsucursal=v.idsucursal
        where v.estado='Aceptado'
        and Month(v.fecha_hora)=month(now()) AND  
          YEAR(v.fecha_hora)=YEAR(now()) " . $filtro . "
        GROUP BY s.idsucursal                     ";
        return ejecutarConsulta($sql);
    }

    public function VentasxSucusalMesGanacia($idsucursal_filtro)
    {
        $filtro = "";
        if (!empty($idsucursal_filtro)) {
            $filtro = " AND v.idsucursal = '$idsucursal_filtro' ";
        }

        $sql = "SELECT 
            DATE_FORMAT(v.fecha_hora,'%M') AS fecha,
            SUM(dv.subtotal1) AS subtotalventa,
            SUM(dv.cantidad * dv.precio_compra) AS subtotalcompra,
            SUM(
                CASE 
                WHEN dv.subtotal1 = 0 THEN 0
                ELSE dv.subtotal1 - (dv.cantidad * dv.precio_compra)
                END
            ) AS ganancia,
            SUM(
                CASE 
                WHEN dv.subtotal1 = 0 THEN dv.cantidad * dv.precio_compra
                ELSE 0
                END
            ) AS costo_promociones,
            s.nombre AS nom_sucursal
            FROM detalle_venta dv
            INNER JOIN venta v ON v.idventa = dv.idventa
            INNER JOIN sucursal s ON s.idsucursal = v.idsucursal
            WHERE v.estado = 'Aceptado'
            AND MONTH(v.fecha_hora) = MONTH(NOW())
            AND YEAR(v.fecha_hora) = YEAR(NOW()) " . $filtro . "
            GROUP BY s.idsucursal   ";
        return ejecutarConsulta($sql);
    }



    public function ventasxfecha($fecha_inicio, $fecha_fin, $idsucursal)
    {
        // Si seleccionó TODOS, no filtramos por sucursal
        $filtroSucursal = "";

        if ($idsucursal != "TODOS") {
            $filtroSucursal = "  v.idsucursal='$idsucursal' ";
        }

        $sql = "SELECT  
                v.idventa,
                DATE(v.fecha_hora) AS fecha,
                v.idcliente,
                p.nombre AS cliente,
                u.idusuario,
                u.nombre AS usuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_ventades,
                v.impuesto,
                v.estado,
                v.cefectivo,
                v.rescambio,
                v.forma_pago,
                v.tipo_pagoBacVisaNet,
                v.opcionesAdicionales,
                ROUND(v.valor_tarjeta, 2) AS valor_tarjeta,
                v.ctarjeta,
                v.ccredito,
                v.nombre_vendedor,
                v.autorizacionEcoFactura,
                v.serie_ecoFactura,
                v.numero_ecoFactura,
                v.fechaCertificacion_ecoFactura
            FROM venta v
            INNER JOIN persona p 
                ON v.idcliente = p.idpersona
            INNER JOIN usuario u 
                ON v.idusuario = u.idusuario
            WHERE 
                $filtroSucursal
                AND DATE(v.fecha_hora) >= '$fecha_inicio'
                AND DATE(v.fecha_hora) <= '$fecha_fin'
            ORDER BY v.idventa DESC";

        return ejecutarConsulta($sql);
    }


    public function ventasxfechaRestaurante($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                v.idventa,
                DATE(v.fecha_hora) as fecha,
                v.idcliente,
                p.nombre as cliente,
                u.idusuario,
                u.nombre as usuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_ventades,
                v.impuesto,
                v.estado,
                v.cefectivo,
                v.rescambio,
                v.forma_pago, 
                v.tipo_pagoBacVisaNet,
                v.opcionesAdicionales,
                ROUND(v.valor_tarjeta, 2) AS valor_tarjeta,  -- Redondeamos valor_tarjeta a 2 decimales
                v.ctarjeta,
                v.ccredito,
                v.nombre_vendedor,
                v.autorizacionEcoFactura, 
                v.serie_ecoFactura,
                v.numero_ecoFactura, 
                v.fechaCertificacion_ecoFactura,
                v.fecha_creacion,
                odd.add_fecha_hora,
                odd.num_comprobante as num_correlativomesa,
                odd.propina,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=odd.idusuario) AS mesero,
                (SELECT m1.nombre FROM mesa m1 WHERE m1.idmesa=odd.idmesa) AS mesas           
            FROM venta v 
            INNER JOIN persona p ON v.idcliente=p.idpersona 
            INNER JOIN usuario u ON v.idusuario=u.idusuario
            INNER JOIN add_orden odd ON odd.id_add_orden=v.id_add_orden
            where v.idsucursal='$idsucursal' and v.estado='Aceptado'
            and  DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin'
            order by  v.idventa DESC";
        return ejecutarConsulta($sql);
    }



    public function ventasxfecha_anuladas($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                v.idventa,
                DATE(v.fecha_hora) as fecha,
                v.idcliente,
                p.nombre as cliente,
                u.idusuario,
                u.nombre as usuario,
                v.tipo_comprobante,
                v.serie_comprobante,
                v.num_comprobante,
                v.total_venta,
                v.total_ventades,
                v.impuesto,
                v.estado,
                v.cefectivo,
                v.rescambio,
                v.forma_pago,
                v.tipo_pagoBacVisaNet,
                v.opcionesAdicionales,
                ROUND(v.valor_tarjeta, 2) AS valor_tarjeta,  -- Redondeamos valor_tarjeta a 2 decimales
                v.ctarjeta,
                v.ccredito,
                v.nombre_vendedor,
                v.autorizacionEcoFactura, 
                v.serie_ecoFactura,
                v.numero_ecoFactura, 
                v.fechaCertificacion_ecoFactura
            FROM venta v 
            INNER JOIN persona p ON v.idcliente=p.idpersona 
            INNER JOIN usuario u ON v.idusuario=u.idusuario
            where v.idsucursal='$idsucursal' and v.estado='Anulado'
            and  DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin'
            order by  v.idventa DESC ";
        return ejecutarConsulta($sql);
    }




    public function ventasxfechaxproductoComprasVentas($fecha_inicio, $fecha_fin, $idsucursal, $codigo_pro)
    {
        $sql = " SELECT 
        o.idoperaciones_compras_ventas,
        o.idingreso,
        o.idventa,
        o.idtraladosucursal,
        o.idtraladosucursal_entrada,
        o.iddevolucion,
        o.cantidad_compras,
        o.cantidad_ventas,
        o.cantidad_entrada,
        o.cantidad_devolucion,
        o.cantidad_salida,
        o.stock_inventario,
        o.fecha_horaCreacion,
        a.descripcion,
        asu.stocksucursal,
        o.idnota_debito,
        o.cantidad_notaDebito,
        o.estado
        FROM operaciones_compras_ventas o
        INNER JOIN articulo a ON a.idarticulo=o.idarticulo
        INNER JOIN articuloxsucursal asu ON asu.idarticulo=a.idarticulo
        WHERE DATE(o.fecha_add)>='$fecha_inicio' 
        AND DATE(o.fecha_add)<='$fecha_fin' 
        AND o.idsucursal='$idsucursal' AND asu.idsucursal='$idsucursal'
        and a.codigo LIKE '%$codigo_pro%' ";
        //print_r($sql);
        return ejecutarConsulta($sql);
    }




    public function ventasxfechaAgrupadas($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                u.idusuario,
                u.nombre as usuario,
                sum(v.total_venta) as totalventa,
                sum(v.total_ventades) AS totalventaDes
            FROM venta v 
            INNER JOIN persona p ON v.idcliente=p.idpersona 
            INNER JOIN usuario u ON v.idusuario=u.idusuario
            where v.idsucursal='$idsucursal' and v.estado='Aceptado'
            and  DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin'
            GROUP BY v.idusuario";
        return ejecutarConsulta($sql);
    }


    public function ordenesxfechaAnuladasSucursal($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                odd.id_add_orden,
                odd.add_fecha_hora,
                odd.delete_fecha_hora,
                odd.idusuario_delete,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=odd.idusuario_delete LIMIT 1) AS usuario_elimino,
                odd.idmesa,
                (SELECT m.nombre FROM mesa m WHERE m.idmesa=odd.idmesa LIMIT 1) AS nombre_mesa,
                odd.idusuario,
                (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=odd.idusuario LIMIT 1)  AS usuario_creacion,
                odd.total_venta,
                odd.motivo,
                odd.estado
                FROM add_orden odd 
                WHERE odd.estado='Anulado' 
                and DATE(odd.fecha_hora)>='$fecha_inicio' 
                AND DATE(odd.fecha_hora)<='$fecha_fin' and odd.idsucursal='$idsucursal' ";
        return ejecutarConsulta($sql);
    }

    public function ordenesxfechaAnuladasSucursaldetalle($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                d.id_detalle_add_ordenanulacion,
                ad.add_fecha_hora,
                d.delete_fecha_hora,
                d.idusuario,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=d.idusuario LIMIT 1) AS usuario_elimino,
                d.idmesa,
                (SELECT m.nombre FROM mesa m WHERE m.idmesa=d.idmesa LIMIT 1) AS nombre_mesa,
                d.idcliente,
                d.idusuario_orden,
                (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=d.idusuario_orden LIMIT 1)  AS usuario_creacion,
                d.idarticulo,
                a.nombre AS articulos,
                d.cantidad,
                d.valormotivo,
                d.estado
                FROM detalle_add_ordenanulacion d 
                INNER JOIN add_orden ad ON ad.id_add_orden=d.id_add_orden
                INNER JOIN articulo a ON a.idarticulo=d.idarticulo
                WHERE  DATE(d.delete_fecha_hora)>='$fecha_inicio' 
                AND DATE(d.delete_fecha_hora)<='$fecha_fin' 
                and d.idsucursal='$idsucursal' ";
        return ejecutarConsulta($sql);
    }


    public function listarFactransportexfecha($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                v.idventa,
                DATE(v.fecha_hora) AS fecha,
                t.nombre AS nombre_transporte,
                v.guia_transporte,
                v.tipo_comprobante,
                v.estadoguia,
                v.estado_venta,
                COALESCE(dc.fechaventa, '0000-00-00') AS fechaventa,  -- Fecha en caso de NULL
                COALESCE(v.total_venta, 0) AS total_venta,
                COALESCE(dc.comision, 0) AS comision,
                COALESCE(dc.vcomision, 0) AS vcomision,
                COALESCE(dc.mliquido, 0) AS mliquido,
                COALESCE(dc.autorizacion, 0) AS autorizacion,
                COALESCE(dc.ctabanco, 0) AS ctabanco,
                COALESCE(dc.vflete, 0) AS vflete,
                COALESCE(dc.subtotal, 0) AS subtotal
             FROM venta v 
             INNER JOIN transporte t ON t.idtransporte=v.idtransporte
             LEFT JOIN detalle_cotejamientoventas_guias dc ON dc.idventa=v.idventa
                WHERE  DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' and v.idsucursal='$idsucursal' ";
        return ejecutarConsulta($sql);
    }

    //Clientes nuevos
    public function clientesnuevosxfecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE(fechaCreacion) as fecha, COUNT(*) as total 
                FROM persona 
                WHERE tipo_persona='Cliente' 
                AND DATE(fechaCreacion) BETWEEN '$fecha_inicio' AND '$fecha_fin'
                GROUP BY DATE(fechaCreacion)
                ORDER BY DATE(fechaCreacion)";
        return ejecutarConsulta($sql);
    }

    public function seguimientosxfecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE(fecha) as fecha, COUNT(*) as total 
                FROM seguimiento 
                WHERE DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'
                GROUP BY DATE(fecha)
                ORDER BY DATE(fecha)";
        return ejecutarConsulta($sql);
    }

    public function clientes_ventas($fecha_inicio, $fecha_fin, $idpersona)
    {
        $sql = "SELECT
            v.total_venta,
            v.total_ventades,
            v.tipo_comprobante,
            v.forma_pago,
            DATE(v.fecha_hora) AS fecha,
            p.nombre
        FROM venta v
        INNER JOIN persona p ON v.idcliente = p.idpersona
        WHERE p.idpersona = '$idpersona'
        AND DATE(v.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin'
        AND v.estado = 'Aceptado'
        ORDER BY v.fecha_hora DESC";
        return ejecutarConsulta($sql);
    }

    public function GenerarSolicitudPedidoTb()
    {
        $sql = "SELECT 
                asu.idarticulo,
                a.nombre,
                c.nombre AS categoria,
                a.idsubcategoria,
                sc.nombre AS subcategoria,
                a.descripcion,
                a.imagen,
                a.codigo,
                a.tipo_producto,
                a.idusuario,
                a.aplica_comision,
                asu.idsucursal,
                ROUND(asu.stocksucursal, 2) AS stock,
                ROUND(asu.stockminimo, 2) AS stockminimo,
                ROUND((asu.stockminimo - asu.stocksucursal), 2) AS cantidad_faltante,
                ROUND(asu.precio_compra, 2) AS precio_compra,
                ROUND(asu.precio_venta, 2) AS precio_venta,
                ROUND(asu.precio_ventaNocturno, 2) AS precio_ventaNocturno,
                ROUND(asu.descuento_porcentaje, 2) AS descuento_porcentaje,
                ROUND(asu.precio_descuento, 2) AS precio_descuento,
                ROUND(asu.precio_rango1, 2) AS precio_rango1,
                ROUND(asu.precio_rango1_Dos, 2) AS precio_rango1_Dos,
                ROUND(asu.precio_rango2, 2) AS precio_rango2,
                ROUND(asu.precio_rango2_Dos, 2) AS precio_rango2_Dos,
                ROUND(asu.precio_rango3, 2) AS precio_rango3,
                ROUND(asu.precio_rango3_Dos, 2) AS precio_rango3_Dos,
                ROUND(asu.precio_rango1_Mecanico, 2) AS precio_rango1_Mecanico,
                ROUND(asu.precio_rango2_MecanicoDos, 2) AS precio_rango2_MecanicoDos,
                ROUND(asu.precio_rango3_MecanicoTres, 2) AS precio_rango3_MecanicoTres,
                ROUND(asu.precio_rango1_Distribuidor, 2) AS precio_rango1_Distribuidor,
                ROUND(asu.precio_rango2_DistribuidorDos, 2) AS precio_rango2_DistribuidorDos,
                ROUND(asu.precio_rango3_DistribuidorTres, 2) AS precio_rango3_DistribuidorTres,
                ROUND(asu.precio_rango1_Mayorista, 2) AS precio_rango1_Mayorista,
                ROUND(asu.precio_rango2_MayoristaDos, 2) AS precio_rango2_MayoristaDos,
                ROUND(asu.precio_rango3_MayoristaTres, 2) AS precio_rango3_MayoristaTres,
                ROUND(asu.stock_unidad, 2) AS stock_unidad,
                ROUND(asu.precio_unidad, 2) AS precio_unidad,
                ROUND(asu.stock_blister, 2) AS stock_blister,
                ROUND(asu.precio_blister, 2) AS precio_blister,
                ROUND(asu.stock_caja, 2) AS stock_caja,
                ROUND(asu.precio_caja, 2) AS precio_caja,
                ROUND(asu.stock_fardo, 2) AS stock_fardo,
                ROUND(asu.precio_fardo, 2) AS precio_fardo,
                ROUND(asu.stock_sacos, 2) AS stock_sacos,
                ROUND(asu.precio_sacos, 2) AS precio_sacos,
                ROUND(asu.stock_paquete, 2) AS stock_paquete,
                ROUND(asu.precio_paquete, 2) AS precio_paquete,
                asu.precio_activado,
                asu.fecha_creacion,
                a.idusuario,
                (SELECT u1.nombre FROM usuario u1 WHERE u1.idusuario=asu.idusuario LIMIT 1) AS user_creacion,
                asu.fecha_update as fecha_modificacion,
                a.idusuario_update,
                (SELECT u2.nombre FROM usuario u2 WHERE u2.idusuario=asu.idusuario_update LIMIT 1) AS user_mod,
                asu.condicion,
                s.nombre as nombreSucursal,
                asu.descripcion_2,
                a.facturar_cero,
                a.tipo_descuento,
                asu.producto_consignacion,
                asu.aplica_impuestos,
                asu.idarticuloxsucursal,
                
                -- Aquí integras los días restantes por fecha de vencimiento más antigua
                v.fecha_vencimiento,
                DATEDIFF(CURDATE(), STR_TO_DATE(v.fecha_vencimiento, '%Y-%m-%d')) AS dias_vencimiento

            FROM articulo a  
            INNER JOIN articuloxsucursal asu ON a.idarticulo = asu.idarticulo
            INNER JOIN categoria c ON c.idcategoria = a.idcategoria
            INNER JOIN subcategoria sc ON sc.idsubcategoria = a.idsubcategoria
            INNER JOIN sucursal s ON s.idsucursal = asu.idsucursal

            -- LEFT JOIN con subconsulta de vencimiento más antiguo por artículo y sucursal
            LEFT JOIN (
                SELECT idarticulo, idsucursal, fecha_vencimiento
                FROM operaciones_compras_ventas
                WHERE (idingreso > 0 OR idtraladosucursal_entrada > 0)
                AND estado = 'Aceptado'
                AND saldo > 0
                GROUP BY idarticulo, idsucursal
            ) AS v ON v.idarticulo = asu.idarticulo AND v.idsucursal = asu.idsucursal
                where   asu.idsucursal='$_SESSION[idsucursal]' and   asu.condicion='1' 
                AND asu.stocksucursal < asu.stockminimo";
        $rspta = ejecutarConsulta($sql);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    public function rpt_registro_ingresoEmpleados($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
			r.idregistro_app,
			e.idempleado,
			r.foto,
			e.nombres,
			e.puesto,
			r.fecha,
			r.tipo_salida_entrada,
			r.condicion,
			e.codigo
		from registro_app r 
		INNER JOIN empleados e ON e.codigo=r.codigo
		WHERE   DATE(r.fecha)>='$fecha_inicio' AND DATE(r.fecha)<='$fecha_fin'
            order by  r.idregistro_app DESC ";
        return ejecutarConsulta($sql);
    }

    public function distribucionEfectivoParqueo()
    {
        $sql = "SELECT 
            IFNULL(SUM(c.total_venta),0) AS T_Venta,
            IFNULL(SUM(c.cefectivo - c.rescambio),0) AS T_efectivo,
            IFNULL(SUM(c.ctransferencia),0) AS T_transferencia,
            IFNULL(SUM(c.ccredito),0) AS T_credito,
            IFNULL(SUM(c.ctarjeta),0) AS T_tarjeta
        FROM cobros_tickets c 
        WHERE c.idsucursal='" . $_SESSION["idsucursal"] . "'  AND c.idusuario='" . $_SESSION["idusuario"] . "'  
        AND c.estado='Aceptado' AND c.tipo_operacion='APERTURA' ";
        return ejecutarConsulta($sql);
    }
}
