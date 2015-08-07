<?php
	
	include "inc/header.php";

?>

	<div id="head-page">
		<div class="container">
			<h1>Hubungi Kami</h1>
		</div>
	</div>

	<div class="breadcrumb">
		<div class="container">
			<a href="index.php">Beranda</a>
			&nbsp;&nbsp;/&nbsp;&nbsp;
			<b>Hubungi Kami</b>
		</div>
	</div>

	<div id="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-12 title">
					<h1>Butuh Bantuan?</h1>
					<p>Kami akan melayani Anda secepat mungkin!</p>
				</div>
				
				<div class="col-md-8">
					<ul class="list-inline no-padding">
						<li><a href="tel:+6285212345678" class="btn-custom-info"><i class="fa fa-phone"></i>&nbsp;&nbsp;0852-1234-5678</a></li>
						<li><a href="mailto:service@bptp-disperindagsulsel.com" class="btn-custom-info"><i class="fa fa-envelope"></i>&nbsp;&nbsp;service@bptp-disperindagsulsel.com</a></li>
					</ul>

					<br>

					<div class="row">
						<form action="" class="form-custom">
							<ul class="list-unstyled no-padding">
								<li class="col-md-4">
									<label for="">Nama Lengkap</label>
									<input type="text">
								</li>
								<li class="col-md-4">
									<label for="">Email</label>
									<input type="text">
								</li>
								<li class="col-md-4">
									<label for="">Subjek</label>
									<input type="text">
								</li>
								<li class="col-md-12">
									<label for="">Pesan Anda</label>
									<textarea name="" id="" rows="10"></textarea>
								</li>
								<li class="col-md-4">
									<button>Kirim</button>
								</li>
							</ul>
						</form>
					</div>
				</div>

				<div class="col-md-4">
					<ul class="list-unstyled link-accordion">
						<li><a href="about.php">Visi Misi</a></li>
						<li><a href="about.php" class="active">Sejarah Kota Makassar</a></li>
						<li><a href="about.php">Struktur Organisasi</a></li>
						<li><a href="about.php">Arti Lambang</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

<?php

	include "inc/footer.php";

?>