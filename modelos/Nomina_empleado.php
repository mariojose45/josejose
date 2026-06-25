<?php
if (strlen(session_id()) < 1)
    session_start();
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Nomina_empleado
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar(
        $codigo_empleado,
        $nombres,
        $cui,
        $nit,
        $direccion,
        $fecha_nacimiento,
        $hijos,
        $sexo,
        $estado_civil,
        $telefono,
        $nacionalidad,
        $nivel_educativo,
        $puesto,
        $fecha_inicio_laboral,
        $salario_base,
        $bonificacion,
        $bono_productividad,
        $salario_extra,
        $descuento_igss,
        $descuento_isr,
        $descuento_prestamo,
        $otros_descuentos,
        $anticipo_salarial,
        $fecha_fin_laboral,
        $edad
    ) {
        $sql = "INSERT INTO empleados (
            nombres, cui, nit, direccion, fecha_nacimiento, hijos, sexo, estado_civil, telefono,
            nacionalidad, nivel_educativo, puesto, fecha_inicio_laboral, 
            salario_base, bonificacion, bono_productividad, salario_extra, 
            descuento_igss, descuento_isr, descuento_prestamo, otros_descuentos, anticipo_salarial, 
            fecha_fin_laboral, condicion, edad,codigo
        )
        VALUES (
            '$nombres', '$cui', '$nit', '$direccion', '$fecha_nacimiento', '$hijos', '$sexo', '$estado_civil', '$telefono',
            '$nacionalidad', '$nivel_educativo', '$puesto', '$fecha_inicio_laboral', 
            '$salario_base', '$bonificacion', '$bono_productividad', '$salario_extra', 
            '$descuento_igss', '$descuento_isr', '$descuento_prestamo', '$otros_descuentos', '$anticipo_salarial', 
            '$fecha_fin_laboral', '1', '$edad','$codigo_empleado'
        )";
        //print_r($sql);
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar(
        $idempleado_r,
        $codigo_empleado,
        $nombres,
        $cui,
        $nit,
        $direccion,
        $fecha_nacimiento,
        $hijos,
        $sexo,
        $estado_civil,
        $telefono,
        $nacionalidad,
        $nivel_educativo,
        $puesto,
        $fecha_inicio_laboral,
        $salario_base,
        $bonificacion,
        $bono_productividad,
        $salario_extra,
        $descuento_igss,
        $descuento_isr,
        $descuento_prestamo,
        $otros_descuentos,
        $anticipo_salarial,
        $fecha_fin_laboral,
        $edad
    ) {
        $sql = "UPDATE empleados SET 
            nombres = '$nombres', 
            cui = '$cui', 
            nit = '$nit', 
            direccion = '$direccion', 
            fecha_nacimiento = '$fecha_nacimiento', 
            hijos = '$hijos', 
            sexo = '$sexo', 
            estado_civil = '$estado_civil', 
            telefono = '$telefono',
            nacionalidad = '$nacionalidad', 
            nivel_educativo = '$nivel_educativo', 
            puesto = '$puesto', 
            fecha_inicio_laboral = '$fecha_inicio_laboral', 
            salario_base = '$salario_base', 
            bonificacion = '$bonificacion', 
            bono_productividad = '$bono_productividad', 
            salario_extra = '$salario_extra', 
            descuento_igss = '$descuento_igss', 
            descuento_isr = '$descuento_isr', 
            descuento_prestamo = '$descuento_prestamo', 
            otros_descuentos = '$otros_descuentos', 
            anticipo_salarial = '$anticipo_salarial', 
            fecha_fin_laboral = '$fecha_fin_laboral',
            edad = '$edad',
            codigo = '$codigo_empleado'
        WHERE idempleado = '$idempleado_r'";
        //print_r($sql);
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar categorías
    public function desactivar($idempleado)
    {
        $sql = "UPDATE empleados SET condicion='0' WHERE idempleado='$idempleado'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function activar($idempleado)
    {
        $sql = "UPDATE empleados SET condicion='1' WHERE idempleado='$idempleado'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idempleado)
    {
        $sql = "SELECT * FROM empleados WHERE idempleado='$idempleado'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM empleados";
        return ejecutarConsulta($sql);
    }
    //Implementar un método para listar los registros y mostrar en el select
    public function select()
    {
        $sql = "SELECT * FROM empleados where condicion=1";
        return ejecutarConsulta($sql);
    }


}

?>