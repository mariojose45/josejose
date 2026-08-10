<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$comision = isset($_POST["comision"]) ? limpiarCadena($_POST["comision"]) : "";
$meta = isset($_POST["meta"]) ? limpiarCadena($_POST["meta"]) : "";



switch ($_GET["op"]) {
    case 'guardaryeditar':

        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imagen = $_POST["imagenactual"];
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
            }
        }
        //Hash SHA256 en la contraseña
        $clavehash = hash("SHA256", $clave);

        if (empty($idusuario)) {
            $rspta = $usuario->insertar(
                $nombre,
                $tipo_documento,
                $num_documento,
                $direccion,
                $telefono,
                $email,
                $cargo,
                $login,
                $clavehash,
                $imagen,
                $_POST['permiso'],
                $comision,
                $_POST['sucursal'],
                $meta
            );
            echo $rspta ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
        } else {
            $rspta = $usuario->editar(
                $idusuario,
                $nombre,
                $tipo_documento,
                $num_documento,
                $direccion,
                $telefono,
                $email,
                $cargo,
                $login,
                $clavehash,
                $imagen,
                $_POST['permiso'],
                $comision,
                $_POST['sucursal'],
                $meta
            );
            echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
        }
        break;

    case 'guardaryeditarClave':

        //Hash SHA256 en la contraseña
        $idusuarioClave = $_REQUEST["idusuarioClave"];
        $nueva_clave = $_REQUEST["nueva_clave"];

        $clavehash = hash("SHA256", $nueva_clave);

        $rspta = $usuario->editarClave($idusuarioClave, $clavehash);
        echo $rspta ? "Usuario se ha cambiado la clave" : "Usuario no se pudo cambiar la clave";

        break;

    case 'desactivar':
        $rspta = $usuario->desactivar($idusuario);
        echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
        break;



    case 'activar':
        $rspta = $usuario->activar($idusuario);
        echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
        break;

    case 'mostrar':
        $rspta = $usuario->mostrar($idusuario);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;


    case 'selectEmpresass':
        $idusuario = $_SESSION["idusuario"];
        $rspta = $usuario->mostrarSucursales($idusuario);

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idsucursal . '>' . $reg->nombre_sucursal . '--' . $reg->direccion_sucursal . '</option>';
        }
        break;

    case 'mostrarSucursales':
        $idusuario = $_GET['idusuario'];
        $rspta = $usuario->mostrarSucursales($idusuario);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre_sucursal,
                "1" => $reg->direccion_sucursal
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'listar':
        $rspta = $usuario->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning" title="Editar" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-danger" title="Desactivar" onclick="desactivar(' . $reg->idusuario . ')"><i class="fa fa-close"></i></button>' .
                    ' <button class="btn btn-info" title="Cambiar contraseña" onclick="cambiar_usuario(' . $reg->idusuario . ')"><i class="fa fa-expeditedssl"></i></button>' :
                    '<button class="btn btn-warning" title="Editar" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-primary" title="Activar" onclick="activar(' . $reg->idusuario . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento,
                "3" => $reg->num_documento,
                "4" => $reg->telefono,
                "5" => $reg->email,
                "6" => $reg->login,
                "7" => $reg->comision,
                "8" => $reg->meta,
                "9" => "<img src='../files/usuarios/" . $reg->imagen . "' height='50px' width='50px' >",
                "10" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>',
                "11" => "<button class='btn btn-primary' onclick='mostrarSucursalesModal(" . $reg->idusuario . ")'><i class='fa fa-check'></i></button>"
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;



    case 'permisos':
        //Obtenemos todos los permisos de la tabla permisos
        require_once "../modelos/Permiso.php";
        $permiso = new Permiso();
        $rspta = $permiso->listar();

        //Obtener los permisos asignados al usuario
        $id = $_GET['id'];
        $marcados = $usuario->listarmarcados($id);
        //Declaramos el array para almacenar todos los permisos marcados
        $valores = array();

        //Almacenar los permisos asignados al usuario en el array
        while ($per = $marcados->fetch_object()) {
            array_push($valores, $per->idpermiso);
        }

        //Mostramos la lista de permisos en la vista y si están o no marcados
        while ($reg = $rspta->fetch_object()) {
            $sw = in_array($reg->idpermiso, $valores) ? 'checked' : '';
            echo '<li> <input type="checkbox" ' . $sw . '  name="permiso[]" value="' . $reg->idpermiso . '">' . $reg->nombre . '</li>';
        }
        break;


    case 'sucursales':
        //Obtenemos todos los permisos de la tabla permisos
        require_once "../modelos/Sucursal.php";
        $sucursal = new Sucursal();
        $rspta = $sucursal->listar();

        //Obtener los permisos asignados al usuario
        $id = $_GET['id'];
        $marcados = $usuario->selectEmpresaMArcados($id);
        //Declaramos el array para almacenar todos los permisos marcados
        $valores = array();

        //Almacenar los permisos asignados al usuario en el array
        while ($suc = $marcados->fetch_object()) {
            array_push($valores, $suc->idsucursal);
        }

        //Mostramos la lista de permisos en la vista y si están o no marcados
        while ($reg = $rspta->fetch_object()) {
            $sw = in_array($reg->idsucursal, $valores) ? 'checked' : '';
            echo '<li> <input type="checkbox" ' . $sw . '  
            name="sucursal[]" value="' . $reg->idsucursal . '">' . $reg->nombre . ' - ' . $reg->direccion . '</li>';
        }
        break;


    case 'verificar':
        $logina = $_POST['logina'];
        $clavea = $_POST['clavea'];

        //Hash SHA256 en la contraseña
        $clavehash = hash("SHA256", $clavea);

        $rspta = $usuario->verificar($logina, $clavehash);

        $fetch = $rspta->fetch_object();

        if (isset($fetch)) {
            //Declaramos las variables de sesión
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;
            $_SESSION['claveordenes'] = $fetch->clave_ordenes;
            $_SESSION['claveingresos'] = $fetch->clave_ingresos;
            $_SESSION['claveventas'] = $fetch->clave_ventas;

            //Obtenemos los permisos del usuario
            $marcados = $usuario->listarmarcados($fetch->idusuario);

            //Declaramos el array para almacenar todos los permisos marcados
            $valores = array();

            //Almacenamos los permisos marcados en el array
            while ($per = $marcados->fetch_object()) {
                array_push($valores, $per->idpermiso);
            }
            //Determinamos los accesos del usuario
            in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
            in_array(2, $valores) ? $_SESSION['almacen'] = 1 : $_SESSION['almacen'] = 0;
            in_array(3, $valores) ? $_SESSION['compras'] = 1 : $_SESSION['compras'] = 0;
            in_array(4, $valores) ? $_SESSION['ventas'] = 1 : $_SESSION['ventas'] = 0;
            in_array(5, $valores) ? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0;
            in_array(6, $valores) ? $_SESSION['consultac'] = 1 : $_SESSION['consultac'] = 0;
            in_array(7, $valores) ? $_SESSION['consultav'] = 1 : $_SESSION['consultav'] = 0;
            in_array(8, $valores) ? $_SESSION['mesarestaurante'] = 1 : $_SESSION['mesarestaurante'] = 0;
            in_array(9, $valores) ? $_SESSION['CuentasXcobrar'] = 1 : $_SESSION['CuentasXcobrar'] = 0;
            in_array(10, $valores) ? $_SESSION['cotizaciones'] = 1 : $_SESSION['cotizaciones'] = 0;
            in_array(11, $valores) ? $_SESSION['cajachica'] = 1 : $_SESSION['cajachica'] = 0;
            in_array(12, $valores) ? $_SESSION['reportes'] = 1 : $_SESSION['reportes'] = 0;
            in_array(13, $valores) ? $_SESSION['inventarioxsucursal'] = 1 : $_SESSION['inventarioxsucursal'] = 0;
            in_array(14, $valores) ? $_SESSION['cuentasxpagar'] = 1 : $_SESSION['cuentasxpagar'] = 0;
            in_array(15, $valores) ? $_SESSION['salidaproducto'] = 1 : $_SESSION['salidaproducto'] = 0;
            in_array(16, $valores) ? $_SESSION['entradaproducto'] = 1 : $_SESSION['entradaproducto'] = 0;
            in_array(17, $valores) ? $_SESSION['restauranteordenes'] = 1 : $_SESSION['restauranteordenes'] = 0;
            in_array(18, $valores) ? $_SESSION['notaCredito'] = 1 : $_SESSION['notaCredito'] = 0;
            in_array(19, $valores) ? $_SESSION['notaDebito'] = 1 : $_SESSION['notaDebito'] = 0;
            in_array(20, $valores) ? $_SESSION['restaurantecobros'] = 1 : $_SESSION['restaurantecobros'] = 0;
            in_array(21, $valores) ? $_SESSION['despachoventas'] = 1 : $_SESSION['despachoventas'] = 0;
            in_array(22, $valores) ? $_SESSION['inventarioxgeneral'] = 1 : $_SESSION['inventarioxgeneral'] = 0;
            in_array(100, $valores) ? $_SESSION['inventarioxgeneralagrupado'] = 1 : $_SESSION['inventarioxgeneralagrupado'] = 0;
            in_array(24, $valores) ? $_SESSION['guiastransporte'] = 1 : $_SESSION['guiastransporte'] = 0;

            //NUEVOS ACCESO
            //ALMACEN
            in_array(25, $valores) ? $_SESSION['almacen_crear_articulo'] = 1 : $_SESSION['almacen_crear_articulo'] = 0;
            in_array(62, $valores) ? $_SESSION['almacen_crear_presentacion'] = 1 : $_SESSION['almacen_crear_presentacion'] = 0;
            in_array(26, $valores) ? $_SESSION['almacen_crear_categoria'] = 1 : $_SESSION['almacen_crear_categoria'] = 0;
            in_array(27, $valores) ? $_SESSION['almacen_crear_sub_categoria'] = 1 : $_SESSION['almacen_crear_sub_categoria'] = 0;
            in_array(52, $valores) ? $_SESSION['almacen_asociar_sub_categoria'] = 1 : $_SESSION['almacen_asociar_sub_categoria'] = 0;
            //COMPRAS
            in_array(28, $valores) ? $_SESSION['almacen_crear_combos'] = 1 : $_SESSION['almacen_crear_combos'] = 0;
            in_array(29, $valores) ? $_SESSION['compras_ordenes_compra'] = 1 : $_SESSION['compras_ordenes_compra'] = 0;
            in_array(30, $valores) ? $_SESSION['compras_revision_orden'] = 1 : $_SESSION['compras_revision_orden'] = 0;
            in_array(31, $valores) ? $_SESSION['compras_ingresos'] = 1 : $_SESSION['compras_ingresos'] = 0;
            in_array(32, $valores) ? $_SESSION['compras_proveedores'] = 1 : $_SESSION['compras_proveedores'] = 0;
            in_array(33, $valores) ? $_SESSION['compras_gastos'] = 1 : $_SESSION['compras_gastos'] = 0;
            in_array(34, $valores) ? $_SESSION['compras_rpt_ingresos'] = 1 : $_SESSION['compras_rpt_ingresos'] = 0;
            //VENTAS
            in_array(35, $valores) ? $_SESSION['ventas_facturacion'] = 1 : $_SESSION['ventas_facturacion'] = 0;
            in_array(36, $valores) ? $_SESSION['ventas_por_categpria'] = 1 : $_SESSION['ventas_por_categpria'] = 0;

            in_array(38, $valores) ? $_SESSION['ventas_mensajero_transporte'] = 1 : $_SESSION['ventas_mensajero_transporte'] = 0;
            //ACCESO
            in_array(39, $valores) ? $_SESSION['acceso_usuarios'] = 1 : $_SESSION['acceso_usuarios'] = 0;
            in_array(40, $valores) ? $_SESSION['acceso_sucursales'] = 1 : $_SESSION['acceso_sucursales'] = 0;
            in_array(41, $valores) ? $_SESSION['acceso_mensajeros'] = 1 : $_SESSION['acceso_mensajeros'] = 0;
            in_array(42, $valores) ? $_SESSION['acceso_transportes'] = 1 : $_SESSION['acceso_transportes'] = 0;
            in_array(43, $valores) ? $_SESSION['acceso_vendedores'] = 1 : $_SESSION['acceso_vendedores'] = 0;
            //CONSULTA DE COMPRAS
            in_array(44, $valores) ? $_SESSION['consulta_compras_compras'] = 1 : $_SESSION['consulta_compras_compras'] = 0;
            in_array(45, $valores) ? $_SESSION['consulta_compras_detallado'] = 1 : $_SESSION['consulta_compras_detallado'] = 0;

            //CONSULTA DE VENTAS
            in_array(53, $valores) ? $_SESSION['consulta_ventas_ventas'] = 1 : $_SESSION['consulta_ventas_ventas'] = 0;
            in_array(54, $valores) ? $_SESSION['consulta_ventas_detallado'] = 1 : $_SESSION['consulta_ventas_detallado'] = 0;
            in_array(55, $valores) ? $_SESSION['consulta_ventas_anuladas'] = 1 : $_SESSION['consulta_ventas_anuladas'] = 0;

            //CUENTAS POR COBRAR
            in_array(46, $valores) ? $_SESSION['cta_cobrar_cobrar'] = 1 : $_SESSION['cta_cobrar_cobrar'] = 0;
            in_array(47, $valores) ? $_SESSION['cta_cobrar_reporte'] = 1 : $_SESSION['cta_cobrar_reporte'] = 0;
            in_array(48, $valores) ? $_SESSION['cta_cobrar_ctas_pagar'] = 1 : $_SESSION['cta_cobrar_ctas_pagar'] = 0;
            //CUENTAS POR PAGAR
            in_array(49, $valores) ? $_SESSION['cta_pagar_generar'] = 1 : $_SESSION['cta_pagar_generar'] = 0;
            in_array(50, $valores) ? $_SESSION['cta_pagar_reporte_pagadas'] = 1 : $_SESSION['cta_pagar_reporte_pagadas'] = 0;
            in_array(51, $valores) ? $_SESSION['cta_pagar_reporte_pagadasxproveedor'] = 1 : $_SESSION['cta_pagar_reporte_pagadasxproveedor'] = 0;

            in_array(56, $valores) ? $_SESSION['escritorioxsucursal'] = 1 : $_SESSION['escritorioxsucursal'] = 0;

            in_array(37, $valores) ? $_SESSION['clientes'] = 1 : $_SESSION['clientes'] = 0;
            in_array(59, $valores) ? $_SESSION['crear_clientes'] = 1 : $_SESSION['crear_clientes'] = 0;
            in_array(60, $valores) ? $_SESSION['clientes_seguimiento'] = 1 : $_SESSION['clientes_seguimiento'] = 0;
            in_array(61, $valores) ? $_SESSION['salidas_inventario'] = 1 : $_SESSION['salidas_inventario'] = 0;

            in_array(63, $valores) ? $_SESSION['almacen_crear_sector'] = 1 : $_SESSION['almacen_crear_sector'] = 0;
            in_array(64, $valores) ? $_SESSION['almacen_crear_empresa_interna'] = 1 : $_SESSION['almacen_crear_empresa_interna'] = 0;

            in_array(65, $valores) ? $_SESSION['taller'] = 1 : $_SESSION['taller'] = 0;
            in_array(66, $valores) ? $_SESSION['taller_ingreso_vehiculo'] = 1 : $_SESSION['taller_ingreso_vehiculo'] = 0;
            in_array(67, $valores) ? $_SESSION['taller_mecanico'] = 1 : $_SESSION['taller_mecanico'] = 0;

            //ORDENES DE TRABAJO
            in_array(69, $valores) ? $_SESSION['orden_trabajo'] = 1 : $_SESSION['orden_trabajo'] = 0;
            in_array(74, $valores) ? $_SESSION['orden_trabajo_crear'] = 1 : $_SESSION['orden_trabajo_crear'] = 0;
            in_array(68, $valores) ? $_SESSION['orden_trabajo_marca'] = 1 : $_SESSION['orden_trabajo_marca'] = 0;
            in_array(70, $valores) ? $_SESSION['orden_trabajo_tecnico'] = 1 : $_SESSION['orden_trabajo_tecnico'] = 0;
            in_array(71, $valores) ? $_SESSION['orden_trabajo_modelo'] = 1 : $_SESSION['orden_trabajo_modelo'] = 0;
            in_array(72, $valores) ? $_SESSION['orden_trabajo_tipo_equipo'] = 1 : $_SESSION['orden_trabajo_tipo_equipo'] = 0;
            in_array(73, $valores) ? $_SESSION['orden_trabajo_colores'] = 1 : $_SESSION['orden_trabajo_colores'] = 0;

            ///TIENDA EN LINEA
            in_array(75, $valores) ? $_SESSION['tienda_web'] = 1 : $_SESSION['tienda_web'] = 0;
            in_array(76, $valores) ? $_SESSION['tienda_web_articulos'] = 1 : $_SESSION['tienda_web_articulos'] = 0;
            in_array(77, $valores) ? $_SESSION['tienda_web_inicio'] = 1 : $_SESSION['tienda_web_inicio'] = 0;
            in_array(78, $valores) ? $_SESSION['tienda_web_nosotros'] = 1 : $_SESSION['tienda_web_nosotros'] = 0;
            in_array(79, $valores) ? $_SESSION['tienda_web_servicios'] = 1 : $_SESSION['tienda_web_servicios'] = 0;
            in_array(80, $valores) ? $_SESSION['tienda_web_contactanos'] = 1 : $_SESSION['tienda_web_contactanos'] = 0;

            //
            in_array(81, $valores) ? $_SESSION['tenico'] = 1 : $_SESSION['tenico'] = 0;
            in_array(82, $valores) ? $_SESSION['tecnico_instalaciones_x_user'] = 1 : $_SESSION['tecnico_instalaciones_x_user'] = 0;
            in_array(83, $valores) ? $_SESSION['tecnico_instalaciones_general'] = 1 : $_SESSION['ventas_servicios'] = 0;

            in_array(86, $valores) ? $_SESSION['ventas_servicios'] = 1 : $_SESSION['ventas_servicios'] = 0;
            in_array(87, $valores) ? $_SESSION['acceso_tecnicos'] = 1 : $_SESSION['acceso_tecnicos'] = 0;
            in_array(88, $valores) ? $_SESSION['acceso_cobradores'] = 1 : $_SESSION['acceso_cobradores'] = 0;

            //
            in_array(89, $valores) ? $_SESSION['nomina'] = 1 : $_SESSION['nomina'] = 0;
            in_array(90, $valores) ? $_SESSION['nomina_empleados'] = 1 : $_SESSION['nomina_empleados'] = 0;
            in_array(91, $valores) ? $_SESSION['nomina_pagos'] = 1 : $_SESSION['nomina_pagos'] = 0;
            in_array(92, $valores) ? $_SESSION['nomina_pagos_14_aguinaldo'] = 1 : $_SESSION['nomina_pagos_14_aguinaldo'] = 0;
            in_array(93, $valores) ? $_SESSION['nomina_pagos_vacaciones'] = 1 : $_SESSION['nomina_pagos_vacaciones'] = 0;

            //modulo de parqueos
            in_array(94, $valores) ? $_SESSION['parqueo'] = 1 : $_SESSION['parqueo'] = 0;
            in_array(95, $valores) ? $_SESSION['parqueo_tarifas'] = 1 : $_SESSION['parqueo_tarifas'] = 0;
            in_array(96, $valores) ? $_SESSION['parqueo_Info_Ticke_Fac'] = 1 : $_SESSION['parqueo_Info_Ticke_Fac'] = 0;
            in_array(97, $valores) ? $_SESSION['parqueo_Operaciones'] = 1 : $_SESSION['parqueo_Operaciones'] = 0;
            in_array(98, $valores) ? $_SESSION['Parqueo_Rpt_Ticket'] = 1 : $_SESSION['Parqueo_Rpt_Ticket'] = 0;
            in_array(99, $valores) ? $_SESSION['Parqueo_Rpt_Graficas'] = 1 : $_SESSION['Parqueo_Rpt_Graficas'] = 0;
        }
        echo json_encode($fetch);
        break;

    case 'salir':
        //Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");

        break;



    case 'selectEmpresa':
        $rspta = $usuario->selectEmpresa();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idsucursal . '>' . $reg->nombre . '--' . $reg->direccion . '</option>';
        }
        break;

    case 'selectUsuario':
        $rspta = $usuario->selectUsuario();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . '--' . $reg->direccion . '</option>';
        }
        break;
}
