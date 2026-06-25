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
                          <h4>Modulo de Cta Bancaria!</h4>
                          En este Modulo Podras Crear, Editar, Listar, Buscar y Desactivar </a>
                        </div>
                    <div class="box-header with-border">
                          <h1 class="box-title"><button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Cod Cta</th>
                            <th>Cta Nombre</th>
                            <th>Cta No.</th>
                            <th>Descripcion</th>
                            <th>Saldo Inicial</th>
                            <th>Saldo Cuenta</th>
                            <th>Tipo Banco</th>
                            <th>Condicion</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Cod Cta</th>
                            <th>Cta Nombre</th>
                            <th>Cta No.</th>
                            <th>Descripcion</th>
                            <th>Saldo Inicial</th>
                            <th>Saldo Cuenta</th>
                            <th>Tipo Banco</th>
                            <th>Condicion</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 300px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Cta Cod:</label>
                            <input type="hidden" name="idcuenta" id="idcuenta">
                            <input type="text" class="form-control" name="cta_cod" id="cta_cod" maxlength="20" placeholder="Cod Cta" required>
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Cta Nombre:</label>
                            <input type="text" class="form-control" name="cta_nombre" id="cta_nombre" maxlength="256" placeholder="0000000000">
                          </div>                          
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>No. Cta:</label>
                            <input type="text" class="form-control" name="num_cta" id="num_cta" maxlength="30" placeholder="0000000000">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Descripción:</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Saldo Inicial:</label>
                            <input type="text" class="form-control" name="saldo_inicial" id="saldo_inicial" maxlength="256" placeholder="Q00.00">
                          </div> 
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Tipo Banco(*):</label>
                            <select name="tipo_banco" id="tipo_banco" class="form-control selectpicker" required="">
                               <option value="Seleccion Banco">Seleccion Banco</option>
                               <option value="Banrural">Banrural</option>
                               <option value="Industrial">Industrial</option>
                               <option value="GyT">GyT</option>
                               <option value="Bac">Bac</option>
                               <option value="Bam">Bam</option>
                               <option value="Reformador">Reformador</option>
                            </select>
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
<script type="text/javascript" src="scripts/cta_bancaria.js"></script> 

<?php  
} 
ob_end_flush();
?>