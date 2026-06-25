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
if ($_SESSION['crear_clientes']==1)
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
                          <small>Bitacora</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Clientes</li>
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
                            <th>Descuento</th>
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
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label>Nombre:</label>
                            <input type="hidden" name="idpersona" id="idpersona">
                            <input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del proveedor" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tipo Documento:</label>
                            <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                              <option value="DPI">DPI</option>
                              <option value="NIT">NIT</option>
                              <option value="PASAPORTE">PASAPORTE</option>
                            </select>
                          </div>
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label>Número Documento:</label>
                            <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" placeholder="Documento">
                          </div>
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label>Dirección:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" maxlength="70" placeholder="Dirección">
                          </div>
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label>Teléfono:</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono">
                          </div>
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                          </div>
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label>Trabajo:</label>
                            <input type="text" class="form-control" name="trabajo" id="trabajo" maxlength="50" placeholder="Trabajo">
                          </div>
                          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="selectFiador">Sector:</label>
                            <select id="idsector" name="idsector" class="form-control selectpicker" data-live-search="true"></select>
                          </div>
                           <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Tipo Cliente:</label>
                            <select class="form-control select-picker" name="tipo_cliente" id="tipo_cliente" required>
                              <option value="DISTRIBUIDOR">Distribuidor</option>
                              <option value="MAYORISTA">Mayorista</option>
                              <option value="TALLER">Taller</option>
                              <option value="PUBLICO">Publico</option>
                            </select>
                          </div> 
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Codigo Cliente:</label>
                            <input type="text" class="form-control" name="codigo_cliente" id="codigo_cliente" maxlength="50" placeholder="0">
                          </div>   


                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <label>Ubicación:</label>
                          <div class="input-group">
                            <input type="text" class="form-control" name="ubicacioncliente" id="ubicacioncliente" maxlength="250" placeholder="Ubicación no definida" readonly>
                            <button type="button" class="btn btn-success" id="btnCapturarUbicacion" onclick="capturarUbicacion()">
                              <i class="fa fa-map-marker"></i> Capturar Ubicación
                            </button>
                            <a id="verEnMapa" class="btn btn-primary" href="#" target="_blank" style="display: none;">
                              <i class="fa fa-map"></i> Ver en Google Maps
                            </a>
                          </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Descuento Cliente:</label>
                            <input type="text" class="form-control" name="descuento_cliente" id="descuento_cliente" maxlength="10" placeholder="0" value="0">
                          </div>                          
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

<div class="modal fade" id="modalFiador" tabindex="-1" role="dialog" aria-labelledby="modalFiadorLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFiadorLabel">Seleccionar Fiador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" name="idpersona_fiador" id="idpersona_fiador">
          <label for="selectFiador">Elige un fiador:</label>
          <select id="idfiador" name="idfiador" class="form-control selectpicker" data-live-search="true" required></select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnSeleccionarFiador" onclick="guardar_fiador()">Seleccionar</button>
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
<script type="text/javascript" src="scripts/cliente.js"></script>
<?php 
}
ob_end_flush();
?>