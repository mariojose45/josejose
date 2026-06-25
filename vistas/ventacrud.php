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
  if ($_SESSION['ventamantenimiento']==1) 
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
                          <h4>Modulo de Ventas Administrador!</h4>
                          podra modificar el cuerpo de la factura para cuadre</a>
                        </div>
                    <div class="box-header with-border">

                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>IdVenta</th>
                            <th>Fecha Hora</th>
                            <th>Serie No.</th>
                            <th>Total</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>IdVenta</th>
                            <th>Fecha Hora</th>
                            <th>Serie No.</th>
                            <th>Total</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Cliente "8":</label>
                            <input type="hidden" name="idventa" id="idventa">
                            <input type="number" class="form-control" name="idcliente" id="idcliente" maxlength="10" placeholder="00" required>
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Usuario "1 o 4":</label>
                            <input type="number" class="form-control" name="idusuario" id="idusuario" maxlength="10" required="">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Tipo Comprobante :</label>
                            <input type="text" class="form-control" name="tipo_comprobante" id="tipo_comprobante" maxlength="50" required="">
                          </div> 
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Serie:</label>
                            <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="50" required="">
                          </div> 
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>No. Comprobante:</label>
                            <input type="number" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="50" required="">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Fecha Hora:</label>
                            <input type="text" class="form-control" name="fecha_hora" id="fecha_hora" maxlength="50" required="">
                          </div> 
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Total Venta:</label>
                            <input type="text" class="form-control" name="total_venta" id="total_venta" maxlength="50" required="">
                          </div>    
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Total Venta Descuento:</label>
                            <input type="text" class="form-control" name="total_ventades" id="total_ventades" maxlength="50" required="">
                          </div>  
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Estado (Aceptado):</label>
                            <input type="text" class="form-control" name="estado" id="estado" maxlength="50" required="">
                          </div>  
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Condicion (1 o 0)</label>
                            <input type="number" class="form-control" name="condicion" id="condicion" maxlength="50" required="">
                          </div>                                                                                                                                                                                                                
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
<script type="text/javascript" src="scripts/vent_add_crud.js"></script> 

<?php  
} 
ob_end_flush();
?>