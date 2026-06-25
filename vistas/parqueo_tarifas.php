<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
    if ($_SESSION['almacen_crear_categoria'] == 1) {

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
                                        Tarifas
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
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Sucursal</th>
                                        <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" style="height: 400px;" id="formularioregistros">
                                <form name="formulario" id="formulario" method="POST">

                                    <input type="hidden" name="id_tariasprecios" id="id_tariasprecios">

                                    <!-- ===================== TARIFAS VEHICULOS ===================== -->

                                    <div class="box box-primary">

                                        <div class="box-header with-border">
                                            <h3 class="box-title"><i class="fa fa-car"></i> Tarifas por Vehículo</h3>
                                        </div>

                                        <div class="box-body">

                                            <div class="row">

                                                <!-- CARRO -->
                                                <div class="col-md-4">
                                                    <div class="box box-success">

                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Carro / Auto</h3>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group">
                                                                <label>Precio por Fracción</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="precio_fraccion_carro" id="precio_fraccion_carro">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Precio por Hora</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="precio_hora_carro" id="precio_hora_carro">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Día</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_dia_carro" id="tarifa_dia_carro">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Noche</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_noche_carro" id="tarifa_noche_carro">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Evento</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_evento_carro" id="tarifa_evento_carro">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- MOTO -->
                                                <div class="col-md-4">
                                                    <div class="box box-success">

                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Moto / Motocicleta</h3>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group">
                                                                <label>Precio por Fracción</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="precio_fraccion_moto" id="precio_fraccion_moto">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Precio por Hora</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="precio_hora_moto" id="precio_hora_moto">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Día</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_dia_moto" id="tarifa_dia_moto">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Noche</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_noche_moto" id="tarifa_noche_moto">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Evento</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_evento_moto" id="tarifa_evento_moto">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- CAMION -->
                                                <div class="col-md-4">
                                                    <div class="box box-success">

                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Camión</h3>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group">
                                                                <label>Precio por Fracción</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="precio_fraccion_camion" id="precio_fraccion_camion">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Precio por Hora</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="precio_hora_camion" id="precio_hora_camion">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Día</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_dia_camion" id="tarifa_dia_camion">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Noche</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_noche_camion" id="tarifa_noche_camion">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tarifa por Evento</label>
                                                                <input type="number" step="0.01" min="0" class="form-control"
                                                                    name="tarifa_evento_camion" id="tarifa_evento_camion">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <!-- ===================== CONFIGURACION TICKET ===================== -->

                                    <div class="box box-warning">

                                        <div class="box-header with-border">
                                            <h3 class="box-title"><i class="fa fa-ticket"></i> Configuración de Ticket</h3>
                                        </div>

                                        <div class="box-body">

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>No. Correlativo Ticket</label>
                                                        <input type="number" class="form-control" name="no_correlativo_ticket"
                                                            id="no_correlativo_ticket">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Valor Ticket Extraviado</label>
                                                        <input type="number" step="0.01" min="0" class="form-control"
                                                            name="valor_ticket_extraviado" id="valor_ticket_extraviado">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tiempo de Gracia (minutos)</label>
                                                        <input type="number" class="form-control" name="tiempo_gracia_ticket"
                                                            id="tiempo_gracia_ticket">
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>


                                    <!-- ===================== CONFIGURACION GENERAL ===================== -->

                                    <div class="box box-info">

                                        <div class="box-header with-border">
                                            <h3 class="box-title"><i class="fa fa-cogs"></i> Configuración General</h3>
                                        </div>

                                        <div class="box-body">

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Cantidad de Parqueos</label>
                                                        <input type="number" class="form-control" name="cantidad_parqueos"
                                                            id="cantidad_parqueos">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Empresa</label>
                                                        <select id="idsucursal" name="idsucursal"
                                                            class="form-control selectpicker" data-live-search="true"
                                                            required></select>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>


                                    <!-- ===================== BOTONES ===================== -->

                                    <div class="row">

                                        <div class="col-md-6">
                                            <button class="btn btn-primary btn-block" type="submit" id="btnGuardar">
                                                <i class="fa fa-save"></i> Guardar
                                            </button>
                                        </div>

                                        <div class="col-md-6">
                                            <button class="btn btn-danger btn-block" onclick="cancelarform()" type="button">
                                                <i class="fa fa-times"></i> Cancelar
                                            </button>
                                        </div>

                                    </div>

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
    <script type="text/javascript" src="scripts/parqueo_tarifas.js"></script>

    <!-- ✅ Luego Driver.js -->
    <script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css" />


    <?php
}
ob_end_flush();
?>