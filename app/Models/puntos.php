<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="https://localhost/afainversiones/public/img/favicon.png">

    <title>Giros - AFAInversiones</title>
    <meta name="csrf-token" content="HLkgsg7QUeIUKcVGg6yVVHRbmemKbs4BkiR5ZAid">
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/plugins/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link href="https://localhost/afainversiones/public/color/assets/plugins/animate/animate.min.css" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/css/default/style.min.css" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/css/default/style-responsive.min.css" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/css/default/theme/default.css" rel="stylesheet" id="theme">
	<link href="https://localhost/afainversiones/public/color/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" id="theme">
	
	<link href="https://localhost/afainversiones/public/color/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" rel="stylesheet">
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="https://localhost/afainversiones/public/color/assets/plugins/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
	
	<link href="https://localhost/afainversiones/public/color/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet">
	<link href="https://localhost/afainversiones/public/color/assets/plugins/chosen/chosen.min.css" rel="stylesheet">
	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="https://localhost/afainversiones/public/color/assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	<style>
	    #content{
	         text-transform: uppercase;
	    }
		legend {
			font-size: 1rem;
			font-weight: 700;
		}
    	.dt-button{
    	    color: #2e353c;
            background: #f2f3f4;
            border-color: #f2f3f4;
            line-height: 16px;
            padding: 5px 10px;
            transition: all .1s ease-in-out;
            box-shadow: none!important;
    	}
		.loader {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			z-index: 99;
			background: 50% 50% no-repeat rgb(249,249,249);
		}
		.loader-container {
			height: 200px;
			
			top:0;
			bottom: 0;
			right: 0;

			margin: 270px;
			text-align: center;
		}
		.loader2 {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			z-index: 99;
			background: 50% 50% no-repeat rgb(249,249,249,0.4);
		}
		.loader-container2 {
			height: 200px;
			
			top:0;
			bottom: 0;
			right: 0;

			margin: 270px;
			text-align: center;
		}
		.glyphicon-refresh-animate {
			-animation: spin .7s infinite linear;
			-webkit-animation: spin2 .7s infinite linear;
		}

		@-webkit-keyframes spin2 {
			from { -webkit-transform: rotate(0deg);}
			to { -webkit-transform: rotate(360deg);}
		}

		@keyframes  spin {
			from { transform: scale(1) rotate(0deg);}
			to { transform: scale(1) rotate(360deg);}
		}
	</style>
<style type="text/css">/* Chart.js */
@-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}@keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}</style><style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style></head>



