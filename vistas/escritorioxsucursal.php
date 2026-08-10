<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['escritorio'] == 1) {
    require_once "../modelos/Consultas.php";
    $consulta = new Consultas();

    // Obtener el filtro de la URL si existe
    $idsucursal_filtro = isset($_GET['idsucursal_filtro']) ? $_GET['idsucursal_filtro'] : "";
    // Las variables de las cajas superiores ahora se cargan vía AJAX
    // Los gráficos ahora se cargan vía AJAX
?>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border box box-primary">
                <section class="content-header">
                  <h1>
                    Escritorio x Sucursal/General
                    <small>Panel</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Articulos</li>
                  </ol>

                </section>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Sucursal (*)</label>
                  <select id="idsucursal_filtro" name="idsucursal_filtro" class="form-control selectpicker" data-live-search="true" required>
                  </select>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>&nbsp;</label><br>
                  <button class="btn btn-primary" id="btnGenerar" type="button"><i class="fa fa-refresh"></i> Generar</button>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-aqua" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner" style="height: 130px; display: flex; flex-direction: column; justify-content: center;">
                      <h4 style="font-size: 22px; font-weight: bold; margin: 0 0 5px 0;">
                        Q/ <span id="lbl_totalc">0.00</span>
                      </h4>
                      <p style="font-size: 15px; margin: 0;">Compras General</p>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px;">
                      <img class="iconos-escritorio" src="../public/iconos/compras.png" style="width: 70px; opacity: 0.7;">
                    </div>
                    <a href="ingreso.php" class="small-box-footer" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">Compras <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-green" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner" style="height: 130px; display: flex; flex-direction: column; justify-content: center;">
                      <h4 style="font-size: 22px; font-weight: bold; margin: 0 0 5px 0;">
                        Q/ <span id="lbl_totalv">0.00</span>
                      </h4>
                      <p style="font-size: 15px; margin: 0;">Ventas General</p>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px;">
                      <img class="iconos-escritorio" src="../public/iconos/venta.png" style="width: 70px; opacity: 0.7;">
                    </div>
                    <a href="venta.php" class="small-box-footer" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-red" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner" style="height: 130px; display: flex; flex-direction: column; justify-content: center;">
                      <h4 style="font-size: 22px; font-weight: bold; margin: 0 0 5px 0;">
                        Q/ <span id="lbl_totalvm">0.00</span>
                      </h4>
                      <p style="font-size: 15px; margin: 0;">Ventas Mes Actual General</p>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px;">
                      <img class="iconos-escritorio" src="../public/iconos/venta.png" style="width: 70px; opacity: 0.7;">
                    </div>
                    <a href="venta.php" class="small-box-footer" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-yellow" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner" style="height: 130px; display: flex; flex-direction: column; justify-content: center; position: relative; z-index: 2;">
                      <div style="font-size: 14px; margin-bottom: 3px;"><strong>Ctas x Cobrar:</strong> (<span id="lbl_totalitem">0</span>)</div>
                      <div style="font-size: 14px; margin-bottom: 3px;"><strong>Saldo x Cobrar:</strong> Q<span id="lbl_totalcobrar">0.00</span></div>
                      <div style="font-size: 14px;"><strong>Total Abonos:</strong> Q<span id="lbl_totalabonos">0.00</span></div>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px; z-index: 1;">
                      <img class="iconos-escritorio" src="../public/iconos/venta.png" style="width: 70px; opacity: 0.3;">
                    </div>
                    <a href="#" data-toggle="modal" data-target="#modalCuentasCobrar" class="small-box-footer" style="position: relative; z-index: 3; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">Ver Detalles en Modal <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-purple" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner" style="height: 130px; display: flex; flex-direction: column; justify-content: center;">
                      <h4 style="font-size: 22px; font-weight: bold; margin: 0 0 5px 0;">
                        Q/ <span id="lbl_totalcapital">0.00</span>
                      </h4>
                      <p style="font-size: 15px; margin: 0;">Capital Recuperado (Mes Actual)</p>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px;">
                      <img class="iconos-escritorio" src="../public/iconos/compras.png" style="width: 70px; opacity: 0.7;">
                    </div>
                    <a href="venta.php" class="small-box-footer" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-teal" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="inner" style="height: 130px; display: flex; flex-direction: column; justify-content: center; position: relative; z-index: 2;">
                      <div style="font-size: 14px; margin-bottom: 3px;"><strong>Ctas x Pagar:</strong> (<span id="lbl_totalitem_pagar">0</span>)</div>
                      <div style="font-size: 14px; margin-bottom: 3px;"><strong>Saldo x Pagar:</strong> Q<span id="lbl_totalpagar">0.00</span></div>
                      <div style="font-size: 14px;"><strong>Total Pagos:</strong> Q<span id="lbl_totalpagos_pagar">0.00</span></div>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px; z-index: 1;">
                      <img class="iconos-escritorio" src="../public/iconos/compras.png" style="width: 70px; opacity: 0.3;">
                    </div>
                    <a href="#" data-toggle="modal" data-target="#modalCuentasPagar" class="small-box-footer" style="position: relative; z-index: 3; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">Ver Detalles en Modal <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
              </div>

              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="box box-warning box-solid">
                  <div class="box-header with-border">
                    <h3 class="box-title">REPORTES DE VENTAS</h3>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>

                  </div>

                  <div class="box-body" style="">
                    <div>
                      <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <h4 style="font-size:17px; display: flex; justify-content: space-between;">
                          Inventario x Sucursal
                        </h4>
                        <div>
                          <table id="tbllistadoInventarioxSucusal" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                              <th>Sucursal</th>
                              <th>Compra</th>
                              <th>Precio Venta</th>
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
                      </div>
                      <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <h4 style="font-size:17px; display: flex; justify-content: space-between;">
                          Ventas x Sucursal hoy
                        </h4>
                        <div>
                          <table id="tbllistadoVentasxSucusal" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                              <th>Sucursal</th>
                              <th>Precio Venta</th>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                              <th></th>
                              <th></th>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <h4 style="font-size:17px; display: flex; justify-content: space-between;">
                          Ventas x Sucursal Mes
                        </h4>
                        <div>
                          <table id="tbllistadoVentasxSucusalMes" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                              <th>Mes</th>
                              <th>Sucursal</th>
                              <th>Precio Venta</th>
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
                      </div>
                      <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <h4 style="font-size:17px; display: flex; justify-content: space-between;">
                          Ventas vrs Ganacia x Sucursal Mes
                        </h4>
                        <div>
                          <table id="tbllistadoVentasxSucusalMesGanacia" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                              <th>Mes</th>
                              <th>Sucursal</th>
                              <th>Precio V</th>
                              <th>Precio C</th>
                              <th>Ganacia</th>
                              <th>Promociones</th>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                              <th>TOTALES</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

              </div>



              <div class="panel-body">


                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Compras de los últimos 10 días
                    </div>
                    <div class="box-body">
                      <canvas id="compras" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Ventas de los últimos 12 meses
                    </div>
                    <div class="box-body">
                      <canvas id="ventas" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Ventas Comparativas mes x mes del año actual y anterior
                    </div>
                    <div class="box-body">
                      <canvas id="ventascomparativas" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>                
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Top 10 Articulos mas Vendidos General
                    </div>
                    <div class="box-body">
                      <canvas id="articulostop" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Integracion de Clientes Nuevos 12 meses
                    </div>
                    <div class="box-body">
                      <canvas id="integracionClientesnuevos12meses" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Integracion de Proveedores Nuevos 12 meses
                    </div>
                    <div class="box-body">
                      <canvas id="integracionProveedornuevos12meses" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Total Clientes/Proveedores 12 meses
                    </div>
                    <div class="box-body">
                      <canvas id="integracionPersonanuevos12meses" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>

              </div>

              <!--Fin centro -->
            </div><!-- /.box -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->

      <!-- Modal -->
      <div class="modal fade" id="modalCuentasCobrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 85% !important;">
          <div class="modal-content">
            <div class="modal-header bg-yellow">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Detalle Cuentas por Cobrar</h4>
            </div>
            <div class="modal-body table-responsive">
              <table id="tblDetalleCuentasCobrar" class="table table-striped table-bordered table-condensed table-hover" width="100%">
                <thead>
                  <th>IdVenta</th>
                  <th>Cliente</th>
                  <th>Comprobante</th>
                  <th>DTE</th>
                  <th>Fecha</th>
                  <th>Venta</th>
                  <th>Abonos</th>
                  <th>Saldo</th>
                  <th>Cuotas</th>
                  <th>Estado</th>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- Fin Modal -->

      <!-- Modal Cuentas Pagar -->
      <div class="modal fade" id="modalCuentasPagar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 85% !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Detalle de Cuentas por Pagar</h4>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table id="tbldetalle_cuentaspagar" class="table table-striped table-bordered table-condensed table-hover" style="width: 100%;">
                  <thead>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Tipo Comprobante</th>
                    <th>No. Comprobante</th>
                    <th>Fecha</th>
                    <th>Total Compra</th>
                    <th>Valor Pagado</th>
                    <th>Saldo x Pagar</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- Fin Modal Cuentas Pagar -->

    </div><!-- /.content-wrapper -->
    <!--Fin-Contenido-->
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script src="../public/js/chart.min.js"></script>
  <script src="../public/js/Chart.bundle.min.js"></script>
  <script type="text/javascript" src="scripts/ventasxfechaxmes.js"></script>

  </script>
<?php
}
ob_end_flush();
?>