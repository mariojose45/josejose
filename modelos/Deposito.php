<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Deposito
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    } 
 
    //Implementamos un método para insertar registros
    public function insertar($idusuario,$idcliente,$idcuenta,$fecha_hora,$tipo_banco,$nombre_agencia,$deposito_no,$valor_deposito,$descripcion,$saldo_cuenta) 
    {
        $sql="INSERT INTO deposito (idusuario,idcliente,idcuenta,fecha_hora,tipo_banco,nombre_agencia,deposito_no,valor_deposito,descripcion,condicion)
        VALUES ('$idusuario','$idcliente','$idcuenta','$fecha_hora','$tipo_banco','$nombre_agencia','$deposito_no','$valor_deposito','$descripcion','1')";
        $iddepositonew=ejecutarConsulta_retornarID($sql); 
        
        $ressaldocuenta=$saldo_cuenta+$valor_deposito;
        $sqloperacionesentrebancos="INSERT INTO operaciones_entre_bancos (iddeposito,fecha_hora_deposito,valor_deposito,idcuenta,saldo_cuenta,saldo_final_cuenta,condicion)
        VALUES ('$iddepositonew','$fecha_hora','$valor_deposito','$idcuenta','$saldo_cuenta','$ressaldocuenta','1')";
        ejecutarConsulta($sqloperacionesentrebancos);        



        $sqlcuenta="UPDATE cuenta SET saldo_cuenta=saldo_cuenta+'$valor_deposito' WHERE idcuenta='$idcuenta'";
        return ejecutarConsulta($sqlcuenta);        
    } 
   


    //Implementamos un método para desactivar categorías
    public function desactivar($iddeposito)
    {
        $sqliddeposito="SELECT * FROM deposito WHERE iddeposito='$iddeposito'"; 
        $res=ejecutarConsulta($sqliddeposito);
        $residdeposito=$res->fetch_object();
        $idcuenta=$residdeposito->idcuenta;
        $valor_deposito=$residdeposito->valor_deposito;

        $sql="UPDATE cuenta SET saldo_cuenta=saldo_cuenta-'$valor_deposito' WHERE idcuenta='$idcuenta'";
        ejecutarConsulta($sql);  

        $sqloperacion="UPDATE operaciones_entre_bancos SET condicion='0' WHERE iddeposito='$iddeposito'";
        ejecutarConsulta($sqloperacion);            

        $sql1="UPDATE deposito SET condicion='0' WHERE iddeposito='$iddeposito'";
        return ejecutarConsulta($sql1);        
    }
  

 
 
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
d.iddeposito,
d.idusuario,
u.nombre as usuario,
d.idcliente,
p.nombre as cliente,
d.idcuenta,
c.cta_cod,
c.cta_nombre,
c.num_cta,
DATE(d.fecha_hora) as fecha,
d.tipo_banco,
d.nombre_agencia,
d.deposito_no,
d.valor_deposito,
d.descripcion,
d.condicion
FROM deposito d
INNER JOIN cuenta c ON c.idcuenta=d.idcuenta
INNER JOIN usuario u ON u.idusuario=d.idusuario
INNER JOIN persona p ON p.idpersona=d.idcliente ";
        return ejecutarConsulta($sql);      
    }

}
 
?>