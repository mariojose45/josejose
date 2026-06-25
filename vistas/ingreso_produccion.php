<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';
 
if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';
 
if ($_SESSION['almacen_crear_combos']==1) 
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
                          Crear Combos  
                          <small>Almacen</small> 
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Crear Combos</li>
                          </ol>
 
                          </section>  
                          <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> </h1>                                             
                        </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th># idproduccion</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Art Combo</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th> 
                            <th></th>
                          </tfoot>
                        </table>
                    </div> 
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label>Articulo Combo(*):</label>
                            <input type="hidden" name="idproduccion" id="idproduccion">
                            <select id="idproducto" name="idproducto" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div> 
                          <select id="idcuenta23" name="idcuenta23" style="display:none"  required>
                          </select>                                                      
                          <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>Fecha Produccion(*):</label>
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                          </div> 

                          <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>P.C.(*):</label>
                            <input type="number" step="any" class="form-control" name="precio_compraProducto" id="precio_compraProducto" readonly="">
                          </div> 

                          <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>GANACIA(*):</label>
                            <input type="number" step="any" onchange="caculardescuento()" class="form-control" name="ganacia_producto" id="ganacia_producto" value="0" required="">
                          </div>  
                          <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>P.V(*):</label>
                            <input type="number" step="any" class="form-control" name="precio_ventaProducto" id="precio_ventaProducto" readonly="">
                          </div>                                                                               

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <a data-toggle="modal" href="#myModal">           
                              <button id="btnAgregarArt" type="button" class="btn btn-primary btn-block"> <span class="fa fa-plus"></span> Agregar Artículos</button>
                            </a>
                          </div>
 
                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>PC</th>
                                    <th>PV</th>
                                    <th>S/PC</th>
                                    <th>Tipo Producto</th>

                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><input type="number" step="any" name="subtotalprecioCompra" class="form-control" id="subtotalprecioCompra" readonly="" value="0"></th>    
                                </tfoot>
                                <tbody>
                                   
                                </tbody>
                            </table>
                          </div>
                         
                                                   
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btn-block" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
                            <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
 
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione un Artículo</h4>
        </div>
        <div class="modal-body">
          <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Stock</th>
                <th>P.C</th>
                <th>P.V.</th>
            </thead>
            <tbody>
               
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>        
      </div>
    </div>
  </div>  
  <!-- Fin modal -->
<?php
}
else
{
  require 'noacceso.php';
}
 
require 'footer.php'; 
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/ingreso_produccion.js"></script>    
<?php 
}
ob_end_flush();
?>