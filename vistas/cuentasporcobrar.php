<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['cta_cobrar_cobrar'] == 1) {

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
                    Cta x Cobrar
                    <small>Integracion de Ctas x Cobrar x unidad y lote</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Cta x Cobrar</li>
                  </ol>

                </section>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a data-toggle="modal" href="#myModalVentasxlote">
                  <button id="btnAgregarArt " type="button" class="btn btn-primary btn-block" onclick="limpiarmodal();"> <span class="fa fa-plus "></span> Abonos x lotes</button>
                </a>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label>Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label>Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">

              </div>

              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="box-header with-border">
                    <h3 class="box-title">Detalle pendientes de Facturas x cobrar</h3>

                  </div>
                  <button class="btn btn-info btn-block" onclick="listarFacturas()">Mostrar Facturas a cobrar</button>
                  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                      <th>Opciones</th>
                      <th>Idventa</th>
                      <th>Sucursal</th>
                      <th>User Craecion</th>
                      <th>Cliente</th>
                      <th>Fecha Fac</th>
                      <th>Total Venta</th>
                      <th>Abono</th>
                      <th>Saldo Venta</th>
                      <th># Pagos</th>
                      <th># Abonos</th>
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
                    </tfoot>
                  </table>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="box-header with-border">
                    <h3 class="box-title">Abonos a Facturas x cobrar</h3>
                  </div>
                  <button class="btn btn-info btn-block" onclick="listarAbonos()">Abonos Facturas a cobrar</button>
                  <table id="tbllistadoPagosEchos" class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                      <th>Opciones</th>
                      <th>id</th>
                      <th>Fech/Pago</th>
                      <th>User</th>
                      <th>Cliente</th>
                      <th>Total Venta</th>
                      <th>Abono</th>
                      <th>Saldo Venta</th>
                      <th>IdVenta</th>
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
                    </tfoot>
                  </table>
                </div>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Nombre Cliente</label>
                    <input type="hidden" name="idventa" id="idventa">
                    <input type="hidden" name="idcliente" id="idcliente">
                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Nombre Cliente" required>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Telefono Cliente:</label>
                    <input type="text" class="form-control" name="telefonocliente" id="telefonocliente" maxlength="256" placeholder="Telefono">
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Fecha Factura(*):</label>
                    <input type="date" class="form-control" name="fecha_hora_factura" id="fecha_hora_factura" required="">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Total Venta:</label>
                    <input type="number" step="any" onchange="calculosaldoingreso();" class="form-control" name="total_venta" id="total_venta">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Abono:</label>
                    <input type="number" step="any" onchange="calculosaldoingreso();" class="form-control" name="total_abono" id="total_abono">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Saldo Venta:</label>
                    <input type="number" step="any" onchange="calculosaldoingreso();" class="form-control" name="saldo_venta" id="saldo_venta">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Tipo Pago:</label>
                    <select class="form-control select-picker" name="tipo_pago" id="tipo_pago" required>
                      <option value="EFECTIVO">EFECTIVO</option>
                      <option value="CHEQUE">CHEQUE</option>
                      <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Fecha Deposito/Operacion/Pago(*):</label>
                    <input type="date" class="form-control" name="fechapago" id="fechapago" required="">
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Tipo Banco:</label>
                    <select class="form-control select-picker" name="tipo_banco" id="tipo_banco" required>
                      <option value="BANRURAL">BANRURAL</option>
                      <option value="BANCO AGRICOLA MERCANTIL">BANCO AGRICOLA MERCANTIL</option>
                      <option value="INDUSTRIAL">INDUSTRIAL</option>
                      <option value="BAC REFORMADOR">BAC REFORMADOR</option>
                      <option value="BANCO GYT">BANCO GYT</option>
                      <option value="BANCO PROMERICA">BANCO PROMERICA</option>
                      <option value="BANCO FICOSA">BANCO FICOSA</option>
                      <option value="INTERBANCO">INTERBANCO</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Boleta #:</label>
                    <input type="text" class="form-control" name="numero_boleta" id="numero_boleta" maxlength="256" placeholder="Boleta #">
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Recibo caja #:</label>
                    <input type="text" class="form-control" name="recibo_caja_numero" id="recibo_caja_numero" maxlength="256" placeholder="Recibo caja #">
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Descripcion:</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256">
                  </div>


                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                    <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
    <div class="modal fade" id="myModalVentasxlote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 95% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccion las Ventas a liquidar</h4>
            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
              <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="idruta">Ruta de Visita:</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                  <select id="idruta" name="idruta" class="form-control selectpicker" data-live-search="true" title="Seleccionar Ruta"></select>
                </div>
              </div>
              <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="idsector">Sector:</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                  <select id="idsector" name="idsector" class="form-control selectpicker" data-live-search="true" title="Seleccionar Sector"></select>
                </div>
              </div>
              <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label>&nbsp;</label>
                <button class="btn btn-success btn-block" onclick="listarVentaxlote()" type="button"><i class="fa fa-gears"></i> Ver Facturas x filtro</button>
              </div>
              <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label>&nbsp;</label>
                <button class="btn btn-success btn-block" onclick="listarVentaxloteGeneral()" type="button"><i class="fa fa-gears"></i> Ver Facturas General</button>
              </div>
            </div>
            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-info btn-block" type="button" id="btnGuardarFacxLotes"><i class="fa fa-gears"></i> Guardar Facturas</button>
              </div>
              <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label><img class="iconos-cambio" src="../public/iconos/efectivo.png">Total Abonos:</label>
                <font color="Red">
                  <h1 id="totalAbonoGeneral">Q/. 0.00</h1>
                </font>
              </div>
              <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label><img class="iconos-cambio" src="../public/iconos/efectivo.png">Total Saldo Pendiente x Pagar:</label>
                <font color="Red">
                  <h1 id="totalSaldoGeneral">Q/. 0.00</h1>
                </font>
              </div>
            </div>



          </div>
          <div class="modal-body">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
              <table id="tbllistadoventasxlote" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Idventa</th>
                  <th>Sucursal</th>
                  <th>User Creacion</th>
                  <th>Cliente</th>
                  <th>Tipo Doc</th>
                  <th>DTE</th>
                  <th>Fecha Fac</th>
                  <th>Total Venta</th>
                  <th>Abono</th>
                  <th>Saldo Venta</th>
                  <th># Pagos</th>
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
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
              <form name="formularioxlote" id="formularioxlote" method="POST">
                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                  <thead style="background-color:#A9D0F5">
                    <th>Opciones</th>
                    <th>Cliente</th>
                    <th>Fecha Factura</th>
                    <th>T/doc</th>
                    <th>DTE</th>
                    <th>Saldo Venta</th>
                    <th>Abono</th>
                    <th>S.Abono</th>
                    <th>T/Pago</th>
                    <th>Fecha Pago</th>
                    <th>Tipo/Banco</th>
                    <th>Boleta #</th>
                    <th>Recibo #</th>
                    <th>Descripcion</th>
                  </thead>
                  <tfoot>
                    <th>TOTAL</th>
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
                    <th><input type="hidden" name="totalAbonoGeneral_t" id="totalAbonoGeneral_t"></th>
                    <th><input type="hidden" name="totalSaldoGeneral_t" id="totalSaldoGeneral_t"></th>

                  </tfoot>
                  <tbody>

                  </tbody>
                </table>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!--Fin-Contenido-->
  <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/cuentasporcobrar.js"></script>

<?php
}
ob_end_flush();
?>