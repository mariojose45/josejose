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
  if ($_SESSION['almacen']==1) 
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
                          <h4>Modulo de Cheques!</h4>
                          En este Modulo Podras Crear,Listar, Buscar y Desactivar </a>
                        </div>
                    <div class="box-header with-border">
                          <h1 class="box-title">Cheque <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header --> 
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Usuario</th>
                            <th>Cliente</th>
                            <th>Cta Banco</th>
                            <th>Fecha Factura</th>
                            <th>Fecha Cheque</th>
                            <th>Valor Cheque</th>
                            <th>Descripcion</th>
                            <th>Estado</th> 
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Usuario</th>
                            <th>Cliente</th>
                            <th>Cta Banco</th>
                            <th>Fecha Factura</th>
                            <th>Fecha Cheque</th>
                            <th>Valor Cheque</th>
                            <th>Descripcion</th>
                            <th>Estado</th> 
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Cliente(*):</label>
                            <input type="hidden" name="idcheque" id="idcheque">
                            <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Cta Bancaria(*):</label>
                            <select id="idcuenta" name="idcuenta" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div> 
                          <select id="idcuenta23" name="idcuenta23" style="display:none"  required>
                          </select>                            
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Saldo Cuenta #:</label>
                            <input type="text" class="form-control" name="saldo_cuenta" id="saldo_cuenta" readonly="">
                          </div>                           
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label> Fac. Serie #:</label>
                            <input type="text" class="form-control" name="fac_serie" id="fac_serie" maxlength="256" placeholder="Fac. Serie #">
                          </div> 
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label> Fac. # Documento:</label>
                            <input type="text" class="form-control" name="fac_documento" id="fac_documento" maxlength="256" placeholder="Fac. # Documento">
                          </div>  
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha Factura(*):</label>
                            <input type="date" class="form-control" name="fecha_hora_factura" id="fecha_hora_factura" required="">
                          </div>                                                                                                         
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha Operacion(*):</label>
                            <input type="date" class="form-control" name="fecha_hora_operacion" id="fecha_hora_operacion" required="">
                          </div> 
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Valor Cheque:</label>
                            <input type="number" step="any" class="form-control" name="valor_cheque" id="valor_cheque" maxlength="40" placeholder="00.00">
                          </div>                           
                                                
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Descripcion:</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripcion">
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
<script type="text/javascript" src="scripts/cheque.js"></script>   

<?php  
} 
ob_end_flush();
?>