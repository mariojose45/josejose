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

?><?php
date_default_timezone_set('America/Guatemala');
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">      
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row"> 
              <div class="col-md-12">
                  <div class="box">
                        <div class="callout callout-info">
                          <h4>Modulo de Pagos Empleados!</h4> 
                          En este Modulo Podras Crear, Editar, Listar, Buscar y Desactivar </a>
                        </div>
                    <div class="box-header with-border"> 
                          <h1 class="box-title"> <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1> 
                        <div class="box-tools pull-right">
                        </div>  
                    </div> 
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>#Idpago Empleado</th>
                            <th>Empleado</th>
                            <th>Fecha Pago</th>
                            <th>Descripcion</th>
                            <th>Horas_acumuladas</th>
                            <th>Horas Tarde</th>
                            <th>Horas Extra</th>
                            <th>Sueldo Liquido</th>
                            <th>Forma pago</th>                            
                            <th>Cta No.</th>
                            <th>Autorizaicon No.</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>#Idpago Empleado</th>
                            <th>Empleado</th>
                            <th>Fecha Pago</th>
                            <th>Descripcion</th>
                            <th>Horas_acumuladas</th>
                            <th>Horas Tarde</th>
                            <th>Horas Extra</th>
                            <th>Sueldo Liquido</th>
                            <th>Forma pago</th>                            
                            <th>Cta No.</th>
                            <th>Autorizaicon No.</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 800px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="box box-danger">
                            <div class="box-header with-border">
                              <h3 class="box-title">Datos Empleado </h3>
                            </div>
                            <div class="box-body">
                              <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Nombre Empleado:</label>
                                  <input type="hidden" name="idpagoempleado" id="idpagoempleado">
                                  <select id="idficha_empleado" name="idficha_empleado" class="form-control selectpicker" data-live-search="true" required>
                                  </select>
                                </div>
                                <select id="idficha_empleado23" name="idficha_empleado23" style="display:none"  required>
                                   
                                </select>              
                                <input type="hidden" name="valorhoraextra" id="valorhoraextra">                  

                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Fecha Inicial(*):</label>
                                  <input type="date" class="form-control" name="fecha_hora_ini" id="fecha_hora_ini" required="">
                                </div> 
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Fecha Final(*):</label>
                                  <input type="date" class="form-control" name="fecha_hora_fin" id="fecha_hora_fin" required="">
                                </div>        


                                <div class="row" style="padding:20px">
                                  <button type="button" id="consultarregistros" class="btn btn-primary">Consultar Registros</button>
                                  <br><br>
                                  <center>
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr>
                                          <td>Codigo</td>
                                          <td>Tipo Registro</td>
                                          <td>Hora de Registro</td>
                                          <td></td>
                                        </tr>
                                      </thead>
                                      <tbody id="datosregistros">
                                      </tbody>
                                    </table>
                                  </center>
                                  <!-- Modal --> 
                                  <div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                                    <div class='modal-dialog' role='document'>
                                      <div class='modal-content'>
                                        <div class='modal-header'>
                                          <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                          <h4 class='modal-title' id='myModalLabel'>Agregar Registro</h4>
                                        </div>
                                        <div class='modal-body'>
                                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <label>Codigo:</label>
                                            <input type="text" class="form-control" name="codigo33" id="codigo33"  placeholder="Codigo">
                                          </div>
                                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <label>Tipo:</label>
                                            <select class="form-control" name="tipo33" id="tipo33">
                                              <option value="0">Entrada</option>
                                              <option value="1">Salida</option>
                                            </select>
                                          </div>
                                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <label>Fecha y Hora:</label>
                                            <input type="date" value='<?php echo date("Y-m-d") ?>' class="form-control" name="fecha33" id="fecha33"  placeholder="Fecha">
                                            <input type="time" value='<?php echo date("H:i") ?>' class="form-control" name="hora33" id="hora33"  placeholder="Hora">
                                          </div>
                                        </div>
                                        <div class='modal-footer'>
                                          <button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
                                          <button type='button' id="btnguardarregistro" class='btn btn-primary'>Guardar Cambios</button>
                                        </div>
                                      </div>
                                    </div> 
                                  </div>

                                </div>

                                <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                  <label>Descripción:</label>
                                  <input type="text" class="form-control" name="descripcion" id="descripcion"  placeholder="00:00">
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Fecha de Generacion pago(*):</label>
                                  <input type="date" class="form-control" name="fecha_generacion_pago" id="fecha_generacion_pago" required="">
                                </div>                                 
                              </div>
                            </div>
                            <!-- /.box-body -->
                          </div>

                          <!-- /.col -->
                          <div class="col-md-4">
                            <div class="box box-success box-solid">
                              <div class="box-header with-border">
                                <h3 class="box-title">Control Horas</h3>

                                <div class="box-tools pull-right">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                  </button>
                                </div>
                                <!-- /.box-tools -->
                              </div> 
                              <!-- /.box-header -->
                              <div class="box-body">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <label>Horas Acumuladas :</label>
                                  <input type="text" onchange="Sumar();" class="form-control" name="horas_acumuladas" id="horas_acumuladas" maxlength="256" placeholder="00:00" step="any">
                                </div> 
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <label>Horas Tarde :</label>
                                  <input type="text" onchange="Sumar();" class="form-control" name="horas_tarde" id="horas_tarde" maxlength="256" placeholder="00:00" step="any">
                                </div> 
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <label>Horas Extras :</label>
                                  <input type="text" onchange="Sumar();" class="form-control" name="horas_extra" id="horas_extra" maxlength="256" placeholder="00:00" step="any">
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                  <label>Horas Feriados :</label>
                                  <input type="text" value="00:00" onchange="Sumar();" class="form-control" name="horas_feriado" id="horas_feriado" maxlength="256" placeholder="00:00" step="any">
                                </div>                                                           
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <label>Total Horas a Pagar:</label>
                                  <input type="text" class="form-control" name="total_horas_pagar" id="total_horas_pagar" maxlength="256" placeholder="00:00" step="any">
                                </div>
                            <button class="btn btn-primary" type="button" id="btnconsultar"><i class="fa fa-save"></i> Consultar</button>          
                            <button class="btn btn-primary" onclick="Sumar()" type="button" id="btncalcular"><i class="fa fa-clock-o"></i> Calcular</button>                      
                               <script type="text/javascript">
                                  function Sumar() {
                                      var h1 = document.getElementById('horas_acumuladas').value;
                                      var h1split=h1.split(":");

                                      var h1_1 = document.getElementById('horas_extra').value;
                                      var h1split_1=h1_1.split(":");

                                      h1split[0]= parseFloat(h1split[0])-parseFloat(h1split_1[0]);
                                      h1split[1]= parseFloat(h1split[1])-parseFloat(h1split_1[1]);


                                      var h4 = document.getElementById('horas_feriado').value;
                                      var h4split=h4.split(":");

                                      console.log((parseFloat(h1split[0])+parseFloat(h4split[0])));

                                      console.log(((parseFloat(h1split[0]/60))+(parseFloat(h4split[0])/60)));

                                      var horasdecimal=(parseFloat(h1split[0])+parseFloat(h4split[0]))+((parseFloat(h1split[1]/60))+(parseFloat(h4split[1])/60));
                                      

                                      var resultado_h = horasdecimal;

                                      document.getElementById('total_horas_pagar').value = resultado_h.toFixed(2);
                                      $("#total_horas_pagar").val(resultado_h.toFixed(2));

                                      var horas=parseFloat($("#valor_hora").val());
                                      var bono=parseFloat($("#bonificacion").val());
                                      var bonoextra=parseFloat($("#bonificacion_extra").val());
                                      var descuento=parseFloat($("#descuento").val());
                                      var sueldobase=parseFloat($("#sueldo_pagar").val());
                                      var sueldoprestaciones=parseFloat($("#prestacion_a_sumar").val());

                                      var sueldoporhora=resultado_h*horas;

                                      var sueldoneto=((sueldoporhora+bono+bonoextra)-descuento)+sueldoprestaciones;

                                      var arrayhe=$("#horas_extra").val().split(":");
                                      var he=parseFloat(arrayhe[0]);

                                      var valhe=parseFloat($("#valorhoraextra").val())
                                      var recibirval=$("#sueldo_liquido_recibir").val()==""?"0":$("#sueldo_liquido_recibir").val();
                                      var recibir=parseFloat(recibirval);

                                      var totalex=he*valhe;
                                      //$("#sueldo_liquido_recibir").val(total.toFixed(2))
  
                                      $("#sueldo_liquido_recibir").val((sueldoneto+totalex).toFixed(2));

                                       //return dias;
                                    //calcular las prestacioens de un empreado segun el rango de fechas asignado
                                    let fecha1 = new Date($("#fecha_hora_de").val());
                                    let fecha2 = new Date($("#fecha_hora_asta").val());

                                    let resta = fecha2.getTime() - fecha1.getTime()
                                    var resultadorestafecha = (Math.round(resta/ (1000*60*60*24)))

                                    $("#dtrabajados").val(resultadorestafecha);
                                    
                                    var resabono14 = (resultadorestafecha*sueldobase)/365
                                    $("#bono14").val(resabono14);

                                    var resaguinaldo = (((sueldobase/30)*30)/365)*resultadorestafecha
                                    $("#aguinaldo").val(resaguinaldo);

                                    var resvacaciones = ((sueldobase/2)/365)*resultadorestafecha
                                    $("#vacaciones").val(resvacaciones);   
                                  }

                                </script>
                              </div>
                              <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                          </div> 
                          <!-- /.col -->
                          <div class="col-md-4">
                            <div class="box box-warning box-solid">
                              <div class="box-header with-border">
                                <h3 class="box-title">Desglose Sueldo</h3>

                                <div class="box-tools pull-right">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                  </button>
                                </div>
                                <!-- /.box-tools -->
                              </div>
                              <!-- /.box-header -->
                              <div class="box-body">

                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Valor hora Q:</label>
                                <input type="number" onchange="SumarS();" class="form-control" name="valor_hora" id="valor_hora" maxlength="256" placeholder="00:00" step="any">
                              </div>  
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Sueldo Base Q:</label>
                                <input type="number"  class="form-control" name="sueldo_pagar" id="sueldo_pagar" maxlength="256" placeholder="00:00" step="any">
                              </div> 
                            <script type="text/javascript">
                                 function SumarS() {
                                      /*var v1 = document.getElementById('valor_hora').value;
                                      var v2 = document.getElementById('total_horas_pagar').value;

                                      //variable de calculo de sueldo a pagar
                                      var resultado_s = parseFloat(v1) * parseFloat(v2);
                                      //impresion de el dato del sueldo a pagar
                                      document.getElementById('sueldo_pagar').innerHTML = resultado_s;
                                      $("#sueldo_pagar").val(resultado_s).toFixed(2);*/
                                  }
                            </script>
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Bonificacion Ley Q:</label>
                                <input type="number" value="0" onchange="SumarSl();" class="form-control" name="bonificacion" id="bonificacion" maxlength="256" placeholder="00:00" step="any">
                              </div> 
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Bonificacion Extra Q:</label>
                                <input type="number" value="0"  onchange="SumarSl();" class="form-control" name="bonificacion_extra" id="bonificacion_extra" maxlength="256" placeholder="00:00" step="any">
                              </div> 
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="progress-group">
                                  <span class="progress-text">Calculos de Prestaciones</span>
                                  <span class="progress-number"><b></span>

                                  <div class="progress sm">
                                    <div class="progress-bar progress-bar-aqua" style="width: 100%"></div>
                                  </div>
                                </div>
                              </div>                  
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Fecha de(*):</label>
                                  <input type="date" class="form-control" name="fecha_hora_de" id="fecha_hora_de" required="">
                                </div> 
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Fecha asta(*):</label>
                                  <input type="date" class="form-control" name="fecha_hora_asta" id="fecha_hora_asta" required="">
                                </div>        
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Dias trabajados:</label>
                                <input type="text"   class="form-control" name="dtrabajados" id="dtrabajados" maxlength="256" placeholder="dd" > 
                              </div> 
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Prestacion a pagar(*):</label>
                                <select name="prestacion_a_pagar" id="prestacion_a_pagar" class="form-control selectpicker" required="">
                                   <option value="bono14">bono14</option>
                                   <option value="aguinaldo">aguinaldo</option>
                                   <option value="vacaciones">vacaciones</option>
                                </select>
                              </div>                              
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Bono14 Q:</label>
                                <input type="text"   class="form-control" name="bono14" id="bono14" maxlength="256" placeholder="00:00" > 
                              </div> 
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Aguinaldo Q:</label>
                                <input type="text"   class="form-control" name="aguinaldo" id="aguinaldo" maxlength="256" placeholder="00:00" >
                              </div> 
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Vacaciones Q:</label>
                                <input type="text"   class="form-control" name="vacaciones" id="vacaciones" maxlength="256" placeholder="00:00" >
                              </div>  
                              <script type="text/javascript">
                                 

                              </script>                                                           
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="progress-group">
                                  <span class="progress-text"></span>
                                  <span class="progress-number"><b></span>

                                  <div class="progress sm">
                                    <div class="progress-bar progress-bar-aqua" style="width: 100%"></div>
                                  </div>
                                </div>
                              </div> 
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Descuentos Q:</label>
                                <input type="number" value="0" min="0"  onchange="SumarSl();" class="form-control" name="descuento" id="descuento" maxlength="256" placeholder="00:00" step="any">
                              </div>    

                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Sueldo Liquido a Recibir Q:</label>
                                <input type="text"  min="0" class="form-control" name="prestacion_a_sumar" id="prestacion_a_sumar"  step="any">                                
                                <input type="number"  class="form-control" name="sueldo_liquido_recibir" id="sueldo_liquido_recibir" maxlength="256" placeholder="00:00" step="any">
                              </div> 

                                                                                         
                              </div>
                              <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                          </div>  

                            <script type="text/javascript">
                                 function SumarSl() {
                                      //*****************************************************//
                                      /*var v3 = document.getElementById('sueldo_pagar').value;
                                      var v4 = document.getElementById('bonificacion_extra').value;
                                      var v5 = document.getElementById('descuento').value;

                                      //variable de calculo de sueldo a pagar
                                      var resultado_ss = parseFloat(v3) + parseFloat(v4) -  parseFloat(v5);
                                      //impresion de el dato del sueldo a pagar
                                      document.getElementById('sueldo_liquido_recibir').innerHTML = resultado_ss;
                                      $("#sueldo_liquido_recibir").val(resultado_ss).toFixed(2);*/
                                  }
                            </script>  

                          <div class="col-md-4">
                            <div class="box box-success box-solid">
                              <div class="box-header with-border">
                                <h3 class="box-title">Control Bancos</h3>

                                <div class="box-tools pull-right">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                  </button>
                                </div>
                                <!-- /.box-tools -->
                              </div>
                              <!-- /.box-header -->
                              <div class="box-body">
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Cta Bancaria(*):</label>
                                <select id="idcuenta" name="idcuenta" class="form-control selectpicker" data-live-search="true" required></select>
                              </div>
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Forma Pago (*):</label>
                                <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required="">
                                   <option value="Cheque">Cheque</option>
                                   <option value="Transferencia">Transferencia</option>
                                   <option value="Efectivo">Efectivo</option>
                                </select>
                              </div> 
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Cheque No/ No. Auto:</label>
                                <input type="text" class="form-control" name="cheque_auto_no" id="cheque_auto_no" maxlength="20" placeholder="00000">
                              </div>                                                           
                              </div>
                              <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
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
<script type="text/javascript" src="scripts/pagos_empleados.js"></script>           
<script src="http://momentjs.com/downloads/moment.min.js"></script>
<?php  
} 
ob_end_flush();
?>