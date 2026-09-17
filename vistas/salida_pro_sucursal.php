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
  if ($_SESSION['salidaproducto'] == 1) {
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
                    Salida
                    <small>Productos</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Salida</li>
                  </ol>

                </section>
                <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> </h1>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <label>Fecha Inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <label>Fecha Fin</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <label>Sucursal</label>
                  <select id="idsucursal_filtro" name="idsucursal_filtro" class="form-control selectpicker" data-live-search="true" data-container="body">
                  </select>
                  <button class="btn btn-info btn-block" onclick="listar()">Listar</button>
                </div>
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th># Salida</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Sucursal Destino</th>
                    <th>Sucursal Origen</th>
                    <th>Descripcion</th>
                    <th>Total Venta</th>
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
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <input type="hidden" name="datos1" id="datos1">
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Sucursal Origen(*):</label>
                    <input type="hidden" name="idtraladosucursal" id="idtraladosucursal">
                    <select id="idsucursalOrigen" name="idsucursalOrigen" class="form-control selectpicker" data-live-search="true" required>

                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Sucursal Destino(*):</label>
                    <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required>

                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Fecha(*):</label>
                    <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Descripcion Salida producto:</label>
                    <input type="text" class="form-control" name="descripcion_salida_producto" id="descripcion_salida_producto">
                  </div>

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a data-toggle="modal" href="#myModal">
                      <button id="btnAgregarArt" type="button" onclick="listarArticulos()" class="btn btn-primary btn-block"> <span class="fa fa-plus"></span> Agregar Artículos</button>
                    </a>
                  </div>

                  <!-- 
                              TABLA DE DETALLES
                          -->

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary btn-block" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                    <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  </div>
                  <input type="hidden" name="total_venta_r" id="total_venta_r">
                </form>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color:#A9D0F5">
                      <th>Opciones</th>
                      <th>Stock</th>
                      <th>Artículo</th>
                      <th>Cantidad</th>
                      <th>Presentacion</th>
                      <th>Descripcion</th>
                      <th>P.V.Unidad</th>
                      <th>Precio Venta Unidad</th>
                      <th>Subtotal</th>
                    </thead>
                    <tfoot>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>
                        <font color="Red">
                          <h1 id="total">Q/. 0.00</h1>
                        </font><input type="hidden" name="total_venta" id="total_venta">
                      </th>
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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione un Artículo</h4>
          </div>
          <div class="modal-body">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
              <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Descripcion</th>
                  <th>Ubicacion</th>
                  <th>Categoría</th>
                  <th>Código</th>
                  <th>Stock</th>
                  <th>Imagen</th>
                  <th>PV.</th>
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
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal -->

  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/salida_pro_sucursal.js"></script>
<?php
}
ob_end_flush();
?>