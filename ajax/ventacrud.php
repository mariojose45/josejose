<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


 
require_once "../modelos/Ventacrud.php";
 
$venta=new Venta();
 
$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
 
switch ($_GET["op"]){ 
    case 'guardaryeditar':
        if (empty($idventa)){
            $rspta=$venta->insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_venta,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]);
            echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la venta";
        }
        else {
        }
    break; 
 
    case 'anular':
        $rspta=$venta->anular($idventa);
        echo $rspta ? "Venta anulada" : "Venta no se puede anular";
    break;
 
    case 'mostrar':
        $rspta=$venta->mostrar($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listarDetalle':
        //Recibimos el idingreso
        $id=$_GET['id'];
 
        $rspta = $venta->listarDetalle($id);
        $total=0;
        echo "<input type='hidden' id='facturaparadetalle' value='".$id."' />";
        echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </thead>';
 
        while ($reg = $rspta->fetch_object())
                {
                    echo '<tr class="filas" id="tr'.$reg->iddetalle_venta.'"><td>
                    <button class="btn btn-danger" type="button" onclick=\'EliminarDetalleVenta("#tr'.$reg->iddetalle_venta.'",'.$reg->cantidad.','.$reg->iddetalle_venta.','.$reg->idarticulo.')\'><span class="fa fa-close"></span></button>
                    <button class="btn btn-warning" type="button" onclick=\'UpdateDetalleVenta("#tr'.$reg->iddetalle_venta.'",'.$reg->cantidad.',"#input'.$reg->iddetalle_venta.'",'.$reg->iddetalle_venta.','.$reg->idarticulo.')\'><span class="fa fa-pencil"></span></button>
                    </td><td>'.$reg->nombre.'</td><td><input value="'.$reg->cantidad.'" id="input'.$reg->iddetalle_venta.'"></td><td>'.$reg->precio_venta.'</td><td>'.$reg->descuento.'</td><td>'.$reg->subtotal.'</td></tr>';
                    $total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
                }
        echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">S/.'.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th> 
                                </tfoot>';
    break;

    case 'EditarFacturaAddDetalleFactura':
        echo "mmm"; 
        $venta->insertDetail($_POST["factura"],$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]);
    break;

    case 'actualizarcuerpofac':
        $venta->updatecuerpofac($_POST["serie_comprobante"],$_POST["idventa"],$_POST["fecha_hora"],$_POST["num_comprobante"]);
    break;     

    case 'eliminardetalle':
           $id=$_GET['id'];
           $cantidad=$_GET['cantidad'];
           $idarticulo=$_GET['idarticulo'];
         
           $rspta = $venta->EliminarDetalle($id,$cantidad,$idarticulo); 
    break;
    case 'updatedetalle':
        $id=$_GET['id'];
        $cantidad=$_GET['cantidad'];
        $nuevacantidad=$_GET['nuevacantidad'];
        $idarticulo=$_GET['idarticulo'];
        $rspta = $venta->ModificarDetalle($id,$cantidad,$nuevacantidad,$idarticulo);
    break; 
    
    case 'listar':
        $rspta=$venta->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
                if ($reg->tipo_comprobante=='Ticket') {
                    # code...
                    $url='../reportes/exTicket.php?id=';

                }
                else{
                    $url='../reportes/exFactura.php?id=';
                }
            $data[]=array(
                "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>').
                '<a target="_blank" href="'.$url.$reg->idventa.'"><button class="btn btn-info"><i class="fa fa-file"></i> </button> </a>',
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "6"=>$reg->total_venta,
                "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
 
    break;
 
    case 'selectCliente':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
 
        $rspta = $persona->listarc();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--Codigo'.$reg->idpersona.'</option>';
                }
    break;

    case 'buscararticulocodebar':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $codigo=$_REQUEST["codigo"];
        $rspta=$articulo->ObtenerProductoBarCode($codigo);
        while ($reg=$rspta->fetch_object()){
            echo $reg->idarticulo.'@'.$reg->nombre.'@'.$reg->precio_venta;
        }
    break;
 
    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $idcliente=$_GET["idcliente"];
        $rspta=$articulo->listarActivosVenta($idcliente);
 
      //  $rspta=$articulo->listarActivosVentadd();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->precio_venta.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->codigo,
                "4"=>$reg->stock,
                "5"=>$reg->precio_venta,
                "6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
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