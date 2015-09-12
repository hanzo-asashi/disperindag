<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->renderPartial('/layouts/partials/meta') ;?>
</head>
<body>
<!-- page container -->
<div class="page-container">

    <!-- page header -->
    <?php echo $this->renderPartial('/layouts/partials/header') ;?>
    <!-- ./page header -->

    <!-- page content -->
    <?php echo $content ;?>
    <!-- ./page content -->

    <!-- page footer -->
    <?php echo $this->renderPartial('/layouts/partials/footer') ;?>
    <!-- ./page footer -->

</div>
<!-- ./page container -->
<?php $this->renderPartial('/modal/modal-kategori'); ?>
<!---->

<script type="text/javascript" src="<?php echo $this->baseUrl ?>/static/assets/js/vendor.js"></script>
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






