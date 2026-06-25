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
  if ($_SESSION['cotizaciones'] == 1) {

    require_once "../modelos/Consultas.php";
    $consulta = new Consultas();
    $rsptav = $consulta->totalventahoy();
    $regv = $rsptav->fetch_object();
    $totalv = $regv->total_venta;

    $res_efectivo = $consulta->totalventahoyefectivo();
    $resv_efectivo = $res_efectivo->fetch_object();
    $restotalpagoefectivo = $resv_efectivo->total_venta;

    $res_credito = $consulta->totalventahoyCredito();
    $resv_credito = $res_credito->fetch_object();
    $restotalpagocredito = $resv_credito->total_venta;


    $res_tarjeta = $consulta->totalventahoyTarjeta();
    $resv_tarjeta = $res_tarjeta->fetch_object();
    $restotalpagotarjeta = $resv_tarjeta->total_venta;


    $rsptavc = $consulta->totalefectivoiniciocaja();
    $regvc = $rsptavc->fetch_object();
    $restotalefectivo = $regvc->totalefectivo;
    $resApertura = $regvc->tipo_operacion;

    $rsptaCalculoDescuento = $consulta->tipoCalculoDescuento();
    $regvCalDes = $rsptaCalculoDescuento->fetch_object();
    $calculodescuento = $regvCalDes->calculo_descuento;

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
                    Cotizaciones / Pedidos X Sucursal
                    <small>Procesa tu Cotizacion</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Cotizaciones</li>
                  </ol>

                </section>
                <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar"
                    onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar (F8)</button> </h1>
              </div>

              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Fecha Inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte"
                    value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Fecha Fin</label>
                  <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte"
                    value="<?php echo date("Y-m-d"); ?>">
                  <button class="btn btn-success btn-block" onclick="listar()">Generar Ventas</button>
                </div>
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th># Cot</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>T. V</th>
                    <th>T. V. Des</th>
                    <th>Fecha</th>
                    <th>Cobro SI/NO</th>
                    <th># Interno</th>
                    <th>Forma Pago</th>
                    <th>Estado</th>
                    <th>Destino</th>
                    <th>Comentario</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th># Cot</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>T. V</th>
                    <th>T. V. Des</th>
                    <th>Fecha</th>
                    <th>Cobro SI/NO</th>
                    <th># Interno</th>
                    <th>Forma Pago</th>
                    <th>Estado</th>
                    <th>Destino</th>
                    <th>Comentario</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 800px;" id="formularioregistros">

                <form name="formulario" id="formulario" method="POST">

                  <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                      <input type="hidden" class="form-control" title="Descuento Cliente" name="descuento_cliente"
                        id="descuento_cliente" maxlength="20" placeholder="0" value="0" readonly>
                      <input type="hidden" name="idcotizacion" id="idcotizacion">
                      <input type="hidden" name="datos1" id="datos1">

                      <div class="input-group">
                        <input type="text" class="form-control" name="codigo_cliente" id="codigo_cliente" maxlength="20"
                          placeholder="CODIGO">
                        <span class="input-group-btn">
                          <button class="btn btn-success" onclick="validarCodigo()" type="button">
                            <i class="fa fa-users"></i>
                          </button>
                        </span>
                      </div>
                    </div>

                    <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                      <div class="input-group">
                        <input type="text" class="form-control" onchange="validarnit()" name="nit" id="nit" maxlength="20"
                          value="CF" placeholder="NIT">
                        <span class="input-group-btn">
                          <button class="btn btn-danger" onclick="validarnit()" type="button">
                            <i class="fa fa-search-minus"></i> NIT
                          </button>
                        </span>
                      </div>
                    </div>

                    <div style="display: flex; gap: 10px; width: 100%;">
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" maxlength="256"
                          value="CONSUMIDOR FINAL" onchange="validarnitNombre()">
                        <span class="input-group-btn">
                          <button class="btn btn-success" onclick="validarnitNombre()" type="button">
                            <i class="fa fa-search"></i>
                          </button>
                        </span>
                        <span class="input-group-btn">
                          <button class="btn btn-success" onclick="listartbBusquedaCliente()" type="button">
                            <i class="fa fa-user"></i>
                          </button>
                        </span>
                      </div>

                    </div>

                    <div style="display: flex; gap: 10px; width: 100%;">
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente"
                          maxlength="256" value="0">
                      </div>
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente"
                          maxlength="256" value="CIUDAD">
                      </div>
                    </div>
                    <div style="display: flex; gap: 10px; width: 100%;">
                      <div class="input-group" style="flex: 1;">
                        <input type="text" class="form-control" name="correo_cliente" id="correo_cliente" maxlength="256"
                          value="soporte@gmail.com">
                      </div>
                      <div class="input-group" style="flex: 1;">
                        <select class="form-control select-picker" name="tipo_cliente" id="tipo_cliente" required>
                          <option value="DISTRIBUIDOR">PRECIO A</option>
                          <option value="MAYORISTA">PRECIO B</option>
                          <option value="TALLER">PRECIO C</option>

                        </select>
                      </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> </div>
                    <div class="row">
                      <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" class="form-control" name="idcliente" id="idcliente" value="1" readonly="">
                        <input type="text" class="form-control" name="valor_tarjeta" id="valor_tarjeta" value="0"
                          readonly="">
                      </div>
                      <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <select class="form-control select-picker" name="tipo_documento_cliente" id="tipo_documento_cliente"
                          required>
                          <option value="NIT">NIT</option>
                          <option value="DPI">DPI</option>
                          <option value="PASAPORTE">PASAPORTE</option>
                        </select>
                      </div>

                      <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control" name="calculo_descuento" id="calculo_descuento"
                          value="<?php echo $calculodescuento; ?>" readonly="">
                      </div>
                    </div>

                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>Fecha(*):</label>
                      <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                    </div>
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>F-pago(*):</label>
                      <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required="">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Credito">Credito</option>
                      </select>
                    </div>

                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>Tipo-Com(*):</label>
                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                        <option value="Cotizacion">Cotizacion</option>
                      </select>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" id="selectMensajero">
                      <label>Vendedor:</label>
                      <select id="idvendedor" name="idvendedor" class="form-control selectpicker"
                        data-live-search="true"></select>
                    </div>
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>Destino(*):</label>
                      <select name="destino" id="destino" class="form-control selectpicker" required>
                        <option value="VENTA">Venta directa</option>
                        <option value="PANTALLA">Pantalla pedidos</option>


                      </select>
                    </div>
                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                      <label>Productos(*):</label>
                      <select name="forma_productos" id="forma_productos" class="form-control selectpicker" required="">
                        <option value="Agrupado">Agrupado</option>
                        <option value="Detallado">Detallado</option>


                      </select>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_formapago" name="div_formapago">
                      <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <label>Tipo Tarjeta:</label>
                        <select name="tipo_pagoBacVisaNet" id="tipo_pagoBacVisaNet" class="form-control selectpicker"
                          onchange="mostrarOpcionesAdicionales();">
                          <option value="Seleccione Uno">Seleccione Uno</option>
                          <option value="VISANET">VISANET</option>
                          <option value="BAC">BAC</option>
                        </select>
                      </div>
                      <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12" id="opcionesAdicionalesDiv">
                        <label>Opciones Adicionales:</label>
                        <select name="opcionesAdicionales" id="opcionesAdicionales" class="form-control ">
                          <!-- Las opciones se llenarán dinámicamente -->
                        </select>
                      </div>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <input type="text" class="form-control" name="comentario_cotizacion" id="comentario_cotizacion"
                        required="" placeholder="Comentario">
                    </div>

                    <!-- 🔹 Botones uno a la par -->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="row">
                        <div class="col-md-6 col-sm-12">
                          <button type="button" class="btn btn-success btn-block" id="btnGuardar">
                            <i class="fa fa-save"></i> Click P/Guardar
                          </button>
                        </div>
                        <div class="col-md-6 col-sm-12">
                          <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()" type="button">
                            <i class="fa fa-arrow-circle-left"></i> Cancelar (F7)
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- 🔹 Totales -->
                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Valor Venta:</label>
                      <h1 id="total" class="valor-venta">Q/. 1484.00</h1>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Valor Descuento:</label>
                      <h1 id="totaldes" class="valor-descuento">Q/. 0.00</h1>
                    </div>

                    <!-- 🔹 Estilos personalizados -->
                    <style>
                      .valor-venta {
                        font-size: 4rem;
                        /* más grande */
                        font-weight: bold;
                        color: #28a745;
                        /* verde Bootstrap */
                      }

                      .valor-descuento {
                        font-size: 4rem;
                        /* más grande */
                        font-weight: bold;
                        color: #dc3545;
                        /* rojo Bootstrap */
                      }
                    </style>


                  </div>

                  <div class="form-group col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <style>
                      /* 🔍 Input buscador ocupa todo el ancho */
                      .search-input {
                        width: 100%;
                        display: block;
                        border-radius: 6px;
                        margin-bottom: 12px;
                        padding: 6px 10px;
                        font-size: 14px;
                      }

                      /* 📦 Tarjetas más compactas */
                      .custom-card {
                        height: 250px;
                        /* <--- antes tenías 320px */
                        padding: 5px;
                      }

                      /* 🧱 Reducir espaciado interior */
                      .custom-card .card-body {
                        padding: 8px 6px;
                      }

                      .custom-card .card-footer {
                        padding: 6px 6px;
                      }

                      /* 🖼️ Imagen más pequeña */
                      .card-body img {
                        max-height: 60px;
                        /* antes: 90px */
                      }

                      /* 📝 Título y texto más controlados */
                      .card-title {
                        font-size: 13px;
                        height: 28px;
                      }

                      .card-text {
                        font-size: 12px;
                        margin-bottom: 6px;
                        height: 18px;
                      }

                      /* ➕ Botón más pequeño */
                      .card-footer .btn {
                        font-size: 12px;
                        padding: 4px 8px;
                        border-radius: 5px;
                      }

                      /* 🧱 Contenedor principal que usa flexbox para alinear tarjetas */
                      #listadoregistros2 {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 10px 15px;
                        /* 10px vertical, 15px horizontal */
                        justify-content: space-between;
                      }

                      /* 📦 Cada tarjeta ocupa 24% del ancho (4 por fila) */
                      .articulo-card {
                        flex: 0 0 24%;
                        max-width: 24%;
                        min-width: 200px;
                        display: flex;
                      }

                      @media (max-width: 992px) {
                        .articulo-card {
                          flex: 0 0 48%;
                          max-width: 48%;
                        }
                      }

                      @media (max-width: 576px) {
                        .articulo-card {
                          flex: 0 0 100%;
                          max-width: 100%;
                        }
                      }

                      .articulo-card {
                        margin-bottom: 0 !important;
                      }

                      .contenedor-articulos-scroll {
                        height: 500px;
                        /* o lo que tú consideres visible */
                        overflow-y: auto;
                        padding: 10px;
                        border: 1px solid #e0e0e0;
                        border-radius: 8px;
                        background-color: #fdfdfd;
                      }

                      .contenedor-articulos-scroll {
                        scroll-behavior: smooth;
                      }
                    </style>
                    <style>
                      .categoria-scroll {
                        display: flex;
                        flex-wrap: wrap;
                        /* 🔹 Permite que bajen a otra línea */
                        gap: 10px;
                        /* 🔹 Espacio entre botones */
                        padding: 10px 0;
                        /* 🔹 Margen vertical */
                        scrollbar-width: thin;
                        /* 🔹 Mantiene scroll fino si hay overflow */
                      }

                      .categoria-scroll::-webkit-scrollbar {
                        height: 6px;
                      }

                      .categoria-scroll::-webkit-scrollbar-thumb {
                        background-color: #888;
                        border-radius: 4px;
                      }

                      .categoria-scroll::-webkit-scrollbar-thumb:hover {
                        background-color: #555;
                      }

                      /* 🔹 --- Estilo de los botones --- 🔹 */
                      .categoria-scroll button {
                        font-size: 15px;
                        /* Tamaño de texto más grande */
                        padding: 10px 20px;
                        /* Espaciado interno mayor */
                        border-radius: 25px;
                        /* Bordes redondeados tipo chip */
                        font-weight: 600;
                        /* Letras más gruesas */
                        text-transform: uppercase;
                        /* Texto en mayúsculas */
                        transition: all 0.2s ease;
                        flex-shrink: 0;
                        /* 🔹 Evita que se aplasten al hacer wrap */
                      }

                      .categoria-scroll button:hover {
                        background-color: #0d6efd;
                        /* Color azul bootstrap */
                        color: #fff;
                        transform: scale(1.05);
                        /* Efecto de zoom */
                      }
                    </style>

                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                      <div id="contenedor-categorias" class="categoria-scroll mb-3"></div>
                      <!-- <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <select id="idcategoria" name="idcategoria" class="form-control selectpicker"
                                    onchange="listarArticulosxcategoria()" data-live-search="true">
                                    <option value="">Seleccione una categoría</option>
                                  
                                  </select>
                                </div>-->


                      <!-- Aquí irá el buscador dinámico -->
                      <div id="contenedor-buscador" class="mb-3"></div>

                      <!-- Resultados -->
                      <div class="row">
                        <div class="col-md-12">
                          <div class="panel-body table-responsive contenedor-articulos-scroll">
                            <div id="listadoregistros2">
                              <div class="col-12 text-center text-muted">Cargando artículos, espere...</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>


                  </div>



                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#A9D0F5">
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Stock</th>
                        <th>Cant</th>
                        <th>Presentacion</th>
                        <th>P.V.</th>
                        <th>Des Q/%</th>
                        <th>S.T</th>
                        <th>S.T.Des</th>
                        <th>Descrip</th>
                      </thead>
                      <tfoot>
                        <th>TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><input type="hidden" name="total_venta" id="total_venta"></th>
                        <th><input type="hidden" name="total_ventades" id="total_ventades"></th>

                      </tfoot>
                      <tbody>
                      </tbody>
                    </table>
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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione un Artículo</h4>
          </div>
          <div class="modal-body">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
              <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Dias Venci</th>
                  <th>Categoría</th>
                  <th>Código</th>
                  <th>SKU</th>
                  <th>UBICACION</th>
                  <th>Stock</th>
                  <th>Estado Stock</th>
                  <th>Precio Venta</th>
                  <th>Imagen</th>
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
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal -->
    <!-- Modal -->
    <div class="modal fade" id="myModalBusquedacliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h4><img class="iconos-cambio" src="../public/iconos/busqueda.png">BUSQUE UN CLIENTE</h4>
                </div>
                <div class="icon">
                  <i class="fa fa-home"></i>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
              <table id="tbBusquedaCliente" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Direccion</th>
                  <th># Doc</th>
                  <th>Telefono</th>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Direccion</th>
                  <th># Doc</th>
                  <th>Telefono</th>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal -->

    <div class="modal fade" id="myModalImpresionFAc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" style="display: block; padding-right: 17px;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccion el Formato a Imprimir</h4>
          </div>
          <div class="modal-body d-flex flex-column align-items-center text-center">
            <input type="hidden" name="idventa_impresion" id="idventa_impresion">
            <a class="btn btn-app btn-success m-2" onclick="impresionticket58mm()">
              <i class="fa fa-print"></i> Ticket 58mm
            </a>
            <a class="btn btn-app btn-primary m-2" onclick="impresionticket79mm()">
              <i class="fa fa-print"></i> Ticket 79mm
            </a>
            <a class="btn btn-app btn-info m-2" onclick="impresionticketCarta()">
              <i class="fa fa-print"></i> Carta
            </a>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger btn-block" onclick="limpiar()" type="button"><i
                class="fa fa-arrow-circle-left"></i> Crear Nueva Venta </button>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal Artículo -->
    <div class="modal fade" id="modalArticulo" tabindex="-1" role="dialog" aria-labelledby="modalArticuloLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalArticuloLabel">Stock Disponible</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="idArticuloModal">
            <table id="tbllistadoxsucursalxarticulo" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Sucursal</th>
                <th>Nombre</th>
                <th>Dias Venci</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>SKU</th>
                <th>UBICACION</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>P.V</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th>Sucursal</th>
                <th>Nombre</th>
                <th>Dias Venci</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>SKU</th>
                <th>UBICACION</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>P.V</th>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>


    <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/sweatlert.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="scripts/cotizaciones.js"></script>
  <?php
}
ob_end_flush();
?>