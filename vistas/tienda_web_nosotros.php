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
                          Nosotros  
                          <small>Tienda en Linea</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Nosotros</li>
                          </ol>

                          </section>  

                        </div> 

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Historia</th>
                            <th>Imagen</th>
                            <th>Mision</th>
                            <th>Vision</th>
                            <th>Diferencia</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                          <th>Opciones</th>
                            <th>Historia</th>
                            <th>Imagen</th>
                            <th>Mision</th>
                            <th>Vision</th>
                            <th>Diferencia</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">

                            <div class="form-group col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Custom Tabs -->
                                    <input type="hidden" name="idnosotros" id="idnosotros">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <label>Historia de la empresa (*):</label>
                                      <textarea class="form-control" name="historia_empresa" id="historia_empresa" rows="8" placeholder="Escriba la historia de la empresa aquí..." required></textarea>
                                    </div>  
                            </div> 
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label>Imagen 1024 x 1536px (recomendado):</label>
                                <input type="file" class="form-control" name="imagen_nosotros" id="imagen_nosotros">
                                <input type="hidden" name="imagenactual_nosotros" id="imagenactual_nosotros">
                                <img src="" width="150px" height="120px" id="imagenmuestra_nosotros">
                            </div> 
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Custom Tabs -->
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <label>Misión (*):</label>
                                      <textarea class="form-control" name="mision_nosotros" id="mision_nosotros" rows="8" placeholder="Escriba la misión de la empresa aquí..." required></textarea>
                                    </div>  
                            </div>      
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Custom Tabs -->
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <label>Visión (*):</label>
                                      <textarea class="form-control" name="vision_nosotros" id="vision_nosotros" rows="8" placeholder="Escriba la visión de la empresa aquí..." required></textarea>
                                    </div>  
                            </div>   
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Custom Tabs -->
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <label>Que nos Diferencia de la Competencia (*):</label>
                                      <textarea class="form-control" name="diferencia_nosotros" id="diferencia_nosotros" rows="8" placeholder="Escriba la diferencia de la empresa con la competencia aquí..." required></textarea>
                                    </div>  
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
<script type="text/javascript" src="scripts/tienda_web_nosotros.js"></script> 

<!-- ✅ Luego Driver.js -->
<script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css"/>


<?php  
} 
ob_end_flush();
?>