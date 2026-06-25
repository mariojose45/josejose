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
  if ($_SESSION['almacen_crear_categoria']==1) 
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
                          Mecanico  
                          <small>Taller</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Mecanico</li>
                          </ol>

                          </section>  
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
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
 
    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->

<div class="modal fade" id="modalActualizarAsistencia" tabindex="-1" role="dialog"
    aria-labelledby="modalActualizarAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-modalasistencia">
            <div class="modal-header modal-header-modalasistencia">
                <h5 class="modal-title modal-title-modalasistencia" id="modalActualizarAsistenciaLabel">Agregar
                    Articulos/Datos Adicionales</h5>
                <button type="button" class="close close-modalasistencia" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- ARTICULOS -->
            <div class="col-md-12">
                <input type="hidden" name="idingreso_vehiculo" id="idingreso_vehiculo">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Agregar Articulos</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Revisión</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Detalles Finales</a></li>
                        <li><a href="#tab_4" data-toggle="tab">Datos Vehiculo Registrado</a></li>
                    </ul>
                    <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                                    <table id="tblarticulos_detalles"
                                        class="table table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                            <th>Opciones</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Código</th>
                                            <th>Stock</th>
                                            <th>Precio Venta</th>
                                            <th>Imagen</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                                    <table id="detalles"
                                        class="table table-striped table-bordered table-condensed table-hover">
                                        <thead style="background-color:#A9D0F5">
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Cant</th>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
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
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                                <div class="container-fluid">
                                    <div class="row-modalDetalles">
                                        <div class="col-md-6-modalDetalles">
                                            <div class="form-group-modalDetalles">
                                                <label for="horaInicio" class="form-label-modalDetalles">Hora de Inicio</label>
                                                <input type="text" class="form-control-modalDetalles" id="horaInicio" name="horaInicio">
                                            </div>
                                            <div class="form-group-modalDetalles">
                                                <label for="horaFinalizada" class="form-label-modalDetalles">Hora finalizada</label>
                                                <input type="text" class="form-control-modalDetalles" id="horaFinalizada" name="horaFinalizada">
                                            </div>
                                            <div class="form-group-modalDetalles">
                                                <label for="tecnico" class="form-label-modalDetalles">Técnico</label>
                                                <input type="text" class="form-control-modalDetalles" id="tecnico" name="tecnico">
                                            </div>
                                        </div>
                                        <div class="col-md-6-modalDetalles">
                                            <div class="form-group-modalDetalles">
                                                <label for="gradoAceite" class="form-label-modalDetalles">Grado de Aceite</label>
                                                <input type="text" class="form-control-modalDetalles" id="gradoAceite" name="gradoAceite">
                                            </div>
                                            <div class="form-group-modalDetalles">
                                                <label for="filtroAceite" class="form-label-modalDetalles">Filtro de Aceite</label>
                                                <input type="text" class="form-control-modalDetalles" id="filtroAceite" name="filtroAceite">
                                            </div>
                                            <div class="form-group-modalDetalles">
                                                <label for="filtroAire" class="form-label-modalDetalles">Filtro de Aire</label>
                                                <input type="text" class="form-control-modalDetalles" id="filtroAire" name="filtroAire">
                                            </div>
                                            <div class="form-group-modalDetalles">
                                                <label for="filtroCombustible" class="form-label-modalDetalles">Filtro de Combustible</label>
                                                <input type="text" class="form-control-modalDetalles" id="filtroCombustible" name="filtroCombustible">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-modalDetalles mt-4">
                                        <label for="observaciones" class="form-label-modalDetalles">Observaciones</label>
                                        <textarea class="form-control-modalDetalles" id="observaciones" name="observaciones" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_4">
                                <div id="contenido_tab_4">
                                </div>
                            </div>
                        
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>

            <div class="modal-footer modal-footer-modalasistencia">
                <button type="button" class="btn btn-secondary btn-secondary-modalasistencia"
                    data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary btn-primary-modalasistencia" id="btnGuardarCambios">Guardar
                    cambios</button>
            </div>

        </div>
    </div>
</div>


<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/mecanico.js"></script>
    <style>
        /* Contenedor principal para los datos del vehículo */
