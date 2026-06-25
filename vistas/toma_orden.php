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
  
if ($_SESSION['restauranteordenes']==1) 
{ 

 

  date_default_timezone_set('America/Guatemala'); 
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
                          Ordenes  
                          <small>Procesa tu Orden</small> 
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ordenes</li>
                          </ol>

                          </section>                                               
                        </div>                     

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <div class="col-lg-3 col-sm-12 col-md-12 col-xs-12">
                        <table id="tbllistadoMesas" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                      </div> 
                      <div class="col-lg-9 col-sm-12 col-md-12 col-xs-12">
                        <table id="tbllistadoOrdenes" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>TOTAL ORDEN</th>
                            <th>Estado</th>
                            <th>#IdOrden</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>TOTAL ORDEN</th>
                            <th>Estado</th>
                            <th>#IdOrden</th>
                          </tfoot>
                        </table>
                      </div>  
                    </div>
                    <div class="panel-body" style="height: 800px;" id="formularioregistros">
                      <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <form name="formulario" id="formulario" method="POST">

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label> <img class="iconos-tama" src="../public/iconos/proveedor.png"> Clientes(*):</label>
                                <input type="hidden" name="id_add_orden" id="id_add_orden">
                                <input type="hidden" name="idmesa" id="idmesa">
                                <input type="hidden" name="datos1" id="datos1">
                                
                                <div style="display: flex; gap: 10px; width: 100%;"> 
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control"  name="codigo_cliente" id="codigo_cliente" maxlength="20" placeholder="CODIGO">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" onclick="validarCodigo()" type="button">
                                                <i class="fa fa-users"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" onchange="validarnit()" name="nit" id="nit" maxlength="20" value="CF">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger" onclick="validarnit()" type="button">
                                                <i class="fa fa-search-minus"></i> NIT
                                            </button>
                                        </span>
                                    </div>
                                </div> 
                                <div class="form-group col-lg-12 col-md-12 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" maxlength="256" value="CONSUMIDOR FINAL" onchange="validarnitNombre()">
                                </div> 
                                <div class="form-group col-lg-6 col-md-12 col-sm-6 col-xs-12">
                                    <button class="btn btn-success btn-block" onclick="validarnitNombre()" type="button">
                                        <i class="fa fa-search"></i>
                                    </button> 
                                </div>  
                                <div class="form-group col-lg-6 col-md-12 col-sm-6 col-xs-12">
                                        <button class="btn btn-success btn-block" onclick="listartbBusquedaCliente()" type="button">
                                            <i class="fa fa-search-minus"></i> NOMBRE
                                        </button>
                                </div>                                                                                                 



                                <div style="display: flex; gap: 10px; width: 100%;">
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" maxlength="256"  value="CIUDAD">
                                    </div>  
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" name="correo_cliente" id="correo_cliente" maxlength="256"  value="soporte@gmail.com">
                                    </div>
                                </div>  
                                <div style="display: flex; gap: 10px; width: 100%;">
                                  <div class="input-group" style="flex: 1;">
                                    <input type="text" class="form-control" name="idcliente" id="idcliente" value="1" readonly="">
                                  </div>
                                  <div class="input-group" style="flex: 1;">
                                    <input type="text" class="form-control" name="valor_tarjeta" id="valor_tarjeta" value="0" readonly="">
                                  </div>
                                  <div class="input-group" style="flex: 1;">
                                    <select class="form-control select-picker" name="tipo_documento_cliente" id="tipo_documento_cliente" required>
                                      <option value="NIT">NIT</option>
                                      <option value="DPI">DPI</option>
                                      <option value="PASAPORTE">PASAPORTE</option> 
                                    </select> 
                                  </div>                               

                                  
                                </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-6 col-xs-12">
                              <a data-toggle="modal" href="#myModal">           
                                <button id="btnAgregarArt" name="btnAgregarArt" type="button" class="btn btn-primary btn-block" > <span class="fa fa-plus"></span> Agregar Artículos</button>
                              </a>
                            </div> 
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                              <label>Codigo de Barra:</label>
                              <input type="text" id="txtbusquedaartcodebar" class="form-control"   placeholder="Click para Gestionar las lecturas con pistola" autofocus="autofocus"> 
                            </div> 
                            <div class="form-group col-lg-6 col-md-23 col-sm-6 col-xs-12">
                              <label>Nombre Articulo:</label>
                                <input type="text" id="txtbusquedaarticulo" class="form-control"  placeholder="Ingresa el nombre correspondiente del articulo a vender">
                            </div>                                                                                                                                                                                                                     
                          </div> 

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                      
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                              <label>Fecha Orden(*):</label>
                              <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                            </div>                          


                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                              <label>Propina Q(*):</label>
                              <input type="number" step="any" class="form-control" name="propina" id="propina" required="" value="0" onchange="modificarSubototales()">
                            </div>                                                          

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                           



                          </div>
                      


                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cant</th>
                                    <th>P.V.</th>
                                    <th>S.T</th>   
                                    <th>S.TDES</th>                                                                      
                                </thead>
                                <tfoot>
                                    <th>TOTAL</th>
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
 
                        </form>
                      </div>
                        <div class="col-lg-8 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <button type="button" class="btn btn-success btn-block" id="btnGuardar"><i class="fa fa-save"></i> Click P/Guardar</button>

                              <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar (F7)</button>
                            </div> 
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>T.Venta:</label>
                              <h2 id="total">Q/. 0.00</h2>                         
                            </div> 
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>T. Descuento:</label>
                              <h3 id="totaldes">Q/. 0.00</h3>                            
                            </div>                             
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="align-self: center;">Categoría Productos(*):</label>
                            <select id="idcategoria" name="idcategoria" class="form-control" onchange="listarArticulosxcategoria()" data-live-search="true">
                                <option value="">Seleccione una categoría</option>
                                <!-- Opciones generadas dinámicamente aquí -->
                            </select>
                          </div>       
                          
                          <div class="row">
                            <div class="col-md-12">
                              <div class="panel-body table-responsive" id="listadoregistros2">
                                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">Cargando Articulos Espere...</div>
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
<div class="modal fade" id="myModalBusquedacliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width: 80% !important;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <div class="col-lg-12 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-green">
                      <div class="inner">
                        <h4><img class="iconos-cambio" src="../public/iconos/busqueda.png">BUSQUE UN CLIENTE</h4>
                      </div>
                      <div class="icon">
                        <i class="fa fa-home"></i>
                      </div>

                    </div> 
                  </div>  
      </div> 
      <div class="modal-body">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
        <table id="tbBusquedaCliente" class="table table-striped table-bordered table-condensed table-hover">
          <thead>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Direccion</th>
              <th># Doc</th>
              <th>Telefono</th>
          </thead>
          <tbody>
             
          </tbody>
          <tfoot>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Direccion</th>
              <th># Doc</th>
              <th>Telefono</th>
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
 


<div class="modal fade" id="myModalImpresionFAc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="display: block; padding-right: 17px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Seleccion el Formato a Imprimir</h4>
            </div>
            <div class="modal-body d-flex flex-column align-items-center text-center">
                <input type="hidden" name="idventa_impresion" id="idventa_impresion">
                <input type="hidden" name="tipo_comprobante_impresion" id="tipo_comprobante_impresion">
                <a class="btn btn-app btn-success m-2" onclick="impresionticket58mm()">
                    <i class="fa fa-print"></i> Ticket 58mm
                </a>                   
            </div>         
            <div class="modal-footer">
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
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>  
<script type="text/javascript" src="scripts/toma_orden.js"></script>   
<?php 
}
ob_end_flush(); 
?>