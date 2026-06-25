<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
 
if (!isset($_SESSION["nombre"]))
  {
    header("Location: login.html");
  }
else
  {
  require 'header.php';
  if ($_SESSION['nomina_empleados']==1) 
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
                                        <div class="col-lg-12 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>Modulo de  "Pestamos, otros descu, Adelantos quince, Anticipos sala" Empleado!</h3>                        
                        </div>
                        <div class="icon">
                          <i class="fa fa-home"></i>
                        </div>

                      </div>
                    </div>

                    <div class=" with-border">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Nuevo Prestamo</button></h1>
                    </div>
                          
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <!-- Custom Tabs -->
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab_1" data-toggle="tab">Nuevo Prestamo</a></li>
                          <li><a href="#tab_2" data-toggle="tab">Abonos Prestamo</a></li>

                          <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane active" id="tab_1">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                  <thead>
                                    <th>Opciones</th>
                                    <th>IdOperacion</th>
                                    <th>Tipo Operacion</th>
                                    <th>Empleado</th>
                                    <th>Monto Prestamo</th>
                                    <th>Abono Prestamo</th>
                                    <th>Saldo Prestamo</th>
                                    <th>No Cuotas</th>
                                    <th>No Cuotas Pagadas</th>
                                    <th>No Cuotas Pendientes</th>
                                    <th>Fecha Prestamo</th>
                                    <th>Fecha Ultimo Abono</th>
                                    <th>Concepto Prestamo</th>
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
                                  </tfoot>
                                </table>                      
                            </div>
                          </div>
                          <!-- /.tab-pane -->
                          <div class="tab-pane" id="tab_2">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Fecha Inicio</label>
                                  <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>" >
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                  <label>Fecha Fin</label>
                                  <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>" >
                                  <button class="btn btn-success btn-block" onclick="listarAbonosPrestamo()">Mostrar Abonos</button>
                                </div>
                                <table id="tbllistadoAbonos" class="table table-striped table-bordered table-condensed table-hover">
                                  <thead>
                                    <th>Opciones</th>
                                    <th>Tipo Operacion</th>
                                    <th>Empleado</th>
                                    <th>Fecha Abono</th>
                                    <th>Monto Prestamo</th>
                                    <th>Abono Prestamo</th>
                                    <th>Saldo Prestamo</th>
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
                                  </tfoot>
                                </table>                      
                            </div> 
                          </div>
                          <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                      </div>
                      <!-- nav-tabs-custom -->
                    </div>                      

                   

                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Empleado:</label>
                            <input type="hidden" name="idotrosdecuentosempleado" id="idotrosdecuentosempleado">
                            <select id="idempleado" name="idempleado" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Monto Prestamo:</label>
                            <input type="number" class="form-control" name="monto_prestamo" id="monto_prestamo" placeholder="Monto Prestamo" required>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                          <label>Tipo de Operacion:</label>
                            <select class="form-control select-picker" name="tipo_operacion" id="tipo_operacion" required>
                              <option value="PRESTAMO">PRESTAMO</option>  
                              <option value="OTROS DESCUENTOS">OTROS DESCUENTOS</option>
                              <option value="ADELANTO QUINCENAL">ADELANTO QUINCENAL</option>
                              <option value="ANTICIPO SALARIAL">ANTICIPO SALARIAL</option>
                              <option value="BONO 14">BONO 14</option>
                              <option value="AGUINALDO">AGUINALDO</option>
                            </select> 
                          </div>                           
                          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label for="no_cuotas">No. Cuotas:</label>
                            <div class="input-group">
                              <input type="number" class="form-control" name="no_cuotas" id="no_cuotas" placeholder="No. Cuotas" required>
                              <span class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-flat" id="btnGenerarCuotas" title="Generar cuotas">
                                  <i class="fa fa-cogs"></i>
                                </button>
                              </span>
                            </div>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Fecha Prestamo:</label>
                            <input type="date" class="form-control" name="fecha_prestamo" id="fecha_prestamo" placeholder="Fecha Prestamo" required>
                          </div>                                                    
                          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Fecha Ultimo Abono:</label>
                            <input type="date" class="form-control" name="fecha_ultimo_abono" id="fecha_ultimo_abono" placeholder="Fecha Ultimo Abono" required>
                          </div>  
                          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Concepto Prestamo:</label>
                            <input type="text" class="form-control" name="concepto_prestamo" id="concepto_prestamo" placeholder="Concepto Prestamo" required>
                          </div> 
                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>No Cuota</th>
                                    <th>Fecha Abono</th>
                                    <th>Monto Abono</th>
                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                  
                                </tfoot>
                                <tbody>
                                   
                                </tbody>
                            </table>
                          </div>                                                     
 
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="button" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
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
<!-- ===================== MODAL ABONOS PRESTAMO ===================== -->
<div class="modal fade" id="modalAbonosPrestamo" tabindex="-1" role="dialog" aria-labelledby="modalAbonosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-green">
        <h4 class="modal-title" id="modalAbonosLabel"><i class="fa fa-gear"></i> Registrar Abono del Préstamo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="formAbonoPrestamo">
          <input type="hidden" id="idotrosdecuentosempleado_abono" name="idotrosdecuentosempleado_abono">
          <input type="hidden" id="idempleadoAbono" name="idempleadoAbono">

          <div class="form-group">
            <label for="monto_prestamoAbono">Monto Prestamo Pendiente:</label>
            <input type="number" class="form-control" id="monto_prestamoAbono" name="monto_prestamoAbono" step="any" readonly>
          </div>

          <div class="form-group">
            <label for="monto_abonoAbono">Monto del abono:</label>
            <input type="number" class="form-control" id="monto_abonoAbono" name="monto_abonoAbono" step="any" required>
          </div>

          <div class="form-group">
            <label for="saldo_prestamoAbono">Saldo Prestamo:</label>
            <input type="number" class="form-control" id="saldo_prestamoAbono" name="saldo_prestamoAbono" step="any" readonly>
          </div>   
          <div class="form-group">
            <label for="saldo_prestamoAbono">Banco:</label>
            <select id="idcuenta" name="idcuenta" class="form-control selectpicker" data-live-search="true" required></select>
          </div> 
          <div class="form-group">
            <label for="saldo_prestamoAbono">Descripcion:</label>
            <input type="text" class="form-control" id="descripcion_abono" name="descripcion_abono" step="any" required>
          </div>                              

          <div class="form-group">
            <label for="fecha_abono">Fecha del abono:</label>
            <input type="date" class="form-control" id="fecha_abono" name="fecha_abono" required>
          </div>
          <div class="form-group">
            <label for="fecha_abono">Tipo de Operacion:</label>
            <input type="text " class="form-control" id="tipo_operacion_abono" name="tipo_operacion_abono" readonly>
          </div>          
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarAbono"><i class="fa fa-save"></i> Guardar abono</button>
      </div>

    </div>
  </div>
</div>
<!-- =============================================================== -->


<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/nomina_otrosdecuentos_empleado.js"></script> 

<?php  
} 
ob_end_flush();
?>