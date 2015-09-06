<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META SECTION -->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- END META SECTION -->

    <link rel="stylesheet" type="text/css" href="<?php echo yii::app()->request->baseUrl ?>/static/css/revolution-slider/extralayers.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo yii::app()->request->baseUrl ?>/static/css/revolution-slider/settings.css" media="screen" />

    <link rel="stylesheet" type="text/css" href="<?php echo yii::app()->request->baseUrl ?>/static/css/styles.css" media="screen" />

</head>
<body>
<!-- page container -->
<div class="page-container">

    <!-- page header -->
    <?php echo $this->renderPartial('/backend/layouts/header') ;?>
    <!-- ./page header -->

    <!-- page content -->
    <?php echo $content ;?>
    <!-- ./page content -->

    <!-- page footer -->
    <?php echo $this->renderPartial('/backend/layouts/footer') ;?>
    <!-- ./page footer -->

</div>
<!-- ./page container -->

<!-- Modal 1 (Basic)-->
<div class='modal fade' id='modal-kategori'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <form action=''>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title'>Tambah Kategori</h4>
                </div>

                <div class='modal-body'>
                    <input type='text' class='form-control' placeholder='Tulis Kategori Baru disini'>
                </div>

                <div class='modal-footer no-margin'>
                    <button type='button' class='btn btn-default' data-dismiss='modal'>Kembali</button>
                    <button type='button' class='btn btn-success'>Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- page scripts -->
<script type="text/javascript" src="<?php echo yii::app()->request->baseUrl ?>/static/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo yii::app()->request->baseUrl ?>/static/js/plugins/bootstrap/bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo yii::app()->request->baseUrl ?>/static/js/plugins/mixitup/jquery.mixitup.js"></script>
<script type="text/javascript" src="<?php echo yii::app()->request->baseUrl ?>/static/js/plugins/appear/jquery.appear.js"></script>

<script type="text/javascript" src="<?php echo yii::app()->request->baseUrl ?>/static/js/plugins/revolution-slider/jquery.themepunch.tools.min.js"></script>
<script type="text/javascript" src="<?php echo yii::app()->request->baseUrl ?>/static/js/plugins/revolution-slider/jquery.themepunch.revolution.min.js"></script>

<script type="text/javascript" src="<?php echo yii::app()->request->baseUrl ?>/static/js/actions.js"></script>
<script type="text/javascript" src="<?php echo yii::app()->request->baseUrl ?>/static/js/slider.js"></script>
<!-- ./page scripts -->
<?php
$this->widget('ext.yii-noty.ENotificationWidget', array(
    'options' => array( // you can add js options here, see noty plugin page for available options
        'dismissQueue' => true,
        'layout' => 'topCenter',
        'theme' => 'relax',
        'animation' => array(
            'open' => 'animated flipInX',
            'close' => 'animated flipOutX',
            'easing' => 'swing',
            'speed ' => 500,
        ),
        'timeout' => 6000,
    ),
    'enableIcon' => true,
    'enableFontAwesomeCss' => true,
));
?>
</body>
</html>






