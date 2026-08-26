<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['inventarioxgeneral'] == 1) {

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
                    Inventario x General
                    <small>Productos</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Inventario</li>
                  </ol>

                </section>
              </div>


              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body" id="listadoregistros">
                <div class="row form-group">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Sucursal:</label>
                    <select name="idsucursal" id="idsucursal" class="form-control selectpicker" data-live-search="true" required>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Filtro de Stock:</label>
                    <select name="filtro_stock" id="filtro_stock" class="form-control selectpicker" data-live-search="true">
                      <option value="todos">Todos (Cualquier stock)</option>
                      <option value="mayor_cero">Mayor a cero (Stock > 0)</option>
                      <option value="no_negativos">Quitar Negativos (Stock >= 0)</option>
                      <option value="solo_ceros">Solo ceros (Stock = 0)</option>
                      <option value="solo_negativos">Solo Negativos (Stock < 0)</option>
                    </select>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-primary" onclick="listar()"><i class="fa fa-search"></i> Filtrar</button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                      <th>Dias Venci</th>
                      <th>Nombre</th>
                      <th>Descrip</th>
                      <th>Descrip2</th>
                      <th>Categoría</th>
                      <th>Tipo Producto</th>
                      <th>Código</th>
                      <th>Stock</th>
                      <th>Stock Minimo</th>
                      <th>Estado Stock</th>
                      <th>Precio venta</th>
                      <th>Sucursal</th>
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
              </div>
              <div class="panel-body" id="formularioregistros">

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
  <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
  <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
  <script type="text/javascript" src="scripts/inventarioxgeneral.js"></script>
<?php
}
ob_end_flush();
?>