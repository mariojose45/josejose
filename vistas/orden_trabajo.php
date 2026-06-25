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
                        <div class="callout callout-info">
                          <h4>Modulo de Ordenes!</h4>
                          En este Modulo Podras Crear, Editar, Listar, Buscar y Desactivar </a>
                        </div>
                    <div class="box-header with-border">
                          <h1 class="box-title">Orden <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th># Orden</th>
                            <th>Cliente</th>
                            <th>Telefono</th>
                            <th>Fecha</th>
                            <th>Modelo</th>
                            <th>Serie</th>
                            <th>Usuario</th>
                            <th>Detalle Reparacion</th>
                            <th>Status</th>
                            <th>Tecnico</th>
                            <th>Feha Detalle Tecnico</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th># Orden</th>
                            <th>Cliente</th>
                            <th>Telefono</th>
                            <th>Fecha</th>
                            <th>Modelo</th>
                            <th>Serie</th>
                            <th>Usuario</th>
                            <th>Detalle Reparacion</th>
                            <th>Status</th>
                            <th>Tecnico</th>
                            <th>Feha Detalle Tecnico</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <label>Cliente(*):</label>
                            <input type="hidden" name="idorden" id="idorden">
                            <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required>
                               
                            </select> 
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha Creacion:</label>
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora"  required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Modelo:</label>
                            <input type="text" class="form-control" name="modelo" id="modelo" maxlength="50" placeholder="Descripción">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Serie:</label>
                            <input type="text" class="form-control" name="serie" id="serie" maxlength="256" placeholder="Descripción">
                          </div>                          
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Descripción Equipo:</label>
                            <input type="text" class="form-control" name="descripcion_equipo" id="descripcion_equipo" maxlength="256" placeholder="Descripción">
                          </div>  
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Reparacion Equipo:</label>
                            <input type="text" class="form-control" name="reparacion_equipo" id="reparacion_equipo" maxlength="256" placeholder="Descripción">
                          </div>                                                                              
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
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

   <!-- Modal -->
  <div class="modal fade" id="ModalDetalleTecnico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 50% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Agregar Detalle de Tecnico</h4>
        </div> 
        <div class="modal-body">
        <form name="formulario3" id="formulario3" method="POST">

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Detalle Tecnico:</label>
                            <input type="hidden" name="idorden2" id="idorden2">
                            <input type="text" class="form-control" name="detalle_tecnico2" id="detalle_tecnico2" maxlength="250" >
                          </div>
                            <div class="form-group col-lg-4 col-md-64col-sm-4 col-xs-12">
                              <label>Status(*):</label>
                              <select name="tipo_status" id="tipo_status" class="form-control selectpicker" >
                                 <option value="Diagnostico">Diagnostico</option>
                                 <option value="Reparado">Reparado</option>
                                 <option value="No Reparado">No Reparado</option>
                              </select>
                            </div> 
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Tecnico(*):</label>
                              <select name="tecnico" id="tecnico" class="form-control selectpicker" >
                                 <option value="Brayan">Brayan</option>
                                 <option value="Alvaro">Alvaro</option>
                                 <option value="Yuri">Yuri</option>
                              </select>
                            </div>  
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha Detalle:</label>
                            <input type="date" class="form-control" name="fecha_hora_detalle" id="fecha_hora_detalle"  >
                          </div>                                                                               

 
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="button" id="btnGuardar3"><i class="fa fa-save"></i> Guardar</button>
 
                           <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cerrar</button> 
                          </div>
        </form>       
        </div>
        <div class="modal-footer">
          
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
<script type="text/javascript" src="scripts/orden_trabajo.js"></script>  

<?php  
} 
ob_end_flush();
?>