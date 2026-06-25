<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
    if ($_SESSION['Parqueo_Rpt_Ticket'] == 1) {

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
                                        Rpt Ticket
                                        <small>Parqueo</small>
                                    </h1>
                                    <ol class="breadcrumb">
                                        <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                                        <li class="active">Parqueo</li>
                                    </ol>

                                </section>
                            </div>

                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Custom Tabs (Pulled to the right) -->
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs pull-right">
                                            <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="true">Cierre
                                                    Caja</a></li>
                                            <li><a href="#tab_2-2" data-toggle="tab">Rtp Lecturas</a></li>
                                            <li class=""><a href="#tab_3-2" data-toggle="tab" aria-expanded="false">Rpt
                                                    Cobrados</a>
                                            </li>

                                            <li class="pull-left header"><i class="fa fa-th"></i> Reportes Parqueo</li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1-1">
                                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Inicio</label>
                                                    <input type="date" class="form-control" name="fecha_inicio"
                                                        id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Fin</label>
                                                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                                                        value="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Empresa(*):</label>
                                                    <select id="idsucursal" name="idsucursal" class="form-control selectpicker"
                                                        data-live-search="true" required>
                                                    </select>
                                                    <button class="btn btn-success" onclick="listar()">Mostrar x fecha y
                                                        sucursal</button>
                                                </div>
                                                <table id="tbllistado"
                                                    class="table table-striped table-bordered table-condensed table-hover">
                                                    <thead>
                                                        <th>Opciones</th>
                                                        <th>Fecha Operacion</th>
                                                        <th>Hora Operacion</th>
                                                        <th>Total Efectivo Inicio</th>
                                                        <th>Total Efectivo</th>
                                                        <th>Total Ventas Diarias</th>
                                                        <th>Total Efectivo Cierre Operaciones</th>
                                                        <th>Operacion Efectivo</th>
                                                        <th>Boleta o Descripcion</th>
                                                        <th>Valor Operacion Efectivo</th>
                                                        <th>Saldo Final Cierre Caja</th>
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
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="tab_2-2">
                                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Inicio</label>
                                                    <input type="date" class="form-control" name="fecha_inicio_lectura"
                                                        id="fecha_inicio_lectura" value="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Fin</label>
                                                    <input type="date" class="form-control" name="fecha_fin_lectura"
                                                        id="fecha_fin_lectura" value="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Empresa(*):</label>
                                                    <select id="idsucursal2" name="idsucursal2"
                                                        class="form-control selectpicker" data-live-search="true" required>
                                                    </select>
                                                    <button class="btn btn-success" onclick="listarLecturaTickets()">Mostrar x
                                                        fecha y
                                                        sucursal</button>
                                                </div>

                                                <div class="box-body table-responsive">

                                                    <table id="tbllistadoLectura"
                                                        class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <th>Opciones</th>
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
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="tab_3-2">
                                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Inicio</label>
                                                    <input type="date" class="form-control" name="fecha_inicio_lectura_fac"
                                                        id="fecha_inicio_lectura_fac" value="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Fecha Fin</label>
                                                    <input type="date" class="form-control" name="fecha_fin_lectura_fac"
                                                        id="fecha_fin_lectura_fac" value="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Empresa(*):</label>
                                                    <select id="idsucursal3" name="idsucursal3"
                                                        class="form-control selectpicker" data-live-search="true" required>
                                                    </select>
                                                    <button class="btn btn-success" onclick="listarFac()">Mostrar x
                                                        fecha y
                                                        sucursal</button>
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
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- nav-tabs-custom -->
                                </div>

                            </div>
                            <div class="panel-body" style="height: 400px;" id="formularioregistros">
                                <form name="formulario" id="formulario" method="POST">

                                </form>
                            </div>
                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->


        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/sweatlert.js"></script>
    <script type="text/javascript" src="scripts/parqueo_Rpt_Ticket.js"></script>



    <?php
}
ob_end_flush();
?>