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
  if ($_SESSION['nomina_pagos_14_aguinaldo']==1) 
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
                          <h3>Modulo de Pagos Nomina 14-Aguinaldo!</h3>                        
                        </div>
                        <div class="icon">
                          <i class="fa fa-home"></i>
                        </div>

                      </div>
                    </div>

                    <div class=" with-border">
                          <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Generar Pagos</button></h1>
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
                            <th>Tipo Operacion</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Descripción</th>
                            <th>Usuario Creacion</th>
                            <th>Fecha Creacion</th>
                            <th>Usuario Modificacion</th>
                            <th>Fecha Modificacion</th>
                            <th>Usuario Delete</th>
                            <th>Fecha Delete</th>
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
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 1000px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Fecha inicio:</label>
                            <input type="hidden" name="idnomina_pagos_14_aguinaldo" id="idnomina_pagos_14_aguinaldo">
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"  required>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Fecha fin:</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"  required>
                          </div>  
                          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Tipo Operacion(*):</label>
                            <select name="tipo_operacion" id="tipo_operacion" class="form-control selectpicker" required="">
                               <option value="Bono 14">Bono 14</option>
                               <option value="Aguinaldo">Aguinaldo</option>
                            </select> 
                          </div>                                                 
                          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Descripción:</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                            <button type="button" class="btn btn-success btn-block" onclick="listarEmpleados()">Listar empleados</button>
                          </div>
                          <style>
                                #detalles input.form-control {
                                    font-size: 12px;      /* Texto más pequeño dentro de los inputs */
                                    padding: 3px 6px;     /* Menos espacio interno */
                                    height: 28px;         /* Altura más compacta */
                                }

                                #detalles td {
                                    font-size: 12px;      /* Texto de las celdas más pequeño */
                                    padding: 4px 6px;     /* Menos espacio vertical */
                                    vertical-align: middle;
                                }

                                #detalles th {
                                    font-size: 13px;      /* Tamaño de encabezado un poco mayor */
                                    text-align: center;
                                    background-color: #cfe2ff; /* tono suave de azul opcional */
                                }

                                #detalles button.btn {
                                    padding: 2px 6px;
                                    font-size: 12px;
                                }
                          </style>
                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Empleado</th>
                                    <th>Puesto</th>
                                    <th>Sueldo Base</th>
                                    <th>Fecha-Del</th>
                                    <th>Fecha-Al</th>
                                    <th>Calculo-Dias</th>
                                    <th>Total-Bono</th>
                                    <th>Ancitipos	</th> 
                                    <th>L-Recibir</th>
                                </thead>
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
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script>
<script type="text/javascript" src="scripts/nomina_pagos_14_aguinaldo.js"></script> 

<?php  
} 
ob_end_flush();
?>