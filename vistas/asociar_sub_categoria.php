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
  if ($_SESSION['almacen_asociar_sub_categoria']==1) 
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
                          Asociar Sub Categoría  
                          <small>Almacen</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Almacen</li>
                          </ol>

                          </section>  
                          <h1 class="box-title"> <button class="btn btn-success btn-block" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> </h1>                                             
                        </div> 

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Categoria</th>
                            <th>User Creación</th>
                            <th>Sucursal</th>
                            <th>F-Creación</th>
                            <th>User-Modificación</th>
                            <th>F-Modificación</th>
                            <th>Condición</th>
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
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 1000px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                        <div class=" col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Categoría(*):</label>
                                <input type="hidden" name="idasociar_subcategoria" id="idasociar_subcategoria">
                                <select id="idcategoria" name="idcategoria" class="form-control selectpicker" data-live-search="true" required></select>
                            </div>  
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary btn-block" type="button" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                                <button class="btn btn-danger btn-block" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>                               
                                </tfoot>
                                <tbody>
                                   
                                </tbody> 
                            </table>
                          </div>                            
                        </div>
                        <div class=" col-lg-8 col-md-12 col-sm-12 col-xs-12 table-responsive">
                            <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover table-responsive">
                                <thead>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                </thead>
                                <tbody> 
                                
                                </tbody>
                                <tfoot>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                </tfoot>
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
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/asociar_sub_categoria.js"></script> 

<?php  
} 
ob_end_flush();
?>