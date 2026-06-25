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
  
if ($_SESSION['reportes']==1)
{ 


  date_default_timezone_set('America/Guatemala'); 
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
                          Cotizaciones  
                          <small>Reporte de Cotizaciones, Clientes, Sucursal</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Cotizaciones</li>
                          </ol>

                          </section>                                                
                        </div>                     

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros" style="height: 800px;">
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">                      
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>Empresa(*):</label>
                            <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                            <button class="btn btn-success" onclick="listarxfechasucursal()">Mostrar x fecha y sucursal</button>   
                        </div>   
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover table-responsive">
                          <thead>
                            <th>Opciones</th>
                            <th># Correlativo</th>
                            <th>Cliente</th>
                            <th>Usuario</th> 
                            <th>T. V</th>
                            <th>T. V. Des</th>
                            <th>Fecha</th>
                            <th>Cobrado SI/NO</th>
                            <th># Interno</th>
                            <th>Forma Pago</th>
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
                          </tfoot>
                        </table> 
                    </div>
                    <div class="panel-body" style="height: 800px;" id="formularioregistros">


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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="scripts/cotizaciones2.js"></script>   
<?php 
}
ob_end_flush(); 
?>