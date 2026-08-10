<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';

require_once "../modelos/Consultas.php";
$consulta = new Consultas();
$rsptac = $consulta->sucursal();
$regc = $rsptac->fetch_object();
$resnombre = $regc->nombre_sucursal;


$rsptacotizaciones = $consulta->totalcotizaconesGeneral();
$regcotizaciones = $rsptacotizaciones->fetch_object();
$resnombrecotizaciones = ($regcotizaciones && isset($regcotizaciones->total_cotizaciones)) ? $regcotizaciones->total_cotizaciones : 0;

$rsptacotizacionesDetalle = $consulta->totalcotizaconesGeneralDetalle();

$rsptacotizaciones2 = $consulta->totalcotizaconesGeneral2();
$regcotizaciones2 = $rsptacotizaciones2->fetch_object();
$resnombrecotizaciones2 = ($regcotizaciones2 && isset($regcotizaciones2->total_cotizaciones)) ? $regcotizaciones2->total_cotizaciones : 0;

$rsptacotizaciones2Detalle = $consulta->totalcotizaconesGeneral2Detalle();





date_default_timezone_set('America/Guatemala');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SOL |Sistema de Operaciones en Linea</title>
  <link rel="icon" type="image/png" href="favicon2.png" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../public/css/font-awesome.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../public/css/_all-skins.min.css">
  <link rel="apple-touch-icon" href="favicon2.png">
  <link rel="shortcut icon" href="favicon2.png">

  <!-- Data Tables -->
  <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">

  <link rel="stylesheet" type="text/css" href="../public/datatables/buttons.dataTables.min.css">

  <link rel="stylesheet" type="text/css" href="../public/datatables/responsive.dataTables.min.css">

  <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>




</head>

