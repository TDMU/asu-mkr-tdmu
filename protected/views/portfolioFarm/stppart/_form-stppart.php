<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 12:42
 */


/**
 * @var PortfolioFarmController $this
 * @var Stppart $model
 */

/**
 * @var $form TbActiveForm
 */

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array());
echo CHtml::errorSummary($model);
echo $form->dropDownListRow($model, 'stppart3', $model->getStppart3Types());
echo $form->textFieldRow($model, 'stppart4');
echo $form->dropDownListRow($model, 'stppart5', Stportfolio::getYears());
echo $form->dropDownListRow($model, 'stppart6', $model->getStppart6Types());
echo $form->dropDownListRow($model, 'stppart7', $model->getStppart7Types());
echo $form->textField($model, 'stppart14', array(
        'style' => $model->stppart7 != 3 ? 'display:none' : ''
));
echo $form->dropDownListRow($model, 'stppart8', $model->getStppart8Types());
echo $form->textField($model, 'stppart15', array(
    'style' => $model->stppart8 != 3 ? 'display:none' : ''
));
Yii::app()->clientScript->registerScript('stppart14-stppart15', <<<JS
    $('#Stppart_stppart7').change(function() {
        var val = $(this).val();
        if(val==3)
            $('#Stppart_stppart14').show();
        else
            $('#Stppart_stppart14').hide();
    })
    $('#Stppart_stppart8').change(function() {
        var val = $(this).val();
        if(val==3)
            $('#Stppart_stppart15').show();
        else
            $('#Stppart_stppart15').hide();
    })
JS
);
?>
<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? tt('Добавить') : tt('Изменить'), array(
		        'class' => 'btn btn-small btn-success'
        )); ?>
</div>
<?php
$this->endWidget();