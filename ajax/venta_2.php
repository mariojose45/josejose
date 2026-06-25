<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


 
require_once "../modelos/Venta_2.php";
 
$venta=new Venta2(); 
   
$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"]; 
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$total_ventades=isset($_POST["total_ventades"])? limpiarCadena($_POST["total_ventades"]):"";
 
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$dias_credito=isset($_POST["dias_credito"])? limpiarCadena($_POST["dias_credito"]):"";
$fecha_hora_cobro=isset($_POST["fecha_hora_cobro"])? limpiarCadena($_POST["fecha_hora_cobro"]):"";
 
switch ($_GET["op"]){  
    case 'guardaryeditar':
        if (empty($idventa)){ 
            $rspta=$venta->insertar($idcliente,$idusuario,$fecha_hora,$impuesto,$total_venta,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento_porcentaje"],$_POST["total_ventades"],$forma_pago,$dias_credito,$fecha_hora_cobro);
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

    case 'EditarFacturaAddDetalleFactura':
       // echo "Ingreso a EditarFacturaAddDetalleFactura "; 
        echo $_POST["factura"];
        $venta->insertDetail($_POST["factura"],$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]);
    break;

    case 'actualizarcuerpofac':
        $venta->updatecuerpofac($_POST["idventa"],$_POST["fecha_hora"]);
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
                                    <th>Stock</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento %</th>
                                    <th>Subtotal</th>
                                    <th>Subtotal Des</th>
                                </thead>';
 
        while ($reg = $rspta->fetch_object())
                { 
                    echo '<tr class="filas" id="tr'.$reg->iddetalle_venta.'"><td>
                    </td><td>'.$reg->nombre.'</td><td>'.$reg->stockinvent.'</td><td>'.$reg->cantidad.'</td><td><input type="number" step="any" readonly="" value="'.$reg->precio_venta.'"></td><td>'.$reg->descuento.'</td><td>'.$reg->subtotal.'</td><td>'.$reg->total_descuento.'</td></tr>';
                    $total=$total+(($reg->precio_venta-(($reg->precio_venta*$reg->descuento)/100))*$reg->cantidad);
                }
        echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>                                    
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total"></h4><input type="hidden" name="total_venta" id="total_venta"></th> 
                                </tfoot>';
    break;
 
    case 'listar':
        $rspta=$venta->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
                if ($reg->tipo_comprobante=='Boleta') {
                    # code...
                    $url='../reportes/exEnvio.php?id='; 

                }
                else{
                    $url='../reportes/exFactura2.php?id=';
                }
            $data[]=array(
                "0"=>($reg->estado=='Materia')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>'.'<a target="_blank" href="'.$url.$reg->idventa.'"><button class="btn btn-info"><i class="fa fa-file"></i> </button> </a>',
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->serie_comprobante.'-'.$reg->idventa,
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
 
        $rspta = $persona->listarC();
 
        while ($reg = $rspta->fetch_object())
                {
                echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '--'.$reg->direccion.'</option>';
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
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.str_replace('"', 't.t', $reg->nombre).'\',\''.$reg->precio_venta.'\',\''.$reg->stock.'\',\''.$reg->descuento_porcentaje.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->codigo,
                "4"=>$reg->stock,
                "5"=>($reg->stockminimo <=$reg->stock )?'<span class="label bg-green">Stock Normal</span>':
                '<span class="label bg-red">Stock Bajo</span>',
                "6"=>$reg->precio_venta,
                "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
                );
        } 
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;

    case 'listarArticulosVenta2':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
        $idcliente=$_POST["idcliente"];
        $rspta=$articulo->listarActivosVenta($idcliente);
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "value"=>$reg->nombre,
                "label"=>$reg->nombre,
                "desc"=>$reg->nombre, 
                "icon"=>$reg->imagen,
                "idarticulo"=>$reg->idarticulo,
                "nombre"=>$reg->nombre,
                "precio_venta"=>$reg->precio_venta,
                "descuento_porcentaje"=>$reg->descuento_porcentaje,
                "stock"=>$reg->stock,
                "img"=>$reg->imagen
                );
        }
        $results = $data;
        echo json_encode($results);
    break;
}
?> 