<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class CtaBancaria
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($cta_cod,$cta_nombre,$num_cta,$descripcion,$saldo_inicial,$tipo_banco)
    {
        $sql="INSERT INTO cuenta (cta_cod,cta_nombre,num_cta,descripcion,saldo_inicial,saldo_cuenta,tipo_banco,condicion)
        VALUES ('$cta_cod','$cta_nombre','$num_cta','$descripcion','$saldo_inicial','$saldo_inicial','$tipo_banco','1')";
        return ejecutarConsulta($sql);
    } 
 
    //Implementamos un método para editar registros
    public function editar($idcuenta,$cta_cod,$cta_nombre,$num_cta,$descripcion,$saldo_inicial,$tipo_banco)
    {
        $sql="UPDATE cuenta SET cta_cod='$cta_cod',cta_nombre='$cta_nombre',num_cta='$num_cta',descripcion='$descripcion',saldo_inicial='$saldo_inicial',tipo_banco='$tipo_banco' WHERE idcuenta='$idcuenta'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idcuenta)
    {
        $sql="UPDATE cuenta SET condicion='0' WHERE idcuenta='$idcuenta'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idcuenta)
    {
        $sql="UPDATE cuenta SET condicion='1' WHERE idcuenta='$idcuenta'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcuenta)
    {
        $sql="SELECT * FROM cuenta WHERE idcuenta='$idcuenta'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT * FROM cuenta";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function selectBanco()
    {
        $sql="SELECT * FROM cuenta where condicion=1";
        return ejecutarConsulta($sql);      
    }
}
 
?>