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
  if ($_SESSION['guiastransporte']==1) 
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>Modulo de Cotejacion de Ventas vrs Guias Cargadas!</h3>                        
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
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>" >
                          <button class="btn btn-success btn-block" onclick="listar()">Mostrar</button>                          
                        </div>                        
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th># Operacion</th>
                            <th>Fecha</th>
                            <th>T Liquidacion</th>
                            <th>Descripcion</th>
                            <th>Usuario</th>
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
                            <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                              <label>Ingrse # de Carga de Excel:</label> 
                              <input type="text" name="idguias_excel" id="idguias_excel" class="form-control">
                              <input type="hidden" name="idguias_excel2" id="idguias_excel2" class="form-control" readonly="" >
                              <button type="button" class="btn  btn-primary btn-block" id="btncargar">Ver Datos</button>
                            </div>  

                          <div class="form-group col-lg-3 col-md-6 col-sm-3 col-xs-12"> 
                            <label>Fecha:</label>
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" >
                          </div>
                          <div class="form-group col-lg-3 col-md-6 col-sm-3 col-xs-12">
                            <label>Total Liquidacion:</label>
                            <input type="number" step="any" class="form-control" name="total_liquidacion" id="total_liquidacion" readonly="">
                          </div>                          
                          <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label>Descripción:</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                          </div>                           
                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    
                                    <th>Opciones</th>
                                    <th>Idventa</th>
                                    <th>Fecha Venta</th>
                                    <th>Total Venta</th>
                                    <th>Guia Venta</th>
                                    <th>Guia Excel</th>
                                    <th>M Venta</th>
                                    <th>Comision %</th>
                                    <th>V. Comision</th>
                                    <th>M Liquidado</th>
                                    <th># Autorizacion</th>
                                    <th># Cta Banco</th>
                                    <th>V Flete</th>
                                    <th>Restante</th>
                                    <th>Transporte</th>
                                </thead>
                                <tfoot>
                                    
                                    <th></th>
                                    <th></th> 
                                    <th></th>
                                    <th><input class="form-control"  type="number" name="t_total_venta" id="t_total_venta"  style="width:100px" readonly=""></th> 
                                    <th></th>
                                    <th></th>
                                    <th><input class="form-control"  type="number" name="t_mventa" id="t_mventa"  style="width:100px" readonly=""></th>   
                                    <th></th>   
                                    <th><input class="form-control"  type="number" name="t_vcomision" id="t_vcomision"  style="width:100px" readonly=""></th>   
                                    <th><input class="form-control"  type="number" name="t_mliquidado" id="t_mliquidado"  style="width:100px" readonly=""></th>   
                                    <th></th>   
                                    <th></th>   
                                    <th><input class="form-control"  type="number" name="t_vflete" id="t_vflete" readonly="" style="width:100px" readonly=""  ></th>   
                                    <th></th>     
                                    <th></th>                                  
                                  
                                </tfoot>
                                <tbody>
                                   
                                </tbody>
                            </table>
                          </div>                          
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btn-block" type="button" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
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

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 80% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione una Ventas</h4>
        </div>
        <div class="modal-body">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>">
                          <button class="btn btn-success" onclick="listarVentasparcotijamientoxfecha()">Listar Ventas para Cargar Cojetamiento</button>                          
                        </div>          
          <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Opciones</th>
                <th>IdVenta/Correlativo</th>
                <th>Fecha</th>
                <th>Transporte</th>
                <th>Guia</th>
                <th>Telefono</th>
                <th>Usuario</th>
            </thead>
            <tbody>
               
            </tbody>
            <tfoot>
                <th>Opciones</th>
                <th>IdVenta/Correlativo</th>
                <th>Fecha</th>
                <th>Transporte</th>
                <th>Guia</th>
                <th>Telefono</th>
                <th>Usuario</th>
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
<?php
}
else
{
  require 'noacceso.php';
}  
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/cotejacionvetas_guias.js"></script> 

<?php  
} 
ob_end_flush();
?>