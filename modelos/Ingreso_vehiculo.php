<?php 

ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 


 
Class Ingreso_vehiculo
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($idcliente, $codigo_cliente, $nit, $nombre_cliente, $telefono_cliente, $direccion_cliente, $correo_cliente, $tipo_documento_cliente, 
    $no_placa,$no_chasis,$serie,$no_motor,$modelo,$km,$trabajos_detalle,$observaciones_adicionales,$idvendedor,$tipo_cliente,
    $imagen1,$imagen2,$imagen3,$imagen4,$imagen5,$imagen6,$imagen7,$imagen8,
    $descripcion1,$descripcion2,$descripcion3,$descripcion4,$descripcion5,$descripcion6,$descripcion7,$descripcion8,$revisiones)
    {
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
            if ($idcliente == '0') 
            {
                $sqlcorrelativo = "UPDATE add_correlativo SET 
                                    codigo_cliente=codigo_cliente+1 
                                    WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                ejecutarConsulta($sqlcorrelativo); 

                $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["codigo_cliente"]; 
                $codigo_cliente = 'COD' . $corre;

                $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre, 
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono,
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente,fechaCreacion)
                                            VALUES ('Cliente',
                                                    '$nombre_cliente',
                                                    '$tipo_documento_cliente',
                                                    '$nit',
                                                    '$direccion_cliente',
                                                    '$telefono_cliente',
                                                    '$correo_cliente',
                                                    '$tipo_cliente',
                                                    '$codigo_cliente','$fechaHora')";
                $residcliente = ejecutarConsulta_retornarID($sqlcliente); 

                if (!$residcliente) {
                    throw new Exception("Error al insertar nuevo cliente.");
                }
            } else  
            {
                $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente'";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["codigo_cliente"]; 

                               // Verificamos si $corre es '0', está vacío o es null
                if (empty($corre) || $corre == '0') {
                    // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente
  
                    $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlcorrelativo); 

                    $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                    $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                    $corress = $correlativos["codigo_cliente"]; 
                    $codigo_clientes = 'COD' . $corress;    

                    $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente'";
                    ejecutarConsulta($sqlupdadtepersona);                         
                }

                $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion_cliente',
                                            telefono='$telefono_cliente',
                                            email='$correo_cliente',
                                            tipo_documento='$tipo_documento_cliente',
                                            nombre='$nombre_cliente',
                                            tipo_cliente='$tipo_cliente'
                                    WHERE idpersona='$idcliente'";

                ejecutarConsulta($sqlcorrelativo);  
                $residcliente = $idcliente;
            }  
        ///////
        /*
        $check_bateria = $estadoChecks['check_bateria'] ? 'Si' : 'No';
        $check_tcs = $estadoChecks['check_tcs'] ? 'Si' : 'No';
        $check_motor = $estadoChecks['check_motor'] ? 'Si' : 'No';
        $check_aceite = $estadoChecks['check_aceite'] ? 'Si' : 'No';
        $check_airbag = $estadoChecks['check_airbag'] ? 'Si' : 'No';
        $check_tpms = $estadoChecks['check_tpms'] ? 'Si' : 'No';
        $check_abs = $estadoChecks['check_abs'] ? 'Si' : 'No';
        
        $checkLado1 = $estadoVehiculo['lado1']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado1 = $estadoVehiculo['lado1']['descripcion'];
        
        $checkLado2 = $estadoVehiculo['lado2']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado2 = $estadoVehiculo['lado2']['descripcion'];

        $checkLado3 = $estadoVehiculo['lado3']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado3 = $estadoVehiculo['lado3']['descripcion'];
        
        $checkLado4 = $estadoVehiculo['lado4']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado4 = $estadoVehiculo['lado4']['descripcion'];

        $checkLado5 = $estadoVehiculo['lado5']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado5 = $estadoVehiculo['lado5']['descripcion'];
        */

        $sql = "INSERT INTO ingreso_vehiculo (
                    idcliente, idusuario, idsucursal, estado, fechaCreacion, no_placa, no_chasis, serie, no_motor, modelo, km,
                    imagen1,imagen2,imagen3,imagen4,imagen5,imagen6,imagen7,imagen8,
                    descripcion1,descripcion2,descripcion3,descripcion4,descripcion5,descripcion6,descripcion7,descripcion8,
                    trabajos_detalle,observaciones_adicionales,idvendedor
                ) VALUES (
                    '$residcliente', '".$_SESSION["idusuario"]."', '".$_SESSION["idsucursal"]."', 'Aceptado', 
                    NOW(), '$no_placa', '$no_chasis', '$serie', '$no_motor', '$modelo', '$km','$imagen1','$imagen2','$imagen3','$imagen4','$imagen5','$imagen6','$imagen7',
                    '$imagen8',
                    '$descripcion1','$descripcion2','$descripcion3','$descripcion4','$descripcion5','$descripcion6','$descripcion7','$descripcion8',
                    '$trabajos_detalle','$observaciones_adicionales','$idvendedor'
                )";
        $idingreso_vehiculonew=ejecutarConsulta_retornarID($sql);

        foreach ($revisiones as $revision) { 
            $nombre_revision = $revision['nombre'];
            $estado_100 = $revision['estado100'] ? 1 : 0;
            $estado_75 = $revision['estado75'] ? 1 : 0;
            $estado_50 = $revision['estado50'] ? 1 : 0;
            $cambio_sugerido = $revision['cambioSugerido'] ? 1 : 0;

            $sql_revision = "INSERT INTO detalle_revision_vehiculo (idingreso_vehiculo, nombre_revision, estado_100, estado_75, estado_50, cambio_sugerido)
                            VALUES ('$idingreso_vehiculonew',
                                    '$nombre_revision',
                                    '$estado_100',
                                    '$estado_75',
                                    '$estado_50',
                                    '$cambio_sugerido')";

            if (!ejecutarConsulta($sql_revision)) {
                $sw = false;
            }
        }
        return $idingreso_vehiculonew;
    }

    public function selectMarca(){
        $sql="SELECT * FROM marca where condicion=1";
        return ejecutarConsulta($sql);      
    }

    public function listar($fecha_inicio_reporte,$fecha_fin_reporte)
    {
        $sql="SELECT
            i.idingreso_vehiculo,
            i.idcliente,
            i.idusuario,
            i.idsucursal,
            i.estado,
            i.fechaCreacion,
            i.no_placa,
            i.no_chasis,
            i.serie,
            i.no_motor,
            i.modelo,
            i.km,
            i.facturado,
            i.checkLado1,
            i.descripcionLado1,
            i.checkLado2,
            i.descripcionLado2,
            i.checkLado3,
            DATE(i.fechaCreacion) AS fecha,
            i.descripcionLado3,
            i.checkLado4,
            i.descripcionLado4,
            i.checkLado5,
            i.descripcionLado5,
            i.check_bateria,
            i.check_tcs,
            i.check_motor,
            i.check_aceite,
            i.check_airbag,
            i.check_tpms,
            i.check_abs,
            i.trabajos_detalle,
            i.observaciones_adicionales,
            i.idvendedor,
            p.nombre AS nombre_cliente,
            u.nombre AS nombre_usuario,
            m.nombre AS nombre_marca
        FROM
            ingreso_vehiculo i
            INNER JOIN persona p ON i.idcliente = p.idpersona
            INNER JOIN usuario u ON i.idusuario = u.idusuario
            INNER JOIN marca m ON i.idvendedor = m.idmarca
            WHERE DATE(i.fechaCreacion) >= '$fecha_inicio_reporte'
            AND DATE(i.fechaCreacion) <= '$fecha_fin_reporte'";
        return ejecutarConsulta($sql);      
    }

    public function mostrar($idingreso_vehiculo)
    {
        $sql="SELECT
            i.idingreso_vehiculo,
            i.idcliente,
            i.idusuario,
            i.idsucursal,
            i.estado,
            i.fechaCreacion,
            i.no_placa,
            i.no_chasis,
            i.serie,
            i.no_motor,
            i.modelo,
            i.km,
            i.checkLado1,
            i.descripcionLado1,
            i.checkLado2,
            i.descripcionLado2,
            i.checkLado3,
            DATE(i.fechaCreacion) AS fecha,
            i.descripcionLado3,
            i.checkLado4,
            i.descripcionLado4,
            i.checkLado5,
            i.descripcionLado5,
            i.check_bateria,
            i.check_tcs,
            i.check_motor,
            i.check_aceite,
            i.check_airbag,
            i.check_tpms,
            i.check_abs,
            i.trabajos_detalle,
            i.observaciones_adicionales,
            i.idvendedor,
            i.imagen1,
            i.imagen2,
            i.imagen3,
            i.imagen4,
            i.imagen5,
            i.imagen6,
            i.imagen7,
            i.imagen8,
            i.descripcion1,
            i.descripcion2,
            i.descripcion3,
            i.descripcion4,
            i.descripcion5,
            i.descripcion6,
            i.descripcion7,
            i.descripcion8,
            p.nombre AS nombre_cliente,
            p.codigo_cliente,
            p.num_documento as nit,
            p.email AS correo_cliente,
            p.telefono AS telefono_cliente,
            p.direccion AS direccion_cliente,
            p.tipo_cliente,
            p.idpersona,
            u.nombre AS nombre_usuario,
            m.nombre AS nombre_marca,
            m.idmarca,
            s.nombre AS nombre_sucursal,
            s.direccion AS direccion_sucursal,
            s.telefono AS telefono_sucursal,
            s.nit AS nit_sucursal,
            s.imagen AS imagen_sucursal
        FROM
            ingreso_vehiculo i
            INNER JOIN persona p ON i.idcliente = p.idpersona
            INNER JOIN usuario u ON i.idusuario = u.idusuario
            INNER JOIN marca m ON i.idvendedor = m.idmarca
            INNER JOIN sucursal s ON i.idsucursal = s.idsucursal
            WHERE i.idingreso_vehiculo = '$idingreso_vehiculo'";
            //print_r($sql);
        return ejecutarConsultaSimpleFila($sql);      
    }

    public function editar($idingreso_vehiculo, $idcliente, $codigo_cliente, $nit, $nombre_cliente, $telefono_cliente, $direccion_cliente, $correo_cliente, $tipo_documento_cliente, 
    
    $no_placa,$no_chasis,$serie,$no_motor,$modelo,$km,$trabajos_detalle,$observaciones_adicionales,$idvendedor,$tipo_cliente,
    $imagen1,$imagen2,$imagen3,$imagen4,$imagen5,$imagen6,$imagen7,$imagen8,
    $descripcion1,$descripcion2,$descripcion3,$descripcion4,$descripcion5,$descripcion6,$descripcion7,$descripcion8,$revisiones)
    {
        /////CAPTURA DE CLIENTE NUEVO Y UPDATE
            if ($idcliente == '0') 
            {
                $sqlcorrelativo = "UPDATE add_correlativo SET 
                                    codigo_cliente=codigo_cliente+1 
                                    WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                ejecutarConsulta($sqlcorrelativo); 

                $sqlCorre = "SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["codigo_cliente"]; 
                $codigo_cliente = 'COD' . $corre;

                $sqlcliente = "INSERT INTO persona (tipo_persona,
                                                    nombre, 
                                                    tipo_documento,
                                                    num_documento,
                                                    direccion,
                                                    telefono,
                                                    email,
                                                    tipo_cliente,
                                                    codigo_cliente,fechaCreacion)
                                            VALUES ('Cliente',
                                                    '$nombre_cliente',
                                                    '$tipo_documento_cliente',
                                                    '$nit',
                                                    '$direccion_cliente',
                                                    '$telefono_cliente',
                                                    '$correo_cliente',
                                                    '$tipo_cliente',
                                                    '$codigo_cliente','$fechaHora')";
                $residcliente = ejecutarConsulta_retornarID($sqlcliente); 

                if (!$residcliente) {
                    throw new Exception("Error al insertar nuevo cliente.");
                }
            } else  
            {
                $sqlCorre = "SELECT * FROM persona WHERE idpersona='$idcliente'";
                $correlativo = ejecutarConsultaSimpleFila($sqlCorre);
                $corre = $correlativo["codigo_cliente"]; 

                               // Verificamos si $corre es '0', está vacío o es null
                if (empty($corre) || $corre == '0') {
                    // Si está vacío, null, o es '0', ejecutamos la lógica de actualización del código cliente
  
                    $sqlcorrelativo = "UPDATE add_correlativo SET codigo_cliente=codigo_cliente+1 WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                    ejecutarConsulta($sqlcorrelativo); 

                    $sqlCorrelativo = "SELECT * FROM add_correlativo WHERE idsucursal='".$_SESSION["idsucursal"]."' ";
                    $correlativos = ejecutarConsultaSimpleFila($sqlCorrelativo);
                    $corress = $correlativos["codigo_cliente"]; 
                    $codigo_clientes = 'COD' . $corress;    

                    $sqlupdadtepersona = "UPDATE persona SET codigo_cliente='$codigo_clientes' WHERE idpersona='$idcliente'";
                    ejecutarConsulta($sqlupdadtepersona);                         
                }

                $sqlcorrelativo = "UPDATE persona SET 
                                            direccion='$direccion_cliente',
                                            telefono='$telefono_cliente',
                                            email='$correo_cliente',
                                            tipo_documento='$tipo_documento_cliente',
                                            nombre='$nombre_cliente',
                                            tipo_cliente='$tipo_cliente'
                                    WHERE idpersona='$idcliente'";

                ejecutarConsulta($sqlcorrelativo);  
                $residcliente = $idcliente;
            }  
        ///////
        
        /*
        $check_bateria = $estadoChecks['check_bateria'] ? 'Si' : 'No';
        $check_tcs = $estadoChecks['check_tcs'] ? 'Si' : 'No';
        $check_motor = $estadoChecks['check_motor'] ? 'Si' : 'No';
        $check_aceite = $estadoChecks['check_aceite'] ? 'Si' : 'No';
        $check_airbag = $estadoChecks['check_airbag'] ? 'Si' : 'No';
        $check_tpms = $estadoChecks['check_tpms'] ? 'Si' : 'No';
        $check_abs = $estadoChecks['check_abs'] ? 'Si' : 'No';
        
        $checkLado1 = $estadoVehiculo['lado1']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado1 = $estadoVehiculo['lado1']['descripcion'];
        
        $checkLado2 = $estadoVehiculo['lado2']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado2 = $estadoVehiculo['lado2']['descripcion'];

        $checkLado3 = $estadoVehiculo['lado3']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado3 = $estadoVehiculo['lado3']['descripcion'];
        
        $checkLado4 = $estadoVehiculo['lado4']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado4 = $estadoVehiculo['lado4']['descripcion'];

        $checkLado5 = $estadoVehiculo['lado5']['seleccionado'] ? 'Si' : 'No';
        $descripcionLado5 = $estadoVehiculo['lado5']['descripcion'];
        */

        // Consulta SQL para actualizar los datos
        $sql = "UPDATE ingreso_vehiculo SET
                    idcliente = '$residcliente',
                    no_placa = '$no_placa', 
                    no_chasis = '$no_chasis', 
                    serie = '$serie', 
                    no_motor = '$no_motor', 
                    modelo = '$modelo', 
                    km = '$km',
                    imagen1 = '$imagen1',
                    imagen2 = '$imagen2',
                    imagen3 = '$imagen3',
                    imagen4 = '$imagen4',
                    imagen5 = '$imagen5',
                    imagen6 = '$imagen6',
                    imagen7 = '$imagen7',
                    imagen8 = '$imagen8',
                    descripcion1 = '$descripcion1',
                    descripcion2 = '$descripcion2',
                    descripcion3 = '$descripcion3',
                    descripcion4 = '$descripcion4',
                    descripcion5 = '$descripcion5',
                    descripcion6 = '$descripcion6',
                    descripcion7 = '$descripcion7',
                    descripcion8 = '$descripcion8',
                    trabajos_detalle = '$trabajos_detalle',
                    observaciones_adicionales = '$observaciones_adicionales',
                    idvendedor = '$idvendedor',
                    idusuario_modificacion = '" . $_SESSION["idusuario"] . "',
                    fecha_modificacion = NOW()
                WHERE idingreso_vehiculo = '$idingreso_vehiculo'";
                
        //eliminar revisiones
        $sqlRevisiones="DELETE from detalle_revision_vehiculo where idingreso_vehiculo=".$idingreso_vehiculo."";
        ejecutarConsulta($sqlRevisiones);

        foreach ($revisiones as $revision) { 
            $nombre_revision = $revision['nombre'];
            $estado_100 = $revision['estado100'] ? 1 : 0;
            $estado_75 = $revision['estado75'] ? 1 : 0;
            $estado_50 = $revision['estado50'] ? 1 : 0;
            $cambio_sugerido = $revision['cambioSugerido'] ? 1 : 0;

            $sql_revision = "INSERT INTO detalle_revision_vehiculo (idingreso_vehiculo, nombre_revision, estado_100, estado_75, estado_50, cambio_sugerido)
                            VALUES ('$idingreso_vehiculo',
                                    '$nombre_revision',
                                    '$estado_100',
                                    '$estado_75',
                                    '$estado_50',
                                    '$cambio_sugerido')";

            if (!ejecutarConsulta($sql_revision)) {
                $sw = false;
            }
        }
        return ejecutarConsulta($sql);
    }

    public function anular($idingreso_vehiculo){
        $sql="UPDATE ingreso_vehiculo SET estado='Anulado',fecha_anulacion = NOW(), idusuario_anulacion = '" . $_SESSION["idusuario"] . "'
        WHERE idingreso_vehiculo='$idingreso_vehiculo'";
        return ejecutarConsulta($sql);
    }

    public function mostrarDetalles($idingreso_vehiculo) {
        $sql_articulos = "SELECT d.iddetalle_ingreso_vehiculo, a.idarticulo, a.nombre, d.cantidad 
                        FROM detalle_ingreso_vehiculo d
                        INNER JOIN articulo a ON d.idarticulo = a.idarticulo
                        WHERE d.idingreso_vehiculo = '$idingreso_vehiculo'";
        //$detalles = ejecutarConsulta($sql_articulos);
        $rspta=ejecutarConsulta($sql_articulos);
        $rows_detalles = array();
        while ($reg=$rspta->fetch_object()){
            $rows_detalles[] = $reg;
        }
        $sql_revisiones = "SELECT iddetalle_revision_vehiculo, nombre_revision, estado_100, estado_75, estado_50, cambio_sugerido
                        FROM detalle_revision_vehiculo
                        WHERE idingreso_vehiculo = '$idingreso_vehiculo'";
        //$revisiones = ejecutarConsulta($sql_revisiones);
        $rspta_revisiones=ejecutarConsulta($sql_revisiones);
        $rows_revisiones = array();
        while ($reg=$rspta_revisiones->fetch_object()){
            $rows_revisiones[] = $reg;
        }

        $sql_datos = "SELECT horaInicio, horaFinalizada, tecnico, gradoAceite, filtroAceite, filtroAire, filtroCombustible, observaciones
                    FROM ingreso_vehiculo
                    WHERE idingreso_vehiculo = '$idingreso_vehiculo'";
        $datos_ingreso = ejecutarConsultaSimpleFila($sql_datos);

        return [
            'detalles' => $rows_detalles,
            'revisiones' => $rows_revisiones,
            'datos' => $datos_ingreso
        ];
    }
}
 
?>