<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
    if ($_SESSION['parqueo_Operaciones'] == 1) {
        require_once "../modelos/Consultas.php";
        $consulta = new Consultas();

        $rsptavc = $consulta->totalefectivoiniciocaja();
        $regvc = $rsptavc->fetch_object();
        $restotalefectivo = $regvc->totalefectivo;
        $resApertura = $regvc->tipo_operacion;

        $res_efectivo = $consulta->distribucionEfectivoParqueo();
        $resv_efectivo = $res_efectivo->fetch_object();
        $totalv = $resv_efectivo->T_Venta;
        $restotalpagoefectivo = $resv_efectivo->T_efectivo;
        $restotalpagocredito = $resv_efectivo->T_transferencia;
        $restotalpagotransferencia = $resv_efectivo->T_credito;
        $restotalpagotarjeta = $resv_efectivo->T_tarjeta;

        $resPendientes = $consulta->countTicketsPendientes();
        $badgePendientes = $resPendientes ? $resPendientes['total'] : 0;

        $resCobrados = $consulta->countTicketsCobrados();
        $badgeCobrados = $resCobrados ? $resCobrados['total'] : 0;
        ?>

        <div class="content-wrapper">
            <section class="content">

                <div class="row">
                    <div class="col-md-12">

                        <div class="box box-primary">

                            <!-- HEADER -->
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    <i class="fa fa-credit-card"></i> Operaciones - Parqueo
                                </h3>
                            </div>

                            <!-- ACCIONES -->
                            <div class="box-body">

                                <div class="row" style="margin-bottom:15px;">

                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <button class="btn btn-warning btn-block" onclick="abrirModalIngreso()">
                                            <i class="fa fa-barcode"></i> Ingresar Placa
                                        </button>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <button class="btn btn-danger btn-block" onclick="abrirModalCobro()">
                                            <i class="fa fa-money"></i> Cobrar Ticket
                                        </button>
                                    </div>


                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="btn-group btn-block">
                                            <button type="button" class="btn btn-success btn-block dropdown-toggle"
                                                data-toggle="dropdown">
                                                <i class="fa fa-calculator"></i> Corte Caja <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu btn-block" role="menu">
                                                <li><a href="#" onclick="abrirApertura()">Apertura</a></li>
                                                <li><a href="#" onclick="abrirCierre()">Cierre</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>

                                <!-- TABS -->
                                <div class="nav-tabs-custom" style="box-shadow:0 2px 6px rgba(0,0,0,0.1); border-radius:8px;">

                                    <ul class="nav nav-tabs" style="position: relative;">
                                        <li class="active">
                                            <a href="#tab_1" data-toggle="tab">Tickets Pendientes <span
                                                    class="label label-primary"><?php echo $badgePendientes; ?></span></a>
                                        </li>
                                        <li>
                                            <a href="#tab_2" data-toggle="tab">Facturados <span
                                                    class="label label-danger"><?php echo $badgeCobrados; ?></span></a>
                                        </li>

                                        <!-- RELOJ CENTRADO -->
                                        <li
                                            style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); pointer-events: none; padding: 10px;">
                                            <span id="reloj_en_vivo"
                                                style="font-size: 24px; font-weight: bold; color: #1e282c;">
                                                <i class="fa fa-clock-o"></i> 00:00:00
                                            </span>
                                        </li>

                                        <li class="pull-right header text-muted" style="font-size: 16px;">
                                            <i class="fa fa-info-circle"></i> Estado:
                                            <?php echo empty($resApertura) ? 'CERRADO' : $resApertura; ?>
                                        </li>
                                    </ul>

                                    <div class="tab-content">

                                        <!-- TAB 1 -->
                                        <div class="tab-pane active" id="tab_1">

                                            <div class="box box-warning">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <i class="fa fa-clock-o"></i> Tickets Pendientes
                                                    </h3>
                                                </div>

                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Inicio</label>
                                                    <input type="date" class="form-control" name="fecha_inicio_lectura"
                                                        id="fecha_inicio_lectura" value="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Fin</label>
                                                    <input type="date" class="form-control" name="fecha_fin_lectura"
                                                        id="fecha_fin_lectura" value="<?php echo date("Y-m-d"); ?>">
                                                    <button class="btn btn-success btn-block" onclick="listar()">Listar Tickets
                                                        Pendientes</button>
                                                </div>

                                                <div class="box-body table-responsive">

                                                    <table id="tbllistado"
                                                        class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <th>Opciones</th>
                                                            <th># Ticket</th>
                                                            <th>T-Vehiculo</th>
                                                            <th>Placa</th>
                                                            <th>Fecha-Hora</th>
                                                            <th>Usuario</th>
                                                            <th>Estado</th>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>

                                                </div>
                                            </div>

                                        </div>

                                        <!-- TAB 2 -->
                                        <div class="tab-pane" id="tab_2">

                                            <div class="box box-success">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <i class="fa fa-check"></i> Tickets Cobrados
                                                    </h3>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Inicio</label>
                                                    <input type="date" class="form-control" name="fecha_inicio_lectura_fac"
                                                        id="fecha_inicio_lectura_fac" value="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Fin</label>
                                                    <input type="date" class="form-control" name="fecha_fin_lectura_fac"
                                                        id="fecha_fin_lectura_fac" value="<?php echo date("Y-m-d"); ?>">
                                                    <button class="btn btn-success btn-block" onclick="listarFac()">Listar
                                                        Tickets Cobrados</button>
                                                </div>

                                                <div class="box-body table-responsive">

                                                    <table id="tbllistadoFac"
                                                        class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <th>Opciones</th>
                                                            <th>#Ticket</th>
                                                            <th>#Cobro</th>
                                                            <th>Cliente</th>
                                                            <th>Tipo. Doc</th>
                                                            <th>Num. Documento</th>
                                                            <th>Placa</th>
                                                            <th>Fecha Entrada</th>
                                                            <th>Fecha Salida</th>
                                                            <th>H/min</th>
                                                            <th>T.V.</th>
                                                            <th>Efectivo</th>
                                                            <th>Tarjeta</th>
                                                            <th>Transferencia</th>
                                                            <th>Credito</th>
                                                            <th>Cambio</th>
                                                            <th>Usuario</th>
                                                            <th>Serie</th>
                                                            <th>Dte</th>
                                                            <th>Estado</th>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </section>
        </div>

        <!-- MODAL INGRESO DE PLACA -->
        <div class="modal fade" id="modalIngresoVehiculo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #00a65a; color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="color: white; opacity: 1;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" style="margin: 0; font-weight: bold; text-transform: uppercase;">
                            ¡Tipo de Vehículo!
                            <i class="fa fa-home pull-right" style="opacity: 0.5; font-size: 50px; margin-top: -10px;"></i>
                        </h3>
                    </div>
                    <div class="modal-body" style="padding: 20px;">
                        <form id="formulario_ingreso" method="POST">


                            <div class="row text-center" style="margin-bottom: 20px;">

                                <div class="col-xs-4">
                                    <button type="button" class="btn btn-block btn-tipo-vehiculo" data-tipo="Carro"
                                        style="background-color: #f39c12; color: white; height: 80px; font-weight: bold; border-radius: 0;">
                                        <i class="fa fa-car fa-2x"></i><br>CARRO
                                    </button>

                                </div>

                                <div class="col-xs-4">
                                    <button type="button" class="btn btn-block btn-tipo-vehiculo" data-tipo="Moto"
                                        style="background-color: #dd4b39; color: white; height: 80px; font-weight: bold; border-radius: 0;">
                                        <i class="fa fa-motorcycle fa-2x"></i><br>MOTO
                                    </button>

                                </div>

                                <div class="col-xs-4">
                                    <button type="button" class="btn btn-block btn-tipo-vehiculo" data-tipo="Camion"
                                        style="background-color: #0073b7; color: white; height: 80px; font-weight: bold; border-radius: 0;">
                                        <i class="fa fa-truck fa-2x"></i><br>CAMIÓN
                                    </button>

                                </div>
                                <input type="text" class="form-control text-center" readonly value="0"
                                    style="border-radius: 0; background: #eee;" name="tipo_vehiculo" id="tipo_vehiculo">

                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control input-lg" id="placa" name="placa" placeholder="PLACA"
                                    style="text-transform: uppercase;" autocomplete="off" required>
                            </div>

                            <div class="form-group">
                                <input type="datetime-local" class="form-control input-lg" id="fecha_ingreso"
                                    name="fecha_ingreso" value="<?php echo date('Y-m-d\TH:i'); ?>" readonly>

                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg" id="btnGuardarIngreso">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN MODAL INGRESO -->

        <!-- MODAL COBRO DE TICKET -->
        <div class="modal fade" id="modalCobroVehiculo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dd4b39; color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="color: white; opacity: 1;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" style="margin: 0; font-weight: bold; text-transform: uppercase;">
                            ¡Cobrar Ticket!
                            <i class="fa fa-money pull-right" style="opacity: 0.5; font-size: 50px; margin-top: -10px;"></i>
                        </h3>
                    </div>
                    <div class="modal-body" style="padding: 20px;">
                        <form id="formulario_cobro" method="POST">

                            <div class="form-group">
                                <input type="hidden" name="idlectura" id="idlectura">
                                <input type="hidden" name="fecha_ingreso_cobro" id="fecha_ingreso_cobro">
                                <input type="hidden" name="tiempo_gracia_ticket_cobro" id="tiempo_gracia_ticket_cobro">
                                <input type="hidden" name="p_fraccion" id="p_fraccion">
                                <input type="hidden" name="p_hora" id="p_hora">
                                <input type="hidden" name="tarifa_dia" id="tarifa_dia">
                                <input type="hidden" name="tarifa_noche" id="tarifa_noche">
                                <input type="hidden" name="tarifa_evento" id="tarifa_evento">
                                <input type="hidden" name="tipo_vehiculo_cobro" id="tipo_vehiculo_cobro">
                                <label for="numero_ticket" style="font-weight: bold;">Número de Ticket / Placa</label>
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" id="numero_ticket" name="numero_ticket"
                                        placeholder="Ingrese No. Ticket"
                                        style="text-transform: uppercase; text-align: center; font-weight: bold; font-size: 24px;"
                                        autocomplete="off" required onchange="validarnumero_ticket()">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger btn-lg" id="btnCalcularCobro"
                                            onclick="validarnumero_ticket()">
                                            <i class="fa fa-calculator"></i> Calcular
                                        </button>
                                    </span>
                                    <select class="form-control input-lg select-picker" name="tip_evento_cobro"
                                        id="tip_evento_cobro" required>
                                        <option value="TICKET">TICKET</option>
                                        <option value="DIA">DIA</option>
                                        <option value="NOCHE">NOCHE</option>
                                        <option value="EVENTO">EVENTO</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row" style="display: none;" id="div_placaEvento">
                                <div class="col-xs-6">
                                    <input type="text" class="form-control input-lg" id="numeroplacaEvento"
                                        name="numeroplacaEvento" placeholder="Ingrese Placa"
                                        style="text-transform: uppercase; text-align: center; font-weight: bold; font-size: 24px;"
                                        autocomplete="off" required onchange="validarnumero_ticket()">
                                </div>
                                <div class="col-xs-6">
                                    <select class="form-control input-lg select-picker" name="tipo_vehiculoEventos"
                                        id="tipo_vehiculoEventos" required>
                                        <option value="CARRO">CARRO</option>
                                        <option value="MOTO">MOTO</option>
                                        <option value="CAMION">CAMION</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="fecha_cobro" style="font-weight: bold;">Fecha / Hora de Cobro</label>
                                        <input type="datetime-local" class="form-control input-lg" id="fecha_cobro"
                                            name="fecha_cobro" readonly>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label style="font-weight: bold;">Tiempo Extra (Horas/Min)</label>
                                        <div class="row">
                                            <div class="col-xs-6" style="padding-right: 5px;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-lg text-center"
                                                        id="tiempo_transcurrido_horas" name="tiempo_transcurrido_horas" readonly
                                                        placeholder="0" style="font-weight: bold; font-size: 18px;">
                                                    <span class="input-group-addon"
                                                        style="font-weight: bold; padding: 0 5px;">Hrs</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6" style="padding-left: 5px;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-lg text-center"
                                                        id="tiempo_transcurrido_minutos" name="tiempo_transcurrido_minutos"
                                                        readonly placeholder="0" style="font-weight: bold; font-size: 18px;">
                                                    <span class="input-group-addon"
                                                        style="font-weight: bold; padding: 0 5px;">Min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="form-group">
                                <label for="nit" style="font-weight: bold;">Buscar Cliente (NIT)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control input-lg" onchange="validarnit()" name="nit" id="nit"
                                        maxlength="20" value="CF" autofocus="autofocus">
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger btn-lg" onclick="validarnit()" type="button">
                                            <i class="fa fa-search"></i> Buscar NIT
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente"
                                    maxlength="256" value="CONSUMIDOR FINAL" onchange="validarnitNombre()">
                            </div>
                            <div class="row">
                                <div class="col-xs-7">
                                    <div class="form-group">
                                        <label for="direccion_cliente" style="font-weight: bold;">Dirección</label>
                                        <input type="text" class="form-control input-lg" name="direccion_cliente"
                                            id="direccion_cliente" maxlength="256" value="CIUDAD">
                                        <input type="hidden" name="idcliente" id="idcliente" value="1" readonly="">
                                    </div>
                                </div>
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <label for="tipo_documento_cliente" style="font-weight: bold;">Documento</label>
                                        <select class="form-control input-lg select-picker" name="tipo_documento_cliente"
                                            id="tipo_documento_cliente" required>
                                            <option value="NIT">NIT</option>
                                            <option value="DPI">DPI</option>
                                            <option value="PASAPORTE">PASAPORTE</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row"
                                style="background-color: #f9f9f9; border-radius: 8px; padding: 15px 15px 5px 15px; margin: 10px 0 20px 0; border: 1px solid #ddd;">

                                <!-- Fila 1: Total Principal -->
                                <div class="col-xs-12 text-center" style="margin-bottom: 15px;">
                                    <label style="font-size: 18px; font-weight: bold; color: #333;">TOTAL A PAGAR</label>
                                    <div class="input-group" style="width: 100%;">
                                        <span class="input-group-addon"
                                            style="font-size: 24px; font-weight: bold; width: 60px;">Q</span>
                                        <input type="text" class="form-control input-lg text-center" name="total_venta"
                                            id="total_venta" value="0.00" readonly
                                            style="font-size: 32px; font-weight: bold; color: #dd4b39; height: 60px;">
                                    </div>
                                </div>

                                <!-- Fila 2: Formas de pago Principales (Efectivo y Tarjeta) -->
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Efectivo:</label>
                                        <input type="number" id="cefectivo" name="cefectivo" step="0.1"
                                            class="form-control input-lg calculadora-activa text-center"
                                            onchange="calcularefectivo()" value="0" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Tarjeta:</label>
                                        <input type="number" step="any" id="ctarjeta" name="ctarjeta"
                                            class="form-control input-lg calculadora-activa text-center"
                                            onchange="calcularefectivo()" value="0" placeholder="0">
                                    </div>
                                </div>

                                <!-- Fila 3: Otros métodos (Transferencia y Crédito) -->
                                <div class="col-xs-6">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                        <label>Transferencia:</label>
                                        <input type="number" step="any" id="ctransferencia" name="ctransferencia"
                                            class="form-control calculadora-activa text-center" onchange="calcularefectivo()"
                                            value="0" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                        <label>Crédito:</label>
                                        <input type="number" id="ccredito" name="ccredito" step="0.1"
                                            class="form-control calculadora-activa text-center" onchange="calcularefectivo()"
                                            value="0" placeholder="0">
                                    </div>
                                </div>

                                <!-- Fila 4: Autorización y Vuelto (Cambios) -->
                                <div class="col-xs-6">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label>No. Autorización:</label>
                                        <input type="text" id="observacion_credito" name="observacion_credito"
                                            class="form-control text-center" placeholder="Boleto / Ref.">
                                    </div>
                                </div>
                                <div class="col-xs-6 text-center">
                                    <div class="form-group"
                                        style="margin-top: 10px; background-color: #e8f5e9; padding: 5px; border-radius: 5px;">
                                        <label style="color: #00a65a; font-size: 15px; margin: 0;">Vuelto a Entregar:</label>
                                        <h2 id="cambio"
                                            style="margin: 5px 0 0 0; color: #00a65a; font-weight: bold; font-size: 26px;">Q
                                            0.00</h2>
                                        <input type="hidden" name="rescambio" id="rescambio">
                                    </div>
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary btn-block" type="button" id="btnGuardarCobro"><i
                                            class="fa fa-save"></i> Guardar</button>

                                    <button class="btn btn-danger btn-block" data-dismiss="modal" type="button"><i
                                            class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN MODAL COBRO -->

        <!-- MODAL APERTURA CAJA -->
        <div class="modal fade" id="modalApertura" tabindex="-1" role="dialog" aria-labelledby="modalAperturaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #00a65a; color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="color: white; opacity: 1;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" style="margin: 0; font-weight: bold; text-transform: uppercase;">
                            ¡Apertura de Caja!
                            <i class="fa fa-unlock pull-right" style="opacity: 0.5; font-size: 50px; margin-top: -10px;"></i>
                        </h3>
                    </div>
                    <form id="formularioApertura" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Monto Inicial (Caja Chica):</label>
                                <input type="number" step="0.01" class="form-control input-lg text-center" name="total_efectivo"
                                    id="total_efectivo" required placeholder="0.00">
                            </div>
                            <div class="form-group" style="margin-top: 25px;">
                                <button class="btn btn-primary btn-block btn-lg" type="button" id="btnGuardarApertura"><i
                                        class="fa fa-save"></i> Guardar Apertura Caja</button>

                                <button class="btn btn-danger btn-block btn-lg" type="button"
                                    onclick="cancelarformaperturacaja()" data-dismiss="modal"><i
                                        class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL APERTURA CAJA -->

        <!-- MODAL CIERRE CAJA -->
        <!-- MODAL CIERRE DE CAJA -->
        <div class="modal fade" id="modalCierre" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content" style="border-radius: 12px;">

                    <!-- HEADER -->
                    <div class="modal-header" style="background-color: #dd4b39; color: white; border-radius: 12px 12px 0 0;">
                        <button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>

                        <h4 class="modal-title text-center" style="font-weight: bold;">
                            Cierre de Caja
                        </h4>
                    </div>

                    <!-- FORM -->
                    <form id="formularioCierre" method="POST">

                        <div class="modal-body">

                            <div class="form-group text-center">
                                <label style="font-weight:600;">Total en Efectivo</label>

                                <!-- INPUT CON ESTILO PRO -->
                                <div style="position:relative;">
                                    <span style="position:absolute; left:15px; top:18px; font-size:20px;">Q</span>

                                    <input type="number" step="0.01" min="0" class="form-control text-center"
                                        name="total_efectivocierre" id="total_efectivocierre" placeholder="0.00"
                                        style="padding-left:40px; font-size:22px; height:55px; border-radius:10px;"
                                        onchange="calcularcaja()" required>
                                </div>
                            </div>

                            <!-- CAMPOS OCULTOS -->
                            <input type="text" name="total_efectivo_inicio" id="total_efectivo_inicio"
                                value="<?php echo $restotalefectivo; ?>">

                            <input type="text" name="total_ventas_diarias" id="total_ventas_diarias"
                                value="<?php echo $totalv; ?>">

                            <input type="text" name="total_ventas_diarias_efectivo" id="total_ventas_diarias_efectivo"
                                value="<?php echo $restotalpagoefectivo; ?>">

                            <input type="text" name="total_ventas_diarias_credito" id="total_ventas_diarias_credito"
                                value="<?php echo $restotalpagocredito; ?>">

                            <input type="text" name="res_total_ventas_diarias_transferencia"
                                id="res_total_ventas_diarias_transferencia" value="<?php echo $restotalpagotransferencia; ?>">

                            <input type="text" name="total_ventas_diarias_tarjeta" id="total_ventas_diarias_tarjeta"
                                value="<?php echo $restotalpagotarjeta; ?>">

                            <input type="text" name="total_ventas_gastosEfectivo" id="total_ventas_gastosEfectivo" value="0">

                            <input type="text" name="total_ventas_AbonosVentas" id="total_ventas_AbonosVentas" value="0">

                            <input type="text" name="total_ventas_NCVentas" id="total_ventas_NCVentas" value="0">

                            <input type="text" name="total_efectivo_cierre_operaciones" id="total_efectivo_cierre_operaciones">

                        </div>

                        <!-- FOOTER -->
                        <div class="modal-footer" style="border-top:none;">

                            <button type="button" class="btn btn-danger btn-block" onclick="cancelarformcierrecaja()"
                                data-dismiss="modal" style="border-radius:10px;">
                                <i class="fa fa-times"></i> Cancelar
                            </button>

                            <button type="button" class="btn btn-success btn-block" id="btnGuardarCierre"
                                style="border-radius:10px; font-size:16px; padding:12px;">
                                <i class="fa fa-save"></i> Guardar Cierre
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>

        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script src="scripts/sweatlert.js"></script>
    <script src="scripts/parqueo_Operaciones.js"></script>

    <?php
}
ob_end_flush();
?>