<body class="  pace-done"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="width: 100%;">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show d-none"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed show">
		<div id="loader" class="loader" style="display: none;">
			<div class="loader-container">
				<img class="img-responsive" src="https://localhost/afainversiones/public/img/cargando.gif" style="margin: 0 auto;">
			</div>
		</div>
		<div id="loader2" class="loader2" style="display:none;">
			<div class="loader-container2">
				<img class="img-responsive" src="https://localhost/afainversiones/public/img/cargando.gif" style="margin: 0 auto;">
			</div>
		</div>
		<!-- begin #header -->
		<div id="header" class="header navbar-default">
			<!-- begin navbar-header -->
			<div class="navbar-header">
				<a href="https://localhost/afainversiones/public" class="navbar-brand"><span class="navbar-logo"></span> <b>AFA</b> Giros</a>
				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- end navbar-header -->
			
			<!-- begin header-nav -->
			<ul class="navbar-nav navbar-right">
				<li class="dropdown navbar-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<img src="color/assets/img/user/user-13.jpg" alt=""> 
						<span class="d-none d-md-inline"> </span> <b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-message"> 
								<i class="demo-pli-male icon-lg icon-fw"></i> Cambiar Password
						</a>
						<a href="https://localhost/afainversiones/public/logout" class="dropdown-item">
								<i class="demo-pli-unlock icon-lg icon-fw"></i> Salir
						</a>
						</div>
				</li>
			</ul>
			<!-- end header navigation right -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;"><div data-scrollbar="true" data-height="100%" style="overflow: hidden; width: auto; height: 100%;" data-init="true">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile">
						<a href="javascript:;" data-toggle="nav-profile">
							<div class="cover with-shadow"></div>
							<div class="image">
								<img src="color/assets/img/user/user-13.jpg" alt="">
							</div>
							<div class="info">
								<b class="caret pull-right"></b>
								 
								<small>Administrador</small>
							</div>
						</a>
					</li>
					<li>
						<ul class="nav nav-profile">
							<li>
									<a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-message">
											<i class="demo-pli-male icon-lg icon-fw"></i> Cambiar Password
									</a>
							</li>
							<li>
									<a href="https://localhost/afainversiones/public/logout" class="dropdown-item">
											<i class="demo-pli-unlock icon-lg icon-fw"></i> Salir
									</a>
							</li>
						</ul>
					</li>
				</ul>
				<!-- end sidebar user -->
				<!-- begin sidebar nav -->
				<ul class="nav">
					<li class="nav-header">Navigation</li>
					<li class="has-sub active">
						<a href="https://localhost/afainversiones/public">
							<i class="fa fa-th-large"></i>
							<span>Inicio</span>
						</a>
					</li>
											<li class="has-sub">
							<a href="https://localhost/afainversiones/public/transacciones">
								<i class="fa fa-columns"></i> 	
								<span>Transacciones</span>
							</a>
						</li>
												<li class="has-sub">
							<a href="https://localhost/afainversiones/public/23/estado-cuenta">
								<b class="caret"></b>
								<i class="fa fa-home"></i> 
								<span>Estado de cuenta</span>
							</a>
						</li>
					

						<li class="has-sub">
						<a href="../app/models/puntos.php" class="button">GIROS AFA</a>
						     </li><li><a href="https://localhost/afainversiones/public">
								<b class="caret"></b>
								<i class="fab fa-autoprefixer"></i> 
								<span>Puntos GIROS AFA</span>
							</a>
						</li>
						

						<li class="has-sub">
							<a href="javascript:;">
								<b class="caret"></b>
								<i class="fa fa-comment-dots"></i> 
								<span>Soporte Tecnico</span>
							</a>
							<ul class="sub-menu">
								<li><a href="https://api.whatsapp.com/send?phone=573205624402" target="_blank">Whatsapp</a></li>
								<li><a href="https://www.facebook.com/GIROS-AFA-910681189277027/" target="_blank">Facebook info</a></li>
							
							</ul>
						</li>
										
					<!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
					<!-- end sidebar minify button -->
				</ul>
				<!-- end sidebar nav -->
			</div><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 877px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<!-- end page-header -->
			
			
    <div class="panel panel-inverse">

        <div class="panel-heading ui-sortable-handle">

            <div class="btn-group pull-right">

                <button type="button" class="btn btn-success btn-sm abrir-modal">Crear sucursal</button>

            </div>

            <h4 class="panel-title">Administrar Sucursales</h4>

        </div>

        <div class="panel-body">

            <div class="col-md-12 ui-sortable">

                    <div class="panel">

                        <!--===================================================-->

                        <div class="panel-body">

                            <div class="table-responsive">

                                <table class="table table-vcenter mar-top dataTable" id="data-table-default">

                                    <thead>

                                        <tr>

                                            <th>Caja</th>

                                            <th class="text-center" style="width: 5%;">Codigo</th>

                                            <th class="min-w-td">Nombre sucursal</th>

                                            <th class="min-w-td">DEPARTAMENTO </th>

                                            <th class="min-w-td">municipio </th>

                                            <th>Dirección</th>

                                            <th>Telefonos</th>

                                            <th>Email</th>

                                            <th>Cupo</th>

                                            <th></th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    
                                        
                                            
                                            <tr id="tr-21">

                                                <td>

                                                    
                                                        <span class="label label-warning">Sin Abrir</span>

                                                                                                        <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-cierre?m=MjE=" class="btn btn-primary btn-factura fa fa-print" title="Cierre de Caja"></a>	
                                                    <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-consolidado?m=MjE=" class="btn btn-success btn-factura fa fa-print" title="Cierre de Caja Detallado"></a>	
									
                                                </td>

                                                <td>

                                                    SUC-0021

                                                </td>

                                                <td>

                                                    Max plast

                                                </td>

                                                <td>

                                                    ocaña

                                                </td>

                                                <td>

                                                   

                                            </td>

                                                <td>

                                                    Calle 13 No 2-11

                                                </td>

                                                <td>

                                                    3115391577 - 3115391577

                                                </td>

                                                <td class="text-left">

                                                    breydyafa@hotmail.com

                                                </td>

                                                <td class="text-right">

                                                    $ 5,000,000

                                                </td>
                                                <td class="text-center">


                                                                                                            <a class="btn btn-sm btn-success btn-hover-success fa fa-check text-white bloquear-sucursal" data-bloqueo="1" data-codsucursal="21"></a>
                                                    

                                                </td>
                                                

                                                <td class="text-center mar-rgt" style="width: 5%;">

                                                    <div class="btn-group">

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-dollar-sign text-white" href="https://localhost/afainversiones/public/21/estado-cuenta"></a>

                                                        <a class="btn btn-sm btn-info btn-hover-success fa fa-clock text-white abrir-modal-horarios" data-codsucursal="21"></a>

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-users text-white abrir-modal-empleados" data-codsucursal="21"></a>

                                                        <a class="btn btn-sm btn-primary btn-hover-success fa fa-edit text-white abrir-modal" data-codsucursal="21"></a>

                                                        <a class="btn btn-sm btn-danger btn-hover-success fa fa-trash text-white add-tooltip eliminar-sucursal" data-codsucursal="21"></a>

                                                        <!-- <a class="btn btn-sm btn-primary btn-hover-success add-tooltip btn-evidencias ti ti-receipt" data-original-title="Registrar Consignación" data-toggle="modal" data-target="#modalFormArchivo" data-codasignatura="Max plast" data-container="body"></a> -->

                                                    </div>

                                                </td>

                                            </tr>

                                        
                                            
                                            <tr id="tr-23">

                                                <td>

                                                    
                                                        <span class="label label-warning">Sin Abrir</span>

                                                                                                        <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-cierre?m=MjM=" class="btn btn-primary btn-factura fa fa-print" title="Cierre de Caja"></a>	
                                                    <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-consolidado?m=MjM=" class="btn btn-success btn-factura fa fa-print" title="Cierre de Caja Detallado"></a>	
									
                                                </td>

                                                <td>

                                                    SUC-0023

                                                </td>

                                                <td>

                                                    repuestos daniel

                                                </td>

                                                <td>

                                                    barranca

                                                </td>

                                                <td>

                                                   santander

                                            </td>

                                                <td>

                                                    aven 61 no 12-54

                                                </td>

                                                <td>

                                                    30424744828 - 30424744828

                                                </td>

                                                <td class="text-left">

                                                    dbenavidezafanador@gmail.com

                                                </td>

                                                <td class="text-right">

                                                    $ 2,000,000

                                                </td>
                                                <td class="text-center">


                                                                                                            <a class="btn btn-sm btn-success btn-hover-success fa fa-check text-white bloquear-sucursal" data-bloqueo="1" data-codsucursal="23"></a>
                                                    

                                                </td>
                                                

                                                <td class="text-center mar-rgt" style="width: 5%;">

                                                    <div class="btn-group">

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-dollar-sign text-white" href="https://localhost/afainversiones/public/23/estado-cuenta"></a>

                                                        <a class="btn btn-sm btn-info btn-hover-success fa fa-clock text-white abrir-modal-horarios" data-codsucursal="23"></a>

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-users text-white abrir-modal-empleados" data-codsucursal="23"></a>

                                                        <a class="btn btn-sm btn-primary btn-hover-success fa fa-edit text-white abrir-modal" data-codsucursal="23"></a>

                                                        <a class="btn btn-sm btn-danger btn-hover-success fa fa-trash text-white add-tooltip eliminar-sucursal" data-codsucursal="23"></a>

                                                        <!-- <a class="btn btn-sm btn-primary btn-hover-success add-tooltip btn-evidencias ti ti-receipt" data-original-title="Registrar Consignación" data-toggle="modal" data-target="#modalFormArchivo" data-codasignatura="repuestos daniel" data-container="body"></a> -->

                                                    </div>

                                                </td>

                                            </tr>

                                        
                                            
                                            <tr id="tr-22">

                                                <td>

                                                    
                                                        <span class="label label-warning">Sin Abrir</span>

                                                                                                        <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-cierre?m=MjI=" class="btn btn-primary btn-factura fa fa-print" title="Cierre de Caja"></a>	
                                                    <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-consolidado?m=MjI=" class="btn btn-success btn-factura fa fa-print" title="Cierre de Caja Detallado"></a>	
									
                                                </td>

                                                <td>

                                                    SUC-0022

                                                </td>

                                                <td>

                                                    supermercado cineth

                                                </td>

                                                <td>

                                                    lebrija

                                                </td>

                                                <td>

                                                   santander

                                            </td>

                                                <td>

                                                    aven 20 No 8-2

                                                </td>

                                                <td>

                                                    3153764377 - 3153764377

                                                </td>

                                                <td class="text-left">

                                                    ingrith777@outlook.com

                                                </td>

                                                <td class="text-right">

                                                    $ 2,000,000

                                                </td>
                                                <td class="text-center">


                                                                                                            <a class="btn btn-sm btn-success btn-hover-success fa fa-check text-white bloquear-sucursal" data-bloqueo="1" data-codsucursal="22"></a>
                                                    

                                                </td>
                                                

                                                <td class="text-center mar-rgt" style="width: 5%;">

                                                    <div class="btn-group">

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-dollar-sign text-white" href="https://localhost/afainversiones/public/22/estado-cuenta"></a>

                                                        <a class="btn btn-sm btn-info btn-hover-success fa fa-clock text-white abrir-modal-horarios" data-codsucursal="22"></a>

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-users text-white abrir-modal-empleados" data-codsucursal="22"></a>

                                                        <a class="btn btn-sm btn-primary btn-hover-success fa fa-edit text-white abrir-modal" data-codsucursal="22"></a>

                                                        <a class="btn btn-sm btn-danger btn-hover-success fa fa-trash text-white add-tooltip eliminar-sucursal" data-codsucursal="22"></a>

                                                        <!-- <a class="btn btn-sm btn-primary btn-hover-success add-tooltip btn-evidencias ti ti-receipt" data-original-title="Registrar Consignación" data-toggle="modal" data-target="#modalFormArchivo" data-codasignatura="supermercado cineth" data-container="body"></a> -->

                                                    </div>

                                                </td>

                                            </tr>

                                        
                                            
                                            <tr id="tr-18">

                                                <td>

                                                    
                                                        <span class="label label-warning">Sin Abrir</span>

                                                                                                        <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-cierre?m=MTg=" class="btn btn-primary btn-factura fa fa-print" title="Cierre de Caja"></a>	
                                                    <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-consolidado?m=MTg=" class="btn btn-success btn-factura fa fa-print" title="Cierre de Caja Detallado"></a>	
									
                                                </td>

                                                <td>

                                                    SUC-0018

                                                </td>

                                                <td>

                                                    Taller el diviso

                                                </td>

                                                <td>

                                                    bucaramanga

                                                </td>

                                                <td>

                                                   santander

                                            </td>

                                                <td>

                                                    calle 13 No 23-77

                                                </td>

                                                <td>

                                                    3041159589 - 3041159589

                                                </td>

                                                <td class="text-left">

                                                    carlossivera93@gmail.com

                                                </td>

                                                <td class="text-right">

                                                    $ 3,000,000

                                                </td>
                                                <td class="text-center">


                                                                                                            <a class="btn btn-sm btn-success btn-hover-success fa fa-check text-white bloquear-sucursal" data-bloqueo="1" data-codsucursal="18"></a>
                                                    

                                                </td>
                                                

                                                <td class="text-center mar-rgt" style="width: 5%;">

                                                    <div class="btn-group">

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-dollar-sign text-white" href="https://localhost/afainversiones/public/18/estado-cuenta"></a>

                                                        <a class="btn btn-sm btn-info btn-hover-success fa fa-clock text-white abrir-modal-horarios" data-codsucursal="18"></a>

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-users text-white abrir-modal-empleados" data-codsucursal="18"></a>

                                                        <a class="btn btn-sm btn-primary btn-hover-success fa fa-edit text-white abrir-modal" data-codsucursal="18"></a>

                                                        <a class="btn btn-sm btn-danger btn-hover-success fa fa-trash text-white add-tooltip eliminar-sucursal" data-codsucursal="18"></a>

                                                        <!-- <a class="btn btn-sm btn-primary btn-hover-success add-tooltip btn-evidencias ti ti-receipt" data-original-title="Registrar Consignación" data-toggle="modal" data-target="#modalFormArchivo" data-codasignatura="Taller el diviso" data-container="body"></a> -->

                                                    </div>

                                                </td>

                                            </tr>

                                        
                                            
                                            <tr id="tr-19">

                                                <td>

                                                    
                                                        <span class="label label-warning">Sin Abrir</span>

                                                                                                        <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-cierre?m=MTk=" class="btn btn-primary btn-factura fa fa-print" title="Cierre de Caja"></a>	
                                                    <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-consolidado?m=MTk=" class="btn btn-success btn-factura fa fa-print" title="Cierre de Caja Detallado"></a>	
									
                                                </td>

                                                <td>

                                                    SUC-0019

                                                </td>

                                                <td>

                                                    Papelería laura

                                                </td>

                                                <td>

                                                    giron

                                                </td>

                                                <td>

                                                   ooooo

                                            </td>

                                                <td>

                                                    Calle 14 No 2-07

                                                </td>

                                                <td>

                                                    3053346257 - 3053346257

                                                </td>

                                                <td class="text-left">

                                                    lauracorredor991@gmail.com

                                                </td>

                                                <td class="text-right">

                                                    $ 2,000,000

                                                </td>
                                                <td class="text-center">


                                                                                                            <a class="btn btn-sm btn-success btn-hover-success fa fa-check text-white bloquear-sucursal" data-bloqueo="1" data-codsucursal="19"></a>
                                                    

                                                </td>
                                                

                                                <td class="text-center mar-rgt" style="width: 5%;">

                                                    <div class="btn-group">

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-dollar-sign text-white" href="https://localhost/afainversiones/public/19/estado-cuenta"></a>

                                                        <a class="btn btn-sm btn-info btn-hover-success fa fa-clock text-white abrir-modal-horarios" data-codsucursal="19"></a>

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-users text-white abrir-modal-empleados" data-codsucursal="19"></a>

                                                        <a class="btn btn-sm btn-primary btn-hover-success fa fa-edit text-white abrir-modal" data-codsucursal="19"></a>

                                                        <a class="btn btn-sm btn-danger btn-hover-success fa fa-trash text-white add-tooltip eliminar-sucursal" data-codsucursal="19"></a>

                                                        <!-- <a class="btn btn-sm btn-primary btn-hover-success add-tooltip btn-evidencias ti ti-receipt" data-original-title="Registrar Consignación" data-toggle="modal" data-target="#modalFormArchivo" data-codasignatura="Papelería laura" data-container="body"></a> -->

                                                    </div>

                                                </td>

                                            </tr>

                                        
                                            
                                            <tr id="tr-20">

                                                <td>

                                                    
                                                        <span class="label label-warning">Sin Abrir</span>

                                                                                                        <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-cierre?m=MjA=" class="btn btn-primary btn-factura fa fa-print" title="Cierre de Caja"></a>	
                                                    <a data-fancybox="" data-type="iframe" href="https://localhost/afainversiones/public/sucursal-consolidado?m=MjA=" class="btn btn-success btn-factura fa fa-print" title="Cierre de Caja Detallado"></a>	
									
                                                </td>

                                                <td>

                                                    SUC-0020

                                                </td>

                                                <td>

                                                    Jugueteria velandia

                                                </td>

                                                <td>

                                                    santander / piedecuesta

                                                </td>

                                                <td>

                                                   aaaaqgg

                                            </td>

                                                <td>

                                                    Cra 20 No 8-18

                                                </td>

                                                <td>

                                                    3158988599 - 3158988599

                                                </td>

                                                <td class="text-left">

                                                    velandia.12@hotmail.com

                                                </td>

                                                <td class="text-right">

                                                    $ 2,000,000

                                                </td>
                                                <td class="text-center">


                                                                                                            <a class="btn btn-sm btn-success btn-hover-success fa fa-check text-white bloquear-sucursal" data-bloqueo="1" data-codsucursal="20"></a>
                                                    

                                                </td>
                                                

                                                <td class="text-center mar-rgt" style="width: 5%;">

                                                    <div class="btn-group">

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-dollar-sign text-white" href="https://localhost/afainversiones/public/20/estado-cuenta"></a>

                                                        <a class="btn btn-sm btn-info btn-hover-success fa fa-clock text-white abrir-modal-horarios" data-codsucursal="20"></a>

                                                        <a class="btn btn-sm btn-success btn-hover-success fa fa-users text-white abrir-modal-empleados" data-codsucursal="20"></a>

                                                        <a class="btn btn-sm btn-primary btn-hover-success fa fa-edit text-white abrir-modal" data-codsucursal="20"></a>

                                                        <a class="btn btn-sm btn-danger btn-hover-success fa fa-trash text-white add-tooltip eliminar-sucursal" data-codsucursal="20"></a>

                                                        <!-- <a class="btn btn-sm btn-primary btn-hover-success add-tooltip btn-evidencias ti ti-receipt" data-original-title="Registrar Consignación" data-toggle="modal" data-target="#modalFormArchivo" data-codasignatura="Jugueteria velandia" data-container="body"></a> -->

                                                    </div>

                                                </td>

                                            </tr>

                                        
                                        

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

        </div>

    </div>

    <div id="demo-lg-modal" class="modal fade" tabindex="-1">

        <div class="modal-dialog modal-lg" id="form-modal">

            

        </div>

    </div>

			
		</div>
		<div class="modal modal-message fade" id="modal-message" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Cambiar Constraseña</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<div class="modal-body">
					<form method="POST" action="https://localhost/afainversiones/public/sucursal/vincular-empleado" accept-charset="UTF-8" class="validation-wizard wizard-circle" id="cambiarPassword" novalidate="novalidate"><input name="_token" type="hidden" value="HLkgsg7QUeIUKcVGg6yVVHRbmemKbs4BkiR5ZAid"> 
						<p>Introduce tu nueva contraseña</p>
						<input type="password" class="form-control" name="passwordNueva" id="passwordNueva" autocomplete="off">
						<input type="password" class="form-control" name="passwordNuevaConfirmar" autocomplete="off">
						<button class="btn btn-primary mt-4" type="submit">Cambiar Contraseña</button>
					</form>
					</div>
					<div class="modal-footer">
						<a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
					</div>
				</div>
			</div>
		</div>
		<!-- end #content -->
		
		<!-- end theme-panel -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="https://localhost/afainversiones/public/color/assets/plugins/jquery/jquery-3.3.1.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
	<!--[if lt IE 9]>
		<script src="color/assets/crossbrowserjs/html5shiv.js"></script>
		<script src="color/assets/crossbrowserjs/respond.min.js"></script>
		<script src="color/assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="https://localhost/afainversiones/public/color/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/js-cookie/js.cookie.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/js/theme/default.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/js/apps.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="https://localhost/afainversiones/public/color/assets/plugins/masked-input/masked-input.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/flot/jquery.flot.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/flot/jquery.flot.time.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/flot/jquery.flot.resize.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/flot/jquery.flot.pie.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/sparkline/jquery.sparkline.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/jquery-jvectormap/jquery-jvectormap.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/js/demo/dashboard.min.js"></script>

	<!--Sparkline [ OPTIONAL ]-->
	<script src="https://localhost/afainversiones/public/plugins/sparkline/jquery.sparkline.min.js"></script>
	<script src="https://localhost/afainversiones/public/plugins/fancybox/jquery.fancybox.min.js"></script>
	<script src="https://localhost/afainversiones/public/plugins/bootbox/bootbox.min.js"></script>

	<!--DataTables [ OPTIONAL ]-->
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/select2/dist/js/select2.min.js"></script>
	<script src="https://localhost/afainversiones/public/plugins/framework/framework.js"></script>

	
    <!-- jQuery validate JavaScript -->
    <script src="https://localhost/afainversiones/public/plugins/jquery-validate/jquery-validate.js"></script>
    <script src="https://localhost/afainversiones/public/plugins/jquery-validate/additional-methods.min.js"></script>
    <script src="https://localhost/afainversiones/public/plugins/jquery-validate/messages_es.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/chosen/chosen.jquery.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/bootstrap-daterangepicker/moment.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/chart-js/Chart.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
	<script src="https://localhost/afainversiones/public/color/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
	
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		 $.ajaxSetup({

headers: {

	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

}

});
	$("#cambiarPassword").validate({
		ignore: ":not(.chosen-select):checkbox",
		submitHandler: function(form) {
			$.ajax({
				url: "https://localhost/afainversiones/public/usuarios/cambiar-password",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'post',
				data: $("#cambiarPassword").serialize(),
				success: function(result){
					$.gritter.add({title:"Operación realizada con éxito",text:"La contraseña fue actualizada con éxito"});
					$('#modal-message').modal('hide');
					$('#modal-message').find('input').val('');
				}
			});
			return false;
		},
		rules: {
			passwordNueva: {
				required: true,
				minlength: 6,
			},
			passwordNuevaConfirmar: {
				equalTo: "#passwordNueva"
			},
		},
		highlight: function (element, errorClass) {
		$(element).parent().addClass('has-feedback has-error');
		$(element).parent().removeClass('has-feedback has-success');
		},
		unhighlight: function (element, errorClass) {
		$(element).parent().removeClass('has-feedback has-error');
		$(element).parent().addClass('has-feedback has-success');
		},
		errorPlacement: function(error, element) {
			if(element.hasClass("no-label")){
			} else if(element.parents('.input-group').length > 0) {
				error.insertAfter(element.parents('.input-group'));
			} else if(element.parents('.form-group').find('.chosen-container').length > 0){
			} else if(element.parents('.radio').find('.chosen-container').length > 0){
				error.insertAfter(element.parents('.radio').find('.chosen-container'));
			} else {
				error.insertAfter(element);
			}
		}
	});
		$(document).ready(function() {
			App.init();
			Dashboard.init();
		});
		$(window).on('load', function(){
			$(".loader").fadeOut(1000);
			$("#contenedor").fadeIn(2000);
		});
		handleDataTableDefault=function(){"use strict";0!==$("#data-table-default").length&&$("#data-table-default").DataTable({responsive:!0})},TableManageDefault=function(){"use strict";return{init:function(){handleDataTableDefault()}}}();
		TableManageDefault.init();
	</script>
	
