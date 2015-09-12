<!-- logo -->
<div class="sidebar-menu">
    <header class="logo-env">
        <div class="logo">
            <a href="/admin">
                <img src="<?php echo $this->baseUrl ?>/static/assets/images/logo@2x.png" width="120" alt=""/>
            </a>
        </div>
        <div class="sidebar-collapse">
            <a href="#" class="sidebar-collapse-icon with-animation"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                <i class="entypo-menu"></i>
            </a>
        </div>
        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
        <div class="sidebar-mobile-menu visible-xs">
            <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                <i class="entypo-menu"></i>
            </a>
        </div>
        <!-- logo collapse icon -->
    </header>

    <div class="sidebar-collapse">
        <a href="#" class="sidebar-collapse-icon with-animation"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
            <i class="entypo-menu"></i>
        </a>
    </div>
    <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
    <div class="sidebar-mobile-menu visible-xs">
        <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
            <i class="entypo-menu"></i>
        </a>
    </div>
    <ul id="main-menu" class="">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
        <!-- Search Bar -->
        <li id="search">
            <form method="get" action="/search">
                <input type="text" name="q" class="search-input" placeholder="Apa yang Anda cari..."/>
                <button type="submit">
                    <i class="entypo-search"></i>
                </button>
            </form>
        </li>
        <li>
            <a href="/" target="_blank">
                <i class="entypo-monitor"></i>
                <span>Halaman Depan</span>
            </a>
        </li>
        <li class="active opened active">
            <a href="/admin">
                <i class="entypo-gauge"></i>
                <span>Beranda</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="entypo-pencil"></i>
                <span>Berita</span>
            </a>
            <ul>
                <li>
                    <a href="/admin/berita/index">
                        <i class="entypo-list"></i>
                        <span>Semua Berita</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/berita/create">
                        <i class="entypo-plus-squared"></i>
                        <span>Tambah Baru</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/berita/kategori">
                        <i class="entypo-tag"></i>
                        <span>Kategori</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="entypo-newspaper"></i>
                <span>Halaman</span>
            </a>
            <ul>
                <li>
                    <a href="/admin/halaman/index">
                        <i class="entypo-list"></i>
                        <span>Semua Halaman</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/halaman/tambah-halaman">
                        <i class="entypo-plus-squared"></i>
                        <span>Tambah Baru</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="entypo-mail"></i>
                <span>Pesan</span>
            </a>
            <ul>
                <li>
                    <a href="/admin/pesan/index">
                        <i class="entypo-inbox"></i>
                        <span>Kotak Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/pesan/pesan-baru">
                        <i class="entypo-plus-squared"></i>
                        <span>Tulis Pesan Baru</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="entypo-camera"></i>
                <span>Media</span>
            </a>
            <ul>
                <li>
                    <a href="/admin/media/index">
                        <i class="entypo-picture"></i>
                        <span>Semua Media</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/media/create">
                        <i class="entypo-plus-squared"></i>
                        <span>Tambah Baru</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="entypo-user"></i>
                <span>Pengguna</span>
            </a>
            <ul>
                <li>
                    <a href="/admin/user/index">
                        <i class="entypo-users"></i>
                        <span>Semua Pengguna</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/user/registration">
                        <i class="entypo-user-add"></i>
                        <span>Tambah Baru</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="/admin/setting">
                <i class="entypo-tools"></i>
                <span>Pengaturan</span>
            </a>
        </li>
    </ul>
</div>
<div class="main-content">
    <div class="row">

        <!-- Profile Info and Notifications -->
        <div class="col-sm-12 clearfix">

            <ul class="user-info pull-right">

                <!-- Profile Info -->
                <li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo $this->baseUrl ?>/static/assets/images/thumb-1@2x.png" alt=""
                             class="img-circle" width="44"/>
                        Gifa Eriyanto
                    </a>

                    <ul class="dropdown-menu">

                        <!-- Reverse Caret -->
                        <li class="caret"></li>

                        <!-- Profile sub-links -->
                        <li>
                            <a href="/admin/seting">
                                <i class="entypo-user"></i>
                                Pengaturan Akun
                            </a>
                        </li>

                        <li>
                            <a href="/admin/lockscreen">
                                <i class="entypo-key"></i>
                                Kunci Akun
                            </a>
                        </li>

                        <li>
                            <a href="/admin/user/logout">
                                <i class="entypo-logout"></i>
                                Keluar
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            <ul class="user-info pull-left pull-none-xsm">

                <!-- Raw Notifications -->
                <li class="notifications dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="entypo-bell"></i>
                        <span class="badge badge-info">6</span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="top">
                            <p class="small">
                                <a href="#" class="pull-right">Mark all Read</a>
                                You have <strong>3</strong> new notifications.
                            </p>
                        </li>

                        <li>
                            <ul class="dropdown-menu-list scroller">
                                <li class="unread notification-success">
                                    <a href="#">
                                        <i class="entypo-user-add pull-right"></i>

                                        <span class="line">
                                            <strong>New user registered</strong>
                                        </span>

                                        <span class="line small">
                                            30 seconds ago
                                        </span>
                                    </a>
                                </li>

                                <li class="unread notification-secondary">
                                    <a href="#">
                                        <i class="entypo-heart pull-right"></i>

                                        <span class="line">
                                            <strong>Someone special liked this</strong>
                                        </span>

                                        <span class="line small">
                                            2 minutes ago
                                        </span>
                                    </a>
                                </li>

                                <li class="notification-primary">
                                    <a href="#">
                                        <i class="entypo-user pull-right"></i>

                                        <span class="line">
                                            <strong>Privacy settings have been changed</strong>
                                        </span>

                                        <span class="line small">
                                            3 hours ago
                                        </span>
                                    </a>
                                </li>

                                <li class="notification-danger">
                                    <a href="#">
                                        <i class="entypo-cancel-circled pull-right"></i>

                                        <span class="line">
                                            John cancelled the event
                                        </span>

                                        <span class="line small">
                                            9 hours ago
                                        </span>
                                    </a>
                                </li>

                                <li class="notification-info">
                                    <a href="#">
                                        <i class="entypo-info pull-right"></i>

                                        <span class="line">
                                            The server is status is stable
                                        </span>

                                        <span class="line small">
                                            yesterday at 10:30am
                                        </span>
                                    </a>
                                </li>

                                <li class="notification-warning">
                                    <a href="#">
                                        <i class="entypo-rss pull-right"></i>

                                        <span class="line">
                                            New comments waiting approval
                                        </span>

                                        <span class="line small">
                                            last week
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="external">
                            <a href="#">View all notifications</a>
                        </li>
                    </ul>

                </li>

            </ul>

        </div>
    </div>

    <hr />
