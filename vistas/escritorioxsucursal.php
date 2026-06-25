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
  
if ($_SESSION['escritorio']==1)
{ 
  require_once "../modelos/Consultas.php";
  $consulta = new Consultas();
  $rsptac = $consulta->totalcomprahoy();
  $regc=$rsptac->fetch_object();
  $totalc=$regc->total_compra;    
   
  $rsptav = $consulta->totalventahoy();
  $regv=$rsptav->fetch_object();
  $totalv=$regv->total_venta;


  $rsptavm = $consulta->totalventaM(); 
  $regvm=$rsptavm->fetch_object();
  $totalvm=$regvm->total_venta;

  $rsptavcobrar = $consulta->totalventaCobrar();  
  $regvcobrar=$rsptavcobrar->fetch_object();
  $totalitem=$regvcobrar->numerodeitems;  
  $totalcobrar=$regvcobrar->totalcobrar;  
 
  //Datos para mostrar el gráfico de barras de las compras
  $compras10 = $consulta->comprasultimos_10dias();
  $fechasc='';
  $totalesc='';
  while ($regfechac= $compras10->fetch_object()) {
    $fechasc=$fechasc.'"'.$regfechac->fecha .'",';
    $totalesc=$totalesc.$regfechac->total .','; 
  }
  //Quitamos la última coma
  $fechasc=substr($fechasc, 0, -1);
  $totalesc=substr($totalesc, 0, -1);
 
  //Datos para mostrar el gráfico de barras de las ventas
  $ventas12 = $consulta->ventasultimos_12meses();
  $fechasv='';
  $totalesv='';
  while ($regfechav= $ventas12->fetch_object()) {
    $fechasv=$fechasv.'"'.$regfechav->fecha .'",';
    $totalesv=$totalesv.$regfechav->total .','; 
  }
  //Quitamos la última coma
  $fechasv=substr($fechasv, 0, -1);
  $totalesv=substr($totalesv, 0, -1);




  //Datos para mostrar el gráfico de barras de las clientes nuevos de los 12 meses
  $Clientesnuevos12mes = $consulta->clientesnuevosultimos_12meses();
  $fechasClientesNuevos='';
  $totalesClientesNuevos='';
  while ($regfechavClientNuevos= $Clientesnuevos12mes->fetch_object()) {
    $fechasClientesNuevos=$fechasClientesNuevos.'"'.$regfechavClientNuevos->fecha .'",';
    $totalesClientesNuevos=$totalesClientesNuevos.$regfechavClientNuevos->total .','; 
  }
  //Quitamos la última coma
  $fechasClientesNuevos=substr($fechasClientesNuevos, 0, -1);
  $totalesClientesNuevos=substr($totalesClientesNuevos, 0, -1);


 //Datos para mostrar el gráfico de barras de las Proveedor nuevos de los 12 meses
  $Proveedornuevos12mes = $consulta->proveedornuevosultimos_12meses();
  $fechasProveedorNuevos='';
  $totalesProveedorNuevos='';
  while ($regfechavProveeNuevos= $Proveedornuevos12mes->fetch_object()) {
    $fechasProveedorNuevos=$fechasProveedorNuevos.'"'.$regfechavProveeNuevos->fecha .'",';
    $totalesProveedorNuevos=$totalesProveedorNuevos.$regfechavProveeNuevos->total .','; 
  }
  //Quitamos la última coma
  $fechasProveedorNuevos=substr($fechasProveedorNuevos, 0, -1);
  $totalesProveedorNuevos=substr($totalesProveedorNuevos, 0, -1);


   //Datos para mostrar el gráfico de barras de las personas nuevos de los 12 meses
  $Personanuevos12mes = $consulta->personanuevosultimos_12meses();
  $fechasPersonaNuevos='';
  $totalesPersonaNuevos='';
  while ($regfechavPersonNuevos= $Personanuevos12mes->fetch_object()) {
    $fechasPersonaNuevos=$fechasPersonaNuevos.'"'.$regfechavPersonNuevos->fecha .'",';
    $totalesPersonaNuevos=$totalesPersonaNuevos.$regfechavPersonNuevos->total .','; 
  }
  //Quitamos la última coma
  $fechasPersonaNuevos=substr($fechasPersonaNuevos, 0, -1);
  $totalesPersonaNuevos=substr($totalesPersonaNuevos, 0, -1);




///top 10 de los ultimos 10 productos mas vendidos
  $articulos10 = $consulta->Articulosultimos_10dias();
  $articulos=''; 
  $total='';
  while ($regArticulotop= $articulos10->fetch_object()) { 
    $articulos=$articulos.'"'.$regArticulotop->articulos .'",';
    $total=$total.$regArticulotop->total .','; 
  } 
  //Quitamos la última coma
  $articulos=substr($articulos, 0, -1);
  $total=substr($total, 0, -1);

?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                        <div class="box-header with-border box box-primary">
                          <section class="content-header">
                          <h1>
                          Escritorio x Sucursal/General
                          <small>Panel</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Articulos</li>
                          </ol>

                          </section>                                               
                        </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <div class="small-box bg-aqua">
                              <div class="inner">
                                <h4 style="font-size:17px;">
                                  <strong>Q/ <?php echo $totalc; ?></strong>
                                </h4>
                                <p>Compras General</p>
                              </div>
                              <div class="icon">
                                <i class="ion ion-bag"><img class="iconos-escritorio" src="../public/iconos/compras.png"></i>
                              </div>
                              <a href="ingreso.php" class="small-box-footer">Compras <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <div class="small-box bg-green">
                              <div class="inner">
                                <h4 style="font-size:17px;">
                                  <strong>Q/ <?php echo $totalv; ?></strong>
                                </h4>
                                <p>Ventas General</p>
                              </div>
                              <div class="icon">
                                <i class="ion ion-bag"><img class="iconos-escritorio" src="../public/iconos/venta.png"></i>
                              </div>
                              <a href="venta.php" class="small-box-footer">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                         <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <div class="small-box bg-red">
                              <div class="inner">
                                <h4 style="font-size:17px;">
                                  <strong>Q/ <?php echo $totalvm; ?></strong>
                                </h4>
                                <p>Ventas Mes Actual General </p>
                              </div>
                              <div class="icon">
                                <i class="ion ion-bag"><img class="iconos-escritorio" src="../public/iconos/venta.png"></i>
                              </div>
                              <a href="venta.php" class="small-box-footer">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div> 
                         <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <div class="small-box bg-yellow">
                              <div class="inner">
                                <h4 style="font-size:17px;">
                                  <strong>#/ (<?php echo $totalitem; ?>)</strong>&nbsp;&nbsp;<strong>Q<?php echo $totalcobrar; ?> </strong>
                                  
                                </h4>
                                <p>No. de Ctas a Cobrar General</p>
                              </div>
                              <div class="icon">
                                <i class="ion ion-bag"><img class="iconos-escritorio" src="../public/iconos/venta.png"></i>
                              </div>
                              <a href="rptcuentasporcobrarpendientes.php" class="small-box-footer">Reporte Detallado<i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div> 

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="box box-warning box-solid">
                      <div class="box-header with-border">
                      <h3 class="box-title">REPORTES DE VENTAS</h3>
                      <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      </div>

                      </div>

                      <div class="box-body" style="">
                        <div >
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 table-responsive">
                              <h4 style="font-size:17px; display: flex; justify-content: space-between;">
                                Inventario x Sucursal
                              </h4>
                              <div >
                                <table id="tbllistadoInventarioxSucusal" class="table table-striped table-bordered table-condensed table-hover">
                                  <thead>
                                    <th>Sucursal</th>
                                    <th>Compra</th>
                                    <th>Precio Venta</th>
                                  </thead>
                                  <tbody>                            
                                  </tbody>
                                  <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                  </tfoot>
                                </table>
                              </div>                        
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 table-responsive">
                              <h4 style="font-size:17px; display: flex; justify-content: space-between;">
                                Ventas x Sucursal hoy
                              </h4> 
                              <div >
                                <table id="tbllistadoVentasxSucusal" class="table table-striped table-bordered table-condensed table-hover">
                                  <thead>
                                    <th>Sucursal</th>
                                    <th>Precio Venta</th>
                                  </thead>
                                  <tbody>                            
                                  </tbody>
                                  <tfoot>
                                    <th></th>
                                    <th></th>
                                  </tfoot>
                                </table>
                              </div>                        
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 table-responsive">
                              <h4 style="font-size:17px; display: flex; justify-content: space-between;">
                                Ventas x Sucursal Mes
                              </h4>
                              <div >
                                <table id="tbllistadoVentasxSucusalMes" class="table table-striped table-bordered table-condensed table-hover">
                                  <thead>
                                    <th>Mes</th>
                                    <th>Sucursal</th>
                                    <th>Precio Venta</th>
                                  </thead>
                                  <tbody>                            
                                  </tbody>
                                  <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                  </tfoot> 
                                </table>
                              </div>                        
                            </div> 
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 table-responsive">
                              <h4 style="font-size:17px; display: flex; justify-content: space-between;">
                                Ventas vrs Ganacia x Sucursal Mes
                              </h4>
                              <div >
                                <table id="tbllistadoVentasxSucusalMesGanacia" class="table table-striped table-bordered table-condensed table-hover">
                                  <thead>
                                    <th>Mes</th>
                                    <th>Sucursal</th>
                                    <th>Precio V</th>
                                    <th>Precio C</th>
                                    <th>Ganacia</th>
                                    <th>Promociones</th>
                                  </thead>
                                  <tbody>                            
                                  </tbody>
                                  <tfoot>
                                    <th>TOTALES</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                  </tfoot> 
                                </table>
                              </div>                        
                            </div>                                                     
                        </div>
                      </div>

                      </div>

                    </div>                    

                   
                                
                    <div class="panel-body">
 
                                                          
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <div class="box box-primary">
                            <div class="box-header with-border">
                                Compras de los últimos 10 días
                            </div>
                            <div class="box-body">
                              <canvas id="compras" width="400" height="300"></canvas>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <div class="box box-primary">
                            <div class="box-header with-border">
                                Ventas de los últimos 12 meses
                            </div>
                            <div class="box-body">
                              <canvas id="ventas" width="400" height="300"></canvas>
                            </div>
                          </div>
                        </div> 
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <div class="box box-primary">
                            <div class="box-header with-border">
                                Top 10 Articulos mas Vendidos General
                            </div>
                            <div class="box-body">
                              <canvas id="articulostop" width="400" height="300"></canvas>
                            </div>
                          </div>
                        </div>   
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <div class="box box-primary">
                            <div class="box-header with-border">
                                Integracion de Clientes Nuevos 12 meses
                            </div>
                            <div class="box-body">
                              <canvas id="integracionClientesnuevos12meses" width="400" height="300"></canvas>
                            </div>
                          </div>
                        </div> 
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <div class="box box-primary">
                            <div class="box-header with-border">
                                Integracion de Proveedores Nuevos 12 meses
                            </div>
                            <div class="box-body">
                              <canvas id="integracionProveedornuevos12meses" width="400" height="300"></canvas>
                            </div>
                          </div>
                        </div> 
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <div class="box box-primary">
                            <div class="box-header with-border">
                                Total Clientes/Proveedores 12 meses
                            </div>
                            <div class="box-body">
                              <canvas id="integracionPersonanuevos12meses" width="400" height="300"></canvas>
                            </div>
                          </div>
                        </div> 

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
<script src="../public/js/chart.min.js"></script>
<script src="../public/js/Chart.bundle.min.js"></script> 
<script type="text/javascript" src="scripts/ventasxfechaxmes.js"></script> 
<script type="text/javascript">
var ctx = document.getElementById("articulostop").getContext('2d');
var articulostop = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $articulos; ?>],
        datasets: [{
            label: 'Top de 10 Articulos mas Vendidos General',
            data: [<?php echo $total; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
 


var ctx = document.getElementById("compras").getContext('2d');
var compras = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasc; ?>],
        datasets: [{
            label: '# Compras en Q/ de los últimos 10 días',
            data: [<?php echo $totalesc; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
 
 
 
var ctx = document.getElementById("ventas").getContext('2d');
var ventas = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasv; ?>],
        datasets: [{
            label: '# Ventas en Q/ de los últimos 12 meses',
            data: [<?php echo $totalesv; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});




var ctx = document.getElementById("integracionClientesnuevos12meses").getContext('2d');
var integracionClientesnuevos12meses = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasClientesNuevos; ?>],
        datasets: [{
            label: '# Clientes nuevos últimos 12 meses',
            data: [<?php echo $totalesClientesNuevos; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});




var ctx = document.getElementById("integracionProveedornuevos12meses").getContext('2d');
var integracionProveedornuevos12meses = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasProveedorNuevos; ?>],
        datasets: [{
            label: '# Proveedor nuevos últimos 12 meses',
            data: [<?php echo $totalesProveedorNuevos; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});




var ctx = document.getElementById("integracionPersonanuevos12meses").getContext('2d');
var integracionPersonanuevos12meses = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasPersonaNuevos; ?>],
        datasets: [{
            label: '# Proveedor/Cliente nuevos últimos 12 meses',
            data: [<?php echo $totalesPersonaNuevos; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

</script>
</script> 
<?php 
}
ob_end_flush();
?>