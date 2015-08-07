<?php
	
	include "inc/header.php";

?>

	<div id="head-page">
		<div class="container">
			<h1>Layanan</h1>
		</div>
	</div>

	<div class="breadcrumb">
		<div class="container">
			<a href="index.php">Beranda</a>
			&nbsp;&nbsp;/&nbsp;&nbsp;
			<a href="service.php">Layanan</a>
			&nbsp;&nbsp;/&nbsp;&nbsp;
			<b>Ball Room CCC</b>
		</div>
	</div>

	<div id="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-12 title">
					<h1>Ball Room CCC Makassar</h1>
				</div>
				
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table">
							<tr class="thead">
								<th class="title">Room</th>
								<th>Coctail Party</th>
								<th>Theatre</th>
								<th>Class</th>
							</tr>

							<tr>
								<td><b>Ball Room Lt.1</b></td>
								<td>1500 orang</td>
								<td>1350 orang</td>
								<td>500 orang</td>
							</tr>

							<tr>
								<td><b>Ball Room Lt.2</b></td>
								<td>1500 orang</td>
								<td>900 orang</td>
								<td>500 orang</td>
							</tr>

							<tr>
								<td><b>Meeting Room VIP</b></td>
								<td>-</td>
								<td>50 orang</td>
								<td>30 orang</td>
							</tr>

							<tr>
								<td><b>Meeting Room</b></td>
								<td>-</td>
								<td>50 orang</td>
								<td>30 orang</td>
							</tr>
						</table>
					</div>

					<p>
						<b>Dapat difungsikan untuk:</b>
						<br>
						Rapat, Pelatihan, Seminar, Pesta Perkawinan, dll
					</p>

					<h5>
						<b>Fasilitas Ruang Meeting:</b> (Minum untuk 250 orang)
					</h5>
					
					<ul>
						<li>Sound System</li>
						<li>Mic Wireless</li>
						<li>Buku Catatan</li>
						<li>Pulpen</li>
						<li>Air Mineral</li>
						<li>Permen</li>
					</ul>

					<h5>
						<b>Alternatif Harga:</b>
					</h5>
					
					<ol>
						<li>
							Harga Paket Meeting (Perda No.1 Th. 2012)
							<ul>
								<li>Meeting Room VIP : Rp. 450.000/Hari</li>
								<li>Meeting Room : Rp. 350.000/Hari</li>
							</ul>
						</li>

						<li>
							Harga Ruang Meeting (Perda No.1 Th. 2012)
							<ul>
								<li>Ball Room Lt. 1 : Rp. 4.000.000/Hari</li>
								<li>Ball Room Lt. 2 : Rp. 5.000.000/Hari</li>
							</ul>
						</li>
					</ol>
				</div>

				<div class="col-md-4">
					<div id="owl-demo" class="owl-carousel">
						<?php for ($i=1; $i <= 8; $i++) { ?>
						<div class="item">
							<div class="slide-item" style="background: url(img/layanan/post-<?php echo $i ?>.jpg) no-repeat center; background-size: cover">
								<div class="slide-content">Ball Room Lt. 1</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php

	include "inc/footer.php";

?>