<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Disperindag Admin Panel"/>
    <meta name="author" content=""/>

    <title>Neon | Login</title>


    <link rel="stylesheet" href="<?php $this->baseUrl; ?>/static/assets/css/app.css">

    <!--[if lt IE 9]>
    <script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body class="page-body login-page login-form-fall">
<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">
    var baseurl = '/user/';
</script>

<div class="login-container">
    <div class="login-header login-caret">
        <div class="login-content">
            <a href="/" class="logo">
                <img src="<?php $this->baseUrl; ?>/static/assets/images/logo@2x.png" width="120" alt=""/>
            </a>

            <p class="description">Selamat datang, masuk untuk mengakses area admin!</p>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('loginMessage')): ?>
        <div class="alert alert-info">
            <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
        </div>
    <?php endif; ?>

    <div class="login-form">
        <div class="login-content">
	        <?php echo CHtml::beginForm(); ?>
            <div class="form-login-error">
                <?php echo CHtml::errorSummary($model); ?>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="entypo-user"></i>
                    </div>
                    <?php echo CHtml::activeTextField($model, 'username', array(
                        'class'        => 'form-control',
                        'placeholder'  => 'Nama Pengguna',
                        //'autocomplete' => 'off',
                    )); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="entypo-key"></i>
                    </div>
                    <?php echo CHtml::activePasswordField($model, 'password', array(
                        'class'        => 'form-control',
                        'placeholder'  => 'Kata Sandi',
                        //'autocomplete' => 'off',
                    )); ?>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-login">
                    <i class="entypo-login"></i>
                    <?php echo AdminModule::t("Login");?>
                </button>
            </div>
            <div class="checkbox">
                <?php echo CHtml::activeCheckBox($model, 'rememberMe'); ?>
                <?php echo CHtml::activeLabelEx($model, 'rememberMe'); ?>
            </div>
            <div class="login-bottom-links">
                <?php echo CHtml::link(AdminModule::t("Registrasi Pengguna"),
                    Yii::app()->getModule('admin')->registrationUrl, array('class' => 'link')); ?> |
                <?php echo CHtml::link(AdminModule::t("Lupa Kata Sandi?"), Yii::app()->getModule('admin')->recoveryUrl,
                    array('class' => 'link')); ?>
                <br/>
                &copy; 2015 <strong>Disperindag</strong>. Dev by <a href="http://media-sakti.com" target="_blank">Media
                    SAKTI</a>
            </div>
	        <?php echo CHtml::endForm(); ?>
        </div>
    </div>
</div>
<?php
    $form = new CForm(array(
        'elements' => array(
            'username'   => array(
                'type'      => 'text',
                'maxlength' => 32,
            ),
            'password'   => array(
                'type'      => 'password',
                'maxlength' => 32,
            ),
            'rememberMe' => array(
                'type' => 'checkbox',
            ),
        ),
        'buttons'  => array(
            'login' => array(
                'type'  => 'submit',
                'label' => 'Login',
            ),
        ),
    ), $model);
?>
<!-- Bottom Scripts -->
<script src="<?php $this->baseUrl; ?>/static/assets/js/vendor.js"></script>
</body>
</html>