<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";




class Categoria
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombre, $descripcion, $tipo_descuento, $valor_descuento, $mostrar_en_venta)
    {
        $sql_categoria = "INSERT INTO categoria (nombre, descripcion, condicion, tipo_descuento, valor_descuento, mostrar_en_venta)
            VALUES ('$nombre', '$descripcion', '1', '$tipo_descuento', '$valor_descuento', '$mostrar_en_venta')";

        $id_categoria = ejecutarConsulta_retornarID($sql_categoria);

        if ($id_categoria > 0) {

            $sql_sucursales = "INSERT INTO categoria_sucursal (idcategoria, idsucursal, mostrar)
                SELECT $id_categoria, idsucursal, 'Si'
                FROM sucursal";
            return ejecutarConsulta($sql_sucursales);

        } else {
            return false;
        }
    }

    //Implementamos un método para editar registros
    public function editar($idcategoria, $nombre, $descripcion, $tipo_descuento, $valor_descuento, $mostrar_en_venta)
    {
        $sql = "UPDATE categoria SET nombre='$nombre',descripcion='$descripcion',
         tipo_descuento = '$tipo_descuento', valor_descuento = '$valor_descuento', mostrar_en_venta = '$mostrar_en_venta'
        WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idcategoria)
    {
        $sql = "UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idcategoria)
    {
        $sql = "UPDATE categoria SET condicion='1' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcategoria)
    {
        $sql = "SELECT * FROM categoria WHERE idcategoria='$idcategoria'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM categoria WHERE condicion = '1'";
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
        $sql = "SELECT 
                c.* FROM 
                categoria c
            INNER JOIN 
                categoria_sucursal cs 
            ON 
                c.idcategoria = cs.idcategoria
            WHERE 
                c.condicion = 1 AND 
                cs.idsucursal = '" . $_SESSION["idsucursal"] . "' AND 
                cs.mostrar = 'Si' ";
        return ejecutarConsulta($sql);
    }






    public function selectCategoriaSubCategoria($idcategoria_actual = 0)
    {
        $sql = "
            SELECT c.*
            FROM categoria c
            WHERE 
                c.condicion = 1
                AND (
                    c.idcategoria = '$idcategoria_actual' 
                    OR c.idcategoria NOT IN (
                        SELECT a.idcategoria
                        FROM asociar_subcategoria a
                    )
                )
            ORDER BY c.nombre ASC
        ";
        return ejecutarConsulta($sql);
    }


    public function listar_categorias_sucursal($idcategoria)
    {
        $sql = "SELECT
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

    public function actualizar_mostrar_sucursal($idcategoria_sucursal, $estado)
    {
        $sql = "UPDATE categoria_sucursal SET mostrar = '$estado' WHERE idcategoria_sucursal = '$idcategoria_sucursal'";
        return ejecutarConsulta($sql);
    }

    public function selectCategoriaslistar()
    {
        $sql = "SELECT 
                c.* FROM 
                categoria c
            INNER JOIN 
                categoria_sucursal cs 
            ON 
                c.idcategoria = cs.idcategoria
            WHERE 
                c.condicion = 1 AND 
                cs.idsucursal = '4' AND 
                cs.mostrar = 'Si' and c.idcategoria not in (12)";
        return ejecutarConsulta($sql);
    }

}

?>