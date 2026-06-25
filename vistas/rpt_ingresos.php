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
 
if ($_SESSION['compras_rpt_ingresos']==1)
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
                          Ingresos  
                          <small>Reporte de Compras</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ingresos</li>
                          </ol>

                          </section>  
                          
                        </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover"> 
                          <thead>
                            <th>Id</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Tipo/Serie y No.</th>
                            <th>Fec. Creacion</th>
                            <th>Total Compra</th>       
                            <th>Forma pago</th>
                            <th>Dias Credito</th>
                            <th>Fecha Pago Credito</th>
                            <th>Tipo Pago</th>
                            <th>Valor Pagar</th>
                            <th>Cheque No.</th>
                            <th>Fecha hora pago aplicado</th>
                            <th>Tipo Banco</th>
                            <th>Numero Boleta</th>
                            <th>User Modfi</th> 
                            <th>Fecha Modfi</th>
                            <th>Desc Modfi</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Id</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Tipo/Serie y No.</th>
                            <th>Fec. Creacion</th>
                            <th>Total Compra</th>                            
                            <th>Forma pago</th>
                            <th>Dias Credito</th>
                            <th>Fecha Pago Credito</th>
                            <th>Tipo Pago</th>                            
                            <th>Valor Pagar</th>
                            <th>Cheque No.</th>                            
                            <th>Fecha hora pago aplicado</th>
                            <th>Tipo Banco</th>
                            <th>Numero Boleta</th>
                            <th>User Modfi</th>                            
                            <th>Fecha Modfi</th>                            
                            <th>Desc Modfi</th>                            
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
<script type="text/javascript" src="scripts/rpt_ingreso.js"></script>
<?php 
}
ob_end_flush();
?>