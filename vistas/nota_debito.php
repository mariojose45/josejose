<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión




// Verificamos si el usuario está logueado
if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
    exit();
} else {
    require 'header.php';

    // Verificamos si tiene permisos
    if ($_SESSION['notaDebito'] == 1) {
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
                          NOTA DEBITO  
                          <small>Compras</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">NOTA DEBITO</li>
                          </ol>

                          </section>  
                          <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> </h1>                                             
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
                          <button class="btn btn-success btn-block" onclick="listar()">Generar Nota Debito</button>
                        </div>
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th># ND</th>
                            <th>Fecha ND</th>
                            <th># Ingreso</th>
                            <th>Fecha Ingreso</th>
                            <th>Proveedor</th>
                            <th>Usuario ND</th>
                            <th>Usuario Ingreso</th>
                            <th>Documento</th>
                            <th>Número</th>
                            <th>T. Compra</th>
                            <th>Motivo</th>
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
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 600px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label> <img class="iconos-tama" src="../public/iconos/proveedor.png"> Proveedor(*):</label>
                                <input type="hidden" name="idnota_debito" id="idnota_debito">
                                <input type="hidden" name="idingreso" id="idingreso">
                                <input type="hidden" name="datos1" id="datos1">
                                <div style="display: flex; gap: 10px; width: 100%;"> 
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control"  name="codigo_cliente" id="codigo_cliente" maxlength="20" placeholder="CODIGO" readonly>
                                        <span class="input-group-btn" >
                                            <button class="btn btn-success" onclick="validarCodigo()" type="button">
                                                <i class="fa fa-users"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" onchange="validarnit()" name="nit" id="nit" maxlength="20" value="CF" readonly>
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
                                        onchange="validarnitNombre()" readonly>
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
                                        <input type="text" class="form-control"  name="telefono_cliente" id="telefono_cliente" maxlength="256" value="0" readonly>
                                    </div>
                                </div> 
                                <div style="display: flex; gap: 10px; width: 100%;">
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" maxlength="256"  value="CIUDAD" readonly>
                                    </div>  
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" name="correo_cliente" id="correo_cliente" maxlength="256"  value="soporte@gmail.com" readonly> 
                                    </div>
                                </div>  
                                <div style="display: flex; gap: 10px; width: 100%;">
                                    <div class="input-group" style="flex: 1;">
                                         <input type="text" class="form-control" name="idcliente" id="idcliente" value="1" readonly="">
                                    </div>  
                                    <div class="input-group" style="flex: 1;">
                                      <input type="text" class="form-control" name="tipo_documento_cliente" id="tipo_documento_cliente"  readonly="">
                                    </div>
                                </div> 
                             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label>MOTIVO(*):</label>
                              <input type="text" class="form-control" name="motivo_ND" id="motivo_ND" >
                            </div>                                  
                                                                                                                                                            
                          </div>
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                              <label>Cargar Ingreso:</label>
                              <input type="text" name="idcotizacion" id="idcotizacion" class="form-control">
                              <button type="button" class="btn  btn-primary btn-block" id="btncargar">Ingreso</button>
                            </div>
                             <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                              <label>Fecha ND(*):</label>
                              <input type="date" class="form-control" name="fecha_hora_ND" id="fecha_hora_ND"  >
                            </div>
                          
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>                       
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>Tipo Ingreso(*):</label>
                              <input type="text" class="form-control" name="tipo_ingreso_producion" id="tipo_ingreso_producion"  readonly="">
                            </div>                           
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>Fecha Ingreso(*):</label>
                              <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" readonly="" readonly="">
                            </div>
                           
                            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>T/ Comprobante(*):</label>
                              <input type="text" class="form-control" name="tipo_comprobante" id="tipo_comprobante" readonly="" readonly="">
                            </div> 
                            <div class="form-group col-lg-4 col-md-2 col-sm-6 col-xs-12">
                              <label>Serie:</label>
                              <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="50" placeholder="Serie" readonly="">
                            </div>
                            <div class="form-group col-lg-4 col-md-2 col-sm-6 col-xs-12">
                              <label>Número:</label>
                              <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="50" placeholder="Número" readonly="">
                            </div>
                            <div class="form-group col-lg-4 col-md-2 col-sm-6 col-xs-12">
                              <label>Impuesto:</label>
                              <input type="text" class="form-control" name="impuesto" id="impuesto" value="0" readonly="">
                            </div>                            
                          </div>                           

 
                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Presentacion</th> 
                                    <th>P.C.</th>
                                    <th>Desc %</th>
                                    <th>P.V.</th>
                                    <th>P.V.NOC</th>
                                    <th>Rango 1/3</th>
                                    <th>Rango 4/6</th>
                                    <th>Rango 7 ADELANTE</th>
                                    <th>P/UNIDAD</th>
                                    <th>P/BLISTER</th>
                                    <th>P/CAJA</th>
                                    <th>P/FARDO</th>
                                    <th>P/SACO</th>
                                    <th>P/PAQUETE</th>
                                    <th>Subtotal</th>
                                    <th>Subtotal Des</th> 
                                    <th></th>
                                </thead>
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
                                    <th>
                                      <h4 id="total">Q/. 0.00</h4>   
                                      <input type="hidden" name="total_compra" id="total_compra">
                                    </th> 
                                    <th>
                                      <h4 id="totaldes">Q/. 0.00</h4>   
                                      <input type="hidden" name="total_comprades" id="total_comprades" >
                                    </th>   
                                    <th></th>                                  
                                </tfoot>
                                <tbody>
                                   
                                </tbody> 
                            </table>
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Forma Pago(*):</label>
                             <input type="text" class="form-control" name="forma_pago" id="forma_pago" value="0" readonly="">
                          </div> 
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Dias:</label>
                            <input type="text" class="form-control"  placeholder="dd" name="dias_credito" id="dias_credito" onchange="calculardiascredito()" readonly="">
                          </div> 
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha Pago Credito(*):</label>
                            <input type="text" class="form-control" name="fecha_hora_pago_credito" id="fecha_hora_pago_credito" readonly="">
                          </div>   
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>direccion de Entrega:</label>
                            <input type="text" class="form-control"  placeholder="Direccion Entrega" name="direccion_entrega_orden_compra" id="direccion_entrega_orden_compra" readonly="">
                          </div> 
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha Entrega(*):</label>
                            <input type="date" class="form-control" name="fecha_entrega_orden_compra" id="fecha_entrega_orden_compra"  readonly="">
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Observaciones:</label>
                            <input type="text" class="form-control"  placeholder="Observaciones" name="observacion_orden_compra" id="observacion_orden_compra" readonly="">
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
 





<?php
}
else
{
  require 'noacceso.php';
}
 
require 'footer.php';  
?> 
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/nota_debito.js"></script>    
<?php 
}
ob_end_flush();
?>