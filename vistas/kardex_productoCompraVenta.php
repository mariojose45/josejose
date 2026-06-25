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
 
if ($_SESSION['reportes']==1)  
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
                          Kardex  
                          <small>Movimientos Articulos</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Kardex</li>
                          </ol>

                          </section>                                                
                        </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros" style="height: 800px;">
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                        </div> 
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <label>Sucursal</label>
                          <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required></select>
                        </div> 
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <label>Codigo Pro</label>
                          <input type="text" class="form-control" name="codigo_pro" id="codigo_pro" >
                          <button class="btn btn-success btn-block" onclick="listar()">Buscar Codigo</button> 
                        </div> 
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <a data-toggle="modal" href="#myModal">           
                                <button id="btnAgregarArt" name="btnAgregarArt" type="button" class="btn btn-primary btn-block" > <span class="fa fa-plus"></span> Agregar Artículos</button>
                              </a>
                        </div>                        

                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Fecha</th>
                            <th>#Ingreso</th>
                            <th>#Debito</th>
                            <th>#Salida</th>
                            <th>#Entrada</th>
                            <th>#Venta</th>
                            <th>#Credito</th>
                            <th>Compras Cant</th>
                            <th>Debito Cant</th>
                            <th>Salidas Cant</th>
                            <th>Entrada Cant</th>
                            <th>Venta Cant</th>
                            <th>Credito Cant</th>
                            <th>Stock</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>TOTALES</th> 
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

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 80% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione un Artículo</h4>
        </div>
        <div class="modal-body">
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
          <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock</th>
                <th>Estado Stock</th>
                <th>Precio Venta</th>
                <th>Imagen</th>
            </thead>
            <tbody>
               
            </tbody>
            <tfoot>
              <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock</th>
                <th>Estado Stock</th>                
                <th>Precio Venta</th>
                <th>Imagen</th>
            </tfoot>
          </table> 
         </div>        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> 
        </div>        
      </div>
    </div>
  </div>     
  <!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
} 
 
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/kardex_productoCompraVenta.js"></script>
<?php 
}
ob_end_flush();
?>