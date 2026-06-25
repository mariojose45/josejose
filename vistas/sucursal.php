<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['acceso_sucursales'] == 1) {

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
                    Sucursal
                    <small>Acceso</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Sucursal</li>
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
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Nit</th>
                    <th>Email</th>
                    <th>Logo</th>
                    <th>Calculo Descuento</th>
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
              <div class="panel-body" style="height: 500px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label>Nombre:</label>
                    <input type="hidden" name="idsucursal" id="idsucursal">
                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Nombre" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label>Direccion:</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" maxlength="256" placeholder="Direccion">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Telefono:</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" maxlength="100" placeholder="+502-1234-1234">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Nit:</label>
                    <input type="text" class="form-control" name="nit" id="nit" maxlength="20" placeholder="12345-1">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Email:</label>
                    <input type="text" class="form-control" name="email" id="email" maxlength="150" placeholder="correo@dominio.com">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Password Ordenes Compra:</label>
                    <input type="text" class="form-control" name="clave_ordenes" id="clave_ordenes" maxlength="50" value="admin">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Password Ingresos:</label>
                    <input type="text" class="form-control" name="clave_ingresos" id="clave_ingresos" maxlength="50" value="admin">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Password Ventas:</label>
                    <input type="text" class="form-control" name="clave_ventas" id="clave_ventas" maxlength="50" value="admin">
                  </div>
                  <div class="form-group col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <label>Imagen:</label>
                    <input type="file" class="form-control" name="imagen" id="imagen">
                    <input type="hidden" name="imagenactual" id="imagenactual">
                    <img src="" width="150px" height="120px" id="imagenmuestra">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Calculo Ganacia Venta(*):</label>
                    <select name="calculo_descuento" id="calculo_descuento" class="form-control selectpicker" required="">
                      <option value="QUETZALES">QUETZALES</option>
                      <option value="PORCENTAJE">PORCENTAJE</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary btn-block" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                    <button class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
  <script type="text/javascript" src="scripts/sucursal.js"></script>

<?php
}
ob_end_flush();
?>