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
                          <h4>Modulo de Traslado de Articulos a Sucursal!</h4>
                          Modulo para la creacion de traslado de articulos hacia otras sucursales
                        </div>                
                    <div class="box-header with-border">
                          <h1 class="box-title">Artículos</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <button style="margin-left:20px" onclick="verificacionSeleccionados()" class="btn btn-info"><i class="fa fa-truck"></i> Traslado en Bloque</button>
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Seleccionar</th>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Tipo Producto</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>Stock Minimo</th>
                            <th>Estado Stock</th>                                       
                            <th>Imagen</th>
                            <th>Precio Venta</th>
                            <th>%</th>                            
                            <th>Previo Venta c/Descuento</th>                            
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Seleccionar</th>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Tipo Producto</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>Stock Minimo</th>
                            <th>Estado Stock</th>                                       
                            <th>Imagen</th>
                            <th>Precio Venta</th>
                            <th>%</th>                            
                            <th>Previo Venta c/Descuento</th>                            
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div> 
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-10 col-md-10 col-sm-10 col-xs-12">
                            <label>Nombre(*):</label>
                            <input type="hidden" name="idarticulo" id="idarticulo">
                            <input type="text" class="form-control" disabled name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                          </div>                                                    
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Categoría(*):</label>
                            <select id="idcategoria" disabled name="idcategoria" class="form-control selectpicker" data-live-search="true" required></select>
                          </div>                                                 
                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <label>Descripción:</label>
                            <input type="text" disabled class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                          </div>                         
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Stock(*):</label>
                            <input type="number" disabled class="form-control" name="stock" id="stock" required>
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Stock Minimo(*):</label>
                            <input type="number" class="form-control" disabled name="stockminimo" id="stockminimo" required>
                          </div>    
                          
                          
                          
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Precio Venta(*):</label>
                             <input type="text" class="form-control" disabled name="precio_venta" id="precio_venta" maxlength="100"  required>
                          </div>  
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Descuento %(*):</label>
                             <input type="text" class="form-control" disabled name="descuento_porcentaje" id="descuento_porcentaje" maxlength="100" placeholder="%" required> 
                          </div>  
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Precio Descuento(*):</label>
                             <input type="text" class="form-control" disabled name="precio_descuento" id="precio_descuento" maxlength="100"  required>
                          </div> 

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <img src="" width="150px" height="120px" id="imagenmuestra">
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">       
                            <fieldset>
                              <legend>Sucursal Destino</legend>

                              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Sucursal Destino(*):</label>
                                <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required></select>
                              </div> 

                              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Cantidad a Trasladar(*):</label>
                                <input id="cantidadt" name="cantidadt" class="form-control" type="number" min="1" required></select>
                              </div> 

                            </fieldset>
                          </div>


                                                                                                         
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-truck"></i> Trasladar</button>  
 
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

  <div class="modal" id="modalTraslados"  tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Trasladar Articulos Seleccionados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="articulosdiv">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="return RealizarTraslado()">Trasladar</button>
      </div>
    </div>
  </div>
</div>

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
<script type="text/javascript" src="scripts/traslados.js"></script>  
<?php 
}
ob_end_flush();
?>