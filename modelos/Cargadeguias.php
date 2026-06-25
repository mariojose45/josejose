<?php

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
require_once "../reportes/Classes/PHPExcel.php";



class Categoria
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function cargarexcel($tmpfname, $idtransporte)
    {
        $leerexcel = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        //cargaremos nuestro excel

        $excelobj = $leerexcel->load($tmpfname);

        //cargar en que hora esta el documneto
        $hoja = $excelobj->getSheet(0);
        $filas = $hoja->getHighestrow();
        // echo $fila;
        echo "<table id='table_detalle' class='table-responsive' style='width:100%; table-layout:fixed' >
        <thead>
            <tr>
                <td>GUIANUMERO</td>
                <td>FECHAGUIA</td>
                <td>MVENTA</td>
                <td>COMISION</td>
                <td>VCOMISION</td>
                <td>MLIQUIDO</td>
                <td>AUTORIZACION</td>
                <td>CTABANCO</td>
                <td>VFLETE</td>
                <td>CODIGO</td>
            </tr>
        </thead>
        <tbody id='tbody_table_detalle'>";
        for ($row = 2; $row <= $filas; $row++) {
            $IDguia = $hoja->getCell('A' . $row)->getValue();
            $Fechaliqui = $hoja->getCell('B' . $row)->getValue();
            $Mventa = $hoja->getCell('C' . $row)->getValue();
            $Comision = $hoja->getCell('D' . $row)->getValue();
            $Vcomision = $hoja->getCell('E' . $row)->getValue();
            $Mliquido = $hoja->getCell('F' . $row)->getValue();
            $Autorizacion = $hoja->getCell('G' . $row)->getValue();
            $Ctabanco = $hoja->getCell('H' . $row)->getValue();
            $Vflete = $hoja->getCell('I' . $row)->getValue();
            $codigo = $idtransporte;
            # code...

            $sql = "SELECT COUNT(*) as contador from detalle_guias_excel a 
                        WHERE a.idtransporte='$codigo' AND a.idguia='$IDguia' ";
            $resultado = ejecutarConsulta($sql);

            $respuesta = $resultado->fetch_assoc();
            if ($respuesta['contador'] == '0') {
                if ($IDguia == "") {
                    # code... 
                } else {
                    echo "<tr>";
                    echo "<td>" . $IDguia . "</td>";
                    echo "<td>" . $Fechaliqui . "</td>";
                    echo "<td>" . $Mventa . "</td>";
                    echo "<td>" . $Comision . "</td>";
                    echo "<td>" . $Vcomision . "</td>";
                    echo "<td>" . $Mliquido . "</td>";
                    echo "<td>" . $Autorizacion . "</td>";
                    echo "<td>" . $Ctabanco . "</td>";
                    echo "<td>" . $Vflete . "</td>";
                    echo "<td>" . $codigo . "</td>";
                    echo "</tr>";
                }
            }
        }
        echo "</tbody>";
        echo "</table>";
    }

    //Implementamos un método para editar registros
    public function guardarexcel1($idtransporte, $obervacioncargaexcel, $fecha_cargaExcel)
    {
        $sql = "INSERT INTO guias_excel (idtransporte,obervacioncargaexcel,fecha_cargaExcel,estado,idusuario,idsucursal)
        VALUES ('$idtransporte','$obervacioncargaexcel','$fecha_cargaExcel','Aceptado','" . $_SESSION["idusuario"] . "','" . $_SESSION["idsucursal"] . "')";

        $idguias_excelnew = ejecutarConsulta_retornarID($sql);
        return $idguias_excelnew;
    }



    public function guardarexcel($idguia, $fechaliqui, $idguias_excelnew, $mventa, $comision, $vcomision, $mliquido, $autorizacion, $ctabanco, $vflete, $codigo)
    {


        $sql_detalle = "INSERT INTO detalle_guias_excel(idguias_excel,idguia,fechaliqui,mventa,comision,vcomision,mliquido,autorizacion,ctabanco,vflete,idtransporte) VALUES ('$idguias_excelnew','$idguia','$fechaliqui','$mventa','$comision','$vcomision','$mliquido','$autorizacion','$ctabanco','$vflete','$codigo')";
        ejecutarConsulta($sql_detalle);

        return $sql_detalle;

    }

    //Implementamos un método para desactivar categorías
    public function anular($id)
    {
        $sql = "UPDATE guias_excel SET condicion='0',estado='Anulado' WHERE idguias_excel='$id'";
        return ejecutarConsulta($sql);
    }


    //Implementar un método para listar los registros
    public function listar($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
            g.idguias_excel,
            g.idtransporte,
            g.obervacioncargaexcel,
            g.fecha_cargaExcel,
            t.nombre AS nombre_transporte,
            g.estado,
            s.nombre AS nombre_sucursal
             FROM guias_excel g
            INNER JOIN transporte t ON g.idtransporte=t.idtransporte
            INNER JOIN sucursal s ON s.idsucursal=g.idsucursal
            where g.idusuario='" . $_SESSION["idusuario"] . "' 
            and DATE(g.fecha_cargaExcel)>='$fecha_inicio' AND DATE(g.fecha_cargaExcel)<='$fecha_fin' 
            ";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listargeneral($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT 
            g.idguias_excel,
            g.idtransporte,
            g.obervacioncargaexcel,
            g.fecha_cargaExcel,
            t.nombre AS nombre_transporte,
            g.estado
             FROM guias_excel g
            INNER JOIN transporte t ON g.idtransporte=t.idtransporte 
            and DATE(g.fecha_cargaExcel)>='$fecha_inicio' AND DATE(g.fecha_cargaExcel)<='$fecha_fin' 
            ";
        return ejecutarConsulta($sql);
    }

    public function cabecerareporteecel($idguias_excel)
    {
        $sql = "SELECT 
            g.idguias_excel,
            g.idtransporte,
            g.obervacioncargaexcel,
            date(g.fecha_cargaExcel) as fecha,
            t.nombre AS nombre_transporte,
            g.estado,
            s.imagen AS sucursal_imagen,
            s.nombre AS sucursal_nombre,
            s.direccion AS sucursal_direccion,
            s.nit AS sucursal_nit,
            s.telefono AS sucursal_telefono,
            s.email AS sucursal_email
             FROM guias_excel g
            INNER JOIN transporte t ON g.idtransporte=t.idtransporte
            INNER JOIN usuario u ON u.idusuario=g.idusuario
            INNER JOIN sucursal s ON s.idsucursal=g.idsucursal
            where g.idguias_excel='$idguias_excel'
            ";
        return ejecutarConsulta($sql);
    }


    public function reporteeceldetalle($idguias_excel)
    {
        $sql = "SELECT 
                d.iddetalle_guias_excel,
                d.idguias_excel,
                d.idguia,
                case  when date(d.fechaliqui)='' then 0 else  IFNULL(date(d.fechaliqui),0) end AS fecha,
                d.mventa,
                d.comision,
                d.vcomision,
                d.mliquido,
                d.autorizacion,
                d.ctabanco,
                d.vflete,
                case  when t.nombre='' then 0 else  IFNULL(t.nombre,0) end   as transportes
                 from detalle_guias_excel d 
                 left JOIN transporte t ON t.idtransporte=d.idtransporte
                 where d.idguias_excel='$idguias_excel'
            ";
        return ejecutarConsulta($sql);
    }



}

?>