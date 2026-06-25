<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['cajachica'] == 1) {
    ?>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="callout callout-warning">
                <h4>Modulo de Caja Chica</h4>
                En este modulo podrá llevar el control de Caja Chica.
              </div>
              <div class="box-header with-border">
                <h1 class="box-title"><button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i
                      class="fa fa-plus-circle"></i> Nueva Caja Chica</button></h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>#Caja Chica</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Val. Inicial</th>
                    <th>Total Abonos a Caja Chica</th>
                    <th>Total Restante</th>
                    <th>Estado Caja</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>#Caja Chica</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Val. Inicial</th>
                    <th>Total Abonos a Caja Chica</th>
                    <th>Total Restante</th>
                    <th>Estado Caja</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <label>Valor Aperura de Caja Chica:</label>
                    <input type="hidden" name="idcotizacion" id="idcotizacion">
                    <input type="number" id='aperturaCaja' class="form-control" placeholder="0.00" required="">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Fecha Caja Chica:</label>
                    <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                  </div>

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Observaciones:</label>
                    <input type="text" class="form-control" id="observacionesCaja" name="observacionesCaja" required="">
                  </div>


                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnSave" onclick="guardaryeditar()"><i
                        class="fa fa-save"></i> Guardar</button>

                    <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i
                        class="fa fa-arrow-circle-left"></i> Cancelar</button>




                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button id="btnRegresar" style='display:none;' class="btn btn-warning" onclick="cancelarform()"
                      type="button"><i class="fa fa-arrow-circle-left"></i>REGRESAR</button>
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
    <!-- Modal -->

    <div class="modal fade opaque" id="ModalAbonoCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><b>Abonar a la Caja Chica</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>

              <div class="form-group">
                <label for="recipient-name" class="form-control-label">Tipo Comprobante(*):</label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                  <option value="Factura">Factura</option>
                  <option value="Recibo">Recibo</option>
                  <option value="Nota">Nota</option>
                  <option value="Envio">Envio</option>
                  <option value="Efectivo">Efectivo</option>
                </select>

                <label for="recipient-name" class="form-control-label">Abono de:</label>
                <input type="number" placeholder="0.00" class="form-control" id="AbonoCajaChica" name="AbonoCajaChica"
                  required=""><br>

                <label for="recipient-name" class="form-control-label">Fecha</label>
                <input type="date" class="form-control" id="Fechaabono" name="Fechaabono" required=""><br>

                <label for="recipient-name" class="form-control-label">Concepto de:</label>
                <input type="text" placeholder="Abono Por:" class="form-control" id="ConceptoAbono" name="ConceptoAbono"
                  required=""><br>


                <center>
                  <div id='dataresponse' class="text-primary text-center"></div>
                </center><br>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="SaveAbono">Guardar Abono</button>
          </div>
        </div>
      </div>
    </div> <!--Fin Modal para soporte-->


    <!-- Fin modal -->








    <div class="modal fade opaque" id="ModalDetalleCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><b>Detalle de la Caja Chica</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id='modal-body'>

            <table class="table table-bordered table-hover table-striped" style="border: 1px;" id="tbllistadoAbonos">


            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div> <!--Fin Modal para soporte-->




    <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/caja_chica.js"></script>
<?php
}
ob_end_flush();
?>