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
  if ($_SESSION['avanceproduccion']==1) 
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
                          <h4>Modulo de Avance de produccion!</h4> </a>
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
                            <th>Fecha</th>
                            <th>Empleado</th>
                            <th>Categoria</th>
                            <th>Articulo</th>
                            <th>Procesos</th>
                            <th>Horarios</th>                            
                            <th>Cantidad</th>
                            <th>Descripcion</th>                            
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Fecha</th>
                            <th>Empleado</th>
                            <th>Categoria</th>
                            <th>Articulo</th>
                            <th>Procesos</th>
                            <th>Horarios</th>                            
                            <th>Cantidad</th>
                            <th>Descripcion</th>                            
                            <th>Estado</th>
                          </tfoot>
                        </table> 
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Empleado(*):</label3>
                            <input type="hidden" name="idavance_produccion" id="idavance_produccion">                               
                            <select id="idficha_empleado" name="idficha_empleado" class="form-control selectpicker" data-live-search="true" required></select>
                          </div>                          
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Categoria(*):</label>
                            <select id="idcategoria" name="idcategoria" class="form-control" data-live-search="true" required></select>
                          </div>  
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Articulo(*):</label>
                            <select id="idarticulo" name="idarticulo" class="form-control" data-live-search="true" required></select>
                          </div>  
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Procesos:</label>
                            <select id="idarticulos_proceso" name="idarticulos_proceso" class="form-control" data-live-search="true" required>
                            </select>
                          </div>   
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Horarios(*):</label>
                            <select id="idhora_produccion" name="idhora_produccion" class="form-control selectpicker" data-live-search="true" required></select>
                          </div>                                                   
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Cantidad de piezas:</label>
                            <input type="number" class="form-control" name="cantidad" id="cantidad" maxlength="250" required="">
                          </div>                          
                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <label>Descripción:</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
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
<script type="text/javascript" src="scripts/avance_produccion.js"></script> 

<?php  
} 
ob_end_flush();
?>