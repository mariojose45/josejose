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
    if ($_SESSION['taller_ingreso_vehiculo'] == 1) { 

  require_once "../modelos/Consultas.php";
    $consulta = new Consultas();
  $rsptav = $consulta->totalventahoy();
  $regv=$rsptav->fetch_object();   
  $totalv=$regv->total_venta;   

  $res_efectivo = $consulta->totalventahoyefectivo();
  $resv_efectivo=$res_efectivo->fetch_object();  
  $restotalpagoefectivo=$resv_efectivo->total_venta;  

  $res_credito = $consulta->totalventahoyCredito();
  $resv_credito=$res_credito->fetch_object(); 
  $restotalpagocredito=$resv_credito->total_venta;    


    $res_tarjeta = $consulta->totalventahoyTarjeta();
  $resv_tarjeta=$res_tarjeta->fetch_object(); 
  $restotalpagotarjeta=$resv_tarjeta->total_venta;    
 

  $rsptavc = $consulta->totalefectivoiniciocaja();
  $regvc=$rsptavc->fetch_object();
  $restotalefectivo=$regvc->totalefectivo;
  $resApertura=$regvc->tipo_operacion; 

    $rsptaCalculoDescuento = $consulta->tipoCalculoDescuento();
    $regvCalDes=$rsptaCalculoDescuento->fetch_object();    
    $calculodescuento=$regvCalDes->calculo_descuento;    

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
                            Ingreso de Vehículo
                          <small>Registra un Vehiculo</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ingreso de Vehículo</li>
                          </ol>

                          </section>  
                          <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar (F8)</button> </h1>                                             
                        </div>                     

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>"
                          style="font-size: medium;">
                        </div>
                        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                          <label>Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>"
                          style="font-size: medium;">
                          <button class="btn btn-success btn-block" onclick="listar()">Generar Ventas</button>
                        </div>
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover"
                        style="width: -webkit-fill-available;">
                          <thead>
                            <th>Opciones</th>
                            <th># Operación</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Fecha</th> 
                            <th>No Placa</th>
                            <th>No Chasis</th>
                            <th>No Serie</th>
                            <th>No Motor</th>
                            <th>Modelo</th>
                            <th>KM</th>
                            <th>Marca</th>
                            <th>Estado</th>
                            <th>Factura</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th># Operación</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Fecha</th> 
                            <th>No Placa</th>
                            <th>No Chasis</th>
                            <th>No Serie</th>
                            <th>No Motor</th>
                            <th>Modelo</th>
                            <th>KM</th>
                            <th>Marca</th>
                            <th>Estado</th>
                            <th>Factura</th>
                          </tfoot>
                        </table> 
                    </div>
                    <div class="panel-body" style="height: 2100px;" id="formularioregistros">

                        <form name="formulario" id="formulario" method="POST">

                            <!-- DATOS CLIENTE -->
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label> <img class="iconos-tama" src="../public/iconos/proveedor.png"> Clientes(*):</label>
                                <input type="hidden" name="idingreso_vehiculo" id="idingreso_vehiculo">
                                <input type="hidden" name="datos1" id="datos1">
                                <div style="display: flex; gap: 10px; width: 100%;"> 
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control"  name="codigo_cliente" id="codigo_cliente" maxlength="20" placeholder="CODIGO"
                                        style="font-size: medium;">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" onclick="validarCodigo()" type="button">
                                                <i class="fa fa-users"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" onchange="validarnit()" name="nit" id="nit" maxlength="20" value="CF"
                                        style="font-size: medium;">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger" onclick="validarnit()" type="button">
                                                <i class="fa fa-search-minus"></i> NIT
                                            </button>
                                        </span>
                                    </div>
                                </div> 
                                <div style="display: flex; gap: 10px; width: 100%;">
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control"  name="nombre_cliente" id="nombre_cliente" maxlength="256" value="CONSUMIDOR FINAL"
                                        onchange="validarnitNombre()" style="font-size: medium;">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" onclick="validarnitNombre()" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>                                        
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" onclick="listartbBusquedaCliente()" type="button">
                                                <i class="fa fa-search-minus"></i> NOMBRE
                                            </button>
                                        </span>
                                    </div>  

                                </div> 
                                <div style="display: flex; gap: 10px; width: 100%;">
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control"  name="telefono_cliente" id="telefono_cliente" maxlength="256" value="0"
                                        style="font-size: medium;">
                                    </div>                                  
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" maxlength="256"  value="CIUDAD"
                                        style="font-size: medium;">
                                    </div>                                        
                                </div>
                                <div style="display: flex; gap: 10px; width: 100%;"> 
                                    <div class="input-group" style="flex: 1;">
                                        <input type="text" class="form-control" name="correo_cliente" id="correo_cliente" maxlength="256"  value="soporte@gmail.com"
                                        style="font-size: medium;">
                                    </div>
                                    <div class="input-group" style="flex: 1;">
                                    <select class="form-control select-picker" name="tipo_cliente" id="tipo_cliente" required style="font-size: medium;">
                                      <option value="PUBLICO">PUBLICO</option>  
                                      <option value="DISTRIBUIDOR">DISTRIBUIDOR</option>
                                      <option value="MAYORISTA">MAYORISTA</option>
                                      <option value="TALLER">TALLER</option>
                                      
                                    </select> 
                                    </div>                                       
                                </div>                                  
                                <div style="display: flex; gap: 10px; width: 100%;">
                                  <div class="input-group" style="flex: 1;">
                                    <input type="text" class="form-control" name="idcliente" id="idcliente" value="1" readonly="">
                                  </div>
                                  <div class="input-group" style="flex: 1;">
                                    <input type="text" class="form-control" name="valor_tarjeta" id="valor_tarjeta" value="0" readonly="">
                                  </div>
                                  <div class="input-group" style="flex: 1;">
                                    <select class="form-control select-picker" name="tipo_documento_cliente" id="tipo_documento_cliente" required>
                                      <option value="NIT">NIT</option>
                                      <option value="DPI">DPI</option>
                                      <option value="PASAPORTE">PASAPORTE</option> 
                                    </select> 
                                  </div>
                                  <div class="input-group" style="flex: 1;">
                                    <input type="text" class="form-control"   name="calculo_descuento" id="calculo_descuento" value="<?php echo $calculodescuento; ?>" readonly="">
                                  </div>                                                                                                    
                                </div>
                                <br>
                            </div>
                            <!-- DATOS CLIENTE -->
                            
                            <!-- DATOS GENERALES -->
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label>Marca:</label>
                                    <select id="idvendedor" name="idvendedor" class="form-control selectpicker" data-live-search="true"></select>
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label>NO DE PLACA:</label>
                                    <input type="text" class="form-control" name="no_placa" id="no_placa" style="font-size: medium;">
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label>NO DE CHASIS:</label>
                                    <input type="text" class="form-control" name="no_chasis" id="no_chasis" style="font-size: medium;">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>SERIE:</label>
                                    <input type="text" class="form-control" name="serie" id="serie" style="font-size: medium;">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>NO DE MOTOR:</label>
                                    <input type="text" class="form-control" name="no_motor" id="no_motor" style="font-size: medium;">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>MODELO:</label>
                                    <input type="text" class="form-control" name="modelo" id="modelo" style="font-size: medium;">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>KM:</label>
                                    <input type="text" class="form-control" name="km" id="km" style="font-size: medium;">
                                </div>
                            </div>
                            <!-- DATOS GENERALES -->

                            <!-- DATOS IMAGENES -->
                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <!-- INFO DE LAS REVISIONES -->
                                 <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive table-revisionVehiculo">
                                    <table id="tblRevisiones" class="table table-bordered table-revisionVehiculo">
                                        <thead class="thead-revisionVehiculo">
                                            <tr>
                                                <th rowspan="2" class="th-revisionVehiculo">Revisiones</th>
                                                <th colspan="3" class="text-center th-revisionVehiculo">Estado Actual</th>
                                                <th rowspan="2" class="th-revisionVehiculo">Cambio Sugerido</th>
                                            </tr>
                                            <tr>
                                                <th class="th-revisionVehiculo">100%</th>
                                                <th class="th-revisionVehiculo">75%</th>
                                                <th class="th-revisionVehiculo">50%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr data-id="1">
                                                <td class="td-revisionVehiculo">Frenos Delanteros</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="2">
                                                <td class="td-revisionVehiculo">Frenos Traseros</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="3">
                                                <td class="td-revisionVehiculo">Suspensión</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="4">
                                                <td class="td-revisionVehiculo">Espirales</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="5">
                                                <td class="td-revisionVehiculo">Amortiguadores Delanteros</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="6">
                                                <td class="td-revisionVehiculo">Amortiguadores Traseros</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="7">
                                                <td class="td-revisionVehiculo">Fajas</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="8">
                                                <td class="td-revisionVehiculo">Filtro de Aire</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="9">
                                                <td class="td-revisionVehiculo">Filtro de Gasolina</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="10">
                                                <td class="td-revisionVehiculo">Filtro de Cabina</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="11">
                                                <td class="td-revisionVehiculo">Aceite de Motor</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="12">
                                                <td class="td-revisionVehiculo">Aceite de Caja</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="13">
                                                <td class="td-revisionVehiculo">Aceite de Diferencial</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="14">
                                                <td class="td-revisionVehiculo">Aceite Hidráulico</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="15">
                                                <td class="td-revisionVehiculo">Líquido de Frenos</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="16">
                                                <td class="td-revisionVehiculo">Parabrisas</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="17">
                                                <td class="td-revisionVehiculo">Estado de Batería</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="18">
                                                <td class="td-revisionVehiculo">Luces</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="19">
                                                <td class="td-revisionVehiculo">Sistema de Escape</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="20">
                                                <td class="td-revisionVehiculo">Revisión de Embrague</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="21">
                                                <td class="td-revisionVehiculo">Engrase de Chasis</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="22">
                                                <td class="td-revisionVehiculo">Refrigerante</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                            <tr data-id="23">
                                                <td class="td-revisionVehiculo">Eje Cardan</td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                                <td><input type="checkbox" class="form-check-input check-revisionVehiculo"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- INFO DE LAS REVISIONES -->
                                <div class="form-container-imagenesData">
                                    <div class="image-upload-block-imagenesData">
                                        <label for="imagen1" class="label-imagenesData">Imagen 1:</label>
                                        <input type="file" class="input-file-imagenesData" name="imagen1" id="imagen1" onchange="previewImage(event, 'imagenmuestra1')">
                                        <input type="hidden" name="imagenactual1" id="imagenactual1">
                                        <img src="" class="img-preview-imagenesData" id="imagenmuestra1" alt="Previsualización de imagen 1">
                                        <button type="button" class="image-delete-button" onclick="clearImage('imagen1', 'imagenmuestra1', 'imagenactual1')">Eliminar foto</button>
                                        <label for="descripcion1" class="label-imagenesData">Descripción 1:</label>
                                        <textarea class="textarea-imagenesData" name="descripcion1" id="descripcion1" rows="3" placeholder="Ingresa una descripción para la imagen 1"></textarea>
                                    </div>

                                    <div class="image-upload-block-imagenesData">
                                        <label for="imagen2" class="label-imagenesData">Imagen 2:</label>
                                        <input type="file" class="input-file-imagenesData" name="imagen2" id="imagen2" onchange="previewImage(event, 'imagenmuestra2')">
                                        <input type="hidden" name="imagenactual2" id="imagenactual2">
                                        <img src="" class="img-preview-imagenesData" id="imagenmuestra2" alt="Previsualización de imagen 2">
                                        <button type="button" class="image-delete-button" onclick="clearImage('imagen2', 'imagenmuestra2', 'imagenactual2')">Eliminar foto</button>
                                        <label for="descripcion2" class="label-imagenesData">Descripción 2:</label>
                                        <textarea class="textarea-imagenesData" name="descripcion2" id="descripcion2" rows="3" placeholder="Ingresa una descripción para la imagen 2"></textarea>
                                    </div>

                                    <div class="image-upload-block-imagenesData">
                                        <label for="imagen3" class="label-imagenesData">Imagen 3:</label>
                                        <input type="file" class="input-file-imagenesData" name="imagen3" id="imagen3" onchange="previewImage(event, 'imagenmuestra3')">
                                        <input type="hidden" name="imagenactual3" id="imagenactual3">
                                        <img src="" class="img-preview-imagenesData" id="imagenmuestra3" alt="Previsualización de imagen 3">
                                        <button type="button" class="image-delete-button" onclick="clearImage('imagen3', 'imagenmuestra3', 'imagenactual3')">Eliminar foto</button>
                                        <label for="descripcion3" class="label-imagenesData">Descripción 3:</label>
                                        <textarea class="textarea-imagenesData" name="descripcion3" id="descripcion3" rows="3" placeholder="Ingresa una descripción para la imagen 3"></textarea>
                                    </div>

                                    <div class="image-upload-block-imagenesData">
                                        <label for="imagen4" class="label-imagenesData">Imagen 4:</label>
                                        <input type="file" class="input-file-imagenesData" name="imagen4" id="imagen4" onchange="previewImage(event, 'imagenmuestra4')">
                                        <input type="hidden" name="imagenactual4" id="imagenactual4">
                                        <img src="" class="img-preview-imagenesData" id="imagenmuestra4" alt="Previsualización de imagen 4">
                                        <button type="button" class="image-delete-button" onclick="clearImage('imagen4', 'imagenmuestra4', 'imagenactual4')">Eliminar foto</button>
                                        <label for="descripcion4" class="label-imagenesData">Descripción 4:</label>
                                        <textarea class="textarea-imagenesData" name="descripcion4" id="descripcion4" rows="3" placeholder="Ingresa una descripción para la imagen 4"></textarea>
                                    </div>

                                    <div class="image-upload-block-imagenesData">
                                        <label for="imagen5" class="label-imagenesData">Imagen 5:</label>
                                        <input type="file" class="input-file-imagenesData" name="imagen5" id="imagen5" onchange="previewImage(event, 'imagenmuestra5')">
                                        <input type="hidden" name="imagenactual5" id="imagenactual5">
                                        <img src="" class="img-preview-imagenesData" id="imagenmuestra5" alt="Previsualización de imagen 5">
                                        <button type="button" class="image-delete-button" onclick="clearImage('imagen5', 'imagenmuestra5', 'imagenactual5')">Eliminar foto</button>
                                        <label for="descripcion5" class="label-imagenesData">Descripción 5:</label>
                                        <textarea class="textarea-imagenesData" name="descripcion5" id="descripcion5" rows="3" placeholder="Ingresa una descripción para la imagen 5"></textarea>
                                    </div>

                                    <div class="image-upload-block-imagenesData">
                                        <label for="imagen6" class="label-imagenesData">Imagen 6:</label>
                                        <input type="file" class="input-file-imagenesData" name="imagen6" id="imagen6" onchange="previewImage(event, 'imagenmuestra6')">
                                        <input type="hidden" name="imagenactual6" id="imagenactual6">
                                        <img src="" class="img-preview-imagenesData" id="imagenmuestra6" alt="Previsualización de imagen 6">
                                        <button type="button" class="image-delete-button" onclick="clearImage('imagen6', 'imagenmuestra6', 'imagenactual6')">Eliminar foto</button>
                                        <label for="descripcion6" class="label-imagenesData">Descripción 6:</label>
                                        <textarea class="textarea-imagenesData" name="descripcion6" id="descripcion6" rows="3" placeholder="Ingresa una descripción para la imagen 6"></textarea>
                                    </div>

                                    <div class="image-upload-block-imagenesData">
                                        <label for="imagen7" class="label-imagenesData">Imagen 7:</label>
                                        <input type="file" class="input-file-imagenesData" name="imagen7" id="imagen7" onchange="previewImage(event, 'imagenmuestra7')">
                                        <input type="hidden" name="imagenactual7" id="imagenactual7">
                                        <img src="" class="img-preview-imagenesData" id="imagenmuestra7" alt="Previsualización de imagen 7">
                                        <button type="button" class="image-delete-button" onclick="clearImage('imagen7', 'imagenmuestra7', 'imagenactual7')">Eliminar foto</button>
                                        <label for="descripcion7" class="label-imagenesData">Descripción 7:</label>
                                        <textarea class="textarea-imagenesData" name="descripcion7" id="descripcion7" rows="3" placeholder="Ingresa una descripción para la imagen 7"></textarea>
                                    </div>

                                    <div class="image-upload-block-imagenesData">
                                        <label for="imagen8" class="label-imagenesData">Imagen 8:</label>
                                        <input type="file" class="input-file-imagenesData" name="imagen8" id="imagen8" onchange="previewImage(event, 'imagenmuestra8')">
                                        <input type="hidden" name="imagenactual8" id="imagenactual8">
                                        <img src="" class="img-preview-imagenesData" id="imagenmuestra8" alt="Previsualización de imagen 8">
                                        <button type="button" class="image-delete-button" onclick="clearImage('imagen8', 'imagenmuestra8', 'imagenactual8')">Eliminar foto</button>
                                        <label for="descripcion8" class="label-imagenesData">Descripción 8:</label>
                                        <textarea class="textarea-imagenesData" name="descripcion8" id="descripcion8" rows="3" placeholder="Ingresa una descripción para la imagen 8"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- DATOS IMAGENES -->

                            <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-card">
                                    <div class="card-header">
                                        <h1>Trabajos a realizar</h1>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="trabajos_detalle" class="form-label">Detalle de trabajos:</label>
                                            <textarea name="trabajos_detalle" id="trabajos_detalle" rows="20" class="form-input-text" placeholder="Descripción de los trabajos a realizar..."></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="observaciones_adicionales" class="form-label">Observaciones:</label>
                                            <textarea name="observaciones_adicionales" id="observaciones_adicionales" rows="20" class="form-input-text" placeholder="Observaciones adicionales..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <hr>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: flex;justify-content: center;">
                                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <button type="button" class="btn btn-success btn-block" id="btnGuardar"><i class="fa fa-save"></i> Click P/Guardar</button>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <button id="btnCancelar" class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar (F7)</button>
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
 <!-- Modal -->
<div class="modal fade" id="myModalBusquedacliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
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
<script type="text/javascript" src="scripts/ingreso_vehiculov2.js"></script>
<style>
    /* Estilos generales para el contenedor de carga de imagen */
    .image-upload-container {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        background-color: #f9f9f9;
        display: flex;
        flex-direction: column;
        gap: 10px; /* Espacio entre los elementos */
    }

    /* Estilos para las etiquetas */
    .image-upload-label {
        font-weight: bold;
        color: #333;
    }

    /* Estilos para el input de tipo file */
    .image-upload-input {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
    }

    /* Estilos para la previsualización de la imagen */
    .image-preview {
        max-width: 200px;
        max-height: 200px;
        border: 1px solid #eee;
        margin-top: 10px;
        display: block; /* Para que ocupe su propia línea y se alinee mejor */
        object-fit: contain; /* Para que la imagen se ajuste sin cortarse */
    }

    /* Estilos para el botón de eliminar imagen */
    .image-delete-button {
        background-color: #dc3545; /* Rojo para indicar peligro/eliminación */
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.3s ease; /* Transición suave para el hover */
        
        margin-top: 5px; /* Pequeño margen superior */
    }

    .image-delete-button:hover {
        background-color: #c82333; /* Un rojo un poco más oscuro al pasar el ratón */
    }

    /* Estilos para el textarea de descripción */
    .image-description-textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical; /* Permite redimensionar verticalmente */
        font-family: inherit; /* Hereda la fuente del cuerpo */
    }
        /* Contenedor principal */
    .form-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        width: 100%; /* Asegura que el contenedor ocupe todo el ancho disponible */
        box-sizing: border-box; /* Incluye el padding en el ancho total para evitar desbordamiento */
    }

    /* Estilo para cada opción de "lado" */
    .side-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        width: 200px; /* Tamaño fijo para cada opción */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); /* Sombra suave */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .side-option:hover {
        transform: translateY(-5px); /* Efecto de "levantar" al pasar el mouse */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    /* Estilo para la imagen y el título */
    .side-label {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px; /* Espacio entre imagen y texto */
        width: 100%;
    }

    .side-image {
        width: 120px; /* Tamaño fijo para todas las imágenes */
        height: 120px;
        object-fit: contain; /* Asegura que la imagen se vea bien sin distorsión */
        border-radius: 4px;
        border: 1px solid #e9ecef;
    }

    .checkbox-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px; /* Espacio antes del checkbox */
    }

    /* Ocultar el checkbox por defecto y estilizarlo manualmente */
    .side-checkbox {
        display: none;
    }

    .side-label .checkbox-title::before {
        content: '⬜'; /* Cuadrado no seleccionado */
        margin-right: 5px;
        font-size: 1.5rem;
        vertical-align: middle;
    }

    .side-checkbox:checked + .checkbox-title::before {
        content: '✅'; /* Marca de verificación al seleccionar */
    }

    /* Estilo para el grupo de descripción */
    .description-group {
        width: 100%;
        margin-top: 15px;
    }

    .description-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #6c757d;
        text-align: left;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 4px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }


    /* Contenedor principal del formulario */
    .form-container-checs {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        max-width: 800px; /* Limita el ancho para un mejor diseño en pantallas grandes */
        margin: 20px auto;
    }

    /* Encabezado del formulario */
    .form-header h1 {
        text-align: center;
        color: #343a40;
        margin-bottom: 25px;
        font-size: 2rem;
        font-weight: 600;
    }

    /* Contenedor de los items del formulario */
    .form-item-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between; /* Distribuye los elementos uniformemente */
        gap: 20px;
    }

    /* Estilo para cada item del formulario (la descripción y el select) */
    .form-item {
        display: flex;
        flex-direction: column;
        width: calc(33.333% - 14px); /* Ancho para 3 columnas en desktop, ajusta según el 'gap' */
        box-sizing: border-box;
    }

    /* Responsividad para pantallas más pequeñas */
    @media (max-width: 992px) {
        .form-item {
            width: calc(50% - 10px); /* 2 columnas en tablets */
        }
    }
    @media (max-width: 768px) {
        .form-item {
            width: 100%; /* 1 columna en móviles */
        }
    }

    /* Texto de la descripción */
    .item-text {
        font-size: 0.95rem;
        color: #495057;
        font-weight: 500;
        margin-bottom: 8px;
    }

    /* Estilo para el select */
    .form-select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #495057;
        background-color: #fff;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
    }

    /* Estilo para el select al pasar el cursor y enfocar */
    .form-select:hover {
        border-color: #80bdff;
    }

    .form-select:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Estilo para la primera opción */
    .form-select option[disabled] {
        color: #999;
    }

    /////////////////////////////////
    /* Estilo para el contenedor principal, como una "tarjeta" */
    .form-card {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        font-family: Arial, sans-serif;
        max-width: 600px;
        margin: 40px auto;
        border: 1px solid #e0e0e0;
    }

    /* Encabezado de la tarjeta */
    .card-header h1 {
        text-align: center;
        color: #333;
        font-size: 1.0em;
        font-weight: 600;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }

    /* Contenedor de cada grupo de formulario */
    .form-group {
        margin-bottom: 20px;
    }

    /* Etiqueta del campo de formulario */
    .form-label {
        display: block;
        font-size: 0.9em;
        color: #555;
        font-weight: bold;
        margin-bottom: 8px;
    }

    /* Estilo para los campos de texto */
    .form-input-text {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1em;
        color: #333;
        background-color: #f9f9f9;
        resize: vertical;
        transition: all 0.3s ease-in-out;
        box-sizing: border-box;
    }

    /* Estilo al enfocar (clic en el campo) */
    .form-input-text:focus {
        outline: none;
        border-color: #007bff;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
    }

    /* Estilo del placeholder */
    .form-input-text::placeholder {
        color: #999;
    }

    /////////////////////////
    /* Contenedor principal del formulario */
    .form-container-check {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        max-width: 800px;
        margin: 20px auto;
    }

    /* Encabezado del formulario */
    .form-header-check h1 {
        text-align: center;
        color: #343a40;
        margin-bottom: 25px;
        font-size: 2rem;
        font-weight: 600;
    }

    /* Estilo para las filas del formulario */
    .form-row-check {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 15px; /* Espacio entre filas */
    }

    /* Estilo para cada item del formulario (dentro de una fila) */
    .form-item-check {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: box-shadow 0.3s ease;
        flex-grow: 1; /* Permite que los elementos se estiren */
        width: calc(33.333% - 10px); /* Ancho para 3 elementos por fila */
    }

    /* Ajuste para la fila de 1 elemento */
    .form-row-check:last-child .form-item-check {
        width: 100%; /* El último elemento ocupa todo el ancho */
    }

    .form-item-check:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    /* Contenido del item (imagen y texto) */
    .item-content-check {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-grow: 1;
    }

    /* Tamaño y estilo de las imágenes */
    .item-icon-check {
        width: 35px;
        height: 35px;
        object-fit: contain;
    }

    /* Texto de la descripción */
    .item-text-check {
        font-size: 1rem;
        color: #495057;
        font-weight: 500;
    }

    /* Estilo del switch */
    .switch-check {
        position: relative;
        display: inline-block;
        width: 45px;
        height: 25px;
    }

    .switch-check input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider-check {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 25px;
    }

    .slider-check:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 3.5px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider-check {
        background-color: #28a745;
    }

    input:checked + .slider-check:before {
        transform: translateX(20px);
    }

