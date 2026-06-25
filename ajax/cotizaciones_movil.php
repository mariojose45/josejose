<?php 
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Cotizaciones_movil.php";
    
$cotizaciones=new Cotizaciones();     
   
$idcotizacion=isset($_POST["idcotizacion"])? limpiarCadena($_POST["idcotizacion"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"]; 
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$nombre_empresa=isset($_POST["nombre_empresa"])? limpiarCadena($_POST["nombre_empresa"]):"";
$telefono_empresa=isset($_POST["telefono_empresa"])? limpiarCadena($_POST["telefono_empresa"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$total_ventades=isset($_POST["total_ventades"])? limpiarCadena($_POST["total_ventades"]):"";
$idventa_administrador=isset($_POST["idventa_administrador"])? limpiarCadena($_POST["idventa_administrador"]):"";
$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";

 
switch ($_GET["op"]){  
    case 'guardaryeditar': 
        if (empty($idcotizacion)){ 
            $rspta=$cotizaciones->insertar($idcotizacion,$idcliente,$idusuario,$fecha_hora,$nombre_empresa,$telefono_empresa,$total_venta,$total_ventades,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento_porcentaje"],$_POST["descripcion_detalle"],$idsucursal);
            echo $rspta ? "Cotizacion registrada" : "No se pudieron registrar todos los datos de la Cotizacion"; 
        }
        else {
            $rspta=$cotizaciones->editar($idcotizacion,$idcliente,$idusuario,$fecha_hora,$nombre_empresa,$telefono_empresa,$total_venta,$total_ventades,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento_porcentaje"],$_POST["descripcion_detalle"],$idsucursal);
            echo $rspta ? "Cotizacion Actualizada" : "No se pudieron Actualizar todos los datos de la Cotizacion";             
        }
    break;
 
    case 'anular':
        $rspta=$cotizaciones->anular($idcotizacion);
        echo $rspta ? "Cotizacion anulada" : "Cotizacion no se puede anular";
    break;
  
    case 'mostrarVentaAdministrador':
        $rspta=$cotizaciones->mostrarVentaAdministrador($idventa_administrador); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
        
    break;


    case 'mostrar':
        $rspta=$cotizaciones->mostrar($idcotizacion); 
        //Codificar el resultado utilizando json
        echo json_encode($rspta);  
    break;

    case 'paraventa':
        $cotiz=$cotizaciones->obtenerdetallecotizacion($idcotizacion);
        echo json_encode($cotiz);
    break;   

    case 'paraventaAdministrador': 
        $cotiz=$cotizaciones->detallecotizacionparaventaadministrador($idventa_administrador);
        echo json_encode($cotiz);
    break;        
 
    case 'listarDetalle':
        //Recibimos el idingreso
        $id=$_GET['id'];
 
        $rspta = $cotizaciones->listarDetalle($id);
        $total=0;
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
                    echo '<tr class="filas" id="fila'.$reg->idcotizacion.'">
                        <td><button class="btn btn-danger" type="button" onclick="eliminarDetalleCotizacion('.$reg->idcotizacion.')"><span class="fa fa-close"></span></button></td>
                        <td><input type="hidden" name="idarticulo[]" id="precio_venta[]" value="'.$reg->idarticulo.'">'.$reg->nombre.'xxxxx</td>
                        <td><input onchange="modificarSubototales()" type="number" step="any"  name="cantidad[]" id="cantidad[]" value="'.$reg->cantidad.'"></td>
                        <td><input type="number" step="any" name="precio_venta[]" id="precio_venta[]" value="'.$reg->precio_venta.'" ></td>
                        <td><input onchange="modificarSubototales();" type="number" step="any"   name="descuento_porcentaje[]" id="descuento_porcentaje[]" value="'.$reg->descuento.'" ></td>
                        <td>'.$reg->subtotal.'</td>
                    </tr>';
                    $total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
                }
        echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">Q/.'.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th> 
                                </tfoot>';
    break;
 
    case 'listar':
        $rspta=$cotizaciones->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){ 

                    $url='../reportes/exCotizacion.php?id=';   

            $data[]=array(
                "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcotizacion.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idcotizacion.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idcotizacion.')"><i class="fa fa-eye"></i></button>').
                '<a target="_blank" href="'.$url.$reg->idcotizacion.'"><button class="btn btn-info"><i class="fa fa-file"></i> </button> </a>',
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>"# Cotizacion".$reg->idcotizacion."  # Correlativo: ".$reg->num_comprobante,
                "6"=>$reg->total_venta,
                "7"=>$reg->total_ventades,   
                "8"=>$reg->nombre_sucursal,                
                "9"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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
            echo $reg->idarticulo.'@'.$reg->articulo.'@'.$reg->precio_venta.'@'.$reg->stock.'@'.$reg->stock.'@'.$reg->descuento_porcentaje;
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
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->precio_venta.'\',\''.$reg->stock.'\',\''.$reg->stock.'\',\''.$reg->descuento_porcentaje.'\')"><span class="fa fa-plus"></span></button>',
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