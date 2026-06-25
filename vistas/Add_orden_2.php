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
  if ($_SESSION['ordenes']==1)    
  {  
      
?>   

<style type="text/css">
  .search-input {
    width: 100%; /* Ocupa el 100% del contenedor */
    padding: 12px; /* Espaciado interno */
    font-size: 16px; /* Tamaño de fuente */
    color: #333; /* Color del texto */
    background-color: #f8f9fa; /* Color de fondo */
    border: 2px solid #007bff; /* Borde azul */
    border-radius: 4px; /* Bordes redondeados */
    transition: border-color 0.3s; /* Efecto de transición */
}

.search-input:focus {
    border-color: #0056b3; /* Cambia el color del borde al enfocar */
    outline: none; /* Quitar el borde por defecto */
}
</style>
<!--Contenido--> 
      <!-- Content Wrapper. Contains page content --> 
      <div class="content-wrapper" >        
        <!-- Main content --> 
        <section class="content"> 
            <div class="row"> 
              <div class="col-md-12">
                  <div class="box">
                    <div class="col-lg-12 col-xs-12">
                      <!-- small box -->
                    <div class="box-header with-border box box-primary">
                      <section class="content-header">
                      <h1>
                      Orden 
                      <small>Toma de Pedidos</small>
                      </h1>
                      <ol class="breadcrumb">
                      <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                      <li class="active">Orden</li>
                      </ol>

                      </section>                    
                    </div>  
                    </div>  
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <div class="col-lg-4 col-sm-12 col-md-12 col-xs-12">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                      </div> 
                      <div class="col-lg-8 col-sm-12 col-md-12 col-xs-12">
                        <table id="tbllistado1" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>TOTAL ORDEN</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>TOTAL ORDEN</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                      </div>                      
                    </div>
                    <div class="panel-body" style="height: 1000px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                        <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            
                              <label>No de Per:</label>
                              <input type="hidden" name="id_add_orden" id="id_add_orden">
                              <input type="hidden" name="idmesa" id="idmesa">
                              <input type="number" class="form-control" name="no_personas" id="no_personas" maxlength="50" required>

                            </div>
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                              <label>Cliente(*):</label>
                              <select id="idcliente" v-model="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required>
                                 
                              </select> 
                            </div> 
                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                              <label>FEC(*):</label>
                              <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" >
                            </div>                              
 
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                              <table  class="table table-striped table-bordered table-condensed table-hover">
                                <thead style="background-color:#A9D0F5">
                                      <th>Opciones</th>
                                      <th>Art</th>
                                      <th>Cant</th>
                                      <th>Descrip</th>
                                      <th>Q</th> 
                                      <th>Sub Q</th>

                                  </thead>
                                  <tfoot>
                                      <th>TOTAL</th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th><input type="hidden" name="total_venta" id="total_venta" ></th>                                  
                                    
                                  </tfoot>
                                  <tbody id="detalles">
                                     
                                  </tbody>
                              </table>
                            </div>                                                                                                         
                            <div class="form-group col-lg-7 col-md-7 col-sm-7 col-xs-12">
                              <button class="btn btn-primary btn-block" type="button" id="btnGuardar"><i class="fa fa-save"></i> Procesar</button>
   
                              <button class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>


                            
                            </div>
                            <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                              <div style="display: flex; gap: 12px; width: 100%;">
                                  <div class="input-group" style="flex: 1;">
                                      <span class="input-group-addon">
                                          <i class="fa fa-money" style="color: green;">S/Total</i>
                                      </span>
                                      <input type="number" step="any" class="form-control" id="total" name="total">
                                  </div>
                              </div>  
                              <div style="display: flex; gap: 12px; width: 100%;">
                                  <div class="input-group" style="flex: 1;">
                                      <span class="input-group-addon">
                                          <i class="fa fa-money" style="color: green;">Des</i>
                                      </span>
                                      <input type="number" step="any" class="form-control" id="descuento_orden" name="descuento_orden" onchange="calculartotalfinal();">
                                  </div>
                              </div> 
                              <div style="display: flex; gap: 12px; width: 100%;">
                                  <div class="input-group" style="flex: 1;">
                                      <span class="input-group-addon">
                                          <i class="fa fa-money" style="color: green;">Propina</i>
                                      </span>
                                      <input type="number" step="any" class="form-control" id="propina_sugerida" onchange="calculartotalfinal();" name="propina_sugerida">
                                <input type="hidden"  class="form-control" id="propina_sugeridatotal" onchange="calculartotalfinal();" name="propina_sugeridatotal">
                                  </div>
                              </div>  
                              <div style="display: flex; gap: 12px; width: 100%;">
                                  <div class="input-group" style="flex: 1;">
                                      <span class="input-group-addon">
                                          <i class="fa fa-money" style="color: green;">Total</i>
                                      </span>
                                      <input type="number" step="any" class="form-control" id="total_final"  name="total_final">
                                  </div>
                              </div>                                                                                                                      
                                                                                                                      
                                <!-- small box -->                                                           
                                                       
                            </div>                             
                        </div>

                        <div class="modal fade" id="myModal23" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                          <div class="modal-dialog" style="width: 30% !important;">
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
                                        <STRONG><input type="text" id="vistatotal" name="vistatotal" class="form-control" readonly=""></STRONG>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><h1>EFECTIVO: </h1></td>
                                      <td ><h1><input type="number" id="cefectivo" name="cefectivo" step="0.1" class="form-control" onchange="calcularefectivo();"></h1>

                                      </td>
                                    </tr>
                                    <tr>
                                      <td><h1>TARJETA: </h1></td>
                                      <td ><h1><input type="number" id="cefectivo_tarjeta" name="cefectivo_tarjeta" step="0.1" class="form-control" onchange="calcularefectivo();"></h1>
                                      </td>
                                    </tr> 
                                    <tr>
                                      <td><h1>FORMA PAGO: </h1></td>
                                      <td>
                                          <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required="">
                                              <option value="Efectivo">Efectivo</option>
                                              <option value="Efectivo/Tarjeta">Efectivo/Tarjeta</option>
                                              <option value="Tarjeta">Tarjeta</option>
                                          </select>
                                    
                                      </td>
                                    </tr> 
                                    <tr>
                                      <td><h1>TIPO TARJETA: </h1></td>
                                      <td>
                                          <select name="tipo_tarjeta" id="tipo_tarjeta" class="form-control selectpicker" required="">
                                              <option value="VISA">VISA</option>
                                              <option value="MASTER">MASTER</option>
                                          </select>
                                    
                                      </td>
                                    </tr>                                     
                                    <tr>
                                      <td><h1>TIPO/DOC: </h1></td>
                                      <td>
                                          <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                                              <option value="Envio">Envio</option>
                                          </select>
                                    
                                      </td>
                                    </tr>                                                                          
                                    <tr>
                                      <td><h1>TARJETA AUT: </h1></td>
                                      <td ><h1><input type="text" id="no_autorizacion_tarjeta" name="no_autorizacion_tarjeta" class="form-control" /h1>

                                      </td>
                                    </tr>                                                                            
                                    <tr>
                                      <td><h1>CAMBIOS: </h1></td>
                                      <td >
                                        <h1 id="cambio"><input type="hidden" name="rescambio" id="rescambio" ></h1>
                                      </td>
                                    </tr>
                                  </table>  
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cerrar</button>
                                <button  type="button" class="btn btn-primary"  id="btnGuardarCobrar" ><i class="fa fa-save"></i> Cobrar</button>
                              </div>        
                            </div>
                          </div>
                        </div >   

                        <div class="modal fade" id="myModal24" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                          <div class="modal-dialog" style="width: 30% !important;">
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
                                        <STRONG><input type="text" id="vistatotal2" name="vistatotal2" class="form-control" readonly=""></STRONG>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><h1>EFECTIVO: </h1></td>
                                      <td ><h1><input type="number" id="cefectivo2" value="0" name="cefectivo2" step="0.1" class="form-control" onchange="calcularefectivo2();"></h1>

                                      </td>
                                    </tr>
                                    <tr>
                                      <td><h1>TARJETA: </h1></td>
                                      <td ><h1><input type="number" id="cefectivo_tarjeta2" value="0" name="cefectivo_tarjeta2" step="0.1" class="form-control" onchange="calcularefectivo2();"></h1>
                                      </td>
                                    </tr> 
                                    <tr>
                                      <td><h1>FORMA PAGO: </h1></td>
                                      <td>
                                          <select name="forma_pago2" id="forma_pago2" class="form-control selectpicker" required="">
                                              <option value="Efectivo">Efectivo</option>
                                              <option value="Efectivo/Tarjeta">Efectivo/Tarjeta</option>
                                              <option value="Tarjeta">Tarjeta</option>
                                          </select>
                                    
                                      </td>
                                    </tr>                                      
                                    <tr>
                                      <td><h1>TARJETA AUT: </h1></td>
                                      <td ><h1><input type="text" id="no_autorizacion_tarjeta2" name="no_autorizacion_tarjeta2" class="form-control"></h1>

                                      </td>
                                    </tr>                                                                            
                                    <tr>
                                      <td><h1>CAMBIOS: </h1></td>
                                      <td >
                                        <h1 id="cambio2"><input type="hidden" name="rescambio2" id="rescambio2" ></h1>
                                      </td>
                                    </tr>
                                  </table>  
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cerrar</button>
                                <button  type="button" class="btn btn-primary"  id="btnGuardarCobrar2" ><i class="fa fa-save"></i> Cobrar</button>
                              </div>        
                            </div>
                          </div>
                        </div > 


                        </form>
                        <div class="col-lg-8 col-sm-8 col-md-12 col-xs-12">
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="align-self: center;">Categoría Productos(*):</label>
                            <select id="idcategoria" name="idcategoria" class="form-control " onchange="listarArticulos()" data-live-search="true" required></select>
                          </div>      
                          
                          <div class="row">
                            <div class="col-md-12">
                              <div class="panel-body table-responsive" id="listadoregistros2">
                                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">asdasd</div>
                              </div>
                            </div>
                          </div>

                      
                        </div>                        
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
 
    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
   <!-- Modal -->
  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 80% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

          <h4 class="modal-title">                    
            <div class="col-lg-12 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>Nuevo Cliente!</h3>
                          <H4> </H4>                          
                        </div>
                        <div class="icon">
                          <i class="fa fa-home"></i>
                        </div>

                      </div>
              </div>
            </h4>
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
                              <option value="NIT">NIT</option>
                              <option value="DPI">DPI</option>
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
                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" value="+502-0000-0000">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="50" value="correo@dominio.com">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tipo Cliente:</label>
                            <select class="form-control select-picker" name="tipo_cliente" id="tipo_cliente" required>
                              <option value="PUBLICO">Publico</option>
                              <option value="DISTRIBUIDOR">Distribuidor</option>
                              <option value="MAYORISTA">Mayorista</option>
                              <option value="MENUDEO">Menudeo</option>
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

  <div class="modal fade" id="myModal23" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 30% !important;">
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
                  <STRONG><input type="text" id="vistatotal" name="vistatotal" class="form-control" readonly=""></STRONG>
                </td>
              </tr>
              <tr>
                <td><h1>EFECTIVO: </h1></td>
                <td ><h1><input type="number" id="cefectivo" name="cefectivo" step="0.1" class="form-control" onchange="calcularefectivo();"></h1>

                </td>
              </tr>
              <tr>
                <td><h1>TARJETA: </h1></td>
                <td ><h1><input type="number" id="cefectivo_tarjeta" name="cefectivo_tarjeta" step="0.1" class="form-control" onchange="calcularefectivo();"></h1>
                </td>
              </tr> 
              <tr>
                <td><h1>FORMA PAGO: </h1></td>
                <td>
                    <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required="">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Efectivo/Tarjeta">Efectivo/Tarjeta</option>
                        <option value="Tarjeta">Tarjeta</option>
                    </select>
              
                </td>
              </tr>                                      
              <tr>
                <td><h1>TARJETA AUT: </h1></td>
                <td ><h1><input type="text" id="no_autorizacion_tarjeta" name="no_autorizacion_tarjeta" class="form-control" /h1>

                </td>
              </tr>                                                                            
              <tr>
                <td><h1>CAMBIOS: </h1></td>
                <td >
                  <h1 id="cambio"><input type="hidden" name="rescambio" id="rescambio" ></h1>
                </td>
              </tr>
            </table>  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cerrar</button>
          <button  type="button" class="btn btn-primary"  id="btnGuardarCobrar" ><i class="fa fa-save"></i> Cobrar</button>
        </div>        
      </div>
    </div>
  </div >   

  <div class="modal fade" id="myModal24" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 30% !important;">
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
                  <STRONG><input type="text" id="vistatotal2" name="vistatotal2" class="form-control" readonly=""></STRONG>
                </td>
              </tr>
              <tr>
                <td><h1>EFECTIVO: </h1></td>
                <td ><h1><input type="number" id="cefectivo2" value="0" name="cefectivo2" step="0.1" class="form-control" onchange="calcularefectivo2();"></h1>

                </td>
              </tr>
              <tr>
                <td><h1>TARJETA: </h1></td>
                <td ><h1><input type="number" id="cefectivo_tarjeta2" value="0" name="cefectivo_tarjeta2" step="0.1" class="form-control" onchange="calcularefectivo2();"></h1>
                </td>
              </tr> 
              <tr>
                <td><h1>FORMA PAGO: </h1></td>
                <td>
                    <select name="forma_pago2" id="forma_pago2" class="form-control selectpicker" required="">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Efectivo/Tarjeta">Efectivo/Tarjeta</option>
                        <option value="Tarjeta">Tarjeta</option>
                    </select>
              
                </td>
              </tr>                                      
              <tr>
                <td><h1>TARJETA AUT: </h1></td>
                <td ><h1><input type="text" id="no_autorizacion_tarjeta2" name="no_autorizacion_tarjeta2" class="form-control"></h1>

                </td>
              </tr>                                                                            
              <tr>
                <td><h1>CAMBIOS: </h1></td>
                <td >
                  <h1 id="cambio2"><input type="hidden" name="rescambio2" id="rescambio2" ></h1>
                </td>
              </tr>
            </table>  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cerrar</button>
          <button  type="button" class="btn btn-primary"  id="btnGuardarCobrar2" ><i class="fa fa-save"></i> Cobrar</button>
        </div>        
      </div>
    </div>
  </div >   
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
<script type="text/javascript" src="scripts/Add_orden.js?V=16062022"></script> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script type="text/javascript" src="scripts/Add_orden_vue.js"></script> 

<?php  
}  
ob_end_flush();
?>