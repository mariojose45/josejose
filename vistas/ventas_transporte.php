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
  if ($_SESSION['ventas']==1)   
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
                          Ventas  
                          <small>Ventas Transporte</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ventas Transporte</li>
                          </ol>

                          </section>                                                
                        </div> 
                         <!-- <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <a data-toggle="modal" href="#myModalVentasxlote">           
                            <button id="btnAgregarArt " type="button" class="btn btn-primary btn-block" onclick="limpiarmodal();"> <span class="fa fa-plus "></span> Completar Ventas</button>
                          </a>
                        </div> -->
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                                                  
                        </div>                                                  

                    <!-- /.box-header -->
                    <!-- centro --> 
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="box-header with-border">
                          <h3 class="box-title">Ventas Transporte</h3>

                        </div>
                        <button class="btn btn-info btn-block" onclick="listarFacturas()">Mostrar Ventas</button>  
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>#Idventa</th>
                            <th>Cliente</th>
                            <th>T/Comprobante</th>
                            <th># Comprobante</th>
                            <th>Fecha Doc</th>
                            <th>T.V</th>
                            <th>ABONO</th>
                            <th>SALDO</th>
                            <th>Estado</th>
                            <th>Descripcion</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>#Idventa</th>
                            <th>Cliente</th>
                            <th>T/Comprobante</th>
                            <th># Comprobante</th>
                            <th>Fecha Doc</th>
                            <th>T.V</th>
                            <th>ABONO</th>
                            <th>SALDO</th>
                            <th>Estado</th>
                            <th>Descripcion</th>
                          </tfoot>
                        </table>
                      </div>                  
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
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
 



<div class="modal fade" id="myModalComentariosMensajero" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 40% !important;">
      <div class="modal-content">

        <div class="modal-body">
          <form name="formulario_comentarioMensajero" id="formulario_comentarioMensajero" method="POST">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Ingrese la Referencia completa para la entrega de tu pedido :</label>
                <input type="hidden" name="idventa_Mensajero" id="idventa_Mensajero">
                <textarea class="form-control" name="comentario_mensajero" id="comentario_mensajero" rows="5" cols="50"></textarea>
            </div>
          </form>    
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="button" id="btnGuardarComentariosMensajero"><i class="fa fa-save"></i> Guardar Comentario</button>
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
<script type="text/javascript" src="scripts/ventas_mensajero.js"></script>           

<?php  
} 
ob_end_flush();
?>