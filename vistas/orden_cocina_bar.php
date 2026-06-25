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
  if ($_SESSION['restauranteordenes']==1)   
  {  
    
?>    
<!--Contenido--> 
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="app">        
        <!-- Main content -->
        <section class="content">
            <div class="row"> 
              <div class="col-md-12">
                  <div class="box">
                    <div class="col-lg-12 col-xs-12">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>{{title}}</h3>
                          <H4>{{subtitle}}</H4>                          
                        </div>
                        <div class="icon">
                          <i class="fa fa-home"></i>
                        </div>

                      </div>
                    </div>  
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <div v-for="item in ordenes" class="col-lg-4 col-sm-6 col-md-6 col-xs-12">
                        <div class="panel panel-default">
                          <div class="panel-body">
                            <center>
                              Fecha: <br> {{item.add_fecha_hora}} <br>
                              Cliente: {{item.nombre}} <br>
                              Direccion: {{item.direccion}} <br>
                              Telefono: {{item.telefono}}<br>
                              N Orden: #{{item.id_add_orden}}
                            </center>

                            <table class='table table-bordered'>
                              <tr>
                                <th>Cantidad</th>
                                <th>Descripcion</th>
                                <th></th>
                              </tr>
                              <tr v-for="detalle in item.detalle">
                                <td>{{detalle.cantidad}}</td>
                                <td>{{detalle.nombre_articulo}} </td><td>{{detalle.descripcion_detalle}}  {{detalle.comentarios}} </td>
                                <td>
                                  <button class='btn btn-success' @click='compleaddetalle(detalle.iddetalle_add_orden)'>Enviar</button>
                                </td>
                              </tr>
                            </table>

                          </div>
                        </div>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script type="text/javascript" src="scripts/orden_cocina_bar.js"></script>   

<?php  
} 
ob_end_flush();
?>