<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

require_once "../modelos/Categoria.php";
 
$categoria=new Categoria();
 
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$tipo_descuento=isset($_POST["tipo_descuento"])? limpiarCadena($_POST["tipo_descuento"]):"";
$valor_descuento=isset($_POST["valor_descuento"])? limpiarCadena($_POST["valor_descuento"]):"";
$mostrar_en_venta=isset($_POST["mostrar_en_venta"])? limpiarCadena($_POST["mostrar_en_venta"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idcategoria)){
            $rspta=$categoria->insertar($nombre,$descripcion,$tipo_descuento,$valor_descuento,$mostrar_en_venta);
            echo $rspta ? "Categoría registrada" : "Categoría no se pudo registrar";
        }
        else {
            $rspta=$categoria->editar($idcategoria,$nombre,$descripcion,$tipo_descuento,$valor_descuento,$mostrar_en_venta);
            echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$categoria->desactivar($idcategoria);
        echo $rspta ? "Categoría Desactivada" : "Categoría no se puede desactivar";
        break;
    break;
 
    case 'activar':
        $rspta=$categoria->activar($idcategoria);
        echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
        break;
    break;
 
    case 'mostrar':
        $rspta=$categoria->mostrar($idcategoria);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listar':
        $rspta=$categoria->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategoria.')"><i class="fa fa-close"></i></button>'.
                    ' <button class="btn btn-info" onclick="mostrar_sucursales('.$reg->idcategoria.')" title="Actualizar Categorías y Sucursales"><i class="fa fa-cubes"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-info" onclick="mostrar_sucursales('.$reg->idcategoria.')" title="Actualizar Categorías y Sucursales"><i class="fa fa-cubes"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idcategoria.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->descripcion,
                "3"=>$reg->tipo_descuento,
                "4"=>$reg->mostrar_en_venta,
                "5"=>$reg->valor_descuento,
                "6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

    case 'listarSucursalesPorCategoria':
        $rspta = $categoria->listar_categorias_sucursal($idcategoria);
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = $reg;
        }
        echo json_encode($data);
    break;

    case 'actualizarSucursales':
        $ids_relacion = $_POST['idcategoria_sucursal_all'];
        $estados = $_POST['estado_mostrar'];
        $respuesta_final = true;
        for ($i = 0; $i < count($ids_relacion); $i++) {
            $id = limpiarCadena($ids_relacion[$i]);
            $estado = limpiarCadena($estados[$i]);

            $rpta = $categoria->actualizar_mostrar_sucursal($id, $estado); 

            if (!$rpta) {
                $respuesta_final = false;
            }
        }

        echo $respuesta_final ? "Visibilidad actualizada exitosamente" : "Error al actualizar algunas sucursales";
    break;
}
?>