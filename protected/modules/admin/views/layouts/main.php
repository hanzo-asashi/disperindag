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






