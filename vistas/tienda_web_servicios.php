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
                          Servicios Testimonios
                          <small>Tienda en Linea</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Servicios</li>
                          </ol>

                          </section>  

                        </div> 
                        <div class="row" style="margin: 10px 0;">
                            <div class="col-md-12">
                              <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)">
                                <i class="fa fa-plus-circle"></i> Agregar
                              </button>
                            </div>
                          </div>                        

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Titulo </th>
                            <th>imagen </th>
                            <th>Descripcion</th>
                            <th>Tipo</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
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
                            <label>Nombre:</label>
                            <input type="hidden" name="idservicios" id="idservicios">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Nombre" required>
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Tipo:</label>
                            <select class="form-control" name="tipo" id="tipo" required>
                              <option value="Testimonio">Testimonio</option>
                              <option value="Servicio">Servicio</option>
                            </select>
                          </div>
                            <div class="form-group col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Custom Tabs -->
                                    
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <label>Descripcion del Servicio (*):</label>
                                      <textarea class="form-control" name="descripcion_servicio" id="descripcion_servicio" rows="8" placeholder="Escriba la historia de la empresa aquí..." required></textarea>
                                    </div>  
                            </div> 
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label>Imagen 1024 x 1536px (recomendado):</label>
                                <input type="file" class="form-control" name="imagen_servicio" id="imagen_servicio">
                                <input type="hidden" name="imagenactual_servicio" id="imagenactual_servicio">
                                <img src="" width="150px" height="120px" id="imagenmuestra_servicio">
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
<script type="text/javascript" src="scripts/tienda_web_servicios.js"></script> 

<!-- ✅ Luego Driver.js -->
<script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css"/>


<?php  
} 
ob_end_flush();
?>