<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['compras_gastos'] == 1) {

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
                    Gastos
                    <small>Compras</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Compras</li>
                  </ol>

                </section>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Serie Fac</th>
                    <th># Fac</th>
                    <th>Mes</th>
                    <th>Año</th>
                    <th>Tipo Factura</th>
                    <th>Nit #</th>
                    <th>Proveedor</th>
                    <th>Valor Q</th>
                    <th>Tipo Compra</th>
                    <th>Usuario</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Fecha</th>
                    <th>Serie Fac</th>
                    <th># Fac</th>
                    <th>Mes</th>
                    <th>Año</th>
                    <th>Tipo Factura</th>
                    <th>Nit #</th>
                    <th>Proveedor</th>
                    <th>Valor Q</th>
                    <th>Tipo Compra</th>
                    <th>Usuario</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Serie No.:</label>
                    <input type="hidden" name="idcompra" id="idcompra">
                    <input type="hidden" name="idcliente_GastoaVenta" id="idcliente_GastoaVenta" value="1" readonly="">
                    <input type="text" class="form-control" name="serie_no" id="serie_no" maxlength="256"
                      placeholder="0000">
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Factura No.:</label>
                    <input type="text" class="form-control" name="factura_no" id="factura_no" maxlength="256"
                      placeholder="000">
                  </div>

                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Mes a Contabilizar (*):</label>
                    <select name="mes_a_contabilizar" id="mes_a_contabilizar" class="form-control selectpicker"
                      data-live-search="true" required="">
                      <!-- Las opciones se generarán dinámicamente con JavaScript -->
                    </select>
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Año a Contabilizar (*):</label>
                    <select name="ano_contabilizar" id="ano_contabilizar" class="form-control selectpicker"
                      data-live-search="true" required="">
                      <!-- Las opciones se generan dinámicamente con JavaScript -->
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Tipo de Factura(*):</label>
                    <select name="tipo_factura" id="tipo_factura" class="form-control selectpicker" data-live-search="true"
                      required="">
                      <option value="Grabada">Grabada</option>
                      <option value="Exenta">Exenta</option>
                      <option value="Pequeño Contribuyente">Pequeño Contribuyente</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Tipo Doc Proveedor.:</label>
                    <select class="form-control select-picker" name="tipo_documento_cliente_GastoaVenta"
                      id="tipo_documento_cliente_GastoaVenta" required>
                      <option value="NIT">NIT</option>
                      <option value="DPI">DPI</option>
                      <option value="PASAPORTE">PASAPORTE</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Nit No.:</label>
                    <input type="text" class="form-control" name="nit_no" id="nit_no" maxlength="20" value="CF"
                      onchange="validarnit2()">
                    <button class="btn btn-danger btn-block" onclick="validarnit2()" type="button">
                      <i class="fa fa-search-minus"></i> NIT
                    </button>
                  </div>


                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Proveedor.:</label>
                    <input type="text" class="form-control" name="proveedor" id="proveedor" maxlength="256"
                      value="CONSUMIDOR FINAL">
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label> Direccion Proveedor.:</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" maxlength="256" value="CIUDAD">
                  </div>
                  <div class="form-group col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <label>Fecha(*):</label>
                    <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                  </div>
                  <div class="form-group col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <label>Valor Q:</label>
                    <input type="number" step="any" class="form-control" name="valor_q" id="valor_q" maxlength="256"
                      placeholder="00.00">
                  </div>
                  <div class="form-group col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <label>Tipo Compra(*):</label>
                    <select name="tipo_compra" id="tipo_compra" class="form-control selectpicker" data-live-search="true"
                      required="">
                      <option value="Seleccion uno">Seleccion uno</option>
                      <option value="Bien">Bien</option>
                      <option value="Servicio">Servicio</option>
                      <option value="Importacion">Importacion</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary btn-block" type="submit" id="btnGuardar"><i class="fa fa-save"></i>
                      Guardar</button>

                    <button class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i
                        class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="scripts/compras.js"></script>

  <?php
}
ob_end_flush();
?>