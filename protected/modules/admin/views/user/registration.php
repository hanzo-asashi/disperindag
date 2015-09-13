<?php $this->pageTitle = Yii::app()->name . ' - ' . AdminModule::t("Registration"); ?>
<ol class="breadcrumb bc-3">
    <li>
        <a href="/admin">Beranda</a>
    </li>

    <li class="active">
<!--        <strong>Tambah Pengguna</strong>-->
        <strong>
            <?= AdminModule::t("Registration"); ?>
        </strong>
    </li>
</ol>

<h2><?php echo AdminModule::t("Registration"); ?></h2>
<br />
<?php if(Yii::app()->user->hasFlash('registration')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('registration'); ?>
    </div>
<?php else: ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
                <?php echo $this->renderPartial('/user/_form', array('model'=>$model,'profile'=>$profile)); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php
Yii::app()->clientScript->registerScript ('test','
    $(document).ready(function(){
        $(".nav-user").addClass("opened");
        $(".nav-user ul li:nth-child(2)").addClass("active");
    });
',CClientScript::POS_END)
?>