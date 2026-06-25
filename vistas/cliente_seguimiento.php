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
if ($_SESSION['clientes_seguimiento']==1)
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
                          Clientes  
                          <small>Seguimiento</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Clientes</li>
                          </ol>

                          </section>
                        </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Número</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Direccion</th>
                            <th>Codigo</th>
                            <th>Tipo Cliente</th>
                            <th>Condicion</th>
                            <th>Ubicacion</th>
                            <th>T-Vendido</th>
                            <th>F-Ultima Venta</th>
                            <th>F-Ultimo Seguimiento</th>
                            <th>F-Ultima Tarea</th>
                            <th>F-Ultimo Evento</th>
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
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btn-block" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
                            <button class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<!-- MODAL PARA EL SEGUIMIENTO DEL CLIENTE -->
   <div class="modal fade" id="myModalSeguimiento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <form name="formulario_seguimiento" id="formulario_seguimiento" method="POST">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Tipo:</label>
              <input type="hidden" name="idseguimiento" id="idseguimiento">
              <input type="hidden" name="idcliente_seguimiento" id="idcliente_seguimiento">
              <select name="tipo_seguimiento" id="tipo_seguimiento" class="form-control selectpicker" data-live-search="true" required="">
                <option value="SELECCIONE">SELECCIONA UNA OPCION</option>
                <option value="LLAMADA">LLAMADA</option>
                <option value="WHATSAPP">WHATSAPP</option>
                <option value="EMAIL">EMAIL</option>
                <option value="VISITA">VISITA</option>
                <option value="REUNIÓN">REUNIÓN</option>
                <option value="COTIZACIÓN">COTIZACIÓN</option>
                <option value="SEGUIMIENTO">SEGUIMIENTO</option>
                <option value="RECLAMO">RECLAMO</option>
                <option value="SOPORTE">SOPORTE</option>
                <option value="VENTA CERRADA">VENTA CERRADA</option>
                <option value="NO INTERESADO">NO INTERESADO</option>
                <option value="REFERIDO">REFERIDO</option>
              </select>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Notas:</label>
              <textarea class="form-control" name="notas_seguimiento" id="notas_seguimiento" rows="5" cols="50"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="button" id="btnGuardarSeguimiento"><i class="fa fa-save"></i> Guardar Datos Seguimiento</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> 
           
        </div>        
    </div>
  </div>
</div>
<!-- MODAL PARA EL SEGUIMIENTO DEL CLIENTE -->

<!-- MODAL PARA TAREAS CLIENTE -->
   <div class="modal fade" id="myModalTareas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <form name="formulario_tareas" id="formulario_tareas" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Titulo:</label>
              <input type="hidden" name="idtarea" id="idtarea">
              <input type="hidden" name="idcliente_tarea" id="idcliente_tarea">
              <input type="text" class="form-control" name="tarea_titulo" id="tarea_titulo">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Descripción:</label>
              <input type="text" class="form-control" name="tarea_desc" id="tarea_desc">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Fecha Limite:</label>
              <input type="date" class="form-control" name="tarea_fecha_limite" id="tarea_fecha_limite">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Prioridad:</label>
              <select name="tarea_prioridad" id="tarea_prioridad" class="form-control selectpicker" data-live-search="true" required="">
                <option value="SELECCIONE">SELECCIONA UNA OPCION</option>
                <option value="BAJA">BAJA</option>
                <option value="MEDIA">MEDIA</option>
                <option value="ALTA">ALTA</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="button" id="btnGuardarTareas"><i class="fa fa-save"></i> Guardar Datos Tareas</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> 
           
        </div>        
    </div>
  </div>
</div>
<!-- MODAL PARA TAREAS CLIENTE -->

<!-- MODAL PARA EVENTOS CLIENTE -->
   <div class="modal fade" id="myModalEventos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <form name="formulario_eventos" id="formulario_eventos" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Titulo:</label>
              <input type="hidden" name="idevento" id="idevento">
              <input type="hidden" name="idcliente_evento" id="idcliente_evento">
              <input type="text" class="form-control" name="evento_titulo" id="evento_titulo">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Descripción:</label>
              <input type="text" class="form-control" name="evento_desc" id="evento_desc">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Fecha Evento:</label>
              <input type="date" class="form-control" name="evento_fecha_evento" id="evento_fecha_evento">
            </div>
            <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
              <label>Ubicación:</label>
              <div class="input-group">
                <input type="text" class="form-control" name="evento_ubicacion" id="evento_ubicacion" maxlength="250" placeholder="Ubicación no definida" readonly>
                <button type="button" class="btn btn-success" id="btnCapturarUbicacion" onclick="capturarUbicacion()">
                  <i class="fa fa-map-marker"></i> Capturar Ubicación
                </button>
                <a id="verEnMapa" class="btn btn-primary" href="#" target="_blank" style="display: none;">
                  <i class="fa fa-map"></i> Ver en Google Maps
                </a>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="button" id="btnGuardarEventos"><i class="fa fa-save"></i> Guardar Datos Eventos</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> 
           
        </div>        
    </div>
  </div>
</div>
<!-- MODAL PARA EVENTOS CLIENTE -->
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/cliente_seguimiento.js"></script>
<?php 
}
ob_end_flush();
?>