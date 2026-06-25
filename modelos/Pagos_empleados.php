<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Pagoempleados
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($idusuario,$idficha_empleado,$fecha_hora_ini,$fecha_hora_fin,$descripcion,$horas_acumuladas,$horas_tarde,$horas_extra,$horas_feriado,$total_horas_pagar,$valor_hora,$sueldo_pagar,$bonificacion,$bonificacion_extra,$descuento,$sueldo_liquido_recibir,$idcuenta,$forma_pago,$cheque_auto_no,$fecha_generacion_pago,$bono14,$aguinaldo,$vacaciones,$fecha_hora_de,$fecha_hora_asta,$prestacion_a_sumar,$prestacion_a_pagar,$dtrabajados) 
    {


        $sql="INSERT INTO pagos_empleados (idficha_empleado,fecha_hora_ini,fecha_hora_fin,descripcion,horas_acumuladas,horas_tarde,horas_extra,horas_feriado,total_horas_pagar,valor_hora,sueldo_pagar,bonificacion,bonificacion_extra,descuento,sueldo_liquido_recibir,idcuenta,forma_pago,cheque_auto_no,estado,fecha_creacion,idusuario,bono14,aguinaldo,vacaciones,fecha_hora_de,fecha_hora_asta,prestacion_a_sumar,prestacion_a_pagar,dtrabajados) 
        VALUES ('$idficha_empleado','$fecha_hora_ini','$fecha_hora_fin','$descripcion','$horas_acumuladas','$horas_tarde','$horas_extra','$horas_feriado','$total_horas_pagar','$valor_hora','$sueldo_pagar','$bonificacion','$bonificacion_extra','$descuento','$sueldo_liquido_recibir','$idcuenta','$forma_pago','$cheque_auto_no','Pago Nomina','$fecha_generacion_pago','$idusuario','$bono14','$aguinaldo','$vacaciones','$fecha_hora_de','$fecha_hora_asta','$prestacion_a_sumar','$prestacion_a_pagar','$dtrabajados')";
        //return ejecutarConsulta($sql);
        $idpagoempleadonew=ejecutarConsulta_retornarID($sql);    
        //$idpagoempleadonew=1;
        $sqlCheque="INSERT INTO cheque (idcliente,idcuenta,fecha_hora_operacion,valor_cheque,descripcion,condicion,idusuario,idpagoempleado)
        VALUES ('$idficha_empleado','$idcuenta','$fecha_generacion_pago','$sueldo_liquido_recibir','$descripcion','1','$idusuario','$idpagoempleadonew')"; 
       ejecutarConsulta($sqlCheque);

       $sqlcuenta="UPDATE cuenta SET saldo_cuenta=saldo_cuenta-'$sueldo_liquido_recibir' WHERE idcuenta='$idcuenta'";
         return ejecutarConsulta($sqlcuenta);           
    }
 
    //Implementamos un método para editar registros
    public function editar($idpagoempleado,$cheque_auto_no)
    {
        $sql="UPDATE pagos_empleados SET cheque_auto_no='$cheque_auto_no' WHERE idpagoempleado='$idpagoempleado'";
        return ejecutarConsulta($sql);
    }
 
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idcategoria)
    {
        $sql="UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                    pe.idpagoempleado,
                    pe.idficha_empleado,
                    fe.nombre as empleado,
                    DATE(pe.fecha_hora_ini) as fecha_hora_ini,
                    DATE(pe.fecha_hora_fin) as fecha_hora_fin,
                    pe.descripcion,
                    pe.horas_acumuladas,
                    pe.horas_tarde,
                    pe.horas_extra,
                    pe.total_horas_pagar, 
                    pe.valor_hora,
                    pe.sueldo_pagar,
                    pe.bonificacion_extra,
                    pe.descuento,
                    pe.sueldo_liquido_recibir,
                    pe.idcuenta,
                    c.num_cta,
                    pe.forma_pago,
                    pe.cheque_auto_no,
                    pe.estado,
                    DATE(pe.fecha_creacion) as fecha_creacion
                from pagos_empleados pe 
                INNER JOIN ficha_empleado fe ON pe.idficha_empleado=fe.idficha_empleado
                INNER join cuenta c ON c.idcuenta=pe.idcuenta ORDER by pe.idpagoempleado DESC";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql="SELECT * FROM categoria where condicion=1"; 
        return ejecutarConsulta($sql);      
    }

    public function pagocabecera($idpagoempleado){
        $sql="SELECT 
                    pe.idpagoempleado,
                    pe.idficha_empleado,
                    fe.nombre as empleado,
                    DATE(pe.fecha_hora_ini) as fecha_hora_ini,
                    DATE(pe.fecha_hora_fin) as fecha_hora_fin,
                    pe.descripcion,
                    pe.horas_acumuladas,
                    pe.horas_tarde,
                    pe.horas_extra,
                    pe.total_horas_pagar,
                    pe.valor_hora,
                    pe.sueldo_pagar,
                    pe.bonificacion,
                    pe.bonificacion_extra,
                    pe.descuento,
                    pe.sueldo_liquido_recibir,
                    pe.idcuenta,
                    c.num_cta,
                    pe.forma_pago,
                    pe.cheque_auto_no,
                    pe.estado,
                    DATE(pe.fecha_creacion) as fecha_creacion,
                    fe.dpi_no,
                    fe.cod_empleado,
                    fe.telefono,
                    pe.bono14,
                    pe.aguinaldo,
                    pe.vacaciones,
                    DATE(pe.fecha_hora_de) as fechahorade,
                    DATE(pe.fecha_hora_asta) as fechahoraasta,
                    pe.prestacion_a_pagar,
                    pe.dtrabajados,
                    pe.prestacion_a_sumar,
                    (pe.sueldo_liquido_recibir+pe.descuento) as totalsindescuento 
                from pagos_empleados pe 
                INNER JOIN ficha_empleado fe ON pe.idficha_empleado=fe.idficha_empleado
                INNER join cuenta c ON c.idcuenta=pe.idcuenta WHERE pe.idpagoempleado='$idpagoempleado'";
        return ejecutarConsulta($sql);
    }

    public function pagocabecerDetalle($idpagoempleado){
        $sql="SELECT 
                    pe.idficha_empleado,
                    r.codigo,
                    r.fecha,
                    r.id,
                    r.accion
                from pagos_empleados pe 
                INNER JOIN ficha_empleado fe ON pe.idficha_empleado=fe.idficha_empleado
                INNER JOIN registro r ON r.codigo=fe.cod_empleado
                and
                date(r.fecha) between date(fecha_hora_ini) and date(fecha_hora_fin)
                 WHERE pe.idpagoempleado='$idpagoempleado' order by r.fecha";
        return ejecutarConsulta($sql);
    }


    public function CalculoHoras($fechaini,$fechafin,$idempleado)
    {
        $sql="SELECT cod_empleado,hora_entrada,
                hora_refaccion,hora_almuerzo,hora_salida 
              FROM ficha_empleado WHERE idficha_empleado=$idempleado";

        $exec=ejecutarConsultaSimpleFila($sql);

        $sqlHoras="SELECT * FROM registro WHERE 
        REPLACE(codigo, CHAR(10), '')=(SELECT cod_empleado FROM ficha_empleado WHERE idficha_empleado=$idempleado)
        AND  date(fecha) between '$fechaini' AND '$fechafin' order by fecha asc";
        #echo $sqlHoras;

        $execHoras=ejecutarConsulta($sqlHoras);

        $horasAcumuladas=0;
        $MinutosAcumuladas=0;
        $horasTardes=0;
        $horasMinutosTardes=0;
        $horasExtras=0;
        $minutosExtras=0;
        $inicio=0;
        $fechaActual="";

        $horaEntrada=strtotime($exec["hora_entrada"]);

        #echo "entrada Real: ".$exec["hora_entrada"]."<br>";

        $horaSalida=strtotime($exec["hora_salida"]);


        $horaEntradaReal="";

        $fechaRow="";

        $horarefacionentrada="";
        $horarefaccionsalida="";

        $horaalmuerzoentrada="";
        $numerohorasextras=0;

        $diastarde=0;

        while($req=$execHoras->fetch_object()){
            $numerohorasextras++;
            $dateEntradaToTime=strtotime($req->fecha);
            $dateEntradaToHora=date('H:i:s',$dateEntradaToTime);
            $dateEntradaToDate=date('Y-m-d',$dateEntradaToTime);



            if($fechaActual!=$dateEntradaToDate){
                $inicio=0;
                $horaEntradaReal="";
            }
            $fechaActual= $dateEntradaToDate;

            #entrada
            if($inicio==0){
                if(trim($req->accion)=="0"){
                    $dateEntrada=strtotime($dateEntradaToDate." ".$exec["hora_entrada"]);
                    if($dateEntrada<$dateEntradaToTime){
                        $horaEntradaReal=$dateEntradaToTime;
                        $rango1=new Datetime(date("Y-m-d h:i:s",$dateEntradaToTime));
                        $rango2=new Datetime(date("Y-m-d h:i:s",$dateEntrada));
                        $intervalo = $rango2->diff($rango1);
                        $horasTardes+=$intervalo->format("%H");
                        $horasMinutosTardes+=$intervalo->format("%i");
                        $diastarde++;
                    }else{
                        $horaEntradaReal=$dateEntradaToTime;
                    }
                }
            }

            #salida refaccion
            if($inicio==1){
                if(trim($req->accion)=="1"){
                    $dateEntrada=strtotime($dateEntradaToDate." ".$exec["hora_entrada"]);
                    if($horaEntradaReal>$dateEntrada){
                        $dateEntrada=$horaEntradaReal;
                    }
                    $rango1=new Datetime(date("Y-m-d H:i:s",$dateEntradaToTime));
                    $rango2=new Datetime(date("Y-m-d H:i:s",$dateEntrada));
                    $intervalo = $rango1->diff($rango2);
                    #echo "Horas de Almuerzo: ".date("Y-m-d h:i:s",$horaEntrada);
                    $horasAcumuladas+=$intervalo->format("%H");
                    $MinutosAcumuladas+=$intervalo->format("%i");
                    #echo "Horas Entrada: $horasAcumuladas:$MinutosAcumuladas <br>";
                }
            }

            #entrada refaccion
            if($inicio==2){
                $horarefacionentrada=date("Y-m-d H:i:s",$dateEntradaToTime);
                #echo "Hora Entrada Refaccion ".$horarefacionentrada." <br>";
            }

            #salida almuerzo
            if($inicio==3){
                if(trim($req->accion)=="1"){
                    $dateEntrada=strtotime($dateEntradaToDate." ".$exec["hora_entrada"]);
                    $rango1=new Datetime(date("Y-m-d H:i:s",$dateEntradaToTime));
                    $rango2=new Datetime($horarefacionentrada);
                    $intervalo = $rango2->diff($rango1);
                    #echo "Horas Salida Almuerzo: ".date("Y-m-d h:i:s",$dateEntradaToTime);
                    $horasAcumuladas+=$intervalo->format("%H");
                    $MinutosAcumuladas+=$intervalo->format("%i");
                    #echo "Horas Trabajadas Almuerzo: ".$intervalo->format("%H").":".$intervalo->format("%i")." <br>";
                }
            }

            #entrada almuerzo
            if($inicio==4){
                $horaalmuerzoentrada=date("Y-m-d H:i:s",$dateEntradaToTime);
                #echo "Hora Entrada Refaccion ".$horarefacionentrada." <br>";
            }

            if($inicio==5){
                #echo "inicio".$inicio;
                if(trim($req->accion)=="1"){
                    $horaEstablecidaSalida=strtotime($dateEntradaToDate." ".$exec["hora_salida"]);
                    $rango1=new Datetime(date("Y-m-d H:i:s",$horaEstablecidaSalida));
                    $rango2=new Datetime($horaalmuerzoentrada);
                    #echo "Rango 1 ".date_format ($rango1 , "Y-m-d h:i:s")." <br>";
                    $intervalo = $rango2->diff($rango1);
                    #echo "Horas Salida: ".date("Y-m-d h:i:s",$dateEntradaToTime)." <br>";
                    $horasAcumuladas+=$intervalo->format("%H");
                    $MinutosAcumuladas+=$intervalo->format("%i");
                    #echo "Horas Trabajadas: ".$intervalo->format("%H").":".$intervalo->format("%i")." <br>";

                    #horas extras
                    #echo "Horas Salida: ".date("Y-m-d h:i:s",$dateEntradaToTime)." <br>";
                    if($dateEntradaToTime>$horaEstablecidaSalida){
                        $rango1=new Datetime(date("Y-m-d H:i:s",$dateEntradaToTime));
                        $rango2=new Datetime(date("Y-m-d H:i:s",$horaEstablecidaSalida));
                        $intervalo = $rango2->diff($rango1);
                        $horasExtras+=$intervalo->format("%H");
                        $minutosExtras+=$intervalo->format("%i");
                        #echo "Horas Extras:  ".$intervalo->format("%H").":".$intervalo->format("%i")." <br>";
                    }

                }
            }

            $inicio++;
            
        }


        $horasBrutas=($horasAcumuladas)*60;
        $minutosBrutos=$MinutosAcumuladas;
        $horasTrabajadas=($horasBrutas+$minutosBrutos)/60;

        /*$horasTardesBrutas=$horasTardes*60;
        $minutostardesbrutos=$minutostardesbrutos;
        $horastardesreales=($horasTardesBrutas+$minutostardesbrutos)/60;*/

        $horasextrabrutas=$horasExtras*60;
        $minutosextrasBrutos=$minutosExtras;
        $horasextrasreales=($horasextrabrutas+$minutosextrasBrutos)/60;

        #echo "Horas Trabajadas: ".$horasTrabajadas."<br>";
        #echo "Horas Extras: ".$horasextrasreales."<br>";
        $horasAcumuladas=$horasTrabajadas+$horasextrasreales; 
        #echo $numerohorasextras;
        #echo "HorasAcumuladas: ".$horasAcumuladas;

        
        $fecha1 = new DateTime($fechaini);
        $fecha2 = new DateTime($fechafin);
        $resultado = $fecha1->diff($fecha2);

        $dias=$resultado->format('%a');

        $horasextras=0;

        #echo "Horas Acumuladas: ".$horasAcumuladas."<br>";
      

        for($i=0;$i<=30;$i++){
            if($dias==$i){
                if($horasAcumuladas>(8*($dias))){
                    $horasextras=horaseX($horasAcumuladas,$dias);
                    if($horasextras<0){
                        $horasextras=0;
                    }
                }
            }
        }

        echo "HorasAcumuladas:".convertTime($horasAcumuladas)."@HorasTarde:".$diastarde."@HorasExtrasHueco:".convertTime($horasextras);

    }
    
    public function ObtenerRegistros($fechaini,$fechafin,$idempleado){
        $sqlHoras="SELECT * FROM registro WHERE 
        REPLACE(codigo, CHAR(10), '')=(SELECT cod_empleado FROM ficha_empleado WHERE idficha_empleado=$idempleado)
        AND  date(fecha) between '$fechaini' AND '$fechafin' order by fecha asc";
        #echo $sqlHoras;

        $execHoras=ejecutarConsulta($sqlHoras);
        return $execHoras;
    }

    public function AgregarRegistros($codigo,$tipo,$fecha){
        $sqlHoras="INSERT INTO registro(codigo,accion,fecha,codsubida)
                    VALUES('$codigo','$tipo','$fecha','".uniqid()."')";
        ejecutarConsulta($sqlHoras);
    }

    public function modificarregistro($id,$tipo,$fecha)
    {
        $sql="UPDATE registro SET accion='$tipo',fecha='$fecha' WHERE id=".$id;
        ejecutarConsulta($sql);
    }

    public function EliminarRegistros($id)
    {
        $sql="delete from registro where id=".$id;
        ejecutarConsulta($sql);
    }


    public function mostrar($idpagoempleado)
    {
        $sql="SELECT 
                pe.idpagoempleado,
                pe.idficha_empleado,
                fe.nombre as empleado,
                DATE(pe.fecha_hora_ini) as fecha_hora_ini,
                DATE(pe.fecha_hora_fin) as fecha_hora_fin,
                pe.descripcion,
                
                pe.horas_acumuladas,
                pe.horas_tarde,
                pe.horas_extra,
                pe.horas_feriado,
                pe.total_horas_pagar,
                pe.valor_hora,
                pe.sueldo_pagar,
                pe.bonificacion,
                pe.bonificacion_extra,
                pe.descuento,
                pe.sueldo_liquido_recibir,
                pe.idcuenta,
                c.cta_nombre,
                c.num_cta,
                c.saldo_cuenta,
                pe.forma_pago,
                pe.cheque_auto_no,
                pe.estado,
                pe.fecha_creacion,
                pe.idusuario,
                u.nombre as usuario,
                pe.bono14,
                pe.aguinaldo,
                pe.vacaciones,
                pe.fecha_hora_de,
                pe.fecha_hora_asta,
                pe.prestacion_a_sumar,
                pe.prestacion_a_pagar,
                pe.dtrabajados
                FROM pagos_empleados pe
                INNER JOIN ficha_empleado fe ON pe.idficha_empleado=pe.idficha_empleado
                INNER JOIN cuenta c ON pe.idcuenta=c.idcuenta
                INNER JOIN usuario u ON u.idusuario=pe.idusuario WHERE pe.idpagoempleado='$idpagoempleado'";
        return ejecutarConsultaSimpleFila($sql);
    }    

}

function horaseX($hA,$dias)
{
    #echo "Horas Dias2: ".($dias)."<br>";
    if($dias==5 || $dias=6 || $dias==7){
        return $hA-45;
    }
    else if($dias==15){
        return $hA-96;
    }
    else if($dias==29 || $dias==30 || $dias==31){
        return $hA-193;
    }
    return $hA-(8*(($dias==0?1:$dias)));
}

function convertTime($dec)
{
    // start by converting to seconds
    $seconds = ($dec * 3600);
    // we're given hours, so let's get those the easy way
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = floor($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    // return the time formatted HH:MM:SS
    return lz($hours).":".lz($minutes).":00";
}

// lz = leading zero
 function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}


 
?>