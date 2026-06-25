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
  if ($_SESSION['nomina']==1) 
  {

?>
        <link rel="stylesheet" href="https://tempusdominus.github.io/bootstrap-3/theme/css/tempusdominus-bootstrap-3.css" />

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12"> 
                  <div class="box">
                        <div class="callout callout-info">
                          <h4>Modulo de Ficha Empleado!</h4>
                          En este Modulo Podras Crear, Editar, Listar, Buscar y Desactivar </a>
                        </div>
                    <div class="box-header with-border">
                          <h1 class="box-title"><button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Crear Ficha Empleado</button></h1>
                        <div class="box-tools pull-right"> 
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Cod Empleado</th> 
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>DPI No.</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>Cta No.</th>
                            <th>Banco</th>
                            <th>Sueldo Base</th>
                            <th>Bonificacion</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Cod Empleado</th>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>DPI No.</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>Cta No.</th>
                            <th>Banco</th>
                            <th>Sueldo Base</th>
                            <th>Bonificacion</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 900px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Cod Empleado:</label>
                            <input type="text" class="form-control" name="cod_empleado" id="cod_empleado" maxlength="256" placeholder="Cod Empleado">
                          </div>                          
                          <div class="form-group col-lg-10 col-md-10 col-sm-10 col-xs-12">
                            <label>Nombre Completo:</label>
                            <input type="hidden" name="idficha_empleado" id="idficha_empleado">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="256" placeholder="Nombre" required>
                          </div>
                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <label>Direccion:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" maxlength="256" placeholder="Direccion">
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>DPI NO:</label>
                            <input type="text" class="form-control" name="dpi_no" id="dpi_no" maxlength="30" placeholder="0000-00000-0000">
                          </div>                          
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Telefono:</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="15" placeholder="+502-0000-0000">
                          </div> 
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Celular:</label>
                            <input type="text" class="form-control" name="celular" id="celular" maxlength="15" placeholder="+502-0000-0000">
                          </div> 
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Sexo(*):</label>
                            <select name="tipo_sexo" id="tipo_sexo" class="form-control selectpicker" required="">
                               <option value="Masculino">Masculino</option>
                               <option value="Femenino">Femenino</option>
                            </select> 
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>E. Civil(*):</label>
                            <select name="estado_civil" id="estado_civil" class="form-control selectpicker" required="">
                               <option value="Soltero(a)">Soltero(a)</option>
                               <option value="Casado(a)">Casado(a)</option>
                               <option value="Viudo(a)">Viudo(a)</option>
                               <option value="Divorsiado(a)">Divorsiado(a)</option>
                               <option value="Unido(a)">Unido(a)</option>
                            </select> 
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Forma de Pago(*):</label>
                            <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required="">
                               <option value="Efectivo">Efectivo</option>
                               <option value="Deposito">Deposito</option>
                               <option value="Cheque">Cheque</option>
                            </select> 
                          </div>                          
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Email:</label>
                            <input type="Email" class="form-control" name="email" id="email" maxlength="50" placeholder="usuario@dominio.com">
                          </div> 
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Fecha Nacimiento(*):</label>
                            <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required="">
                          </div>                          
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>No. Cta:</label>
                            <input type="text" class="form-control" name="cta_no" id="cta_no" maxlength="256" placeholder="0000-0000">
                          </div>                            
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Tipo de Banco(*):</label>
                            <select name="tipo_banco" id="tipo_banco" class="form-control selectpicker" required="">
                               <option value="Banco Industrial">Banco Industrial</option>
                               <option value="Banco Banrural">Banco Banrural</option>
                               <option value="Banco Gyt">Banco Gyt</option>
                               <option value="Banco Agromercantil">Banco Agromercantil</option>
                            </select> 
                          </div> 
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Sueldo Base</label>
                            <input type="number" step="any" class="form-control" name="sueldo_base" id="sueldo_base"  placeholder="Q00.00">
                          </div> 
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Bonificacion:</label>
                            <input type="number" step="any" class="form-control" name="bonificacion" id="bonificacion" " placeholder="Q 00.00">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Hora Extra:</label>
                            <input type="number" step="any" class="form-control" name="horaext" id="horaext" " placeholder="Q 00.00">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Fecha Ingreso Laboral(*):</label>
                            <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" required="">
                          </div>

                          

                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <fieldset>
                                  <legend>Control de Horas</legend>

                                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                              <label>Hora Entrada:</label>
                                                 <input type="time" required id="horaentrada" name="horaentrada" class="form-control "/>
                                               
                                            </div>
                                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                              <label>Hora Refaccion:</label>
                                              <input type="time" required id="horarefaccion" name="horarefaccion" class="form-control " />
                                                  
                                            </div>

                                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                              <label>Hora Almuerzo:</label>
                                                  <input type="time" required id="horaalmuerzo" name="horaalmuerzo" class="form-control"/>
                                            </div>

                                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                              <label>Hora Salida:</label>
                                                  <input type="time" required id="horasalida"  name="horasalida" class="form-control" />
                                            </div>



                              </fieldset>  
                           </div>

                           <hr>
                           <br>

                          <fieldset>
                            <legend>&nbsp</legend>
                          </fieldset>

                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-success collapsed-box box-solid">
                              <div class="box-header with-border">
                                <h3 class="box-title">Referencias Laborales</h3>

                                <div class="box-tools pull-right">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                  </button>
                                </div>
                                <!-- /.box-tools -->
                              </div>
                              <!-- /.box-header -->
                              <div class="box-body">
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>1)Jefe Inmediato:</label>
                                  <input type="text" class="form-control" name="jefe_immediato_1" id="jefe_immediato_1" maxlength="200" >
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Tiempo de Trabajo:</label>
                                  <input type="text" class="form-control" name="tiempo_trabajo_1" id="tiempo_trabajo_1" maxlength="30" >
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Fecha Finalizacion Laboral(*):</label>
                                  <input type="date" class="form-control" name="fecha_finalizacion_labora_1" id="fecha_finalizacion_labora_1" >
                                </div>                                                               
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Telefono:</label>
                                  <input type="text" class="form-control" name="telefono_1" id="telefono_1" maxlength="15" placeholder="0000-0000">
                                </div> 
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>2)Jefe Inmediato:</label>
                                  <input type="text" class="form-control" name="jefe_immediato_2" id="jefe_immediato_2" maxlength="200" >
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Tiempo de Trabjao:</label>
                                  <input type="text" class="form-control" name="tiempo_trabajo_2" id="tiempo_trabajo_2" maxlength="256" >
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Fecha Finalizacion Laboral(*):</label>
                                  <input type="date" class="form-control" name="fecha_finalizacion_labora_2" id="fecha_finalizacion_labora_2" >
                                </div>                                                               
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Telefono:</label>
                                  <input type="text" class="form-control" name="telefono_2" id="telefono_2" maxlength="15" >
                                </div >
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>3)Jefe Inmediato:</label>
                                  <input type="text" class="form-control" name="jefe_immediato_3" id="jefe_immediato_3" maxlength="200" >
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Tiempo de Trabjao:</label>
                                  <input type="text" class="form-control" name="tiempo_trabajo_3" id="tiempo_trabajo_3" maxlength="256" >
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Fecha Finalizacion Laboral(*):</label>
                                  <input type="date" class="form-control" name="fecha_finalizacion_labora_3" id="fecha_finalizacion_labora_3" >
                                </div>                                                               
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Telefono:</label>
                                  <input type="text" class="form-control" name="telefono_3" id="telefono_3" maxlength="15" placeholder="0000-0000">
                                </div>                    


                              </div>
                              <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-warning  collapsed-box box-solid">
                              <div class="box-header with-border">
                                <h3 class="box-title">Referencias Personales</h3>

                                <div class="box-tools pull-right">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                  </button>
                                </div>
                                <!-- /.box-tools -->
                              </div>
                              <!-- /.box-header -->
                              <div class="box-body">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>1)Nombre:</label>
                                  <input type="text" class="form-control" name="nombre_refe_laboral_1" id="nombre_refe_laboral_1" maxlength="256" ">
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Telefono:</label>
                                  <input type="text" class="form-control" name="telefono_refe_laboral_1" id="telefono_refe_laboral_1" maxlength="15" ">
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Parentesco:</label>
                                  <input type="text" class="form-control" name="parentesco_refe_laboral_1" id="parentesco_refe_laboral_1" maxlength="200" ">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>2)Nombre:</label>
                                  <input type="text" class="form-control" name="nombre_refe_laboral_2" id="nombre_refe_laboral_2" maxlength="200" ">
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Telefono:</label>
                                  <input type="text" class="form-control" name="telefono_refe_laboral_2" id="telefono_refe_laboral_2" maxlength="15" ">
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Parentesco:</label>
                                  <input type="text" class="form-control" name="parentesco_refe_laboral_2" id="parentesco_refe_laboral_2" maxlength="200" ">
                                </div>   
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>3)Nombre:</label>
                                  <input type="text" class="form-control" name="nombre_refe_laboral_3" id="nombre_refe_laboral_3" maxlength="256" ">
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Telefono:</label>
                                  <input type="text" class="form-control" name="telefono_refe_laboral_3" id="telefono_refe_laboral_3" maxlength="256" ">
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Parentesco:</label>
                                  <input type="text" class="form-control" name="parentesco_refe_laboral_3" id="parentesco_refe_laboral_3" maxlength="256" ">
                                </div>                                                                                                                               
                              </div>
                              <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                          </div>  
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-danger  collapsed-box box-solid">
                              <div class="box-header with-border">
                                <h3 class="box-title">Fotos Ficha Empleado</h3>

                                <div class="box-tools pull-right">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                  </button>
                                </div>
                                <!-- /.box-tools -->
                              </div>
                              <!-- /.box-header --> 
                              <div class="box-body">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Dpi Lado 1:</label>
                                  <input type="file" class="form-control" name="dpi_Lado1_imagen" id="dpi_Lado1_imagen">
                                  <input type="hidden" name="dpi_lado1_imagenactual" id="dpi_lado1_imagenactual">
                                  <img src="" width="150px" height="120px" id="dpi_lado1_imagenmuestra">
                                </div> 
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Dpi Lado 2:</label>
                                  <input type="file" class="form-control" name="dpi_lado2_imagen" id="dpi_lado2_imagen">
                                  <input type="hidden" name="dpi_lado2_imagenactual" id="dpi_lado2_imagenactual">
                                  <img src="" width="150px" height="120px" id="dpi_lado2_imagenmuestra">
                                </div> 
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Carta Recomendacion 1:</label>
                                  <input type="file" class="form-control" name="carta_recomendacion1_imagen" id="carta_recomendacion1_imagen">
                                  <input type="hidden" name="carta_recomendacion1_imagenactual" id="carta_recomendacion1_imagenactual">
                                  <img src="" width="150px" height="120px" id="carta_recomendacion1_imagenmuestra">
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Carta Recomendacion 2:</label>
                                  <input type="file" class="form-control" name="carta_recomendacion2_imagen" id="carta_recomendacion2_imagen">
                                  <input type="hidden" name="carta_recomendacion2_imagenactual" id="carta_recomendacion2_imagenactual">
                                  <img src="" width="150px" height="120px" id="carta_recomendacion2_imagenmuestra">
                                </div>  
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Carta Recomendacion 3:</label>
                                  <input type="file" class="form-control" name="carta_recomendacion3_imagen" id="carta_recomendacion3_imagen">
                                  <input type="hidden" name="carta_recomendacion3_imagenactual" id="carta_recomendacion3_imagenactual">
                                  <img src="" width="150px" height="120px" id="carta_recomendacion3_imagenmuestra">
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Carta Trabajo 1:</label>
                                  <input type="file" class="form-control" name="carta_trabajo1_imagen" id="carta_trabajo1_imagen">
                                  <input type="hidden" name="carta_trabajo1_imagenactual" id="carta_trabajo1_imagenactual">
                                  <img src="" width="150px" height="120px" id="carta_trabajo1_imagenmuestra">
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Carta Trabajo 2:</label>
                                  <input type="file" class="form-control" name="carta_trabajo2_imagen" id="carta_trabajo2_imagen">
                                  <input type="hidden" name="carta_trabajo2_imagenactual" id="carta_trabajo2_imagenactual">
                                  <img src="" width="150px" height="120px" id="carta_trabajo2_imagenmuestra">
                                </div>  
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Carta Trabajo 3:</label>
                                  <input type="file" class="form-control" name="carta_trabajo3_imagen" id="carta_trabajo3_imagen">
                                  <input type="hidden" name="carta_trabajo3_imagenactual" id="carta_trabajo3_imagenactual">
                                  <img src="" width="150px" height="120px" id="carta_trabajo3_imagenmuestra">
                                </div>                                                                                                                                                                                                     
                              </div>
                              <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                          </div>                                                    
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
<script type="text/javascript" src="scripts/ficha_empleado.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data-2012-2022.min.js"></script>
<script src="https://tempusdominus.github.io/bootstrap-3/theme/js/tempusdominus-bootstrap-3.js"></script>
<script>
  $(function () {
        /*$('#horaentrada,#horarefaccion,#horaalmuerzo,#horasalida').datetimepicker({
            format: 'LT'
        });*/
    });

</script>


<?php  
} 
ob_end_flush(); 
?>
