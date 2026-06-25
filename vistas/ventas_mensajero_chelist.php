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
  if ($_SESSION['ventasxmensajero']==1)   
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
                          Ventas  
                          <small>Ventas Mensajeros Check list</small>
                          </h1>
                          <ol class="breadcrumb">
                          <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                          <li class="active">Ventas Mensajeros</li>
                          </ol>

                          </section>                                                
                        </div>                                                 

                    <!-- /.box-header -->
                    <!-- centro --> 
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="box-header with-border">
                          <h3 class="box-title">Ventas Mensajeros</h3>

                        </div>
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>#Idventa</th>
                            <th>Cliente</th>
                            <th>Fecha Doc</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>#Idventa</th>
                            <th>Cliente</th>
                            <th>Fecha Doc</th>
                          </tfoot>
                        </table>
                      </div>                  
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">

                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
 
    </div><!-- /.content-wrapper -->
 
 


<div class="modal fade" id="myModalCheklistMensajero" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 40% !important;">
    <div class="modal-content">
      <div class="modal-body">
        <!-- Incluye la librería de Signature Pad -->
        
        <form name="formulario_comentarioMensajero" id="formulario_comentarioMensajero" method="POST">
          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label>Firma de satisfacción:</label>
            <input type="hidden" name="idventa_Mensajero" id="idventa_Mensajero">
            <canvas id="signatureCanvas" width="100%" height="150" style="border: 1px solid #000;"></canvas>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="button" id="btnGuardarComentariosMensajero"><i class="fa fa-save"></i> Guardar Comentario</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> 
      </div>        
    </div>
  </div>
</div>
      
  <!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php'; 
?> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.0.1/signature_pad.umd.min.js"></script>
<script type="text/javascript" src="scripts/sweatlert.js"></script> 
<script type="text/javascript" src="scripts/ventas_mensajero_chelist.js"></script>     

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function () {
    var modal = document.getElementById("myModalCheklistMensajero");
    var canvas = document.getElementById("signatureCanvas");
    var signaturePad;

    // Verificar si existe el canvas
    if (canvas) {
        function resizeCanvas() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = 150 * ratio;  // Altura fija para evitar distorsión
            canvas.getContext("2d").scale(ratio, ratio);
        }

        // Inicializar firma solo cuando el modal se muestra completamente
        $('#myModalCheklistMensajero').on('shown.bs.modal', function () {
            resizeCanvas(); // Ajustar tamaño del canvas
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: "rgb(255,255,255)" // Fondo blanco
            });
        });

        // Botón de borrar firma (agregarlo si no existe)
        if (!document.getElementById("clearSignatureButton")) {
            var clearButton = document.createElement("button");
            clearButton.id = "clearSignatureButton";
            clearButton.textContent = "Borrar Firma";
            clearButton.classList.add("btn", "btn-warning", "btn-sm");
            clearButton.style.marginTop = "10px";
            clearButton.onclick = function () {
                signaturePad.clear();
            };
            canvas.parentNode.appendChild(clearButton);
        }

        // Guardar firma al presionar el botón de guardar
        document.getElementById("btnGuardarComentariosMensajero").addEventListener("click", function () {
            if (signaturePad && !signaturePad.isEmpty()) {
                var firmaData = signaturePad.toDataURL(); // Convierte la firma en base64
                console.log("Firma guardada:", firmaData);
            } else {
                alert("Por favor, firma antes de guardar.");
            }
        });
    }
});

</script>


<?php  
} 
ob_end_flush();
?>