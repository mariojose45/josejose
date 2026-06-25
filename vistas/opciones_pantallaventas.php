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
  if ($_SESSION['ventas_facturacion']==1) 
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
                          Pantalla Ventas  
                          <small>Ventas Facturacion</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ventas Facturacion</li>
                          </ol>

                          </section>  
                          <div class="row" style="margin: 10px 0;">
                            <!-- Fecha Inicio -->
                            <div class="col-md-3 col-sm-6">
                                <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>">
                            </div>

                            <!-- Fecha Fin -->
                            <div class="col-md-3 col-sm-6">
                                <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>">
                            </div>

                            <!-- Botón Generar -->                            
                            <div class="col-md-2 col-sm-6">
                            <select name="tipo_envioPedidos" id="tipo_envioPedidos" class="form-control selectpicker" required="">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Listo">Listo</option> 

                            </select> 
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <button class="btn btn-success btn-block" onclick="listar()">Generar Pedidos</button>
                            </div>                            

                            <!-- Botón Ayuda 
                            <div class="col-md-2 col-sm-6">
                                <button class="btn btn-info btn-block" id="btnTour">
                                <i class="fa fa-question-circle"></i> Ayuda
                                </button>
                            </div>-->
                            </div>

                        </div> 

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body" id="listadoregistros">
                        <div class="row" id="contenedor-ordenes">
                            <!-- Aquí se renderizan las órdenes dinámicamente -->
                        </div>
                    </div>
                    <style>
                        .orden-card {
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            background: #fff;
                            margin: 10px;
                            padding: 15px;
                            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                            flex: 0 0 calc(50% - 20px); /* 2 por fila */
                        }
                        .orden-header {
                            font-weight: bold;
                            margin-bottom: 8px;
                            color: #444;
                        }
                        .orden-body {
                            font-size: 14px;
                            margin-bottom: 12px;
                        }
                        .orden-footer {
                            text-align: right;
                        }
                        #contenedor-ordenes {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: flex-start;
                        }

                        .orden-card {
                            border: 1px solid #ddd;
                            border-radius: 6px;
                            background: #fff;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        }

                        .orden-card p {
                            margin: 2px 0;
                            font-size: 12px;
                        }

                        .table-sm th, 
                        .table-sm td {
                            padding: 3px 5px;
                            font-size: 12px;
                        }

                        .btn-xs {
                            padding: 2px 6px;
                            font-size: 11px;
                        }                        
                    </style>

                    <div class="panel-body" style="height: 400px;" id="formularioregistros">

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
<script type="text/javascript" src="scripts/ordenes_pantallaventas.js"></script> 

<!-- ✅ Luego Driver.js -->
<script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css"/>


<?php  
} 
ob_end_flush();
?>