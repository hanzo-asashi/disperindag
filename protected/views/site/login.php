<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Disperindag Admin Panel" />
        <meta name="author" content="" />

        <title>Neon | Login</title>


        <link rel="stylesheet" href="<?php $this->baseUrl; ?>/static/assets/css/app.css">

        <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->


    </head>
    <body class="page-body login-page login-form-fall">
        <!-- This is needed when you send requests via Ajax -->
        <script type="text/javascript">
            var baseurl = '/admin/';
        </script>

        <div class="login-container">
            <div class="login-header login-caret">
                <div class="login-content">
                    <a href="/" class="logo">
                        <img src="<?php $this->baseUrl; ?>/static/assets/images/logo@2x.png" width="120" alt="" />
                    </a>
                    <p class="description">Selamat datang, masuk untuk mengakses area admin!</p>
                    <!-- progress bar indicator -->
                    <div class="login-progressbar-indicator">
                        <h3>43%</h3>
                        <span>Proses...</span>
                    </div>
                </div>
            </div>

            <div class="login-progressbar">
                <div></div>
            </div>

            <div class="login-form">

                <div class="login-content">

                    <div class="form-login-error">
                        <h3>Gagal Masuk</h3>
                        <p>Masukkan Kata Sandi yang benar.</p>
                    </div>

                    <form method="post" role="form" action="/site/login">
                
                        <div class="form-group">
                            
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="entypo-user"></i>
                                </div>
                                
                                <input type="text" class="form-control" name="User[username]" id="username" placeholder="Nama Pengguna" autocomplete="off" />
                            </div>
                            
                        </div>
                        
                        <div class="form-group">
                            
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="entypo-key"></i>
                                </div>
                                
                                <input type="password" class="form-control" name="User[password]" id="password" placeholder="Kata Sandi" autocomplete="off" />
                            </div>
                        
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-login">
                                <i class="entypo-login"></i>
                                Masuk
                            </button>
                        </div>          
                    </form>


                    <div class="login-bottom-links">

                        <a href="/admin/users/forgot" class="link">Lupa Kata Sandi?</a>

                        <br />

                        &copy; 2015 <strong>Disperindag</strong>. Dev by <a href="http://media-sakti.com" target="_blank">Media SAKTI</a>

                    </div>

                </div>

            </div>

        </div>

        <!-- Bottom Scripts -->
        <script src="<?php $this->baseUrl; ?>/static/assets/js/vendor.js"></script>

    </body>
</html>