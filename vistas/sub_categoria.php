<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['almacen_crear_sub_categoria'] == 1) {

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
                    Sub Categoria
                    <small>Almacen</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Almacen</li>
                  </ol>

                </section>
                <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar"
                    onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> </h1>
              </div>

              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 200px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Nombre Categoria:</label>
                    <div class="input-group">
                      <input type="hidden" name="idsubcategoria" id="idsubcategoria">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="0">
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Descripción Categoria:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                      <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="50"
                        placeholder="0">
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

    </div><!-- /.content-wrapper -->
    <!--Fin-Contenido-->
    <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/sub_categoria.js"></script>

  <?php
}
ob_end_flush();
?>