</style>
    <style>
        /* Estilos generales */

        .form-container-imagenesData {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            width: 100%;
            /* Modifica esta línea para 4 columnas en pantallas grandes */
            display: grid;
            grid-template-columns: repeat(4, 1fr); 
            gap: 25px; /* Mantener la separación entre las tarjetas */
        }

        .image-upload-block-imagenesData {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px; /* Reducir el padding para hacer la tarjeta más pequeña */
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.2s ease-in-out;
        }

        .image-upload-block-imagenesData:hover {
            border-color: #007bff;
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.1);
        }

        .label-imagenesData {
            /* Mantener o ajustar si es necesario */
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
            font-size: 0.95em;
        }

        .input-file-imagenesData {
            /* Mantener o ajustar si es necesario */
            margin-bottom: 15px;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            width: 100%; 
            font-size: 0.9em;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }

        .input-file-imagenesData:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .img-preview-imagenesData {
            /* Reducir el tamaño de la previsualización de la imagen */
            width: 120px;
            height: 90px;
            border: 1px dashed #ced4da;
            border-radius: 5px;
            object-fit: contain; 
            margin-bottom: 15px;
            background-color: #f1f3f5;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #adb5bd;
            font-size: 0.8em;
            overflow: hidden; 
        }
        /* Estilo para cuando no hay imagen cargada */
        .img-preview-imagenesData:empty::before {
            content: 'No image';
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .textarea-imagenesData {
            /* Mantener o ajustar si es necesario */
            width: 100%; 
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 0.9em;
            line-height: 1.5;
            resize: vertical; 
            min-height: 60px; /* Reducir la altura mínima */
            transition: border-color 0.2s ease;
        }
        .textarea-imagenesData:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        @media (max-width: 1200px) {
            .form-container-imagenesData {
                /* Para tablets o pantallas medianas, puedes usar 2 columnas */
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .form-container-imagenesData {
                /* Una columna en móviles */
                grid-template-columns: 1fr; 
                padding: 20px;
            }
        }
    </style>
<?php 
}
ob_end_flush(); 
?>