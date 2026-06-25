<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
    if ($_SESSION['nomina_empleados'] == 1) {

        ?>
        <div class="content-wrapper">
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border bg-gray-light" style="padding-bottom: 20px;">
                                <h2 class="box-title" style="font-weight: 600;"><i class="fa fa-users margin-r-5"></i> Gestión
                                    de Empleados</h2>
                                <p class="text-muted">Módulo para crear, editar, listar y gestionar la nómina de empleados.</p>
                            </div>
                            <div class="box-header with-border">
                                <h1 class="box-title"><button class="btn btn-info pull-right btn-block" id="btnagregar"
                                        onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Nuevo
                                        Empleado</button></h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>CUI/DPI - NIT</th>
                                        <th>Dirección</th>
                                        <th>Edad</th>
                                        <th>Sexo</th>
                                        <th>Puesto</th>
                                        <th>Salario Base</th>
                                        <th>Bonificación</th>
                                        <th>Bono Productividad</th>
                                        <th>Salario Extra</th>
                                        <th>IGSS</th>
                                        <th>ISR</th>
                                        <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>CUI/DPI - NIT</th>
                                        <th>Dirección</th>
                                        <th>Edad</th>
                                        <th>Sexo</th>
                                        <th>Puesto</th>
                                        <th>Salario Base</th>
                                        <th>Bonificación</th>
                                        <th>Bono Productividad</th>
                                        <th>Salario Extra</th>
                                        <th>IGSS</th>
                                        <th>ISR</th>
                                        <th>Estado</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" id="formularioregistros">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="row custom-form-section">
                                        <h4 class="text-primary"><i class="fa fa-user margin-r-5"></i> Datos Personales</h4>
                                        <hr class="custom-hr">
                                        <div class="form-group col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                            <label>Cod-Empleado (*):</label>
                                            <input type="text" class="form-control" name="codigo_empleado" id="codigo_empleado"
                                                maxlength="10" placeholder="Codigo Empleado" required>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label>Nombres Completos (*):</label>
                                            <input type="hidden" name="idempleado_r" id="idempleado_r">
                                            <input type="text" class="form-control" name="nombres" id="nombres" maxlength="100"
                                                placeholder="Nombres" required>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>CUI/DPI (*):</label>
                                            <input type="text" class="form-control" name="cui" id="cui" maxlength="13"
                                                placeholder="CUI/DPI" required>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>NIT:</label>
                                            <input type="text" class="form-control" name="nit" id="nit" maxlength="15"
                                                placeholder="NIT">
                                        </div>
                                        <div class="form-group col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                            <label>Dirección:</label>
                                            <input type="text" class="form-control" name="direccion" id="direccion"
                                                maxlength="256" placeholder="Dirección completa">
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Fecha de Nacimiento:</label>
                                            <input type="date" class="form-control" name="fecha_nacimiento"
                                                id="fecha_nacimiento">
                                        </div>
                                    </div>

                                    <div class="row custom-form-section">
                                        <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                            <label>Hijos:</label>
                                            <input type="number" class="form-control" name="hijos" id="hijos" value="0" min="0">
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Sexo:</label>
                                            <select name="sexo" id="sexo" class="form-control selectpicker"
                                                data-live-search="true">
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Estado Civil:</label>
                                            <select name="estado_civil" id="estado_civil" class="form-control selectpicker"
                                                data-live-search="true">
                                                <option value="Soltero(a)">Soltero(a)</option>
                                                <option value="Casado(a)">Casado(a)</option>
                                                <option value="Unido(a)">Unido(a)</option>
                                                <option value="Divorciado(a)">Divorciado(a)</option>
                                                <option value="Viudo(a)">Viudo(a)</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                            <label>Edad:</label>
                                            <input type="text" class="form-control" name="edad" id="edad" placeholder="">
                                        </div>
                                        <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                            <label>Teléfono:</label>
                                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="15"
                                                placeholder="Teléfono">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label>Nacionalidad:</label>
                                            <input type="text" class="form-control" name="nacionalidad" id="nacionalidad"
                                                maxlength="50" value="Guatemalteca" placeholder="Nacionalidad">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label>Nivel Educativo:</label>
                                            <select name="nivel_educativo" id="nivel_educativo"
                                                class="form-control selectpicker" data-live-search="true">
                                                <option value="Primaria">Primaria</option>
                                                <option value="Basico/Secundaria">Basico/Secundaria</option>
                                                <option value="Diversificado/Bachiller">Diversificado/Bachiller</option>
                                                <option value="Tecnico Universitario">Tecnico Universitario</option>
                                                <option value="Licenciatura/Universidad">Licenciatura/Universidad</option>
                                                <option value="Postgrado">Postgrado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row custom-form-section">
                                        <h4 class="text-primary"><i class="fa fa-briefcase margin-r-5"></i> Datos Laborales y
                                            Salariales</h4>
                                        <hr class="custom-hr">

                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Puesto (*):</label>
                                            <input type="text" class="form-control" name="puesto" id="puesto" maxlength="100"
                                                placeholder="Puesto en la empresa" required>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Fecha de Inicio Laboral (*):</label>
                                            <input type="date" class="form-control" name="fecha_inicio_laboral"
                                                id="fecha_inicio_laboral" required>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Salario Base (Q) (*):</label>
                                            <input type="number" step="0.01" class="form-control" name="salario" id="salario"
                                                placeholder="Ej: 3000.00" required>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Bonificación (Q):</label>
                                            <input type="number" step="0.01" class="form-control" name="bonificacion"
                                                id="bonificacion" value="250.00" placeholder="Bonificación (Ej: 250.00)">
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Bono Productividad (Q):</label>
                                            <input type="number" step="0.01" class="form-control" name="bono_productividad"
                                                id="bono_productividad" value="0.00" placeholder="Bono por productividad">
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Salario Extra (Q):</label>
                                            <input type="number" step="0.01" class="form-control" name="salario_extra"
                                                id="salario_extra" value="0.00" placeholder="Monto extra">
                                        </div>
                                    </div>

                                    <div class="row custom-form-section">
                                        <h4 class="text-primary"><i class="fa fa-percent margin-r-5"></i> Descuentos y
                                            Terminación</h4>
                                        <hr class="custom-hr">

                                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Descuento IGSS (Q):</label>
                                            <input type="number" step="0.01" class="form-control" name="igss" id="igss"
                                                placeholder="Cálculo IGSS">
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Descuento ISR (Q):</label>
                                            <input type="number" step="0.01" class="form-control" name="isr" id="isr"
                                                value="0.00" placeholder="Cálculo ISR">
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Préstamo (Q):</label>
                                            <input type="number" step="0.01" class="form-control" name="prestamo" id="prestamo"
                                                value="0.00" placeholder="Monto del préstamo">
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Otros Descuentos (Q):</label>
                                            <input type="number" step="0.01" class="form-control" name="otros_descuentos"
                                                id="otros_descuentos" value="0.00" placeholder="Otros descuentos">
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Anticipo Salarial (Q):</label>
                                            <input type="number" step="0.01" class="form-control" name="anticipo_salarial"
                                                id="anticipo_salarial" value="0.00" placeholder="Anticipo">
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label>Fecha de Fin Laboral:</label>
                                            <input type="date" class="form-control" name="fecha_fin_laboral"
                                                id="fecha_fin_laboral">
                                        </div>
                                    </div>


                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 text-center">
                                        <button class="btn btn-primary btn-lg" type="submit" id="btnGuardar"><i
                                                class="fa fa-save"></i> **Guardar**</button>
                                        <button class="btn btn-danger btn-lg" onclick="cancelarform()" type="button"><i
                                                class="fa fa-arrow-circle-left"></i> **Cancelar**</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/nomina_empleado.js"></script>
    <link rel="stylesheet" href="css/nomina_empleado.css">
    <script type="text/javascript" src="scripts/sweatlert.js"></script>

<?php
}
ob_end_flush();
?>