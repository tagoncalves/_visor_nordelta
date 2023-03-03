<!DOCTYPE html>
<html lang="es">
	<head>
		<!-- META -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="msapplication-TileColor" content="#00ba8b">
		<meta name="msapplication-TileImage" content="<?= base_url('assets/icons/ms-icon-144x144.png') ?>">
		<meta name="theme-color" content="#00ba8b">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		
		<!-- LINK -->
		<link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('assets/icons/apple-icon-57x57.png') ?>">
		<link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('assets/icons/apple-icon-60x60.png') ?>">
		<link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('assets/icons/apple-icon-72x72.png') ?>">
		<link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/icons/apple-icon-76x76.png') ?>">
		<link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('assets/icons/apple-icon-114x114.png') ?>">
		<link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('assets/icons/apple-icon-120x120.png') ?>">
		<link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('assets/icons/apple-icon-144x144.png') ?>">
		<link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets/icons/apple-icon-152x152.png') ?>">
		<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/icons/apple-icon-180x180.png') ?>">
		<link rel="icon" type="image/png" sizes="192x192"  href="<?= base_url('assets/icons/android-icon-192x192.png') ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/icons/favicon-32x32.png') ?>">
		<link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/icons/favicon-96x96.png') ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/icons/favicon-16x16.png') ?>">
		<link rel="manifest" href="<?= base_url('assets/icons/manifest.json') ?>">
		
		<title>Visualizador NDLT</title>
	
		<link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/css/bootstrap-responsive.min.css')?>" rel="stylesheet">
		<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">-->
		<link href="<?=base_url('assets/css/font-awesome.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/css/fullcalendar.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/css/datepicker3.css')?>" rel="stylesheet">
		
		
		<link href="<?=base_url('assets/css/sweetalert.css') ?>" rel="stylesheet">
		<link href="<?=base_url('assets/css/jquery.postitall.css') ?>" rel="stylesheet">
		<link href="<?=base_url('assets/css/pages/dashboard.css')?>" rel="stylesheet">	
		<?php if ($page == 'login') { echo '<link href="'.base_url('assets/css/pages/signin.css').'" rel="stylesheet">'; } ?>
		<link href="<?=base_url('assets/css/adm_style.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/css/style.css')?>" rel="stylesheet">
		
	</head>
<body class="sitio">
	<header>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<?php if ($this->session->userdata('login') != null) : ?>
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="<?= base_url().'agenda/consultaHc' ?>">Visualizador de Historia Cl&iacute;nica Nordelta</a>

					
						<div class="nav-collapse">
							<ul class="nav pull-right">					
								<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>&nbsp; <?=$this->session->userdata('servicio')?> | Dr. <?=$this->session->userdata('username') ?> <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="<?= base_url("/user/cambiarPassword") ?>">Cambiar Contraseña</a></li>
										<li><a href="<?= base_url("/user/logout") ?>">Salir</a></li>
									</ul>
								</li>
							</ul>
						</div> <!--/.nav-collapse --> 
					<?php else: ?>
						<a class="brand" href="<?= base_url() ?>">Visualizador de Historia Cl&iacute;nica</a>
					<?php endif; ?>	
				</div> <!-- /container --> 
			</div> <!-- /navbar-inner --> 
		</div> <!-- /navbar -->
		
	</header>
	