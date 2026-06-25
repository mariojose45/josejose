<?php
//Activamos el almacenamiento en el buffer
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión


if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
    if ($_SESSION['parqueo_Info_Ticke_Fac'] == 1) {

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
                                        Información de Tickets
                                        <small>Parqueo</small>
                                    </h1>
                                    <ol class="breadcrumb">
                                        <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                                        <li class="active">Parqueo</li>
                                    </ol>

                                </section>

                            </div>

                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Empresa</th>
                                        <th>Info ticket</th>
                                        <th>Mensaje Final</th>
                                        <th>Correlativo</th>
                                        <th>Horario</th>
                                        <th>Estado</th>
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
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" style="height: 400px;" id="formularioregistros">
                                <style>
                                    .box-body {
                                        padding: 20px;
                                    }

                                    .form-group label {
                                        font-weight: 600;
                                    }

                                    textarea.form-control {
                                        resize: none;
                                    }
                                </style>
                                <form name="formulario" id="formulario" method="POST">

                                    <input type="hidden" name="id_informacionticket" id="id_informacionticket">

                                    <!-- MENSAJE -->
                                    <div class="form-group col-md-12">
                                        <label><i class="fa fa-ticket"></i> Mensaje de Ticket</label>
                                        <textarea name="instruccionesdeticket" id="instruccionesdeticket" class="form-control"
                                            rows="4" placeholder="Escribe instrucciones para el ticket..."></textarea>
                                    </div>

                                    <!-- MENSAJE FINAL -->
                                    <div class="form-group col-md-4">
                                        <label><i class="fa fa-file-text"></i> Mensaje Final Factura</label>
                                        <input type="text" class="form-control" name="mensajefinal" id="mensajefinal"
                                            placeholder="Mensaje que aparecerá en la factura">
                                    </div>

                                    <!-- CORRELATIVO -->
                                    <div class="form-group col-md-4">
                                        <label><i class="fa fa-hashtag"></i> Correlativo Factura</label>
                                        <input type="number" class="form-control" name="correlativo_ticket"
                                            id="correlativo_ticket" placeholder="Ej: 1001">
                                    </div>
                                    <!-- HORARIO DE ATENCIO -->
                                    <div class="form-group col-md-4">
                                        <label><i class="fa fa-hashtag"></i> Horario</label>
                                        <input type="text" class="form-control" name="horario_atencion" id="horario_atencion"
                                            placeholder="Ej: 08:00 - 18:00">
                                    </div>

                                    <!-- BOTONES -->
                                    <div class="col-md-12 text-right" style="margin-top: 15px;">
                                        <button class="btn btn-primary" id="btnGuardar" name="btnGuardar">
                                            <i class="fa fa-save"></i> Guardar
                                        </button>

                                        <button type="button" class="btn btn-default" onclick="cancelarform()">
                                            <i class="fa fa-times"></i> Cancelar
                                        </button>
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
    <script type="text/javascript" src="scripts/parqueo_Info_Ticke_Fac.js"></script>


    <?php
}
ob_end_flush();
?>