<script>



$('.btn-jornada').change(function(){

    if($(this).prop('checked')){

        $(this).parents('.jornada-contenedor').find(".jornada-normal").hide();

        $(this).parents('.jornada-contenedor').find(".jornada-continua").show();

    }else{

        $(this).parents('.jornada-contenedor').find(".jornada-normal").show();

        $(this).parents('.jornada-contenedor').find(".jornada-continua").hide();

    }

});

$('.bloquear-sucursal').click(function(){
    var codsucursal = $(this).data("codsucursal");
    var bloqueo = $(this).data("bloqueo");
    bootbox.confirm({
        message: '<strong>Mensaje de confirmación:</strong> <br><br>¿Esta seguro de bloquear la sucursal?',
        closeButton: false,
    buttons: {
        confirm: {
           label: 'Aceptar',
            className: 'btn-success'
        },
        cancel: {
            label: 'Cancelar',
            className: 'btn-danger'
        }
    },
    callback: function (result) {
        if (result) {
            $.ajax({
                url: "https://localhost/afainversiones/public/sucursal/bloqueo",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                data: {
                    codsucursal: codsucursal,
                    bloqueo: bloqueo
                },
                success: function(result){
                    $.gritter.add({title:"Operación realizada con éxito",text:"La sucursal fue bloqueada con éxito"});
                   location.reload();
                }
            });
        }
    }
});
});

