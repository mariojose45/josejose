<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';

    if ($_SESSION['escritorio'] == 1) {


        ?>
        <!--Contenido-->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content">

                <div class="row">
                    <div class="col-md-12">

                        <div class="box">
                            <div class="box-header with-border box-primary">

                                <section class="content-header">
                                    <h1>
                                        Escritorio x Sucursal/General
                                        <small>Panel</small>
                                    </h1>

                                    <!-- 🔥 SELECT SUCURSAL -->
                                    <div class="row" style="margin-bottom:20px;">
                                        <div class="col-md-3">
                                            <label style="font-weight:600;">Fecha Inicio:</label>
                                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"
                                                value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label style="font-weight:600;">Fecha Fin:</label>
                                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                                                value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label style="font-weight:600;">Empresa:</label>
                                            <select id="idsucursal" class="form-control">
                                            </select>
                                        </div>

                                        <div class="col-md-2" style="margin-top:25px;">
                                            <button class="btn btn-success btn-block" onclick="listar()">
                                                <i class="fa fa-search"></i> Mostrar
                                            </button>
                                        </div>
                                    </div>

                                </section>
                            </div>

                            <!-- 🔥 CARDS -->
                            <div class="panel-body">
                                <style>
                                    .card-dashboard {
                                        position: relative;
                                        border-radius: 8px;
                                        padding: 20px;
                                        color: #fff;
                                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                        transition: transform 0.3s, box-shadow 0.3s;
                                        overflow: hidden;
                                        margin-bottom: 20px;
                                    }

                                    .card-dashboard:hover {
                                        transform: translateY(-3px);
                                        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
                                    }

                                    .card-title {
                                        font-size: 15px;
                                        opacity: 0.9;
                                        margin-bottom: 5px;
                                        z-index: 2;
                                        position: relative;
                                    }

                                    .card-value {
                                        font-size: 30px;
                                        font-weight: bold;
                                        z-index: 2;
                                        position: relative;
                                    }

                                    .card-icon {
                                        font-size: 65px;
                                        opacity: 0.15;
                                        position: absolute;
                                        right: -5px;
                                        bottom: 10px;
                                        z-index: 1;
                                    }

                                    /* Colores estilo AdminLTE */
                                    .bg-ingresos {
                                        background-color: #00a65a;
                                    }

                                    /* Verde */
                                    .bg-vehiculos {
                                        background-color: #3c8dbc;
                                    }

                                    /* Azul */
                                    .bg-ocupacion {
                                        background-color: #f39c12;
                                    }

                                    /* Naranja */
                                    .bg-tiempo {
                                        background-color: #dd4b39;
                                    }

                                    /* Rojo */
                                </style>

                                <!-- 💰 INGRESOS -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card-dashboard bg-ingresos">
                                        <div class="card-title">Total Ventas Hoy</div>
                                        <div class="card-value" id="ingresos">Q/ 0.00</div>
                                        <i class="fa fa-money card-icon"></i>
                                    </div>
                                </div>
                                <!-- 🚗 VEHICULOS -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card-dashboard bg-vehiculos">
                                        <div class="card-title"># Cobros Vehiculo</div>
                                        <div class="card-value" id="vehiculos">0</div>
                                        <i class="fa fa-car card-icon"></i>
                                    </div>
                                </div>

                                <!-- 📊 OCUPACION -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card-dashboard bg-ocupacion">
                                        <div class="card-title"># Lecturas Pendientes</div>
                                        <div class="card-value" id="ocupacion">0%</div>
                                        <i class="fa fa-pie-chart card-icon"></i>
                                    </div>
                                </div>

                                <!-- ⏱ TIEMPO -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card-dashboard bg-tiempo">
                                        <div class="card-title">Tiempo Promedio</div>
                                        <div class="card-value" id="tiempo">0h</div>
                                        <i class="fa fa-clock-o card-icon"></i>
                                    </div>
                                </div>

                                <div class="row" style="margin-top:20px;">

                                    <!-- LECTURAS -->
                                    <div class="col-md-6">
                                        <div class="box">
                                            <div class="box-header">
                                                <h4>📊 Lecturas por Mes</h4>
                                            </div>
                                            <div class="box-body">
                                                <canvas id="graficaLecturas"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- COBROS -->
                                    <div class="col-md-6">
                                        <div class="box">
                                            <div class="box-header">
                                                <h4>💰 Cobros por Mes</h4>
                                            </div>
                                            <div class="box-body">
                                                <canvas id="graficaCobros"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:20px;">
                                    <div class="col-md-12">
                                        <div class="box">
                                            <div class="box-header">
                                                <h4>📋 Totales Cobrados por Usuario y Sucursal</h4>
                                            </div>
                                            <div class="box-body table-responsive">
                                                <table id="tblTotalesUsuarios" class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <th>Usuario</th>
                                                        <th>Sucursal</th>
                                                        <th>Total Venta (Q/.)</th>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
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
        <!--Fin-Contenido-->
        <?php
    } else {
        require 'noacceso.php';
    }

    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/sweatlert.js"></script>
    <script src="../public/js/chart.min.js"></script>
    <script src="../public/js/Chart.bundle.min.js"></script>
    <script type="text/javascript" src="scripts/parqueo_Rpt_Graficas.js"></script>
    <?php
}
ob_end_flush();
?>