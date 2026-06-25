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
if ($_SESSION['inventarioxsucursal']==1)
{ 
    require_once "../modelos/Articulo.php";
  $articulo = new Articulo();
  $rsptac = $articulo->totalcostoinventario();
  $regc=$rsptac->fetch_object();
  $totalc=$regc->total_compra;   

  $rsptac_v = $articulo->totalventainventario(); 
  $regc_v=$rsptac_v->fetch_object();
  $totalc_v=$regc_v->total_venta;  
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
                          Inventario x Sucursal   
                          <small>Productos</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Inventario</li>
                          </ol>

                          </section>                                               
                        </div> 


                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
                          <thead>
                            <th>Dias Venci</th>  
                            <th>Nombre</th>
                            <th>Descrip</th>
                            <th>Descrip 2</th>
                            <th>Categoría</th>
                            <th>Tipo Producto</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>Stock Minimo</th>
                            <th>Estado Stock</th>                                       
                            <th>Imagen</th>
                            <th>P.Venta</th> 
                            <th>P.Unidad</th> 
                            <th>P.Blister</th> 
                            <th>P.Caja</th> 
                            <th>P.Fardo</th> 
                            <th>P.Sacos</th> 
                            <th>P.Paquete</th> 
                            <th>Sucursal</th>                            
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
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
<script type="text/javascript" src="scripts/inventarioxsucursal.js"></script> 
<?php 
}
ob_end_flush();
?>