$('.abrir-modal').click(function(){

    var codsucursal = $(this).data("codsucursal");

    $.ajax({

        url: "https://localhost/afainversiones/public/sucursal/consultar",

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        },

        type: 'post',

        data: {

            codsucursal: codsucursal

        },

        success: function(result){

            console.log(0);

            $("#demo-lg-modal").modal("show");

            $('#form-modal').html(result.formulario);

        }

    });

});

$('.abrir-modal-empleados').click(function(){

    var codsucursal = $(this).data("codsucursal");

    $.ajax({

        url: "https://localhost/afainversiones/public/sucursal/consultar-empleados",

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        },

        type: 'post',

        data: {

            codsucursal: codsucursal

        },

        success: function(result){

            console.log(0);

            $("#demo-lg-modal").modal("show");

            $('#form-modal').html(result.formulario);

        }

    });

});

$('.abrir-modal-horarios').click(function(){

    var codsucursal = $(this).data("codsucursal");

    $.ajax({

        url: "https://localhost/afainversiones/public/sucursal/consultar-horarios",

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        },

        type: 'post',

        data: {

            codsucursal: codsucursal

        },

        success: function(result){

            console.log(0);

            $("#demo-lg-modal").modal("show");

            $('#form-modal').html(result.formulario);

        }

    });

});

$('.eliminar-sucursal').click(function(){

    var codsucursal = $(this).data("codsucursal");

    var trPadre = $("#tr-"+codsucursal);

    bootbox.confirm({

        message: '<strong>Mensaje de confirmación:</strong> <br><br>¿Esta seguro de eliminar al sucursal?',

        closeButton: false,

        buttons: {

            confirm: {

                label: 'Aceptar',

                className: 'btn-success'

            },

            cancel: {

                label: 'Cancelar',

                className: 'btn-danger'

            }

        },

        callback: function (result) {

            if (result) {

                $.ajax({

                    url: "https://localhost/afainversiones/public/sucursal/eliminar",

                    headers: {

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    },

                    type: 'post',

                    data: {

                        codsucursal: codsucursal

                    },

                    success: function(result){

                        $.gritter.add({title:"Operación realizada con éxito",text:"El registro fue eliminado con éxito"});

                        $('table').DataTable().destroy();

                        trPadre.remove();

                        $('table').DataTable({

                            "aaSorting": []

                        });

                    }

                });

            }

        }

    });

    

});

   





  

</script>

    



<!-- Mirrored from seantheme.com/color-admin-v4.2/admin/html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 04 Jan 2019 18:53:31 GMT -->

</body>