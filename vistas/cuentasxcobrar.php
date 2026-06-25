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
  if ($_SESSION['CuentasXcobrar']==1) 
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
                          <h4>Modulo de Cuentas x Cobrar!</h4>
                          En este modulo podras ingresar las facturas a ser cobradas  </a>
                        </div>

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Cliente</th>
                            <th>Telefono</th>
                            <th>Tipo Comprobante</th>
                            <th>Serie #</th>
                            <th>Total Venta</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Cliente</th>
                            <th>Telefono</th>                            
                            <th>Tipo Comprobante</th>
                            <th>Serie #</th>
                            <th>Total Venta</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre Cliente</label>
                            <input type="text" name="idventa" id="idventa">
                            <input type="text" class="form-control" name="nombrecliente" id="nombrecliente" maxlength="50" placeholder="Nombre Cliente" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Telefono Cliente:</label>
                            <input type="text" class="form-control" name="telefonocliente" id="telefonocliente" maxlength="256" placeholder="Telefono">
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Tipo Documento:</label>
                            <input type="text" class="form-control" name="tipodocumento" id="tipodocumento" maxlength="256" placeholder="Tipo Documento">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Serie:</label>
                            <input type="text" class="form-control" name="seriedocuemnto" id="seriedocuemnto" maxlength="256" placeholder="Serie">
                          </div> 
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label># Documento:</label>
                            <input type="text" class="form-control" name="numerodocumento" id="numerodocumento" maxlength="256" placeholder="# Numero">
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Total Venta:</label>
                            <input type="text" class="form-control" name="total_venta" id="total_venta" maxlength="256" placeholder="# Numero">
                          </div>   
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Tipo Pago:</label>
                            <select class="form-control select-picker" name="tipo_pago" id="tipo_pago" required>
                              <option value="EFECTIVO">EFECTIVO</option>
                              <option value="CHEQUE">CHEQUE</option>
                              
                            </select>
                          </div>                                                                          
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Boleta #:</label>
                            <input type="text" class="form-control" name="numero_boleta" id="numero_boleta" maxlength="256" placeholder=Boleta #>
                          </div>  
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha(*):</label>
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
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
<script type="text/javascript" src="scripts/cuentasxcobrar.js"></script> 

<?php  
} 
ob_end_flush();
?>