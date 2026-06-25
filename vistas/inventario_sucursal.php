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
                    <div class="col-lg-12 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-aqua">
                        <div class="inner">
                          <h3>Modulo de Inventario Sucursal!</h3>
                        </div>
                        <div class="icon">
                          <i class="fa glyphicon-home"></i>
                        </div>
                      </div>
                    </div>                       
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Tipo Producto</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>Stock Minimo</th>
                            <th>Estado Stock</th>                                       
                            <th>Imagen</th>
                            <th>Precio venta</th>
                            <th>%</th>                            
                            <th>Previo Venta c/Descuento</th>
                            <th>Sucursal</th>                         
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Tipo Producto</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>Stock Minimo</th>
                            <th>Estado Stock</th>                                       
                            <th>Imagen</th>
                            <th>Precio venta</th>
                            <th>%</th>                            
                            <th>Previo Venta c/Descuento</th>
                            <th>Sucursal</th>                         
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div> 
                    <div class="panel-body" id="formularioregistros">
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
<script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
<script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
<script type="text/javascript" src="scripts/inventario_sucursal.js"></script> 
<?php 
}
ob_end_flush();
?>