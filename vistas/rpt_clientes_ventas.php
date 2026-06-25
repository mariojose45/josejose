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
  if ($_SESSION['clientes_seguimiento']==1) 
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
                        <div class="callout callout-info">
                          <h4>Modulo de Clientes - Ventas!</h4>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label><img class="iconos-cambio" src="../public/iconos/calendario.png">Fecha Inicio:</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label><img class="iconos-cambio" src="../public/iconos/calendario.png">Fecha Fin:</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">                 
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label><img class="iconos-cambio" src="../public/iconos/calendario.png">Cliente:</label>
                            <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required>
                              </select>
                            <button class="btn btn-success btn-block" onclick="cargarDatos()" type="button">Mostrar Datos Cliente</button>                          
                        </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Fecha</th>
                            <th>T/Comprobante</th>
                            <th>F/Pago</th>
                            <th>T. Venta</th>
                            <th>T. Desc</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Fecha</th>
                            <th>T/Comprobante</th>
                            <th>F/Pago</th>
                            <th>T. Venta</th>
                            <th>T. Desc</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
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
<script type="text/javascript" src="scripts/rpt_clientes_ventas.js"></script> 

<?php  
} 
ob_end_flush();
?>