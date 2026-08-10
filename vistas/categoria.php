<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['almacen_crear_categoria'] == 1) {

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
                    Categoria
                    <small>Almacen</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Almacen</li>
                  </ol>

                </section>
                <div class="row" style="margin: 10px 0;">
                  <div class="col-md-12">
                    <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)">
                      <i class="fa fa-plus-circle"></i> Agregar
                    </button>
                  </div>
                </div>
              </div>

              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Tipo Descuento</th>
                    <th>Mostrar en Venta</th>
                    <th>Valor Descuento</th>
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
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label>Nombre:</label>
                      <input type="hidden" name="idcategoria" id="idcategoria">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                        <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50"
                          placeholder="Ingrese Nombre" required>
                      </div>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label>Descripción:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                        <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256"
                          placeholder="Ingrese Descripción">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                      <label>Tipo Descuento:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                        <select name="tipo_descuento" id="tipo_descuento" class="form-control">
                          <option value="Porcentaje">Porcentaje</option>
                          <option value="Quetzales">Quetzales</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                      <label>Mostrar en Venta:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                        <select name="mostrar_en_venta" id="mostrar_en_venta" class="form-control">
                          <option value="SI">Mostrar en Venta</option>
                          <option value="NO">No Mostrar en Venta</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                      <label>Valor Descuento:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                        <input type="number" class="form-control" name="valor_descuento" id="valor_descuento" step="any"
                          placeholder="0">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"
                      style="text-align: center; margin-top: 20px;">
                      <button class="btn btn-primary" type="button" id="btnGuardar"
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
      <div class="modal fade" id="modalSucursales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header bg-info"> <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cubes"></i> Actualizar Visibilidad de Categoría por Sucursal</h4>
            </div>
            <div class="modal-body">
              <form name="formulario_sucursales" id="formulario_sucursales" method="POST">
                <input type="hidden" name="idcategoria_modal" id="idcategoria_modal">

                <div class="alert alert-info">
                  <i class="fa fa-info-circle"></i> Marque las sucursales donde desea que esta categoría sea **visible**.
                </div>

                <div id="sucursales_container">
                </div>

                <hr>
                <div class="form-group text-center">
                  <button class="btn btn-primary" type="submit" id="btnGuardarSucursales"><i class="fa fa-save"></i> Guardar Cambios</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.content-wrapper -->
    <!--Fin-Contenido-->



  <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/categoria.js"></script>



<?php
}
ob_end_flush();
?>