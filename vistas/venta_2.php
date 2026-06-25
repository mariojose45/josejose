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
  
if ($_SESSION['restaurantecobros']==1) 
{ 

  require_once "../modelos/Consultas.php";
    $consulta = new Consultas();
    $rsptav = $consulta->totalventahoy();
    $regv=$rsptav->fetch_object();   
    $totalv=$regv->total_venta;   
  
    $res_efectivo = $consulta->totalventahoyefectivo();
    $resv_efectivo=$res_efectivo->fetch_object(); 
    $restotalpagoefectivo=$resv_efectivo->total_venta;   

    $res_tranferencia = $consulta->totalventahoytransferencia();
    $resv_transferencia=$res_tranferencia->fetch_object(); 
    $restotalpagotransferencia=$resv_transferencia->total_venta; 
  
    $res_credito = $consulta->totalventahoycredito2();
    $resv_credito=$res_credito->fetch_object(); 
    $restotalpagocredito=$resv_credito->total_venta;    
  
  
    $res_tarjeta = $consulta->totalventahoyTarjeta2();
    $resv_tarjeta=$res_tarjeta->fetch_object(); 
    $restotalpagotarjeta=$resv_tarjeta->total_venta;    
  
  
    $rsptavc = $consulta->totalefectivoiniciocaja();
    $regvc=$rsptavc->fetch_object();
    $restotalefectivo=$regvc->totalefectivo;
    $resApertura=$regvc->tipo_operacion; 

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
                          Ventas  
                          <small>Cobro Restaurante</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ventas</li>
                          </ol>

                          </section>  
                          <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar (F8)</button> </h1>                                             
                        </div>                     

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>" >
                          <button class="btn btn-success btn-block" onclick="listar()">Generar Ventas</button>
                        </div>
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                           <th>Opciones</th>
                            <th>#Mesa/#Orden</th>
                            <th>Idventa</th>
                            <th>Cliente</th>
                            <th>Usuario Cobro</th> 
                            <th>Usuario Mesero</th> 
                            <th>Tipo Doc</th> 
                            <th>No/Correlativo</th> 
                            <th>T. V</th>
                            <th>T. V. Des</th>
                            <th>F/Pago</th>                            
                            <th>Efectivo</th>
                            <th>Tarjeta</th>
                            <th>Credito</th>
                            <th>Transferencia</th>
                            <th>Cambio</th>
                            <th>Fecha</th>
                            <th>Fecha/Certi</th>
                            <th>Serie/Certi</th>
                            <th>DTE/Certi</th>
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
                    <div class="panel-body" style="height: 800px;" id="formularioregistros">

                        <form name="formulario" id="formulario" method="POST">

                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label> <img class="iconos-tama" src="../public/iconos/proveedor.png"> Clientes(*):</label>
                                <input type="hidden" name="idventa" id="idventa">
                                <input type="hidden" name="id_add_orden" id="id_add_orden">
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
                                <div style="display: flex; gap: 10px; width: 100%;">
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control"  name="nombre_cliente" id="nombre_cliente" maxlength="256" value="CONSUMIDOR FINAL"
                                        onchange="validarnitNombre()">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" onclick="validarnitNombre()" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>                                        
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" onclick="listartbBusquedaCliente()" type="button">
                                                <i class="fa fa-search-minus"></i> NOMBRE
                                            </button>
                                        </span>
                                    </div>  
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control"  name="telefono_cliente" id="telefono_cliente" maxlength="256" value="0">
                                    </div>
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
                                  <div class="input-group" style="flex: 1;">
                                    <input type="text" class="form-control"   name="aperturaCaja" id="aperturaCaja" value="<?php echo $resApertura; ?>" readonly="">
                                  </div>                                  

                                  
                                </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-6 col-xs-12">
                              <a data-toggle="modal" href="#myModal">           
                                <button id="btnAgregarArt" name="btnAgregarArt" type="button" class="btn btn-primary btn-block" > <span class="fa fa-plus"></span> Agregar Artículos</button>
                              </a>
                            </div> 
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>Busqueda Codigo de Barra:</label>
                              <input type="text" id="txtbusquedaartcodebar" class="form-control"   placeholder="Click para Gestionar las lecturas con pistola" autofocus="autofocus"> 
                            </div> 
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>Nombre Articulo:</label>
                                <input type="text" id="txtbusquedaarticulo" class="form-control"  placeholder="Ingresa el nombre correspondiente del articulo a vender">
                            </div>   
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>Propina:</label>
                                <input type="number" step="any" id="propina" name="propina" class="form-control"  onchange="modificarSubototales()">
                            </div>                                                                                                                                                                                                                                                 
                          </div>

                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                              <label>Orden Mesa:</label> 
                              <input type="text" name="idcotizacion" id="idcotizacion" class="form-control">
                              <button type="button" class="btn  btn-primary btn-block" id="btncargar">Orden Mesa</button>
                            </div>                        
                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                              <label>Fecha Creacion(*):</label>
                              <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                            </div>
                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                              <label>Forma pago(*):</label>
                              <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required="">
                                 <option value="Efectivo">Efectivo</option>
                                 <option value="Efectivo/Tarjeta">Efectivo/Tarjeta</option>
                                 <option value="Tarjeta">Tarjeta</option>                                 
                                 <option value="Credito">Credito</option>
                                 <option value="Transferencia">Transferencia</option>
                              </select> 
                            </div>
                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                              <label>Tipo/Comprobante(*):</label>
                              <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                                 <option value="Envio">Envio</option>
                                 <option value="Factura">Factura</option>
                              </select>
                            </div>                            

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_formapago" name="div_formapago">
                              <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label>Tipo Tarjeta:</label>
                                <select name="tipo_pagoBacVisaNet" id="tipo_pagoBacVisaNet" class="form-control selectpicker" onchange="mostrarOpcionesAdicionales();">
                                  <option value="Seleccione Uno">Seleccione Uno</option>
                                  <option value="VISANET">VISANET</option>
                                  <option value="BAC">BAC</option>
                                </select>
                              </div> 
                              <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12" id="opcionesAdicionalesDiv">
                                  <label>Opciones Adicionales:</label>
                                  <select name="opcionesAdicionales" id="opcionesAdicionales" class="form-control ">
                                      <!-- Las opciones se llenarán dinámicamente -->
                                  </select>
                              </div>                                      
                            </div>                            

                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <button class="btn btn-primary btn-block"  id="btnProcesar"  type="button"  data-toggle="modal" href="#myModal23" ><i class="fa fa-save" ></i> Procesar (F9)</button>

                              <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar (F7)</button>
                            </div> 
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>Valor Venta:</label>
                              <h2 id="total">Q. 0.00</h2>                          
                            </div> 
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>Valor Descuento:</label>
                              <h3 id="totaldes">Q. 0.00</h3>                            
                            </div>  
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <a data-toggle="modal" href="#myModalapertura">           
                                <button id="btnAgregarArt" type="button" class="btn btn-info btn-block"> <span class="fa fa-plus"></span>Apertura</button>
                              </a>
                            </div>


                          </div>
                      


                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Stock</th>
                                    <th>Cant</th>
                                    <th>Presentacion</th>
                                    <th>P.V.</th>
                                    <th>Des %</th>
                                    <th>S.T</th>
                                    <th>S.T.Des</th>                                                                       
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

                         
                          <div class="modal fade" id="myModal23" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" style="width: 60% !important;">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                  <h1 class="modal-title">Detalle de Cambio</h1>
                                </div>
                                <div class="modal-body">

                                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>VENTA:</label>
                                    <strong><h2 id="vistatotal"></h2></strong> <!-- Cambio a h2 o div según tu necesidad -->
                                  </div>

                                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>EFECTIVO:</label>
                                    <input type="number" id="cefectivo" name="cefectivo" step="0.1" class="form-control" onchange="calcularefectivo()" value="0"> <!-- Eliminado el h1 -->
                                  </div>
                                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>CREDITO:</label>
                                    <input type="number" id="ccredito" name="ccredito" step="0.1" class="form-control" onchange="calcularefectivo()" value="0"> <!-- Eliminado el h1 -->
                                  </div> 
                                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>TRANSFERENCIA:</label>
                                    <input type="number" step="any" id="ctransferencia" name="ctransferencia" step="0.1" class="form-control" onchange="calcularefectivo()" value="0"> <!-- Eliminado el h1 -->
                                  </div>                                                                    
                                

                                    <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                      <label>Autorizacion:</label>
                                      <input type="number" id="observacion_credito" name="observacion_credito" class="form-control"> <!-- Eliminado el h1 -->
                                    </div>

                                    <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                      <label>Q TARJETA:</label>
                                      <input type="number" step="any" id="ctarjeta" name="ctarjeta" class="form-control" onchange="calcularefectivo()" value="0"> <!-- Eliminado el h1 -->
                                    </div>

                                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>CAMBIOS:</label>
                                    <h2 id="cambio"></h2> <!-- Opcionalmente usar h2 o div si es un encabezado importante -->
                                    <input type="hidden" name="rescambio" id="rescambio">
                                  </div>
                                </div>

                                <div class="modal-footer">
                                  <button type="button" class="btn btn-success btn-block" id="btnGuardar"><i class="fa fa-save"></i> Click P/Guardar</button>
                                  <button type="button" class="btn btn-danger btn-block" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cerrar</button>
                                </div>
                              </div>
                            </div>
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
 
<div class="modal fade" id="myModalapertura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="display: block; padding-right: 17px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Ingrese el Efectivo de Apertura</h4>
        </div>
        <div class="modal-body">
         <div class="col-lg-812 col-sm-12 col-md-812 col-xs-12 table-responsive">
          <form name="formularioapertura" id="formularioapertura" method="POST">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Efectivo(*):</label>
              <input type="number" step="any" class="form-control" name="total_efectivo" id="total_efectivo" >
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary btn-block" type="button" id="btnGuardarApertura"><i class="fa fa-save"></i> Guardar Apertura Caja</button>

              <button class="btn btn-danger btn-block" onclick="cancelarformaperturacaja()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </form>                               
        </div>         
      </div>
      <div class="modal-footer">
      </div>        
    </div>
  </div>
</div> 


  <div class="modal fade" id="myModalCierre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="display: block; padding-right: 17px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Ingrese el Efectivo de Cierre</h4>
        </div>
        <div class="modal-body">
         <div class="col-lg-812 col-sm-12 col-md-812 col-xs-12 table-responsive">
          <form name="formulariocierre" id="formulariocierre" method="POST">
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Ingrese Total de Efectivo(*):</label>
                                <input type="number" step="any" class="form-control" onchange="calcularcaja()" name="total_efectivocierre" id="total_efectivocierre" >
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Efectivo Ingresado de Apertura de caja:</label>
                            <input type="text" class="form-control" name="total_efectivo_inicio" id="total_efectivo_inicio" value="<?php echo $restotalefectivo; ?>" readonly="" >
                          </div>                             
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Total de Ventas diarias:</label>
                              <input type="number" step="any" class="form-control" onchange="calcularcaja()" name="total_ventas_diarias" id="total_ventas_diarias" value="<?php echo $totalv; ?>" readonly="" >
                            </div>      
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Total de Ventas diarias Efectivo:</label>
                              <input type="number" step="any" class="form-control" onchange="calcularcaja()" name="total_ventas_diarias_efectivo" id="total_ventas_diarias_efectivo" value="<?php echo $restotalpagoefectivo; ?>" readonly="" >
                            </div>  
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Total de Ventas diarias Credito:</label>
                              <input type="number" step="any"class="form-control" onchange="calcularcaja()" name="total_ventas_diarias_credito" id="total_ventas_diarias_credito" value="<?php echo $restotalpagocredito; ?>" readonly="" >
                            </div> 
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Total de Ventas diarias Transferencia:</label>
                               <input type="number" step="any" class="form-control" onchange="calcularcaja()" name="res_total_ventas_diarias_transferencia" id="res_total_ventas_diarias_transferencia" value="<?php echo $restotalpagotransferencia; ?>" readonly="" >
                            </div>  
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Total de Ventas diarias Tarjeta:</label>
                              <input type="number" step="any"class="form-control" onchange="calcularcaja()" name="total_ventas_diarias_tarjeta" id="total_ventas_diarias_tarjeta" value="<?php echo $restotalpagotarjeta; ?>" readonly="" >
                            </div>                                                                                                                                                                                                   
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>Sobrante/Faltante:</label>
                              <input type="number" step="any" class="form-control" onchange="calcularcaja()" name="total_efectivo_cierre_operaciones" id="total_efectivo_cierre_operaciones" readonly="">
                            </div>                              
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btb-block" type="button" id="btnGuardarCierre"><i class="fa fa-save"></i> Guardar</button>
 
                            <button class="btn btn-danger btb-block" onclick="cancelarformCierre()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div> 
            </form>  
        </div>          
        </div> 
        <div class="modal-footer">
        </div>        
      </div>
    </div>
  </div> 

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
                <a class="btn btn-app btn-primary m-2" onclick="impresionticket79mm()">
                    <i class="fa fa-print"></i> Ticket 79mm
                </a>
                <a class="btn btn-app btn-info m-2"  onclick="impresionticketCarta()">
                    <i class="fa fa-print"></i> Carta
                </a>                       
            </div>         
            <div class="modal-footer">
                <button class="btn btn-danger btn-block" onclick="limpiar()" type="button"><i class="fa fa-arrow-circle-left"></i> Crear Nueva Venta </button>
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
<script type="text/javascript" src="scripts/venta_2.js"></script>    
<?php 
}
ob_end_flush(); 
?>