.panel-body-DatosCargados {
    background-color: #f9f9f9;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Títulos de las secciones */
.box-title-DatosCargados {
    color: #333;
    font-size: 1.8em;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

/* Lista de detalles del vehículo */
.list-group-DatosCargados {
    list-style: none;
    padding: 0;
    margin: 0;
}

.list-group-item-DatosCargados {
    background-color: #fff;
    border: 1px solid #e9e9e9;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 6px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.list-group-item-DatosCargados:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

/* Texto dentro de los elementos de la lista */
.list-group-item-DatosCargados p {
    margin: 0;
    font-size: 1em;
    line-height: 1.5;
}

.list-group-item-DatosCargados strong {
    color: #555;
}

/* Estilos para el textarea de detalles */
.form-control-DatosCargados {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
    resize: vertical;
    font-family: inherit;
    background-color: #f5f5f5;
    cursor: default; /* Indica que no es editable */
}

/* Estilos para las imágenes y descripciones */
.row-DatosCargados {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
}

.col-md-3-DatosCargados {
    flex: 1 1 23%;
    text-align: center;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 8px;
    background-color: #fff;
}

.img-responsive-DatosCargados {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin-bottom: 8px;
}
        /* -------------------- Estilos del Modal -------------------- */
        .modal-modalDetalles {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal-modalDetalles.in {
            opacity: 1;
            visibility: visible;
        }

        .modal-dialog-modalDetalles {
            max-width: 90%;
            width: 800px;
            margin: 1.75rem auto;
            position: relative;
            transition: transform 0.3s ease;
            transform: translateY(-50px);
        }

        .modal-modalDetalles.in .modal-dialog-modalDetalles {
            transform: translateY(0);
        }

        .modal-content-modalDetalles {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            outline: 0;
        }

        .modal-header-modalDetalles {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-title-modalDetalles {
            margin-top: 0;
            margin-bottom: 0;
            line-height: 1.5;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close-modalDetalles {
            padding: 1rem 1rem;
            margin: -1rem -1rem -1rem auto;
            background-color: transparent;
            border: 0;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            color: #6c757d;
            opacity: 0.7;
            cursor: pointer;
            transition: color 0.2s ease, opacity 0.2s ease;
        }

        .close-modalDetalles:hover {
            color: #333;
            opacity: 1;
        }

        /* -------------------- Estilos de Pestañas (Tabs) -------------------- */
        .nav-tabs-custom-modalDetalles {
            padding: 1.5rem 2rem 0;
        }

        .nav-tabs-modalDetalles {
            display: flex;
            border-bottom: 2px solid #e9ecef;
            padding: 0;
            margin: 0;
        }

        .nav-tabs-modalDetalles > li {
            list-style: none;
            margin-bottom: -2px;
        }

        .nav-tabs-modalDetalles > li > a {
            display: block;
            padding: 1rem 1.5rem;
            text-decoration: none;
            color: #6c757d;
            font-weight: 500;
            background-color: transparent;
            border: none;
            transition: color 0.2s ease, background-color 0.2s ease;
        }

        .nav-tabs-modalDetalles > li > a:hover {
            color: #333;
            background-color: #f8f9fa;
        }

        .nav-tabs-modalDetalles > li.active > a,
        .nav-tabs-modalDetalles > li.active > a:hover,
        .nav-tabs-modalDetalles > li.active > a:focus {
            color: #007bff;
            border-bottom: 2px solid #007bff;
        }

        .nav-tabs-modalDetalles .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .pull-right-modalDetalles {
            margin-left: auto;
        }

        /* -------------------- Estilos de Contenido y Tablas -------------------- */
        .tab-content-modalDetalles {
            padding: 1.5rem 2rem;
        }

        .table-responsive-modalDetalles {
            overflow-x: auto;
        }

        .table-modalDetalles {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .table-modalDetalles thead th,
        .table-modalDetalles tfoot th,
        .table-modalDetalles tbody td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .table-modalDetalles thead th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .table-modalDetalles.table-striped tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-modalDetalles.table-bordered {
            border: 1px solid #e9ecef;
        }

        .table-modalDetalles.table-bordered th, .table-modalDetalles.table-bordered td {
            border: 1px solid #e9ecef;
        }

        .thead-revisionVehiculo-modalDetalles th {
            background-color: #e9ecef;
            font-weight: 600;
        }

        .td-revisionVehiculo-modalDetalles {
            padding: 8px 10px;
        }

        .check-revisionVehiculo-modalDetalles {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-top: 0;
        }
        
        /* Estilos específicos para la sección de formulario en Tab 3 */
        .form-group-modalDetalles {
            margin-bottom: 1rem;
        }

        .form-control-modalDetalles {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 8px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control-modalDetalles:focus {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-label-modalDetalles {
            display: inline-block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .row-modalDetalles {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-md-6-modalDetalles {
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 15px;
            padding-left: 15px;
        }

        /* -------------------- Estilos del Pie de Página -------------------- */
        .modal-footer-modalDetalles {
            display: flex;
            justify-content: flex-end;
            padding: 1.5rem 2rem;
            border-top: 1px solid #e9ecef;
        }

        .btn-modalDetalles {
            padding: 0.75rem 1.5rem;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .btn-secondary-modalDetalles {
            background-color: #e9ecef;
            color: #333;
        }

        .btn-secondary-modalDetalles:hover {
            background-color: #dae0e5;
            transform: translateY(-2px);
        }

        .btn-primary-modalDetalles {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
            margin-left: 0.75rem;
        }

        .btn-primary-modalDetalles:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }
    </style>
<?php  
} 
ob_end_flush();
?>