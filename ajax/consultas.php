<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Consultas.php";

$consulta = new Consultas();






switch ($_GET["op"]) {


    case 'validarnit':
        $nit = $_REQUEST["nit"];
        $rspta = $consulta->validarnit($nit);
        echo $rspta;
        break;

    case 'validarnitNombre':
        $nombre_cliente = $_REQUEST["nombre_cliente"];
        $rspta = $consulta->validarnitNombre($nombre_cliente);
        echo json_encode($rspta);
        break;


    case 'validarCodigo':
        $codigo_cliente = $_REQUEST["codigo_cliente"];
        $rspta = $consulta->validarCodigo($codigo_cliente);
        echo json_encode($rspta);
        break;


    case 'buscarnitenSistemaparaIdcliente':
        // print_r("ingreso a ajax de validar nit");
        $nit = $_REQUEST["nit"];
        $rspta = $consulta->buscarnitenSistemaparaIdcliente($nit);
        echo json_encode($rspta);
        break;


    case 'listartbBusquedaCliente':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
        $rspta = $persona->listarProveedor();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-block" onclick="RespuestavalidarnitNombre(' . $reg->idpersona . ',\'' . $reg->nombre . '\',
                \'' . $reg->num_documento . '\',\'' . $reg->direccion . '\',
                                    \'' . $reg->telefono . '\',
                                    \'' . $reg->email . '\',
                                    \'' . $reg->tipo_documento . '\',
                                    \'' . $reg->codigo_cliente . '\',
                                    \'' . $reg->tipo_cliente . '\',\'' . $reg->descuento_cliente . '\')"><span class="fa fa-plus"></span></button>',
                "1" => $reg->nombre,
                "2" => $reg->direccion,
                "3" => $reg->tipo_documento . ' / ' . $reg->num_documento,
                "4" => $reg->telefono
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;



    case 'listartbBusquedaProveedor':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
        $rspta = $persona->listarp();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-block" onclick="RespuestavalidarnitNombre(' . $reg->idpersona . ',\'' . $reg->nombre . '\',\'' . $reg->num_documento . '\',\'' . $reg->direccion . '\',
                                    \'' . $reg->telefono . '\',
                                    \'' . $reg->email . '\',
                                    \'' . $reg->tipo_documento . '\',
                                    \'' . $reg->codigo_cliente . '\')"><span class="fa fa-plus"></span></button>',
                "1" => $reg->nombre,
                "2" => $reg->direccion,
                "3" => $reg->tipo_documento . ' / ' . $reg->num_documento,
                "4" => $reg->telefono
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;




    case 'ventasxfechaxmes':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->ventasxfechaxmes($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->total,
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'comprasfecha':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->comprasfecha($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => $reg->proveedor,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->num_comprobante,
                "5" => $reg->serie_comprobante,
                "6" => $reg->total_compra,
                "7" => $reg->forma_pago,
                "8" => $reg->usuariomod,
                "9" => $reg->fecha_modificacion,
                "10" => $reg->nombre_sucursal,
                "11" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;


    case 'comprasfechaDetalle':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->comprasfechaDetalle($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => $reg->proveedor,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->num_comprobante,
                "5" => $reg->serie_comprobante,
                "6" => $reg->total_compra,
                "7" => $reg->forma_pago,
                "8" => $reg->usuariomod,
                "9" => $reg->fecha_modificacion,
                "10" => $reg->nombre_sucursal,
                "11" => $reg->codigo,
                "12" => $reg->nombre_articulo,
                "13" => $reg->stock_inventario,
                "14" => $reg->cantidad,
                "15" => $reg->precio_compra,
                "16" => $reg->precio_venta,
                "17" => $reg->descuento_porcentaje,
                "18" => $reg->producto_consignacion,
                "19" => $reg->aplica_impuestos,
                "20" => $reg->subtotal,
                "21" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "22" => $reg->idingreso
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'comprasfechaxsucursal':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->comprasfechaxsucursal($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => $reg->proveedor,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->num_comprobante,
                "5" => $reg->serie_comprobante,
                "6" => $reg->total_compra,
                "7" => $reg->forma_pago,
                "8" => $reg->usuariomod,
                "9" => $reg->fecha_modificacion,
                "10" => $reg->nombre_sucursal,
                "11" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'comprasfechaxsucursalDetalle':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->comprasfechaxsucursalDetalle($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => $reg->proveedor,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->num_comprobante,
                "5" => $reg->serie_comprobante,
                "6" => $reg->total_compra,
                "7" => $reg->forma_pago,
                "8" => $reg->usuariomod,
                "9" => $reg->fecha_modificacion,
                "10" => $reg->nombre_sucursal,
                "11" => $reg->fechavencimiento,
                "12" => $reg->codigo,
                "13" => $reg->nombre_articulo,
                "14" => $reg->stock_inventario,
                "15" => $reg->cantidad,
                "16" => $reg->totalcantidadpresentacion,
                "17" => $reg->precio_compra,
                "18" => $reg->precio_venta,
                "19" => $reg->descuento_porcentaje,
                "20" => $reg->subtotal,
                "21" => $reg->producto_consignacion,
                "22" => $reg->aplica_impuestos,
                "23" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>',
                "24" => $reg->idingreso
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;


    case 'InventarioxSucusal':

        $rspta = $consulta->InventarioxSucusal();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $data[] = array(
                "0" => $reg->sucursal,
                "1" => number_format($reg->total_compra, 2),  // Aplicar number_format
                "2" => number_format($reg->total_venta, 2)   // Aplicar number_format
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'VentasxSucusal':

        $rspta = $consulta->VentasxSucusal();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $data[] = array(
                "0" => $reg->sucursal,
                "1" => number_format($reg->total_venta, 2)   // Aplicar number_format
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'VentasxSucusalMes':

        $rspta = $consulta->VentasxSucusalMes();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $data[] = array(
                "0" => $reg->sucursal,
                "1" => $reg->fecha,
                "2" => number_format($reg->total_venta, 2)   // Aplicar number_format
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;



    case 'VentasxSucusalMesGanacia':

        $rspta = $consulta->VentasxSucusalMesGanacia();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $data[] = array(
                "0" => $reg->nom_sucursal,
                "1" => $reg->fecha,
                "2" => number_format($reg->subtotalventa, 2),  // Aplicar number_format
                "3" => number_format($reg->subtotalcompra, 2),   // Aplicar number_format
                "4" => number_format($reg->ganancia, 2),  // Aplicar number_format
                "5" => number_format($reg->costo_promociones, 2)   // Aplicar number_format
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;



    case 'rptarticuloscomprados':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->rptarticuloscomprados($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->cate,
                "1" => utf8_encode($reg->codigo),
                "2" => utf8_encode($reg->articulo),
                "3" => $reg->cant
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;



    case 'rptarticulosvendidos':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->rptarticulosvendidos($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->cate,
                "1" => utf8_encode($reg->codigo),
                "2" => utf8_encode($reg->articulo),
                "3" => $reg->cant
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'rptarticulosvendidosxcliente':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];


        $rspta = $consulta->rptarticulosvendidosxcliente($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->cate,
                "1" => utf8_encode($reg->codigo),
                "2" => utf8_encode($reg->articulo),
                "3" => $reg->cant,
                "4" => utf8_encode($reg->cliente)
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;



    case 'rptarticulosvendidosxproveedor':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];


        $rspta = $consulta->rptarticulosvendidosxproveedor($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->cate,
                "1" => utf8_encode($reg->codigo),
                "2" => utf8_encode($reg->articulo),
                "3" => $reg->cant,
                "4" => utf8_encode($reg->cliente)
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;



    case 'ctaxcobrarfecha':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->ctaxcobrarfecha($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => $reg->cliente,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->serie_comprobante . ' ' . $reg->num_comprobante,
                "5" => $reg->total_venta,
                "6" => $reg->impuesto,
                "7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'ctaxcobrarfechapendientes':
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->ctaxcobrarfechapendientes($fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fechacobro,
                "1" => $reg->fecha,
                "2" => $reg->usuario,
                "3" => $reg->cliente,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->serie_comprobante . ' ' . $reg->num_comprobante,
                "6" => $reg->total_venta,
                "7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;



    case 'ventasfechacliente':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idcliente = $_REQUEST["idcliente"];

        $rspta = $consulta->ventasfechacliente($fecha_inicio, $fecha_fin, $idcliente);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => $reg->cliente,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->serie_comprobante . ' ' . $reg->num_comprobante,
                "5" => $reg->total_venta,
                "6" => $reg->impuesto,
                "7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    /* case 'ventasxfecha':
         $fecha_inicio=$_REQUEST["fecha_inicio"];
         $fecha_fin=$_REQUEST["fecha_fin"];
         $idsucursal=$_REQUEST["idsucursal"]; 
         $rspta=$consulta->ventasxfecha($fecha_inicio,$fecha_fin,$idsucursal);
         //Vamos a declarar un array
         $data= Array();

         while ($reg=$rspta->fetch_object()){
                     if ($reg->estado=='Aceptado') {
             # code...
             $restotalventa=$reg->total_venta;
         }else
         {
             $restotalventa='0';
         }

             $data[]=array(
                 "0"=>$reg->fecha,
                 "1"=>$reg->usuario,
                 "2"=>$reg->cliente, 
                 "3"=>$reg->tipo_comprobante,
                 "4"=>$reg->idventa."  #: ".$reg->num_comprobante, 
                 "5"=>$restotalventa,
                 "6"=>$reg->impuesto,
                 "7"=>$reg->serie_ecoFactura,
                 "8"=>$reg->numero_ecoFactura,
                 "9"=>$reg->FechaEcoFact,
                 "10"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                 '<span class="label bg-red">Anulado</span>'
                 );
         }
         $results = array(
             "sEcho"=>1, //Información para el datatables
             "iTotalRecords"=>count($data), //enviamos el total registros al datatable
             "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
             "aaData"=>$data);
         echo json_encode($results);

     break;*/

    case 'ordenesxfechaAnuladasSucursal':

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->ordenesxfechaAnuladasSucursal($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $url = '../reportes/exTicket58mm_tomaOrden.php?id=';
            $data[] = array(
                "0" => '<a target="_blank" href="' . $url . $reg->id_add_orden . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->add_fecha_hora,
                "2" => $reg->delete_fecha_hora,
                "3" => $reg->usuario_elimino,
                "4" => $reg->nombre_mesa,
                "5" => $reg->usuario_creacion,
                "6" => $reg->total_venta,
                "7" => $reg->motivo,
                "8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;


    case 'ordenesxfechaAnuladasSucursaldetalle':

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->ordenesxfechaAnuladasSucursaldetalle($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $data[] = array(
                "0" => $reg->add_fecha_hora,
                "1" => $reg->delete_fecha_hora,
                "2" => $reg->usuario_elimino,
                "3" => $reg->nombre_mesa,
                "4" => $reg->usuario_creacion,
                "5" => $reg->articulos,
                "6" => $reg->cantidad,
                "7" => $reg->valormotivo,
                "8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;





    case 'ventasxfecha':

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->ventasxfecha($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                //  $url2='../reportes/exTicket.php?id=';   

            } else {
                $url = '../reportes/exTicket.php?id=';
                //  $url2='../reportes/exTicket.php?id='; 
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;

            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;

            } else {
                $resDatostarjeta = " ";
            }

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;


            $data[] = array(
                "0" => '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idventa,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->num_comprobante,
                "6" => $resventa,
                "7" => $resventades,
                "8" => $reg->forma_pago . " - " . $resDatostarjeta,
                "9" => $reg->cefectivo,
                "10" => $reg->ctarjeta,
                "11" => $reg->ccredito,
                "12" => $reg->rescambio,
                "13" => $reg->fecha,
                "14" => $fecha_certificacion,
                "15" => $reg->serie_ecoFactura,
                "16" => $reg->numero_ecoFactura,
                "17" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;



    case 'ventasxfechaRestaurante':

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->ventasxfechaRestaurante($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                //  $url2='../reportes/exTicket.php?id=';   

            } else {
                $url = '../reportes/exTicket.php?id=';
                //  $url2='../reportes/exTicket.php?id='; 
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;

            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;

            } else {
                $resDatostarjeta = " ";
            }

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;


            $data[] = array(
                "0" => '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idventa,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->num_comprobante,
                "6" => $resventa,
                "7" => $resventades,
                "8" => $reg->forma_pago . " - " . $resDatostarjeta,
                "9" => $reg->cefectivo,
                "10" => $reg->ctarjeta,
                "11" => $reg->ccredito,
                "12" => $reg->rescambio,
                "13" => $reg->fecha,
                "14" => $fecha_certificacion,
                "15" => $reg->serie_ecoFactura,
                "16" => $reg->numero_ecoFactura,
                "17" => $reg->mesas,
                "18" => $reg->num_correlativomesa,
                "19" => $reg->propina,
                "20" => $reg->mesero,
                "21" => $reg->fecha_creacion,
                "22" => $reg->add_fecha_hora,
                "23" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'ventasxfecha_anuladas':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->ventasxfecha_anuladas($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                //  $url2='../reportes/exTicket.php?id=';   

            } else {
                $url = '../reportes/exTicket.php?id=';
                //  $url2='../reportes/exTicket.php?id='; 
            }

            if ($reg->estado == 'Aceptado') {
                # code... 
                $resventa = $reg->total_venta;
                $resventades = $reg->total_ventades;

            } else {
                $resventa = 0;
                $resventades = 0;
            }

            if ($reg->forma_pago == 'Tarjeta') {
                # code... 
                $resDatostarjeta = $reg->tipo_pagoBacVisaNet . " / " . $reg->opcionesAdicionales . " / " . $reg->valor_tarjeta;

            } else {
                $resDatostarjeta = " ";
            }

            // Validamos si la fecha de certificación es '0000-00-00 00:00:00'
            $fecha_certificacion = ($reg->fechaCertificacion_ecoFactura == '0000-00-00 00:00:00') ? 'No certificado' : $reg->fechaCertificacion_ecoFactura;


            $data[] = array(
                "0" => '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->idventa,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->num_comprobante,
                "6" => $resventa,
                "7" => $resventades,
                "8" => $reg->forma_pago . " - " . $resDatostarjeta,
                "9" => $reg->cefectivo,
                "10" => $reg->ctarjeta,
                "11" => $reg->ccredito,
                "12" => $reg->rescambio,
                "13" => $reg->fecha,
                "14" => $fecha_certificacion,
                "15" => $reg->serie_ecoFactura,
                "16" => $reg->numero_ecoFactura,
                "17" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;




    case 'ventasxfechaxproductoComprasVentas':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];
        $codigo_pro = $_REQUEST["codigo_pro"];

        $rspta = $consulta->ventasxfechaxproductoComprasVentas($fecha_inicio, $fecha_fin, $idsucursal, $codigo_pro);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $movimientoTotal =
                floatval($reg->cantidad_compras) +
                floatval($reg->cantidad_notaDebito) +
                floatval($reg->cantidad_salida) +
                floatval($reg->cantidad_entrada) +
                floatval($reg->cantidad_ventas) +
                floatval($reg->cantidad_devolucion);

            if (strtolower($reg->estado) == 'Anulado') {
                $stockFinal = floatval($reg->stock_inventario);
            } else {
                $stockFinal = floatval($reg->stock_inventario) + $movimientoTotal;
            }


            $data[] = array(
                "0" => $reg->fecha_horaCreacion,
                "1" => $reg->idingreso,
                "2" => $reg->idnota_debito,
                "3" => $reg->idtraladosucursal,
                "4" => $reg->idtraladosucursal_entrada,
                "5" => $reg->idventa,
                "6" => $reg->iddevolucion,
                "7" => $reg->cantidad_compras,
                "8" => $reg->cantidad_notaDebito,
                "9" => $reg->cantidad_salida,
                "10" => $reg->cantidad_entrada,
                "11" => $reg->cantidad_ventas,
                "12" => $reg->cantidad_devolucion,
                "13" => $reg->stock_inventario,
                "14" => $reg->estado
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'ventasxfechaAgrupadas':

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->ventasxfechaAgrupadas($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {


            $data[] = array(
                "0" => $reg->usuario,
                "1" => $reg->totalventa,
                "2" => $reg->totalventaDes
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'listarFactransportexfecha':

        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idsucursal = $_REQUEST["idsucursal"];

        $rspta = $consulta->listarFactransportexfecha($fecha_inicio, $fecha_fin, $idsucursal);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            if ($reg->tipo_comprobante == 'Factura') {
                # code... 
                $url = '../reportes/exTicket_Fel.php?id=';
                $url2 = '../reportes/exTicket_Fel58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_Fel.php?id=';

            } elseif ($reg->tipo_comprobante == 'Cambiaria') {
                # code... 
                $url = '../reportes/exTicket_FelFCAM.php?id=';
                $url2 = '../reportes/exTicket_Fel_FCAM58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta_FelFCAM.php?id=';

            } else {
                $url = '../reportes/exTicket.php?id=';
                $url2 = '../reportes/exTicket58mm.php?id=';
                $url3 = '../reportes/exVentaFormatoCarta.php?id=';
            }

            $data[] = array(
                "0" => '<a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-success"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url2 . $reg->idventa . '"  title="Ticket 58mm"><button class="btn btn-info"><i class="fa fa-print"></i> </button> </a>' .
                    '<a target="_blank" href="' . $url3 . $reg->idventa . '"  title="Carta"><button class="btn btn-warning"><i class="fa fa-print"></i> </button> </a>',
                "1" => $reg->fecha,
                "2" => $reg->idventa,
                "3" => $reg->total_venta,
                "4" => $reg->vcomision,
                "5" => $reg->mliquido,
                "6" => $reg->autorizacion,
                "7" => $reg->vflete,
                "8" => $reg->subtotal,
                "9" => $reg->nombre_transporte,
                "10" => "Guia: " . $reg->guia_transporte . " Estado Guia: " . $reg->estadoguia . " Estado Venta: " . $reg->estado_venta
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;


    case 'clientesnuevosxfecha':
        $rspta = $consulta->clientesnuevosxfecha($_GET["fecha_inicio"], $_GET["fecha_fin"]);
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $data[] = ["fecha" => $reg->fecha, "total" => $reg->total];
        }
        echo json_encode($data);
        break;

    case 'seguimientosxfecha':
        $rspta = $consulta->seguimientosxfecha($_GET["fecha_inicio"], $_GET["fecha_fin"]);
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $data[] = ["fecha" => $reg->fecha, "total" => $reg->total];
        }
        echo json_encode($data);
        break;

    case 'listar':
        $idcliente = $_REQUEST["idcliente"];
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->clientes_ventas($fecha_inicio, $fecha_fin, $idcliente);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->tipo_comprobante,
                "2" => $reg->forma_pago,
                "3" => $reg->total_venta,
                "4" => $reg->total_ventades
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'GenerarSolicitudPedidoTb':
        $cotiz = $consulta->GenerarSolicitudPedidoTb();
        echo json_encode($cotiz);
        break;

    case 'rpt_registro_ingresoEmpleados':

        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];

        $rspta=$consulta->rpt_registro_ingresoEmpleados($fecha_inicio,$fecha_fin);
        //Vamos a declarar un array
        $data= Array(); 
 
        while ($reg=$rspta->fetch_object()){ 
 
           $data[]=array(
                "0"=>($reg->condicion)?
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idregistro_app.')"><i class="fa fa-close"></i></button>':' ',
                "1"=>$reg->codigo,
                "2"=>"<img src='../files/articulos/".$reg->foto."' height='50px' width='50px' >",
                "3"=>$reg->nombres,
                "4"=>$reg->puesto,
                "5"=>$reg->fecha,
                "6" => ($reg->tipo_salida_entrada == 0)? '<span class="badge bg-success">ENTRADA</span>': '<span class="badge bg-danger">SALIDA</span>',
                "7"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;


}
?>