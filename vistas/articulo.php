<?php
// Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


// Verificamos si el usuario está logueado
if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
  exit();
} else {
  require 'header.php';

  // Verificamos si tiene permisos
  if ($_SESSION['almacen_crear_articulo'] == 1) {
    require_once "../modelos/Articulo.php";
    $articulo = new Articulo();
    $rsptac = $articulo->totalcostoinventario();
    $regc = $rsptac->fetch_object();
    $totalc = $regc->total_compra;

    $rsptac_v = $articulo->totalventainventario();
    $regc_v = $rsptac_v->fetch_object();
    $totalc_v = $regc_v->total_venta;

    $rsPresen = $articulo->presentacionprecioventa();
    $rsPresen_v = $rsPresen->fetch_object();
    $nombrepresentacion1 = $rsPresen_v->nombre_presentacion1;
    $nombrepresentacion2 = $rsPresen_v->nombre_presentacion2;
    $nombrepresentacion3 = $rsPresen_v->nombre_presentacion3;
    $nombrepresentacion4 = $rsPresen_v->nombre_presentacion4;
    $nombrepresentacion5 = $rsPresen_v->nombre_presentacion5;
    $nombrepresentacion6 = $rsPresen_v->nombre_presentacion6;
    $nombrepresentacion7 = $rsPresen_v->nombre_presentacion7;
    $nombrepresentacion8 = $rsPresen_v->nombre_presentacion8;
    $nombrepresentacion9 = $rsPresen_v->nombre_presentacion9;
    $nombrepresentacion10 = $rsPresen_v->nombre_presentacion10;
    $nombrepresentacion11 = $rsPresen_v->nombre_presentacion11;
    $nombrepresentacion12 = $rsPresen_v->nombre_presentacion12;
    $nombrepresentacion13 = $rsPresen_v->nombre_presentacion13;
    $nombrepresentacion14 = $rsPresen_v->nombre_presentacion14;
    $nombrepresentacion15 = $rsPresen_v->nombre_presentacion15;
    $nombrepresentacion16 = $rsPresen_v->nombre_presentacion16;
    $nombrepresentacion17 = $rsPresen_v->nombre_presentacion17;
    $nombrepresentacion18 = $rsPresen_v->nombre_presentacion18;
    $nombrepresentacion19 = $rsPresen_v->nombre_presentacion19;
    $nombrepresentacion20 = $rsPresen_v->nombre_presentacion20;
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
                    Articulos
                    <small>Almacen</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Articulos</li>
                  </ol>

                </section>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="small-box bg-aqua" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 5px;">
                  <div class="inner">
                    <h3><sup style="font-size: 20px">Q</sup> <?php echo number_format($totalc, 2, '.', ','); ?></h3>
                    <p style="font-size: 16px; font-weight: 500;">Total Inventario <span
                        class="label label-primary">Costo</span></p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-cube"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="small-box bg-green" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 5px;">
                  <div class="inner">
                    <h3><sup style="font-size: 20px">Q</sup> <?php echo number_format($totalc_v, 2, '.', ','); ?></h3>
                    <p style="font-size: 16px; font-weight: 500;">Total Inventario <span class="label label-success">Precio
                        Venta</span></p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-cash"></i>
                  </div>
                </div>
              </div>
              <div class=" with-border">
                <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar"
                    onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                </h1>

                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <!-- Filtro de Estado -->
                <div class="row" style="margin-bottom: 20px;">
                  <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12">
                    <label style="color: #333; font-weight: 600;"><i class="fa fa-filter"></i> Mostrar por Estado:</label>
                    <div class="input-group">
                      <select name="filtro_estado" id="filtro_estado" class="form-control selectpicker"
                        data-style="btn-default">
                        <option value="1" data-content="<i class='fa fa-check-circle text-green'></i> Activados" selected>
                        </option>
                        <option value="0" data-content="<i class='fa fa-times-circle text-red'></i> Desactivados"></option>
                      </select>
                      <span class="input-group-btn">
                        <button class="btn btn-primary" onclick="listar()"><i class="fa fa-search"></i> Filtrar
                          Tabla</button>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- Fin Filtro de Estado -->
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Fecha Venci</th>
                    <th>Categoría</th>
                    <th>Sub Categoría</th>
                    <th>Descripcion</th>
                    <th>Descripcion 2</th>
                    <th>Stock Actualizar</th>
                    <th>Stock</th>
                    <th>Stock Minimo</th>
                    <th>Imagen</th>
                    <th>Codigo</th>
                    <th>P.C.</th>
                    <th>P.V.</th>
                    <th>CONDICION</th>
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
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Nombre Sat(*):</label>
                    <input type="hidden" name="idarticulo" id="idarticulo">
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Sucursal Una/Todas(*):</label>
                    <input type="text" class="form-control" name="crearArticuloSucursal" id="crearArticuloSucursal" value="Todas" readonly>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Facturar en Cero(*):</label>
                    <select name="facturar_cero" id="facturar_cero" class="form-control selectpicker" required="">
                      <option value="SI">SI</option>
                      <option value="NO">NO</option>

                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Categoría(*):</label>
                    <select id="idcategoria" name="idcategoria" class="form-control selectpicker" data-live-search="true"
                      required></select>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Sub Categoría(*):</label>
                    <select id="idsubcategoria" name="idsubcategoria" class="form-control selectpicker"
                      data-live-search="true" required></select>
                  </div>

                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Descripcion:</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Ubicacion x Sucursal:</label>
                    <input type="text" class="form-control" name="descripcion_2" id="descripcion_2">
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Produc/Consignacion(*):</label>
                    <select name="producto_consignacion" id="producto_consignacion" class="form-control selectpicker"
                      required="">
                      <option value="SI">SI</option>
                      <option value="NO">NO</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label>Aplica Impuestos(*):</label>
                    <select name="aplica_impuestos" id="aplica_impuestos" class="form-control selectpicker" required="">
                      <option value="SI">SI</option>
                      <option value="NO">NO</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Aplica Comision(*):</label>
                    <select name="aplica_comision" id="aplica_comision" class="form-control selectpicker" required="">
                      <option value="SI">SI</option>
                      <option value="NO">NO</option>

                    </select>
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Stock(*):</label>
                    <input type="number" step="any" class="form-control" name="stock" id="stock" required>
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Stock Minimo(*):</label>
                    <input type="number" step="any" class="form-control" name="stockminimo" id="stockminimo" required>
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Stock Maximo(*):</label>
                    <input type="number" step="any" class="form-control" name="stockmaximo" id="stockmaximo" required>
                  </div>

                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Imagen:</label>
                    <input type="file" class="form-control" name="imagen" id="imagen">
                    <input type="hidden" name="imagenactual" id="imagenactual">
                    <img src="" width="150px" height="120px" id="imagenmuestra">
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default" style="border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                      <div class="panel-heading"
                        style="background-color: #f8f9fa; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom: 2px solid #3c8dbc; padding: 10px 15px;">
                        <h3 class="panel-title" style="font-weight: bold; color: #3c8dbc; font-size: 16px;"><i
                            class="fa fa-barcode"></i> Gestión de Códigos y Etiquetas</h3>
                      </div>
                      <div class="panel-body" style="padding-bottom: 5px;">
                        <div class="row">
                          <!-- CÓDIGO -->
                          <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label>Código Barras/Principal:</label>
                            <div class="input-group">
                              <input type="text" class="form-control" name="codigo" id="codigo"
                                placeholder="Ingrese o escanee código">
                              <span class="input-group-btn">
                                <button class="btn btn-success" type="button" onclick="generarbarcode()"
                                  title="Autogenerar Código"><i class="fa fa-magic"></i> Generar</button>
                              </span>
                            </div>
                            <div style="margin-top: 10px; display: flex; gap: 5px;">
                              <button class="btn btn-info btn-xs" style="flex: 1;" type="button" onclick="imprimir()"><i
                                  class="fa fa-print"></i> Imp. Horizontal</button>
                              <button class="btn btn-info btn-xs" style="flex: 1;" type="button" onclick="imprimir2()"><i
                                  class="fa fa-print"></i> Imp. Vertical</button>
                            </div>
                            <div id="print" style="margin-top: 10px; text-align: center;">
                              <svg id="barcode"></svg>
                            </div>
                          </div>

                          <!-- SKU -->
                          <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label>Código SKU / Auxiliar:</label>
                            <input type="text" class="form-control" name="codigo_sku" id="codigo_sku"
                              placeholder="Código interno SKU">
                          </div>

                          <!-- IMPRESIÓN MÚLTIPLE -->
                          <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label>Cantidad (# de Códigos):</label>
                            <input type="number" class="form-control" name="num_codigos" id="num_codigos"
                              placeholder="Cantidad de copias a imprimir" min="1">

                            <div style="margin-top: 10px;">
                              <label style="font-size: 12px; color: #777;">Opciones de Impresión Múltiple:</label>
                              <div style="display: flex; flex-direction: column; gap: 5px;">
                                <div style="display: flex; gap: 5px;">
                                  <button class="btn btn-success btn-sm" style="flex: 1;" type="button" onclick="etiqueta()"
                                    title="Etiqueta Normal"><i class="fa fa-tag"></i> Etiqueta</button>
                                  <button class="btn btn-info btn-sm" style="flex: 1;" type="button" onclick="imprimirUno()"
                                    title="Formato Código 1"><i class="fa fa-barcode"></i> Cod 1</button>
                                  <button class="btn btn-info btn-sm" style="flex: 1;" type="button"
                                    onclick="imprimirUno1()" title="Formato Código 2"><i class="fa fa-barcode"></i> Cod
                                    2</button>
                                </div>
                                <div style="display: flex; gap: 5px;">
                                  <button class="btn btn-warning btn-sm" style="flex: 1;" type="button"
                                    onclick="imprimiCarta1()"><i class="fa fa-file-text-o"></i> Carta 1</button>
                                  <button class="btn btn-danger btn-sm" style="flex: 1;" type="button"
                                    onclick="imprimiCarta2()"><i class="fa fa-file-text-o"></i> Carta 2</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                    style="border-top:1px solid #ddd; margin-top:5px; margin-bottom:15px;"></div>
                  <div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12">
                    <label>Tipo Cálculo:</label>
                    <select name="modo_calculo" id="modo_calculo" class="form-control selectpicker"
                      onchange="cambiarModoCalculo()">
                      <option value="porcentaje">Por % Ganancia</option>
                      <option value="libre">Precio Libre (P.V)</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12">
                    <label>P.C(*):</label>
                    <input type="number" step="any" class="form-control" name="precio_compra" id="precio_compra"
                      onchange="mostrarprecioventa()" onkeyup="mostrarprecioventa()" required>
                  </div>
                  <div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12">
                    <label>% Ganancia(*):</label>
                    <input type="number" step="any" class="form-control" name="pocentaje_ganacia" id="pocentaje_ganacia"
                      onchange="mostrarprecioventa()" onkeyup="mostrarprecioventa()" required>
                  </div>

                  <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <label>P.V(*):</label>
                    <input type="number" step="any" class="form-control" name="precio_venta" id="precio_venta" readonly
                      onchange="mostrarprecioventa()" onkeyup="mostrarprecioventa()">
                    <input type="hidden" step="any" class="form-control" name="precio_ventaNocturno"
                      id="precio_ventaNocturno" value="0">
                  </div>

                  <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <label>Ganancia Q(*):</label>
                    <input type="number" step="any" class="form-control" name="ganacia_articulo" id="ganacia_articulo"
                      readonly>
                  </div>



                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Descuento Q/%(*):</label>
                    <input type="number" step="any" class="form-control" name="descuento_porcentaje"
                      id="descuento_porcentaje" required>
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Precio Descuento(*):</label>
                    <input type="number" step="any" class="form-control" name="precio_descuento" id="precio_descuento"
                      readonly="">
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Tipo Producto(*):</label>
                    <select name="tipo_producto" id="tipo_producto" class="form-control selectpicker" required="">
                      <option value="Productos">Productos</option>
                      <option value="Servicios">Servicios</option>
                      <option value="Combos">Combos</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label>Precio Activo(*):</label>
                    <select name="precio_activo_si_no" id="precio_activo_si_no" class="form-control selectpicker"
                      required="">
                      <option value="NO">SE PUEDE MODIFICAR</option>
                      <option value="SI">NO SE PUEDE MODIFICAR</option>
                    </select>
                  </div>
                  <!-- <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label>Empresa Interna(*):</label>
                            <select id="idempresa" name="idempresa" class="form-control selectpicker" data-live-search="true" ></select>
                          </div>-->
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box box-default box-solid">
                      <div class="box-header with-border">
                        <h3 class="box-title">Rangos de Precios, los rangos tienen que ser mayor a 3 cada rango</h3>

                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                        </div>
                        <!-- /.box-tools -->
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body">
                        <div class="box box-primary"></div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Rangos1(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango1" id="precio_rango1"
                            required>
                          <input type="number" step="any" class="form-control" name="precio_rango1_Dos"
                            id="precio_rango1_Dos" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Rangos2(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango2" id="precio_rango2"
                            required>
                          <input type="number" step="any" class="form-control" name="precio_rango2_Dos"
                            id="precio_rango2_Dos" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Rangos3(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango3" id="precio_rango3"
                            required>
                          <input type="number" step="any" class="form-control" name="precio_rango3_Dos"
                            id="precio_rango3_Dos" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Taller Mecanico Q Rangos 1(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango1_Mecanico"
                            id="precio_rango1_Mecanico" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Taller Mecanico Q Rangos 2(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango2_MecanicoDos"
                            id="precio_rango2_MecanicoDos" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Taller Mecanico Q Rangos 3(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango3_MecanicoTres"
                            id="precio_rango3_MecanicoTres" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Distribuidor Q Rangos 1(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango1_Distribuidor"
                            id="precio_rango1_Distribuidor" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Distribuidor Q Rangos 2(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango2_DistribuidorDos"
                            id="precio_rango2_DistribuidorDos" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Distribuidor Q Rangos 3(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango3_DistribuidorTres"
                            id="precio_rango3_DistribuidorTres" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Mayorista Q Rangos 1(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango1_Mayorista"
                            id="precio_rango1_Mayorista" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Mayorista Q Rangos 2(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango2_MayoristaDos"
                            id="precio_rango2_MayoristaDos" required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <label>Mayorista Q Rangos 3(*):</label>
                          <input type="number" step="any" class="form-control" name="precio_rango3_MayoristaTres"
                            id="precio_rango3_MayoristaTres" required>
                        </div>


                      </div>
                      <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                  </div>





                  <!-- <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
 
                                                                                                                                                                        
                          </div>  -->
                  <style>
                    .centrar-texto {
                      text-align: center;
                      color: blue;
                      font-weight: bold;
                    }
                  </style>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <!-- Unidad -->
                    <?php
                    if (!empty($nombrepresentacion1) && $nombrepresentacion1 !== "NA" && $nombrepresentacion1 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_01" id="nombre_01"
                          value="<?php echo $nombrepresentacion1; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_unidad" id="stock_unidad"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_unidad" id="precio_unidad"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <!-- Blister -->
                    <?php
                    if (!empty($nombrepresentacion2) && $nombrepresentacion2 !== "NA" && $nombrepresentacion2 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_02" id="nombre_02"
                          value="<?php echo $nombrepresentacion2; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_blister" id="stock_blister"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_blister" id="precio_blister"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>

                    <!-- Caja -->
                    <?php
                    if (!empty($nombrepresentacion3) && $nombrepresentacion3 !== "NA" && $nombrepresentacion3 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_03" id="nombre_03"
                          value="<?php echo $nombrepresentacion3; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_caja" id="stock_caja"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_caja" id="precio_caja"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>

                    <!-- Fardo -->
                    <?php
                    if (!empty($nombrepresentacion4) && $nombrepresentacion4 !== "NA" && $nombrepresentacion4 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_04" id="nombre_04"
                          value="<?php echo $nombrepresentacion4; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_fardo" id="stock_fardo"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_fardo" id="precio_fardo"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>

                    <!-- Sacos -->
                    <?php
                    if (!empty($nombrepresentacion5) && $nombrepresentacion5 !== "NA" && $nombrepresentacion5 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_05" id="nombre_05"
                          value="<?php echo $nombrepresentacion5; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_sacos" id="stock_sacos"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_sacos" id="precio_sacos"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>

                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    if (!empty($nombrepresentacion6) && $nombrepresentacion6 !== "NA" && $nombrepresentacion6 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_06" id="nombre_06"
                          value="<?php echo $nombrepresentacion6; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_paquete" id="stock_paquete"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_paquete" id="precio_paquete"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion7) && $nombrepresentacion7 !== "NA" && $nombrepresentacion7 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_07" id="nombre_07"
                          value="<?php echo $nombrepresentacion7; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_07" id="stock_07"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_07" id="precio_07"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion8) && $nombrepresentacion8 !== "NA" && $nombrepresentacion8 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_08" id="nombre_08"
                          value="<?php echo $nombrepresentacion8; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_08" id="stock_08"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_08" id="precio_08"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion9) && $nombrepresentacion9 !== "NA" && $nombrepresentacion9 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_09" id="nombre_09"
                          value="<?php echo $nombrepresentacion9; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_09" id="stock_09"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_09" id="precio_09"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion10) && $nombrepresentacion10 !== "NA" && $nombrepresentacion10 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_10" id="nombre_10"
                          value="<?php echo $nombrepresentacion10; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_10" id="stock_10"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_10" id="precio_10"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    if (!empty($nombrepresentacion11) && $nombrepresentacion11 !== "NA" && $nombrepresentacion11 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_11" id="nombre_11"
                          value="<?php echo $nombrepresentacion11; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_11" id="stock_11"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_11" id="precio_11"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion12) && $nombrepresentacion12 !== "NA" && $nombrepresentacion12 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_12" id="nombre_12"
                          value="<?php echo $nombrepresentacion12; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_12" id="stock_12"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_12" id="precio_12"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion13) && $nombrepresentacion13 !== "NA" && $nombrepresentacion13 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_13" id="nombre_13"
                          value="<?php echo $nombrepresentacion13; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_13" id="stock_13"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_13" id="precio_13"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion14) && $nombrepresentacion14 !== "NA" && $nombrepresentacion14 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_14" id="nombre_14"
                          value="<?php echo $nombrepresentacion14; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_14" id="stock_14"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_14" id="precio_14"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion15) && $nombrepresentacion15 !== "NA" && $nombrepresentacion15 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_15" id="nombre_15"
                          value="<?php echo $nombrepresentacion15; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_15" id="stock_15"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_15" id="precio_15"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    if (!empty($nombrepresentacion16) && $nombrepresentacion16 !== "NA" && $nombrepresentacion16 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_16" id="nombre_16"
                          value="<?php echo $nombrepresentacion16; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_16" id="stock_16"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_16" id="precio_16"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion17) && $nombrepresentacion17 !== "NA" && $nombrepresentacion17 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_17" id="nombre_17"
                          value="<?php echo $nombrepresentacion17; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_17" id="stock_17"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_17" id="precio_17"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion18) && $nombrepresentacion18 !== "NA" && $nombrepresentacion18 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_18" id="nombre_18"
                          value="<?php echo $nombrepresentacion18; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_18" id="stock_18"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_18" id="precio_18"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion19) && $nombrepresentacion19 !== "NA" && $nombrepresentacion19 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_19" id="nombre_19"
                          value="<?php echo $nombrepresentacion19; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_19" id="stock_19"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_19" id="precio_19"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($nombrepresentacion19) && $nombrepresentacion19 !== "NA" && $nombrepresentacion19 !== "0") {
                    ?>
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control centrar-texto" name="nombre_20" id="nombre_20"
                          value="<?php echo $nombrepresentacion20; ?>" readonly>
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="stock_20" id="stock_20"
                              placeholder="Stock">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="number" step="any" class="form-control" name="precio_20" id="precio_20"
                              placeholder="Precio">
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"
                      style="text-align: center; margin-top: 20px;">
                      <button class="btn btn-primary" type="submit" id="btnGuardar"
                        style="padding: 10px 40px; margin-right: 15px; font-size: 15px;">
                        <i class="fa fa-save"></i> Guardar
                      </button>
                      <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"
                        style="padding: 10px 40px; font-size: 15px;">
                        <i class="fa fa-arrow-circle-left"></i> Cancelar
                      </button>
                    </div>
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
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
  <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
  <script type="text/javascript" src="scripts/articulo.js"></script>
<?php
}
ob_end_flush();
?>