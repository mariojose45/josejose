<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Cheque
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    } 
 
    //Implementamos un método para insertar registros
    public function insertar($idusuario,$idcliente,$idcuenta,$fac_serie,$fac_documento,$fecha_hora_factura,$fecha_hora_operacion,$valor_cheque,$descripcion,$saldo_cuenta) 
    {
        $sql="INSERT INTO cheque (idusuario,idcliente,idcuenta,fac_serie,fac_documento,fecha_hora_factura,fecha_hora_operacion,valor_cheque,descripcion,condicion)
        VALUES ('$idusuario','$idcliente','$idcuenta','$fac_serie','$fac_documento','$fecha_hora_factura','$fecha_hora_operacion','$valor_cheque','$descripcion','1')";
        $idchequenew=ejecutarConsulta_retornarID($sql); 

        $ressaldocuenta=$saldo_cuenta-$valor_cheque;
        $sql="INSERT INTO operaciones_entre_bancos (idcheque,fecha_hora_operacion_cheque,valor_cheque,idcuenta,saldo_cuenta,saldo_final_cuenta,condicion)
        VALUES ('$idchequenew','$fecha_hora_operacion','$valor_cheque','$idcuenta','$saldo_cuenta','$ressaldocuenta','1')";
        ejecutarConsulta($sql);   
 
        $sqlcuenta="UPDATE cuenta SET saldo_cuenta=saldo_cuenta-'$valor_cheque' WHERE idcuenta='$idcuenta'";
        return ejecutarConsulta($sqlcuenta);        
    }
  


    //Implementamos un método para desactivar categorías
    public function desactivar($idcheque)
    {
        $sqlidcheque="SELECT * FROM cheque WHERE idcheque='$idcheque'"; 
        $res=ejecutarConsulta($sqlidcheque);
        $residcheque=$res->fetch_object();
        $idcuenta=$residcheque->idcuenta;
        $valor_cheque=$residcheque->valor_cheque;

        $sql="UPDATE cuenta SET saldo_cuenta=saldo_cuenta+'$valor_cheque' WHERE idcuenta='$idcuenta'";
        ejecutarConsulta($sql); 

        $sqloperacion="UPDATE operaciones_entre_bancos SET condicion='0' WHERE idcheque='$idcheque'";
        ejecutarConsulta($sqloperacion);          

        $sql1="UPDATE cheque SET condicion='0' WHERE idcheque='$idcheque'";
        return ejecutarConsulta($sql1);  

    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                che.idcheque,
                che.idcliente,
                p.nombre as cliente,
                che.idcuenta,
                c.cta_cod,
                c.cta_nombre,
                c.num_cta,
                che.fac_serie,
                che.fac_documento,
                DATE(che.fecha_hora_factura) as fecha_factura,
                DATE(che.fecha_hora_operacion) as fecha_operacion,
                che.valor_cheque,
                che.descripcion,
                che.condicion,
                che.idusuario,
                u.nombre as usuario
                FROM cheque che
                INNER JOIN cuenta c ON c.idcuenta=che.idcuenta
                INNER JOIN usuario u ON u.idusuario=che.idusuario
                INNER JOIN persona p ON p.idpersona=che.idcliente
            ORDER BY che.idcheque desc";
        return ejecutarConsulta($sql);      
    }

    public function pagocheque($idcheque)
    {
        $sql="SELECT 
                che.idcheque,
                che.idcliente,
                p.nombre as cliente,
                che.idcuenta,
                c.cta_nombre, 
                che.fac_serie,
                che.fac_documento,
                DATE(che.fecha_hora_factura) as fechafactura,
                DATE(che.fecha_hora_operacion) as fechaoperacion,
                che.valor_cheque,
                che.descripcion,
                che.condicion,
                che.idusuario,
                u.nombre as usuario,
                s.idsucursal,
                s.nombre as sucursal_nombre, 
                s.direccion as sucursal_direccion,
                s.telefono as sucursal_telefono,
                s.nit as sucursal_nit,
                s.email as sucursal_email,
                s.imagen as sucursal_imagen,
                s.condicion as sucursal_condicion                 
                FROM cheque che
                INNER JOIN persona p ON p.idpersona=che.idcliente
                INNER JOIN cuenta c ON c.idcuenta=che.idcuenta
                INNER JOIN usuario u ON u.idusuario=che.idusuario
                INNER JOIN sucursal s ON s.idsucursal=u.idsucursal
                WHERE che.idcheque='$idcheque' ";
        return ejecutarConsulta($sql);      
    }
    public function pagochequenomina($idingreso)
    {
        $sql="SELECT 
                che.idcheque,
                che.idcliente,
                p.nombre as cliente,
                che.idcuenta,
                c.cta_nombre, 
                che.fac_serie,
                che.fac_documento,
                DATE(che.fecha_hora_factura) as fechafactura,
                DATE(che.fecha_hora_operacion) as fechaoperacion,
                che.valor_cheque,
                che.descripcion,
                che.condicion,
                che.idusuario,
                u.nombre as usuario,
                i.no_cheque
                FROM cheque che
                INNER JOIN persona p ON p.idpersona=che.idcliente
                INNER JOIN cuenta c ON c.idcuenta=che.idcuenta
                INNER JOIN usuario u ON u.idusuario=che.idusuario
                INNER JOIN ingreso i on i.idingreso=che.idpagoempleado
                WHERE che.idpagoempleado='$idingreso' ";
        return ejecutarConsulta($sql);      
    }  

    public function pagochequenomina2($idingreso)
    {
        $sql="SELECT 
                i.idingreso,
                i.idproveedor,
                p.nombre as cliente,
                i.idusuario,
                u.nombre as usuario,
                i.tipo_comprobante,
                i.serie_comprobante,
                i.num_comprobante,
                DATE(i.fecha_hora) as fechafac, 
                i.impuesto,
                i.total_compra,
                i.estado,
                i.forma_pago,
                i.dias_credito,
                i.fecha_hora_pago_credito,
                i.valor_pagar,
                i.saldo_ingreso,
                i.tipo_pago,
                i.no_cheque,
                i.fecha_hora_generacion_pago,
                i.tipo_banco,
                i.numero_boleta,
                i.recibo_caja_numero,
                i.direccion_entrega_orden_compra,
                i.fecha_entrega_orden_compra,
                i.observacion_orden_compra
                from ingreso i  
                INNER join persona p ON i.idproveedor=p.idpersona
                INNER join usuario u on i.idusuario=u.idusuario
                WHERE i.idingreso='$idingreso' ";
        return ejecutarConsulta($sql);      
    }        

  
}
 
?>