<body class="hold-transition skin-blue-light sidebar-mini">
  <div class="wrapper">

    <header class="main-header">

      <!-- Logo -->
      <a href="escritorio.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="../files/usuarios/logo-Recuperado1.png" class="user-image"
            width="40px"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="../files/usuarios/logoTransparente.png" class="user-image" width="150px"></span>
      </a>

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Navegación</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-file-text-o"></i>
                <span class="label label-success"><?php echo $resnombrecotizaciones; ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">Cotizaciones Pendiente</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    <li>
                      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                        <table id="tbCotizacionesPendientes"
                          class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>IdCoti</th>
                            <th>Nombre</th>
                            <th>PV</th>
                          </thead>
                          <tbody>
                            <?php
                            while ($regcotizacionesDetalle = $rsptacotizacionesDetalle->fetch_object()) {
                            ?>
                              <tr>
                                <td><?php echo $regcotizacionesDetalle->idcotizacion; ?></td>
                                <td><?php echo $regcotizacionesDetalle->nombre; ?></td>
                                <td><?php echo number_format($regcotizacionesDetalle->total_venta, 2); ?></td>
                              </tr>
                            <?php
                            }
                            ?>
                          </tbody>
                          <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tfoot>
                        </table>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-file-text-o"></i>
                <span class="label label-success"><?php echo $resnombrecotizaciones2; ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">Cotizaciones Tienda Web</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                      <table id="tbCotizacionesTiendaWeb"
                        class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                          <th>IdCoti</th>
                          <th>Nombre</th>
                          <th>PV</th>
                        </thead>
                        <tbody>
                          <?php
                          while ($regcotizaciones2Detalle = $rsptacotizaciones2Detalle->fetch_object()) {
                          ?>
                            <tr>
                              <td><?php echo $regcotizaciones2Detalle->idcotizacion; ?></td>
                              <td><?php echo $regcotizaciones2Detalle->nombre; ?></td>
                              <td><?php echo number_format($regcotizaciones2Detalle->total_venta, 2); ?></td>
                            </tr>
                          <?php
                          }
                          ?>
                        </tbody>
                        <tfoot>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tfoot>
                      </table>
                    </div>
                  </ul>
                </li>
              </ul>
            </li>
            <!-- Messages: style can be found in dropdown.less-->

            <!-- User Account: style can be found in dropdown.less -->
            <?php
            $imagenUsuario = (!empty($_SESSION['imagen'])) ? $_SESSION['imagen'] : '1580489865.png';
            ?>
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="../files/usuarios/<?php echo $imagenUsuario; ?>" class="user-image" alt="User Image">
                <span class="hidden-xs">
                  <?php echo $_SESSION['nombre']; ?> ...NOMBRE SUCURSAL... <?php echo $resnombre; ?>
                </span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="../files/usuarios/<?php echo $imagenUsuario; ?>" class="img-circle" alt="User Image">
                  <p>
                    www.compusisgt.com - Software
                    <small>..</small>
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Cerrar</a>
                  </div>
                </li>
              </ul>
            </li>


          </ul>
        </div>

      </nav>
    </header>

    <style>
      .iconos-tama {
        width: 25px;
        transition: transform 0.3s ease, opacity 0.3s ease;
      }

      .iconos-cambio {
        width: 40px;
      }

      .iconos-escritorio {
        width: 80px;
      }

      .iconos-tama:hover {
        transform: scale(1.2);
        opacity: 0.7;
      }

      .sidebar-menu>li:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Ajusta la sombra a tu preferencia */
        background-color: #f0f0f0;
        /* Puedes cambiar el color de fondo al hacer hover */
        transition: box-shadow 0.3s ease, background-color 0.3s ease;
        /* Añade un efecto suave */
      }

      .iconos-escritorio {
        transition: transform 0.3s ease, opacity 0.3s ease;
      }

      .iconos-escritorio:hover {
        transform: scale(1.2);
        /* Aumenta el tamaño a un 120% */
        opacity: 0.7;
        /* Reduce la opacidad */
      }
    </style>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header"></li>
          <?php
          if ($_SESSION['escritorio'] == 1) {
            # code... 
            echo '<li>
                        <a href="escritorio.php">
                          <img class="iconos-tama efecto" src="../public/iconos/escritorio.png"> 
                          <span>Escritorio x Usuario</span>
                        </a>
                      </li>';
          }
          ?>
          <?php
          if ($_SESSION['escritorioxsucursal'] == 1) {
            # code... 
            echo '<li>
                        <a href="escritorioxsucursal.php">
                          <img class="iconos-tama efecto" src="../public/iconos/escritorio.png"> 
                          <span>Escritorio x Sucursal</span>
                        </a>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['salidaproducto'] == 1) {
            # code...
            echo '<li>
                        <a href="salida_pro_sucursal.php">
                          <img class="iconos-tama efecto" src="../public/iconos/salida.png"> 
                          <span>Salida-Entrada Produc a Sucursal</span>
                        </a>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['inventarioxsucursal'] == 1) {
            # code...
            echo '<li>
                        <a href="inventarioxsucursal.php">
                          <img class="iconos-tama efecto" src="../public/iconos/almacen.png"> 
                          <span>Inventario x Sucursal</span>
                        </a>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['inventarioxgeneral'] == 1) {
            # code...
            echo '<li>
                        <a href="inventarioxgeneral.php">
                          <img class="iconos-tama efecto" src="../public/iconos/almacen.png"> 
                          <span>Inventario x General</span>
                        </a>
                      </li>';
          }
          ?>
          <?php
          if ($_SESSION['inventarioxgeneralagrupado'] == 1) {
            # code...
            echo '<li>
                        <a href="inventarioxgeneralAgrupado.php">
                          <img class="iconos-tama efecto" src="../public/iconos/almacen.png"> 
                          <span>Inventario x General Agrupado</span>
                        </a>
                      </li>';
          }
          ?>          

          <?php
          if ($_SESSION['mesarestaurante'] == 1) {
            # code... 
            echo '<li>
                        <a href="add_mesa.php">
                          <img class="iconos-tama efecto" src="../public/iconos/mesa.png"> 
                          <span>Creacion Mesas</span>
                        </a> 
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['restauranteordenes'] == 1) {
            # code...
            echo '<li class="treeview"> 
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/tomarOrden.png">
                          <span>Gestion Ordenes</span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                          <li><a href="toma_orden.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Toma Ordenes</a></li>
                          <li><a href="orden_cocina_bar.php"><img class="iconos-tama efecto" src="../public/iconos/cocina.png">Ordenes Pantalla</a></li>
                        </ul>
                      </li>';
          }
          ?>



          <?php
          if ($_SESSION['restaurantecobros'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/restaurante.png"> 
                          <span>Cobros Restaurante</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                          <li><a href="venta_2.php"><img class="iconos-tama efecto" src="../public/iconos/restaurante.png"> Crear Cobro</a></li>
                          <li><a href="rtp_ordenAnuladas.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png">Ordenes Mesa Anuladas</a></li>
                          <li><a href="rtp_ordenAnuladasdetallado.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png">Ordenes Mesa Anuladas Detallado</a></li>
                        </ul>
                      </li>';
          }
          ?>


          <?php
          if ($_SESSION['salidas_inventario'] == 1) {
            # code...
            echo '<li>
                        <a href="salidas_inventario.php">
                          <img class="iconos-tama efecto" src="../public/iconos/almacen.png"> 
                          <span>Salidas/Entradas Inventarios</span>
                        </a>
                      </li>';
          }
          ?>




          <?php
          if ($_SESSION['orden_trabajo'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/almacen.png"> 
                          <span>Ordenes de Trabajo</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['orden_trabajo_crear']) && $_SESSION['orden_trabajo_crear'] == 1) {
              echo '<li><a href="admin_ordenes.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Crear Ordenes de Trabajo</a></li>';
            }

            if (isset($_SESSION['orden_trabajo_marca']) && $_SESSION['orden_trabajo_marca'] == 1) {
              echo '<li><a href="marca_vehiculo.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Creacion Marcas</a></li>';
            }

            if (isset($_SESSION['orden_trabajo_tecnico']) && $_SESSION['orden_trabajo_tecnico'] == 1) {
              echo '<li><a href="tecnico.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Tecnicos</a></li>';
            }
            if (isset($_SESSION['orden_trabajo_modelo']) && $_SESSION['orden_trabajo_modelo'] == 1) {
              echo '<li><a href="modelo.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Modelos</a></li>';
            }
            if (isset($_SESSION['orden_trabajo_tipo_equipo']) && $_SESSION['orden_trabajo_tipo_equipo'] == 1) {
              echo '<li><a href="tipo_equipo.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Tipo de Equipo</a></li>';
            }
            if (isset($_SESSION['orden_trabajo_colores']) && $_SESSION['orden_trabajo_colores'] == 1) {
              echo '<li><a href="colores.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Colores</a></li>';
            }



            echo '</ul>
                      </li>';
          }
          ?>
          <?php
          if ($_SESSION['tienda_web'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/almacen.png"> 
                          <span>Tienda en Linea</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['tienda_web_articulos']) && $_SESSION['tienda_web_articulos'] == 1) {
              echo '<li><a href="tienda_web_articulos.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Crear Artículos</a></li>';
            }
            if (isset($_SESSION['tienda_web_inicio']) && $_SESSION['tienda_web_inicio'] == 1) {
              echo '<li><a href="tienda_web_inicio.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Inicio</a></li>';
            }
            if (isset($_SESSION['tienda_web_nosotros']) && $_SESSION['tienda_web_nosotros'] == 1) {
              echo '<li><a href="tienda_web_nosotros.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Nosotros</a></li>';
            }
            if (isset($_SESSION['tienda_web_servicios']) && $_SESSION['tienda_web_servicios'] == 1) {
              echo '<li><a href="tienda_web_servicios.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Servicios</a></li>';
            }
            if (isset($_SESSION['tienda_web_contactanos']) && $_SESSION['tienda_web_contactanos'] == 1) {
              echo '<li><a href="tienda_web_articulos.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Contactanos</a></li>';
            }




            echo '</ul>
                      </li>';
          }
          ?>




          <?php
          if ($_SESSION['almacen'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/almacen.png"> 
                          <span>Almacén Central</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['almacen_crear_articulo']) && $_SESSION['almacen_crear_articulo'] == 1) {
              echo '<li><a href="articulo.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Crear Artículos</a></li>';
            }
            if (isset($_SESSION['almacen_crear_presentacion']) && $_SESSION['almacen_crear_presentacion'] == 1) {
              echo '<li><a href="crear_presentacion.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Crear Presentacion</a></li>';
            }
            if (isset($_SESSION['almacen_crear_categoria']) && $_SESSION['almacen_crear_categoria'] == 1) {
              echo '<li><a href="categoria.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Crear Categorías</a></li>';
            }

            if (isset($_SESSION['almacen_crear_sub_categoria']) && $_SESSION['almacen_crear_sub_categoria'] == 1) {
              echo '<li><a href="sub_categoria.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Sub Categorías</a></li>';
            }
            if (isset($_SESSION['almacen_asociar_sub_categoria']) && $_SESSION['almacen_crear_sub_categoria'] == 1) {
              echo '<li><a href="asociar_sub_categoria.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Asociar Sub Categorías</a></li>';
            }
            if (isset($_SESSION['almacen_crear_combos']) && $_SESSION['almacen_crear_combos'] == 1) {
              echo '<li><a href="ingreso_produccion.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Creacion Combos</a></li>';
            }
            if (isset($_SESSION['almacen_crear_sector']) && $_SESSION['almacen_crear_sector'] == 1) {
              echo '<li><a href="sector.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Creacion de Sectores</a></li>';
            }
            if (isset($_SESSION['almacen_crear_empresa_interna']) && $_SESSION['almacen_crear_empresa_interna'] == 1) {
              echo '<li><a href="empresa_in.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Creacion de Empresas Internas</a></li>';
            }


            echo '</ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['taller'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/salida.png"> 
                          <span>Taller</span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            /*if (isset($_SESSION['taller_ingreso_vehiculo']) && $_SESSION['taller_ingreso_vehiculo'] == 1) {
              echo '<li><a href="ingreso_vehiculo.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Ingreso Vehiculo</a></li>';
            }
            */
            if (isset($_SESSION['taller_ingreso_vehiculo']) && $_SESSION['taller_ingreso_vehiculo'] == 1) {
              echo '<li><a href="ingreso_vehiculov2.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Ingreso Vehiculo</a></li>';
            }
            /*
            if (isset($_SESSION['taller_mecanico']) && $_SESSION['taller_mecanico'] == 1) {
              echo '<li><a href="mecanico.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Mecanico</a></li>';
            }
            */
            echo '</ul>
                      </li>';
          }
          ?>


          <?php
          if ($_SESSION['compras'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/compras.png"> 
                          <span>Compras</span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['compras_ordenes_compra']) && $_SESSION['compras_ordenes_compra'] == 1) {
              echo '<li><a href="ordenes_compra.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Ordenes de Compra</a></li>';
            }
            if (isset($_SESSION['compras_revision_orden']) && $_SESSION['compras_revision_orden'] == 1) {
              echo '<li><a href="ingreso_compra.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Ingreso de Compra</a></li>';
            }
            if (isset($_SESSION['compras_ingresos']) && $_SESSION['compras_ingresos'] == 1) {
              echo '<li><a href="ingreso.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Ingresos</a></li>';
            }
            if (isset($_SESSION['compras_proveedores']) && $_SESSION['compras_proveedores'] == 1) {
              echo '<li><a href="proveedor.php"><img class="iconos-tama efecto" src="../public/iconos/proveedor.png">  Proveedores</a></li>';
            }
            if (isset($_SESSION['compras_gastos']) && $_SESSION['compras_gastos'] == 1) {
              echo '<li><a href="compras.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Gastos</a></li>';
            }
            if (isset($_SESSION['compras_rpt_ingresos']) && $_SESSION['compras_rpt_ingresos'] == 1) {
              echo '<li><a href="rpt_ingresos.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png">  Rpt Ingresos</a></li>';
            }
            echo '</ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['notaDebito'] == 1) {
            # code... 
            echo '<li>
                        <a href="nota_debito.php">
                          <img class="iconos-tama efecto" src="../public/iconos/compras.png"> 
                          <span>Nota Debito</span>
                        </a> 
                      </li>';
          }
          ?>


          <?php
          if ($_SESSION['ventas'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/venta.png"> 
                          <span>Ventas  </span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['ventas_facturacion']) && $_SESSION['ventas_facturacion'] == 1) {
              echo '<li><a href="venta.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Ventas </a></li>';
            }
            if (isset($_SESSION['ventas_facturacion']) && $_SESSION['ventas_facturacion'] == 1) {
              echo '<li><a href="venta2.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Ventas Rapida </a></li>';
            }
            /*if (isset($_SESSION['ventas_facturacion']) && $_SESSION['ventas_facturacion'] == 1) {
              echo '<li><a href="venta2_prueba.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Ventas Rapida </a></li>';
            }   */
            if (isset($_SESSION['ventas_servicios']) && $_SESSION['ventas_servicios'] == 1) {
              echo '<li><a href="venta_servicios.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Ventas - Servicios </a></li>';
            }

            if (isset($_SESSION['ventas_clientes']) && $_SESSION['ventas_clientes'] == 1) {
              echo '<li><a href="cliente.php"><img class="iconos-tama efecto" src="../public/iconos/cliente.png">  Clientes</a></li>';
            }
            if (isset($_SESSION['ventas_mensajero_transporte']) && $_SESSION['ventas_mensajero_transporte'] == 1) {
              echo '<li><a href="ventas_mensajero.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Ventas - Mensajero/Transporte</a></li>';
            }
            if (isset($_SESSION['ventas_facturacion']) && $_SESSION['ventas_mensajero_transporte'] == 1) {
              echo '<li><a href="opciones_pantallaventas.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Pantalla Ventas</a></li>';
            }
            echo '</ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['clientes'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/venta.png"> 
                          <span>Clientes CRM  </span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';

            if (isset($_SESSION['crear_clientes']) && $_SESSION['crear_clientes'] == 1) {
              echo '<li><a href="cliente.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">  Crear Clientes</a></li>';
            }
            if (isset($_SESSION['clientes_seguimiento']) && $_SESSION['clientes_seguimiento'] == 1) {
              echo '<li><a href="cliente_seguimiento.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Clientes - Seguimiento</a></li>';
              echo '<li><a href="rpt_clientes_nuevos.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Clientes - Nuevos </a></li>';
            }
            if (isset($_SESSION['clientes_seguimiento']) && $_SESSION['clientes_seguimiento'] == 1) {
              echo '<li><a href="cliente_seguimiento_rpt.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Reporte - Cliente/Seguimiento</a></li>';
            }
            if (isset($_SESSION['clientes_seguimiento']) && $_SESSION['clientes_seguimiento'] == 1) {
              echo '<li><a href="cliente_tareas_rpt.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Reporte - Cliente/Tareas</a></li>';
            }
            if (isset($_SESSION['clientes_seguimiento']) && $_SESSION['clientes_seguimiento'] == 1) {
              echo '<li><a href="cliente_evento_rpt.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Reporte - Cliente/Eventos</a></li>';
            }

            echo '</ul>
                      </li>';
          }
          ?>



          <?php
          if ($_SESSION['despachoventas'] == 1) {
            # code...
            echo '<li>
                        <a href="despacho_ventas.php">
                          <img class="iconos-tama efecto" src="../public/iconos/venta.png"> 
                          <span>Despacho Ventas</span>
                        </a>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['tenico'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/venta.png"> 
                          <span>Tecnico</span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';

            if (isset($_SESSION['tecnico_instalaciones_x_user']) && $_SESSION['tecnico_instalaciones_x_user'] == 1) {
              echo '<li><a href="tecnico_instalaciones_x_user.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Tecnico Instalaciones x User</a></li>';
            }
            if (isset($_SESSION['tecnico_instalaciones_general']) && $_SESSION['tecnico_instalaciones_general'] == 1) {
              echo '<li><a href="tecnico_instalaciones_general.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png">Tecnico Instalaciones General</a></li>';
            }

            echo '</ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['guiastransporte'] == 1) {
            # code...
            echo '<li class="treeview" >
                        <a href="#">
                          <img class="iconos-tama" src="../public/iconos/almacen.png">
                          <span>Guias Transporte</span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">                         
                          <li ><a href="cargadeguias.php"><img class="iconos-tama" src="../public/iconos/agregar.png"> Carga de Guias</a></li> 
                          <li ><a href="cotejacionvetas_guias.php"><img class="iconos-tama" src="../public/iconos/agregar.png">Cotejacion Ventas vrs Guias</a></li>
                          <li ><a href="rptcotejacionvetas_guias.php"><img class="iconos-tama" src="../public/iconos/reportes.png">Rpt Guias</a></li>
                        </ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['cajachica'] == 1) {
            # code... 
            echo '<li>
                        <a href="cajachica.php">
                          <img class="iconos-tama efecto" src="../public/iconos/venta.png"> 
                          <span>Caja Chica</span>
                        </a> 
                      </li>';
          }
          ?>



          <?php
          if ($_SESSION['notaCredito'] == 1) {
            # code... 
            echo '<li>
                        <a href="nota_credito.php">
                          <img class="iconos-tama efecto" src="../public/iconos/venta.png"> 
                          <span>Nota Credito</span>
                        </a> 
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['cotizaciones'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/cotizacion.png"> 
                          <span>Cotizaciones-Pedidos</span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                          <li><a href="cotizaciones_edit.php"><img class="iconos-tama efecto" src="../public/iconos/cotizacion.png"> Cotizaciones/Pedidos</a></li>
                          <li><a href="ordenes_pantallaCotizaciones.php"><img class="iconos-tama efecto" src="../public/iconos/cotizacion.png"> Pantalla Pedidos</a></li>
                        </ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['acceso'] == 1) {
            # code...
            echo '<li class="treeview">
                      <a href="#">
                        <img class="iconos-tama efecto" src="../public/iconos/acceso.png">
                        <span>Acceso</span>
                        <i class="fa fa-angle-left pull-right"></i>
                      </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['acceso_usuarios']) && $_SESSION['acceso_usuarios'] == 1) {
              echo '<li><a href="usuario.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Usuarios</a></li>';
            }
            if (isset($_SESSION['acceso_sucursales']) && $_SESSION['acceso_sucursales'] == 1) {
              echo '<li><a href="sucursal.php"><img class="iconos-tama efecto" src="../public/iconos/sucursales.png"> Sucursal</a></li>';
            }
            if (isset($_SESSION['acceso_mensajeros']) && $_SESSION['acceso_mensajeros'] == 1) {
              echo '<li><a href="mensajero.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Mensajero</a></li>';
            }
            if (isset($_SESSION['acceso_transportes']) && $_SESSION['acceso_transportes'] == 1) {
              echo '<li><a href="transporte.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Transporte</a></li>';
            }
            if (isset($_SESSION['acceso_vendedores']) && $_SESSION['acceso_vendedores'] == 1) {
              echo '<li><a href="vendedores.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Vendedores</a></li>';
            }
            if (isset($_SESSION['acceso_tecnicos']) && $_SESSION['acceso_tecnicos'] == 1) {
              echo '<li><a href="tecnicos.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Tecnicos</a></li>';
            }
            if (isset($_SESSION['acceso_cobradores']) && $_SESSION['acceso_cobradores'] == 1) {
              echo '<li><a href="cobradores.php"><img class="iconos-tama efecto" src="../public/iconos/usuarios.png"> Cobradores</a></li>';
            }

            echo '</ul>
                    </li>';
          }
          ?>
          <?php
          if ($_SESSION['consultac'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/report.png">
                          <span>Consulta Compras</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['consulta_compras_compras']) && $_SESSION['consulta_compras_compras'] == 1) {
              echo '<li><a href="comprasfecha.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Consulta Compras</a></li>';
            }
            if (isset($_SESSION['consulta_compras_detallado']) && $_SESSION['consulta_compras_detallado'] == 1) {
              echo '<li><a href="comprasxfecha_detalle.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Consulta Compras Detallado</a></li>';
            }
            echo '</ul>
                      </li>';
          }
          ?>
          <?php
          if ($_SESSION['consultav'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/report.png">
                          <span>Consulta Ventas</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['consulta_ventas_ventas']) && $_SESSION['consulta_ventas_ventas'] == 1) {
              echo '<li><a href="ventasxfecha.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Consulta Ventas</a></li>';
            }
            if (isset($_SESSION['consulta_ventas_detallado']) && $_SESSION['consulta_ventas_detallado'] == 1) {
              echo '<li><a href="ventasxfecha_detalle.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Consulta Ventas Detallado</a></li>';
            }
            if (isset($_SESSION['consulta_ventas_anuladas']) && $_SESSION['consulta_ventas_anuladas'] == 1) {
              echo '<li><a href="ventasxfecha_anuladas.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Consulta Ventas x Fecha Anuladas</a></li>';
            }
            echo '</ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['reportes'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/report.png">
                          <span>Reportes</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                          <li><a href="rptarticulos_vendidos.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Articulos mas Vendidos</a></li> 
                          <li><a href="rptarticulos_vendidosxcliente.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Articulos + Vendidos x Cliente </a></li>  
                          <li><a href="rptarticulos_comprados.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Articulos mas Comprados</a></li> 
                          <li><a href="rptarticulos_vendidosxproveedor.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Articulos + Comprados x Proveedor </a></li> 

                          <li><a href="kardex_productoCompraVenta.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> kardex Productos </a></li>

                          <li><a href="salidaxfecha.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Salida productos </a></li>

                          <li><a href="salidaxfechaDetalle.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Salida productos  Detalle</a></li>

                          <li><a href="entradaxfecha.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Entrada productos </a></li>
                          <li><a href="entradaxfechaDetalle.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Entrada productos  Detalle</a></li>   

                          <li><a href="ventasxfechaAgrupadas.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Ventas Agrupadas x Usuario</a></li> 
                          <li><a href="cotizaciondesxfecha.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Consulta Cotizaciones</a></li>
                          <li><a href="cotizaciondesxfechaDetalle.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Consulta Cotizaciones Detallado</a></li>
                          <li><a href="cuadres_caja_cierre.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Cierre Caja</a></li>
                          
                        </ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['CuentasXcobrar'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/ctaxcobrar.png">
                          <span>Cuentas X Cobrar</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['cta_cobrar_cobrar']) && $_SESSION['cta_cobrar_cobrar'] == 1) {
              echo '<li><a href="cuentasporcobrar.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Cuentas Cobrar</a></li>';
            }
            if (isset($_SESSION['cta_cobrar_reporte']) && $_SESSION['cta_cobrar_reporte'] == 1) {
              echo '<li><a href="rptctasxcobrar_rangofechas.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Reporte Ctas cobradas</a></li>';
            }
            if (isset($_SESSION['cta_cobrar_ctas_pagar']) && $_SESSION['cta_cobrar_ctas_pagar'] == 1) {
              echo '<li><a href="rptctasxcobrar_rangofechas_xproveedor.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Reporte Ctas pagadas x Cliente</a></li>';
            }
            echo '</ul>
                      </li>';
          }
          ?>
          <?php
          if ($_SESSION['cuentasxpagar'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/ctaxpagar.png">
                          <span>Ctas x Pagar</span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['cta_pagar_generar']) && $_SESSION['cta_pagar_generar'] == 1) {
              echo '<li><a href="cuentasporpagar.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Generar Cta x pagar</a></li>';
            }
            if (isset($_SESSION['cta_pagar_reporte_pagadas']) && $_SESSION['cta_pagar_reporte_pagadas'] == 1) {
              echo '<li><a href="rptctasxpagar_rangofechas.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Reporte Ctas pagadas</a></li>';
            }
            if (isset($_SESSION['cta_pagar_reporte_pagadasxproveedor']) && $_SESSION['cta_pagar_reporte_pagadasxproveedor'] == 1) {
              echo '<li><a href="rptctasxpagar_rangofechas_xproveedor.php"><img class="iconos-tama efecto" src="../public/iconos/reporte.png"> Reporte Ctas pagadas x proveedor</a></li>';
            }
            echo '</ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['nomina'] == 1) {
            # code... 
            echo '<li id="mconbilidad" class="treeview" id="">
                        <a href="#">
                          <img class="iconos-tama" src="../public/iconos/nomina.png">
                          <span>Nomina</span>
                           
                           <i class="fa fa-angle-left pull-right"></i>
                        </a> 
                        <ul class="treeview-menu">';
            if (isset($_SESSION['nomina_empleados']) && $_SESSION['nomina_empleados'] == 1) {
              echo '<li><a href="nomina_empleado.php"><img class="iconos-tama" src="../public/iconos/proveedor.png"> Ficha Empleado</a></li>';
            }
            if (isset($_SESSION['nomina_empleados']) && $_SESSION['nomina_empleados'] == 1) {
              echo '<li><a href="rpt_registro_ingresoEmpleados.php"><img class="iconos-tama" src="../public/iconos/reporte.png">Rpt Registro Ingreso Empleados</a></li>';
            }
            if (isset($_SESSION['nomina_empleados']) && $_SESSION['nomina_empleados'] == 1) {
              echo '<li><a href="nomina_otrosdecuentos_empleado.php"><img class="iconos-tama" src="../public/iconos/prestamo.png"> Operaciones Empleado</a></li>';
            }
            if (isset($_SESSION['nomina_pagos']) && $_SESSION['nomina_pagos'] == 1) {
              echo '<li><a href="nomina_pagos.php"><img class="iconos-tama" src="../public/iconos/pagos.png"> Pagos Nomina</a></li>';
            }
            if (isset($_SESSION['nomina_pagos_14_aguinaldo']) && $_SESSION['nomina_pagos_14_aguinaldo'] == 1) {
              echo '<li><a href="nomina_pagos_14_aguinaldo.php"><img class="iconos-tama" src="../public/iconos/pagos.png"> Pagos Nomina 14-Aguinaldo</a></li>';
            }
            if (isset($_SESSION['nomina_pagos_vacaciones']) && $_SESSION['nomina_pagos_vacaciones'] == 1) {
              echo '<li><a href="nomina_pagos_vacaciones.php"><img class="iconos-tama" src="../public/iconos/pagos.png"> Empleados Vacaciones</a></li>';
            }


            echo '</ul>
                      </li>';
          }
          ?>

          <?php
          if ($_SESSION['parqueo'] == 1) {
            # code...
            echo '<li class="treeview">
                        <a href="#">
                          <img class="iconos-tama efecto" src="../public/iconos/ctaxpagar.png">
                          <span>Parqueo</span>
                           <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">';
            if (isset($_SESSION['parqueo_tarifas']) && $_SESSION['parqueo_tarifas'] == 1) {
              echo '<li><a href="parqueo_tarifas.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Tarifas</a></li>';
            }
            if (isset($_SESSION['parqueo_Info_Ticke_Fac']) && $_SESSION['parqueo_Info_Ticke_Fac'] == 1) {
              echo '<li><a href="parqueo_Info_Ticke_Fac.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Info Ticket/Factura</a></li>';
            }
            if (isset($_SESSION['parqueo_Operaciones']) && $_SESSION['parqueo_Operaciones'] == 1) {
              echo '<li><a href="parqueo_Operaciones.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Operaciones</a></li>';
            }
            if (isset($_SESSION['Parqueo_Rpt_Ticket']) && $_SESSION['Parqueo_Rpt_Ticket'] == 1) {
              echo '<li><a href="Parqueo_Rpt_Ticket.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Rpt Tickets</a></li>';
            }

            if (isset($_SESSION['Parqueo_Rpt_Graficas']) && $_SESSION['Parqueo_Rpt_Graficas'] == 1) {
              echo '<li><a href="Parqueo_Rpt_Graficas.php"><img class="iconos-tama efecto" src="../public/iconos/agregar.png"> Rpt Graficas</a></li>';
            }

            echo '</ul>
                      </li>';
          }
          ?>

          <li>
            <a href="#">
              <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
              <small class="label pull-right bg-yellow">IT</small>
            </a>
          </li>

        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Script para actualizar tablas de cotizaciones automáticamente -->
    <script>
      $(document).ready(function() {
        // Función para actualizar la tabla de Cotizaciones Pendientes
        function actualizarCotizacionesPendientes() {
          $.ajax({
            url: '../ajax/cotizaciones.php?op=listarCotizacionesPendientes',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
              var tbody = $('#tbCotizacionesPendientes tbody');
              tbody.empty();

              if (data.length > 0) {
                $.each(data, function(index, item) {
                  var row = '<tr>' +
                    '<td>' + item.idcotizacion + '</td>' +
                    '<td>' + item.nombre + '</td>' +
                    '<td>' + item.total_venta + '</td>' +
                    '</tr>';
                  tbody.append(row);
                });
              } else {
                tbody.append('<tr><td colspan="3" class="text-center">No hay cotizaciones pendientes</td></tr>');
              }
            },
            error: function() {
              console.log('Error al cargar cotizaciones pendientes');
            }
          });
        }

        // Función para actualizar la tabla de Cotizaciones Tienda Web
        function actualizarCotizacionesTiendaWeb() {
          $.ajax({
            url: '../ajax/cotizaciones.php?op=listarCotizacionesTiendaWeb',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
              var tbody = $('#tbCotizacionesTiendaWeb tbody');
              tbody.empty();

              if (data.length > 0) {
                $.each(data, function(index, item) {
                  var row = '<tr>' +
                    '<td>' + item.idcotizacion + '</td>' +
                    '<td>' + item.nombre + '</td>' +
                    '<td>' + item.total_venta + '</td>' +
                    '</tr>';
                  tbody.append(row);
                });
              } else {
                tbody.append('<tr><td colspan="3" class="text-center">No hay cotizaciones de tienda web</td></tr>');
              }
            },
            error: function() {
              console.log('Error al cargar cotizaciones tienda web');
            }
          });
        }

        // Función para actualizar los contadores (badges)
        function actualizarContadores() {
          $.ajax({
            url: '../ajax/cotizaciones.php?op=obtenerTotalesCotizaciones',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
              // Actualizar el badge de Cotizaciones Pendientes (primer dropdown)
              $('.dropdown.messages-menu').eq(0).find('.label').text(data.totalPendientes);

              // Actualizar el badge de Cotizaciones Tienda Web (segundo dropdown)
              $('.dropdown.messages-menu').eq(1).find('.label').text(data.totalTiendaWeb);
            },
            error: function() {
              console.log('Error al cargar totales de cotizaciones');
            }
          });
        }

        // Actualizar inmediatamente al cargar la página
        actualizarCotizacionesPendientes();
        actualizarCotizacionesTiendaWeb();
        actualizarContadores();

        // Actualizar cada 30 segundos (30000 milisegundos)
        // Puedes cambiar este valor según tus necesidades: 10000 = 10 seg, 30000 = 30 seg, 60000 = 1 min
        setInterval(function() {
          actualizarCotizacionesPendientes();
          actualizarCotizacionesTiendaWeb();
          actualizarContadores();
        }, 30000); // 30 segundos
      });
    </script>