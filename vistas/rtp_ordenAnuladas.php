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
 
if ($_SESSION['restaurantecobros']==1)
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
                          Ordenes   
                          <small>Reporte de Mesas Anuladas x  Sucursal</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ordenes</li>
                          </ol>

                          </section>                                                
                        </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros" style="height: 800px;">
                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">                      
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Empresa(*):</label>
                            <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                            <button class="btn btn-success" onclick="listar()">Mostrar x fecha y sucursal</button>   
                        </div>                         

                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Fecha Hora Creacion</th>
                            <th>Fecha Hora Anulacion</th>
                            <th>Usuario</th>
                            <th># Mesa</th>
                            <th>Ciente</th>
                            <th>Mesero</th>
                            <th>Total Orden</th>
                            <th>Motivo Anulacion</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Fecha Hora Creacion</th>
                            <th>Fecha Hora Anulacion</th>
                            <th>Usuario</th>
                            <th># Mesa</th>
                            <th>Ciente</th>
                            <th>Mesero</th>
                            <th>Total Orden</th>
                            <th>Motivo Anulacion</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
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
<script type="text/javascript" src="scripts/rtp_ordenAnuladas.js"></script>
<?php 
}
ob_end_flush();
?>