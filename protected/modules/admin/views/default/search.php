<ol class="breadcrumb bc-3">
    <li>
        <a href="index.php">Beranda</a>
    </li>

    <li class="active">
        <strong>Pencarian</strong>
    </li>
</ol>


<section class="search-results-env">

    <div class="row">
        <div class="col-md-12">


            <!-- Search categories tabs -->
            <ul class="nav nav-tabs right-aligned">
                <li class="tab-title pull-left">
                    <div class="search-string">10 hasil ditemukan untuk: <strong>&ldquo;coba&rdquo;</strong></div>
                </li>

                <li class="active">
                    <a href="#blog">
                        Berita
                        <span class="disabled-text">(31)</span>
                    </a>
                </li>
                <li>
                    <a href="#pages">
                        Halaman
                        <span class="disabled-text">(2)</span>
                    </a>
                </li>
            </ul>

            <!-- Search search form -->
            <form method="get" class="search-bar" action="" enctype="application/x-www-form-urlencoded">

                <div class="input-group">
                    <input type="text" class="form-control input-lg" name="search" placeholder="Search for something...">

                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-lg btn-primary btn-icon">
                            Search 
                            <i class="entypo-search"></i>
                        </button>
                    </div>
                </div>

            </form>


            <!-- Search search form -->
            <div class="search-results-panes">

                <div class="search-results-pane active" id="blog">

                    <ul class="search-results">

                        <?php for ($i = 0; $i < 10; $i++) { ?>
                            <li class="search-result">
                                <div class="sr-inner">
                                    <h4>
                                        <a href="news-edit.php">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a>
                                    </h4>
                                    <p>Day handsome addition horrible sensible goodness two contempt. Evening for married his account removal. Estimable me disposing of be moonlight cordially curiosity. Delay rapid joy share allow age manor six. Went why far saw many knew. Exquisite excellent son gentleman acuteness her. Do is voice total power mr ye might round still.</p>
                                    <a href="news-edit.php" class="link">Baca Selengkapnya</a>
                                </div>
                            </li>
                        <?php } ?>

                    </ul>

                    <!-- Pager for search results -->					
                    <ul class="pager">
                        <li><a href="#"><i class="entypo-left-thin"></i> Sebelumnya</a></li>
                        <li><a href="#">Selanjutnya <i class="entypo-right-thin"></i></a></li>
                    </ul>
                </div>

                <div class="search-results-pane" id="pages">

                    <ul class="search-results">

                        <?php for ($i = 0; $i < 10; $i++) { ?>
                            <li class="search-result">
                                <div class="sr-inner">
                                    <h4>
                                        <a href="news-edit.php">Lorem ipsum dolor sit.</a>
                                    </h4>
                                    <p>Day handsome addition horrible sensible goodness two contempt. Evening for married his account removal. Estimable me disposing of be moonlight cordially curiosity. Delay rapid joy share allow age manor six. Went why far saw many knew. Exquisite excellent son gentleman acuteness her. Do is voice total power mr ye might round still.</p>
                                    <a href="news-edit.php" class="link">Baca Selengkapnya</a>
                                </div>
                            </li>
                        <?php } ?>

                    </ul>

                    <!-- Pager for search results -->					
                    <ul class="pager">
                        <li><a href="#"><i class="entypo-left-thin"></i> Sebelumnya</a></li>
                        <li><a href="#">Selanjutnya <i class="entypo-right-thin"></i></a></li>
                    </ul>
                </div>

            </div>

        </div>
    </div>

</section>