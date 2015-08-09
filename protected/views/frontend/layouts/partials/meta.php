<?php
$title = "";

$author = "";

$description = "";

$keyword = "";
?>
<meta name="keyword" content="<?= $keyword ?>">
<meta name="description" content="<?= $description ?>">
<meta name="author" content="<?= $author ?>">
<meta property="og:image" content="img/logo.png" />
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link rel="icon" sizes="16x16" href="<?php echo yii::app()->request->baseUrl ?>/static/img/favicon.png" />
<link rel="stylesheet" href="<?php echo yii::app()->request->baseUrl ?>/static/css/jquery-ui.css">
<!-- Font Icon -->
<link rel="stylesheet" href="<?php echo yii::app()->request->baseUrl ?>/static/plugin/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="<?php echo yii::app()->request->baseUrl ?>/static/plugin/font-icons/font-awesome/css/font-awesome.min.css">
<!-- Bootstrap css -->
<link href="<?php echo yii::app()->request->baseUrl ?>/static/plugin/bootstrap-3.3.1/css/bootstrap.min.css" rel="stylesheet">
<!-- Owl -->
<link rel="stylesheet" href="<?php echo yii::app()->request->baseUrl ?>/static/plugin/owlcarousel/owl-carousel/owl.carousel.css">
<link rel="stylesheet" href="<?php echo yii::app()->request->baseUrl ?>/static/plugin/owlcarousel/owl-carousel/owl.theme.css">
<!-- Custom css -->
<link href="<?php echo yii::app()->request->baseUrl ?>/static/css/font.css" rel="stylesheet">
<link href="<?php echo yii::app()->request->baseUrl ?>/static/css/style.css" rel="stylesheet">
