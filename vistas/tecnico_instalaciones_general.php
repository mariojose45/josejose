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
  if ($_SESSION['tecnico_instalaciones_x_user']==1) 
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
                                Tecnico Instalaciones General
                                <small>Ventas</small>
                            </h1>
                            <ol class="breadcrumb">
                                <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                                <li class="active">Ventas</li>
                            </ol>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Fecha Inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio_reporte"
                                        id="fecha_inicio_reporte" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Fecha Fin</label>
                                    <input type="date" class="form-control" name="fecha_fin_reporte"
                                        id="fecha_fin_reporte" value="<?php echo date("Y-m-d"); ?>">
                                    <button class="btn btn-success btn-block" onclick="listar()">Generar Rpt</button>
                                </div>
                                <table id="tbllistado"
                                    class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>ID Venta</th>
                                        <th>Cliente</th>
                                        <th>Usuario</th>
                                        <th>Tipo Doc</th>
                                        <th>T. V</th>
                                        <th>T. V. Des</th>
                                        <th>F/Pago</th>
                                        <th>Efectivo</th>
                                        <th>Tarjeta</th>
                                        <th>Credito</th>
                                        <th>Transferencia</th>
                                        <th>Cambio</th>
                                        <th>Fecha</th>
                                        <th>Fecha/Certi</th>
                                        <th>Serie/Certi</th>
                                        <th>DTE/Certi</th>
                                        <th>Tipo/Entrega</th>
                                        <th>Vendedor</th>
                                        <th>Estado</th>
                                        <th>Estado Servicio</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" style="height: 400px;" id="formularioregistros">
                                <form name="formulario" id="formulario" method="POST">

                                    <button id="btnHelpFloating" class="btn btn-info">
                                        <i class="fa fa-question-circle"></i> Ayuda
                                    </button>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary btn-block" type="button" id="btnGuardar"><i
                                                class="fa fa-save"></i> Guardar</button>

                                        <button class="btn btn-danger btn-block" onclick="cancelarform()" type="button"
                                            id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<div id="modalServicio" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Actualizar Estado del Servicio</h4>
            </div>
            <div class="modal-body">
                <form id="formServicio">
                    <input type="hidden" id="idventa_servicio" name="idventa_servicio">

                    <div class="form-group">
                        <label>Comentarios:</label>
                        <textarea class="form-control" id="descripcion_comentario" name="descripcion_comentario"
                            rows="3" placeholder="Escribe observaciones del servicio..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>No. de Instalación / IP:</label>
                        <input type="text" class="form-control" id="ip_instalacion" name="ip_instalacion"
                            placeholder="Ej: 192.168.1.10">
                    </div>

                    <div class="form-group">
                        <label>Estado del Servicio:</label>
                        <select class="form-control" id="estado_servicio_venta" name="estado_servicio_venta">
                            <option value="PENDIENTE">PENDIENTE</option>
                            <option value="NO ENTREGADO">NO ENTREGADO</option>
                            <option value="ENTREGADO">ENTREGADO</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ubicación:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="ubicacioncliente" id="ubicacioncliente"
                                maxlength="250" placeholder="Ubicación no definida" readonly>
                            <button type="button" class="btn btn-success" id="btnCapturarUbicacion"
                                onclick="capturarUbicacion()">
                                <i class="fa fa-map-marker"></i> Capturar Ubicación
                            </button>
                            <a id="verEnMapa" class="btn btn-primary" href="#" target="_blank" style="display: none;">
                                <i class="fa fa-map"></i> Ver en Google Maps
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarServicio()">Guardar Cambios</button>
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
<script type="text/javascript" src="scripts/tecnico_instalaciones_general.js"></script>

<!-- ✅ Luego Driver.js -->
<script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css" />


<?php  
} 
ob_end_flush();
?>