<?php
ob_start();
require_once __DIR__ . '/../config/SessionControl.php';
?>

<!DOCTYPE html>  
<html> 
<head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SOL</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- CSS dependencies -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/datatables/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

    <style>
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .content-wrapper {
            flex: 1;
        }

        .iconos-tama {
            width: 25px;
        }

        .iconos-cambio {
            width: 40px;
        }
    </style>
</head>
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <a href="index2.html" class="logo">
            <span class="logo-mini"><b></b></span>
            <span class="logo-lg"><b></b></span>
        </a>
        
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                                <p>www.compusisgt.com - Software<small>..</small></p>
                            </li>
                            <li class="user-footer"> 
                                <div class="pull-right">
                                    <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Cerrar</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Content Wrapper -->
    <div class="content-wrapper" style="height: 400px;"> 
        <div class="lockscreen-wrapper">
            <div class="lockscreen-logo">
                <a href="#"><b>Seleccione Sucursal para realizar sus operaciones</b></a>
            </div>

            <div class="lockscreen-name"><?php echo $_SESSION['nombre']; ?></div>
            <br>

            <div class="lockscreen-item">
                <div class="lockscreen-image">
                    <img src="../reportes/logo.jpg" alt="User Image">
                </div>

                <form class="lockscreen-credentials">
                    <div class="input-group">
                        <select id="idsucursalseleccion" name="idsucursalseleccion" class="form-control selectpicker" data-live-search="true" required></select>
                        <div class="input-group-btn">
                            <br><br><br><br>
                        </div>
                    </div>
                </form>

                <br><br>
                <button type="button" class="btn btn-primary btn-block" id="btnGuardar"><i class="fa fa-save"></i> Ingresar</button>
            </div>
        </div>
    </div>
    <!-- End of Content Wrapper --> 

    <!-- Footer -->
    <?php require 'footer.php'; ?>

</div>

<script type="text/javascript" src="scripts/seleccionaagencia.js"></script>
</body>
</html>