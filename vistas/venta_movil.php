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
  
if ($_SESSION['ventas']==1)
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
                          <h1 class="box-title"><button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar (F8)</button></h1>
                        <div class="box-tools pull-right">
                        </div> 
 
                    </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>" >
                          <button class="btn btn-success" onclick="listar()">Generar Ventas</button>
                        </div>
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Comprobante</th>
                            <th># Interno</th>
                            <th># Correlativo</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Total Venta</th>
                            <th>Efectivo</th>
                            <th>Cambio</th>
                            <th>Fecha</th>
                            <th># Autorizacion</th>
                            <th>Serie Autorizacion</th>
                            <th># Autorizacion</th>
                            <th>Fecha Autorizacion</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Comprobante</th>
                            <th># Interno</th>
                            <th># Correlativo</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Total Venta</th>
                            <th>Efectivo</th>
                            <th>Cambio</th>
                            <th>Fecha</th>
                            <th># Autorizacion</th>
                            <th>Serie Autorizacion</th>
                            <th># Autorizacion</th>
                            <th>Fecha Autorizacion</th>
                            <th>Estado</th>
                          </tfoot>
                        </table> 
                    </div>
                    <div class="panel-body" style="height: 800px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 table-responsive">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Cargar Cotizacion o Cta x Cobrar:</label>
                              <input type="text" name="idcotizacion" id="idcotizacion" class="form-control">

                              <button type="button" class="btn  btn-primary" id="btncargar">Cotizacion</button>
                              <button type="button" class="btn  btn-success" id="btncargarVenta">CtXCobrar</button> 
                            </div>                        
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Cliente(*):</label>
                                <input type="hidden" name="idventa" id="idventa">
                                <input type="hidden" name="idcotizacion_2" id="idcotizacion_2" class="form-control">
                                <input type="hidden" name="idventa_2" id="idventa_2" class="form-control">
                                <input type="text" class="form-control" name="nit" id="nit" maxlength="20" value="CF">
                                <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" maxlength="256"  value="CONSUMIDOR FINAL">
                                <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" maxlength="256"  value="CIUDAD">
                                <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" maxlength="256"  value="+502-000-0000">
                                <input type="text" class="form-control" name="correo_cliente" id="correo_cliente" maxlength="256"  value="soporte@gmail.com">
                                <input type="hidden" class="form-control" name="idcliente" id="idcliente" value="1">
                              <button  class="btn btn-danger" onclick="validarnit()" type="button"><i class="fa fa-search-minus">VALIDAR NIT</i></button>
                            </div>                            
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Fecha(*):</label>
                              <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Comprobante(*):</label>
                              <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                                <option value="Envio">Envio</option> 
                              </select>
                            </div> 
                              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Forma pago(*):</label>
                                <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required="">
                                   <option value="Efectivo">Efectivo</option>
                                   <option value="Credito">Credito</option>
                                </select>
                              </div>
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Observacion Credito(*):</label>
                                <input type="text" class="form-control" name="observacion_credito" id="observacion_credito" maxlength="250" value="0">
                                <input type="hidden" class="form-control" name="nombre_vendedor" id="nombre_vendedor" maxlength="250">
                              </div> 

                       
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          </div>                            
                           
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <button class="btn btn-primary"  id="btnProcesar" type="button"  data-toggle="modal" href="#myModal23" ><i class="fa fa-save" ></i> Procesar (F9)</button>

                            <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar (F7)</button>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Valor Venta:</label>
                            <h2 id="total">Q/. 0.00</h2>                         
                          </div> 
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Valor Descuento:</label>
                            <h3 id="totaldes">Q/. 0.00</h3>                            
                          </div>                                                                                                                  


                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Busqueda Articulo:</label>
                            <input type="hidden" id="txtbusquedaartcodebar" class="form-control"  placeholder="Click para Gestionar las lecturas con pistola">
                              <input type="text" id="txtbusquedaarticulo" class="form-control" autofocus="autofocus" placeholder="Ingresa el nombre correspondiente del articulo a vender">
                            </div> 
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <a data-toggle="modal" href="#myModal">           
                              <button id="btnAgregarArt" type="button" class="btn btn-primary"> <span class="fa fa-plus"></span> Agregar Artículos</button>
                            </a>
                          </div>                                                      
                          </div> 

                          <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Desripcion</th>
                                    <th>Stock</th>
                                    <th>Can.</th>
                                    <th>P.V.</th>
                                    <th>Des. Q</th>
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
                                    <th><input type="hidden" name="total_venta" id="total_venta" ></th>
                                    <th><input type="hidden" name="total_ventades" id="total_ventades" ></th>                                    
                                  
                                </tfoot>
                                <tbody>
                                   
                                </tbody>
                            </table>
                          </div>

                         
                          <div class="modal fade" id="myModal23" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                            <div class="modal-dialog" style="display: block; padding-right: 17px;">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                  <h1 class="modal-title">Detalle de Cambio</h1>
                                </div>
                                <div class="modal-body">
                                    <table>
                                      <tr>
                                        <td><h1>VENTA: </h1> </td>
                                        <td>
                                          <STRONG><h1 id="vistatotal"></h1></STRONG>
                                        </td>
                                      </tr> 
                                      <tr>
                                        <td><h1>EFECTIVO: </h1></td>
                                        <td ><h1><input type="number" id="cefectivo" name="cefectivo" step="0.1" class="form-control" style="width:100px"></h1>
      
                                        </td>
                                      </tr>
                                      <tr>
                                        <td><h1>CAMBIOS: </h1></td>
                                        <td >
                                          <h1 id="cambio"><input type="text" name="rescambio" id="rescambio" style="width:100px"></h1>
                                        </td>
                                      </tr>
                                    </table>  
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cerrar</button>
                                  <button  type="button" class="btn btn-primary"  id="btnGuardar"   ><i class="fa fa-save"></i> Click P/Guardar</button>
                                </div>        
                              </div>
                            </div>
                          </div real> 

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
                <th>Modelo</th>
                <th>Color</th>
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
                <th>Modelo</th>
                <th>Color</th>
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
 


<?php
}
else
{
  require 'noacceso.php';
}
 
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script>  
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="scripts/venta_movil.js"></script>   
<?php 
}
ob_end_flush(); 
?>