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
 
  require_once "../modelos/Consultas.php"; 
    $consulta = new Consultas();
    $rsptav = $consulta->totalventahoy();
    $regv=$rsptav->fetch_object();    
    $totalv=$regv->total_venta;   
  
    $res_efectivo = $consulta->totalventahoyefectivo();
    $resv_efectivo=$res_efectivo->fetch_object(); 
    $restotalpagoefectivo=$resv_efectivo->total_venta;   

    $res_tranferencia = $consulta->totalventahoytransferencia();
    $resv_transferencia=$res_tranferencia->fetch_object(); 
    $restotalpagotransferencia=$resv_transferencia->total_venta;
  
    $res_credito = $consulta->totalventahoycredito2(); 
    $resv_credito=$res_credito->fetch_object(); 
    $restotalpagocredito=$resv_credito->total_venta;    
   
  
    $res_tarjeta = $consulta->totalventahoyTarjeta2();
    $resv_tarjeta=$res_tarjeta->fetch_object(); 
    $restotalpagotarjeta=$resv_tarjeta->total_venta;    


    $res_gasto = $consulta->GastosVenta();
    $resv_gasto=$res_gasto->fetch_object(); 
    $restotalgastoventa=$resv_gasto->total_venta;    

    $res_AbonosCtacobrar = $consulta->AbonosCtaCobrarVenta();
    $resv_AbonosCtacobrar=$res_AbonosCtacobrar->fetch_object(); 
    $restotatalAbonoctacobrar=$resv_AbonosCtacobrar->total_venta;            
  
  
    $rsptavc = $consulta->totalefectivoiniciocaja();
    $regvc=$rsptavc->fetch_object();
    $restotalefectivo=$regvc->totalefectivo;
    $resApertura=$regvc->tipo_operacion; 

    $resDisponibleparaGastos=$restotalpagoefectivo-$restotalgastoventa;

  date_default_timezone_set('America/Guatemala'); 
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
                          <small>Procesa tu Venta</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ventas</li>
                          </ol>

                          </section>  
                        </div>                     

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12"> 
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>" >
                          
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                              <label>Tipo/Entrega(*):</label>
                              <select name="tipo_entrega" id="tipo_entrega" class="form-control" required="">
                                <option value="Tienda">Tienda</option>
                                <option value="Transporte">Transporte</option>
                                <option value="Mensajero">Mensajero</option> 
                                <option value="*">Todo</option>                                
                              </select>
                              <button class="btn btn-success btn-block" onclick="listar()">Generar Ventas</button>
                        </div>                        
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Cambiar</th>
                            <th>Mensajero Estatus</th>
                            <th>Venta Despacho</th>
                            <th>Idventa</th>
                            <th>Tipo Entrega</th>
                            <th>Cliente</th>
                            <th>Usuario</th> 
                            <th>Tipo Doc</th> 
                            <th>No/Correlativo</th> 
                            <th>T. V</th>
                            <th>T. V. Des</th>
                            <th>F/Pago</th>                            
                            <th>Efectivo</th>
                            <th>Tarjeta</th>
                            <th>Credito</th>
                            <th>Transferencia</th>
                            <th>Cambio</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Estado Venta</th>
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
                    <div class="panel-body" style="height: 800px;" id="formularioregistros">
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
 
    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  <!-- Modal -->


<?php
}
else
{
  require 'noacceso.php';
}
 
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="scripts/despacho_ventas.js"></script>   
<?php 
}
ob_end_flush(); 
?>