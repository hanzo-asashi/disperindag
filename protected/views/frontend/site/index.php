<div id="slide">
    <div class="container">
        <div class="slide-content">
            <div class="position">
                <h1>Selamat Datang</h1>
                <h2>di website resmi <b>Dinas Perindustrian dan Perdagangan</b></h2>
                <h3>Prov. Sulawesi Selatan</h3>
                <a href="about.php" class="link">Profil Dinas&nbsp;&nbsp;<i class="fa fa-angle-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div id="layanan">
    <div class="row">
        <div class="col-md-6 layanan-item">
            <div class="col-xs-offset-2 col-md-offset-4 col-md-8">
                <h1><span>01</span>Layanan</h1>
                <h3>Lorem ipsum dolor sit amet</h3>
                <ol>
                    <li>Ballroom Celebes Convention Center(CCC) Makassar</li>
                    <li>Pusat Distribusi Regional Makassar</li>
                    <li>Rumah Kemasan Makassar</li>
                    <li>Pasar Tradisional Modern di Pare-pare</li>
                    <li>Pusat Pameran Produk Dalam Negeri Makassar</li>
                </ol>
            </div>
        </div>

        <div class="col-md-6 layanan-item layanan-item-2">
            <div class="col-xs-offset-2 col-md-8">
                <h1><span>02</span>Ball Room CCC</h1>
                <h3>Lorem ipsum dolor sit amet</h3>
                <ol>
                    <li>Rapat</li>
                    <li>Pelatihan & Seminar</li>
                    <li>Pesta Pernikahan</li>
                    <li>Dan Lain-lain</li>
                </ol>
                <a href="contact.php">Hubungi Kami &nbsp;<i class="fa fa-long-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>


<div id="galeri">
    <div class="row">
        <div class="head-section">
            <h1><span><i class="fa fa-image"></i></span>Foto-foto Kegiatan</h1>
        </div>

        <div class="galeri-items">
<?php for ($i = 1; $i <= 8; $i++) { ?>
                <div class="col-sm-6 col-md-3 no-padding col">
                    <a href="gallery-item.php">
                        <img src="img/gallery/post-<?php echo $i ?>.jpg" alt="">
                    </a>
                </div>
<?php } ?>
        </div>

        <div class="clearfix"></div>

        <div class="col-md-12">
            <a href="gallery.php" class="more-center">Lihat Lainnya&nbsp;&nbsp;<i class="fa fa-long-arrow-right"></i></a>
        </div>
    </div>
</div>



<div id="news">
    <div class="row">
        <div class="head-section">
            <h1><span><i class="fa fa-newspaper-o"></i></span>Berita Terbaru</h1>
        </div>

        <div class="container">
            <div class="news-items">
<?php for ($i = 1; $i <= 4; $i++) { ?>
                    <div class="col-md-6 col item">
                        <div class="col-md-5">
                            <div class="img" style="background: url(img/gallery/post-<?php echo $i ?>.jpg)"></div>
                        </div>
                        <div class="col-md-7">
                            <a href="single-news.php">
                                <h1>Lorem ipsum dolor sit.</h1>
                            </a>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non esse sint deleniti omnis dolor optio sunt fugiat maxime totam qui. Non esse sint deleniti omnis dolor consectetur adipisicing elit.</p>
                            <a href="single-news.php">Selengkapnya&nbsp;&nbsp;<i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    </div>
<?php } ?>
            </div>
        </div>

        <div class="col-md-12">
            <a href="gallery.php" class="more-center">Tampilkan Lebih Banyak&nbsp;&nbsp;<i class="fa fa-long-arrow-right"></i></a>
        </div>
    </div>
</div>