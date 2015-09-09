<?php $form=$this->beginWidget('UActiveForm', array(
    'id'=>'registration-form',
    'enableAjaxValidation'=>true,
    'disableAjaxValidationAttributes'=>array('RegistrationForm_verifyCode'),
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions' => array(
        'class' => 'form-horizontal form-groups-bordered validate',
        'role' => 'form',
        'enctype'=>'multipart/form-data'
    ),
)); ?>

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
        <!--                <button type="submit" class="btn btn-success">Simpan</button>-->
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-success add-post')); ?>
    </div>
</div>
<?php
$profileFields=$profile->getFields();
if ($profileFields) {
    foreach($profileFields as $field) {
        ?>
        <div class="form-group">
            <?php echo $form->labelEx($profile,$field->varname); ?>
            <?php
            if ($widgetEdit = $field->widgetEdit($profile)) {
                echo $widgetEdit;
            } elseif ($field->range) {
                echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
            } elseif ($field->field_type=="TEXT") {
                echo$form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
            } else {
                echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
            }
            ?>
            <?php echo $form->error($profile,$field->varname); ?>
        </div>
        <?php
    }
}
?>
<?php if (UserModule::doCaptcha('registration')): ?>
    <div class="row">
        <?php echo $form->labelEx($model,'verifyCode'); ?>

        <?php $this->widget('CCaptcha'); ?>
        <?php echo $form->textField($model,'verifyCode'); ?>
        <?php echo $form->error($model,'verifyCode'); ?>

        <p class="hint"><?php echo UserModule::t("Please enter the letters as they are shown in the image above."); ?>
            <br/><?php echo UserModule::t("Letters are not case-sensitive."); ?></p>
    </div>
<?php endif; ?>
<?php $this->endWidget(); ?>

