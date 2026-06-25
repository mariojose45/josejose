<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php'; // ✅ esto controla el inicio y expiración de sesión

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
?>
<!--Contenido-->
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">Seleccionar Sucursal</h1>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Sucursal:</label>
                                <select name="sucursal_activa" id="sucursal_activa" class="form-control selectpicker" data-live-search="true" required>
                                    <?php
                                    require_once "../modelos/Sucursal.php";
                                    $sucursal = new Sucursal();
                                    $rspta = $sucursal->listar();
                                    while ($reg = $rspta->fetch_object()) {
                                        if (in_array($reg->idsucursal, $_SESSION['sucursales_asignadas'])) {
                                            echo '<option value="' . $reg->idsucursal . '">' . $reg->nombre . ' - ' . $reg->direccion . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" onclick="cambiar_sucursal()">Seleccionar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!--Fin-Contenido-->
<?php
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/seleccionar_sucursal.js"></script> 