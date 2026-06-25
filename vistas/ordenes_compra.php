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
  if ($_SESSION['compras_ordenes_compra'] == 1) {
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
                    Ordenes de
                    <small>Compras</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Compras</li>
                  </ol>

                </section>
                <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> </h1>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Fecha Inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Fecha Fin</label>
                  <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>">
                  <button class="btn btn-success btn-block" onclick="listar()">Generar Ingresos</button>
                </div>
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th># Orden</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Total Compra</th>
                    <th>Estado</th>
                    <th>Estado Orden de Compra</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th># Orden</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Total Compra</th>
                    <th>Estado</th>
                    <th>Estado Orden de Compra</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 1000px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label> <img class="iconos-tama" src="../public/iconos/proveedor.png"> Proveedor(*):</label>
                    <input type="hidden" name="idorden_compra" id="idorden_compra">
                    <input type="hidden" name="datos1" id="datos1">
                    <div style="display: flex; gap: 10px; width: 100%;">
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="codigo_cliente" id="codigo_cliente" maxlength="20" placeholder="CODIGO">
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
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" maxlength="256" value="CONSUMIDOR FINAL"
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

                    </div>
                    <div style="display: flex; gap: 10px; width: 100%;">
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" maxlength="256" value="CIUDAD">
                      </div>
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="correo_cliente" id="correo_cliente" maxlength="256" value="soporte@gmail.com">
                      </div>
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" maxlength="256" value="0">
                      </div>
                    </div>
                    <div style="display: flex; gap: 10px; width: 100%;">
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="idcliente" id="idcliente" value="1" readonly="">
                      </div>
                      <div class="input-group" style="flex: 1;">
                        <select class="form-control select-picker" name="tipo_documento_cliente" id="tipo_documento_cliente" required>
                          <option value="NIT">NIT</option>
                          <option value="DPI">DPI</option>
                          <option value="PASAPORTE">PASAPORTE</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      <label>Sucursal Origen(*):</label>
                      <select id="idsucursalOrigen" name="idsucursalOrigen" class="form-control selectpicker" data-live-search="true">

                      </select>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      <label>Articulos(*):</label>
                      <button id="btnAgregarArt" name="btnAgregarArt" type="button" onclick="listarArticulos()" class="btn btn-primary btn-block"> <span class="fa fa-plus"></span> Agregar Artículos</button>

                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label>Busqueda Codigo de Barra:</label>
                      <input type="text" id="txtbusquedaartcodebar" class="form-control" placeholder="Click para Gestionar las lecturas con pistola" autofocus="autofocus">
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>Tipo Ingreso(*):</label>
                      <select name="tipo_ingreso_producion" id="tipo_ingreso_producion" class="form-control selectpicker" required="">
                        <option value="Producto">Producto</option>
                        <option value="Materia">Materia Prima</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>Fecha(*):</label>
                      <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                    </div>
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>T-Doc(*):</label>
                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                        <option value="Boleta">Boleta</option>
                        <option value="Factura">Factura</option>
                        <option value="Ticket">Ticket</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-4 col-md-2 col-sm-6 col-xs-12">
                      <label>Serie:</label>
                      <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="50" placeholder="Serie">
                    </div>
                    <div class="form-group col-lg-4 col-md-2 col-sm-6 col-xs-12">
                      <label>Número:</label>
                      <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="50" placeholder="Número">
                    </div>
                    <div class="form-group col-lg-4 col-md-2 col-sm-6 col-xs-12">
                      <label>Impuesto:</label>
                      <input type="text" class="form-control" name="impuesto" id="impuesto" value="0">
                    </div>
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>T PC:</label>
                      <h2 id="total">Q/. 0.00</h2>
                    </div>
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>T PC. DES:</label>
                      <h2 id="totaldes">Q/. 0.00</h2>
                    </div>
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <button class="btn btn-primary btn-block" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                      <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>

                    </div>
                  </div>


                  <!-- 
                            TABLA DE DETALLES
                          -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>.</label>
                    <input type="hidden" class="form-control">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Forma Pago(*):</label>
                    <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required=" Ingrese la forma de pago">
                      <option value="Efectivo">Efectivo</option>
                      <option value="Cheque">Cheque</option>
                      <option value="Credito">Credito</option>
                      <option value="Transferencia">Transferencia</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Dias:</label>
                    <input type="text" class="form-control" placeholder="dd" value="0" name="dias_credito" id="dias_credito" onchange="calculardiascredito()">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Fecha Pago Credito(*):</label>
                    <input type="text" class="form-control" name="fecha_hora_pago_credito" id="fecha_hora_pago_credito">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>direccion de Entrega:</label>
                    <input type="text" class="form-control" placeholder="Direccion Entrega" name="direccion_entrega_orden_compra" id="direccion_entrega_orden_compra">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Fecha Entrega(*):</label>
                    <input type="date" class="form-control" name="fecha_entrega_orden_compra" id="fecha_entrega_orden_compra" required="">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Observaciones:</label>
                    <input type="text" class="form-control" placeholder="Observaciones" name="observacion_orden_compra" id="observacion_orden_compra">
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                  </div>
                  <input type="hidden" name="total_compra_r" id="total_compra_r">
                  <input type="hidden" name="total_comprades_r" id="total_comprades_r">
                </form>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color:#A9D0F5">
                      <th>Opciones</th>
                      <th>Artículo</th>
                      <th>F/Vencimiento</th>
                      <th>Cantidad</th>
                      <th>Presentacion</th>
                      <th>P.C.</th>
                      <th>Desc %</th>
                      <th>P.V.</th>
                      <th>P.V.NOC</th>
                      <th>Rango 1/Mecanico</th>
                      <th>Rango 1/Distribuidor</th>
                      <th>Rango 1/Mayorista</th>
                      <th>Rango 2/Mecanico</th>
                      <th>Rango 2/Distribuidor</th>
                      <th>Rango 2/Mayorista</th>
                      <th>Rango 3/Mecanico</th>
                      <th>Rango 3/Distribuidor</th>
                      <th>Rango 3/Mayorista</th>
                      <th>P/1</th>
                      <th>P/2</th>
                      <th>P/3</th>
                      <th>P/4</th>
                      <th>P/5</th>
                      <th>P/6</th>
                      <th>P/7</th>
                      <th>P/8</th>
                      <th>P/9</th>
                      <th>P/10</th>
                      <th>P/11</th>
                      <th>P/12</th>
                      <th>P/13</th>
                      <th>P/14</th>
                      <th>P/15</th>
                      <th>P/16</th>
                      <th>P/17</th>
                      <th>P/18</th>
                      <th>P/19</th>
                      <th>P/20</th>
                      <th>Subtotal</th>
                      <th>Subtotal Des</th>
                      <th></th>
                    </thead>
                    <tfoot>
                      <th>TOTAL</th>
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

                        <input type="hidden" name="total_compra" id="total_compra">
                      </th>
                      <th>

                        <input type="hidden" name="total_comprades" id="total_comprades">
                      </th>
                      <th></th>
                    </tfoot>
                    <tbody>

                    </tbody>
                  </table>
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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione un Artículo</h4>
          </div>

          <div class="modal-body">
            <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock</th>
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
                <th>Imagen</th>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal -->

    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="myModalBusquedacliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h4><img class="iconos-cambio" src="../public/iconos/busqueda.png">BUSQUE UN PROVEEDOR</h4>
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


  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript">
    // Define la variable de clave desde la sesión
    const claveingresos = "<?php echo htmlspecialchars($_SESSION['claveingresos'], ENT_QUOTES, 'UTF-8'); ?>";
  </script>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/ordenes_compra.js"></script>
<?php
}
ob_end_flush();
?>