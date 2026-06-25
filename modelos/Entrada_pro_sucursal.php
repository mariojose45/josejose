<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Entradaprosucursal
{
    //Implementamos nuestro constructor
    public function __construct() {}

    //Implementamos un método para insertar registros
    public function insertar(
        $idtraladosucursal,
        $idsucursal,
        $fecha_hora,
        $descripcion_salida_producto,
        $idusuario,
        $idsucursalingreso,
        $idsucursalorigen,
        $idsucursaldestino,
        $nombresucursaldestino,
        $datosArticulos
    ) {

        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');


        $sql = "INSERT INTO traslado_sucursal_entrada (idtraladosucursal,fecha_hora,descripcion_entrada_producto,
        idusuario,idsucursalingreso,idsucursalorigen,
        idsucursaldestino,estado,nombresucursaldestino)
        VALUES ('$idtraladosucursal','$fecha_hora','$descripcion_salida_producto','$idusuario','$idsucursalingreso','$idsucursalorigen',
        '$idsucursaldestino','INGRESO PRODUCTO','$nombresucursaldestino')";
        // return ejecutarConsulta($sql);
        $idtraladosucursal_entradanew = ejecutarConsulta_retornarID($sql);
        //print_r($sql);


        $num_elementos = 0;
        $sw = true;


        if ($idtraladosucursal_entradanew) {
            $articulos = $datosArticulos['articulos'];
            $numArticulos = count($articulos['idarticulo']);
            for ($i = 0; $i < $numArticulos; $i++) {
                $idarticulo = $articulos['idarticulo'][$i];
                $cantidadpresentacion = $articulos['cantidadpresentacion'][$i];
                $cantidad = $articulos['cantidad'][$i];
                $totalcantidadpresentacion = $articulos['totalcantidadpresentacion'][$i];
                $presentacion = $articulos['presentacion'][$i];
                $descripcion_detalle = $articulos['descripcion_detalle'][$i];
                $precio_venta = $articulos['precio_venta'][$i];
                $fecha_vencimiento = $articulos['fecha_vencimiento'][$i];

                $sqlArticulo1 = "SELECT 
                            asu.precio_compra as pc_anterior, 
                            asu.stocksucursal 
                        FROM articuloxsucursal asu
                    WHERE asu.idarticulo='$idarticulo'  and asu.idsucursal='$idsucursaldestino' ";
                $Articulo1 = ejecutarConsultaSimpleFila($sqlArticulo1);
                $stocksucursal_anterior = $Articulo1["stocksucursal"];

                $sql_detalle = "INSERT INTO detalle_traslado_sucursal_entrada(idtraladosucursal_entrada, idarticulo,cantidad,
                descripcion_detalle,idsucursalorigen,idsucursaldestino,precio_venta,
                    cantidadpresentacion,totalcantidadpresentacion,presentacion,fecha_vencimiento) 
                VALUES ('$idtraladosucursal_entradanew','$idarticulo','$cantidad','$descripcion_detalle','$idsucursalorigen','$idsucursaldestino',
                '$precio_venta','$cantidadpresentacion','$totalcantidadpresentacion','$presentacion','$fecha_vencimiento')";
                ejecutarConsulta($sql_detalle) or $sw = false;
                //print_r($sql_detalle);

                $sqlArticuloStock = "UPDATE articuloxsucursal SET stocksucursal = stocksucursal - " . $totalcantidadpresentacion . " ,
                        precio_venta = " . $precio_venta . " ,precio_unidad=" . $precio_venta . "  WHERE idarticulo =$idarticulo    and idsucursal='$idsucursalorigen' ";
                ejecutarConsulta($sqlArticuloStock);

                $sql_proc = "CALL procesar_traslado_articuloEntrada($idtraladosucursal_entradanew, $idarticulo, $totalcantidadpresentacion, 
                        $idsucursalorigen, " . $_SESSION["idusuario"] . ",$fecha_vencimiento,$idsucursalorigen,$idsucursaldestino);";
                ejecutarConsulta($sql_proc);


                $sql_detalleoperaciones = "INSERT INTO operaciones_compras_ventas(idingreso,idventa,idtraladosucursal,idtraladosucursal_entrada,
                    iddevolucion,cantidad_compras,cantidad_ventas,
                    cantidad_entrada,cantidad_devolucion,cantidad_salida,stock_inventario,fecha_horaCreacion,idarticulo,idusuario,idsucursal,fecha_vencimiento,saldo) 
                    VALUES ('0','0','0','$idtraladosucursal_entradanew',
                    '0','0','0','$cantidad','0','0','$stocksucursal_anterior','$fechaHora','$idarticulo','$idusuario',
                    '" . $_SESSION["idsucursal"] . "','$fecha_vencimiento'," . $totalcantidadpresentacion . ")";
                ejecutarConsulta($sql_detalleoperaciones);
            }
        }

        $sqlDetalletrasladosucursalentrada = "SELECT * FROM detalle_traslado_sucursal_entrada WHERE idtraladosucursal_entrada='$idtraladosucursal_entradanew'";
        $Detalle = ejecutarConsulta($sqlDetalletrasladosucursalentrada);



        while ($reg = $Detalle->fetch_object()) {
            $sqlVerificacionExistencia = "SELECT * FROM articuloxsucursal WHERE idarticulo=" . $reg->idarticulo . " and idsucursal='$idsucursaldestino'";
            $EXIS = ejecutarConsulta($sqlVerificacionExistencia);
            $numexis = 0;
            #
            while ($reeeq = $EXIS->fetch_object()) {
                $updateArticuloDetalle = "UPDATE articuloxsucursal SET 
                stocksucursal=stocksucursal+" . $reg->totalcantidadpresentacion . ", 
                precio_venta=" . $reg->precio_venta . ",
                precio_unidad=" . $reg->precio_venta . "
                WHERE idarticulo=" . $reg->idarticulo . " and idsucursal='$idsucursaldestino' ";
                ejecutarConsulta($updateArticuloDetalle);
                $numexis++;
            }
            if ($numexis == 0) {
                $isertArticuloDetalle = "INSERT INTO articuloxsucursal(idarticulo,idsucursal,stocksucursal,precio_venta,precio_unidad,condicion,fecha_creacion) 
                VALUES(" . $reg->idarticulo . ",'$idsucursaldestino'," . $reg->cantidad . "," . $reg->precio_venta . "," . $reg->precio_venta . ",'1','$fechaHora') ";
                ejecutarConsulta($isertArticuloDetalle);
                //ACTUALIZAR TODOS SUS PRECIOS CON LA ORIGEN
                // Obtener los datos del artículo desde la sucursal origen para actualizar los precios en la sucursal destino
                $sqlOrigen = "SELECT * FROM articuloxsucursal WHERE idarticulo = " . $reg->idarticulo . " AND idsucursal = '$idsucursalorigen'";
                $EXISOrigen = ejecutarConsulta($sqlOrigen);

                if ($rowOrigen = $EXISOrigen->fetch_object()) {
                    // Actualizar todos los precios en la sucursal destino con los precios de la sucursal origen
                    $sqlArticuloxSucursalPrecios = "UPDATE articuloxsucursal SET 
                        idusuario = " . $_SESSION["idusuario"] . ",
                        stockminimo = " . $rowOrigen->stockminimo . ",
                        precio_compra = " . $rowOrigen->precio_compra . ",
                        precio_ventaNocturno = " . $rowOrigen->precio_ventaNocturno . ",
                        descuento_porcentaje = " . $rowOrigen->descuento_porcentaje . ",
                        precio_descuento = " . $rowOrigen->precio_descuento . ",
                        precio_rango1 = " . $rowOrigen->precio_rango1 . ",
                        precio_rango2 = " . $rowOrigen->precio_rango2 . ",
                        precio_rango3 = " . $rowOrigen->precio_rango3 . ",
                        stock_unidad = " . $rowOrigen->stock_unidad . ",
                        precio_unidad = " . $rowOrigen->precio_unidad . ",
                        stock_blister = " . $rowOrigen->stock_blister . ",
                        precio_blister = " . $rowOrigen->precio_blister . ",
                        stock_caja = " . $rowOrigen->stock_caja . ",
                        precio_caja = " . $rowOrigen->precio_caja . ",
                        stock_fardo = " . $rowOrigen->stock_fardo . ",
                        precio_fardo = " . $rowOrigen->precio_fardo . ",
                        stock_sacos = " . $rowOrigen->stock_sacos . ",
                        precio_sacos = " . $rowOrigen->precio_sacos . ", 
                        stock_paquete = " . $rowOrigen->stock_paquete . ",
                        precio_paquete = " . $rowOrigen->precio_paquete . ",
                        ganacia_articulo = " . $rowOrigen->ganacia_articulo . ",
                        producto_consignacion = " . $rowOrigen->producto_consignacion . ",
                        aplica_impuestos = " . $rowOrigen->aplica_impuestos . ",
                        tipo_ganacia = " . $rowOrigen->tipo_ganacia . ",
                        precio_rango1_Dos = " . $rowOrigen->precio_rango1_Dos . ",
                        precio_rango2_Dos = " . $rowOrigen->precio_rango2_Dos . ",
                        precio_rango3_Dos = " . $rowOrigen->precio_rango3_Dos . ",
                        precio_rango1_Mecanico = " . $rowOrigen->precio_rango1_Mecanico . ",
                        precio_rango2_MecanicoDos = " . $rowOrigen->precio_rango2_MecanicoDos . ",
                        precio_rango3_MecanicoTres = " . $rowOrigen->precio_rango3_MecanicoTres . ",
                        precio_rango1_Distribuidor = " . $rowOrigen->precio_rango1_Distribuidor . ",
                        precio_rango2_DistribuidorDos = " . $rowOrigen->precio_rango2_DistribuidorDos . ",
                        precio_rango3_DistribuidorTres = " . $rowOrigen->precio_rango3_DistribuidorTres . ",
                        precio_rango1_Mayorista = " . $rowOrigen->precio_rango1_Mayorista . ",
                        precio_rango2_MayoristaDos = " . $rowOrigen->precio_rango2_MayoristaDos . ",
                        precio_rango3_MayoristaTres = " . $rowOrigen->precio_rango3_MayoristaTres . ",
                        codigo_sku = " . $rowOrigen->codigo_sku . ",
                        stockmaximo = " . $rowOrigen->stockmaximo . ",
                        precio_activado = " . $rowOrigen->precio_activado . ",
                        descripcion_2 = " . $rowOrigen->descripcion_2 . "
                        WHERE idarticulo = " . $reg->idarticulo . " 
                        AND idsucursal = '$idsucursaldestino'";
                    ejecutarConsulta($sqlArticuloxSucursalPrecios);
                }
            }
        }

        $sqlUpdate = "UPDATE traslado_sucursal SET estado='PRODUCTO INGRESADO A SUCURSAL' WHERE idtraladosucursal='$idtraladosucursal'";
        ejecutarConsulta($sqlUpdate);

        $this->registrarAuditoria($idtraladosucursal_entradanew, 'CREACIÓN', 'Se registró el ingreso de productos a la sucursal', $idusuario, $datosArticulos);

        return $sw;
    }


    //Implementamos un método para anular la venta
    public function anular($idventa)
    {
        $sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
        return ejecutarConsulta($sql);
    }


    public function detalletraaladoparasucursal($idtraladosucursal)
    {

        $sqldetalle = "SELECT 
            dts.id_detalle_traslado_sucursal,
            dts.idtraladosucursal,
            dts.idarticulo,
            a.nombre as articulo,
            a.codigo,
            a.descripcion,
            dts.cantidad,
            dts.descripcion_detalle,
            dts.idsucursalorigen,
            dts.idsucursaldestino,
            dts.precio_venta,
            dts.cantidadpresentacion,
            dts.totalcantidadpresentacion,
            dts.presentacion
            FROM detalle_traslado_sucursal dts 
            INNER JOIN articulo a on a.idarticulo=dts.idarticulo
             where dts.idtraladosucursal=" . $idtraladosucursal;

        #echo $sql;
        $rspta = ejecutarConsulta($sqldetalle);
        $rows = array();
        while ($reg = $rspta->fetch_object()) {
            $rows[] = $reg;
        }
        return $rows;
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT 
                tse.idtraslado_sucursal_entrada,
                tse.idtraladosucursal,
                DATE(tse.fecha_hora) as fecha,
                tse.descripcion_entrada_producto,
                tse.idusuario,
                u.nombre as usuario,
                tse.idsucursalingreso, 
                tse.idsucursalorigen,
                (select s1.nombre from sucursal s1 where s1.idsucursal=tse.idsucursalingreso limit 0,1 ) as nombresucursaldestino,
                (select s2.nombre from sucursal s2 where s2.idsucursal=tse.idsucursalorigen limit 0,1 ) as nombresucursalorigen,
                tse.estado,
                tse.nombresucursaldestino as sucursaraingresarproducto
                FROM traslado_sucursal_entrada tse
                inner join usuario u on u.idusuario=tse.idusuario 
                where  u.idusuario='" . $_SESSION["idusuario"] . "'
                                ORDER by tse.idtraslado_sucursal_entrada DESC ";
        return ejecutarConsulta($sql);
    }


    public function entradaprosucursalcabecera($idtraslado_sucursal_entrada)
    {
        $sql = "SELECT 
                tse.idtraslado_sucursal_entrada,
                tse.idtraladosucursal,
                date(tse.fecha_hora)  as fecha,
                tse.descripcion_entrada_producto,
                tse.idusuario,
                u.nombre AS usuario,
                tse.idsucursalingreso,
                tse.idsucursalorigen,
                (select s1.nombre from sucursal s1 where s1.idsucursal=tse.idsucursalorigen limit 0,1 ) as nombresucursalorigen,
                tse.idsucursaldestino,
                (SELECT s2.nombre from sucursal s2 WHERE s2.idsucursal=tse.idsucursaldestino limit 0,1 ) as nombresucursaldestino,
                tse.estado,
                s.nombre as sucursal_nombre,
                s.imagen as sucursal_imagen,
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.email as sucursal_email,
                s.nit as sucursal_nit,
                s.nombre_fel,
                c.empresadesarrollo
                 FROM traslado_sucursal_entrada tse
                INNER JOIN usuario u ON u.idusuario=tse.idusuario
                INNER JOIN sucursal s ON s.idsucursal=tse.idsucursaldestino
                inner join certificador c on c.idsucursal=tse.idsucursaldestino
                 WHERE tse.idtraslado_sucursal_entrada='$idtraslado_sucursal_entrada' and c.condicion='1'";
        return ejecutarConsulta($sql);
    }

    public function entradaprosucursaltadetalle($idtraslado_sucursal_entrada)
    {
        $sql = "SELECT 
                dtse.iddetalle_traslado_sucursal_entrada,
                dtse.idtraladosucursal_entrada,
                dtse.idarticulo,
                dtse.cantidad,
                a.nombre as articulo,
                a.codigo,
                COALESCE(NULLIF(dtse.descripcion_detalle, ''), '.') as descripcion_detalle,
                dtse.idsucursalorigen,
                dtse.idsucursaldestino,
                COALESCE(NULLIF(dtse.presentacion, ''), 'UNIDAD') as presentacion,
                dtse.cantidadpresentacion,
                dtse.totalcantidadpresentacion,
                dtse.precio_venta
                 FROM detalle_traslado_sucursal_entrada dtse
                 INNER JOIN articulo a on a.idarticulo=dtse.idarticulo
             WHERE dtse.idtraladosucursal_entrada='$idtraslado_sucursal_entrada'";
        return ejecutarConsulta($sql);
    }


    public function selectSucursal()
    {
        $sql = "SELECT * FROM sucursal";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idtraladosucursal)
    {
        $sql = "SELECT 
        ts.idtraladosucursal,
        ts.idsucursaldestino,
        ts.idusuario,
        ts.idsucursalorigen,
        (select s1.nombre from sucursal s1 where s1.idsucursal=ts.idsucursaldestino limit 0,1 ) as nombresucursaldestino,        
        DATE(ts.fecha_hora) as fecha,
        ts.estado,
        ts.descripcion_salida_producto
        FROM traslado_sucursal ts
          WHERE ts.idtraladosucursal='$idtraladosucursal' 
          and estado='SALIDA PRODUCTO' ";
        return ejecutarConsultaSimpleFila($sql);
    }



    public function listarxfechasucursal($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                tse.idtraslado_sucursal_entrada,
                tse.idtraladosucursal,
                DATE(tse.fecha_hora) as fecha,
                tse.descripcion_entrada_producto,
                tse.idusuario,
                u.nombre as usuario,
                tse.idsucursalingreso, 
                tse.idsucursalorigen,
                (select s1.nombre from sucursal s1 where s1.idsucursal=tse.idsucursalingreso limit 0,1 ) as nombresucursaldestino,
                (select s2.nombre from sucursal s2 where s2.idsucursal=tse.idsucursalorigen limit 0,1 ) as nombresucursalorigen,
                tse.estado,
                tse.nombresucursaldestino as sucursaraingresarproducto
                FROM traslado_sucursal_entrada tse
                inner join usuario u on u.idusuario=tse.idusuario 
                where  tse.idsucursaldestino='$idsucursal' and
                DATE(tse.fecha_hora)>='$fecha_inicio' AND DATE(tse.fecha_hora)<='$fecha_fin'
               ORDER by tse.idtraslado_sucursal_entrada DESC";
        return ejecutarConsulta($sql);
    }

    public function listarxfechasucursalDetalle($fecha_inicio, $fecha_fin, $idsucursal)
    {
        $sql = "SELECT 
                tse.idtraslado_sucursal_entrada,
                tse.idtraladosucursal,
                DATE(tse.fecha_hora) as fecha,
                tse.descripcion_entrada_producto,
                tse.idusuario,
                u.nombre as usuario,
                tse.idsucursalingreso, 
                tse.idsucursalorigen,
                (select s1.nombre from sucursal s1 where s1.idsucursal=tse.idsucursalingreso limit 0,1 ) as nombresucursaldestino,
                (select s2.nombre from sucursal s2 where s2.idsucursal=tse.idsucursalorigen limit 0,1 ) as nombresucursalorigen,
                tse.estado,
                tse.nombresucursaldestino as sucursaraingresarproducto,
                a.codigo,
                CONCAT(a.nombre, ' ', asu.descripcion_2)  as articulo,
                d.cantidad,
                d.precio_venta,
                tse.estado,
                date(d.fecha_vencimiento) as fechavencimiento
                FROM traslado_sucursal_entrada tse
                INNER JOIN detalle_traslado_sucursal_entrada d ON d.idtraladosucursal_entrada=tse.idtraslado_sucursal_entrada
                inner join articulo a on a.idarticulo=d.idarticulo
                inner join articuloxsucursal asu on asu.idarticulo=d.idarticulo and asu.idsucursal=tse.idsucursaldestino
                inner join usuario u on u.idusuario=tse.idusuario 
                where  tse.idsucursaldestino='$idsucursal' and
                DATE(tse.fecha_hora)>='$fecha_inicio' AND DATE(tse.fecha_hora)<='$fecha_fin'
               ORDER by tse.idtraslado_sucursal_entrada DESC ";
        return ejecutarConsulta($sql);
    }

    // Método para registrar auditoría
    public function registrarAuditoria($idtraladosucursal_entrada, $accion, $descripcion, $idusuario, $datosArticulos = null)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s');

        // Convertimos el arreglo de artículos a formato de texto JSON para guardarlo
        $detalle_json = $datosArticulos ? json_encode($datosArticulos) : '';

        $sql = "INSERT INTO auditoria_traslado_sucursal_entrada (idtraladosucursal_entrada, accion, descripcion, detalle_articulos, idusuario, fecha_hora) 
                VALUES ('$idtraladosucursal_entrada', '$accion', '$descripcion', '$detalle_json', '$idusuario', '$fechaHora')";

        return ejecutarConsulta($sql);
    }
}
