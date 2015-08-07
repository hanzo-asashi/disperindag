<?php

	$title = "";

	$author = "";

	$description = "";

	$keyword = "";

?>

<!doctype html>

<html lang="en">

<head>

	<meta name="keyword" content="<?= $keyword ?>">

	<meta name="description" content="<?= $description ?>">

	<meta name="author" content="<?= $author ?>">

	<meta property="og:image" content="img/logo.png" />

	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?= $title ?></title>

	<link rel="icon" sizes="16x16" href="img/favicon.png" />

	<!-- Library js -->
	<script src="js/jquery-2.0.3.min.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">

	<!-- Font Icon -->
	<link rel="stylesheet" href="plugin/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="plugin/font-icons/font-awesome/css/font-awesome.min.css">

	<!-- Bootstrap css -->
	<link href="plugin/bootstrap-3.3.1/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Owl -->
	<link rel="stylesheet" href="plugin/owlcarousel/owl-carousel/owl.carousel.css">
	<link rel="stylesheet" href="plugin/owlcarousel/owl-carousel/owl.theme.css">

	<!-- Custom css -->
	<link href="css/font.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

</head>


<body>

	<header>
		<div class="container header-container">
			<div class="row">
				<div class="col-md-6">
					<div class="logo">
						<a href="">
							<img src="img/logo_disperindag.png" width="60px">
							<div class="logo-name">
								<h2>UPTD Balai Pelayanan Logistik Perdagangan</h2>
								<h1>Dinas Perindustrian dan Perdagangan</h1>
								<h3>Prov. Sulawesi Selatan</h3>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-6">
					<ul class="list-inline pull-right">
						<li><a href="tel:+6285212345678" class="btn-custom-info"><i class="fa fa-phone"></i>&nbsp;&nbsp;0852-1234-5678</a></li>
						<li><a href="mailto:service@bptp-disperindagsulsel.com" class="btn-custom-info"><i class="fa fa-envelope"></i>&nbsp;&nbsp;service@bptp-disperindagsulsel.com</a></li>
					</ul>
				</div>
			</div>
		</div>

		<nav class="navbar navbar-default navbar-custom">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<a href="tel:+6285212345678" class="view-mobile nav-mobile"><i class="fa fa-phone"></i></a>
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active"><a href="index.php">Beranda</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tentang Kami <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="about.php">Visi Misi</a></li>
								<li><a href="about.php">Sejarah Kota Makassar</a></li>
								<li><a href="about.php">Struktur Organisasi</a></li>
								<li><a href="about.php">Arti Lambang</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Layanan <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="service.php">Semua Layanan</a></li>
								<li><a href="service.php">Ball Room CCC</a></li>
							</ul>
						</li>
						<li><a href="news.php">Berita</a></li>
						<li><a href="contact.php">Hubungi Kami</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#" class="search-click"><i class="fa fa-search"></i><span class="view-mobile">&nbsp;&nbsp;Pencarian</span></a></li>
						<li><a href="#"><i class="fa fa-share-alt"></i><span class="view-mobile">&nbsp;&nbsp;Bagikan</span></a></li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container -->
		</nav>
	</header>