<div id="head-page">
    <div class="container">
        <h1>Berita</h1>
    </div>
</div>

<div class="breadcrumb">
    <div class="container">
        <a href="/">Beranda</a>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <b>Berita</b>
    </div>
</div>

<div id="blog">
    <div class="container">
        <div class="row">				
            <div class="col-md-8">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <div class="row post">					
                        <h1 class="title-content"><a href="/berita/detail">Lorem ipsum dolor sit amet, consectetur adipisicing elit., consectetur adipisicing elit.</a></h1>
                        <span class="date-content">Diposting tanggal 19 Agustus 2015</span>

                        <div class="img-content" style="background: url(<?php $this->baseUrl; ?>/static/assets/img/gallery/post-<?php echo $i ?>.jpg);"></div>
                        <p> 
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, eum dolorem. Ratione totam minus dolores harum suscipit, temporibus veritatis ab hic omnis eum dolore adipisci, eos aperiam aliquam molestiae. At beatae explicabo voluptates exercitationem voluptatibus expedita eius perspiciatis esse, accusamus itaque nesciunt, aut similique consequuntur, non labore quia veritatis. Aperiam!
                        </p>

                        <div class="col-md-12">
                            <center><a href="/berita/detail" class="btn-more">Baca selengkapnya</a></center>
                        </div>
                    </div>
                <?php } ?>

            </div>

            <div class="col-md-4">
                <br>

                <ul class="list-unstyled link-accordion">
                    <li>
                        <h3>Kategori</h3>
                    </li>
                    <li><a href="/tags">Agama</a></li>
                    <li><a href="/tags" class="active">Bisnis</a></li>
                    <li><a href="/tags">Elektronik</a></li>
                    <li><a href="/tags">Web</a></li>
                </ul>

                <br>
                <br>

                <ul class="list-unstyled link-accordion">
                    <li>
                        <h3>Baca juga</h3>
                    </li>
                    <li>
                        <div class="img-small" style="background: url(<?php $this->baseUrl; ?>/static/assets/img/gallery/post-1.jpg);"></div>
                        <div class="ellipsis">
                            <a href="/berita/detail">Lorem ipsum dolor sit amet, consectetur adipisicing elit</a>
                        </div>
                    </li>
                    <li>
                        <div class="img-small" style="background: url(<?php $this->baseUrl; ?>/static/assets/img/gallery/post-2.jpg);"></div>
                        <div class="ellipsis">
                            <a href="/berita/detail">Ratione totam minus dolores harum suscipit</a>
                        </div>
                    </li>
                    <li>
                        <div class="img-small" style="background: url(<?php $this->baseUrl; ?>/static/assets/img/gallery/post-3.jpg);"></div>
                        <div class="ellipsis">
                            <a href="/berita/detail">Consectetur adipisicing elit</a>
                        </div>
                    </li>
                    <li>
                        <div class="img-small" style="background: url(<?php $this->baseUrl; ?>/static/assets/img/gallery/post-4.jpg);"></div>
                        <div class="ellipsis">
                            <a href="/berita/detail">Ratione totam minus dolores harum suscipit</a>
                        </div>
                    </li>
                    <li>
                        <div class="img-small" style="background: url(<?php $this->baseUrl; ?>/static/assets/img/gallery/post-5.jpg);"></div>
                        <div class="ellipsis">
                            <a href="/berita/detail">Consectetur adipisicing elit</a>
                        </div>
                    </li>
                    <li>
                        <div class="img-small" style="background: url(<?php $this->baseUrl; ?>/static/assets/img/gallery/post-6.jpg);"></div>
                        <div class="ellipsis">
                            <a href="/berita/detail">Lorem ipsum dolor sit amet, consectetur adipisicing elit</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>