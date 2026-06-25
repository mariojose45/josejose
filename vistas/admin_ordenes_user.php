
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
  if ($_SESSION['orden_trabajo_crear']==1)   
  {
  require_once "../modelos/Admin_ordenes.php";
  $adminordenes=new AdminOrdenes();
  $rsptac = $adminordenes->ordenesenreparacion();
  $regc=$rsptac->fetch_object();
  $num_ordenesreparadas=$regc->num_ordenes;   

  $rsptac = $adminordenes->ordenesespera();
  $regc=$rsptac->fetch_object(); 
  $num_ordenesespera=$regc->num_ordenes;   
  
  $rsptac = $adminordenes->ordenesentregado();
  $regc=$rsptac->fetch_object();
  $num_ordenesentragado=$regc->num_ordenes;   

  $rsptac = $adminordenes->ordenesgarantia();
  $regc=$rsptac->fetch_object();
  $num_ordenesgarantia=$regc->num_ordenes;  

  $rsptac = $adminordenes->ordenessinreparar(); 
  $regc=$rsptac->fetch_object();
  $num_ordenessinreparar=$regc->num_ordenes;  

    $rsptac = $adminordenes->ordenesreparadas();
  $regc=$rsptac->fetch_object();
  $num_ordenesreparadass=$regc->num_ordenes;  

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
                    <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
                      <!-- small box -->
                      <div class="small-box bg-aqua">
                        <div class="inner">
                          <h4>Equipos En Reparacion!</h4>
                          <H2># <?php echo $num_ordenesreparadas; ?></H2>                         
                        </div>
                        <div class="icon"> 
                          <i class="fa fa-wrench"></i>
                        </div>
                          <a data-toggle="modal" href="#myModalEquiposreparados" onclick="rptordenesEquiposReparados()" class="small-box-footer">Click Reporte <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div> 
                    <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
                      <!-- small box -->
                      <div class="small-box bg-red"> 
                        <div class="inner">
                          <h4>En Espera!</h4>
                          <H2># <?php echo $num_ordenesespera; ?></H2>                         
                        </div>
                        <div class="icon">
                          <i class="fa fa-clock-o"></i>
                        </div>
                        <a data-toggle="modal" href="#myModalEquiposEspera" onclick="rptordenesEquiposESPERA()" class="small-box-footer">Click Reporte <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h4>Equipos Entregados!</h4>
                          <H2># <?php echo $num_ordenesentragado; ?></H2>                          
                        </div>
                        <div class="icon">
                          <i class="fa fa-car"></i>
                        </div>
                        <a data-toggle="modal" href="#myModalEquiposEntregados" onclick="rptordenesEquiposENTREGADOS()" class="small-box-footer">Click Reporte <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div>   
                    <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
                      <!-- small box -->
                      <div class="small-box bg-yellow">
                        <div class="inner">
                          <h4>Equipos Garantia!</h4>
                          <H2># <?php echo $num_ordenesgarantia; ?></H2>                           
                        </div>
                        <div class="icon">
                          <i class="fa fa-wrench"></i>
                        </div>
                        <a data-toggle="modal" href="#myModalEquiposGarantia" onclick="rptordenesEquiposGARANTIA()" class="small-box-footer">Click Reporte <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div> 
                    <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
                      <!-- small box -->
                      <div class="small-box bg-red">
                        <div class="inner">
                          <h4>Equipos sin Reparar!</h4>
                          <H2># <?php echo $num_ordenessinreparar; ?></H2>                           
                        </div>
                        <div class="icon">
                          <i class="fa fa-wrench"></i>
                        </div>
                        <a data-toggle="modal" href="#myModalEquiposSinReparar" onclick="rptordenesEquiposSINREPARAR()" class="small-box-footer">Click Reporte <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div> 
                    <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
                      <!-- small box -->
                      <div class="small-box bg-aqua">
                        <div class="inner">
                          <h4>Equipos Reparados!</h4>
                          <H2># <?php echo $num_ordenesreparadass; ?></H2>                           
                        </div>
                        <div class="icon">
                          <i class="fa fa-wrench"></i>
                        </div>
                        <a data-toggle="modal" href="#myModalEquiposREPARADOSS" onclick="rptordenesEquiposREPARADOSS()" class="small-box-footer">Click Reporte <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                      
                    </div>                     

                                                                                            

                    <div class=" with-border">
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <a data-toggle="modal" href="#myModalNuevaorden">           
                                <button id="btnAgregarArt" type="button" class="btn btn-primary btn-block"> <span class="fa fa-plus"></span> Agregar Artículos</button>
                          </a> 
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover display">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th>Cliente</th>
                              <th>T/Equipo</th>
                              <th>Marca</th>
                              <th>Modelo</th>
                              <th>Estado</th>
                              <th>F.Ingreso</th>
                            </tr>                 
                          </thead>
                          <tbody> 

                          </tbody>
                          <tfoot>
                            <tr>
                              <th>#</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th>Cliente</th>
                              <th>T/Equipo</th>
                              <th>Marca</th>
                              <th>Modelo</th>
                              <th>Estado</th>
                              <th>F.Ingreso</th>
                            </tr>                        
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">

                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
  
    </div><!-- /.content-wrapper -->  
  <!-- Modal -->
  <div class="modal fade" id="myModalNuevaorden" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 60% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/busqueda.png">Orden de Trabajo</h4>
        </div> 
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
        <div class="modal-body">
          <form name="formularioNuevaorden" id="formularioNuevaorden" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 tecnicoselect">
              <label>Tecnico(*):</label>
              <input type="hidden" name="idnueva_orden" id="idnueva_orden">              
              <select id="idtecnico" name="idtecnico" class="form-control selectpicker" data-live-search="true" ></select>
            </div>             
            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 clienteselect">
              <label>Cliente(*):</label>
              <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required>
              </select>  
              <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" style="display: none;">                              
            </div>  
            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <label>Imei:</label>
              <input type="text" class="form-control" name="imei_cel" id="imei_cel" maxlength="256" >
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 marcaselect">
              <label>Marca(*):</label>
              <select id="idmarca" name="idmarca" class="form-control selectpicker" data-live-search="true" required>
              </select>                             
            </div> 
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 modeloselect" >
              <label>Modelo(*):</label>
              <select id="idmodelo" name="idmodelo" class="form-control selectpicker" data-live-search="true" required>
              </select>                              
            </div> 
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 tipoequiposelect">
              <label>Tipo Equipo(*):</label>
              <select id="idtipo_equipo" name="idtipo_equipo" class="form-control selectpicker" data-live-search="true" required>
              </select>                             
            </div> 
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 coloresselect">
              <label>Color(*):</label>
              <select id="idcolor" name="idcolor" class="form-control selectpicker" data-live-search="true" required>
              </select>                             
            </div> 
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
              <label>Enciende(*):</label>
              <select name="enciende" id="enciende" class="form-control selectpicker" required="">
                <option value="NO">NO</option>
                <option value="SI">SI</option>
              </select> 
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
              <label>Golpes(*):</label>
              <select name="golpes" id="golpes" class="form-control selectpicker" required="">
                <option value="NO">NO</option>
                <option value="SI">SI</option>
              </select> 
            </div>   
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
              <label>Puerto Carga(*):</label>
              <select name="puerto_carga" id="puerto_carga" class="form-control selectpicker" required="">
                <option value="NO">NO</option>
                <option value="SI">SI</option>
              </select> 
            </div>   
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Password:</label>
              <input type="text" class="form-control" name="password_orden" id="password_orden" >
            </div>  
            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
              <textarea class="form-control" placeholder="Especifique la falla de equipo" id="falla_equipo" name="falla_equipo" ></textarea>
              <label for="floatingTextarea">Especifique la falla de equipo</label>
            </div> 
            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
              <textarea class="form-control" placeholder="Diagnóstico" id="diagnostico_equipo" name="diagnostico_equipo" ></textarea>
              <label for="floatingTextarea">Diagnóstico</label>
            </div> 
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
              <label>Presupuesto Q:</label>
              <input type="number" step="any" class="form-control" name="presupuesto" id="presupuesto" onchange="calculoar()">
            </div> 
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
              <label>Repuestos Q:</label>
              <input type="number" step="any" class="form-control" name="repuestos" id="repuestos" onchange="calculoar()">
            </div>  
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
              <label>Anticipo Q:</label>
              <input type="number" step="any" class="form-control" name="anticipo" id="anticipo" onchange="calculoar()" >  
            </div> 
            <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
              <label>Total Q:</label>
              <input type="number" step="any" class="form-control" name="total_orden" id="total_orden" readonly="">
            </div>               
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Código:</label>
              <input type="text" class="form-control" name="codigo_ordennueva" id="codigo_ordennueva" placeholder="Código Barras maximo 20 carac" maxlength="20">
              <button class="btn btn-success btn-block" type="button" onclick="generarbarcode()">Generar</button>
              <button class="btn btn-info btn-block" type="button" onclick="imprimir()">Imprimir</button>
              <div id="print">
                <svg id="barcode"></svg>
              </div>
            </div>                                                                                                                                                                          
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary btn-block" type="button" id="btnGuardarNuevaOrden"><i class="fa fa-save"></i> Guardar</button>
              <button class="btn btn-danger btn-block" onclick="cancelarformNuevaOrden()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </form>        
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
  <div class="modal fade" id="myModalTecnico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 30% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/cliente.png">Nuevo Tecnico</h4>
        </div> 
        <div class="modal-body">
          <form name="formulariotecnico" id="formulariotecnico" method="POST">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Nombre:</label>
              <input type="text" class="form-control" name="nombre_tecnico" id="nombre_tecnico" maxlength="50" placeholder="Nombre" required>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
              <label>Descripción:</label>
              <input type="text" class="form-control" name="descripcion_tecnico" id="descripcion_tecnico" maxlength="256" placeholder="Descripción">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
              <label>Comision %:</label>
              <input type="number" step="any" class="form-control" name="comision_tecnico" id="comision_tecnico" maxlength="10" >
            </div>                           

          </form>       
        </div>
        <div class="modal-footer">
           <button class="btn btn-primary btn-block" type="button" id="btnGuardarTecnico"><i class="fa fa-save"></i> Guardar</button>
          <button type="button" class="btn btn-default btn-block" onclick="cancelarformTecnico()"  data-dismiss="modal">Cerrar</button> 
        </div>        
      </div>
    </div>
  </div>   
  <!-- Fin modal -->   

   <!-- Modal -->
  <div class="modal fade" id="myModalCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" >
    <div class="modal-dialog" style="width: 30% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/cliente.png">Nuevo Cliente</h4>
        </div> 
        <div class="modal-body">
          <form name="formularioCliente" id="formularioCliente" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
              <label>Nombre:</label>
              <input type="hidden" name="tipo_persona_cliente" id="tipo_persona_cliente" value="Cliente">
              <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" maxlength="100" value="C/F" required>
            </div> 
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Tipo Documento:</label>
              <select class="form-control select-picker" name="tipo_documento_cliente" id="tipo_documento_cliente" required>
                <option value="NIT">NIT</option>
                <option value="DPI">DPI</option>
                <option value="PASAPORTE">PASAPORTE</option>
              </select>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Número Documento:</label>
              <input type="text" class="form-control" name="num_documento_cliente" id="num_documento_cliente" maxlength="20" value="C/F">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Dirección:</label>
              <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" maxlength="70" value="CIUDAD">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Teléfono:</label>
              <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" maxlength="20" value="0">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Email:</label>
              <input type="email" class="form-control" name="email_cliente" id="email_cliente" maxlength="50" value="soporte@gmail.com">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Tipo Cliente:</label>
              <select class="form-control select-picker" name="tipo_cliente_cliente" id="tipo_cliente_cliente" required>
                <option value="PUBLICO">Publico</option>
                <option value="DISTRIBUIDOR">Distribuidor</option>
                <option value="MAYORISTA">Mayorista</option>
                <option value="MENUDEO">Menudeo</option>
                <option value="PUBLICO">Publico</option>
              </select>
            </div>  
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary" type="button" id="btnGuardarCliente"><i class="fa fa-save"></i> Guardar</button>

              <button class="btn btn-danger" onclick="cancelarformCliente()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </form>       
        </div>
        <div class="modal-footer">
        </div>        
      </div>
    </div>
  </div>  
  <!-- Fin modal -->  

     <!-- Modal -->
  <div class="modal fade" id="myModalMarca" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" >
    <div class="modal-dialog" style="width: 30% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/cliente.png">Nueva Marca</h4>
        </div> 
        <div class="modal-body">
          <form name="formularioMarca" id="formularioMarca" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Codigo:</label>
              <input type="text" class="form-control" name="codigo_marca" id="codigo_marca" maxlength="50" placeholder="Cod" required>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Nombre:</label>
              <input type="text" class="form-control" name="nombre_marca" id="nombre_marca" maxlength="50" placeholder="Nombre" required>
            </div>                          
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Descripción:</label>
              <input type="text" class="form-control" name="descripcion_marca" id="descripcion_marca" maxlength="256" placeholder="Descripción">
            </div> 
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary btn-block" type="button" id="btnGuardarMarca"><i class="fa fa-save"></i> Guardar</button>
              <button class="btn btn-danger btn-block" onclick="cancelarformMarca()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </form>       
        </div>
        <div class="modal-footer">
        </div>        
      </div>
    </div>
  </div>  
  <!-- Fin modal -->

     <!-- Modal -->
  <div class="modal fade" id="myModalModelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" >
    <div class="modal-dialog" style="width: 30% !important;">
      <div class="modal-content">
        <div class="modal-header"> 
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/cliente.png">Nuevo Modelo</h4>
        </div> 
        <div class="modal-body">
          <form name="formularioModelo" id="formularioModelo" method="POST">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Nombre:</label>
              <input type="text" class="form-control" name="nombre_modelo" id="nombre_modelo" maxlength="50" placeholder="Nombre" required>
            </div>                          
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Descripción:</label>
              <input type="text" class="form-control" name="descripcion_modelo" id="descripcion_modelo" maxlength="256" placeholder="Descripción">
            </div> 
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary btn-block" type="button" id="btnGuardarModelo"><i class="fa fa-save"></i> Guardar</button>
              <button class="btn btn-danger btn-block" onclick="cancelarformModelo()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </form>       
        </div>
        <div class="modal-footer">
        </div>        
      </div>
    </div>
  </div>  
  <!-- Fin modal -->   
 
     <!-- Modal -->
  <div class="modal fade" id="myModalTipoequipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" >
    <div class="modal-dialog" style="width: 30% !important;">
      <div class="modal-content">
        <div class="modal-header"> 
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/cliente.png">Nuevo Tipo Equipo</h4>
        </div> 
        <div class="modal-body">
          <form name="formularioTipoequipo" id="formularioTipoequipo" method="POST">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Nombre:</label>
              <input type="text" class="form-control" name="nombre_tipoequipo" id="nombre_tipoequipo" maxlength="50" placeholder="Nombre" required>
            </div>                          
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Descripción:</label>
              <input type="text" class="form-control" name="descripcion_tipoequipo" id="descripcion_tipoequipo" maxlength="256" placeholder="Descripción">
            </div> 
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary btn-block" type="button" id="btnGuardarTipoequipo"><i class="fa fa-save"></i> Guardar</button>
              <button class="btn btn-danger btn-block" onclick="cancelarformModelo()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </form>       
        </div>
        <div class="modal-footer">
        </div>        
      </div>
    </div>
  </div>  
  <!-- Fin modal --> 

     <!-- Modal -->
  <div class="modal fade" id="myModalColores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" >
    <div class="modal-dialog" style="width: 30% !important;">
      <div class="modal-content">
        <div class="modal-header"> 
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/cliente.png">Nuevo Color</h4>
        </div> 
        <div class="modal-body">
          <form name="formularioColor" id="formularioColor" method="POST">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Nombre:</label>
              <input type="text" class="form-control" name="nombre_color" id="nombre_color" maxlength="50" placeholder="Nombre" required>
            </div>                          
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Descripción:</label>
              <input type="text" class="form-control" name="descripcion_color" id="descripcion_color" maxlength="256" placeholder="Descripción">
            </div> 
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary btn-block" type="button" id="btnGuardarColor"><i class="fa fa-save"></i> Guardar</button>
              <button class="btn btn-danger btn-block" onclick="cancelarformColor()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </form>       
        </div>
        <div class="modal-footer">
        </div>        
      </div>
    </div>
  </div>  
  <!-- Fin modal -->
  
     <!-- Modal -->
     <div class="modal modal-success fade" id="myModalCambiarestado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" >
      <div class="modal-dialog" style="width: 20% !important;">
        <div class="modal-content">
          <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/categoria.png">Cambiar Estado</h4>
          </div> 
          <div class="modal-body"> 
            <form name="formularioCambiarestado" id="formularioCambiarestado" method="POST">
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Cambiar Estado(*):</label>
                <input type="hidden" name="idnueva_orden_cambiar_estado" id="idnueva_orden_cambiar_estado">
                <select name="cambiar_estado" id="cambiar_estado" class="form-control selectpicker" required="">
                  <option value="EN REPARACION">EN REPARACION</option>
                  <option value="ESPERA">ESPERA</option>
                  <option value="ENTREGADO">ENTREGADO</option>
                  <option value="GARANTIA">GARANTIA</option> 
                  <option value="SIN REPARACION">SIN REPARACION</option>
                  <option value="REPARADOS">REPARADOS</option> 
                </select> 
              </div>  
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" name="div_cambiarEstado" id="div_cambiarEstado" >
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Forma pago(*):</label>
                  <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required="">
                   <option value="Efectivo">Efectivo</option>
                   <option value="Credito">Credito</option>
                 </select>
               </div>                  
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                  <label>Presupuesto Q:</label>
                  <input type="number" step="any" class="form-control" name="Entrega_presupuesto" id="Entrega_presupuesto"  >
                </div> 
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Repuestos Q:</label>
                  <input type="number" step="any" class="form-control" name="Entrega_repuestos" id="Entrega_repuestos" readonly="">
                </div>  
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Anticipo Q:</label>
                  <input type="number" step="any" class="form-control" name="Entrega_anticipo" id="Entrega_anticipo" readonly="">  
                </div> 
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>S/ Pendiente Q:</label>
                  <input type="number" step="any" class="form-control" name="Entrega_SaldoPendientexpagar" id="Entrega_SaldoPendientexpagar" value="0" onchange="calcularEntregaPresupuesto()">  
                </div>                                 
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Total Q:</label>
                  <input type="number" step="any" class="form-control" name="Entrega_total_orden" id="Entrega_total_orden" readonly="">
                </div>                  
              </div>
             

              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-primary btn-block" type="button" id="btnGuardarCambiarestado"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger btn-block" onclick="cancelarformCambiarestado()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              </div>
            </form>       
          </div>
          <div class="modal-footer">
          </div>        
        </div>
      </div>
    </div>   

    <div class="modal fade" id="myModalEquiposreparados" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/busqueda.png">Reporte de Equipos en Reparacion </h4>

          </div>
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
            <div class="modal-body">
              <table id="tblarticulosequiposreparados" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th># Transac</th>
                  <th>Cliente</th>
                  <th># Cod/Orden</th>
                  <th>T/Equipo</th>
                  <th>Marca</th>
                  <th>Falla</th>
                  <th>Diagnostico</th>
                  <th>Presupuesto</th>
                  <th>Repuestos</th>
                  <th>Anticipo</th>
                  <th>Total/Or</th>
                  <th>User Add</th>
                  <th>User Update</th>
                  <th>Fecha/Modi</th>
                  <th>Fecha/Crea</th>
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

    <div class="modal fade" id="myModalEquiposEspera" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/busqueda.png">Reporte de Equipos En Espera </h4>
          </div>
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
            <div class="modal-body">
              <table id="tblarticulosequiposEspera" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th># Transac</th>
                  <th>Cliente</th>
                  <th># Cod/Orden</th>
                  <th>T/Equipo</th>
                  <th>Marca</th>
                  <th>Falla</th>
                  <th>Diagnostico</th>
                  <th>Presupuesto</th>
                  <th>Repuestos</th>
                  <th>Anticipo</th>
                  <th>Total/Or</th>
                  <th>User Add</th>
                  <th>User Update</th>
                  <th>Fecha/Modi</th>
                  <th>Fecha/Crea</th>
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

    <div class="modal fade" id="myModalEquiposEntregados" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/busqueda.png">Reporte de Equipos Entregados </h4>
          </div>
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
            <div class="modal-body">
              <table id="tblarticulosequiposEntregados" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th># Transac</th>
                  <th>Cliente</th>
                  <th># Cod/Orden</th>
                  <th>T/Equipo</th>
                  <th>Marca</th>
                  <th>Falla</th>
                  <th>Diagnostico</th>
                  <th>Presupuesto</th>
                  <th>Repuestos</th>
                  <th>Anticipo</th>
                  <th>Total/Or</th>
                  <th>User Add</th>
                  <th>User Update</th>
                  <th>Fecha/Modi</th>
                  <th>Fecha/Crea</th>
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

    <div class="modal fade" id="myModalEquiposGarantia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/busqueda.png">Reporte de Equipos Garantia </h4>
          </div>
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
            <div class="modal-body">
              <table id="tblarticulosequiposGarantia" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th># Transac</th>
                  <th>Cliente</th>
                  <th># Cod/Orden</th>
                  <th>T/Equipo</th>
                  <th>Marca</th>
                  <th>Falla</th>
                  <th>Diagnostico</th>
                  <th>Presupuesto</th>
                  <th>Repuestos</th>
                  <th>Anticipo</th>
                  <th>Total/Or</th>
                  <th>User Add</th>
                  <th>User Update</th>
                  <th>Fecha/Modi</th>
                  <th>Fecha/Crea</th>
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


    <div class="modal fade" id="myModalEquiposSinReparar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/busqueda.png">Reporte de Equipos Sin Reparar </h4>
          </div>
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
            <div class="modal-body">
              <table id="tblarticulosequiposSinreparar" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th># Transac</th>
                  <th>Cliente</th>
                  <th># Cod/Orden</th>
                  <th>T/Equipo</th>
                  <th>Marca</th>
                  <th>Falla</th>
                  <th>Diagnostico</th>
                  <th>Presupuesto</th>
                  <th>Repuestos</th>
                  <th>Anticipo</th>
                  <th>Total/Or</th>
                  <th>User Add</th>
                  <th>User Update</th>
                  <th>Fecha/Modi</th>
                  <th>Fecha/Crea</th>
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


    <div class="modal fade" id="myModalEquiposREPARADOSS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><img class="iconos-cambio" src="../public/iconos/busqueda.png">Reporte de Equipos Reparados </h4>
          </div>
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
            <div class="modal-body">
              <table id="tblarticulosequiposReparadoss" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th># Transac</th>
                  <th>Cliente</th>
                  <th># Cod/Orden</th>
                  <th>T/Equipo</th>
                  <th>Marca</th>
                  <th>Falla</th>
                  <th>Diagnostico</th>
                  <th>Presupuesto</th>
                  <th>Repuestos</th>
                  <th>Anticipo</th>
                  <th>Total/Or</th>
                  <th>User Add</th>
                  <th>User Update</th>
                  <th>Fecha/Modi</th>
                  <th>Fecha/Crea</th>
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
<script type="text/javascript" src="scripts/admin_ordenes.js"></script>  
<!-- jQuery -->



  

<?php  
} 
ob_end_flush();
?>