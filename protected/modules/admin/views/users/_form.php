<?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'form-login',
        'enableAjaxValidation' => true,
        'focus' => array($model, 'namalengkap'),
        'htmlOptions' => array(
            'class' => 'form-horizontal form-groups-bordered validate',
            'role' => 'form',
            //'name'=>'form-login',
        ),
    ));
?>
<div class="form-group">
    <?php echo CHtml::activeLabel($model, 'namalengkap',
        array('for' => 'field-1', 'class' => 'col-sm-3 control-label')); ?>
    <div class="col-sm-5">
        <?php echo CHtml::activeTextField($model, 'namalengkap', array(
            'class' => 'form-control',
            'name' => 'User[namalengkap]',
            'data-validate' => 'required',
            'data-message-required' => 'Wajib diisi'
        )); ?>
    </div>
</div>
<div class="form-group">
    <?php echo CHtml::activeLabel($model, 'email', array('for' => 'field-1', 'class' => 'col-sm-3 control-label')); ?>
    <div class="col-sm-5">
        <?php echo CHtml::activeEmailField($model, 'email', array(
            'class' => 'form-control',
            'name' => 'User[email]',
            'data-validate' => 'required',
            'data-message-required' => 'Wajib diisi'
        )); ?>
    </div>
</div>
<div class="form-group">
    <?php echo CHtml::activeLabel($model, 'username',
        array('for' => 'field-1', 'class' => 'col-sm-3 control-label')); ?>
    <div class="col-sm-5">
        <?php echo CHtml::activeTextField($model, 'username', array(
            'class' => 'form-control',
            'name' => 'User[username]',
            'data-validate' => 'required',
            'data-message-required' => 'Wajib diisi'
        )); ?>
    </div>
</div>
<div class="form-group">
    <?php echo CHtml::activeLabel($model, 'password',
        array('for' => 'field-1', 'class' => 'col-sm-3 control-label')); ?>
    <div class="col-sm-5">
        <?php echo CHtml::activePasswordField($model, 'password', array(
            'class' => 'form-control',
            'name' => 'User[password]',
            'data-validate' => 'required',
            'data-message-required' => 'Wajib diisi'
        )); ?>
    </div>
</div>
<div class="form-group">
    <label for="field-1" class="col-sm-3 control-label">Ulangi Kata Sandi</label>

    <div class="col-sm-5">
        <input type="password" class="form-control" name="User[repassword]" value="" data-validate="required"
               data-message-required="Wajib diisi">
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-5">
            <!-- <button type="submit" class="btn btn-success add-post">Simpan</button> -->
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-success add-post')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>
<?php
    // $js = Yii::app()->clientScript;
    // $js->registerScript('createUsers','
    //     $(document).ready(function(){
    //         $(".form-login").submit(function(){
    //             submitData();
    //         });
    //     });
    //     function submitData(){
    //         var form = $("#form-login").serialize();
    //         $.ajax({
    //             url : "/admin/users/create",
    //             data : {
    //                 ajax : "form-login",
    //                 user : form,
    //             },
    //             success : function(response){
    //                 if(response == "Sukses"){
    //                     noty.text("Sukses");
    //                 }else if(response == "Gagal")
    //                     console.log(response);
    //             }
    //         });
    //     }
    // ',CClientScript::POS_END);
?>
