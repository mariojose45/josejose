<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class Tiendawebarticulos
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }

    public function insertar($nombre,$codigo,$idcategoria,$idsubcategoria,
    $descripcion_articulo,$tipo_promocion,$imagen,$stock, $precio_compra,$stock_pv,$stock_pv_oferta,
    $meta_titulo,$meta_descripcion,$meta_keywords)
    {
        date_default_timezone_set('America/Guatemala');
        $fechaHora = date('Y-m-d H:i:s'); 
        $sql="INSERT INTO articulo (nombre,codigo,idcategoria,idsubcategoria,
        descripcion_articulo,imagen,stock,precio_compra,precio_venta,precio_venta_oferta,
        meta_titulo,meta_descripcion,meta_keywords,tipo_producto,descripcion,tipo_promocion)
        VALUES ('$nombre','$codigo','$idcategoria','$idsubcategoria',
        '$descripcion_articulo','$imagen','$stock', '$precio_compra','$stock_pv','$stock_pv_oferta',
        '$meta_titulo','$meta_descripcion','$meta_keywords','Producto','$tipo_promocion');";
        $idarticulonew = ejecutarConsulta_retornarID($sql);

            $sqlArticuloxSucursal = "INSERT INTO articuloxsucursal (idarticulo, idsucursal, idusuario, stocksucursal, 
                                        stockminimo, precio_compra, precio_venta, precio_venta_oferta, precio_ventaNocturno, descuento_porcentaje, 
                                        precio_descuento, precio_rango1, precio_rango2, precio_rango3, 
                                        nombre_01,stock_unidad, precio_unidad, 
                                        nombre_02,stock_blister, precio_blister, 
                                        nombre_03,stock_caja, precio_caja, 
                                        nombre_04,stock_fardo, precio_fardo, 
                                        nombre_05,stock_sacos, precio_sacos, 
                                        nombre_06,stock_paquete, precio_paquete, 
                                        nombre_07,stock_07, precio_07, 
                                        nombre_08,stock_08, precio_08, 
                                        nombre_09,stock_09, precio_09, 
                                        nombre_10,stock_10, precio_10, 
                                        nombre_11,stock_11, precio_11, 
                                        nombre_12,stock_12, precio_12, 
                                        nombre_13,stock_13, precio_13, 
                                        nombre_14,stock_14, precio_14, 
                                        nombre_15,stock_15, precio_15, 
                                        nombre_16,stock_16, precio_16, 
                                        nombre_17,stock_17, precio_17, 
                                        nombre_18,stock_18, precio_18, 
                                        nombre_19,stock_19, precio_19, 
                                        nombre_20,stock_20, precio_20, 
                                        condicion, ganacia_articulo, 
                                        tipo_ganacia, fecha_creacion,producto_consignacion,aplica_impuestos,precio_rango1_Dos,
                                        precio_rango2_Dos,precio_rango3_Dos,precio_rango1_Mecanico,precio_rango2_MecanicoDos,
                                        precio_rango3_MecanicoTres,precio_rango1_Distribuidor,precio_rango2_DistribuidorDos,
                                        precio_rango3_DistribuidorTres,precio_rango1_Mayorista,precio_rango2_MayoristaDos,
                                        precio_rango3_MayoristaTres,codigo_sku,stockmaximo,precio_activado,descripcion_2) 
                                        VALUES ('$idarticulonew', '4', '".$_SESSION["idusuario"]."', '$stock', 
                                                '0', '$precio_compra', '$stock_pv', '$stock_pv_oferta', '0', 
                                                '0', '0', '0', '0', 
                                                '0', 
                                                'UNIDAD', '1', '$stock_pv',
                                                'BLISTER', '0', '0', 
                                                'CAJA', '0', '0', 
                                                'FARDO', '0', '0', 
                                                'SACOS', '0', '0', 
                                                'PAQUETES', '0', '0', 
                                                'BOLSA', '0', '0', 
                                                'BOTELLA', '0', '0', 
                                                'CIENTO', '0', '0', 
                                                'DOCENA', '0', '0', 
                                                'GALON', '0', '0', 
                                                'KILO', '0', '0', 
                                                'ONZAS', '0', '0', 
                                                'LIBRA', '0', '0', 
                                                'KINTAL', '0', '0', 
                                                'ROLLO', '0', '0', 
                                                'CARTON', '0', '0', 
                                                'MILLAR', '0', '0', 
                                                'ARROBA', '0', '0', 
                                                'BOTE', '0', '0', 
                                                '1', '0', 
                                                '0', '$fechaHora','SI','SI','0',
                                                '0','0','0',
                                                '0','0',
                                                '0','0',
                                                '0','0',
                                                '0','0',
                                                '0','0','NO','0')";
                
                $insertSucursal = ejecutarConsulta($sqlArticuloxSucursal);        
                return $idarticulonew; // Retornar el ID del artículo para poder guardar las imágenes
    }
 
    //Implementamos un método para insertar registros
    public function editar($idarticulo,$nombre,$codigo,$idcategoria,$idsubcategoria,
    $descripcion_articulo,$tipo_promocion,$imagen,$stock, $precio_compra,$stock_pv,$stock_pv_oferta,
    $meta_titulo,$meta_descripcion,$meta_keywords)
    {
        $sql="UPDATE articulo SET 
                nombre='$nombre',
                codigo='$codigo',
                idcategoria='$idcategoria',
                idsubcategoria='$idsubcategoria',
                descripcion_articulo='$descripcion_articulo',
                imagen='$imagen',
                stock='$stock',
                precio_compra='$precio_compra',
                precio_venta='$stock_pv',
                precio_venta_oferta='$stock_pv_oferta',
                meta_titulo='$meta_titulo',
                meta_descripcion='$meta_descripcion',
                meta_keywords='$meta_keywords',
                tipo_promocion='$tipo_promocion'
            WHERE idarticulo='$idarticulo'";
        ejecutarConsulta($sql);

        $sqlArticuloxSucursal = "UPDATE articuloxsucursal SET 
                stocksucursal='$stock',
                precio_compra='$precio_compra',
                precio_venta='$stock_pv',
                precio_venta_oferta='$stock_pv_oferta'
                precio_unidad='$stock_pv',
            WHERE idarticulo='$idarticulo' and idsucursal='4'";
        ejecutarConsulta($sqlArticuloxSucursal);
    
        // SI NO SUBIÓ IMÁGENES, NO HACER NADA
        if (empty($_FILES['imagenes']['name'][0])) {
            return "ok";
        }
    
        // CREA LA CARPETA SI NO EXISTE
        $carpeta = "../files/imagenesarticulos/";
        if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
    
        // BORRAR IMÁGENES ANTERIORES
        $sqlDelete="DELETE FROM imagenes_producto WHERE idarticulo='$idarticulo'";
        ejecutarConsulta($sqlDelete);
    
        // GUARDAR NUEVAS IMÁGENES
        foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmp) {
    
            if (!is_uploaded_file($tmp)) continue;
    
            $nombreArchivo = time() . '_' . $_FILES['imagenes']['name'][$i];
            $destino = $carpeta . $nombreArchivo;
    
            if (move_uploaded_file($tmp, $destino)) {
    
                // SI QUIERES GUARDAR RUTA RELATIVA SIN "../"
                $rutaRelativa = "files/imagenesarticulos/" . $nombreArchivo;
    
                $sqlInsert = "
                    INSERT INTO imagenes_producto (idarticulo, ruta_imagen, orden)
                    VALUES ('$idarticulo', '$rutaRelativa', '$i')
                ";
                ejecutarConsulta($sqlInsert);
            }
        }
    
        return "ok";
    }
    
    
    //Implementamos un método para editar registros

    //Implementamos un método para desactivar categorías
    public function desactivar($idcategoria)
    {
        $sql="UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idcategoria)
    {
        $sql="UPDATE categoria SET condicion='1' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idarticulo)
    {
        $sql="SELECT 
                a.idarticulo,
                a.nombre,
                a.codigo,
                a.imagen,
                a.idcategoria,
                a.idsubcategoria,
                a.descripcion_articulo,
                asu.stocksucursal,
                asu.precio_compra,
                asu.precio_venta,
                asu.precio_venta_oferta,
                a.meta_titulo,
                a.meta_descripcion,
                a.meta_keywords
            FROM articulo a
            INNER JOIN categoria c ON c.idcategoria=a.idcategoria
            inner join articuloxsucursal asu on asu.idarticulo=a.idarticulo
             WHERE a.idarticulo='$idarticulo' and asu.idsucursal='4'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                a.idarticulo,
                a.nombre,
                a.imagen,
                a.codigo,
                c.nombre as categoria,
                asu.stocksucursal,
                asu.precio_unidad,
                asu.condicion,
                a.descripcion_articulo
            FROM articulo a
            INNER JOIN articuloxsucursal asu ON asu.idarticulo=a.idarticulo
            INNER JOIN categoria c ON c.idcategoria=a.idcategoria
            WHERE asu.idsucursal=4";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    /*
    public function select()
    {
        $sql="SELECT * FROM categoria where condicion=1";
        return ejecutarConsulta($sql);      
    }
    */
    public function select()
    {
        $sql="SELECT 
                c.* FROM 
                categoria c
            INNER JOIN 
                categoria_sucursal cs 
            ON 
                c.idcategoria = cs.idcategoria
            WHERE 
                c.condicion = 1 AND 
                cs.idsucursal = '".$_SESSION["idsucursal"]."' AND 
                cs.mostrar = 'Si'";
        return ejecutarConsulta($sql);      
    }




 

    public function selectCategoriaSubCategoria()
    {
        $sql="SELECT c.*
                FROM categoria c
                WHERE c.idcategoria NOT IN (
                    SELECT a.idcategoria
                    FROM asociar_subcategoria a
                )
                AND c.condicion = 1;  -- opcional, si solo quieres activas
                ";
        return ejecutarConsulta($sql);      
    }    

    public function listar_categorias_sucursal($idcategoria){
        $sql="SELECT
            cs.idcategoria_sucursal,
            cs.mostrar,
            c.nombre AS nombre_categoria,
            s.nombre AS nombre_sucursal
        FROM categoria_sucursal cs
        INNER JOIN categoria c ON cs.idcategoria = c.idcategoria
        INNER JOIN sucursal s ON cs.idsucursal = s.idsucursal
        WHERE cs.idcategoria = '$idcategoria'";
        return ejecutarConsulta($sql);      
    }

    public function actualizar_mostrar_sucursal($idcategoria_sucursal, $estado) {
        $sql = "UPDATE categoria_sucursal SET mostrar = '$estado' WHERE idcategoria_sucursal = '$idcategoria_sucursal'";
        return ejecutarConsulta($sql); 
    }

    public function selectCategoriaslistar()
    {
        $sql="SELECT 
                c.* FROM 
                categoria c
            INNER JOIN 
                categoria_sucursal cs 
            ON 
                c.idcategoria = cs.idcategoria
            WHERE 
                c.condicion = 1 AND 
                cs.idsucursal = '4' AND 
                cs.mostrar = 'Si'";
        return ejecutarConsulta($sql);      
    }

    //Método para obtener las imágenes de un artículo
    public function obtenerImagenes($idarticulo)
    {
        $sql = "SELECT idimagenes_producto, idarticulo, ruta_imagen, orden
                FROM imagenes_producto
                WHERE idarticulo = '$idarticulo'
                ORDER BY orden ASC";
        return ejecutarConsulta($sql);
    }

}
 
?>