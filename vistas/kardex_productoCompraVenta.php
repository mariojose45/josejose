<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['reportes'] == 1) {
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
                    Kardex
                    <small>Movimientos Articulos</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Kardex</li>
                  </ol>

                </section>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros" style="height: 800px;">
                <div class="row">
                  <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label>Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                  </div>
                  <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label>Fecha Fin</label>
                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <label>Sucursal</label>
                    <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required></select>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <label>Código Pro</label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="codigo_pro" id="codigo_pro" placeholder="Escriba o busque...">
                      <span class="input-group-btn">
                        <a data-toggle="modal" href="#myModal">
                          <button class="btn btn-primary" type="button" title="Buscar artículo" style="height: 34px;"><i class="fa fa-search"></i></button>
                        </a>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-2 col-md-12 col-sm-12 col-xs-12" style="margin-top: 24px;">
                    <button class="btn btn-success btn-block btn-sm" onclick="listar()" title="Mostrar x producto x sucursal">
                      <i class="fa fa-filter"></i> x Producto
                    </button>
                    <button class="btn btn-info btn-block btn-sm" onclick="listarTodosArticulos()" title="Mostrar Todo x sucursal" style="margin-top: 5px;">
                      <i class="fa fa-list"></i> Mostrar Todo
                    </button>
                  </div>
                </div>

                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>idKardex</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Sucursal</th>
                    <th>Concepto</th>
                    <th># Documento</th>
                    <th>Cantidad Anterior</th>
                    <th>Cantidad Modificacion</th>
                    <th>Tipo Modificacion</th>
                    <th>Stock Nuevo</th>
                    <th>Responsable</th>
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

              <!--Fin centro -->
            </div><!-- /.box -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 80% !important;">
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
                  <th>Categoría</th>
                  <th>Código</th>
                  <th>Stock</th>
                  <th>Precio Venta</th>
                  <th>Imagen</th>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Categoría</th>
                  <th>Código</th>
                  <th>Stock</th>
                  <th>Precio Venta</th>
                  <th>Imagen</th>
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
    <!--Fin-Contenido-->
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/kardex_productoCompraVenta.js"></script>
<?php
}
ob_end_flush();
?>