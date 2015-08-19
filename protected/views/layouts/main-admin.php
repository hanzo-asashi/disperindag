<!DOCTYPE html>
<html lang="en">
<head>
     <?php echo $this->renderPartial('/layouts/partials/meta') ;?>
</head>
<body class="page-body page-fade-only">
    <div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	        
        <!-- Header -->      
        <?php echo $this->renderPartial('/layouts/partials/header') ;?>    
        <!-- End Header -->        
        <!-- page content -->               
        <?php echo $content ;?>
            <!-- ./page content -->
        <!-- page footer -->
        <?php echo $this->renderPartial('/layouts/partials/footer') ;?>
        <!-- ./page footer -->
        </div>
    </div>
<!-- Bottom Scripts -->
<script src="<?php echo yii::app()->request->baseUrl ?>/static/assets/js/vendor.js"></script>
</body>
</html>






