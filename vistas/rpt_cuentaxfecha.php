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
 
if ($_SESSION['nomina']==1)
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
                    <div class="box-header with-border">
                          <h1 class="box-title">Consulta de cuentas por fecha </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                        </div> 
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Cta Bancaria(*):</label>
                            <select id="idcuenta" name="idcuenta" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>                          
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Fecha</th>
                            <th>Cta Nombre</th>
                            <th>Cta Numero</th>
                            <th>saldo Inicial</th>
                            <th>Valor Deposito</th>
                            <th>Valor Cheque</th>
                            <th>Saldo Cuenta</th>
                            <th>Fecha Deposito</th>
                            <th>Fecha Cheque</th>
                            <th>Estado</th>

                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Fecha</th>
                            <th>Cta Nombre</th>
                            <th>Cta Numero</th>
                            <th>saldo Inicial</th>
                            <th>Valor Deposito</th>
                            <th>Valor Cheque</th>
                            <th>Saldo Cuenta</th>
                            <th>Fecha Deposito</th>
                            <th>Fecha Cheque</th>
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
<script type="text/javascript" src="scripts/rpt_cuentaxfecha.js"></script>  
<?php 
}
ob_end_flush();
?>