<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['crear_clientes'] == 1) {
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
              <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="row">
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label>Nombre Facturacion :</label>
                      <input type="hidden" name="idpersona" id="idpersona">
                      <input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del cliente" required>
                      </div>
                    </div>
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label>Nombre Comercial :</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" name="nombre_comercial" id="nombre_comercial" maxlength="100" placeholder="Nombre del cliente" required>
                      </div>
                    </div>
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label>Tipo Documento:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
                        <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                          <option value="DPI">DPI</option>
                          <option value="NIT">NIT</option>
                          <option value="PASAPORTE">PASAPORTE</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label>Número Documento:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                        <input type="text" class="form-control" onchange="validarnit()" name="num_documento" id="num_documento" maxlength="20" value="CF" autofocus="autofocus">
                        <span class="input-group-btn">
                          <button class="btn btn-danger" onclick="validarnit()" type="button">
                            <i class="fa fa-search"></i> Validar
                          </button>
                        </span>
                      </div>
                    </div>
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label>Dirección Fiscal:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                        <input type="text" class="form-control" name="direccion" id="direccion" maxlength="250" placeholder="Dirección completa">
                      </div>
                    </div>
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label>Dirección Comercial:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                        <input type="text" class="form-control" name="direccion_comercial" id="direccion_comercial" maxlength="250" placeholder="Dirección completa">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Teléfono:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono">
                      </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Email:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Correo electrónico">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label>Trabajo:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                        <input type="text" class="form-control" name="trabajo" id="trabajo" maxlength="50" placeholder="Lugar de Trabajo">
                      </div>
                    </div>
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label for="selectFiador">Sector:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                        <select id="idsector" name="idsector" class="form-control selectpicker" data-live-search="true" title="Seleccionar Sector"></select>
                        <span class="input-group-btn">
                          <button class="btn btn-success" type="button" onclick="mostrarModalSector()" title="Agregar nuevo sector">
                            <i class="fa fa-plus"></i>
                          </button>
                        </span>
                      </div>
                    </div>
                    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <label for="selectFiador">Ruta de Visita:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                        <select id="idruta" name="idruta" class="form-control selectpicker" data-live-search="true" title="Seleccionar Ruta"></select>
                        <span class="input-group-btn">
                          <button class="btn btn-success" type="button" onclick="mostrarModalRuta()" title="Agregar nueva ruta">
                            <i class="fa fa-plus"></i>
                          </button>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                      <label>Tipo Cliente:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-users"></i></span>
                        <select class="form-control select-picker" name="tipo_cliente" id="tipo_cliente" required>
                          <option value="DISTRIBUIDOR">Distribuidor</option>
                          <option value="MAYORISTA">Mayorista</option>
                          <option value="TALLER">Taller</option>
                          <option value="PUBLICO">Publico</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                      <label>Codigo Cliente:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input type="text" class="form-control" name="codigo_cliente" id="codigo_cliente" maxlength="50" placeholder="0" readonly>
                      </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                      <label>Ubicación:</label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="ubicacioncliente" id="ubicacioncliente" maxlength="250" placeholder="14.638431166815879, -90.52727727236417">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-success" id="btnCapturarUbicacion" onclick="capturarUbicacion()" title="Capturar Ubicación">
                            <i class="fa fa-location-arrow"></i>
                          </button>
                          <a id="verEnMapa" class="btn btn-primary" href="#" target="_blank" style="display: none;" title="Ver en Mapa">
                            <i class="fa fa-map"></i>
                          </a>
                        </span>
                      </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                      <label>Descuento Cliente:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        <input type="text" class="form-control" name="descuento_cliente" id="descuento_cliente" maxlength="10" placeholder="0" value="0">
                      </div>
                    </div>
                  </div>

                  <div class="row" style="margin-top: 20px;">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                      <button class="btn btn-primary" type="submit" id="btnGuardar" style="margin-right: 10px; min-width: 150px;"><i class="fa fa-save"></i> Guardar</button>
                      <button class="btn btn-danger" onclick="cancelarform()" type="button" style="min-width: 150px;"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

    <div class="modal fade" id="modalSector" tabindex="-1" role="dialog" aria-labelledby="modalSectorLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modalSectorLabel">Agregar Nuevo Sector</h4>
          </div>
          <div class="modal-body">
            <form id="formularioSector">
              <div class="form-group">
                <label for="nombre_sector">Nombre del Sector:</label>
                <input type="text" class="form-control" name="nombre_sector" id="nombre_sector" maxlength="50" placeholder="Ej: Zona 1" required>
              </div>
              <div class="form-group">
                <label for="descripcion_sector">Descripción:</label>
                <input type="text" class="form-control" name="descripcion_sector" id="descripcion_sector" maxlength="256" placeholder="Descripción breve">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="guardarSector()"><i class="fa fa-save"></i> Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalRuta" tabindex="-1" role="dialog" aria-labelledby="modalRutaLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modalRutaLabel">Agregar Nueva Ruta de Visita</h4>
          </div>
          <div class="modal-body">
            <form id="formularioRuta">
              <div class="form-group">
                <label for="nombre_ruta">Nombre de la Ruta:</label>
                <input type="text" class="form-control" name="nombre_ruta" id="nombre_ruta" maxlength="50" placeholder="Ej: Ruta Norte" required>
              </div>
              <div class="form-group">
                <label for="descripcion_ruta">Descripción:</label>
                <input type="text" class="form-control" name="descripcion_ruta" id="descripcion_ruta" maxlength="256" placeholder="Descripción breve">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="guardarRuta()"><i class="fa fa-save"></i> Guardar</button>
          </div>
        </div>
      </div>
    </div>

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
  } else {
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