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
                        <div class=" with-border box box-primary">
                          <section class="content-header">
                          <h1>
                          Registro Ingreso Empleados  
                          <small>Registros</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Registro Ingreso Empleados</li>
                          </ol>

                          </section>                                                 
                        </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros" style="height: 800px;">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">    
                          <button class="btn btn-success" onclick="listar()">Mostrar x fecha</button>                     
                        </div>
                      

                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover"> 
                          <thead>
                            <th>Opciones</th>
                            <th>Codigo</th>
                            <th>Imagen</th>
                            <th>Nombres</th>
                            <th>Puesto</th>
                            <th>Fecha</th>
                            <th>Salida/Entrada</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Codigo</th>
                            <th>Imagen</th>
                            <th>Nombres</th>
                            <th>Puesto</th>
                            <th>Fecha</th>
                            <th>Salida/Entrada</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
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
<script type="text/javascript" src="scripts/rpt_registro_ingresoEmpleados.js"></script>
<?php 
}
ob_end_flush();
?>