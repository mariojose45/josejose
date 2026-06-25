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
  if ($_SESSION['entradaproducto'] == 1) {
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
                    Entrada
                    <small>Productos</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Entrada</li>
                  </ol>

                </section>
                <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> </h1>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th># Entrada</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Sucursal Destino</th>
                    <th>Sucursal Origen</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th># Entrada</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Sucursal Destino</th>
                    <th>Sucursal Origen</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <input type="hidden" name="datos1" id="datos1">
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Cargar Ingreso de Producto :</label>
                    <input type="text" name="idtraladosucursal" id="idtraladosucursal" class="form-control">
                    <button type="button" class="btn  btn-primary btn-block" id="btncargar">Cargar</button>
                  </div>
                  <div class="form-group col-lg-3 col-md-8 col-sm-8 col-xs-12">
                    <label>Sucursal a Ingresar Producto(*):</label>
                    <input type="hidden" name="idtraladosucursal_entrada" id="idtraladosucursal_entrada">
                    <input type="hidden" class="form-control" name="idsucursaldestino" id="idsucursaldestino">
                    <input type="text" class="form-control" name="nombresucursaldestino" id="nombresucursaldestino" readonly>

                  </div>
                  <div class="form-group col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <label>Fecha(*):</label>
                    <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                    <input type="hidden" class="form-control" name="idsucursalorigen" id="idsucursalorigen">
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Descripcion Salida producto:</label>
                    <input type="text" class="form-control" name="descripcion_salida_producto" id="descripcion_salida_producto" readonly>
                  </div>

                  <!-- 
                            TABLA DE DETALLES
                          -->

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary btn-block" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                    <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  </div>
                </form>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color:#A9D0F5">
                      <th>Opciones</th>
                      <th>Artículo</th>
                      <th>Cantidad</th>
                      <th>Presentacion</th>
                      <th>Descripcion Articulo</th>
                      <th>P.Va</th>
                      <th>Fecha Venci</th>
                    </thead>
                    <tfoot>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
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


  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/entrada_pro_sucursal.js"></script>
<?php
}
ob_end_flush();
?>