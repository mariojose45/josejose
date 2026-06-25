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
 
if ($_SESSION['cotizaciones']==1)   
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
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>#Cotizacion</th>
                            <th>Total Venta</th>
                            <th>Total Venta Descuento</th>
                            <th>Empresa</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>#Cotizacion</th>
                            <th>Total Venta</th>
                            <th>Total Venta Descuento</th>
                            <th>Empresa</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div> 
                    <div class="panel-body" style="height: 800px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST"> 
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Cliente(*):</label>
                                <input type="hidden" name="idcotizacion" id="idcotizacion">
                                <input type="text" class="form-control" name="nit" id="nit" maxlength="20" value="CF">
                                <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" maxlength="256"  value="CONSUMIDOR FINAL">
                                <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" maxlength="256"  value="CIUDAD">
                                <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" maxlength="256"  value="+502-000-0000">
                                <input type="text" class="form-control" name="correo_cliente" id="correo_cliente" maxlength="256"  value="soporte@gmail.com">
                                <input type="hidden" class="form-control" name="idcliente" id="idcliente" value="1">
                              <button  class="btn btn-danger" onclick="validarnit()" type="button"><i class="fa fa-search-minus">VALIDAR NIT</i></button>
                            </div>                          
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Fecha(*):</label>
                              <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                            </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Asignacion a empresa(*):</label>
                            <select id="idsucursal" name="idsucursal" class="form-control selectpicker" data-live-search="true" required>
                            </select>  
                          </div>                             
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>  

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Busqueda Articulo:</label>
                              <input type="text" id="txtbusquedaarticulo" class="form-control" autofocus="autofocus" placeholder="Ingresa el nombre correspondiente del articulo a vender">
                              <input type="hidden" id="txtbusquedaartcodebar" class="form-control" autofocus="autofocus" placeholder="Click para Gestionar las lecturas con pistola">
                            </div>  
                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                              <a data-toggle="modal" href="#myModal">           
                                <button id="btnAgregarArt" type="button" class="btn btn-primary"> <span class="fa fa-plus"></span> Agregar Artículos</button>
                              </a>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>                                                     
                          </div>

                          <div class="col-lg-8 col-sm-8 col-md-8 col-xs-8  table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Stock</th>
                                    <th>Cant.</th>
                                    <th>Descripcion.</th>
                                    <th>P.V.</th>
                                    <th>Descu Q.</th>
                                    <th>Subtotal</th>
                                    <th>Subtotal Des</th>                                                                       
                                </thead>
                                <tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><font color="Red" ><h3 id="total">Q/. 0.00</h3></font><input type="hidden" name="total_venta" id="total_venta" ></th>
                                    <th><font color="Blue" ><h3 id="totaldes">Q/. 0.00</h3></font><input type="hidden" name="total_ventades" id="total_ventades" ></th>                                    
                                  
                                </tfoot>
                                <tbody>
                                   
                                </tbody>
                            </table>
                          </div>

                         
 
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
                            <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="display: block; padding-right: 17px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione un Artículo</h4>
        </div>
        <div class="modal-body">
        <div class="col-lg-812 col-sm-12 col-md-812 col-xs-12 table-responsive">
          <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Opciones</th>
                <th>Nombre</th>
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
  <!-- Fin modal -->

   <!-- Modal -->
  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 80% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Nuevo Cliente</h4>
        </div> 
        <div class="modal-body">
        <form name="formulario2" id="formulario2" method="POST">
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre:</label>
                            <input type="hidden" name="idpersona" id="idpersona">
                            <input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del Cliente" required>
                          </div> 
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tipo Documento:</label>
                            <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                              <option value="DPI">DPI</option>
                              <option value="NIT">NIT</option>
                              <option value="PASAPORTE">PASAPORTE</option>
                            </select>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Número Documento:</label>
                            <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" value="C/F">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Dirección:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" maxlength="70" value="CIUDAD">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Teléfono:</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" value="0000-0000">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="50" value="soporte@gmail.com">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tipo Cliente:</label>
                            <select class="form-control select-picker" name="tipo_cliente" id="tipo_cliente" required>
                              <option value="PUBLICO">Publico</option>
                              <option value="DISTRIBUIDOR">Distribuidor</option>
                              <option value="MAYORISTA">Mayorista</option>
                              <option value="MENUDEO">Menudeo</option>
                              <option value="PUBLICO">Publico</option>
                            </select>
                          </div>  
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="button" id="btnGuardar2"><i class="fa fa-save"></i> Guardar</button>
 
                            <button class="btn btn-danger" onclick="cancelarform2()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
        </form>       
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="scripts/cotizaciones_movil.js"></script>   
<?php 
}
ob_end_flush(); 
?>