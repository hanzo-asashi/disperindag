<!DOCTYPE html>
<html lang="en">
<head>
     <?php echo $this->renderPartial('/layouts/partials/meta') ;?>
</head>
<body>
    <!-- Header -->
    <header>    
        <?php echo $this->renderPartial('/layouts/partials/header') ;?>
    </header>
    <!-- End Header -->
    <!-- page content -->
    <?php echo $content ;?>
    <!-- ./page content -->

    <!-- page footer -->
    <?php echo $this->renderPartial('/layouts/partials/footer') ;?>
    <!-- ./page footer -->

<!-- page scripts -->
<script src="<?php echo yii::app()->request->baseUrl ?>/static/js/jquery-2.0.3.min.js"></script>
<script src="<?php echo yii::app()->request->baseUrl ?>/static/js/jquery-ui.js"></script>
<!-- Bootstrap -->
<script src="<?php echo yii::app()->request->baseUrl ?>/static/plugin/bootstrap-3.3.1/js/bootstrap.min.js"></script>

<!-- HTML5 & CSS3 Support Browser -->
<script src="<?php echo yii::app()->request->baseUrl ?>/static/plugin/modernizr/modernizr.js"></script>

<!-- Owl Caraosel -->
<script src="<?php echo yii::app()->request->baseUrl ?>/static/plugin/owlcarousel/owl-carousel/owl.carousel.js"></script>
<script>
    $(document).ready(function ($) {
        $("#owl-demo").owlCarousel({
            items: 1
        });
    });
</script>

<script src="<?php echo yii::app()->request->baseUrl ?>/static/js/custom.js"></script>
<!-- ./page scripts -->
</body>
</html>






