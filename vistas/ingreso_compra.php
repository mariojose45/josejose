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
  if ($_SESSION['compras_revision_orden'] == 1) {
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
                    Ingreso de Ordenes de
                    <small>Compras</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Compras</li>
                  </ol>

                </section>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Fecha Inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte"
                    value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Fecha Fin</label>
                  <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte"
                    value="<?php echo date("Y-m-d"); ?>">
                  <button class="btn btn-success btn-block" onclick="listar()">Generar Ingresos</button>
                </div>
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th># Orden</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Estado</th>
                    <th>Estado Orden de Compra</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th># Orden</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Estado</th>
                    <th>Estado Orden de Compra</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 1000px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <input type="hidden" name="idorden_compra" id="idorden_compra">


                  <!-- 
                            TABLA DE DETALLES
                          -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Estado Orden de Compra:</label>
                    <select name="estado_orden_compra" id="estado_orden_compra" class="form-control selectpicker"
                      required="Seleccione un Estado para el Ingreso">
                      <option value="NA">NA</option>
                      <option value="Orden de compra">Orden de Compra</option>
                      <option value="Ingreso de Compra">Ingreso de Compra</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <label style="display: block; visibility: hidden;">Acciones</label>
                    <button class="btn btn-primary " type="submit" id="btnGuardar"><i class="fa fa-save"></i>
                      Guardar</button>
                    <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i
                        class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  </div>


                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                  </div>
                  <input type="hidden" name="total_compra_r" id="total_compra_r">
                  <input type="hidden" name="total_comprades_r" id="total_comprades_r">
                </form>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color:#A9D0F5">
                      <th>Opciones</th>
                      <th>Artículo</th>
                      <th>F/Vencimiento</th>
                      <th>Cantidad</th>
                      <th>Presentacion</th>
                      <th>Descripción</th>

                      <th></th>
                    </thead>
                    <tfoot>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>

                      <th>

                        <input type="hidden" name="total_compra" id="total_compra">
                      </th>
                      <th>

                        <input type="hidden" name="total_comprades" id="total_comprades">
                      </th>
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
    <!-- Modal -->



    <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript">
  </script>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/ingreso_compra.js"></script>
  <?php
}
ob_end_flush();
?>