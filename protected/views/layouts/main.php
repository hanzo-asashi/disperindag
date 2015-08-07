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
    <?php echo $this->renderPartial('/layouts/header') ;?>
    <!-- ./page header -->

    <!-- page content -->
    <?php echo $content ;?>
    <!-- ./page content -->

    <!-- page footer -->
    <?php echo $this->renderPartial('/layouts/footer') ;?>
    <!-- ./page footer -->

</div>
<!-- ./page container -->

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
</body>
</html>






