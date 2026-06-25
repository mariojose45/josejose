<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['cta_pagar_generar'] == 1) {

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
                <h4>Modulo de Cuentas por Pagar!</h4>
                En este Modulo podras registrar los pagos correspondientes a las facturas </a>
              </div>

              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>IdCompra</th>
                    <th>Proveedor</th>
                    <th>Fecha Ingreso</th>
                    <th>Serie #</th>
                    <th>Total Compra</th>
                    <th>Cheque #</th>
                    <th>Fecha Cheque</th>
                    <th>Valor Cheque</th>
                    <th>Restante</th>
                    <th>Tipo pago/ generado</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>IdCompra</th>
                    <th>Proveedor</th>
                    <th>Fecha Ingreso</th>
                    <th>Serie #</th>
                    <th>Total Compra</th>
                    <th>Cheque #</th>
                    <th>Fecha Cheque</th>
                    <th>Valor Cheque</th>
                    <th>Restante</th>
                    <th>Tipo pago/ generado</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Nombre Provedor</label>
                    <input type="hidden" name="idingreso" id="idingreso">
                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="250"
                      placeholder="Nombre Cliente" readonly>
                    <input type="hidden" class="form-control" name="idcliente" id="idcliente" maxlength="50">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Telefono Proveedor:</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" maxlength="256"
                      placeholder="Sin Telefono" readonly>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Fecha Facturacion:</label>
                    <input type="text" class="form-control" name="fecha_operacion" id="fecha_operacion" maxlength="256"
                      readonly>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Serie:</label>
                    <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="256"
                      placeholder="Sin Serie">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label># Documento:</label>
                    <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="256"
                      placeholder="# Numero" readonly>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Forma Pago:</label>
                    <input type="text" class="form-control" name="forma_pago" id="forma_pago" maxlength="256"
                      placeholder="Tipo Documento" readonly>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Dias Credito:</label>
                    <input type="text" class="form-control" name="dias_credito" id="dias_credito" maxlength="256"
                      placeholder="Tipo Documento" readonly>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Fecha pago dias Credito:</label>
                    <input type="text" class="form-control" name="fecha_hora_pago_credito" id="fecha_hora_pago_credito"
                      maxlength="256" placeholder="Sin Fecha pago dias credito" readonly>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Saldo Ingreso:</label>
                    <input type="text" class="form-control" name="total_compra" id="total_compra"
                      onchange="calculosaldoingreso();" maxlength="256" placeholder="00.00" readonly>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Valor a pagar:</label>
                    <input type="text" class="form-control" name="valor_pagar" id="valor_pagar"
                      onchange="calculosaldoingreso();" maxlength="256" placeholder="00.00">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Saldo Restante :</label>
                    <input type="text" class="form-control" name="saldo_ingreso" id="saldo_ingreso" maxlength="256"
                      placeholder="00.00" readonly>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Tipo Pago:</label>
                    <select class="form-control select-picker" name="tipo_pago" id="tipo_pago" required>
                      <option value="Efectivo">Efectivo</option>
                      <option value="Cheque">Cheque</option>
                      <option value="Transferencia">Transferencia</option>
                      <option value="Deposito">Deposito</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" name="tipo_bancodiv" id="tipo_bancodiv">
                    <label>Tipo Banco:</label>
                    <select class="form-control select-picker" name="tipo_banco" id="tipo_banco" required>
                      <option value="BANRURAL">BANRURAL</option>
                      <option value="BANCO AGRICOLA MERCANTIL">BANCO AGRICOLA MERCANTIL</option>
                      <option value="INDUSTRIAL">INDUSTRIAL</option>
                      <option value="BAC REFORMADOR">BAC REFORMADOR</option>
                      <option value="BANCO GYT">BANCO GYT</option>
                      <option value="BANCO PROMERICA">BANCO PROMERICA</option>
                      <option value="BANCO FICOSA">BANCO FICOSA</option>
                      <option value="INTERBANCO">INTERBANCO</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" name="numero_boletadiv"
                    id="numero_boletadiv">
                    <label>Boleta Auto. #:</label>
                    <input type="text" class="form-control" name="numero_boleta" id="numero_boleta" maxlength="50"
                      placeholder="Boleta #">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" name="recibo_caja_numerodiv"
                    id="recibo_caja_numerodiv">
                    <label>Recibo caja #:</label>
                    <input type="text" class="form-control" name="recibo_caja_numero" id="recibo_caja_numero"
                      maxlength="256" placeholder="Recibo caja #">
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" id="idcuentadiv" name="idcuentadiv">
                    <label>Cta Bancaria(*):</label>
                    <select id="idcuenta" name="idcuenta" class="form-control selectpicker"
                      data-live-search="true"></select>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" name="no_chequediv" id="no_chequediv">
                    <label>Numero Cheque #:</label>
                    <input type="text" class="form-control" name="no_cheque" id="no_cheque" maxlength="256"
                      placeholder="Recibo caja #">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" name="fecha_hora_generacion_pagodiv"
                    id="fecha_hora_generacion_pagodiv">
                    <label>Fecha generacion de pago(*):</label>
                    <input type="date" class="form-control" name="fecha_hora_generacion_pago"
                      id="fecha_hora_generacion_pago">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" name="desp_chequediv" id="desp_chequediv">
                    <label>Descripcion del cheque*</label>
                    <textarea class="form-control" name="desp_cheque" id="desp_cheque" rows="4"></textarea>
                  </div>


                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>
                      Guardar</button>

                    <button class="btn btn-danger" onclick="cancelarform()" type="button"><i
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
  <script type="text/javascript" src="scripts/cuentasporpagar.js"></script>

<?php
}
ob_end_flush();
?>