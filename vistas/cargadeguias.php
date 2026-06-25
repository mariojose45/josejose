<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  //require 'phpexcel/Classes/PHPExcel.php.php';
  if ($_SESSION['guiastransporte'] == 1) {

    ?>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3>Modulo de Carga de Guias a liquidar!</h3>
                  </div>
                  <div class="icon">
                    <i class="fa fa-home"></i>
                  </div>

                </div>
              </div>

              <div class=" with-border">
                <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar"
                    onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <label>Fecha Inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"
                    value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <label>Fecha Fin</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                    value="<?php echo date("Y-m-d"); ?>">
                  <button class="btn btn-success" onclick="listar()">Mostrar</button>
                </div>
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Observacion</th>
                    <th>Transporte</th>
                    <th>Estado</th>
                    <th>#</th>
                    <th>Sucursal</th>
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
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form name="formulario" id="formulario" enctype="multipart/form-data" method="POST">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>TRANSPORTE(*):</label>
                    <select id="idtransporte" name="idtransporte" class="form-control selectpicker" data-live-search="true"
                      required></select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Obervacion(*):</label>
                    <input type="text" class="form-control" name="obervacioncargaexcel" id="obervacioncargaexcel">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Fecha(*):</label>
                    <input type="date" class="form-control" name="fecha_cargaExcel" id="fecha_cargaExcel">
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                      <div class="card-header">
                        CARGA DE GUIA A LIQUIDAR EN EXCEL
                      </div>
                      <div class="card-body">
                        <input type="file" class="form-control" name="txt_archivo" id="txt_archivo"
                          accept=".csv,.xlsx,.xls">
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="button" onclick="cargaexcel()"><i class="fa fa-save"></i> Cargar
                      Excel</button>
                    <button class="btn btn-warning" type="button" onclick="GuardarExcel()" disabled
                      id="btn_guardarregistros"><i class="fa fa-save"></i> Guardar Excel</button>

                    <button class="btn btn-danger" onclick="cancelarform()" type="button"><i
                        class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_table">
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
  <script type="text/javascript" src="scripts/cargadeguias.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script type="text/javascript">
    $('input[type="file"]').on('change', function () {
      var ext = $(this).val().split('.').pop();
      if ($(this).val() != '') {
        if (ext == "xls" || ext == "xlsx" || ext == "csv") {
        }
        else {
          $(this).val('');
          Swal.fire("Mensaje De Error", "Extensión no permitida: " + ext + "", "error");
        }
      }
    });
  </script>

  <?php
}
ob_end_flush();
?>