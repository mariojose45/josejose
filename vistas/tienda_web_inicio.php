<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

 
if (!isset($_SESSION["nombre"]))
  {
    header("Location: login.html");
  }
else
  {
  require 'header.php';
  if ($_SESSION['tienda_web_inicio']==1) 
  {
 
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
                          Inicio  
                          <small>Tienda en Linea</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Tienda en Linea</li>
                          </ol>

                          </section>  

                        </div> 

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Titulo 01</th>
                            <th>Sub-Titulo 01</th>
                            <th>Descripción 01</th>
                            <th>Imagen 01</th>
                            <th>Titulo 02</th>
                            <th>Sub-Titulo 02</th>
                            <th>Descripción 02</th>
                            <th>Imagen 02</th>
                            <th>Titulo 03</th>
                            <th>Sub-Titulo 03</th>
                            <th>Descripción 03</th>
                            <th>Imagen 03</th>
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
                            <th></th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <!-- Custom Tabs -->
                            <input type="hidden" name="idinicio" id="idinicio">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Banner Principal</a></li>
                                <li><a href="#tab_2" data-toggle="tab">Banner Secundario</a></li>
                                <li><a href="#tab_3" data-toggle="tab">Banner Terciario</a></li>
                                </ul>
                                <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Titulo:</label>
                                        <input type="text" class="form-control" name="titulo_1" id="titulo_1" maxlength="50" placeholder="Titulo Banner Principal" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Sub-Titulo:</label>
                                        <input type="text" class="form-control" name="sub_titulo_1" id="sub_titulo_1" maxlength="50" placeholder="Sub-Titulo Banner Principal" required>
                                    </div>       
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Descripción:</label>
                                        <input type="text" class="form-control" name="descripcion_titulo_1" id="descripcion_titulo_1" maxlength="100" placeholder="Descripción Banner Principal" required>
                                    </div>                                                                   
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Imagen 915 x 570px (recomendado):</label>
                                        <input type="file" class="form-control" name="imagen_1" id="imagen_1">
                                        <input type="hidden" name="imagenactual_1" id="imagenactual_1">
                                        <img src="" width="150px" height="120px" id="imagenmuestra_1">
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_2">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Titulo 02:</label>
                                        <input type="text" class="form-control" name="titulo_2" id="titulo_2" maxlength="50" placeholder="Titulo Banner Principal" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Sub-Titulo 02:</label>
                                        <input type="text" class="form-control" name="sub_titulo_2" id="sub_titulo_2" maxlength="50" placeholder="Sub-Titulo Banner Principal" required>
                                    </div>       
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Descripción 02:</label>
                                        <input type="text" class="form-control" name="descripcion_titulo_2" id="descripcion_titulo_2" maxlength="100" placeholder="Descripción Banner Principal" required>
                                    </div>                                                                   
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Imagen 02 915 x 570px (recomendado):</label>
                                        <input type="file" class="form-control" name="imagen_2" id="imagen_2">
                                        <input type="hidden" name="imagenactual_2" id="imagenactual_2">
                                        <img src="" width="150px" height="120px" id="imagenmuestra_2">
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_3">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Titulo 03:</label>
                                        <input type="text" class="form-control" name="titulo_3" id="titulo_3" maxlength="50" placeholder="Titulo Banner Principal" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Sub-Titulo 03:</label>
                                        <input type="text" class="form-control" name="sub_titulo_3" id="sub_titulo_3" maxlength="50" placeholder="Sub-Titulo Banner Principal" required>
                                    </div>       
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Descripción 03:</label>
                                        <input type="text" class="form-control" name="descripcion_titulo_3" id="descripcion_titulo_3" maxlength="100" placeholder="Descripción Banner Principal" required>
                                    </div>                                                                   
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Imagen 03 915 x 570px (recomendado):</label>
                                        <input type="file" class="form-control" name="imagen_3" id="imagen_3">
                                        <input type="hidden" name="imagenactual_3" id="imagenactual_3">
                                        <img src="" width="150px" height="120px" id="imagenmuestra_3">
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- nav-tabs-custom -->
                            </div>                        
                                             
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btn-block" type="button" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
                            <button class="btn btn-danger btn-block" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/tienda_web_inicio.js"></script> 

<!-- ✅ Luego Driver.js -->
<script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css"/>


<?php  
} 
ob_end_flush();
?>