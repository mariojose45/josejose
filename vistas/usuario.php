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
if ($_SESSION['acceso_usuarios']==1)  
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
                          Usuario  
                          <small>Accesos</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Usuario</li>
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
                            <th>Login</th>
                            <th>Comision</th>
                            <th>Meta</th>
                            <th>Foto</th>
                            <th>Estado</th>
                            <th>Sucursales</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Número</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Login</th>
                            <th>Comision</th>
                            <th>Meta</th>
                            <th>Foto</th>
                            <th>Estado</th>
                            <th>Sucursales</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Nombre(*):</label>
                            <input type="hidden" name="idusuario" id="idusuario">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                          </div>
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Tipo Documento(*):</label>
                            <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                              <option value="DNI">DPI</option>
                              <option value="RUC">NIT</option>
                              <option value="CEDULA">PASAPORTE</option>
                            </select>
                          </div>
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Número(*):</label>
                            <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" placeholder="Documento" required>
                          </div>
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Dirección:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección" maxlength="70">
                          </div>
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Teléfono:</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono">
                          </div>
                          <div class="form-group col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                          </div>
                          <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>Cargo:</label>
                            <select name="cargo" id="cargo" class="form-control selectpicker" required="">
                            <option value=".">SELECCION UN ROL</option>  
                            <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                              <option value="VENDEDOR">VENDEDOR</option>
                              <option value="SUPERVISOR">SUPERVISOR</option>
                              <option value="BODEGA">BODEGA</option>
                            </select>                            
                          </div>                           
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Login (*):</label>
                            <input type="text" class="form-control" name="login" id="login" maxlength="20" placeholder="Login" required>
                          </div> 
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Clave (*):</label>
                            <input type="password" class="form-control" name="clave" id="clave" maxlength="64" placeholder="Clave" required>
                          </div>
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Comision % (*):</label>
                            <input type="text" class="form-control" name="comision" id="comision">
                          </div> 
                          <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Meta:</label>
                            <input type="text" class="form-control" name="meta" id="meta">
                          </div>                                                    
                          <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>Permisos:</label>
                            <ul style="list-style: none;" id="permisos">
                               
                            </ul>
                          </div>
                          <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>Sucursales Asignadas:</label>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <ul style="list-style: none; padding: 0; margin: 0;" id="sucursales" class="list-group">
                                       
                                    </ul>
                                </div>
                            </div>
                          </div>                          
 
                          <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label>Imagen:</label>
                            <input type="file" class="form-control" name="imagen" id="imagen">
                            <input type="hidden" name="imagenactual" id="imagenactual">
                            <img src="" width="150px" height="120px" id="imagenmuestra">
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

    <div class="modal fade" id="myModalSucursales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Sucursales Asignadas</h4>
          </div>
          <div class="modal-body" style="width: 0px;width: -webkit-fill-available;">
            <table id="tblsucursales" class="table table-striped table-bordered table-condensed table-hover" style="width: 0px;width: -webkit-fill-available;">
              <thead>
                  <th>Nombre Sucursal</th>
                  <th>Dirección</th>
              </thead>
              <tbody> 
                
              </tbody>
              <tfoot>
                  <th>Nombre Sucursal</th>
                  <th>Dirección</th>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>        
        </div>
      </div>
    </div>

    <div class="modal fade" id="myModalCambiarClave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 20% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Cambiar Clave</h4>
          </div>
          <div class="modal-body" style="width: 0px;width: -webkit-fill-available;">
            <form name="formularioClave" id="formularioClave" method="POST">
              <div class="form-group">
                <label>Nueva Clave:</label>
                <input type="hidden" name="idusuarioClave" id="idusuarioClave">
                <input type="password" class="form-control" name="nueva_clave" id="nueva_claveClave" placeholder="Nueva Clave" required>
              </div>
            </form>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btnGuardarClave"><i class="fa fa-save"></i> Guardar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>        
        </div>
      </div>
    </div>    
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
<script type="text/javascript" src="scripts/usuario.js"></script>
<?php 
}
ob_end_flush();
?>