<ol class="breadcrumb bc-3">
    <li>
        <a href="/admin">Beranda</a>
    </li>

    <li class="active">
        <strong>Berita</strong>
    </li>
</ol>

<h2>Berita</h2>
<br />
<?php 
    echo CHtml::beginForm('','POST',array(
        'role'=>'form',
        'class'=>'form-horizontal form-groups-bordered validate'
    ));
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <?php 
                $this->widget('zii.widgets.grid.CGridView',array(
                    'dataProvider' => $model->search(),
                    'filter'=>$model,                    
                ));
            ?>
        </div>
    </div>
</div>
<div class="form-group default-padding">
    <button type="submit" class="btn btn-success">Save Changes</button>
    <button type="reset" class="btn">Reset Previous</button>
</div>
<?php echo CHtml::endForm();?>