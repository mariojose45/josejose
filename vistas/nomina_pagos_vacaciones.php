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
  if ($_SESSION['nomina_pagos_vacaciones']==1) 
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
                          <h3>Modulo de Vacaciones!</h3>                         
                        </div>
                        <div class="icon">
                          <i class="fa fa-home"></i>
                        </div>

                      </div>
                    </div>

                    <div class=" with-border">
                          <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                                <th>Opciones</th>
                                <th>IdOperacion</th>
                                <th>Empleado</th>
                                <th>Período</th>
                                <th>Días</th>
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
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                        <input type="hidden" name="idvacacion" id="idvacacion">

                            <!-- Fecha de Solicitud -->
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label>Fecha Solicitud (*):</label>
                                <input type="date" class="form-control" name="fecha_solicitud" id="fecha_solicitud" required>
                            </div>

                            <!-- Empleado -->
                            <div class="form-group col-lg-8 col-md-8 col-sm-12">
                                <label>Empleado (*):</label>
                                <select name="idempleado" id="idempleado" class="form-control selectpicker" data-live-search="true" required>
                                </select>
                            </div>

                            <!-- Período Solicitado -->
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label>Del (*):</label>
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
                            </div>

                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label>Al (*):</label>
                                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required>
                            </div>

                            <!-- Días -->
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label>Días Solicitados (*):</label>
                                <input type="number" class="form-control" name="dias_solicitados" id="dias_solicitados" required>
                            </div>

                            <!-- Motivo -->
                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <label>Motivo / Observaciones:</label>
                                <textarea class="form-control" name="motivo" id="motivo" rows="3"></textarea>
                            </div>

                            <!-- ================= PANEL INFORMATIVO ================= -->
                            <div class="col-lg-12" id="infoVacaciones" style="display:none; margin-top:10px;">
                                <div class="alert alert-info">
                                    <h4><i class="fa fa-info-circle"></i> Información de Vacaciones del Empleado</h4>
                                    <p><strong>Período:</strong> <span id="periodo_vac"></span></p>
                                    <p><strong>Días Generados:</strong> <span id="dias_generados"></span></p>
                                    <p><strong>Días Gozados:</strong> <span id="dias_gozados"></span></p>
                                    <p><strong>Días Pendientes:</strong> <span id="dias_pendientes"></span></p>
                                </div>
                            </div>

                            <!-- BOTONES -->
                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <button class="btn btn-primary" type="button" id="btnGuardar">
                                    <i class="fa fa-save"></i> Guardar
                                </button>

                                <button class="btn btn-danger" type="button" onclick="cancelarform()">
                                    <i class="fa fa-arrow-circle-left"></i> Cancelar
                                </button>
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
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script>
<script type="text/javascript" src="scripts/nomina_pagos_vacaciones.js"></script> 

<?php  
} 
ob_end_flush();
?>