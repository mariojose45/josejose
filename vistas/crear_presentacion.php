<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['almacen_crear_presentacion'] == 1) {

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
                    Presentacion
                    <small>Almacen</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Almacen</li>
                  </ol>

                </section>

              </div>

              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Nombre 01</th>
                    <th>Nombre 02</th>
                    <th>Nombre 03</th>
                    <th>Nombre 04</th>
                    <th>Nombre 05</th>
                    <th>Nombre 06</th>
                    <th>Nombre 07</th>
                    <th>Nombre 08</th>
                    <th>Nombre 09</th>
                    <th>Nombre 10</th>
                    <th>Nombre 11</th>
                    <th>Nombre 12</th>
                    <th>Nombre 13</th>
                    <th>Nombre 14</th>
                    <th>Nombre 15</th>
                    <th>Nombre 16</th>
                    <th>Nombre 17</th>
                    <th>Nombre 18</th>
                    <th>Nombre 19</th>
                    <th>Nombre 20</th>
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
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 600px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 01:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="hidden" name="idpresentacion" id="idpresentacion">
                      <input type="text" class="form-control" name="nombre_presentacion1" id="nombre_presentacion1"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 02:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion2" id="nombre_presentacion2"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 03:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion3" id="nombre_presentacion3"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 04:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion4" id="nombre_presentacion4"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 05:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion5" id="nombre_presentacion5"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 06:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion6" id="nombre_presentacion6"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 07:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion7" id="nombre_presentacion7"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 08:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion8" id="nombre_presentacion8"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 09:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion9" id="nombre_presentacion9"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 10:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion10" id="nombre_presentacion10"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 11:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion11" id="nombre_presentacion11"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 12:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion12" id="nombre_presentacion12"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 13:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion13" id="nombre_presentacion13"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 14:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion14" id="nombre_presentacion14"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 15:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion15" id="nombre_presentacion15"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 16:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion16" id="nombre_presentacion16"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 17:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion17" id="nombre_presentacion17"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 18:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion18" id="nombre_presentacion18"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 19:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion19" id="nombre_presentacion19"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Nombre 20:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre_presentacion20" id="nombre_presentacion20"
                        maxlength="50" placeholder="Nombre" required>
                    </div>
                  </div>


                  <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"
                      style="text-align: center; margin-top: 20px;">
                      <button class="btn btn-primary" type="submit" id="btnGuardar"
                        style="padding: 10px 40px; margin-right: 15px; font-size: 15px;">
                        <i class="fa fa-save"></i> Guardar
                      </button>
                      <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"
                        style="padding: 10px 40px; font-size: 15px;">
                        <i class="fa fa-arrow-circle-left"></i> Cancelar
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
  <script type="text/javascript" src="scripts/crear_presentacion.js"></script>

  <?php
}
ob_end_flush();
?>