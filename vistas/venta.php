<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión



// Verificamos si el usuario está logueado
if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
    exit();
} else {
    require 'header.php';

    // Verificamos si tiene permisos
    if ($_SESSION['ventas_facturacion'] == 1) {


        require_once "../modelos/Consultas.php";
        $consulta = new Consultas();
        $rsptav = $consulta->totalventahoy();
        $regv = $rsptav->fetch_object();
        $totalv = $regv->total_venta;

        $res_efectivo = $consulta->totalventahoyefectivo();
        $resv_efectivo = $res_efectivo->fetch_object();
        $restotalpagoefectivo = $resv_efectivo->total_venta;

        $res_tranferencia = $consulta->totalventahoytransferencia();
        $resv_transferencia = $res_tranferencia->fetch_object();
        $restotalpagotransferencia = $resv_transferencia->total_venta;

        $res_credito = $consulta->totalventahoycredito2();
        $resv_credito = $res_credito->fetch_object();
        $restotalpagocredito = $resv_credito->total_venta;


        $res_tarjeta = $consulta->totalventahoyTarjeta2();
        $resv_tarjeta = $res_tarjeta->fetch_object();
        $restotalpagotarjeta = $resv_tarjeta->total_venta;


        $res_gasto = $consulta->GastosVenta();
        $resv_gasto = $res_gasto->fetch_object();
        $restotalgastoventa = $resv_gasto->total_venta;

        $res_AbonosCtacobrar = $consulta->AbonosCtaCobrarVenta();
        $resv_AbonosCtacobrar = $res_AbonosCtacobrar->fetch_object();
        $restotatalAbonoctacobrar = $resv_AbonosCtacobrar->total_venta;

        $res_NC = $consulta->NCVenta();
        $resv_NC = $res_NC->fetch_object();
        $restotalNCventa = $resv_NC->total_venta;


        $rsptavc = $consulta->totalefectivoiniciocaja();
        $regvc = $rsptavc->fetch_object();
        $restotalefectivo = $regvc->totalefectivo;
        $resApertura = $regvc->tipo_operacion;

        $rsptaCalculoDescuento = $consulta->tipoCalculoDescuento();
        $regvCalDes = $rsptaCalculoDescuento->fetch_object();
        $calculodescuento = $regvCalDes->calculo_descuento;

        $resDisponibleparaGastos = $restotalpagoefectivo - ($restotalgastoventa + $restotalNCventa);

        date_default_timezone_set('America/Guatemala');
?>
        <!--Contenido-->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class=" with-border box box-primary">
                                <section class="content-header">
                                    <h1>
                                        Ventas
                                        <small>Procesa tu Venta</small>
                                    </h1>
                                    <ol class="breadcrumb">
                                        <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                                        <li class="active">Ventas</li>
                                    </ol>

                                </section>
                                <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar"
                                        onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar (F8)</button>
                                </h1>
                            </div>

                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Fecha Inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio_reporte"
                                        id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Fecha Fin</label>
                                    <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte"
                                        value="<?php echo date("Y-m-d"); ?>">
                                    <button class="btn btn-success btn-block" onclick="listar()">Generar Ventas</button>
                                    <button class="btn btn-warning btn-block" onclick="listarProductosaSolicitar()">Mostrar
                                        Productos a Solicitar</button>
                                </div>
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Idventa</th>
                                        <th>Cliente</th>
                                        <th>Usuario</th>
                                        <th>Tipo Doc</th>
                                        <th>No/Correlativo</th>
                                        <th>T. V</th>
                                        <th>T. V. Des</th>
                                        <th>F/Pago</th>
                                        <th>Efectivo</th>
                                        <th>Tarjeta</th>
                                        <th>Credito</th>
                                        <th>Transferencia</th>
                                        <th>Cambio</th>
                                        <th>Fecha</th>
                                        <th>Fecha/Certi</th>
                                        <th>Serie/Certi</th>
                                        <th>DTE/Certi</th>
                                        <th>Tipo/Entrega</th>
                                        <th>Vendedor</th>
                                        <th>#Cotizacion</th>
                                        <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" style="height: 800px;" id="formularioregistros">

                                <form name="formulario" id="formulario" method="POST">

                                    <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label> <img class="iconos-tama" src="../public/iconos/proveedor.png">
                                            Clientes(*):</label>
                                        <input type="hidden" name="idventa" id="idventa">
                                        <input type="hidden" name="tipo_venta_operacion" id="tipo_venta_operacion"
                                            value="VENTA NORMAL">
                                        <input type="hidden" name="datos1" id="datos1">


                                        <div style="display: flex; gap: 10px; width: 100%;">
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="codigo_cliente"
                                                    id="codigo_cliente" maxlength="20" placeholder="CODIGO">
                                                <input type="hidden" class="form-control" title="Descuento Cliente"
                                                    name="descuento_cliente" id="descuento_cliente" maxlength="20"
                                                    placeholder="0" value="0" readonly>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success" onclick="validarCodigo()" type="button">
                                                        <i class="fa fa-users"></i>
                                                    </button>
                                                </span>
                                            </div>

                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" onchange="validarnit()" name="nit"
                                                    id="nit" maxlength="20" value="CF" autofocus="autofocus">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" onclick="validarnit()" type="button">
                                                        <i class="fa fa-search-minus"></i> NIT
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 10px; width: 100%;">
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="nombre_cliente"
                                                    id="nombre_cliente" maxlength="256" value="CONSUMIDOR FINAL"
                                                    onchange="validarnitNombre()">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success" onclick="validarnitNombre()" type="button">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success" onclick="listartbBusquedaCliente()"
                                                        type="button">
                                                        <i class="fa fa-search-minus"></i> NOMBRE
                                                    </button>
                                                </span>
                                            </div>

                                        </div>
                                        <div style="display: flex; gap: 10px; width: 100%;">
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="direccion_cliente"
                                                    id="direccion_cliente" maxlength="256" value="CIUDAD">
                                            </div>
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="correo_cliente"
                                                    id="correo_cliente" maxlength="256" value="soporte@gmail.com">
                                            </div>
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="telefono_cliente"
                                                    id="telefono_cliente" maxlength="256" value="0">
                                            </div>
                                            <div class="input-group" style="flex: 1;">
                                                <select class="form-control select-picker" name="tipo_cliente" id="tipo_cliente"
                                                    required>
                                                    <option value="PUBLICO">PUBLICO</option>
                                                    <option value="DISTRIBUIDOR">PRECIO A</option>
                                                    <option value="MAYORISTA">PRECIO B</option>
                                                    <option value="TALLER">PRECIO C</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 10px; width: 100%;">
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="idcliente" id="idcliente"
                                                    value="1" readonly="">
                                            </div>
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="valor_tarjeta" id="valor_tarjeta"
                                                    value="0" readonly="">
                                            </div>
                                            <div class="input-group" style="flex: 1;">
                                                <select class="form-control select-picker" name="tipo_documento_cliente"
                                                    id="tipo_documento_cliente" required>
                                                    <option value="NIT">NIT</option>
                                                    <option value="DPI">DPI</option>
                                                    <option value="PASAPORTE">PASAPORTE</option>
                                                </select>
                                            </div>
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="aperturaCaja" id="aperturaCaja"
                                                    value="<?php echo $resApertura; ?>" readonly="">
                                            </div>
                                            <div class="input-group" style="flex: 1;">
                                                <input type="text" class="form-control" name="calculo_descuento"
                                                    id="calculo_descuento" value="<?php echo $calculodescuento; ?>" readonly="">
                                            </div>
                                            <div class="input-group" style="flex: 1;">
                                                <input type="number" step="any" class="form-control"
                                                    name="valor_descuentoGeneral" id="valor_descuentoGeneral" readonly="">
                                            </div>


                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-xs-12">
                                            <a data-toggle="modal" href="#myModal">
                                                <button id="btnAgregarArt" name="btnAgregarArt" type="button"
                                                    class="btn btn-primary btn-block"> <span class="fa fa-plus"></span> Agregar
                                                    Artículos</button>
                                            </a>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-xs-12">
                                            <a data-toggle="modal" href="#modalBuscarArticulos">
                                                <button id="btnAgregarArt_v2" name="btnAgregarArt_v2" type="button" onclick="listarArticulos_v2()"
                                                    class="btn btn-primary btn-block">
                                                    <span class="fa fa-plus"></span> Agregar Artículos V2
                                                </button>
                                            </a>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label>Busqueda Codigo de Barra:</label>
                                            <input type="text" id="txtbusquedaartcodebar" class="form-control"
                                                placeholder="Click para Gestionar las lecturas con pistola">
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <!--
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label># Cotizaciones (ej: 2, 5, 8):</label>
                            <input type="text" name="idcotizacion" id="idcotizacion" class="form-control" placeholder="Separadas por coma">
                            <button type="button" class="btn btn-primary btn-block" id="btncargar">Procesar Cotizaciones</button>
                        </div>
                        -->

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!-- Campo oculto para obtener ID del usuario en JS -->
                                                <input type="hidden" id="idusuario_session"
                                                    value="<?php echo $_SESSION['idusuario']; ?>">

                                                <button type="button" class="btn btn-info margin-bottom" data-toggle="modal"
                                                    data-target="#modalCotizaciones" onclick="listarCotizacionesPendientes();">
                                                    <i class="fa fa-search"></i> Buscar y Seleccionar Cotizaciones
                                                </button>

                                                <table id="tbl_seleccionadas"
                                                    class="table table-striped table-bordered table-condensed table-hover">
                                                    <thead style="background-color: #3c8dbc; color: white;">
                                                        <th width="10%">Opciones</th>
                                                        <th width="15%">ID Cotización</th>
                                                        <th>Cliente</th>
                                                        <th width="20%">Total</th>
                                                    </thead>
                                                    <tbody id="tbody_seleccionadas">
                                                        <!-- Filas agregadas desde el modal -->
                                                    </tbody>
                                                </table>

                                                <button type="button" class="btn btn-success pull-right"
                                                    onclick="ejecutarProcesoVenta();">
                                                    <i class="fa fa-check-circle"></i> Procesar Cotizaciones Seleccionadas
                                                </button>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                <label># Taller:</label>
                                                <input type="text" name="idtaller" id="idtaller" class="form-control">
                                                <button type="button" class="btn  btn-info btn-block"
                                                    id="btncargarTaller">Taller</button>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                <label>Fecha (*):</label>
                                                <input type="date" class="form-control" name="fecha_hora" id="fecha_hora"
                                                    required="" readonly>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                <label>F-pago(*):</label>
                                                <select name="forma_pago" id="forma_pago" class="form-control selectpicker"
                                                    required="" onchange="marcarImpuesto()">
                                                    <option value="Efectivo">Efectivo</option>
                                                    <option value="Efectivo/Tarjeta">Efectivo/Tarjeta</option>
                                                    <option value="Tarjeta">Tarjeta</option>
                                                    <option value="Credito">Credito</option>
                                                    <option value="Transferencia">Transferencia</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label>Destino(*):</label>
                                            <select name="destino" id="destino" class="form-control selectpicker" required>
                                                <option value="VENTA">Venta directa</option>
                                                <option value="PANTALLA">Pantalla Ventas</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label>Productos(*):</label>
                                            <select name="forma_productos" id="forma_productos"
                                                class="form-control selectpicker" required="">
                                                <option value="Agrupado">Agrupado</option>
                                                <option value="Detallado">Detallado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="comentario_venta"
                                                id="comentario_venta" required="" placeholder="Comentario">
                                        </div>

                                        <!-- DETALLES DE TRABAJOS REALIZADOS-->
                                        <div class="col-md-12">
                                            <div class="box box-default collapsed-box">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Detalles de Trabajos</h3>
                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                                class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="box-body" style="display: none;">
                                                    <textarea id="trabajos_detalle_area" class="form-control" rows="10"
                                                        readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- DETALLES DE TRABAJOS REALIZADOS-->
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        </div>
                                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                            <label>Tipo-Com(*):</label>
                                            <select name="tipo_comprobante" id="tipo_comprobante"
                                                class="form-control selectpicker" required="">
                                                <option value="Envio">Envio</option>
                                                <option value="Factura">Factura</option>
                                                <option value="Cambiaria">Cambiaria</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                            <label>Tipo/Entrega(*):</label>
                                            <select name="tipo_entrega" id="tipo_entrega" class="form-control" required="">
                                                <option value="Tienda">Tienda</option>
                                                <option value="Transporte">Transporte</option>
                                                <option value="Mensajero">Mensajero</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                            <label>Vendedor:</label>
                                            <select id="idvendedor" name="idvendedor" class="form-control selectpicker"
                                                data-live-search="true"></select>
                                        </div>

                                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                            <label>Des Q/%:</label>
                                            <select name="descuento_general" id="descuento_general" class="form-control">
                                                <option value="NO APLICA">NO APLICA</option>
                                                <option value="DESCUENTO GENERAL">DESCUENTO GENERAL</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_formapago"
                                            name="div_formapago">
                                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                <label>Tipo Tarjeta:</label>
                                                <select name="tipo_pagoBacVisaNet" id="tipo_pagoBacVisaNet"
                                                    class="form-control selectpicker" onchange="mostrarOpcionesAdicionales();">
                                                    <option value="Seleccione Uno">Seleccione Uno</option>
                                                    <option value="VISANET">VISANET</option>
                                                    <option value="BAC">BAC</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12"
                                                id="opcionesAdicionalesDiv">
                                                <label>Opciones Adicionales:</label>
                                                <select name="opcionesAdicionales" id="opcionesAdicionales"
                                                    class="form-control ">
                                                    <!-- Las opciones se llenarán dinámicamente -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <button class="btn btn-primary btn-block" id="btnProcesar" type="button"
                                                data-toggle="modal" href="#myModal23"><i class="fa fa-save"></i> Procesar
                                                (F9)</button>

                                            <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()"
                                                type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar (F7)</button>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <label>Valor Venta:</label>
                                            <h2 id="total">Q. 0.00</h2>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <label>Valor Descuento:</label>
                                            <h3 id="totaldes">Q. 0.00</h3>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        </div>
                                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <a data-toggle="modal" href="#myModalapertura">
                                                <button id="btnAgregarArt" type="button" class="btn btn-info btn-block"> <span
                                                        class="fa fa-plus"></span>Apertura</button>
                                            </a>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <a data-toggle="modal" href="#myModalCierre">
                                                <button id="btnAgregarArt" type="button" class="btn btn-warning btn-block">
                                                    <span class="fa fa-close"></span>Cierre</button>
                                            </a>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <a data-toggle="modal" href="#ModalGastoVenta">
                                                <button id="btnAbonoVenta" type="button" class="btn btn-success btn-block">
                                                    <span class="fa fa-money"></span>Gasto</button>
                                            </a>
                                        </div>

                                    </div>




                                    <!-- 
                            TABLA DE DETAllES
                          -->


                                    <div class="modal fade" id="myModal23" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" style="width: 70% !important;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">&times;</button>
                                                    <h1 class="modal-title">Detalle de Cambio</h1>
                                                </div>
                                                <div class="modal-body">
                                                    <style>
                                                        .input-grande {
                                                            font-size: 40px;
                                                            /* Tamaño grande del número dentro del input */
                                                            height: 60px;
                                                            /* Altura del input para hacerlo más visible */
                                                            text-align: right;
                                                            /* Alinea los números a la derecha (como calculadora) */
                                                            padding-right: 10px;
                                                            /* Espacio interno a la derecha */
                                                            margin-bottom: 15px;
                                                            /* Espacio debajo del input */
                                                            width: 200px;
                                                            /* ← NUEVO: ancho personalizado */
                                                        }

                                                        .calculadora {
                                                            display: grid;
                                                            grid-template-columns: repeat(4, 1fr);
                                                            gap: 20px;
                                                            max-width: 300px;
                                                            margin: auto;
                                                        }

                                                        .calculadora button {
                                                            font-size: 30px;
                                                            padding: 15px 0;
                                                            background-color: #f0f0f0;
                                                            border: 1px solid #ccc;
                                                            border-radius: 8px;
                                                            cursor: pointer;
                                                            transition: all 0.2s ease-in-out;
                                                        }

                                                        .calculadora button:hover {
                                                            background-color: #dcdcdc;
                                                        }

                                                        .calculadora .borrar {
                                                            background-color: #ffc107;
                                                        }

                                                        .calculadora .limpiar {
                                                            background-color: #dc3545;
                                                            color: white;
                                                        }

                                                        .calculadora .cero {
                                                            grid-column: span 4;
                                                            background-color: #17a2b8;
                                                            color: white;
                                                        }

                                                        .calculadora-activa:focus {
                                                            border: 2px solid #007bff;
                                                            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
                                                        }
                                                    </style>
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>VENTA:</label>
                                                            <strong>
                                                                <h2 id="vistatotal"></h2>
                                                            </strong> <!-- Cambio a h2 o div según tu necesidad -->
                                                        </div>

                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>EFECTIVO:</label>
                                                            <input type="number" id="cefectivo" name="cefectivo" step="0.1"
                                                                class="form-control calculadora-activa input-grande"
                                                                onchange="calcularefectivo()"
                                                                onclick="setInputActivo('cefectivo')"
                                                                onfocus="setInputActivo('cefectivo')" value="0">
                                                            <!-- Eliminado el h1 -->
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>CREDITO:</label>
                                                            <input type="number" id="ccredito" name="ccredito" step="0.1"
                                                                class="form-control calculadora-activa input-grande"
                                                                onchange="calcularefectivo()"
                                                                onclick="setInputActivo('ccredito')"
                                                                onfocus="setInputActivo('ccredito')" value="0">
                                                            <!-- Eliminado el h1 -->
                                                        </div>

                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                            name="div_facCambiaria" id="div_facCambiaria">
                                                            <div class="box box-success box-solid collapsed-box">
                                                                <div class="box-header with-border">
                                                                    <h3 class="box-title">Factura Cambiaria</h3>

                                                                    <div class="box-tools pull-right">
                                                                        <button type="button" class="btn btn-box-tool"
                                                                            data-widget="collapse"><i class="fa fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /.box-tools -->
                                                                </div>
                                                                <!-- /.box-header -->
                                                                <div class="box-body" style="display: none;">
                                                                    <div class="col-md-12">
                                                                        <p class="text-center">
                                                                            <strong>FACTURA CAMBIARIA</strong>
                                                                        </p>

                                                                        <div class="progress-group">

                                                                            <div class="progress sm">
                                                                                <div class="progress-bar progress-bar-aqua"
                                                                                    style="width: 100%"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div
                                                                        class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Num Pagos(*):</label>
                                                                        <input type="number" class="form-control"
                                                                            name="numero_pagos" id="numero_pagos"
                                                                            onclick="setInputActivo('numero_pagos')"
                                                                            onfocus="setInputActivo('numero_pagos')"
                                                                            onchange="calculo(); mostrarBotonGenerar()">
                                                                    </div>
                                                                    <div
                                                                        class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Fecha Pago(*):</label>
                                                                        <input type="date" class="form-control"
                                                                            name="fecha_hora_pago" id="fecha_hora_pago">
                                                                    </div>
                                                                    <div
                                                                        class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Fecha Venci Fac(*):</label>
                                                                        <input type="date" class="form-control"
                                                                            name="fecha_hora_vencimiento_factura"
                                                                            id="fecha_hora_vencimiento_factura">
                                                                    </div>
                                                                    <div
                                                                        class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Q Abono(*):</label>
                                                                        <input type="number" class="form-control"
                                                                            name="monto_abono" id="monto_abono" step="0.1">
                                                                    </div>

                                                                    <div id="contenedor-boton" class="mt-3"></div>
                                                                    <div class="mt-5" id="resultados"></div>
                                                                </div>
                                                                <!-- /.box-body -->
                                                            </div>
                                                            <!-- /.box -->
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>TRANSFERENCIA:</label>
                                                            <input type="number" step="any" id="ctransferencia"
                                                                name="ctransferencia" step="0.1"
                                                                class="form-control calculadora-activa input-grande"
                                                                onchange="calcularefectivo()"
                                                                onclick="setInputActivo('ctransferencia')"
                                                                onfocus="setInputActivo('ctransferencia')" value="0">
                                                            <!-- Eliminado el h1 -->
                                                        </div>
                                                        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Autorizacion:</label>
                                                            <input type="number" id="observacion_credito"
                                                                name="observacion_credito"
                                                                onclick="setInputActivo('observacion_credito')"
                                                                onfocus="setInputActivo('observacion_credito')"
                                                                class="form-control calculadora-activa input-grande">
                                                            <!-- Eliminado el h1 -->
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Q TARJETA:</label>
                                                            <input type="number" step="any" id="ctarjeta" name="ctarjeta"
                                                                class="form-control calculadora-activa input-grande"
                                                                onchange="calcularefectivo()"
                                                                onclick="setInputActivo('ctarjeta')"
                                                                onfocus="setInputActivo('ctarjeta')" value="0">
                                                            <!-- Eliminado el h1 -->
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>CAMBIOS:</label>
                                                            <h2 id="cambio"></h2>
                                                            <!-- Opcionalmente usar h2 o div si es un encabezado importante -->
                                                            <input type="hidden" name="rescambio" id="rescambio">
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <!-- Calculadora estilo clásico -->
                                                        <div class="calculadora">
                                                            <button onclick="ingresarNumero('7', event)">7</button>
                                                            <button onclick="ingresarNumero('8', event)">8</button>
                                                            <button onclick="ingresarNumero('9', event)">9</button>
                                                            <button onclick="borrarNumero(event)" class="borrar">←</button>

                                                            <button onclick="ingresarNumero('4', event)">4</button>
                                                            <button onclick="ingresarNumero('5', event)">5</button>
                                                            <button onclick="ingresarNumero('6', event)">6</button>
                                                            <button onclick="limpiarEfectivo(event)" class="limpiar">C</button>

                                                            <button onclick="ingresarNumero('1', event)">1</button>
                                                            <button onclick="ingresarNumero('2', event)">2</button>
                                                            <button onclick="ingresarNumero('3', event)">3</button>
                                                            <button onclick="ingresarNumero('.', event)">.</button>

                                                            <button onclick="ingresarNumero('0', event)" class="cero">0</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                    id="selectTransportes">
                                                    <label>Transporte:</label>
                                                    <select id="idtransporte" name="idtransporte"
                                                        class="form-control selectpicker" data-live-search="true"></select>
                                                </div>

                                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                    id="selectMensajero">
                                                    <label>Mensajero:</label>
                                                    <select id="idmensajero" name="idmensajero"
                                                        class="form-control selectpicker" data-live-search="true"></select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success btn-block" id="btnGuardar"><i
                                                            class="fa fa-save"></i> Click P/Guardar</button>
                                                    <button type="button" class="btn btn-danger btn-block"
                                                        data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i>
                                                        Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <input type="hidden" name="total_venta_r" id="total_venta_r">
                                    <input type="hidden" name="total_ventades_r" id="total_ventades_r">
                                </form>
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                        <thead style="background-color:#A9D0F5">
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Stock</th>
                                            <th>Cant</th>
                                            <th>Presentacion</th>
                                            <th>P.V.</th>
                                            <th>Des Q/%</th>
                                            <th>S.T</th>
                                            <th>S.T.Des</th>
                                            <th>Descrip</th>
                                        </thead>
                                        <tfoot>
                                            <th>TOTAL</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><input type="hidden" name="total_venta" id="total_venta"></th>
                                            <th><input type="hidden" name="total_ventades" id="total_ventades"></th>
                                            <th></th>

                                        </tfoot>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->
        <!-- Modal -->
        <div class="modal fade" id="myModalListarProductosSolicitar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="width: 80% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Integracion de Productos a Solicitar</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <button class="btn btn-info btn-block" onclick="GenerarSolicitudPedido()">Generar Solicitud
                                Pedido</button>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio_reporteMPH"
                                    id="fecha_inicio_reporteMPH" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin_reporteMPH" id="fecha_fin_reporteMPH"
                                    value="<?php echo date("Y-m-d"); ?>">
                                <button class="btn btn-success btn-block" onclick="MostrarPedidosHechos()">Mostrar Solicitudes
                                    Hechas x Fechas</button>

                            </div>
                            <table id="tblSolicitarProductosGenerados"
                                class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Opciones</th>
                                    <th>Fecha</th>
                                    <th>No</th>
                                    <th>Usuario</th>
                                    <th>Estado</th>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal -->

        <div class="modal fade" id="myModalProductosSolicitar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="width: 80% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Productos a Solicitar</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">

                            <form name="formularioarticulosProductosSolicitar" id="formularioarticulosProductosSolicitar"
                                method="POST">
                                <input type="hidden" name="datos1ProductosSolicitar" id="datos1ProductosSolicitar">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-success btn-block"
                                        id="btnGuardarformularioarticulosProductosSolicitar"><i class="fa fa-save"></i> Click
                                        P/Guardar</button>
                                </div>

                                <table id="tblarticulosProductosSolicitar"
                                    class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>Stock</th>
                                        <th>Cant</th>
                                        <th>Presentacion</th>
                                        <th>T/Unidades</th>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>Stock</th>
                                        <th>Cant</th>
                                        <th>Presentacion</th>
                                        <th>T/Unidades</th>
                                    </tfoot>
                                </table>

                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal -->



        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width: 80% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Seleccione un Artículo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Dias Venci</th>
                                    <th>Categoría</th>
                                    <th>Código</th>
                                    <th>SKU</th>
                                    <th>UBICACION</th>
                                    <th>Stock</th>
                                    <th>Estado Stock</th>
                                    <th>Precio Venta</th>
                                    <th>Imagen</th>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th> </th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal -->
        <!-- Modal -->
        <div class="modal fade" id="myModalBusquedacliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="width: 80% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="col-lg-12 col-xs-12">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h4><img class="iconos-cambio" src="../public/iconos/busqueda.png">BUSQUE UN CLIENTE</h4>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-home"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="tbBusquedaCliente"
                                class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th># Doc</th>
                                    <th>Telefono</th>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th># Doc</th>
                                    <th>Telefono</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal -->

        <div class="modal fade" id="myModalapertura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="display: block; padding-right: 17px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Ingrese el Efectivo de Apertura</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-812 col-sm-12 col-md-812 col-xs-12 table-responsive">
                            <form name="formularioapertura" id="formularioapertura" method="POST">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Efectivo(*):</label>
                                    <input type="number" step="any" class="form-control" name="total_efectivo"
                                        id="total_efectivo">
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary btn-block" type="button" id="btnGuardarApertura"><i
                                            class="fa fa-save"></i> Guardar Apertura Caja</button>

                                    <button class="btn btn-danger btn-block" onclick="cancelarformaperturacaja()"
                                        type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="myModalCierre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="display: block; padding-right: 17px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Ingrese el Efectivo de Cierre</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-812 col-sm-12 col-md-812 col-xs-12 table-responsive">
                            <form name="formulariocierre" id="formulariocierre" method="POST">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Ingrese Total de Efectivo(*):</label>
                                    <input type="number" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_efectivocierre" id="total_efectivocierre">
                                    <input type="hidden" class="form-control" name="total_efectivo_inicio"
                                        id="total_efectivo_inicio" value="<?php echo $restotalefectivo; ?>" readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_ventas_diarias" id="total_ventas_diarias" value="<?php echo $totalv; ?>"
                                        readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_ventas_diarias_efectivo" id="total_ventas_diarias_efectivo"
                                        value="<?php echo $restotalpagoefectivo; ?>" readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_ventas_diarias_credito" id="total_ventas_diarias_credito"
                                        value="<?php echo $restotalpagocredito; ?>" readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="res_total_ventas_diarias_transferencia"
                                        id="res_total_ventas_diarias_transferencia"
                                        value="<?php echo $restotalpagotransferencia; ?>" readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_ventas_diarias_tarjeta" id="total_ventas_diarias_tarjeta"
                                        value="<?php echo $restotalpagotarjeta; ?>" readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_ventas_gastosEfectivo" id="total_ventas_gastosEfectivo"
                                        value="<?php echo $restotalgastoventa; ?>" readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_ventas_AbonosVentas" id="total_ventas_AbonosVentas"
                                        value="<?php echo $restotatalAbonoctacobrar; ?>" readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_ventas_NCVentas" id="total_ventas_NCVentas"
                                        value="<?php echo $restotalNCventa; ?>" readonly="">
                                    <input type="hidden" step="any" class="form-control" onchange="calcularcaja()"
                                        name="total_efectivo_cierre_operaciones" id="total_efectivo_cierre_operaciones"
                                        readonly="">
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary btb-block" type="button" id="btnGuardarCierre"><i
                                            class="fa fa-save"></i> Guardar</button>

                                    <button class="btn btn-danger btb-block" onclick="cancelarformCierre()" type="button"><i
                                            class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModalImpresionFAc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="display: block; padding-right: 17px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Seleccion el Formato a Imprimir</h4>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-center text-center">
                        <input type="hidden" name="idventa_impresion" id="idventa_impresion">
                        <input type="hidden" name="tipo_comprobante_impresion" id="tipo_comprobante_impresion">
                        <a class="btn btn-app btn-success m-2" onclick="impresionticket58mm()">
                            <i class="fa fa-print"></i> Ticket 58mm
                        </a>
                        <a class="btn btn-app btn-primary m-2" onclick="impresionticket79mm()">
                            <i class="fa fa-print"></i> Ticket 79mm
                        </a>
                        <a class="btn btn-app btn-info m-2" onclick="impresionticketCarta()">
                            <i class="fa fa-print"></i> Carta
                        </a>
                        <a class="btn btn-app btn-info m-2" onclick="impresionBlanco()">
                            <i class="fa fa-print"></i> Blanco
                        </a>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-block" onclick="cancelarOperacion()" type="button"><i
                                class="fa fa-arrow-circle-left"></i> Crear Nueva Venta </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ModalGastoVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><b>Registre su Gasto</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form name="formulariogastoventa" id="formulariogastoventa" method="POST">

                            <div class="form-group">
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label>Efectivo Disponible</label>
                                    <input type="number" step="any" class="form-control"
                                        name="TotalEfectivoDisponible_GastoaVenta" id="TotalEfectivoDisponible_GastoaVenta"
                                        value="<?php echo $restotalpagoefectivo; ?>" readonly="">
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label>Acumulado de Gastos:</label>
                                    <input type="number" step="any" class="form-control" name="totalAcumuladoGasots_GastoaVenta"
                                        id="totalAcumuladoGasots_GastoaVenta" value="<?php echo $restotalgastoventa; ?>"
                                        readonly="">
                                </div>

                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label>Disponible Gastos:</label>
                                    <input type="number" step="any" class="form-control" name="disponibleparaGastos_GastoaVenta"
                                        id="disponibleparaGastos_GastoaVenta" value="<?php echo $resDisponibleparaGastos; ?>"
                                        readonly="">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Serie No.:</label>
                                    <input type="text" class="form-control" name="serie_no_GastoaVenta"
                                        id="serie_no_GastoaVenta" maxlength="100" value="N/A">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>DTE No.:</label>
                                    <input type="text" class="form-control" name="factura_no_GastoaVenta"
                                        id="factura_no_GastoaVenta" maxlength="100" value="N/A">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Tipo de Factura(*):</label>
                                    <select name="tipo_factura_GastoaVenta" id="tipo_factura_GastoaVenta"
                                        class="form-control selectpicker" data-live-search="true" required="">
                                        <option value="Grabada">Grabada</option>
                                        <option value="Exenta">Exenta</option>

                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Tipo de Factura(*):</label>
                                    <select name="tipo_comprobante_GastoaVenta" id="tipo_comprobante_GastoaVenta"
                                        class="form-control selectpicker" required="">
                                        <option value="Factura">Factura</option>
                                        <option value="Recibo">Recibo</option>
                                        <option value="Nota">Nota</option>
                                        <option value="Envio">Envio</option>
                                        <option value="Efectivo">Efectivo</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Nit No.:</label>
                                    <input type="text" class="form-control" name="idcliente_GastoaVenta"
                                        id="idcliente_GastoaVenta" value="1" readonly="">
                                    <input type="text" class="form-control" name="nit_no_GastoaVenta" id="nit_no_GastoaVenta"
                                        maxlength="256" value="CF" onchange="validarnit2()">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Tipo Doc Proveedor.:</label>
                                    <select class="form-control select-picker" name="tipo_documento_cliente_GastoaVenta"
                                        id="tipo_documento_cliente_GastoaVenta" required>
                                        <option value="NIT">NIT</option>
                                        <option value="DPI">DPI</option>
                                        <option value="PASAPORTE">PASAPORTE</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Nombre Proveedor:</label>
                                    <input type="text" class="form-control" name="nombreproveedor_GastoaVenta"
                                        id="nombreproveedor_GastoaVenta" maxlength="256" value="CONSUMIDOR FINAL">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Direccion Proveedor:</label>
                                    <input type="text" class="form-control" name="direccion__GastoaVenta"
                                        id="direccion__GastoaVenta" maxlength="256" value="CIUDAD">
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Concepto Factura Proveedor.:</label>
                                    <input type="text" class="form-control" name="concepto_fac_GastoaVenta"
                                        id="concepto_fac_GastoaVenta" value="N/A" maxlength="250">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Fecha(*):</label>
                                    <input type="date" class="form-control" name="fecha_hora_GastoaVenta"
                                        id="fecha_hora_GastoaVenta" required="">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Valor Q:</label>
                                    <input type="number" step="any" class="form-control" name="valor_q_GastoaVenta"
                                        id="valor_q_GastoaVenta" maxlength="256" placeholder="00.00"
                                        onchange="validaringresoefectivo()">
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Tipo Compra(*):</label>
                                    <select name="tipo_compra_GastoaVenta" id="tipo_compra_GastoaVenta"
                                        class="form-control selectpicker" data-live-search="true" required="">
                                        <option value="Seleccion uno">Seleccion uno</option>
                                        <option value="Bien">Bien</option>
                                        <option value="Servicio">Servicio</option>
                                        <option value="Combustible">Combustible</option>
                                        <option value="Pequeño Contribuyente">Pequeño Contribuyente</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12" id="tipo_combus"
                                    name="tipo_combus">
                                    <label>Tipo Combustible(*):</label>
                                    <select name="tipo_combustible_GastoaVenta" id="tipo_combustible_GastoaVenta"
                                        class="form-control selectpicker" data-live-search="true">
                                        <option value="Seleccion uno">Seleccion uno</option>
                                        <option value="Regular">Regular</option>
                                        <option value="Super">Super</option>
                                        <option value="Diesel">Diesel</option>
                                        <option value="GasPropano">Gas Propano</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12" id="no_galo" name="no_galo">
                                    <label># Galonaje:</label>
                                    <input type="number" step="any" class="form-control" name="num_galonaje_GastoaVenta"
                                        id="num_galonaje_GastoaVenta" maxlength="50" value="0">
                                </div>
                                <center>
                                    <div id='dataresponse' class="text-primary text-center"></div>
                                </center><br>
                            </div>



                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-block save-gasto-venta" id="SaveGastoaVenta">Guardar
                            Gasto</button>
                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Cancelar Gasto</button>
                    </div>
                </div>
            </div>
        </div> <!--Fin Modal para soporte-->

        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" style="width: 40% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="col-lg-12 col-xs-12">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>CREAR TRANSPORTE</h3>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-home"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form name="formulario2t" id="formulario2t" method="POST">
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Nombre:</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50"
                                    placeholder="Nombre" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Descripcion:</label>
                                <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256"
                                    placeholder="Direccion">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Telefono:</label>
                                <input type="text" class="form-control" name="telefono" id="telefono" maxlength="256"
                                    placeholder="Telefono">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Placa:</label>
                                <input type="text" class="form-control" name="placa" id="placa" maxlength="256"
                                    placeholder="Placa">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Modelo:</label>
                                <input type="text" class="form-control" name="modelo" id="modelo" maxlength="256"
                                    placeholder="Modelo">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Marca:</label>
                                <input type="text" class="form-control" name="marca" id="marca" maxlength="256"
                                    placeholder="Marca">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Color:</label>
                                <input type="text" class="form-control" name="color" id="color" maxlength="256"
                                    placeholder="Color">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Email:</label>
                                <input type="text" class="form-control" name="email" id="email" maxlength="256"
                                    placeholder="Email">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Tipo Transporte:</label>
                                <select name="tipo_transporte" id="tipo_transporte" class="form-control selectpicker"
                                    required="">
                                    <option value="Camion">Camión</option>
                                    <option value="Furgoneta">Furgoneta</option>
                                    <option value="Automovil">Automovil</option>
                                    <option value="Motocicleta">Motocicleta</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Capacidad:</label>
                                <input type="text" class="form-control" name="capacidad" id="capacidad" maxlength="256"
                                    placeholder="Capacidada">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary btn-block" type="submit" id="btnGuardarT"><i
                                        class="fa fa-save"></i> Guardar</button>

                                <button class="btn btn-danger btn-block" data-dismiss="modal" type="button"><i
                                        class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal2M" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" style="width: 40% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="col-lg-12 col-xs-12">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>CREAR MENSAJERO</h3>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-home"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form name="formulariom" id="formulariom" method="POST">
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Nombre:</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50"
                                    placeholder="Nombre" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Descripcion:</label>
                                <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256"
                                    placeholder="Direccion">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Telefono:</label>
                                <input type="text" class="form-control" name="telefono" id="telefono" maxlength="256"
                                    placeholder="Telefono">
                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Email:</label>
                                <input type="text" class="form-control" name="email" id="email" maxlength="256"
                                    placeholder="Email">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary btn-block" type="submit" id="btnGuardarm"><i
                                        class="fa fa-save"></i> Guardar</button>

                                <button class="btn btn-danger btn-block" data-dismiss="modal" type="button"><i
                                        class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModalFormapago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" style="width: 40% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="col-lg-12 col-xs-12">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>CAMBIAR FORMA DE PAGO</h3>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-home"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form name="formulariom_formapago" id="formulariom_formapago" method="POST">
                            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label>IdVenta:</label>
                                <input type="text" class="form-control" name="idventa_formapago" id="idventa_formapago"
                                    readonly>
                            </div>
                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label>Forma pago(*):</label>
                                <select name="forma_pago_formapago" id="forma_pago_formapago" class="form-control selectpicker">
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Efectivo/Tarjeta">Efectivo/Tarjeta</option>
                                    <option value="Tarjeta">Tarjeta</option>
                                    <option value="Credito">Credito</option>
                                    <option value="Transferencia">Transferencia</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label>Destino(*):</label>
                                <select name="destino_formapago" id="destino_formapago" class="form-control selectpicker"
                                    required>
                                    <option value="VENTA">Venta directa</option>
                                    <option value="PANTALLA">Pantalla Ventas</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label>Tipo/Entrega(*):</label>
                                <select name="tipo_entrega_formapago" id="tipo_entrega_formapago" class="form-control"
                                    required="">
                                    <option value="Tienda">Tienda</option>
                                    <option value="Transporte">Transporte</option>
                                    <option value="Mensajero">Mensajero</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label>Vendedor:</label>
                                <select id="idvendedor_formapago" name="idvendedor_formapago" class="form-control selectpicker"
                                    data-live-search="true"></select>
                            </div>
                            <div class="form-group col-lg-8 col-md-12 col-sm-12 col-xs-12" id="div_formapago_formapago"
                                name="div_formapago_formapago">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label>Tipo Tarjeta:</label>
                                    <select name="tipo_pagoBacVisaNet_formapago" id="tipo_pagoBacVisaNet_formapago"
                                        class="form-control selectpicker" onchange="mostrarOpcionesAdicionalesFormapago();">
                                        <option value="Seleccione Uno">Seleccione Uno</option>
                                        <option value="VISANET">VISANET</option>
                                        <option value="BAC">BAC</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12"
                                    id="opcionesAdicionalesDiv_formapago">
                                    <label>Opciones Adicionales:</label>
                                    <select name="opcionesAdicionales_formapago" id="opcionesAdicionales_formapago"
                                        class="form-control ">
                                        <!-- Las opciones se llenarán dinámicamente -->
                                    </select>

                                </div>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>T-Venta:</label>
                                    <input type="number" step="any" class="form-control input-grande"
                                        name="total_venta_formapago" id="total_venta_formapago" readonly>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>T-Venta Des:</label>
                                    <input type="number" step="any" class="form-control input-grande"
                                        name="total_ventades_formapago" id="total_ventades_formapago" readonly>
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>EFECTIVO:</label>
                                    <input type="number" id="cefectivo_formapago" name="cefectivo_formapago" step="0.1"
                                        onchange="calcularefectivoFormapago()" class="form-control  input-grande" value="0">
                                    <!-- Eliminado el h1 -->
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>CREDITO:</label>
                                    <input type="number" id="ccredito_formapago" name="ccredito_formapago" step="0.1"
                                        onchange="calcularefectivoFormapago()" class="form-control  input-grande" value="0">
                                    <!-- Eliminado el h1 -->
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>TRANSFERENCIA:</label>
                                    <input type="number" step="any" id="ctransferencia_formapago"
                                        name="ctransferencia_formapago" onchange="calcularefectivoFormapago()" step="0.1"
                                        class="form-control input-grande" value="0"> <!-- Eliminado el h1 -->
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label>Autorizacion:</label>
                                    <input type="number" id="observacion_credito_formapago" name="observacion_credito_formapago"
                                        class="form-control input-grande"> <!-- Eliminado el h1 -->
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Q TARJETA:</label>
                                    <input type="number" step="any" id="ctarjeta_formapago" name="ctarjeta_formapago"
                                        onchange="calcularefectivoFormapago()" class="form-control input-grande" value="0">
                                    <!-- Eliminado el h1 -->
                                    <input type="hidden" step="any" id="valor_tarjeta_formapago" name="valor_tarjeta_formapago"
                                        class="form-control " value="0"> <!-- Eliminado el h1 -->
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                    name="div_facCambiaria_formapago" id="div_facCambiaria_formapago">
                                    <div class="box box-success box-solid collapsed-box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Factura Cambiaria</h3>

                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.box-tools -->
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body" style="display: none;">
                                            <div class="col-md-12">
                                                <p class="text-center">
                                                    <strong>FACTURA CAMBIARIA</strong>
                                                </p>

                                                <div class="progress-group">

                                                    <div class="progress sm">
                                                        <div class="progress-bar progress-bar-aqua" style="width: 100%"></div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Num Pagos(*):</label>
                                                <input type="number" class="form-control" name="numero_pagos_formapago"
                                                    id="numero_pagos_formapago" onchange="calculo_formapago();">
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Fecha Pago(*):</label>
                                                <input type="date" class="form-control" name="fecha_hora_pago_formapago"
                                                    id="fecha_hora_pago_formapago">
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Fecha Venci Fac(*):</label>
                                                <input type="date" class="form-control"
                                                    name="fecha_hora_vencimiento_factura_formapago"
                                                    id="fecha_hora_vencimiento_factura_formapago">
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Q Abono(*):</label>
                                                <input type="number" class="form-control" name="monto_abono_formapago"
                                                    id="monto_abono_formapago" step="0.1">
                                            </div>

                                            <div id="contenedor-boton" class="mt-3"></div>
                                            <div class="mt-5" id="resultados_formapago"></div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                            </div>



                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>CAMBIOS:</label>
                                <h2 id="cambio_formapago"></h2>
                                <!-- Opcionalmente usar h2 o div si es un encabezado importante -->
                                <input type="hidden" name="rescambio_formapago" id="rescambio_formapago">
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary btn-block" type="button" id="btnGuardarm_formapago"><i
                                        class="fa fa-save"></i> Guardar</button>

                                <button class="btn btn-danger btn-block" data-dismiss="modal" type="button"><i
                                        class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DE BÚSQUEDA Y SELECCIÓN COTIZACIONES PENDIENTES-->
        <div class="modal fade" id="modalCotizaciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #3c8dbc; color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Seleccionar Cotizaciones Pendientes</h4>
                    </div>
                    <div class="modal-body">
                        <table id="tbl_cotizaciones_modal"
                            class="table table-striped table-bordered table-condensed table-hover" style="width: 100%;">
                            <thead>
                                <th><input type="checkbox" id="chk_todos_modal" onclick="seleccionarTodosModal(this);"></th>
                                <th># Cotización</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Forma Pago</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success" onclick="agregarSeleccionadasModal();">
                            <i class="fa fa-plus"></i> Agregar Seleccionadas
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL 1: Búsqueda y Selección Rápida de Artículos -->
        <div class="modal fade" id="modalBuscarArticulos" tabindex="-1" role="dialog" aria-labelledby="modalBuscarLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width: 85% !important;">
                <div class="modal-content">
                    <div class="modal-header bg-primary" style="color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-search"></i> Búsqueda Rápida de Productos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tblarticulos_rapido" class="table table-striped table-bordered table-hover"
                                style="width:100%">
                                <thead>
                                    <th>Acción</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Ubicación</th>
                                    <th>Stock Total</th>
                                    <th>P.V. General</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <small class="text-muted pull-left"><i class="fa fa-info-circle"></i> Usa TAB / Flechas para navegar y
                            ENTER para seleccionar</small>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 2: Configuración de Presentación, Cantidad y Precio -->
        <div class="modal fade" id="modalConfigurarProducto" tabindex="-1" role="dialog" aria-labelledby="modalConfigLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-cubes"></i> Seleccionar Presentación y Cantidad</h4>
                    </div>
                    <div class="modal-body">
                        <h4 id="conf_nombre_producto" class="text-primary font-weight-bold" style="margin-top:0;"></h4>
                        <input type="hidden" id="conf_articulo_json">

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="conf_presentacion">Presentación:</label>
                                <select id="conf_presentacion" class="form-control input-lg"
                                    onchange="actualizarPrecioPresentacion()"></select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="conf_cantidad">Cantidad:</label>
                                <input type="number" step="any" min="1" value="1" id="conf_cantidad"
                                    class="form-control input-lg">
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="conf_precio">Precio Venta (P.V.):</label>
                                <input type="number" step="any" id="conf_precio" class="form-control input-lg">
                                <input type="hidden" step="any" id="conf_cantidadpresentacion" class="form-control input-lg">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success btn-lg" id="btnConfirmarAgregar"
                            onclick="confirmarAgregarAlDetalle()">
                            <i class="fa fa-plus"></i> Agregar al Detalle (Enter)
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <?php
    } else {
        require 'noacceso.php';
    }

    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/sweatlert.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="scripts/venta.js"></script>
<?php
}
ob_end_flush();
?>