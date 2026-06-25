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
  if ($_SESSION['almacen']==1) 
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
                        <div class="callout callout-info">
                          <h4>Modulo de Categorias!</h4>
                          En este Modulo Podras Crear, Editar, Listar, Buscar y Desactivar </a>
                        </div>
                    <div class="box-header with-border">
                          <h1 class="box-title">Categoría <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 800px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <label>Marca(*):</label>
                            <input type="hidden" name="idaccesorios" id="idaccesorios">                            
                            <select id="idmarca" name="idmarca" class="form-control selectpicker" data-live-search="true" required></select>
                          </div> 
                          <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <label>Linea(*):</label>
                            <select id="idlinea" name="idlinea" class="form-control selectpicker" data-live-search="true" required></select>
                          </div>  
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>#:</label>
                            <input type="number" class="form-control" name="num_filas" id="num_filas" placeholder="00">
                            <button class="btn btn-primary" type="button" id="btncalcular"><i class="fa fa-gear"></i></button>
                          </div> 
                          <script type="text/javascript">
                              document.getElementById("btncalcular").onclick=function(){
                              var usuario= document.getElementById("num_filas").value;
                              var repeticiones = 0;

                              while(repeticiones<usuario){

                                document.getElementById("resultadoaccesorios").innerHTML+= " <label class='form-group col-lg-2 col-md-2 col-sm-2 col-xs-2'>Accesorio #"+(repeticiones+1)+"</label><input class='form-group col-lg-2 col-md-2 col-sm-2 col-xs-2'  type='text' id='codaccesorio[]' name='codaccesorio[]' class='form-control' maxlength='50' placeholder='Cod'> "+"<input class='form-group col-lg-8 col-md-8 col-sm-8 col-xs-8'  type='text' id='descripcion_accesorio[]' name='descripcion_accesorio[]' class='form-control' maxlength='250' placeholder='Descripcion'  > "+ "</br>";
                                repeticiones++;

                              } 
                            
                            }
                       
                        </script>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="resultadoaccesorios">
                          </div>                                                   
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
<script type="text/javascript" src="scripts/accesorios.js"></script> 

<?php  
} 
ob_end_flush();
?>