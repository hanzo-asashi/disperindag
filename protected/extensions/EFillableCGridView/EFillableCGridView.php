<?php
Yii::import('zii.widgets.grid.CGridView');

/**
 * EFillableCGridview Class File
 *
 * EFillableCGridview provides an extension to CGridView, allowing to
 * add datas from an "admin" view, using ajax query.
 *
 * To use this widget, you may insert the following code in a view:
 *
 * <pre>$this->widget('application.extensions.EFillableCGridView', array(
 *    'id'=>'my-grid',
 *    'dataProvider'=>$model->search(),
 *    // Here starts the addition
 *    'fillable' => array(
 *        'columns' => array(
 *            array(
 *                'name' => 'MyCol1',
 *                'value' => CActiveForm::textField($model,'MyCol1')
 *            ),
 *            array(
 *                'name' => 'MyCol2',
 *                'value' => CActiveForm::textField($model,'MyCol2')
 *            ),
 *            array(
 *                'name' => 'MyCol3',
 *                'value' => CActiveForm::dropDownList($model, 'MyCol3', CHtml::listData(Col3::model()->findAll(), 'idCol3', 'nameCol3')),
 *            ),
 *        ),
 *        'CButtonColumn' => array(
 *            'button' => '<a href="'.Yii::app()->createUrl('control/createAjax').'"><img style="vertical-align: middle;" src="'. Yii::app()->baseUrl .'/images/ico_plus.gif" alt="plus"></a>',
 *            'action' => Yii::app()->createUrl('control/createAjax'),
 *        ),
 *        'position' => 'bottom',
 *    ),
 *    // Here ends the addition
 *    'filter'=>$model,
 *    'columns'=>array(
 *        'ColId',
 *        'MyCol1',
 *        'MyCol2',
 *        array(
 *            'name' => 'MyCol3',
 *            'value' => '$data->Col3->nameCol3',
 *        ),
 *        array(
 *            'class'=>'CButtonColumn',
 *        ),
 *    ),
 * ));
 * </pre>
 */
class EFillableCGridView extends CGridView {

    /**
     * @var array The initial parameters
     */
    public $fillable=array();

    /**
     * @var array Simple array colname => html content
     */
    private $filcols = array();

    /**
     * @var string The ajax action
     */
    public $fillAction;

    /**
     * @var string The html button
     */
    public $fillButton;

    /**
     * Initialisation of the widget
     */
    public function init() {
        if (count($this->fillable)>0)
        {
            if (!isset($this->fillable['position']))
            {
                $this->fillable['position'] = 'bottom';
            }
            if (!isset($this->fillable['CButtonColumn']))
            {
                $this->fillAction = $this->owner;
                $this->filButton = '<a href="'.$this->fillAction.'">'.Yii::t('main', 'Add').'</a>';
            }
            else
            {
                $this->fillAction = $this->fillable['CButtonColumn']['action'];
                $this->fillButton = $this->fillable['CButtonColumn']['button'];
            }
            foreach ($this->fillable['columns'] as $k => $col)
            {
                $this->filcols[$col['name']] = $col['value'];
            }

        }
        parent::init();

    }

    /**
     * Renders the form after the header
     * used when position = top
     */
    public function renderTableHeader()
    {
        parent::renderTableHeader();
        if (count($this->fillable)>0 && $this->fillable['position'] == 'top')
        {
           $this->renderFillable();
        }
    }

    /**
     * Renders the form after the tbody
     * used by default
     */
    public function renderTableBody()
    {
        parent::renderTableBody();
        if (count($this->fillable)>0 && $this->fillable['position'] == 'bottom')
        {
            $this->renderFillable();
        }
    }

    /**
     * Renders the new line
     */
    public function renderFillable()
    {
        $this->_renderScript();
        echo '<tr id="update-', $this->id, '">';
        foreach($this->columns as $column) {

            if (is_a($column, 'CDataColumn') && isset($this->filcols[$column->name]))
            {
                echo '<td>', $this->filcols[$column->name], '</td>';
            }
            elseif(is_a($column, 'CButtonColumn') && isset($this->fillButton))
            {
                echo '<td>', $this->fillButton, '</td>';
                //echo '<td>', CHtml::ajaxSubmitButton('add', $this->fillAction, array('update', $this->id)), '</td>';
            }
            else
            {
                echo '<td></td>';
            }
        }
        echo '</tr>', PHP_EOL;
    }

    /**
     * Register the ajax script
     */
    private function _renderScript()
    {
        Yii::app()->clientScript->registerScript("fillable", "
        jQuery(document).on('click', '#update-".$this->id." td a', function() {
            var th = this,
            ajaxFill = function() {};
            form = $('#update-".$this->id."').find(':input')
            data = $(form).serialize();
            $.ajax({
                type: 'POST',
                url: '".$this->fillAction."',
                data:data,
                dataType:'html',
                success: function(data) {
                    jQuery('#".$this->id."').yiiGridView('update');
                    ajaxFill(th, true, data);
                },
                error: function(XHR) {
                    return ajaxFill(th, false, XHR);
                }
             });
             return false;
        });");
    }

}