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
    if ($_SESSION['consulta_compras_compras'] == 1) {
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
                          Ingresos  
                          <small>Reporte de Compras x Fecha o Sucursal</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ingresos</li>
                          </ol>

                          </section>                                               
                        </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros" style="height: 1000px;">
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">                         
                        </div>
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Empresa(*):</label>
                            <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required>
                            </select> 
                            <button class="btn btn-success" onclick="listarxsucursal()">Mostrar x fecha y sucursal</button>                          
                        </div> 

                                                 
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Proveedor</th>
                            <th>Comprobante</th>
                            <th>DTE</th>
                            <th>SERIE</th>
                            <th>T.C.</th>
                            <th>F/PAGO</th>
                            <th>User Mod</th>
                            <th>Fech/ Mod</th>
                            <th>Sucursal</th>
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
<script type="text/javascript" src="scripts/comprasfecha.js"></script>
<?php 
}
ob_end_flush();
?>