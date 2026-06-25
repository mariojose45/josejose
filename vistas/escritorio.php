<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['escritorio'] == 1) {
    require_once "../modelos/Consultas.php";
    $consulta = new Consultas();
    $rsptac = $consulta->totalcomprahoy();
    $regc = $rsptac->fetch_object();
    $totalc = $regc->total_compra;

    $rsptav = $consulta->totalventahoyxUsuario();
    $regv = $rsptav->fetch_object();
    $totalv = $regv->total_venta;


    $rsptavm = $consulta->totalventaMxUsuario();
    $regvm = $rsptavm->fetch_object();
    $totalvm = $regvm->total_venta;



    //Datos para mostrar el gráfico de barras de las ventas
    $ventas12 = $consulta->ventasultimos_12mesesxUsuario();
    $fechasv = '';
    $totalesv = '';
    while ($regfechav = $ventas12->fetch_object()) {
      $fechasv = $fechasv . '"' . $regfechav->fecha . '",';
      $totalesv = $totalesv . $regfechav->total . ',';
    }
    //Quitamos la última coma
    $fechasv = substr($fechasv, 0, -1);
    $totalesv = substr($totalesv, 0, -1);



    ///top 10 de los ultimos 10 productos mas vendidos
    $articulos10 = $consulta->Articulosultimos_10diasxUsuario();
    $articulos = '';
    $total = '';
    while ($regArticulotop = $articulos10->fetch_object()) {
      $articulos = $articulos . '"' . $regArticulotop->articulos . '",';
      $total = $total . $regArticulotop->total . ',';
    }
    //Quitamos la última coma
    $articulos = substr($articulos, 0, -1);
    $total = substr($total, 0, -1);

    ///Ventas vrs Meta Mes Actual
    $ventasvrsMeta = $consulta->ventasvrsMetaxUsuario();
    $fechasvrsMeta = '';
    $totalvrsMeta = '';
    $Meta = '';
    $comision_usuario = '';
    while ($regventasvrsMeta = $ventasvrsMeta->fetch_object()) {
      $comision_usuario = $comision_usuario . $regventasvrsMeta->comision_usuario . ',';
      $Meta = $Meta . $regventasvrsMeta->meta . ',';
      $fechasvrsMeta = $fechasvrsMeta . '"' . $regventasvrsMeta->fecha . '",';
      $totalvrsMeta = $totalvrsMeta . $regventasvrsMeta->total . ',';
    }
    //Quitamos la última coma
    $comision_usuario = substr($comision_usuario, 0, -1);
    $Meta = substr($Meta, 0, -1);
    $totalvrsMeta = substr($totalvrsMeta, 0, -1);
    $fechasvrsMeta = substr($fechasvrsMeta, 0, -1);

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
                    Escritorio x Usuario
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

                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <div class="small-box bg-green">
                    <div class="inner">
                      <h4 style="font-size:17px;">
                        <strong>Q/ <?php echo $totalv; ?></strong>
                      </h4>
                      <p>Ventas x Usuario Hoy</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"><img class="iconos-escritorio" src="../public/iconos/venta.png"></i>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <div class="small-box bg-red">
                    <div class="inner">
                      <h4 style="font-size:17px;">
                        <strong>Q/ <?php echo $totalvm; ?></strong>
                      </h4>
                      <p>Ventas Mes Actual x Usuario </p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"><img class="iconos-escritorio" src="../public/iconos/venta.png"></i>
                    </div>
                  </div>
                </div>


              </div>


              <div class="panel-body">


                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Ventas vrs Meta Mes Actual x Usuario
                    </div>
                    <div class="box-body">
                      <canvas id="VentasxMesUsuario" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Ventas de los últimos 12 meses x Usuario
                    </div>
                    <div class="box-body">
                      <canvas id="ventas" width="400" height="300"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Top 10 Articulos mas Vendidos x Usuario
                    </div>
                    <div class="box-body">
                      <canvas id="articulostop" width="400" height="300"></canvas>
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
  } else {
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
              beginAtZero: true
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
              beginAtZero: true
            }
          }]
        }
      }
    });


    var ctx = document.getElementById("VentasxMesUsuario").getContext('2d');
    var barChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php echo $fechasvrsMeta; ?>],
        datasets: [
          {
            label: 'Meta Mes',
            data: [<?php echo $Meta; ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          },
          {
            label: 'Total Ventas',
            data: [<?php echo $totalvrsMeta; ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
          },
          {
            label: 'Comisión Mes',
            data: [<?php echo $comision_usuario; ?>],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          xAxes: [{
            stacked: false,
            gridLines: {
              display: false
            }
          }],
          yAxes: [{
            stacked: false,
            ticks: {
              beginAtZero: true
            }
          }]
        },
        legend: {
          display: true,
          position: 'top'
        },
        tooltips: {
          mode: 'index',
          intersect: false
        }
      }
    });



  </script>
<?php
}
ob_end_flush();
?>