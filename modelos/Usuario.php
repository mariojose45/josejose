<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Usuario
{
    //Implementamos nuestro constructor
    public function __construct()
    {
  
    }  
 
    //Implementamos un método para insertar registros
    public function insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,
    $cargo,$login,$clave,$imagen,$permisos,$comision,$sucursales,$meta)
    {
        $sql="INSERT INTO usuario (nombre,tipo_documento,num_documento,direccion,telefono,email,
        cargo,login,clave,imagen,condicion,idsucursal,comision,meta)
        VALUES ('$nombre','$tipo_documento','$num_documento','$direccion','$telefono',
        '$email','$cargo','$login','$clave','$imagen','1','0','$comision','$meta')";
        //return ejecutarConsulta($sql);
        $idusuarionew=ejecutarConsulta_retornarID($sql);
 
        $num_elementos=0;
        $sw=true; 
 
        while ($num_elementos < count($permisos))
        {
            $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuarionew', '$permisos[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }

        //Insertar las sucursales asignadas
        $num_elementos=0;
        while ($num_elementos < count($sucursales))
        {
            $sql_detalle = "INSERT INTO usuario_sucursal(idusuario, idsucursal) VALUES('$idusuarionew', '$sucursales[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }
 
        return $sw; 
    }
 
    //Implementamos un método para editar registros
    public function editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,
    $telefono,$email,$cargo,$login,$clave,$imagen,$permisos,$comision,$sucursales,$meta)
    {
        $sql="UPDATE usuario SET nombre='$nombre',tipo_documento='$tipo_documento',
        num_documento='$num_documento',direccion='$direccion',telefono='$telefono',
        email='$email',cargo='$cargo',login='$login',imagen='$imagen',
        comision='$comision',meta='$meta' WHERE idusuario='$idusuario'";
        ejecutarConsulta($sql);
 
        //Eliminamos todos los permisos asignados para volverlos a registrar
        $sqldel="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
        ejecutarConsulta($sqldel);

        //Eliminamos todas las sucursales asignadas para volverlas a registrar
        $sqldel="DELETE FROM usuario_sucursal WHERE idusuario='$idusuario'";
        ejecutarConsulta($sqldel);
 
        $num_elementos=0;
        $sw=true;
 
        while ($num_elementos < count($permisos))
        {
            $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuario', '$permisos[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }

        //Insertar las sucursales asignadas
        $num_elementos=0;
        while ($num_elementos < count($sucursales))
        {
            $sql_detalle = "INSERT INTO usuario_sucursal(idusuario, idsucursal) VALUES('$idusuario', '$sucursales[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }
 
        return $sw;
 
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idusuario)
    {
        $sql="UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idusuario)
    {
        $sql="UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idusuario)
    {
        $sql="SELECT * FROM usuario WHERE idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT 
                u.idusuario,
                u.nombre,
                u.tipo_documento,
                u.num_documento,
                u.direccion,
                u.telefono,
                u.email,
                u.cargo,
                u.login,
                u.imagen,
                u.idsucursal,
                u.condicion,
                u.comision,
                u.meta
                FROM usuario u ";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los permisos marcados
        public function listarmarcados($idusuario)
        {
            $sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
            return ejecutarConsulta($sql);
        }

        //Implementar un método para listar las sucursales marcadas
        public function selectEmpresaMArcados($idusuario)
        {
            $sql="SELECT * FROM usuario_sucursal WHERE idusuario='$idusuario'";
            return ejecutarConsulta($sql);
        }

        //Implementar un método para listar las sucursales marcadas
        public function mostrarSucursales($idusuario)
        {
            $sql="SELECT 
                    us.idusuario_sucursal,
                    us.idusuario,
                    us.idsucursal,
                    s.nombre AS nombre_sucursal,
                    s.direccion AS direccion_sucursal
                FROM usuario_sucursal us 
                INNER JOIN sucursal  s ON s.idsucursal=us.idsucursal 
                WHERE us.idusuario='$idusuario'";
            return ejecutarConsulta($sql);
        }
    //Función para verificar el acceso al sistema
    public function verificar($login,$clave)
    {
        $sql="SELECT 
                u.idusuario,
                u.nombre,
                u.tipo_documento,
                u.num_documento,
                u.telefono,
                u.email,
                u.cargo,
                u.imagen,
                u.login,
                u.idsucursal,
                s.clave_ordenes,
                s.clave_ingresos,
                s.clave_ventas
            FROM usuario u
            inner join usuario_sucursal us ON us.idusuario=u.idusuario
            inner join sucursal s ON s.idsucursal=us.idsucursal
            WHERE u.login='$login' AND u.clave='$clave' AND u.condicion='1' limit 1 "; 
        return ejecutarConsulta($sql);  
    }


     public function selectEmpresa()
    {
        $sql="SELECT * FROM sucursal where condicion=1";
        return ejecutarConsulta($sql);      
    }

    public function selectUsuario()
    {
        $sql="SELECT * FROM usuario where condicion=1";
        return ejecutarConsulta($sql);      
    }  
    
    
    public function editarClave($idusuario,$nueva_clave)
    {
        $sql="UPDATE usuario SET clave='$nueva_clave' WHERE idusuario='$idusuario'";
        ejecutarConsulta($sql);

        return $sql;
 
    }    

    
}
 
?>