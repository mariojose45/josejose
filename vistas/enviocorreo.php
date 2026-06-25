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
  if ($_SESSION['cotizaciones']==1) 
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
                          <h4>Modulo de Envio de Correos!</h4>
                          En este Modulo podras enviar correos de tus cotizaciones</a>
                        </div>
 

                    <div class="panel-body" style="height: 500px;" id="formularioregistros">
                       <form id="form1" class="well col-lg-12" action="enviar.php" method="post" name="form1" enctype="multipart/form-data">
                          <div class="row">
                           <div class="col-lg-6">
                            <label>Nombre*</label> <input id="Nombre" class="form-control" type="text" name="Nombre" /> 
                            <label>Email*</label> <input id="Email" class="form-control" type="email" name="Email" />
                           </div>
                            <div class="col-lg-6"><label>Mensaje*</label> 
                             <textarea id="Mensaje" class="form-control" name="Mensaje" rows="4"></textarea>
                            </div>
                             <div class="col-lg-12">
                        <label for="exampleInputFile">Adjuntar archivo</label>
                        <input type="file" name="adjunto" id="archivo-adjunto">
                        <p class="help-block">Example block-level help text here.</p>
                      </div>
                            <button class="btn btn-default pull-right" type="submit">Enviar</button>
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
<script type="text/javascript" src="scripts/enviarcorreo.js"></script> 

<?php  
} 
ob_end_flush();
?>